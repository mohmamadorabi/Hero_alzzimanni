function toggleTheme() {
    const currentTheme = document.body.getAttribute("data-theme");
    const newTheme = currentTheme === "dark" ? "light" : "dark";

    // تحديث السمة
    document.body.setAttribute("data-theme", newTheme);

    // تخزين الوضع في localStorage
    localStorage.setItem("theme", newTheme);

    // تحديث الأيقونة
    const themeIcon = document.getElementById("themeIcon");
    themeIcon.classList.toggle("fa-sun");
    themeIcon.classList.toggle("fa-moon");
}

// عند تحميل الصفحة، تطبيق الوضع المخزن
window.addEventListener("DOMContentLoaded", () => {
    const savedTheme = localStorage.getItem("theme") || "light"; // الوضع الافتراضي "light"
    document.body.setAttribute("data-theme", savedTheme);

    // ضبط الأيقونة بناءً على الوضع
    const themeIcon = document.getElementById("themeIcon");
    if (savedTheme === "dark") {
        themeIcon.classList.remove("fa-sun");
        themeIcon.classList.add("fa-moon");
    } else {
        themeIcon.classList.remove("fa-moon");
        themeIcon.classList.add("fa-sun");
    }
});