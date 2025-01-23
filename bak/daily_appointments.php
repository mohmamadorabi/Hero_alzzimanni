<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'config.php';

require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';

// إعداد المنطقة الزمنية
date_default_timezone_set('Europe/Brussels');

// === الحالة 1: إرسال تذكير بالموعد قبل 12 ساعة ===
// $queryReminder = "SELECT * FROM appointments 
//                   WHERE DATE = CURDATE() AND TIME >= CURTIME() AND TIME <= ADDTIME(CURTIME(), '12:00:00') AND status = 'Confirmed'";
// $resultReminder = $conn->query($queryReminder);


$queryReminder = "SELECT * FROM appointments 
    WHERE status = 'Confirmed' AND CONCAT(date, ' ', time) BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 12 HOUR)AND reminded = 0";

$resultReminder = $conn->query($queryReminder);

if ($resultReminder->num_rows > 0) {
    while ($appointment = $resultReminder->fetch_assoc()) {
        $mail = new PHPMailer(true);
        try {
            // إعدادات البريد
            $mail->isSMTP();
            $mail->Host = 'smtppro.zoho.eu';
            $mail->SMTPAuth = true;
            $mail->Username = 'info@azorpub.be';
            $mail->Password = 'Orabi@@22';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('info@azorpub.be', 'Azorpub Appointments');
            $mail->addAddress($appointment['email'], $appointment['NAME']);

            $mail->isHTML(true);
            $mail->Subject = 'Appointment Reminder';
            $mail->Body = "
                    <div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                        <div style='background-color: #007bff; color: white; padding: 20px; text-align: center;'>
                            <h1 style='margin: 0;'>Azorpub</h1>
                            <p style='margin: 0;'>Appointment Reminder</p>
                        </div>
                        <div style='padding: 20px; border: 1px solid #ddd; border-radius: 5px; background-color: #f9f9f9;'>
                            <h2 style='color: #007bff;'>Hello {$appointment['NAME']},</h2>
                            <p>This is a reminder for your upcoming appointment with Azorpub:</p>
                            <ul style='list-style: none; padding: 0;'>
                                <li><strong>Date:</strong> {$appointment['DATE']}</li>
                                <li><strong>Time:</strong> {$appointment['TIME']}</li>
                            </ul>
                            <p style='margin-top: 20px; font-size: 14px;'>Please make sure to arrive on time. If you need to modify or cancel your appointment, visit our website.</p>
                            <p style='margin-top: 20px; font-size: 14px;'>Thank you for choosing Azorpub!</p>
                        </div>
                        <div style='text-align: center; background-color: #f1f1f1; color: #666; padding: 10px; font-size: 0.9rem; margin-top: 20px;'>
                            <p style='margin: 0;'>© 2024 Azorpub. All Rights Reserved.</p>
                        </div>
                    </div>
                ";

            $mail->send();

            // تحديث حالة التذكير
            $stmt = $conn->prepare("UPDATE appointments SET reminded = 1 WHERE id = ?");
            $stmt->bind_param("i", $appointment['id']);
            $stmt->execute();
        } catch (Exception $e) {
            error_log("Error sending reminder: " . $mail->ErrorInfo);
        }
    }
}
// === الحالة 2: تحديث وإشعار الموعد الذي تم تفويته ===
$queryMissed = "SELECT * FROM appointments WHERE DATE < CURDATE() AND status = 'Confirmed'";
$resultMissed = $conn->query($queryMissed);

if ($resultMissed->num_rows > 0) {
    // تحضير جملة التحديث
    $updateStmt = $conn->prepare("UPDATE appointments SET status = 'Missed' WHERE id = ?");

    while ($appointment = $resultMissed->fetch_assoc()) {
        $updateStmt->bind_param("i", $appointment['id']);
        if ($updateStmt->execute()) {
            $mail = new PHPMailer(true);
            try {
                // إعدادات SMTP
                $mail->isSMTP();
                $mail->Host = 'smtppro.zoho.eu';
                $mail->SMTPAuth = true;
                $mail->Username = 'info@azorpub.be';
                $mail->Password = 'Orabi@@22'; // استبدلها بكلمة مرور SMTP الخاصة بك
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // إعدادات البريد
                $mail->setFrom('info@azorpub.be', 'Azorpub Appointments');
                $mail->addAddress($appointment['email'], $appointment['NAME']);

                $mail->isHTML(true);
                $mail->Subject = 'Missed Appointment Notification';
                    $mail->Body = "
                        <div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                            <div style='background-color: #ff4d4d; color: white; padding: 20px; text-align: center;'>
                                <h1 style='margin: 0;'>Azorpub</h1>
                                <p style='margin: 0;'>Missed Appointment</p>
                            </div>
                            <div style='padding: 20px; border: 1px solid #ddd; border-radius: 5px; background-color: #f9f9f9;'>
                                <h2 style='color: #ff4d4d;'>Hello {$appointment['NAME']},</h2>
                                <p>We noticed that you missed your scheduled appointment:</p>
                                <ul style='list-style: none; padding: 0;'>
                                    <li><strong>Date:</strong> {$appointment['DATE']}</li>
                                    <li><strong>Time:</strong> {$appointment['TIME']}</li>
                                </ul>
                                <p>You can reschedule your appointment by visiting our website.</p>
                                <p style='margin-top: 20px; font-size: 14px;'>If you have any questions, feel free to contact us at info@azorpub.be.</p>
                                <p style='margin-top: 20px; font-size: 14px;'>Thank you for choosing Azorpub.</p>
                            </div>
                            <div style='text-align: center; background-color: #f1f1f1; color: #666; padding: 10px; font-size: 0.9rem; margin-top: 20px;'>
                                <p style='margin: 0;'>© 2024 Azorpub. All Rights Reserved.</p>
                            </div>
                        </div>
                    ";


                $mail->send();
            } catch (Exception $e) {
                error_log("خطأ في إرسال إشعار التخلف: " . $mail->ErrorInfo);
            }
        } else {
            error_log("خطأ في تحديث الموعد ID {$appointment['id']}: " . $conn->error);
        }
    }
    $updateStmt->close();
}

$conn->close();

echo "تم تنفيذ العملية بنجاح.";
?>

<!-- php /path/to/update_status.php -->