<?php
/**
 * الثوابت العامة للنظام
 */

// مسارات الدليل الجذر
define('ROOT_PATH', dirname(__DIR__, 2));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');
define('VIEWS_PATH', APP_PATH . '/Views');

// إعدادات التطبيق
define('APP_NAME', 'نظام إدارة وصيانة المحمول');
define('APP_VERSION', '2.0.0');
define('TIMEZONE', 'Africa/Cairo');
date_default_timezone_set(TIMEZONE);

// إعدادات الـ API
define('DEEPSEEK_API_URL', 'https://api.deepseek.com/v1/chat/completions');
define('DEEPSEEK_API_KEY', $_ENV['DEEPSEEK_API_KEY'] ?? '');

// الصلاحيات (الأدوار)
define('ROLE_ADMIN', 'admin');
define('ROLE_MANAGER', 'manager');
define('ROLE_TECHNICIAN', 'technician');
define('ROLE_SALES', 'sales');
define('ROLE_ACCOUNTANT', 'accountant');
define('ROLE_RECEPTION', 'reception');

// إعدادات الأمان
define('BCRYPT_ROUNDS', 12);

// إعدادات الجلسة
define('SESSION_LIFETIME', 3600 * 8); // 8 ساعات

// إعدادات النسخ الاحتياطي
define('BACKUP_PATH', ROOT_PATH . '/storage/backups');
define('BACKUP_KEEP_DAYS', 30);

// إعدادات النظام
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_TIME', 15); // دقائق

// الحالات النهائية للأجهزة
define('FINAL_STATUSES', ['ready_for_pickup', 'picked_up', 'rejected']);