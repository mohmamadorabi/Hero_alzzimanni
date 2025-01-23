<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="description" content="Barbershop Azzimani - أفضل صالون حلاقة للرجال. نقدم أحدث قصات الشعر وحلاقة اللحية والحلاقة الكلاسيكية.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barbershop Azzimani</title>
    <script src="https://kit.fontawesome.com/4bd66741d3.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="java_header.js" defer></script>
    <!-- <link rel="stylesheet" href="styles.css"> -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" as="style" onload="this.rel='stylesheet'">
</head>
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
    /* تنسيق الـ body بشكل عام */
    body {
        font-family: 'Oswald', sans-serif;
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
        position: relative;
        z-index: 1;
        margin-top: -120px;
        overflow: hidden;
    }

    #intro-video {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: translate(-50%, -50%);
        z-index: -1;
        opacity: 0;
        transition: opacity 1s ease;
    }

    #intro-video.loaded {
        opacity: 1;
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
    /* تنسيق عام للصفحة */
    .services {
       
        padding: 80px 0;
        background-color: #D9D7C8;
        text-align: center;
    }

    .services h2 {
        font-size: 2.5rem;
        color: #e01391;
        margin-bottom: 40px;
        text-transform: uppercase; /* تحويل النص إلى أحرف كبيرة */
        font-weight: bold;
    }

    /* تنسيق بطاقات الخدمات */
    .service-cards {
       
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 30px;
        padding: 0 20px;
    }

    .service-card {
        background-color: #fff;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        width: 300px;
        padding: 20px;
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden; /* لإخفاء الزوايا الزائدة */
    }

    .service-card:hover {
        transform: translateY(-10px); /* تأثير الرفع عند التحويم */
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2); /* ظل أكبر */
    }

    .service-card img {
        width: 100%;
        border-radius: 10px;
        transition: transform 0.3s ease;
    }
    .service-card img:hover {
    transform: scale(1.1);
}
    .service-card:hover img {
        transform: scale(1.1); /* تكبير الصورة عند التحويم */
    }

    .service-card h3 {
        font-size: 1.5rem;
        color: #333;
        margin-bottom: 10px;
    }

    .service-card p {
        font-size: 1rem;
        color: #555;
        margin-bottom: 10px;
    }

    .service-card .price {
        font-size: 1.2rem;
        color: #e01391;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .service-card .btn {
        display: inline-block;
        padding: 10px 20px;
        background-color: #e01391;
        color: #fff;
        border-radius: 5px;
        text-decoration: none;
        transition: background-color 0.3s ease, transform 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .service-card .btn::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 300%;
        height: 300%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%) scale(0);
        border-radius: 50%;
        transition: transform 0.5s ease;
    }

    .service-card .btn:hover::after {
        transform: translate(-50%, -50%) scale(1); /* تأثير التموج */
    }

    .service-card .btn:hover {
        background-color: #ffcc00;
        transform: scale(1.05); /* تكبير الزر عند التحويم */
    }

    /* تنسيق للشاشات الصغيرة */
    @media (max-width: 768px) {
        .service-cards {
            flex-direction: column;
            align-items: center;
        }

        .service-card {
            width: 90%;
        }
    }
</style>

<body>
    <?php include 'header.php'; ?>
    <section class="intro-bar">
        <div class="intro-container">
            <video autoplay muted loop id="intro-video" poster="images/Haircut_1280X720.png">
                <source src="images/Haircut_1280X720.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <h2 id="contact-title">Our services</h2>
        </div>
    </section>
    <main>
    <style>
        /* تنسيق القسم بالكامل */
        
        .pricing {
            padding: 40px 20px;
            background-color: #F4F2EB;
            color: #fff;
            text-align: center;
            /* backdrop-filter: blur(10px); */
            /* border-radius: 10px; */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-top: 50px;
        }
        /* تنسيق حاوية الأسعار */
        
        .pricing-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        /* تنسيق العنوان */
        
        .pricing h2 {
            font-size: 2.5rem;
            color: #ffcc00;
            margin-bottom: 30px;
        }
        /* تنسيق الوصف */
        
        .pricing-description p {
            font-size: 1.1rem;
            margin-bottom: 20px;
        }
        /* تنسيق التنويه */
        
        .pricing-note {
            margin: 20px 0;
            font-size: 1rem;
        }
        /* تنسيق صندوق الأسعار */
        
        .pricing-box {
            background: none;
            padding: 0 15px 10px 20px;
            /* top, right, bottom, left */
            border-radius: 10px;
            border: 3px solid #ffcc00;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: inline-block;
            width: 25%;
            text-align: left;
            line-height: 3;
            flex-wrap: wrap;
            /* السماح بلف العناصر ضمن الصندوق */
        }
        .pricing-box:hover {
        transform: translateY(-10px); /* تأثير الرفع عند التحويم */
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2); /* ظل أكبر */
    }

        .price-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            width: 100%;
            position: relative;
        }
        
        .price-item .price-name {
            text-align: left;
            width: auto !important;
            color: #000;
        }
        
        .price-item .price-value {
            text-align: right;
            color: #b8860b;
        }
        
        .price-item .price-divider {
            flex-grow: 1;
            border-bottom: 2px dotted #000;
            margin: 0 10px;
            text-align: center;
        }
        
        .pricing-box h3 {
            font-size: 2rem;
            color: black;
            margin-top: 10px;
            /* تقليل المسافة بين العنوان وأعلى الصندوق */
            margin-bottom: 20px;
            text-align: center;
        }
        
        .price-title {
            font-size: 1.2rem;
            color: black;
        }
        /* تخصيص للشاشات الصغيرة */
        /* تخصيص صندوق الأسعار على الشاشات الصغيرة */
        
        @media (max-width: 768px) {
            .pricing-box {
                width: 100%;
                padding: 15px;
                box-sizing: border-box;
                /* التأكد من أن الحواف وال padding لا تؤثر على العرض */
                margin: 0 auto;
                /* ضمان أن الصندوق يتوسط الصفحة */
            }
            .separator {
                display: none;
                /* إخفاء الخط المنقط بين العناصر */
            }
            .pricing h2 {
                font-size: 1.8rem;
                /* تقليل حجم الخط على الهواتف */
            }
            .pricing-description p {
                font-size: 1rem;
                /* تحسين حجم الخط */
            }
            .pricing-note {
                font-size: 1rem;
            }
            .pricing-note p {
                font-size: 0.9rem;
            }
            .pricing-booking .btn {
                padding: 10px 20px;
                font-size: 1.1rem;
                /* تحسين حجم الزر */
            }
        }
    </style>
        <section class="services">
            <div class="container">
                <h2>Our services</h2>
                <div class="service-cards">
                    <!--1 صندوق الأسعار -->
                    <div class="pricing-box">
                        <h3 data-lang="pricing-box-title">Haircut</h3>

                        <div class="price-item">
                            <span class="price-name" data-lang="basic-cut-title">Knippen Heren</span>
                            <span class="price-divider"></span>
                            <span class="price-value" data-lang="basic-cut-price">20 €</span>
                        </div>
                        <div class="price-item">
                            <span class="price-name" data-lang="beard-grooming-title">Kinderen</span>
                            <div class="price-divider"></div>
                            <span class="price-value" data-lang="beard-grooming-price">15 €</span>
                        </div>
                        <div class="price-item">
                            <span class="price-name" data-lang="full-package-title">Baard.M</span>
                            <div class="price-divider"></div>
                            <span class="price-value" data-lang="full-package-price">15 €</span>
                        </div>
                        <div class="price-item">
                            <span class="price-name" data-lang="full-package-title">Baard.N</span>
                            <div class="price-divider"></div>
                            <span class="price-value" data-lang="full-package-price">10 €</span>
                        </div>
                        <div class="price-item">
                            <span class="price-name" data-lang="full-package-title">Wassen</span>
                            <div class="price-divider"></div>
                            <span class="price-value" data-lang="full-package-price">5 €</span>
                        </div>
                        <div class="price-item">
                            <span class="price-name" data-lang="full-package-title">Wax</span>
                            <div class="price-divider"></div>
                            <span class="price-value" data-lang="full-package-price">5 €</span>
                        </div>
                    </div>
                    <!--2صندوق الأسعار -->
                    <div class="pricing-box">
                        <h3 data-lang="pricing-box-title">Beard Grooming</h3>

                        <div class="price-item">
                            <span class="price-name" data-lang="basic-cut-title">Knippen Heren</span>
                            <span class="price-divider"></span>
                            <span class="price-value" data-lang="basic-cut-price">20 €</span>
                        </div>
                        <div class="price-item">
                            <span class="price-name" data-lang="beard-grooming-title">Kinderen</span>
                            <div class="price-divider"></div>
                            <span class="price-value" data-lang="beard-grooming-price">15 €</span>
                        </div>
                        <div class="price-item">
                            <span class="price-name" data-lang="full-package-title">Baard.M</span>
                            <div class="price-divider"></div>
                            <span class="price-value" data-lang="full-package-price">15 €</span>
                        </div>
                        <div class="price-item">
                            <span class="price-name" data-lang="full-package-title">Baard.N</span>
                            <div class="price-divider"></div>
                            <span class="price-value" data-lang="full-package-price">10 €</span>
                        </div>
                        <div class="price-item">
                            <span class="price-name" data-lang="full-package-title">Wassen</span>
                            <div class="price-divider"></div>
                            <span class="price-value" data-lang="full-package-price">5 €</span>
                        </div>
                        <div class="price-item">
                            <span class="price-name" data-lang="full-package-title">Wax</span>
                            <div class="price-divider"></div>
                            <span class="price-value" data-lang="full-package-price">5 €</span>
                        </div>
                    </div>
                    <!--3صندوق الأسعار -->
                    <div class="pricing-box">
                        <h3 data-lang="pricing-box-title">Hair Caring</h3>

                        <div class="price-item">
                            <span class="price-name" data-lang="basic-cut-title">Knippen Heren</span>
                            <span class="price-divider"></span>
                            <span class="price-value" data-lang="basic-cut-price">20 €</span>
                        </div>
                        <div class="price-item">
                            <span class="price-name" data-lang="beard-grooming-title">Kinderen</span>
                            <div class="price-divider"></div>
                            <span class="price-value" data-lang="beard-grooming-price">15 €</span>
                        </div>
                        <div class="price-item">
                            <span class="price-name" data-lang="full-package-title">Baard.M</span>
                            <div class="price-divider"></div>
                            <span class="price-value" data-lang="full-package-price">15 €</span>
                        </div>
                        <div class="price-item">
                            <span class="price-name" data-lang="full-package-title">Baard.N</span>
                            <div class="price-divider"></div>
                            <span class="price-value" data-lang="full-package-price">10 €</span>
                        </div>
                        <div class="price-item">
                            <span class="price-name" data-lang="full-package-title">Wassen</span>
                            <div class="price-divider"></div>
                            <span class="price-value" data-lang="full-package-price">5 €</span>
                        </div>
                        <div class="price-item">
                            <span class="price-name" data-lang="full-package-title">Wax</span>
                            <div class="price-divider"></div>
                            <span class="price-value" data-lang="full-package-price">5 €</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <style>
        /* تنسيق الفوتور */
        
        footer {
            background-color: #333;
            /* لون الخلفية */
            color: #fff;
            /* لون النص */
            padding: 20px 0;
            /* تباعد داخلي */
            font-family: 'Tajawal', sans-serif;
            /* خط النص */
        }
        
        .footer-container {
            display: flex;
            justify-content: space-between;
            /* توزيع العناصر */
            align-items: center;
            /* توسيط العناصر عموديًا */
            max-width: 1200px;
            /* عرض الفوتور */
            margin: 0 auto;
            /* توسيط الفوتور */
            padding: 0 20px;
            /* تباعد جانبي */
        }
        
        .footer-left {
            display: flex;
            align-items: center;
            /* توسيط العناصر عموديًا */
            gap: 20px;
            /* تباعد بين العناصر */
        }
        
        .footer-left p {
            margin: 0;
            /* إزالة الهوامش */
            font-size: 0.9rem;
            /* حجم النص */
        }
        
        .footer-right {
            display: flex;
            gap: 15px;
            /* تباعد بين الأيقونات */
        }
        
        .footer-right a {
            color: #fff;
            /* لون الأيقونات */
            font-size: 1.2rem;
            /* حجم الأيقونات */
            transition: color 0.3s ease;
            /* تأثير انتقالي */
        }
        
        .footer-right a:hover {
            color: #ffcc00;
            /* تغيير لون الأيقونة عند التحويم */
        }
        
        @media (max-width: 768px) {
            .footer-left p {
                font-size: 0.7rem;
                /* حجم النص */
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
    <script src="java_header.js" defer></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const video = document.getElementById("intro-video");

            video.addEventListener("loadeddata", () => {
                video.classList.add("loaded");
            });
        });
    </script>
</body>

</html>