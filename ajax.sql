-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 12, 2021 at 09:41 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ajax`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course`) VALUES
(0, 'visi kursi'),
(1, '1.A'),
(2, '1.C/K'),
(3, '1.D/H/S'),
(4, '1.E'),
(5, '1.F'),
(6, '1.G1'),
(7, '1.G2'),
(8, '1.KT/M'),
(9, '1.P'),
(10, '2.A/D'),
(11, '2.C/K'),
(12, '2.E/P'),
(13, '2.G1'),
(14, '2.G2'),
(15, '2.KT'),
(16, '2.M'),
(17, '2.S/F'),
(18, '3.A/S'),
(19, '3.C'),
(20, '3.D/H'),
(21, '3.G1'),
(22, '3.G2'),
(23, '3.KT'),
(24, '3.P'),
(25, '3.S2'),
(26, '4.A'),
(27, '4.C/P'),
(28, '4.D/H'),
(29, '4.G1'),
(30, '4.G2'),
(31, '4.S');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `type` varchar(255) NOT NULL,
  `everyYear` tinyint(1) NOT NULL,
  `time` time NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `name`, `date`, `type`, `everyYear`, `time`, `description`) VALUES
(2, 'Sieviešu diena', '2021-03-08', 'Svētki', 1, '11:14:01', 'Lorem, ipsum dolor sit amet consectetur adipisicing elit.Lorem, ipsum dolor sit amet consectetur adipisicing elit.Lorem, ipsum dolor sit amet consectetur adipisicing elit.Lorem, ipsum dolor sit amet consectetur adipisicing elit.'),
(42, 'eksāmens', '2021-03-10', 'Eksāmens', 0, '12:00:00', 'dsgdhfgfjgfhdgsfegsrhdtfjhgfghdgsfgrdhfjhhdgsfeasgdhfj'),
(43, 'cits1', '2021-03-11', 'NESAISTĪTS', 0, '12:00:00', 'dsgdhfgfjgfhdgsfegsrhdtfjhgfghdgsfgrdhfjhhdgsfeasgdhfj'),
(44, 'cits2', '2021-03-11', 'NESAISTĪTS', 0, '12:00:00', 'dsgdhfgfjgfhdgsfegsrhdtfjhgfghdgsfgrdhfjhhdgsfeasgdhfj'),
(45, 'cits3', '2021-03-11', 'NESAISTĪTS', 0, '23:19:00', 'dsgdhfgfjgfhdgsfegsrhdtfjhgfghdgsfgrdhfjhhdgsfeasgdhfj'),
(46, 'ekskursija', '2021-03-12', 'Ekskursija', 1, '03:06:00', 'asferwfe'),
(47, 'atceres diena', '2021-03-11', 'Atceres diena', 0, '12:09:00', 'sdsdfsdfsd');

-- --------------------------------------------------------

--
-- Table structure for table `events_courses`
--

CREATE TABLE `events_courses` (
  `eventID` int(11) NOT NULL,
  `courseID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `events_courses`
--

INSERT INTO `events_courses` (`eventID`, `courseID`) VALUES
(2, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Stand-in structure for view `events_out`
-- (See below for the actual view)
--
CREATE TABLE `events_out` (
`id` int(11)
,`courseID` varchar(11)
,`name` varchar(255)
,`date` date
,`type` varchar(255)
,`color` varchar(255)
,`everyYear` tinyint(1)
,`time` time
,`description` text
);

-- --------------------------------------------------------

--
-- Table structure for table `event_colors`
--

CREATE TABLE `event_colors` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `event_colors`
--

INSERT INTO `event_colors` (`id`, `type`, `color`) VALUES
(0, 'default', '#2BFF92'),
(1, 'Svētki', '#ff1c51'),
(2, 'Atceres diena', '#ff1c51'),
(3, 'Ekskursija', '#258fff'),
(4, 'Eksāmens', '#ffe82d');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role`) VALUES
(1, 'skolēns'),
(2, 'skolotājs');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `roleID` int(1) NOT NULL DEFAULT 1,
  `courseID` int(2) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `joined` datetime NOT NULL DEFAULT current_timestamp(),
  `image` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `status`, `roleID`, `courseID`, `username`, `password`, `joined`, `image`) VALUES
(42, 'First', 'Last', '', 1, 12, 'user123', '$argon2i$v=19$m=65536,t=4,p=1$VHlGLktyQkJ3NnplRkJ2MA$jToWc0BAkn//wSyFFLhOkVnJTQBcqrvIyFKRCpYAIqg', '2021-02-19 15:51:38', ''),
(43, 'Filips', 'Šaberts', 'active', 2, 24, 'Filipssh', '$argon2i$v=19$m=65536,t=4,p=1$TDJxdWk0b1hGdlRTOG9Pag$Et9zhB1f+X/JXLhnAuI6TF4sv8m2p/p8zC6srT0qptA', '2021-02-27 21:15:46', 'default.png'),
(44, 'Filips', 'Šaberts', 'active', 1, 24, 'Filipssh2', '$argon2i$v=19$m=65536,t=4,p=1$MS5YR3hOYk1Id1ZZUDAxbg$qcNPDhVlu0Ws2C1UHwUCcDg0NZfKQZKEjJiAOSDOVfI', '2021-03-05 22:01:38', '1614974498.png'),
(46, '&lt;b&gt;filips&lt;/b&gt;', 'Šaberts', 'active', 1, 3, 'Filipssh3', '$argon2i$v=19$m=65536,t=4,p=1$ZU1OaUNtWGdoLnlDNzd4Zw$BW9H1inKsoJFeFedZ42gs96/a2lsc7x+Lq0bcg0sIME', '2021-03-06 22:11:06', 'default.png'),
(47, 'filips', 'Šaberts', 'active', 1, 24, 'Filipssh4', '$argon2i$v=19$m=65536,t=4,p=1$TVV0NmE5VUtVcTJVSzF4UQ$1+yfhdrgrK+CRH2o0Nw9c0JaYx2VKchowuMaYPJWlaI', '2021-03-09 11:00:37', '1615280436.png'),
(48, 'fiieddfdsg', 'dfgdfgdfg', 'active', 1, 2, 'Filipssh5', '$argon2i$v=19$m=65536,t=4,p=1$TUtJSTFkTkovRHJmdk1kMg$lMSEkMlakO8AMAQWTHgigP++ta1WjjLnqzChTyS5iGA', '2021-03-09 11:04:45', 'default.png');

-- --------------------------------------------------------

--
-- Structure for view `events_out`
--
DROP TABLE IF EXISTS `events_out`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `events_out`  AS SELECT `events`.`id` AS `id`, coalesce(`events_courses`.`courseID`,'0') AS `courseID`, `events`.`name` AS `name`, `events`.`date` AS `date`, `events`.`type` AS `type`, coalesce(`event_colors`.`color`,(select `event_colors`.`color` from `event_colors` where `event_colors`.`id` = 0)) AS `color`, `events`.`everyYear` AS `everyYear`, `events`.`time` AS `time`, `events`.`description` AS `description` FROM ((`events` left join `event_colors` on(`events`.`type` = `event_colors`.`type`)) left join `events_courses` on(`events_courses`.`eventID` = `events`.`id`)) ORDER BY `events`.`id` ASC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events_courses`
--
ALTER TABLE `events_courses`
  ADD PRIMARY KEY (`eventID`,`courseID`),
  ADD KEY `courseID` (`courseID`);

--
-- Indexes for table `event_colors`
--
ALTER TABLE `event_colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roleID` (`roleID`),
  ADD KEY `courseID` (`courseID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `event_colors`
--
ALTER TABLE `event_colors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events_courses`
--
ALTER TABLE `events_courses`
  ADD CONSTRAINT `events_courses_ibfk_1` FOREIGN KEY (`eventID`) REFERENCES `events` (`id`),
  ADD CONSTRAINT `events_courses_ibfk_2` FOREIGN KEY (`courseID`) REFERENCES `courses` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`roleID`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`courseID`) REFERENCES `courses` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
