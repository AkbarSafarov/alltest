-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 24, 2021 at 05:45 PM
-- Server version: 5.7.29
-- PHP Version: 7.4.5

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
-- Table structure for table `auth_assignment`
--

CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `auth_assignment`
--

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('admin', '1', 1568293594);

-- --------------------------------------------------------

--
-- Table structure for table `auth_item`
--

CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `auth_item`
--

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('/*', 2, NULL, NULL, NULL, 1537578127, 1537578127),
('/analytics/*', 2, NULL, NULL, NULL, 1621174965, 1621174965),
('/blog/*', 2, NULL, NULL, NULL, 1621152824, 1621152824),
('/connection/*', 2, NULL, NULL, NULL, 1621152824, 1621152824),
('/course/course/*', 2, NULL, NULL, NULL, 1621239220, 1621239220),
('/course/course/index', 2, NULL, NULL, NULL, 1621172211, 1621172211),
('/course/course/view', 2, NULL, NULL, NULL, 1621159584, 1621159584),
('/course/package/*', 2, NULL, NULL, NULL, 1621239220, 1621239220),
('/course/preview/*', 2, NULL, NULL, NULL, 1633596358, 1633596358),
('/course/unit/*', 2, NULL, NULL, NULL, 1621152824, 1621152824),
('/information/*', 2, NULL, NULL, NULL, 1624322106, 1624322106),
('/items-list/*', 2, NULL, NULL, NULL, 1621152824, 1621152824),
('/library/*', 2, NULL, NULL, NULL, 1621174950, 1621174950),
('/library/attachment-template/*', 2, NULL, NULL, NULL, 1621175136, 1621175136),
('/library/attachment-test/*', 2, NULL, NULL, NULL, 1621175136, 1621175136),
('/library/test-category/*', 2, NULL, NULL, NULL, 1621152824, 1621152824),
('/library/test/*', 2, NULL, NULL, NULL, 1621152824, 1621152824),
('/menu/*', 2, NULL, NULL, NULL, 1621174985, 1621174985),
('/moderation/*', 2, NULL, NULL, NULL, 1621174995, 1621174995),
('/order/*', 2, NULL, NULL, NULL, 1621175027, 1621175027),
('/page/*', 2, NULL, NULL, NULL, 1621175030, 1621175030),
('/session/*', 2, NULL, NULL, NULL, 1621152824, 1621152824),
('/site/index', 2, NULL, NULL, NULL, 1621178119, 1621178119),
('/staticpage/*', 2, NULL, NULL, NULL, 1621175833, 1621175833),
('/user/message/create', 2, NULL, NULL, NULL, 1623707614, 1623707614),
('/user/notification/*', 2, NULL, NULL, NULL, 1621152824, 1621152824),
('/user/user/check-password', 2, NULL, NULL, NULL, 1621162889, 1621162889),
('/user/user/index', 2, NULL, NULL, NULL, 1624400477, 1624400477),
('/user/user/profile-update', 2, NULL, NULL, NULL, 1621162895, 1621162895),
('/user/user/subscribers', 2, NULL, NULL, NULL, 1632488422, 1632488422),
('/user/user/view', 2, NULL, NULL, NULL, 1624400479, 1624400479),
('admin', 1, NULL, 'admin', NULL, 1537596415, 1611322289),
('moderator', 1, NULL, 'moderator', NULL, 1620784334, 1621176037),
('student', 1, NULL, 'student', NULL, 1620784362, 1620784362),
('teacher', 1, NULL, 'teacher', NULL, 1620784349, 1620784349);

-- --------------------------------------------------------

--
-- Table structure for table `auth_item_child`
--

CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `auth_item_child`
--

INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
('admin', '/*'),
('moderator', '/blog/*'),
('moderator', '/connection/*'),
('teacher', '/connection/*'),
('moderator', '/course/course/*'),
('teacher', '/course/course/index'),
('teacher', '/course/course/view'),
('moderator', '/course/package/*'),
('moderator', '/course/preview/*'),
('teacher', '/course/preview/*'),
('moderator', '/course/unit/*'),
('teacher', '/course/unit/*'),
('moderator', '/information/*'),
('teacher', '/information/*'),
('moderator', '/items-list/*'),
('teacher', '/items-list/*'),
('moderator', '/library/*'),
('teacher', '/library/attachment-template/*'),
('teacher', '/library/attachment-test/*'),
('teacher', '/library/test-category/*'),
('teacher', '/library/test/*'),
('moderator', '/menu/*'),
('moderator', '/moderation/*'),
('teacher', '/moderation/*'),
('moderator', '/page/*'),
('moderator', '/session/*'),
('teacher', '/session/*'),
('moderator', '/site/index'),
('teacher', '/site/index'),
('moderator', '/staticpage/*'),
('moderator', '/user/message/create'),
('teacher', '/user/message/create'),
('moderator', '/user/notification/*'),
('teacher', '/user/notification/*'),
('moderator', '/user/user/check-password'),
('teacher', '/user/user/check-password'),
('moderator', '/user/user/index'),
('moderator', '/user/user/profile-update'),
('teacher', '/user/user/profile-update'),
('moderator', '/user/user/subscribers'),
('moderator', '/user/user/view');

-- --------------------------------------------------------

--
-- Table structure for table `auth_rule`
--

CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `auth_rule`
--

INSERT INTO `auth_rule` (`name`, `data`, `created_at`, `updated_at`) VALUES
('admin', 0x4f3a32373a22796969326d6f645c726261635c72756c65735c5573657252756c65223a333a7b733a343a226e616d65223b733a353a2261646d696e223b733a393a22637265617465644174223b693a313533373539363339363b733a393a22757064617465644174223b693a313539303431393436313b7d, 1537596396, 1590419461),
('moderator', 0x4f3a32373a22796969326d6f645c726261635c72756c65735c5573657252756c65223a333a7b733a343a226e616d65223b733a393a226d6f64657261746f72223b733a393a22637265617465644174223b693a313632303738343236353b733a393a22757064617465644174223b693a313632313137363032393b7d, 1620784265, 1621176029),
('student', 0x4f3a32373a22796969326d6f645c726261635c72756c65735c5573657252756c65223a333a7b733a343a226e616d65223b733a373a2273747564656e74223b733a393a22637265617465644174223b693a313632303738343237393b733a393a22757064617465644174223b693a313632303738343237393b7d, 1620784279, 1620784279),
('teacher', 0x4f3a32373a22796969326d6f645c726261635c72756c65735c5573657252756c65223a333a7b733a343a226e616d65223b733a373a2274656163686572223b733a393a22637265617465644174223b693a313632303738343237333b733a393a22757064617465644174223b693a313632303738343237333b7d, 1620784273, 1620784273);

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE `blog` (
  `id` int(11) NOT NULL,
  `name` json NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_description` json NOT NULL,
  `full_description` json NOT NULL,
  `published_at_from` datetime NOT NULL,
  `published_at_to` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('linear','exam') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'linear',
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `language_id` int(11) NOT NULL,
  `author` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'alltest',
  `price` bigint(20) NOT NULL,
  `optimal_time` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `demo_time` int(11) DEFAULT NULL,
  `students_start_quantity` int(11) DEFAULT NULL,
  `students_now_quantity` int(11) NOT NULL DEFAULT '0',
  `passing_percent` tinyint(3) DEFAULT NULL,
  `certificate_file` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preview_image` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_description` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `full_description` text COLLATE utf8mb4_unicode_ci,
  `active_structure` json DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_advantage`
--

CREATE TABLE `course_advantage` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_author`
--

CREATE TABLE `course_author` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `full_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `experience` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_package`
--

CREATE TABLE `course_package` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` bigint(20) NOT NULL,
  `language_id` int(11) NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `optimal_time` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `students_start_quantity` int(11) DEFAULT NULL,
  `students_now_quantity` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_package_ref`
--

CREATE TABLE `course_package_ref` (
  `course_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_teacher_ref`
--

CREATE TABLE `course_teacher_ref` (
  `course_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_unit`
--

CREATE TABLE `course_unit` (
  `id` int(11) NOT NULL,
  `tree` int(11) NOT NULL DEFAULT '1',
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `depth` int(11) NOT NULL,
  `expanded` tinyint(1) NOT NULL DEFAULT '1',
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_id` int(11) DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('development','sent_for_moderation','rejected','active') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'development',
  `default_time_for_test` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_unit_type`
--

CREATE TABLE `course_unit_type` (
  `id` int(11) NOT NULL,
  `name` json NOT NULL,
  `icon` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_unit_type`
--

INSERT INTO `course_unit_type` (`id`, `name`, `icon`, `created_at`, `updated_at`) VALUES
(1, '{\"en\": \"Video\", \"ru\": \"Video\", \"uz\": \"Video\"}', '/uploads/img/font-icons/test.svg', '2021-05-02 22:39:00', '2021-10-03 03:15:32'),
(2, '{\"en\": \"Text\", \"ru\": \"Text\", \"uz\": \"Text\"}', '/uploads/img/font-icons/test.svg', '2021-05-02 22:39:00', '2021-10-03 03:15:43'),
(3, '{\"en\": \"Test\", \"ru\": \"Test\", \"uz\": \"Test\"}', '/uploads/img/font-icons/test.svg', '2021-05-02 22:39:00', '2021-10-03 03:15:54'),
(4, '{\"en\": \"Discussion\", \"ru\": \"Discussion\", \"uz\": \"Discussion\"}', '/uploads/img/font-icons/test.svg', '2021-05-02 22:40:00', '2021-10-03 03:16:09'),
(5, '{\"en\": \"Control\", \"ru\": \"Control\", \"uz\": \"Control\"}', '/uploads/img/font-icons/test.svg', '2021-05-02 22:40:00', '2021-10-03 03:16:30'),
(6, '{\"en\": \"Webinar\", \"ru\": \"Webinar\", \"uz\": \"Webinar\"}', '/uploads/img/font-icons/test.svg', '2021-05-02 22:40:00', '2021-10-03 03:16:39');

-- --------------------------------------------------------

--
-- Table structure for table `library_attachment_template`
--

CREATE TABLE `library_attachment_template` (
  `id` int(11) NOT NULL,
  `model_class` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` int(11) NOT NULL,
  `label` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `input_type` enum('text_input','text_area','text_editor','file_manager','file_input','static') COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `template_information` json NOT NULL,
  `group` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `library_attachment_test`
--

CREATE TABLE `library_attachment_test` (
  `id` int(11) NOT NULL,
  `model_class` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` int(11) NOT NULL,
  `input_type` enum('radio','checkbox','text_area','sequence','match') COLLATE utf8mb4_unicode_ci NOT NULL,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` json NOT NULL,
  `sort` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `library_attachment_test_pack`
--

CREATE TABLE `library_attachment_test_pack` (
  `id` int(11) NOT NULL,
  `model_class` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `category_ids` json NOT NULL,
  `input_types` json NOT NULL,
  `quantity` int(11) NOT NULL,
  `sort` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `library_template`
--

CREATE TABLE `library_template` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `language_id` int(11) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `inputs` json NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `library_template_category`
--

CREATE TABLE `library_template_category` (
  `id` int(11) NOT NULL,
  `name` json NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `library_test`
--

CREATE TABLE `library_test` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `input_type` enum('radio','checkbox','text_area','sequence','match') COLLATE utf8mb4_unicode_ci NOT NULL,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` json NOT NULL,
  `status` enum('development','sent_for_moderation','rejected','active') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'development',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `library_test_category`
--

CREATE TABLE `library_test_category` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `library_test_subject`
--

CREATE TABLE `library_test_subject` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `library_test_subject_teacher_ref`
--

CREATE TABLE `library_test_subject_teacher_ref` (
  `subject_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `tree` int(11) NOT NULL DEFAULT '1',
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `depth` int(11) NOT NULL,
  `expanded` tinyint(1) NOT NULL DEFAULT '1',
  `name` json NOT NULL,
  `url` json NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `tree`, `lft`, `rgt`, `depth`, `expanded`, `name`, `url`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 4, 0, 0, '{\"de\": \"Root\", \"en\": \"Root\", \"ru\": \"Корень\"}', 'null', '2020-09-12 15:00:00', '2020-09-12 15:00:00'),
(27, 1, 2, 3, 1, 1, '{\"en\": \"Footer\", \"ru\": \"Footer\", \"uz\": \"Footer\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '2021-04-14 15:50:00', '2021-04-14 15:50:11');

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1558534394),
('m150425_012013_init', 1558534406),
('m150425_082737_redirects', 1558534406);

-- --------------------------------------------------------

--
-- Table structure for table `moderation_status`
--

CREATE TABLE `moderation_status` (
  `id` int(11) NOT NULL,
  `model_class` enum('CourseUnit','LibraryTest') COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `old_value` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `new_value` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `total_price` bigint(20) NOT NULL DEFAULT '0',
  `status` enum('created','paid','paid_paycom','paid_click') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'created',
  `discount_price` bigint(20) NOT NULL DEFAULT '0',
  `promocode_id` int(11) DEFAULT NULL,
  `promocode_price` bigint(20) NOT NULL DEFAULT '0',
  `key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_discount`
--

CREATE TABLE `order_discount` (
  `id` int(11) NOT NULL,
  `name` json NOT NULL,
  `percent` tinyint(3) NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_discount_product_ref`
--

CREATE TABLE `order_discount_product_ref` (
  `discount_id` int(11) NOT NULL,
  `model_class` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_payment_click`
--

CREATE TABLE `order_payment_click` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `click_trans_id` bigint(20) NOT NULL,
  `service_id` int(11) NOT NULL,
  `click_paydoc_id` bigint(20) NOT NULL,
  `amount` int(11) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `sign_time` datetime NOT NULL,
  `sign_string` varchar(100) NOT NULL,
  `prepare_time` datetime DEFAULT NULL,
  `complete_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `order_payment_paycom`
--

CREATE TABLE `order_payment_paycom` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `paycom_transaction_id` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `paycom_time` int(11) NOT NULL,
  `paycom_time_datetime` datetime NOT NULL,
  `create_time` datetime NOT NULL,
  `perform_time` datetime DEFAULT NULL,
  `cancel_time` datetime DEFAULT NULL,
  `amount` int(11) NOT NULL,
  `state` tinyint(2) NOT NULL,
  `reason` tinyint(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_product`
--

CREATE TABLE `order_product` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `model_class` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` int(11) NOT NULL,
  `model_json` json NOT NULL,
  `total_price` bigint(20) NOT NULL,
  `discount_id` int(11) DEFAULT NULL,
  `discount_price` bigint(20) NOT NULL DEFAULT '0',
  `promocode_id` int(11) DEFAULT NULL,
  `promocode_price` bigint(20) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_promocode`
--

CREATE TABLE `order_promocode` (
  `id` int(11) NOT NULL,
  `name` json NOT NULL,
  `percent` tinyint(3) NOT NULL,
  `description` json NOT NULL,
  `key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `max_activations` int(11) DEFAULT NULL,
  `used_activations` int(11) DEFAULT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `max_products` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_promocode_product_ref`
--

CREATE TABLE `order_promocode_product_ref` (
  `promocode_id` int(11) NOT NULL,
  `model_class` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `page`
--

CREATE TABLE `page` (
  `id` int(11) NOT NULL,
  `name` json NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` json NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `queue`
--

CREATE TABLE `queue` (
  `id` int(11) NOT NULL,
  `channel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `job` longblob NOT NULL,
  `pushed_at` int(11) NOT NULL,
  `ttr` int(11) NOT NULL,
  `delay` int(11) NOT NULL DEFAULT '0',
  `priority` int(11) UNSIGNED NOT NULL DEFAULT '1024',
  `reserved_at` int(11) DEFAULT NULL,
  `attempt` int(11) DEFAULT NULL,
  `done_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reward_achievement`
--

CREATE TABLE `reward_achievement` (
  `id` int(11) NOT NULL,
  `key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` json NOT NULL,
  `description` json NOT NULL,
  `icon` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reward_achievement`
--

INSERT INTO `reward_achievement` (`id`, `key`, `name`, `description`, `icon`, `sort`, `updated_at`) VALUES
(1, 'explorer_gold', '{\"en\": \"Исследователь - золото\", \"ru\": \"Исследователь - золото\", \"uz\": \"Исследователь - золото\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/icons/gold.svg', 0, '2021-09-16 17:10:05'),
(2, 'explorer_silver', '{\"en\": \"Исследователь - серебро\", \"ru\": \"Исследователь - серебро\", \"uz\": \"Исследователь - серебро\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/icons/platinum.svg', 1, '2021-09-16 17:10:38'),
(3, 'explorer_bronze', '{\"en\": \"Исследователь - бронза\", \"ru\": \"Исследователь - бронза\", \"uz\": \"Исследователь - бронза\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/icons/silver.svg', 2, '2021-09-16 17:11:25'),
(4, 'nerd_flawless', '{\"en\": \"Безупречный результат\", \"ru\": \"Безупречный результат\", \"uz\": \"Безупречный результат\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/icons/diamond.svg', 3, '2021-09-20 00:31:04'),
(5, 'nerd_gold', '{\"en\": \"Умник - золото\", \"ru\": \"Умник - золото\", \"uz\": \"Умник - золото\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/icons/gold.svg', 4, '2021-09-16 17:19:12'),
(6, 'nerd_silver', '{\"en\": \"Умник - серебро\", \"ru\": \"Умник - серебро\", \"uz\": \"Умник - серебро\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/icons/platinum.svg', 5, '2021-09-16 17:19:33'),
(7, 'nerd_bronze', '{\"en\": \"Умник - бронза\", \"ru\": \"Умник - бронза\", \"uz\": \"Умник - бронза\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/icons/silver.svg', 6, '2021-09-16 17:20:07'),
(8, 'persistent_gold', '{\"en\": \"Настойчивый - золото\", \"ru\": \"Настойчивый - золото\", \"uz\": \"Настойчивый - золото\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/icons/gold.svg', 7, '2021-09-16 17:20:52'),
(9, 'persistent_silver', '{\"en\": \"Настойчивый - серебро\", \"ru\": \"Настойчивый - серебро\", \"uz\": \"Настойчивый - серебро\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/icons/platinum.svg', 8, '2021-09-16 17:21:14'),
(10, 'persistent_bronze', '{\"en\": \"Настойчивый - бронза\", \"ru\": \"Настойчивый - бронза\", \"uz\": \"Настойчивый - бронза\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/icons/silver.svg', 9, '2021-09-16 17:21:32'),
(11, 'intellectual_gold', '{\"en\": \"Интеллектуал - золото\", \"ru\": \"Интеллектуал - золото\", \"uz\": \"Интеллектуал - золото\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/icons/gold.svg', 10, '2021-09-16 17:22:44'),
(12, 'intellectual_silver', '{\"en\": \"Интеллектуал - серебро\", \"ru\": \"Интеллектуал - серебро\", \"uz\": \"Интеллектуал - серебро\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/icons/platinum.svg', 11, '2021-09-16 17:23:07'),
(13, 'intellectual_bronze', '{\"en\": \"Интеллектуал - бронза\", \"ru\": \"Интеллектуал - бронза\", \"uz\": \"Интеллектуал - бронза\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/icons/silver.svg', 12, '2021-09-16 17:24:40');

-- --------------------------------------------------------

--
-- Table structure for table `reward_league`
--

CREATE TABLE `reward_league` (
  `id` int(11) NOT NULL,
  `key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` json NOT NULL,
  `description` json NOT NULL,
  `icon` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reward_league`
--

INSERT INTO `reward_league` (`id`, `key`, `name`, `description`, `icon`, `sort`, `updated_at`) VALUES
(1, 'explorer_best', '{\"en\": \"Исследователь - №1\", \"ru\": \"Исследователь - №1\", \"uz\": \"Исследователь - №1\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/font-icons/mortarboard.svg', 0, '2021-09-20 19:17:00'),
(2, 'explorer_diamond', '{\"en\": \"Исследователь - Алмазная лига\", \"ru\": \"Исследователь - Алмазная лига\", \"uz\": \"Исследователь - Алмазная лига\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/font-icons/idea.svg', 1, '2021-09-20 19:17:17'),
(3, 'explorer_platinum', '{\"en\": \"Исследователь - Платиновая лига\", \"ru\": \"Исследователь - Платиновая лига\", \"uz\": \"Исследователь - Платиновая лига\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/icons/diamond.svg', 2, '2021-09-20 19:17:22'),
(4, 'explorer_gold', '{\"en\": \"Исследователь - Золотая лига\", \"ru\": \"Исследователь - Золотая лига\", \"uz\": \"Исследователь - Золотая лига\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/icons/gold.svg', 3, '2021-09-20 19:17:27'),
(5, 'explorer_silver', '{\"en\": \"Исследователь - Серебряная лига\", \"ru\": \"Исследователь - Серебряная лига\", \"uz\": \"Исследователь - Серебряная лига\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/icons/platinum.svg', 4, '2021-09-20 19:17:46'),
(6, 'explorer_bronze', '{\"en\": \"Исследователь - Бронзовая лига\", \"ru\": \"Исследователь - Бронзовая лига\", \"uz\": \"Исследователь - Бронзовая лига\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/icons/silver.svg', 5, '2021-09-20 19:17:52'),
(7, 'nerd_best', '{\"en\": \"Умник - №1\", \"ru\": \"Умник - №1\", \"uz\": \"Умник - №1\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/font-icons/mortarboard.svg', 6, '2021-09-20 19:18:32'),
(8, 'nerd_diamond', '{\"en\": \"Умник - Алмазная лига\", \"ru\": \"Умник - Алмазная лига\", \"uz\": \"Умник - Алмазная лига\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/font-icons/idea.svg', 7, '2021-09-20 19:18:36'),
(9, 'nerd_platinum', '{\"en\": \"Умник - Платиновая лига\", \"ru\": \"Умник - Платиновая лига\", \"uz\": \"Умник - Платиновая лига\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/icons/diamond.svg', 8, '2021-09-20 19:18:39'),
(10, 'nerd_gold', '{\"en\": \"Умник - Золотая лига\", \"ru\": \"Умник - Золотая лига\", \"uz\": \"Умник - Золотая лига\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/icons/gold.svg', 9, '2021-09-20 19:18:43'),
(11, 'nerd_silver', '{\"en\": \"Умник - Серебряная лига\", \"ru\": \"Умник - Серебряная лига\", \"uz\": \"Умник - Серебряная лига\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/icons/platinum.svg', 10, '2021-09-20 19:18:46'),
(12, 'nerd_bronze', '{\"en\": \"Умник - Бронзовая лига\", \"ru\": \"Умник - Бронзовая лига\", \"uz\": \"Умник - Бронзовая лига\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/icons/silver.svg', 11, '2021-09-20 19:18:49'),
(13, 'persistent_best', '{\"en\": \"Настойчивый - №1\", \"ru\": \"Настойчивый - №1\", \"uz\": \"Настойчивый - №1\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/font-icons/mortarboard.svg', 12, '2021-09-20 19:19:15'),
(14, 'persistent_diamond', '{\"en\": \"Настойчивый - Алмазная лига\", \"ru\": \"Настойчивый - Алмазная лига\", \"uz\": \"Настойчивый - Алмазная лига\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/font-icons/idea.svg', 13, '2021-09-20 19:19:25'),
(15, 'persistent_platinum', '{\"en\": \"Настойчивый - Платиновая лига\", \"ru\": \"Настойчивый - Платиновая лига\", \"uz\": \"Настойчивый - Платиновая лига\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/icons/diamond.svg', 14, '2021-09-20 19:19:29'),
(16, 'persistent_gold', '{\"en\": \"Настойчивый - Золотая лига\", \"ru\": \"Настойчивый - Золотая лига\", \"uz\": \"Настойчивый - Золотая лига\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/icons/gold.svg', 15, '2021-09-20 19:19:32'),
(17, 'persistent_silver', '{\"en\": \"Настойчивый - Серебряная лига\", \"ru\": \"Настойчивый - Серебряная лига\", \"uz\": \"Настойчивый - Серебряная лига\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/icons/platinum.svg', 16, '2021-09-20 19:19:37'),
(18, 'persistent_bronze', '{\"en\": \"Настойчивый - Бронзовая лига\", \"ru\": \"Настойчивый - Бронзовая лига\", \"uz\": \"Настойчивый - Бронзовая лига\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/icons/silver.svg', 17, '2021-09-20 19:19:41'),
(19, 'intellectual_best', '{\"en\": \"Интеллектуал - №1\", \"ru\": \"Интеллектуал - №1\", \"uz\": \"Интеллектуал - №1\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/font-icons/mortarboard.svg', 18, '2021-09-20 19:20:11'),
(20, 'intellectual_diamond', '{\"en\": \"Интеллектуал - Алмазная лига\", \"ru\": \"Интеллектуал - Алмазная лига\", \"uz\": \"Интеллектуал - Алмазная лига\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/font-icons/idea.svg', 19, '2021-09-20 19:20:16'),
(21, 'intellectual_platinum', '{\"en\": \"Интеллектуал - Платиновая лига\", \"ru\": \"Интеллектуал - Платиновая лига\", \"uz\": \"Интеллектуал - Платиновая лига\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/icons/diamond.svg', 20, '2021-09-20 19:20:21'),
(22, 'intellectual_gold', '{\"en\": \"Интеллектуал - Золотая лига\", \"ru\": \"Интеллектуал - Золотая лига\", \"uz\": \"Интеллектуал - Золотая лига\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/icons/gold.svg', 21, '2021-09-20 19:20:25'),
(23, 'intellectual_silver', '{\"en\": \"Интеллектуал - Серебряная лига\", \"ru\": \"Интеллектуал - Серебряная лига\", \"uz\": \"Интеллектуал - Серебряная лига\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/icons/platinum.svg', 22, '2021-09-20 19:20:27'),
(24, 'intellectual_bronze', '{\"en\": \"Интеллектуал - Бронзовая лига\", \"ru\": \"Интеллектуал - Бронзовая лига\", \"uz\": \"Интеллектуал - Бронзовая лига\"}', '{\"en\": \"\", \"ru\": \"\", \"uz\": \"\"}', '/uploads/img/icons/silver.svg', 23, '2021-09-20 19:20:30');

-- --------------------------------------------------------

--
-- Table structure for table `seo_meta`
--

CREATE TABLE `seo_meta` (
  `id` int(11) NOT NULL,
  `model_class` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` int(11) NOT NULL DEFAULT '0',
  `lang` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` json NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE `session` (
  `id` char(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expire` int(11) DEFAULT NULL,
  `data` blob
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staticpage`
--

CREATE TABLE `staticpage` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `route` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_seo_meta` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staticpage`
--

INSERT INTO `staticpage` (`id`, `name`, `label`, `route`, `has_seo_meta`) VALUES
(16, 'footer', 'Футер', NULL, 0),
(17, 'home', 'Главная', 'site/index', 1),
(20, 'auth', 'Авторизация', 'auth/login', 0),
(21, 'contact', 'Контакты', 'site/contact', 1),
(22, 'blog', 'Новости', 'blog/index', 1),
(23, 'faq', 'FAQ', 'site/faq', 1),
(25, 'courses', 'Курсы', 'course/courses', 1),
(28, 'course_packages', 'Пакеты курсов', 'course/packages', 1);

-- --------------------------------------------------------

--
-- Table structure for table `staticpage_block`
--

CREATE TABLE `staticpage_block` (
  `id` int(11) NOT NULL,
  `staticpage_id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` json NOT NULL,
  `type` enum('text_input','text_area','checkbox','elfinder','tinymce','files','groups') COLLATE utf8mb4_unicode_ci NOT NULL,
  `attrs` json NOT NULL,
  `part_index` int(11) NOT NULL,
  `part_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `has_translation` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staticpage_block`
--

INSERT INTO `staticpage_block` (`id`, `staticpage_id`, `name`, `label`, `value`, `type`, `attrs`, `part_index`, `part_name`, `has_translation`) VALUES
(47, 16, 'partners', 'Партнёры', '[{\"link\": \"https://ru.ziyoforum.uz/\", \"image\": \"/uploads/img/content/logo.png\"}]', 'groups', '[{\"name\": \"image\", \"type\": \"elfinder\", \"label\": \"Изображение\"}, {\"name\": \"link\", \"type\": \"text_input\", \"label\": \"Ссылка\"}]', 0, 'Партнёры', 0),
(50, 20, 'image_login', 'Авторизация', '\"/uploads/img/content/login.png\"', 'elfinder', '[]', 0, 'Изображения', 0),
(51, 20, 'image_signup', 'Регистрация', '\"/uploads/img/content/registration.png\"', 'elfinder', '[]', 0, 'Изображения', 0),
(52, 20, 'image_reset_password_request', 'Запрос на восстановление пароля', '\"/uploads/img/content/free-pic.png\"', 'elfinder', '[]', 0, 'Изображения', 0),
(53, 20, 'image_reset_password', 'Восстановление пароля', '\"/uploads/img/content/free-pic.png\"', 'elfinder', '[]', 0, 'Изображения', 0),
(54, 21, 'address', 'Адрес', '{\"en\": \"qw dqwd qwdq w\", \"ru\": \"qwe\"}', 'text_input', '[]', 0, 'Информация', 1),
(55, 21, 'phone', 'Телефон', '\"1231231231\"', 'text_input', '[]', 0, 'Информация', 0),
(56, 21, 'email', 'Почта', '\"qwe@qwe.qwe\"', 'text_input', '[]', 0, 'Информация', 0),
(57, 21, 'dev_by', 'Контент разработан', '\"qwdqw\"', 'text_input', '[]', 0, 'Информация', 0),
(58, 21, 'social_instagram', 'Instagram', '\"\"', 'text_input', '[]', 1, 'Соц.сети', 0),
(59, 21, 'social_facebook', 'Facebook', '\"qw dqw dq\"', 'text_input', '[]', 1, 'Соц.сети', 0),
(60, 21, 'social_youtube', 'Youtube', '\"w dqwd qwd q\"', 'text_input', '[]', 1, 'Соц.сети', 0),
(61, 21, 'social_telegram', 'Telegram', '\"w dqwd qwd q\"', 'text_input', '[]', 1, 'Соц.сети', 0),
(62, 23, 'questions_and_answers', 'Вопросы и ответы', '{\"en\": [{\"answer\": \"<p>dqwdqwd qwd qwd qd</p>\", \"question\": \"123\"}, {\"answer\": \"<p>qwdqwdqwd</p>\\n<p>qw</p>\\n<p>dqw dqw q</p>\", \"question\": \"qwe\"}, {\"answer\": \"<p>dwadawd aw</p>\\n<p>d aw</p>\\n<p>d awd awd awd</p>\", \"question\": \"asd\"}, {\"answer\": \"<p>vrverv</p>\\n<p>erver ver ver </p>\\n<p> </p>\\n<p>v erv erver</p>\", \"question\": \"zxc\"}], \"ru\": [{\"answer\": \"qwdqwd\", \"question\": \"dqwd\"}]}', 'groups', '[{\"name\": \"question\", \"type\": \"text_input\", \"label\": \"Вопрос\"}, {\"name\": \"answer\", \"type\": \"tinymce\", \"label\": \"Ответ\"}]', 0, 'Вопросы и ответы', 1),
(63, 17, 'slider', 'Слайдер', '{\"en\": [{\"link\": \"dqwd\", \"image\": \"/uploads/img/content/main-slider-3.png\", \"title\": \"qwe\", \"description\": \"dqwdqwdqwd\\r\\nd qwdq\"}, {\"link\": \"\", \"image\": \"/uploads/img/content/main-slider.png\", \"title\": \"asd\", \"description\": \"dawd awd awd\"}, {\"link\": \"\", \"image\": \"/uploads/img/content/mooc-pic.png\", \"title\": \"zxc\", \"description\": \"dawd awd\\r\\n\\r\\n dawd awdad\"}], \"ru\": [{\"link\": \"\", \"image\": \"/uploads/computer-sciense-pic.png\", \"title\": \"Знания, с которыми ты станешь студентом, уже здесь.\", \"description\": \"Если ты абитуриент и мечтаешь поступить в университет, эти онлайн курсы для тебя. Занимайся в комфортных для тебя условиях, определяй график занятий сам, конкурируй со сверстниками и получай высокие баллы. Пора готовиться к вузу твоей мечты!\"}], \"uz\": [{\"link\": \"\", \"image\": \"/uploads/computer-sciense-pic.png\", \"title\": \"Sizni shu yerning o‘zida talabalikka olib boruvchi bilimlar.\", \"description\": \"Siz abituriyentsiz va universitetga kirish haqida orzu qilasizmi? Unda bu kurslar aynan Siz uchun! O‘zingizga qulay sharoitlarda shug‘ullaning, mashg‘ulotlar jadvalini o‘zingiz belgilang, tengdoshlaringiz bilan bellashib, yuqori ballarga erishing. Orzuyingizdagi OTMga tayyorlanish vaqti keldi!\"}]}', 'groups', '[{\"name\": \"title\", \"type\": \"text_input\", \"label\": \"Заголовок\"}, {\"name\": \"description\", \"type\": \"text_area\", \"label\": \"Описание\"}, {\"name\": \"link\", \"type\": \"text_input\", \"label\": \"Ссылка\"}, {\"name\": \"image\", \"type\": \"elfinder\", \"label\": \"Изображение\"}]', 0, 'Слайдер', 1),
(64, 17, 'how_it_works_title', 'Заголовок', '{\"en\": \"Как устроена платформа\", \"ru\": \"КАК УСТРОЕНА ПЛАТФОРМА\", \"uz\": \"Platforma qanday tashkil etilgan?\"}', 'text_input', '[]', 1, 'Как устроена платформа', 1),
(65, 17, 'how_it_works_image', 'Изображение', '\"/uploads/img/content/news.jpg\"', 'elfinder', '[]', 1, 'Как устроена платформа', 0),
(66, 17, 'how_it_works_list', 'Список', '{\"en\": [{\"icon\": \"calendar\", \"title\": \"qwe\", \"description\": \"awdawdawd\\naw\\nd awd \\nawd awd a awdw\"}, {\"icon\": \"newspaper\", \"title\": \"asd\", \"description\": \"dawdawdaw a\\nw daw daw\"}, {\"icon\": \"people\", \"title\": \"zxc\", \"description\": \"dawdawd\"}], \"ru\": [], \"uz\": []}', 'groups', '[{\"name\": \"title\", \"type\": \"text_input\", \"label\": \"Заголовок\"}, {\"name\": \"icon\", \"type\": \"elfinder\", \"label\": \"Иконка\"}, {\"name\": \"description\", \"type\": \"text_area\", \"label\": \"Описание\"}]', 1, 'Как устроена платформа', 1),
(67, 17, 'video_title', 'Заголовок', '{\"en\": \"Почему стоит выбрать All Test?\", \"ru\": \"Наш каталог курсов уже ждет тебя, переходи и знакомься.\", \"uz\": \"Kurslar katalogi Sizni kutmoqda! Kirib, tanishib chiqishingiz mumkin.\"}', 'text_input', '[]', 2, 'Блок с видео', 1),
(68, 17, 'video_description', 'Описание', '{\"en\": \"Посмотрите 3-х минутное видео о преимуществах платформы\", \"ru\": \"Чтобы тебе было легче понять преимущества обучения с alltest, мы подготовили видео. Посмотри его и прими решение, как ты хочешь подготовиться к поступлению. \", \"uz\": \"Siz uchun Alltest bilan taʼlim olish afzalliklarini namoyish etish maqsadida video tayyorladik. Videoni ko‘ring va qabulga qanday tayyorlanishga o‘zingiz qaror qiling.\"}', 'text_area', '[]', 2, 'Блок с видео', 1),
(69, 17, 'video', 'Видео', '\"/uploads/img/video/dior.mp4\"', 'elfinder', '[]', 2, 'Блок с видео', 0),
(70, 17, 'video_poster', 'Постер', '\"/uploads/img/flags/br.png\"', 'elfinder', '[]', 2, 'Блок с видео', 0),
(71, 17, 'education_title', 'Заголовок', '{\"en\": \"Как проходит обучение\", \"ru\": \"Как проходит обучение\", \"uz\": \"TAʼLIM QANDAY OLIB BORILADI?\"}', 'text_input', '[]', 3, 'Как проходит обучение', 1),
(72, 17, 'education_image', 'Изображение', '\"/uploads/img/content/education.png\"', 'elfinder', '[]', 3, 'Как проходит обучение', 0),
(73, 17, 'education_list', 'Список', '{\"en\": [{\"title\": \"qwe\", \"description\": \"awdaw \\nawd awd awd\"}, {\"title\": \"asd\", \"description\": \"zrre rber erbe\"}, {\"title\": \"zxc\", \"description\": \"wdawdaw \\ndaw \\ndaw daw \"}], \"ru\": [{\"title\": \"Индивидуальный темп обучения\", \"description\": \"\"}, {\"title\": \"Все предметы в одной платформе\", \"description\": \"\"}, {\"title\": \"Контроль полученных знаний\", \"description\": \"\"}, {\"title\": \"Возможность сравнивать достижения со сверстниками\", \"description\": \"\"}, {\"title\": \"Акции и промокоды\", \"description\": \"\"}], \"uz\": [{\"title\": \"Individual taʼlim tezligi\", \"description\": \"\"}, {\"title\": \"Barcha fanlar bir platformada\", \"description\": \"\"}, {\"title\": \"O‘rganilgan bilimlar nazorati\", \"description\": \"\"}, {\"title\": \"Tengdoshlar bilan yutuqlarni taqqoslash imkoniyati\", \"description\": \"\"}, {\"title\": \"Aksiya va promokodlar\", \"description\": \"\"}]}', 'groups', '[{\"name\": \"title\", \"type\": \"text_input\", \"label\": \"Заголовок\"}, {\"name\": \"description\", \"type\": \"text_area\", \"label\": \"Описание\"}]', 3, 'Как проходит обучение', 1),
(74, 17, 'review_title', 'Заголовок', '{\"en\": \"Отзывы студентов\", \"ru\": \"Отзывы\", \"uz\": \"Talabalar fikri\"}', 'text_input', '[]', 4, 'Отзывы студентов', 1),
(75, 17, 'review_list', 'Список', '{\"en\": [{\"image\": \"/uploads/img/content/creator.jpg\", \"title\": \"qwe\", \"position\": \"dwqdqw \", \"full_name\": \"dawdawdaw\", \"description\": \"qwdqw\\r\\ndqw dq\\r\\nw dqw dq\"}, {\"image\": \"/uploads/img/content/account.jpg\", \"title\": \"asd\", \"position\": \"dqwd\", \"full_name\": \" greg erger\", \"description\": \"dawdaw aw dawd awd \"}, {\"image\": \"/uploads/img/content/education.png\", \"title\": \"zxc\", \"position\": \"qwdq\", \"full_name\": \"dadwd\", \"description\": \"w dawd awd\\r\\n a\\r\\nwd awd a\"}], \"ru\": [{\"image\": \"\", \"title\": \"Hammasi tushunarli va ortiqcha maʼlumotlarsiz berilgan.\", \"position\": \"\", \"full_name\": \"Faxriddinova Kamola - O‘zbek tili.\", \"description\": \"Onlayn kurslarni sinab ko‘rish haqida uzoq o‘yladim, chunki tushunarli bo‘lmasligidan qo‘rqardim. Lekin darslar juda lo‘nda va tushunarli ekan. Birga tayyorlanayotgan sinfdoshlarimga ham sinab ko‘rishni maslahat berdim.\"}, {\"image\": \"\", \"title\": \"Men  eng boshidan boshlagan bo‘lsam ham, fanni ancha yaxshi o‘zlashtirib oldim. \", \"position\": \"\", \"full_name\": \"Maxmudov Otabek - Matematika. \", \"description\": \"Boshlang‘ich sinflardan matematikani yaxshi o‘qimaganim uchun, qo‘shimcha darslarga borishga uyalardim. Menga bu darslar judayam yoqdi! O‘qituvchi hammasini aniq tushuntirib bergan, men esa har kuni shug‘ullanishga harakat qilaman. Kirish imtihonlarigacha tayyorlanishga ulgurishimga ishonaman.\"}, {\"image\": \"\", \"title\": \"Darslarni o‘zimga qulay paytda ko‘raman. Bu judayam zo‘r!\", \"position\": \"\", \"full_name\": \" Mamatqulova Sevinch - Ingliz tili.\", \"description\": \"Dars vaqtini o‘zimning ishlarimga qarab tanlashim mumkin. Barchasi tushunarli ekanligidan savol yuzaga kelmadi. Bu o‘qituvchim Miss Malikaning mehnati! Men nihoyat avvallari bir necha marta o‘tilgan mavzularni tushunishni boshlayapman. Bundan tashqari, matnlar ham juda qiziqarli!\"}, {\"image\": \"\", \"title\": \"Yaxshi tushunmagan mavzularimni qayta o‘rganyapman va ko‘p yangiliklarni bilib olyapman!\", \"position\": \"\", \"full_name\": \"Xusniddinov Doniyor - Kimyo va biologiya.\", \"description\": \"Maktabda kimyoni yaxshi o‘qiganman, chunki bu fan menga juda yoqardi. Lekin yaxshi o‘rganolmagan mavzularim bor edi. Tibbiyot bo‘yicha OTMga kirish uchun 100% tayyor bo‘lishni xohlayman va Alltest kurslari menga juda yoqadi.\"}, {\"image\": \"\", \"title\": \"Qiziqarli darslar.\", \"position\": \"\", \"full_name\": \"Fazilov Avazbek - Fizika.\", \"description\": \"Bu kurs judayam yoqdi! Ayniqsa maʼlumotlarning berilish usuli: videodagi illyustratsiyalar va qiziqarli tushuntirishlar yoqdi. Bu o‘rganishni ancha qiziqarli qiladi va yangi mavzular tez esda qoladi. Testlar va uy vazifalarida o‘rganganlarimni mustahkamlashim mumkin.\"}, {\"image\": \"\", \"title\": \"Bu kurs qandayligi haqida rosa ikkalandim, lekin endi sinab ko‘rganimdan xursandman.\", \"position\": \"\", \"full_name\": \"Nuriddinova Iroda - Tarix\", \"description\": \"Bir nechta darslarni o‘rganib, bemalol Alltest kurslarini sotib olganimdan xursandligimni ayta olaman. Agar ikkilanayotgan bo‘lsangiz, o‘z tajribamdan aytaman: darslarning sifati aʼlo, yaratuvchilar bilim olishingiz haqida chindan ham qayg‘uradi. Men yana boshqa fanlarni, masalan, Ona tili va Ingliz tilini sinab ko‘rmoqchiman, bu fanlar keyingi yilda imtihonlarimda bo‘ladi.\"}], \"uz\": [{\"image\": \"\", \"title\": \"Hammasi tushunarli va ortiqcha maʼlumotlarsiz berilgan.\", \"position\": \"\", \"full_name\": \"Faxriddinova Kamola - O‘zbek tili.\", \"description\": \"Onlayn kurslarni sinab ko‘rish haqida uzoq o‘yladim, chunki tushunarli bo‘lmasligidan qo‘rqardim. Lekin darslar juda lo‘nda va tushunarli ekan. Birga tayyorlanayotgan sinfdoshlarimga ham sinab ko‘rishni maslahat berdim.\"}, {\"image\": \"\", \"title\": \"Men  eng boshidan boshlagan bo‘lsam ham, fanni ancha yaxshi o‘zlashtirib oldim. \", \"position\": \"\", \"full_name\": \"Maxmudov Otabek - Matematika. \", \"description\": \"Boshlang‘ich sinflardan matematikani yaxshi o‘qimaganim uchun, qo‘shimcha darslarga borishga uyalardim. Menga bu darslar judayam yoqdi! O‘qituvchi hammasini aniq tushuntirib bergan, men esa har kuni shug‘ullanishga harakat qilaman. Kirish imtihonlarigacha tayyorlanishga ulgurishimga ishonaman.\"}, {\"image\": \"\", \"title\": \"Darslarni o‘zimga qulay paytda ko‘raman. Bu judayam zo‘r!\", \"position\": \"\", \"full_name\": \" Mamatqulova Sevinch - Ingliz tili.\", \"description\": \"Dars vaqtini o‘zimning ishlarimga qarab tanlashim mumkin. Barchasi tushunarli ekanligidan savol yuzaga kelmadi. Bu o‘qituvchim Miss Malikaning mehnati! Men nihoyat avvallari bir necha marta o‘tilgan mavzularni tushunishni boshlayapman. Bundan tashqari, matnlar ham juda qiziqarli!\"}, {\"image\": \"\", \"title\": \"Yaxshi tushunmagan mavzularimni qayta o‘rganyapman va ko‘p yangiliklarni bilib olyapman!\", \"position\": \"\", \"full_name\": \"Xusniddinov Doniyor - Kimyo va biologiya.\", \"description\": \"Maktabda kimyoni yaxshi o‘qiganman, chunki bu fan menga juda yoqardi. Lekin yaxshi o‘rganolmagan mavzularim bor edi. Tibbiyot bo‘yicha OTMga kirish uchun 100% tayyor bo‘lishni xohlayman va Alltest kurslari menga juda yoqadi.\"}, {\"image\": \"\", \"title\": \"Qiziqarli darslar.\", \"position\": \"\", \"full_name\": \"Fazilov Avazbek - Fizika.\", \"description\": \"Bu kurs judayam yoqdi! Ayniqsa maʼlumotlarning berilish usuli: videodagi illyustratsiyalar va qiziqarli tushuntirishlar yoqdi. Bu o‘rganishni ancha qiziqarli qiladi va yangi mavzular tez esda qoladi. Testlar va uy vazifalarida o‘rganganlarimni mustahkamlashim mumkin.\"}, {\"image\": \"\", \"title\": \"Bu kurs qandayligi haqida rosa ikkalandim, lekin endi sinab ko‘rganimdan xursandman.\", \"position\": \"\", \"full_name\": \"Nuriddinova Iroda - Tarix\", \"description\": \"Bir nechta darslarni o‘rganib, bemalol Alltest kurslarini sotib olganimdan xursandligimni ayta olaman. Agar ikkilanayotgan bo‘lsangiz, o‘z tajribamdan aytaman: darslarning sifati aʼlo, yaratuvchilar bilim olishingiz haqida chindan ham qayg‘uradi. Men yana boshqa fanlarni, masalan, Ona tili va Ingliz tilini sinab ko‘rmoqchiman, bu fanlar keyingi yilda imtihonlarimda bo‘ladi.\"}]}', 'groups', '[{\"name\": \"title\", \"type\": \"text_input\", \"label\": \"Заголовок\"}, {\"name\": \"description\", \"type\": \"text_area\", \"label\": \"Описание\"}, {\"name\": \"full_name\", \"type\": \"text_input\", \"label\": \"ФИО\"}, {\"name\": \"position\", \"type\": \"text_input\", \"label\": \"Должность\"}, {\"name\": \"image\", \"type\": \"elfinder\", \"label\": \"Изображение\"}]', 4, 'Отзывы студентов', 1),
(76, 17, 'banner_title', 'Заголовок', '{\"en\": \"Попробуйте бесплатно!\", \"ru\": \"Все еще сомневаешься? Подпишись на любой курс и получи пробный период, чтобы оценить удобство обучения с alltest.\", \"uz\": \"Hali ham ikkilanyapsizmi? Alltest bilan taʼlim olish qulayligini baholash uchun istalgan kursga yozilib, sinov muddatiga ega bo‘ling.\"}', 'text_input', '[]', 5, 'Баннер', 1),
(77, 17, 'banner_link', 'Ссылка', '{\"en\": \"dqwdqwd\", \"ru\": \"\", \"uz\": \"\"}', 'text_input', '[]', 5, 'Баннер', 1),
(78, 17, 'banner_image', 'Изображение', '\"/uploads/img/content/main-slider-3.png\"', 'elfinder', '[]', 5, 'Баннер', 0);

-- --------------------------------------------------------

--
-- Table structure for table `system_language`
--

CREATE TABLE `system_language` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_main` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_language`
--

INSERT INTO `system_language` (`id`, `name`, `code`, `image`, `is_active`, `is_main`, `created_at`, `updated_at`) VALUES
(1, 'English', 'en', '/uploads/img/flags/gb.png', 0, 0, '2019-12-26 06:14:00', '2021-07-15 13:15:14'),
(2, 'Russian', 'ru', '/uploads/img/flags/ru.png', 1, 1, '2020-11-29 06:56:00', '2021-07-15 13:14:25'),
(3, 'Uzbek', 'uz', '/uploads/img/flags/uz.png', 1, 0, '2021-04-05 16:26:00', '2021-07-31 10:26:04');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('text_input','text_area','checkbox','elfinder','tinymce') COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `label`, `value`, `type`, `sort`) VALUES
(3, 'site_name', 'Название сайта', 'Alltest', 'text_input', 0),
(6, 'site_logo_1', 'Логотип (header)', '/uploads/img/content/logo.svg', 'elfinder', 1),
(7, 'admin_email', 'Почта для обратной связи', 'info@alltest.uz', 'text_input', 4),
(11, 'site_favicon', 'Favicon', '/uploads/img/content/alltest.svg', 'elfinder', 3),
(12, 'default_test_answers_count', 'Количество вариантов ответа при создании теста', '5', 'text_input', 7),
(13, 'site_logo_2', 'Логотип (footer)', '/uploads/img/content/logo-footer.svg', 'elfinder', 2),
(14, 'image_placeholder', 'Placeholder для изображений', '/uploads/placeholder-image.png', 'elfinder', 5),
(15, 'profile_image_placeholder', 'Placeholder для профиля', '/uploads/placeholder-1.jpg', 'elfinder', 6),
(16, 'user_course_unit_available_from_interval', 'Интервал для пересдачи (в часах)', '12', 'text_input', 8);

-- --------------------------------------------------------

--
-- Table structure for table `translation_message`
--

CREATE TABLE `translation_message` (
  `id` int(11) NOT NULL,
  `language` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `translation` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `translation_message`
--

INSERT INTO `translation_message` (`id`, `language`, `translation`) VALUES
(1, 'en', ''),
(1, 'ru', ''),
(1, 'uz', ''),
(2, 'en', ''),
(2, 'ru', ''),
(2, 'uz', ''),
(3, 'en', ''),
(3, 'ru', ''),
(3, 'uz', ''),
(4, 'en', ''),
(4, 'ru', ''),
(4, 'uz', ''),
(5, 'en', ''),
(5, 'ru', ''),
(5, 'uz', ''),
(6, 'en', ''),
(6, 'ru', ''),
(6, 'uz', ''),
(7, 'en', ''),
(7, 'ru', ''),
(7, 'uz', ''),
(8, 'en', ''),
(8, 'ru', ''),
(8, 'uz', ''),
(9, 'en', ''),
(9, 'ru', ''),
(9, 'uz', ''),
(10, 'en', ''),
(10, 'ru', ''),
(10, 'uz', ''),
(11, 'en', ''),
(11, 'ru', ''),
(11, 'uz', ''),
(12, 'en', ''),
(12, 'ru', ''),
(12, 'uz', ''),
(13, 'en', ''),
(13, 'ru', ''),
(13, 'uz', ''),
(14, 'en', ''),
(14, 'ru', ''),
(14, 'uz', ''),
(15, 'en', ''),
(15, 'ru', ''),
(15, 'uz', ''),
(16, 'en', ''),
(16, 'ru', ''),
(16, 'uz', ''),
(17, 'en', ''),
(17, 'ru', ''),
(17, 'uz', ''),
(18, 'en', ''),
(18, 'ru', ''),
(18, 'uz', ''),
(19, 'en', ''),
(19, 'ru', ''),
(19, 'uz', ''),
(20, 'en', ''),
(20, 'ru', ''),
(20, 'uz', ''),
(21, 'en', ''),
(21, 'ru', ''),
(21, 'uz', ''),
(22, 'en', ''),
(22, 'ru', ''),
(22, 'uz', ''),
(23, 'en', ''),
(23, 'ru', ''),
(23, 'uz', ''),
(24, 'en', ''),
(24, 'ru', ''),
(24, 'uz', ''),
(25, 'en', ''),
(25, 'ru', ''),
(25, 'uz', ''),
(26, 'en', ''),
(26, 'ru', ''),
(26, 'uz', ''),
(27, 'en', ''),
(27, 'ru', ''),
(27, 'uz', ''),
(28, 'en', ''),
(28, 'ru', ''),
(28, 'uz', ''),
(29, 'en', ''),
(29, 'ru', ''),
(29, 'uz', ''),
(30, 'en', ''),
(30, 'ru', ''),
(30, 'uz', ''),
(31, 'en', ''),
(31, 'ru', ''),
(31, 'uz', ''),
(32, 'en', ''),
(32, 'ru', ''),
(32, 'uz', ''),
(33, 'en', ''),
(33, 'ru', ''),
(33, 'uz', ''),
(34, 'en', ''),
(34, 'ru', ''),
(34, 'uz', ''),
(35, 'en', ''),
(35, 'ru', ''),
(35, 'uz', ''),
(36, 'en', ''),
(36, 'ru', ''),
(36, 'uz', ''),
(37, 'en', ''),
(37, 'ru', ''),
(37, 'uz', ''),
(38, 'en', ''),
(38, 'ru', ''),
(38, 'uz', ''),
(39, 'en', ''),
(39, 'ru', ''),
(39, 'uz', ''),
(40, 'en', ''),
(40, 'ru', ''),
(40, 'uz', ''),
(41, 'en', ''),
(41, 'ru', ''),
(41, 'uz', ''),
(42, 'en', ''),
(42, 'ru', ''),
(42, 'uz', ''),
(43, 'en', ''),
(43, 'ru', ''),
(43, 'uz', ''),
(44, 'en', ''),
(44, 'ru', ''),
(44, 'uz', ''),
(45, 'en', ''),
(45, 'ru', ''),
(45, 'uz', ''),
(46, 'en', ''),
(46, 'ru', ''),
(46, 'uz', ''),
(47, 'en', ''),
(47, 'ru', ''),
(47, 'uz', ''),
(48, 'en', ''),
(48, 'ru', ''),
(48, 'uz', ''),
(49, 'en', ''),
(49, 'ru', ''),
(49, 'uz', ''),
(50, 'en', ''),
(50, 'ru', ''),
(50, 'uz', ''),
(51, 'en', ''),
(51, 'ru', ''),
(51, 'uz', ''),
(52, 'en', ''),
(52, 'ru', ''),
(52, 'uz', ''),
(53, 'en', ''),
(53, 'ru', ''),
(53, 'uz', ''),
(54, 'en', ''),
(54, 'ru', ''),
(54, 'uz', ''),
(55, 'en', ''),
(55, 'ru', ''),
(55, 'uz', ''),
(56, 'en', ''),
(56, 'ru', ''),
(56, 'uz', ''),
(57, 'en', ''),
(57, 'ru', ''),
(57, 'uz', ''),
(58, 'en', ''),
(58, 'ru', ''),
(58, 'uz', ''),
(59, 'en', ''),
(59, 'ru', ''),
(59, 'uz', ''),
(60, 'en', ''),
(60, 'ru', ''),
(60, 'uz', ''),
(61, 'en', ''),
(61, 'ru', ''),
(61, 'uz', ''),
(62, 'en', ''),
(62, 'ru', ''),
(62, 'uz', ''),
(63, 'en', ''),
(63, 'ru', ''),
(63, 'uz', ''),
(64, 'en', ''),
(64, 'ru', ''),
(64, 'uz', ''),
(65, 'en', ''),
(65, 'ru', ''),
(65, 'uz', ''),
(66, 'en', ''),
(66, 'ru', ''),
(66, 'uz', ''),
(67, 'en', ''),
(67, 'ru', ''),
(67, 'uz', ''),
(68, 'en', ''),
(68, 'ru', ''),
(68, 'uz', ''),
(69, 'en', ''),
(69, 'ru', ''),
(69, 'uz', ''),
(70, 'en', ''),
(70, 'ru', ''),
(70, 'uz', ''),
(71, 'en', ''),
(71, 'ru', ''),
(71, 'uz', ''),
(72, 'en', ''),
(72, 'ru', ''),
(72, 'uz', ''),
(73, 'en', ''),
(73, 'ru', ''),
(73, 'uz', ''),
(74, 'en', ''),
(74, 'ru', ''),
(74, 'uz', ''),
(75, 'en', ''),
(75, 'ru', ''),
(75, 'uz', ''),
(76, 'en', ''),
(76, 'ru', ''),
(76, 'uz', ''),
(77, 'en', ''),
(77, 'ru', ''),
(77, 'uz', ''),
(78, 'en', ''),
(78, 'ru', ''),
(78, 'uz', ''),
(79, 'en', ''),
(79, 'ru', ''),
(79, 'uz', ''),
(80, 'en', ''),
(80, 'ru', ''),
(80, 'uz', ''),
(81, 'en', ''),
(81, 'ru', ''),
(81, 'uz', ''),
(82, 'en', ''),
(82, 'ru', ''),
(82, 'uz', ''),
(83, 'en', ''),
(83, 'ru', ''),
(83, 'uz', ''),
(84, 'en', ''),
(84, 'ru', ''),
(84, 'uz', ''),
(85, 'en', ''),
(85, 'ru', ''),
(85, 'uz', ''),
(86, 'en', ''),
(86, 'ru', ''),
(86, 'uz', ''),
(87, 'en', ''),
(87, 'ru', ''),
(87, 'uz', ''),
(88, 'en', ''),
(88, 'ru', ''),
(88, 'uz', ''),
(89, 'en', ''),
(89, 'ru', ''),
(89, 'uz', ''),
(90, 'en', ''),
(90, 'ru', ''),
(90, 'uz', ''),
(91, 'en', ''),
(91, 'ru', ''),
(91, 'uz', ''),
(92, 'en', ''),
(92, 'ru', ''),
(92, 'uz', ''),
(93, 'en', ''),
(93, 'ru', ''),
(93, 'uz', ''),
(94, 'en', ''),
(94, 'ru', ''),
(94, 'uz', ''),
(95, 'en', ''),
(95, 'ru', ''),
(95, 'uz', ''),
(96, 'en', ''),
(96, 'ru', ''),
(96, 'uz', ''),
(97, 'en', ''),
(97, 'ru', ''),
(97, 'uz', ''),
(98, 'en', ''),
(98, 'ru', ''),
(98, 'uz', ''),
(99, 'en', ''),
(99, 'ru', ''),
(99, 'uz', ''),
(100, 'en', ''),
(100, 'ru', ''),
(100, 'uz', ''),
(101, 'en', ''),
(101, 'ru', ''),
(101, 'uz', ''),
(102, 'en', ''),
(102, 'ru', ''),
(102, 'uz', ''),
(103, 'en', ''),
(103, 'ru', ''),
(103, 'uz', ''),
(104, 'en', ''),
(104, 'ru', ''),
(104, 'uz', ''),
(105, 'en', ''),
(105, 'ru', ''),
(105, 'uz', ''),
(106, 'en', ''),
(106, 'ru', ''),
(106, 'uz', ''),
(107, 'en', ''),
(107, 'ru', ''),
(107, 'uz', ''),
(108, 'en', ''),
(108, 'ru', ''),
(108, 'uz', ''),
(109, 'en', ''),
(109, 'ru', ''),
(109, 'uz', ''),
(110, 'en', ''),
(110, 'ru', ''),
(110, 'uz', ''),
(111, 'en', ''),
(111, 'ru', ''),
(111, 'uz', ''),
(112, 'en', ''),
(112, 'ru', ''),
(112, 'uz', ''),
(113, 'en', ''),
(113, 'ru', ''),
(113, 'uz', ''),
(114, 'en', ''),
(114, 'ru', ''),
(114, 'uz', ''),
(115, 'en', ''),
(115, 'ru', ''),
(115, 'uz', ''),
(116, 'en', ''),
(116, 'ru', ''),
(116, 'uz', ''),
(117, 'en', ''),
(117, 'ru', ''),
(117, 'uz', ''),
(118, 'en', ''),
(118, 'ru', ''),
(118, 'uz', ''),
(119, 'en', ''),
(119, 'ru', ''),
(119, 'uz', ''),
(120, 'en', ''),
(120, 'ru', ''),
(120, 'uz', ''),
(121, 'en', ''),
(121, 'ru', ''),
(121, 'uz', ''),
(122, 'en', ''),
(122, 'ru', ''),
(122, 'uz', ''),
(123, 'en', ''),
(123, 'ru', ''),
(123, 'uz', ''),
(124, 'en', ''),
(124, 'ru', ''),
(124, 'uz', ''),
(125, 'en', ''),
(125, 'ru', ''),
(125, 'uz', ''),
(126, 'en', ''),
(126, 'ru', ''),
(126, 'uz', ''),
(127, 'en', ''),
(127, 'ru', ''),
(127, 'uz', ''),
(128, 'en', ''),
(128, 'ru', ''),
(128, 'uz', ''),
(129, 'en', ''),
(129, 'ru', ''),
(129, 'uz', ''),
(130, 'en', ''),
(130, 'ru', ''),
(130, 'uz', ''),
(131, 'en', ''),
(131, 'ru', ''),
(131, 'uz', ''),
(132, 'en', ''),
(132, 'ru', ''),
(132, 'uz', ''),
(133, 'en', ''),
(133, 'ru', ''),
(133, 'uz', ''),
(134, 'en', ''),
(134, 'ru', ''),
(134, 'uz', ''),
(135, 'en', ''),
(135, 'ru', ''),
(135, 'uz', ''),
(136, 'en', ''),
(136, 'ru', ''),
(136, 'uz', ''),
(137, 'en', ''),
(137, 'ru', ''),
(137, 'uz', ''),
(138, 'en', ''),
(138, 'ru', ''),
(138, 'uz', ''),
(139, 'en', ''),
(139, 'ru', ''),
(139, 'uz', ''),
(140, 'en', ''),
(140, 'ru', ''),
(140, 'uz', ''),
(141, 'en', ''),
(141, 'ru', ''),
(141, 'uz', ''),
(142, 'en', ''),
(142, 'ru', ''),
(142, 'uz', ''),
(143, 'en', ''),
(143, 'ru', ''),
(143, 'uz', ''),
(144, 'en', ''),
(144, 'ru', ''),
(144, 'uz', ''),
(145, 'en', ''),
(145, 'ru', ''),
(145, 'uz', ''),
(146, 'en', ''),
(146, 'ru', ''),
(146, 'uz', ''),
(147, 'en', ''),
(147, 'ru', ''),
(147, 'uz', ''),
(148, 'en', ''),
(148, 'ru', ''),
(148, 'uz', ''),
(149, 'en', ''),
(149, 'ru', ''),
(149, 'uz', ''),
(150, 'en', ''),
(150, 'ru', ''),
(150, 'uz', ''),
(151, 'en', ''),
(151, 'ru', ''),
(151, 'uz', ''),
(152, 'en', ''),
(152, 'ru', ''),
(152, 'uz', ''),
(153, 'en', ''),
(153, 'ru', ''),
(153, 'uz', ''),
(154, 'en', ''),
(154, 'ru', ''),
(154, 'uz', ''),
(155, 'en', ''),
(155, 'ru', ''),
(155, 'uz', ''),
(156, 'en', ''),
(156, 'ru', ''),
(156, 'uz', ''),
(157, 'en', ''),
(157, 'ru', ''),
(157, 'uz', ''),
(158, 'en', ''),
(158, 'ru', ''),
(158, 'uz', ''),
(159, 'en', ''),
(159, 'ru', ''),
(159, 'uz', ''),
(160, 'en', ''),
(160, 'ru', ''),
(160, 'uz', ''),
(161, 'en', ''),
(161, 'ru', ''),
(161, 'uz', ''),
(162, 'en', ''),
(162, 'ru', ''),
(162, 'uz', ''),
(163, 'en', ''),
(163, 'ru', ''),
(163, 'uz', ''),
(164, 'en', ''),
(164, 'ru', ''),
(164, 'uz', ''),
(165, 'en', ''),
(165, 'ru', ''),
(165, 'uz', ''),
(166, 'en', ''),
(166, 'ru', ''),
(166, 'uz', ''),
(167, 'en', ''),
(167, 'ru', ''),
(167, 'uz', ''),
(168, 'en', ''),
(168, 'ru', ''),
(168, 'uz', ''),
(169, 'en', ''),
(169, 'ru', ''),
(169, 'uz', ''),
(170, 'en', ''),
(170, 'ru', ''),
(170, 'uz', ''),
(171, 'en', ''),
(171, 'ru', ''),
(171, 'uz', ''),
(172, 'en', ''),
(172, 'ru', ''),
(172, 'uz', ''),
(173, 'en', ''),
(173, 'ru', ''),
(173, 'uz', ''),
(174, 'en', ''),
(174, 'ru', ''),
(174, 'uz', ''),
(175, 'en', ''),
(175, 'ru', ''),
(175, 'uz', ''),
(176, 'en', ''),
(176, 'ru', ''),
(176, 'uz', ''),
(177, 'en', ''),
(177, 'ru', ''),
(177, 'uz', ''),
(178, 'en', ''),
(178, 'ru', ''),
(178, 'uz', ''),
(179, 'en', ''),
(179, 'ru', ''),
(179, 'uz', ''),
(180, 'en', ''),
(180, 'ru', ''),
(180, 'uz', ''),
(181, 'en', ''),
(181, 'ru', ''),
(181, 'uz', ''),
(182, 'en', ''),
(182, 'ru', ''),
(182, 'uz', ''),
(183, 'en', ''),
(183, 'ru', ''),
(183, 'uz', ''),
(184, 'en', ''),
(184, 'ru', ''),
(184, 'uz', ''),
(185, 'en', ''),
(185, 'ru', ''),
(185, 'uz', ''),
(186, 'en', ''),
(186, 'ru', ''),
(186, 'uz', ''),
(187, 'en', ''),
(187, 'ru', ''),
(187, 'uz', ''),
(188, 'en', ''),
(188, 'ru', ''),
(188, 'uz', ''),
(189, 'en', ''),
(189, 'ru', ''),
(189, 'uz', ''),
(190, 'en', ''),
(190, 'ru', ''),
(190, 'uz', ''),
(191, 'en', ''),
(191, 'ru', ''),
(191, 'uz', ''),
(192, 'en', ''),
(192, 'ru', ''),
(192, 'uz', ''),
(193, 'en', ''),
(193, 'ru', ''),
(193, 'uz', ''),
(194, 'en', ''),
(194, 'ru', ''),
(194, 'uz', ''),
(195, 'en', ''),
(195, 'ru', ''),
(195, 'uz', ''),
(196, 'en', ''),
(196, 'ru', ''),
(196, 'uz', ''),
(197, 'en', ''),
(197, 'ru', ''),
(197, 'uz', ''),
(198, 'en', ''),
(198, 'ru', ''),
(198, 'uz', ''),
(199, 'en', ''),
(199, 'ru', ''),
(199, 'uz', ''),
(200, 'en', ''),
(200, 'ru', ''),
(200, 'uz', ''),
(201, 'en', ''),
(201, 'ru', ''),
(201, 'uz', ''),
(202, 'en', ''),
(202, 'ru', ''),
(202, 'uz', ''),
(203, 'en', ''),
(203, 'ru', ''),
(203, 'uz', ''),
(204, 'en', ''),
(204, 'ru', ''),
(204, 'uz', ''),
(205, 'en', ''),
(205, 'ru', ''),
(205, 'uz', ''),
(206, 'en', ''),
(206, 'ru', ''),
(206, 'uz', ''),
(207, 'en', ''),
(207, 'ru', ''),
(207, 'uz', ''),
(208, 'en', ''),
(208, 'ru', ''),
(208, 'uz', ''),
(209, 'en', ''),
(209, 'ru', ''),
(209, 'uz', ''),
(210, 'en', ''),
(210, 'ru', ''),
(210, 'uz', ''),
(211, 'en', ''),
(211, 'ru', ''),
(211, 'uz', ''),
(212, 'en', ''),
(212, 'ru', ''),
(212, 'uz', ''),
(213, 'en', ''),
(213, 'ru', ''),
(213, 'uz', ''),
(214, 'en', ''),
(214, 'ru', ''),
(214, 'uz', ''),
(215, 'en', ''),
(215, 'ru', ''),
(215, 'uz', ''),
(216, 'en', ''),
(216, 'ru', ''),
(216, 'uz', ''),
(217, 'en', ''),
(217, 'ru', ''),
(217, 'uz', ''),
(218, 'en', ''),
(218, 'ru', ''),
(218, 'uz', ''),
(219, 'en', ''),
(219, 'ru', ''),
(219, 'uz', ''),
(220, 'en', ''),
(220, 'ru', ''),
(220, 'uz', ''),
(221, 'en', ''),
(221, 'ru', ''),
(221, 'uz', ''),
(222, 'en', ''),
(222, 'ru', ''),
(222, 'uz', ''),
(223, 'en', ''),
(223, 'ru', ''),
(223, 'uz', ''),
(224, 'en', ''),
(224, 'ru', ''),
(224, 'uz', ''),
(225, 'en', ''),
(225, 'ru', ''),
(225, 'uz', ''),
(226, 'en', ''),
(226, 'ru', ''),
(226, 'uz', ''),
(227, 'en', ''),
(227, 'ru', ''),
(227, 'uz', ''),
(228, 'en', ''),
(228, 'ru', ''),
(228, 'uz', ''),
(229, 'en', ''),
(229, 'ru', ''),
(229, 'uz', ''),
(230, 'en', ''),
(230, 'ru', ''),
(230, 'uz', ''),
(231, 'en', ''),
(231, 'ru', ''),
(231, 'uz', ''),
(232, 'en', ''),
(232, 'ru', ''),
(232, 'uz', ''),
(233, 'en', ''),
(233, 'ru', ''),
(233, 'uz', ''),
(234, 'en', ''),
(234, 'ru', ''),
(234, 'uz', ''),
(235, 'en', ''),
(235, 'ru', ''),
(235, 'uz', ''),
(236, 'en', ''),
(236, 'ru', ''),
(236, 'uz', ''),
(237, 'en', ''),
(237, 'ru', ''),
(237, 'uz', ''),
(238, 'en', ''),
(238, 'ru', ''),
(238, 'uz', ''),
(239, 'en', ''),
(239, 'ru', ''),
(239, 'uz', ''),
(240, 'en', ''),
(240, 'ru', ''),
(240, 'uz', ''),
(241, 'en', ''),
(241, 'ru', ''),
(241, 'uz', ''),
(242, 'en', ''),
(242, 'ru', ''),
(242, 'uz', ''),
(243, 'en', ''),
(243, 'ru', ''),
(243, 'uz', ''),
(244, 'en', ''),
(244, 'ru', ''),
(244, 'uz', ''),
(245, 'en', ''),
(245, 'ru', ''),
(245, 'uz', ''),
(246, 'en', ''),
(246, 'ru', ''),
(246, 'uz', ''),
(247, 'en', ''),
(247, 'ru', ''),
(247, 'uz', ''),
(248, 'en', ''),
(248, 'ru', ''),
(248, 'uz', ''),
(249, 'en', ''),
(249, 'ru', ''),
(249, 'uz', ''),
(250, 'en', ''),
(250, 'ru', ''),
(250, 'uz', ''),
(251, 'en', ''),
(251, 'ru', ''),
(251, 'uz', ''),
(252, 'en', ''),
(252, 'ru', ''),
(252, 'uz', ''),
(253, 'en', ''),
(253, 'ru', ''),
(253, 'uz', ''),
(254, 'en', ''),
(254, 'ru', ''),
(254, 'uz', ''),
(255, 'en', ''),
(255, 'ru', ''),
(255, 'uz', ''),
(256, 'en', ''),
(256, 'ru', ''),
(256, 'uz', ''),
(257, 'en', ''),
(257, 'ru', ''),
(257, 'uz', ''),
(258, 'en', ''),
(258, 'ru', ''),
(258, 'uz', ''),
(259, 'en', ''),
(259, 'ru', ''),
(259, 'uz', ''),
(260, 'en', ''),
(260, 'ru', ''),
(260, 'uz', ''),
(261, 'en', ''),
(261, 'ru', ''),
(261, 'uz', ''),
(262, 'en', ''),
(262, 'ru', ''),
(262, 'uz', ''),
(263, 'en', ''),
(263, 'ru', ''),
(263, 'uz', ''),
(264, 'en', ''),
(264, 'ru', ''),
(264, 'uz', ''),
(265, 'en', ''),
(265, 'ru', ''),
(265, 'uz', ''),
(266, 'en', ''),
(266, 'ru', ''),
(266, 'uz', ''),
(267, 'en', ''),
(267, 'ru', ''),
(267, 'uz', ''),
(268, 'en', ''),
(268, 'ru', ''),
(268, 'uz', ''),
(269, 'en', ''),
(269, 'ru', ''),
(269, 'uz', ''),
(270, 'en', ''),
(270, 'ru', ''),
(270, 'uz', ''),
(271, 'en', ''),
(271, 'ru', ''),
(271, 'uz', ''),
(272, 'en', ''),
(272, 'ru', ''),
(272, 'uz', ''),
(273, 'en', ''),
(273, 'ru', ''),
(273, 'uz', ''),
(274, 'en', ''),
(274, 'ru', ''),
(274, 'uz', ''),
(275, 'en', ''),
(275, 'ru', ''),
(275, 'uz', ''),
(276, 'en', ''),
(276, 'ru', ''),
(276, 'uz', ''),
(277, 'en', ''),
(277, 'ru', ''),
(277, 'uz', ''),
(278, 'en', ''),
(278, 'ru', ''),
(278, 'uz', ''),
(279, 'en', ''),
(279, 'ru', ''),
(279, 'uz', ''),
(280, 'en', ''),
(280, 'ru', ''),
(280, 'uz', ''),
(281, 'en', ''),
(281, 'ru', ''),
(281, 'uz', ''),
(282, 'en', ''),
(282, 'ru', ''),
(282, 'uz', ''),
(283, 'en', ''),
(283, 'ru', ''),
(283, 'uz', ''),
(284, 'en', ''),
(284, 'ru', ''),
(284, 'uz', ''),
(285, 'en', ''),
(285, 'ru', ''),
(285, 'uz', ''),
(286, 'en', ''),
(286, 'ru', ''),
(286, 'uz', ''),
(287, 'en', ''),
(287, 'ru', ''),
(287, 'uz', ''),
(288, 'en', ''),
(288, 'ru', ''),
(288, 'uz', ''),
(289, 'en', ''),
(289, 'ru', ''),
(289, 'uz', ''),
(290, 'en', ''),
(290, 'ru', ''),
(290, 'uz', ''),
(291, 'en', ''),
(291, 'ru', ''),
(291, 'uz', ''),
(292, 'en', ''),
(292, 'ru', ''),
(292, 'uz', ''),
(293, 'en', ''),
(293, 'ru', ''),
(293, 'uz', ''),
(294, 'en', ''),
(294, 'ru', ''),
(294, 'uz', ''),
(295, 'en', ''),
(295, 'ru', ''),
(295, 'uz', ''),
(296, 'en', ''),
(296, 'ru', ''),
(296, 'uz', ''),
(297, 'en', ''),
(297, 'ru', ''),
(297, 'uz', ''),
(298, 'en', ''),
(298, 'ru', ''),
(298, 'uz', ''),
(299, 'en', ''),
(299, 'ru', ''),
(299, 'uz', ''),
(300, 'en', ''),
(300, 'ru', ''),
(300, 'uz', ''),
(301, 'en', ''),
(301, 'ru', ''),
(301, 'uz', ''),
(302, 'en', ''),
(302, 'ru', ''),
(302, 'uz', ''),
(303, 'en', ''),
(303, 'ru', ''),
(303, 'uz', ''),
(304, 'en', ''),
(304, 'ru', ''),
(304, 'uz', ''),
(305, 'en', ''),
(305, 'ru', ''),
(305, 'uz', ''),
(306, 'en', ''),
(306, 'ru', ''),
(306, 'uz', ''),
(307, 'en', ''),
(307, 'ru', ''),
(307, 'uz', ''),
(308, 'en', ''),
(308, 'ru', ''),
(308, 'uz', ''),
(309, 'en', ''),
(309, 'ru', ''),
(309, 'uz', ''),
(310, 'en', ''),
(310, 'ru', ''),
(310, 'uz', ''),
(311, 'en', ''),
(311, 'ru', ''),
(311, 'uz', ''),
(312, 'en', ''),
(312, 'ru', ''),
(312, 'uz', ''),
(313, 'en', ''),
(313, 'ru', ''),
(313, 'uz', ''),
(314, 'en', ''),
(314, 'ru', ''),
(314, 'uz', ''),
(315, 'en', ''),
(315, 'ru', ''),
(315, 'uz', ''),
(316, 'en', ''),
(316, 'ru', ''),
(316, 'uz', ''),
(317, 'en', ''),
(317, 'ru', ''),
(317, 'uz', ''),
(318, 'en', ''),
(318, 'ru', ''),
(318, 'uz', ''),
(319, 'en', ''),
(319, 'ru', ''),
(319, 'uz', ''),
(320, 'en', ''),
(320, 'ru', ''),
(320, 'uz', ''),
(321, 'en', ''),
(321, 'ru', ''),
(321, 'uz', ''),
(322, 'en', ''),
(322, 'ru', ''),
(322, 'uz', ''),
(323, 'en', ''),
(323, 'ru', ''),
(323, 'uz', ''),
(324, 'en', ''),
(324, 'ru', ''),
(324, 'uz', ''),
(325, 'en', ''),
(325, 'ru', ''),
(325, 'uz', ''),
(326, 'en', ''),
(326, 'ru', ''),
(326, 'uz', ''),
(327, 'en', ''),
(327, 'ru', ''),
(327, 'uz', ''),
(328, 'en', ''),
(328, 'ru', ''),
(328, 'uz', ''),
(329, 'en', ''),
(329, 'ru', ''),
(329, 'uz', ''),
(330, 'en', ''),
(330, 'ru', ''),
(330, 'uz', ''),
(331, 'en', ''),
(331, 'ru', ''),
(331, 'uz', ''),
(332, 'en', ''),
(332, 'ru', ''),
(332, 'uz', ''),
(333, 'en', ''),
(333, 'ru', ''),
(333, 'uz', ''),
(334, 'en', ''),
(334, 'ru', ''),
(334, 'uz', ''),
(335, 'en', ''),
(335, 'ru', ''),
(335, 'uz', ''),
(336, 'en', ''),
(336, 'ru', ''),
(336, 'uz', ''),
(337, 'en', ''),
(337, 'ru', ''),
(337, 'uz', ''),
(338, 'en', ''),
(338, 'ru', ''),
(338, 'uz', ''),
(339, 'en', ''),
(339, 'ru', ''),
(339, 'uz', ''),
(340, 'en', ''),
(340, 'ru', ''),
(340, 'uz', ''),
(341, 'en', ''),
(341, 'ru', ''),
(341, 'uz', ''),
(342, 'en', ''),
(342, 'ru', ''),
(342, 'uz', ''),
(343, 'en', ''),
(343, 'ru', ''),
(343, 'uz', ''),
(344, 'en', ''),
(344, 'ru', ''),
(344, 'uz', ''),
(345, 'en', ''),
(345, 'ru', ''),
(345, 'uz', ''),
(346, 'en', ''),
(346, 'ru', ''),
(346, 'uz', ''),
(347, 'en', ''),
(347, 'ru', ''),
(347, 'uz', ''),
(348, 'en', ''),
(348, 'ru', ''),
(348, 'uz', ''),
(349, 'en', ''),
(349, 'ru', ''),
(349, 'uz', ''),
(350, 'en', ''),
(350, 'ru', ''),
(350, 'uz', ''),
(351, 'en', ''),
(351, 'ru', ''),
(351, 'uz', ''),
(352, 'en', ''),
(352, 'ru', ''),
(352, 'uz', ''),
(353, 'en', ''),
(353, 'ru', ''),
(353, 'uz', ''),
(354, 'en', ''),
(354, 'ru', ''),
(354, 'uz', ''),
(355, 'en', ''),
(355, 'ru', ''),
(355, 'uz', ''),
(356, 'en', ''),
(356, 'ru', ''),
(356, 'uz', ''),
(357, 'en', ''),
(357, 'ru', ''),
(357, 'uz', ''),
(358, 'en', ''),
(358, 'ru', ''),
(358, 'uz', ''),
(359, 'en', ''),
(359, 'ru', ''),
(359, 'uz', ''),
(360, 'en', ''),
(360, 'ru', ''),
(360, 'uz', ''),
(361, 'en', ''),
(361, 'ru', ''),
(361, 'uz', ''),
(362, 'en', ''),
(362, 'ru', ''),
(362, 'uz', ''),
(363, 'en', ''),
(363, 'ru', ''),
(363, 'uz', ''),
(364, 'en', ''),
(364, 'ru', ''),
(364, 'uz', ''),
(365, 'en', ''),
(365, 'ru', ''),
(365, 'uz', ''),
(366, 'en', ''),
(366, 'ru', ''),
(366, 'uz', ''),
(367, 'en', ''),
(367, 'ru', ''),
(367, 'uz', ''),
(368, 'en', ''),
(368, 'ru', ''),
(368, 'uz', ''),
(369, 'en', ''),
(369, 'ru', ''),
(369, 'uz', ''),
(370, 'en', ''),
(370, 'ru', ''),
(370, 'uz', ''),
(371, 'en', ''),
(371, 'ru', ''),
(371, 'uz', ''),
(372, 'en', ''),
(372, 'ru', ''),
(372, 'uz', ''),
(373, 'en', ''),
(373, 'ru', ''),
(373, 'uz', ''),
(374, 'en', ''),
(374, 'ru', ''),
(374, 'uz', ''),
(375, 'en', ''),
(375, 'ru', ''),
(375, 'uz', ''),
(376, 'en', ''),
(376, 'ru', ''),
(376, 'uz', ''),
(377, 'en', ''),
(377, 'ru', ''),
(377, 'uz', ''),
(378, 'en', ''),
(378, 'ru', ''),
(378, 'uz', ''),
(379, 'en', ''),
(379, 'ru', ''),
(379, 'uz', ''),
(380, 'en', ''),
(380, 'ru', ''),
(380, 'uz', ''),
(381, 'en', ''),
(381, 'ru', ''),
(381, 'uz', ''),
(382, 'en', ''),
(382, 'ru', ''),
(382, 'uz', ''),
(383, 'en', ''),
(383, 'ru', ''),
(383, 'uz', ''),
(384, 'en', ''),
(384, 'ru', ''),
(384, 'uz', ''),
(385, 'en', ''),
(385, 'ru', ''),
(385, 'uz', ''),
(386, 'en', ''),
(386, 'ru', ''),
(386, 'uz', ''),
(387, 'en', ''),
(387, 'ru', ''),
(387, 'uz', ''),
(388, 'en', ''),
(388, 'ru', ''),
(388, 'uz', ''),
(389, 'en', ''),
(389, 'ru', ''),
(389, 'uz', ''),
(390, 'en', ''),
(390, 'ru', ''),
(390, 'uz', ''),
(391, 'en', ''),
(391, 'ru', ''),
(391, 'uz', ''),
(392, 'en', ''),
(392, 'ru', ''),
(392, 'uz', ''),
(393, 'en', ''),
(393, 'ru', ''),
(393, 'uz', ''),
(394, 'en', ''),
(394, 'ru', ''),
(394, 'uz', ''),
(395, 'en', ''),
(395, 'ru', ''),
(395, 'uz', ''),
(396, 'en', ''),
(396, 'ru', ''),
(396, 'uz', ''),
(397, 'en', ''),
(397, 'ru', ''),
(397, 'uz', ''),
(398, 'en', ''),
(398, 'ru', ''),
(398, 'uz', ''),
(399, 'en', ''),
(399, 'ru', ''),
(399, 'uz', ''),
(400, 'en', ''),
(400, 'ru', ''),
(400, 'uz', ''),
(401, 'en', ''),
(401, 'ru', ''),
(401, 'uz', ''),
(402, 'en', ''),
(402, 'ru', ''),
(402, 'uz', ''),
(403, 'en', ''),
(403, 'ru', ''),
(403, 'uz', ''),
(404, 'en', ''),
(404, 'ru', ''),
(404, 'uz', ''),
(405, 'en', ''),
(405, 'ru', ''),
(405, 'uz', ''),
(406, 'en', ''),
(406, 'ru', ''),
(406, 'uz', ''),
(407, 'en', ''),
(407, 'ru', ''),
(407, 'uz', ''),
(408, 'en', ''),
(408, 'ru', ''),
(408, 'uz', ''),
(409, 'en', ''),
(409, 'ru', ''),
(409, 'uz', ''),
(410, 'en', ''),
(410, 'ru', ''),
(410, 'uz', ''),
(411, 'en', ''),
(411, 'ru', ''),
(411, 'uz', ''),
(412, 'en', ''),
(412, 'ru', ''),
(412, 'uz', ''),
(413, 'en', ''),
(413, 'ru', ''),
(413, 'uz', ''),
(414, 'en', ''),
(414, 'ru', ''),
(414, 'uz', ''),
(415, 'en', ''),
(415, 'ru', ''),
(415, 'uz', ''),
(416, 'en', ''),
(416, 'ru', ''),
(416, 'uz', ''),
(417, 'en', ''),
(417, 'ru', ''),
(417, 'uz', ''),
(418, 'en', ''),
(418, 'ru', ''),
(418, 'uz', ''),
(419, 'en', ''),
(419, 'ru', ''),
(419, 'uz', ''),
(420, 'en', ''),
(420, 'ru', ''),
(420, 'uz', ''),
(421, 'en', ''),
(421, 'ru', ''),
(421, 'uz', ''),
(422, 'en', ''),
(422, 'ru', ''),
(422, 'uz', ''),
(423, 'en', ''),
(423, 'ru', ''),
(423, 'uz', ''),
(424, 'en', ''),
(424, 'ru', ''),
(424, 'uz', ''),
(425, 'en', ''),
(425, 'ru', ''),
(425, 'uz', ''),
(426, 'en', ''),
(426, 'ru', ''),
(426, 'uz', ''),
(427, 'en', ''),
(427, 'ru', ''),
(427, 'uz', ''),
(428, 'en', ''),
(428, 'ru', ''),
(428, 'uz', ''),
(429, 'en', ''),
(429, 'ru', ''),
(429, 'uz', ''),
(430, 'en', ''),
(430, 'ru', ''),
(430, 'uz', ''),
(431, 'en', ''),
(431, 'ru', ''),
(431, 'uz', ''),
(432, 'en', ''),
(432, 'ru', ''),
(432, 'uz', ''),
(433, 'en', ''),
(433, 'ru', ''),
(433, 'uz', ''),
(434, 'en', ''),
(434, 'ru', ''),
(434, 'uz', ''),
(435, 'en', ''),
(435, 'ru', ''),
(435, 'uz', ''),
(436, 'en', ''),
(436, 'ru', ''),
(436, 'uz', ''),
(437, 'en', ''),
(437, 'ru', ''),
(437, 'uz', ''),
(438, 'en', ''),
(438, 'ru', ''),
(438, 'uz', ''),
(439, 'en', ''),
(439, 'ru', ''),
(439, 'uz', ''),
(440, 'en', ''),
(440, 'ru', ''),
(440, 'uz', ''),
(441, 'en', ''),
(441, 'ru', ''),
(441, 'uz', ''),
(442, 'en', ''),
(442, 'ru', ''),
(442, 'uz', ''),
(443, 'en', ''),
(443, 'ru', ''),
(443, 'uz', ''),
(444, 'en', ''),
(444, 'ru', ''),
(444, 'uz', ''),
(445, 'en', ''),
(445, 'ru', ''),
(445, 'uz', ''),
(446, 'en', ''),
(446, 'ru', ''),
(446, 'uz', ''),
(447, 'en', ''),
(447, 'ru', ''),
(447, 'uz', ''),
(448, 'en', ''),
(448, 'ru', ''),
(448, 'uz', ''),
(449, 'en', ''),
(449, 'ru', ''),
(449, 'uz', ''),
(450, 'en', ''),
(450, 'ru', ''),
(450, 'uz', ''),
(451, 'en', ''),
(451, 'ru', ''),
(451, 'uz', ''),
(452, 'en', ''),
(452, 'ru', ''),
(452, 'uz', ''),
(453, 'en', ''),
(453, 'ru', ''),
(453, 'uz', ''),
(454, 'en', ''),
(454, 'ru', ''),
(454, 'uz', ''),
(455, 'en', ''),
(455, 'ru', ''),
(455, 'uz', ''),
(456, 'en', ''),
(456, 'ru', ''),
(456, 'uz', ''),
(457, 'en', ''),
(457, 'ru', ''),
(457, 'uz', ''),
(458, 'en', ''),
(458, 'ru', ''),
(458, 'uz', ''),
(459, 'en', ''),
(459, 'ru', ''),
(459, 'uz', ''),
(460, 'en', ''),
(460, 'ru', ''),
(460, 'uz', ''),
(461, 'en', ''),
(461, 'ru', ''),
(461, 'uz', ''),
(462, 'en', ''),
(462, 'ru', ''),
(462, 'uz', ''),
(463, 'en', ''),
(463, 'ru', ''),
(463, 'uz', ''),
(464, 'en', ''),
(464, 'ru', ''),
(464, 'uz', ''),
(465, 'en', ''),
(465, 'ru', ''),
(465, 'uz', ''),
(466, 'en', ''),
(466, 'ru', ''),
(466, 'uz', ''),
(467, 'en', ''),
(467, 'ru', ''),
(467, 'uz', ''),
(468, 'en', ''),
(468, 'ru', ''),
(468, 'uz', ''),
(469, 'en', ''),
(469, 'ru', ''),
(469, 'uz', ''),
(470, 'en', ''),
(470, 'ru', ''),
(470, 'uz', ''),
(471, 'en', ''),
(471, 'ru', ''),
(471, 'uz', ''),
(472, 'en', ''),
(472, 'ru', ''),
(472, 'uz', ''),
(473, 'en', ''),
(473, 'ru', ''),
(473, 'uz', ''),
(474, 'en', ''),
(474, 'ru', ''),
(474, 'uz', ''),
(475, 'en', ''),
(475, 'ru', ''),
(475, 'uz', ''),
(476, 'en', ''),
(476, 'ru', ''),
(476, 'uz', ''),
(477, 'en', ''),
(477, 'ru', ''),
(477, 'uz', ''),
(478, 'en', ''),
(478, 'ru', ''),
(478, 'uz', ''),
(479, 'en', ''),
(479, 'ru', ''),
(479, 'uz', ''),
(480, 'en', ''),
(480, 'ru', ''),
(480, 'uz', ''),
(481, 'en', ''),
(481, 'ru', ''),
(481, 'uz', ''),
(482, 'en', ''),
(482, 'ru', ''),
(482, 'uz', ''),
(483, 'en', ''),
(483, 'ru', ''),
(483, 'uz', ''),
(484, 'en', ''),
(484, 'ru', ''),
(484, 'uz', ''),
(485, 'en', ''),
(485, 'ru', ''),
(485, 'uz', ''),
(486, 'en', ''),
(486, 'ru', ''),
(486, 'uz', ''),
(487, 'en', ''),
(487, 'ru', ''),
(487, 'uz', ''),
(488, 'en', ''),
(488, 'ru', ''),
(488, 'uz', ''),
(489, 'en', ''),
(489, 'ru', ''),
(489, 'uz', ''),
(490, 'en', ''),
(490, 'ru', ''),
(490, 'uz', ''),
(491, 'en', ''),
(491, 'ru', ''),
(491, 'uz', ''),
(492, 'en', ''),
(492, 'ru', ''),
(492, 'uz', ''),
(493, 'en', ''),
(493, 'ru', ''),
(493, 'uz', ''),
(494, 'en', ''),
(494, 'ru', ''),
(494, 'uz', ''),
(495, 'en', ''),
(495, 'ru', ''),
(495, 'uz', ''),
(496, 'en', ''),
(496, 'ru', ''),
(496, 'uz', ''),
(497, 'en', ''),
(497, 'ru', ''),
(497, 'uz', ''),
(498, 'en', ''),
(498, 'ru', ''),
(498, 'uz', ''),
(499, 'en', ''),
(499, 'ru', ''),
(499, 'uz', ''),
(500, 'en', ''),
(500, 'ru', ''),
(500, 'uz', ''),
(501, 'en', ''),
(501, 'ru', ''),
(501, 'uz', ''),
(502, 'en', ''),
(502, 'ru', ''),
(502, 'uz', ''),
(503, 'en', ''),
(503, 'ru', ''),
(503, 'uz', ''),
(504, 'en', ''),
(504, 'ru', ''),
(504, 'uz', ''),
(505, 'en', ''),
(505, 'ru', ''),
(505, 'uz', ''),
(506, 'en', ''),
(506, 'ru', ''),
(506, 'uz', ''),
(507, 'en', ''),
(507, 'ru', ''),
(507, 'uz', ''),
(508, 'en', ''),
(508, 'ru', ''),
(508, 'uz', ''),
(509, 'en', ''),
(509, 'ru', ''),
(509, 'uz', ''),
(510, 'en', ''),
(510, 'ru', ''),
(510, 'uz', ''),
(511, 'en', ''),
(511, 'ru', ''),
(511, 'uz', ''),
(512, 'en', ''),
(512, 'ru', ''),
(512, 'uz', ''),
(513, 'en', ''),
(513, 'ru', ''),
(513, 'uz', ''),
(514, 'en', ''),
(514, 'ru', ''),
(514, 'uz', ''),
(515, 'en', ''),
(515, 'ru', ''),
(515, 'uz', ''),
(516, 'en', ''),
(516, 'ru', ''),
(516, 'uz', ''),
(517, 'en', ''),
(517, 'ru', ''),
(517, 'uz', ''),
(518, 'en', ''),
(518, 'ru', ''),
(518, 'uz', ''),
(519, 'en', ''),
(519, 'ru', ''),
(519, 'uz', ''),
(520, 'en', ''),
(520, 'ru', ''),
(520, 'uz', ''),
(521, 'en', ''),
(521, 'ru', ''),
(521, 'uz', ''),
(522, 'en', ''),
(522, 'ru', ''),
(522, 'uz', ''),
(523, 'en', ''),
(523, 'ru', ''),
(523, 'uz', ''),
(524, 'en', ''),
(524, 'ru', ''),
(524, 'uz', ''),
(525, 'en', ''),
(525, 'ru', ''),
(525, 'uz', ''),
(526, 'en', ''),
(526, 'ru', ''),
(526, 'uz', ''),
(527, 'en', ''),
(527, 'ru', ''),
(527, 'uz', ''),
(528, 'en', ''),
(528, 'ru', ''),
(528, 'uz', ''),
(529, 'en', ''),
(529, 'ru', ''),
(529, 'uz', ''),
(530, 'en', ''),
(530, 'ru', ''),
(530, 'uz', ''),
(531, 'en', ''),
(531, 'ru', ''),
(531, 'uz', ''),
(532, 'en', ''),
(532, 'ru', ''),
(532, 'uz', ''),
(533, 'en', ''),
(533, 'ru', ''),
(533, 'uz', ''),
(534, 'en', ''),
(534, 'ru', ''),
(534, 'uz', ''),
(535, 'en', ''),
(535, 'ru', ''),
(535, 'uz', ''),
(536, 'en', ''),
(536, 'ru', ''),
(536, 'uz', ''),
(537, 'en', ''),
(537, 'ru', ''),
(537, 'uz', ''),
(538, 'en', ''),
(538, 'ru', ''),
(538, 'uz', ''),
(539, 'en', ''),
(539, 'ru', ''),
(539, 'uz', ''),
(540, 'en', ''),
(540, 'ru', ''),
(540, 'uz', ''),
(541, 'en', ''),
(541, 'ru', ''),
(541, 'uz', ''),
(542, 'en', ''),
(542, 'ru', ''),
(542, 'uz', ''),
(543, 'en', ''),
(543, 'ru', ''),
(543, 'uz', ''),
(544, 'en', ''),
(544, 'ru', ''),
(544, 'uz', ''),
(545, 'en', ''),
(545, 'ru', ''),
(545, 'uz', ''),
(546, 'en', ''),
(546, 'ru', ''),
(546, 'uz', ''),
(547, 'en', ''),
(547, 'ru', ''),
(547, 'uz', ''),
(548, 'en', ''),
(548, 'ru', ''),
(548, 'uz', ''),
(549, 'en', ''),
(549, 'ru', ''),
(549, 'uz', ''),
(550, 'en', ''),
(550, 'ru', ''),
(550, 'uz', ''),
(551, 'en', ''),
(551, 'ru', ''),
(551, 'uz', ''),
(552, 'en', ''),
(552, 'ru', ''),
(552, 'uz', ''),
(553, 'en', ''),
(553, 'ru', ''),
(553, 'uz', ''),
(554, 'en', ''),
(554, 'ru', ''),
(554, 'uz', ''),
(555, 'en', ''),
(555, 'ru', ''),
(555, 'uz', ''),
(556, 'en', ''),
(556, 'ru', ''),
(556, 'uz', ''),
(557, 'en', ''),
(557, 'ru', ''),
(557, 'uz', ''),
(558, 'en', ''),
(558, 'ru', ''),
(558, 'uz', ''),
(559, 'en', ''),
(559, 'ru', ''),
(559, 'uz', ''),
(560, 'en', ''),
(560, 'ru', ''),
(560, 'uz', ''),
(561, 'en', ''),
(561, 'ru', ''),
(561, 'uz', ''),
(562, 'en', ''),
(562, 'ru', ''),
(562, 'uz', ''),
(563, 'en', ''),
(563, 'ru', ''),
(563, 'uz', ''),
(564, 'en', ''),
(564, 'ru', ''),
(564, 'uz', '');

-- --------------------------------------------------------

--
-- Table structure for table `translation_source`
--

CREATE TABLE `translation_source` (
  `id` int(11) NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `translation_source`
--

INSERT INTO `translation_source` (`id`, `category`, `message`) VALUES
(1, 'app', 'Id'),
(2, 'app', 'Название'),
(3, 'app', 'Код'),
(4, 'app', 'Изображение'),
(5, 'app', 'Активный'),
(6, 'app', 'Основной'),
(7, 'app', 'Создано'),
(8, 'app', 'Изменено'),
(9, 'app', 'Языки'),
(10, 'app', 'Действия'),
(11, 'app', 'Показывать {dropdown} записей'),
(12, 'app', NULL),
(13, 'app', 'Удалить все'),
(14, 'app', 'Файловый менеджер'),
(15, 'app', 'Вы должны выбрать правильный ответ'),
(16, 'app', 'Очистить'),
(17, 'app', 'Уведомления'),
(18, 'app', 'Нет уведомлений'),
(19, 'app', 'Редактирование профиля'),
(20, 'app', 'Выход'),
(21, 'app_menu', 'Меню'),
(22, 'app_menu', 'Курсы'),
(23, 'app_menu', 'Пакеты курсов'),
(24, 'app_menu', 'Тесты'),
(25, 'app_menu', 'Предметы'),
(26, 'app_menu', 'Темы предметов'),
(27, 'app_menu', 'Контент'),
(28, 'app_menu', 'Новости'),
(29, 'app_menu', 'Главная'),
(30, 'app_menu', 'FAQ'),
(31, 'app_menu', 'Контакты'),
(32, 'app_menu', 'Прочие страницы'),
(33, 'app_menu', 'Маркетинг'),
(34, 'app_menu', 'Скидки'),
(35, 'app_menu', 'Промокоды'),
(36, 'app_menu', 'Аналитика'),
(37, 'app_menu', 'Пользователи'),
(38, 'app_menu', 'Заказы'),
(39, 'app_menu', 'Библиотека'),
(40, 'app_menu', 'Файловый менеджер'),
(41, 'app_menu', 'Шаблоны'),
(42, 'app_menu', 'Категории шаблонов'),
(43, 'app_menu', 'Система'),
(44, 'app_menu', 'Настройки'),
(45, 'app_menu', 'Языки'),
(46, 'app_menu', 'Переводы'),
(47, 'app_menu', 'Футер'),
(48, 'app_menu', 'Типы юнитов'),
(49, 'app_menu', 'SEO'),
(50, 'app_menu', 'Мета'),
(51, 'app_menu', 'Файлы'),
(52, 'app_menu', 'Авторизация'),
(53, 'app_menu', 'Кэш'),
(54, 'app_menu', 'Удалить миниатюры'),
(55, 'app_menu', 'Очистить'),
(56, 'app', 'Главная'),
(57, 'app', 'Пожалуйста введите пароль'),
(58, 'app', 'OK'),
(59, 'app', 'Редактирование'),
(60, 'app', 'Применить'),
(61, 'app', 'Адрес электронной почты'),
(62, 'app', 'Публичное имя'),
(63, 'app', 'Роль'),
(64, 'app', 'Последняя активность'),
(65, 'app', 'Новый пароль'),
(66, 'app', 'ФИО'),
(67, 'app', 'Пол'),
(68, 'app', 'Дата рождения'),
(69, 'app', 'Телефон'),
(70, 'app', 'Телефон родителя'),
(71, 'app', 'Адрес'),
(72, 'app', 'Категория'),
(73, 'app', 'Сообщение'),
(74, 'app', 'Переводы'),
(75, 'app', 'Есть перевод'),
(76, 'app', 'Импортировать'),
(77, 'app', 'Экспортировать'),
(78, 'app', 'Редактирование: {value}'),
(79, 'app', 'Информация'),
(80, 'app', 'Сохранить и редактировать'),
(81, 'app', 'Сохранить'),
(82, 'app', 'Произошла ошибка'),
(83, 'app', 'На главную'),
(84, 'app', 'error_page_title'),
(85, 'app', 'error_page_description'),
(86, 'app', 'Вернуться на главную'),
(87, 'app', 'Посмотреть каталог'),
(88, 'app', 'Введите запрос...'),
(89, 'app', 'Добавить пользователя'),
(90, 'app', 'Выйти из профиля'),
(91, 'app', 'footer_link_left'),
(92, 'app', 'footer_link_right'),
(93, 'app', 'Назад'),
(94, 'app', 'В разработке'),
(95, 'app', 'Активные'),
(96, 'app', 'Окончившиеся'),
(97, 'app', 'Удалённые'),
(98, 'app', 'Ссылка'),
(99, 'app', 'Тип'),
(100, 'app', 'Дата (от)'),
(101, 'app', 'Дата (до)'),
(102, 'app', 'Учитель'),
(103, 'app', 'Язык'),
(104, 'app', 'Автор'),
(105, 'app', 'Цена'),
(106, 'app', 'Оптимальное время для прохождения'),
(107, 'app', 'Время для ознакомления (в часах)'),
(108, 'app', 'Начальное значение для счётчика студентов'),
(109, 'app', 'Проходной процент'),
(110, 'app', 'Сертификат'),
(111, 'app', 'Изображение (превью)'),
(112, 'app', 'Изображение (внутреннее)'),
(113, 'app', 'Видео'),
(114, 'app', 'Краткое описание'),
(115, 'app', 'Полное описание'),
(116, 'app', 'Преимущества'),
(117, 'app', 'Авторы'),
(118, 'app', 'Курсы'),
(119, 'app', 'Фильтр'),
(120, 'app', 'Период действия (от)'),
(121, 'app', 'Период действия (до)'),
(122, 'app', 'Дата изменения (от)'),
(123, 'app', 'Дата изменения (до)'),
(124, 'app', 'Цена (от)'),
(125, 'app', 'Цена (до)'),
(126, 'app', 'Сбросить'),
(127, 'app', 'Создание'),
(128, 'app', 'Линейный курс'),
(129, 'app', 'Обучающие курсы'),
(130, 'app', 'Экзамен'),
(131, 'app', 'Тестовые задания'),
(132, 'app', 'Даты'),
(133, 'app', 'Предпросмотр'),
(134, 'app', 'Клонировать'),
(135, 'app', 'Вы уверены?'),
(136, 'app', 'Вернуть в разработку'),
(137, 'app', 'Отправленные на модерацию'),
(138, 'app', 'Отправлен на модерацию'),
(139, 'app', 'Отправить на модерацию'),
(140, 'app', 'Вернуть с модерации'),
(141, 'app', 'Возвращённые'),
(142, 'app', 'Возвращён'),
(143, 'app', 'Вернуть'),
(144, 'app', 'Активен'),
(145, 'app', 'Активировать'),
(146, 'app', 'Структура курса \"{course}\"'),
(147, 'app', 'Статус'),
(148, 'app', 'Скрыть всю структуру'),
(149, 'app', 'Развернуть всю структуру'),
(150, 'app', 'Редактировать название'),
(151, 'app', 'Переместить'),
(152, 'app', 'Добавить юнит'),
(153, 'app', 'Добавить подкатегорию'),
(154, 'app', 'Добавить категорию'),
(155, 'app', 'Клонировать все'),
(156, 'app', 'Все курсы пользователя'),
(157, 'app', 'Линейные курсы'),
(158, 'app', 'Архивные курсы'),
(159, 'app', 'Курсы пользователя'),
(160, 'app', 'Все курсы'),
(161, 'app', 'Пакеты курсов'),
(162, 'app', 'Аккаунт'),
(163, 'app', 'Закладки'),
(164, 'app', 'О проекте'),
(165, 'app', 'Новости'),
(166, 'app', 'Корзина'),
(167, 'app', 'submenu_user_courses_description'),
(168, 'app', 'submenu_courses_description'),
(169, 'app', 'submenu_course_packages_description'),
(170, 'app', 'submenu_profile_description'),
(171, 'app', 'submenu_bookmarks_description'),
(172, 'app', 'submenu_about_description'),
(173, 'app', 'submenu_news_description'),
(174, 'app', 'submenu_cart_description'),
(175, 'app', 'Поиск по курсам пользователя'),
(176, 'app', 'Продолжить'),
(177, 'app', 'Пройдено:'),
(178, 'app', 'Успеваемость:'),
(179, 'app', 'Заходили {date} в {time}'),
(180, 'app', 'Доступ до {date}'),
(181, 'app', 'Сбросить прогресс'),
(182, 'app', 'Меню'),
(183, 'app', 'Описание'),
(184, 'app', 'Выбор файла'),
(185, 'app', '{attribute_to} не должно быть меньше {attribute_from}'),
(186, 'app_notification', 'Запись была сохранена'),
(187, 'app', 'Учителя'),
(188, 'app', 'Просмотр: {value}'),
(189, 'app', 'Рекомендуемое значение - {value}'),
(190, 'app', 'Контент'),
(191, 'app', 'Общие'),
(192, 'app', 'Поиск'),
(193, 'app', 'Поиск...'),
(194, 'app', 'Курс'),
(195, 'app', 'Иконка'),
(196, 'app', 'Опыт'),
(197, 'app', 'SEO мета'),
(198, 'app', 'Пользователи'),
(199, 'app_role', 'Admin'),
(200, 'app_role', 'Moderator'),
(201, 'app_role', 'Student'),
(202, 'app_role', 'Teacher'),
(203, 'app', 'Все'),
(204, 'app', 'Общий поиск'),
(205, 'app', 'Дата регистрации (от)'),
(206, 'app', 'Дата регистрации (до)'),
(207, 'app', 'Дата последней активности (от)'),
(208, 'app', 'Дата последней активности (до)'),
(209, 'app', 'Неактивные'),
(210, 'app', 'Завершившие'),
(211, 'app', 'Подписчики'),
(212, 'app', 'Отправить сообщение'),
(213, 'app', 'Восстановить все'),
(214, 'app', 'Профиль'),
(215, 'app', 'Male'),
(216, 'app', 'Female'),
(217, 'app', 'Необходимый формат: {format}'),
(218, 'app', 'Поле должно содержать только латинские буквы и цифры'),
(219, 'app', 'Телефон родителя должен отличаться от Вашего телефона'),
(220, 'app', 'Текстовое поле'),
(221, 'app', 'Текстовая область'),
(222, 'app', 'Текстовый редактор'),
(223, 'app', 'Загрузка файлов'),
(224, 'app', 'Активные студенты'),
(225, 'app', 'Email'),
(226, 'app', 'Неактивные студенты'),
(227, 'app', 'Окончившие студенты'),
(228, 'app', 'Авторизация'),
(229, 'app', 'Логин'),
(230, 'app', 'Пароль'),
(231, 'app', 'Запомнить меня'),
(232, 'app', 'Войти'),
(233, 'app', 'Регистрация'),
(234, 'app_notification', 'Состояние записи было изменено'),
(235, 'app', 'Предпросмотр: {value}'),
(236, 'app', 'Программа'),
(237, 'app', 'Оптимальное время для прохождения:'),
(238, 'app', 'Длительность прохождения курса'),
(239, 'app', 'Доступен до:'),
(240, 'app', 'Курс доступен'),
(241, 'app', 'Приобрели курс:'),
(242, 'app', 'Пользователи приобретенные курс'),
(243, 'app', 'Описание курса'),
(244, 'app', 'Программа курса'),
(245, 'app', '{count} подразделов'),
(246, 'app', 'Открыть всю программу'),
(247, 'app', 'Создатели курса'),
(248, 'app', 'course_detail_slogan_title'),
(249, 'app', 'course_detail_slogan_description'),
(250, 'app', 'Длительность:'),
(251, 'app', 'Стомиость курса:'),
(252, 'app', '{price} UZS'),
(253, 'app', 'Учиться'),
(254, 'app', 'Все новости'),
(255, 'app', 'Новый пользователь?'),
(256, 'app', 'Создать учетную запись'),
(257, 'app', 'auth_login_hint'),
(258, 'app', 'Забыли пароль?'),
(259, 'app', 'Неверный {attribute}'),
(260, 'app', 'Ничего не найдено'),
(261, 'app', 'Каталог курсов'),
(262, 'app', 'Все типы курсов'),
(263, 'app', 'На всех языках'),
(264, 'app', 'Все авторы'),
(265, 'app', 'Подробнее'),
(266, 'app', 'Учеников:'),
(267, 'app', 'Больше курсов'),
(268, 'app', 'Хочу учиться'),
(269, 'app', 'Купить'),
(270, 'app', 'Опубликовать'),
(271, 'app_notification', 'Вся структура должна быть активирована'),
(272, 'app', 'Юнит курса'),
(273, 'app', 'Тест'),
(274, 'app_notification', 'Курс опубликован'),
(275, 'app', 'Каталог пакетов курсов'),
(276, 'app', 'Найдено {count} вариантов'),
(277, 'app', 'В корзине'),
(278, 'app_notification', 'Продукт был добавлен в корзину'),
(279, 'app', 'Очистить корзину'),
(280, 'app', 'Промокод'),
(281, 'app', 'cart_promocode_description'),
(282, 'app', 'Сумма:'),
(283, 'app', 'Скидка:'),
(284, 'app', 'Итого:'),
(285, 'app', 'Приобрести'),
(286, 'app', 'Типы юнитов'),
(287, 'app_menu', 'Достижения'),
(288, 'app_notification', 'Процесс выполнен'),
(289, 'app', 'Ключ'),
(290, 'app', 'Достижения'),
(291, 'app', 'Процент'),
(292, 'app', 'Секретный ключ'),
(293, 'app', 'Общее число активаций'),
(294, 'app', 'Активировано'),
(295, 'app', 'Промокоды'),
(296, 'app', 'Активации'),
(297, 'app', 'Рекомендуемое время для прохождения теста (в мниутах)'),
(298, 'app', 'Тесты'),
(299, 'app', 'Единственный ответ'),
(300, 'app', 'Множественный ответ'),
(301, 'app', 'Последовательность'),
(302, 'app', 'Установление соответствия'),
(303, 'app', 'Наборы тестов'),
(304, 'app', 'Предмет'),
(305, 'app', 'Темы предмета'),
(306, 'app', 'Типы полей'),
(307, 'app', 'Количество'),
(308, 'app', 'Тип поля'),
(309, 'app', 'Вопрос'),
(310, 'app', 'Варианты ответов'),
(311, 'app', 'Тест состоит из'),
(312, 'app', '{quantity} вопросов'),
(313, 'app', 'Рекомендуемое время заполнения:'),
(314, 'app', '{quantity} минут'),
(315, 'app', 'unit_test_sequence_hint'),
(316, 'app', 'Тема предмета'),
(317, 'app', 'Библиотека тестов'),
(318, 'app', 'Дата создания (от)'),
(319, 'app', 'Дата создания (до)'),
(320, 'app', 'moderation_active_content_update_alert'),
(321, 'app', 'Сохранить и создать'),
(322, 'app', 'Правильная последовательность'),
(323, 'app', 'Измеенение статуса'),
(324, 'app_notification', 'Статус был изменён'),
(325, 'app', 'unit_test_radio_hint'),
(326, 'app', 'Показать/скрыть структуру курса'),
(327, 'app', 'О курсе'),
(328, 'app', 'Добавить в закладки'),
(329, 'app', 'Убрать из закладок'),
(330, 'app', 'Вперёд'),
(331, 'app', 'Хотите задать время для прохождения заданий?'),
(332, 'app', 'минут'),
(333, 'app', 'Задать'),
(334, 'app', 'Завершить тестирование'),
(335, 'app', 'Тест состоял из'),
(336, 'app', 'Вы набрали {percent}%'),
(337, 'app', '{score} из {total} верных'),
(338, 'app', 'Пройти ещё раз'),
(339, 'app_notification', 'Прогресс курса был сброшен'),
(340, 'app', 'Вы не можете изменить {attribute}'),
(341, 'app', 'Тест не пройден!'),
(342, 'app', 'Следующая попытка через:'),
(343, 'app', '{hours} часов'),
(344, 'app', 'Далее'),
(345, 'app', '{date} в {time}'),
(346, 'app', 'app_notification'),
(347, 'app_notification', 'Вы получили демо доступ для курса \"{course}\" до {datetime}'),
(348, 'app_notification', 'Выw получили демо доступ для курса \"{course}\" до {datetime}'),
(349, 'app_notification', 'Поздравляем! Вы получили достижение \"{achievement}\" по курсу \"{course}\"'),
(350, 'app_menu', 'Лиги'),
(351, 'app', 'Лиги'),
(352, 'app_notification', 'Все уведомления были удалены'),
(353, 'app', 'Уже есть учётная запись на платформе «EXM Edx Platform»?'),
(354, 'app', 'Данный email уже занят'),
(355, 'app', 'Данное имя уже занято'),
(356, 'app', 'Логин (адрес электронной почты)'),
(357, 'app', 'Имя пользователя (публичное)'),
(358, 'app', 'Место проживания'),
(359, 'app', 'registration_submit_information'),
(360, 'app', 'Создать учётную запись'),
(361, 'app', 'копия'),
(362, 'app_notification', 'Запись была клонирована'),
(363, 'app', 'Профиль \"{nickname}\"'),
(364, 'app', 'Фотография'),
(365, 'app', 'Общая информация'),
(366, 'app', 'Ссылка на профиль'),
(367, 'app', 'Изменить'),
(368, 'app', 'История платежей'),
(369, 'app', 'Все оплаты'),
(370, 'app', 'Весь прогресс курса онулируется. Вы уверены?'),
(371, 'yii2mod.rbac', 'RBAC'),
(372, 'app', 'Routes'),
(373, 'app', 'Rules'),
(374, 'app', 'Roles'),
(375, 'app', 'Assignments'),
(376, 'app', 'Permissions'),
(377, 'yii2mod.rbac', 'Roles'),
(378, 'yii2mod.rbac', 'Create'),
(379, 'yii2mod.rbac', 'Name'),
(380, 'yii2mod.rbac', 'Rule Name'),
(381, 'yii2mod.rbac', 'Select Rule'),
(382, 'yii2mod.rbac', 'Description'),
(383, 'app', 'View: {value}'),
(384, 'app', 'Information'),
(385, 'app', 'Assignment'),
(386, 'yii2mod.rbac', 'Type'),
(387, 'yii2mod.rbac', 'Data'),
(388, 'yii2mod.rbac', 'Search for available'),
(389, 'app', 'Include'),
(390, 'yii2mod.rbac', 'Assign'),
(391, 'app', 'Exclude'),
(392, 'yii2mod.rbac', 'Remove'),
(393, 'yii2mod.rbac', 'Search for assigned'),
(394, 'app', 'Отправка сообщения'),
(395, 'app', 'Тема'),
(396, 'app', 'Текст'),
(397, 'app', 'Файл'),
(398, 'app', 'Отправить'),
(399, 'app', 'Настройки'),
(400, 'app', 'Лейбл'),
(401, 'app', 'Значение'),
(402, 'app', 'Предметы'),
(403, 'app', 'Заказы'),
(404, 'app', 'Старый пароль'),
(405, 'app', 'Подтверждение пароля'),
(406, 'app', 'Сбросить пароль'),
(407, 'app', 'Удалить аккаунт'),
(408, 'app', 'account_delete_primary_text'),
(409, 'app', 'account_delete_warning_text'),
(410, 'app_menu', 'Подписчики'),
(411, 'yii2mod.rbac', 'Routes'),
(412, 'yii2mod.rbac', 'Refresh'),
(413, 'app', 'Дата подписки (от)'),
(414, 'app', 'Дата подписки (до)'),
(415, 'app', 'Демо-доступ закончится {date} в {time}'),
(416, 'app', 'Для продолжения Вам необходимо купить курс'),
(417, 'app', 'Окончание демо-доступа:'),
(418, 'app', 'Пользователи не найдены'),
(419, 'app_notification', 'Сообщение было отправлено'),
(420, 'app', 'Пользователи должно быть не меньше {min} и не больше {max}'),
(421, 'app', 'Дата приобретения'),
(422, 'app', 'Последнее посещение'),
(423, 'app', 'Восстановление профиля'),
(424, 'app', 'Email не найден'),
(425, 'app', 'Восстановить'),
(426, 'app_mail', 'Инструкция для восстановление пароля'),
(427, 'app_notification', 'reset_password_request_success_alert'),
(428, 'app', 'Пользователей должно быть не менее {min} и не более {max}'),
(429, 'app', 'Экспорт переводов'),
(430, 'app_notification', 'Продукт был удалён из корзины'),
(431, 'app', 'Ваша корзина пуста'),
(432, 'app', 'Вы можете начать свой выбор с каталога, посмотреть курсы или воспользоваться поиском, если ищете что-то конкретное'),
(433, 'app_notification', 'Вы должны авторизоваться'),
(434, 'app', 'Опубликовано (от)'),
(435, 'app', 'Опубликовано (до)'),
(436, 'app', 'Опубликовано'),
(437, 'app', 'Test message'),
(438, 'app', 'course_demo_time_passed_alert'),
(439, 'yii2mod.rbac', 'Assignments'),
(440, 'app', 'Ключи'),
(441, 'app', 'Значения'),
(442, 'app', 'unit_test_match_hint'),
(443, 'app', 'unit_test_match_label_key'),
(444, 'app', 'unit_test_match_label_value'),
(445, 'app', 'Импорт курса'),
(446, 'app', 'Ипортировать'),
(447, 'app_notification', 'Произошла ошибка'),
(448, 'app', 'Структура архива составлена некорректно'),
(449, 'app', 'Показать все достижения'),
(450, 'app', 'Скрыть'),
(451, 'app', 'unit_test_checkbox_hint'),
(452, 'app', 'Здесь несколько вариантов ответа'),
(453, 'app', 'unit_test_text_area_hint'),
(454, 'app', 'Курс не является пустым'),
(455, 'app_notification', 'course_import_success_alert'),
(456, 'app', 'Оставьте поле пустым для применения случайного значения'),
(457, 'app', 'Максимальное количество продуктов'),
(458, 'app', 'Одно из этих полей обязательно: {value}'),
(459, 'app', 'Оплатить с помощью {payment}'),
(460, 'app_notification', 'Данный промокод может действовать только для {quantity} продуктов'),
(461, 'app_notification', 'Данный промокод может быть использован только для {quantity} продуктов'),
(462, 'app', 'Промокод не найден'),
(463, 'app', 'Оплата за заказ'),
(464, 'app', 'Фильтры'),
(465, 'app_notification', 'Промокод не распространяется на продукты: {products}'),
(466, 'app_notification', 'Промокод не найден'),
(467, 'app', 'Все пакеты'),
(468, 'app', 'Скидки'),
(469, 'app', 'Студент'),
(470, 'app', 'Предварительная цена'),
(471, 'app', 'Сумма скидки'),
(472, 'app', 'Сумма по промокоду'),
(473, 'app', 'Продукты'),
(474, 'app', 'Итоговая сумма'),
(475, 'app', 'Итоговая сумма (от)'),
(476, 'app', 'Итоговая сумма (до)'),
(477, 'app', 'Пакеты'),
(478, 'app', 'Приобретённые курсы'),
(479, 'app', 'Подписан на курсы'),
(480, 'app', 'Прогресс'),
(481, 'app', 'Текущий юнит'),
(482, 'app', 'Награды'),
(483, 'app', 'Предварительная сумма'),
(484, 'app', 'Итоговое количество'),
(485, 'app', 'Аналитика: проданные продукты'),
(486, 'app', 'Год'),
(487, 'app', 'Скидка'),
(488, 'app', 'Пакет курсов'),
(489, 'app', 'Аналитика'),
(490, 'app', 'Для просмотра аналитики по месяцам необходимо выбрать год'),
(491, 'app', 'Заказ'),
(492, 'app', 'Продукт'),
(493, 'app', 'Стоимость скидки'),
(494, 'app', 'Стоимость промокода'),
(495, 'app', 'Итоговая стоимость'),
(496, 'app_notification', 'Вы успешно окончили курс'),
(497, 'app_notification', 'Вы сменили профиль'),
(498, 'app', 'Список закладок пуст'),
(499, 'app', 'Вы можете добавлять интересные для вас юниты и затем читать их здесь'),
(500, 'app_notification', 'Корзина была очищена'),
(501, 'app', 'Извините, но по вашему запросу ничего не найдено'),
(502, 'app', 'Может быть вам поискать что-то ещё?'),
(503, 'app', 'Экспорт в Excel'),
(504, 'app', 'Неверные данные'),
(505, 'app', 'Оплата отклонена'),
(506, 'app', 'Получен бесплатно'),
(507, 'app', 'Оплачен (Payme)'),
(508, 'app', 'Оплачен (Click)'),
(509, 'app', 'Январь'),
(510, 'app', 'Февраль'),
(511, 'app', 'Март'),
(512, 'app', 'Апрель'),
(513, 'app', 'Май'),
(514, 'app', 'Июнь'),
(515, 'app', 'Июль'),
(516, 'app', 'Август'),
(517, 'app', 'Сентябрь'),
(518, 'app', 'Октябрь'),
(519, 'app', 'Ноябрь'),
(520, 'app', 'Декабрь'),
(521, 'app', 'Сертификаты'),
(522, 'app', 'Назад в профиль'),
(523, 'app', 'By date (asc)'),
(524, 'app', 'By price (desc)'),
(525, 'app', 'By price (asc)'),
(526, 'app', 'By date (desc)'),
(527, 'app', 'Протестировать сертификат'),
(528, 'app', 'Тестирование сертификата'),
(529, 'app', 'Начать'),
(530, 'app', 'Темы предметов'),
(531, 'app_notification', 'import_course_success_alert'),
(532, 'app', 'Максимальное количество тестов - {limit}, при попытке экспорта - {count}'),
(533, 'app', 'Импорт библиотеки тестов'),
(534, 'app_notification', 'import_library_test_category_success_alert'),
(535, 'app', 'Категория не является пустой'),
(536, 'app', 'Архивы будут отправлены на почту \"{email}\"'),
(537, 'app', 'Экспорт курса'),
(538, 'app_mail', 'Экспорт курса'),
(539, 'app_mail', 'Экспорт тестов'),
(540, 'app', 'Архив в ближайшее время будет отправлены на почту \"{email}\"'),
(541, 'app_notification', 'Скачать архив'),
(542, 'app', 'В ближайшее время Вы получите уведомление со ссылкой на архив'),
(543, 'app', 'Тема предметов не является пустой'),
(544, 'app', 'Итоговое количество заказов'),
(545, 'app', 'Итоговое количество продуктов'),
(546, 'app', 'Тип суммы'),
(547, 'app', 'Фильтр {number}'),
(548, 'app', 'Фильтр №{number}'),
(549, 'app', 'Добавить фильтр'),
(550, 'app', 'Запрос №{number}'),
(551, 'app', 'Все курсы:'),
(552, 'app', 'Пакетом:'),
(553, 'app', 'Выгода:'),
(554, 'app', 'Используется промокод {promocode}'),
(555, 'app', 'Используется промокод: {promocode}'),
(556, 'app', 'Применённый промокод: {promocode}'),
(557, 'app', 'Применённый промокод: \"{promocode}\"'),
(558, 'app', 'Загрузка файла'),
(559, 'app', 'Некоторые пользователи приобретали данный курс'),
(560, 'app', 'Ошибка в медиа-файлах'),
(561, 'app_notification', 'Архив был успешно импортирован в \"{value}\"'),
(562, 'app', 'Импортировать/экспортировать'),
(563, 'app', 'course_integration_title'),
(564, 'app', 'course_integration_description');

-- --------------------------------------------------------

--
-- Table structure for table `trash`
--

CREATE TABLE `trash` (
  `trash_model_class` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trash_model_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nickname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auth_key` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_ids` json NOT NULL,
  `last_activity` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `nickname`, `role`, `image`, `auth_key`, `password_hash`, `password_reset_token`, `session_ids`, `last_activity`, `created_at`, `updated_at`) VALUES
(1, 'alinsky.dmitry@gmail.com', 'admin', 'admin', '', '3acd22f74470aff98dcbc54b58b64f91', '$2y$13$gXNo74d8cDF.K02MvdfLi.eSfRNtIpaXHdpIPEDbnwyFHBn3eNy9m', 'ynjeNc5shIGjpF6szdh1ZM-YN8nobymN_1632764775', '[\"cd478sv6m3ruikeiqp6m37s2a91q659q\", \"ihpl95oh79d6bno5heus606n8nfk9cdk\", \"iuvvpcqbiotm4uhbg77ptf5c66ursskq\"]', '2021-11-24 19:28:17', '2020-02-21 04:58:00', '2021-11-22 14:47:51');

-- --------------------------------------------------------

--
-- Table structure for table `user_cart`
--

CREATE TABLE `user_cart` (
  `user_id` int(11) NOT NULL,
  `promocode_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_cart`
--

INSERT INTO `user_cart` (`user_id`, `promocode_id`) VALUES
(1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_cart_product`
--

CREATE TABLE `user_cart_product` (
  `user_id` int(11) NOT NULL,
  `model_class` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` int(11) NOT NULL,
  `model_json` json NOT NULL,
  `price` bigint(20) NOT NULL,
  `checkout_price` bigint(20) NOT NULL,
  `has_promocode` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_course`
--

CREATE TABLE `user_course` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `course_json` json NOT NULL,
  `user_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `library_attachment_json` json NOT NULL,
  `last_visit` datetime DEFAULT NULL,
  `progress` tinyint(3) NOT NULL DEFAULT '0',
  `performance` tinyint(3) NOT NULL DEFAULT '0',
  `certificate_file` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `demo_datetime_to` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_course_achievement`
--

CREATE TABLE `user_course_achievement` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_course_id` int(11) NOT NULL,
  `achievement_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_course_league`
--

CREATE TABLE `user_course_league` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `user_course_id` int(11) NOT NULL,
  `key` enum('explorer','nerd','persistent','intellectual') COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` int(11) NOT NULL,
  `is_best` tinyint(1) NOT NULL DEFAULT '0',
  `league_id` int(11) DEFAULT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_course_unit`
--

CREATE TABLE `user_course_unit` (
  `id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `unit_json` json NOT NULL,
  `type_id` int(11) DEFAULT NULL,
  `is_unlocked` tinyint(1) NOT NULL DEFAULT '0',
  `is_passed` tinyint(1) NOT NULL DEFAULT '0',
  `is_current` tinyint(1) NOT NULL DEFAULT '0',
  `performance` json NOT NULL,
  `available_from` datetime DEFAULT NULL,
  `is_bookmarked` tinyint(1) NOT NULL DEFAULT '0',
  `tree` int(11) NOT NULL DEFAULT '1',
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `depth` int(11) NOT NULL,
  `library_attachment_json` json NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_message`
--

CREATE TABLE `user_message` (
  `id` int(11) NOT NULL,
  `user_from_id` int(11) NOT NULL,
  `theme` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_notification`
--

CREATE TABLE `user_notification` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_id` int(11) NOT NULL,
  `params` json NOT NULL,
  `is_not_seen` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE `user_profile` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` enum('male','female') COLLATE utf8mb4_unicode_ci NOT NULL,
  `birth_date` date NOT NULL,
  `phone` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_phone` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_profile`
--

INSERT INTO `user_profile` (`id`, `user_id`, `full_name`, `gender`, `birth_date`, `phone`, `parent_phone`, `address`) VALUES
(1, 1, 'Admin', 'male', '2000-01-01', '998903244534', '', 'dqwdqwd');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD PRIMARY KEY (`item_name`,`user_id`),
  ADD KEY `auth_assignment_user_id_idx` (`user_id`);

--
-- Indexes for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD PRIMARY KEY (`name`),
  ADD KEY `rule_name` (`rule_name`),
  ADD KEY `idx-auth_item-type` (`type`);

--
-- Indexes for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD PRIMARY KEY (`parent`,`child`),
  ADD KEY `child` (`child`);

--
-- Indexes for table `auth_rule`
--
ALTER TABLE `auth_rule`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `course_advantage`
--
ALTER TABLE `course_advantage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `sort` (`sort`);

--
-- Indexes for table `course_author`
--
ALTER TABLE `course_author`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `sort` (`sort`);

--
-- Indexes for table `course_package`
--
ALTER TABLE `course_package`
  ADD PRIMARY KEY (`id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `course_package_ref`
--
ALTER TABLE `course_package_ref`
  ADD PRIMARY KEY (`course_id`,`package_id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indexes for table `course_teacher_ref`
--
ALTER TABLE `course_teacher_ref`
  ADD PRIMARY KEY (`course_id`,`teacher_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `course_unit`
--
ALTER TABLE `course_unit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tree` (`tree`),
  ADD KEY `lft` (`lft`),
  ADD KEY `status` (`status`),
  ADD KEY `type_id` (`type_id`);

--
-- Indexes for table `course_unit_type`
--
ALTER TABLE `course_unit_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `library_attachment_template`
--
ALTER TABLE `library_attachment_template`
  ADD PRIMARY KEY (`id`),
  ADD KEY `model_class` (`model_class`,`model_id`),
  ADD KEY `sort` (`sort`),
  ADD KEY `group` (`group`);

--
-- Indexes for table `library_attachment_test`
--
ALTER TABLE `library_attachment_test`
  ADD PRIMARY KEY (`id`),
  ADD KEY `model_class` (`model_class`,`model_id`),
  ADD KEY `sort` (`sort`);

--
-- Indexes for table `library_attachment_test_pack`
--
ALTER TABLE `library_attachment_test_pack`
  ADD PRIMARY KEY (`id`),
  ADD KEY `model_class` (`model_class`,`model_id`),
  ADD KEY `sort` (`sort`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `library_template`
--
ALTER TABLE `library_template`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `language_id` (`language_id`) USING BTREE;

--
-- Indexes for table `library_template_category`
--
ALTER TABLE `library_template_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `library_test`
--
ALTER TABLE `library_test`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `creator_id` (`creator_id`),
  ADD KEY `status` (`status`),
  ADD KEY `updated_at` (`updated_at`);

--
-- Indexes for table `library_test_category`
--
ALTER TABLE `library_test_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `creator_id` (`creator_id`);

--
-- Indexes for table `library_test_subject`
--
ALTER TABLE `library_test_subject`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `library_test_subject_teacher_ref`
--
ALTER TABLE `library_test_subject_teacher_ref`
  ADD PRIMARY KEY (`subject_id`,`teacher_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tree` (`tree`),
  ADD KEY `lft` (`lft`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `moderation_status`
--
ALTER TABLE `moderation_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `model_class` (`model_class`,`model_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`),
  ADD KEY `student_id` (`student_id`) USING BTREE,
  ADD KEY `promocode_id` (`promocode_id`);

--
-- Indexes for table `order_discount`
--
ALTER TABLE `order_discount`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_discount_product_ref`
--
ALTER TABLE `order_discount_product_ref`
  ADD PRIMARY KEY (`discount_id`,`model_class`,`model_id`);

--
-- Indexes for table `order_payment_click`
--
ALTER TABLE `order_payment_click`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `order_payment_paycom`
--
ALTER TABLE `order_payment_paycom`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `order_product`
--
ALTER TABLE `order_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`) USING BTREE,
  ADD KEY `model_class` (`model_class`,`model_id`),
  ADD KEY `discount_id` (`discount_id`),
  ADD KEY `sort` (`sort`),
  ADD KEY `promocode_id` (`promocode_id`);

--
-- Indexes for table `order_promocode`
--
ALTER TABLE `order_promocode`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`);

--
-- Indexes for table `order_promocode_product_ref`
--
ALTER TABLE `order_promocode_product_ref`
  ADD PRIMARY KEY (`promocode_id`,`model_class`,`model_id`);

--
-- Indexes for table `page`
--
ALTER TABLE `page`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `queue`
--
ALTER TABLE `queue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `channel` (`channel`),
  ADD KEY `reserved_at` (`reserved_at`),
  ADD KEY `priority` (`priority`);

--
-- Indexes for table `reward_achievement`
--
ALTER TABLE `reward_achievement`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`),
  ADD KEY `sort` (`sort`);

--
-- Indexes for table `reward_league`
--
ALTER TABLE `reward_league`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`),
  ADD KEY `sort` (`sort`);

--
-- Indexes for table `seo_meta`
--
ALTER TABLE `seo_meta`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `model_class` (`model_class`,`model_id`,`lang`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staticpage`
--
ALTER TABLE `staticpage`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`) USING BTREE;

--
-- Indexes for table `staticpage_block`
--
ALTER TABLE `staticpage_block`
  ADD PRIMARY KEY (`id`),
  ADD KEY `staticpage_id` (`staticpage_id`) USING BTREE,
  ADD KEY `part_index` (`part_index`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `system_language`
--
ALTER TABLE `system_language`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `active` (`is_active`),
  ADD KEY `is_main` (`is_main`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `sort` (`sort`);

--
-- Indexes for table `translation_message`
--
ALTER TABLE `translation_message`
  ADD PRIMARY KEY (`id`,`language`);

--
-- Indexes for table `translation_source`
--
ALTER TABLE `translation_source`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trash`
--
ALTER TABLE `trash`
  ADD PRIMARY KEY (`trash_model_class`,`trash_model_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `auth_key` (`auth_key`),
  ADD UNIQUE KEY `nickname` (`nickname`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`),
  ADD KEY `role` (`role`),
  ADD KEY `updated_at` (`updated_at`),
  ADD KEY `last_activity` (`last_activity`);

--
-- Indexes for table `user_cart`
--
ALTER TABLE `user_cart`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `promocode_id` (`promocode_id`);

--
-- Indexes for table `user_cart_product`
--
ALTER TABLE `user_cart_product`
  ADD PRIMARY KEY (`user_id`,`model_class`,`model_id`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `user_course`
--
ALTER TABLE `user_course`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `user_course_achievement`
--
ALTER TABLE `user_course_achievement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `user_course_id` (`user_course_id`),
  ADD KEY `achievement_id` (`achievement_id`);

--
-- Indexes for table `user_course_league`
--
ALTER TABLE `user_course_league`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_course_id` (`user_course_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `updated_at` (`updated_at`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `league_id` (`league_id`);

--
-- Indexes for table `user_course_unit`
--
ALTER TABLE `user_course_unit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tree` (`tree`),
  ADD KEY `lft` (`lft`),
  ADD KEY `type_id` (`type_id`),
  ADD KEY `unit_id` (`unit_id`),
  ADD KEY `is_current` (`is_current`),
  ADD KEY `is_unlocked` (`is_unlocked`),
  ADD KEY `is_bookmarked` (`is_bookmarked`),
  ADD KEY `is_passed` (`is_passed`);

--
-- Indexes for table `user_message`
--
ALTER TABLE `user_message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_from_id` (`user_from_id`);

--
-- Indexes for table `user_notification`
--
ALTER TABLE `user_notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `course_advantage`
--
ALTER TABLE `course_advantage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `course_author`
--
ALTER TABLE `course_author`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `course_package`
--
ALTER TABLE `course_package`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `course_unit`
--
ALTER TABLE `course_unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `course_unit_type`
--
ALTER TABLE `course_unit_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `library_attachment_template`
--
ALTER TABLE `library_attachment_template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `library_attachment_test`
--
ALTER TABLE `library_attachment_test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `library_attachment_test_pack`
--
ALTER TABLE `library_attachment_test_pack`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `library_template`
--
ALTER TABLE `library_template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `library_template_category`
--
ALTER TABLE `library_template_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `library_test`
--
ALTER TABLE `library_test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `library_test_category`
--
ALTER TABLE `library_test_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `library_test_subject`
--
ALTER TABLE `library_test_subject`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `moderation_status`
--
ALTER TABLE `moderation_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_discount`
--
ALTER TABLE `order_discount`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_payment_click`
--
ALTER TABLE `order_payment_click`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_payment_paycom`
--
ALTER TABLE `order_payment_paycom`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_product`
--
ALTER TABLE `order_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_promocode`
--
ALTER TABLE `order_promocode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `page`
--
ALTER TABLE `page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `queue`
--
ALTER TABLE `queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reward_achievement`
--
ALTER TABLE `reward_achievement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `reward_league`
--
ALTER TABLE `reward_league`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `seo_meta`
--
ALTER TABLE `seo_meta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staticpage`
--
ALTER TABLE `staticpage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `staticpage_block`
--
ALTER TABLE `staticpage_block`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `system_language`
--
ALTER TABLE `system_language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `translation_source`
--
ALTER TABLE `translation_source`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=565;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_course`
--
ALTER TABLE `user_course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_course_achievement`
--
ALTER TABLE `user_course_achievement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_course_league`
--
ALTER TABLE `user_course_league`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_course_unit`
--
ALTER TABLE `user_course_unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_message`
--
ALTER TABLE `user_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_notification`
--
ALTER TABLE `user_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_profile`
--
ALTER TABLE `user_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `course_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `system_language` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `course_advantage`
--
ALTER TABLE `course_advantage`
  ADD CONSTRAINT `course_advantage_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `course_author`
--
ALTER TABLE `course_author`
  ADD CONSTRAINT `course_author_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `course_package`
--
ALTER TABLE `course_package`
  ADD CONSTRAINT `course_package_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `system_language` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `course_package_ref`
--
ALTER TABLE `course_package_ref`
  ADD CONSTRAINT `course_package_ref_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `course_package_ref_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `course_package` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `course_teacher_ref`
--
ALTER TABLE `course_teacher_ref`
  ADD CONSTRAINT `course_teacher_ref_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `course_teacher_ref_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `course_unit`
--
ALTER TABLE `course_unit`
  ADD CONSTRAINT `course_unit_ibfk_1` FOREIGN KEY (`tree`) REFERENCES `course` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `course_unit_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `course_unit_type` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `library_attachment_test_pack`
--
ALTER TABLE `library_attachment_test_pack`
  ADD CONSTRAINT `library_attachment_test_pack_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `library_test_subject` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `library_template`
--
ALTER TABLE `library_template`
  ADD CONSTRAINT `library_template_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `library_template_category` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `library_template_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `system_language` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `library_test`
--
ALTER TABLE `library_test`
  ADD CONSTRAINT `library_test_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `library_test_subject` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `library_test_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `library_test_category` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `library_test_ibfk_4` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `library_test_category`
--
ALTER TABLE `library_test_category`
  ADD CONSTRAINT `library_test_category_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `library_test_subject` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `library_test_category_ibfk_2` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `library_test_subject_teacher_ref`
--
ALTER TABLE `library_test_subject_teacher_ref`
  ADD CONSTRAINT `library_test_subject_teacher_ref_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `library_test_subject` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `library_test_subject_teacher_ref_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `moderation_status`
--
ALTER TABLE `moderation_status`
  ADD CONSTRAINT `moderation_status_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `order_ibfk_3` FOREIGN KEY (`promocode_id`) REFERENCES `order_promocode` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `order_discount_product_ref`
--
ALTER TABLE `order_discount_product_ref`
  ADD CONSTRAINT `order_discount_product_ref_ibfk_1` FOREIGN KEY (`discount_id`) REFERENCES `order_discount` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `order_payment_click`
--
ALTER TABLE `order_payment_click`
  ADD CONSTRAINT `order_payment_click_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `order_payment_paycom`
--
ALTER TABLE `order_payment_paycom`
  ADD CONSTRAINT `order_payment_paycom_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `order_product`
--
ALTER TABLE `order_product`
  ADD CONSTRAINT `order_product_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `order_product_ibfk_2` FOREIGN KEY (`discount_id`) REFERENCES `order_discount` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `order_product_ibfk_3` FOREIGN KEY (`promocode_id`) REFERENCES `order_promocode` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `order_promocode_product_ref`
--
ALTER TABLE `order_promocode_product_ref`
  ADD CONSTRAINT `order_promocode_product_ref_ibfk_1` FOREIGN KEY (`promocode_id`) REFERENCES `order_promocode` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `staticpage_block`
--
ALTER TABLE `staticpage_block`
  ADD CONSTRAINT `staticpage_block_ibfk_1` FOREIGN KEY (`staticpage_id`) REFERENCES `staticpage` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `translation_message`
--
ALTER TABLE `translation_message`
  ADD CONSTRAINT `fk_message_source_message` FOREIGN KEY (`id`) REFERENCES `translation_source` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_cart`
--
ALTER TABLE `user_cart`
  ADD CONSTRAINT `user_cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `user_cart_ibfk_2` FOREIGN KEY (`promocode_id`) REFERENCES `order_promocode` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `user_cart_product`
--
ALTER TABLE `user_cart_product`
  ADD CONSTRAINT `user_cart_product_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_cart` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_course`
--
ALTER TABLE `user_course`
  ADD CONSTRAINT `user_course_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `user_course_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `user_course_ibfk_3` FOREIGN KEY (`language_id`) REFERENCES `system_language` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `user_course_achievement`
--
ALTER TABLE `user_course_achievement`
  ADD CONSTRAINT `user_course_achievement_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `user_course_achievement_ibfk_2` FOREIGN KEY (`user_course_id`) REFERENCES `user_course` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `user_course_achievement_ibfk_3` FOREIGN KEY (`achievement_id`) REFERENCES `reward_achievement` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `user_course_league`
--
ALTER TABLE `user_course_league`
  ADD CONSTRAINT `user_course_league_ibfk_2` FOREIGN KEY (`user_course_id`) REFERENCES `user_course` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `user_course_league_ibfk_3` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `user_course_league_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `user_course_league_ibfk_5` FOREIGN KEY (`league_id`) REFERENCES `reward_league` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `user_course_unit`
--
ALTER TABLE `user_course_unit`
  ADD CONSTRAINT `user_course_unit_ibfk_1` FOREIGN KEY (`tree`) REFERENCES `user_course` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `user_course_unit_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `course_unit_type` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `user_course_unit_ibfk_3` FOREIGN KEY (`unit_id`) REFERENCES `course_unit` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `user_message`
--
ALTER TABLE `user_message`
  ADD CONSTRAINT `user_message_ibfk_1` FOREIGN KEY (`user_from_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `user_notification`
--
ALTER TABLE `user_notification`
  ADD CONSTRAINT `user_notification_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD CONSTRAINT `user_profile_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
