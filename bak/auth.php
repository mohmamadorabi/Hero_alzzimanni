<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// التحقق من الصلاحيات
function checkRole($required_role) {
    if ($_SESSION['role'] !== $required_role && $_SESSION['role'] !== 'admin') {
        die("ليس لديك الصلاحيات للوصول إلى هذه الصفحة.");
    }
}
    // اتصال بقاعدة البيانات
    require_once 'config.php'; // تعديل حسب مسار ملف الاتصال بقاعدة البيانات

    // التحقق مما إذا كان المستخدم لا يزال موجودًا في قاعدة البيانات
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        // المستخدم غير موجود، إنهاء الجلسة
        session_destroy();
        header('Location: login.php');
        exit;
    }

    $stmt->close();
?>
