-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 27, 2024 at 09:11 PM
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
-- Database: `db_acap`
--

--
-- Dumping data for table `ballot_config`
--

INSERT INTO `ballot_config` (`field_id`, `group_id`, `seq`, `field_name`, `field_type`, `description`, `attrib`) VALUES
(1, 1, 2, 'Student Name', 'short_text', NULL, '{\"default\": true, \"active\": true}'),
(2, 1, 3, 'Section', 'short_text', NULL, '{\"default\": true, \"active\": true}'),
(3, 2, 1, 'Candidate Form', 'short_text', NULL, '{\"default\": true, \"active\": true}');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
