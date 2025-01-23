<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once 'config.php';
    include 'navbar.php';

    // التحقق من تسجيل الدخول
    require_once 'auth.php'; // التحقق من الجلسة

    // التحقق من وجود معرف المنتج في الرابط
    if (!isset($_GET['id'])) {
        header("Location: edit_product.php");
        exit();
    }

    $product_id = intval($_GET['id']);

    // جلب بيانات المنتج من قاعدة البيانات
    $stmt = $conn->prepare("SELECT * FROM products1 WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    // إعداد القيم الافتراضية إذا كانت الحقول فارغة
    $name = !empty($product['name']) ? $product['name'] : '';
    $description = !empty($product['description']) ? $product['description'] : '';
    $category_id = isset($product['category_id']) ? $product['category_id'] : 0;
    $price = isset($product['price']) ? $product['price'] : 0.00;
    $quantity = isset($product['quantity']) ? $product['quantity'] : 0;
    $image = !empty($product['image']) ? $product['image'] : 'default.jpg';

    if (!$product) {
        echo "<div class='container mt-5'><div class='alert alert-danger'>Product not found.</div></div>";
        exit();
    }

    // جلب الفئات من قاعدة البيانات
    $categories = $conn->query("SELECT id, name FROM categories");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product | AZORPUB</title>

    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/b8057c684d.js" crossorigin="anonymous"></script>

    <script src="dashboard\js\navbar.js" defer></script>
    <link rel="stylesheet" href="dashboard/css/navbar.css">


    <style>
    /* إعداد الألوان للوضع الفاتح والمظلم */
    :root {
        --bg-color: #ffffff;
        --text-color: #000000;
        --input-bg: #f9f9f9;
        --border-color: #ddd;
        --highlight-color: #007bff;
        --button-bg: #007bff;
        --button-text: #ffffff;
    }

    [data-theme="dark"] {
        --bg-color: #121212;
        --text-color: #ffffff;
        --input-bg: #1e1e1e;
        --border-color: #444;
        --highlight-color: #1e90ff;
        --button-bg: #1e90ff;
        --button-text: #000000;
    }

    /* إعداد الصفحة العامة */
    body {
        background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color 0.3s, color 0.3s;
    }

    .container {
        align-items: center; /* توسيط العناصر عموديًا */
        justify-content: flex-start; /* توسيط العناصر أفقيًا */
        margin-left: 20px;
        margin-right: 20px;
        width: 100%;
        max-width: 800px;
        margin: 20px auto;
    }

    h1 {
        text-align: center;
        font-size: 24px;
        margin-bottom: 20px;
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 8px;
    }

    input, textarea, select {
        width: 100%;
        /* max-width: 700px; */
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        background-color: var(--input-bg);
        color: var(--text-color);
        transition: border-color 0.3s, background-color 0.3s, color 0.3s;
    }

    input:focus, textarea:focus, select:focus {
        border-color: var(--highlight-color);
        outline: none;
        box-shadow: 0 0 5px var(--highlight-color);
    }

    button {
        background-color: var(--button-bg);
        color: var(--button-text);
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s, color 0.3s;
    }

    button:hover {
        background-color: var(--highlight-color);
        color: var(--text-color);
    }

    img {
        max-width: 100%;
        border-radius: 4px;
        margin-top: 10px;
    }

    .text-muted {
        font-size: 14px;
        color: var(--text-color);
    }
/* تنسيق الحاوية المحيطة */
.custom-file-input {
        position: relative;
        display: inline-block;
        width: 100%;
        max-width: 500px; /* عرض أقصى للحقل */
        padding: 10px;
        font-size: 16px;
        color: var(--text-color);
        border: 1px solid var(--border-color);
        border-radius: 5px;
        background-color: var(--input-bg);
        cursor: pointer;
        transition: all 0.3s ease-in-out;
    }

    /* عند التركيز */
    .custom-file-input:focus {
        outline: none;
        border-color: var(--highlight-color);
        box-shadow: 0 0 5px var(--highlight-color);
    }

    /* تحسين النص داخل الزر */
    .custom-file-input::file-selector-button {
        background-color: var(--button-bg);
        color: var(--button-text);
        padding: 8px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s, color 0.3s;
    }

    /* عند التحويم على الزر */
    .custom-file-input::file-selector-button:hover {
        background-color: var(--highlight-color);
        color: var(--text-color);
    }
    /* تصميم الوضع المتجاوب */
    @media (max-width: 768px) {
        .container {
            margin: 20px;
            padding: 15px;
        }

        h1 {
            font-size: 20px;
        }

        input, textarea, select {
            padding: 8px;
        }

        button {
            padding: 8px 15px;
        }
    }
    </style>

</head>
<body data-theme="light">
    <div class="container">
        <h1 class="text-center">Edit Product</h1>
        <div class="row justify-content-center">
            <form action="process_product.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="product_id" value="<?= $product_id; ?>">
                
                <div class="mb-3">
                    <label for="name" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($name); ?>" required>

                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" required><?= htmlspecialchars($description); ?></textarea>

                </div>
                <div class="mb-3">
                    <label for="category_id" class="form-label">Category</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="0" <?= $category_id == 0 ? 'selected' : ''; ?>>No Category</option>
                        <?php while ($category = $categories->fetch_assoc()): ?>
                            <option value="<?= $category['id']; ?>" <?= $category['id'] == $category_id ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>                

                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price" step="0.01" value="<?= htmlspecialchars($price); ?>"lang="en" required>

                </div>

                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" value="<?= htmlspecialchars($quantity); ?>"lang="en" required>

                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" class="form-control custom-file-input" id="image" name="image" accept="image/*">
                    
                    <!-- مكان عرض الصورة -->
                    <div id="image-preview" class="mt-3">
                        <?php if (!empty($image) && $image != 'default.jpg'): ?>
                            <img src="uploads/<?= htmlspecialchars($image); ?>" alt="Current Image" class="img-thumbnail" style="max-width: 150px;">
                            <p class="text-muted">Current Image: <?= htmlspecialchars($image); ?></p>
                        <?php else: ?>
                            <img src="uploads/default-image_450.png" alt="Default Image" class="img-thumbnail" style="max-width: 150px;">
                            <p class="text-muted">No Image Available</p>
                        <?php endif; ?>
                    </div>
                </div>
                                                
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    </div>
    <script>
    document.getElementById('image').addEventListener('change', function (event) {
        const file = event.target.files[0]; // الحصول على الملف المختار
        const previewContainer = document.getElementById('image-preview'); // العنصر لعرض الصورة
        previewContainer.innerHTML = ''; // إزالة المحتوى السابق

        if (file) {
            const reader = new FileReader();

            // عند اكتمال القراءة
            reader.onload = function (e) {
                // إنشاء عنصر صورة جديدة
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Selected Image';
                img.classList.add('img-thumbnail');
                img.style.maxWidth = '150px';
                img.style.marginTop = '10px';

                // إضافة الصورة إلى الحاوية
                previewContainer.appendChild(img);
            };

            reader.readAsDataURL(file); // قراءة الملف وعرضه كـ Base64
        } else {
            // إذا لم يتم اختيار ملف، عرض نص افتراضي
            previewContainer.innerHTML = `
                <img src="uploads/default-image_450.png" alt="Default Image" class="img-thumbnail" style="max-width: 150px;">
                <p class="text-muted">No Image Available</p>
            `;
        }
    });
</script>

</body>
</html>
