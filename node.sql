-- phpMyAdmin SQL Dump
-- version 3.2.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 05, 2013 at 02:38 PM
-- Server version: 5.1.40
-- PHP Version: 5.2.12

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test2`
--

-- --------------------------------------------------------

--
-- Table structure for table `node`
--

CREATE TABLE IF NOT EXISTS `node` (
  `id_node` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  `inheritance` varchar(255) NOT NULL,
  PRIMARY KEY (`id_node`),
  UNIQUE KEY `inheritance` (`inheritance`),
  KEY `parent` (`parent`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `node`
--

INSERT INTO `node` (`id_node`, `name`, `parent`, `inheritance`) VALUES
(1, 'node', 0, '1'),
(3, 'node', 1, '1|1'),
(4, 'node', 1, '1|2'),
(5, 'node', 3, '1|1|1'),
(6, 'node', 5, '1|1|1|1'),
(8, 'oloolo', 6, '1|1|1|1|1'),
(9, 'test', 1, '1|3');
