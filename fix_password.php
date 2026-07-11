<?php
// الاتصال بقاعدة البيانات
$host = 'localhost';
$dbname = 'mobile_repair_system';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // المستخدمين وكلمات المرور الصحيحة
    $users = [
        ['admin', 'admin123', 'مدير النظام', 'admin'],
        ['accountant', 'password', 'محاسب النظام', 'accountant'],
        ['tech1', 'tech123', 'أحمد فني بوردة', 'technician'],
        ['tech2', 'tech123', 'محمد فني سوفت وير', 'technician'],
        ['tech3', 'tech123', 'علي فني فك وتقفيل', 'technician'],
    ];
    
    echo "<h1>🔧 تحديث كلمات المرور</h1>";
    echo "<ul>";
    
    foreach ($users as $u) {
        $username = $u[0];
        $plainPassword = $u[1];
        $fullName = $u[2];
        $role = $u[3];
        $hashed = password_hash($plainPassword, PASSWORD_DEFAULT);
        
        // التحقق من وجود المستخدم
        $check = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $check->execute([$username]);
        $exists = $check->fetch();
        
        if ($exists) {
            $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE username = ?");
            $stmt->execute([$hashed, $username]);
            echo "<li>✅ تم تحديث كلمة مرور <strong>$username</strong> → <code>$plainPassword</code></li>";
        } else {
            // إضافة المستخدم إذا لم يكن موجوداً
            $stmt = $pdo->prepare("
                INSERT INTO users (username, email, password_hash, full_name, phone, role, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, 1)
            ");
            $email = $username . '@system.com';
            $phone = '010' . rand(10000000, 99999999);
            $stmt->execute([$username, $email, $hashed, $fullName, $phone, $role]);
            echo "<li>✅ تم إنشاء مستخدم جديد <strong>$username</strong> → <code>$plainPassword</code></li>";
        }
    }
    
    echo "</ul>";
    echo "<hr>";
    echo "<h2>📋 بيانات الدخول:</h2>";
    echo "<ul>";
    echo "<li><strong>admin</strong> / <code>admin123</code> (مدير)</li>";
    echo "<li><strong>accountant</strong> / <code>password</code> (محاسب)</li>";
    echo "<li><strong>tech1</strong> / <code>tech123</code> (فني)</li>";
    echo "<li><strong>tech2</strong> / <code>tech123</code> (فني)</li>";
    echo "<li><strong>tech3</strong> / <code>tech123</code> (فني)</li>";
    echo "</ul>";
    echo "<p><a href='/login' style='display:inline-block; background:blue; color:white; padding:10px 20px; border-radius:8px; text-decoration:none;'>🔑 الذهاب لتسجيل الدخول</a></p>";
    
} catch (PDOException $e) {
    die("❌ خطأ في الاتصال: " . $e->getMessage());
}