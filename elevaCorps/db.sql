
CREATE DATABASE IF NOT EXISTS `eleva-kreatives_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;  

USE `eleva-kreatives_db`;
CREATE TABLE `blog` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `featured_image` varchar(2048) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog`
--

INSERT INTO `blog` (`id`, `title`, `author`, `content`, `featured_image`, `date_created`) VALUES
(1, 'The Future of Film & Sound', 'Jane Doe', 'This is the first blog post content. It discusses the exciting future of cinematic and audio experiences...', 'assets/images/blog/post-1.jpg', '2025-08-13 12:19:56'),
(2, 'A Look Behind the Scenes', 'Bob Williams', 'A glimpse into our process of creating a new soundscape for a major project...', 'assets/images/blog/post-2.jpg', '2025-08-13 12:19:56');

-- --------------------------------------------------------

--
-- Table structure for table `partners`
--

CREATE TABLE `partners` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `logo_url` varchar(2048) NOT NULL,
  `website` varchar(2048) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partners`
--

INSERT INTO `partners` (`id`, `name`, `logo_url`, `website`) VALUES
(1, 'Partner A', 'https://placehold.co/150x50/FFFFFF/000000?text=Partner+A', 'https://partnerA.com'),
(2, 'Partner B', 'https://placehold.co/150x50/FFFFFF/000000?text=Partner+B', 'https://partnerB.com');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `review` text NOT NULL,
  `rating` tinyint(1) NOT NULL DEFAULT 5,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `client_name`, `review`, `rating`, `date_created`) VALUES
(1, 'Jane Doe', 'Eleva Corps took our vision and made it a reality. The quality and professionalism were outstanding!', 5, '2025-08-13 12:19:56'),
(2, 'John Smith', 'The sound design for our film was exceptional. Highly recommend Eleva Sonic!', 5, '2025-08-13 12:19:56');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `icon_class` varchar(255) NOT NULL,
  `page_link` varchar(2048) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `service_name`, `description`, `icon_class`, `page_link`) VALUES
(1, 'Film Production', 'Comprehensive film and video production from concept to completion.', 'fas fa-film', 'eleva-production/index.php'),
(2, 'Sound Design', 'Creating unique audio experiences for film, television, and games.', 'fas fa-headphones-alt', 'eleva-sonic/index.php'),
(3, 'Music Production', 'Professional recording, mixing, and mastering services for artists.', 'fas fa-music', 'eleva-sonic/index.php');

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `image_url` varchar(2048) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`id`, `name`, `position`, `image_url`) VALUES
(1, 'Alice Johnson', 'Director of Production', 'https://placehold.co/300x300/4A4A4A/FFFFFF?text=Alice'),
(2, 'Bob Williams', 'Lead Sound Engineer', 'https://placehold.co/300x300/4A4A4A/FFFFFF?text=Bob');

-- --------------------------------------------------------

--
-- Table structure for table `tracks`
--

CREATE TABLE `tracks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL COMMENT 'The title of the song.',
  `artist` varchar(255) NOT NULL COMMENT 'The name of the artist.',
  `cover_art_url` varchar(2048) NOT NULL COMMENT 'URL for the album cover image.',
  `audio_url` varchar(2048) NOT NULL COMMENT 'URL for the audio file.',
  `release_date` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'The date and time the track was released.',
  `genre` varchar(100) DEFAULT NULL COMMENT 'The genre of the music.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tracks`
--

INSERT INTO `tracks` (`id`, `title`, `artist`, `cover_art_url`, `audio_url`, `release_date`, `genre`) VALUES
(1, 'Starlight', 'Nova Beats', 'https://placehold.co/500x500/000000/FFFFFF?text=Starlight', 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3', '2025-08-13 12:19:56', 'Electronic'),
(2, 'Deep Resonance', 'Echo Wave', 'https://placehold.co/500x500/121212/87CEEB?text=Deep+Resonance', 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-2.mp3', '2025-08-13 12:19:56', 'Ambient'),
(3, 'Urban Pulse', 'City Lights', 'https://placehold.co/500x500/343434/FFD700?text=Urban+Pulse', 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-3.mp3', '2025-08-13 12:19:56', 'House'),
(4, 'Crimson Sun', 'Vapor Glitch', 'https://placehold.co/500x500/400000/FFFFFF?text=Crimson+Sun', 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-4.mp3', '2025-08-13 12:19:56', 'Synthwave');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `role`, `created_at`) VALUES
(1, 'Lucas Ravie', '$2y$10$CzysVa0FGWC36Oxyy0KrKOOFG18vXUbyThzcw6wGH2NVeR43Z6h0a', 'admin', '2025-08-13 21:18:13'),
(2, 'Promise Kitara', '$2y$10$em0cbaqXyCgWYO/Gybea/ulixiOjQbMqsCkqdb63rIKVjnBgEcdsS', 'admin', '2025-08-13 21:19:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tracks`
--
ALTER TABLE `tracks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `partners`
--
ALTER TABLE `partners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tracks`
--
ALTER TABLE `tracks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
CREATE TABLE IF NOT EXISTS `partners` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;