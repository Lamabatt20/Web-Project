-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 22, 2024 at 03:29 PM
-- Server version: 8.0.37
-- PHP Version: 8.1.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `web1210922_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `Cars`
--

CREATE TABLE `Cars` (
  `car_id` int NOT NULL,
  `model` varchar(50) NOT NULL,
  `make` enum('BMW','VW','Volvo','Octavia') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `type` enum('Van','Mini-Van','Estate','Sedan','SUV') NOT NULL,
  `registration_year` int NOT NULL,
  `description` text,
  `price_per_day` int NOT NULL,
  `capacity_people` int NOT NULL,
  `capacity_suitcases` int NOT NULL,
  `color` varchar(20) DEFAULT NULL,
  `fuel_type` enum('petrol','diesel','electric','hybrid') NOT NULL,
  `avg_consumption` decimal(5,2) DEFAULT NULL,
  `horsepower` int DEFAULT NULL,
  `length` decimal(5,2) DEFAULT NULL,
  `width` decimal(5,2) DEFAULT NULL,
  `gear_type` enum('manual','automatic') NOT NULL,
  `conditions` text,
  `photo1` varchar(255) DEFAULT NULL,
  `photo2` varchar(255) DEFAULT NULL,
  `photo3` varchar(255) DEFAULT NULL,
  `status` enum('available','returning','repair','damaged') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Cars`
--

INSERT INTO `Cars` (`car_id`, `model`, `make`, `type`, `registration_year`, `description`, `price_per_day`, `capacity_people`, `capacity_suitcases`, `color`, `fuel_type`, `avg_consumption`, `horsepower`, `length`, `width`, `gear_type`, `conditions`, `photo1`, `photo2`, `photo3`, `status`) VALUES
(1, '320i', 'BMW', 'Sedan', 2019, 'Luxury sedan with advanced features.', 200, 5, 2, 'Black', 'petrol', 6.50, 180, 4.70, 1.80, 'automatic', 'No smoking.', 'car1img1.jpeg', 'car1img2.jpg', 'car1img3.jpg', 'available'),
(2, 'Tiguan', 'VW', 'SUV', 2020, 'Spacious SUV with modern technology.', 120, 7, 4, 'White', 'diesel', 7.20, 150, 4.80, 1.90, 'automatic', 'No pets.', 'car2img1.jpeg', 'car2img2.jpeg', 'car2img3.jpeg', 'available'),
(3, 'XC90', 'Volvo', 'SUV', 2021, 'Elegant SUV with top safety features.', 200, 7, 5, 'Blue', 'hybrid', 5.50, 250, 4.90, 1.90, 'automatic', 'Requires premium fuel.', 'car3img1.jpeg', 'car3img2.jpeg', 'car3img3.jpeg', 'available'),
(4, 'Passat', 'VW', 'Sedan', 2018, 'Comfortable sedan with great fuel efficiency.', 100, 5, 3, 'Grey', 'petrol', 6.00, 170, 4.60, 1.80, 'automatic', 'No smoking.', 'car4img1.jpeg', 'car4img2.jpeg', 'car4img3.jpeg', 'available'),
(5, 'V60', 'Volvo', 'Estate', 2020, 'Versatile estate car with ample space.', 130, 5, 4, 'Red', 'diesel', 6.80, 190, 4.70, 1.80, 'automatic', 'No pets.', 'car5img1.jpeg', 'car5img2.jpeg', 'car5img3.jpeg', 'available'),
(6, 'X5', 'BMW', 'SUV', 2021, 'High-performance SUV with luxurious interiors.', 250, 7, 5, 'Black', 'hybrid', 5.00, 300, 5.00, 2.00, 'automatic', 'Requires premium fuel.', 'car6img1.jpeg', 'car6img2.jpeg', 'car6img3.jpeg', 'available'),
(21, 'skoda', 'Octavia', 'Sedan', 2019, 'Skoda cars are renowned for their robust build, reliable performance, and stylish design, offering comfort and advanced technology across sedan, hatchback, and SUV models.', 100, 4, 3, 'silver', 'diesel', 7.00, 190, 5.00, 2.00, 'manual', 'No pets.', 'car1800076445img1.jpg', 'car4687345180img2.jpeg', 'car6689722552img3.jpeg', 'available');

-- --------------------------------------------------------

--
-- Table structure for table `Customers`
--

CREATE TABLE `Customers` (
  `customer_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `id_number` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `credit_card_number` varchar(9) NOT NULL,
  `credit_card_expirationDate` date NOT NULL,
  `credit_card_name` varchar(100) NOT NULL,
  `credit_card_bank` varchar(50) NOT NULL,
  `username` varchar(13) NOT NULL,
  `password` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Customers`
--

INSERT INTO `Customers` (`customer_id`, `name`, `address`, `date_of_birth`, `id_number`, `email`, `telephone`, `credit_card_number`, `credit_card_expirationDate`, `credit_card_name`, `credit_card_bank`, `username`, `password`) VALUES
(1, 'Ahmad Mohammad', '123 Main St, Birzeit', '1980-01-01', 'AB123456', 'ahmad@example.com', '+970 2 1111111', '111122223', '2025-12-31', 'Ahmad Mohammad', 'Bank of Palestine', 'ahmad80', 'password1'),
(2, 'Mariam Ali', '456 High St, Ramallah', '1992-02-02', 'CD789012', 'mariam@example.com', '+970 2 2222222', '555566667', '2024-11-30', 'Mariam Ali', 'Bank of Palestine', 'mariam92', 'password2'),
(3, 'Khalid Hassan', '789 Peace St, Nablus', '1985-03-03', 'EF345678', 'khalid@example.com', '+970 2 3333333', '999900001', '2023-10-29', 'Khalid Hassan', 'Bank of Jordan', 'khalid85', 'password3'),
(4, 'Laila Saeed', '321 Freedom St, Jenin', '1987-04-04', 'GH901234', 'laila@example.com', '+970 2 4444444', '333344445', '2026-01-28', 'Laila Saeed', 'Cairo Amman Bank', 'laila87', 'password4'),
(5, 'Sami Yousif', '654 Victory St, Tulkarem', '1990-05-05', 'IJ567890', 'sami@example.com', '+970 2 5555555', '777788889', '2022-09-27', 'Sami Yousif', 'Arab Bank', 'sami90', 'password5'),
(6, 'Huda Ahmad', '987 Fath St, Bethlehem', '1993-06-06', 'KL123456', 'huda@example.com', '+970 2 6666666', '222233334', '2025-08-26', 'Huda Ahmad', 'Bank of Jordan', 'huda93', 'password6'),
(21, 'lama batta', 'Ramallah-alarsal', '2024-06-04', '1234', 'lamabatta@hotmail.com', '0598602028', '123456789', '2025-12-01', 'lama', 'palestine', 'lamabatta', 'lama2003');

-- --------------------------------------------------------

--
-- Table structure for table `Invoices`
--

CREATE TABLE `Invoices` (
  `invoice_id` int NOT NULL,
  `rental_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `invoice_date` date NOT NULL,
  `total_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Invoices`
--

INSERT INTO `Invoices` (`invoice_id`, `rental_id`, `customer_id`, `invoice_date`, `total_amount`) VALUES
(1, 1, 1, '2024-06-30', 1500.00);

-- --------------------------------------------------------

--
-- Table structure for table `Locations`
--

CREATE TABLE `Locations` (
  `location_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `property_number` varchar(50) NOT NULL,
  `street_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `city` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `country` varchar(100) NOT NULL,
  `telephone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Locations`
--

INSERT INTO `Locations` (`location_id`, `name`, `property_number`, `street_name`, `city`, `postal_code`, `country`, `telephone`) VALUES
(1, 'Birzeit ', 'PN001', '123 Main St', 'Birzeit', '12345', 'Palestine', '+970 2 1234567'),
(2, 'Ramallah ', 'PN002', '456 High St', 'Ramallah', '67890', 'Palestine', '+970 2 7654321'),
(3, 'Hebron ', '6789', 'Ramallah-alarsal', 'Hebron', '600', 'Palestine', '0598602028'),
(4, 'Nablus', 'PN123', 'Ravidia', 'Nablus', '300', 'Palestine', '0598602028');


-- --------------------------------------------------------

--
-- Table structure for table `Rentals`
--

CREATE TABLE `Rentals` (
  `rental_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `car_id` int NOT NULL,
  `pick_up_date` date NOT NULL,
  `return_date` date NOT NULL,
  `pick_up_location` varchar(100) NOT NULL,
  `return_location` varchar(100) DEFAULT NULL,
  `total_rent_amount` decimal(10,2) NOT NULL,
  `special_requirements` text,
  `invoice_id` varchar(10) NOT NULL,
  `status` enum('returning','active') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'active',
  `rental_status` enum('future','current','past') NOT NULL,
  `pick_up_time` time NOT NULL,
  `return_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Rentals`
--

INSERT INTO `Rentals` (`rental_id`, `customer_id`, `car_id`, `pick_up_date`, `return_date`, `pick_up_location`, `return_location`, `total_rent_amount`, `special_requirements`, `invoice_id`, `status`, `rental_status`, `pick_up_time`, `return_time`) VALUES
(1, 1, 1, '2024-06-13', '2024-06-19', 'Birzeit', 'Ramallah', 1500.00, 'Child seat', 'INV00001', 'active', 'past', '02:16:26', '03:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `SpecialRequirements`
--

CREATE TABLE `SpecialRequirements` (
  `requirement_id` int NOT NULL,
  `rental_id` int NOT NULL,
  `description` varchar(255) NOT NULL,
  `additional_cost` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `SpecialRequirements`
--

INSERT INTO `SpecialRequirements` (`requirement_id`, `rental_id`, `description`, `additional_cost`) VALUES
(1, 1, 'GPS', 50.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `customer_id` int DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `user_type` enum('customer','manager') NOT NULL DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `customer_id`, `username`, `password`, `created_at`, `user_type`) VALUES
(1, 1, 'ahmad80', 'password1', '2024-06-20 14:04:58', 'customer'),
(2, 2, 'mariam92', 'password2', '2024-06-20 14:04:58', 'manager'),
(3, 21, 'lamabatta', 'lama2003', '2024-06-21 22:19:03', 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Cars`
--
ALTER TABLE `Cars`
  ADD PRIMARY KEY (`car_id`);

--
-- Indexes for table `Customers`
--
ALTER TABLE `Customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `id_number` (`id_number`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `Invoices`
--
ALTER TABLE `Invoices`
  ADD PRIMARY KEY (`invoice_id`),
  ADD KEY `rental_id` (`rental_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `Locations`
--
ALTER TABLE `Locations`
  ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `Rentals`
--
ALTER TABLE `Rentals`
  ADD PRIMARY KEY (`rental_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `car_id` (`car_id`),
  ADD KEY `pick_up_location` (`pick_up_location`),
  ADD KEY `return_location` (`return_location`);

--
-- Indexes for table `SpecialRequirements`
--
ALTER TABLE `SpecialRequirements`
  ADD PRIMARY KEY (`requirement_id`),
  ADD KEY `rental_id` (`rental_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `customer_id` (`customer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Cars`
--
ALTER TABLE `Cars`
  MODIFY `car_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `Customers`
--
ALTER TABLE `Customers`
  MODIFY `customer_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `Invoices`
--
ALTER TABLE `Invoices`
  MODIFY `invoice_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `Locations`
--
ALTER TABLE `Locations`
  MODIFY `location_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `Rentals`
--
ALTER TABLE `Rentals`
  MODIFY `rental_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `SpecialRequirements`
--
ALTER TABLE `SpecialRequirements`
  MODIFY `requirement_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Invoices`
--
ALTER TABLE `Invoices`
  ADD CONSTRAINT `Invoices_ibfk_1` FOREIGN KEY (`rental_id`) REFERENCES `Rentals` (`rental_id`),
  ADD CONSTRAINT `Invoices_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `Customers` (`customer_id`);

--
-- Constraints for table `Rentals`
--
ALTER TABLE `Rentals`
  ADD CONSTRAINT `Rentals_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `Customers` (`customer_id`),
  ADD CONSTRAINT `Rentals_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `Cars` (`car_id`);

--
-- Constraints for table `SpecialRequirements`
--
ALTER TABLE `SpecialRequirements`
  ADD CONSTRAINT `SpecialRequirements_ibfk_1` FOREIGN KEY (`rental_id`) REFERENCES `Rentals` (`rental_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `Customers` (`customer_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
