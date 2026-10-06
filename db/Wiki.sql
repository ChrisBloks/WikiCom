-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 06, 2026 at 11:36 AM
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
-- Table structure for table `application_data`
--

CREATE TABLE `application_data` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `element_info_key` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_data`
--

INSERT INTO `application_data` (`id`, `name`, `element_info_key`) VALUES
(1, '[*RANDOM_ARTICLE*]', 'article_info'),
(2, '[*AUTHOR_NAME*]', 'title'),
(3, '[*ARTICLE_INFO*]', 'article_info'),
(4, '[*ARTICLE_TITLE*]', 'text'),
(5, '[*ARTICLE_AUTHOR*]', 'text'),
(6, '[*ARTICLE_TEXT*]', 'text'),
(7, '[*ARTICLE_CODE*]', 'text'),
(8, '[*ARTICLE_ID*]', 'options_info,article_id'),
(9, '[*USER_ID*]', 'options_info,user_id'),
(10, '[*ARTICLE_IMG*]', 'image'),
(11, '[*ARTICLE_RATING*]', 'options_info,rating'),
(12, '[*AUTHOR_TEXT*]', 'text'),
(13, '[*AUTHOR_IMAGE*]', 'image'),
(14, '[*USER_EMAIL*]', 'text'),
(15, '[*USER_IMG*]', 'image'),
(16, '[*USER_NAME*]', 'text'),
(17, '[*ARTICLE_TAGS*]', 'article_info,tags'),
(18, '[*USER_ARTICLES*]', 'options_info,articles'),
(19, '[*DIALOGUE_ATTRIBUTES*]', 'attributes'),
(20, '[*ADD_USER_ID_TO_HTML_ID*]', 'html_id'),
(21, '[*ARTICLE_N_RATINGS*]', 'options_info,n_ratings'),
(22, '[*IS_LOGGED_IN*]', 'options_info,is_logged_in'),
(23, '[*ARTICLE_AUTHOR_ID*]', 'options_info,author_id');

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
-- Table structure for table `dialogue_window`
--

CREATE TABLE `dialogue_window` (
  `id` int(11) NOT NULL,
  `data-bs-toggle` varchar(255) NOT NULL,
  `data-bs-target` varchar(255) NOT NULL,
  `element_info_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dialogue_window`
--

INSERT INTO `dialogue_window` (`id`, `data-bs-toggle`, `data-bs-target`, `element_info_id`) VALUES
(1, 'modal', '#editUserModal', 65),
(2, 'modal', '#editPasswordModal', 66);

-- --------------------------------------------------------

--
-- Table structure for table `element_info`
--

CREATE TABLE `element_info` (
  `id` int(11) NOT NULL,
  `element_name` varchar(255) NOT NULL,
  `html_tag` text NOT NULL,
  `html_class` varchar(255) NOT NULL,
  `html_id` varchar(255) NOT NULL,
  `php_class` varchar(255) NOT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `element_info`
--

INSERT INTO `element_info` (`id`, `element_name`, `html_tag`, `html_class`, `html_id`, `php_class`, `text`) VALUES
(1, 'main', 'div', 'd-flex flex-column align-items-center w-75 mx-auto', '', 'ContainerElement', ''),
(2, 'home_text', 'h1', 'display-1', '', 'AtomicElement', 'Welcome to our website'),
(3, 'row_container', 'div', 'row g-4 mb-5', '', 'ContainerElement', ''),
(4, 'featured_col', 'div', 'col-md-8', '', 'ContainerElement', ''),
(5, 'random_article', '', '', '', 'Card', ''),
(6, 'small_coll', 'div', 'col-md-4', '', 'ContainerElement', ''),
(7, 'small_row', 'div', 'row g-4', '', 'ContainerElement', ''),
(8, 'small_wrapper', 'div', 'col-12', '', 'ContainerElement', ''),
(9, 'about_title', 'h1', 'display-1 text-center border-bottom', '', 'Title', ''),
(10, 'about_description', 'div', 'fs-5 text-center', '', 'BodyText', ''),
(11, 'about_img', '', 'rounded-circle profile-pic d-flex justify-content-end mb-3', '', 'Image', ''),
(12, 'main_2', 'div', 'd-flex align-items-center w-75 mx-auto', '', 'ContainerElement', ''),
(13, 'sub', 'div', 'flex-grow-1', '', 'ContainerElement', ''),
(14, 'contact_form', 'form', 'form-group', '', 'Form', ''),
(15, 'contact_name_field', '', 'contact-name form-control', '', 'Input', ''),
(16, 'contact_field_email', '', 'contact-email form-control', '', 'Input', ''),
(17, 'contact_field_message', '', 'message-text form-control', '', 'textarea', ''),
(18, 'log_in_form', 'form', 'form-group', '', 'Form', ''),
(19, 'login_in_field_email', '', 'login-email form-control', '', 'Input', ''),
(20, 'log_in_field_password', '', 'login-password form-control', '', 'Input', ''),
(21, 'search_form', 'form', 'form-group', 'search_form', 'Form', ''),
(22, 'search_field_author', '', 'SearchableCheckboxes fw-bold mb-1 border border-3 filter_author', '', 'SearchableCheckboxes', ''),
(23, 'search_field_tag', '', 'SearchableCheckboxes fw-bold mb-1 border border-3 filter_tags', '', 'SearchableCheckboxes', ''),
(24, 'search_field_sortby\r\n', '', 'sort-by form-select', '', 'select', ''),
(25, 'article_form', 'form', 'form-group', '', 'EditableArticle', ''),
(26, 'article_field_title', '', 'article-title form-control', '', 'Input', ''),
(27, 'article_field_bodytext', '', 'article-text form-control', '', 'textarea', ''),
(28, 'article_field_codeblock', '', 'article-codeblock form-control', '', 'textarea', ''),
(29, 'article_field_img', '', 'article-img-file form-control', '', 'Input', ''),
(30, 'article_field_tags', '', 'SearchableCheckboxes fw-bold mb-1 border border-3 filter_tags', '', 'SearchableCheckboxes', ''),
(31, 'register_form', 'form', 'form-group', '', 'Form', ''),
(32, 'register_field_name', '', 'register-name form-control', '', 'Input', ''),
(33, 'register_field_email', '', 'register-email form-control', '', 'Input', ''),
(34, 'register_field_new_password', '', 'register-verifypassword form-control', '', 'new_password', ''),
(35, 'edit_user_form', 'Form', 'form-control mt-5', '', 'Form', ''),
(36, 'edit_user_field_name', '', 'editUser-userName form-control', '', 'Input', ''),
(37, 'edit_user_field_email', '', 'editUser-email form-control', '', 'Input', ''),
(38, 'edit_password_form', 'Form', 'form-control mt-5', '', 'Form', ''),
(39, 'edit_password_field_old', '', 'editPassword-pw1 form-control', '', 'Input', ''),
(40, 'edit_password_field_new', '', 'editPassword-pw2 form-control', '', 'new_password', ''),
(41, 'edit_user_field_description', '', 'about-text form-control', '', 'textarea', ''),
(42, 'edit_user_field_img', '', 'about-img-file form-control', '', 'Input', ''),
(43, 'create_new_article', 'form', '', '', 'Form', ''),
(44, 'search_container', 'div', 'container-fluid', '', 'ContainerElement', ''),
(45, 'search_row', 'div', 'row', '', 'ContainerElement', ''),
(46, 'search_col', 'div', 'col-12 col-md-3 border-end pe-4', '', 'ContainerElement', ''),
(47, 'search_table_container', 'div', 'table-responsive', '', 'ContainerElement', ''),
(50, 'search_table', 'table', 'table table-search table-hover table-striped table-bordered', 'search_table', 'ResultsTable', ''),
(51, 'log_in_hidden_page', '', '', '', 'Input', ''),
(52, 'search_col_results', 'div', 'col-12 col-md-9 ps-4', '', 'ContainerElement', ''),
(53, 'container_fluid', 'div', 'container-fluid', '', 'ContainerElement', ''),
(54, 'row_div', 'div', 'row', '', 'ContainerElement', ''),
(55, 'filter_div', 'div', 'col-12 col-md-3 border-end pe-4', '', 'ContainerElement', ''),
(56, 'result_div', 'div', 'col-12 col-md-9 ps-4', '', 'ContainerElement', ''),
(57, 'user_div', 'div', 'd-flex justify-content-center align-items-center align-items-end gap-3', '', 'ContainerElement', ''),
(58, 'dashboard_image', '', 'p-2 dashboard-pic rounded mb-1 dashboard-pic', '', 'Image', ''),
(59, 'dashboard_title', 'h1', 'd-flex justify-content-center fs-3 userNameDisplay', '', 'Title', ''),
(60, 'dashboard_email', 'h1', 'd-flex justify-content-center fs-6 userEmailDisplay', '', 'Title', ''),
(61, 'new_article_title', 'h1', 'd-flex justify-content-center h4 border-top', '', 'Title', 'Create new article'),
(62, 'hidden_page_dashboard', '', '', '', 'HiddenField', ''),
(63, 'hidden_article_id_dashboard', '', '', '', 'HiddenField', ''),
(64, 'edit_user_information', 'h1', 'd-flex justify-content-center h4 border-top', '', 'Title', 'Edit user information'),
(65, 'edit_user_info_button_dashboard', 'button', 'btn btn-secondary mx-auto d-block', '-edit-user-btn', 'DialogueButton', 'Edit User information'),
(66, 'edit_user_password_button_dashboard', 'button', 'btn btn-danger mx-auto d-block', '-edit-pwd-btn', 'DialogueButton', 'Change Password'),
(67, 'edit_user_modal', '', '', 'editUserModal', 'Modal', 'Edit User Information'),
(68, 'edit_password_modal', '', '', 'editPasswordModal', 'Modal', 'Change Password'),
(69, 'edit_user_modal_errors', 'div', 'alert alert-danger d-none', 'editUserModal-errors', 'AtomicElement', ''),
(70, 'edit_password_modal_errors', 'div', 'alert alert-danger d-none', 'editPasswordModal-errors', 'AtomicElement', ''),
(72, 'hidden_article_ID', '', '', '', 'HiddenField', ''),
(75, 'table_article_title_dashboard', 'h1', 'fs-2', '', 'Title', 'Articles'),
(76, 'table_dashboard', 'table', 'table table-search table-hover table-striped table-bordered', '', 'DashboardTable', ''),
(77, 'toast_div', 'div', 'position-relative', '', 'ContainerElement', ''),
(78, 'toast_container', 'div', 'toast-container top-0 end-0 p-3', 'toast-container', 'Toast', ''),
(79, 'article_body_div', 'div', 'align-items-center w-75 mx-auto', '', 'ContainerElement', ''),
(80, 'article_title', 'h1', 'text-center', '', 'AtomicElement', '$articleTitle'),
(81, 'article_author_name', 'h3', 'text-center', '', 'AtomicElement', '$authorName'),
(82, 'rating_div', 'div', 'rating_div', '', 'Rating', ''),
(83, 'tag_button_container', 'div', 'd-flex flex-wrap gap-2 mb-3 border-top border-bottom py-2\r\n', '', 'TagButtonContainer', ''),
(86, 'article_text_img_div', 'div', 'd-flex flex-grow-1', '', 'ContainerElement', ''),
(87, 'article_body_text', 'div', 'container fs-6 text-start', '', 'ContainerElement', '$article_body_text'),
(88, 'article_body_img', 'img', 'rounded article-pic mx-auto d-flex ms-3', '', 'Image', ''),
(89, 'horizontal_line', 'hr', 'w-100 mx-auto mt-4', '', 'AtomicElement', ''),
(90, 'codeblock_div', 'div', 'align-items-center w-100 mx-auto mt-4', '', 'ContainerElement', ''),
(91, 'code_block_header', 'h1', 'h4', '', 'AtomicElement', 'Code'),
(92, 'article_code_block', 'code', 'container-lg fs-6 col-15 hljs language-php', '', 'CodeBlock', '$article_code'),
(100, 'hidden_page_edit_user', '', '', '', 'HiddenField', ''),
(101, 'hidden_action_edit_user', '', '', '', 'HiddenField', ''),
(102, 'hidden_id_edit_user', '', '', '', 'HiddenField', ''),
(103, 'hidden_page_edit_password', '', '', '', 'HiddenField', ''),
(104, 'hidden_action_edit_password', '', '', '', 'HiddenField', ''),
(105, 'hidden_id_edit_password', '', '', '', 'HiddenField', ''),
(106, 'hidden_action_search_article', '', '', '', 'HiddenField', '');

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
  `has_join` tinyint(1) NOT NULL,
  `lookup_type` varchar(255) NOT NULL,
  `element_order` int(3) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `element_lookup_info`
--

INSERT INTO `element_lookup_info` (`id`, `element_id`, `source_table`, `column_names`, `where_`, `where_value`, `has_join`, `lookup_type`, `element_order`) VALUES
(1, 14, 'form_info', 'action,method,label,submit_caption,enctype,submit_class', 'form_info.element_id', '14', 0, 'form', 0),
(2, 14, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '15', 0, 'element', 0),
(3, 15, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '15', 0, 'field', 0),
(4, 16, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '16', 0, 'field', 0),
(5, 14, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '16', 0, 'element', 0),
(6, 18, 'form_info', 'action,method,label,submit_caption,enctype,submit_class', 'form_info.element_id', '18', 0, 'form', 0),
(7, 19, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '19', 0, 'field', 0),
(8, 20, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '20', 0, 'field', 0),
(9, 18, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '19', 0, 'element', 0),
(11, 18, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '20', 0, 'element', 0),
(12, 21, 'form_info', 'action,method,label,submit_caption,enctype,submit_class', 'form_info.element_id', '21', 0, 'form', 0),
(13, 22, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '22', 0, 'field', 0),
(14, 23, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '23', 0, 'field', 0),
(15, 24, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '24', 0, 'field', 0),
(16, 21, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '22', 0, 'element', 0),
(17, 21, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '23', 0, 'element', 0),
(18, 21, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '24', 0, 'element', 0),
(19, 25, 'form_info', 'action,method,label,submit_caption,enctype,submit_class', 'form_info.element_id', '25', 0, 'form', 0),
(20, 26, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '26', 0, 'field', 0),
(21, 27, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '27', 0, 'field', 0),
(22, 28, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '28', 0, 'field', 0),
(23, 29, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '29', 0, 'field', 0),
(24, 30, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '30', 0, 'field', 0),
(25, 25, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '26', 0, 'element', 10),
(26, 25, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '27', 0, 'element', 30),
(27, 25, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '28', 0, 'element', 40),
(28, 25, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '29', 0, 'element', 50),
(29, 25, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '30', 0, 'element', 20),
(30, 31, 'form_info', 'action,method,label,submit_caption,enctype,submit_class', 'form_info.element_id', '31', 0, 'form', 0),
(31, 32, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '32', 0, 'field', 0),
(32, 33, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '33', 0, 'field', 0),
(33, 34, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '34', 0, 'field', 0),
(34, 31, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '32', 0, 'element', 0),
(35, 31, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '33', 0, 'element', 0),
(36, 31, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '34', 0, 'element', 0),
(37, 35, 'form_info', 'action,method,label,submit_caption,enctype,submit_class', 'form_info.element_id', '35', 0, 'form', 0),
(38, 36, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '36', 0, 'field', 0),
(39, 37, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '37', 0, 'field', 0),
(40, 41, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '41', 0, 'field', 0),
(41, 42, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '42', 0, 'field', 0),
(42, 35, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '36', 0, 'element', 0),
(43, 35, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '37', 0, 'element', 0),
(44, 35, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '41', 0, 'element', 0),
(45, 35, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '42', 0, 'element', 0),
(46, 38, 'form_info', 'action,method,label,submit_caption,enctype,submit_class', 'form_info.element_id', '38', 0, 'form', 0),
(47, 39, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '39', 0, 'field', 0),
(48, 40, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '40', 0, 'field', 0),
(49, 38, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '39', 0, 'element', 0),
(50, 38, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '40', 0, 'element', 0),
(51, 43, 'form_info', 'action,method,label,submit_caption,enctype,submit_class', 'form_info.element_id', '43', 0, 'form', 0),
(52, 14, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '17', 0, 'element', 0),
(53, 17, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '17', 0, 'field', 0),
(55, 23, 'wiki_tag', 'id,name', '', '', 0, 'options', 0),
(56, 22, 'user', 'id,name', '', '', 0, 'options', 0),
(57, 24, 'v_sortby_options', 'id,name', '', '', 0, 'options', 0),
(59, 50, 'table_columns', 'column_name,column_title,display_type,class_types,column_headers,href,display_order', 'column_name', '\'title\',\'author\',\'tags\',\'lastEdit\',\'rating\'', 0, 'options', 0),
(60, 18, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '51', 0, 'element', 0),
(61, 51, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '51', 0, 'field', 0),
(62, 43, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '62', 0, 'element', 0),
(63, 43, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '63', 0, 'element', 0),
(64, 62, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '62', 0, 'field', 0),
(65, 63, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '63', 0, 'field', 0),
(66, 65, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '65', 0, 'field', 0),
(68, 25, 'element_info', 'id as element_id,element_name,php_class,html_class,html_tag', 'element_info.id', '72', 0, 'element', 15),
(69, 72, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '72', 0, 'field', 0),
(70, 30, 'wiki_tag', 'id,name', '', '', 0, 'options', 0),
(71, 76, 'table_columns', 'column_name,column_title,display_type,class_types,column_headers,href,display_order', 'column_name', '\'id\',\'title\',\'lastEdit\'', 0, 'options', 0),
(72, 77, 'aria_attributes', 'name,value', 'id', '1,2', 0, 'aria_attributes', 0),
(73, 77, 'element_info', 'id as element_id,element_name,php_class,html_class,html_tag', 'element_info.id', '78', 0, 'element', 10),
(74, 79, 'element_info', 'id as element_id,element_name,php_class,html_class,html_tag', 'element_info.id', '80', 0, 'element', 10),
(75, 79, 'element_info', 'id as element_id,element_name,php_class,html_class,html_tag', 'element_info.id', '81', 0, 'element', 20),
(77, 86, 'element_info', 'id as element_id,element_name,php_class,html_class,html_tag', 'element_info.id', '87', 0, 'element', 10),
(78, 86, 'element_info', 'id as element_id,element_name,php_class,html_class,html_tag', 'element_info.id', '88', 0, 'element', 20),
(79, 90, 'element_info', 'id as element_id,element_name,php_class,html_class,html_tag', 'element_info.id', '91', 0, 'element', 10),
(80, 90, 'element_info', 'id as element_id,element_name,php_class,html_class,html_tag', 'element_info.id', '92', 0, 'element', 20),
(90, 35, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '100', 0, 'element', 0),
(91, 35, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '101', 0, 'element', 0),
(92, 35, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '102', 0, 'element', 0),
(93, 100, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '100', 0, 'field', 0),
(94, 101, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '101', 0, 'field', 0),
(95, 102, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '102', 0, 'field', 0),
(96, 38, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '103', 0, 'element', 0),
(97, 38, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '104', 0, 'element', 0),
(98, 38, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '105', 0, 'element', 0),
(99, 103, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '103', 0, 'field', 0),
(100, 104, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '104', 0, 'field', 0),
(101, 105, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '105', 0, 'field', 0),
(102, 21, 'element_info', 'id as element_id,element_name,php_class,html_class', 'element_info.id', '106', 0, 'element', 0),
(103, 106, 'field_info', 'field_name,type,label,value,optional', 'field_info.element_id', '106', 0, 'field', 0);

-- --------------------------------------------------------

--
-- Table structure for table `element_to_application_data`
--

CREATE TABLE `element_to_application_data` (
  `id` int(11) NOT NULL,
  `element_id` int(11) NOT NULL,
  `application_data_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `element_to_application_data`
--

INSERT INTO `element_to_application_data` (`id`, `element_id`, `application_data_id`) VALUES
(1, 5, 1),
(2, 9, 2),
(3, 10, 12),
(4, 11, 13),
(5, 60, 14),
(6, 58, 15),
(7, 59, 16),
(8, 25, 3),
(9, 25, 17),
(10, 76, 18),
(11, 65, 19),
(12, 65, 20),
(13, 66, 19),
(14, 66, 20),
(15, 81, 5),
(16, 88, 10),
(17, 87, 6),
(18, 92, 7),
(19, 80, 4),
(20, 82, 11),
(21, 72, 8),
(22, 102, 9),
(23, 105, 9),
(24, 82, 21),
(25, 82, 22),
(26, 82, 9),
(27, 82, 23);

-- --------------------------------------------------------

--
-- Table structure for table `field_info`
--

CREATE TABLE `field_info` (
  `id` int(11) NOT NULL,
  `field_name` varchar(255) NOT NULL,
  `element_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `optional` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `field_info`
--

INSERT INTO `field_info` (`id`, `field_name`, `element_id`, `type`, `label`, `value`, `optional`) VALUES
(1, 'name', 15, 'text', 'Your name:', '', 0),
(2, 'email', 16, 'text', 'Your email:', '', 0),
(3, 'message', 17, 'textarea', 'Your message:', '', 0),
(4, 'email', 19, 'email', 'Email:', '', 0),
(5, 'password', 20, 'password', 'Password:', '', 0),
(6, 'Author', 22, 'SearchableCheckboxes', 'Filter by Author', '', 1),
(7, 'Tag', 23, 'SearchableCheckboxes', 'Filter by Tag', '', 1),
(8, 'sortby', 24, 'select', 'Sort by', '', 0),
(9, 'title', 26, 'text', 'Article title:', '', 0),
(10, 'bodytext', 27, 'textarea', 'Body Text', '', 1),
(11, 'codeblock', 28, 'textarea', 'Codeblock', '', 1),
(12, 'articleimg', 29, 'file', 'Upload File', '', 0),
(13, 'articletags', 30, 'SearchableCheckboxes', 'Article tags:', '', 0),
(14, 'name', 32, 'text', 'Your name:', '', 0),
(15, 'email', 33, 'email', 'Your email:', '', 0),
(16, 'newpassword', 34, 'new_password', 'Verify password:', '', 0),
(17, 'name', 36, 'text', 'Change username:', '', 1),
(18, 'email', 37, 'email', 'Edit email:', '', 1),
(19, 'password', 39, 'password', 'Old password:', '', 0),
(20, 'newpassword', 40, 'new_password', 'New password:', '', 0),
(21, 'description', 41, 'textarea', 'About me:', '', 1),
(22, 'aboutimg', 42, 'file', 'Upload file:', '', 1),
(23, 'page', 51, 'hidden', '', 'login', 0),
(24, 'page', 62, 'hidden', '', 'editArticle', 0),
(25, 'id', 63, 'hidden', '', '0', 0),
(26, 'Edit User Information', 65, 'Button', 'Change user information', '', 1),
(27, 'Edit Password', 66, 'Button', '', '', 1),
(28, 'articleID', 72, 'hidden', '', '$editArticleID', 0),
(29, 'page', 100, 'hidden', '', 'editUser', 0),
(30, 'action', 101, 'hidden', '', 'updateUserInfo', 0),
(31, 'id', 102, 'hidden', '', '#userID', 0),
(32, 'page', 103, 'hidden', '', 'editPassword', 0),
(33, 'action', 104, 'hidden', '', 'updatePassword', 0),
(34, 'id', 105, 'hidden', '', '#userID', 0),
(35, 'action', 104, 'hidden', '', 'searchArticle', 0);

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
(4, 25, '', 'POST', '', 'Save Article', 'multipart/form-data', 'btn btn-primary btn-sm'),
(5, 31, '', 'POST', '', 'Register', '', 'btn btn-primary btn-sm'),
(6, 43, '', 'GET', '', 'Create new article', '', 'btn btn-primary mx-auto d-block'),
(7, 35, '', 'POST', '', 'Change information', '', 'btn btn-primary'),
(8, 38, '', 'POST', '', 'save', '', 'btn btn-primary');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `label` varchar(255) NOT NULL,
  `page_value` varchar(255) NOT NULL,
  `display_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `label`, `page_value`, `display_order`) VALUES
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
(6, 'search'),
(7, 'editArticle'),
(8, 'dashboard'),
(9, 'article');

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
(1, 77, 9, 0),
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
(2, 77, 9, 0),
(2, 12, 10, 0),
(2, 13, 20, 10),
(2, 9, 30, 20),
(2, 10, 40, 20),
(2, 11, 50, 10),
(3, 77, 9, 0),
(3, 1, 10, 0),
(3, 13, 20, 10),
(3, 14, 30, 20),
(4, 77, 9, 0),
(4, 1, 10, 0),
(4, 13, 20, 10),
(4, 18, 30, 20),
(5, 77, 9, 0),
(5, 1, 10, 0),
(5, 13, 20, 10),
(5, 31, 30, 20),
(6, 77, 9, 0),
(6, 44, 30, 0),
(6, 45, 40, 30),
(6, 46, 50, 40),
(6, 21, 60, 50),
(6, 52, 70, 40),
(6, 47, 80, 70),
(6, 50, 90, 70),
(7, 77, 9, 0),
(7, 1, 10, 0),
(7, 13, 20, 10),
(7, 25, 30, 20),
(8, 77, 9, 0),
(8, 53, 10, 0),
(8, 54, 20, 10),
(8, 55, 30, 20),
(8, 56, 40, 20),
(8, 57, 50, 30),
(8, 58, 60, 50),
(8, 59, 70, 30),
(8, 60, 80, 30),
(8, 61, 90, 30),
(8, 43, 100, 30),
(8, 64, 110, 30),
(8, 65, 120, 30),
(8, 66, 130, 30),
(8, 67, 140, 30),
(8, 69, 150, 140),
(8, 35, 160, 140),
(8, 68, 170, 30),
(8, 70, 180, 170),
(8, 38, 190, 170),
(8, 75, 200, 40),
(8, 76, 210, 40),
(9, 77, 9, 0),
(9, 79, 20, 0),
(9, 82, 30, 20),
(9, 83, 40, 20),
(9, 86, 60, 20),
(9, 89, 70, 20),
(9, 90, 80, 20);

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
(1, 'Danny122', '$2y$10$VeY8X0yMrxhmG3A6dQt6vOTV.K8S3W0hrBuCB4R0uDpEis4ybQewy', 'danny@email.com', 'author_1.png', 'Hoi ik ben Marius, een van de makers van deze website.'),
(7, 'Christiannn', '$2y$10$DdCUW.k/k8cMZd3CKEP/IO5v/itkF1gekox1Jamu48tOroQ1PjMiW', 'christian@email.com', '', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'),
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
(28, 'PHP', 1, 'PHP is een scripttaal en is vergelijkbaar met Perl, Python en Ruby. Qua syntaxis lijkt PHP het meest op C, maar net als bij veel andere scripttalen moeten variabelen voorafgegaan worden door een dollarteken $. Dit is overgenomen uit de scripttaal Perl, waarvan PHP mede is afgeleid. In tegenstelling tot C is het in PHP wel mogelijk om naast procedureel programmeren ook objectgeoriënteerd te programmeren, net als in bijvoorbeeld Java, C++ en C#. In de eerste versies van PHP was het objectgeoriënteerd programmeren nog heel beperkt. Pas sinds versie 5 zijn de meest essentiële functies hiervoor allemaal beschikbaar.', '$url = &quot;http://nl.wikipedia.org/wiki/PHP&quot;;\r\n\r\necho &quot;U bevindt zich momenteel op $url. Welkom!&quot;;\r\n// Of\r\necho &quot;U bevindt zich momenteel op &quot;.$url.&quot;. Welkom!&quot;;', 'article_0.jpeg', '2026-09-08'),
(29, 'New article', 1, 'This is the body text.', '', 'article_0.png', '2026-09-08'),
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
-- Indexes for table `application_data`
--
ALTER TABLE `application_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dialogue_window`
--
ALTER TABLE `dialogue_window`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_element_info_to_dialogue` (`element_info_id`);

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
-- Indexes for table `element_to_application_data`
--
ALTER TABLE `element_to_application_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_el2var_element_info` (`element_id`),
  ADD KEY `fk_el2var_response_variables` (`application_data_id`);

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
-- AUTO_INCREMENT for table `application_data`
--
ALTER TABLE `application_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `dialogue_window`
--
ALTER TABLE `dialogue_window`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `element_info`
--
ALTER TABLE `element_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `element_lookup_info`
--
ALTER TABLE `element_lookup_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `element_to_application_data`
--
ALTER TABLE `element_to_application_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `field_info`
--
ALTER TABLE `field_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
-- Constraints for table `dialogue_window`
--
ALTER TABLE `dialogue_window`
  ADD CONSTRAINT `fk_element_info_to_dialogue` FOREIGN KEY (`element_info_id`) REFERENCES `element_info` (`id`);

--
-- Constraints for table `element_lookup_info`
--
ALTER TABLE `element_lookup_info`
  ADD CONSTRAINT `fk_element_info_to_look_up` FOREIGN KEY (`element_id`) REFERENCES `element_info` (`id`);

--
-- Constraints for table `element_to_application_data`
--
ALTER TABLE `element_to_application_data`
  ADD CONSTRAINT `fk_el_to_app_data_application_data_id` FOREIGN KEY (`application_data_id`) REFERENCES `application_data` (`id`),
  ADD CONSTRAINT `fk_el_to_app_data_element_id` FOREIGN KEY (`element_id`) REFERENCES `element_info` (`id`);

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
