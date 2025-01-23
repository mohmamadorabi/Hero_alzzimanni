// تشغيل الكود بعد تحميل الصفحة بالكامل
document.addEventListener("DOMContentLoaded", function() {
    setupDateChangeListener();
    setupBookingFormListener();
    setupEmailValidation();
    setupTimeSelectionListener();
    // استدعاء الوظيفة عند تغيير التاريخ
    document.getElementById("date").addEventListener("change", function() {
        const selectedDate = this.value;
        fetchAvailableTimes(selectedDate);
    });

    // تنفيذ حجز مؤقت عند اختيار وقت
    document.getElementById("time").addEventListener("change", function() {
        const selectedDate = document.getElementById("date").value;
        const selectedTime = document.getElementById("time").value;
        const email = document.getElementById("email").value;

        if (!selectedDate || !selectedTime || !email) {
            console.error("Date, time, or email is missing.");
            return;
        }

        fetch('booking_handler.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=hold_time&date=${selectedDate}&time=${selectedTime}&email=${email}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    console.log(data.message);
                    startCountdown();
                } else {
                    alert(data.message);
                    document.getElementById("time").value = "";
                }
            })
            .catch(error => console.error('Error holding time:', error));
    });
});

// متغير لتخزين معرف العداد
let countdownInterval;

// وظيفة لتشغيل العداد
function startCountdown() {
    let countdown = 10; // 3 دقائق
    const countdownElement = document.getElementById("countdown-message");

    // تصفية أي عداد سابق
    clearInterval(countdownInterval);

    countdownElement.innerHTML = `Please confirm within ${countdown} seconds.`;

    countdownInterval = setInterval(() => {
        if (countdown > 0) {
            countdown--;
            countdownElement.innerHTML = `Please confirm within ${countdown} seconds.`;
        } else {
            clearInterval(countdownInterval);
            checkTimeAvailability(); // تحقق بعد انتهاء العداد
        }
    }, 1000);
}

// وظيفة للتحقق من توفر الوقت
function checkTimeAvailability() {
    const selectedDate = document.getElementById("date").value;
    let selectedTime = document.getElementById("time").value;
    const countdownElement = document.getElementById("countdown-message");

    if (!selectedDate || !selectedTime) {
        return; // الخروج إذا لم يكن هناك تاريخ أو وقت محدد
    }

    selectedTime = formatTimeToDatabase(selectedTime);

    fetch(`booking_handler.php?action=check_time&date=${selectedDate}&time=${selectedTime}`)
        .then(response => response.json())
        .then(data => {
            if (data.available) {
                startCountdown(); // إعادة تشغيل العداد إذا كان الوقت متاحًا
            } else {
                clearInterval(countdownInterval); // إيقاف العداد
                countdownElement.innerHTML = ""; // إزالة العداد من الصفحة
                document.getElementById("time").value = ""; // إعادة حقل الوقت إلى الافتراضي
            }
        })
        .catch(error => {
            console.error("Error checking time availability:", error);
        });
}


// وظيفة لتنسيق الوقت إلى صيغة HH:MM:SS
function formatTimeToDatabase(time) {
    let parts = time.split(":");
    if (parts.length === 2) {
        parts.push("00"); // إضافة الثواني المفقودة
    }
    return parts.join(":");
}

// وظيفة لمسح الوقت وإظهار الرسالة المناسبة
function resetTimeSelection() {
    document.getElementById("time").value = "";
    document.getElementById("countdown-message").innerHTML = "";
    alert("The selected time is no longer available. Please select another time.");
}

// تفعيل العداد عند اختيار وقت جديد
function setupTimeSelectionListener() {
    const timeSelect = document.getElementById("time");
    if (timeSelect) {
        timeSelect.addEventListener("change", function() {
            if (this.value) {
                startCountdown();
            }
        });
    } else {
        console.error("Time select element not found!");
    }
}

// التحقق من الأوقات المتاحة عند اختيار تاريخ
function setupDateChangeListener() {
    const dateInput = document.getElementById("date");
    const timeSelect = document.getElementById("time");

    if (dateInput && timeSelect) {
        dateInput.addEventListener("change", function() {
            const date = this.value;
            timeSelect.innerHTML = `<option value="">Loading...</option>`;

            fetch(`booking_handler.php?date=${date}`, { method: 'GET' })
                .then(response => {
                    if (!response.ok) throw new Error("Network response was not ok");
                    return response.json();
                })
                .then(data => {
                    timeSelect.innerHTML = `<option value="" disabled selected>-- Select a time --</option>`;
                    if (data.length > 0) {
                        data.forEach(time => {
                            const option = document.createElement("option");
                            option.value = time;
                            option.textContent = time;
                            timeSelect.appendChild(option);
                        });
                    } else {
                        timeSelect.innerHTML = `<option value="">No available times</option>`;
                    }
                })
                .catch(error => {
                    console.error("Error fetching available times:", error);
                    timeSelect.innerHTML = `<option value="">Error loading times</option>`;
                });
        });
    } else {
        console.error("Date or Time input not found!");
    }
}

// التحقق من البريد الإلكتروني
function setupEmailValidation() {
    const emailInput = document.getElementById("email");
    const emailStatus = document.getElementById("email-status");

    if (emailInput && emailStatus) {
        emailInput.addEventListener("blur", function() {
            const email = this.value;

            if (email) {
                fetch("booking_handler.php", {
                        method: "POST",
                        body: JSON.stringify({ email: email }),
                        headers: { 'Content-Type': 'application/json' }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "exists") {
                            emailStatus.innerHTML = `<div class="message warning">This email has an existing appointment (Status: ${data.appointment_status}).</div>`;
                        } else {
                            emailStatus.innerHTML = `<div class="message success">This email is available for booking.</div>`;
                        }
                    })
                    .catch(error => {
                        console.error("Error checking email:", error);
                        emailStatus.innerHTML = `<div class="message error">An error occurred while checking the email.</div>`;
                    });
            } else {
                emailStatus.innerHTML = "";
            }
        });
    } else {
        console.error("Email input or status element not found!");
    }
}

// إرسال نموذج الحجز
function setupBookingFormListener() {
    const bookingForm = document.getElementById("booking-form");
    const messageContainer = document.getElementById("message-container");
    const countdownElement = document.getElementById("countdown-message");
    const emailStatus = document.getElementById("email-status");

    if (bookingForm && messageContainer && countdownElement && emailStatus) {
        bookingForm.addEventListener("submit", function(event) {
            event.preventDefault();

            const formData = new FormData(bookingForm);
            messageContainer.innerHTML = `<div class="message loading">Processing your request...</div>`;

            fetch("process_booking.php", {
                    method: "POST",
                    body: formData,
                })
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    messageContainer.innerHTML = "";
                    const message = document.createElement("div");
                    message.textContent = data.message;

                    if (data.status === "success") {
                        bookingForm.reset(); // إعادة تعيين النموذج
                        clearInterval(countdownInterval); // إيقاف العداد
                        countdownElement.innerHTML = ""; // إخفاء العداد
                        emailStatus.innerHTML = ""; // إخفاء حالة البريد الإلكتروني
                        message.classList.add("message", "success");
                    } else {
                        message.classList.add("message", "error");
                    }
                    messageContainer.appendChild(message);
                })
                .catch(error => {
                    console.error("Error submitting form:", error);
                    messageContainer.innerHTML = `<div class="message error">An error occurred while processing your request. Please try again later.</div>`;
                });
        });
    } else {
        console.error("Booking form, countdown message, or email status element not found!");
    }
}

// تحديث الأوقات المتاحة بشكل ديناميكي باستخدام AJAX
function fetchAvailableTimes(selectedDate) {
    fetch(`booking_handler.php?date=${selectedDate}`, {
            method: 'GET',
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then(data => {
            const timeSelect = document.getElementById("time");
            timeSelect.innerHTML = `<option value="" disabled selected>-- Select a time --</option>`;

            if (data.length > 0 && data[0] !== "No available times") {
                data.forEach(time => {
                    const option = document.createElement("option");
                    option.value = time;
                    option.textContent = time;
                    timeSelect.appendChild(option);
                });
            } else {
                timeSelect.innerHTML = `<option value="">No available times</option>`;
            }
        })
        .catch(error => {
            console.error("Error fetching available times:", error);
            document.getElementById("time").innerHTML = `<option value="">Error loading times</option>`;
        });
}

// استدعاء الوظيفة عند تغيير التاريخ
document.getElementById("date").addEventListener("change", function() {
    const selectedDate = this.value;
    fetchAvailableTimes(selectedDate);
});