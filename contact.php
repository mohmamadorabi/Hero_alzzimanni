<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barbershop Azzimani</title>
    <script src="https://kit.fontawesome.com/4bd66741d3.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="java_header.js" defer></script>

    <!-- <script src="https://www.google.com/recaptcha/api.js?render=6LdaqrwqAAAAAOjZl0CC5SCTxWRiC4vETkT1CpnU"></script>  -->

    <script src="https://www.google.com/recaptcha/api.js" async defer></script> <!-- إضافة reCAPTCHA v2 -->



    <!-- <script src="contact.js" defer></script> -->
    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
    <script type="text/javascript">
        (function() {
            emailjs.init({
                publicKey: "csHGfXd8TRxHmRj-6",
            });
        })();
    </script> -->
    <style>
        
        @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap');
        body.arabic {
            font-family: 'Tajawal', 'Arial', sans-serif;
            /* direction: rtl; */
            /* text-align: right; */
        }

        body.dutch {
            font-family: 'Oswald', sans-serif;
            /* direction: rtl;
            text-align: right; */
        }

        body.english {
            font-family: 'Oswald', sans-serif;
            /* direction: rtl; */
            /* text-align: right; */
        }

        .mobile-nav {
                    display: block;
                }
        /* تنسيق الـ body بشكل عام */
        body {
            font-family: 'Tajawal', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #D9D7C8;
            color: #333;
            overflow-x: hidden; /* منع التمرير الأفقي */
            text-align: center; /* توسيط النص بشكل عام */
        }

        /* تنسيق الهيدر */
        header {
            position: relative;
            z-index: 2;
            padding: 20px 0;
            background-color: #fff;
            max-height: 80px !important;
        }

        .logo img {
            height: 130px !important;
            max-width: 140px !important;
        }

        .language-icon {
            width: 20px !important;
            height: 20px !important;
        }

        /* تنسيق الصورة في الشريط العلوي */
        .intro-container {
            width: 100%;
            height: 400px;
            background-image: url('images/head.png');
            background-size: cover;
            background-position: center;
            position: relative;
            z-index: 1;
            margin-top: -120px; /* تداخل الصورة تحت الهيدر */
        }

        .intro-container h2 {
            color: white;
            font-size: 3rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            z-index: 2;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            
        }

        /* تنسيق تفاصيل الاتصال */
        .contact-info {
            position: relative;
            z-index: 1;
            padding: 20px;
            margin-top: 20px;
        }

        .contact-info .info-icons {
            display: flex;
            justify-content: space-between; /* توزيع العناصر بالتساوي */
            max-width: 1000px; /* نفس عرض الفورم */
            margin: 0 auto; /* توسيط العناصر */
        }

        .contact-info .info-item {
            text-align: center;
            flex: 1; /* جعل العناصر تأخذ مساحة متساوية */
        }

        .contact-info .info-item i {
            font-size: 2rem;
            color: #e01391;
        }

        .contact-info .info-item h4 {
            font-size: 1.2rem;
            color: #333;
            margin: 10px 0 5px;
        }

        .contact-info .info-item p {
            font-size: 1rem;
            color: #555;
        }

        /* تنسيق الفورم */
        .contact-form {
            max-width: 1000px; /* توسيع عرض الفورم */
            margin: 40px auto;
            padding: 20px;
            text-align: center;
        }

        .contact-form .form-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .contact-form input {
            width: 48%; /* توسيع عرض الحقول */
            padding: 12px;
            margin: 0px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 1rem;
            box-sizing: border-box; /* التأكد من أن العرض يتضمن الحشوات */
        }

        .contact-form textarea {
            width: 100%;
            height: 150px; /* زيادة ارتفاع حقل الرسالة */
            padding: 12px;
            margin: 0px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 1rem;
            resize: vertical;
            box-sizing: border-box; /* التأكد من أن العرض يتضمن الحشوات */
        }

        .contact-form button {
            background-color: #e01391;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: inline-block;
            margin-top: 20px;
            width: auto; /* عرض الزر يتكيف مع المحتوى */
        }

        .contact-form button:hover {
            background-color: #ffcc00;
        }

        /* تنسيق عنوان الفورم والوصف */
        .contact-header {
            text-align: center;
            margin-bottom: 30px; /* زيادة التباعد بين العنوان والوصف */
        }

        .contact-header h2 {
            font-size: 2.5rem;
            color: #e01391;
            margin-bottom: 10px; /* زيادة التباعد بين العنوان والوصف */
            text-align: center;
            font-size: 3rem;
            color: #e01391;
            margin-bottom: 20px;
            text-transform: uppercase; /* تحويل النص إلى أحرف كبيرة */
            font-weight: bold; /* جعل النص عريض */
            color: #800000; /* تغيير لون العنوان عند التحويم */
            transition: color 0.3s ease; /* تأثير انتقال سلس */
        }

        .contact-header p {
            font-size: 1.1rem;
            color: #333;
        }

        /* تنسيق الخريطة */
        .contact-map {
            max-width: 1000px; /* توسيع عرض الخريطة */
            margin: 40px auto;
            padding: 0 20px; /* تباعد عن الجوانب */
        }

        .contact-map h3 {
            text-align: center;
            font-size: 2rem;
            color: #e01391;
            margin-bottom: 20px;
            text-transform: uppercase; /* تحويل النص إلى أحرف كبيرة */
            font-weight: bold; /* جعل النص عريض */
            color: #800000; /* تغيير لون العنوان عند التحويم */
            transition: color 0.3s ease; /* تأثير انتقال سلس */
        }

        .contact-map iframe {
            width: 100%;
            height: 400px;
            border-radius: 10px;
        }

        /* تنسيق زر العودة إلى الأعلى */
        .back-to-top {
            position: fixed;
            bottom: 75px;
            right: 20px;
            background-color: #e01391;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease, transform 0.3s ease, opacity 0.3s ease;
            z-index: 1000;
            opacity: 0; /* إخفاء الزر بشكل افتراضي */
            visibility: hidden; /* إخفاء الزر بشكل افتراضي */
        }

        .back-to-top.show {
            opacity: 1; /* إظهار الزر */
            visibility: visible; /* إظهار الزر */
        }

        .back-to-top:hover {
            background-color: #ffcc00;
            transform: scale(1.1); /* تأثير تكبير عند التحويم */
        }

        /* تأثير Scroll سلس */
        html {
            scroll-behavior: smooth;
        }

        /* تخصيص الشاشات الصغيرة */
        @media (max-width: 768px) {
            body {
                text-align: center; /* توسيط النص في الشاشات الصغيرة */
                
            }

            .logo img {
                height: 90px !important;
                max-width: 100px !important;
            }

            header {
                justify-content: space-between;
                /* overflow-y: hidden; */
                max-height: 50px !important;
                position: sticky;
                
            }

            .logo img {
                height: 90px !important;
                max-width: 100px !important;
            }

            .intro-container h2 {
                font-size: 2rem;
            }

            .contact-info .info-icons {
                flex-direction: column;
                gap: 20px;
                padding: 0 10px; /* إضافة حشوة جانبية */
            }

           

            .contact-form textarea {
                height: 120px; /* تقليل ارتفاع حقل الرسالة في الشاشات الصغيرة */
            }

            .contact-map {
                padding: 0 10px; /* تقليل التباعد في الشاشات الصغيرة */
            }

            .contact-map h3 {
                font-size: 1.5rem; /* تقليل حجم العنوان في الشاشات الصغيرة */
            }

            .contact-header h2 {
                font-size: 2rem; /* تقليل حجم العنوان في الشاشات الصغيرة */
            }

            .contact-header p {
                font-size: 1rem; /* تقليل حجم الوصف في الشاشات الصغيرة */
            }
            
        }

        footer{
            background-color: #333333;
        }

       
        @keyframes shake {
            0% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            50% { transform: translateX(5px); }
            75% { transform: translateX(-3px); }
            100% { transform: translateX(0); }
        }

        @keyframes colorChange {
            0% { color: #e01391; }
            50% { color: #ffcc00; }
            100% { color: #e01391; }
        }

        #address-detail {
            cursor: pointer; /* تغيير المؤشر إلى يد */
            color: inherit; /* لون النص */
            display: inline-block; /* لجعل التأثير يعمل بشكل صحيح */
            transition: color 0.3s ease; /* تأثير انتقالي */
        }

        #address-detail:hover {
            
            color: #ffcc00; /* تغيير لون النص عند التحويم */
        }
        #phone-detail a {
            text-decoration: none !important; /* إزالة الخط التحتي */
            color: inherit ; /* استخدام لون النص الأصلي */
            cursor: pointer !important; /* تغيير المؤشر إلى يد */
            display: inline-block !important; /* لجعل التأثير يعمل بشكل صحيح */
            transition: color 0.3s ease; /* تأثير انتقالي */
        }

        #phone-detail a:hover {
            text-decoration: none !important; /* التأكد من عدم ظهور خط تحتي عند التحويم */
            color: #ffcc00 !important;
        }


        #email-detail a {
            text-decoration: none !important; /* إزالة الخط التحتي */
            color: inherit ; /* استخدام لون النص الأصلي */
            cursor: pointer !important; /* تغيير المؤشر إلى يد */
            display: inline-block !important; /* لجعل التأثير يعمل بشكل صحيح */
            transition: color 0.3s ease; /* تأثير انتقالي */
        }

        #email-detail a:hover {
            text-decoration: none !important; /* التأكد من عدم ظهور خط تحتي عند التحويم */
            color: #ffcc00 !important;
        }

        #phone-detail, #address-detail, #email-detail {
            
            /* animation: pulse 2s ease-in-out infinite; */
            /* animation: glow 2s ease-in-out infinite, pulse 2s ease-in-out infinite; */
            position: relative;
    overflow: hidden;
        }

        @keyframes pulse {
            0% { transform: scale(1); } /* الحجم الأصلي */
            50% { transform: scale(1.1); } /* تكبير النص */
            100% { transform: scale(1); } /* العودة إلى الحجم الأصلي */
        }

        @keyframes glow {
            0% { text-shadow: 0 0 5px rgba(224, 19, 145, 0.7); }
            50% { text-shadow: 0 0 20px rgba(224, 19, 145, 0.9); }
            100% { text-shadow: 0 0 5px rgba(224, 19, 145, 0.7); }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        #phone-detail::after, #address-detail::after, #email-detail::after {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
                animation: shine 3s ease-in-out infinite;
            }

            @keyframes shine {
                0% { left: -100%; } /* بداية الحركة */
                100% { left: 100%; } /* نهاية الحركة */
}
    </style>
    
</head>

<body>
    <?php include 'header.php'; ?>
    <!-- شريط الصورة العلوي -->
    <section class="intro-bar">
        <div class="intro-container">
            <h2 id="contact-title">Contact Us</h2>
        </div>
    </section>

    <!-- قسم تفاصيل الاتصال -->
    <section class="contact-info">
        <div class="container">
            <div class="info-icons">
                <div class="info-item">
                    <i class="fa fa-map-marker-alt"></i>
                    <h4 id="address-title">Our Address</h4>
                    <p id="address-detail">Steenweg Wijchmaal 16, 3990 Peer</p>
                </div>
                <div class="info-item">
                    <i class="fa fa-phone"></i>
                    <h4 id="phone-title">Phone</h4>
                    <!-- <p id="phone-detail">+123 456 7890</p> -->
                    <p id="phone-detail">
                        <a href="tel:+1234567890">+123 456 7890</a> <!-- رابط الاتصال -->
                    </p>
                </div>
                <div class="info-item">
                    <i class="fa fa-envelope"></i>
                    <h4 id="email-title">Email</h4>
                    <p id="email-detail">
        <a href="mailto:info@barbershop.com?subject=Inquiry&body=Hello, I would like to get more information.">info@barbershop.com</a>
    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- عنوان "اتصل بنا" مع وصف -->
    <section class="contact-form">
        <section class="contact-header">
            <h2 id="contact-title1">Contact Us</h2>
            <img src="images/dark_seperator.png" alt="Image" class="hour-item-image">
            <p id="contact-description">We are available from Monday to Saturday. We will respond within 24 hours.</p>
        </section>
        <div class="container">
            <form id="contact-form">
                <div class="form-row">
                    <input type="text" id="name" name="name" placeholder="Your Name" required>
                    <input type="email" id="email" name="email" placeholder="Your Email" required>
                </div>
                <div class="form-row">
                    <input type="tel" id="phone" name="phone" placeholder="Your Phone" required>
                    <input type="text" id="subject" name="subject" placeholder="Subject" required>
                </div>
                <textarea name="message" id="message" placeholder="Your Message" rows="5" required></textarea>
                <div class="g-recaptcha" data-sitekey="6LfprbwqAAAAAHHBPV1SibUtRLSJIRj4RqmMbYWR"></div> <!-- إضافة reCAPTCHA هنا -->
                <div id="recaptcha-error" style="color: red; display: none;">Please check reCAPTCHA.</div> <!-- رسالة الخطأ -->

                <button type="submit" class="btn" id="submit-button">Send Message</button>
            </form>
          
        </div>
    </section>

    <!-- قسم الخريطة -->
    <section class="contact-map">
        <div class="container">
            <h3 id="map-title">Our Location</h3>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2503.6797688295983!2d5.447682899999999!3d51.132814599999996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c129e64294cf0b%3A0x333f26262d3a5b5c!2sBarbershop%20Azzimani!5e0!3m2!1sen!2sbe!4v1737041201232!5m2!1sen!2sbe" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section>

    <!-- الزر العائد للأعلى -->
    <a href="#" class="back-to-top" title="Back to Top">
        <i class="fa fa-arrow-up"></i>
    </a>

    <!-- الفوتر -->
    <style>
        /* تنسيق الفوتور */
        footer {
            background-color: #333; /* لون الخلفية */
            color: #fff; /* لون النص */
            padding: 20px 0; /* تباعد داخلي */
            font-family: 'Tajawal', sans-serif; /* خط النص */
        }

        .footer-container {
            display: flex;
            justify-content: space-between; /* توزيع العناصر */
            align-items: center; /* توسيط العناصر عموديًا */
            max-width: 1200px; /* عرض الفوتور */
            margin: 0 auto; /* توسيط الفوتور */
            padding: 0 20px; /* تباعد جانبي */
        }

        .footer-left {
            display: flex;
            align-items: center; /* توسيط العناصر عموديًا */
            gap: 20px; /* تباعد بين العناصر */
        }

        .footer-left p {
            margin: 0; /* إزالة الهوامش */
            font-size: 0.9rem; /* حجم النص */
        }



        .footer-right {
            display: flex;
            gap: 15px; /* تباعد بين الأيقونات */
        }

        .footer-right a {
            color: #fff; /* لون الأيقونات */
            font-size: 1.2rem; /* حجم الأيقونات */
            transition: color 0.3s ease; /* تأثير انتقالي */
        }

        .footer-right a:hover {
            color: #ffcc00; /* تغيير لون الأيقونة عند التحويم */
        }
        @media (max-width: 768px) {
            .footer-left p {
                font-size: 0.7rem; /* حجم النص */
            }
            
        }
    </style>
    <footer>
        <div class="footer-container">
            <!-- حقوق النشر وسياسة الخصوصية -->
            <div class="footer-left">
                <p>&copy;2025 All Rights Reserved - Barbershop Azzimani</p>
            </div>

            <!-- وسائل التواصل الاجتماعي -->
            <div class="footer-right">
                <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
                <a href="https://tiktok.com" target="_blank"><i class="fab fa-tiktok"></i></a>
            </div>
        </div>
    </footer>
    <!-- <script>
        const form = document.getElementById("contact-form");
        // التحقق من المدخلات عند الإرسال
        form.addEventListener("submit", (event) => {
            event.preventDefault();

            const inputs = {
                name: document.getElementById("name"),
                email: document.getElementById("email"),
                subject: document.getElementById("subject"),
                message: document.getElementById("message"),
            };

            // التحقق من أن الحقول ليست فارغة
            for (const key in inputs) {
                if (!inputs[key].value.trim()) {
                    alert(`Please enter a valid ${key}.`);
                    inputs[key].focus();
                    return;
                }
            }

            // التحقق من صحة البريد الإلكتروني
            if (!validateEmail(inputs.email.value)) {
                alert("Please enter a valid email address.");
                inputs.email.focus();
                return;
            }

            // إعداد البيانات للإرسال
            const parms = {
                name: inputs.name.value.trim(),
                email: inputs.email.value.trim(),
                subject: inputs.subject.value.trim(),
                message: inputs.message.value.trim(),
            };

            // إرسال البريد باستخدام EmailJS
            // emailjs.send("service_fb5vz5d", "template_xezemri", parms)
            emailjs.send("service_km8fp1j", "template_2hx8zxq1", parms)
                .then((response) => {
                    alert("Your message has been sent successfully!"); // نجاح الإرسال
                    console.log("SUCCESS!", response);
                    form.reset(); // إعادة تعيين النموذج
                })
                .catch((error) => {
                    alert("Failed to send your message. Please try again."); // فشل الإرسال
                    console.error("Error:", error);
                });
        });

        // دالة للتحقق من صحة البريد الإلكتروني
        function validateEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }
    </script> -->

    <script>
        document.getElementById('contact-form').addEventListener('submit', function(event) {
            event.preventDefault();

            // التحقق من reCAPTCHA
            const recaptchaResponse = grecaptcha.getResponse();
            const recaptchaError = document.getElementById('recaptcha-error');

            if (!recaptchaResponse) {
                recaptchaError.style.display = 'block'; // إظهار رسالة الخطأ
                return; // إيقاف الإرسال
            } else {
                recaptchaError.style.display = 'none'; // إخفاء رسالة الخطأ
            }

            // جمع البيانات من النموذج
            const formData = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                phone: document.getElementById('phone').value,
                subject: document.getElementById('subject').value,
                message: document.getElementById('message').value,
                recaptcha: recaptchaResponse
            };

            // إرسال البيانات إلى ملف PHP
            fetch('send_email.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message); // إظهار رسالة نجاح أو خطأ
                if (data.message === 'Message sent successfully!') {
                    document.getElementById('contact-form').reset(); // تنظيف الحقول
                    grecaptcha.reset(); // إعادة تعيين reCAPTCHA
                }
            })
            .catch(error => {
                alert(error);
            });
        });
    </script>

    <script src="java_header.js" defer></script>
    <script>
        // إظهار وإخفاء زر العودة إلى الأعلى عند التمرير
        window.addEventListener('scroll', function () {
            const backToTopButton = document.querySelector('.back-to-top');
            if (window.scrollY > 200) { // إذا تم التمرير لأكثر من 200px
                backToTopButton.classList.add('show');
            } else {
                backToTopButton.classList.remove('show');
            }
        });

        document.addEventListener("DOMContentLoaded", () => {
            const languageMenu = document.getElementById("language-menu");
            const languageBtn = document.getElementById("language-btn");

            // اختيار عناصر القائمة
            const menuIcon = document.getElementById("menu-icon");
            
            const mobileNav = document.getElementById("mobile-nav");
            const languageIcons = document.querySelectorAll(".language-icon");



            // النصوص لجميع اللغات
            const translations = {
                en: {
                    "navHome": "Home",
                    "navServices": "Services",
                    'navGallery': "Gallery",
                    "navShop": "Shop",
                    "navContact": "Contact",
                    navHome1: "Home",
            navServices1: "Services",
            navGallery1: "Gallery",
            navShop1: "Shop",
            navContact1: "Contact",
                    "contact-title": "Contact Us",
                    "contact-title1": "Contact Us",
                    "address-title": "Our Address",
                    "address-detail": "Steenweg Wijchmaal 16, 3990 Peer",
                    "phone-title": "Phone",
                    // "phone-detail": "+123 456 7890",
                    "email-title": "Email",
                    // "email-detail": "info@barbershop.com",
                    "contact-description": "We are available from Monday to Saturday. We will respond within 24 hours.",
                    "map-title": "Our Location",
                    "submit-button": "Send Message",

                    "recaptcha-error":"Please check reCAPTCHA."
                },
                ar: {
                    "navHome": "الرئيسية",
                    "navServices": "الخدمات",
                    "navGallery": "المعرض",
                    "navShop": "المتجر",
                    "navContact": "اتصل بنا",
                    navHome1: "الرئيسية",
            navServices1: "الخدمات",
            navGallery1: "المعرض",
            navShop1: "المتجر",
            navContact1: "اتصل بنا",
                    "contact-title": "اتصل بنا",
                    "contact-title1": "اتصل بنا",
                    "address-title": "عنواننا",
                    "address-detail": "Steenweg Wijchmaal 16, 3990 Peer",
                    "phone-title": "الهاتف",
                    // "phone-detail": "+123 456 7890",
                    "email-title": "البريد الإلكتروني",
                    // "email-detail": "info@barbershop.com",
                    "contact-description": "نحن متاحون من الاثنين إلى السبت. سوف نرد في غضون 24 ساعة.",
                    "map-title": "موقعنا",
                    "submit-button": "إرسال الرسالة",
                    "recaptcha-error":"الرجاء التحقق من reCAPTCHA."
                },
                nl: {
                    navHome: "Startpagina",
                    navServices: "Diensten",
                    navGallery: "Galerij",
                    navShop: "Winkel",
                    navContact: "Contact",
                    navHome1: "Startpagina",
            navServices1: "Diensten",
            navGallery1: "Galerij",
            navShop1: "Winkel",
            navContact1: "Contact",
                    "contact-title": "Neem Contact Op",
                    "contact-title1": "Neem Contact Op",
                    "address-title": "Ons Adres",
                    "address-detail": "Steenweg Wijchmaal 16, 3990 Peer",
                    "phone-title": "Telefoon",
                    // "phone-detail": "+123 456 7890",
                    "email-title": "E-mail",
                    // "email-detail": "info@barbershop.com",
                    "contact-description": "We zijn beschikbaar van maandag tot zaterdag. We reageren binnen 24 uur.",
                    "map-title": "Onze Locatie",
                    "submit-button": "Bericht Verzenden",
                    "recaptcha-error":"Controleer reCAPTCHA."
                }
            };

            // دالة لتحديث النصوص
            function updateLanguage(lang) {
                for (const id in translations[lang]) {
                    const element = document.getElementById(id);
                    if (element) {
                        element.textContent = translations[lang][id];
                    }
                }
                // تطبيق تغييرات على النصوص واتجاه الصفحة
                if (lang === 'ar') {
                    document.body.classList.add('arabic');
                    document.body.classList.remove('english', 'dutch');
                } else if (lang === 'en') {
                    document.body.classList.add('english');
                    document.body.classList.remove('arabic', 'dutch');
                } else if (lang === 'nl') {
                    document.body.classList.add('dutch');
                    document.body.classList.remove('arabic', 'english');
                }

                localStorage.setItem("selectedLang", lang);

                }

            // إضافة حدث النقر لكل أيقونة
            languageIcons.forEach(icon => {
                icon.addEventListener("click", (e) => {
                    e.preventDefault();
                    const selectedLang = icon.getAttribute("id");
                    updateLanguage(selectedLang);

                    // حفظ اللغة في التخزين المحلي
                    localStorage.setItem("selectedLang", selectedLang);
                });
            });

            // استعادة اللغة من التخزين المحلي عند تحميل الصفحة
            const savedLang = localStorage.getItem("selectedLang") || "en";
            updateLanguage(savedLang);

            window.addEventListener("load", () => {
                document.body.classList.add("loaded");
            });

            const addressTitle = document.getElementById("address-detail");

            if (addressTitle) {
                addressTitle.addEventListener("click", () => {
                    const textToCopy = addressTitle.textContent; // الحصول على النص
                    navigator.clipboard.writeText(textToCopy) // نسخ النص إلى الحافظة
                        .then(() => {
                            alert("تم نسخ العنوان: " + textToCopy); // إشعار بنجاح النسخ
                        })
                        .catch((err) => {
                            console.error("فشل في نسخ النص: ", err); // إشعار بالفشل
                        });
                });
            }
            

            const phoneLink = document.querySelector('#phone-detail a');

            if (phoneLink) {
                phoneLink.addEventListener("click", (e) => {
                    if (!/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                        e.preventDefault(); // منع السلوك الافتراضي على أجهزة الكمبيوتر
                        alert("يرجى استخدام جهاز محمول للاتصال بهذا الرقم.");
                    }
                });
            }

            });
    </script>
</body>

</html>