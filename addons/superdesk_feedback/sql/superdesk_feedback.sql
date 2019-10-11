-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2017-12-08 12:09:12
-- 服务器版本： 5.7.19-log
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
-- 表的结构 `ims_superdesk_feedback_feedback`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_feedback_feedback` (
  `id` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned DEFAULT '0',
  `from_user` varchar(100) DEFAULT '',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '0为第一级',
  `nickname` varchar(100) DEFAULT '',
  `username` varchar(100) DEFAULT '',
  `headimgurl` varchar(500) DEFAULT '',
  `content` varchar(200) DEFAULT '' COMMENT '回复内容',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `dateline` int(10) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_feedback_setting`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_feedback_setting` (
  `id` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned DEFAULT '0',
  `title` varchar(100) DEFAULT '' COMMENT '网站名称',
  `pagesize` int(10) unsigned DEFAULT '10' COMMENT '每页显示数量 默认为10',
  `topimgurl` varchar(500) DEFAULT '' COMMENT '顶部图片',
  `pagecolor` varchar(50) DEFAULT '' COMMENT '页面色调',
  `ischeck` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否需要审核',
  `dateline` int(10) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ims_superdesk_feedback_feedback`
--
ALTER TABLE `ims_superdesk_feedback_feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_feedback_setting`
--
ALTER TABLE `ims_superdesk_feedback_setting`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ims_superdesk_feedback_feedback`
--
ALTER TABLE `ims_superdesk_feedback_feedback`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_feedback_setting`
--
ALTER TABLE `ims_superdesk_feedback_setting`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
