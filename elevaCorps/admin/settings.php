<?php
session_start();
require_once '../config/database.php';
require_once '../includes/header.php';

// Optional: Only allow access if admin is logged in
// if (!isset($_SESSION['admin_logged_in'])) {
//     header('Location: login.php');
//     exit();
// }

$success = '';
$error = '';

// Handle site settings update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site_name = trim($_POST['site_name'] ?? '');
    $site_email = trim($_POST['site_email'] ?? '');
    $site_phone = trim($_POST['site_phone'] ?? '');
    $site_address = trim($_POST['site_address'] ?? '');
    $site_description = trim($_POST['site_description'] ?? '');

    if ($site_name === '' || $site_email === '') {
        $error = 'Site name and email are required.';
    } else {
        // Upsert settings (assuming a single row with id=1)
        $stmt = $conn->prepare("REPLACE INTO site_settings (id, site_name, site_email, site_phone, site_address, site_description) VALUES (1, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $site_name, $site_email, $site_phone, $site_address, $site_description);
        if ($stmt->execute()) {
            $success = 'Settings updated successfully!';
        } else {
            $error = 'Failed to update settings.';
        }
        $stmt->close();
    }
}

// Fetch current settings
$site_name = $site_email = $site_phone = $site_address = $site_description = '';
$result = $conn->query("SELECT * FROM site_settings WHERE id = 1 LIMIT 1");
if ($result && $row = $result->fetch_assoc()) {
    $site_name = $row['site_name'];
    $site_email = $row['site_email'];
    $site_phone = $row['site_phone'];
    $site_address = $row['site_address'];
    $site_description = $row['site_description'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Site Settings - Eleva Corps Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .settings-container {
            max-width: 600px;
            margin: 3rem auto;
            background: #181818;
            padding: 2rem;
            border-radius: 14px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.15);
            color: #ffd700;
        }
        .settings-container h2 {
            color: #ffd700;
            margin-bottom: 1.5rem;
        }
        .settings-container input[type="text"],
        .settings-container input[type="email"],
        .settings-container textarea {
            width: 100%;
            padding: 0.8rem;
            border-radius: 8px;
            border: 1px solid #ffd700;
            background: #222;
            color: #ffd700;
            font-size: 1.1rem;
            margin-bottom: 1.2rem;
        }
        .settings-container button {
            background: #ffd700;
            color: #181818;
            border: none;
            border-radius: 8px;
            padding: 0.8rem 2rem;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s;
        }
        .settings-container button:hover {
            background: #fff;
            color: #181818;
        }
        .settings-success {
            color: #00e676;
            margin-bottom: 1rem;
        }
        .settings-error {
            color: #ff4d4d;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="settings-container">
        <h2>Site Settings</h2>
        <?php if ($success): ?>
            <div class="settings-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="settings-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <label for="site_name" style="color:#fff;">Site Name</label>
            <input type="text" name="site_name" id="site_name" value="<?php echo htmlspecialchars($site_name); ?>" required>
            <label for="site_email" style="color:#fff;">Contact Email</label>
            <input type="email" name="site_email" id="site_email" value="<?php echo htmlspecialchars($site_email); ?>" required>
            <label for="site_phone" style="color:#fff;">Contact Phone</label>
            <input type="text" name="site_phone" id="site_phone" value="<?php echo htmlspecialchars($site_phone); ?>">
            <label for="site_address" style="color:#fff;">Address</label>
            <input type="text" name="site_address" id="site_address" value="<?php echo htmlspecialchars($site_address); ?>">
            <label for="site_description" style="color:#fff;">Site Description</label>
            <textarea name="site_description" id="site_description"><?php echo htmlspecialchars($site_description); ?></textarea>
            <button type="submit">Save Settings</button>
        </form>
        <div style="text-align:center; margin-top:1rem;">
            <a href="index.php" style="color:#ffd700;">&larr; Back to Dashboard</a>
        </div>
    </div>
</body>
</html>

<?php require_once '../includes/footer.php'; ?>