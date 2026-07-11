<?php
namespace App\Services;

use App\Config\Database;
use PDO;

class DeviceWorkflowService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * تحديد الفني المناسب بناءً على العطل والمرحلة الحالية
     */
    public function determineNextTechnician(int $deviceId): ?array
    {
        // جلب بيانات الجهاز
        $stmt = $this->db->prepare("
            SELECT d.*, ds.slug as status_slug 
            FROM devices d
            JOIN device_statuses ds ON d.current_status_id = ds.id
            WHERE d.id = ?
        ");
        $stmt->execute([$deviceId]);
        $device = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$device) {
            return null;
        }

        $currentStatus = $device['status_slug'];
        $diagnosedIssue = $device['diagnosed_issue'] ?? '';

        // 1. حالة "تم الاستلام" => نرسل للفحص (فني سوفت وير)
        if ($currentStatus === 'received') {
            $technician = $this->findAvailableTechnician('software');
            if ($technician) {
                return [
                    'technician_id' => $technician['id'],
                    'technician_type_id' => $this->getTechnicianTypeId('software'),
                    'step' => 'initial_inspection',
                    'technician_name' => $technician['full_name']
                ];
            }
        }

        // 2. حالة "جاري الفحص" => حسب نوع العطل
        if ($currentStatus === 'inspection') {
            $issue = strtolower($diagnosedIssue);
            
            $motherboardKeywords = ['ic', 'بوردة', 'معالج', 'شحن', 'power', 'ماس', 'short'];
            $softwareKeywords = ['سوفت', 'برمجي', 'تحديث', 'system', 'ريست', 'أندرويد'];

            foreach ($motherboardKeywords as $keyword) {
                if (strpos($issue, $keyword) !== false) {
                    $technician = $this->findAvailableTechnician('motherboard');
                    if ($technician) {
                        return [
                            'technician_id' => $technician['id'],
                            'technician_type_id' => $this->getTechnicianTypeId('motherboard'),
                            'step' => 'motherboard_repair',
                            'technician_name' => $technician['full_name']
                        ];
                    }
                }
            }

            foreach ($softwareKeywords as $keyword) {
                if (strpos($issue, $keyword) !== false) {
                    $technician = $this->findAvailableTechnician('software');
                    if ($technician) {
                        return [
                            'technician_id' => $technician['id'],
                            'technician_type_id' => $this->getTechnicianTypeId('software'),
                            'step' => 'software_repair',
                            'technician_name' => $technician['full_name']
                        ];
                    }
                }
            }

            // افتراضياً: نرسل لفني بوردة
            $technician = $this->findAvailableTechnician('motherboard');
            if ($technician) {
                return [
                    'technician_id' => $technician['id'],
                    'technician_type_id' => $this->getTechnicianTypeId('motherboard'),
                    'step' => 'general_repair',
                    'technician_name' => $technician['full_name']
                ];
            }
        }

        // 3. بعد الإصلاح => نرسل للفك والتقفيل
        if ($currentStatus === 'repairing') {
            $technician = $this->findAvailableTechnician('disassembly');
            if ($technician) {
                return [
                    'technician_id' => $technician['id'],
                    'technician_type_id' => $this->getTechnicianTypeId('disassembly'),
                    'step' => 'assembly_quality',
                    'technician_name' => $technician['full_name']
                ];
            }
        }

        // ✅ إذا لم تنطبق أي حالة، نرجع null (يجب أن يكون هذا في نهاية الدالة)
        return null;
    }

    /**
     * تنفيذ التحويل التلقائي (بدون معاملة داخلية)
     */
    public function autoTransferDevice(int $deviceId, int $transferredByUserId): bool
    {
        try {
            // تحديد الفني التالي
            $next = $this->determineNextTechnician($deviceId);
            if (!$next) {
                return true; // لا حاجة للتحويل
            }

            // جلب بيانات الجهاز
            $stmt = $this->db->prepare("SELECT * FROM devices WHERE id = ?");
            $stmt->execute([$deviceId]);
            $device = $stmt->fetch(PDO::FETCH_ASSOC);
            $fromTechnicianId = $device['assigned_technician_id'] ?? null;
            $fromTechnicianTypeId = null;

            if ($fromTechnicianId) {
                $stmt = $this->db->prepare("SELECT technician_type_id FROM technician_specialties WHERE user_id = ?");
                $stmt->execute([$fromTechnicianId]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $fromTechnicianTypeId = $row['technician_type_id'] ?? null;
            }

            // تسجيل التحويل
            $stmt = $this->db->prepare("
                INSERT INTO technician_transfers 
                (device_id, from_technician_id, to_technician_id, from_technician_type_id, 
                 to_technician_type_id, transfer_reason, transfer_notes, transferred_by, is_auto_transfer, transferred_at) 
                VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                $deviceId,
                $fromTechnicianId,
                $next['technician_id'],
                $fromTechnicianTypeId,
                $next['technician_type_id'],
                'تحويل آلي بناءً على سير العمل',
                "المرحلة: {$next['step']}",
                $transferredByUserId,
                1
            ]);

            // تحديث الحالة حسب المرحلة
            $statusMap = [
                'initial_inspection' => 'inspection',
                'motherboard_repair' => 'repairing',
                'software_repair' => 'repairing',
                'general_repair' => 'repairing',
                'assembly_quality' => 'repaired'
            ];
            $newStatusSlug = $statusMap[$next['step']] ?? 'repairing';
            $statusId = $this->getStatusIdBySlug($newStatusSlug);

            // تحديث الجهاز
            $stmt = $this->db->prepare("
                UPDATE devices 
                SET assigned_technician_id = ?, current_status_id = ?, updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$next['technician_id'], $statusId, $deviceId]);

            // إنشاء مهمة إصلاح جديدة
            $stmt = $this->db->prepare("
                INSERT INTO repair_jobs 
                (device_id, technician_id, technician_type_id, job_description, started_at, is_completed, created_at) 
                VALUES 
                (?, ?, ?, ?, NOW(), 0, NOW())
            ");
            $jobDescription = "مهمة: {$next['step']} - جهاز {$device['device_code']}";
            $stmt->execute([$deviceId, $next['technician_id'], $next['technician_type_id'], $jobDescription]);

            return true;

        } catch (\Exception $e) {
            error_log('Auto Transfer Error: ' . $e->getMessage());
            throw $e; // نرمي الاستثناء لكي تتعامل معه المعاملة الخارجية
        }
    }

    /**
     * البحث عن فني متاح
     */
    private function findAvailableTechnician(string $typeSlug): ?array
    {
        $typeId = $this->getTechnicianTypeId($typeSlug);
        if (!$typeId) return null;

        $stmt = $this->db->prepare("
            SELECT u.id, u.full_name, u.username 
            FROM users u
            JOIN technician_specialties ts ON u.id = ts.user_id
            WHERE u.role = 'technician' 
              AND u.is_active = 1
              AND ts.technician_type_id = ?
            LIMIT 1
        ");
        $stmt->execute([$typeId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * الحصول على معرف نوع الفني
     */
    private function getTechnicianTypeId(string $slug): ?int
    {
        $stmt = $this->db->prepare("SELECT id FROM technician_types WHERE slug = ?");
        $stmt->execute([$slug]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['id'] : null;
    }

    /**
     * الحصول على معرف الحالة من الـ slug
     */
    private function getStatusIdBySlug(string $slug): int
    {
        $stmt = $this->db->prepare("SELECT id FROM device_statuses WHERE slug = ?");
        $stmt->execute([$slug]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['id'] : 1;
    }

    /**
     * دالة اختبار بسيطة
     */
    public function testWorkflow(int $deviceId): array
    {
        $result = $this->determineNextTechnician($deviceId);
        if ($result) {
            return [
                'status' => 'success',
                'message' => "الجهاز هيتحول للفني: {$result['technician_name']}",
                'data' => $result
            ];
        } else {
            return [
                'status' => 'info',
                'message' => 'الجهاز في المرحلة النهائية أو مفيش فني متاح',
                'data' => null
            ];
        }
    }
}