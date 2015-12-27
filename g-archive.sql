-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 27, 2015 at 06:45 PM
-- Server version: 5.6.25
-- PHP Version: 5.6.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `g-archive`
--

-- --------------------------------------------------------

--
-- Table structure for table `game`
--

CREATE TABLE IF NOT EXISTS `game` (
  `id_game` int(11) NOT NULL,
  `name` varchar(320) COLLATE utf8_czech_ci NOT NULL,
  `picture` int(11) DEFAULT NULL,
  `cartridge_state` int(11) NOT NULL DEFAULT '2',
  `manual_state` int(11) NOT NULL DEFAULT '2',
  `packing_state` int(11) NOT NULL DEFAULT '2',
  `completion` double NOT NULL,
  `affection` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Dumping data for table `game`
--

INSERT INTO `game` (`id_game`, `name`, `picture`, `cartridge_state`, `manual_state`, `packing_state`, `completion`, `affection`) VALUES
(1, 'Duck Talls', 1, 4, 2, 2, 0.265, 8),
(2, 'Nonexistent game with rly rly long title, srsly games aren''t made to be named this long', NULL, 1, 4, 5, 0.001, 5),
(4, 'Humbukla', 40, 4, 4, 5, 0.343, 4),
(6, 'Marioooh', 42, 1, 2, 2, 0.84, 14),
(7, 'Štěpán Ševčík', 43, 4, 2, 6, 1, 6),
(8, 'Jenga', 72, 1, 1, 1, 0.175, 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `games_human`
--
CREATE TABLE IF NOT EXISTS `games_human` (
`id_game` int(11)
,`name` varchar(320)
,`completion` double
,`affection` int(11)
,`picture_path` varchar(320)
,`cartridge_state` varchar(150)
,`manual_state` varchar(150)
,`packing_state` varchar(150)
);

-- --------------------------------------------------------

--
-- Table structure for table `picture`
--

CREATE TABLE IF NOT EXISTS `picture` (
  `id_picture` int(11) NOT NULL,
  `id_game` int(11) DEFAULT NULL,
  `picture_path` varchar(320) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Dumping data for table `picture`
--

INSERT INTO `picture` (`id_picture`, `id_game`, `picture_path`) VALUES
(1, 1, 'dukTails.jpg'),
(40, 4, 'GrayBat7.jpg'),
(42, 6, 'Super_Mario_Bros_cart.jpg'),
(43, 7, '1381947_612455115466897_2108504428_n.jpg'),
(44, 7, 'alhambra10.jpg'),
(45, 7, 'hra12.png'),
(46, 7, 'hra.png'),
(47, 7, 'tumblr_nwjcvehXip1sk5d7jo1_1280.png'),
(48, 2, 'alhambra.jpg'),
(49, 2, 'hra1.png'),
(50, 2, 'tumblr_nv5q8oQEuZ1sflveso1_400.gif'),
(51, 2, 'tumblr_nwjcvehXip1sk5d7jo1_12801.png'),
(52, 2, 'alhambra1.jpg'),
(53, 2, 'geft.PNG'),
(54, 2, 'hra2.png'),
(55, 2, 'tumblr_nv5q8oQEuZ1sflveso1_4001.gif'),
(56, 2, 'tumblr_nwjcvehXip1sk5d7jo1_12802.png'),
(57, 2, 'alhambra2.jpg'),
(58, 2, 'geft1.PNG'),
(59, 2, 'hra3.png'),
(60, 2, 'tumblr_nv5q8oQEuZ1sflveso1_4002.gif'),
(61, 2, 'tumblr_nwjcvehXip1sk5d7jo1_12803.png'),
(62, 2, 'alhambra3.jpg'),
(63, 2, 'geft2.PNG'),
(64, 2, 'hra4.png'),
(65, 2, 'tumblr_nv5q8oQEuZ1sflveso1_4003.gif'),
(66, 2, 'tumblr_nwjcvehXip1sk5d7jo1_12804.png'),
(67, 2, 'alhambra4.jpg'),
(68, 2, 'geft3.PNG'),
(69, 2, 'hra5.png'),
(70, 2, 'tumblr_nv5q8oQEuZ1sflveso1_4004.gif'),
(71, 2, 'tumblr_nwjcvehXip1sk5d7jo1_12805.png'),
(72, 8, 'jenga2.png'),
(73, 8, 'tumblr_nv5q8oQEuZ1sflveso1_4005.gif');

-- --------------------------------------------------------

--
-- Table structure for table `state`
--

CREATE TABLE IF NOT EXISTS `state` (
  `id_state` int(11) NOT NULL,
  `freshness` int(11) NOT NULL,
  `label` varchar(150) COLLATE utf8_czech_ci NOT NULL,
  `state_cls` varchar(5) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Dumping data for table `state`
--

INSERT INTO `state` (`id_state`, `freshness`, `label`, `state_cls`) VALUES
(1, 100, 'Zbrusu nový', 'new'),
(2, 1, 'Chybí', '404'),
(4, 75, 'Lehce užitý', 'mw'),
(5, 50, 'Použitý', 'ft'),
(6, 25, 'Těžce bitý', 'bs');

-- --------------------------------------------------------

--
-- Structure for view `games_human`
--
DROP TABLE IF EXISTS `games_human`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `games_human` AS select `game`.`id_game` AS `id_game`,`game`.`name` AS `name`,`game`.`completion` AS `completion`,`game`.`affection` AS `affection`,`picture`.`picture_path` AS `picture_path`,`cs`.`label` AS `cartridge_state`,`ms`.`label` AS `manual_state`,`ps`.`label` AS `packing_state` from ((((`game` left join `picture` on((`game`.`picture` = `picture`.`id_picture`))) left join `state` `cs` on((`game`.`cartridge_state` = `cs`.`id_state`))) left join `state` `ms` on((`game`.`manual_state` = `ms`.`id_state`))) left join `state` `ps` on((`game`.`packing_state` = `ps`.`id_state`)));

--
-- Indexes for dumped tables
--

--
-- Indexes for table `game`
--
ALTER TABLE `game`
  ADD PRIMARY KEY (`id_game`);

--
-- Indexes for table `picture`
--
ALTER TABLE `picture`
  ADD PRIMARY KEY (`id_picture`);

--
-- Indexes for table `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`id_state`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `game`
--
ALTER TABLE `game`
  MODIFY `id_game` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `picture`
--
ALTER TABLE `picture`
  MODIFY `id_picture` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=74;
--
-- AUTO_INCREMENT for table `state`
--
ALTER TABLE `state`
  MODIFY `id_state` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
