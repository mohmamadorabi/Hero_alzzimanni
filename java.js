document.addEventListener("DOMContentLoaded", () => {
    const languageMenu = document.getElementById("language-menu");
    const languageBtn = document.getElementById("language-btn");
    const elementsToTranslate = document.querySelectorAll("[id]");

    // اختيار عناصر القائمة
    const menuIcon = document.getElementById("menu-icon");
    const mobileNav = document.getElementById("mobile-nav");

    const slides = document.querySelectorAll(".slide");
    const dots = document.querySelectorAll(".dot");
    const slider = document.querySelector(".slider");
    const dotsContainer = slider.querySelector(".dots");
    let currentIndex = 7;

    // اختيار جميع أيقونات اللغة
    const languageIcons = document.querySelectorAll(".language-icon");
    // فتح وإغلاق القائمة عند النقر
    menuIcon.addEventListener("click", () => {
        if (mobileNav.classList.contains("open")) {
            mobileNav.classList.remove("open"); // إغلاق القائمة
            mobileNav.style.height = "0"; // إعادة تعيين الارتفاع
        } else {
            mobileNav.classList.add("open"); // فتح القائمة
            mobileNav.style.height = mobileNav.scrollHeight + "px"; // ضبط الارتفاع
        }
    });

    // إخفاء القائمة أثناء تحريك الصفحة
    let lastScrollTop = 0;
    window.addEventListener("scroll", () => {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        if (scrollTop > lastScrollTop) {
            // المستخدم ينزل للأسفل
            mobileNav.classList.remove("open");
            mobileNav.style.height = "0";
        }
        lastScrollTop = scrollTop;
    });


    // النصوص لجميع اللغات
    const translations = {
        en: {
            // Header
            logo: "Barbershop Azzimani",
            navHome: "Home",
            navServices: "Services",
            navGallery: "Gallery",
            navShop: "Shop",
            navContact: "Contact",
            navHome1: "Home",
            navServices1: "Services",
            navGallery1: "Gallery",
            navShop1: "Shop",
            navContact1: "Contact",
            // Hero Section
            slide1Title: "A Cut Above the Rest",
            slide1Text: "Experience precision and style with every snip.",
            slide2Title: "Timeless Elegance",
            slide2Text: "Classic grooming for the modern gentleman.",
            slide3Title: "The Art of Grooming",
            slide3Text: "Redefining your style, one haircut at a time.",
            slide4Title: "Sharp Looks, Smooth Finish",
            slide4Text: "Tailored grooming to match your personality.",
            slide5Title: "Confidence Starts Here",
            slide5Text: "Feel your best with a perfect cut.",
            slide6Title: "Refined & Redefined",
            slide6Text: "Crafting your signature look with precision.",
            slide7Title: "Where Tradition Meets Trend",
            slide7Text: "Blending classic techniques with modern flair.",
            slide8Title: "Elevate Your Style",
            slide8Text: "Step into sophistication and leave with confidence.",
            slide9Title: "Mastering the Details",
            slide9Text: "Attention to detail in every cut and trim.",
            slide10Title: "Your Style, Our Expertise",
            slide10Text: "Delivering tailored grooming for every gentleman.",
            // Services Section
            servicesTitle: "Our Services",
            service1Title: "Haircut",
            service1Text: "Modern styles to match your taste.",
            service2Title: "Beard Grooming",
            service2Text: "Stay sharp and stylish.",
            service3Title: "Skin Care",
            service3Text: "Refresh and rejuvenate your skin.",
            service4Title: "Hair Treatment",
            service4Text: "Protect and nourish your hair.",
            service5Title: "Hair Coloring",
            service5Text: "Bold colors to express your style.",
            // contact - info
            openingHours: "Opening Hours",
            monday: "Monday",
            tuesday: "Tuesday",
            wednesday: "Wednesday",
            thursday: "Thursday",
            friday: "Friday",
            saturday: "Saturday",
            sunday: "Sunday",
            closed: "Closed",
            lunchBreak: "Closed between 12:00 and 13:00",
            aboutUsTitle: "About Us",
            aboutUsText: "At Barbershop Azzimani, we combine traditional barbering techniques with modern styles to create a personalized grooming experience. Our expert barbers take the time to understand your needs, ensuring you leave feeling refreshed and confident. We pride ourselves on using high-quality products and offering a welcoming atmosphere for all our clients. Whether you're after a classic look or a contemporary style, we are committed to providing exceptional service every time you visit. Join us and experience the perfect blend of luxury, tradition, and style at Barbershop Azzimani – where grooming meets excellence.",
            contactUsTitle: "Contact Us",
            phone: "Phone: +123 456 7890",
            bookNow: "Book Now",
            // Footer
            footerText: "© 2025 All Rights Reserved - Barbershop Azzimani",
        },
        ar: {
            // Header
            logo: "صالون أزيماني",
            navHome: "الرئيسية",
            navServices: "الخدمات",
            navGallery: "المعرض",
            navShop: "المتجر",
            navContact: "اتصل بنا",
            navHome1: "الرئيسية",
            navServices1: "الخدمات",
            navGallery1: "المعرض",
            navShop1: "المتجر",
            navContact1: "اتصل بنا",
            // Hero Section
            slide1Title: "قصة متميزة دائمًا",
            slide1Text: "جرب الدقة والأناقة مع كل قصّة.",
            slide2Title: "أناقة خالدة",
            slide2Text: "حلاقة كلاسيكية للرجل العصري.",
            slide3Title: "فن الحلاقة",
            slide3Text: "إعادة تعريف أسلوبك، قصة بعد أخرى.",
            slide4Title: "مظهر حاد ونهاية سلسة",
            slide4Text: "حلاقة مخصصة تناسب شخصيتك.",
            slide5Title: "الثقة تبدأ هنا",
            slide5Text: "شعر مثالي يمنحك الثقة.",
            slide6Title: "إتقان مُعاد تعريفه",
            slide6Text: "صناعة مظهرك المميز بدقة.",
            slide7Title: "حيث يلتقي التقليد بالحداثة",
            slide7Text: "مزج بين التقنيات الكلاسيكية والأناقة الحديثة.",
            slide8Title: "ارفع مستوى أناقتك",
            slide8Text: "ادخل بثقة واخرج بأناقة.",
            slide9Title: "إتقان التفاصيل",
            slide9Text: "الاهتمام بكل تفصيل في كل قصة وتشذيب.",
            slide10Title: "أسلوبك، خبرتنا",
            slide10Text: "تقديم خدمة حلاقة مخصصة لكل رجل.",
            // Services Section
            servicesTitle: "خدماتنا",
            service1Title: "قص الشعر",
            service1Text: "أنماط عصرية تناسب ذوقك.",
            service2Title: "تهذيب اللحية",
            service2Text: "ابقَ أنيقًا وجذابًا.",
            service3Title: "العناية بالبشرة",
            service3Text: "انتعش وجدد بشرتك.",
            service4Title: "علاج الشعر",
            service4Text: "حماية شعرك وتغذيته.",
            service5Title: "تلوين الشعر",
            service5Text: "ألوان جريئة للتعبير عن شخصيتك.",
            // contact - info
            openingHours: "مواعيد العمل",
            monday: "الاثنين",
            tuesday: "الثلاثاء",
            wednesday: "الأربعاء",
            thursday: "الخميس",
            friday: "الجمعة",
            saturday: "السبت",
            sunday: "الأحد",
            closed: "مغلق",
            lunchBreak: "مغلق بين الساعة 12:00 و 13:00",
            aboutUsTitle: "عنّا",
            aboutUsText: "في صالون أزيماني، ندمج تقنيات الحلاقة التقليدية مع الأساليب الحديثة لإنشاء تجربة حلاقة شخصية. يأخذ حلاقونا الخبراء الوقت لفهم احتياجاتك، مما يضمن لك الخروج وأنت تشعر بالانتعاش والثقة. نحن نفخر باستخدام منتجات عالية الجودة وتقديم بيئة ترحيبية لجميع عملائنا. سواء كنت تبحث عن مظهر كلاسيكي أو أسلوب عصري، نحن ملتزمون بتقديم خدمة استثنائية في كل مرة تزورنا فيها. انضم إلينا وتجربة المزيج المثالي من الفخامة والتقاليد والأناقة في صالون أزيماني – حيث تلتقي الحلاقة مع التميز.",
            contactUsTitle: "اتصل بنا",
            phone: "الهاتف: +123 456 7890",
            bookNow: "احجز الآن",
            // Footer
            footerText: "© 2025 جميع الحقوق محفوظة - صالون أزيماني",
        },
        nl: {
            // Header
            logo: "Kapper Azzimani",
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
            // Hero Section
            slide1Title: "Een stap boven de rest",
            slide1Text: "Ervaar precisie en stijl bij elke knipbeurt.",
            slide2Title: "Tijdloze elegantie",
            slide2Text: "Klassieke verzorging voor de moderne man.",
            slide3Title: "De kunst van het verzorgen",
            slide3Text: "Uw stijl opnieuw definiëren, één knipbeurt tegelijk.",
            slide4Title: "Scherpe looks, gladde afwerking",
            slide4Text: "Op maat gemaakte verzorging die bij uw persoonlijkheid past.",
            slide5Title: "Zelfvertrouwen begint hier",
            slide5Text: "Voel je op je best met een perfecte knipbeurt.",
            slide6Title: "Verfijnd & Heruitgevonden",
            slide6Text: "Uw kenmerkende look met precisie creëren.",
            slide7Title: "Waar traditie trend ontmoet",
            slide7Text: "Klassieke technieken gecombineerd met moderne flair.",
            slide8Title: "Verhoog uw stijl",
            slide8Text: "Stap in elegantie en verlaat met zelfvertrouwen.",
            slide9Title: "Meesterschap in details",
            slide9Text: "Aandacht voor detail bij elke knip- en trimbeurt.",
            slide10Title: "Uw stijl, onze expertise",
            slide10Text: "Op maat gemaakte verzorging voor elke heer.",
            // Services Section
            servicesTitle: "Onze Diensten",
            service1Title: "Haarknippen",
            service1Text: "Moderne stijlen die bij uw smaak passen.",
            service2Title: "Baardverzorging",
            service2Text: "Blijf scherp en stijlvol.",
            service3Title: "Huidverzorging",
            service3Text: "Verfris en verjong uw huid.",
            service4Title: "Haarbehandeling",
            service4Text: "Bescherm en voed uw haar.",
            service5Title: "Haar Kleuren",
            service5Text: "Gedurfde kleuren om uw stijl uit te drukken.",
            // contact - info
            openingHours: "Openingstijden",
            monday: "Maandag",
            tuesday: "Dinsdag",
            wednesday: "Woensdag",
            thursday: "Donderdag",
            friday: "Vrijdag",
            saturday: "Zaterdag",
            sunday: "Zondag",
            closed: "Gesloten",
            lunchBreak: "Gesloten tussen 12:00 en 13:00",
            aboutUsTitle: "Over Ons",
            aboutUsText: "Bij Barbershop Azzimani combineren we traditionele barbiertechnieken met moderne stijlen om een gepersonaliseerde verzorgingservaring te creëren. Onze deskundige kappers nemen de tijd om uw behoeften te begrijpen, zodat u zich verfrist en zelfverzekerd voelt bij vertrek. We zijn er trots op dat we hoogwaardige producten gebruiken en een gastvrije sfeer bieden voor al onze klanten. Of u nu op zoek bent naar een klassieke look of een eigentijdse stijl, we zijn toegewijd om elke keer uitzonderlijke service te bieden. Kom bij ons langs en ervaar de perfecte combinatie van luxe, traditie en stijl bij Barbershop Azzimani – waar verzorging uitmuntendheid ontmoet.",
            contactUsTitle: "Neem contact met ons op",
            phone: "Telefoon: +123 456 7890",
            bookNow: "Boek Nu",
            // Footer
            footerText: "© 2025 Alle rechten voorbehouden - Kapper Azzimani",
        },
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

    // إنشاء النقاط ديناميكيًا
    function createDots() {
        dotsContainer.innerHTML = ""; // مسح النقاط القديمة
        slides.forEach((_, index) => {
            const dot = document.createElement("span");
            dot.classList.add("dot");
            dot.dataset.index = index;
            if (index === currentIndex) {
                dot.classList.add("active");
            }
            dot.addEventListener("click", () => {
                currentIndex = index;
                updateSlides();
            });
            dotsContainer.appendChild(dot);
        });
    }

    // تحديث الشرائح والنقاط
    function updateSlides() {
        slides.forEach((slide, index) => {
            slide.classList.remove("active");
            if (index === currentIndex) {
                slide.classList.add("active");
            }
        });

        const dots = dotsContainer.querySelectorAll(".dot");
        dots.forEach((dot, index) => {
            dot.classList.remove("active");
            if (index === currentIndex) {
                dot.classList.add("active");
            }
        });
    }
    // التبديل التلقائي بين الشرائح
    function nextSlide() {
        currentIndex = (currentIndex + 1) % slides.length;
        updateSlides();
    }

    // إنشاء النقاط وعرض أول شريحة
    createDots();
    updateSlides();

    // بدء التبديل التلقائي
    setInterval(nextSlide, 5000);

});