-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 30, 2019 at 06:35 PM
-- Server version: 10.1.29-MariaDB
-- PHP Version: 7.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `libra_e_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) NOT NULL,
  `code` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `parent_code` varchar(100) NOT NULL,
  `status` int(1) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `code`, `name`, `parent_code`, `status`, `updated_at`, `created_at`) VALUES
(1, '000', 'Computer Science', '000', 1, '2019-09-30 13:50:53', '0000-00-00 00:00:00'),
(2, '001', 'Knowledge', '000', 1, '2019-09-30 13:50:57', '0000-00-00 00:00:00'),
(3, '002', 'The book (writing, libraries, and book-related topics)', '000', 1, '2019-09-30 14:11:41', '0000-00-00 00:00:00'),
(4, '003', 'Systems', '000', 1, '2019-09-30 14:11:41', '0000-00-00 00:00:00'),
(5, '004', 'Data processing & computer science', '000', 1, '2019-09-30 14:11:41', '0000-00-00 00:00:00'),
(6, '005', 'Computer programming, programs & data', '000', 1, '2019-09-30 14:11:41', '0000-00-00 00:00:00'),
(7, '006', 'Special computer methods', '000', 1, '2019-09-30 14:11:41', '0000-00-00 00:00:00'),
(8, '010', 'Bibliography', '010', 1, '2019-09-30 14:11:41', '0000-00-00 00:00:00'),
(9, '011', 'Bibliographies', '010', 1, '2019-09-30 14:11:41', '0000-00-00 00:00:00'),
(10, '012', 'Bibliographies of individuals', '010', 1, '2019-09-30 14:11:41', '0000-00-00 00:00:00'),
(11, '014', 'Bibliographies of anonymous & pseudonymous works', '010', 1, '2019-09-30 14:11:41', '0000-00-00 00:00:00'),
(12, '015', 'Bibliographies of works from specific places', '010', 1, '2019-09-30 14:11:41', '0000-00-00 00:00:00'),
(13, '016', 'Bibliographies of works on specific subjects', '010', 1, '2019-09-30 14:20:56', '0000-00-00 00:00:00'),
(14, '017', 'General subject catalogs', '010', 1, '2019-09-30 14:20:56', '0000-00-00 00:00:00'),
(15, '018', 'Catalogs arranged by author, date, etc.', '010', 1, '2019-09-30 14:20:56', '0000-00-00 00:00:00'),
(16, '019', 'Dictionary catalogs', '010', 1, '2019-09-30 14:20:56', '0000-00-00 00:00:00'),
(17, '020', 'Library & information sciences', '020', 1, '2019-09-30 14:20:56', '0000-00-00 00:00:00'),
(18, '021', 'Library relationships (with archives, information centers, etc.)', '020', 1, '2019-09-30 14:20:56', '0000-00-00 00:00:00'),
(19, '022', 'Administration of physical plant', '020', 1, '2019-09-30 14:20:56', '0000-00-00 00:00:00'),
(20, '023', 'Personnel management', '020', 1, '2019-09-30 14:20:56', '0000-00-00 00:00:00'),
(21, '025', 'Library operations', '020', 1, '2019-09-30 14:20:56', '0000-00-00 00:00:00'),
(22, '026', 'Libraries for specific subjects', '020', 1, '2019-09-30 14:20:56', '0000-00-00 00:00:00'),
(27, '027', 'General libraries', '020', 1, '2019-09-30 14:33:11', '0000-00-00 00:00:00'),
(28, '028', 'Reading & use of other information media', '020', 1, '2019-09-30 14:33:11', '0000-00-00 00:00:00'),
(29, '030', 'General encyclopedic works', '030', 1, '2019-09-30 14:33:11', '0000-00-00 00:00:00'),
(30, '031', 'Encyclopedias in American English', '030', 1, '2019-09-30 14:33:11', '0000-00-00 00:00:00'),
(31, '032', 'Encyclopedias in English', '030', 1, '2019-09-30 14:33:11', '0000-00-00 00:00:00'),
(32, '033', 'Encyclopedias in other Germanic languages', '030', 1, '2019-09-30 14:33:11', '0000-00-00 00:00:00'),
(33, '034', 'Encyclopedias in French, Occitan, and Catalan', '030', 1, '2019-09-30 14:33:11', '0000-00-00 00:00:00'),
(34, '035', 'Encyclopedias in Italian, Romanian, and related languages', '030', 1, '2019-09-30 14:33:11', '0000-00-00 00:00:00'),
(35, '036', 'Encyclopedias in Spanish & Portuguese', '030', 1, '2019-09-30 14:33:11', '0000-00-00 00:00:00'),
(36, '037', 'Encyclopedias in Slavic languages', '030', 1, '2019-09-30 14:33:11', '0000-00-00 00:00:00'),
(37, '038', 'Encyclopedias in Scandinavian languages', '030', 1, '2019-09-30 14:49:01', '0000-00-00 00:00:00'),
(38, '039', 'Encyclopedias in other languages', '030', 1, '2019-09-30 14:49:01', '0000-00-00 00:00:00'),
(39, '050', 'General serial publications', '050', 1, '2019-09-30 14:49:01', '0000-00-00 00:00:00'),
(40, '051', 'Serials in American English', '050', 1, '2019-09-30 14:49:01', '0000-00-00 00:00:00'),
(41, '052', 'Serials in English', '050', 1, '2019-09-30 14:49:01', '0000-00-00 00:00:00'),
(42, '053', 'Serials in other Germanic languages', '050', 1, '2019-09-30 14:49:01', '0000-00-00 00:00:00'),
(43, '054', 'Serials in French, Occitan, and Catalan', '050', 1, '2019-09-30 14:49:01', '0000-00-00 00:00:00'),
(44, '055', 'Serials in Italian, Romanian, and related languages', '050', 1, '2019-09-30 14:49:01', '0000-00-00 00:00:00'),
(45, '056', 'Serials in Spanish & Portuguese', '050', 1, '2019-09-30 14:49:01', '0000-00-00 00:00:00'),
(46, '057', 'Serials in Slavic languages', '050', 1, '2019-09-30 14:49:01', '0000-00-00 00:00:00'),
(47, '058', 'Serials in Scandinavian languages', '050', 1, '2019-09-30 15:04:48', '0000-00-00 00:00:00'),
(48, '059', 'Serials in other languages', '050', 1, '2019-09-30 15:04:48', '0000-00-00 00:00:00'),
(49, '060', 'General organizations & museum science', '060', 1, '2019-09-30 15:04:48', '0000-00-00 00:00:00'),
(50, '061', 'Organizations in North America', '060', 1, '2019-09-30 15:04:48', '0000-00-00 00:00:00'),
(51, '062', 'Organizations in British Isles; in England', '060', 1, '2019-09-30 15:04:48', '0000-00-00 00:00:00'),
(52, '063', 'Organizations in central Europe; in Germany', '060', 1, '2019-09-30 15:04:48', '0000-00-00 00:00:00'),
(53, '064', 'Organizations in France & Monaco', '060', 1, '2019-09-30 15:04:48', '0000-00-00 00:00:00'),
(54, '065', 'Organizations in Italy & adjacent islands', '060', 1, '2019-09-30 15:04:48', '0000-00-00 00:00:00'),
(55, '066', 'Organizations in Iberian peninsula & adjacent islands', '060', 1, '2019-09-30 15:04:48', '0000-00-00 00:00:00'),
(56, '067', 'Organizations in eastern Europe; in Russia', '060', 1, '2019-09-30 15:04:48', '0000-00-00 00:00:00'),
(57, '068', 'Organizations in other geographic areas', '060', 1, '2019-09-30 15:15:25', '0000-00-00 00:00:00'),
(58, '069', 'Museum science', '060', 1, '2019-09-30 15:15:25', '0000-00-00 00:00:00'),
(59, '070', 'News media, journalism, and publishing', '070', 1, '2019-09-30 15:15:25', '0000-00-00 00:00:00'),
(60, '071', 'Newspapers in North America', '070', 1, '2019-09-30 15:15:25', '0000-00-00 00:00:00'),
(61, '072', 'Newspapers in British Isles; in England', '070', 1, '2019-09-30 15:15:25', '0000-00-00 00:00:00'),
(62, '073', 'Newspapers in central Europe; in Germany', '070', 1, '2019-09-30 15:15:25', '0000-00-00 00:00:00'),
(63, '074', 'Newspapers in France & Monaco', '070', 1, '2019-09-30 15:15:25', '0000-00-00 00:00:00'),
(64, '075', 'Newspapers in Italy & adjacent islands', '070', 1, '2019-09-30 15:15:25', '0000-00-00 00:00:00'),
(65, '076', 'Newspapers in Iberian peninsula & adjacent islands', '070', 1, '2019-09-30 15:15:25', '0000-00-00 00:00:00'),
(66, '077', 'Newspapers in eastern Europe; in Russia', '070', 1, '2019-09-30 15:15:25', '0000-00-00 00:00:00'),
(67, '078', 'Newspapers in Scandinavia', '070', 1, '2019-09-30 15:38:28', '0000-00-00 00:00:00'),
(68, '079', 'Newspapers in other geographic areas', '070', 1, '2019-09-30 15:38:28', '0000-00-00 00:00:00'),
(69, '080', 'General collections', '080', 1, '2019-09-30 15:38:28', '0000-00-00 00:00:00'),
(70, '081', 'Collections in American English', '080', 1, '2019-09-30 15:38:28', '0000-00-00 00:00:00'),
(71, '082', 'Collections in English', '080', 1, '2019-09-30 15:38:28', '0000-00-00 00:00:00'),
(72, '083', 'Collections in other Germanic languages', '080', 1, '2019-09-30 15:38:28', '0000-00-00 00:00:00'),
(73, '084', 'Collections in French, Occitan, Catalan', '080', 1, '2019-09-30 15:38:28', '0000-00-00 00:00:00'),
(74, '085', 'Collections in Italian, Romanian, & related languages', '080', 1, '2019-09-30 15:38:28', '0000-00-00 00:00:00'),
(75, '086', 'Collections in Spanish & Portuguese', '080', 1, '2019-09-30 15:38:28', '0000-00-00 00:00:00'),
(76, '087', 'Collections in Slavic languages', '080', 1, '2019-09-30 15:38:28', '0000-00-00 00:00:00'),
(77, '088', 'Collections in Scandinavian languages', '080', 1, '2019-09-30 15:57:01', '0000-00-00 00:00:00'),
(78, '089', 'Collections in other languages', '080', 1, '2019-09-30 15:57:01', '0000-00-00 00:00:00'),
(79, '090', 'Manuscripts and rare books', '090', 1, '2019-09-30 15:57:01', '0000-00-00 00:00:00'),
(80, '091', 'Manuscripts', '090', 1, '2019-09-30 15:57:01', '0000-00-00 00:00:00'),
(81, '092', 'Block books', '090', 1, '2019-09-30 15:57:01', '0000-00-00 00:00:00'),
(82, '093', 'Incunabula', '090', 1, '2019-09-30 15:57:01', '0000-00-00 00:00:00'),
(83, '094', 'Printed books', '090', 1, '2019-09-30 15:57:01', '0000-00-00 00:00:00'),
(84, '095', 'Books notable for bindings', '090', 1, '2019-09-30 15:57:01', '0000-00-00 00:00:00'),
(85, '096', 'Books notable for illustrations', '090', 1, '2019-09-30 15:57:01', '0000-00-00 00:00:00'),
(86, '097', 'Books notable for ownership or origin', '090', 1, '2019-09-30 15:57:01', '0000-00-00 00:00:00'),
(87, '098', 'Prohibited works, forgeries, and hoaxes', '090', 1, '2019-09-30 16:23:46', '0000-00-00 00:00:00'),
(88, '099', 'Books notable for format', '090', 1, '2019-09-30 16:23:46', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
