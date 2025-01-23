<?php
require_once 'config.php';
// require_once 'includes/db.php';

// تضمين PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';

header('Content-Type: application/json'); // إرجاع الرد كـ JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $service = $_POST['service'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $notes = $_POST['notes'];

    // Check if email already exists
    $check_email_sql = "SELECT id FROM appointments WHERE email = ?";
    $stmt = $conn->prepare($check_email_sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // إذا كان البريد الإلكتروني مستخدمًا بالفعل
        echo json_encode(['status' => 'error', 'message' => 'Email is already in use. Please use another email.']);
    } else {
        // Generate confirmation token
        $confirmation_token = bin2hex(random_bytes(32));

        // Insert data into the database
        $insert_sql = "INSERT INTO appointments (name, email, phone, service_type, date, time, notes, status, confirmation_token) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("ssssssss", $name, $email, $phone, $service, $date, $time, $notes, $confirmation_token);

        if ($stmt->execute()) {
            // echo json_encode([
            //     'status' => 'success', 
            //     'message' => 'Your appointment has been successfully booked! Please check your email to confirm your appointment.'
            // ]);
            // Send confirmation email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'azzimani.shop@gmail.com'; // Your email
                $mail->Password = 'vuts dwlm qrqk exgv'; // Your email password
                $mail->SMTPSecure = 'PHPMailer::ENCRYPTION_STARTTLS';
                $mail->Port = 587;

                $mail->setFrom('azzimani.shop@gmail.com', 'Barbershop');
                $mail->addAddress($email, $name); // Client's email

                $mail->isHTML(true);
                $mail->Subject = 'Your Appointment Confirmation - Barbershop Azzimani';

                $mail->Body = "
                <!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Appointment Confirmation</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f4f4f4;
                            margin: 0;
                            padding: 20px;
                            text-align: center;
                        }
                        .email-container {
                            max-width: 600px;
                            margin: auto;
                            background: #ffffff;
                            padding: 20px;
                            border-radius: 10px;
                            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                        }
                        .email-header {
                            background: #007bff;
                            padding: 20px;
                            color: #ffffff;
                            border-radius: 10px 10px 0 0;
                            font-size: 24px;
                            font-weight: bold;
                        }
                        .email-content {
                            padding: 20px;
                            color: #333333;
                            line-height: 1.8;
                            font-size: 18px;
                            text-align: left;
                        }
                        .email-button {
                            display: inline-block;
                            padding: 15px 30px;
                            background: #007bff;
                            color: #ffffff;
                            text-decoration: none;
                            font-size: 20px;
                            border-radius: 5px;
                            margin-top: 20px;
                        }
                        .email-footer {
                            margin-top: 20px;
                            font-size: 14px;
                            color: #777777;
                            text-align: center;
                        }
                        .details {
                            background: #f9f9f9;
                            padding: 15px;
                            border-left: 5px solid #007bff;
                            border-radius: 5px;
                            margin-top: 20px;
                        }
                    </style>
                </head>
                <body>
                    <div class='email-container'>
                        <div class='email-header'>Barbershop Azzimani</div>
                        <div class='email-content'>
                            <p>Dear <strong>$name</strong>,</p>
                            <p>Thank you for scheduling your appointment with us. Below are your appointment details:</p>

                            <div class='details'>
                                <p><strong>Client Name:</strong> $name</p>
                                <p><strong>Phone Number:</strong> $phone</p>
                                <p><strong>Service:</strong> $service</p>
                                <p><strong>Date:</strong> $date</p>
                                <p><strong>Time:</strong> $time</p>
                                <p><strong>Additional Notes:</strong> " . (!empty($notes) ? $notes : 'No additional notes') . "</p>
                            </div>

                            <p>Please confirm your appointment by clicking the button below:</p>
                            <a href='http://www.barber-azzimani.be/confirm_booking.php?token=$confirmation_token' class='email-button'>Confirm Appointment</a>

                            <p>If you have any questions, feel free to contact us.</p>
                        </div>
                        <div class='email-footer'>
                            &copy; " . date("Y") . " Barbershop Azzimani. All Rights Reserved.
                        </div>
                    </div>
                </body>
                </html>";

                
                $mail->send();


                echo json_encode(['status' => 'success', 'message' => 'Appointment booked successfully! Please check your email to confirm.']);

                // echo "<div class='message success'>'Appointment booked successfully! A confirmation email has been sent.'</div>";
            } catch (Exception $e) {

                echo json_encode(['status' => 'error', 'message' => 'Failed to send email: ' . $mail->ErrorInfo]);

                // echo "<div class='message error'>Mail could not be sent: {$mail->ErrorInfo}</div>";
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'An error occurred while booking: ' . $stmt->error]);
            // echo "<div class='message error'>{$lang['booking_error']} {$stmt->error}</div>";
        }
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>