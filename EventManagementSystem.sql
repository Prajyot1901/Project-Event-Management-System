-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 10, 2024 at 12:38 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `EventManagementSystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `Admin`
--

CREATE TABLE `Admin` (
  `Admin_ID` int(11) NOT NULL,
  `Username` varchar(50) DEFAULT NULL,
  `Password` varchar(50) DEFAULT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Email_ID` varchar(100) DEFAULT NULL,
  `Ph_No` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `AdminAuditorium`
--

CREATE TABLE `AdminAuditorium` (
  `Admin_ID` int(11) NOT NULL,
  `Audi_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `AdminEvent`
--

CREATE TABLE `AdminEvent` (
  `AdminEvent_ID` int(11) NOT NULL,
  `Admin_ID` int(11) DEFAULT NULL,
  `Audi_ID` int(11) DEFAULT NULL,
  `Event_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Auditorium`
--

CREATE TABLE `Auditorium` (
  `Audi_ID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Capacity` int(11) NOT NULL,
  `Projector` enum('Yes','No') NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Sound_System` enum('Yes','No') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Events`
--

CREATE TABLE `Events` (
  `Event_ID` int(11) NOT NULL,
  `Capacity` int(11) NOT NULL,
  `Requirements` text DEFAULT NULL,
  `Start_Time` datetime DEFAULT NULL,
  `End_Time` datetime DEFAULT NULL,
  `Projection` varchar(10) DEFAULT NULL,
  `Sound_System` varchar(10) DEFAULT NULL,
  `Status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Feedback`
--

CREATE TABLE `Feedback` (
  `Feedback_ID` int(11) NOT NULL,
  `P_ID` int(11) NOT NULL,
  `Audi_ID` int(11) NOT NULL,
  `Feedback_Text` text DEFAULT NULL,
  `Date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Feedback`
--

-- --------------------------------------------------------

--
-- Table structure for table `Participant`
--

CREATE TABLE `Participant` (
  `P_ID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Email_ID` varchar(100) NOT NULL,
  `Event_ID` int(11) DEFAULT NULL,
  `Event_Name` varchar(255) DEFAULT NULL,
  `Status` enum('pending','approved','disapproved') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `ParticipantAdmin`
--

CREATE TABLE `ParticipantAdmin` (
  `AdminEvent_ID` int(11) NOT NULL,
  `P_ID` int(11) DEFAULT NULL,
  `Admin_ID` int(11) DEFAULT NULL,
  `Event_ID` int(11) DEFAULT NULL,
  `Action` enum('approved','disapproved') DEFAULT NULL,
  `Action_Date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Admin`
--
ALTER TABLE `Admin`
  ADD PRIMARY KEY (`Admin_ID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email_ID` (`Email_ID`);

--
-- Indexes for table `AdminAuditorium`
--
ALTER TABLE `AdminAuditorium`
  ADD PRIMARY KEY (`Admin_ID`,`Audi_ID`),
  ADD KEY `Audi_ID` (`Audi_ID`);

--
-- Indexes for table `AdminEvent`
--
ALTER TABLE `AdminEvent`
  ADD PRIMARY KEY (`AdminEvent_ID`),
  ADD UNIQUE KEY `Admin_ID` (`Admin_ID`,`Audi_ID`,`Event_ID`),
  ADD KEY `Audi_ID` (`Audi_ID`),
  ADD KEY `Event_ID` (`Event_ID`);

--
-- Indexes for table `Auditorium`
--
ALTER TABLE `Auditorium`
  ADD PRIMARY KEY (`Audi_ID`);

--
-- Indexes for table `Events`
--
ALTER TABLE `Events`
  ADD PRIMARY KEY (`Event_ID`);

--
-- Indexes for table `Feedback`
--
ALTER TABLE `Feedback`
  ADD PRIMARY KEY (`Feedback_ID`),
  ADD KEY `P_ID` (`P_ID`),
  ADD KEY `Audi_ID` (`Audi_ID`);

--
-- Indexes for table `Participant`
--
ALTER TABLE `Participant`
  ADD PRIMARY KEY (`P_ID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email_ID` (`Email_ID`),
  ADD KEY `fk_event` (`Event_ID`);

--
-- Indexes for table `ParticipantAdmin`
--
ALTER TABLE `ParticipantAdmin`
  ADD PRIMARY KEY (`AdminEvent_ID`),
  ADD KEY `P_ID` (`P_ID`),
  ADD KEY `Admin_ID` (`Admin_ID`),
  ADD KEY `Event_ID` (`Event_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `AdminEvent`
--
ALTER TABLE `AdminEvent`
  MODIFY `AdminEvent_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Auditorium`
--
ALTER TABLE `Auditorium`
  MODIFY `Audi_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `Events`
--
ALTER TABLE `Events`
  MODIFY `Event_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `Feedback`
--
ALTER TABLE `Feedback`
  MODIFY `Feedback_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `Participant`
--
ALTER TABLE `Participant`
  MODIFY `P_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `ParticipantAdmin`
--
ALTER TABLE `ParticipantAdmin`
  MODIFY `AdminEvent_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `AdminAuditorium`
--
ALTER TABLE `AdminAuditorium`
  ADD CONSTRAINT `adminauditorium_ibfk_1` FOREIGN KEY (`Admin_ID`) REFERENCES `Admin` (`Admin_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `adminauditorium_ibfk_2` FOREIGN KEY (`Audi_ID`) REFERENCES `Auditorium` (`Audi_ID`) ON DELETE CASCADE;

--
-- Constraints for table `AdminEvent`
--
ALTER TABLE `AdminEvent`
  ADD CONSTRAINT `adminevent_ibfk_1` FOREIGN KEY (`Admin_ID`) REFERENCES `Admin` (`Admin_ID`),
  ADD CONSTRAINT `adminevent_ibfk_2` FOREIGN KEY (`Audi_ID`) REFERENCES `Auditorium` (`Audi_ID`),
  ADD CONSTRAINT `adminevent_ibfk_3` FOREIGN KEY (`Event_ID`) REFERENCES `Events` (`Event_ID`);

--
-- Constraints for table `Feedback`
--
ALTER TABLE `Feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`P_ID`) REFERENCES `Participant` (`P_ID`),
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`Audi_ID`) REFERENCES `Auditorium` (`Audi_ID`);

--
-- Constraints for table `Participant`
--
ALTER TABLE `Participant`
  ADD CONSTRAINT `fk_event` FOREIGN KEY (`Event_ID`) REFERENCES `Events` (`Event_ID`) ON DELETE CASCADE;

--
-- Constraints for table `ParticipantAdmin`
--
ALTER TABLE `ParticipantAdmin`
  ADD CONSTRAINT `participantadmin_ibfk_1` FOREIGN KEY (`P_ID`) REFERENCES `Participant` (`P_ID`),
  ADD CONSTRAINT `participantadmin_ibfk_2` FOREIGN KEY (`Admin_ID`) REFERENCES `Admin` (`Admin_ID`),
  ADD CONSTRAINT `participantadmin_ibfk_3` FOREIGN KEY (`Event_ID`) REFERENCES `Events` (`Event_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
