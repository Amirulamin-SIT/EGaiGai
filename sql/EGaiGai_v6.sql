-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 11, 2020 at 06:08 AM
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
(2, 7, 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `cart_view`
-- (See below for the actual view)
--
CREATE TABLE `cart_view` (
`user_iduser` int(11)
,`iditem` int(11)
,`quantity` int(11)
,`price` decimal(7,2)
,`item_name` varchar(45)
,`total_price` decimal(17,2)
,`disabled` tinyint(4)
,`available` int(1)
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
(1, 'Apple Watch Series 6', '<p>Limit two Apple Watch Series 6 GPS devices and two Apple Watch Series 6 GPS + Cellular devices per customer.</p>\r\n<h3>Features</h3>\r\n<ul>\r\n<li>The stainless steel case is durable and polished to a shiny, mirror-like finish.</li>\r\n<li>The Leather Link is made from handcrafted Roux Granada leather with no clasps or buckles, and has embedded magnets for a secure and adjustable fit.</li>\r\n</ul>', '1099.00', 10, 0, 'MY982_VW_34FR+watch-40-stainless-gold-cell-6s_VW_34FR_WF_CO_GEO_SG.jpeg'),
(2, 'iPad Air 2020 64GB (Black)', '', '879.00', 33, 0, 'ipad-air-select-wifi-spacegray-202009.png'),
(3, 'iPhone 12 (Product Red)', '', '1299.00', 22, 0, 'iphone-12-red-select-2020.png'),
(4, 'iPhone 12 Pro (Pacific Blue)', '', '1649.00', 0, 0, 'iphone-12-pro-blue-hero.png');

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
(50, 7, '2020-11-11 12:56:24', '123', 0, '2020-11-13 12:56:24', 0, 'checkedout');

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
(2, 50, 1, '879.00');

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
(7, 'lesliechiew@gmail.com', '$2y$10$GZrUWj0/nqrtmnPSQBe9QeLEDnmzPIbcPlPeZVx8H1rR3d.BIfAjq', 'ZeroX2F', 1, 0, '5NUB84ZF', '2020-11-11 12:10:12', 0, 'user', '+6590114334');

-- --------------------------------------------------------

--
-- Structure for view `cart_view`
--
DROP TABLE IF EXISTS `cart_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cart_view`  AS SELECT `cart_item`.`user_iduser` AS `user_iduser`, `cart_item`.`item_iditem` AS `iditem`, `cart_item`.`quantity` AS `quantity`, `item`.`price` AS `price`, `item`.`item_Name` AS `item_name`, `cart_item`.`quantity`* `item`.`price` AS `total_price`, `item`.`disabled` AS `disabled`, if(`cart_item`.`quantity` <= `item`.`quantity`,1,0) AS `available` FROM (`cart_item` join `item` on(`cart_item`.`item_iditem` = `item`.`iditem`)) ;

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
  MODIFY `order_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `iduser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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

DELIMITER $$
--
-- Events
--

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
