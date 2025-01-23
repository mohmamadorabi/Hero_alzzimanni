<?php
require_once 'auth.php';
if (!isset($_SESSION['role'])) {
    $_SESSION['role'] = 'viewer'; // تعيين دور افتراضي
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>Responsive Navbar</title> -->
    <link rel="stylesheet" href="dashboard/css/dashboard_styles.css">
    <script src="dashboard\js\navbar.js" defer></script>
    <link rel="stylesheet" href="dashboard/css/navbar.css">
   
</head>
<body>
    <nav class="navbar">
        <div class="logo">AZORPUB</div>
        <div class="menu-toggle" onclick="toggleMenu()">&#9776;</div>
        <div class="menu">
            <a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>">Dashboard</a>
            <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'editor'): ?>
                <a href="add_product.php" class="<?= basename($_SERVER['PHP_SELF']) === 'add_product.php' ? 'active' : '' ?>">Add Product</a>
                <a href="edit_product.php" class="<?= basename($_SERVER['PHP_SELF']) === 'edit_product.php' ? 'active' : '' ?>">Edit Products</a>
                <a href="appointments_dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) === 'appointments_dashboard.php' ? 'active' : '' ?>">Appointments</a>

                <a href="manage_categories.php" class="<?= basename($_SERVER['PHP_SELF']) === 'manage_categories.php' ? 'active' : '' ?>">Manage Categories</a>
            <?php endif; ?>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="manage_users.php" class="<?= basename($_SERVER['PHP_SELF']) === 'manage_users.php' ? 'active' : '' ?>">Manage Users</a>
            <?php endif; ?>
            <a href="logout.php" class="<?= basename($_SERVER['PHP_SELF']) === 'logout.php' ? 'active' : '' ?>">Logout</a>
        </div>
        <div class="theme-toggle" id="themeToggle" onclick="toggleTheme()">
            <i id="themeIcon" class="fas fa-sun"></i>
        </div>
    </nav>

    <!-- <script>
        function toggleMenu() {
            const menu = document.querySelector('.menu');
            menu.classList.toggle('active');
        }

        function toggleTheme() {
            const body = document.body;
            const icon = document.getElementById('themeIcon');
            body.dataset.theme = body.dataset.theme === 'dark' ? 'light' : 'dark';
            icon.classList.toggle('fa-sun');
            icon.classList.toggle('fa-moon');
        }
    </script> -->
</body>
</html>
