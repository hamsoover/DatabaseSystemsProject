-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 30, 2020 at 05:24 PM
-- Server version: 5.7.26
-- PHP Version: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fantasyfootball`
--

-- --------------------------------------------------------

--
-- Table structure for table `fantasyteams`
--

DROP TABLE IF EXISTS `fantasyteams`;
CREATE TABLE IF NOT EXISTS `fantasyteams` (
  `team_name` varchar(30) NOT NULL,
  PRIMARY KEY (`team_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `fantasyteams`
--

INSERT INTO `fantasyteams` (`team_name`) VALUES
('New Team'),
('NGA'),
('Superstar Winners'),
('Team Hoover'),
('Test');

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

DROP TABLE IF EXISTS `games`;
CREATE TABLE IF NOT EXISTS `games` (
  `game_id` int(11) NOT NULL,
  `week` int(11) DEFAULT NULL,
  `home_team` varchar(10) DEFAULT NULL,
  `away_team` varchar(10) DEFAULT NULL,
  `home_rush` int(11) DEFAULT NULL,
  `home_pass` int(11) DEFAULT NULL,
  `home_tds` int(11) DEFAULT NULL,
  `away_rush` int(11) DEFAULT NULL,
  `away_pass` int(11) DEFAULT NULL,
  `away_tds` int(11) DEFAULT NULL,
  PRIMARY KEY (`game_id`),
  KEY `home_team_idx` (`home_team`),
  KEY `away_team_idx` (`away_team`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`game_id`, `week`, `home_team`, `away_team`, `home_rush`, `home_pass`, `home_tds`, `away_rush`, `away_pass`, `away_tds`) VALUES
(1, 1, 'CHI', 'GB', 46, 228, 1, 47, 203, 0),
(2, 1, 'CLE', 'TEN', 102, 285, 2, 123, 248, 4),
(3, 1, 'CAR', 'LAR', 127, 239, 3, 166, 186, 3),
(4, 1, 'PHI', 'WAS', 123, 313, 4, 28, 380, 3),
(5, 1, 'NYJ', 'BUF', 68, 175, 1, 128, 254, 2),
(6, 1, 'MIN', 'ATL', 172, 98, 4, 73, 304, 2),
(7, 1, 'MIA', 'BAL', 21, 190, 1, 265, 379, 8),
(8, 1, 'JAX', 'KC', 81, 350, 3, 113, 378, 4),
(9, 1, 'SEA', 'CIN', 72, 195, 3, 34, 418, 2),
(10, 1, 'LAC', 'IND', 125, 333, 4, 203, 180, 3),
(11, 1, 'TB', 'SF', 121, 194, 1, 98, 166, 1),
(12, 1, 'DAL', 'NYG', 89, 405, 5, 151, 323, 2),
(13, 1, 'ARI', 'DET', 112, 308, 2, 116, 385, 3),
(14, 1, 'NE', 'PIT', 99, 373, 3, 32, 276, 0),
(15, 1, 'NO', 'HOU', 148, 370, 3, 180, 268, 4),
(16, 1, 'OAK', 'DEN', 98, 259, 3, 95, 268, 1);

-- --------------------------------------------------------

--
-- Table structure for table `playergamestats`
--

DROP TABLE IF EXISTS `playergamestats`;
CREATE TABLE IF NOT EXISTS `playergamestats` (
  `player` varchar(45) DEFAULT NULL,
  `game_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `rushing_attempts` int(11) DEFAULT NULL,
  `rushing_yds` int(11) DEFAULT NULL,
  `rushing_tds` int(11) DEFAULT NULL,
  `passing_yards` int(11) DEFAULT NULL,
  `passing_attempts` int(11) DEFAULT NULL,
  `completions` int(11) DEFAULT NULL,
  `passing_tds` int(11) DEFAULT NULL,
  `interceptions` int(11) DEFAULT NULL,
  `catches` int(11) DEFAULT NULL,
  `receiving_yds` int(11) DEFAULT NULL,
  `receiving_tds` int(11) DEFAULT NULL,
  PRIMARY KEY (`game_id`,`player_id`),
  KEY `player_id_idx` (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `playergamestats`
--

INSERT INTO `playergamestats` (`player`, `game_id`, `player_id`, `rushing_attempts`, `rushing_yds`, `rushing_tds`, `passing_yards`, `passing_attempts`, `completions`, `passing_tds`, `interceptions`, `catches`, `receiving_yds`, `receiving_tds`) VALUES
('Marcus Mariota', 2, 10, 3, 24, 0, 250, 24, 14, 3, 0, 0, 0, 0),
('Case Keenum', 4, 6, 0, 0, 0, 380, 44, 30, 3, 0, 0, 0, 0),
('Carson Wentz', 4, 8, 4, 5, 0, 313, 39, 28, 3, 0, 0, 0, 0),
('Josh Allen', 5, 16, 10, 38, 1, 254, 37, 24, 1, 2, 0, 0, 0),
('Matt Ryan', 6, 14, 2, 24, 0, 304, 46, 33, 2, 2, 0, 0, 0),
('Lamar Jackson', 7, 1, 3, 6, 0, 324, 20, 17, 5, 0, 0, 0, 0),
('Patrick Mahomes', 8, 5, 1, 2, 0, 378, 33, 25, 3, 0, 0, 0, 0),
('Gardner Minshew II', 8, 15, 1, 6, 0, 275, 25, 22, 2, 1, 0, 0, 0),
('Andy Dalton', 9, 13, 0, 0, 0, 418, 51, 35, 2, 0, 0, 0, 0),
('Russell Wilson', 9, 17, 4, 8, 0, 195, 20, 14, 2, 0, 0, 0, 0),
('Jacoby Brissett', 10, 18, 3, 9, 0, 190, 27, 21, 2, 0, 0, 0, 0),
('Dak Prescott', 12, 2, 4, 12, 0, 405, 32, 25, 4, 0, 0, 0, 0),
('Matthew Stafford', 13, 4, 3, 22, 0, 385, 45, 27, 3, 0, 0, 0, 0),
('Kyler Murray', 13, 11, 3, 13, 0, 308, 54, 29, 2, 1, 0, 0, 0),
('Tom Brady', 14, 7, 0, 0, 0, 341, 36, 24, 3, 0, 0, 0, 0),
('Deshaun Watson', 15, 3, 4, 40, 1, 268, 30, 20, 3, 1, 0, 0, 0),
('Drew Brees', 15, 12, 0, 0, 0, 370, 43, 32, 2, 1, 0, 0, 0),
('Joe Flacco', 16, 20, 1, 1, 0, 268, 31, 21, 1, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

DROP TABLE IF EXISTS `players`;
CREATE TABLE IF NOT EXISTS `players` (
  `player` varchar(30) NOT NULL,
  `player_id` int(11) NOT NULL AUTO_INCREMENT,
  `team` varchar(10) NOT NULL,
  `rushing_attempts` int(11) NOT NULL DEFAULT '0',
  `rushing_yds` int(11) NOT NULL DEFAULT '0',
  `rushing_tds` int(11) NOT NULL DEFAULT '0',
  `passing_yards` int(11) NOT NULL DEFAULT '0',
  `passing_attempts` int(11) NOT NULL DEFAULT '0',
  `completions` int(11) NOT NULL DEFAULT '0',
  `passing_tds` int(11) NOT NULL DEFAULT '0',
  `interceptions` int(11) NOT NULL DEFAULT '0',
  `catches` int(11) NOT NULL DEFAULT '0',
  `receiving_yds` int(11) NOT NULL DEFAULT '0',
  `receiving_tds` int(11) NOT NULL DEFAULT '0',
  `fantasy_id` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`player_id`),
  KEY `team_idx` (`team`),
  KEY `fantasy_id` (`fantasy_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `players`
--

INSERT INTO `players` (`player`, `player_id`, `team`, `rushing_attempts`, `rushing_yds`, `rushing_tds`, `passing_yards`, `passing_attempts`, `completions`, `passing_tds`, `interceptions`, `catches`, `receiving_yds`, `receiving_tds`, `fantasy_id`) VALUES
('Lamar Jackson', 1, 'BAL', 176, 1206, 7, 3127, 400, 265, 36, 6, 0, 0, 0, 'Test'),
('Dak Prescott', 2, 'DAL', 52, 277, 3, 4902, 596, 388, 30, 11, 0, 0, 0, 'Test'),
('Deshaun Watson', 3, 'HOU', 82, 413, 7, 3852, 495, 333, 26, 12, 0, 0, 0, 'NGA'),
('Matthew Stafford', 4, 'DET', 20, 66, 0, 2499, 291, 187, 19, 5, 0, 0, 0, NULL),
('Patrick Mahomes', 5, 'KC', 43, 218, 2, 4031, 484, 319, 26, 5, 0, 0, 0, NULL),
('Case Keenum', 6, 'WAS', 9, 12, 1, 1707, 247, 160, 11, 5, 0, 0, 0, NULL),
('Tom Brady', 7, 'NE', 26, 34, 3, 4057, 613, 373, 24, 8, 0, 0, 0, 'NGA'),
('Carson Wentz', 8, 'PHI', 62, 243, 1, 4039, 607, 388, 27, 7, 0, 0, 0, NULL),
('Marcus Mariota', 10, 'TEN', 24, 129, 0, 1203, 160, 95, 7, 2, 0, 0, 0, NULL),
('Kyler Murray', 11, 'ARI', 93, 544, 4, 3722, 542, 349, 20, 12, 0, 0, 0, 'Team Hoover'),
('Drew Brees', 12, 'NO', 9, -4, 1, 2979, 378, 281, 27, 4, 0, 0, 0, 'Superstar Winners'),
('Andy Dalton', 13, 'CIN', 32, 73, 4, 3494, 528, 314, 16, 14, 0, 0, 0, 'Team Hoover'),
('Matt Ryan', 14, 'ATL', 34, 147, 1, 4466, 616, 408, 26, 14, 0, 0, 0, NULL),
('Gardner Minshew II', 15, 'JAX', 67, 344, 0, 3271, 470, 285, 21, 6, 0, 0, 0, NULL),
('Josh Allen', 16, 'BUF', 109, 510, 9, 3089, 461, 271, 20, 9, 0, 0, 0, NULL),
('Russell Wilson', 17, 'SEA', 75, 342, 3, 4110, 516, 341, 31, 5, 0, 0, 0, NULL),
('Jacoby Brissett', 18, 'IND', 56, 228, 4, 2942, 447, 272, 18, 6, 0, 0, 0, 'Superstar Winners'),
('Joe Flacco', 20, 'DEN', 12, 20, 0, 1822, 262, 171, 6, 5, 0, 0, 0, NULL),
('Test Player', 26, 'DEN', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
CREATE TABLE IF NOT EXISTS `teams` (
  `team_name` varchar(10) NOT NULL,
  `division` varchar(10) NOT NULL,
  `bye_week` int(11) DEFAULT NULL,
  `rushing_yds` int(11) DEFAULT NULL,
  `passing_yds` int(11) DEFAULT NULL,
  `tds` int(11) DEFAULT NULL,
  `rushing_against` int(11) DEFAULT NULL,
  `passing_against` int(11) DEFAULT NULL,
  `tds_against` int(11) DEFAULT NULL,
  PRIMARY KEY (`team_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`team_name`, `division`, `bye_week`, `rushing_yds`, `passing_yds`, `tds`, `rushing_against`, `passing_against`, `tds_against`) VALUES
('ARI', 'NFCW', 7, 1990, 3477, 38, 1391, 3779, 37),
('ATL', 'NFCS', 6, 1361, 4714, 39, 1461, 3868, 39),
('BAL', 'AFCN', 4, 3296, 3225, 58, 1528, 2886, 20),
('BUF', 'AFCE', 8, 2054, 3229, 34, 1775, 3918, 41),
('CAR', 'NFCS', 8, 1819, 3650, 37, 2315, 3470, 44),
('CHI', 'NFCN', 10, 1458, 3291, 28, 1181, 4322, 41),
('CIN', 'AFCN', 10, 1517, 3652, 27, 1812, 4225, 49),
('CLE', 'AFCN', 8, 1901, 3554, 37, 1883, 4223, 41),
('DAL', 'NFCE', 5, 2153, 4751, 48, 1753, 3113, 30),
('DEN', 'AFCW', 10, 1662, 3115, 27, 1922, 4510, 47),
('DET', 'NFCN', 7, 1649, 3900, 35, 1567, 3982, 37),
('GB', 'NFCN', 7, 1795, 3733, 44, 1442, 3865, 40),
('HOU', 'AFCS', 7, 2009, 3783, 44, 1805, 3204, 36),
('IND', 'AFCS', 7, 2130, 3108, 39, 1809, 3625, 38),
('JAX', 'AFCS', 9, 1708, 3760, 27, 1855, 4551, 46),
('KC', 'AFCW', 5, 1569, 4498, 46, 1728, 3737, 31),
('LAC', 'AFCW', 8, 1453, 4426, 36, 2229, 3778, 45),
('LAR', 'NFCW', 6, 1499, 4499, 42, 1656, 3576, 35),
('MIA', 'AFCE', 9, 1156, 3804, 32, 2382, 3917, 42),
('MIN', 'NFCN', 6, 2133, 3523, 45, 1802, 2707, 34),
('NE', 'AFCE', 5, 1703, 3961, 42, 2051, 3543, 35),
('NO', 'NFCS', 5, 1738, 4244, 48, 1632, 3554, 33),
('NYG', 'NFCE', 8, 1685, 3731, 41, 1937, 4276, 45),
('NYJ', 'AFCE', 10, 1257, 3111, 25, 2296, 3696, 52),
('OAK', 'AFCW', 8, 1893, 3926, 35, 1570, 4107, 48),
('PHI', 'NFCE', 6, 1939, 3833, 43, 1672, 4080, 39),
('PIT', 'AFCN', 9, 1447, 2981, 25, 2339, 3823, 49),
('SEA', 'NFCW', 6, 2200, 3791, 46, 1921, 3721, 34),
('SF', 'NFCW', 4, 2305, 3792, 51, 1649, 3123, 27),
('TB', 'NFCS', 4, 1521, 4845, 48, 1494, 3315, 27),
('TEN', 'AFCS', 6, 2223, 3582, 50, 1783, 3609, 28),
('WAS', 'NFCE', 10, 1583, 2812, 27, 2166, 4198, 54);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `away_team` FOREIGN KEY (`away_team`) REFERENCES `teams` (`team_name`),
  ADD CONSTRAINT `home_team` FOREIGN KEY (`home_team`) REFERENCES `teams` (`team_name`);

--
-- Constraints for table `playergamestats`
--
ALTER TABLE `playergamestats`
  ADD CONSTRAINT `game_id` FOREIGN KEY (`game_id`) REFERENCES `games` (`game_id`),
  ADD CONSTRAINT `playergamestats_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `players` (`player_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `playergamestats_ibfk_2` FOREIGN KEY (`player_id`) REFERENCES `players` (`player_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `players`
--
ALTER TABLE `players`
  ADD CONSTRAINT `players_ibfk_1` FOREIGN KEY (`fantasy_id`) REFERENCES `fantasyteams` (`team_name`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `team` FOREIGN KEY (`team`) REFERENCES `teams` (`team_name`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
