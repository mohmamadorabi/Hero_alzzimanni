// ============================
// GLOBAL SCRIPTS (مشتركة لجميع الصفحات)
// ============================
document.addEventListener("DOMContentLoaded", () => {
    const translations = {
        en: {
            "nav-home": "Home",
            "nav-gallery": "Gallery",
            "nav-contact": "Contact Us",
            "gallery-title": "Our Gallery",
            "footer-rights": "© 2024 Azorpub. All rights reserved.",
            "footer-developed": "Developed by Azorpub Team."
        },
        nl: {
            "nav-home": "Startpagina",
            "nav-gallery": "Galerij",
            "nav-contact": "Contacteer Ons",
            "gallery-title": "Onze galerij",
            "footer-rights": "© 2024 Azorpub. Alle rechten voorbehouden.",
            "footer-developed": "Ontwikkeld door Azorpub Team."
        },
        ar: {
            "nav-home": "الرئيسية",
            "nav-gallery": "المعرض",
            "nav-contact": "اتصل بنا",
            "gallery-title": "معرضنا",
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

// ============================
// HOME PAGE SCRIPTS (index.html)
// ============================
// يمكن إضافة وظائف خاصة للصفحة الرئيسية هنا

// ============================
// GALLERY PAGE SCRIPTS (gallery.html)
// ============================
// يمكن إضافة وظائف خاصة بصفحة المعرض هنا

// ============================
// CONTACT PAGE SCRIPTS (contact.html)
// ============================
// يمكن إضافة وظائف خاصة بصفحة اتصل بنا هنا