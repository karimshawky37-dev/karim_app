<?php
// ============================================================
// النسخ الاحتياطي التلقائي لقاعدة البيانات (حفظ في مكانين)
// ============================================================

// إعدادات الاتصال بقاعدة البيانات
$host = 'localhost';
$dbname = 'mobile_repair_system';
$user = 'root';
$pass = '';

// ====== مسارات الحفظ ======
// 1. المسار المحلي (داخل المشروع)
$localBackupDir = __DIR__ . '/storage/backups/';

// 2. المسار السحابي (OneDrive - غير المسار حسب جهازك)
// استخدم forward slashes لتجنب مشاكل الإفلات
$cloudBackupDir = 'C:/Users/Gopran Group/OneDrive/Desktop/Backups/';

// ====== إنشاء المجلدات لو مش موجودة ======
foreach ([$localBackupDir, $cloudBackupDir] as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// ====== توليد اسم ملف فريد ======
$date = date('Y-m-d_H-i-s');
$filename = "backup_{$dbname}_{$date}.sql";

// المسارات الكاملة للملفين
$localFile = $localBackupDir . $filename;
$cloudFile = $cloudBackupDir . $filename;

// ====== مسار mysqldump ======
$mysqldump = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';

// ====== بناء أمر التصدير ======
$command = "\"$mysqldump\" -h $host -u $user " . ($pass ? "-p$pass" : "") . " --databases $dbname > \"%s\" 2>&1";

// ====== تنفيذ الأمر مرتين (مرة لكل مسار) ======
$commands = [
    'المسار المحلي' => sprintf($command, $localFile),
    'المسار السحابي (OneDrive)' => sprintf($command, $cloudFile)
];

$allSuccess = true;
$results = [];

foreach ($commands as $location => $cmd) {
    exec($cmd, $output, $returnCode);
    if ($returnCode === 0) {
        // نأخذ حجم الملف المحلي للعرض (نفس الملف)
        $size = file_exists($localFile) ? filesize($localFile) : 0;
        $results[] = "✅ [$location] تم حفظ النسخة في: " . basename($localFile) . " (حجم: " . number_format($size / 1024, 2) . " KB)";
    } else {
        $allSuccess = false;
        $results[] = "❌ [$location] فشل الحفظ!";
    }
}

// ====== عرض النتائج ======
echo "<h2>📦 نتيجة النسخ الاحتياطي</h2>";
echo "<ul>";
foreach ($results as $msg) {
    echo "<li>$msg</li>";
}
echo "</ul>";

// ====== تنظيف الملفات القديمة (أكثر من 30 يوم) من المجلد المحلي ======
$files = glob($localBackupDir . '*.sql');
$now = time();
$deleted = 0;
foreach ($files as $file) {
    if (is_file($file) && ($now - filemtime($file)) > 30 * 24 * 60 * 60) {
        unlink($file);
        $deleted++;
    }
}
if ($deleted > 0) {
    echo "<p>🗑️ تم حذف $deleted نسخة قديمة (أكثر من 30 يوم) من المجلد المحلي.</p>";
}

// ====== عرض موقع الملفات ======
echo "<hr>";
echo "<p>📁 الملف المحلي: <code>$localFile</code></p>";
echo "<p>📁 الملف السحابي: <code>$cloudFile</code></p>";

if ($allSuccess) {
    echo "<p style='color:green; font-weight:bold;'>✅ النسخ الاحتياطي تم بنجاح في كلا الموقعين!</p>";
} else {
    echo "<p style='color:red; font-weight:bold;'>⚠️ حدث خطأ في أحد المسارات، تحقق من صلاحيات المجلدات.</p>";
}