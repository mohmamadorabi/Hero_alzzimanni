<?php
require_once 'config.php';

header('Content-Type: application/json');

// التحقق من طريقة الطلب POST للتحقق من البريد الإلكتروني
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['email'])) {
        $email = $data['email'];

        $sql = "SELECT status FROM appointments WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($appointment_status);
            $stmt->fetch();
            echo json_encode(['status' => 'exists', 'appointment_status' => $appointment_status]);
        } else {
            echo json_encode(['status' => 'available']);
        }

        $stmt->close();
        $conn->close();
        exit;
    }

    echo json_encode(['error' => 'Invalid email input']);
    exit;
}

// التحقق من طريقة الطلب GET والتحقق من توفر الأوقات
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // تحقق من طلب جلب الأوقات المتاحة
    if (isset($_GET['date']) && !isset($_GET['action'])) {
        $date = $_GET['date'];

        // جميع الأوقات المتاحة
        $all_times = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'];

        // جلب الأوقات المحجوزة من قاعدة البيانات
        $sql = "SELECT time FROM appointments WHERE date = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $date);
        $stmt->execute();
        $result = $stmt->get_result();

        $booked_times = [];
        while ($row = $result->fetch_assoc()) {
            $booked_times[] = date('H:i', strtotime($row['time']));
        }
        $stmt->close();

        // استبعاد الأوقات المحجوزة من القائمة المتاحة
        $available_times = array_values(array_diff($all_times, $booked_times));

        echo json_encode(!empty($available_times) ? $available_times : ["No available times"]);

        $conn->close();
        exit;
    }

    // تحقق من طلب التحقق من وقت محدد
    if (isset($_GET['action']) && $_GET['action'] === 'check_time' && isset($_GET['date']) && isset($_GET['time'])) {
        $date = $_GET['date'];
        $time = $_GET['time'];

        // تحويل الوقت إلى التنسيق الصحيح HH:MM:SS
        $formatted_time = date('H:i:s', strtotime($time));

        $sql = "SELECT COUNT(*) AS count FROM appointments WHERE date = ? AND time = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $date, $formatted_time);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();

        if ($count == 0) {
            echo json_encode(['available' => true]);  // الوقت متاح
        } else {
            echo json_encode(['available' => false]); // الوقت محجوز
        }

        $stmt->close();
        $conn->close();
        exit;
    }
}

// إذا لم يتم استيفاء أي شرط، أرجع الخطأ 405
http_response_code(405);
echo json_encode(['error' => 'Method Not Allowed']);
exit;
?>
