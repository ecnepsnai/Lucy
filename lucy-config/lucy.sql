-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 07, 2013 at 10:57 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `lucy`
--

-- --------------------------------------------------------

--
-- Table structure for table `pwd_reset`
--

CREATE TABLE IF NOT EXISTS `pwd_reset` (
  `email` varchar(45) NOT NULL,
  `salt1` varchar(32) NOT NULL,
  `salt2` varchar(32) NOT NULL,
  `date` datetime NOT NULL,
  `status` enum('Requested','Reset') NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ticketlist`
--

CREATE TABLE IF NOT EXISTS `ticketlist` (
  `id` varchar(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `application` varchar(45) NOT NULL,
  `version` varchar(45) NOT NULL,
  `os` varchar(45) NOT NULL,
  `status` enum('Open','Closed') NOT NULL,
  `subject` varchar(100) NOT NULL,
  `date` datetime NOT NULL,
  `lastreply` enum('Client','Agent') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `userlist`
--

CREATE TABLE IF NOT EXISTS `userlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `password` varchar(32) NOT NULL,
  `salt` tinyint(2) NOT NULL,
  `pwd_reset` tinyint(1) NOT NULL,
  `type` enum('Admin','Client') NOT NULL,
  `date_registered` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `userlist`
--

INSERT INTO `userlist` (`id`, `name`, `email`, `password`, `salt`, `pwd_reset`, `type`, `date_registered`) VALUES
(1, 'admin', 'foo@bar.com', '1fa8e3dbb818021f2908ea7c7552fdc2', 45, 0, 'Client', '2013-02-07');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;