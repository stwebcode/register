-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 18, 2021 at 11:57 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.0

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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `courseID` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `joined` datetime NOT NULL DEFAULT current_timestamp(),
  `image` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `courseID`, `username`, `password`, `joined`, `image`) VALUES
(1, '', '', 0, 'user1', 'abcd', '2021-01-19 14:18:48', ''),
(10, '', '', 0, 'user2', '123456', '2021-02-07 10:33:15', ''),
(11, '', '', 0, 'user3', '123456', '2021-02-07 10:40:32', ''),
(24, '', '', 0, 'user4', '$argon2i$v=19$m=65536,t=4,p=1$bUhiODlzbDM3bXRpVW9TMw$HO6l7CpnTzAe7tguBygTOEU9Zmf3zGHzGRFh71O0XpU', '2021-02-16 17:59:15', '1613491155.png'),
(39, 'Name', 'Lastname', 31, 'user5', '$argon2i$v=19$m=65536,t=4,p=1$Z05iSWo1Sndab25CeGY3SQ$UmZdXrwS5Dc9VAqHCAOhP/DtDNmwiA7Ojy1br9IAmX8', '2021-02-18 12:56:40', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
