document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector(".contact100-form");
    const prefersDarkScheme = window.matchMedia("(prefers-color-scheme: dark)");

    // تفعيل الوضع الداكن أو الفاتح بناءً على النظام
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
        emailjs.send("service_its8tbt", "template_xezemri", parms)
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

    // النصوص المترجمة
    const translations = {
        en: {
            "nav-home": "Home",
            "nav-gallery": "Gallery",
            "nav-contact": "Contact Us",
            "contact-title": "Contact Us",
            "contact-submit": "Send Email",
            "contact-email": "Email:",
            "contact-whatsapp": "WhatsApp:",
            "footer-rights": "© 2024 Azorpub. All rights reserved.",
            "footer-developed": "Developed by Azorpub Team.",
        },
        nl: {
            "nav-home": "Startpagina",
            "nav-gallery": "Galerij",
            "nav-contact": "Contacteer Ons",
            "contact-title": "Contacteer Ons",
            "contact-submit": "E-mail Verzenden",
            "contact-email": "E-mail:",
            "contact-whatsapp": "WhatsApp:",
            "footer-rights": "© 2024 Azorpub. Alle rechten voorbehouden.",
            "footer-developed": "Ontwikkeld door Azorpub Team.",
        },
        ar: {
            "nav-home": "الرئيسية",
            "nav-gallery": "المعرض",
            "nav-contact": "اتصل بنا",
            "contact-title": "اتصل بنا",
            "contact-submit": "إرسال البريد",
            "contact-email": "البريد الإلكتروني:",
            "contact-whatsapp": "واتساب:",
            "footer-rights": "© 2024 Azorpub. جميع الحقوق محفوظة.",
            "footer-developed": "تم التطوير بواسطة فريق Azorpub.",
        },
    };

    // تغيير اللغة عند الاختيار
    const langSelector = document.getElementById("lang-selector");
    if (langSelector) {
        langSelector.addEventListener("change", (event) => {
            const selectedLang = event.target.value; // اللغة المختارة
            document.querySelectorAll("[data-lang]").forEach((element) => {
                const key = element.getAttribute("data-lang"); // المفتاح
                if (translations[selectedLang][key]) {
                    if (key === "contact-email") {
                        element.innerHTML = `${translations[selectedLang][key]} <a href="mailto:info@azorpub.com">info@azorpub.com</a>`;
                    } else if (key === "contact-whatsapp") {
                        element.innerHTML = `${translations[selectedLang][key]} <a href="https://wa.me/+32465720410">+32 465 72 04 10</a>`;
                    } else {
                        element.textContent = translations[selectedLang][key];
                    }
                }
            });
        });
    }
});