# 已更新到 福利内购
# 已更新到 企业采购

CREATE TABLE `ims_superdesk_shop_member_credit_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(50) DEFAULT NULL,
  `add_price` decimal(10,2) DEFAULT '0.00' COMMENT '交易金额',
  `reduce_price` decimal(10,2) DEFAULT '0.00' COMMENT '减少金额',
  `old_price` decimal(10,2) DEFAULT '0.00' COMMENT '旧金额',
  `new_price` decimal(10,2) DEFAULT '0.00' COMMENT '新金额',
  `type` tinyint(3) DEFAULT '0' COMMENT '类型(1是员工导入,2后端充值,3订单,4退款)',
  `finish_time` int(11) DEFAULT '0' COMMENT '交易完成时间',
  `createtime` int(11) DEFAULT '0' COMMENT '该记录创建时间',
  `orderid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



# 已更新到 福利内购
# 已更新到 企业采购
-- 修改成INNODB
ALTER TABLE `ims_superdesk_shop_member`
ENGINE=InnoDB;




-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2019-02-01 16:46:05
-- 服务器版本： 5.7.24-log
-- PHP 版本： 7.1.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `superdesk_module_shopv2_enterprise`
--

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_shop_member_credit_log`
--

CREATE TABLE `ims_superdesk_shop_member_credit_log` (
  `id` int(11) NOT NULL,
  `openid` varchar(50) DEFAULT NULL,
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `add_price` decimal(10,2) DEFAULT '0.00' COMMENT '交易金额',
  `reduce_price` decimal(10,2) DEFAULT '0.00' COMMENT '减少金额',
  `old_price` decimal(10,2) DEFAULT '0.00' COMMENT '旧金额',
  `new_price` decimal(10,2) DEFAULT '0.00' COMMENT '新金额',
  `type` tinyint(3) DEFAULT '0' COMMENT '类型(1是员工导入,2总端充值,3订单,4退款)',
  `finish_time` int(11) DEFAULT '0' COMMENT '交易完成时间',
  `createtime` int(11) DEFAULT '0' COMMENT '该记录创建时间',
  `orderid` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转储表的索引
--

--
-- 表的索引 `ims_superdesk_shop_member_credit_log`
--
ALTER TABLE `ims_superdesk_shop_member_credit_log`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `ims_superdesk_shop_member_credit_log`
--
ALTER TABLE `ims_superdesk_shop_member_credit_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
