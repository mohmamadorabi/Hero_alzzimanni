<?php
session_start();
if ($_POST) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($username === 'admin' && $password === '12345') { // بيانات تسجيل بسيطة
        $_SESSION['logged_in'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>
