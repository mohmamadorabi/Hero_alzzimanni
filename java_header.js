document.addEventListener("DOMContentLoaded", () => {
    // اختيار عناصر القائمة
    const menuIcon = document.getElementById("menu-icon");
    const mobileNav = document.getElementById("mobile-nav");


    // اختيار جميع أيقونات اللغة
    // const languageIcons = document.querySelectorAll(".language-icon");
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
    // إغلاق القائمة عند النقر خارجها
    document.addEventListener("click", (e) => {
        if (mobileNav.classList.contains("open") && !mobileNav.contains(e.target) && !menuIcon.contains(e.target)) {
            mobileNav.classList.remove("open");
            mobileNav.style.height = "0";
        }
    });

});