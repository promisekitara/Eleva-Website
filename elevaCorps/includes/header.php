<?php
// File: includes/header.php

// This file has been updated with the new company name, a divider element, and
// inline styles to adjust the navigation link spacing and add a cool logo animation.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eleva Kreatives | Film & Music Production</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        /* CSS to adjust spacing of navigation links */
        .main-header .nav-links li {
            margin: 0 10px; /* Reduced the horizontal margin */
        }
        
        /* Adjusting the divider margin to match the new spacing */
        .nav-divider {
            margin: 0 15px; /* Reduced the horizontal margin of the divider */
        }

        /* --- Logo Animation --- */
        .header-logo {
            /* Initially hide the logo and position it below its final spot */
            opacity: 0;
            transform: translateY(20px);
            /* Apply the animation on page load */
            animation: logo-fade-in-up 0.8s ease-out forwards;
            animation-delay: 0.5s; /* Add a slight delay for a better effect */
        }

        @keyframes logo-fade-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* CSS for the almost-white logo background */
        .main-header .logo {
            background-color:rgba(245, 245, 245, 0.07); /* Almost white */
            padding: 5px 10px;
            border-radius: 20px 0px 0px 20px; /* Rounded corners on the left side */
        }
    </style>
</head>
<body>

<header class="main-header">
    <div class="container">
        <a href="index.php" class="logo">
            <img src="assets/images/logo.png" alt="Eleva Kreatives Logo" class="header-logo">
        </a>
        <span class="nav-divider"></span>
        <nav class="main-nav">
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="team.php">Team</a></li>
                <li class="dropdown">
                    <a href="#" class="dropbtn">Services</a>
                    <div class="dropdown-content">
                        <a href="eleva-production/index.php">Eleva Production</a>
                        <a href="eleva-sonic/index.php">Eleva Sonic</a>
                    </div>
                </li>
                <li><a href="blog/index.php">Blog</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
        <div class="mobile-menu-icon">
            <i class="fas fa-bars"></i>
        </div>
    </div>
</header>