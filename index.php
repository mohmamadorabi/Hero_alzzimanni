<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barbershop Azzimani</title>
    <link rel="stylesheet" href="styles.css">
    <script src="java.js" defer></script>
    <script src="java_header.js" defer></script>
    <script src="https://kit.fontawesome.com/4bd66741d3.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
</head>

<body>
    <!-- استدعاء الهيدر هنا -->
    <?php include 'header.php'; ?>

    <style>
        .hero h1 {
            font-size: 48px;
            margin-bottom: 10px;
        }
        
        .hero .btn {
            display: inline-block;
            background: #ffcc00;
            color: #000;
            padding: 10px 20px;
            text-decoration: none;
            margin-top: 20px;
            font-weight: bold;
        }
        
        .slider {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            z-index: 1;
        }
        
        .slide {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            z-index: 1;
        }
        
        .slide.active {
            opacity: 1;
            z-index: 1;
        }
        
        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            filter: blur(10px);
            /* تمويه مبدئي */
            transform: scale(1.1);
            /* تكبير بسيط */
            transition: transform 1.5s ease, opacity 1.5s ease, filter 1.5s ease;
        }
        /* عند التفعيل */
        
        .slide.active img {
            opacity: 1;
            filter: blur(0);
            /* إزالة التمويه */
            transform: scale(1);
            /* العودة للحجم الطبيعي */
        }
        
        .dots {
            position: absolute;
            bottom: 10%;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 2;
        }
        
        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .dot.active {
            background: #ffcc00;
        }
        
        .caption {
            position: absolute;
            bottom: 20%;
            left: 10%;
            color: #fff;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.7);
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 1s ease, transform 1s ease;
        }
        
        .slide.active .caption {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
    <section class="hero">
        <div class="slider">
            <div class="slide">
                <img src="images/1.jpg" alt="Slide 1">
                <div class="caption">
                    <h1 id="slide1Title">A Cut Above the Rest</h1>
                    <p id="slide1Text">Experience precision and style with every snip.</p>
                </div>
            </div>
            <div class="slide">
                <img src="images/2.jpg" alt="Slide 2">
                <div class="caption">
                    <h1 id="slide2Title">Timeless Elegance</h1>
                    <p id="slide2Text">Classic grooming for the modern gentleman.</p>
                </div>
            </div>
            <div class="slide">
                <img src="images/3.jpeg" alt="Slide 3">
                <div class="caption">
                    <h1 id="slide3Title">The Art of Grooming</h1>
                    <p id="slide3Text">Redefining your style, one haircut at a time.</p>
                </div>
            </div>
            <div class="slide">
                <img src="images/4.jpg" alt="Slide 4">
                <div class="caption">
                    <h1 id="slide4Title">Sharp Looks, Smooth Finish</h1>
                    <p id="slide4Text">Tailored grooming to match your personality.</p>
                </div>
            </div>
            <div class="slide">
                <img src="images/5.jpg" alt="Slide 5">
                <div class="caption">
                    <h1 id="slide5Title">Confidence Starts Here</h1>
                    <p id="slide5Text">Feel your best with a perfect cut.</p>
                </div>
            </div>
            <div class="slide">
                <img src="images/6.jpg" alt="Slide 6">
                <div class="caption">
                    <h1 id="slide6Title">Refined & Redefined</h1>
                    <p id="slide6Text">Crafting your signature look with precision.</p>
                </div>
            </div>
            <div class="slide">
                <img src="images/7.jpg" alt="Slide 7">
                <div class="caption">
                    <h1 id="slide7Title">Where Tradition Meets Trend</h1>
                    <p id="slide7Text">Blending classic techniques with modern flair.</p>
                </div>
            </div>
            <div class="slide active">
                <img src="images/8.jpg" alt="Slide 8">
                <div class="caption">
                    <h1 id="slide8Title">Elevate Your Style</h1>
                    <p id="slide8Text">Step into sophistication and leave with confidence.</p>
                </div>
            </div>
            <div class="slide">
                <img src="images/9.jpg" alt="Slide 9">
                <div class="caption">
                    <h1 id="slide9Title">Mastering the Details</h1>
                    <p id="slide9Text">Attention to detail in every cut and trim.</p>
                </div>
            </div>
            <div class="slide">
                <img src="images/10.jpg" alt="Slide 10">
                <div class="caption">
                    <h1 id="slide10Title">Your Style, Our Expertise</h1>
                    <p id="slide10Text">Delivering tailored grooming for every gentleman.</p>
                </div>
            </div>
            <!-- نقاط التنقل -->
            <div class="dots">
                <span class="dot active" data-index="0"></span>
            </div>
        </div>
    </section>
    <!-- Services Section -->
    <style>
        /* تصميم القسم */
        
        #servicesTitle {
            font-size: 2rem;
            margin-bottom: 30px;
            color: #b3c41a;
        }
        /* تصميم الشبكة */
        
        .service-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            /* شبكة تلقائية */
            gap: 20px;
            justify-items: center;
            /* محاذاة الكروت في المنتصف */
        }
        /* تصميم الكروت */
        
        .service {
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            /* تأثير التمويه */
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            /* لجعل الكروت مدورة */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
            color: #fff;
        }
        
        .services {
            /* background: url('images/tools.jpg') no-repeat center center/cover; */
            position: relative;
            padding: 50px 20px;
            color: #fff;
        }
        
        .services::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            /* تعتيم */
            backdrop-filter: blur(5px);
            /* تعتيم الخلفية */
            z-index: -1;
        }
        
        .service:hover {
            transform: scale(1.15) rotate(5deg);
            /* تكبير مع دوران */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }
        
        .service i {
            font-size: 40px;
            color: #ffcc00;
            /* margin-bottom: 10px; */
            transition: color 0.3s;
        }
        
        .service:hover i {
            transform: scale(1.2);
            /* تكبير الأيقونة عند التمرير */
            color: #ff9900;
        }
        
        .service:hover h3,
        .service:hover p {
            opacity: 1;
            transform: translateY(-10px);
            /* رفع النص قليلاً */
            transition: all 0.3s ease;
        }
        
        .service h3 {
            font-size: 1.2rem;
            color: #ff9900;
            margin-bottom: 10px;
        }
        
        .service p {
            font-size: 0.9rem;
            color: white;
            line-height: 1.4;
        }
        /* تصميم متجاوب للشاشات الصغيرة */
        
        .wave-container {
            width: 100%;
            height: 120px;
            /* الارتفاع المناسب */
            overflow: hidden;
            /* منع المحتوى الزائد */
            background: none;
            position: relative;
            /* animation: wave-motion 5s infinite alternate; */
        }
        
        .wave-container svg {
            width: 120%;
            height: 100%;
            /* ضمان أن يملأ الـ SVG الحاوية */
            display: block;
            /* إزالة الفراغات الناتجة عن SVG كعنصر inline */
            position: absolute;
            bottom: 0;
            /* تثبيت الموجة في أسفل الحاوية */
        }
        
        @keyframes wave-motion {
            0% {
                transform: translateY(0);
            }
            100% {
                transform: translateY(10px);
            }
        }
        
        .wave-container path {
            animation: waveMoveAlt 8s ease-in-out infinite;
        }
        
        @keyframes waveMoveAlt {
            0% {
                transform: translateX(0);
            }
            50% {
                transform: translateX(-50px);
            }
            100% {
                transform: translateX(0);
            }
        }
        
        svg {
            width: 100%;
            display: block;
            margin-top: -5px;
        }
        
        @media (max-width: 480px) {
            .service-grid {
                grid-template-columns: 1fr 1fr;
                /* دائرتين في الصف */
                gap: 15px;
            }
            .service {
                width: 120px;
                height: 120px;
            }
            .service h3 {
                font-size: 1rem;
            }
            .service p {
                font-size: 0.8rem;
            }
        }
    </style>
    <section class="wave-container">
        <svg viewBox="0 0 1440 320" preserveAspectRatio="none">
            <path fill="#283747" fill-opacity="0.7" d="M0,64L30,85.3C60,107,120,149,180,160C240,171,300,149,360,144C420,139,480,149,540,165.3C600,181,660,203,720,197.3C780,192,840,160,900,138.7C960,117,1020,107,1080,117.3C1140,128,1200,160,1260,176C1320,192,1380,192,1410,192L1440,192L1440,320L1410,320C1380,320,1320,320,1260,320C1200,320,1140,320,1080,320C1020,320,960,320,900,320C840,320,780,320,720,320C660,320,600,320,540,320C480,320,420,320,360,320C300,320,240,320,180,320C120,320,60,320,30,320L0,320Z"></path>
            <path fill="#1c2833" fill-opacity="0.5" d="M0,128L60,160C120,192,240,256,360,250.7C480,245,600,171,720,144C840,117,960,139,1080,160C1200,181,1320,203,1380,213.3L1440,224L1440,320L1380,320C1320,320,1200,320,1080,320C960,320,840,320,720,320C600,320,480,320,360,320C240,320,120,320,60,320L0,320Z"></path>
            <path fill="#2e4053" fill-opacity="0.3" d="M0,160L50,176C100,192,200,224,300,202.7C400,181,500,107,600,101.3C700,96,800,160,900,165.3C1000,171,1100,117,1200,96C1300,75,1400,85,1450,90L1440,96L1440,320L1400,320C1360,320,1280,320,1200,320C1120,320,1040,320,960,320C880,320,800,320,720,320C640,320,560,320,480,320C400,320,320,320,240,320C160,320,80,320,40,320L0,320Z"></path>
        </svg>
        <!-- "#ffcc00" -->
    </section>
    <section class="services">
        <h2 id="servicesTitle">Our Services</h2>
        <div class="service-grid">
            <div class="service">
                <i class="fa fa-cut"></i>
                <h3 id="service1Title">Haircut</h3>
                <p id="service1Text">Modern styles to match your taste.</p>
            </div>
            <div class="service">
                <i class="fa fa-brush"></i>
                <h3 id="service2Title">Beard Grooming</h3>
                <p id="service2Text">Stay sharp and stylish.</p>
            </div>
            <div class="service">
                <i class="fa fa-spa"></i>
                <h3 id="service3Title">Skin Care</h3>
                <p id="service3Text">Refresh and rejuvenate your skin.</p>
            </div>
            <div class="service">
                <i class="fa fa-shield-alt"></i>
                <h3 id="service4Title">Hair Treatment</h3>
                <p id="service4Text">Protect and nourish your hair.</p>
            </div>
            <div class="service">
                <i class="fa fa-tint"></i>
                <h3 id="service5Title">Hair Coloring</h3>
                <p id="service5Text">Bold colors to express your style.</p>
            </div>
        </div>
    </section>
    <style>
        /* تخصيص الخط الفاصل */
        
        .separator {
            width: 100%;
            height: 3px;
            background: linear-gradient(to right, #ffcc00, #ff9900, #ffcc00);
            /* تأثير تدرجي */
            margin-top: 50px;
            /* إضافة مسافة فوق وتحت الخط */
            border: none;
            position: relative;
        }
        /* تأثير الظل للخط */
        
        .separator::before {
            content: "";
            position: absolute;
            top: -2px;
            /* تحريك الظل قليلاً فوق الخط */
            left: 0;
            width: 100%;
            height: 5px;
            background-color: rgba(0, 0, 0, 0.1);
            /* تأثير الظل */
            border-radius: 3px;
        }
        /* تنسيق القسم بالكامل */
        
        .contact-info {
            /* background-color: rgba(255, 255, 255, 0.5); */
            /* خلفية شفافة */
            padding: 50px 0px;
            text-align: center;
            background: none;
            /* backdrop-filter: blur(10px); */
            /* تأثير الزجاج */
            /* border-radius: 10px; */
            /* زوايا دائرية */
            /* box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15); */
            /* تأثير الظل */
        }
        /* حاوية القسم */
        
        .container {
            display: flex;
            justify-content: space-evenly;
            gap: 0;
            flex-wrap: wrap;
            flex-direction: row-reverse;
            /* عكس الترتيب ليصبح من اليمين لليسار */
        }
        /* تنسيق العناصر داخل كل عمود */
        
        .info-item {
            width: 33.33%;
            aspect-ratio: 1;
            background: rgba(255, 255, 255, 0.8);
            padding: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            flex: 1;
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .info-item i {
            font-size: 40px;
            color: #e01391;
            margin-bottom: 15px;
        }
        
        .info-item h3 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 10px;
        }
        
        .info-item p {
            font-size: 1rem;
            color: #555;
        }
        
        .info-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        /* زر الحجز */
        
        .info-item .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #e01391;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
        
        .info-item .btn:hover {
            background-color: #ffcc00;
        }
        
        .hour-item {
            display: flex;
            justify-content: space-between;
            margin: 0;
            margin: 0;
            /* إزالة المسافة بين العناصر */
            padding: 5px 0;
            /* تقليل المسافة بين السطور */
            line-height: 0;
            /* تقليل المسافة بين الأسطر */
        }
        
        .hour-item h4 h5 {
            color: #000000;
            margin: 0;
        }
        
        .info-item h3 {
            color: #ffcc00;
            text-align: center;
            font-size: 1.8rem;
        }
        
        .info-item:nth-child(1) {
            background-image: url('images/01.jpg');
            background-size: cover;
            background-position: center;
            padding: 3px 0;
            /* تقليل المسافة بين السطور */
            line-height: 0.1;
            /* box-sizing: border-box; */
        }
        
        .info-item:nth-child(1) h3 {
            color: white;
        }
        
        .info-item:nth-child(1) h4 {
            color: white;
        }
        
        .info-item:nth-child(1) h5 {
            color: white;
        }
        
        .info-item:nth-child(2) {
            background-color: #E8E3D7;
        }
        
        .info-item:nth-child(2) h3 {
            color: black;
            font-size: 3rem;
            font-weight: normal;
            text-shadow: 0 2px 5px rgba(63, 63, 63, 0.7);
            opacity: 1;
            transform: translateY(0px);
            transition: opacity 1s ease, transform 1s ease;
            margin-bottom: 15px;
        }
        
        .info-item:nth-child(2) p {
            word-wrap: break-word;
            /* لتمكين النص من الانكسار عند الحاجة */
            text-align: justify;
            margin: 0 20px;
            line-height: 2;
        }
        
        .info-item:nth-child(3) {
            background-image: url('images/02.jpg');
            background-size: cover;
            /* تغطي الصورة كامل المساحة */
            background-position: center;
            /* وضع الصورة في المنتصف */
            padding: 20px;
            /* border-radius: 10px; */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            /* width: 10; */
            text-align: start;
            display: flex;
            flex-direction: column;
            /* لضبط العناصر عموديًا */
            justify-content: center;
            /* توسيط العناصر عموديًا */
            align-items: center;
            /* توسيط العناصر أفقيًا */
            transition: transform 0.3s ease;
            position: relative;
        }
        
        .info-item:nth-child(3) p {
            color: #fff;
            /* width: 100%; */
            text-align: left;
        }
        
        .info-item:nth-child(3) h3 {
            text-shadow: 0 2px 5px rgba(63, 63, 63, 0.7);
            opacity: 1;
            transform: translateY(0px);
            transition: opacity 1s ease, transform 1s ease;
            margin-bottom: 15p
        }
        /* تخصيص العرض على الشاشات الصغيرة */
        
        .opening-Hours {
            border: 5px solid #ffcc00;
            border-radius: 10px;
            margin-top: 30px;
            margin-right: 30px;
            margin-left: 30px;
            padding-left: 20px;
            padding-right: 20px;
        }
        
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                align-items: center;
            }
            .info-item {
                /* width: 90%; */
                /* margin-bottom: 20px; */
                width: 100%;
                /* جعل الكرت يشغل عرض الصفحة بالكامل */
                box-sizing: border-box;
                /* تأكد من أن المسافات padding و border لا تؤثر على العرض */
                margin: 20px 0;
                /* مسافة بين الكروت */
            }
        }
    </style>
    <div class="separator"></div>
    <section class="contact-info">
        <div class="container">
            <!-- قسم مواعيد العمل -->
            <div class="info-item">
                <div class="opening-Hours">
                    <h3 id="openingHours">Openingstijden</h3>
                    <div class="hour-item">
                        <h4 id="monday">Maandag</h4>
                        <h5 id="mondayHours">10:00 - 20:00</h5>
                    </div>
                    <div class="hour-item">
                        <h4 id="tuesday">Dinsdag</h4>
                        <h5 id="tuesdayHours">10:00 - 20:00</h5>
                    </div>
                    <div class="hour-item">
                        <h4 id="wednesday">Woensdag</h4>
                        <h5 id="wednesdayHours">10:00 - 20:00</h5>
                    </div>
                    <div class="hour-item">
                        <h4 id="thursday">Donderdag</h4>
                        <h5 id="thursdayHours">10:00 - 20:00</h5>
                    </div>
                    <div class="hour-item">
                        <h4 id="friday">Vrijdag</h4>
                        <h5 id="fridayHours">10:00 - 13:00</h5>
                        <h5 id="fridayEvening">15:00 - 20:00</h5>
                    </div>
                    <div class="hour-item">
                        <h4 id="saturday">Zaterdag</h4>
                        <h5 id="saturdayHours">10:00 - 20:00</h5>
                    </div>
                    <div class="hour-item">
                        <h4 id="sunday">Zondag</h4>
                        <h5 id="sundayClosed">Gesloten</h5>
                    </div>
                </div>


                <div>
                    <span class="vc_sep_holder vc_sep_holder_l"><span class="vc_sep_line"></span></span>
                    <span class="vc_sep_holder vc_sep_holder_r"><span class="vc_sep_line"></span></span>
                </div>
                <div class="divider" style="width: 80%; border-top: 3px solid white;  margin: 10px auto;"></div>
                <div class="wpb_text_column wpb_content_element">
                    <div class="wpb_wrapper">
                        <h4 id="lunchBreak" style="text-align: center; color: white;">Gesloten tussen 12.00 en 13.00 uur</h4>
                    </div>
                </div>
            </div>
            <!-- قسم "About Us" -->
            <div class="info-item">
                <!-- <i class="fa fa-info-circle"></i> -->
                <h3 id="aboutUsTitle">About Us</h3>
                <img src="images/dark_seperator.png" alt="Image" class="hour-item-image">
                <p id="aboutUsText">At Barbershop Azzimani, we combine traditional barbering techniques with modern styles to create a personalized grooming experience. Our expert barbers take the time to understand your needs, ensuring you leave feeling refreshed and confident.
                    We pride ourselves on using high-quality products and offering a welcoming atmosphere for all our clients. Whether you're after a classic look or a contemporary style, we are committed to providing exceptional service every time you
                    visit. Join us and experience the perfect blend of luxury, tradition, and style at Barbershop Azzimani – where grooming meets excellence.</p>
            </div>
            <!-- قسم الاتصال والحجز -->
            <div class="info-item">
                <!-- <i class="fa fa-phone"></i> -->
                <h3>Barbershop Azzimani</h3>
                <p id="phone">call us: +123 456 7890</p>
                <a href="booking.php" class="btn" id="bookNow">Book Now</a>
            </div>
        </div>
    </section>
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
            /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); */
            display: inline-block;
            width: 30%;
            text-align: left;
            line-height: 3;
            flex-wrap: wrap;
            /* السماح بلف العناصر ضمن الصندوق */
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
        /* زر الحجز */
        
        .pricing-booking {
            display: inline-block;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            width: 100%;
            position: relative;
        }
        
        .pricing-booking .btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #ffcc00;
            color: #000;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 10px;
        }
        
        .pricing-booking .btn:hover {
            background-color: #e01391;
            color: #fff;
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
        
        .separator1 {
            width: 100%;
            height: 3px;
            background: linear-gradient(to right, #ffcc00, #ff9900, #ffcc00);
            border: none;
            position: relative;
        }
        /* تأثير الظل للخط */
        
        .separator1::before {
            content: "";
            position: absolute;
            top: -2px;
            /* تحريك الظل قليلاً فوق الخط */
            left: 0;
            width: 100%;
            height: 5px;
            background-color: rgba(0, 0, 0, 0.1);
            /* تأثير الظل */
            border-radius: 3px;
        }
    </style>
    <div class="separator1">
        <section class="pricing">
            <div class="pricing-container">
                <!-- العنوان -->
                <h2 data-lang="pricing-title">Our Pricing Plans</h2>

                <!-- الصورة -->
                <div class="pricing-image">
                    <img src="images/dark_seperator.png" alt="Pricing Image">
                </div>

                <!-- الوصف -->
                <div class="pricing-description" style="color: black;">
                    <p data-lang="pricing-description">At Barbershop Azzimani, we offer flexible pricing based on the services you choose. Each grooming experience is tailored to your specific needs, ensuring you get the best value for your visit.</p>
                </div>

                <!-- صندوق الأسعار -->
                <div class="pricing-box">
                    <h3 data-lang="pricing-box-title">Our Services Prices</h3>

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

                <!-- زر الحجز -->
                <div class="pricing-booking">
                    <!-- التنويه -->
                    <div class="pricing-note" style="color: black;">
                        <p><strong data-lang="pricing-note">Note:</strong> <span data-lang="pricing-note-description">Prices may vary depending on your pre-booking time or special requests. For more details, please contact us directly.</span></p>
                    </div>
                    <a href="booking.php" class="btn" data-lang="book-now">Book Now</a>

                </div>
            </div>

        </section>
    </div>

    <?php include 'footer.php'; ?>
    
</body>

</html>