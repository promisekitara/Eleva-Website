<?php
session_start();
require_once '../config/database.php';
require_once '../includes/header.php';

// Optional: Only allow access if admin is logged in
// if (!isset($_SESSION['admin_logged_in'])) {
//     header('Location: login.php');
//     exit();
// }

$error = '';
$success = '';

// Handle add, update, delete actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = intval($_POST['id'] ?? 0);
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = trim($_POST['role'] ?? 'admin');
    $password = $_POST['password'] ?? '';

    if ($action === 'add') {
        if ($username === '' || $email === '' || $password === '') {
            $error = 'All fields are required to add a user.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email address.';
        } else {
            // Check if username or email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $error = 'Username or email already exists.';
            } else {
                $stmt->close();
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, password, role, email) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $username, $hashed, $role, $email);
                if ($stmt->execute()) {
                    $success = 'User added successfully!';
                } else {
                    $error = 'Failed to add user.';
                }
                $stmt->close();
            }
            if ($stmt) $stmt->close();
        }
    } elseif ($action === 'update') {
        if ($id <= 0 || $username === '' || $email === '' || $role === '') {
            $error = 'All fields are required for update.';
        } else {
            if ($password !== '') {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ?, password = ? WHERE id = ?");
                $stmt->bind_param("ssssi", $username, $email, $role, $hashed, $id);
            } else {
                $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
                $stmt->bind_param("sssi", $username, $email, $role, $id);
            }
            if ($stmt->execute()) {
                $success = 'User updated successfully!';
            } else {
                $error = 'Failed to update user.';
            }
            $stmt->close();
        }
    } elseif ($action === 'delete') {
        if ($id <= 0) {
            $error = 'Invalid user ID.';
        } else {
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                $success = 'User deleted successfully!';
            } else {
                $error = 'Failed to delete user.';
            }
            $stmt->close();
        }
    }
}

// Fetch all users
$users = [];
$result = $conn->query("SELECT id, username, email, role FROM users ORDER BY id DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management - Eleva Corps Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .users-container {
            max-width: 900px;
            margin: 3rem auto;
            background: #181818;
            padding: 2rem;
            border-radius: 14px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.15);
            color: #ffd700;
        }
        .users-container h2 {
            color: #ffd700;
            margin-bottom: 1.5rem;
        }
        .users-container input, .users-container select {
            width: 100%;
            padding: 0.7rem;
            margin-bottom: 1rem;
            border-radius: 8px;
            border: 1px solid #ffd700;
            background: #222;
            color: #ffd700;
        }
        .users-container button {
            background: #ffd700;
            color: #181818;
            border: none;
            border-radius: 8px;
            padding: 0.7rem 1.5rem;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            margin-right: 0.5rem;
        }
        .users-container button:hover {
            background: #fff;
            color: #181818;
        }
        .users-success {
            color: #00e676;
            margin-bottom: 1rem;
        }
        .users-error {
            color: #ff4d4d;
            margin-bottom: 1rem;
        }
        .users-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }
        .users-table th, .users-table td {
            border: 1px solid #ffd700;
            padding: 0.7rem;
            text-align: left;
        }
        .users-table th {
            background: #222;
            color: #ffd700;
        }
        .users-table td {
            background: #181818;
            color: #ffd700;
        }
        .action-btns form {
            display: inline;
        }
        .edit-form {
            background: #222;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="users-container">
        <h2>User Management</h2>
        <?php if ($success): ?>
            <div class="users-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="users-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Add New User -->
        <h3>Add New User</h3>
        <form method="post" action="">
            <input type="hidden" name="action" value="add">
            <label for="username" style="color:#fff;">Username</label>
            <input type="text" name="username" id="username" required>
            <label for="email" style="color:#fff;">Email</label>
            <input type="email" name="email" id="email" required>
            <label for="role" style="color:#fff;">Role</label>
            <select name="role" id="role" required>
                <option value="admin">Admin</option>
                <option value="editor">Editor</option>
            </select>
            <label for="password" style="color:#fff;">Password</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">Add User</button>
        </form>

        <!-- List All Users -->
        <h3 style="margin-top:2.5rem;">All Users</h3>
        <table class="users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['role']); ?></td>
                    <td class="action-btns">
                        <!-- Edit Button (shows inline form) -->
                        <form method="post" action="" style="display:inline;">
                            <input type="hidden" name="action" value="edit_form">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit">Edit</button>
                        </form>
                        <!-- Delete Button -->
                        <form method="post" action="" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" style="background:#900;color:#fff;">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php
                // Show edit form inline if requested
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit_form' && intval($_POST['id'] ?? 0) === intval($row['id'])): ?>
                <tr>
                    <td colspan="5">
                        <form method="post" action="" class="edit-form">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <label for="username_<?php echo $row['id']; ?>" style="color:#fff;">Username</label>
                            <input type="text" name="username" id="username_<?php echo $row['id']; ?>" value="<?php echo htmlspecialchars($row['username']); ?>" required>
                            <label for="email_<?php echo $row['id']; ?>" style="color:#fff;">Email</label>
                            <input type="email" name="email" id="email_<?php echo $row['id']; ?>" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                            <label for="role_<?php echo $row['id']; ?>" style="color:#fff;">Role</label>
                            <select name="role" id="role_<?php echo $row['id']; ?>" required>
                                <option value="admin" <?php if($row['role']=='admin') echo 'selected'; ?>>Admin</option>
                                <option value="editor" <?php if($row['role']=='editor') echo 'selected'; ?>>Editor</option>
                            </select>
                            <label for="password_<?php echo $row['id']; ?>" style="color:#fff;">New Password (leave blank to keep current)</label>
                            <input type="password" name="password" id="password_<?php echo $row['id']; ?>">
                            <button type="submit">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endif; ?>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div style="text-align:center; margin-top:1rem;">
            <a href="index.php" style="color:#ffd700;">&larr; Back to Dashboard</a>
        </div>
    </div>
</body>
</html>

<?php require_once '../includes/footer.php'; ?>