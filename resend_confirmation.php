<?php
require_once 'config.php';
require_once 'includes/db.php';

// تضمين PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if (isset($_GET['email'])) {
    $email = $_GET['email'];

    // Retrieve the confirmation token
    $sql = "SELECT confirmation_token FROM appointments WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($confirmation_token);
    $stmt->fetch();

    if ($confirmation_token) {
        // Resend the confirmation email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtppro.zoho.eu'; // SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'contact@barber-azzimani.be'; // Your email
            $mail->Password = 'azzimani@@22'; // Your email password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 465;

            $mail->setFrom('contact@barber-azzimani.be', 'Barbershop');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Appointment Confirmation';
            $mail->Body    = "Hello,<br><br>Please click the link below to confirm your appointment:<br>
                              <a href='http://barber-azzimani.be/confirm_booking.php?token=$confirmation_token'>Confirm Appointment</a>";

            $mail->send();
            echo "<div class='message success'>{$lang['resend_success']}</div>";
        } catch (Exception $e) {
            echo "<div class='message error'>Mail could not be sent: {$mail->ErrorInfo}</div>";
        }
    } else {
        echo "<div class='message error'>{$lang['resend_error']}</div>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<div class='message error'>Email not found.</div>";
}
?>