<?php
// File: index.php

// This file has been updated with the new company name.
require_once 'config/database.php';
require_once 'includes/header.php';
?>

<!-- Minimal internal styling for the carousel functionality to ensure it works with your external CSS -->
<style>
    .carousel-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0;
        transition: opacity 1.5s ease-in-out;
    }
    .carousel-image.active {
        opacity: 1;
    }
</style>

<!-- Hero Section with Image Carousel -->
<section id="hero" class="hero-section">
    <div class="video-carousel">
        <!-- Replaced video tags with image tags -->
        <img class="carousel-image active" src="assets/images/20250226_084451.jpg" alt="Placeholder Image 1">
        <img class="carousel-image" src="assets/images/20250226_092415.jpg" alt="Placeholder Image 2">
        <img class="carousel-image" src="assets/images/20250210_125341.jpg" alt="Placeholder Image 3">
    </div>
    <div class="hero-content">
        <div class="container animated-hero">
            <h1>Crafting Stories, Amplifying Sounds</h1>
            <p>Welcome to Eleva Kreatives, where vision meets execution in film and music production.</p>
            <div class="cta-buttons">
                <a href="eleva-production/index.php" class="btn btn-primary">Eleva Production</a>
                <a href="eleva-sonic/index.php" class="btn btn-secondary">Eleva Sonic</a>
            </div>
        </div>
    </div>
</section>

<!-- Services Preview Section -->
<section id="services" class="services-section section-padding bg-dark animated-section">
    <div class="container">
        <h2>Our Core Services</h2>
        <div class="service-grid">
            <div class="service-card">
                <i class="fas fa-film"></i>
                <h3>Eleva Production</h3>
                <p>From commercials and documentaries to event coverage, we handle all aspects of film production with a keen eye for detail and cinematic quality.</p>
                <a href="eleva-production/index.php" class="btn-link">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="service-card">
                <i class="fas fa-music"></i>
                <h3>Eleva Sonic</h3>
                <p>We offer professional music production, post-production for film, sound design, and vocal training to create immersive audio experiences.</p>
                <a href="eleva-sonic/index.php" class="btn-link">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>

<?php
require_once 'includes/footer.php';
$conn->close();
?>

<script>
    // This script handles the image carousel functionality
    document.addEventListener('DOMContentLoaded', () => {
        const images = document.querySelectorAll('.carousel-image');
        let currentImageIndex = 0;
        const intervalTime = 5000; // Change image every 5 seconds

        // Function to show the next image and hide the current one
        const showNextImage = () => {
            // Remove active class from the current image
            images[currentImageIndex].classList.remove('active');

            // Update to the next image index
            currentImageIndex = (currentImageIndex + 1) % images.length;

            // Add active class to the next image
            images[currentImageIndex].classList.add('active');
        };

        // Start the carousel by showing and playing the first image
        if (images.length > 0) {
            images[0].classList.add('active');
            setInterval(showNextImage, intervalTime);
        }
    });
</script>
