-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 18, 2024 at 08:41 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `computer_product_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `Apartment_no` int(10) DEFAULT NULL,
  `Street_Name` varchar(200) DEFAULT NULL,
  `State` varchar(255) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL,
  `Pincode` int(6) DEFAULT NULL,
  `Customer_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`Apartment_no`, `Street_Name`, `State`, `City`, `Pincode`, `Customer_ID`) VALUES
(5, 'Bashundhara', 'Dhaka', 'Dhaka', 1000, 3),
(9, 'Mirpur', 'Dhaka', 'Dhaka', 1000, 2);

-- --------------------------------------------------------

--
-- Table structure for table `administrator`
--

CREATE TABLE `administrator` (
  `Admin_ID` int(11) NOT NULL,
  `Admin_Name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `administrator`
--

INSERT INTO `administrator` (`Admin_ID`, `Admin_Name`) VALUES
(1, 'MD. Ibrahim Siddik'),
(2, 'S.M Atiqur Islam');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` varchar(255) NOT NULL,
  `Customer_ID` int(11) DEFAULT NULL,
  `Product_ID` int(11) NOT NULL,
  `Quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `Customer_ID`, `Product_ID`, `Quantity`) VALUES
('cart123', 1, 1, 2),
('cart123', 1, 6, 1),
('cart456', 2, 1, 1),
('cart456', 2, 6, 2);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `Category_ID` int(11) NOT NULL,
  `Category_Name` varchar(100) DEFAULT NULL,
  `Description` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`Category_ID`, `Category_Name`, `Description`) VALUES
(1, 'Motherboard', 'ASUS,MSI,LENOVO,ZOTAC,HP,GIGABYTE and other brands '),
(2, 'Monitor', 'All types of brands are there like Samsung,ASUS,MSI,HP,BENQ,DAHUA etc'),
(3, 'RAM', 'Corsair,Samsung,APACER and many more brands.'),
(4, 'SSD', 'TeamElite,Apacer and many SSD with highest R/W guaranteed');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `Customer_ID` int(11) NOT NULL,
  `First_Name` varchar(50) DEFAULT NULL,
  `Last_Name` varchar(50) DEFAULT NULL,
  `Phone_Number` int(10) DEFAULT NULL,
  `Date_of_Birth` date DEFAULT NULL,
  `E_Mail` varchar(70) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`Customer_ID`, `First_Name`, `Last_Name`, `Phone_Number`, `Date_of_Birth`, `E_Mail`) VALUES
(1, 'Faiyan ', 'Swapnil', 655141442, '2000-11-03', 'faiyanswapnil@gmail.com'),
(2, 'Nion', ' Khan', 545474855, '2001-05-25', 'nion@gmail.com'),
(3, 'Shakib', 'Ahmed', 155464656, '2000-03-11', 'shakib@outlook.com');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `order_id` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`order_id`, `product_id`, `quantity`, `price`) VALUES
('order456', 1, 2, 60000),
('order456', 6, 1, 12000),
('order789', 1, 1, 30000),
('order789', 6, 2, 24000);

-- --------------------------------------------------------

--
-- Table structure for table `order_table`
--

CREATE TABLE `order_table` (
  `Order_ID` varchar(255) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `shipping_date` datetime DEFAULT NULL,
  `Order_Date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_table`
--

INSERT INTO `order_table` (`Order_ID`, `customer_id`, `shipping_date`, `Order_Date`) VALUES
('order456', 1, '2024-10-25 00:00:00', '2024-10-18 21:08:30'),
('order789', 2, '2024-10-30 11:00:00', '2024-10-18 21:40:03');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `Product_ID` int(11) NOT NULL,
  `Category_ID` int(11) DEFAULT NULL,
  `Price` float DEFAULT NULL,
  `Brand` varchar(50) DEFAULT NULL,
  `Product_Name` varchar(100) DEFAULT NULL,
  `stock` tinyint(1) DEFAULT NULL,
  `Product_Description` varchar(300) DEFAULT NULL,
  `Admin_ID` int(11) DEFAULT NULL,
  `Time_of_Entry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`Product_ID`, `Category_ID`, `Price`, `Brand`, `Product_Name`, `stock`, `Product_Description`, `Admin_ID`, `Time_of_Entry`) VALUES
(1, 1, 30000, 'MSI', 'MSI MEG Z590 GODLIKE', 1, 'Supports 11th and 10th Gen Intel® Core™ / Pentium® Celeron® processors for LGA 1200 socket\r\nSupports DDR4 Memory, up to 5600(OC) MHz\r\nExtreme Power Design: INTERSIL digital PWM, direct 20 phases with 90A Smart Power Stage, Titanium Choke III, and dual CPU power connectors to unleash the maximum perf', 1, '2024-10-18 17:59:10'),
(6, 2, 12000, 'ASUS', 'ASUS Gaming Monitor 100 HZ', 1, 'Game clearly with 100HZ display and G-Sync Enabled Monitor by ASUS', 2, '2024-10-18 18:01:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD KEY `Customer_ID` (`Customer_ID`);

--
-- Indexes for table `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`Admin_ID`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`,`Product_ID`),
  ADD KEY `Customer_ID` (`Customer_ID`),
  ADD KEY `Product_ID` (`Product_ID`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`Category_ID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`Customer_ID`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`order_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `order_table`
--
ALTER TABLE `order_table`
  ADD PRIMARY KEY (`Order_ID`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`Product_ID`),
  ADD KEY `Category_ID` (`Category_ID`),
  ADD KEY `Admin_ID` (`Admin_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `Customer_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`Customer_ID`) REFERENCES `customer` (`Customer_ID`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`Customer_ID`) REFERENCES `customer` (`Customer_ID`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`Product_ID`) REFERENCES `product` (`Product_ID`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order_table` (`Order_ID`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`Product_ID`);

--
-- Constraints for table `order_table`
--
ALTER TABLE `order_table`
  ADD CONSTRAINT `order_table_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`Customer_ID`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`Category_ID`) REFERENCES `category` (`Category_ID`),
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`Admin_ID`) REFERENCES `administrator` (`Admin_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
