<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - Azorpub</title>
    <!-- <link rel="stylesheet" href="css/appointment.css">
    <script src="js/appointment.js" defer></script> -->

    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/main.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap');
         :root {
            --bg-color: #f8f9fa;
            --text-color: #333;
            --card-bg: #ffffff;
            --sidebar-bg: #f1f1f1;
            --highlight-color: #007bff;
            --border-color: #ddd;
            --hover-color: #e0e0e0;
            --h3-color: #000;
            --footer_headre: linear-gradient(135deg, #007bff, #8633b3);
            --bg-color_day: #f8f9fa;
            --font-family: 'Poppins', sans-serif;
        }
        
        [data-theme="dark"] {
            --bg-color: #121212;
            --text-color: #ffffff;
            --card-bg: #1e1e1e;
            --sidebar-bg: #1e1e1e;
            --highlight-color: #1e90ff;
            --border-color: #6e4242;
            --hover-color: #333;
            --h3-color: #ffcc00;
            --footer_headre: linear-gradient(135deg, #555, #777);
            --bg-color_day: #444;
            --font-family: 'Poppins', sans-serif;
        }
        
        html,
        body {
            font-family: var(--font-family);
            height: 100%;
            /* لجعل الصفحة بالكامل تغطي الشاشة */
            margin: 0;
        }
        
        body {
            display: flex;
            flex-direction: column;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg-color);
        }
        
        main {
            flex: 1;
            /* يتمدد لملء المساحة المتبقية */
        }
        
        header {
            padding: 20px;
            background: var(--footer_headre);
            color: var(--text-color);
            position: relative;
            /* clip-path: polygon(0 0, 100% 0, 100% 90%, 0 100%); */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }
        
        header img.logo {
            max-width: 150px;
            height: auto;
            object-fit: contain;
            /* لضمان الحفاظ على تناسب الصورة */
        }
        
        .nav-menu {
            display: flex;
            list-style: none;
            justify-content: center;
            gap: 20px;
            padding: 0;
            margin: 0;
        }
        
        .nav-menu li a {
            text-decoration: none;
            color: white;
            font-size: 16px;
            font-weight: bold;
            transition: color 0.3s ease;
        }
        
        .nav-menu li a:hover {
            color: #f1f1f1;
        }
        
        .language-selector {
            position: absolute;
            top: 10px;
            right: 20px;
        }
        
        .language-selector select {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            background-color: white;
            color: #007bff;
            font-weight: bold;
            cursor: pointer;
        }
        
        .booking-section {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: var(--card-bg);
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        #calendar {
            margin-top: 20px;
            border: 1px solid var(--border-color);
            padding: 10px;
            border-radius: 10px;
        }
        
        #calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            max-width: 500px;
            margin: 20px auto;
        }
        
        #time-slots {
            max-width: 500px;
            margin: 20px auto;
        }
        
        .slot {
            padding: 10px;
            margin: 5px 0;
            background: #f5f5f5;
            text-align: center;
            cursor: pointer;
            border: 1px solid #ddd;
        }
        
        .slot.unavailable {
            background: #ddd;
            cursor: not-allowed;
        }
        
        .slot.selected {
            background: #007bff;
            color: white;
        }
        
        #calendar-container {
            max-width: 500px;
            margin: 20px auto;
            text-align: center;
        }
        
        #current-month {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        
        #selected-date {
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: #00ff40;
        }
        
        .flex-container {
            display: flex;
            align-items: center;
            /* لمحاذاة العناصر عموديًا في الوسط */
            gap: 10px;
            /* مسافة بين العناصر */
        }
        
        #selected-date1 {
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: #007bff;
        }
        
        .day {
            padding: 10px;
            background: var(--bg-color_day);
            text-align: center;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            cursor: pointer;
            font-size: 0.9rem;
            color: var(--text-color);
        }
        
        .day.unavailable {
            background: #ddd;
            color: #999;
            cursor: not-allowed;
        }
        
        .day.selected {
            background: #007bff;
            color: white;
        }
        
        .grid-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            /* عدد الأعمدة */
            gap: 10px;
            max-width: 500px;
            margin: 20px auto;
        }
        
        .slot {
            padding: 10px;
            text-align: center;
            background: var(--bg-color_day);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            font-size: 0.9rem;
            color: var(--text-color);
        }
        
        .slot.unavailable {
            background: #ddd;
            color: #999;
            cursor: not-allowed;
        }
        
        .slot.selected {
            background: #007bff;
            color: white;
        }
        
        #form-details {
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-width: 500px;
            margin: 0 auto;
        }
        
        #form-details input {
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            outline: none;
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: border-color 0.3s ease;
        }
        
        #form-details input:focus {
            border-color: var(--border-color);
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            background: var(--bg-color);
        }
        
        #form-details button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        #form-details button:hover {
            background-color: #0056b3;
        }
        
        #summary {
            font-family: var(--font-family);
            display: flex;
            background: var(--bg-color_day);
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            max-width: 470px;
            margin: 20px auto;
            display: none;
            margin-top: 20px;
        }
        
        #summary h4 {
            color: #007bff;
            margin-bottom: 10px;
        }
        
        #summary p {
            font-size: 1rem;
            color: var(--text-color);
        }
        
        footer {
            background: var(--footer_headre);
            color: var(--text-color);
            text-align: center;
            padding: 15px 0;
            font-size: 14px;
        }
        
        .privacy-note {
            font-size: 0.9rem;
            color: #6c757d;
            /* لون رمادي خفيف */
            margin-top: 10px;
            text-align: center;
            /* محاذاة النص إلى المنتصف */
            line-height: 1.5;
        }
        /* دعم الشاشات الصغيرة جدًا */
        /* دعم الشاشات الصغيرة والمتوسطة */
        
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: center;
                padding: 10px 20px;
            }
            header .logo {
                max-width: 100px;
                /* تقليل حجم الشعار */
                margin-bottom: 10px;
            }
            .nav-menu {
                flex-direction: column;
                gap: 8px;
                /* تقليل المسافات */
                align-items: center;
                /* محاذاة الروابط إلى الوسط */
            }
            .nav-menu li {
                width: 100%;
                /* جعل الروابط تملأ العرض */
            }
            .nav-menu li a {
                font-size: 14px;
                padding: 10px;
                text-align: center;
                /* محاذاة النصوص إلى الوسط */
            }
            .language-selector {
                margin-top: 10px;
            }
            .booking-section {
                padding: 15px;
                margin: 10px;
            }
            #calendar {
                grid-template-columns: repeat(3, 1fr);
                /* تقليل الأعمدة */
                gap: 5px;
                /* تقليل المسافات بين الأيام */
            }
            .day {
                padding: 8px;
                /* تقليل حجم الأيام */
                font-size: 12px;
            }
            #time-slots {
                margin: 10px 0;
            }
            .slot {
                font-size: 12px;
                /* تقليل حجم النص */
                padding: 8px;
            }
            #form-details input,
            #form-details button {
                font-size: 14px;
                /* تصغير النص */
                padding: 8px;
                /* تقليل الحشوات */
                width: 100%;
                /* ملء العرض بالكامل */
            }
            #summary {
                font-size: 12px;
                /* تصغير النصوص */
                padding: 10px;
                text-align: center;
                /* محاذاة النصوص إلى الوسط */
            }
        }
        /* دعم الشاشات الصغيرة جدًا */
        
        @media (max-width: 480px) {
            header {
                padding: 10px;
            }
            header .logo {
                max-width: 80px;
                /* مزيد من تقليل حجم الشعار */
            }
            .nav-menu li a {
                font-size: 12px;
            }
            #calendar {
                grid-template-columns: repeat(2, 1fr);
                /* تقليل الأعمدة إلى اثنين */
            }
            .day {
                font-size: 10px;
                /* تصغير النصوص */
                padding: 6px;
            }
            #slots-container {
                grid-template-columns: repeat(2, 1fr);
                /* الأعمدة للساعات */
            }
            #summary {
                font-size: 10px;
                padding: 8px;
            }
            footer {
                font-size: 10px;
                /* تصغير النصوص في الفوتر */
            }
        }
    </style>

</head>

<body>
    <header>
        <div class="header-content"><img src="images/icons/logo 1143X636.png" alt="Azorpub Logo" class="logo">
            <nav>
                <ul class="nav-menu">
                    <li><a href="index.html" data-lang="nav-home">Home</a></li>
                    <li><a href="gallery.html" data-lang="nav-gallery">Gallery</a></li>
                    <li><a href="contact.html" data-lang="nav-contact">Contact Us</a></li>
                </ul>
            </nav>
            <div class="language-selector">
                <select id="lang-selector">
                    <option value="en">EN</option>
                    <option value="nl">NL</option>
                    <option value="ar">AR</option>
                </select>
            </div>
        </div>
    </header>

    <main>
        <section class="booking-section">
            <h2 data-lang="main-title" style=" text-align: center; color: var(--text-color);">Book Your Appointment</h2>
            <!-- التقويم -->

            <div id="calendar-container">
                <h2 id="current-month"></h2>
                <!-- عرض الشهر الحالي -->
                <div id="calendar"></div>
            </div>
            <div id="time-slots" style="display: none;">
                <div class="flex-container">
                    <h3 data-lang="selected-date" id="selected-date1">Selected Date:</h3>
                    <h3 id="selected-date"></h3>
                </div>


                <!-- عرض اليوم والشهر والسنة -->
                <div id="slots-container" class="grid-container"></div>

            </div>
            <!-- نموذج إدخال الاسم والبريد -->
            <div id="booking-form" style="display: none; margin-top: 20px;">
                <h3 data-lang="form-title" style=" text-align: center; color: var(--text-color); text-align: center;">Enter Your Details</h3>
                <form id="form-details">
                    <input type="text" id="name" name="name" placeholder="Your Name" required>
                    <input type="email" id="email" name="email" placeholder="Your Email" required>
                    <p id="booking-message" style="color: red; font-size: 14px; display: none;"></p>
                    <button data-lang="confirm-button" type="submit">Confirm Booking</button>
                </form>
                <!-- رسالة تظهر تحت الحقول -->
                <div id="email-exists-message" style="display: none; color: red; margin-top: 10px;">
                    This email already has a booking.
                    <button id="forgot-appointment" style="background: none; color: blue; text-decoration: underline; cursor: pointer;">Forgot your appointment?</button>
                </div>
                <p class="privacy-note" data-lang="privacy-note">
                    Your name and email will only be used to send you details about your appointment. We value your privacy.
                </p>
                <!-- إضافة عنصر الرسالة هنا -->
                <div id="booking-success-message" style="display: none; margin-top: 10px; padding: 10px; border: 1px solid #ddd; background-color: #f9f9f9; border-radius: 5px;">
                </div>
                <!-- عرض ملخص البيانات -->

                <div id="summary">
                    <h4 data-lang="summary-title">Booking Summary</h4>
                    <p id="summary-details"></p>
                    <button data-lang="book-now-button" id="book-now" style="margin-top: 10px; padding: 10px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">Book Now</button>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <p data-lang="footer-rights">© 2024 Azorpub. All rights reserved.</p>
        <p data-lang="footer-developed">Developed by Azorpub Team.</p>
    </footer>
    <script>
        const translations = {
            en: {
                "nav-home": "Home",
                "nav-gallery": "Gallery",
                "nav-contact": "Contact Us",
                "main-title": "Book Your Appointment",
                "form-title": "Enter Your Details",
                "summary-title": "Booking Summary",
                "confirm-button": "Confirm Booking",
                "book-now-button": "Book Now",
                "footer-rights": "© 2024 Azorpub. All rights reserved.",
                "footer-developed": "Developed by Azorpub Team",
                "selected-date": "Selected Date:",
                "please-enter-details": "Please enter valid details.",
                "booking-confirmed": "Booking Confirmed!",
                "privacy-note": "Your name and email will only be used to send you details about your appointment. We value your privacy."

            },
            nl: {
                "nav-home": "Startpagina",
                "nav-gallery": "Galerij",
                "nav-contact": "Contacteer ons",
                "main-title": "Boek uw afspraak",
                "form-title": "Vul uw gegevens in",
                "summary-title": "Samenvatting van de boeking",
                "confirm-button": "Bevestig afspraak",
                "book-now-button": "Boek nu",
                "footer-rights": "© 2024 Azorpub. Alle rechten voorbehouden.",
                "footer-developed": "Ontwikkeld door Azorpub Team",
                "selected-date": "Geselecteerde datum:",
                "please-enter-details": "Voer geldige gegevens in.",
                "booking-confirmed": "Boeking bevestigd!",
                "privacy-note": "Uw naam en e-mailadres worden alleen gebruikt om u details over uw afspraak te sturen. Wij hechten waarde aan uw privacy."

            },
            ar: {
                "nav-home": "الرئيسية",
                "nav-gallery": "المعرض",
                "nav-contact": "اتصل بنا",
                "main-title": "احجز موعدك",
                "form-title": "أدخل بياناتك",
                "summary-title": "ملخص الحجز",
                "confirm-button": "تأكيد الحجز",
                "book-now-button": "احجز الآن",
                "footer-rights": "© 2024 Azorpub. جميع الحقوق محفوظة.",
                "footer-developed": "تم التطوير بواسطة فريق Azorpub",
                "selected-date": "التاريخ المحدد:",
                "please-enter-details": "يرجى إدخال بيانات صحيحة.",
                "booking-confirmed": "تم تأكيد الحجز!",
                "privacy-note": "لن يتم استخدام اسمك أو بريدك الإلكتروني إلا لإرسال تفاصيل موعدك. نحن نحترم خصوصيتك."

            }
        };

        document.addEventListener('DOMContentLoaded', function() {

            // التحقق من تفضيل المستخدم الحالي
            const userPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            // تعيين الثيم بناءً على التفضيل
            if (userPrefersDark) {
                document.documentElement.setAttribute('data-theme', 'dark');
            } else {
                document.documentElement.setAttribute('data-theme', 'light');
            }

            // التحديث عند تغيير التفضيل
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                const newTheme = e.matches ? 'dark' : 'light';
                document.documentElement.setAttribute('data-theme', newTheme);
            });

            const langSelector = document.getElementById('lang-selector');
            const currentLang = localStorage.getItem('language') || 'en';
            const calendar = document.getElementById('calendar');
            const timeSlots = document.getElementById('time-slots');
            const slotsContainer = document.getElementById('slots-container');
            const bookingForm = document.getElementById('booking-form');
            const selectedDate = document.getElementById('selected-date');
            const bookingMessage = document.getElementById('booking-message');
            const emailExistsMessage = document.getElementById('email-exists-message');

            const today = new Date();
            const daysInMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0).getDate();
            const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

            // تعيين اللغة المحفوظة أو الافتراضية
            langSelector.value = currentLang;
            setLanguage(currentLang);

            // تغيير اللغة عند اختيار جديد
            langSelector.addEventListener('change', (e) => {
                const selectedLang = e.target.value;
                localStorage.setItem('language', selectedLang); // حفظ اللغة
                setLanguage(selectedLang);
            });
            // إنشاء التقويم
            for (let i = 1; i <= daysInMonth; i++) {
                const date = new Date(today.getFullYear(), today.getMonth(), i);
                const dayName = dayNames[date.getDay()];
                const day = document.createElement('div');
                day.className = 'day';
                day.innerText = `${i} (${dayName})`;

                if (dayName === 'Sun') {
                    day.classList.add('unavailable');
                } else {
                    day.addEventListener('click', () => selectDay(i, dayName));
                }

                calendar.appendChild(day);
            }

            function selectDay(day, dayName) {
                document.querySelectorAll('.day').forEach(el => el.classList.remove('selected'));
                const selectedDay = [...calendar.children].find(el => el.innerText.startsWith(day));
                selectedDay.classList.add('selected');

                const today = new Date();
                const currentMonth = today.getMonth() + 1; // الحصول على الشهر الحالي
                const year = today.getFullYear(); // السنة الحالية

                const fullDate = `${year}-${String(currentMonth).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                selectedDate.innerText = `${dayName}, ${fullDate}`;



                timeSlots.style.display = 'block';
                slotsContainer.innerHTML = '';
                bookingForm.style.display = 'none';
                const times = dayName === 'Sat' ? ['10:00 AM', '11:00 AM', '12:00 PM', '1:00 PM', '2:00 PM', '3:00 PM'] : ['10:00 AM', '11:00 AM', '12:00 PM', '1:00 PM', '2:00 PM', '3:00 PM', '4:00 PM', '5:00 PM'];

                times.forEach(time => {
                    const slot = document.createElement('div');
                    slot.className = 'slot';
                    slot.innerText = time;
                    slot.addEventListener('click', () => selectTime(slot));
                    slotsContainer.appendChild(slot);
                });
                // جلب الأوقات المحجوزة
                const selectedDateText = `${today.getFullYear()}-${(today.getMonth() + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
                loadBookedTimes(selectedDateText); // تحميل الأوقات المحجوزة للتاريخ المحدد


            }
            // دالة لتحويل الوقت إلى تنسيق 12 ساعة (يتطابق مع الوقت المحجوز في PHP)
            function convertTo12HourFormat(time24) {
                const [hours, minutes] = time24.split(':').map(Number);
                const period = hours >= 12 ? 'PM' : 'AM';
                const adjustedHours = hours % 12 || 12; // تحويل الساعة 0 إلى 12
                return `${adjustedHours}:${minutes.toString().padStart(2, '0')} ${period}`;
            }

            function formatTimeTo12Hour(time) {
                const [hours, minutes] = time.split(':').map(Number);
                const period = hours >= 12 ? 'PM' : 'AM';
                const adjustedHours = hours % 12 || 12;
                return `${adjustedHours}:${minutes.toString().padStart(2, '0')} ${period}`;
            }

            function loadBookedTimes(date) {
                fetch('process_booking.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            action: 'get_booked_times',
                            date: date,
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const times = ['10:00 AM', '11:00 AM', '12:00 PM', '1:00 PM', '2:00 PM', '3:00 PM', '4:00 PM', '5:00 PM'];
                            slotsContainer.innerHTML = ''; // إعادة تعيين قائمة الساعات

                            // تحويل الأوقات المحجوزة إلى تنسيق 12 ساعة
                            const bookedTimes12Hour = data.bookedTimes.map(convertTo12HourFormat);

                            times.forEach(time => {
                                const slot = document.createElement('div');
                                slot.className = 'slot';
                                slot.innerText = time;

                                // تحقق إذا كان الوقت محجوزًا
                                if (bookedTimes12Hour.includes(time)) {
                                    slot.classList.add('unavailable');
                                    slot.style.pointerEvents = 'none'; // منع التفاعل مع الوقت المحجوز
                                } else {
                                    slot.addEventListener('click', () => selectTime(slot));
                                }

                                slotsContainer.appendChild(slot);
                            });
                        } else {
                            alert('Error loading booked times.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }

            function selectTime(slot) {
                document.querySelectorAll('.slot').forEach(el => el.classList.remove('selected'));
                slot.classList.add('selected');
                bookingForm.style.display = 'block';
            }

            document.getElementById('form-details').onsubmit = function(e) {
                e.preventDefault();
                const name = document.getElementById('name').value.trim();
                const email = document.getElementById('email').value.trim();

                if (!name || !validateEmail(email)) {
                    bookingMessage.style.display = 'block';
                    bookingMessage.innerText = "Please enter valid details.";
                    return;
                }

                // التحقق من وجود الحجز
                checkBooking(email, () => {
                    emailExistsMessage.style.display = 'block'; // عرض رسالة وجود الموعد
                    bookingMessage.style.display = 'none'; // إخفاء أي رسائل أخرى
                }, () => {
                    emailExistsMessage.style.display = 'none'; // إخفاء رسالة الموعد
                    confirmBooking(name, email); // متابعة عملية الحجز
                });
            };

            document.getElementById('forgot-appointment').addEventListener('click', function() {
                const email = document.getElementById('email').value.trim();
                if (!validateEmail(email)) {
                    alert("Please enter a valid email.");
                    return;
                }
                sendCode(email);
            });

            function validateEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            function checkBooking(email, onExists, onNotExists) {
                fetch('process_booking.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            action: 'check',
                            email: email
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            onExists(); // إذا وجد الموعد
                        } else {
                            onNotExists(); // إذا لم يوجد الموعد
                        }
                    })
                    .catch(error => {
                        bookingMessage.style.display = 'block';
                        bookingMessage.innerText = "An error occurred while checking your booking.";
                        console.error("Error:", error);
                    });
            }

            function confirmBooking(name, email) {
                const selectedDateText = document.getElementById('selected-date').innerText.replace('Selected Date: ', '').split(', ')[1];

                const selectedTime = document.querySelector('.slot.selected').innerText;
                const timeIn24HourFormat = convertTo24HourFormat(selectedTime);


                // console /.log(selectedDateText);
                fetch('process_booking.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            action: 'book',
                            name: name,
                            email: email,
                            date: selectedDateText,
                            time: timeIn24HourFormat
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // alert(`Booking successful! Your unique code is: ${data.unique_code}`);
                            // تحديث الرسالة بدلاً من التنبيه
                            const successMessage = document.getElementById('booking-success-message');
                            successMessage.style.display = 'block';
                            successMessage.innerHTML = `
                                <p style="color: green; font-size: 16px;">Your booking is confirmed!</p>
                                <p>Date: <strong>${selectedDateText}</strong></p>
                                <p>Time: <strong>${selectedTime}</strong></p>
                                <p style="color: blue;">Unique Code: <strong>${data.unique_code}</strong></p>
                                <p>Please check your email for further details. Keep your unique code safe for modifications or cancellations.</p>
                            `;
                            // تحديث الوقت ديناميكياً
                            const bookedSlot = document.querySelector(`.slot.selected`);
                            if (bookedSlot) {
                                bookedSlot.classList.add('unavailable'); // إضافة كلاس "غير متاح"
                                bookedSlot.style.pointerEvents = 'none'; // منع النقر عليه مرة أخرى
                                bookedSlot.classList.remove('selected'); // إزالة كلاس "محدد"
                            }
                        } else {
                            alert("Error: " + data.message);
                        }
                    })
                    .catch(error => {
                        alert('An error occurred while booking. Please try again.');
                        console.error('Error:', error);
                    });
            }

            function sendCode(email) {
                fetch('process_booking.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            action: 'send_code',
                            email: email
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Your unique code has been sent to your email.');
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        alert('An error occurred while sending the code. Please try again.');
                        console.error('Error:', error);
                    });
            }

            function extractDate(fullText) {
                // مثال: fullText = "Sat, 2025-01-25"
                const datePart = fullText.split(', ')[1]; // يأخذ الجزء الثاني بعد الفاصلة
                return datePart; // النتيجة: "2025-01-25"
            }

            function getFormattedDate(day, month) {
                const today = new Date(); // للحصول على السنة الحالية
                const year = today.getFullYear(); // السنة الحالية
                const formattedMonth = String(month).padStart(2, '0'); // إضافة صفر إذا كان الشهر أقل من 10
                const formattedDay = String(day).padStart(2, '0'); // إضافة صفر إذا كان اليوم أقل من 10
                return `${year}-${formattedMonth}-${formattedDay}`; // تنسيق YYYY-MM-DD
            }

            // جلب الأوقات المحجوزة من الخادم
            function loadAvailableTimes(date) {
                fetch('process_booking.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            action: 'get_booked_times',
                            date: date
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const times = ['10:00 AM', '11:00 AM', '12:00 PM', '1:00 PM', '2:00 PM', '3:00 PM', '4:00 PM', '5:00 PM'];
                            slotsContainer.innerHTML = '';

                            times.forEach(time => {
                                const slot = document.createElement('div');
                                slot.className = 'slot';
                                slot.innerText = time;

                                if (data.bookedTimes.includes(time)) {
                                    slot.classList.add('unavailable');
                                    slot.style.pointerEvents = 'none';
                                } else {
                                    slot.addEventListener('click', () => selectTime(slot));
                                }

                                slotsContainer.appendChild(slot);
                            });
                        } else {
                            alert('Error loading booked times.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }

            function convertTo24HourFormat(time12) {
                const [time, modifier] = time12.split(' ');
                let [hours, minutes] = time.split(':');
                if (modifier === 'PM' && hours !== '12') {
                    hours = parseInt(hours, 10) + 12;
                }
                if (modifier === 'AM' && hours === '12') {
                    hours = '00';
                }
                return `${hours}:${minutes}:00`;
            }

        });


        document.getElementById('book-now').addEventListener('click', function() {
            // جمع البيانات من ملخص الحجز
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const selectedDateText = document.getElementById('selected-date').innerText.replace('Selected Date: ', '');
            const selectedTime = document.querySelector('.slot.selected').innerText;

            // تأكيد الحجز أو إرسال البيانات إلى الخادم
            alert(`Booking Confirmed!\n\nName: ${name}\nEmail: ${email}\nDate: ${selectedDateText}\nTime: ${selectedTime}`);

            // يمكنك هنا استبدال التنبيه بإرسال البيانات إلى الخادم باستخدام fetch أو AJAX.
        });

        function setLanguage(lang) {
            document.querySelectorAll('[data-lang]').forEach((element) => {
                const key = element.getAttribute('data-lang');
                if (translations[lang][key]) {
                    element.innerText = translations[lang][key];
                }
            });

            // تغيير اتجاه النص إذا كانت اللغة العربية
            if (lang === 'ar') {
                document.documentElement.setAttribute('dir', 'rtl');
            } else {
                document.documentElement.setAttribute('dir', 'ltr');
            }
            setLanguage_placeholders(lang); // لتغيير اللغة إلى الهولندية
        }

        function setLanguage_placeholders(lang) {
            const placeholders = {
                en: {
                    name: "Your Name",
                    email: "Your Email",
                    password: "Your Password"
                },
                nl: {
                    name: "Uw Naam",
                    email: "Uw E-mailadres",
                    password: "Uw Wachtwoord"
                },
                ar: {
                    name: "اسمك",
                    email: "بريدك الإلكتروني",
                    password: "كلمة مرورك"
                }
            };

            // تحديث النصوص في الحقول
            document.getElementById('name').setAttribute('placeholder', placeholders[lang].name);
            document.getElementById('email').setAttribute('placeholder', placeholders[lang].email);
        }

        document.getElementById('book-now').addEventListener('click', function() {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const selectedDateText = document.getElementById('selected-date').innerText.replace('Selected Date: ', '');
            const selectedTime = document.querySelector('.slot.selected').innerText;


            if (!name || !email) {
                alert("Please fill in all the details correctly.");
                return;
            }

            fetch('process_booking.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email,
                        date: selectedDateText,
                        time: selectedTime
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(`Booking successful! Your unique code is: ${data.unique_code}`);
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));

        });
    </script>


</body>

</html>