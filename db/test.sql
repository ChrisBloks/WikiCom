-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 16 aug 2026 om 23:32
-- Serverversie: 10.4.32-MariaDB
-- PHP-versie: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `article`
--

CREATE TABLE `article` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `summary` text NOT NULL,
  `codeBlock` text DEFAULT NULL,
  `imgFileName` varchar(255) DEFAULT NULL,
  `lastEdit` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Gegevens worden geëxporteerd voor tabel `article`
--

INSERT INTO `article` (`id`, `title`, `user_id`, `summary`, `codeBlock`, `imgFileName`, `lastEdit`) VALUES
(1, 'Article1', 1, 'The body text of article 1', 'The codeblock of article 1', 'Article1.jpg', '2026-08-11'),
(2, 'article2', 2, 'the body text of article 2', 'The codeblock of article2', 'article2.jpg\r\n', '2026-08-12'),
(4, 'testaaa', 1, 'test2ddd', 'test3dd', 'test.jpg', '2026-08-12');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `article_to_tag`
--

CREATE TABLE `article_to_tag` (
  `article_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Gegevens worden geëxporteerd voor tabel `article_to_tag`
--

INSERT INTO `article_to_tag` (`article_id`, `tag_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 5),
(2, 3),
(2, 4);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Gegevens worden geëxporteerd voor tabel `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `date`, `message`) VALUES
(1, 'about', 'test', '2026-08-12', 'test');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `fields_per_page`
--

CREATE TABLE `fields_per_page` (
  `website_info_id` int(11) NOT NULL,
  `field_info_id` int(11) NOT NULL,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `label` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Gegevens worden geëxporteerd voor tabel `fields_per_page`
--

INSERT INTO `fields_per_page` (`website_info_id`, `field_info_id`, `display_order`, `label`) VALUES
(3, 1, 0, 'Your name'),
(3, 2, 1, 'Your email'),
(3, 4, 2, 'Your message'),
(4, 2, 0, 'Your email'),
(4, 3, 1, 'Your password'),
(5, 1, 0, 'Your name'),
(5, 2, 1, 'Your email'),
(5, 3, 2, 'Your password'),
(5, 6, 3, 'Verify password'),
(6, 7, 0, 'Filter by Author'),
(6, 8, 1, 'Filter by Tag');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `field_info`
--

CREATE TABLE `field_info` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `options` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Gegevens worden geëxporteerd voor tabel `field_info`
--

INSERT INTO `field_info` (`id`, `name`, `type`, `class`, `options`) VALUES
(1, 'name', 'text', 'text-input', 0),
(2, 'email', 'email', 'text-input', 0),
(3, 'password', 'password', 'text-input', 0),
(4, 'message', 'textarea', 'text-input', 0),
(5, 'contact-by', 'checkboxgroup', 'text-input', 0),
(6, 'verifypassword', 'password', 'text-input', 0),
(7, 'Author', 'select', 'filter-author', 2),
(8, 'Tag', 'select', 'filter-tag', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `form_info`
--

CREATE TABLE `form_info` (
  `id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `method` varchar(25) NOT NULL,
  `submit_caption` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `form_info`
--

INSERT INTO `form_info` (`id`, `action`, `method`, `submit_caption`) VALUES
(1, 'contact.php', 'POST', 'Send message'),
(2, 'login.php', 'POST', 'Log in'),
(3, 'articles.php', 'GET', 'Filter');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `label` varchar(255) NOT NULL,
  `href` varchar(255) NOT NULL,
  `display_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Gegevens worden geëxporteerd voor tabel `menu_items`
--

INSERT INTO `menu_items` (`id`, `label`, `href`, `display_order`) VALUES
(1, 'Home', '?page=home', 0),
(2, 'About', '?page=about', 1),
(3, 'Contact', '?page=contact', 2),
(4, 'Search', '?page=search', 3),
(5, 'Register', '?page=register', 4),
(6, 'Login', '?page=login', 5),
(7, 'Dashboard', '?page=dashboard', 6);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `page_elements`
--

CREATE TABLE `page_elements` (
  `id` int(11) NOT NULL,
  `element` text NOT NULL,
  `display_level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `rating`
--

CREATE TABLE `rating` (
  `user_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `rating` tinyint(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `rating`
--

INSERT INTO `rating` (`user_id`, `article_id`, `rating`) VALUES
(1, 1, 4),
(1, 2, 1),
(2, 1, 1),
(2, 2, 5),
(3, 2, 3);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `table_columns`
--

CREATE TABLE `table_columns` (
  `id` int(11) NOT NULL,
  `column_key` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `display_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `table_columns`
--

INSERT INTO `table_columns` (`id`, `column_key`, `label`, `type`, `display_order`) VALUES
(1, 'title', 'Title', 'text', 0),
(2, 'last_edited', 'Last edited', 'date', 1),
(3, 'id', 'Actions', 'actions', 2);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tag`
--

CREATE TABLE `tag` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Gegevens worden geëxporteerd voor tabel `tag`
--

INSERT INTO `tag` (`id`, `name`) VALUES
(1, 'tag1'),
(2, 'tag2'),
(3, 'tag3'),
(4, 'tag4'),
(5, 'tag82');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `text`
--

CREATE TABLE `text` (
  `id` int(11) NOT NULL,
  `text` text NOT NULL,
  `display_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `imgFileName` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Gegevens worden geëxporteerd voor tabel `user`
--

INSERT INTO `user` (`id`, `name`, `password`, `email`, `imgFileName`, `description`) VALUES
(1, 'Danny', 'Password', 'danny@email.com', 'image.jpg', 'This is the description of user from database'),
(2, 'user2', 'asdfsd', 'dadfa@adfaf.com', 'user2.jpg', 'the description of user2'),
(3, 'Danny3', 'Password', 'danny@email1.com', '', ''),
(5, 'Danny5', 'Password', 'danny@email12.com', '', '');

-- --------------------------------------------------------

--
-- Stand-in structuur voor view `v_article_avg_rating`
-- (Zie onder voor de actuele view)
--
CREATE TABLE `v_article_avg_rating` (
`id` int(11)
,`AVGrating` decimal(8,4)
);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `website_info`
--

CREATE TABLE `website_info` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `bodytext` text NOT NULL,
  `form_info_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Gegevens worden geëxporteerd voor tabel `website_info`
--

INSERT INTO `website_info` (`id`, `name`, `bodytext`, `form_info_id`) VALUES
(1, 'home', 'This is the bodytext for home from the database', NULL),
(2, 'about', '', NULL),
(3, 'contact', '', 1),
(4, 'login', '', 2),
(5, 'register', '', 2),
(6, 'search', '', 3);

-- --------------------------------------------------------

--
-- Structuur voor de view `v_article_avg_rating`
--
DROP TABLE IF EXISTS `v_article_avg_rating`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_article_avg_rating`  AS SELECT `a`.`id` AS `id`, avg(`r`.`rating`) AS `AVGrating` FROM (`article` `a` left join `rating` `r` on(`a`.`id` = `r`.`article_id`)) GROUP BY `a`.`id` ;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_article_to_user_id` (`user_id`);

--
-- Indexen voor tabel `article_to_tag`
--
ALTER TABLE `article_to_tag`
  ADD PRIMARY KEY (`article_id`,`tag_id`),
  ADD KEY `fk_article_to_tag_tag_id` (`tag_id`);

--
-- Indexen voor tabel `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `fields_per_page`
--
ALTER TABLE `fields_per_page`
  ADD PRIMARY KEY (`website_info_id`,`field_info_id`),
  ADD KEY `field_info_id` (`field_info_id`);

--
-- Indexen voor tabel `field_info`
--
ALTER TABLE `field_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `form_info`
--
ALTER TABLE `form_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `page_elements`
--
ALTER TABLE `page_elements`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`user_id`,`article_id`),
  ADD KEY `fk_rating_to_article_id` (`article_id`);

--
-- Indexen voor tabel `table_columns`
--
ALTER TABLE `table_columns`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `column_key_unique` (`column_key`);

--
-- Indexen voor tabel `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexen voor tabel `text`
--
ALTER TABLE `text`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `website_info`
--
ALTER TABLE `website_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `website_info_ibfk_1` (`form_info_id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `article`
--
ALTER TABLE `article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT voor een tabel `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT voor een tabel `field_info`
--
ALTER TABLE `field_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT voor een tabel `form_info`
--
ALTER TABLE `form_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT voor een tabel `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT voor een tabel `page_elements`
--
ALTER TABLE `page_elements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `table_columns`
--
ALTER TABLE `table_columns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT voor een tabel `tag`
--
ALTER TABLE `tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT voor een tabel `text`
--
ALTER TABLE `text`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT voor een tabel `website_info`
--
ALTER TABLE `website_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `fk_article_to_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `article_to_tag`
--
ALTER TABLE `article_to_tag`
  ADD CONSTRAINT `fk_article_to_tag_article_id` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_article_to_tag_tag_id` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`) ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `fields_per_page`
--
ALTER TABLE `fields_per_page`
  ADD CONSTRAINT `fields_per_page_ibfk_1` FOREIGN KEY (`website_info_id`) REFERENCES `website_info` (`id`),
  ADD CONSTRAINT `fields_per_page_ibfk_2` FOREIGN KEY (`field_info_id`) REFERENCES `field_info` (`id`);

--
-- Beperkingen voor tabel `rating`
--
ALTER TABLE `rating`
  ADD CONSTRAINT `fk_rating_to_article_id` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rating_to_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `website_info`
--
ALTER TABLE `website_info`
  ADD CONSTRAINT `website_info_ibfk_1` FOREIGN KEY (`form_info_id`) REFERENCES `form_info` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
