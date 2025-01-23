<?php
require_once 'config.php';
require_once 'includes/db.php';

header('Content-Type: application/json'); // إرجاع الرد كـ JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // جمع البيانات من الطلب
    $data = json_decode(file_get_contents('php://input'), true);
    $email = $data['email'];

    // التحقق من وجود البريد الإلكتروني في قاعدة البيانات
    $sql = "SELECT status FROM appointments WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // إذا كان البريد الإلكتروني موجودًا
        $stmt->bind_result($appointment_status);
        $stmt->fetch();

        echo json_encode([
            'status' => 'exists',
            'appointment_status' => $appointment_status
        ]);
    } else {
        // إذا كان البريد الإلكتروني غير موجود
        echo json_encode([
            'status' => 'available'
        ]);
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['message' => 'الطريقة غير مسموح بها.']);
}
?>