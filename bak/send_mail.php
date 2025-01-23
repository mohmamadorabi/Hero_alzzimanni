<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// تضمين ملفات مكتبة PHPMailer
require_once __DIR__ . '/../libs/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../libs/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../libs/PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipientEmail = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? 'No Subject';
    $message = $_POST['message'] ?? 'No Message';

    if (empty($recipientEmail) || empty($message)) {
        echo json_encode(['success' => false, 'message' => 'Email and message are required.']);
        exit();
    }

    $mail = new PHPMailer(true);

    try {
        // إعدادات SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.zoho.com'; // Zoho SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'info@azorpub.be'; // بريد Zoho الخاص بك
        $mail->Password = 'Orabi@@22'; // كلمة مرور Zoho
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // أو PHPMailer::ENCRYPTION_STARTTLS إذا كنت تستخدم TLS
        $mail->Port = 465; // 465 مع SSL أو 587 مع TLS

        // إعدادات البريد المرسل
        $mail->setFrom('info@azorpub.com', 'Azorpub Info'); // عنوان البريد المرسل
        $mail->addAddress($recipientEmail); // عنوان البريد المستلم
        $mail->addReplyTo('info@azorpub.com', 'Azorpub Info');

        // محتوى الرسالة
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        // إرسال البريد
        $mail->send();
        echo json_encode(['success' => true, 'message' => 'Email sent successfully.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => "Mailer Error: {$mail->ErrorInfo}"]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
