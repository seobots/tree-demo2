-- phpMyAdmin SQL Dump
-- version 3.2.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 07, 2013 at 12:22 AM
-- Server version: 5.1.40
-- PHP Version: 5.2.12

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

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
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `node`
--

INSERT INTO `node` (`id_node`, `name`, `parent`, `inheritance`) VALUES
(1, 'node', 0, '1'),
(2, 'node', 0, '2'),
(3, 'node', 0, '3'),
(4, 'node', 1, '1|1'),
(5, 'node', 1, '1|2'),
(6, 'node', 4, '1|1|1'),
(7, 'node', 4, '1|1|2'),
(8, 'node', 4, '1|1|3'),
(9, 'node', 6, '1|1|1|1'),
(10, 'node', 5, '1|2|1');
