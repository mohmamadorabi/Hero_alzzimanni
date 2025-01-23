<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'config.php';
require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';

if (isset($_GET['code'])) {
    $uniqueCode = $_GET['code'];

    // التحقق من الكود وتحديث الحالة
    $stmt = $conn->prepare("SELECT status, email_sent, name, email, date, time FROM appointments WHERE unique_code = ?");
    $stmt->bind_param("s", $uniqueCode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $appointment = $result->fetch_assoc();

        // التحقق من الحالة
        if ($appointment['status'] === 'Pending') {
            $stmtUpdate = $conn->prepare("UPDATE appointments SET status = 'Confirmed', email_sent = 1 WHERE unique_code = ?");
            $stmtUpdate->bind_param("s", $uniqueCode);
            $stmtUpdate->execute();
        }

        // إرسال البريد الإلكتروني إذا لم يتم إرساله
        if ($appointment['email_sent'] == 0) {
            $name = $appointment['name'];
            $email = $appointment['email'];
            $date = $appointment['date'];
            $time = $appointment['time'];

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtppro.zoho.eu';
                $mail->SMTPAuth = true;
                $mail->Username = 'info@azorpub.be';
                $mail->Password = 'Orabi@@22';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

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
                            <p>Thank you for choosing Azorpub. Your appointment has been successfully confirmed. Below are the details:</p>
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
                            <p style='margin-top: 20px;'>You can use your unique code to <strong>modify</strong> or <strong>cancel</strong> your appointment at any time by clicking the appropriate link below:</p>
                            <p>
                                <a href='https://azorpub.infy.uk/appointment.html?code=$uniqueCode' 
                                style='display: inline-block; padding: 10px 20px; color: white; background-color: #28a745; text-decoration: none; border-radius: 5px; margin-right: 10px;'>
                                Modify Appointment
                                </a>
                                <a href='https://azorpub.infy.uk/cancel_appointment.php?code=$uniqueCode' 
                                style='display: inline-block; padding: 10px 20px; color: white; background-color: #dc3545; text-decoration: none; border-radius: 5px;'>
                                Cancel Appointment
                                </a>
                                <a href='https://azorpub.infy.uk/generate_ics.php?code=$uniqueCode' 
                                style='display: inline-block; padding: 10px 20px; color: white; background-color: #007bff; text-decoration: none; border-radius: 5px; font-size: 16px;'>
                                Add to Calendar
                                </a>
                            </p>
                            <p style='margin-top: 20px;'>If you have any questions, feel free to reach out to us at <a href='mailto:info@azorpub.be'>info@azorpub.be</a>.</p>
                            <p style='margin-top: 20px;'>Thank you for trusting Azorpub. We look forward to serving you!</p>
                        </div>
                        <div style='text-align: center; background-color: #f1f1f1; color: #666; padding: 10px; font-size: 0.9rem; margin-top: 20px;'>
                            <p style='margin: 0;'>© 2024 Azorpub. All Rights Reserved.</p>
                        </div>
                    </div>
                ";

                $mail->send();

                // تحديث حقل email_sent إلى 1
                $stmtUpdateEmail = $conn->prepare("UPDATE appointments SET email_sent = 1 WHERE unique_code = ?");
                $stmtUpdateEmail->bind_param("s", $uniqueCode);
                $stmtUpdateEmail->execute();

                echo "
                    <div style='font-family: Arial, sans-serif; text-align: center; margin-top: 50px;'>
                        <h1 style='color:rgb(27, 182, 53);'>confirmed successfully</h1>
                        <p style='font-size: 16px; color: #333;'>Appointment confirmed and email sent successfully.</p>
                        <a href='index.html' style='display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Return to Home</a>
                    </div>
                    ";
            } catch (Exception $e) {
                echo "<h3>Your appointment has been confirmed, but we couldn't send the email. Please contact support.</h3>";
            }
        } else {
            echo "
            <div style='font-family: Arial, sans-serif; text-align: center; margin-top: 50px;'>
                <h1 style='color:rgb(61, 27, 182);'>Already confirmed </h1>
                <p style='font-size: 16px; color: #333;'>Your appointment has already been confirmed and the email was sent.</p>
                <a href='index.html' style='display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Return to Home</a>
            </div>
            ";
        }
    } else {
        echo "
            <div style='font-family: Arial, sans-serif; text-align: center; margin-top: 50px;'>
                <h1 style='color: #dc3545;'>Invalid or Expired Link</h1>
                <p style='font-size: 16px; color: #333;'>The confirmation link is either invalid or has already been used.</p>
                <a href='index.html' style='display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Return to Home</a>
            </div>
            ";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<h3>No confirmation code provided.</h3>";
}
