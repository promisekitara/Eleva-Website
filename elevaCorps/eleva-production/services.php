<?php
// File: eleva-production/services.php

// This page provides a detailed overview of the video production services offered by Eleva Productions.
// It is styled to be a seamless part of the professional, cinematic website.
require_once '../config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eleva Productions | Our Services</title>
    <!-- Enhanced SEO and metadata -->
    <meta name="description" content="Discover the range of professional video production services offered by Eleva Productions.">
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

        /* Hero Section for Services */
        #hero-services {
            background-image: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), url('https://placehold.co/1920x1080/1a1a1a/e0e0e0?text=Professional+Video+Services');
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
        #hero-services h1 {
            font-family: 'Cinzel', serif;
            font-size: clamp(3rem, 7vw, 5rem);
            font-weight: 700;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.9);
        }

        /* Services Grid */
        #services-list .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            margin-top: 40px;
        }
        #services-list .service-card {
            background-color: #1a1a1a;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.4);
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
            text-align: center;
            border: 2px solid rgba(255, 255, 255, 0.1);
        }
        #services-list .service-card:hover {
            transform: translateY(-15px) scale(1.05);
            box-shadow: 0 15px 30px rgba(230, 81, 0, 0.3); /* Glow effect on hover */
            border-color: #e65100;
        }
        #services-list .service-card .service-icon {
            font-size: 3rem;
            color: #e65100;
            margin-bottom: 20px;
        }
        #services-list .service-card h3 {
            font-size: 2rem;
            font-family: 'Cinzel', serif;
            color: #ffffff;
            margin-bottom: 10px;
        }
        #services-list .service-card p {
            color: #bdc3c7;
            font-size: 1.1rem;
            margin-bottom: 30px;
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
            .section-title {
                text-align: center;
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

<!-- Hero Section for Services -->
<section id="hero-services">
    <div class="hero-content">
        <h1>Our Comprehensive Services</h1>
    </div>
</section>

<!-- Services List Section -->
<section id="services-list" class="section-padding bg-dark">
    <div class="container">
        <p class="text-center" style="font-size: 1.2rem; max-width: 800px; margin: 0 auto 50px auto; color: #bdc3c7;">
            We provide a wide range of services to bring your creative vision to life, from pre-production to final delivery.
        </p>
        <div class="services-grid">
            <!-- Service 1: Film & Documentary Production -->
            <div class="service-card">
                <i class="fas fa-video service-icon"></i>
                <h3>Film & Documentary</h3>
                <p>From narrative shorts to feature-length documentaries, we handle every aspect of cinematic storytelling.</p>
            </div>

            <!-- Service 2: Commercials & Branded Content -->
            <div class="service-card">
                <i class="fas fa-bullhorn service-icon"></i>
                <h3>Commercials & Branded Content</h3>
                <p>Create compelling and impactful advertisements that resonate with your audience and elevate your brand.</p>
            </div>

            <!-- Service 3: Event Videography -->
            <div class="service-card">
                <i class="fas fa-camera-retro service-icon"></i>
                <h3>Event Videography</h3>
                <p>Capture the essence of your events with professional, multi-camera coverage and highlight reels.</p>
            </div>

            <!-- Service 4: Post-Production & Editing -->
            <div class="service-card">
                <i class="fas fa-magic service-icon"></i>
                <h3>Post-Production & Editing</h3>
                <p>Our expert editors transform raw footage into a polished and captivating final product, with color grading and sound design.</p>
            </div>

            <!-- Service 5: Aerial Cinematography -->
            <div class="service-card">
                <i class="fas fa-drone service-icon"></i>
                <h3>Aerial Cinematography</h3>
                <p>Add a new perspective to your project with stunning, high-resolution drone footage and unique aerial shots.</p>
            </div>

            <!-- Service 6: Scriptwriting & Concept Development -->
            <div class="service-card">
                <i class="fas fa-pen-nib service-icon"></i>
                <h3>Scriptwriting & Concept Development</h3>
                <p>Collaborate with our team to develop a powerful concept and a professional script for your next project.</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to action to view packages -->
<section id="cta-packages" class="section-padding text-center">
    <div class="container">
        <h2>Ready to start your project?</h2>
        <p>Explore our pre-defined packages or contact us for a custom quote.</p>
        <a href="packages.php" class="btn btn-primary">View Packages</a>
        <a href="booking.php" class="btn btn-primary">Get a Quote</a>
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
