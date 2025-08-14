<?php
// File: eleva-sonic/contact.php
// This page provides a contact form for users to send a message.
// The PHP code handles form submission and displays a success/error message.

// Include the database connection file if needed, though this page doesn't directly
// interact with the database. We include it for consistency and shared session/config.
require_once '../config/database.php';

$success_message = '';
$error_message = '';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate form inputs
    $name = trim(htmlspecialchars($_POST['name'] ?? ''));
    $email = trim(htmlspecialchars($_POST['email'] ?? ''));
    $subject = trim(htmlspecialchars($_POST['subject'] ?? ''));
    $message = trim(htmlspecialchars($_POST['message'] ?? ''));

    // Basic validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_message = "Please fill in all the fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address.";
    } else {
        // Form is valid, simulate sending the email.
        // In a real-world application, you would use a library like PHPMailer
        // or a service like SendGrid to send the email here.
        
        // For this example, we'll just log the data and show a success message.
        // You could uncomment the following lines to log to a file.
        // $contact_log = "Name: $name\nEmail: $email\nSubject: $subject\nMessage:\n$message\n\n";
        // file_put_contents('contact_log.txt', $contact_log, FILE_APPEND);

        $success_message = "Thank you, $name! Your message has been sent. We will get back to you shortly.";
        
        // Clear the form fields after successful submission
        $name = '';
        $email = '';
        $subject = '';
        $message = '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eleva Sonic | Contact</title>
    <meta name="description" content="Contact the Eleva Sonic team with any questions or feedback.">
    <!-- Google Fonts for a modern, bold look -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    
    <style>
        /* Base Styles */
        body {
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
            background-color: #121212;
            color: #f1f1f1;
            margin: 0;
            padding: 0;
            scroll-behavior: smooth;
            overflow-x: hidden;
        }

        .container {
            max-width: 800px; /* Adjusted max-width for the form */
            margin: 0 auto;
            padding: 0 20px;
        }

        .section-padding {
            padding: 100px 0;
        }

        .section-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(3rem, 8vw, 4rem);
            font-weight: 700;
            margin-bottom: 3rem;
            text-align: center;
            color: #00c6ff;
            text-shadow: 0 0 10px rgba(0, 198, 255, 0.4);
            text-transform: uppercase;
        }

        /* Header Navigation Styles */
        #sonic-nav {
            background-color: rgba(18, 18, 18, 0.9);
            backdrop-filter: blur(8px);
            padding: 15px 0;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }

        #sonic-nav .nav-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #sonic-nav .header-logo {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.5rem;
            color: #ffffff;
            text-decoration: none;
            letter-spacing: 2px;
            transition: color 0.3s ease;
        }
        
        #sonic-nav .header-logo:hover {
            color: #00c6ff;
        }

        #sonic-nav .main-nav ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
        }

        #sonic-nav .main-nav li {
            margin: 0 25px;
        }

        #sonic-nav .main-nav a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            text-transform: uppercase;
        }

        #sonic-nav .main-nav a:hover {
            color: #00c6ff;
        }

        .mobile-menu-icon {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #ffffff;
        }

        .main-nav.active {
            display: flex;
            flex-direction: column;
            position: absolute;
            top: 70px;
            left: 0;
            width: 100%;
            background-color: rgba(18, 18, 18, 0.95);
            padding: 20px 0;
            text-align: center;
        }

        .main-nav.active li {
            margin: 15px 0;
        }

        /* Contact Page Specific Styles */
        :root {
            --primary-color: #00c6ff; /* Vibrant blue accent */
            --background-dark: #121212;
            --input-bg: #1a1a1a;
            --input-border: #333;
            --input-focus-border: #00c6ff;
            --transition-speed: 0.3s;
        }

        .contact-content {
            text-align: center;
            margin-bottom: 50px;
        }

        .contact-content p {
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto 30px;
            color: #ccc;
        }

        .contact-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #fff;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 15px;
            background-color: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 8px;
            color: #fff;
            font-size: 1rem;
            transition: border-color var(--transition-speed), box-shadow var(--transition-speed);
            box-sizing: border-box; /* Ensure padding doesn't affect width */
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--input-focus-border);
            box-shadow: 0 0 10px rgba(0, 198, 255, 0.5);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 150px;
        }

        .submit-button {
            background-color: var(--primary-color);
            color: #fff;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 1.2rem;
            font-weight: 700;
            cursor: pointer;
            transition: background-color var(--transition-speed), transform var(--transition-speed);
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 5px 20px rgba(0, 198, 255, 0.3);
        }
        
        .submit-button:hover {
            background-color: #0072ff;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 198, 255, 0.5);
        }

        .message-box {
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            font-size: 1.1rem;
            margin-bottom: 20px;
        }

        .success-message {
            background-color: #1d3625;
            color: #4CAF50;
            border: 1px solid #4CAF50;
        }

        .error-message {
            background-color: #361d1d;
            color: #f44336;
            border: 1px solid #f44336;
        }

        /* Footer */
        #sonic-footer {
            background-color: #0c0c0c;
            color: #888;
            padding: 30px 0;
            text-align: center;
            font-size: 0.9rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            #sonic-nav .main-nav {
                display: none;
            }
            .mobile-menu-icon {
                display: block;
            }
        }
    </style>
</head>
<body>

<!-- Header Navigation -->
<header id="sonic-nav">
    <div class="container nav-content">
        <a href="index.php" class="header-logo">Eleva Sonic</a>
        <nav class="main-nav">
            <ul>
                <li><a href="../index.php">Kreatives</a></li>
                <li><a href="index.php">Home</a></li>
                <li><a href="artists.php">Artists</a></li>
                <li><a href="albums.php">Albums</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
        <div class="mobile-menu-icon">
            <i class="fas fa-bars"></i>
        </div>
    </div>
</header>

<section id="contact" class="section-padding" style="padding-top: 150px;">
    <div class="container">
        <div class="contact-content">
            <h2 class="section-title">Get in Touch</h2>
            <p>
                Have a question, feedback, or a partnership idea? We'd love to hear from you.
                Fill out the form below, and we'll get back to you as soon as possible.
            </p>
        </div>

        <?php
        // Display a message if a form submission has occurred
        if ($success_message) {
            echo '<div class="message-box success-message">' . $success_message . '</div>';
        } elseif ($error_message) {
            echo '<div class="message-box error-message">' . $error_message . '</div>';
        }
        ?>
        
        <form action="contact.php" method="POST" class="contact-form">
            <div class="form-group">
                <label for="name">Your Name</label>
                <input type="text" id="name" name="name" required placeholder="Enter your name" value="<?php echo htmlspecialchars($name); ?>">
            </div>
            <div class="form-group">
                <label for="email">Your Email</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email address" value="<?php echo htmlspecialchars($email); ?>">
            </div>
            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" required placeholder="What is your message about?" value="<?php echo htmlspecialchars($subject); ?>">
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" required placeholder="Type your message here..."><?php echo htmlspecialchars($message); ?></textarea>
            </div>
            <button type="submit" class="submit-button">Send Message</button>
        </form>
    </div>
</section>

<!-- Footer -->
<footer id="sonic-footer">
    <p>&copy; <?php echo date("Y"); ?> Eleva Sonic. All Rights Reserved.</p>
</footer>

<!-- JavaScript for interactivity -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile Menu Toggle
        const menuIcon = document.querySelector('.mobile-menu-icon');
        const mainNav = document.querySelector('.main-nav');
        const navLinks = document.querySelectorAll('.main-nav a');

        menuIcon.addEventListener('click', () => {
            mainNav.classList.toggle('active');
        });

        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (mainNav.classList.contains('active')) {
                    mainNav.classList.remove('active');
                }
            });
        });
    });
</script>

<?php 
// Close the database connection.
$conn->close(); 
?>
