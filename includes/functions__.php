<?php
// دالة لتحميل الأوقات المتاحة بناءً على التاريخ
function get_available_times($date) {
    global $conn;

    // استعلام لاسترداد الأوقات المحجوزة في التاريخ المحدد
    $sql = "SELECT time FROM appointments WHERE date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();

    // قائمة بالأوقات المحجوزة
    $booked_times = [];
    while ($row = $result->fetch_assoc()) {
        $booked_times[] = $row['time'];
    }

    // قائمة بالأوقات المتاحة (من 9 صباحًا إلى 6 مساءً)
    $all_times = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'];

    // استبعاد الأوقات المحجوزة
    $available_times = array_diff($all_times, $booked_times);

    return $available_times;
}
?>