<?php
// router.php - للتعامل مع الـ PHP Built-in Server
if (file_exists(__DIR__ . '/' . $_SERVER['REQUEST_URI'])) {
    return false; // يخدم الملف لو موجود
} else {
    include_once __DIR__ . '/index.php';
}