<?php
require_once 'config.php';
require_once 'auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $search = $conn->real_escape_string($_POST['search']);
    
    // استعلام البحث
    $query = "
        SELECT id, name, quantity, category_id
        FROM products1
        WHERE name LIKE '%$search%'
        LIMIT 30
    ";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "
                <div class='suggestion-item' data-id='{$row['id']}'>
                    <strong>{$row['name']}</strong> 
                    <span>- الكمية: {$row['quantity']}</span>
                </div>
            ";
        }
    } else {
        echo "<div>لا توجد نتائج مطابقة</div>";
    }
}


?>
