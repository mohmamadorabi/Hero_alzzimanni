<?php
require_once 'cleanup_appointments.php';
date_default_timezone_set('Europe/Brussels');
// الكود الخاص بحجز الموعد والتحقق
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book an Appointment</title>
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- إضافة مكتبة Flatpickr -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


</head>
<body>
    <div class="container">
        <h1>Book an Appointment</h1>
        <form id="booking-form" action="process_booking.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" placeholder="Enter your name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            
            <div id="email-status"></div>
            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>

            <label for="service">Service Type:</label>
            <select id="service" name="service" required>
                <option value="Haircut">Haircut</option>
                <option value="Beard Trim">Beard Trim</option>
                <option value="Haircut & Beard Trim">Haircut & Beard Trim</option>
            </select>
           <style>
            /* تنسيقات إضافية لتحسين الشكل */
                #date {
                    width: 100%;
                    padding: 10px;
                    font-size: 16px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                }

                .flatpickr-calendar {
                    font-family: Arial, sans-serif;
                    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
                }

                .flatpickr-disabled-day {
                    background: #ffdddd !important;
                    color: #d9534f !important;
                }

                .flatpickr-day:hover {
                    background: #007bff !important;
                    color: #fff !important;
                }

           </style>
            <div class="form-row">
                <div class="form-group">
                    <label for="date">Date:</label>
                    <!-- <input type="date" id="date" name="date" required> -->
                    <input type="text" id="date" name="date" placeholder="Click to select date" required>
                </div>
                <div class="form-group">
                    <label for="time">Time:</label>
                    <select id="time" name="time" required>
                        <option value="">Select a date first</option>
                    </select>
                </div>
            </div>

            <label for="notes">Additional Notes:</label>
            <textarea id="notes" name="notes" placeholder="Add any additional notes..."></textarea>

            <div id="message-container"></div>
            <div id="loading-indicator" class="loading-spinner" style="display: none;"></div>
            <button type="button" id="send-confirmation-code">Send Confirmation Code <span id="countdown_btn"></span></button>
            <div id="confirmation-section" style="display:none;">
                <label for="confirmation-code">Enter Confirmation Code:</label>
                <div class="confirm">
                   <input type="text" id="confirmation-code" name="confirmation_code" placeholder="Enter the code" required>
                    <button type="button" id="confirm-code">Confirm</button> 
                </div>
                
                <p id="countdown-timer">You have 5 minutes to confirm.</p>
            </div>
            <!-- <button type="submit" id="submit-booking" disabled>Book Appointment</button> -->
        </form>
    </div>

    <script>
        // document.addEventListener("DOMContentLoaded", function() {
        //     const dateInput = document.getElementById("date");

        //     // الحصول على تاريخ اليوم
        //     let today = new Date();
        //     today.setDate(today.getDate() + 1); // بدءًا من الغد

        //     // حساب 30 يومًا من الغد
        //     let maxDate = new Date();
        //     maxDate.setDate(today.getDate() + 30);

        //     // تنسيق التاريخ إلى YYYY-MM-DD
        //     let formattedToday = today.toISOString().split('T')[0];
        //     let formattedMaxDate = maxDate.toISOString().split('T')[0];

        //     // تعيين الحد الأدنى والحد الأقصى للحقل
        //     dateInput.setAttribute("min", formattedToday);
        //     dateInput.setAttribute("max", formattedMaxDate);

        //     // التأكد من عدم إدخال تاريخ خارج النطاق
        //     dateInput.addEventListener("input", function() {
        //         if (this.value < formattedToday || this.value > formattedMaxDate) {
        //             alert("Please select a date within the next 30 days starting from tomorrow.");
        //             this.value = ""; // إعادة تعيين الحقل
        //         }
        //     });
        // });
        document.addEventListener("DOMContentLoaded", function() {
        // تهيئة التقويم مع تلميحات للأيام المحظورة
        flatpickr("#date", {
            dateFormat: "Y-m-d", // تنسيق التاريخ
            minDate: new Date().fp_incr(1), // الغد هو أول تاريخ متاح
            maxDate: new Date().fp_incr(30), // الحد الأقصى لمدة 30 يومًا
            defaultDate: null, // عدم تحديد تاريخ افتراضي
            disable: [
                function(date) {
                    return date.getDay() === 1; // حظر أيام الاثنين
                }
            ],
            locale: {
                firstDayOfWeek: 0, // بدء الأسبوع من الأحد
                weekdays: {
                    shorthand: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                    longhand: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
                }
            },
            altInput: true,
            altFormat: "F j, Y", // عرض تاريخ أكثر وضوحًا للمستخدم
            theme: "material_blue", // استخدام سمة احترافية
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                if (dayElem.dateObj.getDay() === 1) {
                    dayElem.title = "Monday is always closed";
                    dayElem.classList.add("flatpickr-disabled-day");
                }
            },
            onReady: function(selectedDates, dateStr, instance) {
                instance.input.placeholder = "Select a date"; // تعيين نص توضيحي افتراضي
            }
        });

    });
        </script>
    <script src="assets/js/script.js"></script>
</body>
</html>
