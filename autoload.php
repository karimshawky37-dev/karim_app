<?php
/**
 * Autoloader بسيط وقوي
 */
spl_autoload_register(function ($class) {
    // الـ Prefix بتاعنا
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/';
    
    // هل الـ class بيبدأ بـ App\؟
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    // نشيل الـ prefix
    $relative_class = substr($class, $len);
    
    // نحول الـ namespace لمسار
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    // لو الملف موجود، نحمله
    if (file_exists($file)) {
        require $file;
        return true;
    }
    
    // لو مش موجود، نحاول نبحث في مجلدات تانية
    $alt_file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($alt_file)) {
        require $alt_file;
        return true;
    }
    
    return false;
});

// تحميل الملفات الأساسية يدوياً (ضمان)
$core_files = [
    __DIR__ . '/Config/Database.php',
    __DIR__ . '/Config/constants.php',
    __DIR__ . '/Core/Model.php',
];

foreach ($core_files as $file) {
    if (file_exists($file)) {
        require_once $file;
    }
}