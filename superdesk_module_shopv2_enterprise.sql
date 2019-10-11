/*
Navicat MySQL Data Transfer

Source Server         : cjqt企业购测试
Source Server Version : 50724
Source Host           : 47.107.240.183:3306
Source Database       : superdesk_module_shopv2_enterprise

Target Server Type    : MYSQL
Target Server Version : 50724
File Encoding         : 65001

Date: 2019-10-11 16:44:34
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ims_account
-- ----------------------------
DROP TABLE IF EXISTS `ims_account`;
CREATE TABLE `ims_account` (
  `acid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `hash` varchar(8) NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `isconnect` tinyint(4) NOT NULL,
  `isdeleted` tinyint(2) NOT NULL DEFAULT '0' COMMENT 'isdeleted',
  PRIMARY KEY (`acid`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_account_wechats
-- ----------------------------
DROP TABLE IF EXISTS `ims_account_wechats`;
CREATE TABLE `ims_account_wechats` (
  `acid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `token` varchar(32) NOT NULL,
  `encodingaeskey` varchar(255) NOT NULL,
  `access_token` varchar(1000) NOT NULL,
  `level` tinyint(4) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  `account` varchar(30) NOT NULL,
  `original` varchar(50) NOT NULL,
  `signature` varchar(100) NOT NULL,
  `country` varchar(10) NOT NULL,
  `province` varchar(3) NOT NULL,
  `city` varchar(15) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(32) NOT NULL,
  `lastupdate` int(10) unsigned NOT NULL,
  `key` varchar(50) NOT NULL,
  `secret` varchar(50) NOT NULL,
  `styleid` int(10) unsigned NOT NULL,
  `jsapi_ticket` varchar(1000) NOT NULL,
  `card_ticket` varchar(1000) NOT NULL,
  `subscribeurl` varchar(120) NOT NULL,
  `auth_refresh_token` varchar(255) NOT NULL,
  PRIMARY KEY (`acid`),
  KEY `idx_key` (`key`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_account_yixin
-- ----------------------------
DROP TABLE IF EXISTS `ims_account_yixin`;
CREATE TABLE `ims_account_yixin` (
  `acid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `token` varchar(32) NOT NULL,
  `access_token` varchar(1000) NOT NULL,
  `level` tinyint(4) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  `account` varchar(30) NOT NULL,
  `signature` varchar(100) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(32) NOT NULL,
  `key` varchar(50) NOT NULL,
  `secret` varchar(50) NOT NULL,
  `styleid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`acid`),
  KEY `idx_key` (`key`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_activity_clerk_menu
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_clerk_menu`;
CREATE TABLE `ims_activity_clerk_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `displayorder` int(4) NOT NULL,
  `pid` int(6) NOT NULL,
  `group_name` varchar(20) NOT NULL,
  `title` varchar(20) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `url` varchar(60) NOT NULL,
  `type` varchar(20) NOT NULL,
  `permission` varchar(50) NOT NULL,
  `system` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_activity_clerks
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_clerks`;
CREATE TABLE `ims_activity_clerks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `nickname` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_activity_coupon
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_coupon`;
CREATE TABLE `ims_activity_coupon` (
  `couponid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `type` tinyint(4) NOT NULL,
  `title` varchar(30) NOT NULL,
  `couponsn` varchar(50) NOT NULL,
  `description` text,
  `discount` decimal(10,2) NOT NULL,
  `condition` decimal(10,2) NOT NULL,
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  `limit` int(11) NOT NULL,
  `dosage` int(11) unsigned NOT NULL,
  `amount` int(11) unsigned NOT NULL,
  `thumb` varchar(500) NOT NULL,
  `credit` int(10) unsigned NOT NULL,
  `credittype` varchar(20) NOT NULL,
  `use_module` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`couponid`),
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_activity_coupon_allocation
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_coupon_allocation`;
CREATE TABLE `ims_activity_coupon_allocation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `couponid` int(10) unsigned NOT NULL,
  `groupid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`couponid`,`groupid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_activity_coupon_modules
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_coupon_modules`;
CREATE TABLE `ims_activity_coupon_modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `couponid` int(10) unsigned NOT NULL,
  `module` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `couponid` (`couponid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_activity_coupon_password
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_coupon_password`;
CREATE TABLE `ims_activity_coupon_password` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_activity_coupon_record
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_coupon_record`;
CREATE TABLE `ims_activity_coupon_record` (
  `recid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `grantmodule` varchar(50) NOT NULL,
  `granttime` int(10) unsigned NOT NULL,
  `usemodule` varchar(50) NOT NULL,
  `usetime` int(10) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL,
  `remark` varchar(300) NOT NULL,
  `couponid` int(10) unsigned NOT NULL,
  `operator` varchar(30) NOT NULL,
  `clerk_id` int(10) unsigned NOT NULL,
  `store_id` int(10) unsigned NOT NULL,
  `clerk_type` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`recid`),
  KEY `couponid` (`uid`,`grantmodule`,`usemodule`,`status`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_activity_exchange
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_exchange`;
CREATE TABLE `ims_activity_exchange` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `couponid` int(10) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `thumb` varchar(500) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `extra` varchar(3000) NOT NULL,
  `credit` int(10) unsigned NOT NULL,
  `credittype` varchar(10) NOT NULL,
  `pretotal` int(11) NOT NULL,
  `num` int(11) NOT NULL,
  `total` int(10) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_activity_exchange_trades
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_exchange_trades`;
CREATE TABLE `ims_activity_exchange_trades` (
  `tid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `exid` int(10) unsigned NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`tid`),
  KEY `uniacid` (`uniacid`,`uid`,`exid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_activity_exchange_trades_shipping
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_exchange_trades_shipping`;
CREATE TABLE `ims_activity_exchange_trades_shipping` (
  `tid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `exid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `province` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `district` varchar(30) NOT NULL,
  `address` varchar(255) NOT NULL,
  `zipcode` varchar(6) NOT NULL,
  `mobile` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`tid`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_activity_modules
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_modules`;
CREATE TABLE `ims_activity_modules` (
  `mid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `exid` int(10) unsigned NOT NULL,
  `module` varchar(50) NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `available` int(10) unsigned NOT NULL,
  PRIMARY KEY (`mid`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `module` (`module`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_activity_modules_record
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_modules_record`;
CREATE TABLE `ims_activity_modules_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned NOT NULL,
  `num` tinyint(3) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_activity_stores
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_stores`;
CREATE TABLE `ims_activity_stores` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `business_name` varchar(50) NOT NULL,
  `branch_name` varchar(50) NOT NULL,
  `category` varchar(255) NOT NULL,
  `province` varchar(15) NOT NULL,
  `city` varchar(15) NOT NULL,
  `district` varchar(15) NOT NULL,
  `address` varchar(50) NOT NULL,
  `longitude` varchar(15) NOT NULL,
  `latitude` varchar(15) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `photo_list` varchar(10000) NOT NULL,
  `avg_price` int(10) unsigned NOT NULL,
  `open_time` varchar(50) NOT NULL,
  `recommend` varchar(255) NOT NULL,
  `special` varchar(255) NOT NULL,
  `introduction` varchar(255) NOT NULL,
  `sid` int(10) unsigned NOT NULL,
  `location_id` int(10) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `offset_type` tinyint(3) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `message` varchar(500) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_amouse_house
-- ----------------------------
DROP TABLE IF EXISTS `ims_amouse_house`;
CREATE TABLE `ims_amouse_house` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `title` varchar(25) NOT NULL COMMENT '标题',
  `price` varchar(100) NOT NULL COMMENT '租金总价',
  `square_price` varchar(100) NOT NULL COMMENT '每平方价格',
  `area` varchar(100) NOT NULL COMMENT '面积',
  `house_type` varchar(100) NOT NULL COMMENT '户型',
  `floor` varchar(100) NOT NULL COMMENT '楼层',
  `orientation` varchar(100) NOT NULL COMMENT '朝向',
  `type` varchar(2) NOT NULL COMMENT '0：出租；1：求租；2：出售/3：求购',
  `status` varchar(2) NOT NULL COMMENT '是否显示/审核',
  `recommed` int(1) NOT NULL COMMENT '推荐 0未推荐 1推荐',
  `contacts` varchar(100) NOT NULL COMMENT '联系人',
  `phone` varchar(13) NOT NULL COMMENT '联系电话',
  `introduction` text NOT NULL COMMENT '详细描述',
  `openid` varchar(25) NOT NULL COMMENT '微信OPENID',
  `createtime` int(10) NOT NULL,
  `thumb3` varchar(1000) NOT NULL DEFAULT '',
  `thumb4` varchar(1000) NOT NULL DEFAULT '',
  `thumb1` varchar(1000) NOT NULL DEFAULT '',
  `thumb2` varchar(1000) NOT NULL DEFAULT '',
  `place` varchar(1000) NOT NULL DEFAULT '',
  `lat` varchar(1000) NOT NULL DEFAULT '0.0000000000',
  `lng` varchar(1000) NOT NULL DEFAULT '0.0000000000',
  `location_p` varchar(1000) NOT NULL DEFAULT '',
  `location_c` varchar(1000) NOT NULL DEFAULT '',
  `location_a` varchar(1000) NOT NULL DEFAULT '',
  `brokerage` varchar(1000) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='租房出售';

-- ----------------------------
-- Table structure for ims_amouse_house_slide
-- ----------------------------
DROP TABLE IF EXISTS `ims_amouse_house_slide`;
CREATE TABLE `ims_amouse_house_slide` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `url` varchar(200) NOT NULL DEFAULT '',
  `slide` varchar(200) NOT NULL DEFAULT '',
  `listorder` int(10) unsigned NOT NULL DEFAULT '0',
  `isshow` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_amouse_newflats
-- ----------------------------
DROP TABLE IF EXISTS `ims_amouse_newflats`;
CREATE TABLE `ims_amouse_newflats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL COMMENT '姓名',
  `thumb` varchar(255) NOT NULL COMMENT '图片',
  `price` varchar(100) NOT NULL COMMENT '价格',
  `type` varchar(200) NOT NULL COMMENT '建筑类型',
  `years` varchar(100) NOT NULL COMMENT '产权年限',
  `wytype` varchar(100) NOT NULL COMMENT '物业类别',
  `cqtype` varchar(100) NOT NULL COMMENT '产权类型',
  `jzarea` varchar(100) NOT NULL COMMENT '建筑面积',
  `ratio` varchar(100) NOT NULL COMMENT '容积率',
  `floor_area` varchar(100) NOT NULL COMMENT '房屋面积',
  `afforestation` varchar(100) NOT NULL COMMENT '绿化率',
  `total` varchar(100) NOT NULL COMMENT '总户型',
  `door_area` varchar(100) NOT NULL COMMENT '户型面积',
  `road_transport` varchar(100) NOT NULL COMMENT '道路交通',
  `investors` varchar(100) NOT NULL COMMENT '投资商',
  `developers` varchar(100) NOT NULL COMMENT '开发商',
  `property_compay` varchar(100) NOT NULL COMMENT '物业公司',
  `propertypay` varchar(100) NOT NULL COMMENT '物业费',
  `features` varchar(100) NOT NULL COMMENT '楼盘特色',
  `sales_addres` varchar(100) NOT NULL COMMENT '售楼地址',
  `checkin_time` varchar(100) NOT NULL COMMENT '入住时间',
  `sales_status` varchar(100) NOT NULL COMMENT '销售状况',
  `average_price` varchar(100) NOT NULL COMMENT '均价',
  `discounted_costs` varchar(100) NOT NULL COMMENT '折扣价格',
  `payment` varchar(100) NOT NULL COMMENT '付款方式',
  `business` varchar(100) NOT NULL COMMENT '商业配套',
  `banks` varchar(100) NOT NULL COMMENT '银行',
  `trading_area` varchar(100) NOT NULL COMMENT '商圈',
  `park` varchar(100) NOT NULL COMMENT '公园',
  `hotel` varchar(100) NOT NULL COMMENT '酒店',
  `supermarket` varchar(100) NOT NULL COMMENT '超市',
  `humanities` varchar(100) NOT NULL COMMENT '人文自然景观',
  `supporting` varchar(100) NOT NULL COMMENT '社区内配套',
  `internal` varchar(100) NOT NULL COMMENT '内部配套',
  `parking_number` varchar(100) NOT NULL COMMENT '车位数',
  `base` varchar(100) NOT NULL COMMENT '基本参数',
  `equally` varchar(100) NOT NULL COMMENT '公摊系数',
  `surrounding` varchar(100) NOT NULL COMMENT '周边商业',
  `landscape` varchar(100) NOT NULL COMMENT '周边景观',
  `hospitals` varchar(100) NOT NULL COMMENT '周边医院',
  `school` varchar(100) NOT NULL COMMENT '周边学校',
  `traffic` varchar(100) NOT NULL COMMENT '交通',
  `construction` varchar(100) NOT NULL COMMENT '建筑施工单位',
  `design` varchar(100) NOT NULL COMMENT '规划设计单位',
  `salecom` varchar(100) NOT NULL COMMENT '销售公司',
  `address` varchar(255) NOT NULL COMMENT '销售公司所在位置图片',
  `introduction` text NOT NULL COMMENT '详细描述',
  `readcount` int(11) DEFAULT '0' COMMENT '阅读量',
  `openid` varchar(25) NOT NULL COMMENT '微信OPENID',
  `like` int(11) DEFAULT '0' COMMENT '点赞',
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_amouse_sysset
-- ----------------------------
DROP TABLE IF EXISTS `ims_amouse_sysset`;
CREATE TABLE `ims_amouse_sysset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `jjrmobile` varchar(13) NOT NULL COMMENT '手机',
  `broker` varchar(200) NOT NULL COMMENT '经纪人',
  `guanzhuUrl` varchar(255) DEFAULT '1' COMMENT '引导关注',
  `copyright` varchar(255) DEFAULT '' COMMENT '版权',
  `newflat_images` varchar(255) DEFAULT '' COMMENT '楼盘图片设置',
  `isoauth` int(10) DEFAULT '1' COMMENT '是否开启高级权限',
  `isshow` int(10) DEFAULT '1' COMMENT '是否只显示经纪人信息',
  `cnzz` varchar(255) DEFAULT '' COMMENT '统计',
  `appid` varchar(255) DEFAULT '',
  `appsecret` varchar(255) DEFAULT '',
  `appid_share` varchar(255) DEFAULT '',
  `appsecret_share` varchar(255) DEFAULT '',
  `defcity` varchar(1000) DEFAULT '中国',
  `nickname` varchar(500) DEFAULT NULL COMMENT '昵称',
  `openid` varchar(500) DEFAULT NULL COMMENT 'openid',
  `isadjuest` varchar(1) DEFAULT '1' COMMENT '是否审核',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_article_category
-- ----------------------------
DROP TABLE IF EXISTS `ims_article_category`;
CREATE TABLE `ims_article_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `type` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_article_news
-- ----------------------------
DROP TABLE IF EXISTS `ims_article_news`;
CREATE TABLE `ims_article_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cateid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `source` varchar(255) NOT NULL,
  `author` varchar(50) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `is_display` tinyint(3) unsigned NOT NULL,
  `is_show_home` tinyint(3) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `click` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`) USING BTREE,
  KEY `cateid` (`cateid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_article_notice
-- ----------------------------
DROP TABLE IF EXISTS `ims_article_notice`;
CREATE TABLE `ims_article_notice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cateid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `is_display` tinyint(3) unsigned NOT NULL,
  `is_show_home` tinyint(3) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `click` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`) USING BTREE,
  KEY `cateid` (`cateid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_article_reply
-- ----------------------------
DROP TABLE IF EXISTS `ims_article_reply`;
CREATE TABLE `ims_article_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `articleid` int(11) NOT NULL,
  `isfill` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_article_unread_notice
-- ----------------------------
DROP TABLE IF EXISTS `ims_article_unread_notice`;
CREATE TABLE `ims_article_unread_notice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `notice_id` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `is_new` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE,
  KEY `notice_id` (`notice_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_basic_reply
-- ----------------------------
DROP TABLE IF EXISTS `ims_basic_reply`;
CREATE TABLE `ims_basic_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `content` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_business
-- ----------------------------
DROP TABLE IF EXISTS `ims_business`;
CREATE TABLE `ims_business` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `content` varchar(1000) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `qq` varchar(15) NOT NULL,
  `province` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `dist` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `lng` varchar(10) NOT NULL,
  `lat` varchar(10) NOT NULL,
  `industry1` varchar(10) NOT NULL,
  `industry2` varchar(10) NOT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_lat_lng` (`lng`,`lat`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_cc_superdesk_shop_goods_cc_sku
-- ----------------------------
DROP TABLE IF EXISTS `ims_cc_superdesk_shop_goods_cc_sku`;
CREATE TABLE `ims_cc_superdesk_shop_goods_cc_sku` (
  `sku` int(11) NOT NULL DEFAULT '0',
  `num` bigint(21) NOT NULL DEFAULT '0',
  `ids` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_delete` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_circle_activitypicture
-- ----------------------------
DROP TABLE IF EXISTS `ims_circle_activitypicture`;
CREATE TABLE `ims_circle_activitypicture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `imageid` int(10) NOT NULL,
  `imagesrc` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_circle_activityrecord
-- ----------------------------
DROP TABLE IF EXISTS `ims_circle_activityrecord`;
CREATE TABLE `ims_circle_activityrecord` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `hppeapo` int(5) NOT NULL,
  `startdate` datetime NOT NULL,
  `address` varchar(255) NOT NULL,
  `participatedclass` varchar(20) NOT NULL,
  `type` int(5) NOT NULL COMMENT '活动状态（1：进行中2：正在参加3：已结束）',
  `openid` varchar(64) NOT NULL,
  `pointpraise` int(11) NOT NULL,
  `activityprofile` varchar(128) NOT NULL,
  `enddate` datetime NOT NULL,
  `initiator` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `uniacid` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_circle_cardlist
-- ----------------------------
DROP TABLE IF EXISTS `ims_circle_cardlist`;
CREATE TABLE `ims_circle_cardlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL,
  `content` text NOT NULL,
  `name` varchar(50) NOT NULL,
  `openid` varchar(64) NOT NULL,
  `creattime` timestamp NOT NULL,
  `imgurl` varchar(255) NOT NULL,
  `imgheight` varchar(50) NOT NULL,
  `imgwidth` varchar(50) NOT NULL,
  `imgthumb` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_circle_commentlist
-- ----------------------------
DROP TABLE IF EXISTS `ims_circle_commentlist`;
CREATE TABLE `ims_circle_commentlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(255) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `cid` int(11) NOT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `name` varchar(20) DEFAULT NULL,
  `fid` int(11) NOT NULL DEFAULT '0',
  `bename` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_circle_participaterecord
-- ----------------------------
DROP TABLE IF EXISTS `ims_circle_participaterecord`;
CREATE TABLE `ims_circle_participaterecord` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recordid` int(32) NOT NULL,
  `openid` varchar(100) NOT NULL,
  `playname` varchar(10) NOT NULL,
  `playtel` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_circle_user
-- ----------------------------
DROP TABLE IF EXISTS `ims_circle_user`;
CREATE TABLE `ims_circle_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(32) NOT NULL,
  `headimages` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sex` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `city` varchar(32) NOT NULL,
  `tel` varchar(32) DEFAULT NULL,
  `pwd` varchar(32) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `paytel` varchar(25) DEFAULT NULL,
  `cstid` varchar(50) DEFAULT NULL,
  `cstid2` varchar(50) DEFAULT NULL,
  `cstidstr` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_context_keycode
-- ----------------------------
DROP TABLE IF EXISTS `ims_context_keycode`;
CREATE TABLE `ims_context_keycode` (
  `rid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_core_attachment
-- ----------------------------
DROP TABLE IF EXISTS `ims_core_attachment`;
CREATE TABLE `ims_core_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `filename` varchar(255) NOT NULL,
  `attachment` varchar(255) NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7607 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_core_cache
-- ----------------------------
DROP TABLE IF EXISTS `ims_core_cache`;
CREATE TABLE `ims_core_cache` (
  `key` varchar(50) NOT NULL,
  `value` mediumtext NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_core_cron
-- ----------------------------
DROP TABLE IF EXISTS `ims_core_cron`;
CREATE TABLE `ims_core_cron` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cloudid` int(10) unsigned NOT NULL,
  `module` varchar(50) NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `filename` varchar(50) NOT NULL,
  `lastruntime` int(10) unsigned NOT NULL,
  `nextruntime` int(10) unsigned NOT NULL,
  `weekday` tinyint(3) NOT NULL,
  `day` tinyint(3) NOT NULL,
  `hour` tinyint(3) NOT NULL,
  `minute` varchar(255) NOT NULL,
  `extra` varchar(5000) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `createtime` (`createtime`) USING BTREE,
  KEY `nextruntime` (`nextruntime`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `cloudid` (`cloudid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_core_cron_record
-- ----------------------------
DROP TABLE IF EXISTS `ims_core_cron_record`;
CREATE TABLE `ims_core_cron_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `module` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `tid` int(10) unsigned NOT NULL,
  `note` varchar(500) NOT NULL,
  `tag` varchar(5000) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `tid` (`tid`) USING BTREE,
  KEY `module` (`module`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_core_menu
-- ----------------------------
DROP TABLE IF EXISTS `ims_core_menu`;
CREATE TABLE `ims_core_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `url` varchar(60) NOT NULL,
  `append_title` varchar(30) NOT NULL,
  `append_url` varchar(60) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `type` varchar(15) NOT NULL,
  `is_display` tinyint(3) unsigned NOT NULL,
  `is_system` tinyint(3) unsigned NOT NULL,
  `permission_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=104 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_core_paylog
-- ----------------------------
DROP TABLE IF EXISTS `ims_core_paylog`;
CREATE TABLE `ims_core_paylog` (
  `plid` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `openid` varchar(40) NOT NULL,
  `tid` varchar(64) NOT NULL,
  `fee` decimal(10,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `module` varchar(50) NOT NULL,
  `tag` varchar(2000) NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `is_usecard` tinyint(3) unsigned NOT NULL,
  `card_type` tinyint(3) unsigned NOT NULL,
  `card_id` varchar(50) NOT NULL,
  `card_fee` decimal(10,2) unsigned NOT NULL,
  `encrypt_code` varchar(100) NOT NULL,
  `uniontid` varchar(50) NOT NULL,
  `createtime` date NOT NULL,
  `eso_tag` varchar(2000) NOT NULL DEFAULT '',
  PRIMARY KEY (`plid`),
  KEY `idx_openid` (`openid`) USING BTREE,
  KEY `idx_tid` (`tid`) USING BTREE,
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=12245 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_core_performance
-- ----------------------------
DROP TABLE IF EXISTS `ims_core_performance`;
CREATE TABLE `ims_core_performance` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL,
  `runtime` varchar(10) NOT NULL,
  `runurl` varchar(512) NOT NULL,
  `runsql` varchar(512) NOT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1422 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_core_queue
-- ----------------------------
DROP TABLE IF EXISTS `ims_core_queue`;
CREATE TABLE `ims_core_queue` (
  `qid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `message` varchar(2000) NOT NULL,
  `params` varchar(1000) NOT NULL,
  `keyword` varchar(1000) NOT NULL,
  `response` varchar(2000) NOT NULL,
  `module` varchar(50) NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`qid`),
  KEY `uniacid` (`uniacid`,`acid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=23481 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_core_resource
-- ----------------------------
DROP TABLE IF EXISTS `ims_core_resource`;
CREATE TABLE `ims_core_resource` (
  `mid` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `media_id` varchar(100) NOT NULL,
  `trunk` int(10) unsigned NOT NULL,
  `type` varchar(10) NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`mid`),
  KEY `acid` (`uniacid`) USING BTREE,
  KEY `type` (`type`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_core_sendsms_log
-- ----------------------------
DROP TABLE IF EXISTS `ims_core_sendsms_log`;
CREATE TABLE `ims_core_sendsms_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `result` varchar(255) NOT NULL,
  `createtime` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_core_sessions
-- ----------------------------
DROP TABLE IF EXISTS `ims_core_sessions`;
CREATE TABLE `ims_core_sessions` (
  `sid` char(32) NOT NULL DEFAULT '',
  `uniacid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `data` varchar(500) NOT NULL,
  `expiretime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_core_settings
-- ----------------------------
DROP TABLE IF EXISTS `ims_core_settings`;
CREATE TABLE `ims_core_settings` (
  `key` varchar(200) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_core_wechats_attachment
-- ----------------------------
DROP TABLE IF EXISTS `ims_core_wechats_attachment`;
CREATE TABLE `ims_core_wechats_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `filename` varchar(255) NOT NULL,
  `attachment` varchar(255) NOT NULL,
  `media_id` varchar(255) NOT NULL,
  `width` int(10) unsigned NOT NULL,
  `height` int(10) unsigned NOT NULL,
  `type` varchar(15) NOT NULL,
  `model` varchar(25) NOT NULL,
  `tag` varchar(1000) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `media_id` (`media_id`) USING BTREE,
  KEY `acid` (`acid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_coupon
-- ----------------------------
DROP TABLE IF EXISTS `ims_coupon`;
CREATE TABLE `ims_coupon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `card_id` varchar(50) NOT NULL,
  `type` varchar(15) NOT NULL,
  `logo_url` varchar(150) NOT NULL,
  `code_type` tinyint(3) unsigned NOT NULL,
  `brand_name` varchar(15) NOT NULL,
  `title` varchar(15) NOT NULL,
  `sub_title` varchar(20) NOT NULL,
  `color` varchar(15) NOT NULL,
  `notice` varchar(15) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `date_info` varchar(200) NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  `location_id_list` varchar(1000) NOT NULL,
  `use_custom_code` tinyint(3) NOT NULL,
  `bind_openid` tinyint(3) unsigned NOT NULL,
  `can_share` tinyint(3) unsigned NOT NULL,
  `can_give_friend` tinyint(3) unsigned NOT NULL,
  `get_limit` tinyint(3) unsigned NOT NULL,
  `service_phone` varchar(20) NOT NULL,
  `extra` varchar(1000) NOT NULL,
  `source` varchar(20) NOT NULL,
  `url_name_type` varchar(20) NOT NULL,
  `custom_url` varchar(100) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `is_display` tinyint(3) unsigned NOT NULL,
  `promotion_url_name` varchar(10) NOT NULL,
  `promotion_url` varchar(100) NOT NULL,
  `promotion_url_sub_title` varchar(10) NOT NULL,
  `is_selfconsume` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`acid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_coupon_location
-- ----------------------------
DROP TABLE IF EXISTS `ims_coupon_location`;
CREATE TABLE `ims_coupon_location` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `location_id` int(10) unsigned NOT NULL,
  `business_name` varchar(50) NOT NULL,
  `branch_name` varchar(50) NOT NULL,
  `category` varchar(255) NOT NULL,
  `province` varchar(15) NOT NULL,
  `city` varchar(15) NOT NULL,
  `district` varchar(15) NOT NULL,
  `address` varchar(50) NOT NULL,
  `longitude` varchar(15) NOT NULL,
  `latitude` varchar(15) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `photo_list` varchar(10000) NOT NULL,
  `avg_price` int(10) unsigned NOT NULL,
  `open_time` varchar(50) NOT NULL,
  `recommend` varchar(255) NOT NULL,
  `special` varchar(255) NOT NULL,
  `introduction` varchar(255) NOT NULL,
  `offset_type` tinyint(3) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `message` varchar(255) NOT NULL,
  `sid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`acid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_coupon_modules
-- ----------------------------
DROP TABLE IF EXISTS `ims_coupon_modules`;
CREATE TABLE `ims_coupon_modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `card_id` varchar(50) NOT NULL,
  `module` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`) USING BTREE,
  KEY `card_id` (`card_id`) USING BTREE,
  KEY `uniacid` (`uniacid`,`acid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_coupon_record
-- ----------------------------
DROP TABLE IF EXISTS `ims_coupon_record`;
CREATE TABLE `ims_coupon_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `card_id` varchar(50) NOT NULL,
  `outer_id` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `friend_openid` varchar(50) NOT NULL,
  `givebyfriend` tinyint(3) unsigned NOT NULL,
  `code` varchar(50) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `usetime` int(10) unsigned NOT NULL,
  `status` tinyint(3) NOT NULL,
  `clerk_name` varchar(15) NOT NULL,
  `clerk_id` int(10) unsigned NOT NULL,
  `store_id` int(10) unsigned NOT NULL,
  `clerk_type` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`acid`) USING BTREE,
  KEY `outer_id` (`outer_id`) USING BTREE,
  KEY `card_id` (`card_id`) USING BTREE,
  KEY `hash` (`hash`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_coupon_setting
-- ----------------------------
DROP TABLE IF EXISTS `ims_coupon_setting`;
CREATE TABLE `ims_coupon_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) NOT NULL,
  `logourl` varchar(150) NOT NULL,
  `whitelist` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`acid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_cover_reply
-- ----------------------------
DROP TABLE IF EXISTS `ims_cover_reply`;
CREATE TABLE `ims_cover_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `multiid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `module` varchar(30) NOT NULL,
  `do` varchar(30) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_custom_reply
-- ----------------------------
DROP TABLE IF EXISTS `ims_custom_reply`;
CREATE TABLE `ims_custom_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `start1` int(10) NOT NULL DEFAULT '-1',
  `end1` int(10) NOT NULL DEFAULT '-1',
  `start2` int(10) NOT NULL DEFAULT '-1',
  `end2` int(10) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_elasticsearch_dictionary
-- ----------------------------
DROP TABLE IF EXISTS `ims_elasticsearch_dictionary`;
CREATE TABLE `ims_elasticsearch_dictionary` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uniacid` int(11) DEFAULT '0' COMMENT '所属帐号',
  `word` varchar(50) DEFAULT NULL COMMENT '词',
  `pcate` int(11) DEFAULT '0' COMMENT '一级分类ID',
  `ccate` int(11) DEFAULT '0' COMMENT '二级分类ID',
  `tcate` int(11) DEFAULT '0' COMMENT '三级分类ID',
  `cates` text COMMENT '多重分类数据集',
  `pcates` text COMMENT '一级多重分类',
  `ccates` text COMMENT '二级多重分类',
  `tcates` text COMMENT '三级多重分类',
  `enabled` tinyint(1) DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1982 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_elasticsearch_dictionary_category
-- ----------------------------
DROP TABLE IF EXISTS `ims_elasticsearch_dictionary_category`;
CREATE TABLE `ims_elasticsearch_dictionary_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uniacid` int(11) DEFAULT '0' COMMENT '所属帐号',
  `parentid` int(11) DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `name` varchar(50) DEFAULT NULL COMMENT '分类名称',
  `description` varchar(500) DEFAULT NULL COMMENT '分类介绍',
  `displayorder` tinyint(3) unsigned DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) DEFAULT '1' COMMENT '是否开启',
  `level` tinyint(3) DEFAULT NULL COMMENT '分类是在几级',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_hongapis
-- ----------------------------
DROP TABLE IF EXISTS `ims_hongapis`;
CREATE TABLE `ims_hongapis` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `openid` varchar(80) NOT NULL,
  `keywords` varchar(80) NOT NULL,
  `weid` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_hongapitype
-- ----------------------------
DROP TABLE IF EXISTS `ims_hongapitype`;
CREATE TABLE `ims_hongapitype` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `openid` varchar(80) CHARACTER SET utf8 NOT NULL,
  `weid` int(10) NOT NULL,
  `type` varchar(80) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for ims_images_reply
-- ----------------------------
DROP TABLE IF EXISTS `ims_images_reply`;
CREATE TABLE `ims_images_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `mediaid` varchar(255) NOT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_lonaking_activity_activity
-- ----------------------------
DROP TABLE IF EXISTS `ims_lonaking_activity_activity`;
CREATE TABLE `ims_lonaking_activity_activity` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uniacid` int(11) DEFAULT NULL COMMENT '公众号id',
  `name` varchar(255) DEFAULT '' COMMENT '活动标题',
  `admin_name` varchar(255) DEFAULT '' COMMENT '发布人姓名',
  `admin_pic` varchar(255) DEFAULT '' COMMENT '发布人头像',
  `start` date DEFAULT NULL COMMENT '开始时间',
  `end` date DEFAULT NULL COMMENT '结束时间',
  `address` varchar(255) DEFAULT '' COMMENT '地址',
  `enroll_stop` date DEFAULT NULL COMMENT '报名截止时间',
  `enroll_count` int(11) DEFAULT '0' COMMENT '报名人数',
  `enroll_limit` int(11) DEFAULT '0' COMMENT '限制报名人数',
  `content` text COMMENT '活动介绍',
  `click` int(11) DEFAULT '0' COMMENT '点击次数',
  `share` int(11) DEFAULT '0' COMMENT '分享次数',
  `share_logo` varchar(255) DEFAULT '' COMMENT '分享logo',
  `share_title` varchar(100) DEFAULT '' COMMENT '分享标题',
  `share_description` varchar(255) DEFAULT '' COMMENT '分享内容',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_lonaking_activity_enroll
-- ----------------------------
DROP TABLE IF EXISTS `ims_lonaking_activity_enroll`;
CREATE TABLE `ims_lonaking_activity_enroll` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uniacid` int(11) DEFAULT NULL COMMENT '公众号id',
  `activity_id` int(11) DEFAULT NULL COMMENT '活动id',
  `order_num` varchar(255) DEFAULT '' COMMENT '订单号',
  `openid` varchar(255) DEFAULT '' COMMENT 'openid',
  `pic` varchar(255) DEFAULT '' COMMENT '头像',
  `uid` int(11) DEFAULT NULL COMMENT '微擎uid',
  `name` varchar(255) DEFAULT '' COMMENT '姓名',
  `mobile` varchar(11) DEFAULT '' COMMENT '电话',
  `status` tinyint(1) DEFAULT '0' COMMENT '0 报名 1已经核销 2取消报名',
  `verificate_time` timestamp NULL DEFAULT NULL COMMENT '验证时间',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_lw_comments
-- ----------------------------
DROP TABLE IF EXISTS `ims_lw_comments`;
CREATE TABLE `ims_lw_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `parentid` int(10) unsigned NOT NULL DEFAULT '0',
  `openid` varchar(50) NOT NULL,
  `toUser` varchar(50) NOT NULL,
  `content` varchar(300) NOT NULL DEFAULT '',
  `createtime` varchar(100) NOT NULL,
  `nowColor` varchar(50) NOT NULL,
  `limit` tinyint(2) NOT NULL DEFAULT '0',
  `isok` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_lw_commentslike
-- ----------------------------
DROP TABLE IF EXISTS `ims_lw_commentslike`;
CREATE TABLE `ims_lw_commentslike` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `swnoId` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `createtime` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_lw_fans
-- ----------------------------
DROP TABLE IF EXISTS `ims_lw_fans`;
CREATE TABLE `ims_lw_fans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `openid` varchar(100) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `headimgurl` varchar(300) NOT NULL DEFAULT '',
  `createtime` varchar(100) NOT NULL,
  `isblack` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_lw_report
-- ----------------------------
DROP TABLE IF EXISTS `ims_lw_report`;
CREATE TABLE `ims_lw_report` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `swnoId` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `reporter` varchar(50) NOT NULL,
  `createtime` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_mc_card
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_card`;
CREATE TABLE `ims_mc_card` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `color` varchar(255) NOT NULL,
  `background` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `format` varchar(50) NOT NULL,
  `fields` varchar(1000) NOT NULL,
  `snpos` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `business` text NOT NULL,
  `description` varchar(512) NOT NULL,
  `format_type` tinyint(3) unsigned NOT NULL,
  `discount_type` tinyint(3) unsigned NOT NULL,
  `discount` varchar(3000) NOT NULL,
  `grant` varchar(200) NOT NULL,
  `grant_rate` int(10) unsigned NOT NULL,
  `offset_rate` int(10) unsigned NOT NULL,
  `offset_max` int(10) NOT NULL,
  `nums_status` tinyint(3) unsigned NOT NULL,
  `nums_text` varchar(15) NOT NULL,
  `nums` varchar(1000) NOT NULL,
  `times_status` tinyint(3) unsigned NOT NULL,
  `times_text` varchar(15) NOT NULL,
  `times` varchar(1000) NOT NULL,
  `params` longtext NOT NULL,
  `html` longtext NOT NULL,
  `recharge` varchar(500) NOT NULL,
  `recommend_status` tinyint(3) unsigned NOT NULL,
  `sign_status` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_mc_card_care
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_card_care`;
CREATE TABLE `ims_mc_card_care` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(30) NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `groupid` int(10) unsigned NOT NULL,
  `credit1` int(10) unsigned NOT NULL,
  `credit2` int(10) unsigned NOT NULL,
  `couponid` int(10) unsigned NOT NULL,
  `granttime` int(10) unsigned NOT NULL,
  `days` int(10) unsigned NOT NULL,
  `time` tinyint(3) unsigned NOT NULL,
  `show_in_card` tinyint(3) unsigned NOT NULL,
  `content` varchar(1000) NOT NULL,
  `sms_notice` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_mc_card_credit_set
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_card_credit_set`;
CREATE TABLE `ims_mc_card_credit_set` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `sign` varchar(1000) NOT NULL,
  `share` varchar(500) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_mc_card_members
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_card_members`;
CREATE TABLE `ims_mc_card_members` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) DEFAULT NULL,
  `cid` int(10) NOT NULL,
  `cardsn` varchar(20) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `nums` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_mc_card_notices
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_card_notices`;
CREATE TABLE `ims_mc_card_notices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `title` varchar(30) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `groupid` int(10) unsigned NOT NULL,
  `content` text NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_mc_card_notices_unread
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_card_notices_unread`;
CREATE TABLE `ims_mc_card_notices_unread` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `notice_id` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `is_new` tinyint(3) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `notice_id` (`notice_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_mc_card_recommend
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_card_recommend`;
CREATE TABLE `ims_mc_card_recommend` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(30) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_mc_card_record
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_card_record`;
CREATE TABLE `ims_mc_card_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `type` varchar(15) NOT NULL,
  `model` tinyint(3) unsigned NOT NULL,
  `fee` decimal(10,2) unsigned NOT NULL,
  `tag` varchar(10) NOT NULL,
  `note` varchar(255) NOT NULL,
  `remark` varchar(200) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `addtime` (`addtime`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_mc_card_sign_record
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_card_sign_record`;
CREATE TABLE `ims_mc_card_sign_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `credit` int(10) unsigned NOT NULL,
  `is_grant` tinyint(3) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_mc_cash_record
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_cash_record`;
CREATE TABLE `ims_mc_cash_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `clerk_id` int(10) unsigned NOT NULL,
  `store_id` int(10) unsigned NOT NULL,
  `clerk_type` tinyint(3) unsigned NOT NULL,
  `fee` decimal(10,2) unsigned NOT NULL,
  `final_fee` decimal(10,2) unsigned NOT NULL,
  `credit1` int(10) unsigned NOT NULL,
  `credit1_fee` decimal(10,2) unsigned NOT NULL,
  `credit2` decimal(10,2) unsigned NOT NULL,
  `cash` decimal(10,2) unsigned NOT NULL,
  `return_cash` decimal(10,2) unsigned NOT NULL,
  `final_cash` decimal(10,2) unsigned NOT NULL,
  `remark` varchar(255) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_mc_chats_record
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_chats_record`;
CREATE TABLE `ims_mc_chats_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `flag` tinyint(3) unsigned NOT NULL,
  `openid` varchar(32) NOT NULL,
  `msgtype` varchar(15) NOT NULL,
  `content` varchar(10000) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`acid`) USING BTREE,
  KEY `openid` (`openid`) USING BTREE,
  KEY `createtime` (`createtime`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_mc_credits_recharge
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_credits_recharge`;
CREATE TABLE `ims_mc_credits_recharge` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `tid` varchar(64) NOT NULL,
  `transid` varchar(30) NOT NULL,
  `fee` varchar(10) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `type` varchar(15) NOT NULL,
  `tag` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid_uid` (`uniacid`,`uid`) USING BTREE,
  KEY `idx_tid` (`tid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_mc_credits_record
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_credits_record`;
CREATE TABLE `ims_mc_credits_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `uniacid` int(11) NOT NULL,
  `credittype` varchar(10) NOT NULL,
  `num` decimal(10,2) NOT NULL,
  `operator` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `remark` varchar(200) NOT NULL,
  `module` varchar(30) NOT NULL,
  `clerk_id` int(10) unsigned NOT NULL,
  `store_id` int(10) unsigned NOT NULL,
  `clerk_type` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=185 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_mc_fans_groups
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_fans_groups`;
CREATE TABLE `ims_mc_fans_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `groups` varchar(10000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_mc_groups
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_groups`;
CREATE TABLE `ims_mc_groups` (
  `groupid` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `title` varchar(20) NOT NULL,
  `orderlist` tinyint(4) unsigned NOT NULL,
  `isdefault` tinyint(4) NOT NULL,
  `credit` int(10) unsigned NOT NULL,
  PRIMARY KEY (`groupid`),
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_mc_handsel
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_handsel`;
CREATE TABLE `ims_mc_handsel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `touid` int(10) unsigned NOT NULL,
  `fromuid` varchar(32) NOT NULL,
  `module` varchar(30) NOT NULL,
  `sign` varchar(100) NOT NULL,
  `action` varchar(20) NOT NULL,
  `credit_value` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`touid`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_mc_mapping_fans
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_mapping_fans`;
CREATE TABLE `ims_mc_mapping_fans` (
  `fanid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `acid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `salt` char(8) NOT NULL,
  `follow` tinyint(1) unsigned NOT NULL,
  `followtime` int(10) unsigned NOT NULL,
  `unfollowtime` int(10) unsigned NOT NULL,
  `tag` varchar(1000) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `groupid` int(10) unsigned NOT NULL,
  `updatetime` int(10) unsigned DEFAULT NULL,
  `unionid` varchar(64) NOT NULL,
  PRIMARY KEY (`fanid`),
  KEY `acid` (`acid`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `openid` (`openid`) USING BTREE,
  KEY `updatetime` (`updatetime`) USING BTREE,
  KEY `nickname` (`nickname`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=10651 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_mc_mapping_ucenter
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_mapping_ucenter`;
CREATE TABLE `ims_mc_mapping_ucenter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `centeruid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_mc_mass_record
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_mass_record`;
CREATE TABLE `ims_mc_mass_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `groupname` varchar(50) NOT NULL,
  `fansnum` int(10) unsigned NOT NULL,
  `msgtype` varchar(10) NOT NULL,
  `content` varchar(10000) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `group` int(10) NOT NULL,
  `attach_id` int(10) unsigned NOT NULL,
  `media_id` varchar(100) NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `cron_id` int(10) unsigned NOT NULL,
  `sendtime` int(10) unsigned NOT NULL,
  `finalsendtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`acid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_mc_member_address
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_member_address`;
CREATE TABLE `ims_mc_member_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(50) unsigned NOT NULL,
  `username` varchar(20) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `zipcode` varchar(6) NOT NULL,
  `province` varchar(32) NOT NULL,
  `city` varchar(32) NOT NULL,
  `district` varchar(32) NOT NULL,
  `address` varchar(512) NOT NULL,
  `isdefault` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uinacid` (`uniacid`) USING BTREE,
  KEY `idx_uid` (`uid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_mc_member_fields
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_member_fields`;
CREATE TABLE `ims_mc_member_fields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `fieldid` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `available` tinyint(1) NOT NULL,
  `displayorder` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_fieldid` (`fieldid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=397 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_mc_members
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_members`;
CREATE TABLE `ims_mc_members` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL,
  `salt` varchar(8) NOT NULL,
  `groupid` int(11) NOT NULL,
  `credit1` decimal(10,2) unsigned NOT NULL,
  `credit2` decimal(10,2) unsigned NOT NULL,
  `credit3` decimal(10,2) unsigned NOT NULL,
  `credit4` decimal(10,2) unsigned NOT NULL,
  `credit5` decimal(10,2) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `realname` varchar(10) NOT NULL,
  `nickname` varchar(20) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `qq` varchar(15) NOT NULL,
  `vip` tinyint(3) unsigned NOT NULL,
  `gender` tinyint(1) NOT NULL,
  `birthyear` smallint(6) unsigned NOT NULL,
  `birthmonth` tinyint(3) unsigned NOT NULL,
  `birthday` tinyint(3) unsigned NOT NULL,
  `constellation` varchar(10) NOT NULL,
  `zodiac` varchar(5) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `idcard` varchar(30) NOT NULL,
  `studentid` varchar(50) NOT NULL,
  `grade` varchar(10) NOT NULL,
  `address` varchar(255) NOT NULL,
  `zipcode` varchar(10) NOT NULL,
  `nationality` varchar(30) NOT NULL,
  `resideprovince` varchar(30) NOT NULL,
  `residecity` varchar(30) NOT NULL,
  `residedist` varchar(30) NOT NULL,
  `graduateschool` varchar(50) NOT NULL,
  `company` varchar(50) NOT NULL,
  `education` varchar(10) NOT NULL,
  `occupation` varchar(30) NOT NULL,
  `position` varchar(30) NOT NULL,
  `revenue` varchar(10) NOT NULL,
  `affectivestatus` varchar(30) NOT NULL,
  `lookingfor` varchar(255) NOT NULL,
  `bloodtype` varchar(5) NOT NULL,
  `height` varchar(5) NOT NULL,
  `weight` varchar(5) NOT NULL,
  `alipay` varchar(30) NOT NULL,
  `msn` varchar(30) NOT NULL,
  `taobao` varchar(30) NOT NULL,
  `site` varchar(30) NOT NULL,
  `bio` text NOT NULL,
  `interest` text NOT NULL,
  `credit6` decimal(10,2) NOT NULL,
  `second_avatar` varchar(255) NOT NULL,
  PRIMARY KEY (`uid`),
  KEY `groupid` (`groupid`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4343 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_mc_oauth_fans
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_oauth_fans`;
CREATE TABLE `ims_mc_oauth_fans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `oauth_openid` varchar(50) NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_oauthopenid_acid` (`oauth_openid`,`acid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_meeting_record
-- ----------------------------
DROP TABLE IF EXISTS `ims_meeting_record`;
CREATE TABLE `ims_meeting_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `openid` varchar(100) NOT NULL,
  `apm` varchar(32) DEFAULT 'am',
  `fonid` int(10) NOT NULL,
  `images` varchar(255) NOT NULL,
  `guid` varchar(100) NOT NULL,
  `starttime` varchar(25) DEFAULT '',
  `endtime` varchar(25) DEFAULT '',
  `appointmentsname` varchar(25) DEFAULT '',
  `appointmentstel` varchar(50) DEFAULT '',
  `appointmentspeople` int(20) NOT NULL DEFAULT '0',
  `appointmentsequipment` varchar(255) DEFAULT NULL,
  `isagree` int(1) NOT NULL DEFAULT '0',
  `randcode` varchar(4) NOT NULL,
  `uniacid` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_meeting_room
-- ----------------------------
DROP TABLE IF EXISTS `ims_meeting_room`;
CREATE TABLE `ims_meeting_room` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roomnumber` int(10) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `peaponum` int(20) NOT NULL,
  `appointmenttype` int(1) NOT NULL DEFAULT '0',
  `roomname` varchar(25) DEFAULT NULL,
  `remark` text NOT NULL,
  `uniacid` int(10) NOT NULL,
  `equipment` varchar(255) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_menu_event
-- ----------------------------
DROP TABLE IF EXISTS `ims_menu_event`;
CREATE TABLE `ims_menu_event` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `keyword` varchar(30) NOT NULL,
  `type` varchar(30) NOT NULL,
  `picmd5` varchar(32) NOT NULL,
  `openid` varchar(128) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `picmd5` (`picmd5`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_mobilenumber
-- ----------------------------
DROP TABLE IF EXISTS `ims_mobilenumber`;
CREATE TABLE `ims_mobilenumber` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL,
  `dateline` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_modules
-- ----------------------------
DROP TABLE IF EXISTS `ims_modules`;
CREATE TABLE `ims_modules` (
  `mid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `version` varchar(10) NOT NULL,
  `ability` varchar(500) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `author` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `settings` tinyint(1) NOT NULL,
  `subscribes` varchar(500) NOT NULL,
  `handles` varchar(500) NOT NULL,
  `isrulefields` tinyint(1) NOT NULL,
  `issystem` tinyint(1) unsigned NOT NULL,
  `issolution` tinyint(1) unsigned NOT NULL,
  `target` int(10) unsigned NOT NULL,
  `iscard` tinyint(3) unsigned NOT NULL,
  `permissions` varchar(5000) NOT NULL,
  PRIMARY KEY (`mid`),
  KEY `idx_name` (`name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=288 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_modules_bindings
-- ----------------------------
DROP TABLE IF EXISTS `ims_modules_bindings`;
CREATE TABLE `ims_modules_bindings` (
  `eid` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(30) NOT NULL,
  `entry` varchar(10) NOT NULL,
  `call` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `do` varchar(64) NOT NULL,
  `state` varchar(200) NOT NULL,
  `direct` int(11) NOT NULL,
  `url` varchar(100) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `displayorder` tinyint(255) unsigned NOT NULL,
  PRIMARY KEY (`eid`),
  KEY `idx_module` (`module`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1713 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_music_reply
-- ----------------------------
DROP TABLE IF EXISTS `ims_music_reply`;
CREATE TABLE `ims_music_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `url` varchar(300) NOT NULL,
  `hqurl` varchar(300) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_n1ce_shake_acts
-- ----------------------------
DROP TABLE IF EXISTS `ims_n1ce_shake_acts`;
CREATE TABLE `ims_n1ce_shake_acts` (
  `act_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uniacid` int(11) unsigned NOT NULL,
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '抽奖活动名称',
  `desc` varchar(20) NOT NULL DEFAULT '' COMMENT '抽奖活动描述',
  `onoff` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '抽奖开关',
  `begin_time` int(10) unsigned NOT NULL COMMENT '抽奖活动开始时间',
  `expire_time` int(10) unsigned NOT NULL COMMENT '抽奖活动结束时间',
  `total` int(10) unsigned NOT NULL COMMENT '红包总数',
  `usebefore` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '已添加红包统计',
  `jump_url` varchar(120) NOT NULL DEFAULT '' COMMENT '跳转到',
  `lottery_id` varchar(80) NOT NULL DEFAULT '' COMMENT '生成的红包活动id',
  `page_id` int(11) unsigned NOT NULL COMMENT '生成的模板页面ID',
  `logo_url` varchar(255) NOT NULL DEFAULT '' COMMENT 'logo_url',
  PRIMARY KEY (`act_id`),
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_n1ce_shake_before
-- ----------------------------
DROP TABLE IF EXISTS `ims_n1ce_shake_before`;
CREATE TABLE `ims_n1ce_shake_before` (
  `before_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uniacid` int(10) unsigned NOT NULL COMMENT 'uniacid',
  `mch_billno` varchar(32) NOT NULL DEFAULT '' COMMENT '商户订单号',
  `send_name` varchar(32) NOT NULL DEFAULT '' COMMENT '商户名称',
  `hb_type` varchar(16) NOT NULL DEFAULT 'NORMAL' COMMENT '红包类型',
  `total_amount` int(11) unsigned NOT NULL COMMENT '总金额',
  `total_num` int(11) unsigned NOT NULL COMMENT '红包发放总人数(普通红包填1，裂变红包必须大于1)',
  `amt_type` varchar(32) NOT NULL DEFAULT '' COMMENT '红包金额设置方式',
  `wishing` varchar(16) NOT NULL DEFAULT '' COMMENT '红包祝福语',
  `act_name` varchar(32) NOT NULL DEFAULT '' COMMENT '活动名称',
  `remark` varchar(32) NOT NULL DEFAULT '' COMMENT '备注',
  `risk_cntl` varchar(32) NOT NULL DEFAULT '' COMMENT '风控设置',
  `result_code` varchar(16) NOT NULL DEFAULT '' COMMENT '业务结果',
  `err_code` varchar(32) NOT NULL DEFAULT '' COMMENT '错误代码',
  `ticket` varchar(200) NOT NULL DEFAULT '' COMMENT 'Ticket',
  `detail_id` varchar(80) NOT NULL DEFAULT '' COMMENT '红包订单号',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '使用状态 0不可用 1可用',
  `expire` int(10) unsigned NOT NULL COMMENT '过期时间',
  `act_id` int(11) unsigned NOT NULL COMMENT '所属活动',
  `created` int(11) NOT NULL COMMENT '申请时间',
  `openid` varchar(80) NOT NULL,
  PRIMARY KEY (`before_id`),
  KEY `uniacid` (`uniacid`,`act_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=532 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_nearby_shop
-- ----------------------------
DROP TABLE IF EXISTS `ims_nearby_shop`;
CREATE TABLE `ims_nearby_shop` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `multiid` int(10) unsigned NOT NULL,
  `section` tinyint(4) NOT NULL,
  `module` varchar(50) NOT NULL,
  `displayorder` smallint(5) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `position` tinyint(4) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(500) NOT NULL,
  `css` varchar(1000) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `categoryid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `multiid` (`multiid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=69 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_news_reply
-- ----------------------------
DROP TABLE IF EXISTS `ims_news_reply`;
CREATE TABLE `ims_news_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `parentid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `url` varchar(255) NOT NULL,
  `displayorder` int(10) NOT NULL,
  `incontent` tinyint(1) NOT NULL,
  `author` varchar(64) NOT NULL,
  `createtime` int(10) NOT NULL,
  `parent_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=137 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_nsign_add
-- ----------------------------
DROP TABLE IF EXISTS `ims_nsign_add`;
CREATE TABLE `ims_nsign_add` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `shop` text NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `thumb` text NOT NULL,
  `content` text NOT NULL,
  `type` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_nsign_prize
-- ----------------------------
DROP TABLE IF EXISTS `ims_nsign_prize`;
CREATE TABLE `ims_nsign_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `name` text NOT NULL,
  `type` text NOT NULL,
  `award` text NOT NULL,
  `time` int(11) NOT NULL,
  `num` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_nsign_record
-- ----------------------------
DROP TABLE IF EXISTS `ims_nsign_record`;
CREATE TABLE `ims_nsign_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `username` text NOT NULL,
  `today_rank` int(11) NOT NULL,
  `sign_time` int(11) NOT NULL,
  `last_sign_time` int(11) NOT NULL,
  `continue_sign_days` int(11) NOT NULL,
  `maxcontinue_sign_days` int(11) NOT NULL,
  `total_sign_num` int(11) NOT NULL,
  `maxtotal_sign_num` int(11) NOT NULL,
  `first_sign_days` int(11) NOT NULL,
  `maxfirst_sign_days` int(11) NOT NULL,
  `credit` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=366 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_nsign_reply
-- ----------------------------
DROP TABLE IF EXISTS `ims_nsign_reply`;
CREATE TABLE `ims_nsign_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `title` text NOT NULL,
  `picture` text NOT NULL,
  `description` text NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_parking_order
-- ----------------------------
DROP TABLE IF EXISTS `ims_parking_order`;
CREATE TABLE `ims_parking_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ordernum` varchar(32) NOT NULL,
  `date` datetime NOT NULL,
  `num` varchar(11) NOT NULL DEFAULT '0',
  `money` varchar(11) NOT NULL,
  `type` int(11) NOT NULL,
  `openid` varchar(32) NOT NULL,
  `uniacid` int(10) NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL DEFAULT '0',
  `updatetime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=239 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_parking_reserve
-- ----------------------------
DROP TABLE IF EXISTS `ims_parking_reserve`;
CREATE TABLE `ims_parking_reserve` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `tel` varchar(100) NOT NULL,
  `uniacid` int(10) NOT NULL,
  `updatetime` int(10) NOT NULL,
  `createtime` int(10) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `plate` varchar(50) NOT NULL,
  `reservetime` int(10) NOT NULL,
  `type` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_profile_fields
-- ----------------------------
DROP TABLE IF EXISTS `ims_profile_fields`;
CREATE TABLE `ims_profile_fields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `field` varchar(255) NOT NULL,
  `available` tinyint(1) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `displayorder` smallint(6) NOT NULL,
  `required` tinyint(1) NOT NULL,
  `unchangeable` tinyint(1) NOT NULL,
  `showinregister` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_property_express
-- ----------------------------
DROP TABLE IF EXISTS `ims_property_express`;
CREATE TABLE `ims_property_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booktime` varchar(15) NOT NULL,
  `name` varchar(20) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `state` varchar(10) NOT NULL DEFAULT '未处理',
  `address` varchar(10) NOT NULL,
  `cstid` varchar(30) NOT NULL,
  `uniacid` int(10) NOT NULL,
  `createtime` int(10) NOT NULL,
  `updatetime` int(10) NOT NULL,
  `deleted` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_property_home
-- ----------------------------
DROP TABLE IF EXISTS `ims_property_home`;
CREATE TABLE `ims_property_home` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booktime` varchar(15) NOT NULL,
  `address` varchar(20) NOT NULL,
  `cstid` varchar(30) NOT NULL,
  `state` varchar(10) NOT NULL DEFAULT '未处理',
  `name` varchar(30) NOT NULL,
  `tel` varchar(30) NOT NULL,
  `uniacid` int(10) NOT NULL,
  `createtime` int(10) NOT NULL,
  `updatetime` int(10) NOT NULL,
  `deleted` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_property_order
-- ----------------------------
DROP TABLE IF EXISTS `ims_property_order`;
CREATE TABLE `ims_property_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ordernum` varchar(32) NOT NULL,
  `date` datetime DEFAULT NULL,
  `LFMoney` varchar(11) NOT NULL DEFAULT '0',
  `payMan` varchar(100) NOT NULL,
  `resName` varchar(100) NOT NULL,
  `RevMoney` varchar(11) NOT NULL DEFAULT '0',
  `allprice` varchar(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `openid` varchar(32) DEFAULT NULL,
  `payid` varchar(50) NOT NULL,
  `projectid` varchar(5) NOT NULL,
  `uniacid` int(10) NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL DEFAULT '0',
  `updatetime` int(10) NOT NULL DEFAULT '0',
  `IpItemName` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=806 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_property_sy_report
-- ----------------------------
DROP TABLE IF EXISTS `ims_property_sy_report`;
CREATE TABLE `ims_property_sy_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `OrgID` varchar(30) NOT NULL,
  `CstID` varchar(50) NOT NULL,
  `WOID` varchar(50) NOT NULL,
  `WONoBasicID` varchar(50) NOT NULL DEFAULT '未处理',
  `WONo` varchar(50) NOT NULL,
  `Elements` varchar(50) NOT NULL,
  `CallPhone` varchar(50) DEFAULT NULL,
  `OrgName` varchar(50) DEFAULT NULL,
  `uniacid` int(10) NOT NULL,
  `createtime` int(10) NOT NULL,
  `updatetime` int(10) NOT NULL,
  `deleted` int(10) NOT NULL,
  `firstImg` varchar(255) NOT NULL,
  `secondImg` varchar(255) NOT NULL,
  `openid` varchar(100) NOT NULL,
  `reportMan` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_property_water
-- ----------------------------
DROP TABLE IF EXISTS `ims_property_water`;
CREATE TABLE `ims_property_water` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booknum` int(10) NOT NULL,
  `state` varchar(10) NOT NULL DEFAULT '未处理',
  `booktime` int(10) NOT NULL,
  `address` varchar(20) NOT NULL,
  `cstid` varchar(20) NOT NULL,
  `uniacid` int(10) NOT NULL,
  `createtime` int(10) NOT NULL,
  `updatetime` int(10) NOT NULL,
  `deleted` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_qrcode
-- ----------------------------
DROP TABLE IF EXISTS `ims_qrcode`;
CREATE TABLE `ims_qrcode` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `qrcid` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `model` tinyint(1) unsigned NOT NULL,
  `ticket` varchar(250) NOT NULL,
  `expire` int(10) unsigned NOT NULL,
  `subnum` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `type` varchar(10) NOT NULL,
  `extra` int(10) unsigned NOT NULL,
  `scene_str` varchar(64) NOT NULL,
  `url` varchar(80) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_qrcid` (`qrcid`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_qrcode_stat
-- ----------------------------
DROP TABLE IF EXISTS `ims_qrcode_stat`;
CREATE TABLE `ims_qrcode_stat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `qid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `qrcid` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `scene_str` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_remark_joiner
-- ----------------------------
DROP TABLE IF EXISTS `ims_remark_joiner`;
CREATE TABLE `ims_remark_joiner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `tel` varchar(50) NOT NULL,
  `company` varchar(50) NOT NULL,
  `openid` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for ims_research
-- ----------------------------
DROP TABLE IF EXISTS `ims_research`;
CREATE TABLE `ims_research` (
  `reid` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL,
  `content` text NOT NULL,
  `information` varchar(500) NOT NULL DEFAULT '',
  `thumb` varchar(200) NOT NULL DEFAULT '',
  `inhome` tinyint(4) NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL DEFAULT '0',
  `starttime` int(10) NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `pretotal` int(10) unsigned NOT NULL DEFAULT '1',
  `noticeemail` varchar(50) NOT NULL DEFAULT '',
  `alltotal` int(10) unsigned NOT NULL DEFAULT '100' COMMENT '预约总人数',
  `uyan` int(20) NOT NULL,
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '通知手机号码',
  PRIMARY KEY (`reid`),
  KEY `weid` (`weid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_research_data
-- ----------------------------
DROP TABLE IF EXISTS `ims_research_data`;
CREATE TABLE `ims_research_data` (
  `redid` bigint(20) NOT NULL AUTO_INCREMENT,
  `reid` int(11) NOT NULL,
  `rerid` int(11) NOT NULL,
  `refid` int(11) NOT NULL,
  `data` varchar(800) NOT NULL,
  PRIMARY KEY (`redid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_research_fields
-- ----------------------------
DROP TABLE IF EXISTS `ims_research_fields`;
CREATE TABLE `ims_research_fields` (
  `refid` int(11) NOT NULL AUTO_INCREMENT,
  `reid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `essential` tinyint(4) NOT NULL DEFAULT '0',
  `bind` varchar(30) NOT NULL DEFAULT '',
  `value` varchar(300) NOT NULL DEFAULT '',
  `description` varchar(500) NOT NULL DEFAULT '',
  `displayorder` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`refid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_research_order
-- ----------------------------
DROP TABLE IF EXISTS `ims_research_order`;
CREATE TABLE `ims_research_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `rerid` int(11) DEFAULT NULL,
  `title` varchar(50) NOT NULL,
  `mobile` bigint(50) NOT NULL,
  `trans_id` varchar(255) DEFAULT NULL,
  `order_sn` varchar(20) NOT NULL,
  `price` decimal(11,2) DEFAULT '0.00',
  `status` tinyint(4) NOT NULL COMMENT '0已完成，1等待支付',
  `pay_type` tinyint(11) unsigned NOT NULL COMMENT '支付类型',
  `pay_pattern` int(1) DEFAULT '1' COMMENT '支付方式 1-在线付款，2-货到付款',
  `other` varchar(100) NOT NULL DEFAULT '',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_sn_uniacid` (`order_sn`,`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_research_reply
-- ----------------------------
DROP TABLE IF EXISTS `ims_research_reply`;
CREATE TABLE `ims_research_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `reid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_research_rows
-- ----------------------------
DROP TABLE IF EXISTS `ims_research_rows`;
CREATE TABLE `ims_research_rows` (
  `rerid` int(11) NOT NULL AUTO_INCREMENT,
  `reid` int(11) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `createtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rerid`),
  KEY `reid` (`reid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_reservation_appointment
-- ----------------------------
DROP TABLE IF EXISTS `ims_reservation_appointment`;
CREATE TABLE `ims_reservation_appointment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appoinid` varchar(100) NOT NULL,
  `peaponum` int(5) NOT NULL,
  `date` datetime NOT NULL,
  `seatnumber` varchar(10) DEFAULT NULL,
  `qrcode` varchar(100) DEFAULT NULL,
  `guid` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=298 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_reservation_conferencereservation
-- ----------------------------
DROP TABLE IF EXISTS `ims_reservation_conferencereservation`;
CREATE TABLE `ims_reservation_conferencereservation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roomnumber` int(10) NOT NULL,
  `images` varchar(255) NOT NULL,
  `peaponum` int(20) NOT NULL,
  `appointmenttype` int(1) NOT NULL,
  `roomname` varchar(25) DEFAULT NULL,
  `remark` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_reservation_meetinglist
-- ----------------------------
DROP TABLE IF EXISTS `ims_reservation_meetinglist`;
CREATE TABLE `ims_reservation_meetinglist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `openid` varchar(100) NOT NULL,
  `apm` varchar(32) DEFAULT 'am',
  `fonid` int(10) NOT NULL,
  `images` varchar(255) DEFAULT NULL,
  `guid` varchar(100) DEFAULT NULL,
  `starttime` varchar(25) DEFAULT '',
  `endtime` varchar(25) DEFAULT '',
  `appointmentsname` varchar(25) DEFAULT '',
  `appointmentstel` varchar(50) DEFAULT '',
  `appointmentspeople` int(20) NOT NULL DEFAULT '0',
  `appointmentsequipment` varchar(255) DEFAULT NULL,
  `isagree` int(1) NOT NULL DEFAULT '0',
  `randcode` varchar(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_rule
-- ----------------------------
DROP TABLE IF EXISTS `ims_rule`;
CREATE TABLE `ims_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `module` varchar(50) NOT NULL,
  `displayorder` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=219 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_rule_keyword
-- ----------------------------
DROP TABLE IF EXISTS `ims_rule_keyword`;
CREATE TABLE `ims_rule_keyword` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `module` varchar(50) NOT NULL,
  `content` varchar(255) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_content` (`content`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=332 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_site_article
-- ----------------------------
DROP TABLE IF EXISTS `ims_site_article`;
CREATE TABLE `ims_site_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `kid` int(10) unsigned NOT NULL,
  `iscommend` tinyint(1) NOT NULL,
  `ishot` tinyint(1) unsigned NOT NULL,
  `pcate` int(10) unsigned NOT NULL,
  `ccate` int(10) unsigned NOT NULL,
  `template` varchar(300) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `source` varchar(255) NOT NULL,
  `author` varchar(50) NOT NULL,
  `displayorder` int(10) unsigned NOT NULL,
  `linkurl` varchar(500) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `type` varchar(10) NOT NULL,
  `credit` varchar(255) NOT NULL,
  `incontent` tinyint(1) NOT NULL,
  `click` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_iscommend` (`iscommend`) USING BTREE,
  KEY `idx_ishot` (`ishot`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=99 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_site_category
-- ----------------------------
DROP TABLE IF EXISTS `ims_site_category`;
CREATE TABLE `ims_site_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `nid` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `parentid` int(10) unsigned NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL,
  `icon` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `templatefile` varchar(300) NOT NULL,
  `styleid` int(10) unsigned NOT NULL,
  `linkurl` varchar(500) NOT NULL,
  `ishomepage` tinyint(1) NOT NULL,
  `icontype` tinyint(1) unsigned NOT NULL,
  `css` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_site_multi
-- ----------------------------
DROP TABLE IF EXISTS `ims_site_multi`;
CREATE TABLE `ims_site_multi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(30) NOT NULL,
  `styleid` int(10) unsigned NOT NULL,
  `site_info` text NOT NULL,
  `quickmenu` varchar(2000) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `bindhost` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `bindhost` (`bindhost`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_site_nav
-- ----------------------------
DROP TABLE IF EXISTS `ims_site_nav`;
CREATE TABLE `ims_site_nav` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `multiid` int(10) unsigned NOT NULL,
  `section` tinyint(4) NOT NULL,
  `module` varchar(50) NOT NULL,
  `displayorder` smallint(5) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `position` tinyint(4) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(500) NOT NULL,
  `css` varchar(1000) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `categoryid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `multiid` (`multiid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=184 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_site_page
-- ----------------------------
DROP TABLE IF EXISTS `ims_site_page`;
CREATE TABLE `ims_site_page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `multiid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `params` longtext NOT NULL,
  `html` longtext NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `multiid` (`multiid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_site_slide
-- ----------------------------
DROP TABLE IF EXISTS `ims_site_slide`;
CREATE TABLE `ims_site_slide` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `displayorder` tinyint(4) NOT NULL,
  `multiid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_site_styles
-- ----------------------------
DROP TABLE IF EXISTS `ims_site_styles`;
CREATE TABLE `ims_site_styles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `templateid` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_site_styles_vars
-- ----------------------------
DROP TABLE IF EXISTS `ims_site_styles_vars`;
CREATE TABLE `ims_site_styles_vars` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `templateid` int(10) unsigned NOT NULL,
  `styleid` int(10) unsigned NOT NULL,
  `variable` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `description` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_site_templates
-- ----------------------------
DROP TABLE IF EXISTS `ims_site_templates`;
CREATE TABLE `ims_site_templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `title` varchar(30) NOT NULL,
  `description` varchar(500) NOT NULL,
  `author` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL,
  `sections` int(10) unsigned NOT NULL,
  `version` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_solution_acl
-- ----------------------------
DROP TABLE IF EXISTS `ims_solution_acl`;
CREATE TABLE `ims_solution_acl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `module` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `eid` int(10) unsigned NOT NULL,
  `do` varchar(255) NOT NULL,
  `state` varchar(1000) NOT NULL,
  `enable` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_module` (`module`) USING BTREE,
  KEY `idx_eid` (`eid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_special_mch_id
-- ----------------------------
DROP TABLE IF EXISTS `ims_special_mch_id`;
CREATE TABLE `ims_special_mch_id` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `module_name` varchar(50) NOT NULL COMMENT '模块名',
  `sub_mch_id` varchar(20) NOT NULL COMMENT '子商户的商户号',
  `remark` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_special_pay_order
-- ----------------------------
DROP TABLE IF EXISTS `ims_special_pay_order`;
CREATE TABLE `ims_special_pay_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ordernum` varchar(30) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `remark` varchar(20) NOT NULL,
  `createtime` int(10) NOT NULL,
  `updatetime` int(10) NOT NULL,
  `allprice` varchar(20) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `uniacid` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_stat_fans
-- ----------------------------
DROP TABLE IF EXISTS `ims_stat_fans`;
CREATE TABLE `ims_stat_fans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `new` int(10) unsigned NOT NULL,
  `cancel` int(10) unsigned NOT NULL,
  `cumulate` int(10) NOT NULL,
  `date` varchar(8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`date`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1158 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_stat_keyword
-- ----------------------------
DROP TABLE IF EXISTS `ims_stat_keyword`;
CREATE TABLE `ims_stat_keyword` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `rid` varchar(10) NOT NULL,
  `kid` int(10) unsigned NOT NULL,
  `hit` int(10) unsigned NOT NULL,
  `lastupdate` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_createtime` (`createtime`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=443 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_stat_msg_history
-- ----------------------------
DROP TABLE IF EXISTS `ims_stat_msg_history`;
CREATE TABLE `ims_stat_msg_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `kid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `module` varchar(50) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `type` varchar(10) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_createtime` (`createtime`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=20286 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_stat_rule
-- ----------------------------
DROP TABLE IF EXISTS `ims_stat_rule`;
CREATE TABLE `ims_stat_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `hit` int(10) unsigned NOT NULL,
  `lastupdate` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_createtime` (`createtime`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=12158 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_statistics
-- ----------------------------
DROP TABLE IF EXISTS `ims_statistics`;
CREATE TABLE `ims_statistics` (
  `mid` int(10) NOT NULL,
  `monthnum` varchar(5) NOT NULL,
  `yearnum` varchar(8) NOT NULL,
  `time` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom`;
CREATE TABLE `ims_superdesk_boardroom` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
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
  `uniacid` int(10) NOT NULL DEFAULT '0' COMMENT 'uniacid',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_4school
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_4school`;
CREATE TABLE `ims_superdesk_boardroom_4school` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
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
  `virtual_code` varchar(32) NOT NULL DEFAULT '' COMMENT 'virtual_code',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_4school_announcement
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_4school_announcement`;
CREATE TABLE `ims_superdesk_boardroom_4school_announcement` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
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
  `uid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='发布公告';

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_4school_announcement_reading_member
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_4school_announcement_reading_member`;
CREATE TABLE `ims_superdesk_boardroom_4school_announcement_reading_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `organization_code` varchar(32) DEFAULT '' COMMENT 'organization_code',
  `virtual_code` varchar(32) NOT NULL DEFAULT '' COMMENT 'virtual_code',
  `unionid` varchar(50) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `aid` int(10) unsigned NOT NULL COMMENT '公告id',
  `status` varchar(1000) NOT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7808 DEFAULT CHARSET=utf8 COMMENT='公告阅读记录表';

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_4school_appointment
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_4school_appointment`;
CREATE TABLE `ims_superdesk_boardroom_4school_appointment` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
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
  `quantity` decimal(10,1) NOT NULL DEFAULT '0.0' COMMENT '数量/小时',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=255 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_4school_building_attribute
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_4school_building_attribute`;
CREATE TABLE `ims_superdesk_boardroom_4school_building_attribute` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `organization_code` varchar(32) DEFAULT '' COMMENT 'organization_code',
  `virtual_code` varchar(32) NOT NULL DEFAULT '' COMMENT 'virtual_code',
  `name` varchar(50) NOT NULL COMMENT '名称',
  `thumb` varchar(255) NOT NULL COMMENT '图片',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0',
  `description` varchar(500) NOT NULL COMMENT '介绍',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_4school_building_structures
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_4school_building_structures`;
CREATE TABLE `ims_superdesk_boardroom_4school_building_structures` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `organization_code` varchar(32) DEFAULT '' COMMENT 'organization_code',
  `virtual_code` varchar(32) NOT NULL DEFAULT '' COMMENT 'virtual_code',
  `name` varchar(50) NOT NULL COMMENT '楼宇楼层名称',
  `thumb` varchar(255) NOT NULL COMMENT '楼宇楼层图片',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级楼宇楼层ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0',
  `description` varchar(500) NOT NULL COMMENT '楼宇楼层介绍',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_4school_category
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_4school_category`;
CREATE TABLE `ims_superdesk_boardroom_4school_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
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
  `gtime` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 COMMENT='类型表';

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_4school_equipment
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_4school_equipment`;
CREATE TABLE `ims_superdesk_boardroom_4school_equipment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL COMMENT '设备名字',
  `images` varchar(255) NOT NULL COMMENT '设备图片',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `enabled` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Enabled',
  `uniacid` int(10) NOT NULL COMMENT 'uniacid',
  `organization_code` varchar(32) DEFAULT '' COMMENT 'organization_code',
  `virtual_code` varchar(32) NOT NULL DEFAULT '' COMMENT 'virtual_code',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_4school_images
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_4school_images`;
CREATE TABLE `ims_superdesk_boardroom_4school_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `src` varchar(255) DEFAULT NULL,
  `file` longtext,
  `type` int(11) NOT NULL COMMENT '报修1，租赁2',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_4school_report
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_4school_report`;
CREATE TABLE `ims_superdesk_boardroom_4school_report` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
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
  `out_trade_no` varchar(32) NOT NULL DEFAULT '' COMMENT 'out_trade_no',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid_regionid` (`uniacid`,`regionid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_4school_s_adv
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_4school_s_adv`;
CREATE TABLE `ims_superdesk_boardroom_4school_s_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`) USING BTREE,
  KEY `indx_enabled` (`enabled`) USING BTREE,
  KEY `indx_displayorder` (`displayorder`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_4school_s_cart
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_4school_s_cart`;
CREATE TABLE `ims_superdesk_boardroom_4school_s_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `goodsid` int(11) NOT NULL,
  `goodstype` tinyint(1) NOT NULL DEFAULT '1',
  `from_user` varchar(50) NOT NULL,
  `total` int(10) unsigned NOT NULL,
  `optionid` int(10) DEFAULT '0',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`from_user`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1159 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_4school_s_category
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_4school_s_category`;
CREATE TABLE `ims_superdesk_boardroom_4school_s_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `organization_code` varchar(32) DEFAULT '' COMMENT 'organization_code',
  `virtual_code` varchar(32) NOT NULL DEFAULT '' COMMENT 'virtual_code',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `thumb` varchar(255) NOT NULL COMMENT '分类图片',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0',
  `description` varchar(500) NOT NULL COMMENT '分类介绍',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=76 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_4school_s_dispatch
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_4school_s_dispatch`;
CREATE TABLE `ims_superdesk_boardroom_4school_s_dispatch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `enabled` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`) USING BTREE,
  KEY `indx_displayorder` (`displayorder`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_4school_s_express
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_4school_s_express`;
CREATE TABLE `ims_superdesk_boardroom_4school_s_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `express_name` varchar(50) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `express_price` varchar(10) DEFAULT '',
  `express_area` varchar(100) DEFAULT '',
  `express_url` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`) USING BTREE,
  KEY `indx_displayorder` (`displayorder`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_4school_s_feedback
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_4school_s_feedback`;
CREATE TABLE `ims_superdesk_boardroom_4school_s_feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为维权，2为告擎',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态0未解决，1用户同意，2用户拒绝',
  `feedbackid` varchar(30) NOT NULL COMMENT '投诉单号',
  `transid` varchar(30) NOT NULL COMMENT '订单号',
  `reason` varchar(1000) NOT NULL COMMENT '理由',
  `solution` varchar(1000) NOT NULL COMMENT '期待解决方案',
  `remark` varchar(1000) NOT NULL COMMENT '备注',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_feedbackid` (`feedbackid`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE,
  KEY `idx_transid` (`transid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_4school_s_goods
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_4school_s_goods`;
CREATE TABLE `ims_superdesk_boardroom_4school_s_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
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
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_4school_s_goods_option
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_4school_s_goods_option`;
CREATE TABLE `ims_superdesk_boardroom_4school_s_goods_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `thumb` varchar(60) DEFAULT '',
  `productprice` decimal(10,2) DEFAULT '0.00',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  `costprice` decimal(10,2) DEFAULT '0.00',
  `stock` int(11) DEFAULT '0',
  `weight` decimal(10,2) DEFAULT '0.00',
  `displayorder` int(11) DEFAULT '0',
  `specs` text,
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`goodsid`) USING BTREE,
  KEY `indx_displayorder` (`displayorder`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_4school_s_goods_param
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_4school_s_goods_param`;
CREATE TABLE `ims_superdesk_boardroom_4school_s_goods_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `value` text,
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`goodsid`) USING BTREE,
  KEY `indx_displayorder` (`displayorder`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_4school_s_order
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_4school_s_order`;
CREATE TABLE `ims_superdesk_boardroom_4school_s_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
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
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=93 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_4school_s_order_goods
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_4school_s_order_goods`;
CREATE TABLE `ims_superdesk_boardroom_4school_s_order_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `out_trade_no` varchar(32) NOT NULL DEFAULT '' COMMENT 'out_trade_no',
  `goodsid` int(10) unsigned NOT NULL,
  `price` decimal(10,2) DEFAULT '0.00',
  `total` int(10) unsigned NOT NULL DEFAULT '1',
  `optionid` int(10) DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  `optionname` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=286 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_4school_s_product
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_4school_s_product`;
CREATE TABLE `ims_superdesk_boardroom_4school_s_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goodsid` int(11) NOT NULL,
  `productsn` varchar(50) NOT NULL,
  `title` varchar(1000) NOT NULL,
  `marketprice` decimal(10,0) unsigned NOT NULL,
  `productprice` decimal(10,0) unsigned NOT NULL,
  `total` int(11) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `spec` varchar(5000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_goodsid` (`goodsid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_4school_s_spec
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_4school_s_spec`;
CREATE TABLE `ims_superdesk_boardroom_4school_s_spec` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `displaytype` tinyint(3) unsigned NOT NULL,
  `content` text NOT NULL,
  `goodsid` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_4school_s_spec_item
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_4school_s_spec_item`;
CREATE TABLE `ims_superdesk_boardroom_4school_s_spec_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `specid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `show` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`) USING BTREE,
  KEY `indx_specid` (`specid`) USING BTREE,
  KEY `indx_show` (`show`) USING BTREE,
  KEY `indx_displayorder` (`displayorder`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_4school_x_equipment
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_4school_x_equipment`;
CREATE TABLE `ims_superdesk_boardroom_4school_x_equipment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `boardroom_id` int(11) NOT NULL DEFAULT '0',
  `equipment_id` int(11) NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  `updatetime` int(11) NOT NULL DEFAULT '0',
  `uniacid` int(10) NOT NULL DEFAULT '0' COMMENT 'uniacid',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=451 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_appointment
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_appointment`;
CREATE TABLE `ims_superdesk_boardroom_appointment` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `boardroom_id` int(10) NOT NULL COMMENT '会议室ID',
  `openid` varchar(64) NOT NULL COMMENT 'openid',
  `client_name` varchar(64) NOT NULL COMMENT '客户名字',
  `client_telphone` varchar(25) NOT NULL COMMENT '客户电话',
  `deleted` int(10) NOT NULL DEFAULT '0' COMMENT '删除(1已删除)',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功',
  `relate_id` varchar(80) NOT NULL DEFAULT '0' COMMENT '关联会员表',
  `people_num` int(1) NOT NULL DEFAULT '1' COMMENT '会议人数',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `starttime` int(10) NOT NULL DEFAULT '0' COMMENT '预约开始时间',
  `endtime` int(10) NOT NULL DEFAULT '0' COMMENT '预约结束时间',
  `uniacid` int(10) NOT NULL DEFAULT '0' COMMENT 'uniacid',
  `paydetail` varchar(255) NOT NULL DEFAULT '' COMMENT '支付详情',
  `paytype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1为余额，2为在线，3为到付',
  `out_trade_no` varchar(32) NOT NULL DEFAULT '' COMMENT 'out_trade_no',
  `transaction_id` varchar(32) NOT NULL DEFAULT '' COMMENT '微信支付订单号',
  `situation` text NOT NULL COMMENT 'situation JSON',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '成交价',
  `quantity` decimal(10,1) NOT NULL DEFAULT '0.0' COMMENT '数量/小时',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '成交总价',
  `lable_ymd` varchar(32) NOT NULL DEFAULT '' COMMENT 'eg:2017-09-09',
  `lable_time` varchar(32) NOT NULL DEFAULT '' COMMENT 'eg:00:30-12:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=209 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_equipment
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_equipment`;
CREATE TABLE `ims_superdesk_boardroom_equipment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL COMMENT '设备名字',
  `images` varchar(255) NOT NULL COMMENT '设备图片',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `enabled` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Enabled',
  `uniacid` int(10) NOT NULL COMMENT 'uniacid',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_s_adv
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_s_adv`;
CREATE TABLE `ims_superdesk_boardroom_s_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`) USING BTREE,
  KEY `indx_enabled` (`enabled`) USING BTREE,
  KEY `indx_displayorder` (`displayorder`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_s_cart
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_s_cart`;
CREATE TABLE `ims_superdesk_boardroom_s_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `goodsid` int(11) NOT NULL,
  `goodstype` tinyint(1) NOT NULL DEFAULT '1',
  `from_user` varchar(50) NOT NULL,
  `total` int(10) unsigned NOT NULL,
  `optionid` int(10) DEFAULT '0',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`from_user`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=351 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_s_category
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_s_category`;
CREATE TABLE `ims_superdesk_boardroom_s_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `thumb` varchar(255) NOT NULL COMMENT '分类图片',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0',
  `description` varchar(500) NOT NULL COMMENT '分类介绍',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=79 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_s_dispatch
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_s_dispatch`;
CREATE TABLE `ims_superdesk_boardroom_s_dispatch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `enabled` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`) USING BTREE,
  KEY `indx_displayorder` (`displayorder`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_s_express
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_s_express`;
CREATE TABLE `ims_superdesk_boardroom_s_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `express_name` varchar(50) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `express_price` varchar(10) DEFAULT '',
  `express_area` varchar(100) DEFAULT '',
  `express_url` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`) USING BTREE,
  KEY `indx_displayorder` (`displayorder`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_s_feedback
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_s_feedback`;
CREATE TABLE `ims_superdesk_boardroom_s_feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为维权，2为告擎',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态0未解决，1用户同意，2用户拒绝',
  `feedbackid` varchar(30) NOT NULL COMMENT '投诉单号',
  `transid` varchar(30) NOT NULL COMMENT '订单号',
  `reason` varchar(1000) NOT NULL COMMENT '理由',
  `solution` varchar(1000) NOT NULL COMMENT '期待解决方案',
  `remark` varchar(1000) NOT NULL COMMENT '备注',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_feedbackid` (`feedbackid`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE,
  KEY `idx_transid` (`transid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_s_goods
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_s_goods`;
CREATE TABLE `ims_superdesk_boardroom_s_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
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
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_s_goods_option
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_s_goods_option`;
CREATE TABLE `ims_superdesk_boardroom_s_goods_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `thumb` varchar(60) DEFAULT '',
  `productprice` decimal(10,2) DEFAULT '0.00',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  `costprice` decimal(10,2) DEFAULT '0.00',
  `stock` int(11) DEFAULT '0',
  `weight` decimal(10,2) DEFAULT '0.00',
  `displayorder` int(11) DEFAULT '0',
  `specs` text,
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`goodsid`) USING BTREE,
  KEY `indx_displayorder` (`displayorder`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_s_goods_param
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_s_goods_param`;
CREATE TABLE `ims_superdesk_boardroom_s_goods_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `value` text,
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`goodsid`) USING BTREE,
  KEY `indx_displayorder` (`displayorder`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_s_order
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_s_order`;
CREATE TABLE `ims_superdesk_boardroom_s_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `out_trade_no` varchar(20) NOT NULL COMMENT 'out_trade_no',
  `price` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功',
  `sendtype` tinyint(1) unsigned NOT NULL COMMENT '1为快递，2为自提',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付',
  `transaction_id` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号',
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
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_s_order_goods
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_s_order_goods`;
CREATE TABLE `ims_superdesk_boardroom_s_order_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `out_trade_no` varchar(32) NOT NULL DEFAULT '' COMMENT 'out_trade_no',
  `goodsid` int(10) unsigned NOT NULL,
  `price` decimal(10,2) DEFAULT '0.00',
  `total` int(10) unsigned NOT NULL DEFAULT '1',
  `optionid` int(10) DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  `optionname` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=77 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_s_product
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_s_product`;
CREATE TABLE `ims_superdesk_boardroom_s_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goodsid` int(11) NOT NULL,
  `productsn` varchar(50) NOT NULL,
  `title` varchar(1000) NOT NULL,
  `marketprice` decimal(10,0) unsigned NOT NULL,
  `productprice` decimal(10,0) unsigned NOT NULL,
  `total` int(11) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `spec` varchar(5000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_goodsid` (`goodsid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_s_spec
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_s_spec`;
CREATE TABLE `ims_superdesk_boardroom_s_spec` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `displaytype` tinyint(3) unsigned NOT NULL,
  `content` text NOT NULL,
  `goodsid` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_boardroom_s_spec_item
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_boardroom_s_spec_item`;
CREATE TABLE `ims_superdesk_boardroom_s_spec_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `specid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `show` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`) USING BTREE,
  KEY `indx_specid` (`specid`) USING BTREE,
  KEY `indx_show` (`show`) USING BTREE,
  KEY `indx_displayorder` (`displayorder`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_core_build
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_core_build`;
CREATE TABLE `ims_superdesk_core_build` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `name` varchar(20) DEFAULT NULL COMMENT '名称',
  `organizationId` int(11) DEFAULT NULL COMMENT '学校id',
  `vip` varchar(10) DEFAULT NULL COMMENT 'vip标识：0 否，1 是',
  `remark` varchar(200) DEFAULT NULL COMMENT '备注信息',
  `address` varchar(200) DEFAULT NULL COMMENT '详细地址',
  `lng` decimal(20,4) DEFAULT NULL COMMENT '地图x坐标',
  `lat` decimal(20,4) DEFAULT NULL COMMENT '地图Y坐标',
  `createTime` datetime DEFAULT NULL COMMENT '创建时间',
  `creator` varchar(20) DEFAULT NULL COMMENT '创建人',
  `modifier` varchar(20) DEFAULT NULL COMMENT '修改人',
  `modifyTime` datetime DEFAULT NULL COMMENT '修改时间',
  `isEnabled` varchar(10) DEFAULT NULL COMMENT '是否可用或删除：0 禁用，1 可用',
  `uniacid` int(10) NOT NULL DEFAULT '0' COMMENT 'uniacid',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=410 DEFAULT CHARSET=utf8 COMMENT='高校楼栋地理位置表';

-- ----------------------------
-- Table structure for ims_superdesk_core_dictionary_group
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_core_dictionary_group`;
CREATE TABLE `ims_superdesk_core_dictionary_group` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `groupcode` varchar(30) NOT NULL,
  `groupname` varchar(30) NOT NULL,
  `isenabled` char(1) DEFAULT NULL,
  `oprateversion` bigint(20) DEFAULT NULL,
  `opratetype` char(1) DEFAULT NULL,
  `createby` varchar(30) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `lastupdateby` varchar(30) DEFAULT NULL,
  `lastupdatetime` datetime DEFAULT NULL,
  `uniacid` int(10) NOT NULL DEFAULT '0' COMMENT 'uniacid',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_core_dictionary_item
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_core_dictionary_item`;
CREATE TABLE `ims_superdesk_core_dictionary_item` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `itemcode` varchar(30) NOT NULL,
  `itemname` varchar(30) NOT NULL,
  `groupid` bigint(11) NOT NULL,
  `isenabled` char(1) DEFAULT NULL,
  `oprateversion` bigint(20) DEFAULT NULL,
  `opratetype` char(1) DEFAULT NULL,
  `createby` varchar(30) DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  `lastupdateby` varchar(30) DEFAULT NULL,
  `orderno` int(11) DEFAULT NULL,
  `lastupdatetime` datetime DEFAULT NULL,
  `uniacid` int(10) NOT NULL DEFAULT '0' COMMENT 'uniacid',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=252 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_core_organization
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_core_organization`;
CREATE TABLE `ims_superdesk_core_organization` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL COMMENT '名称',
  `code` varchar(128) DEFAULT NULL COMMENT '编码',
  `type` varchar(20) DEFAULT NULL COMMENT '项目类型（高校校园、小区住宅、CBD写字楼）',
  `telephone` varchar(32) DEFAULT NULL COMMENT '服务热线',
  `provinceCode` varchar(32) DEFAULT NULL COMMENT '所在省份编码',
  `provinceName` varchar(40) DEFAULT NULL COMMENT '所在省份名称',
  `cityCode` varchar(32) DEFAULT NULL COMMENT '所在城市编码',
  `cityName` varchar(40) DEFAULT NULL COMMENT '所在城市名称',
  `address` varchar(256) DEFAULT NULL COMMENT '详细地址',
  `lng` decimal(20,4) DEFAULT NULL COMMENT '经度',
  `lat` decimal(20,4) DEFAULT NULL COMMENT '纬度',
  `status` varchar(2) DEFAULT NULL COMMENT '项目状态（0-待审核，1-通过，2-不通过）',
  `applicantName` varchar(40) DEFAULT NULL COMMENT '申请人姓名',
  `applicantPhone` varchar(12) DEFAULT NULL COMMENT '申请人电话',
  `reviewRemark` varchar(200) DEFAULT NULL COMMENT '审核信息说明',
  `applicantIdentity` varchar(40) DEFAULT NULL COMMENT '申请人身份',
  `wxUserId` int(11) DEFAULT NULL COMMENT '申请人的微信信息ID',
  `createTime` datetime DEFAULT NULL COMMENT '创建时间',
  `creator` varchar(20) DEFAULT NULL COMMENT '创建者',
  `modifyTime` datetime DEFAULT NULL COMMENT '修改时间',
  `modifier` varchar(20) DEFAULT NULL COMMENT '修改人',
  `isEnabled` varchar(1) DEFAULT NULL COMMENT '是否可用或删除',
  `uniacid` int(10) NOT NULL DEFAULT '0' COMMENT 'uniacid',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=663 DEFAULT CHARSET=utf8 COMMENT='组织架构';

-- ----------------------------
-- Table structure for ims_superdesk_core_provincecity
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_core_provincecity`;
CREATE TABLE `ims_superdesk_core_provincecity` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` varchar(2) DEFAULT NULL COMMENT '类型(1-代表省份，2-代表城市)',
  `name` varchar(200) DEFAULT NULL COMMENT '省份/城市名称',
  `provinceCode` varchar(200) DEFAULT NULL COMMENT '省份编码',
  `cityCode` varchar(200) DEFAULT NULL COMMENT '城市编码',
  `description` varchar(50) DEFAULT NULL COMMENT '名称首字母',
  `creator` varchar(40) DEFAULT NULL COMMENT '创建人',
  `createTime` datetime DEFAULT NULL COMMENT '创建时间',
  `modifier` varchar(40) DEFAULT NULL COMMENT '修改人',
  `modifyTime` datetime DEFAULT NULL COMMENT '修改时间',
  `isEnabled` varchar(2) DEFAULT NULL COMMENT '是否可用',
  `uniacid` int(10) NOT NULL DEFAULT '0' COMMENT 'uniacid',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=386 DEFAULT CHARSET=utf8 COMMENT='省份-城市参照信息表';

-- ----------------------------
-- Table structure for ims_superdesk_core_tb_user
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_core_tb_user`;
CREATE TABLE `ims_superdesk_core_tb_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(40) DEFAULT NULL COMMENT '姓名',
  `nickName` varchar(40) DEFAULT NULL COMMENT '昵称',
  `userMobile` varchar(11) DEFAULT NULL COMMENT '手机号码',
  `userType` varchar(2) DEFAULT NULL COMMENT '用户类型',
  `userSex` varchar(2) DEFAULT NULL COMMENT '性别',
  `userCardNo` varchar(40) DEFAULT NULL COMMENT '学生号/身份证',
  `birthday` varchar(20) DEFAULT NULL COMMENT '生日',
  `userPhotoUrl` varchar(200) DEFAULT NULL COMMENT '头像',
  `password` varchar(100) DEFAULT NULL COMMENT '密码',
  `status` varchar(2) DEFAULT NULL COMMENT '认证状态',
  `suggestion` varchar(250) DEFAULT NULL COMMENT '审核建议',
  `address` varchar(200) DEFAULT NULL COMMENT '详细地址',
  `imageUrl01` varchar(200) DEFAULT NULL COMMENT '证件照片1',
  `imageUrl02` varchar(200) DEFAULT NULL COMMENT '证件照片2',
  `imageUrl03` varchar(200) DEFAULT NULL,
  `organizationId` int(11) DEFAULT NULL COMMENT '用户所属组织',
  `virtualArchId` int(11) DEFAULT NULL COMMENT '学院/系部ID',
  `userNumber` varchar(40) DEFAULT NULL COMMENT '员工编号',
  `enteringTime` date DEFAULT NULL COMMENT '入司时间',
  `positionName` varchar(40) DEFAULT NULL COMMENT '职位名称',
  `departmentId` int(11) DEFAULT NULL COMMENT '部门ID',
  `facePlusUserId` int(11) DEFAULT NULL COMMENT 'face++用户唯一标识',
  `roleType` varchar(2) DEFAULT NULL COMMENT '企业用户角色（1-管理员，2-普通用户）',
  `noticePower` varchar(2) DEFAULT NULL COMMENT '接受审核通知（0-不接收用户申请通知，关，1-接收用户申请通知，开）',
  `creator` varchar(20) DEFAULT NULL COMMENT '创建者',
  `createTime` datetime DEFAULT NULL COMMENT '创建时间',
  `modifier` varchar(20) DEFAULT NULL COMMENT '修改人',
  `modifyTime` datetime DEFAULT NULL COMMENT '修改时间',
  `isEnabled` varchar(2) DEFAULT NULL COMMENT '是否可用',
  `isSyncNeigou` int(11) DEFAULT NULL COMMENT '是否同步内购网',
  `isSyncSpaceHome` int(11) NOT NULL DEFAULT '0' COMMENT 'isSyncSpaceHome',
  `uniacid` int(10) NOT NULL DEFAULT '0' COMMENT 'uniacid',
  `newUserId` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13102 DEFAULT CHARSET=utf8 COMMENT='前端用户信息表';

-- ----------------------------
-- Table structure for ims_superdesk_core_users
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_core_users`;
CREATE TABLE `ims_superdesk_core_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `organization_code` varchar(16) NOT NULL,
  `virtual_code` varchar(16) NOT NULL,
  `menus` varchar(500) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT NULL,
  `commission` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Table structure for ims_superdesk_core_virtualarchitecture
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_core_virtualarchitecture`;
CREATE TABLE `ims_superdesk_core_virtualarchitecture` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `name` varchar(50) DEFAULT NULL COMMENT '学院名称',
  `organizationId` int(11) DEFAULT NULL COMMENT '项目ID',
  `type` varchar(2) DEFAULT NULL COMMENT '类型(1-学院、2-专业)',
  `code` varchar(50) DEFAULT NULL COMMENT '当前节点Code',
  `parentCode` varchar(50) DEFAULT NULL COMMENT '父节点Code',
  `remark` varchar(200) DEFAULT NULL COMMENT '备注',
  `codeNumber` varchar(40) DEFAULT NULL COMMENT '唯一编码',
  `customerNumber` varchar(40) DEFAULT NULL COMMENT '客户签约编号',
  `phone` varchar(255) DEFAULT NULL COMMENT '联系电话',
  `address` varchar(400) DEFAULT NULL COMMENT '企业详细地址',
  `contacts` varchar(40) DEFAULT NULL COMMENT '联系人',
  `employees` int(11) DEFAULT NULL COMMENT '企业人数',
  `reserveBalance` decimal(10,2) DEFAULT '0.00' COMMENT '预存款余额',
  `customerType` varchar(2) DEFAULT '0' COMMENT '客户类型（1-VIP客户，0-普通客户）',
  `contractStatus` varchar(2) DEFAULT '0' COMMENT '签约状态（1-已签约，0-未签约）',
  `status` varchar(2) DEFAULT NULL COMMENT '企业状态（0-待审核，1-通过，2-不通过）',
  `reviewRemark` varchar(200) DEFAULT NULL COMMENT '审核信息说明',
  `wxUserId` int(11) DEFAULT NULL COMMENT '申请人的微信信息ID',
  `creator` varchar(20) DEFAULT NULL COMMENT '创建者',
  `createTime` datetime DEFAULT NULL COMMENT '创建时间',
  `modifier` varchar(20) DEFAULT NULL COMMENT '修改人',
  `modifyTime` datetime DEFAULT NULL COMMENT '修改时间',
  `isEnabled` varchar(2) DEFAULT NULL COMMENT '是否可用或者删除1可用，0不可用',
  `uniacid` int(10) NOT NULL DEFAULT '0' COMMENT 'uniacid',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3562 DEFAULT CHARSET=utf8 COMMENT='项目-虚拟组织信息表（比如学院、专业）';

-- ----------------------------
-- Table structure for ims_superdesk_dish_address
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_dish_address`;
CREATE TABLE `ims_superdesk_dish_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `realname` varchar(20) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `address` varchar(300) NOT NULL,
  `dateline` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=406 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_dish_area
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_dish_area`;
CREATE TABLE `ims_superdesk_dish_area` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '区域名称',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_dish_blacklist
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_dish_blacklist`;
CREATE TABLE `ims_superdesk_dish_blacklist` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(100) DEFAULT '' COMMENT '用户ID',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`from_user`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_dish_cart
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_dish_cart`;
CREATE TABLE `ims_superdesk_dish_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `goodsid` int(11) NOT NULL,
  `goodstype` tinyint(1) NOT NULL DEFAULT '1',
  `price` varchar(10) NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `total` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=89 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_dish_category
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_dish_category`;
CREATE TABLE `ims_superdesk_dish_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `storeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店id',
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_dish_collection
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_dish_collection`;
CREATE TABLE `ims_superdesk_dish_collection` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `dateline` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_dish_email_setting
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_dish_email_setting`;
CREATE TABLE `ims_superdesk_dish_email_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `email_enable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '开启邮箱提醒',
  `email_host` varchar(50) DEFAULT '' COMMENT '邮箱服务器',
  `email_send` varchar(100) DEFAULT NULL,
  `email_pwd` varchar(20) DEFAULT '' COMMENT '邮箱密码',
  `email_user` varchar(100) DEFAULT '' COMMENT '发信人名称',
  `email` varchar(100) DEFAULT NULL,
  `email_business_tpl` varchar(200) DEFAULT '' COMMENT '商户接收内容模板',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_dish_goods
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_dish_goods`;
CREATE TABLE `ims_superdesk_dish_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `storeid` int(10) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL,
  `pcate` int(10) unsigned NOT NULL DEFAULT '0',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `recommend` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `thumb` varchar(100) NOT NULL DEFAULT '',
  `unitname` varchar(5) NOT NULL DEFAULT '份',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `taste` varchar(1000) NOT NULL DEFAULT '' COMMENT '口味',
  `isspecial` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `marketprice` varchar(10) NOT NULL DEFAULT '',
  `productprice` varchar(10) NOT NULL DEFAULT '',
  `credit` int(10) NOT NULL DEFAULT '0',
  `subcount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '被点次数',
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_dish_intelligent
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_dish_intelligent`;
CREATE TABLE `ims_superdesk_dish_intelligent` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `storeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店id',
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` int(10) NOT NULL DEFAULT '0' COMMENT '适用人数',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '菜品',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_dish_mealtime
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_dish_mealtime`;
CREATE TABLE `ims_superdesk_dish_mealtime` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `begintime` varchar(20) DEFAULT '09:00' COMMENT '开始时间',
  `endtime` varchar(20) DEFAULT '18:00' COMMENT '结束时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_dish_nave
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_dish_nave`;
CREATE TABLE `ims_superdesk_dish_nave` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `type` int(10) NOT NULL DEFAULT '-1' COMMENT '链接类型 -1:自定义 1:首页2:门店3:菜单列表4:我的菜单',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '导航名称',
  `link` varchar(200) NOT NULL DEFAULT '' COMMENT '导航链接',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_dish_order
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_dish_order`;
CREATE TABLE `ims_superdesk_dish_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号id',
  `storeid` int(10) unsigned NOT NULL COMMENT '门店id',
  `from_user` varchar(50) NOT NULL,
  `ordersn` varchar(30) NOT NULL COMMENT '订单号',
  `totalnum` tinyint(4) DEFAULT NULL COMMENT '总数量',
  `totalprice` varchar(10) NOT NULL COMMENT '总金额',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为确认付款方式，2为成功',
  `paytype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1余额，2在线，3到付',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `address` varchar(250) NOT NULL DEFAULT '' COMMENT '地址',
  `tel` varchar(50) NOT NULL DEFAULT '' COMMENT '联系电话',
  `reply` varchar(1000) NOT NULL DEFAULT '' COMMENT '回复',
  `meal_time` varchar(50) NOT NULL DEFAULT '' COMMENT '就餐时间',
  `counts` tinyint(4) DEFAULT '0' COMMENT '预订人数',
  `seat_type` tinyint(1) DEFAULT '0' COMMENT '位置类型1大厅2包间',
  `carports` tinyint(3) DEFAULT '0' COMMENT '车位',
  `dining_mode` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '用餐类型 1:到店 2:外卖',
  `remark` varchar(1000) NOT NULL DEFAULT '' COMMENT '备注',
  `tables` varchar(10) NOT NULL DEFAULT '' COMMENT '桌号',
  `print_sta` tinyint(1) DEFAULT '-1' COMMENT '打印状态',
  `sign` tinyint(1) NOT NULL DEFAULT '0' COMMENT '-1拒绝，0未处理，1已处理',
  `isfinish` tinyint(1) NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号',
  `goodsprice` decimal(10,2) DEFAULT '0.00',
  `dispatchprice` decimal(10,2) DEFAULT '0.00',
  `isemail` tinyint(1) NOT NULL DEFAULT '0',
  `issms` tinyint(1) NOT NULL DEFAULT '0',
  `istpl` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_dish_order_goods
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_dish_order_goods`;
CREATE TABLE `ims_superdesk_dish_order_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `goodsid` int(10) unsigned NOT NULL,
  `price` varchar(10) NOT NULL,
  `total` int(10) unsigned NOT NULL DEFAULT '1',
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=67 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_dish_print_order
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_dish_print_order`;
CREATE TABLE `ims_superdesk_dish_print_order` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `print_usr` varchar(50) DEFAULT '',
  `print_status` tinyint(1) DEFAULT '-1',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_dish_print_setting
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_dish_print_setting`;
CREATE TABLE `ims_superdesk_dish_print_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
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
  `feyin_key` varchar(100) DEFAULT '' COMMENT 'api密钥',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_dish_reply
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_dish_reply`;
CREATE TABLE `ims_superdesk_dish_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '入口类型',
  `storeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '入口门店',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `picture` varchar(255) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加日期',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_dish_setting
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_dish_setting`;
CREATE TABLE `ims_superdesk_dish_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) DEFAULT '' COMMENT '网站名称',
  `thumb` varchar(200) DEFAULT '' COMMENT '背景图',
  `storeid` int(10) unsigned NOT NULL DEFAULT '0',
  `entrance_type` tinyint(1) unsigned NOT NULL COMMENT '入口类型1:首页2门店列表3菜品列表4我的菜单',
  `entrance_storeid` tinyint(1) unsigned NOT NULL COMMENT '入口门店id',
  `order_enable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '订餐开启',
  `dining_mode` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '用餐类型 1:到店 2:外卖',
  `dateline` int(10) DEFAULT '0',
  `istplnotice` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否模版通知',
  `tplneworder` varchar(200) DEFAULT '' COMMENT '模板id',
  `tpluser` text COMMENT '通知用户',
  `searchword` varchar(1000) DEFAULT '' COMMENT '搜索关键字',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_dish_sms_checkcode
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_dish_sms_checkcode`;
CREATE TABLE `ims_superdesk_dish_sms_checkcode` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(100) DEFAULT '' COMMENT '用户ID',
  `mobile` varchar(30) DEFAULT '' COMMENT '手机',
  `checkcode` varchar(100) DEFAULT '' COMMENT '验证码',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '状态 0未使用1已使用',
  `dateline` int(10) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_dish_sms_setting
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_dish_sms_setting`;
CREATE TABLE `ims_superdesk_dish_sms_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `sms_enable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '开启短信提醒',
  `sms_verify_enable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '开启短信验证提醒',
  `sms_username` varchar(20) DEFAULT '' COMMENT '平台帐号',
  `sms_pwd` varchar(20) DEFAULT '' COMMENT '平台密码',
  `sms_mobile` varchar(20) DEFAULT '' COMMENT '商户接收短信手机',
  `sms_verify_tpl` varchar(120) DEFAULT '' COMMENT '验证短信模板',
  `sms_business_tpl` varchar(120) DEFAULT '' COMMENT '商户短信模板',
  `sms_user_tpl` varchar(120) DEFAULT '' COMMENT '用户短信模板',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_dish_store_setting
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_dish_store_setting`;
CREATE TABLE `ims_superdesk_dish_store_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `order_enable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '订餐开启',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_dish_stores
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_dish_stores`;
CREATE TABLE `ims_superdesk_dish_stores` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
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
  `enable_wifi` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有wifi',
  `enable_card` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否能刷卡',
  `enable_room` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有包厢',
  `enable_park` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有停车',
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
  `typeid` int(10) NOT NULL DEFAULT '0' COMMENT '商家类型',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_dish_type
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_dish_type`;
CREATE TABLE `ims_superdesk_dish_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '类型名称',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_feedback_feedback
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_feedback_feedback`;
CREATE TABLE `ims_superdesk_feedback_feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned DEFAULT '0',
  `from_user` varchar(100) DEFAULT '',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '0为第一级',
  `nickname` varchar(100) DEFAULT '',
  `username` varchar(100) DEFAULT '',
  `headimgurl` varchar(500) DEFAULT '',
  `telphone` varchar(16) NOT NULL DEFAULT '' COMMENT 'telphone',
  `issue_type` varchar(32) NOT NULL COMMENT 'issue_type',
  `content` varchar(200) DEFAULT '' COMMENT '回复内容',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_feedback_setting
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_feedback_setting`;
CREATE TABLE `ims_superdesk_feedback_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned DEFAULT '0',
  `title` varchar(100) DEFAULT '' COMMENT '网站名称',
  `pagesize` int(10) unsigned DEFAULT '10' COMMENT '每页显示数量 默认为10',
  `topimgurl` varchar(500) DEFAULT '' COMMENT '顶部图片',
  `pagecolor` varchar(50) DEFAULT '' COMMENT '页面色调',
  `ischeck` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否需要审核',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_jd_vop_area
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_jd_vop_area`;
CREATE TABLE `ims_superdesk_jd_vop_area` (
  `code` int(11) NOT NULL COMMENT 'code',
  `parent_code` int(11) NOT NULL COMMENT 'parent_code',
  `level` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'level',
  `text` varchar(128) NOT NULL DEFAULT '' COMMENT 'text',
  `state` tinyint(3) NOT NULL DEFAULT '1' COMMENT 'state',
  `remark` varchar(128) NOT NULL DEFAULT '' COMMENT '标注',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT 'createtime',
  `updatetime` int(11) NOT NULL COMMENT 'updatetime',
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_jd_vop_balance_detail
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_jd_vop_balance_detail`;
CREATE TABLE `ims_superdesk_jd_vop_balance_detail` (
  `id` bigint(20) NOT NULL COMMENT '余额明细 ID',
  `accountType` tinyint(4) NOT NULL DEFAULT '0' COMMENT '账户类型',
  `amount` decimal(10,2) NOT NULL COMMENT '金额(元)',
  `pin` varchar(256) NOT NULL DEFAULT '' COMMENT '京东 Pin',
  `orderId` bigint(13) NOT NULL DEFAULT '0' COMMENT '订单号',
  `tradeType` int(11) NOT NULL DEFAULT '0' COMMENT '业务类型',
  `tradeTypeName` varchar(1000) NOT NULL COMMENT '业务类型名称',
  `createdDate` datetime NOT NULL COMMENT '余额变动日期',
  `notePub` text NOT NULL COMMENT '备注信息',
  `tradeNo` bigint(13) NOT NULL DEFAULT '0' COMMENT '业务号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_jd_vop_balance_detail_processing
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_jd_vop_balance_detail_processing`;
CREATE TABLE `ims_superdesk_jd_vop_balance_detail_processing` (
  `id` bigint(20) NOT NULL,
  `processing` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0未处理,1已处理',
  `process_result` varchar(1000) NOT NULL DEFAULT '' COMMENT '处理结果',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='京东余额处理表';

-- ----------------------------
-- Table structure for ims_superdesk_jd_vop_bill
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_jd_vop_bill`;
CREATE TABLE `ims_superdesk_jd_vop_bill` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `billNo` varchar(32) DEFAULT '' COMMENT '账单编号',
  `billStartDate` bigint(20) DEFAULT '0' COMMENT '账单开始日期',
  `billEndDate` bigint(20) DEFAULT '0' COMMENT '账单结束日期',
  `repayDate` bigint(20) DEFAULT '0' COMMENT '应还款日期',
  `billAmount` decimal(10,2) DEFAULT '0.00' COMMENT '账单金额',
  `status` int(5) DEFAULT '0' COMMENT '还款状态(5001=正常，5002=延期，5003=逾期，5004=已结清，5005=延期已结清，5006=逾期已结清)',
  `overDays` int(5) DEFAULT '0' COMMENT '逾期天数',
  `overAmount` decimal(10,2) DEFAULT '0.00' COMMENT '违约金',
  `billType` int(5) DEFAULT '0' COMMENT '账单类型(5106：月结；5170：周结；5102：文档没写)',
  `execRate` decimal(5,2) DEFAULT '0.00' COMMENT '服务费率（执行利率）',
  `sumPri` decimal(10,2) DEFAULT '0.00' COMMENT '已还本金',
  `sumNrPri` decimal(10,2) DEFAULT '0.00' COMMENT '未还本金',
  `sumDefer` decimal(10,2) DEFAULT '0.00' COMMENT '已还延期服务费',
  `sumNrDefer` decimal(10,2) DEFAULT '0.00' COMMENT '未还延期服务费',
  `sumDelin` decimal(10,2) DEFAULT '0.00' COMMENT '已还违约服务费',
  `sumNrDelin` decimal(10,2) DEFAULT '0.00' COMMENT '未还违约服务费',
  `delinDate` bigint(20) DEFAULT '0' COMMENT '违约开始日期',
  `createtime` int(11) DEFAULT '0' COMMENT '建立时间',
  `updatetime` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_jd_vop_bill_order
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_jd_vop_bill_order`;
CREATE TABLE `ims_superdesk_jd_vop_bill_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `orderNo` bigint(20) DEFAULT '0' COMMENT '订单号',
  `billNo` varchar(32) DEFAULT '' COMMENT '账单编号',
  `orderAmount` decimal(10,2) DEFAULT '0.00' COMMENT '订单金额',
  `settleStatus` int(5) DEFAULT '0' COMMENT '结清状态(2500:未结清；2501:已结清)',
  `settleDate` bigint(20) DEFAULT '0' COMMENT '结算时间',
  `sumRefundAmount` decimal(10,2) DEFAULT '0.00' COMMENT '总退款金额',
  `customerName` varchar(255) DEFAULT '' COMMENT '结算单位',
  `shopOrderSn` varchar(30) DEFAULT '' COMMENT '商城订单编号',
  `shopOrderPrice` decimal(10,2) DEFAULT '0.00' COMMENT '商城订单金额',
  `shopOrderStatus` tinyint(3) DEFAULT '0' COMMENT '商城订单状态',
  `orderFreight` int(11) DEFAULT '0' COMMENT '运费',
  `isRight` tinyint(4) DEFAULT '0' COMMENT '是否正确',
  `createtime` int(11) DEFAULT '0' COMMENT '建立时间',
  `updatetime` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=523 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_jd_vop_configs
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_jd_vop_configs`;
CREATE TABLE `ims_superdesk_jd_vop_configs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` varchar(100) DEFAULT '',
  `client_secret` varchar(100) DEFAULT '',
  `username` varchar(100) DEFAULT '',
  `password` varchar(100) DEFAULT '',
  `is_default` tinyint(3) DEFAULT '0' COMMENT '是否默认(0否1是)',
  `createtime` int(10) DEFAULT NULL,
  `updatetime` int(10) DEFAULT NULL,
  `title` varchar(255) DEFAULT '' COMMENT '标题.作为知道这个是用在哪里?',
  `deleted` tinyint(3) DEFAULT '0' COMMENT '软删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_jd_vop_logs
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_jd_vop_logs`;
CREATE TABLE `ims_superdesk_jd_vop_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT 'createtime',
  `url` varchar(500) NOT NULL COMMENT 'url',
  `api` varchar(500) NOT NULL COMMENT 'api',
  `method` varchar(16) NOT NULL COMMENT 'method',
  `post_fields` text NOT NULL COMMENT 'post_fields',
  `curl_info` text NOT NULL COMMENT 'curl_info',
  `success` int(1) NOT NULL COMMENT 'success',
  `resultMessage` varchar(500) NOT NULL COMMENT 'url',
  `resultCode` varchar(16) NOT NULL COMMENT 'resultCode',
  `result` text NOT NULL COMMENT 'result',
  PRIMARY KEY (`id`),
  KEY `success` (`success`) USING BTREE,
  KEY `api` (`api`) USING BTREE,
  KEY `idx_union_search_0` (`api`,`success`) USING BTREE,
  KEY `idx_union_search_1` (`api`,`success`,`createtime`) USING BTREE,
  KEY `idx_union_search_2` (`api`,`success`,`resultCode`,`createtime`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13511 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_jd_vop_order_submit_order
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_jd_vop_order_submit_order`;
CREATE TABLE `ims_superdesk_jd_vop_order_submit_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `isparent` tinyint(3) NOT NULL DEFAULT '0' COMMENT '1为父',
  `order_id` int(11) NOT NULL DEFAULT '0',
  `thirdOrder` varchar(64) NOT NULL DEFAULT '' COMMENT '第三方的订单单号',
  `sku` text NOT NULL COMMENT '[{"skuId":商品编号, "num":商品数量,"bNeedAnnex":true, "bNeedGift":true, "price":100, "yanbao":[{"skuId":商品编号}]}] (最高支持50种商品)',
  `name` varchar(128) NOT NULL COMMENT '收货人',
  `province` int(11) NOT NULL COMMENT '一级地址',
  `city` int(11) NOT NULL COMMENT '二级地址',
  `county` int(11) NOT NULL COMMENT '三级地址',
  `town` int(11) NOT NULL COMMENT '四级地址 (如果该地区有四级地址，则必须传递四级地址，没有四级地址则传0)',
  `address` varchar(512) NOT NULL COMMENT '详细地址',
  `zip` varchar(16) DEFAULT NULL COMMENT '邮编',
  `phone` varchar(16) DEFAULT NULL COMMENT '座机号',
  `mobile` varchar(16) NOT NULL COMMENT '手机号',
  `email` varchar(32) NOT NULL COMMENT '邮箱',
  `remark` varchar(200) NOT NULL COMMENT '备注（少于100字）',
  `invoiceState` int(11) NOT NULL COMMENT '开票方式(1为随货开票，0为订单预借，2为集中开票 )',
  `invoiceType` int(11) NOT NULL COMMENT '1普通发票2增值税发票',
  `selectedInvoiceTitle` int(11) NOT NULL COMMENT '发票类型：4个人，5单位',
  `companyName` varchar(256) NOT NULL COMMENT '发票抬头 (如果selectedInvoiceTitle=5则此字段必须)',
  `invoiceContent` int(11) NOT NULL COMMENT '1:明细，3：电脑配件，19:耗材，22：办公用品 备注:若增值发票则只能选1 明细',
  `paymentType` int(11) NOT NULL COMMENT '支付方式 (1：货到付款，2：邮局付款，4：在线支付，5：公司转账，6：银行转账，7：网银钱包，101：金采支付)',
  `isUseBalance` int(11) NOT NULL COMMENT '使用余额paymentType=4时，此值固定是1 其他支付方式0',
  `submitState` int(11) NOT NULL COMMENT '是否预占库存，0是预占库存（需要调用确认订单接口），1是不预占库存 金融支付必须预占库存传0',
  `invoiceName` int(11) DEFAULT NULL COMMENT '增值票收票人姓名 备注：当invoiceType=2 且invoiceState=1时则此字段必填',
  `invoicePhone` int(11) DEFAULT NULL COMMENT '增值票收票人电话 备注：当invoiceType=2 且invoiceState=1时则此字段必填',
  `invoiceProvice` int(11) DEFAULT NULL COMMENT '增值票收票人所在省(京东地址编码) 备注：当invoiceType=2 且invoiceState=1时则此字段必填',
  `invoiceCity` int(11) DEFAULT NULL COMMENT '增值票收票人所在市(京东地址编码) 备注：当invoiceType=2 且invoiceState=1时则此字段必填',
  `invoiceCounty` int(11) DEFAULT NULL COMMENT '增值票收票人所在区/县(京东地址编码) 备注：当invoiceType=2 且invoiceState=1时则此字段必填',
  `invoiceAddress` int(11) DEFAULT NULL COMMENT '增值票收票人所在地址 备注：当invoiceType=2 且invoiceState=1时则此字段必填',
  `doOrderPriceMode` int(11) NOT NULL COMMENT '"0: 客户端订单价格快照不做验证对比，还是以京东价格正常下单; 1:必需验证客户端订单价格快照，如果快照与京东价格不一致返回下单失败，需要更新商品价格后，重新下单;"',
  `orderPriceSnap` text NOT NULL COMMENT '客户端订单价格快照	 "Json格式的数据，格式为:[{""price"":21.30,"skuId":123123 },{ "price":99.55, "skuId":22222 }] //商品价格 ,类型：BigDecimal" //商品编号,类型：long',
  `reservingDate` int(11) DEFAULT NULL COMMENT '大家电配送日期	 | 	默认值为-1，0表示当天，1表示明天，2：表示后天; 如果为-1表示不使用大家电预约日历',
  `installDate` int(11) DEFAULT NULL COMMENT '大家电安装日期	 | 	不支持默认按-1处理，0表示当天，1表示明天，2：表示后天',
  `needInstall` int(11) NOT NULL COMMENT '大家电是否选择了安装	 | 	是否选择了安装，默认为true，选择了“暂缓安装”，此为必填项，必填值为false。',
  `promiseDate` varchar(64) NOT NULL COMMENT '中小件配送预约日期	 | 	格式：yyyy-MM-dd',
  `promiseTimeRange` varchar(64) NOT NULL COMMENT '中小件配送预约时间段	 | 	时间段如： 9:00-15:00',
  `promiseTimeRangeCode` int(11) NOT NULL COMMENT '中小件预约时间段的标记',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT 'createtime',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT 'updatetime',
  `response` text NOT NULL COMMENT 'api response',
  `jd_vop_success` tinyint(1) NOT NULL,
  `jd_vop_resultMessage` varchar(128) NOT NULL DEFAULT '',
  `jd_vop_resultCode` varchar(8) NOT NULL DEFAULT '',
  `jd_vop_code` int(11) NOT NULL,
  `jd_vop_result_jdOrderId` varchar(32) NOT NULL COMMENT '京东订单编号',
  `jd_vop_result_freight` int(11) NOT NULL DEFAULT '0' COMMENT '运费(合同配置了才返回此字段)',
  `jd_vop_result_orderPrice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品总价格',
  `jd_vop_result_orderNakedPrice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单裸价',
  `jd_vop_result_orderTaxPrice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单税额',
  `checking_by_third` int(8) NOT NULL DEFAULT '0' COMMENT '订单反查标记',
  `jd_vop_recheck_orderState` int(11) NOT NULL DEFAULT '0' COMMENT '反查之后更新orderState',
  `jd_vop_recheck_submitState` int(11) NOT NULL DEFAULT '0' COMMENT '反查之后更新submitState',
  `jd_vop_recheck_pOrder` varchar(32) NOT NULL DEFAULT '' COMMENT '用于拆单',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`) USING BTREE,
  KEY `thirdOrder` (`thirdOrder`) USING BTREE,
  KEY `checking_by_third` (`checking_by_third`) USING BTREE,
  KEY `jd_vop_result_jdOrderId_2` (`jd_vop_result_jdOrderId`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11547 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for ims_superdesk_jd_vop_order_submit_order_sku
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_jd_vop_order_submit_order_sku`;
CREATE TABLE `ims_superdesk_jd_vop_order_submit_order_sku` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pOrder` varchar(32) NOT NULL DEFAULT '' COMMENT '主订单号',
  `jdOrderId` varchar(32) NOT NULL COMMENT 'jdOrderId',
  `skuId` bigint(20) NOT NULL COMMENT 'skuId',
  `num` int(11) NOT NULL COMMENT '数量',
  `category` int(11) NOT NULL COMMENT '分类',
  `price` decimal(10,2) NOT NULL COMMENT '售价',
  `name` varchar(512) NOT NULL,
  `tax` int(11) NOT NULL,
  `taxPrice` decimal(10,2) NOT NULL COMMENT '税额',
  `nakedPrice` decimal(10,2) NOT NULL COMMENT '裸价',
  `type` int(11) NOT NULL COMMENT 'type为 0普通、1附件、2赠品',
  `oid` bigint(20) NOT NULL COMMENT 'oid为主商品skuid，如果本身是主商品，则oid为0',
  `shop_order_id` int(11) NOT NULL DEFAULT '0' COMMENT '商城订单ID',
  `shop_order_sn` varchar(64) NOT NULL DEFAULT '' COMMENT 'shop_order_sn',
  `shop_goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商城商品ID',
  `return_goods_nun` int(11) NOT NULL DEFAULT '0' COMMENT '京东退货数量',
  `return_goods_result` longtext NOT NULL COMMENT '京东退货信息',
  PRIMARY KEY (`id`),
  KEY `index_order_id` (`shop_order_id`,`shop_goods_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=29839 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for ims_superdesk_jd_vop_page_num
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_jd_vop_page_num`;
CREATE TABLE `ims_superdesk_jd_vop_page_num` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT 'uniacid',
  `page_num` int(11) NOT NULL COMMENT 'page_num',
  `name` varchar(128) NOT NULL DEFAULT '' COMMENT '商品池名字',
  `state` tinyint(3) NOT NULL DEFAULT '1' COMMENT 'state',
  `deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT '删除标记',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT 'createtime',
  `updatetime` int(11) NOT NULL COMMENT 'updatetime',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2653 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_jd_vop_product_check
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_jd_vop_product_check`;
CREATE TABLE `ims_superdesk_jd_vop_product_check` (
  `skuId` bigint(18) NOT NULL COMMENT 'skuId',
  `isCanVAT` int(3) NOT NULL DEFAULT '0' COMMENT '是否可开增票，1：支持，0：不支持',
  `is7ToReturn` int(3) NOT NULL DEFAULT '0' COMMENT '是否支持 7 天无理由退货，1：是，0：否',
  `createtime` int(11) NOT NULL COMMENT 'createtime',
  `updatetime` int(11) NOT NULL COMMENT 'updatetime',
  PRIMARY KEY (`skuId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for ims_superdesk_jd_vop_product_detail
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_jd_vop_product_detail`;
CREATE TABLE `ims_superdesk_jd_vop_product_detail` (
  `sku` bigint(20) NOT NULL COMMENT '单品',
  `name` varchar(512) NOT NULL COMMENT '商品名称',
  `page_num` varchar(16) NOT NULL DEFAULT '' COMMENT 'page_num',
  `category` varchar(32) NOT NULL COMMENT '类别',
  `upc` varchar(128) NOT NULL COMMENT '条形码',
  `saleUnit` varchar(16) NOT NULL DEFAULT '' COMMENT '销售单位',
  `weight` decimal(10,2) NOT NULL COMMENT '重量',
  `productArea` varchar(256) NOT NULL DEFAULT '' COMMENT '产地',
  `wareQD` varchar(256) NOT NULL COMMENT '商品清单',
  `imagePath` varchar(512) NOT NULL DEFAULT '' COMMENT '主图地址',
  `param` text NOT NULL,
  `brandName` varchar(256) NOT NULL DEFAULT '' COMMENT '品牌',
  `state` tinyint(3) NOT NULL COMMENT '上下架状态',
  `shouhou` text NOT NULL COMMENT '售后',
  `introduction` text NOT NULL COMMENT 'web介绍',
  `appintroduce` text NOT NULL COMMENT 'app介绍',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT 'createtime',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT 'updatetime',
  `lowestBuy` int(5) NOT NULL DEFAULT '0' COMMENT '最低购买数量',
  PRIMARY KEY (`sku`),
  KEY `idx_sku` (`sku`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for ims_superdesk_jd_vop_product_exts
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_jd_vop_product_exts`;
CREATE TABLE `ims_superdesk_jd_vop_product_exts` (
  `sku` bigint(20) NOT NULL COMMENT 'skuId',
  `category` varchar(32) NOT NULL COMMENT 'sku所属分类',
  `taxCode` varchar(32) NOT NULL COMMENT '税务编码',
  `isFactoryShip` int(11) NOT NULL COMMENT '是否厂商直送',
  `isEnergySaving` int(11) NOT NULL COMMENT '是否政府节能',
  `contractSkuExt` varchar(64) NOT NULL COMMENT '定制商品池开关',
  `ChinaCatalog` varchar(64) NOT NULL COMMENT '中图法分类号',
  `createtime` int(11) NOT NULL COMMENT 'createtime',
  `updatetime` int(11) NOT NULL COMMENT 'updatetime',
  PRIMARY KEY (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for ims_superdesk_jd_vop_product_price
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_jd_vop_product_price`;
CREATE TABLE `ims_superdesk_jd_vop_product_price` (
  `skuId` bigint(20) NOT NULL COMMENT 'skuId',
  `productprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '京东价格',
  `marketprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '客户购买价格',
  `costprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '协议价格',
  `createtime` int(11) NOT NULL COMMENT 'createtime',
  `updatetime` int(11) NOT NULL COMMENT 'updatetime',
  PRIMARY KEY (`skuId`),
  KEY `idx_sku` (`skuId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for ims_superdesk_jd_vop_search
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_jd_vop_search`;
CREATE TABLE `ims_superdesk_jd_vop_search` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(50) DEFAULT NULL,
  `has_keyword` tinyint(2) DEFAULT '0' COMMENT '是否有关键字(0否1是)',
  `keyword` varchar(512) DEFAULT '' COMMENT '搜索关键字',
  `has_pricebetween` tinyint(2) DEFAULT '0' COMMENT '是否有价格区间(0否1是)',
  `minprice` decimal(10,2) DEFAULT '0.00' COMMENT '最低价格',
  `maxprice` decimal(10,2) DEFAULT '0.00' COMMENT '最高价格',
  `has_filter` tinyint(2) DEFAULT '0' COMMENT '是否有筛选(0否1是)',
  `filters` varchar(255) DEFAULT '' COMMENT '筛选(以逗号分隔)',
  `has_cate` tinyint(2) DEFAULT '0' COMMENT '是否有选择分类(0否1是)',
  `cate` int(11) DEFAULT '0' COMMENT '分类id',
  `has_order` tinyint(2) DEFAULT '0' COMMENT '是否有排序(0否1是)',
  `order_by` varchar(64) DEFAULT '' COMMENT '排序',
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=379825 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_jd_vop_task_cron
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_jd_vop_task_cron`;
CREATE TABLE `ims_superdesk_jd_vop_task_cron` (
  `name` varchar(128) NOT NULL,
  `orde` int(10) NOT NULL DEFAULT '0',
  `file` varchar(100) NOT NULL DEFAULT '',
  `no` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `desc` text,
  `freq` int(10) NOT NULL DEFAULT '0',
  `lastdo` int(11) unsigned NOT NULL DEFAULT '0',
  `log` text,
  PRIMARY KEY (`name`),
  UNIQUE KEY `name` (`name`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_abonus_bill
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_abonus_bill`;
CREATE TABLE `ims_superdesk_shop_abonus_bill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `billno` varchar(100) DEFAULT '',
  `paytype` int(11) DEFAULT '0',
  `year` int(11) DEFAULT '0',
  `month` int(11) DEFAULT '0',
  `week` int(11) DEFAULT '0',
  `ordercount` int(11) DEFAULT '0',
  `ordermoney` decimal(10,2) DEFAULT '0.00',
  `paytime` int(11) DEFAULT '0',
  `aagentcount1` int(11) DEFAULT '0',
  `aagentcount2` int(11) DEFAULT '0',
  `aagentcount3` int(11) DEFAULT '0',
  `bonusmoney1` decimal(10,2) DEFAULT '0.00',
  `bonusmoney_send1` decimal(10,2) DEFAULT '0.00',
  `bonusmoney_pay1` decimal(10,2) DEFAULT '0.00',
  `bonusmoney2` decimal(10,2) DEFAULT '0.00',
  `bonusmoney_send2` decimal(10,2) DEFAULT '0.00',
  `bonusmoney_pay2` decimal(10,2) DEFAULT '0.00',
  `bonusmoney3` decimal(10,2) DEFAULT '0.00',
  `bonusmoney_send3` decimal(10,2) DEFAULT '0.00',
  `bonusmoney_pay3` decimal(10,2) DEFAULT '0.00',
  `createtime` int(11) DEFAULT '0',
  `status` tinyint(3) DEFAULT '0',
  `starttime` int(11) DEFAULT '0',
  `endtime` int(11) DEFAULT '0',
  `confirmtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_paytype` (`paytype`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE,
  KEY `idx_paytime` (`paytime`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE,
  KEY `idx_month` (`month`) USING BTREE,
  KEY `idx_week` (`week`) USING BTREE,
  KEY `idx_year` (`year`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_abonus_billo
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_abonus_billo`;
CREATE TABLE `ims_superdesk_shop_abonus_billo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `billid` int(11) DEFAULT '0',
  `orderid` int(11) DEFAULT '0',
  `ordermoney` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `idx_billid` (`billid`) USING BTREE,
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_abonus_billp
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_abonus_billp`;
CREATE TABLE `ims_superdesk_shop_abonus_billp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `billid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `payno` varchar(255) DEFAULT '',
  `paytype` tinyint(3) DEFAULT '0',
  `bonus1` decimal(10,4) DEFAULT '0.0000',
  `bonus2` decimal(10,4) DEFAULT '0.0000',
  `bonus3` decimal(10,4) DEFAULT '0.0000',
  `money1` decimal(10,2) DEFAULT '0.00',
  `realmoney1` decimal(10,2) DEFAULT '0.00',
  `paymoney1` decimal(10,2) DEFAULT '0.00',
  `money2` decimal(10,2) DEFAULT '0.00',
  `realmoney2` decimal(10,2) DEFAULT '0.00',
  `paymoney2` decimal(10,2) DEFAULT '0.00',
  `money3` decimal(10,2) DEFAULT '0.00',
  `realmoney3` decimal(10,2) DEFAULT '0.00',
  `paymoney3` decimal(10,2) DEFAULT '0.00',
  `chargemoney1` decimal(10,2) DEFAULT '0.00',
  `chargemoney2` decimal(10,2) DEFAULT '0.00',
  `chargemoney3` decimal(10,2) DEFAULT '0.00',
  `charge` decimal(10,2) DEFAULT '0.00',
  `status` tinyint(3) DEFAULT '0',
  `reason` varchar(255) DEFAULT '',
  `paytime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_billid` (`billid`) USING BTREE,
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_abonus_level
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_abonus_level`;
CREATE TABLE `ims_superdesk_shop_abonus_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `levelname` varchar(50) DEFAULT '',
  `bonus1` decimal(10,4) DEFAULT '0.0000',
  `bonus2` decimal(10,4) DEFAULT '0.0000',
  `bonus3` decimal(10,4) DEFAULT '0.0000',
  `ordermoney` decimal(10,2) DEFAULT '0.00',
  `ordercount` int(11) DEFAULT '0',
  `bonusmoney` decimal(10,2) DEFAULT '0.00',
  `downcount` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_adv
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_adv`;
CREATE TABLE `ims_superdesk_shop_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  `shopid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_enabled` (`enabled`) USING BTREE,
  KEY `idx_displayorder` (`displayorder`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_article
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_article`;
CREATE TABLE `ims_superdesk_shop_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_title` varchar(255) NOT NULL DEFAULT '' COMMENT '文章标题',
  `resp_desc` text NOT NULL COMMENT '回复介绍',
  `resp_img` text NOT NULL COMMENT '回复图片',
  `article_content` longtext,
  `article_category` int(11) NOT NULL DEFAULT '0' COMMENT '文章分类',
  `article_date_v` varchar(20) NOT NULL DEFAULT '' COMMENT '虚拟发布时间',
  `article_date` varchar(20) NOT NULL DEFAULT '' COMMENT '文章发布时间',
  `article_mp` varchar(50) NOT NULL DEFAULT '' COMMENT '公众号',
  `article_author` varchar(20) NOT NULL DEFAULT '' COMMENT '发布作者',
  `article_readnum_v` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟阅读量',
  `article_readnum` int(11) NOT NULL DEFAULT '0' COMMENT '真实阅读量',
  `article_likenum_v` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟点赞数',
  `article_likenum` int(11) NOT NULL DEFAULT '0' COMMENT '真实点赞数',
  `article_linkurl` varchar(300) NOT NULL DEFAULT '' COMMENT '阅读原文链接',
  `article_rule_daynum` int(11) NOT NULL DEFAULT '0' COMMENT '每人每天参与次数',
  `article_rule_allnum` int(11) NOT NULL DEFAULT '0' COMMENT '所有参与次数',
  `article_rule_credit` int(11) NOT NULL DEFAULT '0' COMMENT '增加y积分',
  `article_rule_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '增加z余额',
  `page_set_option_nocopy` int(1) NOT NULL DEFAULT '0' COMMENT '页面禁止复制url',
  `page_set_option_noshare_tl` int(1) NOT NULL DEFAULT '0' COMMENT '页面禁止分享至朋友圈',
  `page_set_option_noshare_msg` int(1) NOT NULL DEFAULT '0' COMMENT '页面禁止发送给好友',
  `article_keyword` varchar(255) NOT NULL DEFAULT '' COMMENT '页面关键字',
  `article_report` int(1) NOT NULL DEFAULT '0' COMMENT '举报按钮',
  `product_advs_type` int(1) NOT NULL DEFAULT '0' COMMENT '营销显示产品',
  `product_advs_title` varchar(255) NOT NULL DEFAULT '' COMMENT '营销产品标题',
  `product_advs_more` varchar(255) NOT NULL DEFAULT '' COMMENT '推广产品底部标题',
  `product_advs_link` varchar(255) NOT NULL DEFAULT '' COMMENT '推广产品底部链接',
  `product_advs` text NOT NULL COMMENT '营销商品',
  `article_state` int(1) NOT NULL DEFAULT '0',
  `network_attachment` varchar(255) DEFAULT '',
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `article_rule_credittotal` int(11) DEFAULT '0',
  `article_rule_moneytotal` decimal(10,2) DEFAULT '0.00',
  `article_rule_credit2` int(11) NOT NULL DEFAULT '0',
  `article_rule_money2` decimal(10,2) NOT NULL DEFAULT '0.00',
  `article_rule_creditm` int(11) NOT NULL DEFAULT '0',
  `article_rule_moneym` decimal(10,2) NOT NULL DEFAULT '0.00',
  `article_rule_creditm2` int(11) NOT NULL DEFAULT '0',
  `article_rule_moneym2` decimal(10,2) NOT NULL DEFAULT '0.00',
  `article_readtime` int(11) DEFAULT '0',
  `article_areas` varchar(255) DEFAULT '',
  `article_endtime` int(11) DEFAULT '0',
  `article_hasendtime` tinyint(3) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `article_keyword2` varchar(255) NOT NULL DEFAULT '',
  `article_advance` int(11) DEFAULT '0',
  `article_virtualadd` tinyint(3) DEFAULT '0',
  `article_visit` tinyint(3) DEFAULT '0',
  `article_visit_level` text,
  `article_visit_tip` varchar(500) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_article_title` (`article_title`) USING BTREE,
  KEY `idx_article_content` (`article_content`(10)) USING BTREE,
  KEY `idx_article_keyword` (`article_keyword`) USING BTREE,
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='营销文章';

-- ----------------------------
-- Table structure for ims_superdesk_shop_article_category
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_article_category`;
CREATE TABLE `ims_superdesk_shop_article_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL DEFAULT '' COMMENT '分类名称',
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `displayorder` int(11) NOT NULL DEFAULT '0',
  `isshow` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_category_name` (`category_name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_article_log
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_article_log`;
CREATE TABLE `ims_superdesk_shop_article_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '文章id',
  `read` int(11) NOT NULL DEFAULT '0',
  `like` int(11) NOT NULL DEFAULT '0',
  `openid` varchar(255) NOT NULL DEFAULT '' COMMENT '用户openid',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `uniacid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_aid` (`aid`) USING BTREE,
  KEY `idx_openid` (`openid`) USING BTREE,
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_article_report
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_article_report`;
CREATE TABLE `ims_superdesk_shop_article_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL DEFAULT '0',
  `openid` varchar(255) NOT NULL DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `aid` int(11) DEFAULT '0',
  `cate` varchar(255) NOT NULL DEFAULT '',
  `cons` varchar(255) NOT NULL DEFAULT '',
  `uniacid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_article_share
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_article_share`;
CREATE TABLE `ims_superdesk_shop_article_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) NOT NULL DEFAULT '0',
  `share_user` int(11) NOT NULL DEFAULT '0' COMMENT '分享人',
  `click_user` int(11) NOT NULL DEFAULT '0' COMMENT '点击人',
  `click_date` varchar(20) NOT NULL DEFAULT '' COMMENT '执行时间',
  `add_credit` int(11) NOT NULL DEFAULT '0' COMMENT '添加的积分',
  `add_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '添加的余额',
  `uniacid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_aid` (`aid`) USING BTREE,
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_article_sys
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_article_sys`;
CREATE TABLE `ims_superdesk_shop_article_sys` (
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `article_message` varchar(255) NOT NULL DEFAULT '',
  `article_title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `article_image` varchar(300) NOT NULL DEFAULT '' COMMENT '图片',
  `article_shownum` int(11) NOT NULL DEFAULT '0' COMMENT '每页数量',
  `article_keyword` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字',
  `article_temp` int(11) NOT NULL DEFAULT '0',
  `article_source` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`uniacid`),
  KEY `idx_article_message` (`article_message`) USING BTREE,
  KEY `idx_article_keyword` (`article_keyword`) USING BTREE,
  KEY `idx_article_title` (`article_title`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_author_bill
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_author_bill`;
CREATE TABLE `ims_superdesk_shop_author_bill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `billno` varchar(100) DEFAULT '',
  `paytype` int(11) DEFAULT '0',
  `year` int(11) DEFAULT '0',
  `month` int(11) DEFAULT '0',
  `week` int(11) DEFAULT '0',
  `ordercount` int(11) DEFAULT '0',
  `ordermoney` decimal(10,2) DEFAULT '0.00',
  `bonusordermoney` decimal(10,2) DEFAULT '0.00',
  `bonusrate` decimal(10,2) DEFAULT '0.00',
  `bonusmoney` decimal(10,2) DEFAULT '0.00',
  `bonusmoney_send` decimal(10,2) DEFAULT '0.00',
  `bonusmoney_pay` decimal(10,2) DEFAULT '0.00',
  `paytime` int(11) DEFAULT '0',
  `partnercount` int(11) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `status` tinyint(3) DEFAULT '0',
  `starttime` int(11) DEFAULT '0',
  `endtime` int(11) DEFAULT '0',
  `confirmtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_paytype` (`paytype`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE,
  KEY `idx_paytime` (`paytime`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE,
  KEY `idx_month` (`month`) USING BTREE,
  KEY `idx_week` (`week`) USING BTREE,
  KEY `idx_year` (`year`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_author_billo
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_author_billo`;
CREATE TABLE `ims_superdesk_shop_author_billo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `billid` int(11) DEFAULT '0',
  `authorid` int(11) DEFAULT NULL,
  `orderid` text,
  `ordermoney` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `idx_billid` (`billid`) USING BTREE,
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_author_billp
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_author_billp`;
CREATE TABLE `ims_superdesk_shop_author_billp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `billid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `payno` varchar(255) DEFAULT '',
  `paytype` tinyint(3) DEFAULT '0',
  `bonus` decimal(10,2) DEFAULT '0.00',
  `money` decimal(10,2) DEFAULT '0.00',
  `realmoney` decimal(10,2) DEFAULT '0.00',
  `paymoney` decimal(10,2) DEFAULT '0.00',
  `charge` decimal(10,2) DEFAULT '0.00',
  `chargemoney` decimal(10,2) DEFAULT '0.00',
  `status` tinyint(3) DEFAULT '0',
  `reason` varchar(255) DEFAULT '',
  `paytime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_billid` (`billid`) USING BTREE,
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_author_level
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_author_level`;
CREATE TABLE `ims_superdesk_shop_author_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `levelname` varchar(50) DEFAULT '',
  `bonus` decimal(10,4) DEFAULT '0.0000',
  `ordermoney` decimal(10,2) DEFAULT '0.00',
  `ordercount` int(11) DEFAULT '0',
  `commissionmoney` decimal(10,2) DEFAULT '0.00',
  `bonusmoney` decimal(10,2) DEFAULT '0.00',
  `downcount` int(11) DEFAULT '0',
  `bonus_fg` varchar(500) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_author_team
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_author_team`;
CREATE TABLE `ims_superdesk_shop_author_team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `teamno` varchar(50) DEFAULT '',
  `year` int(11) DEFAULT '0',
  `month` int(11) DEFAULT '0',
  `team_count` int(11) DEFAULT '0',
  `team_ids` longtext,
  `status` tinyint(1) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `paytime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `teamno` (`teamno`) USING BTREE,
  KEY `year` (`year`) USING BTREE,
  KEY `month` (`month`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `createtime` (`createtime`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_author_team_pay
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_author_team_pay`;
CREATE TABLE `ims_superdesk_shop_author_team_pay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `teamid` int(11) DEFAULT '0',
  `mid` int(11) DEFAULT '0',
  `payno` varchar(255) DEFAULT '',
  `money` decimal(10,2) DEFAULT '0.00',
  `paymoney` decimal(10,2) DEFAULT '0.00',
  `paytime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_teamid` (`teamid`) USING BTREE,
  KEY `idx_mid` (`mid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_banner
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_banner`;
CREATE TABLE `ims_superdesk_shop_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `bannername` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  `shopid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_enabled` (`enabled`) USING BTREE,
  KEY `idx_displayorder` (`displayorder`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_bargain_account
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_bargain_account`;
CREATE TABLE `ims_superdesk_shop_bargain_account` (
  `id` int(11) NOT NULL,
  `mall_name` varchar(255) DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `mall_title` varchar(255) DEFAULT NULL,
  `mall_content` varchar(255) DEFAULT NULL,
  `mall_logo` varchar(255) DEFAULT NULL,
  `message` int(11) DEFAULT '0',
  `partin` int(11) DEFAULT '0',
  `rule` text,
  `end_message` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_bargain_actor
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_bargain_actor`;
CREATE TABLE `ims_superdesk_shop_bargain_actor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL,
  `now_price` decimal(9,2) NOT NULL,
  `created_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `bargain_times` int(10) NOT NULL,
  `openid` varchar(50) NOT NULL DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `nickname` varchar(20) NOT NULL,
  `head_image` varchar(200) NOT NULL,
  `bargain_price` decimal(9,2) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `account_id` int(11) NOT NULL,
  `initiate` tinyint(4) NOT NULL DEFAULT '0',
  `order` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_bargain_goods
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_bargain_goods`;
CREATE TABLE `ims_superdesk_shop_bargain_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `goods_id` varchar(20) NOT NULL,
  `end_price` decimal(10,2) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `status` tinyint(2) NOT NULL,
  `type` tinyint(2) NOT NULL,
  `user_set` text,
  `rule` text,
  `act_times` int(11) NOT NULL,
  `mode` tinyint(4) NOT NULL,
  `total_time` int(11) NOT NULL,
  `each_time` int(11) NOT NULL,
  `time_limit` int(11) NOT NULL,
  `probability` text NOT NULL,
  `custom` varchar(255) DEFAULT NULL,
  `maximum` int(11) DEFAULT NULL,
  `initiate` tinyint(4) NOT NULL DEFAULT '0',
  `myself` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_bargain_record
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_bargain_record`;
CREATE TABLE `ims_superdesk_shop_bargain_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `actor_id` int(11) NOT NULL,
  `bargain_price` decimal(9,2) NOT NULL,
  `openid` varchar(50) NOT NULL DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `nickname` varchar(20) NOT NULL,
  `head_image` varchar(200) NOT NULL,
  `bargain_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_browser_logs
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_browser_logs`;
CREATE TABLE `ims_superdesk_shop_browser_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userAgent` text,
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_carrier
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_carrier`;
CREATE TABLE `ims_superdesk_shop_carrier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `realname` varchar(50) DEFAULT '',
  `mobile` varchar(50) DEFAULT '',
  `address` varchar(255) DEFAULT '',
  `deleted` tinyint(1) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_deleted` (`deleted`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_category
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_category`;
CREATE TABLE `ims_superdesk_shop_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) DEFAULT NULL COMMENT '分类名称',
  `thumb` varchar(255) DEFAULT NULL COMMENT '分类图片',
  `parentid` int(11) DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0' COMMENT '是否推荐',
  `description` varchar(500) DEFAULT NULL COMMENT '分类介绍',
  `displayorder` tinyint(3) unsigned DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) DEFAULT '1' COMMENT '是否开启',
  `ishome` tinyint(3) DEFAULT '0' COMMENT '是否首页显示',
  `advimg` varchar(255) DEFAULT '' COMMENT '广告图片',
  `advurl` varchar(500) DEFAULT '' COMMENT '广告链接',
  `level` tinyint(3) DEFAULT NULL COMMENT '分类是在几级',
  `jd_vop_page_num` bigint(14) NOT NULL DEFAULT '0' COMMENT 'jd_vop_page_num',
  `fiscal_code` varchar(32) NOT NULL DEFAULT '' COMMENT '财务代码',
  `old_shop_cate_id` int(11) NOT NULL DEFAULT '0' COMMENT 'old_shop_cate_id',
  `cateType` tinyint(4) DEFAULT '1' COMMENT '分类类型(1:京东,2:内部,3:严选)',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_displayorder` (`displayorder`) USING BTREE,
  KEY `idx_enabled` (`enabled`) USING BTREE,
  KEY `idx_parentid` (`parentid`) USING BTREE,
  KEY `idx_isrecommand` (`isrecommand`) USING BTREE,
  KEY `idx_ishome` (`ishome`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=21081 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_category_20190715
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_category_20190715`;
CREATE TABLE `ims_superdesk_shop_category_20190715` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) DEFAULT NULL COMMENT '分类名称',
  `thumb` varchar(255) DEFAULT NULL COMMENT '分类图片',
  `parentid` int(11) DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0' COMMENT '是否推荐',
  `description` varchar(500) DEFAULT NULL COMMENT '分类介绍',
  `displayorder` tinyint(3) unsigned DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) DEFAULT '1' COMMENT '是否开启',
  `ishome` tinyint(3) DEFAULT '0' COMMENT '是否首页显示',
  `advimg` varchar(255) DEFAULT '' COMMENT '广告图片',
  `advurl` varchar(500) DEFAULT '' COMMENT '广告链接',
  `level` tinyint(3) DEFAULT NULL COMMENT '分类是在几级',
  `jd_vop_page_num` bigint(14) NOT NULL DEFAULT '0' COMMENT 'jd_vop_page_num',
  `fiscal_code` varchar(32) NOT NULL DEFAULT '' COMMENT '财务代码',
  `old_shop_cate_id` int(11) NOT NULL DEFAULT '0' COMMENT 'old_shop_cate_id',
  `cateType` tinyint(4) DEFAULT '1' COMMENT '分类类型(1:京东,2:内部,3:严选)',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_displayorder` (`displayorder`) USING BTREE,
  KEY `idx_enabled` (`enabled`) USING BTREE,
  KEY `idx_parentid` (`parentid`) USING BTREE,
  KEY `idx_isrecommand` (`isrecommand`) USING BTREE,
  KEY `idx_ishome` (`ishome`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=19075 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_category_enterprise_discount
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_category_enterprise_discount`;
CREATE TABLE `ims_superdesk_shop_category_enterprise_discount` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT '0' COMMENT '分类id',
  `core_enterprise` int(11) DEFAULT '0' COMMENT '企业id',
  `merchid` int(11) DEFAULT '0' COMMENT '商户id',
  `type` tinyint(4) DEFAULT '1' COMMENT '类型(1:折扣,2:成本价)',
  `discount` decimal(4,3) DEFAULT '0.000' COMMENT '折扣(%作单位,100%即不打折)',
  `createtime` int(11) DEFAULT NULL,
  `updatetime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1323 DEFAULT CHARSET=utf8 COMMENT='分类企业折扣表';

-- ----------------------------
-- Table structure for ims_superdesk_shop_category_relation
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_category_relation`;
CREATE TABLE `ims_superdesk_shop_category_relation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `self_category` int(11) DEFAULT NULL,
  `other_category` int(11) DEFAULT NULL,
  `createtime` int(11) DEFAULT NULL,
  `updatetime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=273 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_comments_goods
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_comments_goods`;
CREATE TABLE `ims_superdesk_shop_comments_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) DEFAULT NULL,
  `goodsid` int(11) DEFAULT NULL,
  `level` tinyint(1) DEFAULT NULL COMMENT '商品质量评分',
  `content` varchar(255) DEFAULT NULL COMMENT '评价内容',
  `createtime` int(11) DEFAULT NULL COMMENT '评价时间',
  `merchid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_goodsid` (`goodsid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COMMENT='评价系统-商品质量评分表';

-- ----------------------------
-- Table structure for ims_superdesk_shop_comments_report
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_comments_report`;
CREATE TABLE `ims_superdesk_shop_comments_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `merchid` int(11) DEFAULT NULL COMMENT '商户id',
  `com_logis` decimal(6,2) DEFAULT NULL COMMENT '综合物流',
  `com_service` decimal(6,2) DEFAULT NULL COMMENT '综合服务',
  `com_describes` decimal(6,2) DEFAULT NULL COMMENT '综合描述',
  `compr` decimal(6,2) DEFAULT NULL COMMENT '综合以上三项评分',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='评价商户3项维度评分报表';

-- ----------------------------
-- Table structure for ims_superdesk_shop_comments_report_goods
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_comments_report_goods`;
CREATE TABLE `ims_superdesk_shop_comments_report_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `merchid` int(11) DEFAULT NULL,
  `orderid` int(11) DEFAULT NULL,
  `goodsid` int(11) DEFAULT NULL,
  `com_level` decimal(6,2) DEFAULT NULL COMMENT '综合评分',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='商品报表维度综合评分表';

-- ----------------------------
-- Table structure for ims_superdesk_shop_commission_apply
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_commission_apply`;
CREATE TABLE `ims_superdesk_shop_commission_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `applyno` varchar(255) DEFAULT '',
  `mid` int(11) DEFAULT '0' COMMENT '会员ID',
  `type` tinyint(3) DEFAULT '0' COMMENT '0 余额 1 微信',
  `orderids` longtext,
  `commission` decimal(10,2) DEFAULT '0.00',
  `commission_pay` decimal(10,2) DEFAULT '0.00',
  `content` text,
  `status` tinyint(3) DEFAULT '0' COMMENT '-1 无效 0 未知 1 正在申请 2 审核通过 3 已经打款',
  `applytime` int(11) DEFAULT '0',
  `checktime` int(11) DEFAULT '0',
  `paytime` int(11) DEFAULT '0',
  `invalidtime` int(11) DEFAULT '0',
  `refusetime` int(11) DEFAULT '0',
  `realmoney` decimal(10,2) DEFAULT '0.00',
  `charge` decimal(10,2) DEFAULT '0.00',
  `deductionmoney` decimal(10,2) DEFAULT '0.00',
  `beginmoney` decimal(10,2) DEFAULT '0.00',
  `endmoney` decimal(10,2) DEFAULT '0.00',
  `alipay` varchar(50) NOT NULL DEFAULT '',
  `bankname` varchar(50) NOT NULL DEFAULT '',
  `bankcard` varchar(50) NOT NULL DEFAULT '',
  `realname` varchar(50) NOT NULL DEFAULT '',
  `repurchase` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_mid` (`mid`) USING BTREE,
  KEY `idx_checktime` (`checktime`) USING BTREE,
  KEY `idx_paytime` (`paytime`) USING BTREE,
  KEY `idx_applytime` (`applytime`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE,
  KEY `idx_invalidtime` (`invalidtime`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_commission_bank
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_commission_bank`;
CREATE TABLE `ims_superdesk_shop_commission_bank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `bankname` varchar(255) NOT NULL DEFAULT '',
  `content` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `displayorder` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_commission_clickcount
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_commission_clickcount`;
CREATE TABLE `ims_superdesk_shop_commission_clickcount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `from_openid` varchar(255) DEFAULT '',
  `clicktime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_openid` (`openid`) USING BTREE,
  KEY `idx_from_openid` (`from_openid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=611 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_commission_level
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_commission_level`;
CREATE TABLE `ims_superdesk_shop_commission_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `levelname` varchar(50) DEFAULT '',
  `commission1` decimal(10,2) DEFAULT '0.00',
  `commission2` decimal(10,2) DEFAULT '0.00',
  `commission3` decimal(10,2) DEFAULT '0.00',
  `commissionmoney` decimal(10,2) DEFAULT '0.00',
  `ordermoney` decimal(10,2) DEFAULT '0.00',
  `downcount` varchar(255) DEFAULT '',
  `ordercount` int(11) DEFAULT '0',
  `withdraw` decimal(10,2) DEFAULT '0.00',
  `repurchase` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_commission_log
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_commission_log`;
CREATE TABLE `ims_superdesk_shop_commission_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `applyid` int(11) DEFAULT '0',
  `mid` int(11) DEFAULT '0',
  `commission` decimal(10,2) DEFAULT '0.00',
  `createtime` int(11) DEFAULT '0',
  `commission_pay` decimal(10,2) DEFAULT '0.00',
  `realmoney` decimal(10,2) DEFAULT '0.00',
  `charge` decimal(10,2) DEFAULT '0.00',
  `deductionmoney` decimal(10,2) DEFAULT '0.00',
  `type` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_applyid` (`applyid`) USING BTREE,
  KEY `idx_mid` (`mid`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_commission_rank
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_commission_rank`;
CREATE TABLE `ims_superdesk_shop_commission_rank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `num` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_commission_repurchase
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_commission_repurchase`;
CREATE TABLE `ims_superdesk_shop_commission_repurchase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `year` int(4) DEFAULT '0',
  `month` tinyint(2) DEFAULT '0',
  `repurchase` decimal(10,2) DEFAULT '0.00',
  `applyid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `applyid` (`applyid`) USING BTREE,
  KEY `openid` (`openid`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_commission_shop
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_commission_shop`;
CREATE TABLE `ims_superdesk_shop_commission_shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `mid` int(11) DEFAULT '0',
  `name` varchar(255) DEFAULT '',
  `logo` varchar(255) DEFAULT '',
  `img` varchar(255) DEFAULT NULL,
  `desc` varchar(255) DEFAULT '',
  `selectgoods` tinyint(3) DEFAULT '0',
  `selectcategory` tinyint(3) DEFAULT '0',
  `goodsids` text,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_mid` (`mid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_coupon
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_coupon`;
CREATE TABLE `ims_superdesk_shop_coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `catid` int(11) DEFAULT '0',
  `couponname` varchar(255) DEFAULT '',
  `gettype` tinyint(3) DEFAULT '0',
  `getmax` int(11) DEFAULT '0',
  `usetype` tinyint(3) DEFAULT '0' COMMENT '消费方式 0 付款使用 1 下单使用',
  `returntype` tinyint(3) DEFAULT '0' COMMENT '退回方式 0 不可退回 1 取消订单(未付款) 2.退款可以退回',
  `bgcolor` varchar(255) DEFAULT '',
  `enough` decimal(10,2) DEFAULT '0.00',
  `timelimit` tinyint(3) DEFAULT '0' COMMENT '0 领取后几天有效 1 时间范围',
  `coupontype` tinyint(3) DEFAULT '0' COMMENT '0 优惠券 1 充值券',
  `timedays` int(11) DEFAULT '0',
  `timestart` int(11) DEFAULT '0',
  `timeend` int(11) DEFAULT '0',
  `discount` decimal(10,2) DEFAULT '0.00' COMMENT '折扣',
  `deduct` decimal(10,2) DEFAULT '0.00' COMMENT '抵扣',
  `backtype` tinyint(3) DEFAULT '0',
  `backmoney` varchar(50) DEFAULT '' COMMENT '返现',
  `backcredit` varchar(50) DEFAULT '' COMMENT '返积分',
  `backredpack` varchar(50) DEFAULT '',
  `backwhen` tinyint(3) DEFAULT '0',
  `thumb` varchar(255) DEFAULT '',
  `desc` text,
  `createtime` int(11) DEFAULT '0',
  `total` int(11) DEFAULT '0' COMMENT '数量 -1 不限制',
  `status` tinyint(3) DEFAULT '0' COMMENT '可用',
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '购买价格',
  `respdesc` text COMMENT '推送描述',
  `respthumb` varchar(255) DEFAULT '' COMMENT '推送图片',
  `resptitle` varchar(255) DEFAULT '' COMMENT '推送标题',
  `respurl` varchar(255) DEFAULT '',
  `credit` int(11) DEFAULT '0',
  `usecredit2` tinyint(3) DEFAULT '0',
  `remark` varchar(1000) DEFAULT '',
  `descnoset` tinyint(3) DEFAULT '0',
  `pwdkey` varchar(255) DEFAULT '',
  `pwdsuc` text,
  `pwdfail` text,
  `pwdurl` varchar(255) DEFAULT '',
  `pwdask` text,
  `pwdstatus` tinyint(3) DEFAULT '0',
  `pwdtimes` int(11) DEFAULT '0',
  `pwdfull` text,
  `pwdwords` text,
  `pwdopen` tinyint(3) DEFAULT '0',
  `pwdown` text,
  `pwdexit` varchar(255) DEFAULT '',
  `pwdexitstr` text,
  `displayorder` int(11) DEFAULT '0',
  `pwdkey2` varchar(255) DEFAULT '',
  `merchid` int(11) DEFAULT '0',
  `limitgoodtype` tinyint(1) DEFAULT '0',
  `limitgoodcatetype` tinyint(1) DEFAULT '0',
  `limitgoodcateids` varchar(500) DEFAULT '',
  `limitgoodids` varchar(500) DEFAULT '',
  `islimitlevel` tinyint(1) DEFAULT '0',
  `limitmemberlevels` varchar(500) DEFAULT '',
  `limitagentlevels` varchar(500) DEFAULT '',
  `limitpartnerlevels` varchar(500) DEFAULT '',
  `limitaagentlevels` varchar(500) DEFAULT '',
  `tagtitle` varchar(20) DEFAULT '',
  `settitlecolor` tinyint(1) DEFAULT '0',
  `titlecolor` varchar(10) DEFAULT '',
  `limitdiscounttype` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_coupontype` (`coupontype`) USING BTREE,
  KEY `idx_timestart` (`timestart`) USING BTREE,
  KEY `idx_timeend` (`timeend`) USING BTREE,
  KEY `idx_timelimit` (`timelimit`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE,
  KEY `idx_givetype` (`backtype`) USING BTREE,
  KEY `idx_catid` (`catid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_coupon_category
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_coupon_category`;
CREATE TABLE `ims_superdesk_shop_coupon_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `name` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `merchid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_displayorder` (`displayorder`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_coupon_data
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_coupon_data`;
CREATE TABLE `ims_superdesk_shop_coupon_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `couponid` int(11) DEFAULT '0',
  `gettype` tinyint(3) DEFAULT '0' COMMENT '获取方式 0 发放 1 领取 2 积分商城',
  `used` int(11) DEFAULT '0',
  `usetime` int(11) DEFAULT '0',
  `gettime` int(11) DEFAULT '0' COMMENT '获取时间',
  `senduid` int(11) DEFAULT '0',
  `ordersn` varchar(255) DEFAULT '',
  `back` tinyint(3) DEFAULT '0',
  `backtime` int(11) DEFAULT '0',
  `merchid` int(11) DEFAULT '0',
  `isnew` tinyint(1) DEFAULT '1',
  `nocount` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_couponid` (`couponid`) USING BTREE,
  KEY `idx_gettype` (`gettype`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_coupon_goodsendtask
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_coupon_goodsendtask`;
CREATE TABLE `ims_superdesk_shop_coupon_goodsendtask` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `goodsid` int(11) DEFAULT '0',
  `couponid` int(11) DEFAULT '0',
  `starttime` int(11) DEFAULT '0',
  `endtime` int(11) DEFAULT '0',
  `sendnum` int(11) DEFAULT '1',
  `num` int(11) DEFAULT '0',
  `sendpoint` tinyint(1) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_coupon_guess
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_coupon_guess`;
CREATE TABLE `ims_superdesk_shop_coupon_guess` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `couponid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `times` int(11) DEFAULT '0',
  `pwdkey` varchar(255) DEFAULT '',
  `ok` tinyint(3) DEFAULT '0',
  `merchid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_couponid` (`couponid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_coupon_log
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_coupon_log`;
CREATE TABLE `ims_superdesk_shop_coupon_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `logno` varchar(255) DEFAULT '',
  `openid` varchar(255) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `couponid` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `paystatus` tinyint(3) DEFAULT '0',
  `creditstatus` tinyint(3) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `paytype` tinyint(3) DEFAULT '0',
  `getfrom` tinyint(3) DEFAULT '0',
  `merchid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_couponid` (`couponid`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE,
  KEY `idx_paystatus` (`paystatus`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE,
  KEY `idx_getfrom` (`getfrom`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_coupon_sendshow
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_coupon_sendshow`;
CREATE TABLE `ims_superdesk_shop_coupon_sendshow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `showkey` varchar(20) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `openid` varchar(255) NOT NULL,
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `coupondataid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_coupon_sendtasks
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_coupon_sendtasks`;
CREATE TABLE `ims_superdesk_shop_coupon_sendtasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `enough` decimal(10,2) DEFAULT '0.00',
  `couponid` int(11) DEFAULT '0',
  `starttime` int(11) DEFAULT '0',
  `endtime` int(11) DEFAULT '0',
  `sendnum` int(11) DEFAULT '1',
  `num` int(11) DEFAULT '0',
  `sendpoint` tinyint(1) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_coupon_taskdata
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_coupon_taskdata`;
CREATE TABLE `ims_superdesk_shop_coupon_taskdata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL,
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `taskid` int(11) DEFAULT '0',
  `couponid` int(11) DEFAULT '0',
  `sendnum` int(11) DEFAULT '0',
  `tasktype` tinyint(1) DEFAULT '0',
  `orderid` int(11) DEFAULT '0',
  `parentorderid` int(11) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `sendpoint` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_creditshop_adv
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_creditshop_adv`;
CREATE TABLE `ims_superdesk_shop_creditshop_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_enabled` (`enabled`) USING BTREE,
  KEY `idx_displayorder` (`displayorder`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_creditshop_category
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_creditshop_category`;
CREATE TABLE `ims_superdesk_shop_creditshop_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `name` varchar(50) DEFAULT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  `displayorder` tinyint(3) unsigned DEFAULT '0',
  `enabled` tinyint(1) DEFAULT '1',
  `advimg` varchar(255) DEFAULT '',
  `advurl` varchar(500) DEFAULT '',
  `isrecommand` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_displayorder` (`displayorder`) USING BTREE,
  KEY `idx_enabled` (`enabled`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_creditshop_goods
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_creditshop_goods`;
CREATE TABLE `ims_superdesk_shop_creditshop_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `cate` int(11) DEFAULT '0',
  `thumb` varchar(255) DEFAULT '',
  `price` decimal(10,2) DEFAULT '0.00',
  `type` tinyint(3) DEFAULT '0',
  `credit` int(11) DEFAULT '0',
  `money` decimal(10,2) DEFAULT '0.00',
  `total` int(11) DEFAULT '0',
  `totalday` int(11) DEFAULT '0',
  `chance` int(11) DEFAULT '0',
  `chanceday` int(11) DEFAULT '0',
  `detail` text,
  `rate1` int(11) DEFAULT '0',
  `rate2` int(11) DEFAULT '0',
  `endtime` int(11) DEFAULT '0',
  `joins` int(11) DEFAULT '0',
  `views` int(11) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `status` tinyint(3) DEFAULT '0',
  `deleted` tinyint(3) DEFAULT '0',
  `showlevels` text,
  `buylevels` text,
  `showgroups` text,
  `buygroups` text,
  `vip` tinyint(3) DEFAULT '0',
  `istop` tinyint(3) DEFAULT '0',
  `isrecommand` tinyint(3) DEFAULT '0',
  `istime` tinyint(3) DEFAULT '0',
  `timestart` int(11) DEFAULT '0',
  `timeend` int(11) DEFAULT '0',
  `share_title` varchar(255) DEFAULT '',
  `share_icon` varchar(255) DEFAULT '',
  `share_desc` varchar(500) DEFAULT '',
  `followneed` tinyint(3) DEFAULT '0',
  `followtext` varchar(255) DEFAULT '',
  `subtitle` varchar(255) DEFAULT '',
  `subdetail` text,
  `noticedetail` text,
  `usedetail` varchar(255) DEFAULT '',
  `goodsdetail` text,
  `isendtime` tinyint(3) DEFAULT '0',
  `usecredit2` tinyint(3) DEFAULT '0',
  `area` varchar(255) DEFAULT '',
  `dispatch` decimal(10,2) DEFAULT '0.00',
  `storeids` text,
  `noticeopenid` varchar(255) DEFAULT '',
  `noticetype` tinyint(3) DEFAULT '0',
  `isverify` tinyint(3) DEFAULT '0',
  `goodstype` tinyint(3) DEFAULT '0',
  `couponid` int(11) DEFAULT '0',
  `goodsid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_type` (`type`) USING BTREE,
  KEY `idx_endtime` (`endtime`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE,
  KEY `idx_displayorder` (`displayorder`) USING BTREE,
  KEY `idx_deleted` (`deleted`) USING BTREE,
  KEY `idx_istop` (`istop`) USING BTREE,
  KEY `idx_isrecommand` (`isrecommand`) USING BTREE,
  KEY `idx_istime` (`istime`) USING BTREE,
  KEY `idx_timestart` (`timestart`) USING BTREE,
  KEY `idx_timeend` (`timeend`) USING BTREE,
  KEY `idx_goodstype` (`goodstype`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_creditshop_log
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_creditshop_log`;
CREATE TABLE `ims_superdesk_shop_creditshop_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `logno` varchar(255) DEFAULT '',
  `eno` varchar(255) DEFAULT '',
  `openid` varchar(255) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `goodsid` int(11) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `status` tinyint(3) DEFAULT '0',
  `paystatus` tinyint(3) DEFAULT '0',
  `paytype` tinyint(3) DEFAULT '-1',
  `dispatchstatus` tinyint(3) DEFAULT '0',
  `creditpay` tinyint(3) DEFAULT '0',
  `addressid` int(11) DEFAULT '0',
  `dispatchno` varchar(255) DEFAULT '',
  `usetime` int(11) DEFAULT '0',
  `express` varchar(255) DEFAULT '',
  `expresssn` varchar(255) DEFAULT '',
  `expresscom` varchar(255) DEFAULT '',
  `verifyopenid` varchar(255) DEFAULT '',
  `transid` varchar(255) DEFAULT '',
  `dispatchtransid` varchar(255) DEFAULT '',
  `storeid` int(11) DEFAULT '0',
  `realname` varchar(255) DEFAULT '',
  `mobile` varchar(255) DEFAULT '',
  `couponid` int(11) DEFAULT '0',
  `dupdate1` tinyint(3) DEFAULT '0',
  `address` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_customer
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_customer`;
CREATE TABLE `ims_superdesk_shop_customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `kf_id` varchar(255) DEFAULT NULL,
  `kf_account` varchar(255) DEFAULT '',
  `kf_nick` varchar(255) DEFAULT '',
  `kf_pwd` varchar(255) DEFAULT '',
  `kf_headimgurl` varchar(255) DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_customer_category
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_customer_category`;
CREATE TABLE `ims_superdesk_shop_customer_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_customer_guestbook
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_customer_guestbook`;
CREATE TABLE `ims_superdesk_shop_customer_guestbook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `realname` varchar(11) DEFAULT '',
  `mobile` varchar(255) DEFAULT '',
  `weixin` varchar(255) DEFAULT '',
  `images` text,
  `content` text,
  `remark` text,
  `status` tinyint(3) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `deleted` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_customer_robot
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_customer_robot`;
CREATE TABLE `ims_superdesk_shop_customer_robot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `cate` int(11) DEFAULT '0',
  `keywords` varchar(500) DEFAULT '',
  `title` varchar(255) DEFAULT '',
  `content` longtext,
  `url` varchar(255) DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_cate` (`cate`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_designer
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_designer`;
CREATE TABLE `ims_superdesk_shop_designer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `pagename` varchar(255) NOT NULL DEFAULT '',
  `pagetype` tinyint(3) NOT NULL DEFAULT '0',
  `pageinfo` text NOT NULL,
  `createtime` varchar(255) NOT NULL DEFAULT '',
  `keyword` varchar(255) DEFAULT '',
  `savetime` varchar(255) NOT NULL DEFAULT '',
  `setdefault` tinyint(3) NOT NULL DEFAULT '0',
  `datas` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_pagetype` (`pagetype`) USING BTREE,
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_keyword` (`keyword`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_designer_menu
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_designer_menu`;
CREATE TABLE `ims_superdesk_shop_designer_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `menuname` varchar(255) DEFAULT '',
  `isdefault` tinyint(3) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `menus` text,
  `params` text,
  PRIMARY KEY (`id`),
  KEY `idx_createtime` (`createtime`) USING BTREE,
  KEY `idx_isdefault` (`isdefault`) USING BTREE,
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_discount_enterprise_mapping
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_discount_enterprise_mapping`;
CREATE TABLE `ims_superdesk_shop_discount_enterprise_mapping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `core_enterprise` int(11) NOT NULL COMMENT '客户企业ID',
  `discount_id` bigint(22) NOT NULL COMMENT '折扣ID',
  `status` tinyint(1) NOT NULL COMMENT '状态 0为禁用 1为启用',
  `operator_id` int(11) DEFAULT NULL COMMENT '操作ID',
  `operator_name` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '操作真姓名',
  `createtime` int(11) DEFAULT NULL,
  `updatetime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=401 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for ims_superdesk_shop_discount_merchid
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_discount_merchid`;
CREATE TABLE `ims_superdesk_shop_discount_merchid` (
  `discount_id` bigint(20) NOT NULL,
  `uniacid` int(11) NOT NULL COMMENT 'uniacid',
  `discount_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '价套名称',
  `discount_merchid` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '供应商折扣 {[merchid:id,value:val]}',
  `price_type` tinyint(1) NOT NULL COMMENT '价格类型 1 销售价 2 成本价',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0禁用 1启用',
  `remark` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `operator_id` int(11) DEFAULT NULL COMMENT '操作人ID',
  `operator_name` char(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '操作人姓名',
  `createtime` int(11) DEFAULT NULL,
  `updatetime` int(11) DEFAULT NULL,
  PRIMARY KEY (`discount_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='给供应商设置折扣';

-- ----------------------------
-- Table structure for ims_superdesk_shop_dispatch
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_dispatch`;
CREATE TABLE `ims_superdesk_shop_dispatch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `dispatchname` varchar(50) DEFAULT '',
  `dispatchtype` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `firstprice` decimal(10,2) DEFAULT '0.00',
  `secondprice` decimal(10,2) DEFAULT '0.00',
  `firstweight` int(11) DEFAULT '0',
  `secondweight` int(11) DEFAULT '0',
  `express` varchar(250) DEFAULT '',
  `areas` text,
  `carriers` text,
  `enabled` int(11) DEFAULT '0',
  `calculatetype` tinyint(1) DEFAULT '0',
  `firstnum` int(11) DEFAULT '0',
  `secondnum` int(11) DEFAULT '0',
  `firstnumprice` decimal(10,2) DEFAULT '0.00',
  `secondnumprice` decimal(10,2) DEFAULT '0.00',
  `isdefault` tinyint(1) DEFAULT '0',
  `shopid` int(11) DEFAULT '0',
  `merchid` int(11) DEFAULT '0',
  `nodispatchareas` text,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_displayorder` (`displayorder`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_diyform_category
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_diyform_category`;
CREATE TABLE `ims_superdesk_shop_diyform_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_diyform_data
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_diyform_data`;
CREATE TABLE `ims_superdesk_shop_diyform_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `typeid` int(11) NOT NULL DEFAULT '0',
  `cid` int(11) DEFAULT '0',
  `diyformfields` text,
  `fields` text NOT NULL,
  `openid` varchar(255) NOT NULL DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `type` tinyint(2) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_typeid` (`typeid`) USING BTREE,
  KEY `idx_cid` (`cid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_diyform_temp
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_diyform_temp`;
CREATE TABLE `ims_superdesk_shop_diyform_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `typeid` int(11) DEFAULT '0',
  `cid` int(11) NOT NULL DEFAULT '0',
  `diyformfields` text,
  `fields` text NOT NULL,
  `openid` varchar(255) NOT NULL DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `type` tinyint(1) DEFAULT '0',
  `diyformid` int(11) DEFAULT '0',
  `diyformdata` text,
  `carrier_realname` varchar(255) DEFAULT '',
  `carrier_mobile` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_cid` (`cid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_diyform_type
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_diyform_type`;
CREATE TABLE `ims_superdesk_shop_diyform_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `cate` int(11) DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `fields` text NOT NULL,
  `usedata` int(11) NOT NULL DEFAULT '0',
  `alldata` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_cate` (`cate`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_diypage
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_diypage`;
CREATE TABLE `ims_superdesk_shop_diypage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `data` longtext NOT NULL,
  `createtime` int(11) NOT NULL DEFAULT '0',
  `lastedittime` int(11) NOT NULL DEFAULT '0',
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `diymenu` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_type` (`type`) USING BTREE,
  KEY `idx_keyword` (`keyword`) USING BTREE,
  KEY `idx_lastedittime` (`lastedittime`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_diypage_menu
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_diypage_menu`;
CREATE TABLE `ims_superdesk_shop_diypage_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `data` text NOT NULL,
  `createtime` int(11) NOT NULL DEFAULT '0',
  `lastedittime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE,
  KEY `idx_lastedittime` (`lastedittime`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_diypage_template
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_diypage_template`;
CREATE TABLE `ims_superdesk_shop_diypage_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `type` tinyint(3) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `data` longtext NOT NULL,
  `preview` varchar(255) NOT NULL DEFAULT '',
  `tplid` int(11) DEFAULT '0',
  `cate` int(11) DEFAULT '0',
  `deleted` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_type` (`type`) USING BTREE,
  KEY `idx_cate` (`cate`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_diypage_template_category
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_diypage_template_category`;
CREATE TABLE `ims_superdesk_shop_diypage_template_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_enterprise_account
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_enterprise_account`;
CREATE TABLE `ims_superdesk_shop_enterprise_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `core_enterprise` int(11) DEFAULT '0' COMMENT '企业id',
  `username` varchar(255) DEFAULT '' COMMENT '用户名',
  `pwd` varchar(255) DEFAULT '' COMMENT '用户密码',
  `salt` varchar(255) DEFAULT '' COMMENT '密码加盐',
  `status` tinyint(3) DEFAULT '0',
  `perms` text COMMENT '权限',
  `isfounder` tinyint(3) DEFAULT '0' COMMENT '是否创始人',
  `lastip` varchar(255) DEFAULT '' COMMENT '最后IP',
  `lastvisit` varchar(255) DEFAULT '' COMMENT '最后访问',
  `roleid` int(11) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `updatetime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_merchid` (`core_enterprise`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_enterprise_category
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_enterprise_category`;
CREATE TABLE `ims_superdesk_shop_enterprise_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `catename` varchar(255) DEFAULT '' COMMENT '企业分类名',
  `createtime` int(11) DEFAULT '0' COMMENT '分类建立时间',
  `status` tinyint(1) DEFAULT '0' COMMENT '是否显示',
  `displayorder` int(11) DEFAULT '0' COMMENT '分类排序',
  `thumb` varchar(500) DEFAULT '' COMMENT '分类图片',
  `isrecommand` tinyint(1) DEFAULT '0' COMMENT '是否首页推荐',
  `updatetime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_enterprise_group
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_enterprise_group`;
CREATE TABLE `ims_superdesk_shop_enterprise_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `groupname` varchar(255) DEFAULT '' COMMENT '企业组名',
  `createtime` int(11) DEFAULT '0' COMMENT '建立时间',
  `status` tinyint(3) DEFAULT '0' COMMENT '组状态',
  `isdefault` tinyint(1) DEFAULT '0' COMMENT '是否默认',
  `updatetime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_enterprise_import_log
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_enterprise_import_log`;
CREATE TABLE `ims_superdesk_shop_enterprise_import_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT NULL,
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `enterprise_id` int(11) DEFAULT '0' COMMENT '企业id',
  `realname` varchar(50) DEFAULT '' COMMENT '导入时的真实姓名',
  `nickname` varchar(50) DEFAULT '' COMMENT '导入时的昵称',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '导入时要增加的金额',
  `mobile` varchar(11) DEFAULT '0' COMMENT '电话号码',
  `createtime` int(11) DEFAULT '0',
  `updatetime` int(11) DEFAULT '0',
  `is_old` tinyint(2) DEFAULT '0' COMMENT '是否已有的记录(0否1是)',
  `import_sn` varchar(50) DEFAULT '' COMMENT '导入excel名称',
  `account_id` int(11) DEFAULT '0' COMMENT '操作员id',
  `old_price` decimal(10,2) DEFAULT '0.00' COMMENT '原金额',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='企业端员工导入记录表';

-- ----------------------------
-- Table structure for ims_superdesk_shop_enterprise_perm_log
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_enterprise_perm_log`;
CREATE TABLE `ims_superdesk_shop_enterprise_perm_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `name` varchar(255) DEFAULT '',
  `type` varchar(255) DEFAULT '',
  `op` text,
  `ip` varchar(255) DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  `core_enterprise` int(11) DEFAULT '0' COMMENT '企业id',
  `updatetime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_createtime` (`createtime`) USING BTREE,
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_merchid` (`core_enterprise`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_enterprise_perm_role
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_enterprise_perm_role`;
CREATE TABLE `ims_superdesk_shop_enterprise_perm_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `core_enterprise` int(11) DEFAULT '0' COMMENT '企业id',
  `rolename` varchar(255) DEFAULT '',
  `status` tinyint(3) DEFAULT '0',
  `perms` text,
  `deleted` tinyint(3) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `updatetime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE,
  KEY `idx_deleted` (`deleted`) USING BTREE,
  KEY `merchid` (`core_enterprise`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_enterprise_reg
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_enterprise_reg`;
CREATE TABLE `ims_superdesk_shop_enterprise_reg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `enterprise_name` varchar(255) DEFAULT '',
  `desc` varchar(500) DEFAULT '',
  `realname` varchar(255) DEFAULT '',
  `mobile` varchar(255) DEFAULT '',
  `status` tinyint(3) DEFAULT '0',
  `diyformdata` text,
  `diyformfields` text,
  `applytime` int(11) DEFAULT '0',
  `reason` text,
  `createtime` int(11) DEFAULT '0',
  `updatetime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_enterprise_user
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_enterprise_user`;
CREATE TABLE `ims_superdesk_shop_enterprise_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `regid` int(11) DEFAULT '0' COMMENT '企业注册ID',
  `groupid` int(11) DEFAULT '0' COMMENT '企业分组ID',
  `cateid` int(11) DEFAULT '0' COMMENT '企业分类ID',
  `openid` varchar(255) NOT NULL DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `enterprise_no` varchar(255) NOT NULL DEFAULT '' COMMENT '企业编号',
  `enterprise_name` varchar(255) NOT NULL DEFAULT '' COMMENT '企业名',
  `address` varchar(255) DEFAULT '' COMMENT '地址',
  `realname` varchar(255) NOT NULL DEFAULT '' COMMENT '实名',
  `mobile` varchar(255) NOT NULL DEFAULT '' COMMENT '手机号',
  `status` tinyint(3) DEFAULT '0' COMMENT '状态 1 允许入驻 2 暂停 3 即将到期',
  `accountid` int(11) DEFAULT '0' COMMENT '帐号表ID',
  `accounttotal` int(11) DEFAULT '0' COMMENT '可以开多少子帐号',
  `accounttime` int(11) DEFAULT '0' COMMENT '服务时间，默认1年',
  `applytime` int(11) DEFAULT '0' COMMENT '审核时间',
  `jointime` int(11) DEFAULT '0' COMMENT '加入时间',
  `diyformdata` text COMMENT '自定义数据',
  `diyformfields` text COMMENT '自定义字段',
  `remark` text COMMENT '备注',
  `sets` text COMMENT '企业基础设置',
  `tel` varchar(255) DEFAULT '' COMMENT '电话',
  `lat` varchar(255) DEFAULT '' COMMENT '经度',
  `logo` varchar(255) NOT NULL DEFAULT '' COMMENT '标志',
  `lng` varchar(255) DEFAULT '' COMMENT '纬度',
  `desc` varchar(500) NOT NULL DEFAULT '' COMMENT '介绍',
  `createtime` int(11) DEFAULT '0',
  `updatetime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE,
  KEY `idx_groupid` (`groupid`) USING BTREE,
  KEY `idx_regid` (`regid`) USING BTREE,
  KEY `idx_cateid` (`cateid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_exhelper_express
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_exhelper_express`;
CREATE TABLE `ims_superdesk_shop_exhelper_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `type` int(1) NOT NULL DEFAULT '1',
  `expressname` varchar(255) DEFAULT '',
  `expresscom` varchar(255) NOT NULL DEFAULT '',
  `express` varchar(255) NOT NULL DEFAULT '',
  `width` decimal(10,2) DEFAULT '0.00',
  `datas` text,
  `height` decimal(10,2) DEFAULT '0.00',
  `bg` varchar(255) DEFAULT '',
  `isdefault` tinyint(3) DEFAULT '0',
  `merchid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_isdefault` (`isdefault`) USING BTREE,
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_exhelper_senduser
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_exhelper_senduser`;
CREATE TABLE `ims_superdesk_shop_exhelper_senduser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `sendername` varchar(255) DEFAULT '',
  `sendertel` varchar(255) DEFAULT '',
  `sendersign` varchar(255) DEFAULT '',
  `sendercode` int(11) DEFAULT NULL,
  `senderaddress` varchar(255) DEFAULT '',
  `sendercity` varchar(255) DEFAULT NULL,
  `isdefault` tinyint(3) DEFAULT '0',
  `merchid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_isdefault` (`isdefault`) USING BTREE,
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_exhelper_sys
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_exhelper_sys`;
CREATE TABLE `ims_superdesk_shop_exhelper_sys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(20) NOT NULL DEFAULT 'localhost',
  `ip_cloud` varchar(255) NOT NULL DEFAULT '',
  `port` int(11) NOT NULL DEFAULT '8000',
  `port_cloud` int(11) NOT NULL DEFAULT '8000',
  `is_cloud` int(1) NOT NULL DEFAULT '0',
  `merchid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_express
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_express`;
CREATE TABLE `ims_superdesk_shop_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT '',
  `express` varchar(50) DEFAULT '',
  `status` tinyint(1) DEFAULT '1',
  `displayorder` tinyint(3) unsigned DEFAULT '0',
  `code` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_express_kdniao
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_express_kdniao`;
CREATE TABLE `ims_superdesk_shop_express_kdniao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=421 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_express_kuaidi100
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_express_kuaidi100`;
CREATE TABLE `ims_superdesk_shop_express_kuaidi100` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT '',
  `express` varchar(50) DEFAULT '',
  `status` tinyint(1) DEFAULT '1',
  `displayorder` tinyint(3) unsigned DEFAULT '0',
  `code` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_feedback
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_feedback`;
CREATE TABLE `ims_superdesk_shop_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '0',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `type` tinyint(1) DEFAULT '1',
  `status` tinyint(1) DEFAULT '0',
  `feedbackid` varchar(100) DEFAULT '',
  `transid` varchar(100) DEFAULT '',
  `reason` varchar(1000) DEFAULT '',
  `solution` varchar(1000) DEFAULT '',
  `remark` varchar(1000) DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_feedbackid` (`feedbackid`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE,
  KEY `idx_transid` (`transid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_form
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_form`;
CREATE TABLE `ims_superdesk_shop_form` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `isrequire` tinyint(3) DEFAULT '0',
  `key` varchar(255) DEFAULT '',
  `title` varchar(255) DEFAULT '',
  `type` varchar(255) DEFAULT '',
  `values` text,
  `cate` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_form_category
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_form_category`;
CREATE TABLE `ims_superdesk_shop_form_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `name` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_gift
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_gift`;
CREATE TABLE `ims_superdesk_shop_gift` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `activity` tinyint(3) NOT NULL DEFAULT '1',
  `orderprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `goodsid` varchar(255) NOT NULL,
  `giftgoodsid` varchar(255) NOT NULL,
  `starttime` int(11) NOT NULL DEFAULT '0',
  `endtime` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(3) NOT NULL DEFAULT '0',
  `displayorder` int(11) NOT NULL DEFAULT '0',
  `share_title` varchar(255) NOT NULL,
  `share_icon` varchar(255) NOT NULL,
  `share_desc` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_globonus_bill
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_globonus_bill`;
CREATE TABLE `ims_superdesk_shop_globonus_bill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `billno` varchar(100) DEFAULT '',
  `paytype` int(11) DEFAULT '0',
  `year` int(11) DEFAULT '0',
  `month` int(11) DEFAULT '0',
  `week` int(11) DEFAULT '0',
  `ordercount` int(11) DEFAULT '0',
  `ordermoney` decimal(10,2) DEFAULT '0.00',
  `bonusmoney` decimal(10,2) DEFAULT '0.00',
  `bonusmoney_send` decimal(10,2) DEFAULT '0.00',
  `bonusmoney_pay` decimal(10,2) DEFAULT '0.00',
  `paytime` int(11) DEFAULT '0',
  `partnercount` int(11) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `status` tinyint(3) DEFAULT '0',
  `starttime` int(11) DEFAULT '0',
  `endtime` int(11) DEFAULT '0',
  `confirmtime` int(11) DEFAULT '0',
  `bonusordermoney` decimal(10,2) DEFAULT '0.00',
  `bonusrate` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_paytype` (`paytype`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE,
  KEY `idx_paytime` (`paytime`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE,
  KEY `idx_month` (`month`) USING BTREE,
  KEY `idx_week` (`week`) USING BTREE,
  KEY `idx_year` (`year`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_globonus_billo
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_globonus_billo`;
CREATE TABLE `ims_superdesk_shop_globonus_billo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `billid` int(11) DEFAULT '0',
  `orderid` int(11) DEFAULT '0',
  `ordermoney` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `idx_billid` (`billid`) USING BTREE,
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_globonus_billp
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_globonus_billp`;
CREATE TABLE `ims_superdesk_shop_globonus_billp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `billid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `payno` varchar(255) DEFAULT '',
  `paytype` tinyint(3) DEFAULT '0',
  `bonus` decimal(10,2) DEFAULT '0.00',
  `money` decimal(10,2) DEFAULT '0.00',
  `realmoney` decimal(10,2) DEFAULT '0.00',
  `paymoney` decimal(10,2) DEFAULT '0.00',
  `charge` decimal(10,2) DEFAULT '0.00',
  `chargemoney` decimal(10,2) DEFAULT '0.00',
  `status` tinyint(3) DEFAULT '0',
  `reason` varchar(255) DEFAULT '',
  `paytime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_billid` (`billid`) USING BTREE,
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_globonus_level
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_globonus_level`;
CREATE TABLE `ims_superdesk_shop_globonus_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `levelname` varchar(50) DEFAULT '',
  `bonus` decimal(10,4) DEFAULT '0.0000',
  `ordermoney` decimal(10,2) DEFAULT '0.00',
  `ordercount` int(11) DEFAULT '0',
  `commissionmoney` decimal(10,2) DEFAULT '0.00',
  `bonusmoney` decimal(10,2) DEFAULT '0.00',
  `downcount` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_goods
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_goods`;
CREATE TABLE `ims_superdesk_shop_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uniacid` int(11) DEFAULT '0',
  `merchid` int(11) DEFAULT '0' COMMENT 'v2 商户ID',
  `pcate` int(11) DEFAULT '0' COMMENT '一级分类ID',
  `ccate` int(11) DEFAULT '0' COMMENT '二级分类ID',
  `tcate` int(11) DEFAULT '0' COMMENT '三级分类ID',
  `cates` text COMMENT '多重分类数据集',
  `pcates` text COMMENT '一级多重分类',
  `ccates` text COMMENT '二级多重分类',
  `tcates` text COMMENT '三级多重分类',
  `type` tinyint(2) DEFAULT '1' COMMENT '类型 1 实体物品 2 虚拟物品 3 虚拟物品(卡密) 4 批发 10 话费流量充值 20 充值卡',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 0 下架 1 上架 2 赠品上架',
  `displayorder` int(11) DEFAULT '0' COMMENT '显示顺序',
  `title` varchar(100) DEFAULT '' COMMENT '商品名称',
  `thumb` varchar(255) DEFAULT '' COMMENT '商品图',
  `unit` varchar(5) DEFAULT '' COMMENT '商品单位',
  `description` varchar(1000) DEFAULT NULL COMMENT '分享描述',
  `content` text COMMENT '商品详情',
  `goodssn` varchar(50) DEFAULT '' COMMENT '商品编号',
  `productsn` varchar(50) DEFAULT '' COMMENT '商品条码',
  `productprice` decimal(10,2) DEFAULT '0.00' COMMENT '商品原价',
  `marketprice` decimal(10,2) DEFAULT '0.00' COMMENT '商品现价',
  `costprice` decimal(10,2) DEFAULT '0.00' COMMENT '商品成本',
  `originalprice` decimal(10,2) DEFAULT '0.00' COMMENT '原价 好象已经废弃',
  `total` int(10) DEFAULT '0' COMMENT '商品库存',
  `totalcnf` int(11) DEFAULT '0' COMMENT '减库存方式 0 拍下减库存 1 付款减库存 2 永不减库存',
  `sales` int(11) DEFAULT '0' COMMENT '已出售数',
  `salesreal` int(11) DEFAULT '0' COMMENT '实际售出数',
  `spec` varchar(5000) DEFAULT '' COMMENT '商品规格设置',
  `weight` decimal(10,2) DEFAULT '0.00' COMMENT '重量(单位g)',
  `credit` varchar(255) DEFAULT NULL COMMENT '购买赠送积分，如果带%号，则为按成交价比例计算',
  `maxbuy` int(11) DEFAULT '0' COMMENT '单次最多购买量',
  `usermaxbuy` int(11) DEFAULT '0' COMMENT '用户最多购买量',
  `hasoption` int(11) DEFAULT '0' COMMENT '启用商品规则 0 不启用 1 启用',
  `dispatch` int(11) DEFAULT '0' COMMENT '配送',
  `thumb_url` text COMMENT '缩略图地址',
  `isnew` tinyint(1) DEFAULT '0' COMMENT '新上',
  `ishot` tinyint(1) DEFAULT '0' COMMENT '热卖',
  `isdiscount` tinyint(1) DEFAULT '0' COMMENT '促销',
  `isrecommand` tinyint(1) DEFAULT '0' COMMENT '推荐',
  `issendfree` tinyint(1) DEFAULT '0' COMMENT '包邮',
  `istime` tinyint(1) DEFAULT '0' COMMENT '限时卖',
  `iscomment` tinyint(1) DEFAULT '0' COMMENT '允许评价',
  `timestart` int(11) DEFAULT '0' COMMENT '限卖开始时间',
  `timeend` int(11) DEFAULT '0' COMMENT '限卖结束时间',
  `viewcount` int(11) DEFAULT '0' COMMENT '查看次数',
  `hascommission` tinyint(3) DEFAULT '0' COMMENT '是否有分销',
  `commission1_rate` decimal(10,2) DEFAULT '0.00' COMMENT '一级分销比率',
  `commission1_pay` decimal(10,2) DEFAULT '0.00' COMMENT '一级分销固定佣金',
  `commission2_rate` decimal(10,2) DEFAULT '0.00' COMMENT '二级分销比率',
  `commission2_pay` decimal(10,2) DEFAULT '0.00' COMMENT '二级分销固定佣金',
  `commission3_rate` decimal(10,2) DEFAULT '0.00' COMMENT '三级分销比率',
  `commission3_pay` decimal(10,2) DEFAULT '0.00' COMMENT '三级分销固定佣金',
  `score` decimal(10,2) DEFAULT '0.00' COMMENT '得分 好象已经废弃',
  `taobaoid` varchar(255) DEFAULT '' COMMENT '淘宝ID 淘宝助手',
  `taotaoid` varchar(255) DEFAULT '' COMMENT '淘宝ID 淘宝助手',
  `taobaourl` varchar(255) DEFAULT '' COMMENT '淘宝网址 淘宝助手',
  `share_title` varchar(255) DEFAULT '' COMMENT '分享标题',
  `share_icon` varchar(255) DEFAULT '' COMMENT '分享图标',
  `cash` tinyint(3) DEFAULT '0' COMMENT '货到付款 1 不支持 2 支持',
  `commission_thumb` varchar(255) DEFAULT '' COMMENT '海报图片',
  `isnodiscount` tinyint(3) DEFAULT '0' COMMENT '不参与会员折扣',
  `showlevels` text COMMENT '浏览权限',
  `buylevels` text COMMENT '购买权限',
  `showgroups` text COMMENT '会员组浏览权限',
  `buygroups` text COMMENT '会员组购买权限',
  `isverify` tinyint(3) DEFAULT '0' COMMENT '支持线下核销 Null 0 1 不支持 2 支持',
  `storeids` text COMMENT '支持门店ID',
  `noticeopenid` varchar(255) DEFAULT '' COMMENT '商家通知',
  `noticetype` text COMMENT '提醒类型',
  `needfollow` tinyint(3) DEFAULT '0' COMMENT '需要关注',
  `followtip` varchar(255) DEFAULT '' COMMENT '关注事项',
  `followurl` varchar(255) DEFAULT '' COMMENT '关注地址',
  `deduct` decimal(10,2) DEFAULT '0.00',
  `virtual` int(11) DEFAULT '0' COMMENT '虚拟商品模板ID 0 多规格虚拟商品',
  `discounts` text COMMENT '折扣',
  `nocommission` tinyint(3) DEFAULT '0' COMMENT '不执行分销',
  `hidecommission` tinyint(3) DEFAULT '0' COMMENT '隐藏分销按钮',
  `artid` int(11) DEFAULT '0' COMMENT '营销文章ID',
  `detail_logo` varchar(255) DEFAULT '' COMMENT '店铺LOGO',
  `detail_shopname` varchar(255) DEFAULT '' COMMENT '店铺名称',
  `detail_btntext1` varchar(255) DEFAULT '' COMMENT '按钮1名称',
  `detail_btnurl1` varchar(255) DEFAULT '' COMMENT '按钮1链接 默认"查看所有商品"及"默认的全部商品连接"',
  `detail_btntext2` varchar(255) DEFAULT '' COMMENT '按钮2名称',
  `detail_btnurl2` varchar(255) DEFAULT '' COMMENT '按钮2链接 默认"进店逛逛"及"默认的小店或商城连接"',
  `detail_totaltitle` varchar(255) DEFAULT '' COMMENT '全部宝贝x个',
  `deduct2` decimal(10,2) DEFAULT '0.00' COMMENT '余额抵扣 0 支持全额抵扣 -1 不支持余额抵扣 >0 最多抵扣 元',
  `ednum` int(11) DEFAULT '0' COMMENT '单品满件包邮 0 : 不支持满件包邮',
  `edmoney` decimal(10,2) DEFAULT '0.00' COMMENT '单品满额包邮 0 : 不支持满额包邮',
  `edareas` text COMMENT '不参加满包邮的地区 ，0 : 不支持满件包邮',
  `diyformtype` tinyint(1) DEFAULT '0' COMMENT '自定义表单类型 0 关闭 1 附加形式 2 替换模式',
  `diyformid` int(11) DEFAULT '0' COMMENT '自定义表单ID',
  `diymode` tinyint(1) DEFAULT '0' COMMENT '自定义表单模式 0 系统默认 1 点击立即购买时填写 2 确认订单时填写',
  `dispatchtype` tinyint(1) DEFAULT '0' COMMENT '配送类型 0 运费模板 1 统一邮费',
  `dispatchid` int(11) DEFAULT '0' COMMENT '配送ID',
  `dispatchprice` decimal(10,2) DEFAULT '0.00' COMMENT '统一邮费',
  `manydeduct` tinyint(1) DEFAULT '0' COMMENT '多件累计抵扣积分',
  `shorttitle` varchar(255) DEFAULT '' COMMENT '短名称 打印需要',
  `isdiscount_title` varchar(255) DEFAULT '' COMMENT 'v2 促销标题',
  `isdiscount_time` int(11) DEFAULT '0' COMMENT 'v2 促销结束时间',
  `isdiscount_discounts` text COMMENT 'v2 促销价格 数字为价格 百分数 为折扣',
  `commission` text COMMENT 'v2 分销',
  `shopid` int(11) DEFAULT '0' COMMENT 'v2 商户ID',
  `allcates` text COMMENT 'v2 好象无用',
  `minbuy` int(11) DEFAULT '0' COMMENT 'v2 用户单次必须购买数量',
  `invoice` tinyint(3) DEFAULT '0' COMMENT 'v2 提供发票',
  `repair` tinyint(3) DEFAULT '0' COMMENT 'v2 保修',
  `seven` tinyint(3) DEFAULT '0' COMMENT 'v2 7天无理由退换',
  `money` varchar(255) DEFAULT '' COMMENT 'v2 余额返现',
  `minprice` decimal(10,2) DEFAULT '0.00' COMMENT 'v2 多规格中最小价格，无规格时显示销售价',
  `maxprice` decimal(10,2) DEFAULT '0.00' COMMENT 'v2 多规格中最大价格，无规格时显示销售价',
  `province` varchar(255) DEFAULT '' COMMENT 'v2 商品所在省 如为空则显示商城所在',
  `city` varchar(255) DEFAULT '' COMMENT 'v2 商品所在城市 如为空则显示商城所在',
  `buyshow` tinyint(1) DEFAULT '0' COMMENT 'v2 购买后可见 0 关闭 1 开启',
  `buycontent` text COMMENT 'v2 购买可见开启后的内容',
  `virtualsend` tinyint(1) DEFAULT '0' COMMENT 'v2 自动发货 0 否 1 是 ，注：自动发货后，订单自动完成',
  `virtualsendcontent` text COMMENT 'v2 自动发货内容',
  `verifytype` tinyint(1) DEFAULT '0' COMMENT 'v2 核销类型 0 按订单核销 1 按次核销 2 按消费码核销',
  `diyfields` text COMMENT 'v2',
  `diysaveid` int(11) DEFAULT '0' COMMENT 'v2',
  `diysave` tinyint(1) DEFAULT '0' COMMENT 'v2',
  `quality` tinyint(3) DEFAULT '0' COMMENT 'v2 正品保证',
  `groupstype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'v2 是否支持拼团 0 否 1 支持',
  `showtotal` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'v2 显示库存 0 不显示 1 显示',
  `subtitle` varchar(255) DEFAULT '' COMMENT 'v2 子标题',
  `sharebtn` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'v2 分享按钮链接方式 0 弹出关注提示层 1 跳转至商品海报',
  `checked` tinyint(3) DEFAULT '0' COMMENT 'v2 审核状态',
  `thumb_first` tinyint(3) DEFAULT '0' COMMENT 'v2 详情页面显示首图 0 不显示 1 显示 注：首图为列表页使用，尺寸较小',
  `catesinit3` text COMMENT 'v2 是否初始化分类信息 标识字段无用',
  `showtotaladd` tinyint(1) DEFAULT '0' COMMENT 'v2 是否调整过显示库存 标识字段无用',
  `merchsale` tinyint(1) DEFAULT '0' COMMENT 'v2 手机端使用的价格 0 当前设置促销价格 1 商户设置促销价格',
  `keywords` varchar(255) DEFAULT '' COMMENT 'v2 关键词',
  `catch_id` varchar(255) DEFAULT '' COMMENT 'v2 抓取产品信息ID',
  `catch_url` varchar(255) DEFAULT '' COMMENT 'v2 抓取产品地址',
  `catch_source` varchar(255) DEFAULT '' COMMENT 'v2 抓取产品来源',
  `minpriceupdated` tinyint(1) DEFAULT '0' COMMENT 'v2 是否更新过价格 标识字段无用',
  `labelname` text COMMENT 'v2 商品标签',
  `autoreceive` int(11) DEFAULT '0' COMMENT 'v2 自动收货 0 系统设置 -1 不自动收货 >0 天数',
  `cannotrefund` tinyint(3) DEFAULT '0' COMMENT 'v2 不允许退货',
  `bargain` int(11) DEFAULT '0' COMMENT 'v2 砍价',
  `buyagain` decimal(10,2) DEFAULT '0.00' COMMENT 'v2 重复购买折扣',
  `diypage` int(11) DEFAULT NULL COMMENT 'v2 自定义页面ID',
  `buyagain_islong` tinyint(1) DEFAULT '0' COMMENT 'v2 购买一次后,以后都使用这个折扣 0 否 1 是',
  `buyagain_condition` tinyint(1) DEFAULT '0' COMMENT 'v2 使用条件 0 付款后 1 完成后',
  `buyagain_sale` tinyint(1) DEFAULT '0' COMMENT 'v2 可否使用优惠 （重复购买时,是否与其他优惠共享!其他优惠享受后,再使用这个折扣）',
  `buyagain_commission` text COMMENT 'v2 复购分销',
  `jd_vop_sku` bigint(20) NOT NULL DEFAULT '0',
  `jd_vop_page_num` varchar(16) NOT NULL DEFAULT '0' COMMENT 'jd_vop_page_num',
  `old_shop_goods_id` int(11) NOT NULL DEFAULT '0' COMMENT 'old_shop_goods_id',
  `createtime` int(11) DEFAULT '0' COMMENT '建立时间',
  `updatetime` int(11) DEFAULT '0' COMMENT '更新时间',
  `deleted` tinyint(3) DEFAULT '0' COMMENT '是否删除',
  `synctime_jd_vop_price` int(11) NOT NULL DEFAULT '0' COMMENT 'synctime_jd_vop_price',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_pcate` (`pcate`) USING BTREE,
  KEY `idx_ccate` (`ccate`) USING BTREE,
  KEY `idx_isnew` (`isnew`) USING BTREE,
  KEY `idx_ishot` (`ishot`) USING BTREE,
  KEY `idx_isdiscount` (`isdiscount`) USING BTREE,
  KEY `idx_isrecommand` (`isrecommand`) USING BTREE,
  KEY `idx_iscomment` (`iscomment`) USING BTREE,
  KEY `idx_issendfree` (`issendfree`) USING BTREE,
  KEY `idx_istime` (`istime`) USING BTREE,
  KEY `idx_deleted` (`deleted`) USING BTREE,
  KEY `idx_tcate` (`tcate`) USING BTREE,
  KEY `idx_merchid` (`merchid`) USING BTREE,
  KEY `idx_checked` (`checked`) USING BTREE,
  KEY `jd_vop_sku` (`jd_vop_sku`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE,
  KEY `idx_union1` (`uniacid`,`status`,`deleted`,`checked`,`displayorder`,`createtime`) USING BTREE,
  KEY `idx_union2` (`uniacid`,`status`,`deleted`,`minprice`,`checked`,`displayorder`,`createtime`) USING BTREE,
  KEY `updatetime` (`updatetime`) USING BTREE,
  KEY `idx_union_4_sku_dto_2_redis_queue` (`uniacid`,`status`,`deleted`,`jd_vop_sku`,`updatetime`) USING BTREE,
  KEY `idx_goodssn` (`goodssn`) USING BTREE,
  FULLTEXT KEY `idx_buylevels` (`buylevels`),
  FULLTEXT KEY `idx_showgroups` (`showgroups`),
  FULLTEXT KEY `idx_buygroups` (`buygroups`)
) ENGINE=InnoDB AUTO_INCREMENT=2479928 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_goods_comment
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_goods_comment`;
CREATE TABLE `ims_superdesk_shop_goods_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `goodsid` int(10) DEFAULT '0',
  `openid` varchar(50) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `nickname` varchar(50) DEFAULT '',
  `headimgurl` varchar(255) DEFAULT '',
  `content` varchar(255) DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_goodsid` (`goodsid`) USING BTREE,
  KEY `idx_openid` (`openid`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_goods_exts
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_goods_exts`;
CREATE TABLE `ims_superdesk_shop_goods_exts` (
  `sku` bigint(20) NOT NULL COMMENT 'skuId',
  `category` varchar(32) NOT NULL COMMENT 'sku所属分类',
  `taxCode` varchar(32) NOT NULL COMMENT '税务编码',
  `isFactoryShip` int(11) NOT NULL COMMENT '是否厂商直送',
  `isEnergySaving` int(11) NOT NULL COMMENT '是否政府节能',
  `contractSkuExt` varchar(64) NOT NULL COMMENT '定制商品池开关',
  `ChinaCatalog` varchar(64) NOT NULL COMMENT '中图法分类号',
  `createtime` int(11) NOT NULL COMMENT 'createtime',
  `updatetime` int(11) NOT NULL COMMENT 'updatetime',
  `express_need_time` int(3) DEFAULT '0' COMMENT '物流时长',
  `wareQD` text COMMENT '商品清单',
  `shouhou` text COMMENT '售后保障',
  PRIMARY KEY (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for ims_superdesk_shop_goods_group
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_goods_group`;
CREATE TABLE `ims_superdesk_shop_goods_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `goodsids` text NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_enabled` (`enabled`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=127 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_goods_label
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_goods_label`;
CREATE TABLE `ims_superdesk_shop_goods_label` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `label` varchar(255) NOT NULL DEFAULT '',
  `labelname` text NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT '0',
  `displayorder` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_goods_labelstyle
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_goods_labelstyle`;
CREATE TABLE `ims_superdesk_shop_goods_labelstyle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `style` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_goods_option
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_goods_option`;
CREATE TABLE `ims_superdesk_shop_goods_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `thumb` varchar(60) DEFAULT '',
  `productprice` decimal(10,2) DEFAULT '0.00',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  `costprice` decimal(10,2) DEFAULT '0.00',
  `stock` int(11) DEFAULT '0',
  `weight` decimal(10,2) DEFAULT '0.00',
  `displayorder` int(11) DEFAULT '0',
  `specs` text,
  `skuId` varchar(255) DEFAULT '',
  `goodssn` varchar(255) DEFAULT '',
  `productsn` varchar(255) DEFAULT '',
  `virtual` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_goodsid` (`goodsid`) USING BTREE,
  KEY `idx_displayorder` (`displayorder`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=274 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_goods_param
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_goods_param`;
CREATE TABLE `ims_superdesk_shop_goods_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `value` text,
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_goodsid` (`goodsid`) USING BTREE,
  KEY `idx_displayorder` (`displayorder`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=211 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_goods_similar
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_goods_similar`;
CREATE TABLE `ims_superdesk_shop_goods_similar` (
  `id` int(11) NOT NULL COMMENT '对应商品表的id',
  `uniacid` int(11) DEFAULT '0',
  `similar_id` varchar(255) DEFAULT NULL,
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_goods_spec
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_goods_spec`;
CREATE TABLE `ims_superdesk_shop_goods_spec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `goodsid` int(11) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `description` varchar(1000) DEFAULT '',
  `displaytype` tinyint(3) DEFAULT '0',
  `content` text,
  `displayorder` int(11) DEFAULT '0',
  `propId` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_goodsid` (`goodsid`) USING BTREE,
  KEY `idx_displayorder` (`displayorder`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=96 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_goods_spec_item
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_goods_spec_item`;
CREATE TABLE `ims_superdesk_shop_goods_spec_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `specid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `show` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `valueId` varchar(255) DEFAULT '',
  `virtual` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_specid` (`specid`) USING BTREE,
  KEY `idx_show` (`show`) USING BTREE,
  KEY `idx_displayorder` (`displayorder`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=300 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_groups_adv
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_groups_adv`;
CREATE TABLE `ims_superdesk_shop_groups_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_enabled` (`enabled`) USING BTREE,
  KEY `idx_displayorder` (`displayorder`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_groups_category
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_groups_category`;
CREATE TABLE `ims_superdesk_shop_groups_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `name` varchar(50) DEFAULT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  `displayorder` tinyint(3) unsigned DEFAULT '0',
  `enabled` tinyint(1) DEFAULT '1',
  `advimg` varchar(255) DEFAULT '',
  `advurl` varchar(500) DEFAULT '',
  `isrecommand` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_displayorder` (`displayorder`) USING BTREE,
  KEY `idx_enabled` (`enabled`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_groups_goods
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_groups_goods`;
CREATE TABLE `ims_superdesk_shop_groups_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `displayorder` int(11) unsigned DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `goodssn` varchar(50) DEFAULT NULL,
  `productsn` varchar(50) DEFAULT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `category` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `showstock` tinyint(2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT '0',
  `price` decimal(10,2) DEFAULT '0.00',
  `groupsprice` decimal(10,2) DEFAULT '0.00',
  `goodsnum` int(11) NOT NULL DEFAULT '1',
  `purchaselimit` int(11) NOT NULL DEFAULT '0',
  `single` tinyint(2) NOT NULL DEFAULT '0',
  `singleprice` decimal(10,2) DEFAULT '0.00',
  `units` varchar(255) NOT NULL DEFAULT '件',
  `dispatchtype` tinyint(2) NOT NULL,
  `dispatchid` int(11) NOT NULL,
  `freight` decimal(10,2) DEFAULT '0.00',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `groupnum` int(10) NOT NULL DEFAULT '0',
  `sales` int(10) NOT NULL DEFAULT '0',
  `thumb` varchar(255) DEFAULT '',
  `description` varchar(1000) DEFAULT NULL,
  `content` text,
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) NOT NULL DEFAULT '0',
  `isindex` tinyint(3) NOT NULL DEFAULT '0',
  `deleted` tinyint(3) NOT NULL DEFAULT '0',
  `goodsid` int(11) NOT NULL DEFAULT '0',
  `followneed` tinyint(2) NOT NULL DEFAULT '0',
  `followtext` varchar(255) DEFAULT NULL,
  `followurl` varchar(255) DEFAULT NULL,
  `share_title` varchar(255) DEFAULT NULL,
  `share_icon` varchar(255) DEFAULT NULL,
  `share_desc` varchar(500) DEFAULT NULL,
  `deduct` decimal(10,2) NOT NULL DEFAULT '0.00',
  `thumb_url` text,
  `rights` tinyint(2) NOT NULL DEFAULT '1',
  `gid` int(11) DEFAULT '0',
  `discount` tinyint(3) DEFAULT '0',
  `headstype` tinyint(3) DEFAULT NULL,
  `headsmoney` decimal(10,2) DEFAULT '0.00',
  `headsdiscount` int(11) DEFAULT '0',
  `isdiscount` tinyint(3) DEFAULT '0',
  `isverify` tinyint(3) DEFAULT '0',
  `verifytype` tinyint(3) DEFAULT '0',
  `verifynum` int(11) DEFAULT '0',
  `storeids` text,
  `merchid` int(11) DEFAULT '0',
  `shorttitle` varchar(255) DEFAULT '',
  `teamnum` int(11) DEFAULT '0',
  `ishot` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_type` (`category`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE,
  KEY `idx_istop` (`isindex`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_groups_goods_atlas
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_groups_goods_atlas`;
CREATE TABLE `ims_superdesk_shop_groups_goods_atlas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `g_id` int(11) NOT NULL,
  `thumb` varchar(145) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_groups_order
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_groups_order`;
CREATE TABLE `ims_superdesk_shop_groups_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `openid` varchar(45) NOT NULL,
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `orderno` varchar(45) NOT NULL,
  `groupnum` int(11) NOT NULL,
  `paytime` int(11) NOT NULL,
  `credit` int(11) DEFAULT '0',
  `creditmoney` decimal(11,2) DEFAULT '0.00',
  `price` decimal(11,2) DEFAULT '0.00',
  `freight` decimal(11,2) DEFAULT '0.00',
  `status` int(9) NOT NULL,
  `pay_type` varchar(45) DEFAULT NULL,
  `dispatchid` int(11) DEFAULT NULL,
  `addressid` int(11) NOT NULL DEFAULT '0',
  `address` varchar(255) DEFAULT NULL,
  `goodid` int(11) NOT NULL,
  `teamid` int(11) NOT NULL,
  `is_team` int(2) NOT NULL,
  `heads` int(11) DEFAULT '0',
  `discount` decimal(10,2) DEFAULT '0.00',
  `starttime` int(11) NOT NULL,
  `canceltime` int(11) NOT NULL DEFAULT '0',
  `endtime` int(45) NOT NULL,
  `createtime` int(11) NOT NULL,
  `finishtime` int(11) NOT NULL DEFAULT '0',
  `refundid` int(11) NOT NULL DEFAULT '0',
  `refundstate` tinyint(2) NOT NULL DEFAULT '0',
  `refundtime` int(11) NOT NULL DEFAULT '0',
  `express` varchar(45) DEFAULT NULL,
  `expresscom` varchar(100) DEFAULT NULL,
  `expresssn` varchar(45) DEFAULT NULL,
  `sendtime` int(45) DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `remarkclose` text,
  `remarksend` text,
  `message` varchar(255) DEFAULT NULL,
  `success` int(2) NOT NULL DEFAULT '0',
  `deleted` int(2) NOT NULL DEFAULT '0',
  `realname` varchar(20) DEFAULT NULL,
  `mobile` varchar(11) DEFAULT NULL,
  `isverify` tinyint(3) DEFAULT '0',
  `verifytype` tinyint(3) DEFAULT '0',
  `verifycode` varchar(45) DEFAULT '0',
  `verifynum` int(11) DEFAULT '0',
  `printstate` int(11) NOT NULL DEFAULT '0',
  `printstate2` int(11) NOT NULL DEFAULT '0',
  `apppay` tinyint(3) NOT NULL DEFAULT '0',
  `delete` int(2) NOT NULL DEFAULT '0',
  `isborrow` tinyint(1) DEFAULT '0',
  `borrowopenid` varchar(50) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_groups_order_refund
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_groups_order_refund`;
CREATE TABLE `ims_superdesk_shop_groups_order_refund` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `openid` varchar(45) NOT NULL DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `orderid` int(11) NOT NULL DEFAULT '0',
  `refundno` varchar(45) NOT NULL DEFAULT '0',
  `refundstatus` tinyint(3) NOT NULL DEFAULT '0',
  `refundaddressid` int(11) NOT NULL DEFAULT '0',
  `refundaddress` varchar(255) NOT NULL DEFAULT '0',
  `content` varchar(255) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `images` varchar(255) DEFAULT NULL,
  `applytime` varchar(45) NOT NULL DEFAULT '0',
  `applycredit` int(11) NOT NULL DEFAULT '0',
  `applyprice` decimal(11,2) NOT NULL DEFAULT '0.00',
  `reply` text,
  `refundtype` varchar(45) DEFAULT NULL,
  `rtype` int(3) NOT NULL DEFAULT '0',
  `refundtime` varchar(45) NOT NULL,
  `endtime` varchar(45) NOT NULL DEFAULT '0',
  `message` varchar(255) DEFAULT NULL,
  `operatetime` varchar(45) NOT NULL DEFAULT '0',
  `realcredit` int(11) NOT NULL,
  `realmoney` decimal(11,2) NOT NULL,
  `express` varchar(45) DEFAULT NULL,
  `expresscom` varchar(100) DEFAULT NULL,
  `expresssn` varchar(45) DEFAULT NULL,
  `sendtime` varchar(45) NOT NULL DEFAULT '0',
  `returntime` int(11) NOT NULL DEFAULT '0',
  `rexpress` varchar(45) DEFAULT NULL,
  `rexpresscom` varchar(100) DEFAULT NULL,
  `rexpresssn` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_groups_paylog
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_groups_paylog`;
CREATE TABLE `ims_superdesk_shop_groups_paylog` (
  `plid` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `openid` varchar(40) NOT NULL,
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `tid` varchar(64) NOT NULL,
  `credit` int(10) NOT NULL DEFAULT '0',
  `creditmoney` decimal(10,2) NOT NULL,
  `fee` decimal(10,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `module` varchar(50) NOT NULL,
  `tag` varchar(2000) NOT NULL,
  `is_usecard` tinyint(3) unsigned NOT NULL,
  `card_type` tinyint(3) unsigned NOT NULL,
  `card_id` varchar(50) NOT NULL,
  `card_fee` decimal(10,2) unsigned NOT NULL,
  `encrypt_code` varchar(100) NOT NULL,
  `uniontid` varchar(50) NOT NULL,
  PRIMARY KEY (`plid`),
  KEY `idx_openid` (`openid`) USING BTREE,
  KEY `idx_tid` (`tid`) USING BTREE,
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `uniontid` (`uniontid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_groups_set
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_groups_set`;
CREATE TABLE `ims_superdesk_shop_groups_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` varchar(45) DEFAULT NULL,
  `groups` int(2) NOT NULL DEFAULT '0',
  `followurl` varchar(255) DEFAULT NULL,
  `followqrcode` varchar(255) DEFAULT NULL,
  `groupsurl` varchar(255) DEFAULT NULL,
  `share_title` varchar(255) DEFAULT NULL,
  `share_icon` varchar(255) DEFAULT NULL,
  `share_desc` varchar(255) DEFAULT NULL,
  `share_url` varchar(255) DEFAULT NULL,
  `groups_description` text,
  `description` int(2) NOT NULL DEFAULT '0',
  `creditdeduct` tinyint(2) NOT NULL DEFAULT '0',
  `groupsdeduct` tinyint(2) NOT NULL DEFAULT '0',
  `credit` int(11) NOT NULL DEFAULT '1',
  `groupsmoney` decimal(11,2) NOT NULL DEFAULT '0.00',
  `refund` int(11) NOT NULL DEFAULT '0',
  `refundday` int(11) NOT NULL DEFAULT '0',
  `goodsid` text NOT NULL,
  `rules` text,
  `receive` int(11) DEFAULT '0',
  `discount` tinyint(3) DEFAULT '0',
  `headstype` tinyint(3) DEFAULT '0',
  `headsmoney` decimal(10,2) DEFAULT '0.00',
  `headsdiscount` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_groups_verify
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_groups_verify`;
CREATE TABLE `ims_superdesk_shop_groups_verify` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(45) DEFAULT '0',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `orderid` int(11) DEFAULT '0',
  `verifycode` varchar(45) DEFAULT '',
  `storeid` int(11) DEFAULT '0',
  `verifier` varchar(45) DEFAULT '0',
  `isverify` tinyint(3) DEFAULT '0',
  `verifytime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_mc_merchant
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_mc_merchant`;
CREATE TABLE `ims_superdesk_shop_mc_merchant` (
  `id` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `merchant_no` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `salt` varchar(8) NOT NULL DEFAULT '',
  `contact_name` varchar(255) NOT NULL DEFAULT '',
  `contact_mobile` varchar(16) NOT NULL DEFAULT '',
  `contact_address` varchar(255) NOT NULL DEFAULT '',
  `type` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `createtime` int(11) NOT NULL,
  `validitytime` int(11) NOT NULL,
  `industry` varchar(255) NOT NULL DEFAULT '',
  `remark` varchar(1000) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_member
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_member`;
CREATE TABLE `ims_superdesk_shop_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `uid` int(11) DEFAULT '0',
  `groupid` int(11) DEFAULT '0',
  `level` int(11) DEFAULT '0',
  `agentid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `realname` varchar(20) DEFAULT '',
  `mobile` varchar(11) DEFAULT '',
  `pwd` varchar(32) DEFAULT '',
  `weixin` varchar(100) DEFAULT '',
  `content` text,
  `agenttime` int(10) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `isagent` tinyint(1) DEFAULT '0',
  `clickcount` int(11) DEFAULT '0',
  `agentlevel` int(11) DEFAULT '0',
  `noticeset` text,
  `nickname` varchar(255) DEFAULT '',
  `credit1` decimal(10,2) DEFAULT '0.00',
  `credit2` decimal(10,2) DEFAULT '0.00',
  `birthyear` varchar(255) DEFAULT '',
  `birthmonth` varchar(255) DEFAULT '',
  `birthday` varchar(255) DEFAULT '',
  `gender` tinyint(3) DEFAULT '0',
  `avatar` varchar(255) DEFAULT '',
  `province` varchar(255) DEFAULT '',
  `city` varchar(255) DEFAULT '',
  `area` varchar(255) DEFAULT '',
  `childtime` int(11) DEFAULT '0',
  `agentnotupgrade` int(11) DEFAULT '0',
  `inviter` int(11) DEFAULT '0',
  `agentselectgoods` tinyint(3) DEFAULT '0',
  `agentblack` int(11) DEFAULT '0',
  `username` varchar(255) DEFAULT '',
  `fixagentid` tinyint(3) DEFAULT '0',
  `diymemberid` int(11) DEFAULT '0',
  `diymemberdataid` int(11) DEFAULT '0',
  `diymemberdata` text,
  `diycommissionid` int(11) DEFAULT '0',
  `diycommissiondataid` int(11) DEFAULT '0',
  `diycommissiondata` text,
  `isblack` int(11) DEFAULT '0',
  `diymemberfields` text,
  `diycommissionfields` text,
  `commission_total` decimal(10,2) DEFAULT '0.00',
  `endtime2` int(11) DEFAULT '0',
  `ispartner` tinyint(3) DEFAULT '0',
  `partnertime` int(11) DEFAULT '0',
  `partnerstatus` tinyint(3) DEFAULT '0',
  `partnerblack` tinyint(3) DEFAULT '0',
  `partnerlevel` int(11) DEFAULT '0',
  `partnernotupgrade` tinyint(3) DEFAULT '0',
  `diyglobonusid` int(11) DEFAULT '0',
  `diyglobonusdata` text,
  `diyglobonusfields` text,
  `isaagent` tinyint(3) DEFAULT '0',
  `aagentlevel` int(11) DEFAULT '0',
  `aagenttime` int(11) DEFAULT '0',
  `aagentstatus` tinyint(3) DEFAULT '0',
  `aagentblack` tinyint(3) DEFAULT '0',
  `aagentnotupgrade` tinyint(3) DEFAULT '0',
  `aagenttype` tinyint(3) DEFAULT '0',
  `aagentprovinces` text,
  `aagentcitys` text,
  `aagentareas` text,
  `diyaagentid` int(11) DEFAULT '0',
  `diyaagentdata` text,
  `diyaagentfields` text,
  `salt` varchar(32) DEFAULT NULL,
  `mobileverify` tinyint(3) DEFAULT '0',
  `mobileuser` tinyint(3) DEFAULT '0',
  `carrier_mobile` varchar(11) DEFAULT '0',
  `isauthor` tinyint(1) DEFAULT '0',
  `authortime` int(11) DEFAULT '0',
  `authorstatus` tinyint(1) DEFAULT '0',
  `authorblack` tinyint(1) DEFAULT '0',
  `authorlevel` int(11) DEFAULT '0',
  `authornotupgrade` tinyint(1) DEFAULT '0',
  `diyauthorid` int(11) DEFAULT '0',
  `diyauthordata` text,
  `diyauthorfields` text,
  `authorid` int(11) DEFAULT '0',
  `comefrom` varchar(20) DEFAULT NULL,
  `openid_qq` varchar(50) DEFAULT NULL,
  `openid_wx` varchar(50) DEFAULT NULL,
  `core_enterprise` int(11) NOT NULL DEFAULT '0' COMMENT '超级前台_企业ID',
  `core_organization` int(11) DEFAULT '0' COMMENT '项目id',
  `createtime` int(10) DEFAULT '0',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT 'updatetime',
  `logintime` int(11) NOT NULL DEFAULT '0' COMMENT 'logintime',
  `cash_role_id` int(11) NOT NULL DEFAULT '0' COMMENT '角色id(对应superdesk_shop_member_cash_role)',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_shareid` (`agentid`) USING BTREE,
  KEY `idx_openid` (`openid`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE,
  KEY `idx_agenttime` (`agenttime`) USING BTREE,
  KEY `idx_isagent` (`isagent`) USING BTREE,
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_groupid` (`groupid`) USING BTREE,
  KEY `idx_level` (`level`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5437 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_member_address
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_member_address`;
CREATE TABLE `ims_superdesk_shop_member_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '0',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `realname` varchar(20) DEFAULT '',
  `mobile` varchar(18) DEFAULT NULL COMMENT '+86 134 1111 1111',
  `province` varchar(30) DEFAULT '',
  `city` varchar(30) DEFAULT '',
  `area` varchar(30) DEFAULT '',
  `town` varchar(30) NOT NULL DEFAULT '' COMMENT '四级地址',
  `address` varchar(300) DEFAULT '',
  `isdefault` tinyint(1) DEFAULT '0',
  `zipcode` varchar(255) DEFAULT '',
  `deleted` tinyint(1) DEFAULT '0',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT 'createtime',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT 'updatetime',
  `jd_vop_province_code` int(11) NOT NULL DEFAULT '0' COMMENT '京东一级province_code',
  `jd_vop_city_code` int(11) NOT NULL DEFAULT '0' COMMENT '京东二级city_code',
  `jd_vop_county_code` int(11) NOT NULL DEFAULT '0' COMMENT '京东三级county_code',
  `jd_vop_town_code` int(11) NOT NULL DEFAULT '0' COMMENT '京东四级town_code',
  `jd_vop_area` varchar(64) NOT NULL DEFAULT '' COMMENT '用于查库存与下单 格式：1_0_0 (分别代表1、2、3级地址)',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_openid` (`openid`) USING BTREE,
  KEY `idx_isdefault` (`isdefault`) USING BTREE,
  KEY `idx_deleted` (`deleted`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1676 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_member_cart
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_member_cart`;
CREATE TABLE `ims_superdesk_shop_member_cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(100) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `goodsid` int(11) DEFAULT '0',
  `total` int(11) DEFAULT '0',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  `deleted` tinyint(1) DEFAULT '0',
  `optionid` int(11) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `diyformdataid` int(11) DEFAULT '0',
  `diyformdata` text,
  `diyformfields` text,
  `diyformid` int(11) DEFAULT '0',
  `selected` tinyint(1) DEFAULT '1',
  `merchid` int(11) DEFAULT '0',
  `selectedadd` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_goodsid` (`goodsid`) USING BTREE,
  KEY `idx_openid` (`openid`) USING BTREE,
  KEY `idx_deleted` (`deleted`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=32044 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_member_cash_role
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_member_cash_role`;
CREATE TABLE `ims_superdesk_shop_member_cash_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rolename` varchar(50) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='会员角色表';

-- ----------------------------
-- Table structure for ims_superdesk_shop_member_credit_log
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_member_credit_log`;
CREATE TABLE `ims_superdesk_shop_member_credit_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(50) DEFAULT NULL,
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `add_price` decimal(10,2) DEFAULT '0.00' COMMENT '交易金额',
  `reduce_price` decimal(10,2) DEFAULT '0.00' COMMENT '减少金额',
  `old_price` decimal(10,2) DEFAULT '0.00' COMMENT '旧金额',
  `new_price` decimal(10,2) DEFAULT '0.00' COMMENT '新金额',
  `type` tinyint(3) DEFAULT '0' COMMENT '类型(1是员工导入,2总端充值,3订单,4退款)',
  `finish_time` int(11) DEFAULT '0' COMMENT '交易完成时间',
  `createtime` int(11) DEFAULT '0' COMMENT '该记录创建时间',
  `orderid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=202 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_member_enter
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_member_enter`;
CREATE TABLE `ims_superdesk_shop_member_enter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enter_id` bigint(20) NOT NULL COMMENT '卡号',
  `enter_key` char(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '登录唯一标识',
  `enter_uid` int(11) NOT NULL COMMENT '绑定的用户ID',
  `enter_balance` decimal(10,0) NOT NULL COMMENT '卡面金额',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为未激活 1为激活',
  `createtime` int(11) NOT NULL,
  `updatetime` int(11) DEFAULT NULL COMMENT '更新时间',
  `expiretime` int(11) DEFAULT NULL COMMENT '过期时间',
  `usedtime` int(11) DEFAULT NULL COMMENT '第一次使用时间',
  `enter_qrcode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '进入二维码',
  PRIMARY KEY (`id`),
  UNIQUE KEY `_index_enter_key` (`enter_key`) USING BTREE,
  UNIQUE KEY `_index_enter_id` (`enter_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1500 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for ims_superdesk_shop_member_favorite
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_member_favorite`;
CREATE TABLE `ims_superdesk_shop_member_favorite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `goodsid` int(10) DEFAULT '0',
  `openid` varchar(50) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `deleted` tinyint(1) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `merchid` int(11) DEFAULT '0',
  `type` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_goodsid` (`goodsid`) USING BTREE,
  KEY `idx_openid` (`openid`) USING BTREE,
  KEY `idx_deleted` (`deleted`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1587 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_member_group
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_member_group`;
CREATE TABLE `ims_superdesk_shop_member_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `groupname` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_member_history
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_member_history`;
CREATE TABLE `ims_superdesk_shop_member_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `goodsid` int(10) DEFAULT '0',
  `openid` varchar(50) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `deleted` tinyint(1) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `times` int(11) DEFAULT '0',
  `merchid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_goodsid` (`goodsid`) USING BTREE,
  KEY `idx_openid` (`openid`) USING BTREE,
  KEY `idx_deleted` (`deleted`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=53717 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_member_invoice
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_member_invoice`;
CREATE TABLE `ims_superdesk_shop_member_invoice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `realname` varchar(20) DEFAULT '' COMMENT '个人发票抬头',
  `invoiceState` int(11) DEFAULT '1' COMMENT '开票方式(1为随货开票，0为订单预借，2为集中开票 )',
  `invoiceType` int(4) DEFAULT '1' COMMENT '1普通发票 2增值税发票',
  `selectedInvoiceTitle` int(4) DEFAULT '4' COMMENT '发票类型：4个人，5单位',
  `companyName` varchar(250) NOT NULL DEFAULT '' COMMENT '发票抬头  (如果selectedInvoiceTitle=5则此字段必须)',
  `taxpayersIDcode` varchar(250) NOT NULL DEFAULT '' COMMENT '纳税人识别码 备注 开企业抬头发票，请填写 纳税人识别号 ,以免影响您报销',
  `invoiceContent` int(4) NOT NULL DEFAULT '1' COMMENT '1:明细，3：电脑配件，19:耗材，22：办公用品 备注:若增值发票则只能选1 明细',
  `invoiceName` varchar(64) NOT NULL DEFAULT '' COMMENT '增值票收票人姓名 备注：当invoiceType=2 且invoiceState=1时则此字段必填',
  `invoicePhone` varchar(64) NOT NULL DEFAULT '' COMMENT '增值票收票人电话 备注：当invoiceType=2 且invoiceState=1时则此字段必填',
  `invoiceProvice` int(11) NOT NULL DEFAULT '0' COMMENT '增值票收票人所在省(京东地址编码) 备注：当invoiceType=2 且invoiceState=1时则此字段必填',
  `invoiceCity` int(11) NOT NULL DEFAULT '0' COMMENT '增值票收票人所在市(京东地址编码) 备注：当invoiceType=2 且invoiceState=1时则此字段必填',
  `invoiceCounty` int(11) NOT NULL DEFAULT '0' COMMENT '增值票收票人所在区/县(京东地址编码) 备注：当invoiceType=2 且invoiceState=1时则此字段必填',
  `invoiceAddress` varchar(512) NOT NULL DEFAULT '' COMMENT '增值票收票人所在地址 备注：当invoiceType=2 且invoiceState=1时则此字段必填',
  `invoiceBank` varchar(512) NOT NULL DEFAULT '' COMMENT '增值票开户银行',
  `invoiceAccount` varchar(512) NOT NULL DEFAULT '' COMMENT '增值票开户帐号',
  `isdefault` tinyint(1) DEFAULT '0',
  `deleted` tinyint(1) DEFAULT '0',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT 'createtime',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT 'updatetime',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=842 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_member_level
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_member_level`;
CREATE TABLE `ims_superdesk_shop_member_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `level` int(11) DEFAULT '0',
  `levelname` varchar(50) DEFAULT '',
  `ordermoney` decimal(10,2) DEFAULT '0.00',
  `ordercount` int(10) DEFAULT '0',
  `discount` decimal(10,2) DEFAULT '0.00',
  `enabled` tinyint(3) DEFAULT '0',
  `enabledadd` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_member_log
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_member_log`;
CREATE TABLE `ims_superdesk_shop_member_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `type` tinyint(3) DEFAULT NULL,
  `logno` varchar(255) DEFAULT '',
  `title` varchar(255) DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `money` decimal(10,2) DEFAULT '0.00',
  `rechargetype` varchar(255) DEFAULT '',
  `transid` varchar(255) DEFAULT '',
  `gives` decimal(10,2) DEFAULT NULL,
  `couponid` int(11) DEFAULT '0',
  `isborrow` tinyint(3) DEFAULT '0',
  `borrowopenid` varchar(100) DEFAULT '',
  `realmoney` decimal(10,2) DEFAULT '0.00',
  `charge` decimal(10,2) DEFAULT '0.00',
  `deductionmoney` decimal(10,2) DEFAULT '0.00',
  `remark` varchar(255) NOT NULL DEFAULT '',
  `apppay` tinyint(3) NOT NULL DEFAULT '0',
  `alipay` varchar(50) NOT NULL DEFAULT '',
  `bankname` varchar(50) NOT NULL DEFAULT '',
  `bankcard` varchar(50) NOT NULL DEFAULT '',
  `realname` varchar(50) NOT NULL DEFAULT '',
  `applytype` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_openid` (`openid`) USING BTREE,
  KEY `idx_type` (`type`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=194 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_member_message_template
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_member_message_template`;
CREATE TABLE `ims_superdesk_shop_member_message_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `template_id` varchar(255) DEFAULT '',
  `first` text NOT NULL,
  `firstcolor` varchar(255) DEFAULT '',
  `data` text NOT NULL,
  `remark` text NOT NULL,
  `remarkcolor` varchar(255) DEFAULT '',
  `url` varchar(255) NOT NULL,
  `createtime` int(11) DEFAULT '0',
  `sendtimes` int(11) DEFAULT '0',
  `sendcount` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_member_message_template_default
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_member_message_template_default`;
CREATE TABLE `ims_superdesk_shop_member_message_template_default` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typecode` varchar(255) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  `templateid` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_member_message_template_type
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_member_message_template_type`;
CREATE TABLE `ims_superdesk_shop_member_message_template_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `typecode` varchar(255) DEFAULT NULL,
  `templatecode` varchar(255) DEFAULT NULL,
  `templateid` varchar(255) DEFAULT NULL,
  `templatename` varchar(255) DEFAULT NULL,
  `content` varchar(1000) DEFAULT NULL,
  `typegroup` varchar(255) DEFAULT '',
  `groupname` varchar(255) DEFAULT '',
  `showtotaladd` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id` (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_member_printer
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_member_printer`;
CREATE TABLE `ims_superdesk_shop_member_printer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `type` tinyint(3) DEFAULT '0',
  `print_data` text,
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_member_printer_template
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_member_printer_template`;
CREATE TABLE `ims_superdesk_shop_member_printer_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `type` tinyint(3) DEFAULT '0',
  `print_title` varchar(255) DEFAULT '',
  `print_style` varchar(255) DEFAULT '',
  `print_data` text,
  `code` varchar(500) DEFAULT '',
  `qrcode` varchar(500) DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_member_rank
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_member_rank`;
CREATE TABLE `ims_superdesk_shop_member_rank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL,
  `num` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_merch_account
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_merch_account`;
CREATE TABLE `ims_superdesk_shop_merch_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `merchid` int(11) DEFAULT '0' COMMENT '商户ID',
  `username` varchar(255) DEFAULT '' COMMENT '用户名',
  `pwd` varchar(255) DEFAULT '' COMMENT '用户密码',
  `salt` varchar(255) DEFAULT '' COMMENT '密码加盐',
  `status` tinyint(3) DEFAULT '0',
  `perms` text COMMENT '权限',
  `isfounder` tinyint(3) DEFAULT '0' COMMENT '是否创始人',
  `lastip` varchar(255) DEFAULT '' COMMENT '最后IP',
  `lastvisit` varchar(255) DEFAULT '' COMMENT '最后访问',
  `roleid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_merchid` (`merchid`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=81 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_merch_adv
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_merch_adv`;
CREATE TABLE `ims_superdesk_shop_merch_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT NULL COMMENT '幻灯片名称',
  `link` varchar(255) DEFAULT NULL COMMENT '幻灯片链接',
  `thumb` varchar(255) DEFAULT NULL COMMENT '幻灯片图片',
  `displayorder` int(11) DEFAULT NULL COMMENT '显示顺序',
  `enabled` int(11) DEFAULT NULL COMMENT '是否显示',
  `merchid` int(11) DEFAULT '0' COMMENT '商户号ID',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_merchid` (`merchid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_merch_banner
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_merch_banner`;
CREATE TABLE `ims_superdesk_shop_merch_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `bannername` varchar(50) DEFAULT '' COMMENT '广告名字',
  `link` varchar(255) DEFAULT '' COMMENT '广告链接',
  `thumb` varchar(255) DEFAULT '' COMMENT '广告图片',
  `displayorder` int(11) DEFAULT '0' COMMENT '显示顺序',
  `enabled` int(11) DEFAULT '0' COMMENT '广告是否显示',
  `merchid` int(11) DEFAULT '0' COMMENT '商户ID',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_enabled` (`enabled`) USING BTREE,
  KEY `idx_displayorder` (`displayorder`) USING BTREE,
  KEY `idx_merchid` (`merchid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_merch_bill
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_merch_bill`;
CREATE TABLE `ims_superdesk_shop_merch_bill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `applyno` varchar(255) NOT NULL DEFAULT '',
  `merchid` int(11) NOT NULL DEFAULT '0',
  `orderids` text NOT NULL,
  `realprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `realpricerate` decimal(10,2) NOT NULL DEFAULT '0.00',
  `finalprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payrateprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payrate` decimal(10,2) NOT NULL DEFAULT '0.00',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `applytime` int(11) NOT NULL DEFAULT '0',
  `checktime` int(11) NOT NULL DEFAULT '0',
  `paytime` int(11) NOT NULL DEFAULT '0',
  `invalidtime` int(11) NOT NULL DEFAULT '0',
  `refusetime` int(11) NOT NULL DEFAULT '0',
  `remark` text NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT '0',
  `ordernum` int(11) NOT NULL DEFAULT '0',
  `orderprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `passrealprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `passrealpricerate` decimal(10,2) NOT NULL DEFAULT '0.00',
  `passorderids` text NOT NULL,
  `passordernum` int(11) NOT NULL DEFAULT '0',
  `passorderprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `alipay` varchar(50) NOT NULL DEFAULT '',
  `bankname` varchar(50) NOT NULL DEFAULT '',
  `bankcard` varchar(50) NOT NULL DEFAULT '',
  `applyrealname` varchar(50) NOT NULL DEFAULT '',
  `applytype` tinyint(3) NOT NULL DEFAULT '0',
  `handpay` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_merchid` (`merchid`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_merch_billo
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_merch_billo`;
CREATE TABLE `ims_superdesk_shop_merch_billo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `billid` int(11) NOT NULL DEFAULT '0',
  `orderid` int(11) NOT NULL DEFAULT '0',
  `ordermoney` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_merch_category
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_merch_category`;
CREATE TABLE `ims_superdesk_shop_merch_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `catename` varchar(255) DEFAULT '' COMMENT '商户分类名',
  `createtime` int(11) DEFAULT '0' COMMENT '分类建立时间',
  `status` tinyint(1) DEFAULT '0' COMMENT '是否显示',
  `displayorder` int(11) DEFAULT '0' COMMENT '分类排序',
  `thumb` varchar(500) DEFAULT '' COMMENT '分类图片',
  `isrecommand` tinyint(1) DEFAULT '0' COMMENT '是否首页推荐',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_merch_category_swipe
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_merch_category_swipe`;
CREATE TABLE `ims_superdesk_shop_merch_category_swipe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `thumb` varchar(500) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_merch_clearing
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_merch_clearing`;
CREATE TABLE `ims_superdesk_shop_merch_clearing` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `merchid` int(11) NOT NULL DEFAULT '0',
  `clearno` varchar(64) NOT NULL DEFAULT '',
  `goodsprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `dispatchprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `deductprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `deductcredit2` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discountprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `deductenough` decimal(10,2) NOT NULL DEFAULT '0.00',
  `merchdeductenough` decimal(10,2) NOT NULL DEFAULT '0.00',
  `isdiscountprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `realprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `realpricerate` decimal(10,2) NOT NULL DEFAULT '0.00',
  `finalprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `remark` varchar(2000) NOT NULL DEFAULT '',
  `paytime` int(11) NOT NULL DEFAULT '0',
  `payrate` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `merchid` (`merchid`) USING BTREE,
  KEY `starttime` (`starttime`) USING BTREE,
  KEY `endtime` (`endtime`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_merch_group
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_merch_group`;
CREATE TABLE `ims_superdesk_shop_merch_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `groupname` varchar(255) DEFAULT '' COMMENT '商户组名',
  `createtime` int(11) DEFAULT '0' COMMENT '建立时间',
  `status` tinyint(3) DEFAULT '0' COMMENT '组状态',
  `isdefault` tinyint(1) DEFAULT '0' COMMENT '是否默认',
  `goodschecked` tinyint(1) DEFAULT '0' COMMENT '商户添加的商品是否免审核',
  `commissionchecked` tinyint(1) DEFAULT '0' COMMENT '商户添加的商品是否可以设置商品佣金',
  `changepricechecked` tinyint(1) DEFAULT '0' COMMENT '商户是否可以修改订单价格',
  `finishchecked` tinyint(1) DEFAULT '0' COMMENT '商户是否可以在后台点击确认收货',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_merch_nav
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_merch_nav`;
CREATE TABLE `ims_superdesk_shop_merch_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `navname` varchar(255) DEFAULT '' COMMENT '导航名称',
  `icon` varchar(255) DEFAULT '' COMMENT '图标',
  `url` varchar(255) DEFAULT '' COMMENT '链接地址',
  `displayorder` int(11) DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) DEFAULT '0' COMMENT '状态',
  `merchid` int(11) DEFAULT '0' COMMENT '商户ID',
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`) USING BTREE,
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_merchid` (`merchid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_merch_notice
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_merch_notice`;
CREATE TABLE `ims_superdesk_shop_merch_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0' COMMENT '显示顺序',
  `title` varchar(255) DEFAULT '' COMMENT '标题',
  `thumb` varchar(255) DEFAULT '' COMMENT '图片',
  `link` varchar(255) DEFAULT '' COMMENT '链接地址',
  `detail` text COMMENT '详细信息',
  `status` tinyint(3) DEFAULT '0' COMMENT '状态',
  `createtime` int(11) DEFAULT NULL COMMENT '建立时间',
  `merchid` int(11) DEFAULT '0' COMMENT '商户ID',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_merchid` (`merchid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_merch_perm_log
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_merch_perm_log`;
CREATE TABLE `ims_superdesk_shop_merch_perm_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `name` varchar(255) DEFAULT '',
  `type` varchar(255) DEFAULT '',
  `op` text,
  `ip` varchar(255) DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  `merchid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_createtime` (`createtime`) USING BTREE,
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_merchid` (`merchid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1200 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_merch_perm_role
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_merch_perm_role`;
CREATE TABLE `ims_superdesk_shop_merch_perm_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `merchid` int(11) DEFAULT '0',
  `rolename` varchar(255) DEFAULT '',
  `status` tinyint(3) DEFAULT '0',
  `perms` text,
  `deleted` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE,
  KEY `idx_deleted` (`deleted`) USING BTREE,
  KEY `merchid` (`merchid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_merch_reg
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_merch_reg`;
CREATE TABLE `ims_superdesk_shop_merch_reg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `merchname` varchar(255) DEFAULT '',
  `salecate` varchar(255) DEFAULT '',
  `desc` varchar(500) DEFAULT '',
  `realname` varchar(255) DEFAULT '',
  `mobile` varchar(255) DEFAULT '',
  `status` tinyint(3) DEFAULT '0',
  `diyformdata` text,
  `diyformfields` text,
  `applytime` int(11) DEFAULT '0',
  `reason` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_merch_saler
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_merch_saler`;
CREATE TABLE `ims_superdesk_shop_merch_saler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `storeid` int(11) DEFAULT '0' COMMENT '商户门店ID',
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '' COMMENT '店员openid',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `status` tinyint(3) DEFAULT '0' COMMENT '工作状态 1 启用 0 禁用',
  `salername` varchar(255) DEFAULT '' COMMENT '店员姓名',
  `merchid` int(11) DEFAULT '0' COMMENT '商户ID',
  PRIMARY KEY (`id`),
  KEY `idx_storeid` (`storeid`) USING BTREE,
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_merchid` (`merchid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_merch_store
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_merch_store`;
CREATE TABLE `ims_superdesk_shop_merch_store` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `storename` varchar(255) DEFAULT '' COMMENT '门店名称',
  `address` varchar(255) DEFAULT '' COMMENT '门店地址',
  `tel` varchar(255) DEFAULT '' COMMENT '电话',
  `lat` varchar(255) DEFAULT '' COMMENT '经度',
  `lng` varchar(255) DEFAULT '' COMMENT '纬度',
  `status` tinyint(3) DEFAULT '0' COMMENT '状态 1 启用 0 禁用',
  `type` tinyint(1) DEFAULT '0' COMMENT '类型 1 自提 2 核销 3 同时支持',
  `realname` varchar(255) DEFAULT '' COMMENT '自提联系人名字',
  `mobile` varchar(255) DEFAULT '' COMMENT '自提联系电话',
  `fetchtime` varchar(255) DEFAULT '' COMMENT '自提时间',
  `logo` varchar(255) DEFAULT '' COMMENT '门点图片',
  `saletime` varchar(255) DEFAULT '' COMMENT '营业时间',
  `desc` text COMMENT '门店介绍',
  `displayorder` int(11) DEFAULT '0' COMMENT '显示顺序',
  `commission_total` decimal(10,2) DEFAULT NULL,
  `merchid` int(11) DEFAULT '0' COMMENT '商户ID',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE,
  KEY `idx_merchid` (`merchid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_merch_user
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_merch_user`;
CREATE TABLE `ims_superdesk_shop_merch_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `regid` int(11) DEFAULT '0' COMMENT '商户注册ID',
  `groupid` int(11) DEFAULT '0' COMMENT '商户分组ID',
  `cateid` int(11) DEFAULT '0' COMMENT '商户分类ID',
  `openid` varchar(255) NOT NULL DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `merchno` varchar(255) NOT NULL DEFAULT '' COMMENT '商户编号',
  `merchname` varchar(255) NOT NULL DEFAULT '' COMMENT '商户名',
  `address` varchar(255) DEFAULT '' COMMENT '地址',
  `salecate` varchar(255) NOT NULL DEFAULT '' COMMENT '销售类别',
  `realname` varchar(255) NOT NULL DEFAULT '' COMMENT '实名',
  `mobile` varchar(255) NOT NULL DEFAULT '' COMMENT '手机号',
  `status` tinyint(3) DEFAULT '0' COMMENT '状态 1 允许入驻 2 暂停 3 即将到期',
  `accountid` int(11) DEFAULT '0' COMMENT '帐号表ID',
  `accounttotal` int(11) DEFAULT '0' COMMENT '可以开多少子帐号',
  `accounttime` int(11) DEFAULT '0' COMMENT '服务时间，默认1年',
  `applytime` int(11) DEFAULT '0' COMMENT '审核时间',
  `jointime` int(11) DEFAULT '0' COMMENT '加入时间',
  `diyformdata` text COMMENT '自定义数据',
  `diyformfields` text COMMENT '自定义字段',
  `remark` text COMMENT '备注',
  `sets` text COMMENT '商家基础设置',
  `payopenid` varchar(32) NOT NULL DEFAULT '' COMMENT '收款人openid',
  `payrate` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '抽成利率',
  `tel` varchar(255) DEFAULT '' COMMENT '电话',
  `lat` varchar(255) DEFAULT '' COMMENT '经度',
  `logo` varchar(255) NOT NULL DEFAULT '' COMMENT '标志',
  `lng` varchar(255) DEFAULT '' COMMENT '纬度',
  `desc` varchar(500) NOT NULL DEFAULT '' COMMENT '介绍',
  `isrecommand` tinyint(1) DEFAULT '0' COMMENT '是否推荐',
  `is_default_see` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否默认对所有商户和客户可见(0:否,1:是)',
  `storename` varchar(255) DEFAULT '' COMMENT '店铺名称(显示在前端)',
  `merchMinbuy` int(5) DEFAULT '0' COMMENT '商户起送件数',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE,
  KEY `idx_groupid` (`groupid`) USING BTREE,
  KEY `idx_regid` (`regid`) USING BTREE,
  KEY `idx_cateid` (`cateid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_merch_x_enterprise
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_merch_x_enterprise`;
CREATE TABLE `ims_superdesk_shop_merch_x_enterprise` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `merchid` int(11) NOT NULL DEFAULT '0',
  `core_enterprise` int(11) DEFAULT '0' COMMENT '企业id',
  `status` tinyint(1) DEFAULT '1',
  `createtime` int(11) NOT NULL DEFAULT '0',
  `enterprise_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `merchid` (`merchid`) USING BTREE,
  KEY `enterprise_id` (`core_enterprise`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=568 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_multi_shop
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_multi_shop`;
CREATE TABLE `ims_superdesk_shop_multi_shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `uid` int(11) DEFAULT '0',
  `name` varchar(255) DEFAULT '',
  `company` varchar(255) DEFAULT '',
  `sales` varchar(255) DEFAULT '',
  `starttime` int(11) DEFAULT '0',
  `endtime` int(11) DEFAULT '0',
  `applytime` int(11) DEFAULT '0',
  `jointime` int(11) DEFAULT '0',
  `status` tinyint(3) DEFAULT '0',
  `refusecontent` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_nav
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_nav`;
CREATE TABLE `ims_superdesk_shop_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `navname` varchar(255) DEFAULT '',
  `icon` varchar(255) DEFAULT '',
  `url` varchar(1024) DEFAULT NULL,
  `displayorder` int(11) DEFAULT '0',
  `status` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`) USING BTREE,
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_notice
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_notice`;
CREATE TABLE `ims_superdesk_shop_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `detail` text,
  `status` tinyint(3) DEFAULT '0',
  `createtime` int(11) DEFAULT NULL,
  `shopid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_order
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_order`;
CREATE TABLE `ims_superdesk_shop_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `agentid` int(11) DEFAULT '0',
  `ordersn` varchar(30) DEFAULT '',
  `price` decimal(10,2) DEFAULT '0.00',
  `goodsprice` decimal(10,2) DEFAULT '0.00',
  `discountprice` decimal(10,2) DEFAULT '0.00',
  `status` tinyint(3) DEFAULT '0',
  `paytype` tinyint(1) DEFAULT '0',
  `transid` varchar(30) DEFAULT '0',
  `remark` varchar(1000) DEFAULT '',
  `addressid` int(11) DEFAULT '0',
  `dispatchprice` decimal(10,2) DEFAULT '0.00',
  `dispatchid` int(10) DEFAULT '0',
  `createtime` int(10) DEFAULT NULL,
  `dispatchtype` tinyint(3) DEFAULT '0',
  `carrier` text,
  `refundid` int(11) DEFAULT '0',
  `iscomment` tinyint(3) DEFAULT '0' COMMENT '原先判断评价，现已弃用',
  `creditadd` tinyint(3) DEFAULT '0',
  `deleted` tinyint(3) DEFAULT '0',
  `userdeleted` tinyint(3) DEFAULT '0',
  `finishtime` int(11) DEFAULT '0',
  `paytime` int(11) DEFAULT '0',
  `expresscom` varchar(30) NOT NULL DEFAULT '',
  `expresssn` varchar(50) NOT NULL DEFAULT '',
  `express` varchar(255) DEFAULT '',
  `sendtime` int(11) DEFAULT '0',
  `finish_maybe_time` int(11) DEFAULT '0' COMMENT '预计到货时间',
  `fetchtime` int(11) DEFAULT '0',
  `cash` tinyint(3) DEFAULT '0',
  `canceltime` int(11) DEFAULT NULL,
  `cancelpaytime` int(11) DEFAULT '0',
  `refundtime` int(11) DEFAULT '0',
  `isverify` tinyint(3) DEFAULT '0',
  `verified` tinyint(3) DEFAULT '0',
  `verifyopenid` varchar(255) DEFAULT '',
  `verifytime` int(11) DEFAULT '0',
  `verifycode` varchar(255) DEFAULT '',
  `verifystoreid` int(11) DEFAULT '0',
  `deductprice` decimal(10,2) DEFAULT '0.00',
  `deductcredit` int(10) DEFAULT '0',
  `deductcredit2` decimal(10,2) DEFAULT '0.00',
  `deductenough` decimal(10,2) DEFAULT '0.00',
  `virtual` int(11) DEFAULT '0',
  `virtual_info` text,
  `virtual_str` text,
  `address` text,
  `sysdeleted` tinyint(3) DEFAULT '0',
  `ordersn2` int(11) DEFAULT '0',
  `changeprice` decimal(10,2) DEFAULT '0.00',
  `changedispatchprice` decimal(10,2) DEFAULT '0.00',
  `oldprice` decimal(10,2) DEFAULT '0.00',
  `olddispatchprice` decimal(10,2) DEFAULT '0.00',
  `isvirtual` tinyint(3) DEFAULT '0',
  `couponid` int(11) DEFAULT '0',
  `couponprice` decimal(10,2) DEFAULT '0.00',
  `diyformdata` text,
  `diyformfields` text,
  `diyformid` int(11) DEFAULT '0',
  `storeid` int(11) DEFAULT '0',
  `closereason` text,
  `remarksaler` text,
  `printstate` tinyint(1) DEFAULT '0',
  `printstate2` tinyint(1) DEFAULT '0',
  `address_send` text,
  `refundstate` tinyint(3) DEFAULT '0',
  `remarkclose` text,
  `remarksend` text,
  `ismr` int(1) NOT NULL DEFAULT '0',
  `isdiscountprice` decimal(10,2) DEFAULT '0.00',
  `isvirtualsend` tinyint(1) DEFAULT '0',
  `virtualsend_info` text,
  `verifyinfo` text,
  `verifytype` tinyint(1) DEFAULT '0',
  `verifycodes` text,
  `invoiceid` int(11) NOT NULL DEFAULT '0' COMMENT '发票ID',
  `invoice` text,
  `merchid` int(11) DEFAULT '0' COMMENT '商户id',
  `ismerch` tinyint(1) DEFAULT '0',
  `parentid` int(11) DEFAULT '0',
  `isparent` tinyint(1) DEFAULT '0',
  `grprice` decimal(10,2) DEFAULT '0.00',
  `merchshow` tinyint(1) DEFAULT '0',
  `merchdeductenough` decimal(10,2) DEFAULT '0.00',
  `couponmerchid` int(11) DEFAULT '0',
  `isglobonus` tinyint(3) DEFAULT '0',
  `merchapply` tinyint(1) DEFAULT '0',
  `isabonus` tinyint(3) DEFAULT '0',
  `isborrow` tinyint(3) DEFAULT '0',
  `borrowopenid` varchar(100) DEFAULT '',
  `merchisdiscountprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `apppay` tinyint(3) NOT NULL DEFAULT '0',
  `authorid` int(11) DEFAULT '0',
  `isauthor` tinyint(1) DEFAULT '0',
  `coupongoodprice` decimal(10,2) DEFAULT '1.00',
  `buyagainprice` decimal(10,2) DEFAULT '0.00',
  `ispackage` tinyint(3) DEFAULT '0',
  `packageid` int(11) DEFAULT '0',
  `taskdiscountprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `source_from` varchar(6) NOT NULL DEFAULT 'wechat' COMMENT '订单来源(wechat,pc)',
  `member_enterprise_name` varchar(30) DEFAULT '' COMMENT '下单时的项目名称',
  `member_enterprise_id` int(11) DEFAULT '0' COMMENT '企业id',
  `core_enterprise` int(11) DEFAULT NULL,
  `member_organization_id` int(11) DEFAULT '0' COMMENT '下单时的项目id',
  `remarkmaster` text,
  `category_enterprise_discount` decimal(10,2) DEFAULT '0.00' COMMENT '分类企业折扣',
  `cancel_status` tinyint(3) DEFAULT '0' COMMENT '对应shop_order_cancel表的status,0为未申请',
  `cancelid` tinyint(3) DEFAULT '0' COMMENT '取消表id',
  `remind_delivery` tinyint(3) DEFAULT '0' COMMENT '是否已经发送收货提醒(0否1是)',
  `need_finish_time` int(11) DEFAULT '0' COMMENT '客户要求到货时间',
  `yxPackageId` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_openid` (`openid`) USING BTREE,
  KEY `idx_shareid` (`agentid`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE,
  KEY `idx_refundid` (`refundid`) USING BTREE,
  KEY `idx_paytime` (`paytime`) USING BTREE,
  KEY `idx_finishtime` (`finishtime`) USING BTREE,
  KEY `idx_merchid` (`merchid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=22639 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_order_20190226
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_order_20190226`;
CREATE TABLE `ims_superdesk_shop_order_20190226` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `agentid` int(11) DEFAULT '0',
  `ordersn` varchar(30) DEFAULT '',
  `price` decimal(10,2) DEFAULT '0.00',
  `goodsprice` decimal(10,2) DEFAULT '0.00',
  `discountprice` decimal(10,2) DEFAULT '0.00',
  `status` tinyint(3) DEFAULT '0',
  `paytype` tinyint(1) DEFAULT '0',
  `transid` varchar(30) DEFAULT '0',
  `remark` varchar(1000) DEFAULT '',
  `addressid` int(11) DEFAULT '0',
  `dispatchprice` decimal(10,2) DEFAULT '0.00',
  `dispatchid` int(10) DEFAULT '0',
  `createtime` int(10) DEFAULT NULL,
  `dispatchtype` tinyint(3) DEFAULT '0',
  `carrier` text,
  `refundid` int(11) DEFAULT '0',
  `iscomment` tinyint(3) DEFAULT '0',
  `creditadd` tinyint(3) DEFAULT '0',
  `deleted` tinyint(3) DEFAULT '0',
  `userdeleted` tinyint(3) DEFAULT '0',
  `finishtime` int(11) DEFAULT '0',
  `paytime` int(11) DEFAULT '0',
  `expresscom` varchar(30) NOT NULL DEFAULT '',
  `expresssn` varchar(50) NOT NULL DEFAULT '',
  `express` varchar(255) DEFAULT '',
  `sendtime` int(11) DEFAULT '0',
  `fetchtime` int(11) DEFAULT '0',
  `cash` tinyint(3) DEFAULT '0',
  `canceltime` int(11) DEFAULT NULL,
  `cancelpaytime` int(11) DEFAULT '0',
  `refundtime` int(11) DEFAULT '0',
  `isverify` tinyint(3) DEFAULT '0',
  `verified` tinyint(3) DEFAULT '0',
  `verifyopenid` varchar(255) DEFAULT '',
  `verifytime` int(11) DEFAULT '0',
  `verifycode` varchar(255) DEFAULT '',
  `verifystoreid` int(11) DEFAULT '0',
  `deductprice` decimal(10,2) DEFAULT '0.00',
  `deductcredit` int(10) DEFAULT '0',
  `deductcredit2` decimal(10,2) DEFAULT '0.00',
  `deductenough` decimal(10,2) DEFAULT '0.00',
  `virtual` int(11) DEFAULT '0',
  `virtual_info` text,
  `virtual_str` text,
  `address` text,
  `sysdeleted` tinyint(3) DEFAULT '0',
  `ordersn2` int(11) DEFAULT '0',
  `changeprice` decimal(10,2) DEFAULT '0.00',
  `changedispatchprice` decimal(10,2) DEFAULT '0.00',
  `oldprice` decimal(10,2) DEFAULT '0.00',
  `olddispatchprice` decimal(10,2) DEFAULT '0.00',
  `isvirtual` tinyint(3) DEFAULT '0',
  `couponid` int(11) DEFAULT '0',
  `couponprice` decimal(10,2) DEFAULT '0.00',
  `diyformdata` text,
  `diyformfields` text,
  `diyformid` int(11) DEFAULT '0',
  `storeid` int(11) DEFAULT '0',
  `closereason` text,
  `remarksaler` text,
  `printstate` tinyint(1) DEFAULT '0',
  `printstate2` tinyint(1) DEFAULT '0',
  `address_send` text,
  `refundstate` tinyint(3) DEFAULT '0',
  `remarkclose` text,
  `remarksend` text,
  `ismr` int(1) NOT NULL DEFAULT '0',
  `isdiscountprice` decimal(10,2) DEFAULT '0.00',
  `isvirtualsend` tinyint(1) DEFAULT '0',
  `virtualsend_info` text,
  `verifyinfo` text,
  `verifytype` tinyint(1) DEFAULT '0',
  `verifycodes` text,
  `invoiceid` int(11) NOT NULL DEFAULT '0' COMMENT '发票ID',
  `invoice` text,
  `merchid` int(11) DEFAULT '0',
  `ismerch` tinyint(1) DEFAULT '0',
  `parentid` int(11) DEFAULT '0',
  `isparent` tinyint(1) DEFAULT '0',
  `grprice` decimal(10,2) DEFAULT '0.00',
  `merchshow` tinyint(1) DEFAULT '0',
  `merchdeductenough` decimal(10,2) DEFAULT '0.00',
  `couponmerchid` int(11) DEFAULT '0',
  `isglobonus` tinyint(3) DEFAULT '0',
  `merchapply` tinyint(1) DEFAULT '0',
  `isabonus` tinyint(3) DEFAULT '0',
  `isborrow` tinyint(3) DEFAULT '0',
  `borrowopenid` varchar(100) DEFAULT '',
  `merchisdiscountprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `apppay` tinyint(3) NOT NULL DEFAULT '0',
  `authorid` int(11) DEFAULT '0',
  `isauthor` tinyint(1) DEFAULT '0',
  `coupongoodprice` decimal(10,2) DEFAULT '1.00',
  `buyagainprice` decimal(10,2) DEFAULT '0.00',
  `ispackage` tinyint(3) DEFAULT '0',
  `packageid` int(11) DEFAULT '0',
  `taskdiscountprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `source_from` varchar(6) NOT NULL DEFAULT 'wechat' COMMENT '订单来源(wechat,pc)',
  `member_enterprise_name` varchar(30) DEFAULT '' COMMENT '下单时的项目名称',
  `member_enterprise_id` int(11) DEFAULT '0' COMMENT '下单时的企业id',
  `member_organization_id` int(11) DEFAULT '0' COMMENT '下单时的项目id',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_openid` (`openid`) USING BTREE,
  KEY `idx_shareid` (`agentid`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE,
  KEY `idx_refundid` (`refundid`) USING BTREE,
  KEY `idx_paytime` (`paytime`) USING BTREE,
  KEY `idx_finishtime` (`finishtime`) USING BTREE,
  KEY `idx_merchid` (`merchid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=18704 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_order_cancel
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_order_cancel`;
CREATE TABLE `ims_superdesk_shop_order_cancel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `orderid` int(11) DEFAULT '0',
  `cancelno` varchar(255) DEFAULT '' COMMENT '取消编号',
  `reason` varchar(255) DEFAULT '' COMMENT '取消原因',
  `content` text COMMENT '取消描述',
  `createtime` int(11) DEFAULT '0',
  `updatetime` int(11) DEFAULT '0',
  `status` tinyint(3) DEFAULT '1' COMMENT '状态(1:审核中,2:不用审核,3:审核通过,4:审核不通过,-1:撤回)',
  `canceltime` int(11) DEFAULT '0' COMMENT '审核时间',
  `message` text COMMENT '审核备注',
  `merchid` int(11) DEFAULT '0',
  `third_status` tinyint(3) DEFAULT '0' COMMENT '第三方审核状态(0:非第三方,1:待审核,2:审核通过,3:审核不通过)',
  `third_message` text,
  PRIMARY KEY (`id`),
  KEY `idx_createtime` (`createtime`) USING BTREE,
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_order_comment
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_order_comment`;
CREATE TABLE `ims_superdesk_shop_order_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `orderid` int(11) DEFAULT '0',
  `goodsid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `nickname` varchar(50) DEFAULT '',
  `headimgurl` varchar(255) DEFAULT '',
  `level` tinyint(3) DEFAULT '0',
  `content` varchar(255) DEFAULT '' COMMENT '评价',
  `images` text,
  `createtime` int(11) DEFAULT '0',
  `deleted` tinyint(3) DEFAULT '0',
  `append_content` varchar(255) DEFAULT '' COMMENT '追加评价',
  `append_images` text,
  `reply_content` varchar(255) DEFAULT '' COMMENT '首次回复',
  `reply_images` text,
  `append_reply_content` varchar(255) DEFAULT '' COMMENT '追加回复',
  `append_reply_images` text,
  `istop` tinyint(3) DEFAULT '0',
  `checked` tinyint(3) NOT NULL DEFAULT '0',
  `replychecked` tinyint(3) NOT NULL DEFAULT '0',
  `logis` tinyint(3) DEFAULT NULL COMMENT '物流评分',
  `service` tinyint(3) DEFAULT NULL COMMENT '服务评分',
  `describes` tinyint(3) DEFAULT NULL COMMENT '描述评分',
  `comgoodid` int(11) DEFAULT NULL COMMENT '商品质量评分表id',
  `status` tinyint(1) DEFAULT '1' COMMENT '1评价  2追加评价 ',
  `state` tinyint(1) DEFAULT '1' COMMENT '1启用  2禁用',
  `merchid` int(11) DEFAULT NULL COMMENT '商户id',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_goodsid` (`goodsid`) USING BTREE,
  KEY `idx_openid` (`openid`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE,
  KEY `idx_orderid` (`orderid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=145 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_order_examine
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_order_examine`;
CREATE TABLE `ims_superdesk_shop_order_examine` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `orderid` int(11) DEFAULT '0' COMMENT '订单id',
  `openid` varchar(50) DEFAULT '' COMMENT '采购员',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `realname` varchar(20) DEFAULT '' COMMENT '申请人名称',
  `mobile` varchar(11) DEFAULT '' COMMENT '申请人电话',
  `manager_openid` varchar(50) DEFAULT '' COMMENT '采购经理openid',
  `manager_core_user` int(10) NOT NULL DEFAULT '0' COMMENT '审核人core_user',
  `manager_realname` varchar(20) DEFAULT '' COMMENT '审核人名称',
  `manager_mobile` varchar(11) DEFAULT '' COMMENT '审核人电话',
  `status` tinyint(4) DEFAULT '0' COMMENT '状态(0:未审核,1:审核通过,2:不通过)',
  `examinetime` int(11) DEFAULT '0' COMMENT '审核时间',
  `createtime` int(11) DEFAULT '0' COMMENT '创建时间',
  `core_enterprise` int(11) DEFAULT '0' COMMENT '企业id',
  `parent_order_id` int(11) DEFAULT '0' COMMENT '父订单id',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '订单金额',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5760 DEFAULT CHARSET=utf8 COMMENT='企业月结审核表';

-- ----------------------------
-- Table structure for ims_superdesk_shop_order_examine_20190226
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_order_examine_20190226`;
CREATE TABLE `ims_superdesk_shop_order_examine_20190226` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `orderid` int(11) DEFAULT '0' COMMENT '订单id',
  `openid` varchar(50) DEFAULT '' COMMENT '采购员',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `realname` varchar(20) DEFAULT '' COMMENT '申请人名称',
  `mobile` varchar(11) DEFAULT '' COMMENT '申请人电话',
  `manager_openid` varchar(50) DEFAULT '' COMMENT '采购经理openid',
  `manager_realname` varchar(20) DEFAULT '' COMMENT '审核人名称',
  `manager_mobile` varchar(11) DEFAULT '' COMMENT '审核人电话',
  `status` tinyint(4) DEFAULT '0' COMMENT '状态(0:未审核,1:审核通过,2:不通过)',
  `examinetime` int(11) DEFAULT '0' COMMENT '审核时间',
  `createtime` int(11) DEFAULT '0' COMMENT '创建时间',
  `enterprise` int(11) DEFAULT '0' COMMENT '企业id',
  `parent_order_id` int(11) DEFAULT '0' COMMENT '父订单id',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '订单金额',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3762 DEFAULT CHARSET=utf8 COMMENT='企业月结审核表';

-- ----------------------------
-- Table structure for ims_superdesk_shop_order_finance
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_order_finance`;
CREATE TABLE `ims_superdesk_shop_order_finance` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `merchid` int(11) DEFAULT '0' COMMENT '商户id',
  `orderid` int(11) DEFAULT '0' COMMENT '订单id',
  `ordersn` varchar(30) DEFAULT '' COMMENT '订单编号',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态(1:未开票,2:已开票)',
  `create_invoice_time` int(11) DEFAULT '0' COMMENT '开票时间',
  `invoice_sn` varchar(50) DEFAULT '' COMMENT '发票号',
  `remark` text COMMENT '备注',
  `expressid` int(11) DEFAULT '0' COMMENT '快递id',
  `express` varchar(50) DEFAULT '' COMMENT '快递',
  `express_sn` varchar(50) DEFAULT '' COMMENT '快递单号',
  `press_type` tinyint(4) DEFAULT '0' COMMENT '催款类型(1:业务催款,2:财务催款)',
  `press_msg` text COMMENT '催款跟进记录',
  `press_status` tinyint(4) DEFAULT '1' COMMENT '是否回款(1:未回款,2:已回款)',
  `press_time` int(11) DEFAULT '0' COMMENT '回款时间',
  `createtime` int(11) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4293 DEFAULT CHARSET=utf8 COMMENT='订单财务表(开票,寄票,催款)';

-- ----------------------------
-- Table structure for ims_superdesk_shop_order_goods
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_order_goods`;
CREATE TABLE `ims_superdesk_shop_order_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `costprice` decimal(10,2) DEFAULT '0.00' COMMENT '商品购买时的成本价',
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
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
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
  `return_goods_result` text NOT NULL COMMENT '京东退货信息',
  `refundid` int(11) DEFAULT '0' COMMENT '退款id',
  `refund_status` tinyint(3) DEFAULT '0' COMMENT '退款状态(对应order_refund表的status)',
  `goods_static` longtext COMMENT '商品静态化(即整个商品序列化丢进来)',
  `category_enterprise_discount` decimal(10,2) DEFAULT '0.00' COMMENT '分类企业折扣',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_orderid` (`orderid`) USING BTREE,
  KEY `idx_goodsid` (`goodsid`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE,
  KEY `idx_applytime1` (`applytime1`) USING BTREE,
  KEY `idx_checktime1` (`checktime1`) USING BTREE,
  KEY `idx_status1` (`status1`) USING BTREE,
  KEY `idx_applytime2` (`applytime2`) USING BTREE,
  KEY `idx_checktime2` (`checktime2`) USING BTREE,
  KEY `idx_status2` (`status2`) USING BTREE,
  KEY `idx_applytime3` (`applytime3`) USING BTREE,
  KEY `idx_invalidtime1` (`invalidtime1`) USING BTREE,
  KEY `idx_checktime3` (`checktime3`) USING BTREE,
  KEY `idx_invalidtime2` (`invalidtime2`) USING BTREE,
  KEY `idx_invalidtime3` (`invalidtime3`) USING BTREE,
  KEY `idx_status3` (`status3`) USING BTREE,
  KEY `idx_paytime1` (`paytime1`) USING BTREE,
  KEY `idx_paytime2` (`paytime2`) USING BTREE,
  KEY `idx_paytime3` (`paytime3`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=33869 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_order_goods_20190226
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_order_goods_20190226`;
CREATE TABLE `ims_superdesk_shop_order_goods_20190226` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `costprice` decimal(10,2) DEFAULT '0.00' COMMENT '商品购买时的成本价',
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
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
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
  `return_goods_result` text NOT NULL COMMENT '京东退货信息',
  `goods_static` longtext COMMENT '商品静态化(即整个商品序列化丢进来)',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_orderid` (`orderid`) USING BTREE,
  KEY `idx_goodsid` (`goodsid`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE,
  KEY `idx_applytime1` (`applytime1`) USING BTREE,
  KEY `idx_checktime1` (`checktime1`) USING BTREE,
  KEY `idx_status1` (`status1`) USING BTREE,
  KEY `idx_applytime2` (`applytime2`) USING BTREE,
  KEY `idx_checktime2` (`checktime2`) USING BTREE,
  KEY `idx_status2` (`status2`) USING BTREE,
  KEY `idx_applytime3` (`applytime3`) USING BTREE,
  KEY `idx_invalidtime1` (`invalidtime1`) USING BTREE,
  KEY `idx_checktime3` (`checktime3`) USING BTREE,
  KEY `idx_invalidtime2` (`invalidtime2`) USING BTREE,
  KEY `idx_invalidtime3` (`invalidtime3`) USING BTREE,
  KEY `idx_status3` (`status3`) USING BTREE,
  KEY `idx_paytime1` (`paytime1`) USING BTREE,
  KEY `idx_paytime2` (`paytime2`) USING BTREE,
  KEY `idx_paytime3` (`paytime3`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=26548 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_order_refund
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_order_refund`;
CREATE TABLE `ims_superdesk_shop_order_refund` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `orderid` int(11) DEFAULT '0',
  `refundno` varchar(255) DEFAULT '',
  `price` varchar(255) DEFAULT '',
  `reason` varchar(255) DEFAULT '',
  `images` text,
  `content` text,
  `createtime` int(11) DEFAULT '0',
  `status` tinyint(3) DEFAULT '0',
  `reply` text,
  `refundtype` tinyint(3) DEFAULT '0',
  `realprice` decimal(10,2) DEFAULT '0.00',
  `refundtime` int(11) DEFAULT '0',
  `orderprice` decimal(10,2) DEFAULT '0.00',
  `applyprice` decimal(10,2) DEFAULT '0.00',
  `imgs` text,
  `rtype` tinyint(3) DEFAULT '0',
  `refundaddress` text,
  `message` text,
  `express` varchar(100) DEFAULT '',
  `expresscom` varchar(100) DEFAULT '',
  `expresssn` varchar(100) DEFAULT '',
  `operatetime` int(11) DEFAULT '0',
  `sendtime` int(11) DEFAULT '0',
  `returntime` int(11) DEFAULT '0',
  `rexpress` varchar(100) DEFAULT '',
  `rexpresscom` varchar(100) DEFAULT '',
  `rexpresssn` varchar(100) DEFAULT '',
  `refundaddressid` int(11) DEFAULT '0',
  `endtime` int(11) DEFAULT '0',
  `merchid` int(11) DEFAULT '0',
  `order_goods_id` int(11) DEFAULT '0' COMMENT '订单商品id(对应shop_order_goods表)',
  `refund_total` int(5) DEFAULT '0' COMMENT '退货数量',
  `thirdRefundNo` varchar(50) DEFAULT '' COMMENT '第三方售后编号',
  PRIMARY KEY (`id`),
  KEY `idx_createtime` (`createtime`) USING BTREE,
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=404 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_order_transfer
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_order_transfer`;
CREATE TABLE `ims_superdesk_shop_order_transfer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `orderid` int(11) NOT NULL DEFAULT '0' COMMENT '订单id',
  `old_id` int(11) NOT NULL DEFAULT '0' COMMENT '转让前的merchid',
  `new_id` int(11) NOT NULL DEFAULT '0' COMMENT '转让后的merchid',
  `createtime` int(12) NOT NULL DEFAULT '0' COMMENT '订单的createtime',
  `updatetime` int(12) NOT NULL DEFAULT '0' COMMENT '转让时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=87 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_order_transfer_member
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_order_transfer_member`;
CREATE TABLE `ims_superdesk_shop_order_transfer_member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `orderid` int(11) NOT NULL DEFAULT '0' COMMENT '订单id',
  `old_openid` varchar(40) NOT NULL DEFAULT '' COMMENT '转让前的openid',
  `old_core_enterprise` int(11) DEFAULT '0' COMMENT '企业id',
  `old_enterprise_name` varchar(100) DEFAULT '',
  `old_organization_id` int(11) DEFAULT '0',
  `old_invoice_id` int(11) DEFAULT '0',
  `old_invoice` text,
  `old_address_id` int(11) DEFAULT '0',
  `old_address` text,
  `new_openid` varchar(40) NOT NULL DEFAULT '' COMMENT '转让后的openidid',
  `new_core_enterprise` int(11) DEFAULT '0' COMMENT '企业id',
  `new_enterprise_name` varchar(100) DEFAULT '',
  `new_organization_id` int(11) DEFAULT '0',
  `new_invoice_id` int(11) DEFAULT '0',
  `new_invoice` text,
  `new_address_id` int(11) DEFAULT '0',
  `new_address` text,
  `createtime` int(12) NOT NULL DEFAULT '0' COMMENT '订单的createtime',
  `updatetime` int(12) NOT NULL DEFAULT '0' COMMENT '转让时间',
  `status` tinyint(4) DEFAULT '0' COMMENT '转介状态(0:申请中,1:已通过,2:已拒绝)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_package
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_package`;
CREATE TABLE `ims_superdesk_shop_package` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `freight` decimal(10,2) NOT NULL DEFAULT '0.00',
  `thumb` varchar(255) NOT NULL,
  `starttime` int(11) NOT NULL DEFAULT '0',
  `endtime` int(11) NOT NULL DEFAULT '0',
  `goodsid` varchar(255) NOT NULL,
  `cash` tinyint(3) NOT NULL DEFAULT '0',
  `share_title` varchar(255) NOT NULL,
  `share_icon` varchar(255) NOT NULL,
  `share_desc` varchar(500) NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT '0',
  `deleted` tinyint(3) NOT NULL DEFAULT '0',
  `displayorder` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_package_goods
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_package_goods`;
CREATE TABLE `ims_superdesk_shop_package_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `goodsid` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `option` varchar(255) NOT NULL,
  `goodssn` varchar(255) NOT NULL,
  `productsn` varchar(255) NOT NULL,
  `hasoption` tinyint(3) NOT NULL DEFAULT '0',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  `packageprice` decimal(10,2) DEFAULT '0.00',
  `commission1` decimal(10,2) DEFAULT '0.00',
  `commission2` decimal(10,2) DEFAULT '0.00',
  `commission3` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_package_goods_option
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_package_goods_option`;
CREATE TABLE `ims_superdesk_shop_package_goods_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `goodsid` int(11) NOT NULL DEFAULT '0',
  `optionid` int(11) NOT NULL DEFAULT '0',
  `pid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `packageprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `marketprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `commission1` decimal(10,2) NOT NULL DEFAULT '0.00',
  `commission2` decimal(10,2) NOT NULL DEFAULT '0.00',
  `commission3` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_pc_adv
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_pc_adv`;
CREATE TABLE `ims_superdesk_shop_pc_adv` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `advname` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT '',
  `src` varchar(255) NOT NULL,
  `alt` varchar(255) DEFAULT '',
  `enabled` tinyint(3) unsigned NOT NULL,
  `link` varchar(255) DEFAULT '',
  `width` int(11) unsigned NOT NULL,
  `height` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_pc_floor_category
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_pc_floor_category`;
CREATE TABLE `ims_superdesk_shop_pc_floor_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `category_id` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `createtime` int(10) DEFAULT '0',
  `updatetime` int(10) DEFAULT '0',
  `enabled` tinyint(3) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_pc_link
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_pc_link`;
CREATE TABLE `ims_superdesk_shop_pc_link` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `linkname` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `displayorder` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_pc_menu
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_pc_menu`;
CREATE TABLE `ims_superdesk_shop_pc_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `type` int(11) unsigned DEFAULT '0',
  `displayorder` int(11) unsigned DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `enabled` tinyint(3) unsigned DEFAULT '1',
  `createtime` int(11) unsigned DEFAULT NULL,
  `link_type` tinyint(3) DEFAULT '1' COMMENT '1:自定义页面,2:内部链接,3:外部链接',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_pc_slide
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_pc_slide`;
CREATE TABLE `ims_superdesk_shop_pc_slide` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned DEFAULT '0',
  `type` int(11) unsigned DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `backcolor` varchar(255) DEFAULT NULL,
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  `shopid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_enabled` (`enabled`) USING BTREE,
  KEY `idx_displayorder` (`displayorder`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_perm_log
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_perm_log`;
CREATE TABLE `ims_superdesk_shop_perm_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `name` varchar(255) DEFAULT '',
  `type` varchar(255) DEFAULT '',
  `op` text,
  `createtime` int(11) DEFAULT '0',
  `ip` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE,
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  FULLTEXT KEY `idx_type` (`type`),
  FULLTEXT KEY `idx_op` (`op`)
) ENGINE=MyISAM AUTO_INCREMENT=1106 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_perm_plugin
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_perm_plugin`;
CREATE TABLE `ims_superdesk_shop_perm_plugin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acid` int(11) DEFAULT '0',
  `uid` int(11) DEFAULT '0',
  `type` tinyint(3) DEFAULT '0',
  `plugins` text,
  `coms` text,
  `datas` text,
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_uniacid` (`acid`) USING BTREE,
  KEY `idx_type` (`type`) USING BTREE,
  KEY `idx_acid` (`acid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_perm_role
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_perm_role`;
CREATE TABLE `ims_superdesk_shop_perm_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rolename` varchar(255) DEFAULT '',
  `status` tinyint(3) DEFAULT '0',
  `perms` text,
  `perms2` text,
  `deleted` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE,
  KEY `idx_deleted` (`deleted`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_perm_user
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_perm_user`;
CREATE TABLE `ims_superdesk_shop_perm_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `uid` int(11) DEFAULT '0',
  `username` varchar(255) DEFAULT '',
  `password` varchar(255) DEFAULT '',
  `roleid` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `perms` text,
  `perms2` text,
  `deleted` tinyint(3) DEFAULT '0',
  `realname` varchar(255) DEFAULT '',
  `mobile` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_roleid` (`roleid`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE,
  KEY `idx_deleted` (`deleted`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_plugin
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_plugin`;
CREATE TABLE `ims_superdesk_shop_plugin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `displayorder` int(11) DEFAULT '0',
  `identity` varchar(50) DEFAULT '',
  `name` varchar(50) DEFAULT '',
  `version` varchar(10) DEFAULT '',
  `author` varchar(20) DEFAULT '',
  `status` int(11) DEFAULT '0',
  `category` varchar(255) DEFAULT '',
  `isv2` tinyint(3) DEFAULT '0',
  `thumb` varchar(255) DEFAULT '',
  `desc` text,
  `iscom` tinyint(3) DEFAULT '0',
  `deprecated` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_displayorder` (`displayorder`) USING BTREE,
  FULLTEXT KEY `idx_identity` (`identity`)
) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_poster
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_poster`;
CREATE TABLE `ims_superdesk_shop_poster` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `type` tinyint(3) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `bg` varchar(255) DEFAULT '',
  `data` text,
  `keyword` varchar(255) DEFAULT '',
  `keyword2` varchar(255) DEFAULT '',
  `times` int(11) DEFAULT '0',
  `follows` int(11) DEFAULT '0',
  `isdefault` tinyint(3) DEFAULT '0',
  `resptype` tinyint(3) DEFAULT '0',
  `resptext` text,
  `resptitle` varchar(255) DEFAULT '',
  `respthumb` varchar(255) DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  `respdesc` varchar(255) DEFAULT '',
  `respurl` varchar(255) DEFAULT '',
  `waittext` varchar(255) DEFAULT '',
  `oktext` varchar(255) DEFAULT '',
  `subcredit` int(11) DEFAULT '0',
  `submoney` decimal(10,2) DEFAULT '0.00',
  `reccredit` int(11) DEFAULT '0',
  `recmoney` decimal(10,2) DEFAULT '0.00',
  `scantext` varchar(255) DEFAULT '',
  `subtext` varchar(255) DEFAULT '',
  `beagent` tinyint(3) DEFAULT '0',
  `bedown` tinyint(3) DEFAULT '0',
  `isopen` tinyint(3) DEFAULT '0',
  `opentext` varchar(255) DEFAULT '',
  `openurl` varchar(255) DEFAULT '',
  `paytype` tinyint(1) NOT NULL DEFAULT '0',
  `subpaycontent` text,
  `recpaycontent` varchar(255) DEFAULT '',
  `templateid` varchar(255) DEFAULT '',
  `entrytext` varchar(255) DEFAULT '',
  `reccouponid` int(11) DEFAULT '0',
  `reccouponnum` int(11) DEFAULT '0',
  `subcouponid` int(11) DEFAULT '0',
  `subcouponnum` int(11) DEFAULT '0',
  `resptext11` text,
  `reward_totle` varchar(500) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_type` (`type`) USING BTREE,
  KEY `idx_times` (`times`) USING BTREE,
  KEY `idx_isdefault` (`isdefault`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_poster_log
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_poster_log`;
CREATE TABLE `ims_superdesk_shop_poster_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `posterid` int(11) DEFAULT '0',
  `from_openid` varchar(255) DEFAULT '',
  `subcredit` int(11) DEFAULT '0',
  `submoney` decimal(10,2) DEFAULT '0.00',
  `reccredit` int(11) DEFAULT '0',
  `recmoney` decimal(10,2) DEFAULT '0.00',
  `createtime` int(11) DEFAULT '0',
  `reccouponid` int(11) DEFAULT '0',
  `reccouponnum` int(11) DEFAULT '0',
  `subcouponid` int(11) DEFAULT '0',
  `subcouponnum` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_openid` (`openid`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE,
  KEY `idx_posterid` (`posterid`) USING BTREE,
  KEY `idx_from_openid` (`from_openid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_poster_qr
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_poster_qr`;
CREATE TABLE `ims_superdesk_shop_poster_qr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acid` int(10) unsigned NOT NULL,
  `openid` varchar(100) NOT NULL DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `type` tinyint(3) DEFAULT '0',
  `sceneid` int(11) DEFAULT '0',
  `mediaid` varchar(255) DEFAULT '',
  `ticket` varchar(250) NOT NULL,
  `url` varchar(80) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `goodsid` int(11) DEFAULT '0',
  `qrimg` varchar(1000) DEFAULT '',
  `posterid` int(11) DEFAULT '0',
  `scenestr` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_acid` (`acid`) USING BTREE,
  KEY `idx_sceneid` (`sceneid`) USING BTREE,
  KEY `idx_type` (`type`) USING BTREE,
  KEY `idx_openid` (`openid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_poster_scan
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_poster_scan`;
CREATE TABLE `ims_superdesk_shop_poster_scan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `posterid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `from_openid` varchar(255) DEFAULT '',
  `scantime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_posterid` (`posterid`) USING BTREE,
  KEY `idx_scantime` (`scantime`) USING BTREE,
  KEY `idx_openid` (`openid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_postera
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_postera`;
CREATE TABLE `ims_superdesk_shop_postera` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `type` tinyint(3) DEFAULT '0',
  `days` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `bg` varchar(255) DEFAULT '',
  `data` text,
  `keyword` varchar(255) DEFAULT '',
  `keyword2` varchar(255) DEFAULT '',
  `isdefault` tinyint(3) DEFAULT '0',
  `resptype` tinyint(3) DEFAULT '0',
  `resptext` text,
  `resptitle` varchar(255) DEFAULT '',
  `respthumb` varchar(255) DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  `respdesc` varchar(255) DEFAULT '',
  `respurl` varchar(255) DEFAULT '',
  `waittext` varchar(255) DEFAULT '',
  `oktext` varchar(255) DEFAULT '',
  `subcredit` int(11) DEFAULT '0',
  `submoney` decimal(10,2) DEFAULT '0.00',
  `reccredit` int(11) DEFAULT '0',
  `recmoney` decimal(10,2) DEFAULT '0.00',
  `scantext` varchar(255) DEFAULT '',
  `subtext` varchar(255) DEFAULT '',
  `beagent` tinyint(3) DEFAULT '0',
  `bedown` tinyint(3) DEFAULT '0',
  `isopen` tinyint(3) DEFAULT '0',
  `opentext` varchar(255) DEFAULT '',
  `openurl` varchar(255) DEFAULT '',
  `paytype` tinyint(1) NOT NULL DEFAULT '0',
  `subpaycontent` text,
  `recpaycontent` varchar(255) DEFAULT '',
  `templateid` varchar(255) DEFAULT '',
  `entrytext` varchar(255) DEFAULT '',
  `reccouponid` int(11) DEFAULT '0',
  `reccouponnum` int(11) DEFAULT '0',
  `subcouponid` int(11) DEFAULT '0',
  `subcouponnum` int(11) DEFAULT '0',
  `timestart` int(11) DEFAULT '0',
  `timeend` int(11) DEFAULT '0',
  `status` tinyint(3) DEFAULT '0',
  `goodsid` int(11) DEFAULT '0',
  `starttext` varchar(255) DEFAULT '',
  `endtext` varchar(255) DEFAULT NULL,
  `testflag` tinyint(1) DEFAULT '0',
  `reward_totle` varchar(500) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_type` (`type`) USING BTREE,
  KEY `idx_isdefault` (`isdefault`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_postera_log
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_postera_log`;
CREATE TABLE `ims_superdesk_shop_postera_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `posterid` int(11) DEFAULT '0',
  `from_openid` varchar(255) DEFAULT '',
  `subcredit` int(11) DEFAULT '0',
  `submoney` decimal(10,2) DEFAULT '0.00',
  `reccredit` int(11) DEFAULT '0',
  `recmoney` decimal(10,2) DEFAULT '0.00',
  `createtime` int(11) DEFAULT '0',
  `reccouponid` int(11) DEFAULT '0',
  `reccouponnum` int(11) DEFAULT '0',
  `subcouponid` int(11) DEFAULT '0',
  `subcouponnum` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_openid` (`openid`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE,
  KEY `idx_posteraid` (`posterid`) USING BTREE,
  KEY `idx_from_openid` (`from_openid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_postera_qr
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_postera_qr`;
CREATE TABLE `ims_superdesk_shop_postera_qr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acid` int(10) unsigned NOT NULL,
  `openid` varchar(100) NOT NULL DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `posterid` int(11) DEFAULT '0',
  `type` tinyint(3) DEFAULT '0',
  `sceneid` int(11) DEFAULT '0',
  `mediaid` varchar(255) DEFAULT '',
  `ticket` varchar(250) NOT NULL,
  `url` varchar(80) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `goodsid` int(11) DEFAULT '0',
  `qrimg` varchar(1000) DEFAULT '',
  `expire` int(11) DEFAULT '0',
  `endtime` int(11) DEFAULT '0',
  `qrtime` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_acid` (`acid`) USING BTREE,
  KEY `idx_sceneid` (`sceneid`) USING BTREE,
  KEY `idx_type` (`type`) USING BTREE,
  KEY `idx_posterid` (`posterid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_qa_adv
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_qa_adv`;
CREATE TABLE `ims_superdesk_shop_qa_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_enabled` (`enabled`) USING BTREE,
  KEY `idx_displayorder` (`displayorder`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_qa_category
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_qa_category`;
CREATE TABLE `ims_superdesk_shop_qa_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `name` varchar(50) DEFAULT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  `displayorder` tinyint(3) unsigned DEFAULT '0',
  `enabled` tinyint(1) DEFAULT '1',
  `isrecommand` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_displayorder` (`displayorder`) USING BTREE,
  KEY `idx_enabled` (`enabled`) USING BTREE,
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_qa_question
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_qa_question`;
CREATE TABLE `ims_superdesk_shop_qa_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `cate` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `content` mediumtext NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT '0',
  `isrecommand` tinyint(3) NOT NULL DEFAULT '0',
  `displayorder` int(11) NOT NULL DEFAULT '0',
  `createtime` int(11) NOT NULL DEFAULT '0',
  `lastedittime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_qa_set
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_qa_set`;
CREATE TABLE `ims_superdesk_shop_qa_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `showmember` tinyint(3) NOT NULL DEFAULT '0',
  `showtype` tinyint(3) NOT NULL DEFAULT '0',
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `enter_title` varchar(255) NOT NULL DEFAULT '',
  `enter_img` varchar(255) NOT NULL DEFAULT '',
  `enter_desc` varchar(255) NOT NULL DEFAULT '',
  `share` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_unaicid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_refund_address
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_refund_address`;
CREATE TABLE `ims_superdesk_shop_refund_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '0',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `title` varchar(20) DEFAULT '',
  `name` varchar(20) DEFAULT '',
  `tel` varchar(20) DEFAULT '',
  `mobile` varchar(11) DEFAULT '',
  `province` varchar(30) DEFAULT '',
  `city` varchar(30) DEFAULT '',
  `area` varchar(30) DEFAULT '',
  `address` varchar(300) DEFAULT '',
  `isdefault` tinyint(1) DEFAULT '0',
  `zipcode` varchar(255) DEFAULT '',
  `content` text,
  `deleted` tinyint(1) DEFAULT '0',
  `merchid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_openid` (`openid`) USING BTREE,
  KEY `idx_isdefault` (`isdefault`) USING BTREE,
  KEY `idx_deleted` (`deleted`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_sale_coupon
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_sale_coupon`;
CREATE TABLE `ims_superdesk_shop_sale_coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `name` varchar(255) DEFAULT '',
  `type` tinyint(3) DEFAULT '0',
  `ckey` decimal(10,2) DEFAULT '0.00',
  `cvalue` decimal(10,2) DEFAULT '0.00',
  `nums` int(11) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_sale_coupon_data
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_sale_coupon_data`;
CREATE TABLE `ims_superdesk_shop_sale_coupon_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `couponid` int(11) DEFAULT '0',
  `gettime` int(11) DEFAULT '0',
  `gettype` tinyint(3) DEFAULT '0',
  `usedtime` int(11) DEFAULT '0',
  `orderid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_couponid` (`couponid`) USING BTREE,
  KEY `idx_gettime` (`gettime`) USING BTREE,
  KEY `idx_gettype` (`gettype`) USING BTREE,
  KEY `idx_usedtime` (`usedtime`) USING BTREE,
  KEY `idx_orderid` (`orderid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_saler
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_saler`;
CREATE TABLE `ims_superdesk_shop_saler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `storeid` int(11) DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `status` tinyint(3) DEFAULT '0',
  `salername` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_storeid` (`storeid`) USING BTREE,
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_sign_records
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_sign_records`;
CREATE TABLE `ims_superdesk_shop_sign_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0',
  `openid` varchar(50) NOT NULL DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `credit` int(11) NOT NULL DEFAULT '0',
  `log` varchar(255) DEFAULT '',
  `type` tinyint(3) NOT NULL DEFAULT '0',
  `day` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_time` (`time`) USING BTREE,
  KEY `idx_type` (`type`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_sign_set
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_sign_set`;
CREATE TABLE `ims_superdesk_shop_sign_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `iscenter` tinyint(3) NOT NULL DEFAULT '0',
  `iscreditshop` tinyint(3) NOT NULL DEFAULT '0',
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `desc` varchar(255) NOT NULL DEFAULT '',
  `isopen` tinyint(3) NOT NULL DEFAULT '0',
  `signold` tinyint(3) NOT NULL DEFAULT '0',
  `signold_price` int(11) NOT NULL DEFAULT '0',
  `signold_type` tinyint(3) NOT NULL DEFAULT '0',
  `textsign` varchar(255) NOT NULL DEFAULT '',
  `textsignold` varchar(255) NOT NULL DEFAULT '',
  `textsigned` varchar(255) NOT NULL DEFAULT '',
  `textsignforget` varchar(255) NOT NULL DEFAULT '',
  `maincolor` varchar(20) NOT NULL DEFAULT '',
  `cycle` tinyint(3) NOT NULL DEFAULT '0',
  `reward_default_first` int(11) NOT NULL DEFAULT '0',
  `reward_default_day` int(11) NOT NULL DEFAULT '0',
  `reword_order` text NOT NULL,
  `reword_sum` text NOT NULL,
  `reword_special` text NOT NULL,
  `sign_rule` text NOT NULL,
  `share` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_sign_user
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_sign_user`;
CREATE TABLE `ims_superdesk_shop_sign_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `openid` varchar(255) NOT NULL DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `order` int(11) NOT NULL DEFAULT '0',
  `orderday` int(11) NOT NULL DEFAULT '0',
  `sum` int(11) NOT NULL DEFAULT '0',
  `signdate` varchar(10) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_sms
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_sms`;
CREATE TABLE `ims_superdesk_shop_sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(10) NOT NULL DEFAULT '',
  `template` tinyint(3) NOT NULL DEFAULT '0',
  `smstplid` varchar(255) NOT NULL DEFAULT '',
  `smssign` varchar(255) NOT NULL DEFAULT '',
  `content` varchar(100) NOT NULL DEFAULT '',
  `data` text NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_sms_set
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_sms_set`;
CREATE TABLE `ims_superdesk_shop_sms_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `juhe` tinyint(3) NOT NULL DEFAULT '0',
  `juhe_key` varchar(255) NOT NULL DEFAULT '',
  `dayu` tinyint(3) NOT NULL DEFAULT '0',
  `dayu_key` varchar(255) NOT NULL DEFAULT '',
  `dayu_secret` varchar(255) NOT NULL DEFAULT '',
  `emay` tinyint(3) NOT NULL DEFAULT '0',
  `emay_url` varchar(255) NOT NULL DEFAULT '',
  `emay_sn` varchar(255) NOT NULL DEFAULT '',
  `emay_pw` varchar(255) NOT NULL DEFAULT '',
  `emay_sk` varchar(255) NOT NULL DEFAULT '',
  `emay_phost` varchar(255) NOT NULL DEFAULT '',
  `emay_pport` int(11) NOT NULL DEFAULT '0',
  `emay_puser` varchar(255) NOT NULL DEFAULT '',
  `emay_ppw` varchar(255) NOT NULL DEFAULT '',
  `emay_out` int(11) NOT NULL DEFAULT '0',
  `emay_outresp` int(11) NOT NULL DEFAULT '30',
  `emay_warn` decimal(10,2) NOT NULL DEFAULT '0.00',
  `emay_mobile` varchar(11) NOT NULL DEFAULT '',
  `emay_warn_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_sns_adv
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_sns_adv`;
CREATE TABLE `ims_superdesk_shop_sns_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_enabled` (`enabled`) USING BTREE,
  KEY `idx_displayorder` (`displayorder`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_sns_board
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_sns_board`;
CREATE TABLE `ims_superdesk_shop_sns_board` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `cid` int(11) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `logo` varchar(255) DEFAULT '',
  `desc` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  `showgroups` text,
  `showlevels` text,
  `postgroups` text,
  `postlevels` text,
  `showagentlevels` text,
  `postagentlevels` text,
  `postcredit` int(11) DEFAULT '0',
  `replycredit` int(11) DEFAULT '0',
  `bestcredit` int(11) DEFAULT '0',
  `bestboardcredit` int(11) DEFAULT '0',
  `notagent` tinyint(3) DEFAULT '0',
  `notagentpost` tinyint(3) DEFAULT '0',
  `topcredit` int(11) DEFAULT '0',
  `topboardcredit` int(11) DEFAULT '0',
  `status` tinyint(3) DEFAULT '0',
  `noimage` tinyint(3) DEFAULT '0',
  `novoice` tinyint(3) DEFAULT '0',
  `needfollow` tinyint(3) DEFAULT '0',
  `needpostfollow` tinyint(3) DEFAULT '0',
  `share_title` varchar(255) DEFAULT '',
  `share_icon` varchar(255) DEFAULT '',
  `share_desc` varchar(255) DEFAULT '',
  `keyword` varchar(255) DEFAULT '',
  `isrecommand` tinyint(3) DEFAULT '0',
  `banner` varchar(255) DEFAULT '',
  `needcheck` tinyint(3) DEFAULT '0',
  `needcheckmanager` tinyint(3) DEFAULT '0',
  `needcheckreply` int(11) DEFAULT '0',
  `needcheckreplymanager` int(11) DEFAULT '0',
  `showsnslevels` text,
  `postsnslevels` text,
  `showpartnerlevels` text,
  `postpartnerlevels` text,
  `notpartner` tinyint(3) DEFAULT '0',
  `notpartnerpost` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_enabled` (`enabled`) USING BTREE,
  KEY `idx_displayorder` (`displayorder`) USING BTREE,
  KEY `idx_cid` (`cid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_sns_board_follow
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_sns_board_follow`;
CREATE TABLE `ims_superdesk_shop_sns_board_follow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `bid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT NULL,
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_bid` (`bid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_sns_category
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_sns_category`;
CREATE TABLE `ims_superdesk_shop_sns_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `name` varchar(50) DEFAULT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  `displayorder` tinyint(3) unsigned DEFAULT '0',
  `enabled` tinyint(1) DEFAULT '1',
  `advimg` varchar(255) DEFAULT '',
  `advurl` varchar(500) DEFAULT '',
  `isrecommand` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_enabled` (`enabled`) USING BTREE,
  KEY `idx_isrecommand` (`isrecommand`) USING BTREE,
  KEY `idx_displayorder` (`displayorder`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_sns_complain
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_sns_complain`;
CREATE TABLE `ims_superdesk_shop_sns_complain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `type` tinyint(3) NOT NULL,
  `postsid` int(11) NOT NULL DEFAULT '0',
  `defendant` varchar(255) NOT NULL DEFAULT '0',
  `complainant` varchar(255) NOT NULL DEFAULT '0',
  `complaint_type` int(10) NOT NULL DEFAULT '0',
  `complaint_text` text NOT NULL,
  `images` text NOT NULL,
  `createtime` int(11) NOT NULL DEFAULT '0',
  `checkedtime` int(11) NOT NULL DEFAULT '0',
  `checked` tinyint(3) NOT NULL DEFAULT '0',
  `checked_note` varchar(255) NOT NULL,
  `deleted` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_sns_complaincate
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_sns_complaincate`;
CREATE TABLE `ims_superdesk_shop_sns_complaincate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT '0',
  `displayorder` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_sns_level
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_sns_level`;
CREATE TABLE `ims_superdesk_shop_sns_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `levelname` varchar(255) DEFAULT '',
  `credit` int(11) DEFAULT '0',
  `enabled` tinyint(3) DEFAULT '0',
  `post` int(11) DEFAULT '0',
  `color` varchar(255) DEFAULT '',
  `bg` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_enabled` (`enabled`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_sns_like
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_sns_like`;
CREATE TABLE `ims_superdesk_shop_sns_like` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `pid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_pid` (`pid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_sns_manage
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_sns_manage`;
CREATE TABLE `ims_superdesk_shop_sns_manage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `bid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `enabled` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_bid` (`bid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_sns_member
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_sns_member`;
CREATE TABLE `ims_superdesk_shop_sns_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT NULL,
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `level` int(11) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `credit` int(11) DEFAULT '0',
  `sign` varchar(255) DEFAULT '',
  `isblack` tinyint(3) DEFAULT '0',
  `notupgrade` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_sns_post
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_sns_post`;
CREATE TABLE `ims_superdesk_shop_sns_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `bid` int(11) DEFAULT '0',
  `pid` int(11) DEFAULT '0',
  `rpid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `avatar` varchar(255) DEFAULT '',
  `nickname` varchar(255) DEFAULT '',
  `title` varchar(50) DEFAULT '',
  `content` text,
  `images` text,
  `voice` varchar(255) DEFAULT NULL,
  `createtime` int(11) DEFAULT '0',
  `replytime` int(11) DEFAULT '0',
  `credit` int(11) DEFAULT '0',
  `views` int(11) DEFAULT '0',
  `islock` tinyint(1) DEFAULT '0',
  `istop` tinyint(1) DEFAULT '0',
  `isboardtop` tinyint(1) DEFAULT '0',
  `isbest` tinyint(1) DEFAULT '0',
  `isboardbest` tinyint(3) DEFAULT '0',
  `deleted` tinyint(3) DEFAULT '0',
  `deletedtime` int(11) DEFAULT '0',
  `checked` tinyint(3) DEFAULT NULL,
  `checktime` int(11) DEFAULT '0',
  `isadmin` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_bid` (`bid`) USING BTREE,
  KEY `idx_pid` (`pid`) USING BTREE,
  KEY `idx_createtime` (`createtime`) USING BTREE,
  KEY `idx_islock` (`islock`) USING BTREE,
  KEY `idx_istop` (`istop`) USING BTREE,
  KEY `idx_isboardtop` (`isboardtop`) USING BTREE,
  KEY `idx_isbest` (`isbest`) USING BTREE,
  KEY `idx_deleted` (`deleted`) USING BTREE,
  KEY `idx_deletetime` (`deletedtime`) USING BTREE,
  KEY `idx_checked` (`checked`) USING BTREE,
  KEY `idx_rpid` (`rpid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_store
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_store`;
CREATE TABLE `ims_superdesk_shop_store` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `storename` varchar(255) DEFAULT '',
  `address` varchar(255) DEFAULT '',
  `tel` varchar(255) DEFAULT '',
  `lat` varchar(255) DEFAULT '',
  `lng` varchar(255) DEFAULT '',
  `status` tinyint(3) DEFAULT '0',
  `type` tinyint(1) DEFAULT '0',
  `realname` varchar(255) DEFAULT '',
  `mobile` varchar(255) DEFAULT '',
  `fetchtime` varchar(255) DEFAULT '',
  `logo` varchar(255) DEFAULT '',
  `saletime` varchar(255) DEFAULT '',
  `desc` text,
  `displayorder` int(11) DEFAULT '0',
  `order_printer` varchar(500) DEFAULT '',
  `order_template` int(11) DEFAULT '0',
  `ordertype` varchar(500) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_sysset
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_sysset`;
CREATE TABLE `ims_superdesk_shop_sysset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `sets` longtext,
  `plugins` longtext,
  `sec` text,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_system_adv
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_system_adv`;
CREATE TABLE `ims_superdesk_shop_system_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `url` varchar(255) DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `module` varchar(255) DEFAULT '',
  `status` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_system_article
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_system_article`;
CREATE TABLE `ims_superdesk_shop_system_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT '',
  `author` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `content` text,
  `createtime` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `cate` int(11) DEFAULT '0',
  `status` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_system_banner
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_system_banner`;
CREATE TABLE `ims_superdesk_shop_system_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `url` varchar(255) DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `status` tinyint(3) DEFAULT '0',
  `background` varchar(10) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_system_case
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_system_case`;
CREATE TABLE `ims_superdesk_shop_system_case` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `qr` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `status` tinyint(3) DEFAULT '0',
  `cate` int(11) DEFAULT '0',
  `description` varchar(255) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_system_casecategory
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_system_casecategory`;
CREATE TABLE `ims_superdesk_shop_system_casecategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_system_category
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_system_category`;
CREATE TABLE `ims_superdesk_shop_system_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_system_company_article
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_system_company_article`;
CREATE TABLE `ims_superdesk_shop_system_company_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT '',
  `author` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `content` text,
  `createtime` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `cate` int(11) DEFAULT '0',
  `status` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_system_company_category
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_system_company_category`;
CREATE TABLE `ims_superdesk_shop_system_company_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_system_copyright
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_system_copyright`;
CREATE TABLE `ims_superdesk_shop_system_copyright` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `copyright` text,
  `bgcolor` varchar(255) DEFAULT '',
  `ismanage` tinyint(3) DEFAULT '0',
  `logo` varchar(255) DEFAULT '',
  `title` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_system_copyright_notice
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_system_copyright_notice`;
CREATE TABLE `ims_superdesk_shop_system_copyright_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT '',
  `author` varchar(255) DEFAULT '',
  `content` text,
  `createtime` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `status` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_system_guestbook
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_system_guestbook`;
CREATE TABLE `ims_superdesk_shop_system_guestbook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` varchar(255) NOT NULL DEFAULT '',
  `nickname` varchar(255) NOT NULL DEFAULT '',
  `createtime` int(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL DEFAULT '',
  `clientip` varchar(64) NOT NULL DEFAULT '',
  `mobile` varchar(11) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_system_link
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_system_link`;
CREATE TABLE `ims_superdesk_shop_system_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `displayorder` int(11) DEFAULT NULL,
  `status` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_system_setting
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_system_setting`;
CREATE TABLE `ims_superdesk_shop_system_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) DEFAULT NULL,
  `background` varchar(10) DEFAULT '',
  `casebanner` varchar(255) DEFAULT '',
  `contact` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_system_site
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_system_site`;
CREATE TABLE `ims_superdesk_shop_system_site` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(32) NOT NULL DEFAULT '',
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_task_adv
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_task_adv`;
CREATE TABLE `ims_superdesk_shop_task_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_task_default
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_task_default`;
CREATE TABLE `ims_superdesk_shop_task_default` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `data` text,
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_task_join
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_task_join`;
CREATE TABLE `ims_superdesk_shop_task_join` (
  `join_id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `join_user` varchar(100) NOT NULL DEFAULT '',
  `task_id` int(11) NOT NULL DEFAULT '0',
  `task_type` tinyint(1) NOT NULL DEFAULT '0',
  `needcount` tinyint(3) NOT NULL DEFAULT '0',
  `completecount` tinyint(3) NOT NULL DEFAULT '0',
  `reward_data` text,
  `is_reward` tinyint(1) NOT NULL DEFAULT '0',
  `failtime` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) DEFAULT '0',
  PRIMARY KEY (`join_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_task_joiner
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_task_joiner`;
CREATE TABLE `ims_superdesk_shop_task_joiner` (
  `complete_id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `task_user` varchar(100) NOT NULL DEFAULT '',
  `joiner_id` varchar(100) NOT NULL DEFAULT '',
  `join_id` int(11) NOT NULL DEFAULT '0',
  `task_id` int(11) NOT NULL DEFAULT '0',
  `task_type` tinyint(1) NOT NULL DEFAULT '0',
  `join_status` tinyint(1) NOT NULL DEFAULT '1',
  `addtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`complete_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_task_log
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_task_log`;
CREATE TABLE `ims_superdesk_shop_task_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `openid` varchar(100) NOT NULL DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `from_openid` varchar(100) NOT NULL DEFAULT '',
  `join_id` int(11) NOT NULL DEFAULT '0',
  `taskid` int(11) DEFAULT '0',
  `task_type` tinyint(1) NOT NULL DEFAULT '0',
  `subdata` text,
  `recdata` text,
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_task_poster
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_task_poster`;
CREATE TABLE `ims_superdesk_shop_task_poster` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `days` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `bg` varchar(255) DEFAULT '',
  `data` text,
  `keyword` varchar(255) DEFAULT NULL,
  `resptype` tinyint(1) NOT NULL DEFAULT '0',
  `resptext` text,
  `resptitle` varchar(255) DEFAULT NULL,
  `respthumb` varchar(255) DEFAULT NULL,
  `respdesc` varchar(255) DEFAULT NULL,
  `respurl` varchar(255) DEFAULT NULL,
  `createtime` int(11) DEFAULT NULL,
  `waittext` varchar(255) DEFAULT NULL,
  `oktext` varchar(255) DEFAULT NULL,
  `scantext` varchar(255) DEFAULT NULL,
  `beagent` tinyint(1) NOT NULL DEFAULT '0',
  `bedown` tinyint(1) NOT NULL DEFAULT '0',
  `timestart` int(11) DEFAULT NULL,
  `timeend` int(11) DEFAULT NULL,
  `is_repeat` tinyint(1) DEFAULT '0',
  `getposter` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `starttext` varchar(255) DEFAULT NULL,
  `endtext` varchar(255) DEFAULT NULL,
  `reward_data` text,
  `needcount` tinyint(3) NOT NULL DEFAULT '0',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_task_poster_qr
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_task_poster_qr`;
CREATE TABLE `ims_superdesk_shop_task_poster_qr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acid` int(11) NOT NULL DEFAULT '0',
  `openid` varchar(100) NOT NULL,
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `posterid` int(11) NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `sceneid` int(11) NOT NULL DEFAULT '0',
  `mediaid` varchar(255) DEFAULT NULL,
  `ticket` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `createtime` int(11) DEFAULT NULL,
  `qrimg` varchar(1000) DEFAULT NULL,
  `expire` int(11) DEFAULT NULL,
  `endtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_version
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_version`;
CREATE TABLE `ims_superdesk_shop_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `type` tinyint(3) NOT NULL DEFAULT '0',
  `uniacid` int(11) NOT NULL,
  `version` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_version` (`version`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_virtual_category
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_virtual_category`;
CREATE TABLE `ims_superdesk_shop_virtual_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `name` varchar(50) DEFAULT NULL,
  `merchid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_virtual_data
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_virtual_data`;
CREATE TABLE `ims_superdesk_shop_virtual_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `typeid` int(11) NOT NULL DEFAULT '0',
  `pvalue` varchar(255) DEFAULT '',
  `fields` text NOT NULL,
  `openid` varchar(255) NOT NULL DEFAULT '',
  `core_user` int(10) DEFAULT '0' COMMENT '楼宇之窗用户ID',
  `usetime` int(11) NOT NULL DEFAULT '0',
  `orderid` int(11) DEFAULT '0',
  `ordersn` varchar(255) DEFAULT '',
  `price` decimal(10,2) DEFAULT '0.00',
  `merchid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_typeid` (`typeid`) USING BTREE,
  KEY `idx_usetime` (`usetime`) USING BTREE,
  KEY `idx_orderid` (`orderid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_shop_virtual_type
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_shop_virtual_type`;
CREATE TABLE `ims_superdesk_shop_virtual_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `cate` int(11) DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `fields` text NOT NULL,
  `usedata` int(11) NOT NULL DEFAULT '0',
  `alldata` int(11) NOT NULL DEFAULT '0',
  `merchid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_cate` (`cate`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_yanxuan_area
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_yanxuan_area`;
CREATE TABLE `ims_superdesk_yanxuan_area` (
  `code` varchar(25) NOT NULL DEFAULT '',
  `parent_code` varchar(25) NOT NULL DEFAULT '',
  `level` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'level',
  `text` varchar(128) NOT NULL DEFAULT '' COMMENT '标注',
  `nativeCode` varchar(25) NOT NULL DEFAULT '' COMMENT '严选国标编码',
  `state` tinyint(3) NOT NULL DEFAULT '1' COMMENT 'state',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT 'createtime',
  `updatetime` int(11) NOT NULL COMMENT 'updatetime',
  `jd_code` int(11) DEFAULT '0' COMMENT '京东编码',
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_yanxuan_callback_config
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_yanxuan_callback_config`;
CREATE TABLE `ims_superdesk_yanxuan_callback_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `text` varchar(50) DEFAULT '' COMMENT '名称',
  `desc` varchar(255) DEFAULT '' COMMENT '描述',
  `value` varchar(50) DEFAULT '' COMMENT '值',
  `type` tinyint(4) DEFAULT '1' COMMENT '类型(1:商品变更回调,2:事件回调)',
  `status` tinyint(4) DEFAULT '0' COMMENT '是否需要回调(0否1是)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_yanxuan_callback_logs
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_yanxuan_callback_logs`;
CREATE TABLE `ims_superdesk_yanxuan_callback_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `method` varchar(100) DEFAULT '' COMMENT '回调类型',
  `content` longtext COMMENT '回调信息',
  `success` tinyint(1) DEFAULT '0',
  `createtime` int(11) DEFAULT NULL,
  `updatetime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_yanxuan_logs
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_yanxuan_logs`;
CREATE TABLE `ims_superdesk_yanxuan_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) DEFAULT '',
  `method` varchar(50) DEFAULT '',
  `api` varchar(100) DEFAULT '',
  `post_fields` text,
  `curl_info` text,
  `resultMessage` varchar(500) NOT NULL DEFAULT '' COMMENT 'url',
  `resultCode` varchar(16) NOT NULL DEFAULT '' COMMENT 'resultCode',
  `result` text NOT NULL COMMENT 'result',
  `createtime` int(11) DEFAULT NULL,
  `updatetime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=350 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_superdesk_yanxuan_order_submit_order
-- ----------------------------
DROP TABLE IF EXISTS `ims_superdesk_yanxuan_order_submit_order`;
CREATE TABLE `ims_superdesk_yanxuan_order_submit_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `expressCode` varchar(255) DEFAULT '' COMMENT '严选快递编号',
  `expressCompany` varchar(255) DEFAULT '' COMMENT '严选快递公司名称',
  `expressNo` varchar(50) DEFAULT '' COMMENT '严选快递单号',
  `skuIds` varchar(255) DEFAULT '' COMMENT '包裹包含的sku.'',''分隔',
  `orderid` varchar(50) DEFAULT '0' COMMENT '内部订单编号',
  `packageId` bigint(20) DEFAULT '0' COMMENT '包裹号',
  `expCreateTime` varchar(14) DEFAULT '' COMMENT '严选发货时间',
  `is_last` tinyint(4) DEFAULT '0' COMMENT '是否最后一单(0否1是)',
  `createtime` int(11) DEFAULT NULL,
  `updatetime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_sv_dpt
-- ----------------------------
DROP TABLE IF EXISTS `ims_sv_dpt`;
CREATE TABLE `ims_sv_dpt` (
  `sv_dpt_id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `acid` int(10) NOT NULL,
  `sv_dpt_name` varchar(255) NOT NULL,
  `sv_dpt_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`sv_dpt_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_sv_qr
-- ----------------------------
DROP TABLE IF EXISTS `ims_sv_qr`;
CREATE TABLE `ims_sv_qr` (
  `sv_qr_id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `acid` int(10) NOT NULL,
  `dptid` int(10) NOT NULL,
  `videoid` int(10) NOT NULL,
  PRIMARY KEY (`sv_qr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_sv_videos
-- ----------------------------
DROP TABLE IF EXISTS `ims_sv_videos`;
CREATE TABLE `ims_sv_videos` (
  `sv_video_id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `acid` int(10) NOT NULL,
  `sv_video_name` varchar(255) NOT NULL,
  `sv_video_code` varchar(255) NOT NULL,
  `sv_video_time` int(10) NOT NULL,
  PRIMARY KEY (`sv_video_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_uni_account
-- ----------------------------
DROP TABLE IF EXISTS `ims_uni_account`;
CREATE TABLE `ims_uni_account` (
  `uniacid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `groupid` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `default_acid` int(10) unsigned NOT NULL,
  `rank` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`uniacid`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_uni_account_group
-- ----------------------------
DROP TABLE IF EXISTS `ims_uni_account_group`;
CREATE TABLE `ims_uni_account_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `groupid` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_uni_account_menus
-- ----------------------------
DROP TABLE IF EXISTS `ims_uni_account_menus`;
CREATE TABLE `ims_uni_account_menus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `menuid` int(10) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `title` varchar(30) NOT NULL,
  `sex` tinyint(3) unsigned NOT NULL,
  `group_id` int(10) NOT NULL,
  `client_platform_type` tinyint(3) unsigned NOT NULL,
  `area` varchar(50) NOT NULL,
  `data` text NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `menuid` (`menuid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_uni_account_modules
-- ----------------------------
DROP TABLE IF EXISTS `ims_uni_account_modules`;
CREATE TABLE `ims_uni_account_modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `module` varchar(50) NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL,
  `settings` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_module` (`module`) USING BTREE,
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1370 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_uni_account_users
-- ----------------------------
DROP TABLE IF EXISTS `ims_uni_account_users`;
CREATE TABLE `ims_uni_account_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `role` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_memberid` (`uid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=74 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_uni_group
-- ----------------------------
DROP TABLE IF EXISTS `ims_uni_group`;
CREATE TABLE `ims_uni_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `modules` varchar(10000) NOT NULL,
  `templates` varchar(5000) NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_uni_settings
-- ----------------------------
DROP TABLE IF EXISTS `ims_uni_settings`;
CREATE TABLE `ims_uni_settings` (
  `uniacid` int(10) unsigned NOT NULL,
  `passport` varchar(200) NOT NULL,
  `oauth` varchar(100) NOT NULL,
  `uc` varchar(500) NOT NULL,
  `notify` varchar(2000) NOT NULL,
  `creditnames` varchar(500) NOT NULL,
  `creditbehaviors` varchar(500) NOT NULL,
  `welcome` varchar(60) NOT NULL,
  `default` varchar(60) NOT NULL,
  `default_message` varchar(1000) NOT NULL,
  `shortcuts` varchar(5000) NOT NULL,
  `payment` varchar(2000) NOT NULL,
  `groupdata` varchar(100) NOT NULL,
  `stat` varchar(300) NOT NULL,
  `menuset` text NOT NULL,
  `default_site` int(10) unsigned DEFAULT NULL,
  `sync` varchar(100) NOT NULL,
  `jsauth_acid` int(10) unsigned NOT NULL,
  `bootstrap` varchar(255) DEFAULT NULL,
  `recharge` varchar(500) NOT NULL,
  `tplnotice` varchar(1000) NOT NULL,
  `grouplevel` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_uni_verifycode
-- ----------------------------
DROP TABLE IF EXISTS `ims_uni_verifycode`;
CREATE TABLE `ims_uni_verifycode` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `receiver` varchar(50) NOT NULL,
  `verifycode` varchar(6) NOT NULL,
  `total` tinyint(3) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_userapi_cache
-- ----------------------------
DROP TABLE IF EXISTS `ims_userapi_cache`;
CREATE TABLE `ims_userapi_cache` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(32) NOT NULL,
  `content` text NOT NULL,
  `lastupdate` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_userapi_reply
-- ----------------------------
DROP TABLE IF EXISTS `ims_userapi_reply`;
CREATE TABLE `ims_userapi_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `description` varchar(300) NOT NULL,
  `apiurl` varchar(300) NOT NULL,
  `token` varchar(32) NOT NULL,
  `default_text` varchar(100) NOT NULL,
  `cachetime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_users
-- ----------------------------
DROP TABLE IF EXISTS `ims_users`;
CREATE TABLE `ims_users` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `groupid` int(10) unsigned NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(200) NOT NULL,
  `salt` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `joindate` int(10) unsigned NOT NULL,
  `joinip` varchar(15) NOT NULL,
  `lastvisit` int(10) unsigned NOT NULL,
  `lastip` varchar(15) NOT NULL,
  `remark` varchar(500) NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_users_failed_login
-- ----------------------------
DROP TABLE IF EXISTS `ims_users_failed_login`;
CREATE TABLE `ims_users_failed_login` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) NOT NULL,
  `username` varchar(32) NOT NULL,
  `count` tinyint(1) unsigned NOT NULL,
  `lastupdate` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ip_username` (`ip`,`username`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=501 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_users_group
-- ----------------------------
DROP TABLE IF EXISTS `ims_users_group`;
CREATE TABLE `ims_users_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `package` varchar(5000) NOT NULL,
  `maxaccount` int(10) unsigned NOT NULL,
  `maxsubaccount` int(10) unsigned NOT NULL,
  `timelimit` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_users_invitation
-- ----------------------------
DROP TABLE IF EXISTS `ims_users_invitation`;
CREATE TABLE `ims_users_invitation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(64) NOT NULL,
  `fromuid` int(10) unsigned NOT NULL,
  `inviteuid` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_code` (`code`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_users_permission
-- ----------------------------
DROP TABLE IF EXISTS `ims_users_permission`;
CREATE TABLE `ims_users_permission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `url` varchar(255) NOT NULL,
  `type` varchar(30) NOT NULL,
  `permission` varchar(10000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=364 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_users_profile
-- ----------------------------
DROP TABLE IF EXISTS `ims_users_profile`;
CREATE TABLE `ims_users_profile` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `realname` varchar(10) NOT NULL,
  `nickname` varchar(20) NOT NULL,
  `avatar` varchar(100) NOT NULL,
  `qq` varchar(15) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `fakeid` varchar(30) NOT NULL,
  `vip` tinyint(3) unsigned NOT NULL,
  `gender` tinyint(1) NOT NULL,
  `birthyear` smallint(6) unsigned NOT NULL,
  `birthmonth` tinyint(3) unsigned NOT NULL,
  `birthday` tinyint(3) unsigned NOT NULL,
  `constellation` varchar(10) NOT NULL,
  `zodiac` varchar(5) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `idcard` varchar(30) NOT NULL,
  `studentid` varchar(50) NOT NULL,
  `grade` varchar(10) NOT NULL,
  `address` varchar(255) NOT NULL,
  `zipcode` varchar(10) NOT NULL,
  `nationality` varchar(30) NOT NULL,
  `resideprovince` varchar(30) NOT NULL,
  `residecity` varchar(30) NOT NULL,
  `residedist` varchar(30) NOT NULL,
  `graduateschool` varchar(50) NOT NULL,
  `company` varchar(50) NOT NULL,
  `education` varchar(10) NOT NULL,
  `occupation` varchar(30) NOT NULL,
  `position` varchar(30) NOT NULL,
  `revenue` varchar(10) NOT NULL,
  `affectivestatus` varchar(30) NOT NULL,
  `lookingfor` varchar(255) NOT NULL,
  `bloodtype` varchar(5) NOT NULL,
  `height` varchar(5) NOT NULL,
  `weight` varchar(5) NOT NULL,
  `alipay` varchar(30) NOT NULL,
  `msn` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `taobao` varchar(30) NOT NULL,
  `site` varchar(30) NOT NULL,
  `bio` text NOT NULL,
  `interest` text NOT NULL,
  `workerid` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_version
-- ----------------------------
DROP TABLE IF EXISTS `ims_version`;
CREATE TABLE `ims_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setnumber` varchar(255) NOT NULL,
  `explanation` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '1:Android;2:iOS',
  `status` tinyint(4) NOT NULL COMMENT '1:强制更新;0:无需强制更新',
  `version` varchar(255) NOT NULL COMMENT 'api版本',
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_video_reply
-- ----------------------------
DROP TABLE IF EXISTS `ims_video_reply`;
CREATE TABLE `ims_video_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `mediaid` varchar(255) NOT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_voice_reply
-- ----------------------------
DROP TABLE IF EXISTS `ims_voice_reply`;
CREATE TABLE `ims_voice_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `mediaid` varchar(255) NOT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_wechat_attachment
-- ----------------------------
DROP TABLE IF EXISTS `ims_wechat_attachment`;
CREATE TABLE `ims_wechat_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `filename` varchar(255) NOT NULL,
  `attachment` varchar(255) NOT NULL,
  `media_id` varchar(255) NOT NULL,
  `width` int(10) unsigned NOT NULL,
  `height` int(10) unsigned NOT NULL,
  `type` varchar(15) NOT NULL,
  `model` varchar(25) NOT NULL,
  `tag` varchar(5000) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `media_id` (`media_id`) USING BTREE,
  KEY `acid` (`acid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_wechat_news
-- ----------------------------
DROP TABLE IF EXISTS `ims_wechat_news`;
CREATE TABLE `ims_wechat_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned DEFAULT NULL,
  `attach_id` int(10) unsigned NOT NULL,
  `thumb_media_id` varchar(60) NOT NULL,
  `thumb_url` varchar(255) NOT NULL,
  `title` varchar(50) NOT NULL,
  `author` varchar(30) NOT NULL,
  `digest` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `content_source_url` varchar(200) NOT NULL,
  `show_cover_pic` tinyint(3) unsigned NOT NULL,
  `url` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `attach_id` (`attach_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_weivote_log
-- ----------------------------
DROP TABLE IF EXISTS `ims_weivote_log`;
CREATE TABLE `ims_weivote_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `oid` int(10) unsigned NOT NULL COMMENT '选项ID',
  `options` varchar(50) NOT NULL COMMENT '选项',
  `realname` varchar(100) NOT NULL DEFAULT '' COMMENT '姓名',
  `qq` varchar(100) NOT NULL DEFAULT '' COMMENT 'QQ',
  `mobile` varchar(100) NOT NULL DEFAULT '' COMMENT '手机',
  `from_user` varchar(50) NOT NULL COMMENT '用户唯一身份ID',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0有效,1无效',
  `createtime` int(10) unsigned NOT NULL COMMENT '投票日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_weivote_option
-- ----------------------------
DROP TABLE IF EXISTS `ims_weivote_option`;
CREATE TABLE `ims_weivote_option` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `title` varchar(50) NOT NULL COMMENT '名称',
  `description` varchar(100) NOT NULL DEFAULT '' COMMENT '描述',
  `picture` varchar(100) NOT NULL COMMENT '图片',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0开启，1关闭',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_weivote_setting
-- ----------------------------
DROP TABLE IF EXISTS `ims_weivote_setting`;
CREATE TABLE `ims_weivote_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `title` varchar(100) NOT NULL COMMENT '活动标题',
  `picture` varchar(100) NOT NULL COMMENT '活动图片',
  `description` varchar(1000) NOT NULL COMMENT '活动描述',
  `rule` varchar(1000) NOT NULL COMMENT '规则',
  `max_vote_day` smallint(10) unsigned NOT NULL DEFAULT '1' COMMENT '每人每天最大投票数',
  `max_vote_count` smallint(10) unsigned NOT NULL DEFAULT '1' COMMENT '每人总共最大投票数',
  `type_vote` smallint(10) unsigned NOT NULL DEFAULT '1' COMMENT '每天必须投不同用户1每天可重复投同一用户2',
  `default_tips` varchar(100) NOT NULL COMMENT '默认提示信息',
  `start_time` int(11) NOT NULL COMMENT '开启日期',
  `end_time` int(11) NOT NULL COMMENT '结束日期',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0开启，1关闭',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_wenvote_fans
-- ----------------------------
DROP TABLE IF EXISTS `ims_wenvote_fans`;
CREATE TABLE `ims_wenvote_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_user` varchar(50) NOT NULL,
  `rid` int(11) NOT NULL,
  `oid` int(11) NOT NULL,
  `vote_num` int(11) NOT NULL,
  `vote_time` int(10) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2446 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_wenvote_option
-- ----------------------------
DROP TABLE IF EXISTS `ims_wenvote_option`;
CREATE TABLE `ims_wenvote_option` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `thumb` varchar(60) NOT NULL,
  `content` text NOT NULL,
  `vote_num` int(11) NOT NULL,
  `is_claim` tinyint(1) unsigned zerofill NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=573 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_wenvote_reply
-- ----------------------------
DROP TABLE IF EXISTS `ims_wenvote_reply`;
CREATE TABLE `ims_wenvote_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `thumb` varchar(60) NOT NULL,
  `vote_title` varchar(50) NOT NULL,
  `vote_request` varchar(255) NOT NULL,
  `vote_type` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_wxcard_reply
-- ----------------------------
DROP TABLE IF EXISTS `ims_wxcard_reply`;
CREATE TABLE `ims_wxcard_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `title` varchar(30) NOT NULL,
  `card_id` varchar(50) NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `brand_name` varchar(30) NOT NULL,
  `logo_url` varchar(255) NOT NULL,
  `success` varchar(255) NOT NULL,
  `error` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
