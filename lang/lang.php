<?php
// تحديد اللغة المختارة (إذا لم يتم تحديدها، نستخدم اللغة الافتراضية)
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
} else {
    $lang = $default_lang;
}

// تحميل ملف اللغة المحدد
$lang_file = "lang/{$lang}.php";
if (file_exists($lang_file)) {
    require_once $lang_file;
} else {
    die("ملف اللغة غير موجود!");
}
?>