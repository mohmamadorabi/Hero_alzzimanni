<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <ul>
        <li><a href="add_product.php">Add Product</a></li>
        <li><a href="edit_product.php">Edit/Delete Product</a></li>
    </ul>
    <a href="logout.php">Logout</a>
</body>
</html>
