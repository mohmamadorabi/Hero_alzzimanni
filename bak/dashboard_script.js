function toggleTheme() {
    const currentTheme = document.body.getAttribute("data-theme");
    const newTheme = currentTheme === "dark" ? "light" : "dark";
    document.body.setAttribute("data-theme", newTheme);

    // تحديث الأيقونة
    const themeIcon = document.getElementById("themeIcon");
    themeIcon.classList.toggle("fa-sun");
    themeIcon.classList.toggle("fa-moon");
}

// ضبط الوضع الافتراضي بناءً على تفضيلات النظام
const userPreference = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
document.body.setAttribute("data-theme", userPreference);

// ضبط الأيقونة عند التحميل
const themeIcon = document.getElementById("themeIcon");
if (document.body.getAttribute("data-theme") === "dark") {
    themeIcon.classList.remove("fa-sun");
    themeIcon.classList.add("fa-moon");
}