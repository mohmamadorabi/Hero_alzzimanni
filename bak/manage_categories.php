<?php
require_once 'config.php';
include 'navbar.php';
require_once 'auth.php'; // التحقق من الجلسة

// التحقق من صلاحيات المستخدم
if ($_SESSION['role'] === 'viewer') {
    die("ليس لديك الصلاحيات لإدارة الفئات.");
}

// رسالة للتأكيد
$message = '';

// إضافة فئة جديدة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $category_name = trim($_POST['category_name']);
    if (!empty($category_name)) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $category_name);
        if ($stmt->execute()) {
            $message = "تمت إضافة الفئة بنجاح.";
        } else {
            $message = "حدث خطأ أثناء إضافة الفئة.";
        }
        $stmt->close();
    } else {
        $message = "يرجى إدخال اسم الفئة.";
    }
}

// تعديل فئة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_category'])) {
    $category_id = intval($_POST['category_id']);
    $category_name = trim($_POST['category_name']);
    if (!empty($category_name)) {
        $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $category_name, $category_id);
        if ($stmt->execute()) {
            $message = "تم تعديل الفئة بنجاح.";
        } else {
            $message = "حدث خطأ أثناء تعديل الفئة.";
        }
        $stmt->close();
    } else {
        $message = "يرجى إدخال اسم الفئة.";
    }
}

// حذف فئة
if (isset($_GET['delete'])) {
    $category_id = intval($_GET['delete']);

    // التحقق من المنتجات المرتبطة بالفئة
    $stmt = $conn->prepare("SELECT COUNT(*) FROM products1 WHERE category_id = ?");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $stmt->bind_result($product_count);
    $stmt->fetch();
    $stmt->close();

    if ($product_count > 0) {
        $message = "لا يمكن حذف الفئة لأنها تحتوي على $product_count منتج/منتجات.";
    } else {
        $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bind_param("i", $category_id);
        if ($stmt->execute()) {
            $message = "تم حذف الفئة بنجاح.";
        } else {
            $message = "حدث خطأ أثناء حذف الفئة.";
        }
        $stmt->close();
    }
}

// جلب جميع الفئات مع عدد المنتجات المرتبطة بكل فئة
$query = "
    SELECT categories.id, categories.name,
           (SELECT COUNT(*) FROM products1 WHERE products1.category_id = categories.id) AS product_count 
    FROM categories
";
$categories = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories | AZORPUB</title>

    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/b8057c684d.js" crossorigin="anonymous"></script>

    <script src="dashboard\js\navbar.js" defer></script>
    <link rel="stylesheet" href="dashboard/css/navbar.css">

    <style>
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

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color 0.3s, color 0.3s;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: var(--bg-color);
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .alert {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            background-color: #f8d7da;
            color: #721c24;
            text-align: center;
        }

        form {
            margin-bottom: 20px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            background-color: var(--input-bg);
            color: var(--text-color);
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--highlight-color);
            outline: none;
            box-shadow: 0 0 5px var(--highlight-color);
        }

        button {
            background-color: var(--button-bg);
            color: var(--button-text);
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: var(--highlight-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: var(--bg-color);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        th, td {
            text-align: center;
            padding: 10px;
            border: 1px solid var(--border-color);
        }

        th {
            background-color: var(--highlight-color);
            color: #fff;
            font-weight: bold;
        }

        td {
            background-color: var(--input-bg);
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                padding: 15px;
            }

            .form-control {
                padding: 8px;
            }

            button {
                padding: 8px 10px;
            }
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">إدارة الفئات</h1>
    <?php if ($message): ?>
        <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <!-- نموذج إضافة فئة جديدة -->
    <form action="manage_categories.php" method="POST" class="mb-4">
        <div class="row">
            <div class="col-md-8">
                <input type="text" name="category_name" class="form-control" placeholder="اسم الفئة الجديدة" required>
            </div>
            <div class="col-md-4">
                <button type="submit" name="add_category" class="btn btn-primary w-100">إضافة فئة</button>
            </div>
        </div>
    </form>

    <!-- جدول الفئات -->
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>اسم الفئة</th>
            <th>عدد المنتجات</th>
            <th>إجراءات</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($category = $categories->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($category['id']) ?></td>
                <td>
                    <form action="manage_categories.php" method="POST" class="d-flex "style="display: flex; align-items: center; gap: 10px;>
                        <input type="hidden" name="category_id" value="<?= $category['id'] ?>">
                        <input type="text" name="category_name" class="form-control" value="<?= htmlspecialchars($category['name']) ?>" required>
                        <!-- <button type="submit" name="edit_category" class="btn btn-success btn-sm ms-2">تعديل</button> -->
                        <button type="submit" name="edit_category" class="btn btn-success btn-sm" style="background: none; border: none; padding: 0; cursor: pointer;">
                            <i class="fas fa-save" style="font-size: 1.2rem; color: var(--highlight-color);"></i>
                        </button>
                    </form>
                </td>
                <td><?= $category['product_count'] ?></td>
                <td>
                    <a href="manage_categories.php?delete=<?= $category['id'] ?>" 
                       class="btn btn-danger btn-sm" 
                       onclick="return confirm('هل أنت متأكد من حذف هذه الفئة؟');">حذف</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php
// إغلاق الاتصال بقاعدة البيانات
$conn->close();
?>
