USE `mydb`;

INSERT INTO `user` (`iduser`, `email`, `password_hash`, `username`, `verified`, `login_fail_amt`, `token`, `token_time`, `disabled`, `type`, `hp_num`) VALUES
(7, 'lesliechiew@gmail.com', '$2y$10$GZrUWj0/nqrtmnPSQBe9QeLEDnmzPIbcPlPeZVx8H1rR3d.BIfAjq', 'ZeroX2F', 1, 0, '5NUB84ZF', '2020-11-11 12:10:12', 0, 'user', '+6590114334');

INSERT INTO `item` (`iditem`, `item_Name`, `description`, `price`, `quantity`, `disabled`, `image_location`) VALUES
(1, 'Apple Watch Series 6', '<p>Limit two Apple Watch Series 6 GPS devices and two Apple Watch Series 6 GPS + Cellular devices per customer.</p>\r\n<h3>Features</h3>\r\n<ul>\r\n<li>The stainless steel case is durable and polished to a shiny, mirror-like finish.</li>\r\n<li>The Leather Link is made from handcrafted Roux Granada leather with no clasps or buckles, and has embedded magnets for a secure and adjustable fit.</li>\r\n</ul>', '1099.00', 10, 0, 'MY982_VW_34FR+watch-40-stainless-gold-cell-6s_VW_34FR_WF_CO_GEO_SG.jpeg'),
(2, 'iPad Air 2020 64GB (Black)', '', '879.00', 33, 0, 'ipad-air-select-wifi-spacegray-202009.png'),
(3, 'iPhone 12 (Product Red)', '', '1299.00', 22, 0, 'iphone-12-red-select-2020.png'),
(4, 'iPhone 12 Pro (Pacific Blue)', '', '1649.00', 0, 0, 'iphone-12-pro-blue-hero.png');

INSERT INTO `orders` (`order_no`, `user_iduser`, `date`, `delivery_address`, `delivered`, `timeout`, `disabled`, `status`) VALUES
(50, 7, '2020-11-11 12:56:24', '123', 0, '2020-11-13 12:56:24', 0, 'checkedout');

INSERT INTO `cart_item` (`item_iditem`, `user_iduser`, `quantity`) VALUES
(2, 7, 1);

INSERT INTO `order_item` (`item_iditem`, `orders_order_no`, `quantity`, `item_price`) VALUES
(2, 50, 1, '879.00');