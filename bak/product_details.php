<?php
// تفاصيل الاتصال بقاعدة البيانات
$conn = new mysqli('sql113.infinityfree.com', 'if0_37965291', '2DDOcIPdiD', 'if0_37965291_my_website');

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// التحقق من وجود معرف المنتج (ID)
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Product ID is missing.");
}

$product_id = $_GET['id'];

// جلب تفاصيل المنتج بناءً على ID
$product_query = $conn->query("SELECT * FROM products1 WHERE id = $product_id");

if ($product_query->num_rows == 0) {
    die("Product not found.");
}

$product = $product_query->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $product['name'] ?> | Product Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="product_details.css">
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">AZORPUB</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="gallery.html">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" href="product_page.php">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Product Details -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <img src="uploads/<?= $product['image'] ?>" class="img-fluid" alt="<?= $product['name'] ?>">
            </div>
            <div class="col-md-6">
                <h1><?= $product['name'] ?></h1>
                <p><strong>Price:</strong> $<?= $product['price'] ?></p>
                <p><strong>Description:</strong> <?= $product['description'] ?></p>
                <a href="product_page.php" class="btn btn-primary mt-3">Back to Products</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// إغلاق الاتصال بقاعدة البيانات
$conn->close();
?>
