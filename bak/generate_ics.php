<?php
require_once 'config.php';

// التحقق من وجود الكود الفريد
if (!isset($_GET['code']) || empty($_GET['code'])) {
    die("Invalid request. Appointment code is required.");
}

$code = $_GET['code'];

// جلب بيانات الموعد من قاعدة البيانات
$stmt = $conn->prepare("SELECT name, date, time, email, status FROM appointments WHERE unique_code = ?");
$stmt->bind_param("s", $code);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No appointment found for the provided code.");
}

$appointment = $result->fetch_assoc();

// إعداد القيم الثابتة والديناميكية
$title = "Your Appointment with Azorpub"; // عنوان ثابت
$startDateTime = $appointment['date'] . ' ' . $appointment['time']; // دمج التاريخ والوقت
$startDate = gmdate("Ymd\THis\Z", strtotime($startDateTime)); // تحويل إلى تنسيق UTC
$endDate = gmdate("Ymd\THis\Z", strtotime($startDateTime . ' +1 hour')); // إضافة ساعة لنهاية الموعد
$description = "Thank you for booking with Azorpub.
Name: {$appointment['name']}
Email: {$appointment['email']}
WhatsApp: +32465720410
To modify your appointment, visit:
https://azorpub.infy.uk/appointment.html?code={$code}";

// التحقق من حالة الموعد
if ($appointment['status'] === 'Missed') {
    $description .= "\nNote: This appointment was missed and has been rescheduled.";
} elseif ($appointment['status'] === 'Cancelled') {
    die("This appointment has been cancelled and cannot be added to the calendar.");
}

$location = ""; // الموقع فارغ حالياً
$uid = $code . "@azorpub.com"; // استخدام الكود الفريد كمعرف UID ثابت للحدث
$sequence = time(); // تسلسل فريد بناءً على الوقت الحالي
$lastModified = gmdate("Ymd\THis\Z"); // آخر تعديل

// إنشاء محتوى ملف ICS
$icsContent = "BEGIN:VCALENDAR
VERSION:2.0
CALSCALE:GREGORIAN
METHOD:PUBLISH
BEGIN:VEVENT
UID:$uid
SEQUENCE:$sequence
LAST-MODIFIED:$lastModified
SUMMARY:$title
DTSTART:$startDate
DTEND:$endDate
DESCRIPTION:$description
LOCATION:$location
STATUS:CONFIRMED
END:VEVENT
END:VCALENDAR";

// إرسال الملف للتنزيل
header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename="appointment.ics"');

echo $icsContent;
exit();
