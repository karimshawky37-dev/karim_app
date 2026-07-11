<?php
namespace App\Controllers;

use App\Config\Database;
use App\Services\AuditService;

class ShiftController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    // ▶️ بدء وردية جديدة
    public function start()
    {
        $userId = $this->userId;
        $userName = $this->userName;

        // إنهاء أي وردية نشطة للمستخدم
        $stmt = $this->db->prepare("UPDATE shifts SET status = 'closed', end_time = NOW() WHERE user_id = ? AND status = 'active'");
        $stmt->execute([$userId]);

        // إنشاء وردية جديدة
        $stmt = $this->db->prepare("INSERT INTO shifts (user_id, user_name, start_time, status) VALUES (?, ?, NOW(), 'active')");
        $stmt->execute([$userId, $userName]);

        $this->audit->log('shift_start', 'shifts', $this->db->lastInsertId(), null, ['user' => $userName]);
        $this->redirect('/', 'تم بدء الوردية بنجاح', 'success');
    }

    // ⏹️ إنهاء الوردية (مع الأرشفة الكاملة)
    public function end()
    {
        $userId = $this->userId;

        $stmt = $this->db->prepare("SELECT id FROM shifts WHERE user_id = ? AND status = 'active'");
        $stmt->execute([$userId]);
        $shift = $stmt->fetch();

        if ($shift) {
            $shiftId = $shift['id'];

            // 1. أرشفة سجلات التدقيق
            $this->archiveShiftLogs($shiftId);

            // 2. أرشفة سجلات الحضور
            $this->archiveAttendance($shiftId);

            // 3. تحديث الوردية إلى closed
            $stmt = $this->db->prepare("UPDATE shifts SET status = 'closed', end_time = NOW() WHERE id = ?");
            $stmt->execute([$shiftId]);

            // 4. أرشفة الوردية نفسها
            $stmt = $this->db->prepare("
                INSERT INTO shifts_archive (shift_id, user_id, user_name, start_time, end_time, total_actions, archived_at)
                SELECT id, user_id, user_name, start_time, NOW(), total_actions, NOW()
                FROM shifts WHERE id = ?
            ");
            $stmt->execute([$shiftId]);

            $this->audit->log('shift_end', 'shifts', $shiftId, null, ['status' => 'archived']);
        }

        $this->redirect('/', 'تم إنهاء الوردية وأرشفة جميع السجلات', 'success');
    }

    // 🗑️ أرشفة سجلات التدقيق
    private function archiveShiftLogs($shiftId)
    {
        try {
            // نسخ إلى الأرشيف
            $stmt = $this->db->prepare("
                INSERT INTO audit_logs_archive (user_id, shift_id, user_name, user_role, action, table_name, record_id, old_data, new_data, ip_address, user_agent, created_at, archived_at)
                SELECT user_id, shift_id, user_name, user_role, action, table_name, record_id, old_data, new_data, ip_address, user_agent, created_at, NOW()
                FROM audit_logs
                WHERE shift_id = ?
            ");
            $stmt->execute([$shiftId]);

            // حذف من الجدول الأساسي
            $stmt = $this->db->prepare("DELETE FROM audit_logs WHERE shift_id = ?");
            $stmt->execute([$shiftId]);
        } catch (\Exception $e) {
            error_log("Archive error (logs): " . $e->getMessage());
        }
    }

    // 🗑️ أرشفة سجلات الحضور
    private function archiveAttendance($shiftId)
    {
        try {
            // نسخ الحضور إلى الأرشيف
            $stmt = $this->db->prepare("
                INSERT INTO attendance_archive (user_id, shift_id, check_in, check_out, status, working_hours, is_modified, modification_reason, modified_by, modified_at, check_in_ip, check_in_device, check_out_ip, check_out_device, shift_type, created_at, archived_at)
                SELECT user_id, ?, check_in, check_out, status, working_hours, is_modified, modification_reason, modified_by, modified_at, check_in_ip, check_in_device, check_out_ip, check_out_device, shift_type, created_at, NOW()
                FROM attendance
                WHERE DATE(check_in) BETWEEN (SELECT start_time FROM shifts WHERE id = ?) AND NOW()
            ");
            $stmt->execute([$shiftId, $shiftId]);

            // حذف الحضور من الجدول الأساسي
            $stmt = $this->db->prepare("
                DELETE FROM attendance 
                WHERE DATE(check_in) BETWEEN (SELECT start_time FROM shifts WHERE id = ?) AND NOW()
            ");
            $stmt->execute([$shiftId]);
        } catch (\Exception $e) {
            error_log("Archive error (attendance): " . $e->getMessage());
        }
    }

    // 🗑️ تنظيف الأرشيف القديم (أكثر من 30 يوم)
    public function cleanArchive()
    {
        if ($this->userRole !== 'admin') {
            $this->redirect('/', 'غير مصرح', 'error');
            return;
        }

        $stmt = $this->db->prepare("DELETE FROM shifts_archive WHERE archived_at < DATE_SUB(NOW(), INTERVAL 30 DAY)");
        $stmt->execute();
        $deletedShifts = $stmt->rowCount();

        $stmt = $this->db->prepare("DELETE FROM audit_logs_archive WHERE archived_at < DATE_SUB(NOW(), INTERVAL 30 DAY)");
        $stmt->execute();
        $deletedLogs = $stmt->rowCount();

        $stmt = $this->db->prepare("DELETE FROM attendance_archive WHERE archived_at < DATE_SUB(NOW(), INTERVAL 30 DAY)");
        $stmt->execute();
        $deletedAttendance = $stmt->rowCount();

        $this->audit->log('clean_archive', null, null, null, [
            'deleted_shifts' => $deletedShifts,
            'deleted_logs' => $deletedLogs,
            'deleted_attendance' => $deletedAttendance
        ]);

        $this->redirect("/shifts?clean=1", 'تم تنظيف الأرشيف', 'success');
    }

    // 📋 عرض الورديات
    public function index()
    {
        if ($this->userRole !== 'admin') {
            $this->redirect('/', 'غير مصرح', 'error');
            return;
        }

        $stmt = $this->db->query("
            SELECT s.*, 
                   (SELECT COUNT(*) FROM audit_logs WHERE shift_id = s.id) as log_count,
                   (SELECT COUNT(*) FROM attendance_archive WHERE shift_id = s.id) as attendance_count
            FROM shifts s
            WHERE s.status != 'archived'
            ORDER BY s.id DESC
        ");
        $shifts = $stmt->fetchAll();

        $this->view('shifts/index', [
            'title' => 'الورديات',
            'shifts' => $shifts
        ]);
    }
}