<?php
// File: eleva-production/about.php

// This page provides an overview of Eleva Productions, its history, mission, and vision.
// It is styled to be a seamless part of the professional, cinematic website.
require_once '../config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eleva Productions | Our Story</title>
    <!-- Enhanced SEO and metadata -->
    <meta name="description" content="Learn about Eleva Productions, our mission, our passion for cinematic storytelling, and our dedicated team.">
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
            margin: 0 10px;
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

        /* Hero Section for About */
        #hero-about {
            background-image: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), url('https://placehold.co/1920x1080/1a1a1a/e0e0e0?text=Our+Story');
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
        #hero-about h1 {
            font-family: 'Cinzel', serif;
            font-size: clamp(3rem, 7vw, 5rem);
            font-weight: 700;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.9);
        }

        /* About Content Section */
        #about-content {
            padding: 80px 0;
            background-color: #0c0c0c;
        }
        #about-content .content-wrapper {
            display: flex;
            gap: 50px;
            align-items: flex-start;
        }
        #about-content .text-block {
            flex: 1;
        }
        #about-content h2 {
            font-family: 'Cinzel', serif;
            color: #e65100;
            font-size: 2.5rem;
            margin-bottom: 25px;
            text-transform: uppercase;
        }
        #about-content p {
            font-size: 1.1rem;
            margin-bottom: 20px;
            color: #bdc3c7;
        }
        #about-content .vision-box {
            flex: 1;
            background-color: #1a1a1a;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.4);
            border: 2px solid rgba(255, 255, 255, 0.1);
        }
        #about-content .vision-box h3 {
            font-family: 'Cinzel', serif;
            color: #ffffff;
            font-size: 2rem;
            margin-bottom: 15px;
        }
        #about-content .vision-box p {
            font-size: 1rem;
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
            #about-content .content-wrapper {
                flex-direction: column;
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

<!-- Hero Section for About -->
<section id="hero-about">
    <div class="hero-content">
        <h1>Our Story</h1>
    </div>
</section>

<!-- About Content Section -->
<section id="about-content" class="section-padding">
    <div class="container">
        <div class="content-wrapper">
            <div class="text-block">
                <h2>Our Mission</h2>
                <p>
                    Eleva Productions was founded on a simple yet powerful principle: to elevate stories through the art of visual media. We believe that every narrative, whether it's a corporate brand, an independent film, or a personal event, deserves to be told with cinematic precision and emotional depth. Our journey began with a small team of passionate creatives and has grown into a full-service production house dedicated to delivering exceptional results.
                </p>
                <p>
                    We specialize in creating content that not only looks stunning but also connects with your audience on a profound level. We manage the entire production process, from the initial script and concept development to the final edit, ensuring a seamless and collaborative experience every step of the way.
                </p>
            </div>
            <div class="vision-box">
                <h3>Our Vision</h3>
                <p>
                    To be a leader in innovative visual storytelling, setting new standards for quality and creativity in the film and video production industry. We strive to be a catalyst for change, using our craft to inspire, inform, and entertain audiences worldwide.
                </p>
                <a href="services.php" class="btn btn-primary">Our Services</a>
            </div>
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
