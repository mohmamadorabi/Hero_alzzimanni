<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// إعدادات الاتصال بقاعدة البيانات
define('DB_SERVER', 'sql113.infinityfree.com');
define('DB_USERNAME', 'if0_37965291');
define('DB_PASSWORD', '2DDOcIPdiD');
define('DB_NAME', 'if0_37965291_my_website');

// محاولة الاتصال بقاعدة البيانات
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// بدء الجلسة


// إعدادات الرأس
header('Content-Type: text/html; charset=utf-8');

// عرض الأخطاء (لأغراض التطوير فقط، يُنصح بتعطيلها في بيئة الإنتاج)
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
