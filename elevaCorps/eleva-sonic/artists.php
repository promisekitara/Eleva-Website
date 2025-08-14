<?php
// File: eleva-sonic/artists.php
// This page lists all unique artists in the database with a unified, modern design
// that matches the Eleva Sonic homepage, including the header and footer.

// Require the database connection file.
require_once '../config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eleva Sonic | Artists</title>
    <meta name="description" content="Discover artists on the Eleva Sonic platform.">
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

        /* Artists Page Specific Styles */
        :root {
            --primary-color: #00c6ff; /* Vibrant blue accent */
            --secondary-color: #ffffff; /* White for text */
            --text-light: #f1f1f1;
            --background-dark: #121212;
            --card-bg: #1a1a1a;
            --border-color: rgba(255, 255, 255, 0.1);
            --glow-color: rgba(0, 198, 255, 0.3);
            --transition-speed: 0.3s;
        }
        
        .artists-grid {
            display: grid;
            gap: 30px;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            padding: 40px 0;
        }
        
        .artist-card {
            background: linear-gradient(145deg, #1f1f1f, #151515);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.4);
            transition: transform var(--transition-speed), box-shadow var(--transition-speed);
            overflow: hidden;
            position: relative;
            text-align: center;
            /* New: Create a glowing border effect on hover using a pseudo-element */
            border-image-slice: 1;
            border-image-source: linear-gradient(45deg, #00c6ff, #0072ff);
            border: 2px solid transparent;
        }

        .artist-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 10px 30px rgba(0, 198, 255, 0.5);
            border-color: #00c6ff;
        }
        
        .artist-card a {
            text-decoration: none;
            color: inherit;
            display: block;
            padding: 40px 30px;
            position: relative;
            z-index: 2;
        }
        
        .artist-card h3 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.2rem;
            font-weight: 700;
            margin: 0;
            color: var(--secondary-color);
            transition: color var(--transition-speed), text-shadow var(--transition-speed);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .artist-card:hover h3 {
            color: var(--primary-color);
            text-shadow: 0 0 15px rgba(0, 198, 255, 0.7);
        }

        .artist-card p {
            font-family: 'Roboto', sans-serif;
            font-size: 1rem;
            color: #aaa;
            margin-top: 10px;
            font-weight: 400;
            transition: color var(--transition-speed);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .artist-card p i {
            margin-right: 8px;
            color: var(--primary-color);
            transition: transform var(--transition-speed);
        }

        .artist-card:hover p i {
            transform: translateX(5px);
        }


        .no-artists {
            text-align: center;
            grid-column: 1 / -1;
            font-size: 1.2rem;
            color: var(--text-light);
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

<?php
// Fetch a unique list of artists from the 'tracks' table.
$sql = "SELECT DISTINCT artist FROM tracks ORDER BY artist ASC";
$result = $conn->query($sql);
?>

<section id="artists-list" class="section-padding" style="padding-top: 150px;">
    <div class="container">
        <h2 class="section-title">Our Artists</h2>
        <div class="artists-grid">
            <?php
            // Check if there are any artists in the database.
            if ($result && $result->num_rows > 0) {
                // Loop through each unique artist found.
                while ($row = $result->fetch_assoc()) {
                    $artist_name = htmlspecialchars($row['artist']);
                    // URL-encode the artist's name to safely pass it as a query parameter.
                    $url_encoded_artist = urlencode($artist_name);
                    
                    echo '<div class="artist-card">';
                    // The link points to artist.php, passing the artist's name.
                    echo '<a href="artist.php?name=' . $url_encoded_artist . '">';
                    echo '<h3>' . $artist_name . '</h3>';
                    echo '<p><i class="fas fa-music"></i> View Profile</p>';
                    echo '</a>';
                    echo '</div>';
                }
            } else {
                // Display a message if no artists are found.
                echo '<p class="no-artists">No artists found in the database.</p>';
            }
            ?>
        </div>
    </div>
</section>

<!-- Footer -->
<footer id="sonic-footer">
    <p>&copy; <?php echo date("Y"); ?> Eleva Sonic. All Rights Reserved.</p>
</footer>

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
    });
</script>

<?php 
// Close the database connection.
$conn->close(); 
?>
