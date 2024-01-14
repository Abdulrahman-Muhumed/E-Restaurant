-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 13, 2021 at 10:43 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `erestaurant`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `item_id` int(11) NOT NULL,
  `user_id` int(200) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`item_id`, `user_id`, `quantity`) VALUES
(6, 12, 3);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(5) NOT NULL,
  `name` varchar(250) NOT NULL,
  `description` text DEFAULT NULL,
  `price` float NOT NULL,
  `category` varchar(250) NOT NULL,
  `Meal_Img` varchar(100) DEFAULT NULL,
  `Kcal` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `description`, `price`, `category`, `Meal_Img`, `Kcal`, `Quantity`) VALUES
(1, 'cheeseburger', 'cheeseburger', 9.99, 'Dinner', 'food1.png', 140, 0),
(2, 'Egg Muffin', 'Commodi id vitae ', 12, 'Breakfast', 'burgerbf.png', 198, 1),
(3, 'strawberry slushie', 'Aliquip sapiente ips', 23, 'Drinks', 'strawberry-slushie-feature.png', 260, 0),
(4, 'Shiso Fine', 'Mixed Green fruits', 24, 'Drinks', 'ShisoFine.png', 190, 2),
(5, 'Special E-res', '', 45, 'Lunch', 'E-res-Special.png', 360, 2),
(6, 'vege Plate', '', 37, 'Dinner', 'vegetarian-reci.png', 230, 5),
(7, 'Chicken Wrap', '', 20, 'Dinner', 'Chicken-Wrap.png', 223, 4),
(8, 'Pot Chicken', '', 54, 'Lunch', 'Pot-Chicken.png', 600, 2);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(200) NOT NULL,
  `order_item` int(200) NOT NULL,
  `order_date` date NOT NULL,
  `Order_Address` varchar(200) NOT NULL,
  `Order_Status` varchar(50) NOT NULL,
  `User_id` int(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_item`, `order_date`, `Order_Address`, `Order_Status`, `User_id`) VALUES
(1, 1, '2021-03-29', 'Saudi Arabia_Khobar', 'Paid', 12),
(4, 2, '2021-04-13', 'Saudi Arabia_Khobar', 'Paid', 12),
(5, 3, '2021-04-13', 'Saudi Arabia_Khobar', 'Paid', 12);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `name` varchar(200) NOT NULL,
  `cvv` varchar(200) NOT NULL,
  `expDate` varchar(200) NOT NULL,
  `type` varchar(200) NOT NULL,
  `Number` varchar(200) NOT NULL,
  `user_id` int(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`name`, `cvv`, `expDate`, `type`, `Number`, `user_id`) VALUES
('Test', '$2y$10$OA3r3sN76xx./kmjpaJvdeJsKecqFAvlxGzuNPy5/gldvqiL11PSm', '2021-04', 'paypal', '$2y$10$acbR9MCHz4XQAnkk.gzByeGP2FyfpJu.WWdnS/y4BMh/i/ThfnfFG', 12),
('qwddqwqwd', '$2y$10$I8AG0LPZh72igcaXrss/f.cHGNwKOSCi0GJMoc4EcckS6YMFrWNn2', '2021-03', 'paypal', '$2y$10$bg7DTpbV4qQ8A/Cri6c4IuTmEWt7qqIpjan8AB/l4zjPhq05vGmG2', 12),
('qdwdqwdqw', '$2y$10$tw9FkAMmW53fDtwnn/m5Ze/SKwjUmhNg3AOQvIQDD3ahIetqcaWJ6', '2021-11', 'paypal', '$2y$10$q70PDVYsV.kXcTJ4H24hYuSSDyE6IIaHPrQe4BledysPmfgfAjmQq', 12);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(200) NOT NULL,
  `First_Name` varchar(250) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `Last_Name` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(30) NOT NULL,
  `Phone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `First_Name`, `is_admin`, `Last_Name`, `Email`, `Password`, `Phone`) VALUES
(1, 'Abdulrahman ', 1, 'Mohamed', 'amohamed@admin.com', '$2y$10$16WyYC0.P2ZbrNuq4Z2Ose0', '0500000000'),
(2, 'jawad', 1, 'Al-Ibrahim', 'Jwad@admin.com', '$2y$10$F78FEd5LQXm9C0u/5p.Nx.n', '0501111111'),
(3, 'Ali', 1, 'Al-Basher', 'aalbasher@admin.com', '$2y$10$OxMMhbqyQg1wjRjiZEGaOe8', '0502222222'),
(4, 'AbdulAziz', 1, 'Al-Gurini', 'aalgurini@admin.com', '$2y$10$w5Ex0kKDmkCwou44y.hXpul', '0503333333'),
(5, 'Ali', 1, 'Al-Muallim', 'aalmuallim@admin.com', '$2y$10$m7xKhLdGxTMgzwkotrc8B.d', '0504444444'),
(6, 'Abdullah', 1, 'Hamed', 'ahamed@admin.com', '$2y$10$sOH/EfeLkf3UzZXxY8OqPeD', '0505555555'),
(7, 'Mazin', 1, 'Al-Mhsin', 'malmhsin@admin.com', '$2y$10$mo.DMyWizAE1ljZiTutb7ef', '0506666666'),
(8, 'Hussain', 1, 'Khoder', 'hkhoder@admin.com', '$2y$10$6uQqeAvccjBM3cJTzZEoouh', '0507777777'),
(9, 'Mohamed', 0, 'Ali', 'mohamed123@hotmail.com', '$2y$10$78TVxx1iYSNPJyOrNeK1ROH', '0501597482'),
(10, 'Khaled', 0, 'ahmed', 'kahmed@gmail.com', '$2y$10$0urCAYBs6Cq59Feh/yICS.6', '0541236547'),
(11, 'Hussain', 0, 'Talal', 'htalal@admin.com', '$2y$10$iwzaKg1GTeRyHwpIjOtTKuR', '0501523145'),
(12, 'Test', 0, 'Test2', 'test@hotmail.com', '123123', '12321321'),
(13, 'Admin', 1, 'Test', 'test@admin.com', '123123', '2222222');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD KEY `cart_ibfk_1` (`item_id`),
  ADD KEY `users_id` (`user_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `User_id` (`User_id`),
  ADD KEY `order_item` (`order_item`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`Number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `users_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`order_item`) REFERENCES `items` (`id`) ON DELETE NO ACTION;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
