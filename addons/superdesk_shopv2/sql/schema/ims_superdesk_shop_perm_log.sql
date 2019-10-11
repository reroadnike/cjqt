-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2018-12-06 11:45:24
-- 服务器版本： 5.6.33-log
-- PHP Version: 7.1.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_super_desk`
--

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_shop_perm_log`
--

CREATE TABLE `ims_superdesk_shop_perm_log` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `name` varchar(255) DEFAULT '',
  `type` varchar(255) DEFAULT '',
  `op` text,
  `createtime` int(11) DEFAULT '0',
  `ip` varchar(255) DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ims_superdesk_shop_perm_log`
--
ALTER TABLE `ims_superdesk_shop_perm_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_uid` (`uid`) USING BTREE,
  ADD KEY `idx_createtime` (`createtime`) USING BTREE,
  ADD KEY `idx_uniacid` (`uniacid`) USING BTREE;
ALTER TABLE `ims_superdesk_shop_perm_log` ADD FULLTEXT KEY `idx_type` (`type`);
ALTER TABLE `ims_superdesk_shop_perm_log` ADD FULLTEXT KEY `idx_op` (`op`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `ims_superdesk_shop_perm_log`
--
ALTER TABLE `ims_superdesk_shop_perm_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
