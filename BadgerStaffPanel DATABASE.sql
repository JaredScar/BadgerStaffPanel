-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 21, 2020 at 10:50 PM
-- Server version: 10.2.31-MariaDB
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `badgerst_primerp`
--

-- --------------------------------------------------------

--
-- Table structure for table `Bans`
--

CREATE TABLE `Bans` (
  `User_ID` int(16) NOT NULL,
  `steamIdStaff` varchar(32) NOT NULL,
  `steamIdPlayer` varchar(32) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `uid` int(9) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Kicks`
--

CREATE TABLE `Kicks` (
  `User_ID` int(16) NOT NULL,
  `steamIdStaff` varchar(32) NOT NULL,
  `steamIdPlayer` varchar(32) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `uid` int(9) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Logger`
--

CREATE TABLE `Logger` (
  `Punish_ID` int(255) NOT NULL,
  `Punish_Type` varchar(9) NOT NULL,
  `Punished_By_steamID` varchar(255) NOT NULL,
  `ID_Punished` int(255) NOT NULL,
  `Data` varchar(1024) NOT NULL,
  `Action_Date` varchar(255) NOT NULL,
  `Action` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Notes`
--

CREATE TABLE `Notes` (
  `User_ID` int(16) NOT NULL,
  `steamIdStaff` varchar(32) NOT NULL,
  `steamIdPlayer` varchar(32) NOT NULL,
  `note` varchar(255) NOT NULL,
  `uid` int(9) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Tempbans`
--

CREATE TABLE `Tempbans` (
  `User_ID` int(16) NOT NULL,
  `steamIdStaff` varchar(32) NOT NULL,
  `steamIdPlayer` varchar(32) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `endDate` int(32) NOT NULL,
  `uid` int(9) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `ID` int(64) NOT NULL,
  `steamID` varchar(32) NOT NULL,
  `lastPlayerName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `gameLicense` varchar(128) NOT NULL,
  `live` varchar(128) NOT NULL,
  `xbl` varchar(128) NOT NULL,
  `discord` varchar(128) NOT NULL,
  `ip` varchar(128) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Warns`
--

CREATE TABLE `Warns` (
  `User_ID` int(16) NOT NULL,
  `steamIdStaff` varchar(32) NOT NULL,
  `steamIdPlayer` varchar(32) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `uid` int(9) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Bans`
--
ALTER TABLE `Bans`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `Kicks`
--
ALTER TABLE `Kicks`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `Logger`
--
ALTER TABLE `Logger`
  ADD PRIMARY KEY (`Punish_ID`);

--
-- Indexes for table `Notes`
--
ALTER TABLE `Notes`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `Tempbans`
--
ALTER TABLE `Tempbans`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `ID` (`ID`);

--
-- Indexes for table `Warns`
--
ALTER TABLE `Warns`
  ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Bans`
--
ALTER TABLE `Bans`
  MODIFY `uid` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Kicks`
--
ALTER TABLE `Kicks`
  MODIFY `uid` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Logger`
--
ALTER TABLE `Logger`
  MODIFY `Punish_ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Notes`
--
ALTER TABLE `Notes`
  MODIFY `uid` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Tempbans`
--
ALTER TABLE `Tempbans`
  MODIFY `uid` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `ID` int(64) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Warns`
--
ALTER TABLE `Warns`
  MODIFY `uid` int(9) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
