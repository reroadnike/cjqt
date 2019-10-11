ALTER TABLE `ims_superdesk_shop_member_address`
ADD `jd_vop_province_code` INT NOT NULL DEFAULT '0' COMMENT '京东一级province_code' AFTER `deleted`,
ADD `jd_vop_city_code` INT NOT NULL DEFAULT '0' COMMENT '京东二级city_code' AFTER `jd_vop_province_code`,
ADD `jd_vop_county_code` INT NOT NULL DEFAULT '0' COMMENT '京东三级county_code' AFTER `jd_vop_city_code`,
ADD `jd_vop_town_code` INT NOT NULL DEFAULT '0' COMMENT '京东四级town_code' AFTER `jd_vop_county_code`,
ADD `jd_vop_area` VARCHAR(64) NOT NULL DEFAULT '' COMMENT '用于查库存与下单 格式：1_0_0 (分别代表1、2、3级地址)' AFTER `jd_vop_town_code`;

ALTER TABLE `ims_superdesk_shop_member_address`
ADD `town` VARCHAR(30) NOT NULL DEFAULT '' COMMENT '四级地址' AFTER `area`;

DELETE FROM `ims_superdesk_shop_member_address` WHERE deleted = 1



CREATE TABLE IF NOT EXISTS `ims_superdesk_shop_member_address` (
  `id` int(11) NOT NULL,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '0',
  `realname` varchar(20) DEFAULT '',
  `mobile` varchar(11) DEFAULT '',
  `jd_vop_province_code` int(11) NOT NULL DEFAULT '0' COMMENT '京东一级province_code',
  `province` varchar(30) DEFAULT '',
  `jd_vop_city_code` int(11) NOT NULL DEFAULT '0' COMMENT '京东二级city_code',
  `city` varchar(30) DEFAULT '',
  `jd_vop_county_code` int(11) NOT NULL DEFAULT '0' COMMENT '京东三级county_code',
  `area` varchar(30) DEFAULT '',
  `jd_vop_town_code` int(11) NOT NULL DEFAULT '0' COMMENT '京东四级town_code',
  `town` varchar(30) NOT NULL DEFAULT '' COMMENT '四级地址',
  `address` varchar(300) DEFAULT '',
  `jd_vop_area` varchar(64) NOT NULL DEFAULT '' COMMENT '用于查库存与下单 格式：1_0_0 (分别代表1、2、3级地址)',
  `isdefault` tinyint(1) DEFAULT '0',
  `zipcode` varchar(255) DEFAULT '',
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;