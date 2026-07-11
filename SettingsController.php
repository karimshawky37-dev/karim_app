<?php
namespace App\Controllers;

class SettingsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    // عرض إعدادات العمل
    public function work()
    {
        $settings = $this->getSettings('attendance');
        $this->view('settings/work', [
            'title' => 'إعدادات العمل',
            'settings' => $settings
        ]);
    }

    // تحديث إعدادات العمل
    public function updateWork()
    {
        $keys = ['work_start_time', 'work_end_time', 'work_hours_per_day', 'late_grace_minutes'];
        foreach ($keys as $key) {
            $value = isset($_POST[$key]) ? trim($_POST[$key]) : '';
            $this->setSetting($key, $value, 'attendance');
        }

        $this->audit->log('update_work_settings', 'settings', 0, ['action' => 'update_work_settings'], [
            'work_start_time' => $_POST['work_start_time'] ?? '',
            'work_end_time' => $_POST['work_end_time'] ?? '',
            'work_hours_per_day' => $_POST['work_hours_per_day'] ?? '',
            'late_grace_minutes' => $_POST['late_grace_minutes'] ?? ''
        ]);

        $this->redirect('/settings/work', 'تم تحديث إعدادات العمل بنجاح', 'success');
    }

    // عرض إعدادات الورديات
    public function shifts()
    {
        $settings = $this->getSettings('attendance');
        $this->view('settings/shifts', [
            'title' => 'إعدادات الورديات',
            'settings' => $settings
        ]);
    }

    // تحديث إعدادات الورديات
    public function updateShifts()
    {
        $keys = ['shift_enabled', 'shift_morning_start', 'shift_morning_end', 'shift_evening_start', 'shift_evening_end'];
        foreach ($keys as $key) {
            $value = isset($_POST[$key]) ? trim($_POST[$key]) : '0';
            $this->setSetting($key, $value, 'attendance');
        }

        $this->audit->log('update_shift_settings', 'settings', 0, ['action' => 'update_shift_settings'], $_POST);

        $this->redirect('/settings/shifts', 'تم تحديث إعدادات الورديات بنجاح', 'success');
    }

    // جلب إعداد معين (API)
    public function get($key)
    {
        $value = $this->getSetting($key);
        $this->json(['key' => $key, 'value' => $value]);
    }

    // ===== دوال مساعدة =====
    private function getSettings($group = 'general')
    {
        $stmt = $this->db->prepare("SELECT setting_key, setting_value FROM settings WHERE setting_group = ?");
        $stmt->execute([$group]);
        $result = [];
        while ($row = $stmt->fetch()) {
            $result[$row['setting_key']] = $row['setting_value'];
        }
        return $result;
    }

    private function getSetting($key)
    {
        $stmt = $this->db->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $row = $stmt->fetch();
        return $row ? $row['setting_value'] : null;
    }

    private function setSetting($key, $value, $group = 'general')
    {
        $stmt = $this->db->prepare("
            INSERT INTO settings (setting_key, setting_value, setting_group, updated_at)
            VALUES (?, ?, ?, NOW())
            ON DUPLICATE KEY UPDATE setting_value = ?, updated_at = NOW()
        ");
        $stmt->execute([$key, $value, $group, $value]);
    }
}