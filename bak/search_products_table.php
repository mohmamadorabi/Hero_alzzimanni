<?php
require_once 'config.php';
require_once 'auth.php';
// التحقق من وجود الطلب
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $search = isset($_GET['search']) ? $conn->real_escape_string(trim($_GET['search'])) : '';
    $default = isset($_GET['default']) && $_GET['default'] === 'true';

    if ($default) {
        // عرض المنتجات الافتراضية (آخر 20 منتجًا)
        $query = "
            SELECT products1.id, products1.name, products1.price, products1.quantity, categories.name AS category_name
            FROM products1
            LEFT JOIN categories ON products1.category_id = categories.id
            ORDER BY products1.created_at DESC
            LIMIT 20
        ";
    } else {
        // البحث بناءً على النص المدخل
        $query = "
            SELECT products1.id, products1.name, products1.price, products1.quantity, categories.name AS category_name
            FROM products1
            LEFT JOIN categories ON products1.category_id = categories.id
            WHERE products1.name LIKE '%$search%'
            ORDER BY products1.created_at DESC
        ";
    }

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($product = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($product['id']) . "</td>
                    <td>" . htmlspecialchars($product['name']) . "</td>
                    <td>" . htmlspecialchars($product['category_name'] ?: 'Uncategorized') . "</td>
                    <td>$" . htmlspecialchars($product['price']) . "</td>
                    <td>" . htmlspecialchars($product['quantity']) . "</td>
                    <td>
                        <a href='edit_product.php?id=" . htmlspecialchars($product['id']) . "' class='btn btn-sm btn-warning'>Edit</a>
                        <button class='btn btn-sm btn-danger' onclick='deleteProduct(" . htmlspecialchars($product['id']) . ")'>Delete</button>
                    </td>
                </tr>";
        }
    } else {
        echo "<tr><td colspan='6' class='text-center'>No results found.</td></tr>";
    }
    exit;
}
?>
