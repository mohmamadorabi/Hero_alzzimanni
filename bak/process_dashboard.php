<?php
require_once 'config.php';

// الحصول على المدخلات
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? intval($_GET['category']) : '';

// إعداد الاستعلام
$query = "SELECT products1.name, products1.description, products1.price, products1.image FROM products1";
$params = [];
$conditions = [];

// إضافة شروط البحث
if (!empty($search)) {
    $conditions[] = "products1.name LIKE ?";
    $params[] = "%" . $search . "%";
}

// إضافة شروط التصفية
if (!empty($category)) {
    $conditions[] = "products1.category_id = ?";
    $params[] = $category;
}

// إضافة الشروط إلى الاستعلام
if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

// تنفيذ الاستعلام
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $types = str_repeat("s", count($params));
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// جلب الإحصائيات
$productCount = $conn->query("SELECT COUNT(*) AS count FROM products1")->fetch_assoc()['count'];
$categoryCount = $conn->query("SELECT COUNT(*) AS count FROM categories")->fetch_assoc()['count'];

// إنشاء HTML للنتائج
$html = '';
while ($product = $result->fetch_assoc()) {
    $html .= '
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="uploads/' . htmlspecialchars($product['image']) . '" class="card-img-top" alt="Product Image">
                <div class="card-body">
                    <h5 class="card-title">' . htmlspecialchars($product['name']) . '</h5>
                    <p class="card-text">' . htmlspecialchars($product['description']) . '</p>
                    <p class="card-text"><strong>السعر:</strong> ' . htmlspecialchars($product['price']) . '€</p>
                </div>
            </div>
        </div>
    ';
}

// إرسال البيانات كـ JSON
echo json_encode([
    'html' => $html,
    'productCount' => $productCount,
    'categoryCount' => $categoryCount
]);

$conn->close();
?>
