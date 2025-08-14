<?php
// File: admin/login.php
// This script handles the login process for admin users.

session_start();

// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection file
require_once '../config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate user input
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic form validation
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        try {
            // Prepare a statement to select the user by username
            $stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE username = ? LIMIT 1");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 1) {
                // Should not happen with UNIQUE key on username, but good practice
                $error = 'Internal server error. Please contact support.';
            } elseif ($user = $result->fetch_assoc()) {
                // Verify the submitted password against the stored hash
                if (password_verify($password, $user['password_hash'])) {
                    // Password is correct, start the session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $username;
                    $_SESSION['is_logged_in'] = true;

                    // Redirect the user to the admin dashboard
                    header('Location: index.php');
                    exit();
                } else {
                    // Invalid password
                    $error = 'Invalid username or password.';
                }
            } else {
                // Invalid username (user not found)
                $error = 'Invalid username or password.';
            }
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            // Catch and display specific database errors for debugging
            $error = "Database Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Include your external stylesheet -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /*
         * Internal CSS for the login page.
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
        .login-container {
            max-width: 450px;
            width: 90%;
            padding: 2.5rem;
            background: #1c1c1c;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.5);
            text-align: center;
            border: 1px solid #333;
        }
        .login-container h2 {
            font-size: 2rem;
            margin-bottom: 2rem;
            color: #FFD700;
            font-weight: 600;
        }
        .login-container label {
            display: block;
            margin-bottom: 0.5rem;
            color: #b0b0b0;
            font-weight: 500;
            text-align: left;
        }
        .login-container input {
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
        .login-container input:focus {
            outline: none;
            border-color: #FFD700;
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.3);
        }
        .login-container button {
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
        .login-container button:hover {
            background: #e5c000;
            transform: translateY(-2px);
        }
        .message-container {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            color: #ff6b6b;
            background: #3a1a1a;
        }
        .register-link {
            display: block;
            margin-top: 1.5rem;
            color: #FFD700;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        .register-link:hover {
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        
        <?php if ($error): ?>
            <div class="message-container">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required autofocus>
            
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            
            <button type="submit">Log In</button>
        </form>
        <a href="register.php" class="register-link">Don't have an account? Register here.</a>
    </div>
</body>
</html>
