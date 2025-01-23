<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'config.php';

require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';

header('Content-Type: application/json');
require_once 'config.php';

// قراءة البيانات المرسلة
$data = json_decode(file_get_contents('php://input'), true);

$name = $data['name'] ?? '';
$email = $data['email'] ?? '';
$date = $data['date'] ?? '';
$time = $data['time'] ?? '';
$action = $data['action'] ?? ''; // تحديد نوع الطلب: check أو book
error_log("Received date: $date");
try {
    $formattedDate = (new DateTime($date))->format('Y-m-d');
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Invalid date format.']);
    exit();
}


if ($action === 'check') {
     // إذا كنا نقوم بتعديل موعد، تجاوز التحقق
    if (isset($_POST['code'])) {
        echo json_encode(['exists' => false]); // السماح بالمتابعة
        exit;
    }
    // التحقق من وجود الحجز
    $stmt = $conn->prepare("SELECT date, time FROM appointments WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode([
            'exists' => true,
            'message' => "You already have a booking on {$row['date']} at {$row['time']}."
        ]);
    } else {
        echo json_encode(['exists' => false, 'message' => 'No existing booking found for this email.']);
    }

    $stmt->close();
    $conn->close();
    exit();
}
if ($action === 'send_code') {
    // إرسال الكود الفريد بالبريد الإلكتروني
    $stmt = $conn->prepare("SELECT unique_code FROM appointments WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $uniqueCode = $row['unique_code'];

        // إرسال البريد الإلكتروني (يمكنك استخدام أي مكتبة بريد إلكتروني مثل PHPMailer)
        $subject = "Your Appointment Code";
        $message = "Your unique appointment code is: $uniqueCode";
        $headers = "From: no-reply@azorpub.com";

        if (mail($email, $subject, $message, $headers)) {
            echo json_encode(['success' => true, 'message' => 'The code has been sent to your email.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to send the email.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No booking found for this email.']);
    }

    $stmt->close();
    $conn->close();
    exit();
}

if ($action === 'get_booked_times') {
    $date = $data['date'] ?? '';
    
    if (!$date) {
        echo json_encode(['success' => false, 'message' => 'Invalid date.']);
        exit();
    }

    // $stmt = $conn->prepare("SELECT time FROM appointments WHERE date = ?");
    $stmt = $conn->prepare("SELECT time FROM appointments WHERE date = ? AND status != 'Cancelled'");

    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();

    $bookedTimes = [];
    while ($row = $result->fetch_assoc()) {
        $bookedTimes[] = $row['time'];
    }

    echo json_encode(['success' => true, 'bookedTimes' => $bookedTimes]);
    exit();
}
if ($action === 'fetch_appointment') {
    $code = $data['code'] ?? '';

    if (!$code) {
        echo json_encode(['success' => false, 'message' => 'Invalid appointment code.']);
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM appointments WHERE unique_code = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'No appointment found for this code.']);
    } else {
        $appointment = $result->fetch_assoc();
        echo json_encode(['success' => true, 'appointment' => $appointment]);
    }

    $stmt->close();
    exit();
}
if ($action === 'update') {
    if (!$name || !$email || !$date || !$time) {
        echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
        exit();
    }
    // جلب قيمة الكود من الطلب
    $appointmentCode = isset($data['code']) ? $data['code'] : null;
    // تحقق من وجود الكود الفريد
    if (!$appointmentCode) {
        echo json_encode(['success' => false, 'message' => 'Appointment code is missing.']);
        exit();
    }

    // تحديث الموعد في قاعدة البيانات
    // $stmt = $conn->prepare("UPDATE appointments SET name = ?, date = ?, time = ? WHERE unique_code = ?");
    $stmt = $conn->prepare("UPDATE appointments SET name = ?, date = ?, time = ?, status = 'Confirmed' WHERE unique_code = ?");

    $stmt->bind_param("ssss", $name, $date, $time, $appointmentCode);

    if ($stmt->execute()) {
        // إعداد البريد الإلكتروني لتأكيد التعديل
        $mail = new PHPMailer(true);

        try {
            // إعدادات SMTP
            $mail->isSMTP();
            $mail->Host = 'smtppro.zoho.eu'; // خادم SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'info@azorpub.be'; // بريدك الإلكتروني
            $mail->Password = 'Orabi@@22'; // كلمة المرور
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // التشفير TLS
            $mail->Port = 587;

            // إعدادات البريد المرسل
            $mail->setFrom('info@azorpub.be', 'Azorpub Appointments');
            $mail->addAddress($email);

            // محتوى البريد
            $mail->isHTML(true);
            $mail->Subject = 'Appointment Updated Successfully';
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                    <div style='background-color: #007bff; color: white; padding: 20px; text-align: center;'>
                        <h1 style='margin: 0;'>Azorpub</h1>
                        <p style='margin: 0;'>Appointment Update Confirmation</p>
                    </div>
                    <div style='padding: 20px; border: 1px solid #ddd; border-radius: 5px; background-color: #f9f9f9;'>
                        <h2 style='color: #007bff;'>Hello $name,</h2>
                        <p>Your appointment has been successfully updated. Here are the new details:</p>
                        <ul style='list-style: none; padding: 0;'>
                            <li><strong>Date:</strong> $date</li>
                            <li><strong>Time:</strong> $time</li>
                        </ul>
                        <p style='margin-top: 20px; font-size: 14px;'>If you have any questions, feel free to contact us at info@azorpub.be.</p>
                        <p style='margin-top: 20px; font-size: 14px;'>Thank you for choosing Azorpub!</p>
                    </div>
                    <div style='text-align: center; background-color: #f1f1f1; color: #666; padding: 10px; font-size: 0.9rem; margin-top: 20px;'>
                        <p style='margin: 0;'>© 2024 Azorpub. All Rights Reserved.</p>
                    </div>
                </div>
            ";

            $mail->send();
            echo json_encode(['success' => true, 'message' => 'Appointment updated successfully! A confirmation email has been sent.']);
        } catch (Exception $e) {
            error_log("Mailer Error: " . $mail->ErrorInfo);
            echo json_encode(['success' => true, 'message' => 'Appointment updated successfully, but failed to send email.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update the appointment.']);
    }

    $stmt->close();
    $conn->close();
}


if ($action === 'book') {
    if (!$name || !$email || !$date || !$time) {
        echo json_encode(['success' => false, 'message' => 'الرجاء ملء جميع الحقول المطلوبة.']);
        exit();
    }

    // التحقق مما إذا كان البريد الإلكتروني موجودًا بالفعل
    $stmt = $conn->prepare("SELECT * FROM appointments WHERE email = ? AND status != 'Cancelled'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // البريد الإلكتروني موجود بالفعل
        echo json_encode([
            'success' => false,
            'message' => 'Email already has an appointment',
            'forgot_password' => true // مؤشر لإظهار خيار "نسيت كلمة المرور"
        ]);
        exit();
    }

    // التحقق من توفر الموعد
    $stmt = $conn->prepare("SELECT * FROM appointments WHERE date = ? AND time = ?");
    $stmt->bind_param("ss", $date, $time);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'هذا الموعد محجوز بالفعل.']);
        exit();
    }

    // توليد كود فريد
    function generateUniqueCode($length = 6) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    do {
        $uniqueCode = generateUniqueCode(6);
        $stmt = $conn->prepare("SELECT * FROM appointments WHERE unique_code = ?");
        $stmt->bind_param("s", $uniqueCode);
        $stmt->execute();
        $result = $stmt->get_result();
    } while ($result->num_rows > 0);

    // إدخال الموعد في قاعدة البيانات
    $stmt = $conn->prepare("INSERT INTO appointments (name, email, date, time, unique_code, status) VALUES (?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("sssss", $name, $email, $date, $time, $uniqueCode);

    if ($stmt->execute()) {
        // إرسال البريد الإلكتروني لتأكيد الحجز
        $mail = new PHPMailer(true);

        try {
            // إعدادات SMTP
            $mail->isSMTP();
            $mail->Host = 'smtppro.zoho.eu'; // خادم SMTP لعملاء Zoho المدفوعين
            $mail->SMTPAuth = true;
            $mail->Username = 'info@azorpub.be'; // بريدك الإلكتروني
            $mail->Password = 'Orabi@@22'; // كلمة مرور Zoho الخاصة بك
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // استخدام التشفير TLS
            $mail->Port = 587; // المنفذ لـ TLS

            // إعدادات البريد المرسل
            $mail->setFrom('info@azorpub.be', 'Azorpub Appointments'); // عنوان البريد والاسم
            $mail->addAddress($email); // البريد المستلم

            // محتوى البريد
            $mail->isHTML(true);
            $mail->Subject = 'Appointment Confirmation';
            $verificationLink = "https://azorpub.infy.uk//confirm_booking.php?code=$uniqueCode";

                $mail->Body = "
                    <div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                        <div style='background-color: #007bff; color: white; padding: 20px; text-align: center;'>
                            <h1 style='margin: 0;'>Azorpub</h1>
                            <p style='margin: 0;'>Appointment Confirmation</p>
                        </div>
                        <div style='padding: 20px; border: 1px solid #ddd; border-radius: 5px; background-color: #f9f9f9;'>
                            <h2 style='color: #007bff;'>Hello $name,</h2>
                            <p>Thank you for booking an appointment with Azorpub. To confirm your appointment, please click the button below:</p>
                            <p style='text-align: center; margin-top: 20px;'>
                                <a href='https://azorpub.infy.uk/confirm_booking.php?code=$uniqueCode' 
                                style='display: inline-block; padding: 10px 20px; color: white; background-color: #28a745; text-decoration: none; border-radius: 5px; font-size: 16px;'>
                                Confirm Appointment
                                </a>
                            </p>
                            <p style='margin-top: 20px; font-size: 14px; color: #555;'>
                                If the button above does not work, you can also copy and paste the following link into your browser:
                            </p>
                            <p style='background-color: #f1f1f1; padding: 10px; border-radius: 5px; word-wrap: break-word;'>
                                https://azorpub.infy.uk/confirm_booking.php?code=$uniqueCode
                            </p>
                            <p style='margin-top: 20px; font-size: 14px;'>Please confirm your appointment within the next 24 hours to secure your slot.</p>
                            <p style='margin-top: 20px; font-size: 14px;'>Thank you for choosing Azorpub. We look forward to serving you!</p>
                        </div>
                        <div style='text-align: center; background-color: #f1f1f1; color: #666; padding: 10px; font-size: 0.9rem; margin-top: 20px;'>
                            <p style='margin: 0;'>© 2024 Azorpub. All Rights Reserved.</p>
                        </div>
                    </div>
                ";


            $mail->send();
            // echo json_encode(['success' => true, 'unique_code' => $uniqueCode, 'message' => 'Appointment booked and email sent successfully.']);
            echo json_encode(['success' => true, 'message' => 'Booking successful! Please check your email to confirm the appointment.']);

        } catch (Exception $e) {
            error_log("Mailer Error: " . $mail->ErrorInfo);
            echo json_encode(['success' => true, 'unique_code' => $uniqueCode, 'message' => 'Appointment booked successfully, but failed to send email.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save the booking.']);
    }
    $stmt->close();
    $conn->close();
}
if ($action === 'resend_appointment_details') {
    // $email = $_POST['email'] ?? '';

    if (empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Email is required.']);
        exit();
    }

    $stmt = $conn->prepare("SELECT name, date, time, unique_code, status FROM appointments WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $appointment = $result->fetch_assoc();
        $name = $appointment['name'];
        $date = $appointment['date'];
        $time = $appointment['time'];
        $uniqueCode = $appointment['unique_code'];

        if ($appointment['status'] === 'Cancelled') {
            echo json_encode(['success' => false, 'message' => 'This appointment has been cancelled.']);
            exit();
        }

        // إعداد البريد الإلكتروني
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtppro.zoho.eu';
            $mail->SMTPAuth = true;
            $mail->Username = 'info@azorpub.be';
            $mail->Password = 'Orabi@@22';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;



            $mail->setFrom('info@azorpub.be', 'Azorpub Appointments');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Your Appointment Details';
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                    <div style='background-color: #007bff; color: white; padding: 20px; text-align: center;'>
                        <h1 style='margin: 0;'>Azorpub Appointments</h1>
                        <p style='margin: 0;'>Your Trusted Partner in Scheduling</p>
                    </div>
                    <div style='padding: 20px; border: 1px solid #ddd; border-radius: 5px; background-color: #f9f9f9;'>
                        <h2 style='color: #007bff;'>Dear $name,</h2>
                        <p>Your appointment details are as follows:</p>
                        <table style='width: 100%; border-collapse: collapse; margin-top: 20px;'>
                            <tr>
                                <td style='padding: 10px; border: 1px solid #ddd; font-weight: bold;'>Date:</td>
                                <td style='padding: 10px; border: 1px solid #ddd;'>$date</td>
                            </tr>
                            <tr>
                                <td style='padding: 10px; border: 1px solid #ddd; font-weight: bold;'>Time:</td>
                                <td style='padding: 10px; border: 1px solid #ddd;'>$time</td>
                            </tr>
                            <tr>
                                <td style='padding: 10px; border: 1px solid #ddd; font-weight: bold;'>Unique Code:</td>
                                <td style='padding: 10px; border: 1px solid #ddd;'>$uniqueCode</td>
                            </tr>
                        </table>
                        <p style='margin-top: 20px;'>You can <strong>modify</strong> or <strong>cancel</strong> your appointment by clicking the appropriate link below:</p>
                        <p>
                            <a href='https://azorpub.infy.uk/appointment.html?code=$uniqueCode' 
                            style='display: inline-block; padding: 10px 20px; color: white; background-color: #28a745; text-decoration: none; border-radius: 5px; margin-right: 10px;'>
                            Modify Appointment
                            </a>
                            <a href='https://azorpub.infy.uk/cancel_appointment.php?code=$uniqueCode' 
                            style='display: inline-block; padding: 10px 20px; color: white; background-color: #dc3545; text-decoration: none; border-radius: 5px;'>
                            Cancel Appointment
                            </a>
                        </p>
                    </div>
                    <div style='text-align: center; background-color: #f1f1f1; color: #666; padding: 10px; font-size: 0.9rem; margin-top: 20px;'>
                        <p style='margin: 0;'>© 2024 Azorpub. All Rights Reserved.</p>
                    </div>
                </div>
            ";

            $mail->send();
            
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Mailer Error: ' . $mail->ErrorInfo]);

            // error_log("Mailer Error: " . $mail->ErrorInfo);
            echo json_encode(['success' => false, 'message' => 'Failed to send email. Please try again.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No appointment found for this email.']);
    }
    exit();
}

?>