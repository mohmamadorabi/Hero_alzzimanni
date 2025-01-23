<?php
require_once 'config.php';

$response = ['success' => false, 'message' => '', 'user' => null];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // التحقق من وجود اسم المستخدم
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $response['message'] = 'Username already exists.';
    } else {
        // إضافة المستخدم
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashed_password, $role);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['user'] = [
                'id' => $stmt->insert_id,
                'username' => $username,
                'role' => $role
            ];
        } else {
            $response['message'] = 'Failed to add user.';
        }
    }

    $stmt->close();
}

echo json_encode($response);
$conn->close();
?>
