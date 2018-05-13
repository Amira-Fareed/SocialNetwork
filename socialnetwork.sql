-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 13, 2018 at 10:39 PM
-- Server version: 5.7.21
-- PHP Version: 5.6.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `socialnetwork`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `body` text NOT NULL,
  `posted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `post_id`, `user_id`, `body`, `posted_at`) VALUES
(25, 3, 1, 'hihihihi', '2018-05-12 01:36:26'),
(26, 4, 1, 'ss', '2018-05-12 01:42:00'),
(28, 44, 10, 'n', '2018-05-12 02:07:00'),
(29, 40, 11, 'PM PM PM', '2018-05-12 07:34:18'),
(30, 45, 11, 'Shit', '2018-05-12 07:35:45'),
(31, 49, 11, '7anon> Amira', '2018-05-12 07:45:57'),
(32, 49, 11, 'then 7anon = ???', '2018-05-12 07:46:46');

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

DROP TABLE IF EXISTS `friends`;
CREATE TABLE IF NOT EXISTS `friends` (
  `friends_ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_ID` int(11) UNSIGNED DEFAULT NULL,
  `friend_ID` int(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`friends_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`friends_ID`, `user_ID`, `friend_ID`) VALUES
(7, 2, 3),
(8, 3, 2),
(9, 2, 4),
(10, 4, 2),
(53, 1, 2),
(54, 2, 1),
(57, 1, 4),
(58, 4, 1),
(61, 1, 3),
(62, 3, 1),
(67, 10, 6),
(68, 6, 10);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `groupID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `adminID` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`groupID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`groupID`, `name`, `adminID`) VALUES
(4, 'eee', 1),
(3, '1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `group_users`
--

DROP TABLE IF EXISTS `group_users`;
CREATE TABLE IF NOT EXISTS `group_users` (
  `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_ID` int(11) UNSIGNED NOT NULL,
  `user_ID` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `group_users`
--

INSERT INTO `group_users` (`ID`, `group_ID`, `user_ID`) VALUES
(1, 3, 1),
(2, 4, 1),
(3, 3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `post_ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_ID` int(11) UNSIGNED DEFAULT NULL,
  `body` varchar(260) DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `likes` int(11) DEFAULT '0',
  `comments` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `group_ID` int(11) UNSIGNED DEFAULT '0',
  PRIMARY KEY (`post_ID`),
  KEY `user_ID` (`user_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_ID`, `user_ID`, `body`, `posted_at`, `likes`, `comments`, `group_ID`) VALUES
(3, 3, 'my name is mohamed', '2018-04-30 02:43:27', 1, 1, 0),
(4, 2, 'my name is ahmed', '2018-04-30 02:44:56', 2, 1, 0),
(5, 1, 'my name is eslam', '2018-04-30 02:46:06', 1, 0, 0),
(9, 1, 'my name is eslam', '2018-04-30 15:52:24', 2, 0, 0),
(40, 4, 'am am am ', '2018-05-07 03:00:38', 3, 1, 0),
(41, 7, 'hii', '2018-05-11 22:03:58', 0, 0, 0),
(42, 7, 'no no ', '2018-05-11 22:04:18', 0, 0, 0),
(44, 10, '', '2018-05-12 02:05:05', 1, 1, 0),
(45, 1, 'emi w7sha', '2018-05-13 15:52:25', 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `post_likes`
--

DROP TABLE IF EXISTS `post_likes`;
CREATE TABLE IF NOT EXISTS `post_likes` (
  `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `post_ID` int(11) UNSIGNED NOT NULL,
  `user_ID` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `post_likes`
--

INSERT INTO `post_likes` (`ID`, `post_ID`, `user_ID`) VALUES
(20, 9, 4),
(26, 8, 4),
(31, 40, 4),
(27, 4, 4),
(28, 3, 1),
(34, 4, 1),
(30, 5, 1),
(35, 9, 1),
(33, 40, 1),
(37, 44, 10),
(38, 40, 11),
(39, 49, 11);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(32) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `email` text,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `username`, `password`, `email`) VALUES
(1, 'eslam medhat', 'eslam', 'eslam@gmail.com'),
(2, 'ahmed', 'ahmed', 'ahmed@gmail.com'),
(3, 'mohamed', 'mohamed', 'mohamed@gmial.com'),
(4, 'amira', 'amira', 'amira@gmail.com'),
(5, 'eman', 'eman', 'eman@gmail.com'),
(6, 'aya', 'aya', 'aya@gmail.com'),
(7, 'alaa', 'alaa', 'alaa@gmail.com'),
(8, '', '', ''),
(9, '', '', ''),
(10, 'amira', 'amira', 'amira@yahoo.com'),
(11, 'Mohamed', 'habla', 'Fareeda.Fareed@7anon.Commadyntnasr');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `users` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
