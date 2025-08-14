<?php
// File: eleva-production/booking.php

// This page provides a contact form for clients to book a new video production project.
// It is styled to be a seamless part of the Eleva Productions website.
require_once '../config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eleva Productions | Book a Project</title>
    <!-- Enhanced SEO and metadata -->
    <meta name="description" content="Contact Eleva Productions to book your next cinematic project, commercial, or event video.">
    <!-- Favicon for better branding -->
    <link rel="icon" href="../assets/images/favicon.ico" type="image/x-icon">
    <!-- Google Fonts for a professional look -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for social and UI icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    
    <style>
        /* A more cinematic and moody aesthetic */
        body {
            font-family: 'Montserrat', sans-serif;
            line-height: 1.8;
            background-color: #0c0c0c;
            color: #e0e0e0;
            margin: 0;
            padding: 0;
            scroll-behavior: smooth;
        }
        .container {
            max-width: 1140px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .section-padding {
            padding: 100px 0;
        }
        .bg-dark {
            background-color: #121212;
            color: #ffffff;
        }
        .text-center {
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 14px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 10px; /* Consistent margin for buttons */
        }
        .btn-primary {
            background-color: #e65100;
            color: #ffffff;
            border: 2px solid #e65100;
        }
        .btn-primary:hover {
            background-color: transparent;
            color: #e65100;
            box-shadow: 0 0 15px rgba(230, 81, 0, 0.6);
        }
        .section-title {
            font-family: 'Cinzel', serif;
            font-size: clamp(2.5rem, 6vw, 3.5rem);
            font-weight: 700;
            margin-bottom: 3rem;
            text-align: center;
            color: #e65100;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 0 10px rgba(230, 81, 0, 0.4);
        }

        /* Header Navigation Styles */
        #production-nav {
            background-color: rgba(18, 18, 18, 0.8);
            backdrop-filter: blur(5px);
            padding: 20px 0;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            transition: background-color 0.3s ease;
        }
        #production-nav .nav-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        #production-nav .header-logo {
            height: 40px;
        }
        #production-nav .main-nav ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
        }
        #production-nav .main-nav li {
            margin: 0 20px;
        }
        #production-nav .main-nav a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease, text-shadow 0.3s ease;
            text-transform: uppercase;
        }
        #production-nav .main-nav a:hover {
            color: #e65100;
            text-shadow: 0 0 5px #e65100;
        }
        .mobile-menu-icon {
            display: none;
        }
        .main-nav.active {
            display: flex;
            flex-direction: column;
            position: absolute;
            top: 80px;
            left: 0;
            width: 100%;
            background-color: rgba(18, 18, 18, 0.95);
            padding: 20px 0;
            text-align: center;
        }
        .main-nav.active li {
            margin: 10px 0;
        }

        /* Hero Section for Booking */
        #hero-booking {
            background-image: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), url('https://placehold.co/1920x1080/1a1a1a/e0e0e0?text=Booking+a+Project');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: #ffffff;
            height: 50vh; /* Shorter hero for sub-page */
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding-top: 80px;
        }
        #hero-booking h1 {
            font-family: 'Cinzel', serif;
            font-size: clamp(3rem, 7vw, 5rem);
            font-weight: 700;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.9);
        }

        /* Booking Form Styles */
        #booking-form-section {
            padding: 80px 0;
        }
        #booking-form-section .form-wrapper {
            max-width: 800px;
            margin: 0 auto;
            background-color: #1a1a1a;
            padding: 50px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.4);
            border: 2px solid rgba(255, 255, 255, 0.1);
        }
        .form-group {
            margin-bottom: 25px;
        }
        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
            color: #ffffff;
        }
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background-color: #2a2a2a;
            color: #e0e0e0;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #e65100;
            box-shadow: 0 0 10px rgba(230, 81, 0, 0.5);
        }
        textarea {
            resize: vertical;
            min-height: 150px;
        }
        .submit-btn {
            width: 100%;
            padding: 18px;
            background-color: #e65100;
            color: #ffffff;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            text-transform: uppercase;
        }
        .submit-btn:hover {
            background-color: #ff7043;
            box-shadow: 0 0 20px rgba(230, 81, 0, 0.6);
        }

        /* Footer */
        #production-footer {
            background-color: #0c0c0c;
            color: #ffffff;
            padding: 30px 0;
            text-align: center;
            font-size: 0.9rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        #production-footer p {
            margin: 0;
            color: #888;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            #production-nav .main-nav {
                display: none;
            }
            .mobile-menu-icon {
                display: block;
                font-size: 1.5rem;
                cursor: pointer;
                color: #ffffff;
            }
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<header id="production-nav">
    <div class="container nav-content">
        <a href="../index.php" class="logo">
            <img src="../assets/images/logo.png" alt="Eleva Studios Logo" class="header-logo">
        </a>
        <nav class="main-nav">
            <ul>
                <li><a href="../index.php">Kreatives</a></li>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="packages.php">Packages</a></li>
                <li><a href="booking.php">Booking</a></li>
            </ul>
        </nav>
        <div class="mobile-menu-icon">
            <i class="fas fa-bars"></i>
        </div>
    </div>
</header>

<!-- Hero Section for Booking -->
<section id="hero-booking">
    <div class="hero-content">
        <h1>Book Your Project</h1>
    </div>
</section>

<!-- Booking Form Section -->
<section id="booking-form-section" class="section-padding">
    <div class="container">
        <h2 class="section-title">Let's Get Started</h2>
        <p class="text-center" style="font-size: 1.2rem; max-width: 800px; margin: 0 auto 50px auto; color: #bdc3c7;">
            Please fill out the form below with details about your project, and a member of our team will get in touch shortly.
        </p>
        <div class="form-wrapper">
            <!-- Form will be processed by a backend script (e.g., process_booking.php) -->
            <form action="process_booking.php" method="POST">
                <div class="form-group">
                    <label for="full_name">Full Name*</label>
                    <input type="text" id="full_name" name="full_name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address*</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone">
                </div>
                <div class="form-group">
                    <label for="company">Company/Organization Name</label>
                    <input type="text" id="company" name="company">
                </div>
                <div class="form-group">
                    <label for="project_type">Project Type*</label>
                    <select id="project_type" name="project_type" required>
                        <option value="">-- Select a project type --</option>
                        <option value="film-documentary">Film & Documentary</option>
                        <option value="tv-commercial">TV Commercial</option>
                        <option value="event-videography">Event Videography</option>
                        <option value="video-package">Video Package</option>
                        <option value="custom">Custom Project</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="project_details">Project Details*</label>
                    <textarea id="project_details" name="project_details" required placeholder="Tell us about your project, including goals, target audience, and any specific ideas you have."></textarea>
                </div>
                <div class="form-group">
                    <label for="budget">Project Budget (Optional)</label>
                    <select id="budget" name="budget">
                        <option value="">-- Select a budget range --</option>
                        <option value="$1k-$5k">$1,000 - $5,000</option>
                        <option value="$5k-$10k">$5,000 - $10,000</option>
                        <option value="$10k-$25k">$10,000 - $25,000</option>
                        <option value="$25k+">$25,000+</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="timeline">Desired Project Timeline</label>
                    <input type="text" id="timeline" name="timeline" placeholder="e.g., 'October 2024' or 'Within 3 months'">
                </div>
                <button type="submit" class="submit-btn">Submit Inquiry</button>
            </form>
        </div>
    </div>
</section>

<!-- Footer -->
<footer id="production-footer">
    <p>&copy; <?php echo date("Y"); ?> Eleva Productions. All Rights Reserved.</p>
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

        // Close mobile menu when a link is clicked
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
$conn->close();
?>
</body>
</html>
