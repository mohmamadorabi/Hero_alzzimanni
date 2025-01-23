<?php
require_once 'config.php'; // الاتصال بقاعدة البيانات

// قائمة موديلات هواتف سامسونج بدون كلمة "جالكسي"
$productNames = [
    "S20", "S20+", "S20 Ultra", "Z Flip", "Note 20", "Note 20 Ultra", "Z Fold 2", 
    "A01 Core", "A21s", "A31", "A41", "A51 5G", "A71 5G", "A42 5G", "M01", "M11", 
    "M21", "M31", "M51", "F41", "S20 FE", "S21", "S21+", "S21 Ultra", "A02", "A12", 
    "A32", "A52", "A72", "M12", "M32", "M52 5G", "F02s", "F12", "F22", "Z Fold 3", 
    "Z Flip 3", "S21 FE", "S22", "S22+", "S22 Ultra", "A13", "A23", "A33 5G", 
    "A53 5G", "A73 5G", "M13", "M23 5G", "M33 5G", "F13", "F23 5G", "Z Fold 4", 
    "Z Flip 4", "S23", "S23+", "S23 Ultra", "A14", "A24", "A34 5G", "A54 5G", 
    "M14 5G", "M34 5G", "F14 5G", "F54 5G", "Z Fold 5", "Z Flip 5"
];


// جلب الفئات من قاعدة البيانات
$categoriesQuery = "SELECT id FROM categories";
$categoriesResult = $conn->query($categoriesQuery);

// حفظ الفئات في مصفوفة
$categories = [];
while ($row = $categoriesResult->fetch_assoc()) {
    $categories[] = $row['id'];
}

// التحقق من وجود فئات
if (count($categories) === 0) {
    die("لا توجد فئات في قاعدة البيانات. الرجاء إضافة فئات أولاً.");
}

// عدد المنتجات التي تريد إضافتها
$numberOfProducts = 10000;

for ($i = 0; $i < $numberOfProducts; $i++) {
    // اختيار اسم منتج عشوائي
    $name = $productNames[array_rand($productNames)];
    $quantity = rand(0, 100); // كمية عشوائية بين 0 و 100
    $price = rand(500, 2000); // سعر عشوائي بين 500 و 2000
    $category_id = $categories[array_rand($categories)]; // اختيار فئة عشوائية

    // إدخال المنتج في قاعدة البيانات
    $query = "INSERT INTO products1 (name, quantity, price, category_id) VALUES ('$name', $quantity, $price, $category_id)";
    if ($conn->query($query) === TRUE) {
        echo "تم إدخال المنتج رقم $i بنجاح: $name<br>";
    } else {
        echo "خطأ في إدخال المنتج رقم $i: " . $conn->error . "<br>";
    }
}

$conn->close();
?>

