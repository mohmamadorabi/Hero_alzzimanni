<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barbershop Azzimani</title>
    <script src="https://kit.fontawesome.com/b8057c684d.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="java.js" defer></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap');
        body.arabic {
            font-family: 'Tajawal', 'Arial', sans-serif;
            direction: rtl;
            text-align: right;
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
        /* تنسيق عام للعناصر */
        * {
            box-sizing: border-box; /* التأكد من أن الحشوات لا تؤثر على العرض الكلي */
            margin: 0;
            padding: 0;
        }

        /* تنسيق الـ body بشكل عام */
        body {
            font-family: 'Tajawal', sans-serif;
            line-height: 1.6;
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
            max-height: 110px !important;
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
            margin-top: -110px; /* تداخل الصورة تحت الهيدر */
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
            padding: 0 20px; /* إضافة حشوة جانبية */
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
            margin: 5px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 1rem;
            box-sizing: border-box; /* التأكد من أن العرض يتضمن الحشوات */
        }

        .contact-form textarea {
            width: 100%;
            height: 150px; /* زيادة ارتفاع حقل الرسالة */
            padding: 12px;
            margin: 5px;
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
        }

        .contact-map iframe {
            width: 100%;
            height: 400px;
            border-radius: 10px;
        }

        /* تنسيق زر العودة إلى الأعلى */
        .back-to-top {
            position: fixed;
            bottom: 20px;
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

            header {
                justify-content: space-between;
                overflow-x: hidden;
                max-height: 90px;
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

            .contact-form .form-row {
                flex-direction: column;
            }

            .contact-form input {
                width: 100%;
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
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <!-- شريط الصورة العلوي -->
    <section class="intro-bar">
        <div class="intro-container">
            <h2 data-lang="contact-title">Contact Us</h2>
        </div>
    </section>

    <!-- قسم تفاصيل الاتصال -->
    <section class="contact-info">
        <div class="container">
            <div class="info-icons">
                <div class="info-item">
                    <i class="fa fa-map-marker-alt"></i>
                    <h4 data-lang="address-title">Our Address</h4>
                    <p data-lang="address-detail">123 Main St, City, Country</p>
                </div>
                <div class="info-item">
                    <i class="fa fa-phone"></i>
                    <h4 data-lang="phone-title">Phone</h4>
                    <p data-lang="phone-detail">+123 456 7890</p>
                </div>
                <div class="info-item">
                    <i class="fa fa-envelope"></i>
                    <h4 data-lang="email-title">Email</h4>
                    <p data-lang="email-detail">info@barbershop.com</p>
                </div>
            </div>
        </div>
    </section>

    <!-- عنوان "اتصل بنا" مع وصف -->
    <section class="contact-form">
        <section class="contact-header">
            <h2 data-lang="contact-title">Contact Us</h2>
            <img src="images/dark_seperator.png" alt="Image" class="hour-item-image">
            <p data-lang="contact-description">We are available from Monday to Saturday. We will respond within 24 hours.</p>
        </section>
        <div class="container">
            <form action="submit_contact.php" method="POST">
                <div class="form-row">
                    <input type="text" name="name" placeholder="Your Name" required>
                    <input type="email" name="email" placeholder="Your Email" required>
                </div>
                <div class="form-row">
                    <input type="tel" name="phone" placeholder="Your Phone" required>
                    <input type="text" name="subject" placeholder="Subject" required>
                </div>
                <textarea name="message" placeholder="Your Message" rows="5" required></textarea>
                <button type="submit" class="btn" data-lang="submit-button">Send Message</button>
            </form>
        </div>
    </section>

    <!-- قسم الخريطة -->
    <section class="contact-map">
        <div class="container">
            <h3 data-lang="map-title">Our Location</h3>
            <iframe src="https://www.google.com/maps/embed?pb=..." width="100%" height="400" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
        </div>
    </section>

    <!-- الزر العائد للأعلى -->
    <a href="#" class="back-to-top" title="Back to Top">
        <i class="fa fa-arrow-up"></i>
    </a>

    <!-- الفوتر -->
    <?php include 'footer.php'; ?>

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
    </script>
</body>

</html>