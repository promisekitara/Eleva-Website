<?php
require_once '../config/database.php';
require_once '../includes/header.php';
?>

<main class="production-main">
    <section class="all-projects">
        <h2>All Production Projects</h2>
        <div class="project-grid">
            <?php
            $sql = "SELECT * FROM projects WHERE type='production' ORDER BY date_created DESC";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="project-card">';
                    if ($row['video_url']) {
                        echo '<video controls src="' . htmlspecialchars(VIDEO_PATH . 'uploads/' . $row['video_url']) . '" poster="' . htmlspecialchars(IMG_PATH . 'projects/' . $row['image_url']) . '"></video>';
                    } else {
                        echo '<img src="' . htmlspecialchars(IMG_PATH . 'projects/' . $row['image_url']) . '" alt="' . htmlspecialchars($row['title']) . '">';
                    }
                    echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
                    echo '</div>';
                }
            } else {
                echo '<p class="text-center">No projects found.</p>';
            }
            ?>
        </div>
    </section>
</main>

<?php require_once '../includes/footer.php'; ?>