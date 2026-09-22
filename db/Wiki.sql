-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 22, 2026 at 11:58 AM
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
-- Database: `wiki`
--
CREATE DATABASE IF NOT EXISTS `wiki` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `wiki`;

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
(1, 'about', 'test', '2026-08-12', 'test'),
(2, 'Test', 'danny@hotmail.comasd', '2026-08-25', 'ads'),
(3, 'asdf', 'danny@hotmail.com', '2026-08-25', 'ads                          a'),
(4, 'Test', 'danny@hotmail.com', '2026-08-25', 'ads'),
(5, 'Test', 'asd@gada.com', '2026-08-25', 'ads'),
(6, 'ads', 'danny@hotmail.com', '2026-08-25', 'ads'),
(7, 'ads', 'asd@gada.com', '2026-08-25', 'ad'),
(8, 'test123', 'dddd@mail.com', '2026-08-25', 'adsadsa'),
(9, '', '', '2026-09-02', ''),
(10, '', '', '2026-09-02', ''),
(11, '', '', '2026-09-07', ''),
(12, 'd', '', '2026-09-07', ''),
(13, '', '', '2026-09-07', ''),
(14, 'Test', 'danny@email.com', '2026-09-07', 'dsd'),
(15, 'd', 'danny@email.com', '2026-09-07', 'd'),
(16, '', '', '2026-09-07', ''),
(17, 'd', 'danny@email.com', '2026-09-07', 'sddssd'),
(18, 'Test', 'danny@email.com', '2026-09-08', 'dfsdf'),
(19, 'Test', 'danny@email.com', '2026-09-08', 'dfsdf'),
(20, 'Test', 'dannytest@email.com', '2026-09-08', 'ddd'),
(21, 'd', 'danny@email.com', '2026-09-08', 'test'),
(22, 'Test', 'danny@email.com', '2026-09-08', 'd');

-- --------------------------------------------------------

--
-- Table structure for table `element_info`
--

CREATE TABLE `element_info` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `html_tag` text NOT NULL,
  `html_class` varchar(255) NOT NULL,
  `php_class` varchar(255) NOT NULL,
  `js_class` text NOT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `element_info`
--

INSERT INTO `element_info` (`id`, `name`, `html_tag`, `html_class`, `php_class`, `js_class`, `text`) VALUES
(1, 'main', 'div', 'd-flex flex-column align-items-center w-75 mx-auto', 'ContainerElement', '', ''),
(2, 'home_text', 'h1', 'display-1', 'AtomicElement', '', 'Welcome to our website'),
(3, 'row_container', 'div', 'row g-4 mb-5', 'ContainerElement', '', ''),
(4, 'featured_col', 'div', 'col-md-8', 'ContainerElement', '', ''),
(5, 'random_article', '', '', 'Card', '', ''),
(6, 'small_coll', 'div', 'col-md-4', 'ContainerElement', '', ''),
(7, 'small_row', 'div', 'row g-4', 'ContainerElement', '', ''),
(8, 'small_wrapper', 'div', 'col-12', 'ContainerElement', '', ''),
(9, 'about_title', 'h1', 'display-1 text-center border-bottom', 'Title', '', ''),
(10, 'about_description', 'div', 'fs-5 text-center', 'BodyText', '', ''),
(11, 'about_img', '', 'rounded-circle profile-pic d-flex justify-content-end mb-3', 'Image', '', ''),
(12, 'main_2', 'div', 'd-flex align-items-center w-75 mx-auto', 'ContainerElement', '', ''),
(13, 'sub', 'div', 'flex-grow-1', 'ContainerElement', '', ''),
(14, 'contact_form', 'form', 'form-group', 'Form', '', ''),
(15, 'contact_name_field', '', '', 'text', '', ''),
(16, 'contact_field_email', '', '', 'email', '', ''),
(17, 'contact_field_message', '', '', 'textarea', '', ''),
(18, 'log_in_form', 'form', 'form-group', 'Form', '', ''),
(19, 'login_in_field_email', '', '', 'email', '', ''),
(20, 'log_in_field_password', '', '', 'password', '', ''),
(21, 'search_form', 'form', 'form-group', 'Form', '', ''),
(22, 'search_field_author', '', '', 'SearchableCheckboxes', '', ''),
(23, 'search_field_tag', '', '', 'SearchableCechboxes', '', ''),
(24, 'search_field_sortby\r\n', '', '', 'select', '', ''),
(25, 'article_form', '', 'form-group', 'Form', '', ''),
(26, 'article_field_title', '', '', 'text', '', ''),
(27, 'article_field_bodytext', '', '', 'textarea', '', ''),
(28, 'article_field_codeblock', '', '', 'textarea', '', ''),
(29, 'article_field_img', '', '', 'file', '', ''),
(30, 'article_field_tags', '', '', 'SearchableCheckboxes', '', ''),
(31, 'register_form', 'form', 'form-group', 'Form', '', ''),
(32, 'register_field_name', '', '', 'text', '', ''),
(33, 'register_field_email', '', '', 'email', '', ''),
(34, 'register_field_new_password', '', '', 'new_password', '', ''),
(35, 'edit_user_form', '', 'form-control mt-5', 'Form', '', ''),
(36, 'edit_user_field_name', '', '', 'text', '', ''),
(37, 'edit_user_field_email', '', '', 'email', '', ''),
(38, 'edit_password_form', '', 'form-control mt-5', 'Form', '', ''),
(39, 'edit_password_field_old', '', '', 'password', '', ''),
(40, 'edit_password_field_new', '', '', 'new_password', '', ''),
(41, 'edit_user_field_description', '', '', '', '', ''),
(42, 'edit_user_field_img', '', '', '', '', ''),
(43, 'create_new_article', '', 'form-group', 'Form', '', ''),
(44, 'search_container', 'div', 'container-fluid', 'ContainerElement', '', ''),
(45, 'search_row', 'div', 'row', 'ContainerElement', '', ''),
(46, 'search_col', 'div', 'col-12 col-md-3 border-end pe-4', 'ContainerElement', '', ''),
(47, 'search_table_container', 'div', 'table-responsive', 'ContainerElement', '', ''),
(50, 'search_table', 'table', 'table table-search table-hover table-striped table-bordered', 'ResultsTable', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `element_lookup_info`
--

CREATE TABLE `element_lookup_info` (
  `id` int(11) NOT NULL,
  `element_id` int(11) NOT NULL,
  `source_table` varchar(255) NOT NULL,
  `column_names` varchar(255) NOT NULL,
  `where_` varchar(255) NOT NULL,
  `where_value` varchar(255) NOT NULL,
  `join_table` varchar(255) NOT NULL,
  `join_on_values` varchar(255) NOT NULL,
  `lookup_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `element_lookup_info`
--

INSERT INTO `element_lookup_info` (`id`, `element_id`, `source_table`, `column_names`, `where_`, `where_value`, `join_table`, `join_on_values`, `lookup_type`) VALUES
(1, 14, 'form_info', 'action,method,label,submit_caption,enctype,submit_class', 'form_info.element_id', '14', '', '', 'form'),
(2, 14, 'element_info', 'id as element_id,php_class', 'element_info.id', '15', '', '', 'element'),
(3, 15, 'field_info', 'name,type,label,value,html_class', 'field_info.element_id', '15', '', '', 'field'),
(4, 16, 'field_info', 'name,type,label,value,html_class', 'field_info.element_id', '16', '', '', 'field'),
(5, 14, 'element_info', 'id as element_id,php_class', 'element_info.id', '16', '', '', 'element'),
(6, 18, 'form_info', 'action,method,label,submit_caption,enctype,submit_class', 'form_info.element_id', '18', '', '', 'form'),
(7, 19, 'field_info', 'name,type,label,value,html_class', 'field_info.element_id', '19', '', '', 'field'),
(8, 20, 'field_info', 'name,type,label,value,html_class', 'field_info.element_id', '20', '', '', 'field'),
(9, 18, 'element_info', 'id as element_id,php_class', 'element_info.id', '19', '', '', 'element'),
(11, 18, 'element_info', 'id as element_id,php_class', 'element_info.id', '20', '', '', 'element'),
(12, 21, 'form_info', 'action,method,label,submit_caption,enctype,submit_class', 'form_info.element_id', '21', '', '', 'form'),
(13, 22, 'field_info', 'name,type,label,value,html_class', 'field_info.element_id', '22', '', '', 'field'),
(14, 23, 'field_info', 'name,type,label,value,html_class', 'field_info.element_id', '23', '', '', 'field'),
(15, 24, 'field_info', 'name,type,label,value,html_class', 'field_info.element_id', '24', '', '', 'field'),
(16, 21, 'element_info', 'id as element_id,php_class', 'element_info.id', '22', '', '', 'element'),
(17, 21, 'element_info', 'id as element_id,php_class', 'element_info.id', '23', '', '', 'element'),
(18, 21, 'element_info', 'id as element_id,php_class', 'element_info.id', '24', '', '', 'element'),
(19, 25, 'form_info', 'action,method,label,submit_caption,enctype,submit_class', 'form_info.element_id', '25', '', '', 'form'),
(20, 26, 'field_info', 'name,type,label,value,html_class', 'field_info.element_id', '26', '', '', 'field'),
(21, 27, 'field_info', 'name,type,label,value,html_class', 'field_info.element_id', '27', '', '', 'field'),
(22, 28, 'field_info', 'name,type,label,value,html_class', 'field_info.element_id', '28', '', '', 'field'),
(23, 29, 'field_info', 'name,type,label,value,html_class', 'field_info.element_id', '29', '', '', 'field'),
(24, 30, 'field_info', 'name,type,label,value,html_class', 'field_info.element_id', '30', '', '', 'field'),
(25, 25, 'element_info', 'id as element_id,php_class', 'element_info.id', '26', '', '', 'element'),
(26, 25, 'element_info', 'id as element_id,php_class', 'element_info.id', '27', '', '', 'element'),
(27, 25, 'element_info', 'id as element_id,php_class', 'element_info.id', '28', '', '', 'element'),
(28, 25, 'element_info', 'id as element_id,php_class', 'element_info.id', '29', '', '', 'element'),
(29, 25, 'element_info', 'id as element_id,php_class', 'element_info.id', '30', '', '', 'element'),
(30, 31, 'form_info', 'action,method,label,submit_caption,enctype,submit_class', 'form_info.element_id', '31', '', '', 'form'),
(31, 32, 'field_info', 'name,type,label,value,html_class', 'field_info.element_id', '32', '', '', 'field'),
(32, 33, 'field_info', 'name,type,label,value,html_class', 'field_info.element_id', '33', '', '', 'field'),
(33, 34, 'field_info', 'name,type,label,value,html_class', 'field_info.element_id', '34', '', '', 'field'),
(34, 31, 'element_info', 'id as element_id,php_class', 'element_info.id', '32', '', '', 'element'),
(35, 31, 'element_info', 'id as element_id,php_class', 'element_info.id', '33', '', '', 'element'),
(36, 31, 'element_info', 'id as element_id,php_class', 'element_info.id', '34', '', '', 'element'),
(37, 35, 'form_info', 'action,method,label,submit_caption,enctype,submit_class', 'form_info.element_id', '35', '', '', 'form'),
(38, 36, 'field_info', 'name,type,label,value,html_class', 'field_info.element_id', '36', '', '', 'field'),
(39, 37, 'field_info', 'name,type,label,value,html_class', 'field_info.element_id', '37', '', '', 'field'),
(40, 41, 'field_info', 'name,type,label,value,html_class', 'field_info.element_id', '41', '', '', 'field'),
(41, 42, 'field_info', 'name,type,label,value,html_class', 'field_info.element_id', '42', '', '', 'field'),
(42, 35, 'element_info', 'id as element_id,php_class', 'element_info.id', '36', '', '', 'element'),
(43, 35, 'element_info', 'id as element_id,php_class', 'element_info.id', '37', '', '', 'element'),
(44, 35, 'element_info', 'id as element_id,php_class', 'element_info.id', '41', '', '', 'element'),
(45, 35, 'element_info', 'id as element_id,php_class', 'element_info.id', '42', '', '', 'element'),
(46, 38, 'form_info', 'action,method,label,submit_caption,enctype,submit_class', 'form_info.element_id', '38', '', '', 'form'),
(47, 39, 'field_info', 'name,type,label,value,html_class', 'field_info.element_id', '39', '', '', 'field'),
(48, 40, 'field_info', 'name,type,label,value,html_class', 'field_info.element_id', '40', '', '', 'field'),
(49, 38, 'element_info', 'id as element_id,php_class', 'element_info.id', '39', '', '', 'element'),
(50, 38, 'element_info', 'id as element_id,php_class', 'element_info.id', '40', '', '', 'element'),
(51, 43, 'form_info', 'action,method,label,submit_caption,enctype,submit_class', 'form_info.element_id', '43', '', '', 'form'),
(52, 14, 'element_info', 'id as element_id,php_class', 'element_info.id', '17', '', '', 'element'),
(53, 17, 'field_info', 'name,type,label,value,html_class', 'field_info.element_id', '17', '', '', 'field'),
(55, 23, 'wiki_tag', 'id,name', '', '', '', '', 'options'),
(56, 22, 'user', 'id,name', '', '', '', '', 'options'),
(57, 24, 'v_sortby_options', 'id,name', '', '', '', '', 'options');

-- --------------------------------------------------------

--
-- Table structure for table `field_info`
--

CREATE TABLE `field_info` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `element_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `html_class` varchar(255) NOT NULL,
  `optional` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `field_info`
--

INSERT INTO `field_info` (`id`, `name`, `element_id`, `type`, `label`, `value`, `html_class`, `optional`) VALUES
(1, 'name', 15, 'text', 'Your name:', '', 'contact-name form-control', 0),
(2, 'email', 16, 'text', 'Your email:', '', 'contact-email form-control', 0),
(3, 'message', 17, 'textarea', 'Your message:', '', 'message-text form-control', 0),
(4, 'email', 19, 'email', 'Email:', '', 'login-email form-control', 0),
(5, 'password', 20, 'password', 'Password:', '', 'login-password form-control', 0),
(6, 'Author', 22, 'SearchableCheckboxes', 'Filter by Author', '', 'SearchableCheckboxes fw-bold mb-1 border border-3 filter_author', 1),
(7, 'Tag', 23, 'SearchableCheckboxes', 'Filter by Tag', '', 'SearchableCheckboxes fw-bold mb-1 border border-3 filter_tags', 1),
(8, 'sortby', 24, 'select', 'Sort by', '', 'sort-by form-select', 0),
(9, 'title', 26, 'text', 'Article title:', '', 'article-title form-control', 0),
(10, 'bodytext', 27, 'textarea', 'Body Text', '', 'article-text form-control', 1),
(11, 'codeblock', 28, 'textarea', 'Codeblock', '', 'article-codeblock form-control', 1),
(12, 'articleimg', 29, 'file', 'Upload File', '', 'article-img-file form-control', 0),
(13, 'articletags', 30, 'SearchableCheckboxes', 'Article tags:', '', 'SearchableCheckboxes fw-bold mb-1 border border-3 filter_tags', 0),
(14, 'name', 32, 'text', 'Your name:', '', 'register-name form-control', 0),
(15, 'email', 33, 'email', 'Your email:', '', 'register-email form-control', 0),
(16, 'newpassword', 34, 'new_password', 'Verify password:', '', 'register-verifypassword form-control', 0),
(17, 'name', 36, 'text', 'Change username:', '', 'editUser-userName form-control', 1),
(18, 'email', 37, 'email', 'Edit email:', '', 'editUser-email form-control', 1),
(19, 'password', 39, 'password', 'Old password:', '', 'editPassword-pw1 form-control', 0),
(20, 'newpassword', 40, 'new_password', 'New password:', '', 'editPassword-pw2 form-control', 0),
(21, 'description', 41, 'textarea', 'About me:', '', 'about-text form-control', 1),
(22, 'aboutimg', 42, 'file', 'Upload file:', '', 'about-img-file form-control', 1);

-- --------------------------------------------------------

--
-- Table structure for table `form_info`
--

CREATE TABLE `form_info` (
  `id` int(11) NOT NULL,
  `element_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `method` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `submit_caption` varchar(255) NOT NULL,
  `enctype` varchar(255) NOT NULL,
  `submit_class` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `form_info`
--

INSERT INTO `form_info` (`id`, `element_id`, `action`, `method`, `label`, `submit_caption`, `enctype`, `submit_class`) VALUES
(1, 14, '', 'POST', '', 'Send message', '', 'btn btn-primary btn-sm'),
(2, 18, '', 'POST', '', 'Log in', '', 'btn btn-primary btn-sm'),
(3, 21, '', 'POST', '', 'Filter', '', 'btn btn-primary btn-sm'),
(4, 25, '', 'POST', '', 'SaveArticle', 'multipart/form-data', 'btn btn-primary btn-sm'),
(5, 31, '', 'POST', '', 'Register', '', 'btn btn-primary btn-sm'),
(6, 43, '', 'GET', '', 'Create new article', '', 'btn btn-primary btn-sm'),
(7, 35, '', 'POST', '', 'Change information', '', 'btn btn-primary'),
(8, 38, '', 'POST', '', 'save', '', 'btn btn-primary');

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
-- Table structure for table `page`
--

CREATE TABLE `page` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `page`
--

INSERT INTO `page` (`id`, `name`) VALUES
(1, 'home'),
(2, 'about'),
(3, 'contact'),
(4, 'login'),
(5, 'register'),
(6, 'search');

-- --------------------------------------------------------

--
-- Table structure for table `page_elements`
--

CREATE TABLE `page_elements` (
  `page_id` int(11) NOT NULL,
  `element_id` int(11) NOT NULL,
  `order_by` int(11) NOT NULL,
  `parent_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `page_elements`
--

INSERT INTO `page_elements` (`page_id`, `element_id`, `order_by`, `parent_order`) VALUES
(1, 1, 10, 0),
(1, 2, 20, 10),
(1, 3, 30, 10),
(1, 4, 40, 30),
(1, 5, 50, 40),
(1, 6, 60, 30),
(1, 7, 70, 60),
(1, 8, 80, 70),
(1, 5, 90, 80),
(1, 5, 100, 80),
(2, 12, 10, 0),
(2, 13, 20, 10),
(2, 9, 30, 20),
(2, 10, 40, 20),
(2, 11, 50, 10),
(3, 12, 10, 0),
(3, 13, 20, 10),
(3, 14, 30, 20),
(4, 1, 10, 0),
(4, 13, 20, 10),
(4, 18, 30, 20),
(5, 1, 10, 0),
(5, 13, 20, 10),
(5, 31, 30, 20),
(6, 44, 30, 0),
(6, 45, 40, 30),
(6, 46, 50, 40),
(6, 21, 60, 50),
(6, 46, 70, 30),
(6, 47, 80, 70),
(6, 50, 90, 70);

-- --------------------------------------------------------

--
-- Table structure for table `styling_containers`
--

CREATE TABLE `styling_containers` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `styling` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `styling_containers`
--

INSERT INTO `styling_containers` (`id`, `name`, `styling`) VALUES
(1, 'main_div', '<div class=\"d-flex flex-column align-items-center w-75 mx-auto\">'),
(2, 'top_div', '<div class=\"flex-grow-1\">'),
(4, 'main_div_2', '<div class=\"d-flex align-items-center w-75 mx-auto\">'),
(5, 'sub_div', '<div class=\"flex-grow-1\">'),
(6, 'container_div', '<div class=\"container-fluid\">'),
(7, 'row_div', '<div class=\"row\">'),
(8, 'filter_div', '<div class=\"col-12 col-md-3 border-end pe-4\">'),
(9, 'result_div', '<div class=\"col-12 col-md-9 ps-4\">'),
(10, 'table_div', '<div class=\"table-responsive\">'),
(11, 'table_class', 'table table-search table-hover table-striped table-bordered'),
(12, 'main_div', '<div class=\"align-items-center w-75 mx-auto\">'),
(13, 'sub_div', '<div class=\"d-flex flex-grow-1\">'),
(14, 'horizontal_rule', '<hr class=\"w-75 mx-auto my-4\">'),
(15, 'bot_div', '<div class=\"align-items-center w-75 mx-auto mt-4\">'),
(16, 'user_div', '<div class=\"d-flex justify-content-center align-items-center align-items-end gap-3\">'),
(17, 'tag_div', '<div class=\"d-flex flex-wrap gap-2 mb-3 border-top border-bottom py-2\">'),
(18, 'add_tag_div', '<div id=\"add-tag-widget\" class=\"d-flex gap-2 mt-2 mb-2\">');

-- --------------------------------------------------------

--
-- Table structure for table `styling_elements`
--

CREATE TABLE `styling_elements` (
  `id` int(11) NOT NULL,
  `website_info_id` int(11) NOT NULL,
  `class_name` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `styling_elements`
--

INSERT INTO `styling_elements` (`id`, `website_info_id`, `class_name`, `class`) VALUES
(1, 1, 'bodytext_class', 'text-center'),
(2, 2, 'description_class', 'fs-5 text-center'),
(3, 2, 'name_class', 'display-1 text-center border-bottom'),
(4, 9, 'title_class', 'text-center'),
(5, 9, 'author_class', 'text-center'),
(6, 9, 'body_class', 'container fs-6 text-start'),
(7, 9, 'codeblock_class', 'container-lg fs-6 col-15'),
(8, 9, 'img_class', 'rounded article-pic mx-auto d-flex ms-3'),
(9, 2, 'img_class', 'rounded-circle profile-pic d-flex justify-content-end mb-3'),
(11, 8, 'img_class', 'p-2 dashboard-pic rounded mb-1'),
(12, 8, 'user_title', 'p-2 fs-1 fw-bold align-middle'),
(13, 8, 'new_article_title', 'd-flex justify-content-center h4 border-top'),
(14, 8, 'articles_class', 'fs-2'),
(15, 9, 'article_script', '<script src=\"./src/js/articlePage.js\"></script>'),
(16, 9, 'button_class', 'button button-sm'),
(17, 9, 'description_class', 'h4 mb-4'),
(18, 7, 'tag_input_class', '<input type=\"text\" id=\"new-tag-name\"\r\n                                                    class=\"form-control form-control-sm\" placeholder=\"New tag\">'),
(19, 7, 'tag_button_class', '<button type=\"button\" id=\"add-tag-btn\" \r\n                                                    class=\"btn btn-sm btn-secondary\">Add tag</button>'),
(20, 8, 'email_class', 'fs-6');

-- --------------------------------------------------------

--
-- Table structure for table `styling_system`
--

CREATE TABLE `styling_system` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `styling` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `styling_system`
--

INSERT INTO `styling_system` (`id`, `name`, `styling`) VALUES
(1, 'header', 'fs-1 fw-bold text-center p-3 bg-primary-subtle bg-opacity-10 border border-info'),
(2, 'menu_items', 'nav bg-body-secondary border-bottom justify-content-around'),
(3, 'footer', 'border-top text-end flex-end bg-primary-subtle mt-auto pe-5');

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
  `column_headers` varchar(255) NOT NULL DEFAULT '0',
  `display_order` int(11) NOT NULL,
  `href` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `table_columns`
--

INSERT INTO `table_columns` (`id`, `column_name`, `column_title`, `display_type`, `class_types`, `column_headers`, `display_order`, `href`) VALUES
(1, 'id', 'Actions', 'first_cell', 'first_cell', 'first_cellTableHead', 0, ''),
(2, 'title', 'Title', 'Title', 'articletitle', 'articletitleTableHead', 10, 'main.php?page=article&id='),
(3, 'lastEdit', 'Last edited', 'date', 'lastEdit', 'lastEditTableHead', 40, ''),
(5, 'rating', 'Average Rating', 'rating', 'rating', 'ratingTableHead', 30, ''),
(6, 'tags', 'Tags', 'string', 'tags_class', 'tagsTableHead', 20, ''),
(7, 'Author', 'Author', 'string', 'Author_class', 'AuthorTableHead', 15, '');

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
(1, 'Danny12', '$2y$10$0UIJllrDci5.4ibTGhUp3OlP0oOGcmqlF5DowGJ0Mce0Zc./RKPrm', 'danny@email.com', 'author_1.png', 'Hoi ik ben Marius, een van de makers van deze website.'),
(7, 'Christian', '$2y$10$DdCUW.k/k8cMZd3CKEP/IO5v/itkF1gekox1Jamu48tOroQ1PjMiW', 'christian@email.com', '', ''),
(8, 'test', '$2y$10$ZKo8N0xwhh9ln1QV8OtsGuCXeAzfhon7mNM0W5FqAlUA0qsDKCOtK', 'email@email.com', 'author_8_082026.png', 'According to all known laws of aviation, there is no way that a bee should be able to fly. Its wings are too small to get its fat little body off the ground. The bee, of course, flies anyway because bees don&#039;t care what humans think is impossible.'),
(11, 'maruis', '$2y$10$M2A9UyZxjKNLJ2YSVeUA2.E21G6yuexpBqQgPdpBng3kGvzsZBog6', 'marius@email.com', 'author_11.png', '');

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_article_avg_rating`
-- (See below for the actual view)
--
CREATE TABLE `v_article_avg_rating` (
`id` int(11)
,`AVGrating` decimal(8,4)
,`Nratings` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_sortby_options`
-- (See below for the actual view)
--
CREATE TABLE `v_sortby_options` (
`id` int(1)
,`name` varchar(8)
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
(1, 'home', 'Welkom op onze hoofdpagina.'),
(2, 'about', 'This is the bodytext for about from the database'),
(3, 'contact', ''),
(4, 'login', ''),
(5, 'register', ''),
(6, 'search', ''),
(7, 'editArticle', ''),
(8, 'dashboard', ''),
(9, 'article', ''),
(10, 'editUser', ''),
(11, 'editPassword', '');

-- --------------------------------------------------------

--
-- Table structure for table `website_info_to_styling_containers`
--

CREATE TABLE `website_info_to_styling_containers` (
  `website_info_id` int(11) NOT NULL,
  `styling_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `website_info_to_styling_containers`
--

INSERT INTO `website_info_to_styling_containers` (`website_info_id`, `styling_id`) VALUES
(1, 1),
(2, 1),
(2, 2),
(2, 4),
(2, 5),
(3, 1),
(3, 5),
(4, 1),
(4, 5),
(5, 1),
(5, 5),
(6, 6),
(6, 7),
(6, 8),
(6, 9),
(6, 10),
(6, 11),
(7, 1),
(7, 5),
(7, 18),
(8, 6),
(8, 7),
(8, 8),
(8, 9),
(8, 11),
(8, 16),
(9, 12),
(9, 13),
(9, 14),
(9, 15),
(9, 17),
(10, 1),
(10, 5),
(11, 1),
(11, 5);

-- --------------------------------------------------------

--
-- Table structure for table `wiki_article`
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
-- Dumping data for table `wiki_article`
--

INSERT INTO `wiki_article` (`id`, `title`, `user_id`, `summary`, `codeBlock`, `imgFileName`, `lastEdit`) VALUES
(1, 'http build query', 1, 'Met deze functie kun je een HTTPS url samenstellen aan de hand van parameters.', 'public static function buildUrl(array $params = []): string\n    {\n        return \'?\' . http_build_query($params);\n    }', 'article1.jpeg', '2026-08-11'),
(28, 'PHP', 1, 'PHP is een scripttaal en is vergelijkbaar met Perl, Python en Ruby. Qua syntaxis lijkt PHP het meest op C, maar net als bij veel andere scripttalen moeten variabelen voorafgegaan worden door een dollarteken $. Dit is overgenomen uit de scripttaal Perl, waarvan PHP mede is afgeleid. In tegenstelling tot C is het in PHP wel mogelijk om naast procedureel programmeren ook objectgeoriënteerd te programmeren, net als in bijvoorbeeld Java, C++ en C#. In de eerste versies van PHP was het objectgeoriënteerd programmeren nog heel beperkt. Pas sinds versie 5 zijn de meest essentiële functies hiervoor allemaal beschikbaar.', '$url = &quot;http://nl.wikipedia.org/wiki/PHP&quot;;\r\n\r\necho &quot;U bevindt zich momenteel op $url. Welkom!&quot;;\r\n// Of\r\necho &quot;U bevindt zich momenteel op &quot;.$url.&quot;. Welkom!&quot;;', 'article_0.jpg', '2026-09-08'),
(29, 'New article', 1, 'This is the body text.', '', 'article_0.jpg', '2026-09-08'),
(31, 'test', 1, 'testd', '', '', '2026-09-11');

-- --------------------------------------------------------

--
-- Table structure for table `wiki_article_to_tag`
--

CREATE TABLE `wiki_article_to_tag` (
  `article_id` int(11) NOT NULL,
  `wiki_tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `wiki_article_to_tag`
--

INSERT INTO `wiki_article_to_tag` (`article_id`, `wiki_tag_id`) VALUES
(1, 1),
(28, 53),
(28, 54),
(29, 54),
(31, 53),
(31, 54),
(31, 55);

-- --------------------------------------------------------

--
-- Table structure for table `wiki_rating`
--

CREATE TABLE `wiki_rating` (
  `user_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `rating` tinyint(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wiki_rating`
--

INSERT INTO `wiki_rating` (`user_id`, `article_id`, `rating`) VALUES
(1, 1, 4),
(7, 1, 1),
(7, 28, 3),
(7, 29, 4),
(8, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `wiki_sortby_info`
--

CREATE TABLE `wiki_sortby_info` (
  `id` int(11) NOT NULL,
  `sortby_name` varchar(30) NOT NULL,
  `sortby_value` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `wiki_sortby_info`
--

INSERT INTO `wiki_sortby_info` (`id`, `sortby_name`, `sortby_value`) VALUES
(1, 'Rating', 'rating'),
(2, 'Date', 'lastEdit');

-- --------------------------------------------------------

--
-- Table structure for table `wiki_tag`
--

CREATE TABLE `wiki_tag` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `wiki_tag`
--

INSERT INTO `wiki_tag` (`id`, `name`) VALUES
(54, 'code'),
(53, 'php'),
(1, 'tag1'),
(55, 'test2');

-- --------------------------------------------------------

--
-- Structure for view `v_article_avg_rating`
--
DROP TABLE IF EXISTS `v_article_avg_rating`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_article_avg_rating`  AS SELECT `a`.`id` AS `id`, avg(`r`.`rating`) AS `AVGrating`, count(`r`.`rating`) AS `Nratings` FROM (`wiki_article` `a` left join `wiki_rating` `r` on(`a`.`id` = `r`.`article_id`)) GROUP BY `a`.`id` ;

-- --------------------------------------------------------

--
-- Structure for view `v_sortby_options`
--
DROP TABLE IF EXISTS `v_sortby_options`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_sortby_options`  AS SELECT 1 AS `id`, 'lastEdit' AS `name`union select 2 AS `2`,'rating' AS `rating`  ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `element_info`
--
ALTER TABLE `element_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `element_lookup_info`
--
ALTER TABLE `element_lookup_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_element_info_to_look_up` (`element_id`);

--
-- Indexes for table `field_info`
--
ALTER TABLE `field_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_element_info_to_field_info` (`element_id`);

--
-- Indexes for table `form_info`
--
ALTER TABLE `form_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_element_info_to_form_info` (`element_id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page`
--
ALTER TABLE `page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page_elements`
--
ALTER TABLE `page_elements`
  ADD PRIMARY KEY (`page_id`,`order_by`),
  ADD KEY `fk_element_info_to_page_elements` (`element_id`);

--
-- Indexes for table `styling_containers`
--
ALTER TABLE `styling_containers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `styling_elements`
--
ALTER TABLE `styling_elements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_website_info_dplay_classes` (`website_info_id`);

--
-- Indexes for table `styling_system`
--
ALTER TABLE `styling_system`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `table_columns`
--
ALTER TABLE `table_columns`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `column_key_unique` (`column_name`);

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
-- Indexes for table `website_info_to_styling_containers`
--
ALTER TABLE `website_info_to_styling_containers`
  ADD PRIMARY KEY (`website_info_id`,`styling_id`),
  ADD KEY `styling_id` (`styling_id`);

--
-- Indexes for table `wiki_article`
--
ALTER TABLE `wiki_article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_article_to_user_id` (`user_id`);

--
-- Indexes for table `wiki_article_to_tag`
--
ALTER TABLE `wiki_article_to_tag`
  ADD PRIMARY KEY (`article_id`,`wiki_tag_id`),
  ADD KEY `fk_article_to_tag_tag_id` (`wiki_tag_id`);

--
-- Indexes for table `wiki_rating`
--
ALTER TABLE `wiki_rating`
  ADD PRIMARY KEY (`user_id`,`article_id`),
  ADD KEY `fk_rating_to_article_id` (`article_id`);

--
-- Indexes for table `wiki_sortby_info`
--
ALTER TABLE `wiki_sortby_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wiki_tag`
--
ALTER TABLE `wiki_tag`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `element_info`
--
ALTER TABLE `element_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `element_lookup_info`
--
ALTER TABLE `element_lookup_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `field_info`
--
ALTER TABLE `field_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `form_info`
--
ALTER TABLE `form_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `page`
--
ALTER TABLE `page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `styling_containers`
--
ALTER TABLE `styling_containers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `styling_elements`
--
ALTER TABLE `styling_elements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `styling_system`
--
ALTER TABLE `styling_system`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `table_columns`
--
ALTER TABLE `table_columns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `website_info`
--
ALTER TABLE `website_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `wiki_article`
--
ALTER TABLE `wiki_article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `wiki_sortby_info`
--
ALTER TABLE `wiki_sortby_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wiki_tag`
--
ALTER TABLE `wiki_tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `element_lookup_info`
--
ALTER TABLE `element_lookup_info`
  ADD CONSTRAINT `fk_element_info_to_look_up` FOREIGN KEY (`element_id`) REFERENCES `element_info` (`id`);

--
-- Constraints for table `field_info`
--
ALTER TABLE `field_info`
  ADD CONSTRAINT `fk_element_info_to_field_info` FOREIGN KEY (`element_id`) REFERENCES `element_info` (`id`);

--
-- Constraints for table `form_info`
--
ALTER TABLE `form_info`
  ADD CONSTRAINT `fk_element_info_to_form_info` FOREIGN KEY (`element_id`) REFERENCES `element_info` (`id`);

--
-- Constraints for table `page_elements`
--
ALTER TABLE `page_elements`
  ADD CONSTRAINT `fk_element_info_to_page_elements` FOREIGN KEY (`element_id`) REFERENCES `element_info` (`id`),
  ADD CONSTRAINT `fk_page_to_page_elements` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`);

--
-- Constraints for table `styling_elements`
--
ALTER TABLE `styling_elements`
  ADD CONSTRAINT `fk_website_info_dplay_classes` FOREIGN KEY (`website_info_id`) REFERENCES `website_info` (`id`);

--
-- Constraints for table `website_info_to_styling_containers`
--
ALTER TABLE `website_info_to_styling_containers`
  ADD CONSTRAINT `website_info_to_styling_containers_ibfk_1` FOREIGN KEY (`website_info_id`) REFERENCES `website_info` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `website_info_to_styling_containers_ibfk_2` FOREIGN KEY (`styling_id`) REFERENCES `styling_containers` (`id`);

--
-- Constraints for table `wiki_article`
--
ALTER TABLE `wiki_article`
  ADD CONSTRAINT `fk_article_to_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `wiki_article_to_tag`
--
ALTER TABLE `wiki_article_to_tag`
  ADD CONSTRAINT `fk_article_to_tag_article_id` FOREIGN KEY (`article_id`) REFERENCES `wiki_article` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_article_to_tag_tag_id` FOREIGN KEY (`wiki_tag_id`) REFERENCES `wiki_tag` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `wiki_rating`
--
ALTER TABLE `wiki_rating`
  ADD CONSTRAINT `fk_rating_to_article_id` FOREIGN KEY (`article_id`) REFERENCES `wiki_article` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rating_to_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
