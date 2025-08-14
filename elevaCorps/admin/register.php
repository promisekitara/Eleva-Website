<?php
// File: admin/register.php
// This script allows for the registration of new admin users.

session_start();


// This code should be placed at the very top of your PHP script, before any output.

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Include the database connection file
require_once '../config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate user input
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Basic form validation
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = 'Please fill in all the fields.';
    } elseif ($password !== $confirm_password) {
        $error = 'The passwords do not match.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long.';
    } else {
        // Check if the username already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = 'This username is already taken. Please choose another one.';
        } else {
            // Hash the password for secure storage
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = 'admin'; // Hardcode the role as 'admin' for this registration page

            // Insert the new user into the database
            $stmt = $conn->prepare("INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashed_password, $role);

            if ($stmt->execute()) {
                $success = 'User registered successfully! You can now log in.';
            } else {
                $error = 'Something went wrong. Please try again.';
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <!-- Include your external stylesheet -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /*
         * Internal CSS for the registration page.
         * For best practice, move this into your main `style.css` file.
         */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #0e0e0e;
            color: #e0e0e0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .register-container {
            max-width: 450px;
            width: 90%; /* Responsive width */
            padding: 2.5rem;
            background: #1c1c1c;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.5);
            text-align: center;
            border: 1px solid #333;
        }
        .register-container h2 {
            font-size: 2rem;
            margin-bottom: 2rem;
            color: #FFD700;
            font-weight: 600;
        }
        .register-container label {
            display: block;
            margin-bottom: 0.5rem;
            color: #b0b0b0;
            font-weight: 500;
            text-align: left;
        }
        .register-container input {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1.5rem;
            border: 1px solid #444;
            border-radius: 8px;
            background: #282828;
            color: #ffffff;
            font-size: 1rem;
            box-sizing: border-box;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .register-container input:focus {
            outline: none;
            border-color: #FFD700;
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.3);
        }
        .register-container button {
            width: 100%;
            padding: 0.9rem;
            background: #FFD700;
            color: #1c1c1c;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s ease-in-out, transform 0.1s ease-in-out;
        }
        .register-container button:hover {
            background: #e5c000;
            transform: translateY(-2px);
        }
        .message-container {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            font-weight: 600;
        }
        .error-message {
            color: #ff6b6b;
            background: #3a1a1a;
        }
        .success-message {
            color: #8cff9a;
            background: #1a3a1a;
        }
        .login-link {
            display: block;
            margin-top: 1.5rem;
            color: #FFD700;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        .login-link:hover {
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Admin Registration</h2>
        
        <?php if ($error): ?>
            <div class="message-container error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="message-container success-message">
                <?php echo htmlspecialchars($success); ?>
            </div>
            <a href="login.php" class="login-link">Go to Login</a>
        <?php else: ?>
            <form method="post" action="">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required autofocus>
                
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
                
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
                
                <button type="submit">Register</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
