<?php
// الاتصال بقاعدة البيانات
$host = 'localhost';
$dbname = 'mobile_repair_system';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // كلمات المرور الجديدة
    $users = [
        ['admin', 'admin123'],
        ['tech1', 'tech123'],
        ['tech2', 'tech123'],
        ['tech3', 'tech123'],
    ];
    
    echo "<h1>تحديث كلمات المرور</h1>";
    echo "<ul>";
    
    foreach ($users as $u) {
        $username = $u[0];
        $plainPassword = $u[1];
        $hashed = password_hash($plainPassword, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE username = ?");
        $stmt->execute([$hashed, $username]);
        
        if ($stmt->rowCount() > 0) {
            echo "<li>✅ تم تحديث كلمة مرور <strong>$username</strong> بنجاح</li>";
        } else {
            echo "<li>❌ المستخدم <strong>$username</strong> غير موجود</li>";
        }
    }
    
    echo "</ul>";
    echo "<p><a href='/login'>الذهاب لتسجيل الدخول →</a></p>";
    
} catch (PDOException $e) {
    die("خطأ في الاتصال: " . $e->getMessage());
}