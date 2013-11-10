-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 10, 2013 at 06:30 PM
-- Server version: 5.5.30
-- PHP Version: 5.3.24

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `productName` varchar(50) NOT NULL,
  `price` float NOT NULL,
  `productDescription` varchar(150) DEFAULT NULL,
  `update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `productName`, `price`, `productDescription`, `update_time`) VALUES
(1, 'Shampoo loreal', 20.25, 'veru good shampoo', '2013-11-10 04:24:24'),
(2, 'lasjon', 52.1, 'perfect lasjon', '2013-11-10 04:15:13'),
(3, 'Shampoo', 20.25, 'veru good shampoo', '2013-11-10 04:24:24'),
(4, 'lasjon', 52.1, 'perfect lasjon', '2013-11-10 04:15:13'),
(5, 'Shampoo', 20.25, 'veru good shampoo', '2013-11-10 04:24:24'),
(6, 'lasjon', 52.1, 'perfect lasjon', '2013-11-10 04:15:13'),
(7, 'Shampoo', 20.25, 'veru good shampoo', '2013-11-10 04:24:24'),
(8, 'lasjon', 52.1, 'perfect lasjon', '2013-11-10 04:15:13'),
(9, 'Shampoo', 20.25, 'veru good shampoo', '2013-11-10 04:24:24'),
(10, 'lasjon', 52.1, 'perfect lasjon', '2013-11-10 04:15:13');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
