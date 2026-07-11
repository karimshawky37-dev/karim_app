<?php
namespace App\Controllers;

class AttendanceController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        // السماح للفنيين بتسجيل الحضور بدون صلاحية
        if ($this->userRole !== 'technician') {
            $this->requirePermission('view_attendance');
        }
    }

    public function index()
    {
        $month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
        $startDate = date('Y-m-01', strtotime($month));
        $endDate = date('Y-m-t', strtotime($month));

        $stmt = $this->db->prepare("
            SELECT u.id, u.full_name, u.role, DATE(a.check_in) as date, 
                   a.check_in, a.check_out, a.status, a.working_hours, a.is_modified, 
                   a.modification_reason, a.id as attendance_id
            FROM users u
            LEFT JOIN attendance a ON u.id = a.user_id AND DATE(a.check_in) BETWEEN ? AND ?
            WHERE u.is_active = 1
            ORDER BY u.full_name
        ");
        $stmt->execute([$startDate, $endDate]);
        $records = $stmt->fetchAll();

        $stmt = $this->db->prepare("
            SELECT 
                COUNT(DISTINCT user_id) as total_employees,
                COUNT(DISTINCT CASE WHEN DATE(check_in) = CURDATE() AND status != 'absent' THEN user_id END) as today_present,
                COUNT(DISTINCT CASE WHEN DATE(check_in) = CURDATE() AND status = 'absent' THEN user_id END) as today_absent,
                COUNT(DISTINCT CASE WHEN DATE(check_in) = CURDATE() AND status = 'late' THEN user_id END) as today_late,
                COUNT(DISTINCT CASE WHEN DATE(check_in) = CURDATE() AND status = 'half_day' THEN user_id END) as today_half_day
            FROM attendance
            WHERE DATE(check_in) = CURDATE()
        ");
        $stmt->execute();
        $stats = $stmt->fetch();

        $this->view('attendance/index', [
            'title' => 'الحضور والانصراف',
            'records' => $records,
            'stats' => $stats,
            'month' => $month,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    public function checkIn()
    {
        $userId = $this->userId;
        $today = date('Y-m-d');

        $stmt = $this->db->prepare("SELECT id FROM attendance WHERE user_id = ? AND DATE(check_in) = ?");
        $stmt->execute([$userId, $today]);
        if ($stmt->fetch()) {
            $this->redirect('/', 'تم تسجيل حضورك بالفعل اليوم', 'warning');
            return;
        }

        // جلب إعدادات العمل
        $stmt = $this->db->query("SELECT setting_key, setting_value FROM settings WHERE setting_group = 'attendance'");
        $settings = [];
        while ($row = $stmt->fetch()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }

        $workStart = $settings['work_start_time'] ?? '09:00';
        $graceMinutes = (int) ($settings['late_grace_minutes'] ?? 15);
        $shiftEnabled = (int) ($settings['shift_enabled'] ?? 0);
        $morningStart = $settings['shift_morning_start'] ?? '09:00';
        $morningEnd = $settings['shift_morning_end'] ?? '14:00';
        $eveningStart = $settings['shift_evening_start'] ?? '14:00';
        $eveningEnd = $settings['shift_evening_end'] ?? '20:00';

        $now = new \DateTime();
        $currentTime = $now->format('H:i');
        $status = 'present';
        $shiftType = null;

        if ($shiftEnabled) {
            if ($currentTime >= $morningStart && $currentTime < $morningEnd) {
                $shiftType = 'morning';
                $startTime = $morningStart;
            } elseif ($currentTime >= $eveningStart && $currentTime < $eveningEnd) {
                $shiftType = 'evening';
                $startTime = $eveningStart;
            } else {
                $status = 'absent';
                $startTime = $workStart;
            }
        } else {
            $startTime = $workStart;
        }

        if ($status !== 'absent') {
            $startDateTime = new \DateTime($today . ' ' . $startTime);
            $diffMinutes = ($now->getTimestamp() - $startDateTime->getTimestamp()) / 60;
            if ($diffMinutes > $graceMinutes) {
                $status = 'late';
            }
        }

        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        $device = $_SERVER['HTTP_USER_AGENT'] ?? null;

        $stmt = $this->db->prepare("
            INSERT INTO attendance 
            (user_id, check_in, status, check_in_ip, check_in_device, created_at, shift_type) 
            VALUES (?, NOW(), ?, ?, ?, NOW(), ?)
        ");
        $stmt->execute([$userId, $status, $ip, $device, $shiftType]);

        $this->audit->logCreate('attendance', $this->db->lastInsertId(), [
            'user_id' => $userId,
            'action' => 'check_in',
            'status' => $status,
            'shift_type' => $shiftType
        ]);

        $message = '✅ تم تسجيل حضورك بنجاح';
        if ($status === 'late') {
            $message = '⚠️ تم تسجيل حضورك متأخراً';
        }
        $this->redirect('/', $message, $status === 'late' ? 'warning' : 'success');
    }

    public function checkOut()
    {
        $userId = $this->userId;
        $today = date('Y-m-d');

        $stmt = $this->db->prepare("SELECT id, check_in FROM attendance WHERE user_id = ? AND DATE(check_in) = ? AND check_out IS NULL ORDER BY id DESC LIMIT 1");
        $stmt->execute([$userId, $today]);
        $record = $stmt->fetch();

        if (!$record) {
            $this->redirect('/', 'لا يوجد حضور مسجل اليوم', 'error');
            return;
        }

        $checkIn = new \DateTime($record['check_in']);
        $checkOut = new \DateTime('now');
        $diff = $checkIn->diff($checkOut);
        $hours = $diff->h + ($diff->days * 24) + ($diff->i / 60);
        $working_hours = round($hours, 2);

        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        $device = $_SERVER['HTTP_USER_AGENT'] ?? null;

        $stmt = $this->db->prepare("
            UPDATE attendance 
            SET check_out = NOW(), check_out_ip = ?, check_out_device = ?, working_hours = ? 
            WHERE id = ?
        ");
        $stmt->execute([$ip, $device, $working_hours, $record['id']]);

        $this->audit->logUpdate('attendance', $record['id'], ['action' => 'check_out', 'working_hours' => $working_hours], []);
        $this->redirect('/', '🚪 تم تسجيل انصرافك بنجاح', 'success');
    }

    public function edit()
    {
        $this->requirePermission('edit_attendance');
        $attendanceId = (int) $_POST['attendance_id'];
        $status = $_POST['status'] ?? 'present';
        $reason = isset($_POST['reason']) ? trim($_POST['reason']) : 'تعديل يدوي';

        $stmt = $this->db->prepare("
            UPDATE attendance 
            SET status = ?, is_modified = 1, modified_by = ?, modified_at = NOW(), modification_reason = ? 
            WHERE id = ?
        ");
        $stmt->execute([$status, $this->userId, $reason, $attendanceId]);

        $this->audit->logUpdate('attendance', $attendanceId, ['action' => 'edit_status', 'status' => $status, 'reason' => $reason], []);
        $this->redirect('/attendance', 'تم تحديث حالة الحضور', 'success');
    }

    public function report()
    {
        $this->requirePermission('view_attendance');
        $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

        $stmt = $this->db->prepare("
            SELECT u.id, u.full_name, u.role,
                   COUNT(a.id) as days_worked,
                   SUM(CASE WHEN a.status = 'present' THEN 1 ELSE 0 END) as present_days,
                   SUM(CASE WHEN a.status = 'late' THEN 1 ELSE 0 END) as late_days,
                   SUM(CASE WHEN a.status = 'absent' THEN 1 ELSE 0 END) as absent_days,
                   SUM(CASE WHEN a.status = 'half_day' THEN 1 ELSE 0 END) as half_days,
                   SUM(CASE WHEN a.status = 'holiday' THEN 1 ELSE 0 END) as holiday_days,
                   SUM(a.working_hours) as total_hours,
                   ROUND(AVG(a.working_hours), 2) as avg_hours
            FROM users u
            LEFT JOIN attendance a ON u.id = a.user_id AND DATE(a.check_in) BETWEEN ? AND ?
            WHERE u.is_active = 1
            GROUP BY u.id
            ORDER BY u.full_name
        ");
        $stmt->execute([$startDate, $endDate]);
        $report = $stmt->fetchAll();

        $this->view('attendance/report', [
            'title' => 'تقرير الحضور',
            'report' => $report,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    public function exportCsv()
    {
        $this->requirePermission('view_attendance');
        $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

        $stmt = $this->db->prepare("
            SELECT u.full_name, u.role, DATE(a.check_in) as date, a.check_in, a.check_out, a.status, a.working_hours
            FROM users u
            LEFT JOIN attendance a ON u.id = a.user_id AND DATE(a.check_in) BETWEEN ? AND ?
            WHERE u.is_active = 1
            ORDER BY u.full_name, a.check_in
        ");
        $stmt->execute([$startDate, $endDate]);
        $data = $stmt->fetchAll();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="تقرير_الحضور_' . date('Y-m-d') . '.csv"');
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($output, ['الموظف', 'الدور', 'التاريخ', 'وقت الحضور', 'وقت الانصراف', 'الحالة', 'ساعات العمل']);
        foreach ($data as $row) {
            fputcsv($output, [
                $row['full_name'],
                $row['role'],
                $row['date'] ?? '—',
                $row['check_in'] ? date('h:i A', strtotime($row['check_in'])) : '—',
                $row['check_out'] ? date('h:i A', strtotime($row['check_out'])) : '—',
                $row['status'] ?? 'absent',
                $row['working_hours'] ?? 0
            ]);
        }
        fclose($output);
        exit;
    }

    public function calendar()
    {
        $year = isset($_GET['year']) ? (int) $_GET['year'] : date('Y');
        $month = isset($_GET['month']) ? (int) $_GET['month'] : date('m');
        $firstDay = date('N', strtotime("$year-$month-01"));
        $daysInMonth = date('t', strtotime("$year-$month-01"));

        $stmt = $this->db->prepare("
            SELECT u.id, u.full_name, DAY(a.check_in) as day, a.status, a.working_hours 
            FROM users u 
            LEFT JOIN attendance a ON u.id = a.user_id AND MONTH(a.check_in) = ? AND YEAR(a.check_in) = ? 
            WHERE u.is_active = 1 
            ORDER BY u.id
        ");
        $stmt->execute([$month, $year]);
        $records = $stmt->fetchAll();

        $this->view('attendance/calendar', [
            'title' => 'تقويم الحضور',
            'year' => $year,
            'month' => $month,
            'daysInMonth' => $daysInMonth,
            'firstDay' => $firstDay,
            'records' => $records
        ]);
    }
}