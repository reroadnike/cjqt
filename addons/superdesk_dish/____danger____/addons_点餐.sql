-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2018-08-06 15:47:14
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
-- 表的结构 `ims_superdesk_dish_address`
--

CREATE TABLE `ims_superdesk_dish_address` (
  `id` int(10) UNSIGNED NOT NULL,
  `weid` int(10) UNSIGNED NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `realname` varchar(20) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `address` varchar(300) NOT NULL,
  `dateline` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_dish_address`
--

INSERT INTO `ims_superdesk_dish_address` (`id`, `weid`, `from_user`, `realname`, `mobile`, `address`, `dateline`) VALUES
(403, 16, 'oX8KYwkxwNW6qzHF4cF-tGxYTcPg', '安先生', '13422832499', '中国', 0),
(404, 16, 'oX8KYwvbdoY-Ngq-nMqYBV1gODws', '秦凯', '13826431563', '航空大厦32楼', 0);

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_dish_area`
--

CREATE TABLE `ims_superdesk_dish_area` (
  `id` int(10) UNSIGNED NOT NULL,
  `weid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '区域名称',
  `parentid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `displayorder` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序',
  `dateline` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_dish_area`
--

INSERT INTO `ims_superdesk_dish_area` (`id`, `weid`, `name`, `parentid`, `displayorder`, `dateline`, `status`) VALUES
(3, 16, '深圳-福田', 0, 0, 0, 1),
(4, 16, '深圳-罗湖', 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_dish_blacklist`
--

CREATE TABLE `ims_superdesk_dish_blacklist` (
  `id` int(10) NOT NULL,
  `weid` int(10) UNSIGNED NOT NULL,
  `from_user` varchar(100) DEFAULT '' COMMENT '用户ID',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `dateline` int(10) DEFAULT '0' COMMENT '创建时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_dish_cart`
--

CREATE TABLE `ims_superdesk_dish_cart` (
  `id` int(10) UNSIGNED NOT NULL,
  `weid` int(10) UNSIGNED NOT NULL,
  `storeid` int(10) UNSIGNED NOT NULL,
  `goodsid` int(11) NOT NULL,
  `goodstype` tinyint(1) NOT NULL DEFAULT '1',
  `price` varchar(10) NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `total` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_dish_cart`
--

INSERT INTO `ims_superdesk_dish_cart` (`id`, `weid`, `storeid`, `goodsid`, `goodstype`, `price`, `from_user`, `total`) VALUES
(57, 16, 5, 42, 17, '6', 'oX8KYwutluzuhcJhorvPV6WPcV00', 2),
(56, 16, 5, 44, 18, '3', 'oX8KYwnjPlgsMwc04fYOnUxrlE9s', 1),
(66, 16, 5, 44, 18, '3', 'oX8KYwnly4UHbqCxSuGW8iRGr7Cs', 1);

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_dish_category`
--

CREATE TABLE `ims_superdesk_dish_category` (
  `id` int(10) UNSIGNED NOT NULL,
  `storeid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '门店id',
  `weid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `parentid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `displayorder` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '是否开启'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_dish_category`
--

INSERT INTO `ims_superdesk_dish_category` (`id`, `storeid`, `weid`, `name`, `parentid`, `displayorder`, `enabled`) VALUES
(17, 5, 16, '荤菜', 0, 0, 1),
(18, 5, 16, '素菜', 0, 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_dish_collection`
--

CREATE TABLE `ims_superdesk_dish_collection` (
  `id` int(10) UNSIGNED NOT NULL,
  `weid` int(10) UNSIGNED NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `storeid` int(10) UNSIGNED NOT NULL,
  `dateline` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_dish_collection`
--

INSERT INTO `ims_superdesk_dish_collection` (`id`, `weid`, `from_user`, `storeid`, `dateline`) VALUES
(39, 16, 'oX8KYwkxwNW6qzHF4cF-tGxYTcPg', 5, 1505115894),
(42, 16, 'oX8KYwutluzuhcJhorvPV6WPcV00', 5, 1505128708);

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_dish_email_setting`
--

CREATE TABLE `ims_superdesk_dish_email_setting` (
  `id` int(10) NOT NULL,
  `weid` int(10) UNSIGNED NOT NULL,
  `storeid` int(10) UNSIGNED NOT NULL,
  `email_enable` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '开启邮箱提醒',
  `email_host` varchar(50) DEFAULT '' COMMENT '邮箱服务器',
  `email_send` varchar(100) DEFAULT NULL,
  `email_pwd` varchar(20) DEFAULT '' COMMENT '邮箱密码',
  `email_user` varchar(100) DEFAULT '' COMMENT '发信人名称',
  `email` varchar(100) DEFAULT NULL,
  `email_business_tpl` varchar(200) DEFAULT '' COMMENT '商户接收内容模板',
  `dateline` int(10) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_dish_goods`
--

CREATE TABLE `ims_superdesk_dish_goods` (
  `id` int(10) UNSIGNED NOT NULL,
  `storeid` int(10) UNSIGNED NOT NULL,
  `weid` int(10) UNSIGNED NOT NULL,
  `pcate` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `ccate` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `recommend` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `displayorder` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `thumb` varchar(100) NOT NULL DEFAULT '',
  `unitname` varchar(5) NOT NULL DEFAULT '份',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `taste` varchar(1000) NOT NULL DEFAULT '' COMMENT '口味',
  `isspecial` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `marketprice` varchar(10) NOT NULL DEFAULT '',
  `productprice` varchar(10) NOT NULL DEFAULT '',
  `credit` int(10) NOT NULL DEFAULT '0',
  `subcount` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '被点次数',
  `dateline` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_dish_goods`
--

INSERT INTO `ims_superdesk_dish_goods` (`id`, `storeid`, `weid`, `pcate`, `ccate`, `status`, `recommend`, `displayorder`, `title`, `thumb`, `unitname`, `description`, `taste`, `isspecial`, `marketprice`, `productprice`, `credit`, `subcount`, `dateline`) VALUES
(39, 5, 16, 17, 0, 1, 0, 0, '白切鸡', '', '份', '白切鸡', '白切鸡', 2, '6', '8', 1, 2, 1505113340),
(40, 5, 16, 17, 0, 1, 0, 0, '胡萝卜丝炒肉', '', '份', '胡萝卜丝炒肉', '胡萝卜丝炒肉', 2, '6', '8', 0, 1, 1505113364),
(41, 5, 16, 17, 0, 1, 0, 0, '腐竹炒肉', '', '份', '腐竹炒肉', '腐竹炒肉', 2, '0.01', '8', 0, 6, 1505113387),
(42, 5, 16, 17, 0, 1, 0, 0, '红烧鱼块', '', '份', '红烧鱼块', '红烧鱼块', 2, '0.01', '8', 0, 9, 1505113405),
(43, 5, 16, 18, 0, 1, 0, 0, '炒白菜', '', '份', '炒白菜', '炒白菜', 2, '0.01', '5', 0, 4, 1505113425),
(44, 5, 16, 18, 0, 1, 0, 0, '芹菜炒香干', '', '份', '芹菜炒香干', '芹菜炒香干', 2, '0.01', '5', 0, 13, 1505113444);

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_dish_intelligent`
--

CREATE TABLE `ims_superdesk_dish_intelligent` (
  `id` int(10) UNSIGNED NOT NULL,
  `storeid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '门店id',
  `weid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` int(10) NOT NULL DEFAULT '0' COMMENT '适用人数',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '菜品',
  `displayorder` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '是否开启'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_dish_mealtime`
--

CREATE TABLE `ims_superdesk_dish_mealtime` (
  `id` int(10) NOT NULL,
  `weid` int(10) UNSIGNED NOT NULL,
  `storeid` int(10) UNSIGNED NOT NULL,
  `begintime` varchar(20) DEFAULT '09:00' COMMENT '开始时间',
  `endtime` varchar(20) DEFAULT '18:00' COMMENT '结束时间',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否开启',
  `dateline` int(10) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_dish_mealtime`
--

INSERT INTO `ims_superdesk_dish_mealtime` (`id`, `weid`, `storeid`, `begintime`, `endtime`, `status`, `dateline`) VALUES
(1, 16, 0, '08:30', '09:00', 0, 1505115193),
(2, 16, 0, '09:00', '09:30', 0, 1505115193),
(3, 16, 0, '09:30', '10:00', 0, 1505115193),
(4, 16, 0, '10:00', '10:30', 0, 1505115193),
(5, 16, 0, '10:30', '11:00', 0, 1505115193),
(6, 16, 0, '11:00', '11:30', 0, 1505115193),
(7, 16, 0, '11:30', '12:00', 0, 1505115193),
(8, 16, 0, '12:00', '12:30', 0, 1505115193),
(9, 16, 0, '12:30', '13:00', 0, 1505115193),
(10, 16, 0, '13:00', '13:30', 0, 1505115193),
(11, 16, 0, '13:30', '14:00', 0, 1505115193),
(12, 16, 0, '14:00', '14:30', 0, 1505115193),
(13, 16, 0, '14:30', '15:00', 0, 1505115193),
(14, 16, 0, '15:00', '15:30', 0, 1505115193),
(15, 16, 0, '15:30', '16:00', 0, 1505115193),
(16, 16, 0, '16:00', '16:30', 0, 1505115193),
(17, 16, 0, '16:30', '17:00', 0, 1505115193),
(18, 16, 0, '17:00', '17:30', 0, 1505115193),
(19, 16, 0, '17:30', '18:00', 0, 1505115193),
(20, 16, 0, '18:00', '18:30', 0, 1505115193),
(21, 16, 0, '18:30', '19:00', 0, 1505115193),
(22, 16, 0, '19:00', '19:30', 0, 1505115193),
(23, 16, 0, '19:30', '20:00', 0, 1505115193),
(24, 16, 0, '20:00', '20:30', 0, 1505115193),
(25, 16, 0, '20:30', '21:00', 0, 1505115193);

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_dish_nave`
--

CREATE TABLE `ims_superdesk_dish_nave` (
  `id` int(10) UNSIGNED NOT NULL,
  `weid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `type` int(10) NOT NULL DEFAULT '-1' COMMENT '链接类型 -1:自定义 1:首页2:门店3:菜单列表4:我的菜单',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '导航名称',
  `link` varchar(200) NOT NULL DEFAULT '' COMMENT '导航链接',
  `displayorder` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '是否开启'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_dish_order`
--

CREATE TABLE `ims_superdesk_dish_order` (
  `id` int(10) UNSIGNED NOT NULL,
  `weid` int(10) UNSIGNED NOT NULL COMMENT '公众号id',
  `storeid` int(10) UNSIGNED NOT NULL COMMENT '门店id',
  `from_user` varchar(50) NOT NULL,
  `ordersn` varchar(30) NOT NULL COMMENT '订单号',
  `totalnum` tinyint(4) DEFAULT NULL COMMENT '总数量',
  `totalprice` varchar(10) NOT NULL COMMENT '总金额',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为确认付款方式，2为成功',
  `paytype` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '1余额，2在线，3到付',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `address` varchar(250) NOT NULL DEFAULT '' COMMENT '地址',
  `tel` varchar(50) NOT NULL DEFAULT '' COMMENT '联系电话',
  `reply` varchar(1000) NOT NULL DEFAULT '' COMMENT '回复',
  `meal_time` varchar(50) NOT NULL DEFAULT '' COMMENT '就餐时间',
  `counts` tinyint(4) DEFAULT '0' COMMENT '预订人数',
  `seat_type` tinyint(1) DEFAULT '0' COMMENT '位置类型1大厅2包间',
  `carports` tinyint(3) DEFAULT '0' COMMENT '车位',
  `dining_mode` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '用餐类型 1:到店 2:外卖',
  `remark` varchar(1000) NOT NULL DEFAULT '' COMMENT '备注',
  `tables` varchar(10) NOT NULL DEFAULT '' COMMENT '桌号',
  `print_sta` tinyint(1) DEFAULT '-1' COMMENT '打印状态',
  `sign` tinyint(1) NOT NULL DEFAULT '0' COMMENT '-1拒绝，0未处理，1已处理',
  `isfinish` tinyint(1) NOT NULL DEFAULT '0',
  `dateline` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号',
  `goodsprice` decimal(10,2) DEFAULT '0.00',
  `dispatchprice` decimal(10,2) DEFAULT '0.00',
  `isemail` tinyint(1) NOT NULL DEFAULT '0',
  `issms` tinyint(1) NOT NULL DEFAULT '0',
  `istpl` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_dish_order`
--

INSERT INTO `ims_superdesk_dish_order` (`id`, `weid`, `storeid`, `from_user`, `ordersn`, `totalnum`, `totalprice`, `status`, `paytype`, `username`, `address`, `tel`, `reply`, `meal_time`, `counts`, `seat_type`, `carports`, `dining_mode`, `remark`, `tables`, `print_sta`, `sign`, `isfinish`, `dateline`, `transid`, `goodsprice`, `dispatchprice`, `isemail`, `issms`, `istpl`) VALUES
(25, 16, 5, 'oX8KYwvbdoY-Ngq-nMqYBV1gODws', '091200008214', 8, '36', 0, 0, '秦凯', '航空大厦32楼', '13826431563', '', '08:30~09:00', 1, 0, 0, 2, '', '0', -1, 0, 0, 1505215597, '0', '36.00', '0.00', 0, 0, 0),
(26, 16, 5, 'oX8KYwvbdoY-Ngq-nMqYBV1gODws', '092800001422', 6, '18', 1, 2, '秦凯', '航空大厦32楼', '13826431563', '', '08:30~09:00', 1, 0, 0, 2, '', '0', -1, 0, 0, 1506595094, '5851', '18.00', '0.00', 0, 0, 0),
(24, 16, 5, 'oX8KYwvbdoY-Ngq-nMqYBV1gODws', '091100009643', 4, '18', 0, 0, '秦凯', '航空大厦32楼', '13826431563', '', '08:30~09:00', 1, 0, 0, 2, '好茶', '0', -1, 0, 0, 1505125559, '0', '18.00', '0.00', 0, 0, 0),
(28, 16, 5, 'oX8KYwkxwNW6qzHF4cF-tGxYTcPg', '102300008302', 4, '0.04', 1, 2, '安先生', '', '13422832499', '', '08:30~09:00', 1, 1, 0, 1, '这！', '2', -1, 0, 0, 1508728752, '5874', '0.04', '0.00', 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_dish_order_goods`
--

CREATE TABLE `ims_superdesk_dish_order_goods` (
  `id` int(10) UNSIGNED NOT NULL,
  `weid` int(10) UNSIGNED NOT NULL,
  `storeid` int(10) UNSIGNED NOT NULL,
  `orderid` int(10) UNSIGNED NOT NULL,
  `goodsid` int(10) UNSIGNED NOT NULL,
  `price` varchar(10) NOT NULL,
  `total` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `dateline` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_dish_order_goods`
--

INSERT INTO `ims_superdesk_dish_order_goods` (`id`, `weid`, `storeid`, `orderid`, `goodsid`, `price`, `total`, `dateline`) VALUES
(46, 16, 5, 24, 41, '6', 1, 1505125559),
(45, 16, 5, 24, 42, '6', 1, 1505125559),
(44, 16, 5, 24, 44, '3', 2, 1505125559),
(48, 16, 5, 25, 43, '3', 2, 1505215597),
(47, 16, 5, 25, 44, '3', 2, 1505215597),
(50, 16, 5, 25, 41, '6', 1, 1505215597),
(49, 16, 5, 25, 42, '6', 1, 1505215597),
(51, 16, 5, 25, 40, '6', 1, 1505215597),
(52, 16, 5, 25, 39, '6', 1, 1505215597),
(53, 16, 5, 26, 43, '3', 5, 1506595082),
(54, 16, 5, 26, 44, '3', 1, 1506595082),
(58, 16, 5, 28, 42, '0.01', 1, 1508728734),
(59, 16, 5, 28, 44, '0.01', 1, 1508728734),
(60, 16, 5, 28, 43, '0.01', 1, 1508728734),
(61, 16, 5, 28, 41, '0.01', 1, 1508728734);

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_dish_print_order`
--

CREATE TABLE `ims_superdesk_dish_print_order` (
  `id` int(10) NOT NULL,
  `weid` int(10) UNSIGNED NOT NULL,
  `orderid` int(10) UNSIGNED NOT NULL,
  `print_usr` varchar(50) DEFAULT '',
  `print_status` tinyint(1) DEFAULT '-1',
  `dateline` int(10) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_dish_print_setting`
--

CREATE TABLE `ims_superdesk_dish_print_setting` (
  `id` int(10) NOT NULL,
  `weid` int(10) UNSIGNED NOT NULL,
  `storeid` int(10) UNSIGNED NOT NULL,
  `title` varchar(200) DEFAULT '',
  `print_status` tinyint(1) NOT NULL,
  `print_type` tinyint(1) NOT NULL,
  `print_usr` varchar(50) DEFAULT '',
  `print_nums` tinyint(3) DEFAULT '1',
  `print_top` varchar(40) DEFAULT '',
  `print_bottom` varchar(40) DEFAULT '',
  `dateline` int(10) DEFAULT '0',
  `qrcode_status` tinyint(1) NOT NULL DEFAULT '0',
  `qrcode_url` varchar(200) DEFAULT '',
  `type` varchar(50) DEFAULT 'hongxin',
  `member_code` varchar(100) DEFAULT '' COMMENT '商户代码',
  `feyin_key` varchar(100) DEFAULT '' COMMENT 'api密钥'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_dish_reply`
--

CREATE TABLE `ims_superdesk_dish_reply` (
  `id` int(10) UNSIGNED NOT NULL,
  `rid` int(10) UNSIGNED NOT NULL,
  `weid` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `type` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '入口类型',
  `storeid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '入口门店',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `picture` varchar(255) NOT NULL DEFAULT '',
  `dateline` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '添加日期'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_dish_setting`
--

CREATE TABLE `ims_superdesk_dish_setting` (
  `id` int(10) NOT NULL,
  `weid` int(10) UNSIGNED NOT NULL,
  `title` varchar(50) DEFAULT '' COMMENT '网站名称',
  `thumb` varchar(200) DEFAULT '' COMMENT '背景图',
  `storeid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `entrance_type` tinyint(1) UNSIGNED NOT NULL COMMENT '入口类型1:首页2门店列表3菜品列表4我的菜单',
  `entrance_storeid` tinyint(1) UNSIGNED NOT NULL COMMENT '入口门店id',
  `order_enable` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '订餐开启',
  `dining_mode` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '用餐类型 1:到店 2:外卖',
  `dateline` int(10) DEFAULT '0',
  `istplnotice` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否模版通知',
  `tplneworder` varchar(200) DEFAULT '' COMMENT '模板id',
  `tpluser` text COMMENT '通知用户',
  `searchword` varchar(1000) DEFAULT '' COMMENT '搜索关键字'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_dish_setting`
--

INSERT INTO `ims_superdesk_dish_setting` (`id`, `weid`, `title`, `thumb`, `storeid`, `entrance_type`, `entrance_storeid`, `order_enable`, `dining_mode`, `dateline`, `istplnotice`, `tplneworder`, `tpluser`, `searchword`) VALUES
(2, 16, '超级团餐', '../addons/superdesk_dish/template/images/bg.jpg', 5, 0, 0, 0, 0, 1505115193, 0, '', '', '超级 团餐');

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_dish_sms_checkcode`
--

CREATE TABLE `ims_superdesk_dish_sms_checkcode` (
  `id` int(10) NOT NULL,
  `weid` int(10) UNSIGNED NOT NULL,
  `from_user` varchar(100) DEFAULT '' COMMENT '用户ID',
  `mobile` varchar(30) DEFAULT '' COMMENT '手机',
  `checkcode` varchar(100) DEFAULT '' COMMENT '验证码',
  `status` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '状态 0未使用1已使用',
  `dateline` int(10) DEFAULT '0' COMMENT '创建时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_dish_sms_setting`
--

CREATE TABLE `ims_superdesk_dish_sms_setting` (
  `id` int(10) NOT NULL,
  `weid` int(10) UNSIGNED NOT NULL,
  `storeid` int(10) UNSIGNED NOT NULL,
  `sms_enable` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '开启短信提醒',
  `sms_verify_enable` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '开启短信验证提醒',
  `sms_username` varchar(20) DEFAULT '' COMMENT '平台帐号',
  `sms_pwd` varchar(20) DEFAULT '' COMMENT '平台密码',
  `sms_mobile` varchar(20) DEFAULT '' COMMENT '商户接收短信手机',
  `sms_verify_tpl` varchar(120) DEFAULT '' COMMENT '验证短信模板',
  `sms_business_tpl` varchar(120) DEFAULT '' COMMENT '商户短信模板',
  `sms_user_tpl` varchar(120) DEFAULT '' COMMENT '用户短信模板',
  `dateline` int(10) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_dish_stores`
--

CREATE TABLE `ims_superdesk_dish_stores` (
  `id` int(10) NOT NULL,
  `weid` int(10) NOT NULL DEFAULT '0' COMMENT '公众号id',
  `areaid` int(10) NOT NULL DEFAULT '0' COMMENT '区域id',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `logo` varchar(200) NOT NULL DEFAULT '' COMMENT '商家logo',
  `info` varchar(1000) NOT NULL DEFAULT '' COMMENT '简短描述',
  `content` text NOT NULL COMMENT '简介',
  `tel` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `location_p` varchar(100) NOT NULL DEFAULT '' COMMENT '省',
  `location_c` varchar(100) NOT NULL DEFAULT '' COMMENT '市',
  `location_a` varchar(100) NOT NULL DEFAULT '' COMMENT '区',
  `address` varchar(200) NOT NULL COMMENT '地址',
  `place` varchar(200) NOT NULL DEFAULT '',
  `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度',
  `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度',
  `password` varchar(20) NOT NULL DEFAULT '' COMMENT '登录密码',
  `hours` varchar(200) NOT NULL DEFAULT '' COMMENT '营业时间',
  `recharging_password` varchar(20) NOT NULL DEFAULT '' COMMENT '充值密码',
  `thumb_url` varchar(1000) DEFAULT NULL,
  `enable_wifi` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否有wifi',
  `enable_card` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否能刷卡',
  `enable_room` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否有包厢',
  `enable_park` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否有停车',
  `is_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否在手机端显示',
  `is_meal` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否店内点餐',
  `is_delivery` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否外卖订餐',
  `sendingprice` varchar(10) NOT NULL DEFAULT '' COMMENT '起送价格',
  `displayorder` tinyint(3) NOT NULL DEFAULT '0',
  `updatetime` int(10) NOT NULL DEFAULT '0',
  `is_sms` tinyint(1) NOT NULL DEFAULT '0',
  `dateline` int(10) NOT NULL DEFAULT '0',
  `dispatchprice` decimal(10,2) DEFAULT '0.00',
  `is_hot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '搜索页显示',
  `freeprice` decimal(10,2) DEFAULT '0.00',
  `begintime` varchar(20) DEFAULT '09:00' COMMENT '开始时间',
  `announce` varchar(1000) NOT NULL DEFAULT '' COMMENT '通知',
  `endtime` varchar(20) DEFAULT '18:00' COMMENT '结束时间',
  `consume` varchar(20) NOT NULL COMMENT '人均消费',
  `level` tinyint(1) NOT NULL DEFAULT '1' COMMENT '级别',
  `is_rest` tinyint(1) NOT NULL DEFAULT '0',
  `typeid` int(10) NOT NULL DEFAULT '0' COMMENT '商家类型'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_dish_stores`
--

INSERT INTO `ims_superdesk_dish_stores` (`id`, `weid`, `areaid`, `title`, `logo`, `info`, `content`, `tel`, `location_p`, `location_c`, `location_a`, `address`, `place`, `lat`, `lng`, `password`, `hours`, `recharging_password`, `thumb_url`, `enable_wifi`, `enable_card`, `enable_room`, `enable_park`, `is_show`, `is_meal`, `is_delivery`, `sendingprice`, `displayorder`, `updatetime`, `is_sms`, `dateline`, `dispatchprice`, `is_hot`, `freeprice`, `begintime`, `announce`, `endtime`, `consume`, `level`, `is_rest`, `typeid`) VALUES
(5, 16, 3, '超级团餐', 'images/16/2017/09/Qmy4F4XWJ35yNKs1MbXK1D413n3xYS.png', '超级团餐 - 试运行', '&lt;p&gt;超级团餐 - 试运行&lt;/p&gt;', '', '', '', '', '广东省深圳市福田区深南中路3024号32层', '', '22.5467690000', '114.0892190000', '', '', '', NULL, 0, 0, 0, 0, 1, 1, 1, '15', 0, 1508727859, 0, 1505113227, '0.00', 1, '15.00', '11:00', '公告 超级团餐 - 试运行', '12:00', '15', 5, 1, 24);

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_dish_store_setting`
--

CREATE TABLE `ims_superdesk_dish_store_setting` (
  `id` int(10) NOT NULL,
  `weid` int(10) UNSIGNED NOT NULL,
  `storeid` int(10) UNSIGNED NOT NULL,
  `order_enable` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '订餐开启',
  `dateline` int(10) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_dish_type`
--

CREATE TABLE `ims_superdesk_dish_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `weid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '类型名称',
  `parentid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `displayorder` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序',
  `dateline` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_dish_type`
--

INSERT INTO `ims_superdesk_dish_type` (`id`, `weid`, `name`, `parentid`, `displayorder`, `dateline`, `status`) VALUES
(24, 16, '团餐', 0, 0, 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ims_superdesk_dish_address`
--
ALTER TABLE `ims_superdesk_dish_address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_dish_area`
--
ALTER TABLE `ims_superdesk_dish_area`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_dish_blacklist`
--
ALTER TABLE `ims_superdesk_dish_blacklist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_openid` (`from_user`);

--
-- Indexes for table `ims_superdesk_dish_cart`
--
ALTER TABLE `ims_superdesk_dish_cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_dish_category`
--
ALTER TABLE `ims_superdesk_dish_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_dish_collection`
--
ALTER TABLE `ims_superdesk_dish_collection`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_dish_email_setting`
--
ALTER TABLE `ims_superdesk_dish_email_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_dish_goods`
--
ALTER TABLE `ims_superdesk_dish_goods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_dish_intelligent`
--
ALTER TABLE `ims_superdesk_dish_intelligent`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_dish_mealtime`
--
ALTER TABLE `ims_superdesk_dish_mealtime`
  ADD PRIMARY KEY (`id`),
  ADD KEY `indx_weid` (`weid`);

--
-- Indexes for table `ims_superdesk_dish_nave`
--
ALTER TABLE `ims_superdesk_dish_nave`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_dish_order`
--
ALTER TABLE `ims_superdesk_dish_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_dish_order_goods`
--
ALTER TABLE `ims_superdesk_dish_order_goods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_dish_print_order`
--
ALTER TABLE `ims_superdesk_dish_print_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_dish_print_setting`
--
ALTER TABLE `ims_superdesk_dish_print_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_dish_reply`
--
ALTER TABLE `ims_superdesk_dish_reply`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_rid` (`rid`);

--
-- Indexes for table `ims_superdesk_dish_setting`
--
ALTER TABLE `ims_superdesk_dish_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_dish_sms_checkcode`
--
ALTER TABLE `ims_superdesk_dish_sms_checkcode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_dish_sms_setting`
--
ALTER TABLE `ims_superdesk_dish_sms_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_dish_stores`
--
ALTER TABLE `ims_superdesk_dish_stores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_dish_store_setting`
--
ALTER TABLE `ims_superdesk_dish_store_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_dish_type`
--
ALTER TABLE `ims_superdesk_dish_type`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `ims_superdesk_dish_address`
--
ALTER TABLE `ims_superdesk_dish_address`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=405;

--
-- 使用表AUTO_INCREMENT `ims_superdesk_dish_area`
--
ALTER TABLE `ims_superdesk_dish_area`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `ims_superdesk_dish_blacklist`
--
ALTER TABLE `ims_superdesk_dish_blacklist`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- 使用表AUTO_INCREMENT `ims_superdesk_dish_cart`
--
ALTER TABLE `ims_superdesk_dish_cart`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- 使用表AUTO_INCREMENT `ims_superdesk_dish_category`
--
ALTER TABLE `ims_superdesk_dish_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- 使用表AUTO_INCREMENT `ims_superdesk_dish_collection`
--
ALTER TABLE `ims_superdesk_dish_collection`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- 使用表AUTO_INCREMENT `ims_superdesk_dish_email_setting`
--
ALTER TABLE `ims_superdesk_dish_email_setting`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `ims_superdesk_dish_goods`
--
ALTER TABLE `ims_superdesk_dish_goods`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- 使用表AUTO_INCREMENT `ims_superdesk_dish_intelligent`
--
ALTER TABLE `ims_superdesk_dish_intelligent`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 使用表AUTO_INCREMENT `ims_superdesk_dish_mealtime`
--
ALTER TABLE `ims_superdesk_dish_mealtime`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- 使用表AUTO_INCREMENT `ims_superdesk_dish_nave`
--
ALTER TABLE `ims_superdesk_dish_nave`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用表AUTO_INCREMENT `ims_superdesk_dish_order`
--
ALTER TABLE `ims_superdesk_dish_order`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- 使用表AUTO_INCREMENT `ims_superdesk_dish_order_goods`
--
ALTER TABLE `ims_superdesk_dish_order_goods`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- 使用表AUTO_INCREMENT `ims_superdesk_dish_print_order`
--
ALTER TABLE `ims_superdesk_dish_print_order`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- 使用表AUTO_INCREMENT `ims_superdesk_dish_print_setting`
--
ALTER TABLE `ims_superdesk_dish_print_setting`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `ims_superdesk_dish_reply`
--
ALTER TABLE `ims_superdesk_dish_reply`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `ims_superdesk_dish_setting`
--
ALTER TABLE `ims_superdesk_dish_setting`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `ims_superdesk_dish_sms_checkcode`
--
ALTER TABLE `ims_superdesk_dish_sms_checkcode`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- 使用表AUTO_INCREMENT `ims_superdesk_dish_sms_setting`
--
ALTER TABLE `ims_superdesk_dish_sms_setting`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `ims_superdesk_dish_stores`
--
ALTER TABLE `ims_superdesk_dish_stores`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用表AUTO_INCREMENT `ims_superdesk_dish_store_setting`
--
ALTER TABLE `ims_superdesk_dish_store_setting`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `ims_superdesk_dish_type`
--
ALTER TABLE `ims_superdesk_dish_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
