-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 05, 2024 at 05:41 PM
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
-- Database: `csg2431: interactive web development(Advanced)`
--
CREATE DATABASE IF NOT EXISTS `csg2431: interactive web development (Advanced)` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `csg2431: interactive web development (Advanced)`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- RELATIONSHIPS FOR TABLE `admin`:
--

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`username`, `password`) VALUES
('admin', ' $2a$12$isX/D7vYAwMW6s1JhIkhv.f6xrcuJgoS5r1pC8RTtOuNvq33cfuci '),
('Sebbs', '12345678');

-- --------------------------------------------------------

--
-- Table structure for table `attendee`
--

CREATE TABLE `attendee` (
  `mobile_number` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `dob` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- RELATIONSHIPS FOR TABLE `attendee`:
--

--
-- Dumping data for table `attendee`
--

INSERT INTO `attendee` (`mobile_number`, `first_name`, `surname`, `password`, `dob`) VALUES
('0402413949', 'Ethan', 'Hunter', '$2y$10$Djh12DrsBctJ9dL4AZtP.uXq94DiWbAROUFQr0fimd8G6Gmy8G2Me', '2024-09-28'),
('0402445747', 'Steven', 'Miller', '$2y$10$JGNsWBK5kDoK7o15xlhk5uu13xKexePAjXFOJyBhTsZFCHrjUT7eW', '2024-09-28'),
('0402449784', 'Amanda', 'Hill', '$2y$10$GR9apuKCSSxVS2c55Q9zCuyEX1SEUWK2Q2Kuyjpw4kD1HCAHnGLWe', '2024-09-28'),
('0403215486', 'Raymond', 'Price', '$2y$10$adSfVh61OCWAkrt5vKj7eeG8fAsGs9BUw9JKfRyv7GasnSGzq1CV.', '2024-09-28'),
('0404477819', 'Mark', 'Griffin', '$2y$10$CzmIXtWyrTFI6kBw6GwQseElFEY6GS6PVXHujyaIWItoUU/w9Lubq', '2024-09-28'),
('0405413987', 'Natalie', 'Brooks', '$2y$10$X2sLDpn02ZsxPCfv6e8u6u6ARNwbPCu9oqQy5Byf/UDyLC27GkJR2', '2024-09-28'),
('0405896324', 'Sarah', 'Collier', '$2y$10$yvbse7FAddRO9JwVJCLmlet.vFVunY5LPtzlYcoGiqP.1I.1rVkpe', '2024-09-28'),
('0406649884', 'Rachel', 'Stark', '$2y$10$IaMt4zdwnQHpPUSyqJBgtOdo8wemncOgjTUZoSt6cs4jyhDQisGIS', '2024-09-28'),
('0407788149', 'Jessica', 'Bell', '$2y$10$cS6Wlz5RcYv2oKGqBOpPBOMujDE09GzDNVDyHw8eMjPBxFhKxLAEm', '2024-09-28'),
('0413062101', 'David', 'Denholm', '12345678', '1983-07-17'),
('0413062102', 'David', 'Denholm', '12345678', '2013-07-26'),
('0413062201', 'Test', 'User', '$2y$10$uA4GIb3TMt9XJ6Qqm4Pt2O86ywAxkT3312p.ULxohYtSwYI59IHWK', '2009-01-03');

-- --------------------------------------------------------

--
-- Table structure for table `band`
--

CREATE TABLE `band` (
  `band_id` smallint(6) NOT NULL,
  `band_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- RELATIONSHIPS FOR TABLE `band`:
--

--
-- Dumping data for table `band`
--

INSERT INTO `band` (`band_id`, `band_name`) VALUES
(28, '12124'),
(19, 'Baby Animals'),
(16, 'Birds of Tokyo'),
(18, 'Eskimo Joe'),
(14, 'Gyroscope'),
(15, 'Jebediah'),
(22, 'Karnivool'),
(21, 'Little Birdy'),
(17, 'Pond'),
(20, 'The Sleepy Jackson');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(10) UNSIGNED NOT NULL,
  `mobile_number` varchar(255) NOT NULL,
  `concert_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- RELATIONSHIPS FOR TABLE `booking`:
--   `mobile_number`
--       `attendee` -> `mobile_number`
--   `concert_id`
--       `concert` -> `concert_id`
--

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `mobile_number`, `concert_id`) VALUES
(1, '0402449784', 11),
(48, '0413062101', 22),
(58, '0413062101', 11),
(73, '0413062102', 15),
(74, '0413062102', 18),
(75, '0413062102', 16),
(76, '0413062102', 12),
(77, '0413062101', 18),
(79, '0413062201', 15);

-- --------------------------------------------------------

--
-- Table structure for table `concert`
--

CREATE TABLE `concert` (
  `concert_id` int(10) UNSIGNED NOT NULL,
  `band_id` smallint(6) DEFAULT NULL,
  `venue_id` smallint(6) DEFAULT NULL,
  `concert_date` datetime DEFAULT NULL,
  `adult` varchar(1) NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- RELATIONSHIPS FOR TABLE `concert`:
--   `band_id`
--       `band` -> `band_id`
--   `venue_id`
--       `venue` -> `venue_id`
--

--
-- Dumping data for table `concert`
--

INSERT INTO `concert` (`concert_id`, `band_id`, `venue_id`, `concert_date`, `adult`) VALUES
(11, 15, 7, '2024-09-29 22:00:00', 'N'),
(12, 16, 5, '2024-11-02 21:30:00', 'N'),
(14, 14, 5, '2024-11-08 22:10:00', 'N'),
(15, 14, 5, '2024-10-18 10:10:00', 'N'),
(16, 16, 5, '2024-11-01 20:21:00', 'N'),
(18, 14, 5, '2024-10-05 20:20:00', 'N'),
(22, 15, 9, '2024-12-01 22:00:00', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `venue`
--

CREATE TABLE `venue` (
  `venue_id` smallint(6) NOT NULL,
  `venue_name` varchar(255) DEFAULT NULL,
  `capacity` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- RELATIONSHIPS FOR TABLE `venue`:
--

--
-- Dumping data for table `venue`
--

INSERT INTO `venue` (`venue_id`, `venue_name`, `capacity`) VALUES
(5, 'Perth Concert Hall', 300),
(6, 'Optus Stadium', 0),
(7, 'His Majesty\'s Theatre', 200),
(8, 'Crown Theatre Perth', 1),
(9, 'Astor Theatre Perth', 10);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `attendee`
--
ALTER TABLE `attendee`
  ADD PRIMARY KEY (`mobile_number`);

--
-- Indexes for table `band`
--
ALTER TABLE `band`
  ADD PRIMARY KEY (`band_id`),
  ADD UNIQUE KEY `band_name` (`band_name`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `mobile_number` (`mobile_number`),
  ADD KEY `concert_id` (`concert_id`);

--
-- Indexes for table `concert`
--
ALTER TABLE `concert`
  ADD PRIMARY KEY (`concert_id`),
  ADD KEY `band_id` (`band_id`),
  ADD KEY `venue_id` (`venue_id`);

--
-- Indexes for table `venue`
--
ALTER TABLE `venue`
  ADD PRIMARY KEY (`venue_id`),
  ADD UNIQUE KEY `venue_name` (`venue_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `band`
--
ALTER TABLE `band`
  MODIFY `band_id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `concert`
--
ALTER TABLE `concert`
  MODIFY `concert_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `venue`
--
ALTER TABLE `venue`
  MODIFY `venue_id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`mobile_number`) REFERENCES `attendee` (`mobile_number`),
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`concert_id`) REFERENCES `concert` (`concert_id`);

--
-- Constraints for table `concert`
--
ALTER TABLE `concert`
  ADD CONSTRAINT `concert_ibfk_1` FOREIGN KEY (`band_id`) REFERENCES `band` (`band_id`),
  ADD CONSTRAINT `concert_ibfk_2` FOREIGN KEY (`venue_id`) REFERENCES `venue` (`venue_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
