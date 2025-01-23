<?php
require_once 'config.php';

date_default_timezone_set('Europe/Brussels'); // تأكد من استخدام التوقيت الصحيح

$current_time = date('Y-m-d H:i:s');

$sql = "DELETE FROM appointments WHERE status = 'pending' AND temp_hold_expires <= ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $current_time);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    // echo json_encode(['status' => 'success', 'message' => 'Expired appointments cleaned.', 'deleted' => $stmt->affected_rows]);
} else {
    // echo json_encode(['status' => 'success', 'message' => 'No expired appointments found.']);
}

// $stmt->close();
// $conn->close();


?>
