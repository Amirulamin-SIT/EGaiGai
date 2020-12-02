-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2020 at 08:05 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `iduser` int(11) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password_hash` varchar(90) NOT NULL,
  `username` varchar(45) DEFAULT NULL,
  `verified` tinyint(4) DEFAULT 0,
  `login_fail_amt` int(11) DEFAULT 0,
  `token` varchar(90) DEFAULT NULL,
  `token_time` datetime DEFAULT NULL,
  `disabled` tinyint(4) DEFAULT 0,
  `type` varchar(10) DEFAULT 'user',
  `hp_num` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`iduser`, `email`, `password_hash`, `username`, `verified`, `login_fail_amt`, `token`, `token_time`, `disabled`, `type`, `hp_num`) VALUES
(7, 'lesliechiew@gmail.com', '$2y$10$GZrUWj0/nqrtmnPSQBe9QeLEDnmzPIbcPlPeZVx8H1rR3d.BIfAjq', 'ZeroX2F', 1, 0, NULL, NULL, 0, 'user', '+6590114334'),
(8, 'soen0x7e3@gmail.com', '$2y$10$FsTzxX8W4nqs8ODSZKDY/uw9FK9QDwuIHMl1ijeyFflosGZ3xDlsm', 'Zero', 1, 0, NULL, NULL, 0, 'user', '+6590114331');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`iduser`),
  ADD UNIQUE KEY `token_UNIQUE` (`token`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `iduser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
