<?php
/**
 * ملف التصعيد (SLA) - يشتغل كل ساعة عن طريق Cron Job
 * المسار: cron/check_sla.php
 */

// ============================================================
// 1. تحميل البيئة
// ============================================================
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// تحديد المسار الصحيح
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');

// تحميل الـ Autoload
require_once ROOT_PATH . '/app/autoload.php';
require_once ROOT_PATH . '/app/Config/constants.php';

use App\Config\Database;

// ============================================================
// 2. الاتصال بقاعدة البيانات
// ============================================================
$db = Database::getInstance()->getConnection();

// ============================================================
// 3. التحقق من الأجهزة المتأخرة (أكثر من 48 ساعة)
// ============================================================
$stmt = $db->prepare("
    SELECT d.id, d.device_code, d.assigned_technician_id,
           TIMESTAMPDIFF(HOUR, d.received_at, NOW()) as hours_passed
    FROM devices d
    WHERE d.current_status_id NOT IN (SELECT id FROM device_statuses WHERE is_final = 1)
      AND d.deleted_at IS NULL
      AND TIMESTAMPDIFF(HOUR, d.received_at, NOW()) > 48
");
$stmt->execute();
$escalatedDevices = $stmt->fetchAll();

if (count($escalatedDevices) > 0) {
    // ============================================================
    // 4. جلب معرف حالة التصعيد (suspended)
    // ============================================================
    $statusStmt = $db->prepare("SELECT id FROM device_statuses WHERE slug = 'suspended'");
    $statusStmt->execute();
    $statusRow = $statusStmt->fetch();
    $statusId = $statusRow ? $statusRow['id'] : 1;

    // ============================================================
    // 5. تسجيل التصعيد في جدول escalations
    // ============================================================
    foreach ($escalatedDevices as $device) {
        // التحقق من عدم وجود تصعيد نشط لهذا الجهاز
        $checkStmt = $db->prepare("
            SELECT id FROM device_escalations 
            WHERE device_id = ? AND resolved_at IS NULL
        ");
        $checkStmt->execute([$device['id']]);
        if (!$checkStmt->fetch()) {
            $insertStmt = $db->prepare("
                INSERT INTO device_escalations 
                (device_id, status_id, escalated_at, notes)
                VALUES (?, ?, NOW(), ?)
            ");
            $notes = 'تجاوز الجهاز 48 ساعة (' . $device['hours_passed'] . ' ساعة)';
            $insertStmt->execute([$device['id'], $statusId, $notes]);
        }
    }

    // ============================================================
    // 6. إشعار للمدير (user_id = 1)
    // ============================================================
    try {
        $notifStmt = $db->prepare("
            INSERT INTO notifications 
            (user_id, type, title, message, link, created_at)
            VALUES (1, 'sla', ?, ?, '/devices?status=escalated', NOW())
        ");
        $title = '🚨 أجهزة متأخرة!';
        $message = 'يوجد ' . count($escalatedDevices) . ' أجهزة تجاوزت 48 ساعة بدون إصلاح.';
        $notifStmt->execute([$title, $message]);
    } catch (Exception $e) {
        error_log('SLA Notification Error: ' . $e->getMessage());
    }

    // ============================================================
    // 7. تسجيل في سجل النظام (Log)
    // ============================================================
    $logMessage = date('Y-m-d H:i:s') . ' - SLA Check: ' . count($escalatedDevices) . ' devices escalated.' . PHP_EOL;
    file_put_contents(ROOT_PATH . '/storage/logs/sla.log', $logMessage, FILE_APPEND);
}

// ============================================================
// 8. خروج آمن
// ============================================================
echo "✅ SLA Check completed at " . date('Y-m-d H:i:s') . PHP_EOL;
exit;