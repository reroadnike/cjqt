-- phpMyAdmin SQL Dump
-- version 4.4.15.8
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2017-08-10 20:01:08
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
-- 表的结构 `ims_superdesk_boardroom_4school`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school` (
  `id` int(10) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '名称',
  `address` varchar(100) NOT NULL COMMENT '地址',
  `floor` int(10) NOT NULL DEFAULT '1' COMMENT '楼层',
  `traffic` varchar(255) NOT NULL COMMENT '交通',
  `lat` decimal(9,6) NOT NULL COMMENT '经度',
  `lng` decimal(9,6) NOT NULL COMMENT '纬度',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `basic` text NOT NULL,
  `carousel` text NOT NULL COMMENT '轮播图',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '租金/小时',
  `equipment` text NOT NULL COMMENT '设备 序列化的设备编号数组',
  `max_num` int(10) NOT NULL DEFAULT '1' COMMENT '容纳人数',
  `appointment_num` int(10) NOT NULL DEFAULT '0',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '注意事项',
  `cancle_rule` varchar(255) NOT NULL DEFAULT '' COMMENT '取消预约规则',
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  `updatetime` int(10) NOT NULL COMMENT '修改时间',
  `enabled` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Enabled',
  `uniacid` int(10) NOT NULL DEFAULT '0' COMMENT 'uniacid'
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_boardroom_4school`
--

INSERT INTO `ims_superdesk_boardroom_4school` (`id`, `name`, `address`, `floor`, `traffic`, `lat`, `lng`, `thumb`, `basic`, `carousel`, `price`, `equipment`, `max_num`, `appointment_num`, `remark`, `cancle_rule`, `createtime`, `updatetime`, `enabled`, `uniacid`) VALUES
(1, 'D1028号会议室', 'D1028号会议室地址', 20, 'D1028号会议室', '39.902780', '116.285993', 'images/15/2017/06/wSSAzSCxB1SJ0BYBsGosOxgSHJ1nfx.png', '', 'a:1:{i:0;s:52:"images/15/2017/07/bsvKxR70a88MgbR6866VbuAbU9SA6J.png";}', '0.00', 's:240:"{&quot;value&quot;:1,&quot;text&quot;:&quot;电脑&quot;},{&quot;value&quot;:4,&quot;text&quot;:&quot;投影仪&quot;},{&quot;value&quot;:3,&quot;text&quot;:&quot;记号笔&quot;},{&quot;value&quot;:2,&quot;text&quot;:&quot;黑板擦&quot;}";', 10, 0, '', '', 1501104735, 1501753492, 1, 15),
(2, '地址名称', '地址名称名称名称名称', 30, '', '0.000000', '0.000000', '', '', 'a:1:{i:0;s:52:"images/15/2017/07/CMfQi6Z7w7MsHaYtCJ6JmzTh1WFcPZ.jpg";}', '0.00', 's:57:"{&quot;value&quot;:1,&quot;text&quot;:&quot;电脑&quot;}";', 0, 0, '', '', 1501104742, 1501257427, 1, 15);

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_4school_appointment`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_appointment` (
  `id` int(10) NOT NULL,
  `boardroom_id` int(10) NOT NULL COMMENT '会议室ID',
  `openid` varchar(64) NOT NULL COMMENT 'openid',
  `subject` varchar(255) NOT NULL DEFAULT '' COMMENT '会议主题',
  `client_name` varchar(64) NOT NULL COMMENT '客户名字',
  `client_telphone` varchar(25) NOT NULL COMMENT '客户电话',
  `people_num` int(1) NOT NULL DEFAULT '1' COMMENT '会议人数',
  `deleted` int(10) NOT NULL DEFAULT '0' COMMENT '删除(1已删除)',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功',
  `relate_id` varchar(80) NOT NULL DEFAULT '0' COMMENT '关联会员表',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `starttime` int(10) NOT NULL DEFAULT '0' COMMENT '预约开始时间',
  `endtime` int(10) NOT NULL DEFAULT '0' COMMENT '预约结束时间',
  `uniacid` int(10) NOT NULL DEFAULT '0' COMMENT 'uniacid',
  `paydetail` varchar(255) NOT NULL DEFAULT '' COMMENT '支付详情',
  `paytype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1为余额，2为在线，3为到付',
  `out_trade_no` varchar(32) NOT NULL DEFAULT '' COMMENT 'out_trade_no',
  `transaction_id` varchar(32) NOT NULL DEFAULT '' COMMENT '微信支付订单号',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '成交价',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '总价',
  `lable_ymd` varchar(32) NOT NULL DEFAULT '' COMMENT 'eg:2017-09-09',
  `lable_time` varchar(32) NOT NULL DEFAULT '' COMMENT 'eg:00:30-12:00',
  `situation` text NOT NULL COMMENT 'situation JSON',
  `quantity` decimal(10,1) NOT NULL DEFAULT '0.0' COMMENT '数量/小时'
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_4school_building_structures`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_building_structures` (
  `id` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '楼宇楼层名称',
  `thumb` varchar(255) NOT NULL COMMENT '楼宇楼层图片',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级楼宇楼层ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0',
  `description` varchar(500) NOT NULL COMMENT '楼宇楼层介绍',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启'
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_boardroom_4school_building_structures`
--

INSERT INTO `ims_superdesk_boardroom_4school_building_structures` (`id`, `uniacid`, `name`, `thumb`, `parentid`, `isrecommand`, `description`, `displayorder`, `enabled`) VALUES
(1, 15, '1号楼', '', 0, 0, '', 0, 1),
(2, 15, '一层', '', 1, 0, '', 0, 1),
(3, 15, '2号楼', '', 0, 0, '', 0, 1),
(4, 15, '3号楼', '', 0, 0, '', 0, 1),
(5, 15, '一层半老楼', '', 1, 0, '', 0, 0),
(6, 15, '一层老楼', '', 1, 0, '', 0, 0),
(7, 15, '二层', '', 1, 0, '', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_4school_equipment`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_equipment` (
  `id` int(11) NOT NULL,
  `name` varchar(10) NOT NULL COMMENT '设备名字',
  `images` varchar(255) NOT NULL COMMENT '设备图片',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `enabled` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Enabled',
  `uniacid` int(10) NOT NULL COMMENT 'uniacid'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_boardroom_4school_equipment`
--

INSERT INTO `ims_superdesk_boardroom_4school_equipment` (`id`, `name`, `images`, `updatetime`, `createtime`, `enabled`, `uniacid`) VALUES
(1, '电脑', '', 1501257829, 1501223187, 1, 15),
(2, '黑板擦', '', 1501230664, 1501228109, 1, 15),
(3, '记号笔', '', 1501230664, 1501228116, 1, 15),
(4, '投影仪', '', 1501230664, 1501228126, 1, 15);

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_4school_s_adv`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_s_adv` (
  `id` int(11) NOT NULL,
  `uniacid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_boardroom_4school_s_adv`
--

INSERT INTO `ims_superdesk_boardroom_4school_s_adv` (`id`, `uniacid`, `advname`, `link`, `thumb`, `displayorder`, `enabled`) VALUES
(1, 2, 'fsdf', '', 'images/2/2015/06/H5tAo0xGkk0V6K6b152jNuBTaQ61We.jpg', 0, 1),
(2, 2, 'fsdf', '', 'images/2/2015/06/BmZ3kL6H35hkKP9p1vQQql3KKSVbh5.jpg', 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_4school_s_cart`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_s_cart` (
  `id` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `goodsid` int(11) NOT NULL,
  `goodstype` tinyint(1) NOT NULL DEFAULT '1',
  `from_user` varchar(50) NOT NULL,
  `total` int(10) unsigned NOT NULL,
  `optionid` int(10) DEFAULT '0',
  `marketprice` decimal(10,2) DEFAULT '0.00'
) ENGINE=MyISAM AUTO_INCREMENT=175 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_4school_s_category`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_s_category` (
  `id` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `thumb` varchar(255) NOT NULL COMMENT '分类图片',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0',
  `description` varchar(500) NOT NULL COMMENT '分类介绍',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启'
) ENGINE=MyISAM AUTO_INCREMENT=67 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_boardroom_4school_s_category`
--


-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_4school_s_dispatch`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_s_dispatch` (
  `id` int(11) NOT NULL,
  `uniacid` int(11) DEFAULT '0',
  `dispatchname` varchar(50) DEFAULT '',
  `dispatchtype` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `firstprice` decimal(10,2) DEFAULT '0.00',
  `secondprice` decimal(10,2) DEFAULT '0.00',
  `firstweight` int(11) DEFAULT '0',
  `secondweight` int(11) DEFAULT '0',
  `express` int(11) DEFAULT '0',
  `description` text,
  `enabled` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_boardroom_4school_s_dispatch`
--

INSERT INTO `ims_superdesk_boardroom_4school_s_dispatch` (`id`, `uniacid`, `dispatchname`, `dispatchtype`, `displayorder`, `firstprice`, `secondprice`, `firstweight`, `secondweight`, `express`, `description`, `enabled`) VALUES
(1, 2, '顺丰', 0, 0, '10.00', '6.00', 1000, 1000, 2, '顺丰', 0);

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_4school_s_express`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_s_express` (
  `id` int(11) NOT NULL,
  `uniacid` int(11) DEFAULT '0',
  `express_name` varchar(50) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `express_price` varchar(10) DEFAULT '',
  `express_area` varchar(100) DEFAULT '',
  `express_url` varchar(255) DEFAULT ''
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_boardroom_4school_s_express`
--

INSERT INTO `ims_superdesk_boardroom_4school_s_express` (`id`, `uniacid`, `express_name`, `displayorder`, `express_price`, `express_area`, `express_url`) VALUES
(2, 2, '顺丰', 10000, '', '', ''),
(3, 2, '申通', 20000, '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_4school_s_feedback`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_s_feedback` (
  `id` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为维权，2为告擎',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态0未解决，1用户同意，2用户拒绝',
  `feedbackid` varchar(30) NOT NULL COMMENT '投诉单号',
  `transid` varchar(30) NOT NULL COMMENT '订单号',
  `reason` varchar(1000) NOT NULL COMMENT '理由',
  `solution` varchar(1000) NOT NULL COMMENT '期待解决方案',
  `remark` varchar(1000) NOT NULL COMMENT '备注',
  `createtime` int(10) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_4school_s_goods`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_s_goods` (
  `id` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `pcate` int(10) unsigned NOT NULL DEFAULT '0',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为实体，2为虚拟',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `unit` varchar(5) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `goodssn` varchar(50) NOT NULL DEFAULT '',
  `productsn` varchar(50) NOT NULL DEFAULT '',
  `marketprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `productprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `originalprice` decimal(10,2) NOT NULL,
  `costprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` int(10) unsigned NOT NULL DEFAULT '0',
  `totalcnf` int(11) DEFAULT '0' COMMENT '0 拍下减库存 1 付款减库存 2 永久不减',
  `sales` int(10) unsigned NOT NULL DEFAULT '0',
  `spec` varchar(5000) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `weight` decimal(10,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(10,2) NOT NULL DEFAULT '0.00',
  `maxbuy` int(11) DEFAULT '0',
  `usermaxbuy` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户最多购买数量',
  `hasoption` int(11) DEFAULT '0',
  `dispatch` int(11) DEFAULT '0',
  `thumb_url` text,
  `isnew` int(11) DEFAULT '0',
  `ishot` int(11) DEFAULT '0',
  `isdiscount` int(11) DEFAULT '0',
  `isrecommand` int(11) DEFAULT '0',
  `istime` int(11) DEFAULT '0',
  `timestart` int(11) DEFAULT '0',
  `timeend` int(11) DEFAULT '0',
  `viewcount` int(11) DEFAULT '0',
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_boardroom_4school_s_goods`
--

INSERT INTO `ims_superdesk_boardroom_4school_s_goods` (`id`, `uniacid`, `pcate`, `ccate`, `type`, `status`, `displayorder`, `title`, `thumb`, `unit`, `description`, `content`, `goodssn`, `productsn`, `marketprice`, `productprice`, `originalprice`, `costprice`, `total`, `totalcnf`, `sales`, `spec`, `createtime`, `weight`, `credit`, `maxbuy`, `usermaxbuy`, `hasoption`, `dispatch`, `thumb_url`, `isnew`, `ishot`, `isdiscount`, `isrecommand`, `istime`, `timestart`, `timeend`, `viewcount`, `deleted`) VALUES
(4, 15, 65, 66, 1, 1, 0, '益力矿泉水一箱（330ml）', 'images/15/2017/08/XL2y3ywl82zFdXy8lk5zzd2NmyYKE5.jpg', '箱', '', '', '', '', '0.00', '0.00', '0.00', '0.00', 998, 1, 1, '', 1501283442, '0.00', '0.00', 1, 999, 0, 0, 'a:0:{}', 0, 0, 0, 0, 0, 0, 0, 16, 0),
(5, 15, 65, 66, 1, 1, 0, '1L眼泪', 'images/15/2017/08/XL2y3ywl82zFdXy8lk5zzd2NmyYKE5.jpg', '箱', '', '', '', '', '0.00', '0.00', '0.00', '0.00', 999998, 1, 1, '', 1501560169, '0.00', '0.00', 10, 10, 0, 0, 'a:0:{}', 0, 0, 0, 0, 0, 0, 0, 1, 0),
(6, 15, 65, 66, 1, 1, 0, '农夫山泉 饮用天然水塑膜量贩装550ml*12瓶', 'images/15/2017/08/XL2y3ywl82zFdXy8lk5zzd2NmyYKE5.jpg', '', '', '', '', '', '0.00', '0.00', '0.00', '0.00', 999, 1, 0, '', 1501560331, '0.00', '0.00', 9, 9, 0, 0, 'a:0:{}', 0, 0, 0, 0, 0, 0, 0, 2, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_4school_s_goods_option`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_s_goods_option` (
  `id` int(11) NOT NULL,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `thumb` varchar(60) DEFAULT '',
  `productprice` decimal(10,2) DEFAULT '0.00',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  `costprice` decimal(10,2) DEFAULT '0.00',
  `stock` int(11) DEFAULT '0',
  `weight` decimal(10,2) DEFAULT '0.00',
  `displayorder` int(11) DEFAULT '0',
  `specs` text
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_4school_s_goods_param`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_s_goods_param` (
  `id` int(11) NOT NULL,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `value` text,
  `displayorder` int(11) DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_boardroom_4school_s_goods_param`
--

INSERT INTO `ims_superdesk_boardroom_4school_s_goods_param` (`id`, `goodsid`, `title`, `value`, `displayorder`) VALUES
(1, 4, '箱', '330ml，24瓶', 0);

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_4school_s_order`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_s_order` (
  `id` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `price` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功',
  `sendtype` tinyint(1) unsigned NOT NULL COMMENT '1为快递，2为自提',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付',
  `paydetail` varchar(255) NOT NULL COMMENT '支付详情',
  `goodstype` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `goodsprice` decimal(10,2) DEFAULT '0.00',
  `dispatchprice` decimal(10,2) DEFAULT '0.00',
  `dispatch` int(10) DEFAULT '0',
  `address` varchar(1024) NOT NULL DEFAULT '' COMMENT '收货地址信息',
  `out_trade_no` varchar(20) NOT NULL,
  `transaction_id` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号',
  `remark` varchar(1000) NOT NULL DEFAULT '',
  `expresscom` varchar(30) NOT NULL DEFAULT '',
  `expresssn` varchar(50) NOT NULL DEFAULT '',
  `express` varchar(200) NOT NULL DEFAULT '',
  `createtime` int(10) unsigned NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_4school_s_order_goods`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_s_order_goods` (
  `id` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `out_trade_no` varchar(32) NOT NULL DEFAULT '' COMMENT 'out_trade_no',
  `goodsid` int(10) unsigned NOT NULL,
  `price` decimal(10,2) DEFAULT '0.00',
  `total` int(10) unsigned NOT NULL DEFAULT '1',
  `optionid` int(10) DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  `optionname` text
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_boardroom_4school_s_order_goods`
--

INSERT INTO `ims_superdesk_boardroom_4school_s_order_goods` (`id`, `uniacid`, `orderid`, `out_trade_no`, `goodsid`, `price`, `total`, `optionid`, `createtime`, `optionname`) VALUES
(15, 15, 10, '', 4, '0.01', 1, 0, 1501599393, NULL),
(14, 15, 9, '', 5, '0.01', 1, 0, 1501590659, NULL),
(13, 15, 9, '', 4, '0.01', 1, 0, 1501590659, NULL),
(12, 15, 8, '', 6, '0.01', 1, 0, 1501589629, NULL),
(11, 15, 8, '', 5, '0.01', 1, 0, 1501589629, NULL),
(10, 15, 8, '', 4, '0.01', 1, 0, 1501589629, NULL),
(7, 15, 7, '', 4, '0.01', 1, 0, 1501576785, NULL),
(8, 15, 7, '', 5, '0.01', 1, 0, 1501576785, NULL),
(9, 15, 7, '', 6, '0.01', 1, 0, 1501576785, NULL),
(16, 15, 10, '', 5, '0.01', 1, 0, 1501599393, NULL),
(17, 15, 11, '08033384', 6, '0.01', 1, 0, 1501753327, NULL),
(18, 15, 12, '08038814', 4, '0.00', 1, 0, 1501753510, NULL),
(19, 15, 12, '08038814', 5, '0.00', 1, 0, 1501753510, NULL),
(20, 15, 13, '08105421', 4, '0.00', 0, 0, 1502363244, NULL),
(21, 15, 13, '08105421', 5, '0.00', 0, 0, 1502363244, NULL),
(22, 15, 14, '08106744', 4, '0.00', 0, 0, 1502364373, NULL),
(23, 15, 14, '08106744', 5, '0.00', 0, 0, 1502364373, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_4school_s_product`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_s_product` (
  `id` int(10) unsigned NOT NULL,
  `goodsid` int(11) NOT NULL,
  `productsn` varchar(50) NOT NULL,
  `title` varchar(1000) NOT NULL,
  `marketprice` decimal(10,0) unsigned NOT NULL,
  `productprice` decimal(10,0) unsigned NOT NULL,
  `total` int(11) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `spec` varchar(5000) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_4school_s_spec`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_s_spec` (
  `id` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `displaytype` tinyint(3) unsigned NOT NULL,
  `content` text NOT NULL,
  `goodsid` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_4school_s_spec_item`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_s_spec_item` (
  `id` int(11) NOT NULL,
  `uniacid` int(11) DEFAULT '0',
  `specid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `show` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_boardroom_4school_s_spec_item`
--

INSERT INTO `ims_superdesk_boardroom_4school_s_spec_item` (`id`, `uniacid`, `specid`, `title`, `thumb`, `show`, `displayorder`) VALUES
(1, 15, 1, '330ml', '', 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ims_superdesk_boardroom_4school`
--
ALTER TABLE `ims_superdesk_boardroom_4school`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_boardroom_4school_appointment`
--
ALTER TABLE `ims_superdesk_boardroom_4school_appointment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_boardroom_4school_building_structures`
--
ALTER TABLE `ims_superdesk_boardroom_4school_building_structures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_boardroom_4school_equipment`
--
ALTER TABLE `ims_superdesk_boardroom_4school_equipment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_boardroom_4school_s_adv`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_adv`
  ADD PRIMARY KEY (`id`),
  ADD KEY `indx_uniacid` (`uniacid`),
  ADD KEY `indx_enabled` (`enabled`),
  ADD KEY `indx_displayorder` (`displayorder`);

--
-- Indexes for table `ims_superdesk_boardroom_4school_s_cart`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_openid` (`from_user`);

--
-- Indexes for table `ims_superdesk_boardroom_4school_s_category`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_boardroom_4school_s_dispatch`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_dispatch`
  ADD PRIMARY KEY (`id`),
  ADD KEY `indx_uniacid` (`uniacid`),
  ADD KEY `indx_displayorder` (`displayorder`);

--
-- Indexes for table `ims_superdesk_boardroom_4school_s_express`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_express`
  ADD PRIMARY KEY (`id`),
  ADD KEY `indx_uniacid` (`uniacid`),
  ADD KEY `indx_displayorder` (`displayorder`);

--
-- Indexes for table `ims_superdesk_boardroom_4school_s_feedback`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_uniacid` (`uniacid`),
  ADD KEY `idx_feedbackid` (`feedbackid`),
  ADD KEY `idx_createtime` (`createtime`),
  ADD KEY `idx_transid` (`transid`);

--
-- Indexes for table `ims_superdesk_boardroom_4school_s_goods`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_goods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_boardroom_4school_s_goods_option`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_goods_option`
  ADD PRIMARY KEY (`id`),
  ADD KEY `indx_goodsid` (`goodsid`),
  ADD KEY `indx_displayorder` (`displayorder`);

--
-- Indexes for table `ims_superdesk_boardroom_4school_s_goods_param`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_goods_param`
  ADD PRIMARY KEY (`id`),
  ADD KEY `indx_goodsid` (`goodsid`),
  ADD KEY `indx_displayorder` (`displayorder`);

--
-- Indexes for table `ims_superdesk_boardroom_4school_s_order`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_boardroom_4school_s_order_goods`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_order_goods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_boardroom_4school_s_product`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_goodsid` (`goodsid`);

--
-- Indexes for table `ims_superdesk_boardroom_4school_s_spec`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_spec`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_boardroom_4school_s_spec_item`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_spec_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `indx_uniacid` (`uniacid`),
  ADD KEY `indx_specid` (`specid`),
  ADD KEY `indx_show` (`show`),
  ADD KEY `indx_displayorder` (`displayorder`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school`
--
ALTER TABLE `ims_superdesk_boardroom_4school`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_appointment`
--
ALTER TABLE `ims_superdesk_boardroom_4school_appointment`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_building_structures`
--
ALTER TABLE `ims_superdesk_boardroom_4school_building_structures`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_equipment`
--
ALTER TABLE `ims_superdesk_boardroom_4school_equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_s_adv`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_adv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_s_cart`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_cart`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=175;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_s_category`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_category`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=67;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_s_dispatch`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_dispatch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_s_express`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_express`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_s_feedback`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_feedback`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_s_goods`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_goods`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_s_goods_option`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_goods_option`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_s_goods_param`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_goods_param`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_s_order`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_order`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_s_order_goods`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_order_goods`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_s_product`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_product`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_s_spec`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_spec`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_s_spec_item`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_spec_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
