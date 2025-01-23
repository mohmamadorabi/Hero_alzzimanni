document.addEventListener("DOMContentLoaded", () => {
    const galleryImages = document.querySelectorAll(".gallery img");

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

    galleryImages.forEach((img) => {
        img.addEventListener("click", () => {
            const overlay = document.createElement("div");
            overlay.classList.add("lightbox-overlay");

            const lightboxImage = document.createElement("img");
            lightboxImage.src = img.src;
            lightboxImage.alt = img.alt;
            lightboxImage.classList.add("lightbox-image");

            overlay.appendChild(lightboxImage);

            document.body.appendChild(overlay);

            overlay.addEventListener("click", () => {
                document.body.removeChild(overlay);
            });
        });
    });
    // الترجمات
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
    // تغيير اللغة بناءً على الاختيار
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