-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2020 at 04:16 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET GLOBAL event_scheduler = ON;
SET SQL_SAFE_UPDATES = 0;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_item`
--

CREATE TABLE `cart_item` (
  `item_iditem` int(11) NOT NULL,
  `user_iduser` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cart_item`
--

INSERT INTO `cart_item` (`item_iditem`, `user_iduser`, `quantity`) VALUES
(1, 7, 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `cart_view`
-- (See below for the actual view)
--
CREATE TABLE `cart_view` (
`user_iduser` int(11)
,`quantity` int(11)
,`price` decimal(7,2)
,`item_name` varchar(45)
,`item_id` int(11)
,`image_location` varchar(256)
);

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `iditem` int(11) NOT NULL,
  `item_Name` varchar(45) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(7,2) DEFAULT 0.00,
  `quantity` int(11) DEFAULT 0,
  `disabled` tinyint(4) DEFAULT 0,
  `image_location` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`iditem`, `item_Name`, `description`, `price`, `quantity`, `disabled`, `image_location`) VALUES
(1, 'Apple Watch Series 6', '<p>Limit two Apple Watch Series 6 GPS devices and two Apple Watch Series 6 GPS + Cellular devices per customer.</p>\r\n<h3>Features</h3>\r\n<ul>\r\n<li>The stainless steel case is durable and polished to a shiny, mirror-like finish.</li>\r\n<li>The Leather Link is made from handcrafted Roux Granada leather with no clasps or buckles, and has embedded magnets for a secure and adjustable fit.</li>\r\n</ul>', '1099.00', 7, 0, 'a7967537f6cfed55ea4634d92d9de30ccebec2767e7c2dfaeb3b0942584e2ba1.jpeg'),
(2, 'iPad Air 2020 64GB (Black)', '', '879.00', 18, 0, '02073f03fb37b447ed74d68e4e3e652ac7da51432f6abb9848f735790000387f.png'),
(3, 'iPhone 12 (Product Red)', '', '1299.00', 21, 0, 'c43ab6fd307e922dc310c9d0a3d19a43b1c0709e1d3bbeb880de68c8d02f9ae8.png'),
(4, 'iPhone 12 Pro (Pacific Blue)', '', '1649.00', 1000, 0, '330cc7604060f5e7a6a141a7a63383bea57c35da9b75f4173ce29d4e81dac0d6.png');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_no` int(11) NOT NULL,
  `user_iduser` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `delivery_address` varchar(256) DEFAULT NULL,
  `delivered` tinyint(4) DEFAULT 0,
  `timeout` datetime DEFAULT NULL,
  `disabled` tinyint(4) DEFAULT 0,
  `status` varchar(10) DEFAULT 'unknown'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_no`, `user_iduser`, `date`, `delivery_address`, `delivered`, `timeout`, `disabled`, `status`) VALUES
(64, 7, '2020-11-12 00:31:05', ' ', 0, '2020-11-14 00:31:05', 1, 'incheckout'),
(65, 7, '2020-11-12 00:31:08', '123', 0, '2020-11-14 00:31:08', 0, 'PAID'),
(66, 7, '2020-11-12 00:49:31', '1234', 0, '2020-11-14 00:49:31', 1, 'checkedout'),
(67, 8, '2020-11-14 01:32:08', '1234', 0, '2020-11-16 01:32:08', 0, 'PAID');

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `item_iditem` int(11) NOT NULL,
  `orders_order_no` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `item_price` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`item_iditem`, `orders_order_no`, `quantity`, `item_price`) VALUES
(1, 64, 1, '1099.00'),
(1, 65, 1, '1099.00'),
(2, 66, 1, '879.00'),
(3, 67, 1, '1299.00');

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
(7, 'lesliechiew@gmail.com', '$2y$10$GZrUWj0/nqrtmnPSQBe9QeLEDnmzPIbcPlPeZVx8H1rR3d.BIfAjq', 'ZeroX2F', 1, 0, 'PACNN57B', '2020-11-15 11:02:32', 0, 'user', '+6590114334'),
(8, 'soen0x7e3@gmail.com', '$2y$10$FsTzxX8W4nqs8ODSZKDY/uw9FK9QDwuIHMl1ijeyFflosGZ3xDlsm', 'Zero', 1, 0, NULL, NULL, 0, 'user', '+6590114331'),
(9, 'lesliechiew@gmail.com2', '$2y$10$x40S04hfOWjYVqTK0GO29eUrY42KMZLnx3FIYHaVEHL0DMqdihTgy', 'Zerox@', 0, 0, 'KH5U1Q5IN', '2020-11-15 01:10:41', 0, 'user', '+6590114323');

-- --------------------------------------------------------

--
-- Structure for view `cart_view`
--
DROP TABLE IF EXISTS `cart_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cart_view`  AS SELECT `c`.`user_iduser` AS `user_iduser`, `c`.`quantity` AS `quantity`,if(`c`.`quantity` <= `i`.`quantity`,1,0) AS available, `i`.`price` AS `price`, `i`.`item_Name` AS `item_name`, `i`.`disabled` AS `disabled`, `c`.`item_iditem` AS `item_id`, `i`.`image_location` AS `image_location` FROM (`cart_item` `c` join `item` `i`) WHERE `c`.`item_iditem` = `i`.`iditem` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD KEY `fk_cart_items_item1_idx` (`item_iditem`),
  ADD KEY `fk_cart_items_user1_idx` (`user_iduser`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`iditem`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_no`,`date`),
  ADD KEY `fk_orders_user1_idx` (`user_iduser`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD KEY `fk_order_items_item1_idx` (`item_iditem`),
  ADD KEY `fk_order_items_orders1_idx` (`orders_order_no`);

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
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `iditem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `iduser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD CONSTRAINT `fk_cart_items_item1` FOREIGN KEY (`item_iditem`) REFERENCES `item` (`iditem`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cart_items_user1` FOREIGN KEY (`user_iduser`) REFERENCES `user` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user1` FOREIGN KEY (`user_iduser`) REFERENCES `user` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `fk_order_items_item1` FOREIGN KEY (`item_iditem`) REFERENCES `item` (`iditem`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_order_items_orders1` FOREIGN KEY (`orders_order_no`) REFERENCES `orders` (`order_no`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
