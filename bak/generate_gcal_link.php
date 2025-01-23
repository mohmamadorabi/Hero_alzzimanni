<?php
require_once 'config.php';

// التحقق من وجود الكود الفريد
if (!isset($_GET['code'])) {
    die("Invalid request. Appointment code is required.");
}

$code = $_GET['code'];

// جلب بيانات الموعد من قاعدة البيانات
$stmt = $conn->prepare("SELECT name, date, time FROM appointments WHERE unique_code = ?");
$stmt->bind_param("s", $code);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No appointment found for the provided code.");
}

$appointment = $result->fetch_assoc();

// إعداد القيم
$title = urlencode("Your Appointment with Azorpub"); // عنوان الحدث
$description = urlencode("Thank you for booking with Azorpub.");
$location = urlencode(""); // الموقع فارغ حالياً

// تحويل التواريخ إلى تنسيق UTC
$startDateTime = $appointment['date'] . ' ' . $appointment['time'];
$startDate = date("Ymd\THis\Z", strtotime($startDateTime));
$endDate = date("Ymd\THis\Z", strtotime($startDateTime . ' +1 hour'));

// إنشاء رابط Google Calendar
$googleCalendarLink = "https://calendar.google.com/calendar/render?action=TEMPLATE&text=$title&dates=$startDate/$endDate&details=$description&location=$location";

header("Location: $googleCalendarLink");
exit();
?>
