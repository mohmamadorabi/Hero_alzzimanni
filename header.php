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
            
        <style>
            /* تصميم الهيدر */
            
            header {
                width: 100%;
                max-width: 100vw;
                /* ضمان أن لا يتجاوز عرض الهيدر عرض الشاشة */
                backdrop-filter: blur(0);
                transition: backdrop-filter 0.5s ease-in-out;
                display: flex;
                justify-content: space-between;
                /* توزيع العناصر */
                align-items: center;
                justify-content: center;
                /* توسيط العناصر أفقيًا */
                /* تمركز عمودي */
                /* padding: 10px 20px; */
                background: rgba(255, 255, 255, 0.1);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                backdrop-filter: blur(10px);
                /* تأثير التمويه */
                border: 1px solid rgba(255, 255, 255, 0.1);
                color: #fff;
                position: sticky;
                top: 0;
                z-index: 1000;
                height: 90px;
                /* ارتفاع الهيدر */
                border-bottom: 2px solid #555;
                /* خط الفاصل */
            }
            
            body.loaded header {
                backdrop-filter: blur(10px);
            }
            /* الشعار في المنتصف */
            
            .logo {
                display: flex;
                align-items: center;
            }
            
            .logo img {
                height: 110px;
                /* تكبير الشعار */
                max-width: 120px;
                /* الحد الأقصى للعرض */
            }
            /* القائمة الرئيسية (الشاشات الكبيرة) */
            /* إخفاء القائمة الرئيسية على الموبايل */
            
            .main-nav {
                align-items: center;
                display: block;
            }
            
            .main-nav ul {
                list-style: none;
                display: flex;
                gap: 60px;
                /* مسافة بين العناصر */
                margin: 0;
                padding: 0;
            }
            
            .main-nav ul li a {
                color: #fff;
                text-decoration: none;
                font-size: 1.2rem;
                /* تكبير حجم النص */
                transition: color 0.3s ease;
                /* اجعل النص شفافًا */
                text-shadow: 1px 1px 2px rgba(0, 0, 0, 1), /* ظل داكن للحفر */
                -1px -1px 2px rgba(255, 255, 255, 0.2);
                /* إضاءة خفيفة من الأعلى */
                background: linear-gradient(135deg, #ffffff, #c0c0c0);
                /* خلفية للنص */
                -webkit-background-clip: text;
                /* قص الخلفية على النص */
                background-clip: text;
                animation: float 1s infinite alternate;
            }
            
            .main-nav ul li a:hover {
                color: #ffcc00;
                /* تغيير اللون عند التمرير */
            }
            
            .mobile-nav ul li a::after {
                content: "";
                position: absolute;
                bottom: -5px;
                /* المسافة بين الكلمة والخط السفلي */
                left: 0;
                width: 100%;
                /* امتداد الخط على عرض العنصر */
                height: 1px;
                background-color: #555;
                transition: background-color 0.3s ease;
            }
            
            .mobile-nav ul li a:hover::after {
                background-color: #ffcc00;
                /* تغيير لون الخط عند التمرير */
            }
            
            .main-nav ul li a:hover {
                color: #ffcc00;
            }
            /* زر القائمة (للموبايل) */
            
            .menu-icon {
                display: none;
                align-items: center;
                font-size: 1.8rem;
                cursor: pointer;
                color: #fff;
                margin: 15px 15px 15px 15px;
            }
            /* القائمة المنسدلة للموبايل */
            
            .mobile-nav {
                position: absolute;
                top: 90px;
                /* تحت الهيدر */
                left: 0;
                width: 100%;
                background: rgba(0, 0, 0, 0.7);
                /* خلفية شفافة وغامقة */
                backdrop-filter: blur(10px);
                /* تأثير الزجاج */
                -webkit-backdrop-filter: blur(10px);
                /* دعم Safari */
                overflow: hidden;
                height: 0;
                /* مخفية افتراضيًا */
                transition: height 0.5s ease;
                /* تأثير الانزلاق */
                border-top: 1px solid rgba(255, 255, 255, 0.2);
                /* خط فاصل شفاف */
                transition: height 0.5s ease;
                /* تأثير الانزلاق */
                /* border-top: 1px solid #555; */
                border-bottom: 2px solid #555;
                /* خط فاصل */
            }
            
            .mobile-nav.open ul {
                opacity: 1;
                /* ظهور العناصر */
            }
            
            .mobile-nav.open {
                height: 200px;
                /* ضبط الارتفاع الكافي لعرض الروابط */
            }
            
            .mobile-nav ul {
                list-style: none;
                padding: 10px 20px;
                margin: 0;
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
            
            .mobile-nav ul li {
                position: relative;
            }
            
            .mobile-nav ul li a {
                /* font-family: 'Oswald'; */
                display: block;
                color: #fff;
                text-decoration: none;
                font-size: 1.1rem;
                padding: 15px 15px;
                /* التحكم بالمسافة العمودية */
                text-align: left;
                position: relative;
                /* ضروري لإظهار ::after */
                /* اجعل النص شفافًا */
                text-shadow: 1px 1px 2px rgba(0, 0, 0, 1), /* ظل داكن للحفر */
                -1px -1px 2px rgba(255, 255, 255, 0.2);
                /* إضاءة خفيفة من الأعلى */
                background: linear-gradient(135deg, #ffffff, #c0c0c0);
                /* خلفية للنص */
                -webkit-background-clip: text;
                /* قص الخلفية على النص */
                background-clip: text;
                animation: float 1s infinite alternate;
            }
            
            .mobile-nav ul li a:hover {
                color: #ffcc00;
                border-bottom-color: #ffcc00;
                /* تغيير لون الخط عند التمرير */
            }
            /* تصميم متجاوب للشاشات الصغيرة */
            /* تنسيق الأيقونات اللغوية */
            
            .language-options {
                display: flex;
                justify-content: center;
                /* توسيط الأيقونات */
                gap: 15px;
                /* مسافة بين الأيقونات */
                padding: 10px 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.2);
                /* خط فاصل تحت الأيقونات */
            }
            
            .language-icon {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                /* أيقونات دائرية */
                background: rgba(255, 255, 255, 0.1);
                transition: background 0.3s ease;
            }
            
            .language-icon .flag-icon {
                width: 24px;
                height: 18px;
                border-radius: 3px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            }
            
            .language-icon:hover {
                background: rgba(255, 255, 255, 0.3);
            }
            /* أزرار اللغة للشاشات الكبيرة */
            
            .language-options.desktop-only {
                display: flex;
                flex-direction: column;
                /* ترتيب عمودي */
                position: absolute;
                right: 20px;
                /* الجهة اليمنى */
                /* top: 120px; */
                /* المسافة من الأعلى */
                gap: 10px;
                /* مسافة بين الأيقونات */
                z-index: 1000;
            }
            /* إخفاء أزرار اللغة للشاشات الكبيرة على الشاشات الصغيرة */
            
            @media (max-width: 768px) {
                .language-options.desktop-only {
                    display: none;
                }
            }
            /* أزرار اللغة داخل ناف الموبايل */
            
            .mobile-nav .language-options {
                display: flex;
                flex-direction: row;
                /* ترتيب أفقي */
                justify-content: center;
                gap: 10px;
                /* مسافة بين الأيقونات */
                padding: 10px 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.2);
                /* خط فاصل */
            }
            
            @media (max-width: 768px) {
                header {
                    justify-content: space-between;
                }
                .main-nav {
                    display: none;
                    /* إخفاء القائمة الرئيسية */
                }
                .menu-icon {
                    display: flex;
                    /* إظهار زر القائمة */
                    /* زر القائمة يظهر */
                }
                .logo img {
                    height: 100px;
                    /* تصغير حجم الشعار */
                }
            }
            /* تحسين التنسيق للشاشات الكبيرة */
            
            @media (min-width: 992px) {
                header {
                    /* padding: 30px 60px; */
                    /* زيادة المسافات */
                    height: 180px;
                    /* تكبير الهيدر أكثر */
                    position: sticky;
                    top: 0;
                    left: 0;
                    width: 100%;
                    z-index: 1000;
                    background: linear-gradient(to bottom, rgba(0, 0, 0, 0.6), transparent);
                    backdrop-filter: blur(10px);
                }
                .logo {
                    margin: 100px;
                }
                .logo img {
                    height: 240px;
                    /* تكبير الشعار */
                    max-width: 260px;
                    /* تكبير الشعار أكثر */
                }
                .main-nav ul li a {
                    font-size: 1.5rem;
                    /* تكبير حجم النص أكثر */
                }
                .mobile-nav {
                    display: none;
                }
            }
        </style>

    </head>
    <header>
        <nav class="main-nav">
            <ul>
                <li><a id="navHome" href="index.php">Home</a></li>
                <li><a id="navServices" href="services.php">Services</a></li>
                <li><a id="navGallery" href="#gallery">Gallery</a></li>
            </ul>

        </nav>
        <!-- الشعار -->
        <div class="logo">
            <a href="index.php">
                <img src="./images/logo2.png" alt="Barbershop Azzimani Logo">
            </a>
        </div>
        <nav class="main-nav">
            <ul>
                <li><a id="navShop" href="#shop">Shop</a></li>
                <li><a id="navContact" href="contact.php">Contact</a></li>
            </ul>

        </nav>
        <!-- أزرار اللغة للشاشات الكبيرة -->
        <div class="language-options desktop-only">
            <a href="#" class="language-icon" id="nl" title="Dutch">
                <span class="flag-icon flag-icon-nl"></span>
            </a>
            <a href="#" class="language-icon" id="en" title="English">
                <span class="flag-icon flag-icon-gb"></span>
            </a>
            <a href="#" class="language-icon" id="ar" title="Arabic">
                <span class="flag-icon flag-icon-sa"></span>
            </a>
        </div>
        <!-- زر القائمة للموبايل -->
        <div class="menu-icon" id="menu-icon">
            <i class="fa-solid fa-bars"></i>
            <!-- أيقونة القائمة -->
        </div>

        <!-- القائمة المنسدلة للموبايل -->
        <nav class="mobile-nav" id="mobile-nav">
            <!-- قسم الأيقونات اللغوية -->
            <div class="language-options">
                <a href="#" class="language-icon" id="nl" title="Dutch">
                    <span class="flag-icon flag-icon-nl"></span>
                </a>
                <a href="#" class="language-icon" id="en" title="English">
                    <span class="flag-icon flag-icon-gb"></span>
                </a>
                <a href="#" class="language-icon" id="ar" title="Arabic">
                    <span class="flag-icon flag-icon-sa"></span>
                </a>
            </div>
            <ul>
                <li><a id="navHome1" href="index.php">Home</a></li>
                <li><a id="navServices1" href="services.php">Services</a></li>
                <li><a id="navGallery1" href="#gallery">Gallery</a></li>
                <li><a id="navShop1" href="#shop">Shop</a></li>
                <li><a id="navContact1"  href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>
</html>