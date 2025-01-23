<?php

// الاتصال بقاعدة البيانات
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}
// تعيين ترميز الأحرف إلى UTF-8
$conn->set_charset("utf8mb4");
?>