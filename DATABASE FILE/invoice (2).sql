-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 16, 2024 at 08:23 PM
-- Server version: 8.0.28
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `invoice`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int NOT NULL,
  `invoice` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address_1` varchar(255) NOT NULL,
  `town` varchar(255) NOT NULL,
  `county` varchar(255) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `name_ship` varchar(255) NOT NULL,
  `address_1_ship` varchar(255) NOT NULL,
  `town_ship` varchar(255) NOT NULL,
  `county_ship` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `invoice` int NOT NULL,
  `orders_id` int NOT NULL,
  `product_vendor` varchar(255) NOT NULL,
  `invoice_date` date NOT NULL,
  `invoice_due_date` date NOT NULL,
  `subtotal` decimal(10,0) NOT NULL,
  `shipping` decimal(10,0) NOT NULL,
  `vat` decimal(10,0) NOT NULL,
  `total` decimal(10,0) NOT NULL,
  `invoice_type` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int NOT NULL,
  `invoice` int NOT NULL,
  `product` varchar(255) NOT NULL,
  `qty` int NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `discount` decimal(10,0) NOT NULL,
  `subtotal` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `status` enum('pending','processed') NOT NULL DEFAULT 'pending',
  `product_name` varchar(255) NOT NULL,
  `product_price` decimal(10,0) NOT NULL,
  `quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `product_desc` varchar(255) NOT NULL,
  `product_vendor` varchar(255) NOT NULL,
  `product_price` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `photo`, `product_desc`, `product_vendor`, `product_price`) VALUES
(1, 'iPhone 14 Pro Max', 'images/iphone.jpeg', 'improved battery life, support 5G connectivity ', 'okumu', 120500),
(2, 'HP Spectre x360 14', 'images/lap.jpeg', '14-inch diagonal 4K OLED touchscreen display,12th Gen Intel Core i7,16GB ram,SSD with capacities of 1TB', 'okumu', 140000),
(3, 'Prada Crocodile Leather Sneakers', 'images/shoe.jpeg', 'Rubber for the outsole,cushioned insoles, padded collars, and breathable linings.', 'reigns', 10000),
(4, 'LG OLED C1 Series', 'images/telv.jpeg', '4K Ultra HD resolution (3840 x 2160 pixels),LG\'s OLED technology,HDR (High Dynamic Range).', 'reigns', 145000),
(5, 'iPhone 14 Pro Max', 'images/iphone-x-gold-black.jpg', 'ProMotion OLED display,latest A-series chip for enhanced performance,latest version of iOS, offering new features, security enhancements, and optimizations.', 'Mark Owino', 135000),
(6, 'HP Spectre x360 15', 'images/laps.jpeg', 'impressive battery life,come with support for active stylus pens, 360-degree hinge', 'Mark Owino', 146000);

-- --------------------------------------------------------

--
-- Table structure for table `store_customers`
--

CREATE TABLE `store_customers` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address_1` varchar(255) NOT NULL,
  `town` varchar(255) NOT NULL,
  `county` varchar(255) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `name_ship` varchar(255) NOT NULL,
  `address_1_ship` varchar(255) NOT NULL,
  `town_ship` varchar(255) NOT NULL,
  `county_ship` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `store_customers`
--

INSERT INTO `store_customers` (`id`, `name`, `email`, `address_1`, `town`, `county`, `phone`, `name_ship`, `address_1_ship`, `town_ship`, `county_ship`) VALUES
(1, 'brian john', 'brianriziki2020@gmail.com', '0741094403', 'Nairobi', 'Kenya', '+254741094403', 'brian john', '0741094403', 'Nairobi', 'Kenya'),
(2, 'Reigns', 'brianriziki2026@gmail.com', '0741094403', 'Nairobi', 'Kenya', '+254741094403', 'Reigns', '0741094403', 'Nairobi', 'Kenya');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `address_1` varchar(255) NOT NULL,
  `county` varchar(255) NOT NULL,
  `town` varchar(255) NOT NULL,
  `role` enum('customer','vendor','admin') DEFAULT 'customer',
  `password` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `phone`, `address_1`, `county`, `town`, `role`, `password`) VALUES
(1, 'boldreigns', 'brianriziki2020@gmail.com', '+254741094403', '0741094403', 'kisumu', 'maseno', 'admin', '$2y$10$voM1iRFxykhsystsE9JxO.VtpcHQVF.qorLU7x2bcxD4pZTPdN4HO'),
(2, 'okumu', 'okumu3030@gmail.com', '+254741094403', '0741094403', 'Nairobi', 'Nairobi', 'vendor', '$2y$10$AnTvUm0Rtfe//xWgGk7.I.l5QBKZZH3HdDoBuN78xpep/zav5zFge'),
(3, 'enock', 'brianriziki2021@gmail.com', '+254741094403', '0741094403', 'Nairobi', 'Nairobi', 'customer', '$2y$10$J/TyIYnoWg8ddpLqU3GJ6uekpwjZTz2q4HkeJj5bHYvaLXUAmiBGS'),
(4, 'were', 'mark@gmail.com', '0796569716', '1143 Kuhl Avenue', 'Nairobi', 'Nairobi', 'customer', '$2y$10$u6smQuX8gQm7yPmVE7Gu8.S34nM4QkPjEE4nRpO9cfaromk3NWw7.'),
(5, 'reigns', 'enockmarkjunior@gmail.com', '+254741094403', 'maseno', 'Nairobi', 'Nairobi', 'vendor', '$2y$10$Gt0nceaSo4k4EnGrJvhpEON49ha6sdMHYyZ9A7nV9W4DN9WlRispW'),
(6, 'Mark Owino', 'markowino418@gmail.com', '+25474109440', 'kisumu west', 'kisumu', 'maseno', 'vendor', '$2y$10$Z1PUw4JBvC5wQlRvD80EzuTAPQlxwM9V4Gbwj8gf4p5EIZE3KTgoS');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_invoice_idx` (`invoice`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`invoice`),
  ADD KEY `fk_orders_id` (`orders_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_invoice_idx` (`invoice`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orders_idx` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `store_customers`
--
ALTER TABLE `store_customers`
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
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `invoice` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `store_customers`
--
ALTER TABLE `store_customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `fk_invoice` FOREIGN KEY (`invoice`) REFERENCES `invoices` (`invoice`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `fk_orders_id` FOREIGN KEY (`orders_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `fk_invoice_items` FOREIGN KEY (`invoice`) REFERENCES `invoices` (`invoice`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
