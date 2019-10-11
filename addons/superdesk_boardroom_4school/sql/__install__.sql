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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE `ims_superdesk_boardroom_4school_building_structures` ADD PRIMARY KEY(`id`);
ALTER TABLE `ims_superdesk_boardroom_4school_building_structures` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;


CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_building_attribute` (
  `id` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '名称',
  `thumb` varchar(255) NOT NULL COMMENT '图片',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0',
  `description` varchar(500) NOT NULL COMMENT '介绍',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启'
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

ALTER TABLE `ims_superdesk_boardroom_4school_building_attribute` ADD PRIMARY KEY(`id`);
ALTER TABLE `ims_superdesk_boardroom_4school_building_attribute` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;