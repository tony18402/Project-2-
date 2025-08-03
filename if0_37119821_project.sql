-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql110.infinityfree.com
-- Generation Time: Feb 03, 2025 at 02:18 AM
-- Server version: 10.11.9-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_37119821_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `email` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `product_id`, `user_name`, `comment_text`, `created_at`, `email`) VALUES
(29, 23, '', '5555', '2025-01-22 07:56:54', 'admin@gmail.com'),
(31, 26, '', 'สีสวยครับ', '2025-01-29 07:01:03', 'admin@gmail.com'),
(13, 0, '', 'sssss', '2025-01-22 07:18:41', 'admin@gmail.com'),
(30, 23, '', '555555555', '2025-01-22 08:13:11', 'admin@gmail.com'),
(28, 24, '', 'ดีมากครับ456\r\n', '2025-01-22 07:56:38', 'admin@gmail.com'),
(32, 25, '', 'สวยงามครับ', '2025-01-29 07:01:21', 'admin@gmail.com'),
(33, 26, '', 'ใส่สบายเท้า', '2025-01-29 07:06:37', '123@gmail.com'),
(34, 25, '', 'สวยกันร้อนหนาวดี', '2025-01-29 07:07:08', '123@gmail.com'),
(35, 28, '', 'ขี้เท็จ', '2025-01-30 17:28:19', '66302040044@svc.ac.th'),
(36, 28, '', 'เติมสต๊อคบ้างนะคะ', '2025-01-30 17:28:52', '66302040044@svc.ac.th'),
(37, 28, '', '123', '2025-02-01 15:14:14', '999@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `Orders`
--

CREATE TABLE `Orders` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','paid','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `shipping_status` enum('pending','shipped','delivered','canceled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_address` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `Orders`
--

INSERT INTO `Orders` (`order_id`, `product_id`, `order_date`, `quantity`, `total_price`, `payment_status`, `shipping_status`, `created_at`, `updated_at`, `email`, `shipping_address`) VALUES
(56, 28, '2025-01-30 09:27:30', 5, '40825.00', 'paid', 'delivered', '2025-01-30 09:27:30', '2025-01-30 09:34:05', '123@gmail.com', '123456'),
(48, 28, '2025-01-30 23:16:21', 1, '8165.00', 'paid', 'delivered', '2025-01-30 23:16:21', '2025-01-30 23:16:46', 'admin@gmail.com', '123/123'),
(47, 28, '2025-01-30 23:02:27', 12, '97980.00', 'paid', 'delivered', '2025-01-30 23:02:27', '2025-01-30 23:10:23', 'admin@gmail.com', '123/123'),
(46, 30, '2025-01-30 22:49:35', 2, '246.00', 'paid', 'delivered', '2025-01-30 22:49:35', '2025-01-30 22:51:10', '123@gmail.com', '123456'),
(45, 28, '2025-01-30 01:53:41', 7, '57155.00', 'paid', 'delivered', '2025-01-30 01:53:41', '2025-01-30 22:45:01', 'admin@gmail.com', '123/123'),
(44, 30, '2025-01-30 01:52:07', 1, '123.00', 'paid', 'delivered', '2025-01-30 01:52:07', '2025-01-30 01:53:34', 'admin@gmail.com', '123/123'),
(57, 28, '2025-01-31 07:40:16', 7, '57155.00', 'paid', 'delivered', '2025-01-31 07:40:16', '2025-01-31 07:40:49', '88@gmail.com', '99'),
(58, 28, '2025-02-01 07:15:20', 1, '8165.00', 'paid', 'delivered', '2025-02-01 07:15:20', '2025-02-01 07:15:55', '999@gmail.com', '999/999'),
(55, 28, '2025-01-30 09:26:12', 8, '65320.00', 'paid', 'shipped', '2025-01-30 09:26:12', '2025-01-30 09:26:55', '66302040044@svc.ac.th', 'SVC ?????\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `Products`
--

CREATE TABLE `Products` (
  `product_id` int(11) NOT NULL,
  `product_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('shoes','clothes') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `cond` enum('new','used') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('available','sold','removed') DEFAULT 'available',
  `is_orderable` tinyint(1) DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `Products`
--

INSERT INTO `Products` (`product_id`, `product_name`, `category`, `description`, `price`, `cond`, `size`, `color`, `brand`, `image_url`, `created_at`, `updated_at`, `status`, `is_orderable`) VALUES
(25, 'Diesel Jus Hood', 'clothes', 'ขนาด S สภาพดีขนาดหน้าอก 20.08 นิ้ว | 51 ซม.ความยาว 27.95 นิ้ว | 71 ซม.ความกว้างไหล่ 17.32 นิ้ว | 44 ซม.ความยาวแขน 25.98 นิ้ว | 66 ซม.', '2202.40', 'used', 'S', 'ดำ', 'Diesel', 'uploads/6799d0eddaf34_Screenshot 2025-01-29 135303.png', '2025-01-28 22:55:41', '2025-02-01 07:17:11', 'available', 0),
(26, 'Nike Air Jordan 1 Retro High OG', 'shoes', 'สภาพสินค้า  \r\nใหม่พร้อมกล่อง: สินค้าที่ใหม่, ไม่เคยใช้, และไม่เคยสวมใส่ (รวมถึงสินค้าทำมือ) ในบรรจุภัณฑ์เดิม\r\n\r\nการปิดรองเท้า  \r\nผูกเชือก\r\n\r\nโอกาส  \r\nชุดออกกำลังกาย, ลำลอง, ชุดทำงาน\r\n\r\nทรง  \r\nJordan 1 Retro High OG สีน้ำตาล\r\n\r\nปีที่ผลิต  \r\n2021\r\n\r\nสินค้าวินเทจ  \r\nไม่\r\n\r\nแผนก  \r\nผู้ชาย\r\n\r\nปีที่วางจำหน่าย  \r\n2022\r\n\r\nสไตล์  \r\nรองเท้าผ้าใบ\r\n\r\nวัสดุพื้นรองเท้า  \r\nยาง\r\n\r\nคุณสมบัติ  \r\nความสบาย\r\n\r\nฤดูกาล  \r\nฤดูใบไม้ร่วง, ฤดูใบไม้ผลิ, ฤดูร้อน, ฤดูหนาว\r\n\r\nสไตล์รองเท้าผ้าใบ  \r\nรองเท้าผ้าใบแบบหุ้มข้อสูง\r\n\r\nรหัสสไตล์  \r\n555088202\r\n\r\nลาย  \r\nสีบล็อก\r\n\r\nขนาดรองเท้า EU  \r\n38.5, 39, 40, 40.5, 41, 42, 42.5, 43, 44, 44.5, 45, 45.5, 46, 47, 47.5, 48, 48.5\r\n\r\nขนาดรองเท้า UK  \r\n3, 3.5, 4, 4.5, 5, 5.5, 6, 6.5, 7, 7.5, 8, 8.5, 9, 9.5, 10, 10.5, 11, 11.5, 12, 12.5\r\n\r\nสี  \r\nเบจ\r\n\r\nวัสดุซับใน  \r\nผ้า\r\n\r\nแบรนด์  \r\nNike\r\n\r\nประเภท  \r\nรองเท้ากีฬา\r\n\r\nปรับแต่ง  \r\nไม่\r\n\r\nโมเดล  \r\nNike Air Jordan 1\r\n\r\nธีม  \r\nยุค 80s, 90s, สีสันสดใส, ธรรมชาติ, เรโทร\r\n\r\nความกว้างของรองเท้า  \r\nมาตรฐาน\r\n\r\nวัสดุด้านบน  \r\nหนัง\r\n\r\nวัสดุพื้นรองเท้า  โฟม\r\nประเทศ/ภูมิภาคที่ผลิต  \r\nจีน กิจกรรม/การใช้งาน \r\nบาสเกตบอล, ข้ามการฝึก, การเดินสายผลิตภัณฑ์  \r\nAir Jordan', '4980.80', 'used', '38.5, 39, 40, 40.5, 41, 42, 42.5, 43, 44, 44.5, 45', 'ทอง', 'Nike', 'uploads/6799d21ac4100_Screenshot 2025-01-29 135806.png', '2025-01-28 23:00:42', '2025-01-30 00:40:14', 'available', 0),
(28, 'Nike Air Jordan 1 Retro', 'shoes', 'สภาพสินค้า: ใหม่พร้อมกล่อง (สินค้าที่ยังไม่เคยใช้และไม่เคยสวมใส่)\r\n\r\nขนาดรองเท้า UK: 3 - 12.5  \r\nขนาดรองเท้า EU: 38.5 - 48.5  \r\nทรงรองเท้า: หุ้มต่ำ  \r\nการปิดรองเท้า: ผูกเชือก  \r\nวัสดุซับใน: ผ้า  \r\nสี: เบจ  \r\nแบรนด์: Nike  \r\nโมเดล: Nike Air Jordan 1  \r\nสไตล์: รองเท้าผ้าใบ  \r\nวัสดุพื้นรองเท้า: สังเคราะห์  \r\nฤดูกาล: ทุกฤดูกาล  \r\nคุณสมบัติ: ปรับได้, ระบายอากาศ, สบาย, รองรับแรงกระแทก, รุ่นลิมิเต็ด  \r\nกิจกรรม/การใช้งาน: บาสเกตบอล, ยกน้ำหนัก, วิ่ง, โยคะ, ขี่จักรยาน, เดิน, และอื่นๆ', '8165.00', 'new', '38.5, 39, 40, 40.5, 41, 42, 42.5, 43, 44, 44.5, 45', 'เบจ', 'Nike', 'uploads/6799d42fa3ccf_Screenshot 2025-01-29 140249.png', '2025-01-28 23:09:35', '2025-01-30 01:34:05', 'available', 1),
(29, 'Northern ', 'shoes', 'คอเสื้อ: คอกลม โอกาส: สวมใส่ลำลอง ลวดลาย: กราฟิก ', '450.00', 'used', '45', '45', '45', 'uploads/6799d7e29c1fb_Screenshot 2025-01-29 142501.png', '2025-01-28 23:23:36', '2025-01-28 23:29:15', 'available', 0),
(31, '123', 'shoes', '123', '123.00', 'new', '123', '123', '123', 'uploads/679e3ad161c2e_Screenshot 2024-12-30 225023.png', '2025-02-01 07:16:33', '2025-02-01 07:17:13', 'available', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` enum('buyer','admin') DEFAULT 'buyer',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  `shipping_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`user_id`, `username`, `password_hash`, `email`, `first_name`, `last_name`, `phone_number`, `level`, `created_at`, `updated_at`, `shipping_address`) VALUES
(6, '123', '123', '123@gmail.com', 'ต้น', 'กล้า', '0936850267', 'buyer', '2025-01-12 22:33:57', '2025-01-12 22:33:57', '123456'),
(2, 'tony', 'admin', 'admin@gmail.com', 'tony', 'one', '0849976900', 'admin', '2025-01-07 22:44:00', '2025-01-07 22:44:00', '123/123'),
(9, '', '456', '456@gmailcom', '456', '465', '456', 'buyer', '2025-01-26 23:05:31', '2025-01-26 23:05:31', NULL),
(10, 'หิวข้าว', '2005pp', '66302040044@svc.ac.th', 'nuntiya', 'supkeaw', '0949285608', 'buyer', '2025-01-30 08:47:45', '2025-01-30 08:47:45', 'SVC'),
(11, '88', '88', '88@gmail.com', '99', '99', '88', 'buyer', '2025-01-31 07:39:31', '2025-01-31 07:39:31', '99'),
(12, '999', '999', '999@gmail.com', '999', '999', '0999999999', 'buyer', '2025-02-01 07:13:51', '2025-02-01 07:13:51', '999/999');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `Orders`
--
ALTER TABLE `Orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_email` (`email`(250)),
  ADD KEY `idx_shipping_status` (`shipping_status`);

--
-- Indexes for table `Products`
--
ALTER TABLE `Products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`) USING HASH,
  ADD UNIQUE KEY `email` (`email`) USING HASH;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `Orders`
--
ALTER TABLE `Orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `Products`
--
ALTER TABLE `Products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
