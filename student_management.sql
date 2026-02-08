-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2026 at 03:13 PM
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
-- Database: `student_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `course_code` varchar(20) DEFAULT NULL,
  `lecturer_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `class_time` varchar(50) DEFAULT NULL,
  `class_day` varchar(20) DEFAULT NULL,
  `room` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `code`, `course_code`, `lecturer_id`, `name`, `class_time`, `class_day`, `room`) VALUES
(2, '163227', 'ET3050', 2, 'Thong tin so', '7:00 - 9:30', '5', 'D8-505'),
(4, '312324', 'ET2072', 1, 'Ly thuyet thong tin', '7:00 - 9:30', '3', 'D3-501'),
(5, '163115', 'ET2050', 1, 'Công nghệ thông tin', '10:00 - 11:30', '3', 'D3-501'),
(6, '123513', 'ET2010', 2, 'Nhập môn kỹ thuật', '12:30 - 14:55', '6', 'D8-505');

-- --------------------------------------------------------

--
-- Table structure for table `class_students`
--

CREATE TABLE `class_students` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `attendance` int(11) DEFAULT 0,
  `qt` decimal(4,2) DEFAULT NULL,
  `ck` decimal(4,2) DEFAULT NULL,
  `total` decimal(4,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_students`
--

INSERT INTO `class_students` (`id`, `class_id`, `student_id`, `attendance`, `qt`, `ck`, `total`) VALUES
(1, 5, 1, 2, 4.00, 7.00, 5.80),
(2, 5, 9, 1, 8.00, 9.00, 8.60),
(3, 5, 8, 0, 10.00, 9.50, 9.70),
(4, 5, 7, 0, 6.00, 9.00, 7.80),
(5, 5, 12, 1, 5.00, 3.50, 4.10),
(8, 2, 9, 0, NULL, NULL, NULL),
(9, 4, 9, 0, 10.00, 10.00, 10.00),
(10, 6, 9, 0, 5.00, 6.00, 5.60);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `info` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `info`) VALUES
(1, 'Kinh tế', 'Văn phòng: C9 - 303,304 / \r\nĐiện thoại: 024 38 692 304 / \r\nWebsite: sem.hust.edu.vn'),
(2, 'Lý luận Chính trị', 'Văn phòng: D3 - 306 / \r\nĐiện thoại: 024 3869 2105 - 024 3868 4401 / \r\nWebsite: fpt.hust.edu.vn'),
(4, 'Cơ Khí', 'Văn phòng: 614M - C7 / Điên thoại: 0868040770 / Website: sme.hust.edu.vn'),
(5, 'Điện - Điện tử', 'Văn phòng: C7 - E605 / \r\nĐiện thoại: 024 3869 6211/024 3869 2242 / \r\nWebsite: seee.hust.edu.vn'),
(6, 'Vật lý Kĩ thuật', 'Văn phòng: C10-116 /\r\nĐiện thoại: 024 3869 3350 /\r\nWebsite: sep.hust.edu.vn'),
(7, 'Toán tin', 'Văn phòng: D3 - 106 / \r\nĐiện thoại: 024 38692137 / \r\nWebsite: fami.hust.edu.vn'),
(9, 'Công nghệ và Truyền thông', '');

-- --------------------------------------------------------

--
-- Table structure for table `lecturers`
--

CREATE TABLE `lecturers` (
  `id` int(11) NOT NULL,
  `lecturer_code` varchar(20) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `department_id` int(11) NOT NULL,
  `unit` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lecturers`
--

INSERT INTO `lecturers` (`id`, `lecturer_code`, `full_name`, `email`, `department_id`, `unit`, `position`) VALUES
(1, '001002003', 'Nguyễn Thành Long', 'ntl223199@sis.hust.edu.vn', 5, 'Trường Điện Điện tử', 'Giáo viên chính'),
(2, '002005232', 'Lê Trường Giang', 'dasdasds@gmail.com', 5, 'Viện toán - tin', 'Giáo viên chính');

-- --------------------------------------------------------

--
-- Table structure for table `majors`
--

CREATE TABLE `majors` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `majors`
--

INSERT INTO `majors` (`id`, `code`, `name`) VALUES
(1, 'ET1', 'Điện tử viễn thông'),
(2, 'IT1', 'Khoa học Máy tính'),
(3, 'EE2', 'Tự động hóa'),
(4, 'ME2', 'Kỹ thuật Cơ khí'),
(5, 'TE1', 'Kỹ thuật Y sinh');

-- --------------------------------------------------------

--
-- Table structure for table `major_subjects`
--

CREATE TABLE `major_subjects` (
  `id` int(11) NOT NULL,
  `major_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `major_subjects`
--

INSERT INTO `major_subjects` (`id`, `major_id`, `subject_id`) VALUES
(2, 1, 1),
(3, 1, 2),
(5, 1, 3),
(6, 1, 4),
(7, 1, 5),
(8, 1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_code` varchar(20) NOT NULL,
  `major_id` int(11) DEFAULT NULL,
  `birth_date` date NOT NULL,
  `email` varchar(150) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `major_class` varchar(100) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `gender` enum('Nam','Nữ','Khác') DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `cohort` varchar(10) DEFAULT NULL,
  `hometown` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_code`, `major_id`, `birth_date`, `email`, `full_name`, `major_class`, `department_id`, `avatar`, `gender`, `phone`, `cohort`, `hometown`) VALUES
(1, '20250051', 1, '2007-02-13', 'ab@gmail.com', 'Anh Nguyen Van', 'IT1 02', 5, '1770300574_9081.jpg', 'Nam', '2131252342312', 'K68', 'Việt trì'),
(7, '20253214', 1, '2007-06-13', 'ndsadasdg@gmail.com', 'Long Dao Thai', 'ET1 05', 5, '1770300715_2161.jpg', 'Nam', '1231251245643748', 'K67', 'Nghệ an'),
(8, '20254234', 4, '2016-06-06', 'dssdsada@gmail.com', 'Hai Hello Thanh', 'ME2', 4, NULL, 'Nam', '1249827510412494', 'K69', 'Thanh hóa'),
(9, '20223919', 1, '2004-02-14', 'dsadsdadsgdsg@gmail.com', 'Duc Nguyen Truong Hong', 'ET1 09', 5, '1768748565_9577.jpg', 'Nam', '2314151231321', 'K67', 'Hà nội'),
(12, '20225213', 1, '0000-00-00', 'dsadsdadsgdsfdg@gmail.com', 'Lê Văn Thành', 'ET1 09', 5, 'sv_6962848b01143.png', 'Nam', '324234324239', 'K69', 'Hà nội');

-- --------------------------------------------------------

--
-- Table structure for table `student_subject_scores`
--

CREATE TABLE `student_subject_scores` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `score` decimal(4,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_subject_scores`
--

INSERT INTO `student_subject_scores` (`id`, `student_id`, `subject_id`, `class_id`, `score`, `created_at`) VALUES
(1, 12, 1, NULL, 3.50, '2026-02-07 08:56:26'),
(2, 12, 2, NULL, 3.20, '2026-02-07 08:56:26'),
(3, 9, 2, NULL, 3.00, '2026-02-07 08:56:26'),
(4, 9, 1, NULL, 4.00, '2026-02-07 08:56:26'),
(5, 9, 3, NULL, 4.00, '2026-02-07 08:56:26'),
(6, 9, 4, NULL, 2.00, '2026-02-07 08:56:26'),
(7, 9, 5, NULL, 3.50, '2026-02-07 08:56:26'),
(8, 12, 4, NULL, 3.50, '2026-02-07 08:56:26'),
(9, 12, 5, NULL, 3.50, '2026-02-07 08:56:26'),
(10, 12, 3, NULL, 2.00, '2026-02-07 08:56:26'),
(16, 9, 5, 4, 3.04, '2026-02-07 08:56:49'),
(17, 9, 5, 4, 2.40, '2026-02-07 08:57:09'),
(18, 9, 5, 4, 3.36, '2026-02-07 08:58:09'),
(19, 9, 5, 4, 4.00, '2026-02-07 08:58:11'),
(20, 9, 6, 6, 1.60, '2026-02-07 08:59:53'),
(21, 9, 6, 6, 1.76, '2026-02-07 09:00:28'),
(22, 9, 6, 6, 2.00, '2026-02-07 09:00:29'),
(23, 9, 6, 6, 2.48, '2026-02-08 13:47:59'),
(24, 9, 6, 6, 2.24, '2026-02-08 13:48:05'),
(25, 1, 2, NULL, 3.00, '2026-02-08 13:48:52'),
(26, 1, 6, NULL, 5.00, '2026-02-08 13:48:52'),
(27, 1, 1, NULL, 1.00, '2026-02-08 13:48:53'),
(28, 1, 4, NULL, 2.00, '2026-02-08 13:48:54'),
(29, 1, 5, NULL, 3.00, '2026-02-08 13:48:56'),
(30, 1, 3, NULL, 3.00, '2026-02-08 13:48:59'),
(31, 1, 6, NULL, 3.50, '2026-02-08 13:49:02');

-- --------------------------------------------------------

--
-- Table structure for table `student_subject_scores_old`
--

CREATE TABLE `student_subject_scores_old` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `score` float DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_subject_scores_old`
--

INSERT INTO `student_subject_scores_old` (`id`, `student_id`, `subject_id`, `score`, `class_id`) VALUES
(1, 12, 1, 3.5, NULL),
(2, 12, 2, 3.2, NULL),
(7, 9, 2, 3, NULL),
(8, 9, 1, 4, NULL),
(9, 9, 3, 4, NULL),
(10, 9, 4, 2, NULL),
(11, 9, 5, 3.5, NULL),
(17, 12, 4, 3.5, NULL),
(18, 12, 5, 3.5, NULL),
(19, 12, 3, 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `credits` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `code`, `name`, `credits`) VALUES
(1, 'ET2020', 'Lập trình C', 2),
(2, 'ET2000', 'Nhập môn viễn thông', 2),
(3, 'ET3050', 'Thông tin số', 3),
(4, 'ET2060', 'Tín hiệu hệ thống', 3),
(5, 'ET2072', 'Lý thuyết thông tin', 2),
(6, 'ET2010', 'Nhập môn kỹ thuật', 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`) VALUES
(1, 'admin', '$2y$10$WZmVxsYtHvhDYH83VCcPu.MoAEgCFmLmUoWPy9vTVD7DIcEd5mdCa', NULL),
(4, '20223919', '$2y$10$K9RCHwwpr9T.rahHpKBRYuaguVM2n/B2wTc82TwV5g.wpB1bvxXce', 'Duc Nguyen Truong Hong'),
(5, '001002003', '$2y$10$oldyH/.jmIX3cf86lN8L/eWBT7F/sFYeYMXls4ydalYgbGh2wt2R6', 'Nguyễn Thành Long');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_classes_lecturer` (`lecturer_id`);

--
-- Indexes for table `class_students`
--
ALTER TABLE `class_students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_class_student` (`class_id`,`student_id`),
  ADD KEY `fk_cs_student` (`student_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lecturers`
--
ALTER TABLE `lecturers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lecturer_code` (`lecturer_code`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `majors`
--
ALTER TABLE `majors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `major_subjects`
--
ALTER TABLE `major_subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `major_id` (`major_id`,`subject_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_code` (`student_code`),
  ADD KEY `fk_students_department` (`department_id`),
  ADD KEY `fk_students_majors` (`major_id`);

--
-- Indexes for table `student_subject_scores`
--
ALTER TABLE `student_subject_scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_student` (`student_id`),
  ADD KEY `idx_subject` (`subject_id`),
  ADD KEY `idx_class` (`class_id`);

--
-- Indexes for table `student_subject_scores_old`
--
ALTER TABLE `student_subject_scores_old`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`,`subject_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `class_students`
--
ALTER TABLE `class_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `lecturers`
--
ALTER TABLE `lecturers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `majors`
--
ALTER TABLE `majors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `major_subjects`
--
ALTER TABLE `major_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `student_subject_scores`
--
ALTER TABLE `student_subject_scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `student_subject_scores_old`
--
ALTER TABLE `student_subject_scores_old`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `fk_classes_lecturer` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `class_students`
--
ALTER TABLE `class_students`
  ADD CONSTRAINT `fk_cs_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cs_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lecturers`
--
ALTER TABLE `lecturers`
  ADD CONSTRAINT `lecturers_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);

--
-- Constraints for table `major_subjects`
--
ALTER TABLE `major_subjects`
  ADD CONSTRAINT `major_subjects_ibfk_1` FOREIGN KEY (`major_id`) REFERENCES `majors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `major_subjects_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_students_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  ADD CONSTRAINT `fk_students_major` FOREIGN KEY (`major_id`) REFERENCES `majors` (`id`),
  ADD CONSTRAINT `fk_students_majors` FOREIGN KEY (`major_id`) REFERENCES `majors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `student_subject_scores_old`
--
ALTER TABLE `student_subject_scores_old`
  ADD CONSTRAINT `student_subject_scores_old_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_subject_scores_old_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
