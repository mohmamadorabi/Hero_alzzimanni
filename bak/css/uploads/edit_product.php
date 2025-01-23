<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

$conn = new mysqli('localhost', 'your_username', 'your_password', 'my_website');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_GET && isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM products WHERE id=$id");
    echo "Product deleted successfully!";
}

$result = $conn->query("SELECT * FROM products");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit/Delete Product</title>
</head>
<body>
    <h1>Edit/Delete Products</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= $row['description'] ?></td>
                <td><img src="uploads/<?= $row['image'] ?>" width="50"></td>
                <td><a href="?delete=<?= $row['id'] ?>">Delete</a></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
