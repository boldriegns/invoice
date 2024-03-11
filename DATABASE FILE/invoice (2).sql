-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 11, 2024 at 01:42 AM
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

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `invoice`, `name`, `email`, `address_1`, `town`, `county`, `phone`, `name_ship`, `address_1_ship`, `town_ship`, `county_ship`) VALUES
(21, 2, 'okus', 'markow@gmail.com', '1143 Kuhl Avenue', 'Norcross', 'Nairobi', '0796569716', 'okus', '1143 Kuhl Avenue', 'Norcross', 'Nairobi'),
(23, 4, 'were', 'brianriziki2021@gmail.com', 'hewe', 'mwiti', 'winty', '0796569716', 'were', 'hewe', 'mwiti', 'winty'),
(24, 5, 'were', 'brianriziki2021@gmail.com', 'hewe', 'mwiti', 'winty', '0796569716', 'were', 'hewe', 'mwiti', 'winty'),
(25, 1, 'okus', 'markow@gmail.com', '1143 Kuhl Avenue', 'Norcross', 'Nairobi', '0796569716', 'okus', '1143 Kuhl Avenue', 'Norcross', 'Nairobi'),
(26, 3, 'okus', 'markow@gmail.com', '1143 Kuhl Avenue', 'Norcross', 'Nairobi', '0796569716', 'okus', '1143 Kuhl Avenue', 'Norcross', 'Nairobi');

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

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`invoice`, `orders_id`, `product_vendor`, `invoice_date`, `invoice_due_date`, `subtotal`, `shipping`, `vat`, `total`, `invoice_type`, `status`) VALUES
(1, 20, 'Mark Owino', '2024-03-09', '2024-03-15', 6997, 0, 700, 7697, 'invoice', 'paid'),
(2, 21, 'Mark Owino', '2024-03-09', '2024-03-22', 5945, 0, 0, 5945, 'invoice', 'open'),
(3, 22, 'okumu', '2024-03-09', '2024-03-16', 2745, 0, 275, 3020, 'invoice', 'paid'),
(4, 19, 'okumu', '2024-03-09', '2024-03-16', 5245, 0, 525, 5770, 'invoice', 'open'),
(5, 18, 'okumu', '2024-03-09', '2024-03-22', 2749, 0, 275, 3024, 'invoice', 'open');

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

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice`, `product`, `qty`, `price`, `discount`, `subtotal`) VALUES
(21, 2, 'second product', 7, 850, 5, 5945),
(23, 4, '4 product', 5, 1050, 5, 5245),
(24, 5, '3 product', 5, 550, 1, 2749),
(25, 1, '5 product', 4, 1750, 3, 6997),
(26, 3, '3 product', 5, 550, 5, 2745);

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

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `product_id`, `customer_name`, `status`, `product_name`, `product_price`, `quantity`) VALUES
(18, 9, 'were', 'processed', '3 product', 550, 5),
(19, 10, 'were', 'processed', '4 product', 1050, 5),
(20, 11, 'okus', 'processed', '5 product', 1750, 4),
(21, 7, 'okus', 'processed', 'second product', 850, 7),
(22, 9, 'okus', 'processed', '3 product', 550, 5);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_desc` varchar(255) NOT NULL,
  `product_vendor` varchar(255) NOT NULL,
  `product_price` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_desc`, `product_vendor`, `product_price`) VALUES
(6, 'first product', 'high quality', 'okumu', 900),
(7, 'second product', 'high quality', 'Mark Owino', 850),
(9, '3 product', 'high quality', 'okumu', 550),
(10, '4 product', 'high quality', 'okumu', 1050),
(11, '5 product', 'high quality', 'Mark Owino', 1750);

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
(1, 'Enock', 'enockmarkjunior@gmail.com', '0741094403', 'Nairobi', 'Kenya', '+254741094403', 'Enock', '0741094403', 'Nairobi', 'Kenya'),
(2, 'brian john', 'brianriziki2020@gmail.com', '0741094403', 'Nairobi', 'Kenya', '+254741094403', 'brian john', '0741094403', 'Nairobi', 'Kenya');

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
(1, 'Mark Owino', 'mark@gmail.com', '0796569716', 'mwea', 'vihiga', 'norcos', 'vendor', '81dc9bdb52d04dc20036dbd8313ed055'),
(2, 'boldreigns', 'brianriziki2020@gmail.com', '+254741094403', 'luanda', 'kisumu', 'maseno', 'admin', '81dc9bdb52d04dc20036dbd8313ed055'),
(6, 'were', 'brianriziki2021@gmail.com', '0796569716', 'hewe', 'winty', 'mwiti', 'customer', '81dc9bdb52d04dc20036dbd8313ed055'),
(8, 'okus', 'markow@gmail.com', '0796569716', '1143 Kuhl Avenue', 'Nairobi', 'Norcross', 'customer', '81dc9bdb52d04dc20036dbd8313ed055'),
(9, 'okumu', 'ookumu@gmail.com', '0796544676', 'mawe', 'nairobi', 'venus', 'vendor', '81dc9bdb52d04dc20036dbd8313ed055');

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `invoice` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `store_customers`
--
ALTER TABLE `store_customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
