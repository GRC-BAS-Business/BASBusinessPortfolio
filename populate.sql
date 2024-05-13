-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 10, 2024 at 01:10 AM
-- Server version: 10.6.17-MariaDB
-- PHP Version: 8.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `willia15_basbusinessportfolio`
--

-- --------------------------------------------------------

--
-- Table structure for table `PortfolioItem`
--

CREATE TABLE `PortfolioItem` (
  `ItemID` int(11) NOT NULL,
  `Title` varchar(255) DEFAULT NULL,
  `CreationDate` date DEFAULT NULL,
  `ItemType` varchar(255) DEFAULT NULL,
  `ItemDescription` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `PortfolioTask`
--

CREATE TABLE `PortfolioTask` (
  `TaskID` int(11) NOT NULL,
  `Title` varchar(255) DEFAULT NULL,
  `TaskDescription` varchar(255) DEFAULT NULL,
  `StartDate` date DEFAULT NULL,
  `DueDate` date DEFAULT NULL,
  `CompletionStatus` tinyint(1) DEFAULT NULL,
  `PortfolioItemID` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `PortfolioTimeline`
--

CREATE TABLE `PortfolioTimeline` (
  `TimelineID` int(11) NOT NULL,
  `Title` varchar(255) DEFAULT NULL,
  `CreatedDate` date DEFAULT NULL,
  `PortfolioTaskID` int(11) NOT NULL,
  `PortfolioItemID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Student`
--

CREATE TABLE `Student` (
  `Email` varchar(255) DEFAULT NULL,
  `FirstName` varchar(255) DEFAULT NULL,
  `LastName` varchar(255) DEFAULT NULL,
  `HasGraduated` tinyint(1) DEFAULT NULL,
  `PortfolioTimelineID` int(11) DEFAULT NULL,
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `UserAccount`
--

CREATE TABLE `UserAccount` (
  `Username` varchar(255) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `CreatedDate` date DEFAULT NULL,
  `IsActive` tinyint(1) DEFAULT NULL,
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `PortfolioItem`
--
ALTER TABLE `PortfolioItem`
  ADD PRIMARY KEY (`ItemID`);

--
-- Indexes for table `PortfolioTask`
--
ALTER TABLE `PortfolioTask`
  ADD PRIMARY KEY (`TaskID`),
  ADD KEY `PortfolioItemID` (`PortfolioItemID`);

--
-- Indexes for table `PortfolioTimeline`
--
ALTER TABLE `PortfolioTimeline`
  ADD PRIMARY KEY (`TimelineID`),
  ADD KEY `PortfolioTaskID` (`PortfolioTaskID`),
  ADD KEY `PortfolioID` (`PortfolioItemID`);

--
-- Indexes for table `Student`
--
ALTER TABLE `Student`
  ADD KEY `UserID` (`UserID`),
  ADD KEY `PortfolioTimelineID` (`PortfolioTimelineID`);

--
-- Indexes for table `UserAccount`
--
ALTER TABLE `UserAccount`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `PortfolioItem`
--
ALTER TABLE `PortfolioItem`
  MODIFY `ItemID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `PortfolioTask`
--
ALTER TABLE `PortfolioTask`
  MODIFY `TaskID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `UserAccount`
--
ALTER TABLE `UserAccount`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Student`
--
ALTER TABLE `Student`
  ADD CONSTRAINT `Student_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `UserAccount` (`UserID`),
  ADD CONSTRAINT `Student_ibfk_2` FOREIGN KEY (`PortfolioTimelineID`) REFERENCES `PortfolioTimeline` (`TimelineID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
