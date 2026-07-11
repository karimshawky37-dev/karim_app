<?php
// تنظيف سجلات التدقيق المؤرشفة التي عمرها أكثر من 14 يوم
require_once __DIR__ . '/app/Config/Database.php';
require_once __DIR__ . '/app/Config/constants.php';

use App\Config\Database;

$db = Database::getInstance()->getConnection();

// حذف السجلات المؤرشفة الأقدم من 14 يوم
$stmt = $db->prepare("DELETE FROM audit_logs_archive WHERE archived_at < DATE_SUB(NOW(), INTERVAL 14 DAY)");
$stmt->execute();
$deletedLogs = $stmt->rowCount();

// حذف الورديات المؤرشفة الأقدم من 14 يوم
$stmt = $db->prepare("DELETE FROM shifts_archive WHERE archived_at < DATE_SUB(NOW(), INTERVAL 14 DAY)");
$stmt->execute();
$deletedShifts = $stmt->rowCount();

echo "✅ [" . date('Y-m-d H:i:s') . "] تم حذف $deletedShifts وردية مؤرشفة و $deletedLogs سجل تدقيق مؤرشف (أقدم من 14 يومًا).\n";