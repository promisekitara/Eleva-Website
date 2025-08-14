<?php
// File: about.php

// This page contains the about, reviews, and partners sections.
// It includes the header and footer for a consistent site layout.
require_once 'config/database.php';
require_once 'includes/header.php';
?>

<!-- About Section -->
<section id="about" class="about-section section-padding animated-section bg-light" style="padding-top: 150px;">
    <div class="container">
        <h2>About Eleva Corps</h2>
        <div class="about-grid">
            <div class="about-text">
                <p>Eleva Corps is a full-service creative agency specializing in film and music production. Our mission is to elevate your vision, transforming ideas into stunning visual and auditory experiences. With a team of dedicated professionals and cutting-edge technology, we are committed to delivering excellence in every project we undertake.</p>
            </div>
            <div class="about-values">
                <h3>Our Values</h3>
                <ul>
                    <li><i class="fas fa-bullseye"></i> **Mission:** To inspire and move audiences through compelling stories and powerful soundscapes.</li>
                    <li><i class="fas fa-eye"></i> **Vision:** To be a leading force in the creative industry, known for innovation and quality.</li>
                    <li><i class="fas fa-handshake"></i> **Commitment:** We are dedicated to building lasting relationships with our clients through trust and collaboration.</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Customer Reviews Section -->
<section id="reviews" class="reviews-section section-padding animated-section">
    <div class="container">
        <h2>What Our Clients Say</h2>
        <div class="reviews-grid">
            <?php
            // Fetch all reviews from the database
            $sql = "SELECT * FROM reviews ORDER BY date_created DESC";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="review-card">';
                    echo '<i class="fas fa-quote-left"></i>';
                    echo '<p class="review-text">' . htmlspecialchars($row['review']) . '</p>';
                    echo '<p class="review-author">- ' . htmlspecialchars($row['client_name']) . '</p>';
                    echo '<div class="review-rating">';
                    for ($i = 0; $i < $row['rating']; $i++) {
                        echo '<i class="fas fa-star gold"></i>';
                    }
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p class="no-data">No reviews found.</p>';
            }
            ?>
        </div>
    </div>
</section>

<!-- Partners Section -->
<section id="partners" class="partners-section bg-dark animated-section">
    <div class="container">
        <h2>Our Valued Partners</h2>
        <div class="partner-logos">
            <?php
            // Fetch all partner logos from the database
            $sql = "SELECT * FROM partners";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="partner-logo-container">';
                    echo '<a href="' . htmlspecialchars($row['website']) . '" target="_blank">';
                    echo '<img src="' . htmlspecialchars($row['logo_url']) . '" alt="' . htmlspecialchars($row['name']) . ' Logo" class="partner-logo">';
                    echo '</a>';
                    echo '</div>';
                }
            } else {
                echo '<p class="no-data">No partners found.</p>';
            }
            ?>
        </div>
    </div>
</section>

<?php
require_once 'includes/footer.php';
$conn->close();
?>
