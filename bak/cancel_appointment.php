<?php
require_once 'config.php';

// التحقق من وجود الكود في الرابط
if (!isset($_GET['code'])) {
    $status = "error";
    $message = "Invalid Request: No appointment code provided.";
} else {
    $uniqueCode = $_GET['code'];

    // التحقق من الموعد في قاعدة البيانات
    $stmt = $conn->prepare("SELECT * FROM appointments WHERE unique_code = ?");
    $stmt->bind_param("s", $uniqueCode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $status = "error";
        $message = "Invalid Code: No appointment found for the provided code.";
    } else {
        $stmt = $conn->prepare("UPDATE appointments SET status = 'Cancelled' WHERE unique_code = ?");
        $stmt->bind_param("s", $uniqueCode);

        if ($stmt->execute()) {
            $status = "success";
            $message = "Your appointment has been successfully cancelled.";
        } else {
            $status = "error";
            $message = "Failed to cancel the appointment. Please try again later.";
        }
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Cancellation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
            background-color: #f4f4f9;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .success {
            color: #28a745;
        }

        .error {
            color: #dc3545;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="<?php echo $status; ?>">
            <?php echo $status === "success" ? "Appointment Cancelled" : "Error"; ?>
        </h1>
        <p><?php echo $message; ?></p>
        <a href="index.html">Return to Home</a>
    </div>
</body>

</html>
