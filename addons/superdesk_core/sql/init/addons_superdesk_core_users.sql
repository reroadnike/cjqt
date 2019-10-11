-- phpMyAdmin SQL Dump
-- version 4.4.15.8
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2017-08-24 15:17:15
-- 服务器版本： 5.6.33-log
-- PHP Version: 7.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_smart_office_building_20161220`
--

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_core_users`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_core_users` (
  `id` int(11) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `organization_code` varchar(16) NOT NULL,
  `virtual_code` varchar(16) NOT NULL,
  `menus` varchar(500) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT NULL,
  `commission` float DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='管理员表';

--
-- 转存表中的数据 `ims_superdesk_core_users`
--



--
-- Indexes for dumped tables
--

--
-- Indexes for table `ims_superdesk_core_users`
--
ALTER TABLE `ims_superdesk_core_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ims_superdesk_core_users`
--
ALTER TABLE `ims_superdesk_core_users`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
