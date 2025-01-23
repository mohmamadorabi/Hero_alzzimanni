<?php
    require_once 'config.php';
    require_once 'auth.php';
    
    checkRole(required_role: 'admin'); // Allow only Admin users

    $message = ''; // To store messages

    // Add a new user
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $role = $_POST['role'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
        if (!empty($username) && !empty($password)) {
            // التحقق مما إذا كان اسم المستخدم موجودًا بالفعل
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                $message = "Username already exists. Please choose a different username.";
            } else {
                $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $hashed_password, $role);
                if ($stmt->execute()) {
                    $message = "User added successfully.";
                } else {
                    $message = "An error occurred while adding the user.";
                }
            }
            $stmt->close();
        } else {
            $message = "Please enter a username and password.";
        }
    }
    

    // Edit a user
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_user') {
        $response = ['success' => false, 'message' => ''];
    
        $user_id = intval($_POST['user_id']);
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $role = $_POST['role'];
    
        if (!empty($username)) {
            if (!empty($password)) {
                // إذا تم إدخال كلمة مرور جديدة، قم بتحديثها
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET username = ?, password = ?, role = ? WHERE id = ?");
                $stmt->bind_param("sssi", $username, $hashed_password, $role, $user_id);
            } else {
                // إذا لم يتم إدخال كلمة مرور جديدة، لا تقم بتحديثها
                $stmt = $conn->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
                $stmt->bind_param("ssi", $username, $role, $user_id);
            }
    
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'User updated successfully.';
            } else {
                $response['message'] = 'An error occurred while updating the user.';
            }
            $stmt->close();
        } else {
            $response['message'] = 'Please enter a valid username.';
        }
    
        echo json_encode($response);
        exit;
    }
    
    
    // Delete a user
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_user') {
        $response = ['success' => false, 'message' => ''];
    
        $user_id = intval($_POST['user_id']);
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'User deleted successfully.';
        } else {
            $response['message'] = 'An error occurred while deleting the user.';
        }
        $stmt->close();
    
        echo json_encode($response);
        exit;
    }

    // Fetch all users
    $users = $conn->query("SELECT id, username, role FROM users");
    
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $response = ['success' => false, 'message' => '', 'users' => []];
        $action = $_POST['action'] ?? '';
    
        switch ($action) {
            case 'add':
                $username = trim($_POST['username']);
                $password = trim($_POST['password']);
                $role = $_POST['role'];
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
                // التحقق من وجود اسم المستخدم
                $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $stmt->store_result();
    
                if ($stmt->num_rows > 0) {
                    $response['message'] = 'Username already exists.';
                } else {
                    // إضافة المستخدم
                    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $username, $hashed_password, $role);
                    if ($stmt->execute()) {
                        $response['success'] = true;
                        $response['message'] = 'User added successfully.';
                    } else {
                        $response['message'] = 'Failed to add user.';
                    }
                }
    
                $stmt->close();
                break;
    
            case 'fetch_users':
                // جلب جميع المستخدمين لتحديث الجدول
                $response['users'] = [];
                $result = $conn->query("SELECT id, username, role FROM users");
                while ($row = $result->fetch_assoc()) {
                    $response['users'][] = $row;
                }
                $response['success'] = true;
                break;
    
            default:
                $response['message'] = 'Invalid action.';
        }
    
        echo json_encode($response);
        exit;
    }
    
    ?>
<?php
include 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users | AZORPUB</title>
    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/b8057c684d.js" crossorigin="anonymous"></script>

    <script src="dashboard\js\navbar.js" defer></script>
    <link rel="stylesheet" href="dashboard/css/navbar.css">

    <style>
        :root {
            --bg-color: #ffffff;
            --text-color: #000000;
            --input-bg: #f9f9f9;
            --border-color: #ddd;
            --highlight-color: #007bff;
            --button-bg: #007bff;
            --button-text: #ffffff;
        }

        [data-theme="dark"] {
            --bg-color: #121212;
            --text-color: #ffffff;
            --input-bg: #1e1e1e;
            --border-color: #444;
            --highlight-color: #1e90ff;
            --button-bg: #1e90ff;
            --button-text: #000000;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            transition: background-color 0.3s, color 0.3s;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: var(--input-bg);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: var(--highlight-color);
        }

        .alert {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        form {
            margin-bottom: 20px;
        }

        .btn {
            padding: 10px;
            margin: 5px 0;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            width: auto;
            box-sizing: border-box;
            color: var(--button-text);
            background-color: var(--button-bg);
            cursor: pointer;
            transition: background-color 0.3s;
            font-weight: bold;
            font-size: 16px;
        }
        .btn_action{
            padding: 5px;
            border: none;
            margin: auto;
            border-radius: 5px;
            width: auto;
            padding: 5px;
            box-sizing: border-box;
            background: none;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
        }
        /* زر مفعل */
        #add-user-btn {
            background-color: var(--button-bg); /* لون الخلفية */
            color: var(--button-text); /* لون النص */
            cursor: pointer; /* مؤشر الفأرة */
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* زر معطل */
        #add-user-btn:disabled {
            background-color: #ccc; /* لون رمادي */
            color: #666; /* لون النص */
            cursor: not-allowed; /* مؤشر الفأرة */
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .form-control,select{
            background-color: var(--input-bg) !important;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
            color: var(---text-color);
        }
        input:focus, select:focus, button:focus {
            outline: none;
            border-color: var(--highlight-color);
            box-shadow: 0 0 5px var(--highlight-color);
        }

        button:hover {
            background-color: var(--highlight-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid var(--border-color);
            text-align: center;
        }

        table th {
            background-color: var(--highlight-color);
            color: white;
        }

        .d-flex {
            display: flex;
            margin: auto;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }
        .input_new_pass{
            position: relative; 
            max-width: auto; 
            margin: 0 auto;
        }
        .input_new_pass > button {
            position: absolute; 
            top: 50%; 
            right: 10px; 
            transform: translateY(-50%);
            padding: 5px 10px;
            border: none; 
            background-color: #007bff; 
            color: white; 
            border-radius: 5px; 
            cursor: pointer;

        }
        @media (max-width: 768px) {
            .d-flex {
                flex-direction: column;
                align-items: stretch;
            }

            input, select, button {
                width: 100%;
            }

            h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="text-center">Manage Users</h1>
    <?php if (!empty($message)): ?>
        <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <!-- Add New User Form -->
    <form action="manage_users.php" method="POST" class="mb-4"autocomplete="off">
        <input type="text" style="display: none;" autocomplete="username">
        <input type="password" style="display: none;" autocomplete="new-password">
        <div class="row">
            <div>
                <input id="username" type="text" name="username" class="form-control" placeholder="Enter username" required oninput="checkUsername()">

                <!-- <input id="username" type="text" name="username" class="form-control" autocomplete="off" placeholder="Enter username" required> -->
                <div id="username-message" style="color: red; font-size: 0.9em; margin-top: 5px;"></div>

            </div>
            <div class="input_new_pass">
                <input id="password"  type="text" name="password" class="form-control" autocomplete="off" placeholder="Enter password" required>
                <button 
                    type="button" 
                    class="generate-password-btn" 
                    onclick="generatePassword(this.parentElement)">
                    <i class="fas fa-key"></i>
                </button>
            </div>
            <div>
                <select name="role" class="form-select" required>
                    <option value="viewer">Viewer</option>
                    <option value="editor">Editor</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div>
                <button id="add-user-btn" class="btn" type="button" onclick="addUser()">Add User</button>

                <!-- <button id="add-user-btn" class="btn"  onclick="addUser()" type="submit" name="add_user">Add User</button> -->
            </div>
        </div>
    </form>

    <!-- Users Table -->
    <table id="users-table" class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Username</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($user = $users->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($user['id']) ?></td>
                <td>
                    <form action="manage_users.php" method="POST" class="d-flex">
                        <input type="text" style="display: none;" autocomplete="username">
                        <input type="password" style="display: none;" autocomplete="new-password">

                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
                        <select name="role" class="form-select mx-2" required>
                            <option value="viewer" <?= $user['role'] === 'viewer' ? 'selected' : '' ?>>Viewer</option>
                            <option value="editor" <?= $user['role'] === 'editor' ? 'selected' : '' ?>>Editor</option>
                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                        </select>
                        <input type="text" name="password" class="form-control" placeholder="******** (Leave blank to keep current password)">

                        <button 
                            type="button" 
                            class="generate-password-btn" 
                            onclick="generatePassword(this.parentElement)">
                            <i class="fas fa-key"></i>
                        </button>
                        <button class="btn_action" type="button" onclick="editUser(<?= $user['id'] ?>, this.parentElement.parentElement)">
                            <i class="fas fa-edit" style="color: #007bff; font-size: 1.2em;"></i>
                        </button>
                    </form>
                </td>
                <td><?= htmlspecialchars($user['role']) ?></td>
                <td>
                    <!-- زر حذف -->
                     
                    <button class="btn_action" onclick="deleteUser(<?= $user['id'] ?>)">
                        <i class="fas fa-trash-alt" style="color: red; font-size: 1.2em;"></i>
                    </button>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
    function generatePassword(rowElement) {
        const length = 12; // طول كلمة المرور
        const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
        let password = "";
        for (let i = 0; i < length; i++) {
            const randomIndex = Math.floor(Math.random() * chars.length);
            password += chars[randomIndex];
        }

        // العثور على حقل كلمة المرور في نفس الصف
        const passwordInput = rowElement.querySelector('input[name="password"]');
        if (passwordInput) {
            passwordInput.value = password;
        }
}
</script>
<script>

    function checkUsername() {
        const username = document.getElementById('username').value;
        const messageDiv = document.getElementById('username-message');
        const addUserButton = document.getElementById('add-user-btn');
        if (username.trim() === '') {
            messageDiv.textContent = ''; // مسح الرسالة إذا كان الحقل فارغًا
            addUserButton.disabled = true; // تعطيل الزر إذا كان الحقل فارغًا
            addUserButton.style.backgroundColor = '#ccc'; // لون الزر المعطل
            addUserButton.style.color = '#666'; // لون النص في الزر المعطل
            return;
        }

        // إرسال طلب AJAX للتحقق من اسم المستخدم
        fetch('check_username.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `username=${encodeURIComponent(username)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                messageDiv.textContent = data.message;
                messageDiv.style.color = 'red';
                addUserButton.disabled = true; // تعطيل الزر إذا كان الاسم موجودًا
                addUserButton.style.backgroundColor = '#ccc'; // لون الزر المعطل
                addUserButton.style.color = '#666'; // لون النص في الزر المعطل
            } else {
                messageDiv.textContent = 'Username is available.';
                messageDiv.style.color = 'green';
                addUserButton.disabled = false; // تفعيل الزر إذا كان الاسم متاحًا
                addUserButton.style.backgroundColor = '#007bff'; // لون الزر المفعل
                addUserButton.style.color = 'white'; // لون النص في الزر المفعل
            }
        })
        .catch(error => console.error('Error:', error));
    }
    function fetchUsers() {
        fetch('manage_users.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=fetch_users'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateUsersTable(data.users);
            }else {
                console.error('Failed to fetch users:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
    function updateUsersTable(users) {
    const usersTableBody = document.querySelector('#users-table tbody');
    usersTableBody.innerHTML = ''; // تنظيف الجدول

    users.forEach(user => {
        const newRow = document.createElement('tr');
        newRow.innerHTML =  `
            <td>${user.id}</td>
            <td>
                <form action="manage_users.php" method="POST" class="d-flex">

                    <input type="text" style="display: none;" autocomplete="username">
                    <input type="password" style="display: none;" autocomplete="new-password">

                    <input type="hidden" name="user_id" value="${user.id}">
                    <input type="text" name="username" class="form-control" value="${user.username}" required>
                    <select name="role" class="form-select mx-2" required>
                        <option value="viewer" ${user.role === 'viewer' ? 'selected' : ''}>Viewer</option>
                        <option value="editor" ${user.role === 'editor' ? 'selected' : ''}>Editor</option>
                        <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>Admin</option>
                    </select>
                    <input type="text" name="password" class="form-control" placeholder="******** (Leave blank to keep current password)">
                    <button type="button" class="generate-password-btn" onclick="generatePassword(this.parentElement)">
                        <i class="fas fa-key"></i>
                    </button>
                    <button class="btn_action" type="button" onclick="editUser(${user.id}, this.parentElement.parentElement)">
                        <i class="fas fa-edit" style="color: #007bff; font-size: 1.2em;"></i>
                    </button>
                </form>
            </td>
            <td>${user.role}</td>
            <td>
                <button class="btn_action" onclick="deleteUser(${user.id})">
                    <i class="fas fa-trash-alt" style="color: red; font-size: 1.2em;"></i>
                </button>
            </td>
        `;
        usersTableBody.appendChild(newRow);
    });
}

    function addUser() {
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();
        const role = document.querySelector('select[name="role"]').value;
        const messageDiv = document.getElementById('username-message'); // عنصر الرسالة
        if (!username || !password) {
            alert('Please fill in all fields.');
            return;
        }

        fetch('manage_users.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=add&username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}&role=${encodeURIComponent(role)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                fetchUsers(); // تحديث الجدول
                document.getElementById('username').value = '';
                document.getElementById('password').value = '';
                document.querySelector('select[name="role"]').value = 'viewer';
                // إخفاء رسالة التحقق
                messageDiv.textContent = '';
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
    function deleteUser(userId) {
    if (!confirm('Are you sure you want to delete this user?')) {
        return;
    }

    fetch('manage_users.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=delete_user&user_id=${encodeURIComponent(userId)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message); // يمكن استبدال التنبيه بإشعار أفضل
            fetchUsers(); // تحديث الجدول ديناميكيًا
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}
    function editUser(userId, rowElement) {
        const usernameInput = rowElement.querySelector('input[name="username"]');
        const passwordInput = rowElement.querySelector('input[name="password"]');
        const roleSelect = rowElement.querySelector('select[name="role"]');

        const username = usernameInput.value.trim();
        const password = passwordInput.value.trim();
        const role = roleSelect.value;

        if (!username) {
            alert('Username cannot be empty.');
        return;
    }

        fetch('manage_users.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=edit_user&user_id=${encodeURIComponent(userId)}&username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}&role=${encodeURIComponent(role)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                fetchUsers(); // تحديث الجدول ديناميكيًا
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }

</script>
</body>
</html>

<?php
$conn->close();
?>
