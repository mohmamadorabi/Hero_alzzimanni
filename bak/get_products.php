<?php
require_once 'config.php'; // الاتصال بقاعدة البيانات
require_once 'auth.php'; // التحقق من الجلسة
// التحقق من وجود معرف الفئة
if (isset($_GET['category_id'])) {
    $categoryId = intval($_GET['category_id']);
    $stmt = $conn->prepare("SELECT id, name, quantity, price, image FROM products1 WHERE category_id = ?");
    $stmt->bind_param("i", $categoryId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $imagePath = $_SERVER['DOCUMENT_ROOT'] . "/uploads/" . $row['image'];
            $imageUrl = (!empty($row['image']) && $row['image'] !== '0' && file_exists($imagePath)) ? "/uploads/" . $row['image'] : "/uploads/default-image_450.png";

            echo "<div class='product-item'>";
            echo "<a href='{$imageUrl}' target='_blank' class='product-image-link'>";
            echo "<img src='{$imageUrl}' alt='صورة المنتج' class='product-image' />";
            echo "</a>";
            echo "<span class='product-name'>{$row['name']}</span>";
            echo "<span> - السعر: {$row['price']}</span>";
            echo "<span class='product-quantity'>الكمية: {$row['quantity']}</span>";
            echo "<a href='edit_product_form.php?id={$row['id']}&redirect=" . urlencode('dashboard.php') . "' target='_blank' class='edit-button'>تعديل</a>";

            echo "</div>";
        }
    } else {
        echo "<p>لا توجد منتجات في هذه الفئة.</p>";
    }
} else {
    echo "<p>لم يتم تحديد فئة.</p>";
}

?>
