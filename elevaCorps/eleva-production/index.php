<?php
// File: eleva-production/index.php
// This version of the production page is now a self-contained HTML document with
// a complete, responsive navigation bar and SEO-friendly meta tags.
require_once '../config/database.php';

// --- NEW CODE STARTS HERE ---
// Define a query to fetch the latest videos from the database.
// The `videos` table is assumed to have `title`, `thumbnail_url`, and `video_url` columns.
// We'll limit the results to a small number for the homepage and order by a date column.
$sql_videos = "SELECT title, thumbnail_url, video_url FROM videos ORDER BY upload_date DESC LIMIT 6";
$result_videos = $conn->query($sql_videos);

// Check for query errors, though it's good practice to handle this more robustly
if ($result_videos === false) {
    // You might want to log this error in a real application
    // For now, we'll just set the result to an empty array to avoid breaking the page
    $result_videos = [];
    error_log("Database query failed: " . $conn->error);
}
// --- NEW CODE ENDS HERE ---
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eleva Productions | Cinematic Stories</title>
    <!-- Enhanced SEO and metadata -->
    <meta name="description" content="Eleva Productions brings cinematic stories to life. Specializing in documentaries, commercials, and event videography.">
    <!-- Favicon for better branding -->
    <link rel="icon" href="../assets/images/favicon.ico" type="image/x-icon">
    <!-- Google Fonts for a professional look -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for social and UI icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <style>
        /* A more cinematic and moody aesthetic */
        body {
            font-family: 'Montserrat', sans-serif;
            line-height: 1.8;
            background-color: #0c0c0c;
            color: #e0e0e0;
            margin: 0;
            padding: 0;
            scroll-behavior: smooth;
        }
        .container {
            max-width: 1140px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .section-padding {
            padding: 100px 0;
        }
        .bg-dark {
            background-color: #121212;
            color: #ffffff;
        }
        .text-center {
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 14px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 10px; /* Consistent margin for buttons */
        }
        .btn-primary {
            background-color: #e65100;
            color: #ffffff;
            border: 2px solid #e65100;
        }
        .btn-primary:hover {
            background-color: transparent;
            color: #e65100;
            box-shadow: 0 0 15px rgba(230, 81, 0, 0.6);
        }
        .btn-secondary {
            background-color: transparent;
            color: #e0e0e0;
            border: 2px solid #e0e0e0;
        }
        .btn-secondary:hover {
            background-color: #e0e0e0;
            color: #121212;
            box-shadow: 0 0 15px rgba(224, 224, 224, 0.6);
        }
        .section-title {
            font-family: 'Cinzel', serif;
            font-size: clamp(2.5rem, 6vw, 3.5rem);
            font-weight: 700;
            margin-bottom: 3rem;
            text-align: center;
            color: #e65100;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 0 10px rgba(230, 81, 0, 0.4);
        }

        /* Header Navigation Styles */
        #production-nav {
            background-color: rgba(18, 18, 18, 0.8);
            backdrop-filter: blur(5px);
            padding: 20px 0;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            transition: background-color 0.3s ease;
        }
        #production-nav .nav-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        #production-nav .header-logo {
            height: 40px;
        }
        #production-nav .main-nav ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
        }
        #production-nav .main-nav li {
            margin: 0 20px;
        }
        #production-nav .main-nav a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease, text-shadow 0.3s ease;
            text-transform: uppercase;
        }
        #production-nav .main-nav a:hover {
            color: #e65100;
            text-shadow: 0 0 5px #e65100;
        }
        .mobile-menu-icon {
            display: none;
        }
        .main-nav.active {
            display: flex;
            flex-direction: column;
            position: absolute;
            top: 80px;
            left: 0;
            width: 100%;
            background-color: rgba(18, 18, 18, 0.95);
            padding: 20px 0;
            text-align: center;
        }
        .main-nav.active li {
            margin: 10px 0;
        }

        /* Hero Section with animation */
        #hero-production {
            background-image: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), url('https://placehold.co/1920x1080/1a1a1a/e0e0e0?text=A+camera+filming+a+scene');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: #ffffff;
            height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding-top: 80px;
        }
        .hero-content {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInSlideUp 1s forwards ease-out;
            animation-delay: 0.5s;
        }
        #hero-production h1 {
            font-family: 'Cinzel', serif;
            font-size: clamp(3rem, 7vw, 5rem);
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.9);
        }
        #hero-production p {
            font-size: clamp(1rem, 2vw, 1.25rem);
            margin-bottom: 3rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            color: #bdc3c7;
        }
        @keyframes fadeInSlideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* About Section */
        #about-production {
            background-color: #121212;
        }
        #about-production .about-content {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
            border-left: 2px solid #e65100;
            border-right: 2px solid #e65100;
            padding: 0 40px;
        }
        #about-production .about-content p {
            font-size: 1.1rem;
            color: #c0c0c0;
        }

        /* Services Menu Section */
        #services-menu .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 40px;
            margin-top: 40px;
        }
        #services-menu .service-card {
            background-color: #1a1a1a;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.4);
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        #services-menu .service-card:hover {
            transform: translateY(-15px) scale(1.05);
            box-shadow: 0 15px 30px rgba(230, 81, 0, 0.3); /* Glow effect on hover */
            border-color: #e65100;
        }
        #services-menu .service-card h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        #services-menu .service-card h3 a {
            color: #ffffff;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        #services-menu .service-card h3 a:hover {
            color: #e65100;
        }
        #services-menu .service-card p {
            color: #bdc3c7;
        }

        /* --- NEW CSS FOR VIDEO SHOWCASE --- */
        #video-showcase .video-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        #video-showcase .video-card {
            background-color: #1a1a1a;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.4);
            position: relative;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        #video-showcase .video-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(230, 81, 0, 0.3);
        }

        #video-showcase .video-card img {
            width: 100%;
            height: 200px; /* Fixed height for consistent look */
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        #video-showcase .video-card:hover img {
            transform: scale(1.05);
        }

        #video-showcase .video-card .video-info {
            padding: 20px;
            text-align: center;
        }

        #video-showcase .video-card h3 {
            font-size: 1.25rem;
            color: #ffffff;
            margin: 0;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }

        /* --- NEW CSS FOR VIDEO MODAL --- */
        #video-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.95);
            display: none; /* Hidden by default */
            justify-content: center;
            align-items: center;
            z-index: 2000;
            padding: 20px;
        }

        #video-modal .video-container {
            position: relative;
            width: 90%;
            max-width: 1200px;
            height: auto;
            padding-top: 56.25%; /* 16:9 aspect ratio */
            box-shadow: 0 0 30px rgba(230, 81, 0, 0.5);
            border-radius: 12px;
            overflow: hidden;
        }

        #video-modal iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        #video-modal .close-btn {
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 2.5rem;
            color: #ffffff;
            cursor: pointer;
            z-index: 2001;
            transition: color 0.3s ease;
        }

        #video-modal .close-btn:hover {
            color: #e65100;
        }
        /* --- END NEW CSS --- */

        /* Detailed Services Sections */
        .section-padding h2 {
            text-align: left;
            margin-bottom: 1.5rem;
        }
        .section-padding p {
            font-size: 1.1rem;
            color: #c0c0c0;
            max-width: 900px;
        }
        .section-padding.bg-dark h2, .section-padding.bg-dark p {
            color: #ffffff;
        }

        /* Booking Section */
        #booking {
            background-image: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), url('https://placehold.co/1920x1080/1a1a1a/ffffff?text=Booking+with+a+film+crew');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        #booking h2 {
            color: #e65100;
        }

        /* Footer */
        #production-footer {
            background-color: #0c0c0c;
            color: #ffffff;
            padding: 30px 0;
            text-align: center;
            font-size: 0.9rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        #production-footer p {
            margin: 0;
            color: #888;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            #production-nav .main-nav {
                display: none;
            }
            .mobile-menu-icon {
                display: block;
                font-size: 1.5rem;
                cursor: pointer;
                color: #ffffff;
            }
            .section-title {
                text-align: center;
            }
            .section-padding h2 {
                text-align: center;
            }
        }
    </style>
</head>
<body>

<!-- New, Independent Navigation Bar for Eleva Productions -->
<header id="production-nav">
    <div class="container nav-content">
        <a href="../index.php" class="logo">
            <img src="../assets/images/logo.png" alt="Eleva Studios Logo" class="header-logo">
        </a>
        <nav class="main-nav">
            <ul>
                <li><a href="../index.php">Kreatives</a></li>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="packages.php">Packages</a></li>
                <li><a href="booking.php">Booking</a></li>
            </ul>
        </nav>
        <div class="mobile-menu-icon">
            <i class="fas fa-bars"></i>
        </div>
    </div>
</header>

<!-- Production Hero Section -->
<section id="hero-production">
    <div class="hero-content">
        <h1>Your Vision. Our Frame.</h1>
        <p>From script to screen, we craft compelling stories that leave a lasting impression.</p>
        <div class="cta-buttons">
            <a href="booking.php" class="btn btn-primary">Start a Project</a>
            <a href="services.php" class="btn btn-secondary">Explore Services</a>
        </div>
    </div>
</section>

<!-- Brief About Section -->
<section id="about-production" class="section-padding">
    <div class="container">
        <h2 class="section-title">Who We Are</h2>
        <div class="about-content">
            <p>Eleva Productions is the dedicated film and video branch of Eleva Studios. We are a collective of passionate filmmakers, cinematographers, and editors committed to transforming ideas into visual masterpieces. Our work is guided by a belief that every story, no matter how big or small, deserves to be told with cinematic excellence.</p>
        </div>
    </div>
</section>

<!-- Services Menu Section -->
<section id="services-menu" class="section-padding bg-dark">
    <div class="container">
        <h2 class="section-title text-center">Our Services</h2>
        <div class="services-grid">
            <div class="service-card">
                <h3><a href="services.php">Film & Documentary</a></h3>
                <p>Full-service production for independent films, shorts, and insightful documentaries.</p>
            </div>
            <div class="service-card">
                <h3><a href="services.php">TV Commercials</a></h3>
                <p>Crafting high-impact commercials that elevate your brand's presence and message.</p>
            </div>
            <div class="service-card">
                <h3><a href="services.php">Events</a></h3>
                <p>Capturing the essence of your events with dynamic videography and professional editing.</p>
            </div>
            <div class="service-card">
                <h3><a href="packages.php">Video Packages</a></h3>
                <p>Customizable packages for corporate videos, social media content, and promotional material.</p>
            </div>
            <div class="service-card">
                <h3><a href="services.php">Equipment Rental</a></h3>
                <p>Access our professional-grade cameras, lighting, and sound equipment for your own projects.</p>
            </div>
        </div>
    </div>
</section>

<!-- --- NEW VIDEO SHOWCASE SECTION STARTS HERE --- -->
<section id="video-showcase" class="section-padding">
    <div class="container">
        <h2 class="section-title text-center">Our Latest Work</h2>
        <div class="video-grid">
            <?php
            // Check if the query returned any rows
            if ($result_videos && $result_videos->num_rows > 0) {
                // Loop through each video from the database
                while($row = $result_videos->fetch_assoc()) {
                    // Use the data to create a video card
                    // We've changed the <a> tag to use a data attribute for the video URL
                    // and set the href to # to prevent default navigation.
                    echo '<div class="video-card">';
                    echo '<a href="#" class="video-link" data-video-url="' . htmlspecialchars($row['video_url']) . '">';
                    echo '<img src="' . htmlspecialchars($row['thumbnail_url']) . '" alt="' . htmlspecialchars($row['title']) . ' Thumbnail">';
                    echo '<div class="video-info">';
                    echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
                    echo '</div>';
                    echo '</a>';
                    echo '</div>';
                }
            } else {
                // Display a message if no videos are found
                echo '<p class="text-center" style="grid-column: 1 / -1;">No videos have been uploaded yet. Please check back soon!</p>';
            }
            ?>
        </div>
    </div>
</section>
<!-- --- NEW VIDEO SHOWCASE SECTION ENDS HERE --- -->

<!-- Detailed Services Sections -->
<section id="film-doc" class="section-padding bg-dark">
    <div class="container">
        <h2 class="section-title">Film & Documentary</h2>
        <p>Our passion lies in storytelling. We partner with you from pre-production to post-production to bring your narrative to life with stunning visuals and powerful soundscapes.</p>
        <!-- Dynamic content can go here, like a gallery of film stills -->
    </div>
</section>

<section id="tv-commercials" class="section-padding">
    <div class="container">
        <h2 class="section-title">TV Commercials</h2>
        <p>Make your brand unforgettable. Our team specializes in creating commercials that are not just advertisements, but short cinematic experiences that resonate with your target audience.</p>
        <!-- Dynamic content can go here, like a showcase of recent commercials -->
    </div>
</section>

<section id="events" class="section-padding bg-dark">
    <div class="container">
        <h2 class="section-title">Event Videography</h2>
        <p>From corporate conferences to brand launches, we expertly document your special moments. Our event packages ensure every key moment is captured with professionalism and style.</p>
    </div>
</section>

<section id="video-packages" class="section-padding">
    <div class="container">
        <h2 class="section-title">Video Packages</h2>
        <p>No project is too small. We offer a variety of scalable video packages perfect for social media campaigns, explainer videos, and personal brand development.</p>
    </div>
</section>

<section id="equipment-rental" class="section-padding bg-dark">
    <div class="container">
        <h2 class="section-title">Equipment Rental</h2>
        <p>Empower your own creativity. We provide access to our top-of-the-line production equipment, including cameras, lenses, drones, and lighting setups, at competitive rental rates.</p>
    </div>
</section>

<!-- Booking Call to Action Section -->
<section id="booking" class="section-padding bg-dark text-center">
    <div class="container">
        <h2>Ready to tell your story?</h2>
        <p>We're here to help you turn your concepts into reality. Let's start the conversation.</p>
        <a href="booking.php" class="btn btn-primary">Book a Project</a>
    </div>
</section>

<!-- New, Self-Contained Footer -->
<footer id="production-footer">
    <p>&copy; <?php echo date("Y"); ?> Eleva Productions. All Rights Reserved.</p>
</footer>

<!-- --- NEW VIDEO MODAL HTML --- -->
<div id="video-modal">
    <span class="close-btn">&times;</span>
    <div class="video-container">
        <iframe src="" title="Eleva Production Video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
    </div>
</div>

<!-- JavaScript for interactivity -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile Menu Toggle
        const menuIcon = document.querySelector('.mobile-menu-icon');
        const mainNav = document.querySelector('.main-nav');
        const navLinks = document.querySelectorAll('.main-nav a');

        menuIcon.addEventListener('click', () => {
            mainNav.classList.toggle('active');
        });

        // Close mobile menu when a link is clicked
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (mainNav.classList.contains('active')) {
                    mainNav.classList.remove('active');
                }
            });
        });

        // --- NEW JAVASCRIPT FOR VIDEO MODAL ---
        const videoModal = document.getElementById('video-modal');
        const closeBtn = videoModal.querySelector('.close-btn');
        const videoIframe = videoModal.querySelector('iframe');
        const videoLinks = document.querySelectorAll('.video-link');

        // Function to open the modal and play the video
        function openVideoModal(url) {
            // The video URL is extracted from the data attribute.
            // We ensure it is a valid embed URL. For example, a YouTube URL needs
            // to be converted to an embed format. This example assumes the database
            // provides an embeddable URL directly.
            videoIframe.src = url;
            videoModal.style.display = 'flex';
        }

        // Function to close the modal and stop the video
        function closeVideoModal() {
            videoModal.style.display = 'none';
            // Stop the video from playing by resetting the iframe src
            videoIframe.src = '';
        }

        // Event listeners for each video thumbnail
        videoLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                // Prevent the default link behavior
                e.preventDefault();
                // Get the video URL from the data-video-url attribute
                const videoUrl = link.getAttribute('data-video-url');
                if (videoUrl) {
                    openVideoModal(videoUrl);
                }
            });
        });

        // Event listener for the close button
        closeBtn.addEventListener('click', closeVideoModal);

        // Close the modal if the user clicks outside of the video container
        videoModal.addEventListener('click', (e) => {
            if (e.target === videoModal) {
                closeVideoModal();
            }
        });

        // Close the modal with the Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && videoModal.style.display === 'flex') {
                closeVideoModal();
            }
        });
        // --- END NEW JAVASCRIPT ---
    });
</script>

<?php
// Close the database connection at the end of the file
$conn->close();
?>
</body>
</html>
