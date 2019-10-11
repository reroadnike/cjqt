-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2018-05-15 11:25:07
-- 服务器版本： 5.7.20-log
-- PHP Version: 7.2.0

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
-- 表的结构 `ims_superdesk_shop_order_goods`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_shop_order_goods` (
  `id` int(11) NOT NULL,
  `uniacid` int(11) DEFAULT '0',
  `parent_order_id` int(11) NOT NULL DEFAULT '0' COMMENT 'parent_order_id',
  `orderid` int(11) DEFAULT '0',
  `goodsid` int(11) DEFAULT '0',
  `price` decimal(10,2) DEFAULT '0.00',
  `total` int(11) DEFAULT '1',
  `optionid` int(10) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `optionname` text,
  `commission1` text,
  `applytime1` int(11) DEFAULT '0',
  `checktime1` int(10) DEFAULT '0',
  `paytime1` int(11) DEFAULT '0',
  `invalidtime1` int(11) DEFAULT '0',
  `deletetime1` int(11) DEFAULT '0',
  `status1` tinyint(3) DEFAULT '0',
  `content1` text,
  `commission2` text,
  `applytime2` int(11) DEFAULT '0',
  `checktime2` int(10) DEFAULT '0',
  `paytime2` int(11) DEFAULT '0',
  `invalidtime2` int(11) DEFAULT '0',
  `deletetime2` int(11) DEFAULT '0',
  `status2` tinyint(3) DEFAULT '0',
  `content2` text,
  `commission3` text,
  `applytime3` int(11) DEFAULT '0',
  `checktime3` int(10) DEFAULT '0',
  `paytime3` int(11) DEFAULT '0',
  `invalidtime3` int(11) DEFAULT '0',
  `deletetime3` int(11) DEFAULT '0',
  `status3` tinyint(3) DEFAULT '0',
  `content3` text,
  `realprice` decimal(10,2) DEFAULT '0.00',
  `goodssn` varchar(255) DEFAULT '',
  `productsn` varchar(255) DEFAULT '',
  `nocommission` tinyint(3) DEFAULT '0',
  `changeprice` decimal(10,2) DEFAULT '0.00',
  `oldprice` decimal(10,2) DEFAULT '0.00',
  `commissions` text,
  `diyformdata` text,
  `diyformfields` text,
  `diyformdataid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `diyformid` int(11) DEFAULT '0',
  `rstate` tinyint(3) DEFAULT '0',
  `refundtime` int(11) DEFAULT '0',
  `printstate` int(11) NOT NULL DEFAULT '0',
  `printstate2` int(11) NOT NULL DEFAULT '0',
  `merchid` int(11) DEFAULT '0',
  `parentorderid` int(11) DEFAULT '0',
  `merchsale` tinyint(3) NOT NULL DEFAULT '0',
  `isdiscountprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `canbuyagain` tinyint(1) DEFAULT '0',
  `return_goods_nun` int(11) NOT NULL DEFAULT '0' COMMENT '京东退货数量',
  `return_goods_result` text NOT NULL COMMENT '京东退货信息'
) ENGINE=InnoDB AUTO_INCREMENT=10585 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_shop_order_goods`
--

INSERT INTO `ims_superdesk_shop_order_goods` (`id`, `uniacid`, `parent_order_id`, `orderid`, `goodsid`, `price`, `total`, `optionid`, `createtime`, `optionname`, `commission1`, `applytime1`, `checktime1`, `paytime1`, `invalidtime1`, `deletetime1`, `status1`, `content1`, `commission2`, `applytime2`, `checktime2`, `paytime2`, `invalidtime2`, `deletetime2`, `status2`, `content2`, `commission3`, `applytime3`, `checktime3`, `paytime3`, `invalidtime3`, `deletetime3`, `status3`, `content3`, `realprice`, `goodssn`, `productsn`, `nocommission`, `changeprice`, `oldprice`, `commissions`, `diyformdata`, `diyformfields`, `diyformdataid`, `openid`, `diyformid`, `rstate`, `refundtime`, `printstate`, `printstate2`, `merchid`, `parentorderid`, `merchsale`, `isdiscountprice`, `canbuyagain`, `return_goods_nun`, `return_goods_result`) VALUES
(1252, 16, 0, 853, 106870, '34.55', 1, 0, 1522204510, '', 'a:2:{s:7:"default";s:1:"0";s:6:"level1";s:1:"0";}', 0, 0, 0, 0, 0, 0, NULL, 'a:2:{s:7:"default";s:1:"0";s:6:"level1";s:1:"0";}', 0, 0, 0, 0, 0, 0, NULL, 'a:2:{s:7:"default";i:0;s:6:"level1";i:0;}', 0, 0, 0, 0, 0, 0, NULL, '34.55', '4962422', '6921262102445', 0, '0.00', '34.55', 'a:3:{s:6:"level1";i:0;s:6:"level2";i:0;s:6:"level3";i:0;}', 'false', 'a:0:{}', 0, 'oX8KYwq7-bcBDOPIaQov-FXkzQj4', 0, 0, 0, 0, 0, 8, 0, 0, '0.00', 0, 0, ''),
(1260, 16, 0, 853, 398375, '20.90', 1, 0, 1522204510, '', 'a:2:{s:7:"default";s:1:"0";s:6:"level1";s:1:"0";}', 0, 0, 0, 0, 0, 0, NULL, 'a:2:{s:7:"default";s:1:"0";s:6:"level1";s:1:"0";}', 0, 0, 0, 0, 0, 0, NULL, 'a:2:{s:7:"default";i:0;s:6:"level1";i:0;}', 0, 0, 0, 0, 0, 0, NULL, '20.90', '5785989', '6921262102070', 0, '0.00', '20.90', 'a:3:{s:6:"level1";i:0;s:6:"level2";i:0;s:6:"level3";i:0;}', 'false', 'a:0:{}', 0, 'oX8KYwq7-bcBDOPIaQov-FXkzQj4', 0, 0, 0, 0, 0, 8, 0, 0, '0.00', 0, 0, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ims_superdesk_shop_order_goods`
--
ALTER TABLE `ims_superdesk_shop_order_goods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_uniacid` (`uniacid`) USING BTREE,
  ADD KEY `idx_orderid` (`orderid`) USING BTREE,
  ADD KEY `idx_goodsid` (`goodsid`) USING BTREE,
  ADD KEY `idx_createtime` (`createtime`) USING BTREE,
  ADD KEY `idx_applytime1` (`applytime1`) USING BTREE,
  ADD KEY `idx_checktime1` (`checktime1`) USING BTREE,
  ADD KEY `idx_status1` (`status1`) USING BTREE,
  ADD KEY `idx_applytime2` (`applytime2`) USING BTREE,
  ADD KEY `idx_checktime2` (`checktime2`) USING BTREE,
  ADD KEY `idx_status2` (`status2`) USING BTREE,
  ADD KEY `idx_applytime3` (`applytime3`) USING BTREE,
  ADD KEY `idx_invalidtime1` (`invalidtime1`) USING BTREE,
  ADD KEY `idx_checktime3` (`checktime3`) USING BTREE,
  ADD KEY `idx_invalidtime2` (`invalidtime2`) USING BTREE,
  ADD KEY `idx_invalidtime3` (`invalidtime3`) USING BTREE,
  ADD KEY `idx_status3` (`status3`) USING BTREE,
  ADD KEY `idx_paytime1` (`paytime1`) USING BTREE,
  ADD KEY `idx_paytime2` (`paytime2`) USING BTREE,
  ADD KEY `idx_paytime3` (`paytime3`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ims_superdesk_shop_order_goods`
--
ALTER TABLE `ims_superdesk_shop_order_goods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10585;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
