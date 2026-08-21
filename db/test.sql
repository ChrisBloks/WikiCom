-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 21 aug 2026 om 13:23
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
-- Tabelstructuur voor tabel `display_classes`
--

CREATE TABLE `display_classes` (
  `id` int(11) NOT NULL,
  `website_info_id` int(11) NOT NULL,
  `class_name` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `display_classes`
--

INSERT INTO `display_classes` (`id`, `website_info_id`, `class_name`, `class`) VALUES
(1, 1, 'bodytext_class', 'fs-2 text-center'),
(2, 2, 'description_class', 'fs-2 text-center'),
(3, 2, 'name_class', 'fs-2 text-center'),
(4, 9, 'title_class', 'text-center'),
(5, 9, 'author_class', 'text-center'),
(6, 9, 'body_class', 'text-center'),
(7, 9, 'codeblock_class', 'php text-center'),
(8, 9, 'img_class', 'img-thumbnail'),
(9, 2, 'img_class', 'img-thumbnail');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `field_info`
--

CREATE TABLE `field_info` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `lookup_info_id` int(11) DEFAULT NULL,
  `form_info_id` int(11) NOT NULL,
  `label` varchar(255) NOT NULL,
  `display_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Gegevens worden geëxporteerd voor tabel `field_info`
--

INSERT INTO `field_info` (`id`, `name`, `type`, `class`, `lookup_info_id`, `form_info_id`, `label`, `display_order`) VALUES
(1, 'name', 'text', 'contact-name form-control', NULL, 1, 'Your name:', 0),
(2, 'email', 'email', 'contact-email form-control', NULL, 1, 'Your email:', 1),
(3, 'password', 'password', 'login-password form-control', NULL, 2, 'Password:', 2),
(4, 'description', 'textarea', 'about-text form-control', NULL, 5, 'About me:', 0),
(6, 'verifypassword', 'password', 'register-verifypassword form-control', NULL, 7, 'Verify password:', 5),
(7, 'Author', 'checkboxgroup', 'filter-author form-check-input', 2, 3, 'Filter by Author', 1),
(8, 'Tag', 'checkboxgroup', 'filter-tag form-check-input', 1, 3, 'Filter by Tag', 0),
(9, 'aboutimg', 'file', 'about-img-file form-control', NULL, 5, 'Upload file:', 1),
(12, 'message', 'textarea', 'message-text form-control', NULL, 1, 'Your message:', 2),
(13, 'email', 'email', 'login-email form-control', NULL, 2, 'Email:', 0),
(14, 'name', 'text', 'register-name form-control', NULL, 7, 'Your name:', 1),
(15, 'email', 'email', 'register-email form-control', NULL, 7, 'Your email:', 2),
(16, 'password', 'password', 'register-password form-control', NULL, 7, 'Password:', 3),
(17, 'summary', 'textarea', 'article-text form-control', NULL, 4, 'Body text:', 15),
(18, 'codeBlock', 'textarea', 'article-codeblock form-control', NULL, 4, 'Codeblock:', 16),
(19, 'articleimg', 'file', 'article-img-file form-control', NULL, 4, 'Upload file:', 17),
(20, 'sortby', 'select', 'sort-by form-control', 3, 3, 'Sort by', 3),
(21, 'new tag', 'text', 'add-new-tag form-control', NULL, 4, 'New tag name:', 0),
(22, 'new tag button', 'button', 'add-new-tag-button btn btn-outline-secondary', NULL, 4, 'Add new tag', 1),
(23, 'Tag', 'select', 'add-tag form-control', 1, 4, 'Tag to add', 2),
(24, 'tag button', 'button', 'add--tag-button btn btn-outline-secondary', NULL, 4, 'Add tag', 3),
(25, 'title', 'text', 'article-title form-control', NULL, 4, 'Article title:', 14),
(26, 'Existing Tag', 'checkboxgroup', 'Existing-tag form-check-input', 4, 4, 'Remove tag', 4),
(27, 'existing tag button', 'button', 'remove--tag-button btn btn-outline-secondary', NULL, 4, 'Remove tag', 5);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `form_info`
--

CREATE TABLE `form_info` (
  `id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `method` varchar(25) NOT NULL,
  `submit_caption` varchar(255) NOT NULL,
  `website_info_id` int(11) NOT NULL,
  `display_class` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `form_info`
--

INSERT INTO `form_info` (`id`, `action`, `method`, `submit_caption`, `website_info_id`, `display_class`) VALUES
(1, 'contact.php', 'POST', 'Send message', 3, 'form-group'),
(2, 'login.php', 'POST', 'Log in', 4, 'form-group'),
(3, 'search.php', 'GET', 'Filter', 6, 'form-group'),
(4, 'editArticle.php', 'POST', 'SaveArticle\r\n', 7, 'form-group'),
(5, '\"\"', 'POST', 'Save About', 2, 'form-group'),
(6, 'test.php', 'POST', 'Create new article', 8, 'form-group'),
(7, 'register.php', 'POST', 'Register', 5, 'form-group');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `lookup_info`
--

CREATE TABLE `lookup_info` (
  `id` int(11) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `display_names` varchar(255) NOT NULL,
  `order_by` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `bridge_table` varchar(255) NOT NULL,
  `bridgevalues` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `lookup_info`
--

INSERT INTO `lookup_info` (`id`, `table_name`, `display_names`, `order_by`, `value`, `bridge_table`, `bridgevalues`) VALUES
(1, 'wiki_tag\n', 'id,name', 'wiki_tag.name', '', '', ''),
(2, 'user', 'id,name', 'user.name', '', '', ''),
(3, '', 'rating,date', '', '', '', ''),
(4, 'wiki_tag\n', 'id,name', 'wiki_tag.name', 'article_id\n', 'wiki_article_to_tag', 'wiki_tag_id,wiki_tag.id');

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
(1, 'Home', 'home', 0),
(2, 'About', 'about', 1),
(3, 'Contact', 'contact', 2),
(4, 'Search', 'search', 3),
(5, 'Register', 'register', 4),
(6, 'Login', 'login', 5),
(7, 'Dashboard', 'dashboard', 6),
(8, 'Logout', 'logout', 7),
(9, 'Article1', 'article&id=1', 9),
(10, 'Article2', 'article&id=2', 9),
(11, 'EditArticle2', 'editArticle&id=2', 11),
(12, 'EditArticle1', 'editArticle&id=1', 9);

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
-- Tabelstructuur voor tabel `table_columns`
--

CREATE TABLE `table_columns` (
  `id` int(11) NOT NULL,
  `column_name` varchar(255) NOT NULL,
  `column_title` varchar(255) NOT NULL,
  `display_type` varchar(255) NOT NULL,
  `class_types` varchar(255) NOT NULL,
  `column_headers` varchar(255) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `table_columns`
--

INSERT INTO `table_columns` (`id`, `column_name`, `column_title`, `display_type`, `class_types`, `column_headers`) VALUES
(1, 'id', 'Actions', 'first_cell', 'first_cell', 'first_cellTableHead'),
(2, 'title', 'Title', 'string', 'articletitle', 'articletitleTableHead'),
(3, 'lastEdit', 'Last edited', 'date', 'lastEdit', 'lastEditTableHead');

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
(3, 'Danny3', 'Password', 'danny@email1.com', '', 'hallo'),
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
  `bodytext` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Gegevens worden geëxporteerd voor tabel `website_info`
--

INSERT INTO `website_info` (`id`, `name`, `bodytext`) VALUES
(1, 'home', 'Welkom op onze hoofdpagina.'),
(2, 'about', 'This is the bodytext for about from the database'),
(3, 'contact', ''),
(4, 'login', ''),
(5, 'register', ''),
(6, 'search', ''),
(7, 'editArticle', ''),
(8, 'dashboard', ''),
(9, 'article', '');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `wiki_article`
--

CREATE TABLE `wiki_article` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `summary` text NOT NULL,
  `codeBlock` text DEFAULT NULL,
  `imgFileName` varchar(255) DEFAULT NULL,
  `lastEdit` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Gegevens worden geëxporteerd voor tabel `wiki_article`
--

INSERT INTO `wiki_article` (`id`, `title`, `user_id`, `summary`, `codeBlock`, `imgFileName`, `lastEdit`) VALUES
(1, 'Article1', 1, 'The body text of article 1', 'The codeblock of article 1', 'Article1.jpg', '2026-08-11'),
(2, 'article2', 2, 'the body text of article 2', 'The codeblock of article2', 'article2.jpg\r\n', '2026-08-12'),
(4, 'testaaa', 1, 'test2ddd', 'test3dd', 'test.jpg', '2026-08-12');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `wiki_article_to_tag`
--

CREATE TABLE `wiki_article_to_tag` (
  `article_id` int(11) NOT NULL,
  `wiki_tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Gegevens worden geëxporteerd voor tabel `wiki_article_to_tag`
--

INSERT INTO `wiki_article_to_tag` (`article_id`, `wiki_tag_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 5),
(2, 3),
(2, 4);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `wiki_rating`
--

CREATE TABLE `wiki_rating` (
  `user_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `rating` tinyint(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `wiki_rating`
--

INSERT INTO `wiki_rating` (`user_id`, `article_id`, `rating`) VALUES
(1, 1, 4),
(1, 2, 1),
(2, 1, 1),
(2, 2, 5),
(3, 2, 3);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `wiki_tag`
--

CREATE TABLE `wiki_tag` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Gegevens worden geëxporteerd voor tabel `wiki_tag`
--

INSERT INTO `wiki_tag` (`id`, `name`) VALUES
(1, 'tag1'),
(2, 'tag2'),
(3, 'tag3'),
(4, 'tag4'),
(5, 'tag82');

-- --------------------------------------------------------

--
-- Structuur voor de view `v_article_avg_rating`
--
DROP TABLE IF EXISTS `v_article_avg_rating`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_article_avg_rating`  AS SELECT `a`.`id` AS `id`, avg(`r`.`rating`) AS `AVGrating` FROM (`wiki_article` `a` left join `wiki_rating` `r` on(`a`.`id` = `r`.`article_id`)) GROUP BY `a`.`id` ;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `display_classes`
--
ALTER TABLE `display_classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_website_info_dplay_classes` (`website_info_id`);

--
-- Indexen voor tabel `field_info`
--
ALTER TABLE `field_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_info_id` (`form_info_id`),
  ADD KEY `lookup_info_id` (`lookup_info_id`);

--
-- Indexen voor tabel `form_info`
--
ALTER TABLE `form_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `lookup_info`
--
ALTER TABLE `lookup_info`
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
-- Indexen voor tabel `table_columns`
--
ALTER TABLE `table_columns`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `column_key_unique` (`column_name`);

--
-- Indexen voor tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `website_info`
--
ALTER TABLE `website_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `wiki_article`
--
ALTER TABLE `wiki_article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_article_to_user_id` (`user_id`);

--
-- Indexen voor tabel `wiki_article_to_tag`
--
ALTER TABLE `wiki_article_to_tag`
  ADD PRIMARY KEY (`article_id`,`wiki_tag_id`),
  ADD KEY `fk_article_to_tag_tag_id` (`wiki_tag_id`);

--
-- Indexen voor tabel `wiki_rating`
--
ALTER TABLE `wiki_rating`
  ADD PRIMARY KEY (`user_id`,`article_id`),
  ADD KEY `fk_rating_to_article_id` (`article_id`);

--
-- Indexen voor tabel `wiki_tag`
--
ALTER TABLE `wiki_tag`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT voor een tabel `display_classes`
--
ALTER TABLE `display_classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT voor een tabel `field_info`
--
ALTER TABLE `field_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT voor een tabel `form_info`
--
ALTER TABLE `form_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT voor een tabel `lookup_info`
--
ALTER TABLE `lookup_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT voor een tabel `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT voor een tabel `page_elements`
--
ALTER TABLE `page_elements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `table_columns`
--
ALTER TABLE `table_columns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT voor een tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT voor een tabel `website_info`
--
ALTER TABLE `website_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT voor een tabel `wiki_article`
--
ALTER TABLE `wiki_article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT voor een tabel `wiki_tag`
--
ALTER TABLE `wiki_tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `display_classes`
--
ALTER TABLE `display_classes`
  ADD CONSTRAINT `fk_website_info_dplay_classes` FOREIGN KEY (`website_info_id`) REFERENCES `website_info` (`id`);

--
-- Beperkingen voor tabel `field_info`
--
ALTER TABLE `field_info`
  ADD CONSTRAINT `field_info_ibfk_1` FOREIGN KEY (`form_info_id`) REFERENCES `form_info` (`id`),
  ADD CONSTRAINT `field_info_ibfk_2` FOREIGN KEY (`lookup_info_id`) REFERENCES `lookup_info` (`id`);

--
-- Beperkingen voor tabel `wiki_article`
--
ALTER TABLE `wiki_article`
  ADD CONSTRAINT `fk_article_to_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `wiki_article_to_tag`
--
ALTER TABLE `wiki_article_to_tag`
  ADD CONSTRAINT `fk_article_to_tag_article_id` FOREIGN KEY (`article_id`) REFERENCES `wiki_article` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_article_to_tag_tag_id` FOREIGN KEY (`wiki_tag_id`) REFERENCES `wiki_tag` (`id`) ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `wiki_rating`
--
ALTER TABLE `wiki_rating`
  ADD CONSTRAINT `fk_rating_to_article_id` FOREIGN KEY (`article_id`) REFERENCES `wiki_article` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rating_to_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
