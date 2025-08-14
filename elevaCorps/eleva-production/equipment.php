<?php
require_once '../config/database.php';
require_once '../includes/header.php';
?>

<main class="production-main">
    <section id="production-equipment">
        <h2 class="section-heading">Production Equipment Rental</h2>
        <div class="equipment-grid">
            <?php
            $sql = "SELECT * FROM equipment WHERE category='production' ORDER BY name";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="equipment-card">';
                    echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                    echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                    echo '<p class="rate">Daily Rate: $' . htmlspecialchars($row['daily_rate']) . '</p>';
                    echo '<p class="availability">Status: ' . htmlspecialchars($row['availability']) . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p class="text-center">No production equipment listed.</p>';
            }
            ?>
        </div>
    </section>
</main>

<?php require_once '../includes/footer.php'; ?>