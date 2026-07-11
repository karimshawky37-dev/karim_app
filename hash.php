<?php
// تشفير كلمة مرور جديدة
$password = 'tech123'; // 👈 غيّرها لكلمة المرور اللي تريدها
$hashed = password_hash($password, PASSWORD_DEFAULT);

echo "كلمة المرور: " . $password . "<br>";
echo "التشفير: " . $hashed . "<br>";
echo "<hr>";
echo "<strong>انسخ هذا النص وضعه في عمود password_hash في قاعدة البيانات:</strong><br>";
echo "<code style='background:#f0f0f0; padding:10px; display:block; border-radius:5px;'>" . $hashed . "</code>";