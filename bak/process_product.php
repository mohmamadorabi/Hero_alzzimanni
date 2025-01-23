<?php
require_once 'config.php';
require_once 'auth.php'; // التحقق من الجلسة
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// التحقق من نوع العملية
$action = $_POST['action'] ?? '';
$message = '';

// $value = "Hello from PHP!";
// echo "<script>console.log('PHP Value: " . addslashes($action) . "');</script>";
$redirect_url = isset($_POST['redirect']) ? $_POST['redirect'] : 'edit_product.php'; // رابط افتراضي

// التحقق من صحة الحقول
function validateFields($name, $description, $category_id, $price) {
    if (empty($name) || empty($description) || empty($category_id) || empty($price)) {
        return "Please fill in all required fields.";
    }
    if (!is_numeric($price) || $price <= 0) {
        return "Price must be a positive number.";
    }
    return null;
}

// رفع الصورة
function uploadImage($image) {
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if ($image && $image['error'] === UPLOAD_ERR_OK) {
        $image_name = uniqid('product_', true) . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
        $image_path = $upload_dir . $image_name;

        if (move_uploaded_file($image['tmp_name'], $image_path)) {
            return $image_name;
        }
    }
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $category_id = intval($_POST['category_id']);
    $price = floatval($_POST['price']);
    $quantity = intval($_POST['quantity'] ?? 0); // إضافة الكمية
    $image = $_FILES['image'] ?? null;

    
    // التحقق من صحة البيانات
    $validation_error = validateFields($name, $description, $category_id, $price);
    if ($validation_error) {
        die($validation_error);
    }

    // رفع الصورة
    $image_name = uploadImage($image);

    // العملية: إضافة أو تعديل
    if ($action === 'add') {
        $stmt = $conn->prepare("INSERT INTO products1 (name, description, category_id, price, quantity, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssidis", $name, $description, $category_id, $price, $quantity, $image_name);
        if ($stmt->execute()) {
            header("Location: add_product.php?success=1");
            exit();
        } else {
            die("Error adding product: " . $stmt->error);
        }
    } elseif ($action === 'edit') {
        $product_id = intval($_POST['product_id']);
        
        if ($image_name) {
            // $image_name = "MO";
            // echo "<script>console.log('PHP Value: " . addslashes($image_name) . "');</script>";
            // echo "<script>console.log('PHP Value: " . addslashes($product_id) . "');</script>";
            // تحديث المنتج مع الصورة
            $stmt = $conn->prepare("UPDATE products1 SET name = ?, description = ?, category_id = ?, price = ?, quantity = ?, image = ? WHERE id = ?");
            // $stmt->bind_param("ssidsii", $name, $description, $category_id, $price, $quantity, $image_name, $product_id);
            $stmt->bind_param("ssiddsi", $name, $description, $category_id, $price, $quantity, $image_name, $product_id);

        } else {
            // تحديث المنتج بدون تغيير الصورة
            $stmt = $conn->prepare("UPDATE products1 SET name = ?, description = ?, category_id = ?, price = ?, quantity = ? WHERE id = ?");
            $stmt->bind_param("ssidsi", $name, $description, $category_id, $price, $quantity, $product_id);
        }

        if ($stmt->execute()) {
            // header("Location: $redirect_url?success=1");
            header("Location: " . $redirect_url);

            exit();
        } else {
            die("Error updating product: " . $stmt->error);
        }
    }
}
?>
