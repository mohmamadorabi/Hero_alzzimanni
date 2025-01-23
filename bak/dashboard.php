<?php
require_once 'config.php';
require_once 'auth.php';
include 'navbar.php';
// الإحصائيات العامة
$total_categories_query = "SELECT COUNT(*) AS total_categories FROM categories";
$total_products_query = "SELECT COUNT(*) AS total_products FROM products1";
$total_categories = $conn->query($total_categories_query)->fetch_assoc()['total_categories'];
$total_products = $conn->query($total_products_query)->fetch_assoc()['total_products'];

// المنتجات المنتهية وشبه المنتهية
$low_stock_query = "
    SELECT 
        products1.id, 
        products1.name AS product_name, 
        products1.quantity, 
        categories.name AS category_name 
    FROM 
        products1 
    LEFT JOIN 
        categories 
    ON 
        products1.category_id = categories.id 
    WHERE 
        products1.quantity <= 1
    ORDER BY 
        products1.quantity ASC
";
$low_stock_result = $conn->query($low_stock_query);

// جلب الفئات والمنتجات
$categories_query = "
    SELECT 
        categories.id AS category_id, 
        categories.name AS category_name, 
        COUNT(products1.id) AS product_count 
    FROM 
        categories 
    LEFT JOIN 
        products1 
    ON 
        categories.id = products1.category_id 
    GROUP BY 
        categories.id, categories.name
";
$categories_result = $conn->query($categories_query);

$products_query = "
    SELECT 
        products1.id AS product_id, 
        products1.name AS product_name, 
        products1.quantity, 
        products1.price, 
        products1.image, -- إضافة حقل الصورة
        categories.id AS category_id, 
        categories.name AS category_name
    FROM 
        products1 
    LEFT JOIN 
        categories 
    ON 
        products1.category_id = categories.id
";
$products_result = $conn->query($products_query);

// ترتيب المنتجات حسب الفئات
$products_by_category = [];
while ($row = $products_result->fetch_assoc()) {
    $products_by_category[$row['category_id']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | AZORPUB</title>
    <script src="dashboard\js\navbar.js" defer></script>
    <link rel="stylesheet" href="dashboard/css/navbar.css">
    <script src="https://kit.fontawesome.com/b8057c684d.js" crossorigin="anonymous"></script>

    
    <style>
        :root {
            --bg-color: #f8f9fa;
            --text-color: #333;
            --card-bg: #ffffff;
            --sidebar-bg: #f1f1f1;
            --highlight-color: #007bff;
            --border-color: #ddd;
            --hover-color: #e0e0e0;
            --h3-color: #000;
        }

        [data-theme="dark"] {
            --bg-color: #121212;
            --text-color: #ffffff;
            --card-bg: #1e1e1e;
            --sidebar-bg: #1e1e1e;
            --highlight-color: #1e90ff;
            --border-color: #444;
            --hover-color: #333;
            --h3-color: #ffcc00;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color 0.3s, color 0.3s;
        }

        h1, h2, h3 {
            text-align: center;
            color: var(--h3-color);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background-color: var(--card-bg);
            padding: 20px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .card:hover {
            transform: scale(1.03);
            transition: transform 0.3s ease;
        }



        .toggle-sidebar {
            position: fixed;
            top: 70px; /* نفس ارتفاع السايدبار */
            left: 10px; /* افتراضي عند الإغلاق */
            width: 40px;
            height: 40px;
            background-color: #007bff;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1100; /* فوق السايدبار */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: left 0.3s ease; /* الحركة تتزامن مع السايدبار */
                }
        

        .toggle-sidebar:hover {
            background-color: var(--hover-color);
        }
        .sidebar.open + .toggle-sidebar {
            left: 310px; /* ملاصقة للسلايدر عند الفتح (عرض السايدبار + المسافة) */
        }
        .category-item {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .category-item:hover {
            background-color: var(--hover-color);
        }

        .product-list {
            background-color: var(--bg-color);
            padding: 10px;
            border-left: 2px solid var(--border-color);
        }
        .sidebar {
            position: fixed;
            top: 60px; /* المسافة من الأعلى */
            left: -310px; /* إخفاء السايدبار عند الإغلاق */
            width: 300px;
            height: 100%;
            background-color:var(--bg-color);
            border-right: 1px solid var(--border-color);
            transition: left 0.3s ease; /* حركة السايدبار عند الفتح والإغلاق */
            z-index: 1050; /* السايدبار فوق باقي العناصر */
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
                        }
        .sidebar.open {
                    left: 0; /* فتح السلايدر */
        }

        .sidebar-header {
            display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        font-size: 1.25rem;
        font-weight: bold;
        background-color: var(--card-bg);
        border-bottom: 1px solid var(--border-color);
            }
            /* السلايدر */
        .slider-container {
            overflow-y: auto; /* السماح بالتمرير العمودي */
            max-height: 400px; /* أقصى ارتفاع للسلايدر */
            background-color: var(--bg-color);
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            margin-top: 10px;
        }

        .sidebar-content {
            display: flex;
            flex-direction: column; /* ترتيب المنتجات عموديًا */
            gap: 10px; /* مسافة بين العناصر */
                }


        a {
            color: var(--highlight-color);
        }

        a:hover {
            text-decoration: none;
            opacity: 0.8;
        }

        .product-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ddd; /* خط فاصل */
            background-color: var(---bg-color); /* تغيير لون الخلفية */
            transform: scale(1.02); /* تكبير خفيف */
            transition: all 0.3s ease; /* تأثير الانتقال */
        }

        .product-item:hover {
            background-color:var(--hover-color); /* لون خفيف عند التمرير */
        }

        .product-item span {
            flex: 1; /* توزيع المساحات بين العناصر */
            text-align: left;
            padding: 0 10px; /* مسافة داخلية بين النصوص */
        }

        .product-item .edit-button {
            flex: 0 0 auto; /* زر التعديل على اليمين */
            margin-left: auto; /* دفع الزر إلى أقصى اليمين */
            background-color: #007bff; /* لون أزرق */
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s ease;
        }

        .product-item .edit-button:hover {
            background-color: #0056b3; /* لون أغمق */
            transform: scale(1.05); /* تكبير بسيط */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* ظل خفيف */
            transition: all 0.3s ease;
                }

        .product-item .edit-button i {
            margin-right: 5px; /* مسافة صغيرة بين الأيقونة والنص */
        }


        .slider-item {
            display: flex;
            justify-content: space-between; /* توزيع الاسم وزر التعديل */
            align-items: center;
            padding: 10px;
            background-color: var(--bg-color);
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .product-info {
            display: flex;
            flex-direction: column; /* ترتيب النصوص عموديًا */
            gap: 5px;
        }

        .product-name {
            font-weight: bold;
            font-size: 1rem;
            color: var(--text-color);
        }

        .product-category,
        .product-quantity {
            font-size: 0.9rem;
            color: var(--text-color);
        }

        .edit-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s ease;
        }

        .edit-button:hover {
            background-color: #0056b3;
        }

        .edit-button a {
            color: white;
            text-decoration: none;
        }
        .search-bar {
    position: relative;
    display: flex;
    align-items: center; /* محاذاة الزر مع حقل الإدخال */
    width: 50%;
    margin: 20px auto; /* توسيط الشريط بالكامل */
}

#product-search {
    flex: 1; /* اجعل حقل الإدخال يتمدد لملء المساحة */
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    outline: none;
}

.clear-button {
    background-color: #ff4d4d; /* لون أحمر للتوضيح */
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 0.8rem;
    margin-left: 10px; /* مسافة صغيرة بين الزر والحقل */
    transition: background-color 0.3s ease;
}

.clear-button:hover {
    background-color: #cc0000; /* لون داكن عند التمرير */
}

.clear-button:focus {
    outline: none; /* إزالة إطار التركيز */
}

        .search-bar {
            position: relative;
            width: 50%; /* عرض الحقل */
            margin: 20px auto; /* تمركز الحقل في المنتصف */
        }

        #product-search {
            background-color: var(--bg-color); /* لون الخلفية */
            color: var(--text-color); /* لون النص */
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease; /* تأثير عند التغيير */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* ظل خفيف */
        }
        #product-search:focus {
            background-color: var(--card-bg); /* لون الخلفية عند التركيز */
            outline: none; /* إزالة الإطار الافتراضي */
            border: 1px solid #007bff; /* لون الإطار عند التركيز */
        }
        #product-search:hover {
            background-color: var(--hover-color); /* لون الخلفية عند التمرير */
        }
        #suggestions {
            position: absolute;
            top: 40px;
            left: 0;
            width: 100%;
            /* border: 1px solid var(--border-color); */
            border-radius: 5px;
            background-color: var(--bg-color);
            max-height: 200px; /* الحد الأقصى لارتفاع القائمة */
            overflow-y: auto; /* السماح بالتمرير إذا زادت النتائج */
            z-index: 1000;
        }

        #suggestions div {
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #suggestions div:hover {
            background-color: var(--hover-color);
        }
       
        .product-item {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color:var(--bg-color);
}

.product-image {
    width: 50px;
    height: 50px;
    border-radius: 5px;
    object-fit: cover;
}

.product-name {
    font-weight: bold;
    flex: 1;
}

.edit-button {
    background-color: #007bff;
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    font-size: 0.9rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.edit-button:hover {
    background-color: #0056b3;
}



        /* الشاشات الصغيرة */
        @media (max-width: 768px) {
            .sidebar {
                width: 80%; /* عرض السايدبار على الشاشات الصغيرة */
            }

            .toggle-sidebar {
                top: 10px;
                right: 10px;
            }
        }
       
</style>
</head>
<body data-theme="light">




<div id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <span>Notifications</span>
        
    </div>
    <hr> <!-- خط فاصل تحت العنوان -->
    <div id="slider-container" class="slider-container">
        <strong>Total Out of Stock:</strong> <?= $low_stock_result->num_rows ?><br>
        <div class="slider-content">
            <?php while ($product = $low_stock_result->fetch_assoc()): ?>
                <div class="slider-item">
                    <div class="product-info">
                        <span class="product-name"><?= htmlspecialchars($product['product_name']) ?></span>
                        <span class="product-category">الفئة: <?= htmlspecialchars($product['category_name'] ?? 'غير مصنفة') ?></span>
                        <span class="product-quantity">الكمية: <?= htmlspecialchars($product['quantity']) ?></span>
                    </div>
                    <button class="edit-button">
                        <a href="edit_product_form.php?id=<?= htmlspecialchars($product['id']) ?>&redirect=<?= urlencode($_SERVER['REQUEST_URI']); ?>" class="text-white">

                            <i class="fas fa-edit"></i> تعديل
                        </a>
                    </button>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>
<div class="toggle-sidebar" onclick="toggleSidebar()">&#128276;</div>


<div class="search-bar">
    <input 
        type="text" 
        id="product-search" 
        placeholder="ابحث عن المنتج باستخدام اسمه..." 
        autocomplete="off">
        <button id="clear-search" class="clear-button">X</button>
    <div id="suggestions"></div>
</div>


<div class="container">
    <h1>Dashboard</h1>

    <div class="stats">
        <div class="card">
            <h3>Total Categories</h3>
            <p><?= $total_categories ?></p>
        </div>
        <div class="card">
            <h3>Total Products</h3>
            <p><?= $total_products ?></p>
        </div>
    </div>

    <h2>Categories and Products</h2>


    <div class="categories-list">
    <?php while ($category = $categories_result->fetch_assoc()): ?>
        <div class="category-item" data-category-id="<?= $category['category_id'] ?>">
            <?= htmlspecialchars($category['category_name']) ?> (<?= $category['product_count'] ?>)
        </div>
        <div class="product-list" id="products-<?= $category['category_id'] ?>" style="display: none;"></div>
    <?php endwhile; ?>
    </div>


</div>
<script>
    // التبديل بين الفتح والإغلاق للـ Sidebar
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.querySelector('.toggle-sidebar');
        sidebar.classList.toggle('open');

        // تحديث موقع الزر بناءً على حالة السايدبار
        if (sidebar.classList.contains('open')) {
            toggleBtn.style.left = '310px'; // عرض السايدبار + المسافة
        } else {
            toggleBtn.style.left = '10px'; // العودة إلى مكانه الأساسي
        }
    }

    // إغلاق الـ Sidebar عند النقر خارجها
    document.addEventListener('click', function (event) {
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.querySelector('.toggle-sidebar');

        // التحقق إذا كان النقر خارج الـ Sidebar وزر التبديل
        if (sidebar.classList.contains('open') && !sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
            sidebar.classList.remove('open'); // إغلاق السايدبار
            toggleBtn.style.left = '10px'; // إعادة الزر إلى موقعه الأساسي
        }
    });

    // التبديل بين الوضع الداكن والفاتح
    function toggleTheme() {
        const body = document.body;
        body.dataset.theme = body.dataset.theme === 'dark' ? 'light' : 'dark';
    }

    // البحث داخل المنتجات
    function filterProducts() {
        const searchInput = document.getElementById('productSearch').value.toLowerCase();
        const products = document.querySelectorAll('.product-item');

        products.forEach(product => {
            const productName = product.querySelector('.product-name').textContent.toLowerCase();
            if (productName.includes(searchInput)) {
                product.style.display = 'flex';
            } else {
                product.style.display = 'none';
            }
        });
    }

    // تنفيذ الأحداث بعد تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('product-search');
        const suggestions = document.getElementById('suggestions');
        const clearButton = document.getElementById('clear-search');
        // البحث التلقائي عند الكتابة
        searchInput.addEventListener('input', function () {
            const query = searchInput.value.trim();

            if (query.length > 0) {
                // إرسال طلب إلى ملف البحث
                fetch('search_products.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `search=${encodeURIComponent(query)}`
                })
                .then(response => response.text())
                .then(data => {
                    suggestions.innerHTML = data; // عرض الاقتراحات
                });
            } else {
                suggestions.innerHTML = ''; // إخفاء الاقتراحات إذا كان الإدخال فارغًا
            }
        });

        // التوجيه عند اختيار منتج من الاقتراحات
        suggestions.addEventListener('click', function (e) {
            if (e.target.classList.contains('suggestion-item')) {
                // const productId = e.target.getAttribute('data-id');
                // window.open(`edit_product_form.php?id=${productId}`, '_blank'); // فتح في نافذة جديدة
                const productId = e.target.getAttribute('data-id');
                const redirectUrl = encodeURIComponent(window.location.href); // الرابط الحالي للصفحة
                window.open(`edit_product_form.php?id=${productId}&redirect=${redirectUrl}`); // فتح في نافذة جديدة

            }
        });
// وظيفة زر Clear
clearButton.addEventListener('click', function () {
    searchInput.value = '';
        suggestions.innerHTML = '';
        searchInput.focus(); // إعادة التركيز إلى الحقل
    });
        // إدارة عرض القوائم الفرعية للفئات
        document.querySelectorAll('.category-item').forEach(item => {
            item.addEventListener('click', function (event) {
                event.stopPropagation(); // منع إغلاق القائمة

                const categoryId = this.getAttribute('data-category-id');
                const productList = document.getElementById(`products-${categoryId}`);

                // إغلاق القوائم المفتوحة باستثناء القائمة الحالية
                document.querySelectorAll('.product-list').forEach(list => {
                    if (list !== productList) {
                        list.style.display = 'none';
                    }
                });

                // جلب المنتجات إذا لم تكن محملة
                if (productList.innerHTML === '' && productList.style.display !== 'block') {
                    fetch(`get_products.php?category_id=${categoryId}`)
                        .then(response => response.text())
                        .then(data => {
                            productList.innerHTML = data;
                            productList.style.display = 'block';
                        })
                        .catch(error => console.error(`خطأ أثناء جلب المنتجات: ${error}`));
                } else {
                    // تبديل العرض بين الفتح والإغلاق
                    productList.style.display = productList.style.display === 'none' ? 'block' : 'none';
                }
            });
        });

        // منع إغلاق القائمة عند النقر على الصورة
        document.querySelectorAll('.product-image-link').forEach(link => {
            link.addEventListener('click', function (event) {
                event.stopPropagation(); // منع إغلاق القائمة
            });
        });

        // إغلاق القوائم عند النقر خارجها
        document.addEventListener('click', function (event) {
            document.querySelectorAll('.product-list').forEach(list => {
                if (!list.contains(event.target) && !event.target.closest('.category-item')) {
                    list.style.display = 'none';
                }
            });
        });
    });
</script>


</body>
</html>