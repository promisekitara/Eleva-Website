<?php
// File: blog/index.php

// This page has been updated with the new company name.
require_once '../config/database.php';
require_once '../includes/header.php';
?>

<!-- Blog Section -->
<section id="blog" class="blog-section section-padding bg-dark animated-section" style="padding-top: 150px;">
    <div class="container">
        <h2>Our Blog</h2>
        <div class="blog-grid">
            <?php
            // Fetch all blog posts from the database, ordered by creation date
            $sql = "SELECT * FROM blog ORDER BY date_created DESC";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Loop through each blog post and display it
                    echo '<div class="blog-post">';
                    // The image path is adjusted to correctly reference the parent directory
                    echo '<img src="../' . htmlspecialchars($row['featured_image']) . '" alt="' . htmlspecialchars($row['title']) . '" class="blog-img">';
                    echo '<div class="post-content">';
                    echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
                    echo '<p class="post-meta">By ' . htmlspecialchars($row['author']) . ' on ' . date("F j, Y", strtotime($row['date_created'])) . '</p>';
                    // Link to the single post page, passing the post ID
                    echo '<a href="post.php?id=' . htmlspecialchars($row['id']) . '" class="btn-link">Read More <i class="fas fa-arrow-right"></i></a>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p class="no-data">No blog posts found.</p>';
            }
            ?>
        </div>
    </div>
</section>

<?php
require_once '../includes/footer.php';
$conn->close();
?>
