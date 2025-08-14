<?php
// File: team.php

// This page displays all team members by fetching them from the database.
// It includes the header and footer for a consistent site layout.
require_once 'config/database.php';
require_once 'includes/header.php';
?>

<!-- Team Section -->
<section id="team" class="team-section section-padding animated-section" style="padding-top: 150px;">
    <div class="container">
        <h2>Meet Our Team</h2>
        <div class="team-grid">
            <?php
            // Fetch ALL team members from the database
            $sql = "SELECT * FROM team";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="team-card">';
                    echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '" class="team-img">';
                    echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                    echo '<p class="team-position">' . htmlspecialchars($row['position']) . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p class="no-data">No team members found.</p>';
            }
            ?>
        </div>
    </div>
</section>

<?php
require_once 'includes/footer.php';
$conn->close();
?>
