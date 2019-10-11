-- phpMyAdmin SQL Dump
-- version 4.4.15.9
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2017-07-29 06:16:09
-- 服务器版本： 5.6.35-log
-- PHP Version: 7.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_wechatengine`
--

-- --------------------------------------------------------

--
-- 表的结构 `ims_modules_bindings`
--

CREATE TABLE IF NOT EXISTS `ims_modules_bindings` (
  `eid` int(11) NOT NULL,
  `module` varchar(30) NOT NULL,
  `entry` varchar(10) NOT NULL,
  `call` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `do` varchar(30) NOT NULL,
  `state` varchar(200) NOT NULL,
  `direct` int(11) NOT NULL,
  `url` varchar(100) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `displayorder` tinyint(255) unsigned NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=365 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_modules_bindings`
--

INSERT INTO `ims_modules_bindings` (`eid`, `module`, `entry`, `call`, `title`, `do`, `state`, `direct`, `url`, `icon`, `displayorder`) VALUES
(184, 'ewei_shopping', 'profile', '', '我的订单', 'myorder', '', 0, '', '', 0),
(179, 'ewei_shopping', 'menu', '', '物流管理', 'express', '', 0, '', '', 0),
(180, 'ewei_shopping', 'menu', '', '配送方式', 'dispatch', '', 0, '', '', 0),
(181, 'ewei_shopping', 'menu', '', '幻灯片管理', 'adv', '', 0, '', '', 0),
(182, 'ewei_shopping', 'menu', '', '维权与告警', 'notice', '', 0, '', '', 0),
(178, 'ewei_shopping', 'menu', '', '商品分类', 'category', '', 0, '', '', 0),
(177, 'ewei_shopping', 'menu', '', '商品管理', 'goods', '', 0, '', '', 0),
(176, 'ewei_shopping', 'menu', '', '订单管理', 'order', '', 0, '', '', 0),
(183, 'ewei_shopping', 'home', '', '商城', 'list', '', 0, '', '', 0),
(175, 'ewei_shopping', 'cover', '', '商城入口设置', 'list', '', 0, '', '', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ims_modules_bindings`
--
ALTER TABLE `ims_modules_bindings`
  ADD PRIMARY KEY (`eid`),
  ADD KEY `idx_module` (`module`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ims_modules_bindings`
--
ALTER TABLE `ims_modules_bindings`
  MODIFY `eid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=365;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
