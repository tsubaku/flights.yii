-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 192.168.1.251:3306
-- Generation Time: Feb 22, 2018 at 04:40 PM
-- Server version: 5.6.34
-- PHP Version: 5.6.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ortexsecur_tracking`
--

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`id`, `name`) VALUES
(240, 'Клиент 1'),
(242, 'Клиент 2'),
(243, 'Клиент 3'),
(245, 'Клиент 5'),
(247, 'Клиент 4');

-- --------------------------------------------------------

--
-- Table structure for table `flight`
--

CREATE TABLE `flight` (
  `id` int(11) UNSIGNED NOT NULL,
  `data_vyezda` date DEFAULT NULL,
  `vremja` time(6) NOT NULL,
  `klient` varchar(32) NOT NULL,
  `podklient` varchar(32) NOT NULL,
  `nomer_mashiny` varchar(32) NOT NULL,
  `prinjatie_pod_ohranu` varchar(32) NOT NULL,
  `sdacha_s_ohrany` varchar(32) NOT NULL,
  `sostav_ohr` varchar(32) NOT NULL,
  `fio` varchar(32) NOT NULL,
  `vydano` varchar(32) NOT NULL,
  `mashina` varchar(32) NOT NULL,
  `srok_dostavki` time(6) DEFAULT '00:00:00.000000',
  `prinjatie` datetime(6) DEFAULT NULL,
  `sdacha` datetime(6) DEFAULT NULL,
  `fakticheskij_srok_dostavki` text,
  `prostoj_chasy` int(11) NOT NULL,
  `prostoj_stavka_za_ohrannika` int(11) NOT NULL,
  `prostoj_summa` int(11) NOT NULL,
  `stavka_bez_nds` int(11) NOT NULL,
  `stavka_s_nds` int(11) NOT NULL,
  `schet` int(11) NOT NULL,
  `zp` int(11) NOT NULL,
  `prostoj` int(11) NOT NULL,
  `arenda_mashin` int(11) NOT NULL,
  `oplata_mashin` int(11) NOT NULL,
  `itogo` int(11) NOT NULL,
  `zp_plus_prostoj` int(11) NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `flight`
--

INSERT INTO `flight` (`id`, `data_vyezda`, `vremja`, `klient`, `podklient`, `nomer_mashiny`, `prinjatie_pod_ohranu`, `sdacha_s_ohrany`, `sostav_ohr`, `fio`, `vydano`, `mashina`, `srok_dostavki`, `prinjatie`, `sdacha`, `fakticheskij_srok_dostavki`, `prostoj_chasy`, `prostoj_stavka_za_ohrannika`, `prostoj_summa`, `stavka_bez_nds`, `stavka_s_nds`, `schet`, `zp`, `prostoj`, `arenda_mashin`, `oplata_mashin`, `itogo`, `zp_plus_prostoj`, `status`) VALUES
(1, '2018-02-06', '10:00:00.000000', 'Клиент 3', '', '6606', '', '', '', 'Менеджер', '3000', '', '24:00:00.000000', '2018-02-06 11:01:00.000000', '2018-02-07 12:01:00.000000', '25:00', 1, 1200, 2400, 0, 0, 2400, 0, 0, 0, 0, 0, 0, 0),
(2, '2018-02-07', '00:00:00.000000', '', '', '', '', '', '', '', '', '', '00:00:00.000000', NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(3, '2018-02-15', '00:00:00.000000', '', '', '', '', '', '', '', '', '', '00:00:00.000000', NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(4, NULL, '00:00:00.000000', '', '', '', '', '', '', '', '', '', '00:00:00.000000', NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(5, NULL, '00:00:00.000000', '', '', '', '', '', '', '', '', '', '00:00:00.000000', NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gun`
--

CREATE TABLE `gun` (
  `id` int(11) NOT NULL,
  `name` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gun`
--

INSERT INTO `gun` (`id`, `name`) VALUES
(1, 'ИЖ 71 0001'),
(2, 'ИЖ 71 0002'),
(3, 'ИЖ 71 0003'),
(4, 'ИЖ 71 0004'),
(5, 'ИЖ 71 0005'),
(6, 'ИЖ 71 0006'),
(7, 'ИЖ 71 0007'),
(8, 'ИЖ 71 0008'),
(9, 'ИЖ 71 0009');

-- --------------------------------------------------------

--
-- Table structure for table `photo`
--

CREATE TABLE `photo` (
  `id` int(100) NOT NULL,
  `n_flight` int(100) NOT NULL,
  `user_login` varchar(100) NOT NULL,
  `path` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sentry`
--

CREATE TABLE `sentry` (
  `id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `time_on` time NOT NULL,
  `gun` varchar(199) NOT NULL,
  `date_off` date NOT NULL,
  `time_off` time NOT NULL,
  `time_report` time NOT NULL,
  `note` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `sentry`
--

INSERT INTO `sentry` (`id`, `number`, `full_name`, `date`, `time_on`, `gun`, `date_off`, `time_off`, `time_report`, `note`) VALUES
(1, 0, 'Петров Пётр', '2018-01-25', '16:34:44', '', '2018-01-25', '16:34:35', '00:00:00', ''),
(3, 2, 'Иванов Иван', '2018-02-19', '17:18:52', 'ИЖ 71 0002', '0000-00-00', '00:00:00', '00:00:00', ''),
(4, 0, 'Петров Пётр', '2018-01-25', '17:17:46', 'ИЖ 71 0003', '2018-01-31', '15:38:32', '00:00:00', ''),
(5, 0, 'Сидоров Сергей', '2018-01-25', '16:34:46', 'ИЖ 71 0006', '2018-01-31', '15:38:33', '00:00:00', ''),
(6, 0, 'Николаев Николай', '2018-01-25', '17:31:06', '', '2018-01-25', '16:34:38', '00:00:00', ''),
(8, 3, 'Петров Пётр', '2018-01-25', '17:19:22', '', '2018-01-25', '16:34:38', '00:00:00', ''),
(11, 6, 'Иванов Иван', '2018-01-25', '17:31:29', 'ИЖ 71 0002', '2018-01-25', '16:34:42', '00:00:00', ''),
(14, 4, 'Иванов Иван', '2018-01-26', '00:00:00', 'ИЖ 71 0004', '2018-01-31', '15:38:22', '00:00:00', ''),
(37, 4, 'Николаев Николай', '2018-01-27', '00:00:00', '', '2018-01-31', '15:38:24', '00:00:00', ''),
(40, 0, 'Не выбран', '2018-01-27', '00:00:00', 'ИЖ 71 0002', '0000-00-00', '12:56:28', '00:00:00', ''),
(44, 3, 'Не выбран', '2018-01-27', '00:00:00', '', '0000-00-00', '12:51:38', '00:00:00', ''),
(45, 0, 'Петров Пётр', '2018-01-29', '00:00:00', '', '2018-01-29', '12:51:44', '00:00:00', ''),
(55, 0, 'Петров Пётр', '2018-01-29', '12:52:23', 'ИЖ 71 0003', '2018-01-31', '15:38:07', '00:00:00', ''),
(56, 1, 'Иванов Иван', '2018-01-28', '12:48:19', '', '2018-01-31', '15:38:17', '00:00:00', ''),
(70, 0, 'Сидоров Сергей', '2018-01-31', '16:32:31', '', '2018-01-31', '15:49:51', '00:00:00', ''),
(71, 0, 'Петров Пётр', '2018-01-31', '16:19:30', '', '2018-01-31', '15:38:29', '00:00:00', ''),
(78, 12, 'Петров Пётр', '2018-01-28', '00:00:00', '', '2018-01-31', '15:38:18', '00:00:00', ''),
(79, 4, 'Николаев Николай', '2018-01-28', '12:57:04', '', '2018-01-31', '15:38:19', '00:00:00', ''),
(88, 11, 'Иванов Иван', '2018-01-28', '13:00:46', 'ИЖ 71 0002', '2018-01-30', '12:56:29', '00:00:00', ''),
(111, 0, 'Иванов Иван', '2018-02-18', '12:02:40', 'ИЖ 71 0004', '0000-00-00', '00:00:00', '00:00:00', ''),
(112, 1, 'Петров Пётр', '2018-02-01', '15:13:49', '', '2018-02-05', '17:53:18', '00:00:00', ''),
(113, 4, 'Николаев Николай', '2018-02-01', '09:00:00', '', '2018-02-05', '17:53:17', '00:00:00', ''),
(114, 2, 'Петров Пётр', '2018-02-01', '09:03:00', '', '2018-02-05', '17:53:17', '00:00:00', ''),
(115, 3, 'Сидоров Сергей', '2018-02-01', '09:08:00', '', '2018-02-05', '17:53:16', '00:00:00', ''),
(116, 0, 'Сидоров Сергей', '2018-02-01', '10:03:00', '', '2018-02-05', '17:53:15', '00:00:00', ''),
(119, 0, 'Васильев Василий', '2018-02-01', '08:20:00', 'ИЖ 71 0001', '2018-02-05', '17:53:15', '00:00:00', ''),
(120, 0, 'Сидоров Сергей', '2018-02-01', '08:30:00', 'ИЖ 71 0009', '2018-02-05', '17:53:14', '00:00:00', ''),
(121, 0, 'Иванов Иван', '2018-02-01', '08:10:00', 'ИЖ 71 0004', '2018-02-05', '17:53:14', '00:00:00', ''),
(122, 1, 'Иванов Иван', '2018-02-16', '15:13:25', '', '0000-00-00', '00:00:00', '00:00:00', ''),
(123, 2, 'Петров Пётр', '2018-02-17', '15:13:25', '', '0000-00-00', '00:00:00', '00:00:00', ''),
(124, 3, 'Не выбран', '2018-01-01', '00:00:00', '', '0000-00-00', '00:00:00', '00:00:00', ''),
(125, 4, 'Не выбран', '2018-01-01', '00:00:00', '', '0000-00-00', '00:00:00', '00:00:00', ''),
(132, 0, 'Не выбран', '2018-01-01', '00:00:00', '', '0000-00-00', '00:00:00', '00:00:00', ''),
(144, 0, 'Васильев Василий', '2018-02-05', '09:00:00', '', '2018-02-05', '17:53:24', '00:00:00', ''),
(145, 0, 'Иванов Иван', '2018-02-05', '09:01:00', '', '2018-02-05', '17:53:23', '00:00:00', ''),
(146, 0, 'Сидоров Сергей', '2018-02-05', '15:13:21', '', '2018-02-05', '17:53:23', '00:00:00', ''),
(147, 0, 'Иванов Иван', '2018-02-05', '15:13:21', '', '2018-02-05', '17:53:23', '00:00:00', ''),
(148, 0, 'Сидоров Сергей', '2018-02-05', '15:13:22', '', '2018-02-05', '17:53:22', '00:00:00', ''),
(149, 0, 'Васильев Василий', '2018-02-05', '15:13:22', '', '2018-02-05', '17:53:22', '00:00:00', ''),
(150, 0, 'Николаев Николай', '2018-02-05', '15:13:23', '', '2018-02-05', '17:53:22', '00:00:00', ''),
(151, 0, 'Иванов Иван', '2018-02-05', '15:13:23', '', '2018-02-05', '17:53:22', '00:00:00', ''),
(152, 0, 'Николаев Николай', '2018-02-05', '15:13:23', '', '2018-02-05', '17:53:21', '00:00:00', ''),
(153, 0, 'Николаев Николай', '2018-02-05', '15:13:24', '', '2018-02-05', '17:53:26', '00:00:00', ''),
(154, 0, 'Иванов Иван', '2018-02-05', '15:13:24', '', '2018-02-05', '17:53:28', '00:00:00', ''),
(172, 0, 'Иванов Иван', '2018-02-20', '15:00:47', 'ИЖ 71 0004', '2018-02-21', '15:01:00', '00:00:00', ''),
(173, 0, 'Сидоров Сергей', '2018-02-01', '00:00:00', '', '0000-00-00', '00:00:00', '00:00:00', ''),
(174, 0, 'Васильев Василий', '2018-02-12', '00:00:00', '', '0000-00-00', '00:00:00', '00:00:00', ''),
(175, 0, 'Сидоров Сергей', '2018-02-13', '00:00:00', '', '0000-00-00', '00:00:00', '00:00:00', ''),
(176, 0, 'Иванов Иван', '2018-02-14', '00:00:00', '', '0000-00-00', '00:00:00', '00:00:00', ''),
(177, 0, 'Иванов Иван', '2018-02-15', '00:00:00', '', '0000-00-00', '00:00:00', '00:00:00', '');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `content`) VALUES
(1, 'sentryHeaderText', '<p><b>Утверждаю</b></p><p>Генеральный директор ООО ЧОП Охрана Охраны</p> <p>Иванов И.И.</p>'),
(17, 'companyName', 'ООО ЧОП Охрана Охраны');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `full_name` text NOT NULL,
  `role` text NOT NULL,
  `department` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `full_name`, `role`, `department`) VALUES
(101, 'manager', '$2y$13$Rx8wuhT0GTHIs3tT85AU1.hBLEjBHMLAppVG37Q//ihuRHiAPCQuK', 'Менеджер', '20', 'Без отдела'),
(258, 'IvanovI', '$2y$13$hZ23a5dDGfn4VPoyGQZvI.9lObZtYpG/KuPMZc5G4Ubcb8w6w14QK', 'Иванов Иван', '10', 'Сопровождение'),
(259, 'PetrovP', '$2y$13$j.BsfloY51QlVpLl9Gvx5eZkZXKlnLyuv7xF7qblp1QaZpzo0C242', 'Петров Пётр', '10', 'Без отдела'),
(260, 'SidorovS', '$2y$13$b.KWKH1a3uWtIwILin9D0uY53RIylDJi9FZnUvXJWBF75S/UNNe6.', 'Сидоров Сергей', '10', 'Сопровождение'),
(261, 'NikolaevN', '$2y$13$IQBnVdcGWA406DMO/qkG3OKd9g1gJrfQhHbav/WmXrG8jJkxGPDNC', 'Николаев Николай', '10', 'Сопровождение'),
(263, 'Operator', '$2y$13$kz/Ywt7L.JiOlR2OeP0ju.Sgn9nZOifb5eCqgklfeDheg69XeO3I.', 'Оператор', '15', 'Без отдела'),
(264, 'VasiliyV', '$2y$13$5YiATdZFQwkYgkWXjQzUdOi8F2F.BEDfcDpx0BwuJLNEw2LxcxZ3O', 'Васильев Василий', '10', 'Сопровождение');

-- --------------------------------------------------------

--
-- Table structure for table `user_gun`
--

CREATE TABLE `user_gun` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `gun_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_gun`
--

INSERT INTO `user_gun` (`id`, `user_id`, `gun_id`) VALUES
(269, 264, 1),
(270, 264, 5),
(271, 258, 2),
(272, 101, 3),
(274, 101, 1),
(275, 261, 2),
(276, 261, 1),
(277, 263, 6),
(278, 260, 1),
(279, 260, 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `flight`
--
ALTER TABLE `flight`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gun`
--
ALTER TABLE `gun`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sentry`
--
ALTER TABLE `sentry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_gun`
--
ALTER TABLE `user_gun`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=249;
--
-- AUTO_INCREMENT for table `flight`
--
ALTER TABLE `flight`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `gun`
--
ALTER TABLE `gun`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `photo`
--
ALTER TABLE `photo`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `sentry`
--
ALTER TABLE `sentry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=216;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=271;
--
-- AUTO_INCREMENT for table `user_gun`
--
ALTER TABLE `user_gun`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=280;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
