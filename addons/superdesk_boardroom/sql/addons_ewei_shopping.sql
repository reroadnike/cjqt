-- phpMyAdmin SQL Dump
-- version 4.4.15.9
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2017-07-29 03:05:49
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
-- 表的结构 `ims_superdesk_boardroom_s_adv`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_s_adv` (
  `id` int(11) NOT NULL,
  `uniacid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_boardroom_s_adv`
--

INSERT INTO `ims_superdesk_boardroom_s_adv` (`id`, `uniacid`, `advname`, `link`, `thumb`, `displayorder`, `enabled`) VALUES
(1, 2, 'fsdf', '', 'images/2/2015/06/H5tAo0xGkk0V6K6b152jNuBTaQ61We.jpg', 0, 1),
(2, 2, 'fsdf', '', 'images/2/2015/06/BmZ3kL6H35hkKP9p1vQQql3KKSVbh5.jpg', 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_s_cart`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_s_cart` (
  `id` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `goodsid` int(11) NOT NULL,
  `goodstype` tinyint(1) NOT NULL DEFAULT '1',
  `from_user` varchar(50) NOT NULL,
  `total` int(10) unsigned NOT NULL,
  `optionid` int(10) DEFAULT '0',
  `marketprice` decimal(10,2) DEFAULT '0.00'
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_s_category`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_s_category` (
  `id` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `thumb` varchar(255) NOT NULL COMMENT '分类图片',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0',
  `description` varchar(500) NOT NULL COMMENT '分类介绍',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启'
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_boardroom_s_category`
--

INSERT INTO `ims_superdesk_boardroom_s_category` (`id`, `uniacid`, `name`, `thumb`, `parentid`, `isrecommand`, `description`, `displayorder`, `enabled`) VALUES
(2, 2, '软件开发', 'images/2/2015/12/id0fITtNzvU100O9DOr1919DTrT0T3.jpg', 0, 1, '软件开发', 0, 1),
(3, 2, '企业/基础型ERP/定制型ERP', '', 2, 1, '', 0, 1),
(4, 2, '客户/基础型CRM/定制型CRM', '', 2, 1, '', 0, 1),
(5, 2, '移动应用开发', 'images/2/2015/12/I5g92YPZ5S9PTZ4Gz5XpGsXOZSRGSP.jpg', 0, 1, '', 0, 1),
(6, 2, 'UI设计', 'images/2/2015/12/bZWcQu8wvelPOv16V268zXp324c2w8.jpg', 0, 1, 'UI设计', 0, 1),
(7, 2, '网店设计', 'images/2/2015/12/De25xXeSU51X2AIT2aY1jt522IiOIY.jpg', 0, 1, '网店设计', 0, 1),
(8, 2, '网站建设', 'images/2/2015/12/hnFPuSq26wVJsgFbgwZvP6jRfb2kVz.jpg', 0, 1, '', 0, 1),
(9, 2, '营销推广', 'images/2/2015/12/N2SXsx6a36VORKu06ZwGXggx0agZVF.jpg', 0, 1, '营销推广', 0, 1),
(10, 2, '微信/企业号/服务号/订阅号', '', 5, 1, '', 0, 1),
(11, 2, '微站/微商城/微网站', '', 5, 1, '', 0, 1),
(12, 2, '平台/微信开发/微信认证', '', 5, 1, '', 0, 1),
(13, 2, 'APP/IOS开发/Android', '', 5, 1, '', 0, 1),
(14, 2, '原生/定制APP/iPad开发', '', 5, 1, '', 0, 1),
(15, 2, '资讯/新闻资讯/教育APP', '', 5, 1, '', 0, 1),
(16, 2, '生活/点餐APP/电商app', '', 5, 1, '', 0, 1),
(17, 2, '娱乐/社交APP/旅游app', '', 5, 1, '', 0, 1),
(18, 2, '内容/基础型CMS/定制型CMS', '', 2, 1, '', 0, 1),
(19, 2, '办公/基础型OA/定制型OA', '', 2, 1, '', 0, 1),
(20, 2, '插件/网站插件/浏览器插件', '', 2, 1, '', 0, 1),
(21, 2, '应用/桌面软件/多媒体软件', '', 2, 1, '', 0, 1),
(22, 2, '维护/软件维护/数据库维护', '', 2, 1, '', 0, 1),
(23, 2, '服务/数据采集/脚本制作', '', 2, 1, '', 0, 1),
(24, 2, '网站/网页设计/网站布局', '', 6, 1, '', 0, 1),
(25, 2, 'APP/整套设计/icon设计', '', 6, 1, '', 0, 1),
(26, 2, '软件/框架设计/菜单设计', '', 6, 1, '', 0, 1),
(27, 2, '游戏/操作界面/技能图标', '', 6, 1, '', 0, 1),
(28, 2, '特效/网页特效/动画特效', '', 6, 1, '', 0, 1),
(29, 2, '交互/网站交互/APP交互', '', 6, 1, '', 0, 1),
(30, 2, '响应/网页响应/移动站点', '', 6, 1, '', 0, 1),
(31, 2, '特色/UI设计专区', '', 6, 1, '', 0, 1),
(32, 2, '流量/包月推广/精准流量', '', 7, 1, '', 0, 1),
(33, 2, '销量/爆款打造/销量提升', '', 7, 1, '', 0, 1),
(34, 2, '收藏/人工收藏/开团提醒', '', 7, 1, '', 0, 1),
(35, 2, '托管/店铺托管/直通车托管', '', 7, 1, '', 0, 1),
(36, 2, '装修/整店装修/详情页设计', '', 7, 1, '', 0, 1),
(37, 2, '无线/首页设计/无线详情', '', 7, 1, '', 0, 1),
(38, 2, '平台/微商营销/京东推广', '', 7, 1, '', 0, 1),
(39, 2, '优化/加购物车/动态评分', '', 7, 1, '', 0, 1),
(40, 2, '精选/精品企业站/精品电商站', '', 8, 1, '', 0, 1),
(41, 2, '模版/电商网站/企业网站', '', 8, 1, '', 0, 1),
(42, 2, '升级/团购网站/旅游网站', '', 8, 1, '', 0, 1),
(43, 2, '定制/门户网站/金融网站', '', 8, 1, '', 0, 1),
(44, 2, '前端/前端切图/网页特效', '', 8, 1, '', 0, 1),
(45, 2, '测试/兼容性测试/功能测试', '', 8, 1, '', 0, 1),
(46, 2, '维护/数据库维护/软件维护', '', 8, 1, '', 0, 1),
(47, 2, '活动/模版建站/千元建站', '', 8, 1, '', 0, 1),
(48, 2, '营销/整合营销/品牌/产品', '', 9, 1, '', 0, 1),
(49, 2, '微信/微信关注/微信转发', '', 9, 1, '', 0, 1),
(50, 2, '微博/微博关注/微博转发', '', 9, 1, '', 0, 1),
(51, 2, '推广/软文推广/APP推广', '', 9, 1, '', 0, 1),
(52, 2, 'SEO/外链发布/关键词排名', '', 9, 1, '', 0, 1),
(53, 2, '口碑/知道问答/企业百科', '', 9, 1, '', 0, 1),
(54, 2, '群发/QQ推广/短信/旺旺', '', 9, 1, '', 0, 1),
(55, 2, '优选/官方套餐/点击付费', '', 9, 1, '', 0, 1),
(56, 2, '文案策划', 'images/2/2015/12/B4dpGZkLDZys7dAz1AP3R4d66Dgo11.jpg', 0, 1, '', 0, 1),
(57, 2, '文案/软文写作/新闻写作', '', 56, 1, '', 0, 1),
(58, 2, '策划/促销策划/商业策划', '', 56, 1, '', 0, 1),
(59, 2, '品牌/品牌故事/公司简介', '', 56, 1, '', 0, 1),
(60, 2, '翻译/专业翻译/文件翻译', '', 56, 1, '', 0, 1),
(61, 2, '语种/法语翻译/英语翻译', '', 56, 1, '', 0, 1),
(62, 2, '写作/公文写作/剧本创作', '', 56, 1, '', 0, 1),
(63, 2, '取名/宝宝取名/品牌取名', '', 56, 1, '', 0, 1),
(64, 6, '面食', '', 0, 0, '', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_s_dispatch`
--
# 快递计费
CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_s_dispatch` (
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
-- 转存表中的数据 `ims_superdesk_boardroom_s_dispatch`
--

INSERT INTO `ims_superdesk_boardroom_s_dispatch` (`id`, `uniacid`, `dispatchname`, `dispatchtype`, `displayorder`, `firstprice`, `secondprice`, `firstweight`, `secondweight`, `express`, `description`, `enabled`) VALUES
(1, 2, '顺丰', 0, 0, '10.00', '6.00', 1000, 1000, 2, '顺丰', 0);

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_s_express`
--
# 快递公司
CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_s_express` (
  `id` int(11) NOT NULL,
  `uniacid` int(11) DEFAULT '0',
  `express_name` varchar(50) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `express_price` varchar(10) DEFAULT '',
  `express_area` varchar(100) DEFAULT '',
  `express_url` varchar(255) DEFAULT ''
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_boardroom_s_express`
--

INSERT INTO `ims_superdesk_boardroom_s_express` (`id`, `uniacid`, `express_name`, `displayorder`, `express_price`, `express_area`, `express_url`) VALUES
(2, 2, '顺丰', 10000, '', '', ''),
(3, 2, '申通', 20000, '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_s_feedback`
--

# 反馈
CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_s_feedback` (
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
-- 表的结构 `ims_superdesk_boardroom_s_goods`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_s_goods` (
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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_boardroom_s_goods`
--

INSERT INTO `ims_superdesk_boardroom_s_goods` (`id`, `uniacid`, `pcate`, `ccate`, `type`, `status`, `displayorder`, `title`, `thumb`, `unit`, `description`, `content`, `goodssn`, `productsn`, `marketprice`, `productprice`, `originalprice`, `costprice`, `total`, `totalcnf`, `sales`, `spec`, `createtime`, `weight`, `credit`, `maxbuy`, `usermaxbuy`, `hasoption`, `dispatch`, `thumb_url`, `isnew`, `ishot`, `isdiscount`, `isrecommand`, `istime`, `timestart`, `timeend`, `viewcount`, `deleted`) VALUES
(1, 2, 2, 3, 1, 1, 0, '米欧尼 2015夏装新款 白色短袖镂空圆点上衣短裤两件套休闲套装女', 'images/2/2015/07/hu4Pa4XQ24c4YUSCusppSp66qa4yFt.jpg', '件', '', '<p><img src="https://img.alicdn.com/imgextra/i1/478543166/T238zyXPtXXXXXXXXX-478543166.jpg" alt="" align="absmiddle" /><img src="https://img.alicdn.com/imgextra/i2/478543166/T2ebjBXHFXXXXXXXXX-478543166.jpg" alt="" align="absmiddle" /><img src="https://img.alicdn.com/imgextra/i1/478543166/T28yLxXQpXXXXXXXXX-478543166.jpg" alt="" align="absmiddle" /><img src="https://img.alicdn.com/imgextra/i3/478543166/T2WgvzXMJXXXXXXXXX-478543166.jpg" alt="" align="absmiddle" /><img src="https://img.alicdn.com/imgextra/i3/478543166/T2vgrxXPFXXXXXXXXX-478543166.jpg" alt="" align="absmiddle" /><img src="https://img.alicdn.com/imgextra/i3/478543166/T2e0LxXPtXXXXXXXXX-478543166.jpg" alt="" align="absmiddle" /><img src="https://img.alicdn.com/imgextra/i2/478543166/T2A4zzXGlXXXXXXXXX-478543166.jpg" alt="" align="absmiddle" /><img src="https://img.alicdn.com/imgextra/i4/478543166/T2f7fuXNxaXXXXXXXX-478543166.jpg" alt="" align="absmiddle" /><img src="https://img.alicdn.com/imgextra/i4/478543166/T2tVnCXHRXXXXXXXXX-478543166.jpg" alt="" align="absmiddle" /><img src="https://img.alicdn.com/imgextra/i1/478543166/T2BtvzXNlXXXXXXXXX-478543166.jpg" alt="" align="absmiddle" /><img src="https://img.alicdn.com/imgextra/i4/478543166/T24efvXMpaXXXXXXXX-478543166.jpg" alt="" align="absmiddle" /><img src="https://img.alicdn.com/imgextra/i3/478543166/T2rKHzXPBXXXXXXXXX-478543166.jpg" alt="" align="absmiddle" /><img src="https://img.alicdn.com/imgextra/i1/478543166/T2V_2BXHVXXXXXXXXX-478543166.jpg" alt="" usemap="#Mapssd" align="absmiddle" border="0" /><map name="Mapssd"> <img src="https://img.alicdn.com/imgextra/i1/478543166/TB2r_M3XVXXXXbJXXXXXXXXXXXX-478543166.jpg" alt="" align="absmiddle" /><img src="https://img.alicdn.com/imgextra/i1/478543166/T2KNTvXLJaXXXXXXXX-478543166.jpg" alt="" align="absmiddle" /><img src="https://img.alicdn.com/imgextra/i2/478543166/T2QdbCXFtXXXXXXXXX-478543166.jpg" alt="" align="absmiddle" /><img src="https://img.alicdn.com/imgextra/i1/478543166/T21tnwXKXaXXXXXXXX-478543166.jpg" alt="" align="absmiddle" /><img src="https://img.alicdn.com/imgextra/i4/478543166/T2822xXMRXXXXXXXXX-478543166.jpg" alt="" align="absmiddle" /><img src="https://img.alicdn.com/imgextra/i2/478543166/T2WufxXPJXXXXXXXXX-478543166.jpg" alt="" align="absmiddle" /></map></p>', '', '', '90.00', '100.00', '120.00', '60.00', 87, 1, 13, '', 1435557035, '50.00', '0.00', 10, 0, 0, 0, 'a:5:{i:0;s:51:"images/2/2015/07/Ei6dGDgz89DiIJIFwWDijFGNZJwiWw.jpg";i:1;s:51:"images/2/2015/07/Z3wy3Lb7BEJblyWjhJLlYYXRMZqW9g.jpg";i:2;s:51:"images/2/2015/07/o92wD82CN2wTiOdzkdoi6ZIIOtNN72.jpg";i:3;s:51:"images/2/2015/07/Q6nKOIK6TaII1oWb0nIIPiIfZblnKn.jpg";i:4;s:51:"images/2/2015/07/xfA16G6zFpRI1NXYYc1ItFdPXrERyn.jpg";}', 0, 0, 0, 0, 0, 1435556820, 1435556820, 37, 0),
(2, 2, 2, 3, 1, 1, 0, '恩诺童 美豆婴儿奶瓶带手柄吸管 宽口径耐高温ppsu新生儿童宝宝防摔塑料防胀气奶', 'images/2/2015/07/hapRsS1H72RnHrQbBRB88BI5PHPLbA.jpg', '', '', '', '', '', '11.00', '11.00', '11.00', '11.00', 111111, 0, 11, '', 1436963219, '1111.00', '0.00', 1, 0, 0, 0, 'a:2:{i:0;s:51:"images/2/2015/07/l787xN88n8473QVNN7hnqMlRl73ULI.jpg";i:1;s:51:"images/2/2015/07/UsIqVKn3c25IbIKKKd6KAKi2CiVIvk.jpg";}', 0, 0, 0, 0, 0, 1436963160, 1436963160, 12, 0),
(3, 2, 9, 0, 2, 1, 0, '月嫂服务 - 陈姐', 'images/2/2015/07/hapRsS1H72RnHrQbBRB88BI5PHPLbA.jpg', '人', '', '', '', '', '1.00', '1.00', '1.00', '1.00', 1, 0, 111, '', 1436963389, '1.00', '0.00', 1, 0, 0, 0, 'a:0:{}', 0, 0, 0, 0, 0, 1436963280, 1436963280, 12, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_s_goods_option`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_s_goods_option` (
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
-- 表的结构 `ims_superdesk_boardroom_s_goods_param`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_s_goods_param` (
  `id` int(11) NOT NULL,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `value` text,
  `displayorder` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_s_order`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_s_order` (
  `id` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `ordersn` varchar(20) NOT NULL,
  `price` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功',
  `sendtype` tinyint(1) unsigned NOT NULL COMMENT '1为快递，2为自提',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付',
  `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号',
  `goodstype` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `remark` varchar(1000) NOT NULL DEFAULT '',
  `address` varchar(1024) NOT NULL DEFAULT '' COMMENT '收货地址信息',
  `expresscom` varchar(30) NOT NULL DEFAULT '',
  `expresssn` varchar(50) NOT NULL DEFAULT '',
  `express` varchar(200) NOT NULL DEFAULT '',
  `goodsprice` decimal(10,2) DEFAULT '0.00',
  `dispatchprice` decimal(10,2) DEFAULT '0.00',
  `dispatch` int(10) DEFAULT '0',
  `paydetail` varchar(255) NOT NULL COMMENT '支付详情',
  `createtime` int(10) unsigned NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_boardroom_s_order`
--

INSERT INTO `ims_superdesk_boardroom_s_order` (`id`, `uniacid`, `from_user`, `ordersn`, `price`, `status`, `sendtype`, `paytype`, `transid`, `goodstype`, `remark`, `address`, `expresscom`, `expresssn`, `express`, `goodsprice`, `dispatchprice`, `dispatch`, `paydetail`, `createtime`) VALUES
(1, 2, 'osNMAt_RQ3HR0J1dDivnv--7iZOQ', '06292384', '10.04', 1, 0, 3, '0', 0, '', '林业局|18938777776||广东省|中山市||大东裕', '', '', '', '0.04', '10.00', 1, '', 1435577122),
(2, 2, 'osNMAt_RQ3HR0J1dDivnv--7iZOQ', '06298877', '10.04', 1, 0, 3, '0', 0, '', '林业局|18938777776||广东省|中山市||大东裕', '', '', '', '0.04', '10.00', 1, '', 1435577577),
(3, 2, 'osNMAt_RQ3HR0J1dDivnv--7iZOQ', '06298849', '10.01', 2, 0, 3, '0', 0, '发货了。。', '林业局|18938777776||广东省|中山市||大东裕', '顺丰', '111111111111111111XX', 'shunfeng', '0.01', '10.00', 1, '', 1435579664),
(4, 2, 'osNMAtwM9gLXqSEm7T5bVp6CF574', '07020934', '10.02', 1, 0, 3, '0', 0, '他自己去掉好看得知是不是', '季節|13377665543||广东省|中山市||東京股市', '', '', '', '0.02', '10.00', 1, '', 1435804377),
(5, 2, 'osNMAtwM9gLXqSEm7T5bVp6CF574', '07111385', '10.01', 3, 0, 3, '0', 0, '', '季節|13377665543||广东省|中山市||東京股市', '', '', '', '0.01', '10.00', 1, '', 1436585481),
(6, 2, 'osNMAtwM9gLXqSEm7T5bVp6CF574', '07138824', '10.01', 1, 0, 3, '0', 0, '', '季節|13377665543||广东省|中山市||東京股市', '', '', '', '0.01', '10.00', 1, '', 1436751830);

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_s_order_goods`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_s_order_goods` (
  `id` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `goodsid` int(10) unsigned NOT NULL,
  `price` decimal(10,2) DEFAULT '0.00',
  `total` int(10) unsigned NOT NULL DEFAULT '1',
  `optionid` int(10) DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  `optionname` text
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_boardroom_s_order_goods`
--

INSERT INTO `ims_superdesk_boardroom_s_order_goods` (`id`, `uniacid`, `orderid`, `goodsid`, `price`, `total`, `optionid`, `createtime`, `optionname`) VALUES
(1, 2, 1, 1, '0.01', 4, 0, 1435577122, NULL),
(2, 2, 2, 1, '0.01', 4, 0, 1435577577, NULL),
(3, 2, 3, 1, '0.01', 1, 0, 1435579664, NULL),
(4, 2, 4, 1, '0.01', 2, 0, 1435804377, NULL),
(5, 2, 5, 1, '0.01', 1, 0, 1436585481, NULL),
(6, 2, 6, 1, '0.01', 1, 0, 1436751830, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_boardroom_s_product`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_s_product` (
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
-- 表的结构 `ims_superdesk_boardroom_s_spec`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_s_spec` (
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
-- 表的结构 `ims_superdesk_boardroom_s_spec_item`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_s_spec_item` (
  `id` int(11) NOT NULL,
  `uniacid` int(11) DEFAULT '0',
  `specid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `show` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ims_superdesk_boardroom_s_adv`
--
ALTER TABLE `ims_superdesk_boardroom_s_adv`
  ADD PRIMARY KEY (`id`),
  ADD KEY `indx_uniacid` (`uniacid`),
  ADD KEY `indx_enabled` (`enabled`),
  ADD KEY `indx_displayorder` (`displayorder`);

--
-- Indexes for table `ims_superdesk_boardroom_s_cart`
--
ALTER TABLE `ims_superdesk_boardroom_s_cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_openid` (`from_user`);

--
-- Indexes for table `ims_superdesk_boardroom_s_category`
--
ALTER TABLE `ims_superdesk_boardroom_s_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_boardroom_s_dispatch`
--
ALTER TABLE `ims_superdesk_boardroom_s_dispatch`
  ADD PRIMARY KEY (`id`),
  ADD KEY `indx_uniacid` (`uniacid`),
  ADD KEY `indx_displayorder` (`displayorder`);

--
-- Indexes for table `ims_superdesk_boardroom_s_express`
--
ALTER TABLE `ims_superdesk_boardroom_s_express`
  ADD PRIMARY KEY (`id`),
  ADD KEY `indx_uniacid` (`uniacid`),
  ADD KEY `indx_displayorder` (`displayorder`);

--
-- Indexes for table `ims_superdesk_boardroom_s_feedback`
--
ALTER TABLE `ims_superdesk_boardroom_s_feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_uniacid` (`uniacid`),
  ADD KEY `idx_feedbackid` (`feedbackid`),
  ADD KEY `idx_createtime` (`createtime`),
  ADD KEY `idx_transid` (`transid`);

--
-- Indexes for table `ims_superdesk_boardroom_s_goods`
--
ALTER TABLE `ims_superdesk_boardroom_s_goods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_boardroom_s_goods_option`
--
ALTER TABLE `ims_superdesk_boardroom_s_goods_option`
  ADD PRIMARY KEY (`id`),
  ADD KEY `indx_goodsid` (`goodsid`),
  ADD KEY `indx_displayorder` (`displayorder`);

--
-- Indexes for table `ims_superdesk_boardroom_s_goods_param`
--
ALTER TABLE `ims_superdesk_boardroom_s_goods_param`
  ADD PRIMARY KEY (`id`),
  ADD KEY `indx_goodsid` (`goodsid`),
  ADD KEY `indx_displayorder` (`displayorder`);

--
-- Indexes for table `ims_superdesk_boardroom_s_order`
--
ALTER TABLE `ims_superdesk_boardroom_s_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_boardroom_s_order_goods`
--
ALTER TABLE `ims_superdesk_boardroom_s_order_goods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_boardroom_s_product`
--
ALTER TABLE `ims_superdesk_boardroom_s_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_goodsid` (`goodsid`);

--
-- Indexes for table `ims_superdesk_boardroom_s_spec`
--
ALTER TABLE `ims_superdesk_boardroom_s_spec`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_boardroom_s_spec_item`
--
ALTER TABLE `ims_superdesk_boardroom_s_spec_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `indx_uniacid` (`uniacid`),
  ADD KEY `indx_specid` (`specid`),
  ADD KEY `indx_show` (`show`),
  ADD KEY `indx_displayorder` (`displayorder`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_s_adv`
--
ALTER TABLE `ims_superdesk_boardroom_s_adv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_s_cart`
--
ALTER TABLE `ims_superdesk_boardroom_s_cart`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_s_category`
--
ALTER TABLE `ims_superdesk_boardroom_s_category`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=65;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_s_dispatch`
--
ALTER TABLE `ims_superdesk_boardroom_s_dispatch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_s_express`
--
ALTER TABLE `ims_superdesk_boardroom_s_express`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_s_feedback`
--
ALTER TABLE `ims_superdesk_boardroom_s_feedback`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_s_goods`
--
ALTER TABLE `ims_superdesk_boardroom_s_goods`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_s_goods_option`
--
ALTER TABLE `ims_superdesk_boardroom_s_goods_option`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_s_goods_param`
--
ALTER TABLE `ims_superdesk_boardroom_s_goods_param`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_s_order`
--
ALTER TABLE `ims_superdesk_boardroom_s_order`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_s_order_goods`
--
ALTER TABLE `ims_superdesk_boardroom_s_order_goods`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_s_product`
--
ALTER TABLE `ims_superdesk_boardroom_s_product`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_s_spec`
--
ALTER TABLE `ims_superdesk_boardroom_s_spec`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ims_superdesk_boardroom_s_spec_item`
--
ALTER TABLE `ims_superdesk_boardroom_s_spec_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
