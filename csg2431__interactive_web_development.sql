-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 15, 2024 at 07:08 PM
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
-- Database: `csg2431: interactive web development`
--
CREATE DATABASE IF NOT EXISTS `csg2431: interactive web development` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `csg2431: interactive web development`;

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

-- --------------------------------------------------------

--
-- Table structure for table `attendee`
--

CREATE TABLE `attendee` (
  `mobile_number` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `dob` DATE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- RELATIONSHIPS FOR TABLE `attendee`:
--

--
-- Dumping data for table `attendee`
--

INSERT INTO `attendee` (`mobile_number`, `first_name`, `surname`, `password`, `dob`) VALUES
('0402413949', 'Ethan', 'Hunter', '12345678', '2024-09-28'),
('0402445747', 'Steven', 'Miller', '12345678', '2024-09-28'),
('0402449784', 'Amanda', 'Hill', '12345678', '2024-09-28'),
('0403215486', 'Raymond', 'Price', '12345678', '2024-09-28'),
('0404477819', 'Mark', 'Griffin', '12345678', '2024-09-28'),
('0405413987', 'Natalie', 'Brooks', '12345678', '2024-09-28'),
('0405896324', 'Sarah', 'Collier', '12345678', '2024-09-28'),
('0406649884', 'Rachel', 'Stark', '12345678', '2024-09-28'),
('0407788149', 'Jessica', 'Bell', '12345678', '2024-09-28');

-- --------------------------------------------------------

--
-- Table structure for table `band`
--

CREATE TABLE `band` (
  `band_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `band_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`band_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- RELATIONSHIPS FOR TABLE `band`:
--

--
-- Dumping data for table `band`
--

INSERT INTO `band` (`band_id`, `band_name`) VALUES
(19, 'Baby Animals'),
(16, 'Birds of Tokyo'),
(18, 'Eskimo Joe'),
(14, 'Gyroscope'),
(15, 'Jebediah'),
(22, 'Karnivool'),
(21, 'Little Birdy'),
(23, 'Pendulum'),
(17, 'Pond'),
(20, 'The Sleepy Jackson');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `mobile_number` varchar(255) DEFAULT NULL,
  `concert_id` int(10) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`booking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- RELATIONSHIPS FOR TABLE `booking`:
--   `mobile_number`
--       `attendee` -> `mobile_number`
--   `concert_id`
--       `concert` -> `concert_id`
--

-- --------------------------------------------------------

--
-- Table structure for table `concert`
--

CREATE TABLE `concert` (
  `concert_id` int(10) UNSIGNED NOT NULL,
  `band_id` smallint(6) DEFAULT NULL,
  `venue_id` smallint(6) DEFAULT NULL,
  `concert_date` datetime DEFAULT NULL
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

INSERT INTO `concert` (`concert_id`, `band_id`, `venue_id`, `concert_date`) VALUES
(11, 15, 7, '2024-09-28'),
(12, 16, 5, '2024-11-02'),
(13, 18, 6, '2024-11-22');

-- --------------------------------------------------------

--
-- Table structure for table `venue`
--

CREATE TABLE `venue` (
  `venue_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `venue_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`venue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- RELATIONSHIPS FOR TABLE `venue`:
--

--
-- Dumping data for table `venue`
--

INSERT INTO `venue` (`venue_id`, `venue_name`) VALUES
(9, 'Astor Theatre Perth'),
(8, 'Crown Theatre Perth'),
(10, 'HBF Arena'),
(7, 'His Majesty\'s Theatre'),
(6, 'Optus Stadium'),
(5, 'Perth Concert Hall');

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
  ADD UNIQUE KEY `band_name` (`band_name`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
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
  ADD UNIQUE KEY `venue_name` (`venue_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `band`
--
ALTER TABLE `band`
  MODIFY `band_id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `concert`
--
ALTER TABLE `concert`
  MODIFY `concert_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `venue`
--
ALTER TABLE `venue`
  MODIFY `venue_id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
