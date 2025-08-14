<?php
// File: contact.php

// This page contains the contact form and information.
// It includes the header and footer for a consistent site layout.
require_once 'config/database.php';
require_once 'includes/header.php';
?>

<!-- Contact Section -->
<section id="contact" class="contact-section section-padding animated-section" style="padding-top: 150px;">
    <div class="container">
        <h2>Get in Touch</h2>
        <div class="contact-grid">
            <div class="contact-info">
                <h3>Contact Details</h3>
                <p><i class="fas fa-phone"></i> (123) 456-7890</p>
                <p><i class="fas fa-envelope"></i> info@elevastudios.com</p>
                <p><i class="fas fa-map-marker-alt"></i> Gulu Independent Hospital</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="contact-form-container">
                <h3>Send us a message</h3>
                <form action="api/contact.php" method="POST" class="contact-form">
                    <input type="text" name="name" placeholder="Your Name" required>
                    <input type="email" name="email" placeholder="Your Email" required>
                    <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php
require_once 'includes/footer.php';
$conn->close();
?>
