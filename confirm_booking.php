<?php
require_once 'config.php';

$confirmation_message = "";

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // التحقق من صحة التوكن
    $sql = "SELECT id FROM appointments WHERE confirmation_token = ? AND status = 'pending'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // تحديث الحالة إلى "مؤكد"
        $update_sql = "UPDATE appointments SET status = 'confirmed' WHERE confirmation_token = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("s", $token);
        $update_stmt->execute();
        $confirmation_message = "<div class='success-message'>تم تأكيد الحجز بنجاح. شكرًا لك!</div>";
    } else {
        $confirmation_message = "<div class='error-message'>التوكن غير صالح أو تم تأكيده مسبقًا.</div>";
    }

    $stmt->close();
    $conn->close();
} else {
    $confirmation_message = "<div class='error-message'>لم يتم العثور على رمز التأكيد.</div>";
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تأكيد الحجز</title>
    <style>
        /* تنسيق عام للصفحة */
body {
    font-family: Arial, sans-serif;
    background: linear-gradient(to right, #007bff, #00c6ff);
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
    margin: 0;
}

/* تنسيق الحاوية الرئيسية */
.confirmation-container {
    text-align: center;
    max-width: 400px;
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* عنوان الصفحة */
.confirmation-box h1 {
    color: #333;
    font-size: 24px;
    margin-bottom: 20px;
}

/* رسائل النجاح */
.success-message {
    color: #28a745;
    font-size: 18px;
    background: #e9f7ef;
    padding: 15px;
    border-radius: 5px;
    border: 1px solid #28a745;
    margin-bottom: 20px;
}

/* رسائل الخطأ */
.error-message {
    color: #dc3545;
    font-size: 18px;
    background: #f8d7da;
    padding: 15px;
    border-radius: 5px;
    border: 1px solid #dc3545;
    margin-bottom: 20px;
}

/* زر العودة */
.back-button {
    display: inline-block;
    text-decoration: none;
    background: #007bff;
    color: #fff;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 18px;
    transition: 0.3s;
}

.back-button:hover {
    background: #0056b3;
}

    </style>
</head>
<body>
    <div class="confirmation-container">
        <div class="confirmation-box">
            <h1>تأكيد الحجز</h1>
            <?php echo $confirmation_message; ?>
            <a href="index.php" class="back-button">العودة إلى الصفحة الرئيسية</a>
        </div>
    </div>
</body>
</html>
