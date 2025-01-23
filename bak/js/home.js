document.addEventListener("DOMContentLoaded", () => {
    const prefersDarkScheme = window.matchMedia("(prefers-color-scheme: dark)");

    const toggleDarkMode = () => {
        if (prefersDarkScheme.matches) {
            document.body.classList.add("dark-mode");
            document.body.classList.remove("light-mode");
        } else {
            document.body.classList.add("light-mode");
            document.body.classList.remove("dark-mode");
        }
    };

    toggleDarkMode();

    prefersDarkScheme.addEventListener("change", toggleDarkMode);

    // ==========================
    // SUPPORT FOR LANGUAGES
    // ==========================
    const translations = {
        en: {
            "header-title": "Welcome to Azorpub",
            "header-subtitle": "Your trusted partner for illuminated signs, 3D logos, and advertising solutions.",
            "nav-home": "Home",
            "nav-gallery": "Gallery",
            "nav-contact": "Contact Us",
            "about-title": "Why Choose Azorpub?",
            "about-description": "We specialize in crafting innovative illuminated signs that combine modern technology and creative designs to suit your business needs.",
            "feature-1": "High-quality materials and eco-friendly solutions.",
            "feature-2": "Creative 3D designs that make your business stand out.",
            "feature-3": "Customizable options for all types of businesses.",
            "footer-rights": "© 2024 Azorpub. All rights reserved.",
            "footer-developed": "Developed by Azorpub Team."
        },
        nl: {
            "header-title": "Welkom bij Azorpub",
            "header-subtitle": "Uw vertrouwde partner voor verlichte borden, 3D-logo's en reclameoplossingen.",
            "nav-home": "Startpagina",
            "nav-gallery": "Galerij",
            "nav-contact": "Contacteer Ons",
            "about-title": "Waarom kiezen voor Azorpub?",
            "about-description": "Wij zijn gespecialiseerd in innovatieve verlichte borden die moderne technologie en creatieve ontwerpen combineren.",
            "feature-1": "Hoogwaardige materialen en milieuvriendelijke oplossingen.",
            "feature-2": "Creatieve 3D-ontwerpen die uw bedrijf laten opvallen.",
            "feature-3": "Aanpasbare opties voor alle soorten bedrijven.",
            "footer-rights": "© 2024 Azorpub. Alle rechten voorbehouden.",
            "footer-developed": "Ontwikkeld door Azorpub Team."
        },
        ar: {
            "header-title": "مرحبًا بكم في Azorpub",
            "header-subtitle": "شريكك الموثوق لتصميم اللوحات الإعلانية المضيئة والشعارات ثلاثية الأبعاد.",
            "nav-home": "الرئيسية",
            "nav-gallery": "المعرض",
            "nav-contact": "اتصل بنا",
            "about-title": "لماذا تختار Azorpub؟",
            "about-description": "نحن متخصصون في تصميم اللوحات الإعلانية المضيئة باستخدام التكنولوجيا الحديثة والتصاميم الإبداعية لتلبية احتياجات عملك.",
            "feature-1": "مواد عالية الجودة وحلول صديقة للبيئة.",
            "feature-2": "تصاميم ثلاثية الأبعاد إبداعية تجعل عملك يبرز.",
            "feature-3": "خيارات قابلة للتخصيص لجميع أنواع الشركات.",
            "footer-rights": "© 2024 Azorpub. جميع الحقوق محفوظة.",
            "footer-developed": "تم التطوير بواسطة فريق Azorpub."
        }
    };


    const langSelector = document.getElementById("lang-selector");
    if (langSelector) {
        langSelector.addEventListener("change", (event) => {
            const selectedLang = event.target.value;
            document.querySelectorAll("[data-lang]").forEach((element) => {
                const key = element.getAttribute("data-lang");
                element.textContent = translations[selectedLang][key] || element.textContent;
            });
        });
    }
});