-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 24, 2022 at 08:43 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `seproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `accountID` int(12) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`accountID`, `username`, `password`, `firstname`, `lastname`) VALUES
(0, 'admin', '$2y$10$Xq6pV6UoG8FQQGNm0McfseOVwnwxqmz9w1D5lGZh9k4TdcX043vUm', 'Admin', 'Tesla'),
(1, 'felix', '$2y$10$lZcKYinz6a/Uw4y0HoPcfuOHLun5q2OTtIgycuYCyRzhOBJsb6OPG', 'Felix', 'Ganteng'),
(2, 'bryan', '$2y$10$RvKzv80hzVAGrAWjtVQHSOfgJgQOf4M2Lohua8KOaLMnNIl4RflsW', 'Bryan', 'Felix'),
(19, 'Fourier', '$2y$10$C6YGvxBucojyUgD.45EWM.70xDYRaHzhS0LyEPd3nTsqHQ6oxVy3O', 'Joseph', 'Fourier');

-- --------------------------------------------------------

--
-- Table structure for table `accounts_groups`
--

CREATE TABLE `accounts_groups` (
  `accountID` int(12) NOT NULL,
  `groupID` int(12) NOT NULL,
  `positionID` int(12) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `accounts_groups`
--

INSERT INTO `accounts_groups` (`accountID`, `groupID`, `positionID`) VALUES
(0, 1, 1),
(1, 3, 3),
(0, 3, 4),
(2, 3, 4),
(2, 1, 21),
(2, 27, 22),
(2, 35, 23),
(2, 36, 24);

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `assignmentID` int(12) NOT NULL,
  `groupID` int(11) NOT NULL,
  `assignmentTitle` varchar(255) NOT NULL,
  `assignmentDescription` varchar(255) NOT NULL,
  `assignmentCreated` date NOT NULL,
  `assignmentDeadline` date NOT NULL,
  `assignedTo` int(12) DEFAULT 0,
  `assignmentStatus` int(12) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`assignmentID`, `groupID`, `assignmentTitle`, `assignmentDescription`, `assignmentCreated`, `assignmentDeadline`, `assignedTo`, `assignmentStatus`) VALUES
(1, 1, 'Kerjakan Soal SE', '1. LALALA\r\n2. BBB\r\n3. DDD', '2022-03-21', '2022-03-24', 0, 3),
(2, 1, 'Kerjakan Soal Kalkulus', 'yeyeyeye', '2022-03-21', '2022-03-24', 1, 0),
(3, 1, 'Kerjakan soal PDE', 'yeyeyeye', '2022-03-21', '2022-03-23', 1, 0),
(4, 3, 'Kerjakan soal Real Analysis', 'yeyeyeye', '2022-03-21', '2022-03-24', 0, 1),
(5, 3, 'Kerjakan soal Modern Algebra', 'wawawaw', '2022-03-21', '2022-03-26', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `groupID` int(12) NOT NULL,
  `groupOwner` int(12) NOT NULL,
  `groupName` varchar(255) NOT NULL,
  `groupDetail` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`groupID`, `groupOwner`, `groupName`, `groupDetail`) VALUES
(1, 0, 'Group 1 uid 1', 'Group 1 UID 1'),
(3, 1, 'Group 2 uid 3', 'Group 2 UID 3'),
(27, 2, 'Group 3 ', ''),
(34, 2, 'testt', 'teztst'),
(35, 2, 'testt', 'teztst'),
(36, 2, 'Group Baru Bryan', '');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notificationID` int(11) NOT NULL,
  `accountID` int(11) NOT NULL,
  `notificationTitle` varchar(255) NOT NULL,
  `notificationType` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `positionID` int(12) NOT NULL,
  `groupID` int(12) NOT NULL,
  `positionName` varchar(255) NOT NULL,
  `positionValue` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`positionID`, `groupID`, `positionName`, `positionValue`) VALUES
(1, 1, 'Owner Group 1 UID 1', 1),
(2, 1, 'Wakil Group 1 UID 1', 2),
(3, 3, 'Owner Group 2 UID 3', 1),
(4, 3, 'Wakil Owner Group 2 uid 3', 2),
(17, 1, 'Sekretaris Group 1 UID 1', 4),
(21, 1, 'Bendahara Group 1 UID 1', 3),
(22, 27, 'Owner', 1),
(23, 35, 'Owner Baru', 1),
(24, 36, 'Owner', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`accountID`);

--
-- Indexes for table `accounts_groups`
--
ALTER TABLE `accounts_groups`
  ADD PRIMARY KEY (`accountID`,`groupID`),
  ADD KEY `accountID` (`accountID`),
  ADD KEY `groupID` (`groupID`),
  ADD KEY `positionID` (`positionID`);

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`assignmentID`),
  ADD KEY `groupID` (`groupID`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`groupID`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notificationID`),
  ADD KEY `accountID` (`accountID`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`positionID`),
  ADD KEY `groupID` (`groupID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `accountID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `assignmentID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `groupID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notificationID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `positionID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts_groups`
--
ALTER TABLE `accounts_groups`
  ADD CONSTRAINT `accounts_groups_ibfk_1` FOREIGN KEY (`accountID`) REFERENCES `accounts` (`accountID`),
  ADD CONSTRAINT `accounts_groups_ibfk_2` FOREIGN KEY (`groupID`) REFERENCES `groups` (`groupID`),
  ADD CONSTRAINT `accounts_groups_ibfk_3` FOREIGN KEY (`positionID`) REFERENCES `positions` (`positionID`);

--
-- Constraints for table `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `assignments_ibfk_1` FOREIGN KEY (`groupID`) REFERENCES `groups` (`groupID`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`accountID`) REFERENCES `accounts` (`accountID`);

--
-- Constraints for table `positions`
--
ALTER TABLE `positions`
  ADD CONSTRAINT `positions_ibfk_1` FOREIGN KEY (`groupID`) REFERENCES `groups` (`groupID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
