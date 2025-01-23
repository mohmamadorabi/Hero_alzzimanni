// تشغيل الكود بعد تحميل الصفحة بالكامل
document.addEventListener("DOMContentLoaded", function() {
    const bookingForm = document.getElementById("booking-form");
    const confirmButton = document.getElementById("confirm-code");
    const codeInput = document.getElementById("confirmation-code");
    const emailInput = document.getElementById("email");
    const sendCodeButton = document.getElementById("send-confirmation-code");
    const countdownElement = document.getElementById("countdown-timer");
    // الحصول على العناصر
    const nameInput = document.getElementById("name");
    const phoneInput = document.getElementById("phone");
    const serviceInput = document.getElementById("service");
    const dateInput = document.getElementById("date");
    const timeInput = document.getElementById("time");
    const messageContainer = document.getElementById("message-container");
    const confirmation_section = document.getElementById("confirmation-section");
    let countdownInterval;
    document.getElementById("date").addEventListener("change", function() {
        const selectedDate = this.value;
        fetchAvailableTimes(selectedDate);
    });
    const emailStatus = document.getElementById("email-status");
    let emailCheckTimeout;
    emailInput.addEventListener("input", function() {
        clearTimeout(emailCheckTimeout);

        const email = emailInput.value.trim();

        // التحقق من التنسيق الأساسي للبريد الإلكتروني
        if (!isValidEmail(email)) {
            emailStatus.innerHTML = `<div class="message error">Invalid email format.</div>`;
            return;
        } else {
            emailStatus.innerHTML = "";
        }

        // التحقق بعد إدخال نطاق البريد الإلكتروني
        if (email.includes('@') && email.split('@')[1].includes('.')) {
            emailCheckTimeout = setTimeout(() => {
                checkEmailAvailability(email);
            }, 500); // التأخير 500 مللي ثانية لتجنب الاستعلامات المتكررة
        }
    });

    function isValidEmail(email) {
        // التحقق من تنسيق البريد الإلكتروني عبر التعبيرات النمطية
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return emailRegex.test(email);
    }

    function checkEmailAvailability(email) {
        fetch("booking_handler.php", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'check_email', email: email })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "exists") {
                    emailStatus.innerHTML = `<div class="message warning">This email is already registered.</div>`;
                } else {
                    emailStatus.innerHTML = `<div class="message success">This email is available for booking.</div>`;
                }
            })
            .catch(error => {
                console.error("Error checking email:", error);
                emailStatus.innerHTML = `<div class="message error">An error occurred while checking the email.</div>`;
            });
    }


    // إضافة مستمع لكل حقل للتحقق عند التغيير
    [nameInput, emailInput, phoneInput, serviceInput, dateInput, timeInput].forEach(input => {
        input.addEventListener("input", function() {
            validateFormFields();
        });
    });

    // عند الضغط على زر إرسال الكود
    sendCodeButton.addEventListener("click", function() {
        const email = emailInput.value.trim();
        if (!isValidEmail(email)) {
            messageContainer.innerHTML = `<div class="message error">Invalid email format.</div>`;
            return;
        }
        if (!validateFormFields()) {
            return; // إذا كانت هناك أخطاء، لا تكمل العملية
        }

        document.getElementById("loading-indicator").style.display = "block";
        sendCodeButton.disabled = true; // تعطيل الزر أثناء العملية


        const selectedDate = dateInput.value;
        const selectedTime = timeInput.value;
        const name = nameInput.value.trim();
        const phone = phoneInput.value.trim();
        const service = serviceInput.value;

        // التحقق مما إذا كان الحجز موجود مسبقًا
        fetch("booking_handler.php", {
                method: "POST",
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'check_existing_appointment',
                    email: email,
                    date: selectedDate,
                    time: selectedTime
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "exists") {
                    // إذا كان الحجز موجودًا، إعادة إرسال الكود
                    resendConfirmationCode(email, selectedDate, selectedTime);
                } else {
                    // إذا لم يكن موجودًا، إنشاء حجز جديد
                    bookNewAppointment(name, email, phone, service, selectedDate, selectedTime);
                }
            })
            .catch(error => {
                console.error("Error checking appointment:", error);
                messageContainer.innerHTML = `<div class="message error">An error occurred while checking the appointment.</div>`;
                document.getElementById("loading-indicator").style.display = "none";
                sendCodeButton.disabled = false;
            });
    });
    // وظيفة إعادة إرسال الكود
    function resendConfirmationCode(email, date, time) {
        fetch("booking_handler.php", {
                method: "POST",
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'resend_confirmation_code',
                    email: email,
                    date: date,
                    time: time
                })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById("loading-indicator").style.display = "none";
                if (data.status === "success") {
                    startCountdown();
                    messageContainer.innerHTML = `<div class="message success">Confirmation code re-sent. Check your email.</div>`;
                } else {
                    messageContainer.innerHTML = `<div class="message error">${data.message}</div>`;
                }
            })
            .catch(error => {
                console.error("Error resending confirmation code:", error);
                messageContainer.innerHTML = `<div class="message error">An error occurred while resending the confirmation code.</div>`;
            });
    }
    // وظيفة لحجز موعد جديد
    function bookNewAppointment(name, email, phone, service, date, time) {
        fetch("booking_handler.php", {
                method: "POST",
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'send_confirmation_code',
                    name: name,
                    email: email,
                    phone: phone,
                    service: service,
                    date: date,
                    time: time
                })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById("loading-indicator").style.display = "none";
                if (data.status === "success") {
                    startCountdown();
                    messageContainer.innerHTML = `<div class="message success">Confirmation code sent successfully. Check your email.</div>`;
                } else {
                    sendCodeButton.disabled = false; // تعطيل الزر أثناء العملية

                    messageContainer.innerHTML = `<div class="message error">${data.message}</div>`;
                }
            })
            .catch(error => {
                console.error("Error booking appointment:", error);
                messageContainer.innerHTML = `<div class="message error">An error occurred while booking the appointment.</div>`;
            });
    }


    document.getElementById("confirm-code").addEventListener("click", function() {
        const confirmationCode = document.getElementById("confirmation-code").value;

        if (!confirmationCode) {
            alert("Please enter the confirmation code.");
            return;
        }
        // إظهار مؤشر التحميل
        document.getElementById("loading-indicator").style.display = "block";
        fetch("booking_handler.php", {
                method: "POST",
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'verify_code', code: confirmationCode })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById("loading-indicator").style.display = "none";
                if (data.status === "success") {
                    document.getElementById("confirmation-section").style.display = "none";
                    document.getElementById("loading-indicator").style.display = "none";
                    clearInterval(countdownInterval);
                    sendCodeButton.disabled = false;
                    document.getElementById("send-confirmation-code").textContent = "Send Confirmation Code";
                    document.getElementById("booking-form").reset();
                    document.getElementById("countdown-timer").innerHTML = "";
                    document.getElementById("email-status").innerHTML = "";
                    document.getElementById("message-container").innerHTML = "Your appointment has been successfully booked, and a confirmation email with the details has been sent.";
                    // alert("Your appointment has been confirmed successfully!");
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                document.getElementById("loading-indicator").style.display = "none";
                console.error("Error verifying confirmation code:", error);
                alert("An error occurred while verifying the confirmation code.");
            });
    });

    function startCountdown() {
        sendCodeButton.disabled = true; // تعطيل الزر أثناء العد التنازلي
        document.getElementById("confirmation-section").style.display = "block";
        const countdownElement = document.getElementById("countdown_btn");

        let countdown = 30; // 60
        countdownElement.textContent = countdown;

        countdownInterval = setInterval(() => {
            if (countdown > 0) {
                countdown--;
                countdownElement.textContent = countdown;
                sendCodeButton.disabled = true;
            } else {
                clearInterval(countdownInterval);
                countdownElement.innerHTML = "";
                sendCodeButton.disabled = false;
                // alert("Time expired. Please request a new confirmation code.");
            }
        }, 1000);
    }

    function validateFormFields() {
        let errors = [];

        if (!nameInput.value.trim()) {
            errors.push("Name is required.");
        } else if (!emailInput.value.trim()) {
            errors.push("Email is required.");
        } else if (!phoneInput.value.trim()) {
            errors.push("Phone number is required.");
        } else if (!serviceInput.value.trim()) {
            errors.push("Please select a service.");
        } else if (!dateInput.value.trim()) {
            errors.push("Please select a date.");
        } else if (!timeInput.value.trim()) {
            errors.push("Please select a time.");
        }

        // عرض رسائل الأخطاء إن وجدت
        if (errors.length > 0) {
            messageContainer.innerHTML = `<div class="message error">${errors.join("<br>")}</div>`;
            return false; // هناك أخطاء، لا يتم التقديم
        } else {
            messageContainer.innerHTML = ""; // مسح أي رسالة سابقة
            return true; // جميع الحقول صحيحة
        }
    }


});

let countdownInterval;

function startCountdown() {
    let countdown = 10;
    const countdownElement = document.getElementById("countdown-message");
    clearInterval(countdownInterval);

    countdownElement.innerHTML = `Please confirm within ${countdown} seconds.`;

    countdownInterval = setInterval(() => {
        if (countdown > 0) {
            countdown--;
            countdownElement.innerHTML = `Please confirm within ${countdown} seconds.`;
        } else {
            clearInterval(countdownInterval);
            checkTimeAvailability();
        }
    }, 1000);
}

function checkTimeAvailability() {
    const selectedDate = document.getElementById("date").value;
    let selectedTime = document.getElementById("time").value;
    const countdownElement = document.getElementById("countdown-message");

    if (!selectedDate || !selectedTime) {
        return;
    }

    selectedTime = formatTimeToDatabase(selectedTime);

    // fetch(`booking_handler.php?action=check_time&date=${selectedDate}&time=${selectedTime}`)

    fetch('booking_handler.php', {
            method: 'POST',
            body: JSON.stringify({ action: 'check_time', date: selectedDate, time: selectedTime }),
            headers: { 'Content-Type': 'application/json' },
            // body: `action=hold_time&date=${selectedDate}&time=${selectedTime}&email=${email}`
        })
        .then(response => response.text()) // استجابة كنص لمعرفة المشاكل
        .then(text => {
            const data = JSON.parse(text);
            if (data["available"]) {
                // console.log("Server response:", text);
                startCountdown();
            } else {
                clearInterval(countdownInterval);
                countdownElement.innerHTML = "";
                document.getElementById("time").value = "";
            }
        })
        .catch(error => {
            console.error("Error checking time availability:", error);
        });
}

function formatTimeToDatabase(time) {
    let parts = time.split(":");
    if (parts.length === 2) {
        parts.push("00");
    }
    return parts.join(":");
}


// تحديث الأوقات المتاحة بشكل ديناميكي باستخدام AJAX
function fetchAvailableTimes(selectedDate) {
    fetch("booking_handler.php", {
            method: 'POST',
            body: JSON.stringify({ action: 'get_available_times', date: selectedDate }),
            headers: { 'Content-Type': 'application/json' }
        })
        .then(response => response.text()) // استجابة كنص لمعرفة المشاكل
        .then(text => {
            // console.log("Server response:", text);
            const data = JSON.parse(text);
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

// وظيفة التحقق من الحقول وإظهار رسالة خطأ