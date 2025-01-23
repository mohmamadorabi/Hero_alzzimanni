<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

if ($_POST) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];

    // رفع الصورة إلى مجلد "uploads"
    move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image);

    // الاتصال بقاعدة البيانات
    $conn = new mysqli('localhost', 'your_username', 'your_password', 'my_website');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // إدخال البيانات في الجدول
    $sql = "INSERT INTO products (name, description, image) VALUES ('$name', '$description', '$image')";
    if ($conn->query($sql) === TRUE) {
        echo "Product added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
</head>
<body>
    <h1>Add Product</h1>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Product Name" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <input type="file" name="image" required>
        <button type="submit">Add Product</button>
    </form>
</body>
</html>
