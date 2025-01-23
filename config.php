<?php
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

$conn->set_charset("utf8mb4");

// تفعيل أو تعطيل عرض الأخطاء
define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    error_reporting(error_level: E_ALL);
    ini_set('display_errors', 1);
} else {
    // error_reporting(0);
    // ini_set('display_errors', 0);
}

// دالة لجلب الأوقات المتاحة
function get_available_times($date) {
    global $conn;

    // جميع الأوقات المتاحة
    $all_times = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'];

    // استعلام لاسترداد الأوقات المحجوزة في التاريخ المحدد
    $sql = "SELECT time FROM appointments WHERE date = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return ['error' => 'Database error: ' . $conn->error];
    }

    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();

    $booked_times = [];
    while ($row = $result->fetch_assoc()) {
        $booked_times[] = $row['time'];
    }
    
    $stmt->close();

    // استبعاد الأوقات المحجوزة
    $available_times = array_diff($all_times, $booked_times);

    return array_values($available_times);
}


// تحميل ملف اللغة المحددة
// require_once 'lang/lang.php';
?>
