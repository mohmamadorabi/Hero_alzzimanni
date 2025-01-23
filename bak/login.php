<?php
session_start();
require_once 'config.php';

$error = ''; // To store error messages

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Handle login request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate user credentials
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            session_regenerate_id(true); // Prevent session hijacking
            $_SESSION['user_id'] = $id;
            $_SESSION['role'] = $role; // Store user role
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Username does not exist.";
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | AZORPUB Team</title>
    <link rel="icon" type="image/png" href="images/icons/logo 229 X 127.png">
    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/b8057c684d.js" crossorigin="anonymous"></script>
    <!-- Styles -->
    <style>
        :root {
            --bg-color: #ffffff;
            --text-color: #000000;
            --input-bg: #f0f0f0;
            --border-color: #ddd;
            --highlight-color: #007bff;
            --button-bg: #007bff;
            --button-text: #ffffff;
            --logo-color: #007bff;
        }

        [data-theme="dark"] {
            --bg-color: #121212;
            --text-color: #ffffff;
            --input-bg: #1e1e1e;
            --border-color: #444;
            --highlight-color: #1e90ff;
            --button-bg: #1e90ff;
            --button-text: #000000;
            --logo-color: #1e90ff;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            transition: background-color 0.3s, color 0.3s;
        }

        .container {
            width: 100%;
            max-width: 450px;
            margin: 50px auto;
            padding: 20px;
            background-color: var(--input-bg);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header img {
            max-width: 120px;
            margin-bottom: 10px;
        }

        .header h1 {
            margin: 10px 0;
            font-size: 24px;
            color: var(--logo-color);
        }

        .form-label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
            font-size: 14px;
        }
        .form-control {
            width: calc(100% - 20px); /* لضمان وجود مسافة من الطرفين */
            padding: 10px;
            margin: 10px 10px 20px 10px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            background-color: var(--input-bg);
            color: var(--text-color);
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: var(--highlight-color);
            outline: none;
            box-shadow: 0 0 5px var(--highlight-color);
        }
        .alert {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            color: #fff;
            background-color: #e74c3c;
            text-align: center;
        }

        .btn-primary {
            display: block;
            width: calc(100% - 20px); /* لضمان وجود مسافة من الطرفين */
            padding: 12px;
            margin: 10px 10px 0 10px;
            background-color: var(--button-bg);
            color: var(--button-text);
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: var(--highlight-color);
        }

        .text-center {
            text-align: center;
        }
        @media (max-width: 480px) {
            .container {
                max-width: 90%;
                padding: 15px;
            }

            .form-control,
            .btn-primary {
                width: calc(100% - 20px);
                margin: 10px 10px 20px 10px;
            }
        }
    </style>
</head>
<body data-theme="light">
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <img src="images/icons/logo 229 X 127.png" alt="AZORPUB Logo">
            <h1>AZORPUB Team</h1>
        </div>

        <!-- Error Message -->
        <?php if (!empty($error)): ?>
            <div class="alert"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="login.php" method="POST">
            <div>
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div>
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn-primary">Login</button>
            </div>
        </form>
    </div>

    <script>
        // Automatically adjust theme based on user preference
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    </script>
</body>
</html>
