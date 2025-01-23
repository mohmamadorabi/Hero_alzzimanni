<?php
require_once 'config.php';
require_once 'cleanup_appointments.php';
require_once __DIR__ . '/phpqrcode/qrlib.php';
date_default_timezone_set('Europe/Brussels'); // تأكد من استخدام التوقيت الصحيح
// الكود الخاص بحجز الموعد والتحقق
// تضمين PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';
header('Content-Type: application/json');

// تحديد الإجراء المطلوب بناءً على action
$input = json_decode(file_get_contents('php://input'), true);
$action = isset($input['action']) ? $input['action'] : '';

switch ($action) {
    case 'check_email':
        checkEmail($conn, $input);
        break;

    case 'hold_time':
        holdTime($conn, $input);
        break;

    case 'get_available_times':
        getAvailableTimes($conn, $input);
        break;

    case 'verify_code':
        verifyCode($conn);
        break;
    case 'send_confirmation_code':
        sendConfirmationCode($conn);
        break;
        case 'check_existing_appointment':
            checkExistingAppointment($conn, $input);
            break;
        
        case 'resend_confirmation_code':
            resendConfirmationCode($conn, $input);
            break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Invalid action']);
        exit;
}

// التحقق من البريد الإلكتروني
function checkEmail($conn, $input) {
    if (isset($input['email'])) {
        $email = $input['email'];
        $sql = "SELECT status FROM appointments WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $status=0;
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($status);
            $stmt->fetch();
            echo json_encode(['status' => 'exists', 'appointment_status' => $status]);
        } else {
            echo json_encode(['status' => 'available']);
        }
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Email not provided']);
    }
}

// حجز الوقت مؤقتًا
function holdTime($conn, $input) {
    if (isset($input['date'], $input['time'], $input['email'])) {
        $date = $input['date'];
        $time = $input['time'];
        $email = $input['email'];
        $expire_time = date('Y-m-d H:i:s', strtotime('+5 minutes'));
        
        $sql = "INSERT INTO appointments (email, date, time, status, temp_hold_expires) 
                VALUES (?, ?, ?, 'temporary', ?) 
                ON DUPLICATE KEY UPDATE temp_hold_expires = VALUES(temp_hold_expires)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $email, $date, $time, $expire_time);
        $stmt->execute();
        
        echo json_encode(['status' => 'success', 'message' => 'Time is temporarily reserved.']);
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Invalid input']);
    }
}

// جلب الأوقات المتاحة
function getAvailableTimes($conn, $input) {
    if (isset($input['date'])) {
        $date = $input['date'];
        $all_times = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'];

        $sql = "SELECT time FROM appointments WHERE date = ? AND (status = 'confirmed' OR temp_hold_expires > NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $date);
        $stmt->execute();
        $result = $stmt->get_result();

        $booked_times = [];
        while ($row = $result->fetch_assoc()) {
            $booked_times[] = date('H:i', strtotime($row['time']));
        }
        $stmt->close();

        $available_times = array_values(array_diff($all_times, $booked_times));
        echo json_encode(!empty($available_times) ? $available_times : ["No available times"]);
    } else {
        echo json_encode(['error' => 'Date not provided']);
    }
}

function verifyCode($conn) {
    header('Content-Type: application/json');

    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['code'])) {
        echo json_encode(['status' => 'error', 'message' => 'Confirmation code is required.']);
        exit;
    }

    $confirmation_code = $data['code'];

    // التحقق مما إذا كان الكود صحيحًا ولم تنتهي صلاحيته
    $sql = "SELECT id, name, email, phone, service_type, date, time FROM appointments WHERE confirmation_code = ? AND temp_hold_expires > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $confirmation_code);
    $stmt->execute();
    $stmt->store_result();
    $appointment_id=0; $name=0; $email=0; $phone=0; $service=0; $date=0; $time=0;
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($appointment_id, $name, $email, $phone, $service, $date, $time);
        $stmt->fetch();

        // توليد باركود فريد باستخدام المعلومات الأساسية
        $barcodeData = "Appointment ID: $appointment_id | Name: $name | Date: $date | Time: $time";
        $barcode = generateQRCode($barcodeData);

        // تحديث حالة الموعد إلى "مؤكد" وحفظ الباركود في قاعدة البيانات
        $update_sql = "UPDATE appointments SET status = 'confirmed', confirmation_code = NULL, barcode = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $barcode, $appointment_id);
        $update_stmt->execute();

        // إرسال البريد الإلكتروني مع الباركود
        sendConfirmationEmail($email, $name, $phone, $service, $date, $time, $barcode);

        echo json_encode(['status' => 'success', 'message' => 'Appointment confirmed successfully. Check your email for details.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid or expired confirmation code.']);
    }

    $stmt->close();
    $conn->close();
}
function sendConfirmationEmail($email, $name, $phone, $service, $date, $time) {
    require_once 'PHPMailer/src/PHPMailer.php';
    require_once 'PHPMailer/src/SMTP.php';
    require_once 'PHPMailer/src/Exception.php';

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'azzimani.shop@gmail.com';
        $mail->Password = 'vuts dwlm qrqk exgv';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('azzimani.shop@gmail.com', 'Barbershop');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Appointment Confirmation with QR Code';

        // توليد الباركود وإضافته في البريد الإلكتروني
        $qrData = "Name: $name, Service: $service, Date: $date, Time: $time";
        $barcodeBase64 = generateQRCode($qrData);
        // echo "<img src='data:image/png;base64,{$barcodeBase64}' alt='QR Code'>";
        $mail->addStringAttachment(base64_decode($barcodeBase64), "appointment_qr.png", "base64", "image/png");

        $mail->Body = "
            <h3>Hello $name,</h3>
            <p>Your appointment has been confirmed with the following details:</p>
            <ul>
                <li><strong>Name:</strong> $name</li>
                <li><strong>Email:</strong> $email</li>
                <li><strong>Phone:</strong> $phone</li>
                <li><strong>Service:</strong> $service</li>
                <li><strong>Date:</strong> $date</li>
                <li><strong>Time:</strong> $time</li>
            </ul>
            
             <p>Please present this QR code at the time of your appointment:</p>
            <img src='data:image/png;base64,{$barcodeBase64}' alt='Appointment QR Code' style='width:200px;height:200px;'>
            <p>Thank you for choosing us!</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email sending failed: " . $mail->ErrorInfo);
        return false;
    }
}





function sendConfirmationCode($conn) {
    header('Content-Type: application/json');

    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['email']) || !isset($data['date']) || !isset($data['time']) || 
        !isset($data['name']) || !isset($data['phone']) || !isset($data['service'])) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    $email = $data['email'];
    $date = $data['date'];
    $time = date('H:i:s', strtotime($data['time']));
    $name = $data['name'];
    $phone = $data['phone'];
    $service = $data['service'];
     // التحقق من توفر البريد الإلكتروني
     $emailCount=0;
     $sql = "SELECT COUNT(*) FROM appointments WHERE email = ?";
     $stmt = $conn->prepare($sql);
     $stmt->bind_param("s", $email);
     $stmt->execute();
     $stmt->bind_result($emailCount);
     $stmt->fetch();
     $stmt->close();
 
     if ($emailCount > 0) {
         echo json_encode(['status' => 'error', 'message' => 'This email is already registered.']);
         exit;
     }
 
     // التحقق مما إذا كان الوقت متاحًا
     $timeCount=0;
     $sql = "SELECT COUNT(*) FROM appointments WHERE date = ? AND time = ? AND (status = 'confirmed' OR temp_hold_expires > NOW())";
     $stmt = $conn->prepare($sql);
     $stmt->bind_param("ss", $date, $time);
     $stmt->execute();
     $stmt->bind_result($timeCount);
     $stmt->fetch();
     $stmt->close();
 
     if ($timeCount > 0) {
         echo json_encode(['status' => 'error', 'message' => 'The selected time is no longer available.']);
         exit;
     }
    // توليد كود التحقق
    $confirmation_code = rand(100000, 999999);
    $expire_time = date('Y-m-d H:i:s', strtotime('+5 minutes'));

    // تحديث أو إدخال البيانات الجديدة
    $sql = "INSERT INTO appointments (name, email, phone, service_type, date, time, status, temp_hold_expires, confirmation_code) 
            VALUES (?, ?, ?, ?, ?, ?, 'pending', ?, ?)
            ON DUPLICATE KEY UPDATE confirmation_code = VALUES(confirmation_code), temp_hold_expires = VALUES(temp_hold_expires)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $name, $email, $phone, $service, $date, $time, $expire_time, $confirmation_code);

    if ($stmt->execute()) {
        sendEmailWithCode($email, $confirmation_code);
        echo json_encode(['status' => 'success', 'message' => 'Confirmation code sent successfully.']);
        logBookingAttempt($conn, $name, $email, $phone, $service, $date, $time, 'success');

    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save the appointment.']);
    }

    $stmt->close();
}
function generateQRCode($data) {
    ob_start(); // بدء التخزين المؤقت
    QRcode::png($data, null, QR_ECLEVEL_L, 6); // توليد الباركود
    $imageData = ob_get_contents(); // جلب محتوى الصورة
    ob_end_clean(); // إنهاء التخزين المؤقت
    return base64_encode($imageData);
}
function checkExistingAppointment($conn, $input) {
    header('Content-Type: application/json');

    if (!isset($input['email']) || !isset($input['date']) || !isset($input['time'])) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
        exit;
    }

    $email = $input['email'];
    $date = $input['date'];
    $time = date('H:i:s', strtotime($input['time']));
    $count=0;
    $sql = "SELECT COUNT(*) FROM appointments WHERE email = ? AND date = ? AND time = ? AND temp_hold_expires > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $email, $date, $time);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo json_encode(['status' => 'exists']);
    } else {
        echo json_encode(['status' => 'not_found']);
    }
}
function resendConfirmationCode($conn, $input) {
    header('Content-Type: application/json');

    if (!isset($input['email']) || !isset($input['date']) || !isset($input['time'])) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
        exit;
    }

    $email = $input['email'];
    $date = $input['date'];
    $time = date('H:i:s', strtotime($input['time']));
    $confirmation_code = rand(100000, 999999);
    $expire_time = date('Y-m-d H:i:s', strtotime('+5 minutes'));

    // تحديث كود التأكيد في القاعدة
    $sql = "UPDATE appointments SET confirmation_code = ?, temp_hold_expires = ? WHERE email = ? AND date = ? AND time = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $confirmation_code, $expire_time, $email, $date, $time);

    if ($stmt->execute()) {
        sendEmailWithCode($email, $confirmation_code);
        echo json_encode(['status' => 'success', 'message' => 'Confirmation code resent successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to resend confirmation code.']);
    }

    $stmt->close();
}

function sendEmailWithCode($email, $code) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'azzimani.shop@gmail.com';
        $mail->Password = 'vuts dwlm qrqk exgv';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('azzimani.shop@gmail.com', 'Barbershop');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Your Confirmation Code';
        $mail->Body    = "Your confirmation code is: <b>$code</b>";

        $mail->send();
    } catch (Exception $e) {
        echo json_encode(['status' => 'success', 'message' => 'Confirmation code sent successfully.']);
        echo json_encode(['status' => 'error', 'message' => 'Failed to send confirmation email.']);
    }
}

function logBookingAttempt($conn, $name, $email, $phone, $service, $date, $time, $status) {
    $sql = "INSERT INTO booking_attempts (name, email, phone, service, date, time, status, attempt_time) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $name, $email, $phone, $service, $date, $time, $status);
    $stmt->execute();
    $stmt->close();
}

?>
