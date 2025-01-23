<?php
// إعدادات قاعدة البيانات

// date_default_timezone_set('Europe/Brussels'); // غيّر المنطقة الزمنية حسب موقعك

// إعدادات قاعدة البيانات
define('DB_HOST', 'sql203.infinityfree.com'); // اسم الخادم
define('DB_USER', 'if0_38130306');      // اسم المستخدم
define('DB_PASS', 'tP1zCAnrhS3K');          // كلمة المرور
define('DB_NAME', 'if0_38130306_azzimani'); // اسم قاعدة البيانات
date_default_timezone_set('Europe/Brussels'); // غيّر المنطقة الزمنية حسب موقعك
// الاتصال بقاعدة البيانات
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}
?>
