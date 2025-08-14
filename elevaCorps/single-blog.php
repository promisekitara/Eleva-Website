<?php
// File: blog/post.php

// This page has been updated with the new company name.
require_once '../config/database.php';
require_once '../includes/header.php';

// Get the blog post ID from the URL
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($post_id > 0) {
    $sql = "SELECT * FROM blog WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
} else {
    $post = false;
}
?>

<section id="single-post" class="blog-single section-padding" style="padding-top: 150px;">
    <div class="container">
        <?php if ($post): ?>
            <div class="post-content-full">
                <img src="../<?php echo htmlspecialchars($post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="post-img-full">
                <h1><?php echo htmlspecialchars($post['title']); ?></h1>
                <p class="post-meta-full">By <?php echo htmlspecialchars($post['author']); ?> on <?php echo date("F j, Y", strtotime($post['date_created'])); ?></p>
                <div class="post-body">
                    <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                </div>
            </div>
        <?php else: ?>
            <div class="no-data-full">
                <h2>Post not found.</h2>
                <p>The blog post you are looking for does not exist.</p>
                <a href="index.php" class="btn btn-primary">Back to Blog</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
require_once '../includes/footer.php';
$conn->close();
?>
