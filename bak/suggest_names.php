<?php
require_once 'config.php';
require_once 'auth.php';

// التحقق من طريقة الطلب
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['type'])) {
    $type = $_GET['type'];
    $search = isset($_GET['search']) ? $conn->real_escape_string(trim($_GET['search'])) : '';

    // التحقق من وجود النوع المطلوب
    if ($type === 'suggestions' && !empty($search)) {
        // استعلام لإرجاع اقتراحات الأسماء
        $query = "
            SELECT name
            FROM products1
            WHERE name LIKE '%$search%'
            LIMIT 30
        ";
        $result = $conn->query($query);

        $suggestions = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $suggestions[] = $row['name'];
            }
        }

        // إرجاع النتائج كـ JSON
        header('Content-Type: application/json');
        echo json_encode($suggestions);
        exit;

    } elseif ($type === 'exists' && !empty($search)) {
        // استعلام للتحقق من وجود الاسم
        $query = "
            SELECT COUNT(*) as count
            FROM products1
            WHERE name = '$search'
        ";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();

        // إرجاع نتيجة التحقق
        header('Content-Type: application/json');
        echo json_encode(['exists' => $row['count'] > 0]);
        exit;
    }
}

// إذا لم يتم استيفاء الشروط، إرجاع خطأ
http_response_code(400);
echo json_encode(['error' => 'Invalid request']);
exit;
?>
