<?php
require_once 'config.php';
include 'navbar.php';
require_once 'auth.php'; // التحقق من الجلسة

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // جلب البيانات من النموذج
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $category_id = intval($_POST['category_id']);
    $price = floatval($_POST['price']);
    $quantity = intval($_POST['quantity']);
    $image = $_FILES['image'];

    // التحقق من صحة البيانات
    if (empty($name) || empty($description) || empty($category_id) || empty($price) || empty($quantity) || empty($image['name'])) {
        $message = "Please fill in all required fields.";
    } else {
        // التحقق من وجود الاسم في قاعدة البيانات
        $query = "SELECT COUNT(*) as count FROM products1 WHERE name = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['count'] > 0) {
            // إذا كان الاسم موجودًا
            $message = "The product name already exists. Please choose a different name.";
        } else {
            // التحقق من نوع وحجم الملف المرفوع
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($image['type'], $allowed_types)) {
                $message = "Unsupported file type. Please upload a JPEG, PNG, or GIF image.";
            } elseif ($image['size'] > 2 * 1024 * 1024) { // الحد الأقصى 2 ميجابايت
                $message = "File size is too large. The maximum size is 2 MB.";
            } else {
                // رفع الصورة
                $upload_dir = 'uploads/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $image_name = time() . '_' . basename($image['name']);
                $image_path = $upload_dir . $image_name;
                if (move_uploaded_file($image['tmp_name'], $image_path)) {
                    // إدخال المنتج في قاعدة البيانات
                    $stmt = $conn->prepare("INSERT INTO products1 (name, description, category_id, price, quantity, image) VALUES (?, ?, ?, ?, ?, ?)");
                    if ($stmt) {
                        $stmt->bind_param("ssidis", $name, $description, $category_id, $price, $quantity, $image_name);
                        if ($stmt->execute()) {
                            $message = "Product added successfully.";
                        } else {
                            $message = "Error adding product: " . htmlspecialchars($stmt->error);
                        }
                        $stmt->close();
                    } else {
                        $message = "Failed to prepare query: " . htmlspecialchars($conn->error);
                    }
                } else {
                    $message = "Error uploading the image.";
                }
            }
        }
    }
}


// جلب الفئات من قاعدة البيانات لعرضها في النموذج
$categories = $conn->query("SELECT id, name FROM categories");
?>

<!DOCTYPE html>
<html lang="en">
<head>      
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product | AZORPUB</title>
    <!-- Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->

    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/b8057c684d.js" crossorigin="anonymous"></script>


    <script src="dashboard\js\navbar.js" defer></script>
    <link rel="stylesheet" href="dashboard/css/navbar.css">
    
    <style>
    :root {
            --bg-color: #f8f9fa;
            --text-color: #333;
            --input-bg: #fff;
            --input-border: #ddd;
            --button-bg: #007bff;
            --button-color: #fff;
            --button-hover-bg: #0056b3;
        }

        [data-theme="dark"] {
            --bg-color: #121212;
            --text-color: #fff;
            --input-bg: #1e1e1e;
            --input-border: #444;
            --button-bg: #1e90ff;
            --button-color: #000;
            --button-hover-bg: #0056b3;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 0;
            transition: background-color 0.3s, color 0.3s;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background-color: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            width: 100%;
            text-align: left;
            max-width: 700px;
        }

        input, select ,textarea{
            width: 100%;
            max-width: 700px;
            padding: 10px;
            border: 1px solid var(--input-border);
            border-radius: 5px;
            background-color: var(--input-bg);
            color: var(--text-color);
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--button-bg);
        }

        .button {
            display: block;
            width: 100%;
            max-width: 200px;
            margin: 20px auto 0;
            padding: 10px;
            background-color: var(--button-bg);
            color: var(--button-color);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: var(--button-hover-bg);
        }

        .alert {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }


        #suggestions {
            /* display: block; */
            
    /* position: absolute; */
    background-color: var(--bg-color);
    border: 1px solid #ddd;
    z-index: 1000;
    max-width: 700px;
    max-height: 150px;
    overflow-y: auto;
    width: 100%; /* لتطابق عرض حقل الإدخال */
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* إضافة ظل لتحسين المظهر */
    border-radius: 5px; /* لتقريب الزوايا */
}

.suggestion-item {
    padding: 10px;
    font-size: 14px;
    cursor: pointer;
}

.suggestion-item:hover {
    background-color:var(--input-border);
    color: #007bff; /* تغيير اللون عند التمرير */
}

        @media (max-width: 768px) {
            input, select {
                max-width: 100%;
            }

            .button {
                width: 80%;
            }
        }
    </style>
</head>
<body data-theme="light>
<div class="container">
        <h1>Add New Product</h1>
        <?php if (!empty($message)): ?>
            <div class="alert" id="success-message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form id="add-product-form"  method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name" >Product Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter product name" required>
                        <div id="suggestions" style="display: none;"></div> <!-- قائمة الاقتراحات -->
                    <div id="exists-message" style="margin-top: 5px; font-size: 14px;"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description" >Description</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="category_id" >Category</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="" disabled selected>Select a Category</option>
                            <?php
                            while ($category = $categories->fetch_assoc()) {
                                echo "<option value='{$category['id']}'>{$category['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="price" >Price</label>
                        <input type="number" class="form-control" id="price" name="price" placeholder="Enter product price"  step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity" >Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter product quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="image" >Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                    </div>
                    <button type="submit" class="button">Add Product</button>
                </form>
    </div>
    <script>
       // مراقبة إدخال المستخدم
document.getElementById('name').addEventListener('input', function () {
    const name = this.value;

    if (name.length > 2) {
        // طلب الاقتراحات
        fetch(`suggest_names.php?type=suggestions&search=${encodeURIComponent(name)}`)
            .then(response => response.json())
            .then(data => {
                const suggestionsDiv = document.getElementById('suggestions');
                suggestionsDiv.innerHTML = '';

                if (data.length > 0) {
                    suggestionsDiv.style.display = 'block';
                    data.forEach(productName => {
                        const suggestion = document.createElement('div');
                        suggestion.textContent = productName;
                        suggestion.classList.add('suggestion-item');
                        
                        // عند النقر على اقتراح
                        suggestion.addEventListener('click', () => {
                            document.getElementById('name').value = productName;
                            suggestionsDiv.style.display = 'none';
                            
                            // التحقق من وجود الاسم بعد النقر
                            checkNameExists(productName);
                        });
                        suggestionsDiv.appendChild(suggestion);
                    });
                } else {
                    suggestionsDiv.style.display = 'none';
                }
            });

        // التحقق من وجود الاسم أثناء الكتابة
        checkNameExists(name);
    } else {
        document.getElementById('suggestions').style.display = 'none';
        document.getElementById('exists-message').textContent = '';
    }
});

// مراقبة إرسال النموذج
document.getElementById('add-product-form').addEventListener('submit', function (event) {
    const name = document.getElementById('name').value;
    event.preventDefault(); // منع الإرسال مؤقتًا

    // التحقق من وجود الاسم قبل الإرسال
    fetch(`suggest_names.php?type=exists&search=${encodeURIComponent(name)}`)
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                // إذا كان الاسم موجودًا
                const existsMessage = document.getElementById('exists-message');
                existsMessage.textContent = 'This product name already exists.';
                existsMessage.style.color = 'red';
            } else {
                // إذا كان الاسم غير موجود، تابع الإرسال
                document.getElementById('add-product-form').submit();
            }
        })
        .catch(error => {
            console.error('Error during fetch:', error);
        });
});

// وظيفة التحقق من وجود الاسم
function checkNameExists(name) {
    fetch(`suggest_names.php?type=exists&search=${encodeURIComponent(name)}`)
        .then(response => response.json())
        .then(data => {
            const existsMessage = document.getElementById('exists-message');
            if (data.exists) {
                existsMessage.textContent = 'This product name already exists.';
                existsMessage.style.color = 'red';
            } else {
                existsMessage.textContent = '';
            }
        });
}

// تحديد عنصر الرسالة
const successMessage = document.getElementById('success-message');

// وظيفة لإخفاء الرسالة
function hideSuccessMessage() {
    if (successMessage) {
        successMessage.style.display = 'none';
    }
}

// إضافة أحداث لإخفاء الرسالة عند التفاعل
document.querySelectorAll('#add-product-form input, #add-product-form textarea, #add-product-form select').forEach(element => {
    element.addEventListener('input', hideSuccessMessage); // عند الكتابة
    element.addEventListener('change', hideSuccessMessage); // عند تغيير الملف أو الاختيار
});
    </script>
</body>
</html>

<?php
$conn->close();
?>
