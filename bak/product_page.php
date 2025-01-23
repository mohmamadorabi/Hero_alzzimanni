<?php
// استدعاء ملف الإعدادات
require_once 'config.php';

// جلب الفئات من قاعدة البيانات
$category_result = $conn->query("SELECT id, name FROM categories");

// إذا تم تحديد فئة، جلب المنتجات الخاصة بها
$category_filter = isset($_GET['category']) ? intval($_GET['category']) : 0;

// جلب المنتجات بناءً على الفئة المحددة
if ($category_filter > 0) {
    $product_result = $conn->query("SELECT * FROM products1 WHERE category_id = $category_filter");
} else {
    $product_result = $conn->query("SELECT * FROM products1");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products | AZORPUB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="product_page.css">
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">AZORPUB</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="gallery.html">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link active" href="product_page.php">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
                </ul>
                <div class="language-switcher ms-3">
                    <a href="?lang=en">EN</a> | <a href="?lang=ar">AR</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2 col-md-3 sidebar">
                <h4>Categories</h4>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="?category=0" class="nav-link <?= !$category_filter ? 'active' : '' ?>">All</a>
                    </li>
                    <?php while ($category = $category_result->fetch_assoc()): ?>
                        <li class="nav-item">
                            <a href="?category=<?= $category['id'] ?>" class="nav-link <?= $category_filter == $category['id'] ? 'active' : '' ?>">
                                <?= htmlspecialchars($category['name']) ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>

            <!-- Products -->
            <div class="col-lg-10 col-md-9">
                <div class="container mt-4">
                    <div class="row">
                        <?php if ($product_result && $product_result->num_rows > 0): ?>
                            <?php while ($product = $product_result->fetch_assoc()): ?>
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                    <div class="card product-card">
                                        <img src="uploads/<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                            <p class="card-text"><?= htmlspecialchars(substr($product['description'], 0, 50)) ?>...</p>
                                            <p class="card-text"><strong>Price: $<?= htmlspecialchars($product['price']) ?></strong></p>
                                            <a href="product_details.php?id=<?= $product['id'] ?>" class="btn btn-outline-primary btn-sm">More Info</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-center">No products found in this category.</p>
                        <?php endif; ?>
                    </div>
                </div>
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
