<?php
// this file name : server_manage_appointments.php
require_once 'config.php'; // الاتصال بقاعدة البيانات
require_once 'auth.php'; // التحقق من الجلسة

// التحقق من وجود طلب POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // قراءة البيانات القادمة من الطلب
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['action'])) {
        $action = $input['action'];

        // تعديل الموعد
        if ($action === 'update' && isset($input['id'], $input['name'], $input['email'], $input['date'], $input['time'], $input['status'])) {
            $id = intval($input['id']);
            $name = $input['name'];
            $email = $input['email'];
            $date = $input['date'];
            $time = $input['time'];
            $status = $input['status'];

            $stmt = $conn->prepare("UPDATE appointments SET name = ?, email = ?, date = ?, time = ?, status = ? WHERE id = ?");
            $stmt->bind_param("sssssi", $name, $email, $date, $time, $status, $id);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Appointment updated successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update appointment.']);
            }
            $stmt->close();
        }

        // حذف الموعد
        elseif ($action === 'delete' && isset($input['id'])) {
            $id = intval($input['id']);

            $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Appointment deleted successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete appointment.']);
            }
            $stmt->close();
        }

        // إرسال تذكير
        elseif ($action === 'remind' && isset($input['id'])) {
            $id = intval($input['id']);
            // هنا يمكنك إضافة منطق إرسال التذكير بالبريد الإلكتروني
            echo json_encode(['status' => 'success', 'message' => 'Reminder sent successfully.']);
        }

        // نقل الموعد إلى جدول آخر
        elseif ($action === 'transfer' && isset($input['id'])) {
            $id = intval($input['id']);
            $stmt = $conn->prepare("INSERT INTO completed_appointments (id, name, email, date, time, status) 
                                    SELECT id, name, email, date, time, status FROM appointments WHERE id = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                // حذف الموعد من الجدول الحالي بعد النقل
                $stmtDelete = $conn->prepare("DELETE FROM appointments WHERE id = ?");
                $stmtDelete->bind_param("i", $id);
                $stmtDelete->execute();
                $stmtDelete->close();

                echo json_encode(['status' => 'success', 'message' => 'Appointment transferred successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to transfer appointment.']);
            }
            $stmt->close();
        }

        // الإجراء غير معروف
        else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Action not specified.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

$conn->close();
?>
