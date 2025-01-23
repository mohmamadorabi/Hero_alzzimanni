<?php
// إعدادات قاعدة البيانات
define('DB_HOST', 'sql203.infinityfree.com'); // اسم الخادم
define('DB_USER', 'if0_38130306');      // اسم المستخدم
define('DB_PASS', 'tP1zCAnrhS3K');          // كلمة المرور
define('DB_NAME', 'if0_38130306_azzimani'); // اسم قاعدة البيانات
date_default_timezone_set('Europe/Brussels'); // غيّر المنطقة الزمنية حسب موقعك$conn = new mysqli($host, $user, $pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>