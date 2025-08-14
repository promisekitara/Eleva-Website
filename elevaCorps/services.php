<?php
// File: services.php

// This page displays a comprehensive list of all services.
require_once 'config/database.php';
require_once 'includes/header.php';
?>

<!-- Services Section -->
<section id="services" class="services-section section-padding bg-dark animated-section" style="padding-top: 150px;">
    <div class="container">
        <h2>Our Services</h2>
        <div class="service-grid">
            <?php
            // Fetch all services from the database, ordered by service name
            $sql = "SELECT * FROM services ORDER BY service_name ASC";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Display each service in a card
                    echo '<div class="service-card">';
                    echo '<i class="' . htmlspecialchars($row['icon_class']) . '"></i>';
                    echo '<h3>' . htmlspecialchars($row['service_name']) . '</h3>';
                    echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                    // Link to a detailed page for the service if available
                    if (!empty($row['page_link'])) {
                        echo '<a href="' . htmlspecialchars($row['page_link']) . '" class="btn-link">Learn More <i class="fas fa-arrow-right"></i></a>';
                    }
                    echo '</div>';
                }
            } else {
                echo '<p class="no-data">No services found.</p>';
            }
            ?>
        </div>
    </div>
</section>

<?php
require_once 'includes/footer.php';
$conn->close();
?>
