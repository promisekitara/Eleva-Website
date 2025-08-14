<?php
// File: eleva-sonic/index.php
// This is the homepage for Eleva Sonic, a music production and discovery platform.
// It features a dynamic, modern design with a built-in audio player.
require_once '../config/database.php';

// Define a query to fetch the latest tracks from the database.
$sql_tracks = "SELECT title, artist, cover_art_url, audio_url FROM tracks ORDER BY release_date DESC LIMIT 8";
$result_tracks = $conn->query($sql_tracks);

// Check for query errors
if ($result_tracks === false) {
    // Log the error and set result to an empty array to prevent the page from crashing.
    $result_tracks = [];
    error_log("Database query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eleva Sonic | The Sound of Tomorrow</title>
    <meta name="description" content="Discover new music, artists, and soundscapes from Eleva Sonic.">
    <!-- Google Fonts for a modern, bold look -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    
    <style>
        /* Base Styles */
        body {
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
            background-color: #121212;
            color: #f1f1f1;
            margin: 0;
            padding: 0;
            scroll-behavior: smooth;
            overflow-x: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .section-padding {
            padding: 100px 0;
        }

        .text-center {
            text-align: center;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 10px;
        }

        .btn-primary {
            background: linear-gradient(45deg, #00c6ff, #0072ff);
            color: #ffffff;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 114, 255, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 114, 255, 0.6);
        }

        .btn-secondary {
            background-color: transparent;
            color: #ffffff;
            border: 2px solid #ffffff;
        }

        .btn-secondary:hover {
            background-color: #ffffff;
            color: #121212;
        }

        .section-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(3rem, 8vw, 4rem);
            font-weight: 700;
            margin-bottom: 3rem;
            text-align: center;
            color: #00c6ff;
            text-shadow: 0 0 10px rgba(0, 198, 255, 0.4);
            text-transform: uppercase;
        }

        /* Header Navigation Styles */
        #sonic-nav {
            background-color: rgba(18, 18, 18, 0.9);
            backdrop-filter: blur(8px);
            padding: 15px 0;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }

        #sonic-nav .nav-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #sonic-nav .header-logo {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.5rem;
            color: #ffffff;
            text-decoration: none;
            letter-spacing: 2px;
            transition: color 0.3s ease;
        }
        
        #sonic-nav .header-logo:hover {
            color: #00c6ff;
        }

        #sonic-nav .main-nav ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
        }

        #sonic-nav .main-nav li {
            margin: 0 25px;
        }

        #sonic-nav .main-nav a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            text-transform: uppercase;
        }

        #sonic-nav .main-nav a:hover {
            color: #00c6ff;
        }

        .mobile-menu-icon {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #ffffff;
        }

        .main-nav.active {
            display: flex;
            flex-direction: column;
            position: absolute;
            top: 70px;
            left: 0;
            width: 100%;
            background-color: rgba(18, 18, 18, 0.95);
            padding: 20px 0;
            text-align: center;
        }

        .main-nav.active li {
            margin: 15px 0;
        }

        /* Hero Section */
        #hero-sonic {
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), url('https://placehold.co/1920x1080/1a1a1a/e0e0e0?text=Mixing+board+with+neon+lights');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: #ffffff;
            height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding-top: 70px;
            position: relative;
        }

        .hero-content h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(4rem, 10vw, 6rem);
            font-weight: 700;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 5px;
            text-shadow: 2px 2px 20px rgba(0, 198, 255, 0.6);
        }

        .hero-content p {
            font-size: clamp(1rem, 2vw, 1.5rem);
            margin-bottom: 2rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            color: #ccc;
        }

        /* Track Showcase Section */
        #track-showcase {
            background-color: #121212;
        }

        #track-showcase .track-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        #track-showcase .track-card {
            background-color: #1a1a1a;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.4);
            position: relative;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        #track-showcase .track-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 198, 255, 0.3);
        }

        #track-showcase .track-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        #track-showcase .track-card:hover img {
            transform: scale(1.05);
        }

        #track-showcase .track-card .track-info {
            padding: 20px;
        }

        #track-showcase .track-card h3 {
            font-size: 1.25rem;
            color: #ffffff;
            margin: 0 0 5px 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        #track-showcase .track-card p {
            font-size: 1rem;
            color: #aaa;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* Audio Player Modal */
        #audio-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.95);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 2000;
            padding: 20px;
        }
        
        #audio-modal .audio-container {
            width: 90%;
            max-width: 600px;
            background-color: #1a1a1a;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 30px rgba(0, 198, 255, 0.5);
            position: relative;
            text-align: center;
        }

        #audio-modal .close-btn {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 2rem;
            color: #ffffff;
            cursor: pointer;
            z-index: 2001;
            transition: color 0.3s ease;
        }

        #audio-modal .close-btn:hover {
            color: #00c6ff;
        }
        
        #audio-modal h3 {
            margin-top: 0;
        }

        #audio-modal audio {
            width: 100%;
            margin-top: 20px;
            background-color: #333;
            border-radius: 5px;
            border: 1px solid #444;
        }
        
        #audio-modal audio::-webkit-media-controls-panel {
            background-color: #222;
            color: #f1f1f1;
        }
        
        #audio-modal audio::-webkit-media-controls-current-time-display,
        #audio-modal audio::-webkit-media-controls-time-remaining-display {
            color: #f1f1f1;
        }
        
        #audio-modal audio::-webkit-media-controls-play-button,
        #audio-modal audio::-webkit-media-controls-volume-slider {
            color: #00c6ff;
        }
        
        /* Other Sections */
        #about-sonic, #contact-sonic {
            background-color: #1a1a1a;
        }
        
        #contact-sonic h2 {
            color: #00c6ff;
        }
        
        /* Footer */
        #sonic-footer {
            background-color: #0c0c0c;
            color: #888;
            padding: 30px 0;
            text-align: center;
            font-size: 0.9rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            #sonic-nav .main-nav {
                display: none;
            }
            .mobile-menu-icon {
                display: block;
            }
        }
    </style>
</head>
<body>

<!-- Header Navigation -->
<header id="sonic-nav">
    <div class="container nav-content">
        <a href="index.php" class="header-logo">Eleva Sonic</a>
        <nav class="main-nav">
            <ul>
                <li><a href="../index.php">Kreatives</a></li>
                <li><a href="index.php">Home</a></li>
                <li><a href="artists.php">Artists</a></li>
                <li><a href="albums.php">Albums</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
        <div class="mobile-menu-icon">
            <i class="fas fa-bars"></i>
        </div>
    </div>
</header>

<!-- Hero Section -->
<section id="hero-sonic">
    <div class="hero-content">
        <h1>The Sound of Tomorrow</h1>
        <p>A new frontier in music and audio production. Discover, listen, and create.</p>
        <div class="cta-buttons">
            <a href="albums.php" class="btn btn-primary">Explore Music</a>
            <a href="artists.php" class="btn btn-secondary">Featured Artists</a>
        </div>
    </div>
</section>

<!-- Track Showcase Section -->
<section id="track-showcase" class="section-padding">
    <div class="container">
        <h2 class="section-title">Latest Tracks</h2>
        <div class="track-grid">
            <?php
            if ($result_tracks && $result_tracks->num_rows > 0) {
                while($row = $result_tracks->fetch_assoc()) {
                    echo '<div class="track-card">';
                    // Use a data attribute to store the audio URL
                    echo '<a href="#" class="track-link" data-audio-url="' . htmlspecialchars($row['audio_url']) . '">';
                    echo '<img src="' . htmlspecialchars($row['cover_art_url']) . '" alt="' . htmlspecialchars($row['title']) . ' Album Cover">';
                    echo '<div class="track-info">';
                    echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
                    echo '<p>' . htmlspecialchars($row['artist']) . '</p>';
                    echo '</div>';
                    echo '</a>';
                    echo '</div>';
                }
            } else {
                echo '<p class="text-center" style="grid-column: 1 / -1;">No tracks available yet. Please check back soon!</p>';
            }
            ?>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about-sonic" class="section-padding">
    <div class="container text-center">
        <h2 class="section-title">About Eleva Sonic</h2>
        <p>Eleva Sonic is the music and sound division of Eleva Studios. We are dedicated to pushing the boundaries of audio, from cinematic soundtracks to modern electronic music. Our mission is to provide a platform for artists to be heard and for listeners to discover their next favorite sound.</p>
    </div>
</section>

<!-- Contact Call to Action -->
<section id="contact-sonic" class="section-padding text-center">
    <div class="container">
        <h2 class="section-title">Let's Connect</h2>
        <p>Whether you're an artist looking for a home or a collaborator with a vision, we'd love to hear from you.</p>
        <a href="contact.php" class="btn btn-primary">Get in Touch</a>
    </div>
</section>

<!-- Footer -->
<footer id="sonic-footer">
    <p>&copy; <?php echo date("Y"); ?> Eleva Sonic. All Rights Reserved.</p>
</footer>

<!-- Audio Player Modal -->
<div id="audio-modal">
    <div class="audio-container">
        <span class="close-btn">&times;</span>
        <h3 id="modal-track-title"></h3>
        <audio id="audio-player" controls autoplay></audio>
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

        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (mainNav.classList.contains('active')) {
                    mainNav.classList.remove('active');
                }
            });
        });

        // Audio Player Modal Logic
        const audioModal = document.getElementById('audio-modal');
        const closeBtn = audioModal.querySelector('.close-btn');
        const audioPlayer = document.getElementById('audio-player');
        const modalTitle = document.getElementById('modal-track-title');
        const trackLinks = document.querySelectorAll('.track-link');

        function openAudioModal(url) {
            audioPlayer.src = url;
            audioModal.style.display = 'flex';
            audioPlayer.play();
        }
        
        function closeAudioModal() {
            audioModal.style.display = 'none';
            audioPlayer.pause();
            audioPlayer.src = '';
        }

        trackLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const audioUrl = link.getAttribute('data-audio-url');
                const trackTitle = link.querySelector('h3').innerText;
                modalTitle.innerText = trackTitle;
                if (audioUrl) {
                    openAudioModal(audioUrl);
                }
            });
        });

        closeBtn.addEventListener('click', closeAudioModal);

        audioModal.addEventListener('click', (e) => {
            if (e.target === audioModal) {
                closeAudioModal();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && audioModal.style.display === 'flex') {
                closeAudioModal();
            }
        });
    });
</script>

<?php
// Close the database connection
$conn->close();
?>
