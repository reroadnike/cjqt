-- phpMyAdmin SQL Dump
-- version 4.4.15.8
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2017-09-22 11:16:25
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
  `structures_parentid` int(10) NOT NULL DEFAULT '0' COMMENT '楼宇',
  `structures_childid` int(10) NOT NULL DEFAULT '0' COMMENT '楼层',
  `attribute` int(10) NOT NULL DEFAULT '0' COMMENT '属性',
  `traffic` varchar(255) NOT NULL COMMENT '交通',
  `lat` decimal(9,6) NOT NULL COMMENT '经度',
  `lng` decimal(9,6) NOT NULL COMMENT '纬度',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `basic` text NOT NULL,
  `carousel` text NOT NULL COMMENT '轮播图',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '租金/小时',
  `equipment` text NOT NULL COMMENT '设备 序列化的设备编号数组',
  `desk` varchar(255) NOT NULL DEFAULT '' COMMENT '桌子',
  `chair` varchar(255) NOT NULL DEFAULT '' COMMENT '椅子',
  `max_num` int(10) NOT NULL DEFAULT '1' COMMENT '容纳人数',
  `appointment_num` int(10) NOT NULL DEFAULT '0',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '注意事项',
  `cancle_rule` varchar(255) NOT NULL DEFAULT '' COMMENT '取消预约规则',
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  `updatetime` int(10) NOT NULL COMMENT '修改时间',
  `enabled` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Enabled',
  `uniacid` int(10) NOT NULL DEFAULT '0' COMMENT 'uniacid',
  `organization_code` varchar(32) DEFAULT '' COMMENT 'organization_code',
  `virtual_code` varchar(32) NOT NULL DEFAULT '' COMMENT 'virtual_code'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_4school_announcement`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_announcement` (
  `id` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `organization_code` varchar(32) DEFAULT '' COMMENT 'organization_code',
  `virtual_code` varchar(32) NOT NULL DEFAULT '' COMMENT 'virtual_code',
  `regionid` varchar(255) NOT NULL COMMENT '小区编号',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `author` varchar(50) NOT NULL COMMENT '作者',
  `createtime` int(10) unsigned NOT NULL,
  `starttime` int(11) unsigned NOT NULL COMMENT '开始时间',
  `endtime` int(11) unsigned NOT NULL COMMENT '结束时间',
  `status` tinyint(1) NOT NULL COMMENT '1禁用，2启用',
  `enable` tinyint(1) NOT NULL COMMENT '模板类型',
  `datetime` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL COMMENT '通知范围',
  `reason` varchar(100) NOT NULL COMMENT '通知范围',
  `remark` varchar(100) NOT NULL COMMENT '通知备注',
  `pid` int(10) unsigned NOT NULL COMMENT '物业ID',
  `tit` varchar(255) NOT NULL COMMENT '标题',
  `time` varchar(100) NOT NULL COMMENT '门禁卡失效时间',
  `scope` varchar(100) NOT NULL COMMENT '门禁卡失效范围',
  `method` varchar(300) NOT NULL COMMENT '门禁卡重新激活办法',
  `uid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='发布公告';

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_4school_announcement_reading_member`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_announcement_reading_member` (
  `id` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `organization_code` varchar(32) DEFAULT '' COMMENT 'organization_code',
  `virtual_code` varchar(32) NOT NULL DEFAULT '' COMMENT 'virtual_code',
  `unionid` varchar(50) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `aid` int(10) unsigned NOT NULL COMMENT '公告id',
  `status` varchar(1000) NOT NULL COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='公告阅读记录表';

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
  `organization_code` varchar(32) DEFAULT '' COMMENT 'organization_code',
  `virtual_code` varchar(32) NOT NULL DEFAULT '' COMMENT 'virtual_code',
  `paydetail` varchar(255) NOT NULL DEFAULT '' COMMENT '支付详情',
  `paytype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1为余额，2为在线，3为到付',
  `out_trade_no` varchar(32) NOT NULL DEFAULT '' COMMENT 'out_trade_no',
  `transaction_id` varchar(32) NOT NULL DEFAULT '' COMMENT '微信支付订单号',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '成交价',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '总价',
  `lable_ymd` varchar(255) NOT NULL DEFAULT '' COMMENT 'eg:2017-09-09',
  `lable_time` varchar(128) NOT NULL DEFAULT '' COMMENT 'eg:00:30-12:00',
  `situation` text NOT NULL COMMENT 'situation JSON',
  `quantity` decimal(10,1) NOT NULL DEFAULT '0.0' COMMENT '数量/小时'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_4school_building_attribute`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_building_attribute` (
  `id` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `organization_code` varchar(32) DEFAULT '' COMMENT 'organization_code',
  `virtual_code` varchar(32) NOT NULL DEFAULT '' COMMENT 'virtual_code',
  `name` varchar(50) NOT NULL COMMENT '名称',
  `thumb` varchar(255) NOT NULL COMMENT '图片',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0',
  `description` varchar(500) NOT NULL COMMENT '介绍',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_4school_building_structures`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_building_structures` (
  `id` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `organization_code` varchar(32) DEFAULT '' COMMENT 'organization_code',
  `virtual_code` varchar(32) NOT NULL DEFAULT '' COMMENT 'virtual_code',
  `name` varchar(50) NOT NULL COMMENT '楼宇楼层名称',
  `thumb` varchar(255) NOT NULL COMMENT '楼宇楼层图片',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级楼宇楼层ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0',
  `description` varchar(500) NOT NULL COMMENT '楼宇楼层介绍',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_4school_category`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_category` (
  `id` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned DEFAULT NULL,
  `organization_code` varchar(32) DEFAULT '' COMMENT 'organization_code',
  `virtual_code` varchar(32) NOT NULL DEFAULT '' COMMENT 'virtual_code',
  `parentid` int(10) unsigned DEFAULT NULL,
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `description` varchar(50) NOT NULL COMMENT '分类描述',
  `thumb` varchar(100) NOT NULL COMMENT '分类图片',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  `type` int(10) unsigned NOT NULL COMMENT '1家政，2报修，3投诉，4二手，5超市，6商家',
  `price` decimal(12,2) DEFAULT NULL,
  `gtime` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='类型表';

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
  `uniacid` int(10) NOT NULL COMMENT 'uniacid',
  `organization_code` varchar(32) DEFAULT '' COMMENT 'organization_code',
  `virtual_code` varchar(32) NOT NULL DEFAULT '' COMMENT 'virtual_code'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_4school_images`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_images` (
  `id` int(11) NOT NULL,
  `src` varchar(255) DEFAULT NULL,
  `file` longtext,
  `type` int(11) NOT NULL COMMENT '报修1，租赁2'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_4school_report`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_report` (
  `id` int(11) unsigned NOT NULL,
  `uniacid` int(11) unsigned NOT NULL COMMENT '公众号ID',
  `organization_code` varchar(32) DEFAULT '' COMMENT 'organization_code',
  `virtual_code` varchar(32) NOT NULL DEFAULT '' COMMENT 'virtual_code',
  `openid` varchar(50) NOT NULL COMMENT '用户身份',
  `regionid` int(10) unsigned NOT NULL COMMENT '小区编号',
  `type` tinyint(1) NOT NULL COMMENT '1为报修，2为投诉',
  `category` varchar(50) NOT NULL DEFAULT '' COMMENT '类目',
  `content` varchar(255) NOT NULL COMMENT '投诉内容',
  `requirement` varchar(1000) NOT NULL,
  `createtime` int(11) unsigned NOT NULL COMMENT '投诉日期',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态,1已处理,2未处理,3受理中',
  `newmsg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有新信息',
  `rank` tinyint(3) unsigned NOT NULL COMMENT '评级 1满意，2一般，3不满意',
  `comment` varchar(1000) NOT NULL,
  `resolve` varchar(1000) NOT NULL COMMENT '处理结果',
  `resolver` varchar(50) NOT NULL COMMENT '处理人',
  `resolvetime` int(10) NOT NULL COMMENT '处理时间',
  `address` varchar(80) NOT NULL COMMENT '地址',
  `images` text,
  `print_sta` int(3) NOT NULL COMMENT '打印状态',
  `out_trade_no` varchar(32) NOT NULL DEFAULT '' COMMENT 'out_trade_no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_4school_s_category`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_s_category` (
  `id` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `organization_code` varchar(32) DEFAULT '' COMMENT 'organization_code',
  `virtual_code` varchar(32) NOT NULL DEFAULT '' COMMENT 'virtual_code',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `thumb` varchar(255) NOT NULL COMMENT '分类图片',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0',
  `description` varchar(500) NOT NULL COMMENT '分类介绍',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
  `organization_code` varchar(32) DEFAULT '' COMMENT 'organization_code',
  `virtual_code` varchar(32) NOT NULL DEFAULT '' COMMENT 'virtual_code',
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_4school_s_order`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_s_order` (
  `id` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `organization_code` varchar(32) DEFAULT '' COMMENT 'organization_code',
  `virtual_code` varchar(32) NOT NULL DEFAULT '' COMMENT 'virtual_code',
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_4school_x_equipment`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_x_equipment` (
  `id` int(11) NOT NULL,
  `boardroom_id` int(11) NOT NULL DEFAULT '0',
  `equipment_id` int(11) NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  `updatetime` int(11) NOT NULL DEFAULT '0',
  `uniacid` int(10) NOT NULL DEFAULT '0' COMMENT 'uniacid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ims_superdesk_boardroom_4school`
--
ALTER TABLE `ims_superdesk_boardroom_4school`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_boardroom_4school_announcement`
--
ALTER TABLE `ims_superdesk_boardroom_4school_announcement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_boardroom_4school_announcement_reading_member`
--
ALTER TABLE `ims_superdesk_boardroom_4school_announcement_reading_member`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_boardroom_4school_appointment`
--
ALTER TABLE `ims_superdesk_boardroom_4school_appointment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_boardroom_4school_building_attribute`
--
ALTER TABLE `ims_superdesk_boardroom_4school_building_attribute`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_boardroom_4school_building_structures`
--
ALTER TABLE `ims_superdesk_boardroom_4school_building_structures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_boardroom_4school_category`
--
ALTER TABLE `ims_superdesk_boardroom_4school_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_boardroom_4school_equipment`
--
ALTER TABLE `ims_superdesk_boardroom_4school_equipment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_boardroom_4school_images`
--
ALTER TABLE `ims_superdesk_boardroom_4school_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_boardroom_4school_report`
--
ALTER TABLE `ims_superdesk_boardroom_4school_report`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_uniacid_regionid` (`uniacid`,`regionid`);

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
-- Indexes for table `ims_superdesk_boardroom_4school_x_equipment`
--
ALTER TABLE `ims_superdesk_boardroom_4school_x_equipment`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school`
--
ALTER TABLE `ims_superdesk_boardroom_4school`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_announcement`
--
ALTER TABLE `ims_superdesk_boardroom_4school_announcement`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_announcement_reading_member`
--
ALTER TABLE `ims_superdesk_boardroom_4school_announcement_reading_member`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_appointment`
--
ALTER TABLE `ims_superdesk_boardroom_4school_appointment`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_building_attribute`
--
ALTER TABLE `ims_superdesk_boardroom_4school_building_attribute`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_building_structures`
--
ALTER TABLE `ims_superdesk_boardroom_4school_building_structures`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_category`
--
ALTER TABLE `ims_superdesk_boardroom_4school_category`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_equipment`
--
ALTER TABLE `ims_superdesk_boardroom_4school_equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_images`
--
ALTER TABLE `ims_superdesk_boardroom_4school_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_report`
--
ALTER TABLE `ims_superdesk_boardroom_4school_report`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_s_adv`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_adv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_s_cart`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_cart`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_s_category`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_category`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_s_dispatch`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_dispatch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_s_express`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_express`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_s_feedback`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_feedback`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_s_goods`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_goods`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_s_goods_option`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_goods_option`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_s_goods_param`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_goods_param`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_s_order`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_order`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_s_order_goods`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_order_goods`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_s_product`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_product`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_s_spec`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_spec`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_s_spec_item`
--
ALTER TABLE `ims_superdesk_boardroom_4school_s_spec_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_4school_x_equipment`
--
ALTER TABLE `ims_superdesk_boardroom_4school_x_equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
