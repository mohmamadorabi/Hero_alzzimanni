<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard mohmed</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="sidebar">
        <h2>Dashboard</h2>
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">Appointments</a></li>
            <li><a href="#">Customers</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="#">Logout</a></li>
        </ul>
    </div>
    <div class="content">
        <header>
            <h1>Welcome, Admin</h1>
        </header>
        <section class="cards">
            <div class="card">
                <h3>Total Appointments</h3>
                <p>150</p>
            </div>
            <div class="card">
                <h3>New Clients</h3>
                <p>45</p>
            </div>
            <div class="card">
                <h3>Pending Appointments</h3>
                <p>20</p>
            </div>
        </section>
    </div>
</body>
</html>
