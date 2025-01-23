<?php

require_once 'config.php';
// include 'navbar.php';
require_once 'auth.php'; // التحقق من الجلسة
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20; // عدد الصفوف المطلوب تحميلها (افتراضي 20)
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("DELETE FROM products1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Product deleted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete product.']);
    }
    $stmt->close();
    exit;
}

// جلب آخر 20 منتجًا افتراضيًا
$query = "
    SELECT products1.id, products1.name, products1.price, products1.quantity, categories.name AS category_name
    FROM products1
    LEFT JOIN categories ON products1.category_id = categories.id
    ORDER BY products1.created_at DESC, products1.created_at DESC
    LIMIT 20
";
$products = $conn->query($query);
?>
<?php
include 'navbar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products | AZORPUB</title>

    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/b8057c684d.js" crossorigin="anonymous"></script>
    <script src="dashboard\js\navbar.js" defer></script>
    <link rel="stylesheet" href="dashboard/css/navbar.css">

    <style>
        /* أنماط الجدول والصفحة */
        :root {
            --bg-color: #ffffff;
            --text-color: #000000;
            --navbar-bg: #f8f9fa;
            --border-color: #ddd;
            --highlight-color: #007bff;
            --table-odd: #f8f9fa;
            --table-even: #ffffff;
            --table-dark: #ffffff;
        }

        [data-theme="dark"] {
            --bg-color: #121212;
            --text-color: #ffffff;
            --navbar-bg: #1e1e1e;
            --border-color: #444;
            --highlight-color: #1e90ff;
            --table-odd: #444444;
            --table-even: #1e1e1e;
        }
        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color 0.3s, color 0.3s;
        }
      /* تنسيق الجدول */
        .table {
            width: 100%; /* تأكد من أن الجدول لا يمتد لأكثر من عرض الحاوية */
            max-width: 100%; /* تحديد عرض أقصى للجدول */
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 16px;
            text-align: left;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* إضافة تأثير الظل للجدول */
            border-radius: 10px; /* إضافة زاوية دائرية للجدول */
        }

        .table th, .table td {
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            text-align: center; /* محاذاة النص في المركز */
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: var(--table-odd);
        }

        .table-striped tbody tr:nth-of-type(even) {
            background-color: var(--table-even);
        }

        .table-hover tbody tr:hover {
            background-color: var(--highlight-color);
            color: #fff;
            cursor: pointer;
        }

        .table th {
            background-color: var(--navbar-bg);
            color: var(--text-color);
            font-weight: bold;
        }

        .table td {
            color: var(--text-color);
        }
        .table-responsive {
            width: 100%;
            overflow-x: auto; /* تمكين التمرير الأفقي */
            -webkit-overflow-scrolling: touch; /* تحسين التمرير على الأجهزة التي تعمل باللمس */
            
        }
        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background-color: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* العنوان */
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
      /* صندوق البحث */
        .search-container {
            display: flex;
            justify-content: center;
            width: 100%;
            margin-bottom: 20px;
        }

        .search-bar {
            width: 300px; /* يمكنك تعديل هذا العرض حسب الحاجة */
            padding: 10px;
            border-radius: 5px;
            border: 1px solid var(--border-color);
            font-size: 16px;
        }

        .load-more {
            margin: 20px auto;
            display: block;
            background-color: var(--highlight-color);
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .message {
            margin: 20px auto;
            text-align: center;
        }
        /* تنسيق الأزرار */
        .btn {
            padding: 8px 16px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
            border: none;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-warning {
            background-color: #ffc107;
            color: black;
            border: none;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        /* تحسين عرض الجدول للأجهزة الصغيرة */
        @media (max-width: 768px) {
            .table {
                font-size: 14px;
            }

            .table th, .table td {
                padding: 8px 10px;
            }

            .container {
                margin-left: 10px;
                margin-right: 10px;
            }
        }
    </style>
</head>
<body data-theme="light">
    <div class="container">
        <h1 class="text-center">Manage Products (<span id="rowCount"><?= $products->num_rows ?></span> Rows)</h1>
        <!-- رسالة تأكيد -->
        <?php if ($message): ?>
            <div class="alert alert-info message">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- شريط البحث -->
        <div class="search-container">
            <input type="text" id="search-bar" class="search-bar" placeholder="Search by product name...">
        </div>

        <!-- جدول المنتجات -->
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="products-table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                        <?php 
                        $number = 1; // متغير لتتبع الترقيم
                        if ($products->num_rows > 0): 
                        ?>
                        
                        <?php while ($product = $products->fetch_assoc()): ?>
                            <tr id="product-row-<?= htmlspecialchars($product['id']) ?>">
                                <td class="number-cell"><?= $number++ ?></td> <!-- عمود الترقيم -->
                                <td><?= htmlspecialchars($product['id']) ?></td>
                                <td><?= htmlspecialchars($product['name']) ?></td>
                                <td><?= htmlspecialchars($product['category_name'] ?: 'Uncategorized') ?></td>
                                <td>$<?= htmlspecialchars($product['price']) ?></td>
                                <td><?= htmlspecialchars($product['quantity']) ?></td>
                                <td>
                                <a 
                                    href="edit_product_form.php?id=<?= htmlspecialchars($product['id']) ?>&redirect=<?= urlencode($_SERVER['REQUEST_URI']); ?>" 
                                    class="btn btn-sm btn-warning">
                                    Edit
                                </a>
                                <!-- <a target="_blank" href="edit_product_form.php?id=<?= htmlspecialchars($product['id']) ?>" class="btn btn-sm btn-warning">Edit</a> -->
                                <button class="btn btn-sm btn-danger" onclick="deleteProduct(<?= htmlspecialchars($product['id']) ?>)">Delete</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No products found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- زر إظهار المزيد -->
        <button class="load-more" id="load-more" onclick="loadMoreProducts()">Load More</button>
    </div>

    <script>
        function deleteProduct(id) {
            if (confirm('Are you sure you want to delete this product?')) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);

                fetch('', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json()) // تأكد من أن الاستجابة هي JSON
                .then(data => {
                    console.log("Server response:", data); // تحقق من الاستجابة القادمة من الخادم
                    if (data.status === 'success') {
                        alert(data.message);
                        removeProductRow(id);
                    } else {
                        alert("Error: " + data.message);
                    }
                })


                .then(data => {
                    alert(data.message); // عرض رسالة النجاح أو الفشل

                    if (data.status === 'success') {
                        // إزالة الصف من الجدول بعد الحذف
                        removeProductRow(id);
                    } else {
                        alert("Error: " + data.message); // عرض رسالة فشل
                    }
                })
                .catch(error => {
                    console.error("Error deleting product:", error);
                });
            }
        }

        // دالة لإزالة صف المنتج من الجدول بعد الحذف
        function removeProductRow(id) {
            const row = document.querySelector(`#product-row-${id}`);
            if (row) {
                row.remove(); // إزالة الصف من الجدول
            }
        }

        // البحث الديناميكي
        document.getElementById('search-bar').addEventListener('input', function () {
            const query = this.value;

            if (query.trim() !== '') { // إذا كان الحقل غير فارغ
                fetch(`search_products_table.php?search=${encodeURIComponent(query)}`)
                    .then(response => response.text())
                    .then(data => {
                        document.querySelector('#products-table tbody').innerHTML = data;
                        updateTableRowNumbers(); // تحديث الترقيم بعد البحث
                    });
            } else {
                // إعادة تحميل المنتجات الافتراضية
                fetch(`search_products_table.php?default=true`)
                    .then(response => response.text())
                    .then(data => {
                        document.querySelector('#products-table tbody').innerHTML = data;
                        updateTableRowNumbers(); // تحديث الترقيم بعد البحث
                    });
            }
        });

        // وظيفة إظهار المزيد
        function loadMoreProducts() {
            const rows = document.querySelectorAll('#products-table tbody tr').length;
            fetch(`search_products_table.php?offset=${rows}`)
                .then(response => response.text())
                .then(data => {
                    const tableBody = document.querySelector('#products-table tbody');
                    tableBody.insertAdjacentHTML('beforeend', data);
                    if (data.trim() === '') {
                        document.getElementById('load-more').style.display = 'none';
                    }
                    updateTableRowNumbers(); // تحديث الترقيم بعد البحث
                });
        }
    

        function updateTableRowNumbers() {
            const table = document.getElementById('products-table');
            const rows = table.querySelectorAll('tbody tr'); // جلب جميع الصفوف في الجدول
            const rowCountSpan = document.getElementById('rowCount'); // العنصر لعرض عدد الصفوف (يمكن إضافته لاحقًا)

            rows.forEach((row, index) => {
                let numberCell = row.querySelector('.number-cell'); // البحث عن خلية الترقيم
                if (!numberCell) {
                    // إذا لم تكن موجودة، قم بإنشائها
                    numberCell = document.createElement('td');
                    numberCell.classList.add('number-cell');
                    row.insertAdjacentElement('afterbegin', numberCell); // أضف الخلية في بداية الصف
                }
                numberCell.textContent = index + 1; // تخصيص الرقم للصف
            });

            // تحديث عدد الصفوف (اختياري)
            if (rowCountSpan) {
                rowCountSpan.textContent = rows.length;
            }
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
