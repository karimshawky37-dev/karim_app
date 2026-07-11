<?php
namespace App\Controllers;

class TechnicianController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function dashboard()
    {
        $technicianId = $this->userId;

        $stmt = $this->db->prepare("
            SELECT d.*, 
                   c.full_name as customer_name, 
                   c.phone as customer_phone,
                   ds.name as status_name, 
                   ds.slug as status_slug
            FROM devices d
            LEFT JOIN customers c ON d.customer_id = c.id
            LEFT JOIN device_statuses ds ON d.current_status_id = ds.id
            WHERE d.assigned_technician_id = ? AND d.deleted_at IS NULL
            ORDER BY d.id DESC
        ");
        $stmt->execute([$technicianId]);
        $devices = $stmt->fetchAll();

        $this->view('technician/dashboard', [
            'title' => 'لوحة تحكم الفني',
            'devices' => $devices,
            'technician' => ['full_name' => $this->userName]
        ]);
    }

    public function startInspection()
    {
        $deviceId = (int) $_POST['device_id'];
        $technicianId = $this->userId;

        $stmt = $this->db->prepare("SELECT assigned_technician_id FROM devices WHERE id = ?");
        $stmt->execute([$deviceId]);
        $device = $stmt->fetch();
        if (!$device || $device['assigned_technician_id'] != $technicianId) {
            $this->redirect('/technician-dashboard', '⚠️ هذا الجهاز ليس مسنداً لك', 'error');
            return;
        }

        $statusId = $this->getStatusIdBySlug('inspection');
        $stmt = $this->db->prepare("UPDATE devices SET current_status_id = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$statusId, $deviceId]);

        $stmt = $this->db->prepare("
            INSERT INTO device_maintenance_log (device_id, action, description, performed_by, performed_at)
            VALUES (?, 'start_inspection', 'بدء فحص الجهاز', ?, NOW())
        ");
        $stmt->execute([$deviceId, $technicianId]);

        $this->redirect('/technician-dashboard', '✅ بدء الفحص', 'success');
    }

    public function requestPart()
    {
        $deviceId = (int) $_POST['device_id'];
        $partName = trim($_POST['part_name']);
        $technicianId = $this->userId;

        if (empty($partName)) {
            $this->redirect('/technician-dashboard', '⚠️ يجب كتابة اسم القطعة', 'error');
            return;
        }

        $stmt = $this->db->prepare("SELECT assigned_technician_id FROM devices WHERE id = ?");
        $stmt->execute([$deviceId]);
        $device = $stmt->fetch();
        if (!$device || $device['assigned_technician_id'] != $technicianId) {
            $this->redirect('/technician-dashboard', '⚠️ هذا الجهاز ليس مسنداً لك', 'error');
            return;
        }

        // ✅ البحث عن القطعة في المخزون
        $stmt = $this->db->prepare("
            SELECT id, name, current_quantity 
            FROM inventory 
            WHERE name LIKE ? AND current_quantity > 0 AND deleted_at IS NULL
            LIMIT 1
        ");
        $stmt->execute(['%' . $partName . '%']);
        $part = $stmt->fetch();

        if ($part) {
            // ✅ القطعة موجودة → نغير الحالة لـ repairing
            $statusId = $this->getStatusIdBySlug('repairing');
            $stmt = $this->db->prepare("UPDATE devices SET current_status_id = ?, waiting_for_part = NULL, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$statusId, $deviceId]);

            $stmt = $this->db->prepare("
                INSERT INTO device_maintenance_log (device_id, action, description, performed_by, performed_at)
                VALUES (?, 'part_available', CONCAT('قطعة غيار متوفرة: ', ?), ?, NOW())
            ");
            $stmt->execute([$deviceId, $part['name'], $technicianId]);

            $this->sendNotification(
                $technicianId,
                'inventory',
                '🔧 قطعة غيار متوفرة',
                "القطعة '{$part['name']}' موجودة في المخزون (الكمية: {$part['current_quantity']}). تم تغيير حالة الجهاز إلى جاري الإصلاح.",
                "/devices/{$deviceId}"
            );

            $this->redirect('/technician-dashboard', '✅ القطعة موجودة! تم تغيير الحالة إلى "جاري الإصلاح"', 'success');
        } else {
            // ❌ القطعة غير موجودة → نروح لـ suspended
            $statusId = $this->getStatusIdBySlug('suspended');
            $stmt = $this->db->prepare("UPDATE devices SET current_status_id = ?, waiting_for_part = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$statusId, $partName, $deviceId]);

            $stmt = $this->db->prepare("
                INSERT INTO device_maintenance_log (device_id, action, description, performed_by, performed_at)
                VALUES (?, 'request_part', CONCAT('طلب قطعة غيار: ', ?), ?, NOW())
            ");
            $stmt->execute([$deviceId, $partName, $technicianId]);

            $this->sendNotification(
                1,
                'inventory',
                '⏳ طلب قطعة غيار',
                "الفني {$this->userName} طلب قطعة '$partName' للجهاز رقم {$deviceId}",
                "/devices/{$deviceId}"
            );

            $this->redirect('/technician-dashboard', '⏳ القطعة غير موجودة، تم تعليق الجهاز بانتظار وصولها', 'warning');
        }
    }

    public function startRepair()
    {
        $deviceId = (int) $_POST['device_id'];
        $technicianId = $this->userId;

        $stmt = $this->db->prepare("SELECT assigned_technician_id FROM devices WHERE id = ?");
        $stmt->execute([$deviceId]);
        $device = $stmt->fetch();
        if (!$device || $device['assigned_technician_id'] != $technicianId) {
            $this->redirect('/technician-dashboard', '⚠️ هذا الجهاز ليس مسنداً لك', 'error');
            return;
        }

        $statusId = $this->getStatusIdBySlug('repairing');
        $stmt = $this->db->prepare("UPDATE devices SET current_status_id = ?, waiting_for_part = NULL, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$statusId, $deviceId]);

        $stmt = $this->db->prepare("
            INSERT INTO device_maintenance_log (device_id, action, description, performed_by, performed_at)
            VALUES (?, 'start_repair', 'استئناف الإصلاح', ?, NOW())
        ");
        $stmt->execute([$deviceId, $technicianId]);

        $this->redirect('/technician-dashboard', '✅ استئناف الإصلاح', 'success');
    }

    // ============================================================
    // ✅ completeRepair - بدون repair_jobs
    // ============================================================
    public function completeRepair()
    {
        $deviceId = (int) $_POST['device_id'];
        $technicianId = $this->userId;

        $stmt = $this->db->prepare("SELECT assigned_technician_id FROM devices WHERE id = ?");
        $stmt->execute([$deviceId]);
        $device = $stmt->fetch();
        if (!$device || $device['assigned_technician_id'] != $technicianId) {
            $this->redirect('/technician-dashboard', '⚠️ هذا الجهاز ليس مسنداً لك', 'error');
            return;
        }

        $statusId = $this->getStatusIdBySlug('ready');
        $stmt = $this->db->prepare("UPDATE devices SET current_status_id = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$statusId, $deviceId]);

        // ✅ سجل صيانة (بدون repair_jobs)
        $stmt = $this->db->prepare("
            INSERT INTO device_maintenance_log (device_id, action, description, performed_by, performed_at)
            VALUES (?, 'completed', 'تم إصلاح الجهاز', ?, NOW())
        ");
        $stmt->execute([$deviceId, $technicianId]);

        // إشعار للمدير
        $this->sendNotification(
            1,
            'repair',
            '✅ جهاز تم إصلاحه',
            "تم إصلاح الجهاز رقم {$deviceId} بواسطة الفني {$this->userName}",
            "/devices/{$deviceId}"
        );

        $this->redirect('/technician-dashboard', '✅ تم إصلاح الجهاز', 'success');
    }

    public function diagnose()
    {
        $deviceId = (int) $_POST['device_id'];
        $diagnosis = trim($_POST['diagnosis']);
        $technicianId = $this->userId;

        $stmt = $this->db->prepare("SELECT assigned_technician_id FROM devices WHERE id = ?");
        $stmt->execute([$deviceId]);
        $device = $stmt->fetch();
        if (!$device || $device['assigned_technician_id'] != $technicianId) {
            $this->redirect('/technician-dashboard', '⚠️ هذا الجهاز ليس مسنداً لك', 'error');
            return;
        }

        if (empty($diagnosis)) {
            $this->redirect("/devices/{$deviceId}", '⚠️ التشخيص لا يمكن أن يكون فارغاً', 'error');
            return;
        }

        $stmt = $this->db->prepare("UPDATE devices SET diagnosed_issue = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$diagnosis, $deviceId]);

        $stmt = $this->db->prepare("
            INSERT INTO device_maintenance_log (device_id, action, description, performed_by, performed_at)
            VALUES (?, 'diagnose', CONCAT('تشخيص: ', ?), ?, NOW())
        ");
        $stmt->execute([$deviceId, $diagnosis, $technicianId]);

        $this->redirect("/devices/{$deviceId}", '✅ تم حفظ التشخيص', 'success');
    }

    public function checklist($deviceId)
    {
        $technicianId = $this->userId;
        $stmt = $this->db->prepare("SELECT * FROM devices WHERE id = ? AND assigned_technician_id = ?");
        $stmt->execute([$deviceId, $technicianId]);
        $device = $stmt->fetch();

        if (!$device) {
            $this->redirect('/technician-dashboard', 'الجهاز غير مسند لك', 'error');
            return;
        }

        $this->view('technician/checklist', [
            'title' => 'فحص بعد الإصلاح',
            'device' => $device,
            'mode' => 'after'
        ]);
    }

    public function saveAfterRepairChecklist()
    {
        $deviceId = (int) $_POST['device_id'];
        $checklist = $_POST['checklist'] ?? [];
        $technicianId = $this->userId;

        try {
            $stmt = $this->db->prepare("
                INSERT INTO device_checklist 
                (device_id, wifi_working, bluetooth_working, network_working, camera_working, 
                 fingerprint_working, audio_working, charging_working, screen_condition, 
                 check_notes, checked_by, checked_at, check_type, repair_notes)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'after_repair', ?)
            ");
            $stmt->execute([
                $deviceId,
                isset($checklist['wifi']) ? 1 : 0,
                isset($checklist['bluetooth']) ? 1 : 0,
                isset($checklist['network']) ? 1 : 0,
                isset($checklist['camera']) ? 1 : 0,
                isset($checklist['fingerprint']) ? 1 : 0,
                isset($checklist['audio']) ? 1 : 0,
                isset($checklist['charging']) ? 1 : 0,
                $checklist['screen'] ?? 'good',
                $checklist['notes'] ?? '',
                $technicianId,
                $checklist['repair_notes'] ?? ''
            ]);

            $this->audit->logCreate('device_checklist', $this->db->lastInsertId(), [
                'device_id' => $deviceId,
                'type' => 'after_repair',
                'technician_id' => $technicianId
            ]);

            $this->redirect("/devices/{$deviceId}", '✅ تم حفظ الفحص', 'success');
        } catch (\Exception $e) {
            $this->redirect("/technician/checklist/{$deviceId}", 'خطأ: ' . $e->getMessage(), 'error');
        }
    }

    public function forceUpdateStatus()
    {
        $deviceId = (int) $_POST['device_id'];
        $technicianId = $this->userId;

        $stmt = $this->db->prepare("SELECT assigned_technician_id, waiting_for_part FROM devices WHERE id = ?");
        $stmt->execute([$deviceId]);
        $device = $stmt->fetch();
        if (!$device || $device['assigned_technician_id'] != $technicianId) {
            $this->redirect('/technician-dashboard', '⚠️ غير مسند لك', 'error');
            return;
        }

        $statusId = $this->getStatusIdBySlug('repairing');
        $stmt = $this->db->prepare("UPDATE devices SET current_status_id = ?, waiting_for_part = NULL, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$statusId, $deviceId]);

        $this->redirect('/technician-dashboard', '✅ تم تحديث الحالة إلى جاري الإصلاح', 'success');
    }

    private function getStatusIdBySlug($slug)
    {
        $stmt = $this->db->prepare("SELECT id FROM device_statuses WHERE slug = ?");
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        return $row ? (int) $row['id'] : 1;
    }
}