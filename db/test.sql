-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 19, 2026 at 08:37 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
-- Table structure for table `article`
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
-- Dumping data for table `article`
--

INSERT INTO `article` (`id`, `title`, `user_id`, `summary`, `codeBlock`, `imgFileName`, `lastEdit`) VALUES
(1, 'Article1', 1, 'The body text of article 1', 'The codeblock of article 1', 'Article1.jpg', '2026-08-11'),
(2, 'article2', 2, 'the body text of article 2', 'The codeblock of article2', 'article2.jpg\r\n', '2026-08-12'),
(4, 'testaaa', 1, 'test2ddd', 'test3dd', 'test.jpg', '2026-08-12');

-- --------------------------------------------------------

--
-- Table structure for table `article_to_tag`
--

CREATE TABLE `article_to_tag` (
  `article_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `article_to_tag`
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
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `date`, `message`) VALUES
(1, 'about', 'test', '2026-08-12', 'test');

-- --------------------------------------------------------

--
-- Table structure for table `field_info`
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
-- Dumping data for table `field_info`
--

INSERT INTO `field_info` (`id`, `name`, `type`, `class`, `lookup_info_id`, `form_info_id`, `label`, `display_order`) VALUES
(1, 'name', 'text', 'contact-name', NULL, 1, 'Your name:', 0),
(2, 'email', 'email', 'contact-email', NULL, 1, 'Your email:', 1),
(3, 'password', 'password', 'login-password', NULL, 2, 'Password:', 1),
(4, 'abouttext', 'textarea', 'about-text', NULL, 5, 'About me:', 0),
(6, 'verifypassword', 'password', 'register-verifypassword', NULL, 7, 'Verify password:', 0),
(7, 'Author', 'select', 'filter-author', 2, 3, 'Filter by Author', 1),
(8, 'Tag', 'select', 'filter-tag', 1, 3, 'Filter by Tag', 0),
(9, 'aboutimg', 'file', 'about-img-file', NULL, 5, 'Upload file:', 1),
(12, 'message', 'textarea', 'message-text', NULL, 1, 'Your message:', 2),
(13, 'email', 'email', 'login-email', NULL, 2, 'Email:', 0),
(14, 'name', 'text', 'register-name', NULL, 7, 'Your name:', 1),
(15, 'email', 'email', 'register-email', NULL, 7, 'Your email:', 2),
(16, 'password', 'password', 'register-password', NULL, 7, 'Password:', 3),
(17, 'articletext', 'textarea', 'article-text', NULL, 6, 'Body text:', 0),
(18, 'articlecodeblock', 'textarea', 'article-codeblock', NULL, 6, 'Codeblock:', 1),
(19, 'articleimg', 'file', 'article-img-file', NULL, 6, 'Upload file:', 4);

-- --------------------------------------------------------

--
-- Table structure for table `form_info`
--

CREATE TABLE `form_info` (
  `id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `method` varchar(25) NOT NULL,
  `submit_caption` varchar(255) NOT NULL,
  `website_info_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `form_info`
--

INSERT INTO `form_info` (`id`, `action`, `method`, `submit_caption`, `website_info_id`) VALUES
(1, 'contact.php', 'POST', 'Send message', 3),
(2, 'login.php', 'POST', 'Log in', 4),
(3, 'search.php', 'GET', 'Filter', 6),
(4, 'editArticle.php', 'POST', 'SaveArticle\r\n', 7),
(5, '\"\"', 'POST', 'Save About', 2),
(6, 'test.php', 'POST', 'Create new article', 8),
(7, 'register.php', 'POST', 'Register', 5);

-- --------------------------------------------------------

--
-- Table structure for table `lookup_info`
--

CREATE TABLE `lookup_info` (
  `id` int(11) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `column_names` varchar(255) NOT NULL,
  `order_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lookup_info`
--

INSERT INTO `lookup_info` (`id`, `table_name`, `column_names`, `order_by`) VALUES
(1, 'tag\r\n', 'id,name', 'tag.name'),
(2, 'user', 'id,name', 'user.name');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `label` varchar(255) NOT NULL,
  `href` varchar(255) NOT NULL,
  `display_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `label`, `href`, `display_order`) VALUES
(1, 'Home', 'home', 0),
(2, 'About', 'about', 1),
(3, 'Contact', 'contact', 2),
(4, 'Search', 'search', 3),
(5, 'Register', 'register', 4),
(6, 'Login', 'login', 5),
(7, 'Dashboard', 'dashboard', 6),
(8, 'Logout', 'logout', 7);

-- --------------------------------------------------------

--
-- Table structure for table `page_elements`
--

CREATE TABLE `page_elements` (
  `id` int(11) NOT NULL,
  `element` text NOT NULL,
  `display_level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `user_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `rating` tinyint(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`user_id`, `article_id`, `rating`) VALUES
(1, 1, 4),
(1, 2, 1),
(2, 1, 1),
(2, 2, 5),
(3, 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `table_columns`
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
-- Dumping data for table `table_columns`
--

INSERT INTO `table_columns` (`id`, `column_name`, `column_title`, `display_type`, `class_types`, `column_headers`) VALUES
(1, 'id', 'Actions', 'first_cell', 'first_cell', 'first_cellTableHead'),
(2, 'title', 'Title', 'string', 'articletitle', 'articletitleTableHead'),
(3, 'lastEdit', 'Last edited', 'date', 'lastEdit', 'lastEditTableHead');

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE `tag` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tag`
--

INSERT INTO `tag` (`id`, `name`) VALUES
(1, 'tag1'),
(2, 'tag2'),
(3, 'tag3'),
(4, 'tag4'),
(5, 'tag82');

-- --------------------------------------------------------

--
-- Table structure for table `user`
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
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `password`, `email`, `imgFileName`, `description`) VALUES
(1, 'Danny', 'Password', 'danny@email.com', 'image.jpg', 'This is the description of user from database'),
(2, 'user2', 'asdfsd', 'dadfa@adfaf.com', 'user2.jpg', 'the description of user2'),
(3, 'Danny3', 'Password', 'danny@email1.com', '', ''),
(5, 'Danny5', 'Password', 'danny@email12.com', '', '');

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_article_avg_rating`
-- (See below for the actual view)
--
CREATE TABLE `v_article_avg_rating` (
`id` int(11)
,`AVGrating` decimal(8,4)
);

-- --------------------------------------------------------

--
-- Table structure for table `website_info`
--

CREATE TABLE `website_info` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `bodytext` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `website_info`
--

INSERT INTO `website_info` (`id`, `name`, `bodytext`) VALUES
(1, 'home', 'This is the bodytext for home from the database'),
(2, 'about', 'This is the bodytext for about from the database'),
(3, 'contact', ''),
(4, 'login', ''),
(5, 'register', ''),
(6, 'search', ''),
(7, 'editArticle', ''),
(8, 'dashboard', '');

-- --------------------------------------------------------

--
-- Structure for view `v_article_avg_rating`
--
DROP TABLE IF EXISTS `v_article_avg_rating`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_article_avg_rating`  AS SELECT `a`.`id` AS `id`, avg(`r`.`rating`) AS `AVGrating` FROM (`article` `a` left join `rating` `r` on(`a`.`id` = `r`.`article_id`)) GROUP BY `a`.`id` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_article_to_user_id` (`user_id`);

--
-- Indexes for table `article_to_tag`
--
ALTER TABLE `article_to_tag`
  ADD PRIMARY KEY (`article_id`,`tag_id`),
  ADD KEY `fk_article_to_tag_tag_id` (`tag_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `field_info`
--
ALTER TABLE `field_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_info_id` (`form_info_id`),
  ADD KEY `lookup_info_id` (`lookup_info_id`);

--
-- Indexes for table `form_info`
--
ALTER TABLE `form_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lookup_info`
--
ALTER TABLE `lookup_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page_elements`
--
ALTER TABLE `page_elements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`user_id`,`article_id`),
  ADD KEY `fk_rating_to_article_id` (`article_id`);

--
-- Indexes for table `table_columns`
--
ALTER TABLE `table_columns`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `column_key_unique` (`column_name`);

--
-- Indexes for table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `website_info`
--
ALTER TABLE `website_info`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `article`
--
ALTER TABLE `article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `field_info`
--
ALTER TABLE `field_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `form_info`
--
ALTER TABLE `form_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `lookup_info`
--
ALTER TABLE `lookup_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `page_elements`
--
ALTER TABLE `page_elements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `table_columns`
--
ALTER TABLE `table_columns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tag`
--
ALTER TABLE `tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `website_info`
--
ALTER TABLE `website_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `fk_article_to_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `article_to_tag`
--
ALTER TABLE `article_to_tag`
  ADD CONSTRAINT `fk_article_to_tag_article_id` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_article_to_tag_tag_id` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `field_info`
--
ALTER TABLE `field_info`
  ADD CONSTRAINT `field_info_ibfk_1` FOREIGN KEY (`form_info_id`) REFERENCES `form_info` (`id`),
  ADD CONSTRAINT `field_info_ibfk_2` FOREIGN KEY (`lookup_info_id`) REFERENCES `lookup_info` (`id`);

--
-- Constraints for table `rating`
--
ALTER TABLE `rating`
  ADD CONSTRAINT `fk_rating_to_article_id` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rating_to_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
