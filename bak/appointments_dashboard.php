<?php
// this file name: manage_appointments.php
require_once 'config.php';
require_once 'auth.php'; // التحقق من الجلسة

// جلب المواعيد من قاعدة البيانات
$query = "SELECT id, unique_code, name, email, DATE_FORMAT(date, '%Y-%m-%d') AS formatted_date, TIME_FORMAT(time, '%H:%i') AS formatted_time, status 
          FROM appointments 
          ORDER BY date ASC, time ASC";
$result = $conn->query($query);

// الحالات وألوانها
$statuses = [
    'Pending' => 'orange',
    'Confirmed' => 'green',
    'Missed' => 'red',
    'Cancelled' => 'gray'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments | Dashboard</title>
    <link rel="stylesheet" href="dashboard/css/navbar.css">
    <script src="https://kit.fontawesome.com/b8057c684d.js" crossorigin="anonymous"></script>
    <style>
        :root {
            --bg-color: #ffffff;
            --text-color: #000000;
            --border-color: #ddd;
            --highlight-color: #007bff;
        }

        [data-theme="dark"] {
            --bg-color: #121212;
            --text-color: #ffffff;
            --border-color: #444;
            --highlight-color: #1e90ff;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            isolation: isolate; /* لعزل التأثيرات */
            z-index: 1 !important; /* قيمة أقل من القائمة */
            overflow: visible !important;
            position: relative;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: var(--bg-color);
            border: 1px solid var(--border-color);
            border-radius: 10px;
        }

        table {
            position: relative; /* اجعل الجدول مرجعًا */
            z-index: 1 !important; /* قيمة أقل من القائمة */
            overflow: visible; /* اجعل المحتوى المنبثق مرئيًا */
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-collapse: separate;
            border-spacing: 0;
        }
        .table-container {
            position: relative; /* لإعادة ضبط سياق العناصر */
            overflow: visible; /* لجعل القائمة المنسدلة تظهر بالكامل */
            z-index: 1 !important;
        }
        table tbody tr {
            position: relative; /* إصلاح تداخل الـ z-index */
            z-index: 1 !important; /* أقل من القائمة */
        }
                /* تأثير عند التمرير فوق الصفوف */
        .table tbody tr:hover {
            background-color: var(--highlight-color);
            color: #fff;
            cursor: pointer;
        }

        th, td {
            position: relative; /* إصلاح تداخل الـ z-index */
            z-index: 1 !important; /* أقل من القائمة */
            padding: 12px;
            border: 1px solid var(--border-color);
            text-align: center;
        }

        th {
            z-index: 1 !important; /* أقل من القائمة */
            background-color: var(--highlight-color);
            color: #fff;
        }

        .status {
            font-weight: bold;
            padding: 5px;
            border-radius: 5px;
        }

        .btn {
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            border: none;
        }

        .btn-edit {
            background-color: #ffc107;
            color: #000;
        }

        .btn-delete {
            background-color: #dc3545;
            color: #fff;
        }

        .btn-reminder {
            background-color: #007bff;
            color: #fff;
        }

        .btn-transfer {
            background-color: #17a2b8;
            color: #fff;
        }
        /* الأزرار الأساسية */
        .btn {
            transition: background-color 0.3s ease, color 0.3s ease, transform 0.2s ease;
        }

        .btn:hover {
            transform: scale(1.05); /* تكبير طفيف */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* ألوان مختلفة للأزرار عند التمرير */
        .btn-edit:hover {
            background-color: #e0a800; /* لون جديد لزر التعديل */
            color: #fff;
        }

        .btn-delete:hover {
            background-color: #b21f2d; /* لون جديد لزر الحذف */
        }

        .btn-reminder:hover {
            background-color: #0056b3; /* لون جديد لزر التذكير */
        }

        .btn-transfer:hover {
            background-color: #28a745; /* لون جديد لزر النقل */
        }
        /* تأثير الضغط على الأزرار */
        .btn:active {
            transform: scale(0.95);
            opacity: 0.8;
        }
        /* الحقول القابلة للتعديل */
        .editable {
            transition: border-color 0.3s ease, background-color 0.3s ease;
            border: 1px solid var(--border-color);
            border-radius: 5px;
        }

        .editable:hover {
            border-color: var(--highlight-color); /* لون مميز عند التمرير */
            background-color: #f0f8ff; /* خلفية خفيفة */
        }

        .editable:focus {
            border-color: #1e90ff;
            box-shadow: 0 0 5px rgba(30, 144, 255, 0.5); /* تأثير التركيز */
        }
        /* قائمة الحالة */
        select.editable {
            background-color: var(--bg-color);
            color: var(--text-color);
            border: 1px solid var(--border-color);
            padding: 5px;
            border-radius: 5px;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }

        select.editable:hover {
            border-color: var(--highlight-color);
        }

        select.editable:focus {
            border-color: #1e90ff;
            box-shadow: 0 0 5px rgba(30, 144, 255, 0.5);
        }
        /* تأثير تحميل الصفحة */
        .fade-in {
            opacity: 0;
            transform: translateY(10px);
            animation: fadeIn 0.5s ease-in-out forwards;
        }


        .btn-action {
            z-index: 9999 !important; /* جعل القائمة فوق كل العناصر */
            background-color: #007bff;
            color: #fff;
        }

        .action-dropdown {
            z-index: 9999 !important; /* جعل القائمة فوق كل العناصر */

            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none; /* إخفاء القائمة افتراضيًا */
            position: absolute;
            top: calc(100% + 5px); /* أسفل الزر */
            left: 0;
            background-color: #fff;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 9999 !important; /* جعل القائمة فوق كل العناصر */
            border-radius: 5px;
        }

        .dropdown-content a {
            z-index: 9999 !important;
            position: relative;
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            cursor: pointer;
        }

        .dropdown-content a:hover {
            z-index: 9999 !important;
            background-color: #f1f1f1;
            color: #fff; /* لون النص */
            font-weight: bold; /* خط عريض */
        }

        .action-dropdown:hover .dropdown-content {
            display: block;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: var(--bg-color);
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            border-radius: 10px;
        }

        .modal-content button {
            margin: 10px;
            padding: 10px 20px;
            cursor: pointer;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @media (max-width: 768px) {
        .container {
            padding: 10px;
            }

            .search-bar {
                width: 100%; /* عرض كامل لشريط البحث */
                font-size: 14px;
            }

            .btn {
                font-size: 12px; /* تصغير حجم الأزرار */
                padding: 6px 10px;
            }

            .btn-edit, .btn-delete, .btn-reminder {
                margin-bottom: 5px; /* مسافة بين الأزرار */
                width: 100%; /* عرض كامل */
            }
        }
        @media (min-width: 1200px) {
            .container {
                max-width: 1400px; /* توسيع الحاوية للشاشات الأكبر */
            }

            table {
                font-size: 16px;
            }

            .btn {
                padding: 8px 12px;
            }
        }
        @media (max-width: 768px) {
            input[type="text"],
            input[type="email"],
            input[type="date"],
            input[type="time"],
            select {
                width: 100%; /* عرض كامل للحقل */
                margin-bottom: 10px; /* مسافة بين الحقول */
                font-size: 14px;
            }

            td input,
            td select {
                margin-bottom: 5px;
            }
        }
        @media (max-width: 768px) {
            .load-more {
                width: 100%; /* زر إظهار المزيد بعرض كامل */
                font-size: 14px;
            }
        }
        /* ألوان ثابتة لعناصر الجدول */
        [data-theme="dark"] .table tbody tr:hover {
            background-color: #333;
            color: #fff;
        }


    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <h1>Manage Appointments</h1>
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>UUID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php 
        $rowNumber = 1; // متغير لاحتساب رقم الصف
        while ($row = $result->fetch_assoc()): ?>
            <tr id="appointment-row-<?= $row['id'] ?>" style="background-color: <?= $statuses[$row['status']] ?? '#ffffff' ?>">
                <td><?= $rowNumber++ ?></td>
                <td><?= htmlspecialchars($row['unique_code']) ?></td>
                <td><input type="text" class="name-input" value="<?= htmlspecialchars($row['name']) ?>"></td>
                <td><input type="email" class="email-input" value="<?= htmlspecialchars($row['email']) ?>"></td>
                <td><input type="date" class="date-input" value="<?= htmlspecialchars($row['formatted_date']) ?>"></td>
                <td><input type="time" class="time-input" value="<?= htmlspecialchars($row['formatted_time']) ?>"></td>
                <td>
                    <select class="status-select">
                        <?php foreach ($statuses as $status => $color): ?>
                            <option value="<?= $status ?>" <?= $row['status'] === $status ? 'selected' : '' ?>><?= $status ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <button class="btn btn-edit" onclick="updateAppointment(<?= $row['id'] ?>)">Update</button>
                    <button class="btn btn-delete" onclick="deleteAppointment(<?= $row['id'] ?>)">Delete</button>
                    <button class="btn btn-transfer" onclick="transferAppointment(<?= $row['id'] ?>)">Transfer</button>
                    <div class="action-dropdown">
                        <button class="btn btn-action">Actions</button>
                        <div class="dropdown-content">
                            <a onclick="confirmAction('send', 'Send Appointment', <?= $row['id'] ?>)">Send Appointment</a>
                            <a onclick="confirmAction('remind', 'Send Reminder', <?= $row['id'] ?>)">Send Reminder</a>
                            <a onclick="confirmAction('update', 'Send Update', <?= $row['id'] ?>)">Send Update</a>
                            <a onclick="confirmAction('close', 'Send Closure', <?= $row['id'] ?>)">Send Closure</a>
                            <a onclick="confirmAction('missed', 'Mark as Missed', <?= $row['id'] ?>)">Mark as Missed</a>
                        </div>
                    </div>
                </td>
        
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<!-- Modal -->
<div id="confirmationModal" class="modal">
    <div class="modal-content">
        <p id="confirmationMessage"></p>
        <button id="confirmButton">Confirm</button>
        <button onclick="closeModal()">Cancel</button>
    </div>
</div>
<script>
     let currentAction = '';
    let currentId = '';

    function confirmAction(action, message, id) {
        currentAction = action;
        currentId = id;
        document.getElementById('confirmationMessage').innerText = message;
        document.getElementById('confirmationModal').style.display = 'block';
    }

    document.getElementById('confirmButton').addEventListener('click', function () {
        // إرسال الطلب إلى الخادم
        fetch('server_manage_appointments.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: currentAction,
                id: currentId
            })
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.status === 'success') {
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));

        closeModal();
    });

    function closeModal() {
        document.getElementById('confirmationModal').style.display = 'none';
    }
    
    
    function updateAppointment(id) {
        const row = document.querySelector(`#appointment-row-${id}`);
        const name = row.querySelector('.name-input').value;
        const email = row.querySelector('.email-input').value;
        const date = row.querySelector('.date-input').value;
        const time = row.querySelector('.time-input').value;
        const status = row.querySelector('.status-select').value;

        fetch('server_manage_appointments.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'update',
                id,
                name,
                email,
                date,
                time,
                status,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                alert(data.message);
                if (data.status === 'success') {
                    location.reload(); // تحديث الصفحة لتحديث البيانات
                }
            })
            .catch((error) => console.error('Error updating appointment:', error));
    }

    function deleteAppointment(id) {
        if (confirm('Are you sure you want to delete this appointment?')) {
            fetch('server_manage_appointments.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'delete', id }),
            })
                .then((response) => response.json())
                .then((data) => {
                    alert(data.message);
                    if (data.status === 'success') {
                        document.querySelector(`#appointment-row-${id}`).remove();
                    }
                })
                .catch((error) => console.error('Error deleting appointment:', error));
        }
    }

    function transferAppointment(id) {
        if (confirm('Are you sure you want to transfer this appointment?')) {
            fetch('server_manage_appointments.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'transfer', id }),
            })
                .then((response) => response.json())
                .then((data) => {
                    alert(data.message);
                    if (data.status === 'success') {
                        document.querySelector(`#appointment-row-${id}`).remove();
                    }
                })
                .catch((error) => console.error('Error transferring appointment:', error));
        }
    }
    document.querySelectorAll('.btn').forEach((button) => {
        button.addEventListener('click', () => {
            button.classList.add('clicked');
            setTimeout(() => button.classList.remove('clicked'), 200); // إزالة التأثير بعد 200ms
        });
    });
    document.querySelectorAll('.editable').forEach((field) => {
        field.addEventListener('focus', (e) => {
            e.target.style.backgroundColor = '#f0f8ff'; // لون خفيف عند التركيز
        });

        field.addEventListener('blur', (e) => {
            e.target.style.backgroundColor = ''; // العودة للوضع الطبيعي عند الخروج
        });

        field.addEventListener('click', (e) => {
            e.target.style.borderColor = '#1e90ff'; // تغيير لون الإطار عند النقر
        });
    });
    document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('tr, .btn').forEach((el) => {
        el.classList.add('fade-in');
    });
});
</script>
</body>
</html>
<?php
$conn->close();
?>
