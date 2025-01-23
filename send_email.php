<?php
error_reporting(E_ALL); // تفعيل الإبلاغ عن جميع الأخطاء
ini_set('display_errors', 1); // عرض الأخطاء على الشاشة

// التحقق من أن الطلب هو POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // جمع البيانات من الطلب
    $data = json_decode(file_get_contents('php://input'), true);

    // التحقق من وجود جميع الحقول
    if (empty($data['name']) || empty($data['email']) || empty($data['phone']) || empty($data['subject']) || empty($data['message']) || empty($data['recaptcha'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['message' => 'جميع الحقول مطلوبة.']);
        exit;
    }

    // التحقق من صحة reCAPTCHA
    $recaptchaResponse = $data['recaptcha'];
    $secretKey = '6LfprbwqAAAAAC5FDcPi7WYoc_OjVcm0nkOHN2BG'; // استبدل بالمفتاح السري من Google reCAPTCHA
    $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$recaptchaResponse}");
    $responseData = json_decode($verifyResponse);

    if (!$responseData->success) {
        http_response_code(400); // Bad Request
        echo json_encode(['message' => 'التحقق من reCAPTCHA فشل.', 'details' => $responseData]);
        exit;
    }

    // تنظيف البيانات
    $name = htmlspecialchars($data['name'], ENT_QUOTES, 'UTF-8');
    $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars($data['phone'], ENT_QUOTES, 'UTF-8');
    $subject = htmlspecialchars($data['subject'], ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars($data['message'], ENT_QUOTES, 'UTF-8');

    // التحقق من صحة البريد الإلكتروني
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400); // Bad Request
        echo json_encode(['message' => 'البريد الإلكتروني غير صحيح.']);
        exit;
    }

    // إعداد بيانات EmailJS
    $serviceID = 'service_km8fp1j'; // استبدل بمعرف الخدمة الخاص بك
    $templateID = 'template_2hx8zxq'; // استبدل بمعرف القالب الخاص بك
    $privateKey = 'G6A9A74-BQYPI1VCP'; // استبدل بمعرف المستخدم الخاص بك

    // بيانات البريد الإلكتروني
    $emailData = [
        'service_id' => $serviceID,
        'template_id' => $templateID,
        'user_id' => $privateKey,
        'template_params' => [
            'to_email' => 'azzimani.shop@gmail.com', // البريد الإلكتروني الذي تريد استلام الرسالة عليه
            'email' => $email, // البريد الإلكتروني الذي أدخله المستخدم
            'name' => $name,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message
        ]
    ];

    // إرسال الطلب إلى EmailJS
    $ch = curl_init('https://api.emailjs.com/api/v1.0/email/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($emailData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // التحقق من نجاح الإرسال
    if ($httpCode === 200) {
        echo json_encode(['message' => 'Message sent successfully!']);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(['message' => 'حدث خطأ أثناء إرسال البريد الإلكتروني.', 'details' => $response]);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['message' => 'الطريقة غير مسموح بها.']);
}
?>