-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2026 at 03:57 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sales_erp`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'إلكترونيات', 'أجهزة إلكترونية وكهربائية', '2026-05-24 16:43:39'),
(2, 'أثاث منزلي', 'أثاث وغرف نوم ومكاتب', '2026-05-24 16:43:39'),
(3, 'ملابس رجالية', 'ملابس واكسسوارات رجالية', '2026-05-24 16:43:39'),
(4, 'ملابس نسائية', 'ملابس واكسسوارات نسائية', '2026-05-24 16:43:39'),
(5, 'مواد غذائية', 'منتجات غذائية ومشروبات', '2026-05-24 16:43:39'),
(6, 'مستحضرات تجميل', 'عناية شخصية ومكياج', '2026-05-24 16:43:39');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `tax_number` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `phone`, `email`, `address`, `tax_number`, `is_active`, `created_at`) VALUES
(1, 'عميل نقدي', '0500000000', 'cash@example.com', 'مبيعات نقدية', NULL, 1, '2026-05-24 16:43:39'),
(2, 'محمد أحمد', '0555123456', 'mohamed@example.com', 'الرياض - حي المروج', NULL, 1, '2026-05-24 16:43:39'),
(3, 'نورة خالد', '0555789012', 'noura@example.com', 'جدة - حي الزهراء', NULL, 1, '2026-05-24 16:43:39'),
(4, 'عبدالله عمر', '0555345678', 'abdullah@example.com', 'الدمام - حي الفيصلية', NULL, 1, '2026-05-24 16:43:39');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `invoice_no` varchar(50) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_percent` int(11) DEFAULT 0,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `tax_percent` int(11) DEFAULT 0,
  `tax_amount` decimal(10,2) DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `status` enum('draft','completed','cancelled') DEFAULT 'completed',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `invoice_no`, `customer_id`, `warehouse_id`, `date`, `subtotal`, `discount_percent`, `discount_amount`, `tax_percent`, `tax_amount`, `total`, `notes`, `created_by`, `status`, `created_at`) VALUES
(1, 'INV-2025-001', 2, 1, '2026-05-24 16:43:40', 2498.00, 10, 249.80, 0, 0.00, 2248.20, NULL, 1, 'completed', '2026-05-24 16:43:40'),
(2, 'INV-20260525-0002', 4, 1, '2026-05-25 14:53:15', 100.00, 10, 10.00, 0, 0.00, 90.00, NULL, 1, 'completed', '2026-05-25 15:53:15');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `product_id`, `quantity`, `price`, `discount`, `total`) VALUES
(1, 1, 1, 1, 1999.00, 0.00, 1999.00),
(2, 1, 2, 1, 299.00, 0.00, 299.00),
(3, 1, 7, 8, 25.00, 0.00, 200.00),
(4, 2, 7, 4, 25.00, 0.00, 100.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cost` decimal(10,2) DEFAULT 0.00,
  `alert_quantity` int(11) DEFAULT 5,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `code`, `name`, `description`, `category_id`, `price`, `cost`, `alert_quantity`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'P001', 'هاتف ذكي - موديل جديد', '', 1, 1999.00, 1700.00, 6, 1, '2026-05-24 16:43:39', '2026-05-24 20:23:15'),
(2, 'P002', 'سماعات لاسلكية', NULL, 1, 299.00, 200.00, 10, 1, '2026-05-24 16:43:39', NULL),
(3, 'P003', 'كرسي مكتب مريح', NULL, 2, 599.00, 450.00, 3, 1, '2026-05-24 16:43:39', NULL),
(4, 'P004', 'طقم جلسات صالون', NULL, 2, 2499.00, 2000.00, 2, 1, '2026-05-24 16:43:39', NULL),
(5, 'P005', 'قميص رجالي - أبيض', NULL, 3, 89.00, 60.00, 15, 1, '2026-05-24 16:43:39', NULL),
(6, 'P006', 'فستان كاجوال', NULL, 4, 159.00, 110.00, 8, 1, '2026-05-24 16:43:39', NULL),
(7, 'P007', 'شوكولاتة سويسرية', NULL, 5, 25.00, 18.00, 20, 1, '2026-05-24 16:43:39', NULL),
(8, 'P008', 'أحمر شفاه', NULL, 6, 45.00, 30.00, 12, 1, '2026-05-24 16:43:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_warehouse`
--

CREATE TABLE `product_warehouse` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `reserved_quantity` int(11) DEFAULT 0,
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `product_warehouse`
--

INSERT INTO `product_warehouse` (`id`, `product_id`, `warehouse_id`, `quantity`, `reserved_quantity`, `last_updated`) VALUES
(1, 1, 1, 49, 0, '2026-05-24 16:43:40'),
(2, 1, 2, 20, 0, '2026-05-24 16:43:39'),
(3, 1, 3, 10, 0, '2026-05-24 16:43:39'),
(4, 2, 1, 99, 0, '2026-05-24 16:43:40'),
(5, 2, 2, 40, 0, '2026-05-24 16:43:39'),
(6, 2, 3, 25, 0, '2026-05-24 16:43:39'),
(7, 3, 1, 15, 0, '2026-05-24 16:43:39'),
(8, 3, 2, 8, 0, '2026-05-24 16:43:39'),
(9, 3, 3, 5, 0, '2026-05-24 16:43:39'),
(10, 4, 1, 6, 0, '2026-05-24 16:43:39'),
(11, 4, 2, 4, 0, '2026-05-24 16:43:39'),
(12, 4, 3, 2, 0, '2026-05-24 16:43:39'),
(13, 5, 1, 80, 0, '2026-05-24 16:43:39'),
(14, 5, 2, 30, 0, '2026-05-24 16:43:39'),
(15, 5, 3, 15, 0, '2026-05-24 16:43:39'),
(16, 6, 1, 40, 0, '2026-05-24 16:43:39'),
(17, 6, 2, 20, 0, '2026-05-24 16:43:39'),
(18, 6, 3, 10, 0, '2026-05-24 16:43:39'),
(19, 7, 1, 188, 0, '2026-05-25 15:53:15'),
(20, 7, 2, 100, 0, '2026-05-24 16:43:39'),
(21, 7, 3, 50, 0, '2026-05-24 16:43:39'),
(22, 8, 1, 300, 0, '2026-05-24 22:45:33'),
(23, 8, 2, 25, 0, '2026-05-24 16:43:39'),
(24, 8, 3, 15, 0, '2026-05-24 16:43:39');

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `quantity_change` int(11) NOT NULL,
  `quantity_before` int(11) NOT NULL,
  `quantity_after` int(11) NOT NULL,
  `movement_type` enum('purchase','sale','adjustment','return') NOT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `product_id`, `warehouse_id`, `quantity_change`, `quantity_before`, `quantity_after`, `movement_type`, `reference_id`, `created_by`, `notes`, `created_at`) VALUES
(1, 1, 1, -1, 50, 49, 'sale', 1, 1, NULL, '2026-05-24 16:43:40'),
(2, 2, 1, -1, 100, 99, 'sale', 1, 1, NULL, '2026-05-24 16:43:40'),
(3, 7, 1, -8, 200, 192, 'sale', 1, 1, NULL, '2026-05-24 16:43:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user_warehouse') NOT NULL DEFAULT 'user_warehouse',
  `warehouse_id` int(11) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `remember_token` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `warehouse_id`, `full_name`, `is_active`, `remember_token`, `created_at`, `last_login`) VALUES
(1, 'admin', 'admin@erp.com', '$2y$10$JfBy0BsvIYsJi2RiNREbjuBGSK26N0eh8Xx2NQsIqhw3newWfTcd6', 'admin', NULL, 'مدير النظام', 1, NULL, '2026-05-24 16:43:40', NULL),
(2, 'warehouse1', 'user1@erp.com', '$2y$10$JfBy0BsvIYsJi2RiNREbjuBGSK26N0eh8Xx2NQsIqhw3newWfTcd6', 'user_warehouse', 1, 'مسؤول مستودع الرياض', 1, NULL, '2026-05-24 16:43:40', NULL),
(3, 'warehouse2', 'user2@erp.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user_warehouse', 2, 'مسؤول مستودع جدة', 1, NULL, '2026-05-24 16:43:40', NULL),
(4, 'warehouse3', 'user3@erp.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user_warehouse', 3, 'مسؤول مستودع تبوك', 1, NULL, '2026-05-24 16:43:40', NULL),
(5, 'test', 'test@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL, NULL, 1, NULL, '2026-05-24 17:26:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `name`, `location`, `is_active`, `created_at`) VALUES
(1, 'المستودع الرئيسي', 'الرياض - المنطقة الصناعية', 1, '2026-05-24 16:43:39'),
(2, 'مستودع فرعي', 'جدة - طريق المدينة', 1, '2026-05-24 16:43:39'),
(3, 'مستودع الشمال', 'تبوك - المنطقة اللوجستية', 1, '2026-05-24 16:43:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_name` (`name`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_phone` (`phone`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_invoice_no` (`invoice_no`),
  ADD KEY `idx_customer` (`customer_id`),
  ADD KEY `idx_warehouse` (`warehouse_id`),
  ADD KEY `idx_date` (`date`),
  ADD KEY `idx_created_by` (`created_by`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_invoice` (`invoice_id`),
  ADD KEY `idx_product` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_code` (`code`),
  ADD KEY `idx_category` (`category_id`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `product_warehouse`
--
ALTER TABLE `product_warehouse`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_product_warehouse` (`product_id`,`warehouse_id`),
  ADD KEY `idx_product` (`product_id`),
  ADD KEY `idx_warehouse` (`warehouse_id`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product` (`product_id`),
  ADD KEY `idx_warehouse` (`warehouse_id`),
  ADD KEY `idx_movement_type` (`movement_type`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_username` (`username`),
  ADD UNIQUE KEY `unique_email` (`email`),
  ADD KEY `idx_role` (`role`),
  ADD KEY `warehouse_id` (`warehouse_id`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product_warehouse`
--
ALTER TABLE `product_warehouse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `invoices_ibfk_2` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`),
  ADD CONSTRAINT `invoices_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoice_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_warehouse`
--
ALTER TABLE `product_warehouse`
  ADD CONSTRAINT `product_warehouse_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_warehouse_ibfk_2` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_movements_ibfk_2` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_movements_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
