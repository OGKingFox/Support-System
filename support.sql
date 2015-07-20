-- phpMyAdmin SQL Dump
-- version 4.2.8.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 20, 2015 at 06:08 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `elverono_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `fox_likes`
--

CREATE TABLE IF NOT EXISTS `fox_likes` (
`id` int(11) NOT NULL,
  `postId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fox_posts`
--

CREATE TABLE IF NOT EXISTS `fox_posts` (
`id` int(11) NOT NULL,
  `category` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `body` longtext NOT NULL,
  `poster` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_post` bigint(20) NOT NULL DEFAULT '0',
  `state` int(11) NOT NULL DEFAULT '1',
  `likes` int(11) NOT NULL DEFAULT '0',
  `dislikes` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fox_replies`
--

CREATE TABLE IF NOT EXISTS `fox_replies` (
`id` int(11) NOT NULL,
  `author` varchar(255) NOT NULL,
  `topic` int(11) NOT NULL,
  `body` longtext NOT NULL,
  `timePosted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fox_users`
--

CREATE TABLE IF NOT EXISTS `fox_users` (
`id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rights` int(11) NOT NULL,
  `registered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `avatar_url` varchar(255) DEFAULT NULL,
  `theme` varchar(255) NOT NULL DEFAULT 'united',
  `banned` tinyint(1) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fox_likes`
--
ALTER TABLE `fox_likes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fox_posts`
--
ALTER TABLE `fox_posts`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fox_replies`
--
ALTER TABLE `fox_replies`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fox_users`
--
ALTER TABLE `fox_users`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fox_likes`
--
ALTER TABLE `fox_likes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=68;
--
-- AUTO_INCREMENT for table `fox_posts`
--
ALTER TABLE `fox_posts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=49;
--
-- AUTO_INCREMENT for table `fox_replies`
--
ALTER TABLE `fox_replies`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=77;
--
-- AUTO_INCREMENT for table `fox_users`
--
ALTER TABLE `fox_users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
