<?php
session_start();
require_once '../config/database.php';
require_once '../includes/header.php';

// Optional: Only allow access if admin is logged in
// if (!isset($_SESSION['admin_logged_in'])) {
//     header('Location: login.php');
//     exit();
// }

// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$type = $_GET['type'] ?? 'blog';
$allowed_types = ['blog', 'reviews', 'services', 'partners', 'team'];
if (!in_array($type, $allowed_types)) {
    $type = 'blog';
}

$table_map = [
    'blog' => [
        'table' => 'blog', 
        'title' => 'Blog Posts', 
        'fields' => ['title', 'content'], 
        'add_fields' => ['title' => 'Title', 'content' => 'Content'], 
        'has_date' => true
    ],
    'reviews' => [
        'table' => 'reviews', 
        'title' => 'Reviews', 
        'fields' => ['client_name', 'review'], 
        'add_fields' => ['client_name' => 'Client Name', 'review' => 'Review'], 
        'has_date' => true
    ],
    'services' => [
        'table' => 'services', 
        'title' => 'Services', 
        'fields' => ['service_name', 'description'], 
        'add_fields' => ['service_name' => 'Service Name', 'description' => 'Description'], 
        'has_date' => false
    ],
    'partners' => [
        'table' => 'partners', 
        'title' => 'Partners', 
        'fields' => ['name', 'description'], 
        'add_fields' => ['name' => 'Name', 'description' => 'Description'], 
        'has_date' => false
    ],
    'team' => [
        'table' => 'team', 
        'title' => 'Team Members', 
        'fields' => ['photo_path', 'name', 'role', 'bio'], 
        'add_fields' => ['name' => 'Name', 'role' => 'Role', 'bio' => 'Bio', 'photo_path' => 'Photo'], 
        'has_date' => false
    ],
];

$info = $table_map[$type];
$table = $info['table'];
$title = $info['title'];
$fields = $info['fields'];
$add_fields = $info['add_fields'];
$has_date = $info['has_date'];

$error = '';
$success = '';

// Handle add entry for all types
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_entry'])) {
    $data = [];
    $is_valid = true;
    foreach ($add_fields as $field => $label) {
        if ($field !== 'photo_path') {
            $data[$field] = trim($_POST[$field] ?? '');
            if ($data[$field] === '') {
                $is_valid = false;
                $error = "All fields are required.";
                break;
            }
        }
    }

    $photo_path = null;
    if ($type === 'team' && isset($_FILES['photo_path'])) {
        $file = $_FILES['photo_path'];
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $error = "File upload failed with error code " . $file['error'];
        } else {
            $upload_dir = '../uploads/team/';
            
            // Check if directory exists, if not, try to create it
            if (!is_dir($upload_dir)) {
                if (!mkdir($upload_dir, 0755, true)) {
                    $error = "Failed to create the upload directory. Please check file permissions.";
                }
            }
            
            if (!$error) {
                $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                $max_file_size = 5 * 1024 * 1024; // 5 MB

                if (!in_array(strtolower($file_extension), $allowed_extensions)) {
                    $error = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
                } elseif ($file['size'] > $max_file_size) {
                    $error = "File size exceeds the 5 MB limit.";
                } else {
                    $new_filename = uniqid('team_', true) . '.' . $file_extension;
                    $destination = $upload_dir . $new_filename;
                    if (move_uploaded_file($file['tmp_name'], $destination)) {
                        $photo_path = 'uploads/team/' . $new_filename;
                    } else {
                        $error = "Failed to move uploaded file. Check if the directory is writable.";
                    }
                }
            }
        }
    }

    if ($is_valid && !$error) {
        $field_names = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $stmt_types = str_repeat('s', count($data));

        if ($type === 'team') {
            $field_names .= ", photo_path";
            $placeholders .= ", ?";
            $stmt_types .= "s";
            $data['photo_path'] = $photo_path;
        }

        if ($has_date) {
            $query = "INSERT INTO `$table` ($field_names, date_created) VALUES ($placeholders, NOW())";
        } else {
            $query = "INSERT INTO `$table` ($field_names) VALUES ($placeholders)";
        }
        
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param($stmt_types, ...array_values($data));
            if ($stmt->execute()) {
                $success = ucfirst($type) . " added successfully!";
            } else {
                $error = "Failed to add " . $type . ". Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "Failed to prepare statement. Check your SQL syntax or table/column names.";
        }
    }
}

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    // Fetch the photo path before deleting
    $stmt_select = $conn->prepare("SELECT photo_path FROM `$table` WHERE id = ?");
    $stmt_select->bind_param("i", $delete_id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    $item = $result->fetch_assoc();
    $stmt_select->close();

    // Delete the file if it exists
    if ($item && !empty($item['photo_path']) && file_exists('../' . $item['photo_path'])) {
        unlink('../' . $item['photo_path']);
    }

    // Now delete the database entry
    $stmt = $conn->prepare("DELETE FROM `$table` WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $success = ucfirst($type) . " deleted successfully!";
    } else {
        $error = "Failed to delete.";
    }
    $stmt->close();
}

// Fetch all content
$items = [];
$result = $conn->query("SELECT * FROM `$table` ORDER BY id DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> - Eleva Corps Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0d0d0d;
            color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .content-list-container {
            width: 95%;
            max-width: 1200px;
            margin: 2rem auto;
            background: #1a1a1a;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
        }

        .content-list-container h2 {
            color: #ffd700;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 2.5rem;
            font-weight: 700;
        }

        .message-box {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-weight: 600;
            text-align: center;
            animation: fadeIn 0.5s ease-out;
        }
        .success {
            background-color: #1a4d2e;
            color: #a5d6a7;
            border: 1px solid #a5d6a7;
        }
        .error {
            background-color: #6a1a1a;
            color: #ef9a9a;
            border: 1px solid #ef9a9a;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .add-form-container {
            background: #282828;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        .add-form-container h3 {
            color: #f0f0f0;
            margin-top: 0;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
        }
        .add-form-container form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .add-form-container label {
            font-weight: 600;
            color: #ccc;
        }
        .add-form-container input, .add-form-container textarea, .add-form-container input[type="file"] {
            width: 100%;
            padding: 0.8rem;
            border-radius: 8px;
            border: 1px solid #444;
            background: #1a1a1a;
            color: #ffd700;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .add-form-container input[type="file"] {
            padding: 0.8rem; /* Correct padding for file input */
        }
        .add-form-container input:focus, .add-form-container textarea:focus {
            outline: none;
            border-color: #ffd700;
            box-shadow: 0 0 0 2px rgba(255, 215, 0, 0.2);
        }
        .add-form-container button {
            background: #ffd700;
            color: #1a1a1a;
            border: none;
            border-radius: 8px;
            padding: 0.8rem 1.5rem;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .add-form-container button:hover {
            background: #fff;
            transform: translateY(-2px);
        }

        .content-list-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
        }
        .content-list-table thead tr {
            background: #282828;
            color: #ffd700;
        }
        .content-list-table th, .content-list-table td {
            border: 1px solid #333;
            padding: 1rem;
            text-align: left;
            word-wrap: break-word;
        }
        .content-list-table th {
            font-weight: 600;
            text-transform: uppercase;
        }
        .content-list-table tbody tr {
            background: #1a1a1a;
            transition: background-color 0.3s ease;
        }
        .content-list-table tbody tr:hover {
            background-color: #222;
        }
        .content-list-table img.team-photo {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #444;
        }

        .action-btns {
            display: flex;
            gap: 0.5rem;
            white-space: nowrap;
        }
        .action-btns button {
            background: #ff4d4d;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .action-btns button:hover {
            background: #ff1a1a;
        }
        .back-link {
            display: inline-block;
            margin-top: 2rem;
            text-align: center;
            color: #ffd700;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        .back-link:hover {
            color: #fff;
        }
        @media (max-width: 768px) {
            .content-list-container {
                padding: 1rem;
            }
            .content-list-table, .content-list-table thead, .content-list-table tbody, .content-list-table th, .content-list-table td, .content-list-table tr {
                display: block;
            }
            .content-list-table thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            .content-list-table tr {
                margin-bottom: 1.5rem;
                border: 1px solid #333;
                border-radius: 8px;
                overflow: hidden;
            }
            .content-list-table td {
                border: none;
                position: relative;
                padding-left: 50%;
                text-align: right;
            }
            .content-list-table td:before {
                content: attr(data-label);
                position: absolute;
                left: 1rem;
                width: calc(50% - 2rem);
                text-align: left;
                font-weight: 700;
                color: #ccc;
            }
            .action-btns {
                justify-content: flex-end;
            }
        }
    </style>
</head>
<body>
    <div class="content-list-container">
        <h2><?php echo htmlspecialchars($title); ?></h2>
        <?php if ($success): ?><div class="message-box success"><?php echo $success; ?></div><?php endif; ?>
        <?php if ($error): ?><div class="message-box error"><?php echo $error; ?></div><?php endif; ?>

        <!-- Dynamic Add Form -->
        <div class="add-form-container">
            <h3>Add New <?php echo ucwords(str_replace('_', ' ', $type)); ?></h3>
            <form method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="add_entry" value="1">
                <?php foreach ($add_fields as $field => $label): ?>
                    <label for="<?php echo $field; ?>"><?php echo htmlspecialchars($label); ?></label>
                    <?php if ($field === 'photo_path'): ?>
                        <input type="file" name="photo_path" id="photo_path" accept="image/*">
                    <?php elseif ($field === 'content' || $field === 'review' || $field === 'description' || $field === 'bio'): ?>
                        <textarea name="<?php echo $field; ?>" id="<?php echo $field; ?>" rows="4" required></textarea>
                    <?php else: ?>
                        <input type="text" name="<?php echo $field; ?>" id="<?php echo $field; ?>" required>
                    <?php endif; ?>
                <?php endforeach; ?>
                <button type="submit">Add <?php echo ucwords(str_replace('_', ' ', $type)); ?></button>
            </form>
        </div>

        <!-- Content List Table -->
        <?php if (!empty($items)): ?>
            <table class="content-list-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <?php foreach ($fields as $field): ?>
                            <th><?php echo ucwords(str_replace('_', ' ', $field)); ?></th>
                        <?php endforeach; ?>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td data-label="ID"><?php echo htmlspecialchars($item['id'] ?? ''); ?></td>
                        <?php foreach ($fields as $field): ?>
                            <td data-label="<?php echo ucwords(str_replace('_', ' ', $field)); ?>">
                                <?php if ($field === 'photo_path'): ?>
                                    <?php if (!empty($item['photo_path'])): ?>
                                        <img src="../<?php echo htmlspecialchars($item['photo_path']); ?>" alt="Team Photo" class="team-photo">
                                    <?php else: ?>
                                        No Photo
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php echo nl2br(htmlspecialchars($item[$field] ?? '')); ?>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                        <td data-label="Actions" class="action-btns">
                            <form method="post" action="" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                <input type="hidden" name="delete_id" value="<?php echo $item['id']; ?>">
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center; color: #ccc;">No <?php echo strtolower($title); ?> found.</p>
        <?php endif; ?>

        <div style="text-align:center; margin-top:2rem;">
            <a href="index.php" class="back-link">&larr; Back to Dashboard</a>
        </div>
    </div>
</body>
</html>

<?php require_once '../includes/footer.php'; ?>
