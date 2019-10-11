



# sync


CREATE TABLE `ims_superdesk_boardroom_4school_x_equipment` (
`id` INT(11) NOT NULL  ,
`boardroom_id` INT(11) NOT NULL DEFAULT '0' ,
`equipment_id` INT(11) NOT NULL DEFAULT '0' ,
`createtime` int(10) NOT NULL COMMENT '创建时间',
`uniacid` int(10) NOT NULL DEFAULT '0' COMMENT 'uniacid'
) ENGINE = InnoDB;

ALTER TABLE `ims_superdesk_boardroom_4school_x_equipment` ADD PRIMARY KEY(`id`);
ALTER TABLE `ims_superdesk_boardroom_4school_x_equipment` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `ims_superdesk_boardroom_4school_x_equipment` ADD `updatetime` INT(11) NOT NULL DEFAULT '0' AFTER `createtime`;

# sync

ALTER TABLE `ims_superdesk_boardroom_4school_report` ADD `out_trade_no` VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'out_trade_no' AFTER `print_sta`;


# sync




CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_category` (
  `id` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned DEFAULT NULL,
  `organization_code` VARCHAR(32) NULL DEFAULT '' COMMENT 'organization_code',
  `virtual_code` VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'virtual_code',
  `parentid` int(10) unsigned DEFAULT NULL,
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `description` varchar(50) NOT NULL COMMENT '分类描述',
  `thumb` varchar(100) NOT NULL COMMENT '分类图片',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  `type` int(10) unsigned NOT NULL COMMENT '1家政，2报修，3投诉，4二手，5超市，6商家',
  `price` decimal(12,2) DEFAULT NULL,
  `gtime` varchar(50) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 COMMENT='类型表';

INSERT INTO `ims_superdesk_boardroom_4school_category` (`id`, `uniacid`, `parentid`, `name`, `description`, `thumb`, `displayorder`, `enabled`, `type`, `price`, `gtime`) VALUES
(36, 16, NULL, '一般报事', '', '', 0, 1, 2, NULL, NULL),
(37, 16, NULL, '家政保洁', '', '', 0, 1, 1, '30.00', '1天/1小时'),
(38, 16, NULL, '公共区域', '', '', 0, 1, 2, NULL, NULL),
(39, 16, NULL, '工程保修', '', '', 0, 1, 2, NULL, NULL),
(40, 16, NULL, '田园时蔬', '', 'images/7/2016/07/nSbL1Bg19L4aLF6a4s76ZT0LF1Vb9w.jpg', 0, 1, 5, NULL, NULL),
(41, 16, NULL, '投诉', '', '', 0, 1, 3, NULL, NULL),
(42, 16, NULL, '建议', '', '', 0, 1, 3, NULL, NULL),
(43, 16, NULL, '冷气机', '', '', 0, 1, 4, NULL, NULL),
(44, 16, NULL, '冰箱', '', '', 0, 1, 4, NULL, NULL),
(45, 16, NULL, '时鲜水果', '', 'images/7/2016/07/pha178817820h1jl301LhBB8jLYnZy.jpg', 0, 1, 5, NULL, NULL),
(46, 16, NULL, '电视机', '', '', 0, 1, 4, NULL, NULL),
(47, 16, NULL, '手机', '', '', 0, 1, 4, NULL, NULL);

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_report` (
  `id` int(11) unsigned NOT NULL,
  `uniacid` int(11) unsigned NOT NULL COMMENT '公众号ID',
  `organization_code` VARCHAR(32) NULL DEFAULT '' COMMENT 'organization_code',
  `virtual_code` VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'virtual_code',
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
  `print_sta` int(3) NOT NULL COMMENT '打印状态'
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

INSERT INTO `ims_superdesk_boardroom_4school_report` (`id`, `openid`, `uniacid`, `regionid`, `type`, `category`, `content`, `requirement`, `createtime`, `status`, `newmsg`, `rank`, `comment`, `resolve`, `resolver`, `resolvetime`, `address`, `images`, `print_sta`) VALUES
(50, 'oc_Czs-qxjmEokvRZCAnBEfD6LDQ', 16, 193, 1, '请选择报修类型', '测试测试题系', '', 1469540074, 1, 0, 2, '不错，反应很及时', '已处理', '李富贵', 1471070071, '海珠区中州中心1栋2409室', '', 0),
(51, 'oc_Czs-qxjmEokvRZCAnBEfD6LDQ', 16, 193, 1, '一般报事', '测试测试题阿婆', '', 1469544149, 2, 0, 0, '', '', '', 0, '海珠区中州中心1栋2409室', '50', 0),
(52, 'oc_Czs-qxjmEokvRZCAnBEfD6LDQ', 16, 193, 2, '投诉', '测试题系他的话', '', 1469544181, 2, 0, 0, '', '', '', 0, '海珠区中州中心1栋2409室', '', 0),
(53, 'oc_Czs-qxjmEokvRZCAnBEfD6LDQ', 16, 193, 1, '公共区域', '测试测试测试', '', 1471070214, 2, 0, 0, '', '', '', 0, '海珠区中州中心C栋307室', '', 0),
(54, 'oc_Czs-qxjmEokvRZCAnBEfD6LDQ', 16, 193, 1, '请选择报修类型', '测试题系他离', '', 1471070241, 2, 0, 0, '', '', '', 0, '海珠区中州中心C栋307室', '', 0),
(55, 'oc_Czs-qxjmEokvRZCAnBEfD6LDQ', 16, 193, 1, '请选择报修类型', '测试测试测试', '', 1471070255, 2, 0, 0, '', '', '', 0, '海珠区中州中心C栋307室', '', 0),
(56, 'oc_Czs-qxjmEokvRZCAnBEfD6LDQ', 16, 193, 1, '公共区域', '测试测啥饿死', '', 1471070270, 2, 0, 0, '', '', '', 0, '海珠区中州中心C栋307室', '', 0),
(57, 'oc_Czs-qxjmEokvRZCAnBEfD6LDQ', 16, 193, 1, '请选择报修类型', '水管爆裂，快找人来修', '', 1471070311, 2, 0, 0, '', '', '', 0, '海珠区中州中心C栋307室', '', 0),
(58, 'oc_Czs-qxjmEokvRZCAnBEfD6LDQ', 16, 193, 1, '公共区域', '热水为什么没有啊', '', 1471070341, 2, 0, 0, '', '', '', 0, '海珠区中州中心C栋307室', '', 0),
(59, 'oc_Czs-qxjmEokvRZCAnBEfD6LDQ', 16, 193, 1, '公共区域', '倒下的树挡住了路', '', 1471070410, 2, 0, 0, '', '', '', 0, '海珠区中州中心C栋307室', '', 0),
(60, 'oc_Czs-qxjmEokvRZCAnBEfD6LDQ', 16, 193, 1, '请选择报修类型', '声控灯无法关闭', '', 1471070447, 2, 0, 0, '', '', '', 0, '海珠区中州中心C栋307室', '', 0),
(61, 'oc_Czs-qxjmEokvRZCAnBEfD6LDQ', 16, 193, 1, '一般报事', '噪音太大了，无法入眠', '', 1471070482, 1, 0, 0, '', '已处理', '小区管理人', 1471078508, '海珠区中州中心C栋307室', '', 0),
(62, 'oc_Czs-qxjmEokvRZCAnBEfD6LDQ', 16, 193, 1, '一般报事', '处理速度要快', '', 1471082505, 2, 0, 0, '', '', '', 0, '海珠区中州中心C栋307室', '51', 0),
(63, 'oc_Czs-qxjmEokvRZCAnBEfD6LDQ', 16, 193, 1, '公共区域', '尽快修好水管问题', '', 1471082575, 2, 0, 0, '', '', '', 0, '海珠区中州中心C栋307室', '52', 0),
(64, 'oc_Czs-qxjmEokvRZCAnBEfD6LDQ', 16, 193, 1, '一般报事', '真的要修一下水管问题了', '', 1471082993, 2, 0, 0, '', '', '', 0, '海珠区中州中心C栋307室', '53,54,55', 0),
(65, 'oc_Czs-xPSVsRsXqtCwAbMPnDRKs', 16, 193, 1, '请选择报修类型', '沟沟壑壑和恍恍惚惚', '', 1480321770, 2, 0, 0, '', '', '', 0, '海珠区中州中心11栋4室', '', 0);

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_images` (
  `id` int(11) NOT NULL,
  `src` varchar(255) DEFAULT NULL,
  `file` longtext,
  `type` int(11) NOT NULL COMMENT '报修1，租赁2'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `ims_superdesk_boardroom_4school_category`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `ims_superdesk_boardroom_4school_report`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_uniacid_regionid` (`uniacid`,`regionid`);
ALTER TABLE `ims_superdesk_boardroom_4school_images`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `ims_superdesk_boardroom_4school_category`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=48;
ALTER TABLE `ims_superdesk_boardroom_4school_report`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=66;
ALTER TABLE `ims_superdesk_boardroom_4school_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;




CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_announcement_reading_member` (
  `id` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `organization_code` VARCHAR(32) NULL DEFAULT '' COMMENT 'organization_code',
  `virtual_code` VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'virtual_code',
  `unionid` varchar(50) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `aid` int(10) unsigned NOT NULL COMMENT '公告id',
  `status` varchar(1000) NOT NULL COMMENT '状态'

) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='公告阅读记录表';

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_4school_announcement` (
  `id` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `organization_code` VARCHAR(32) NULL DEFAULT '' COMMENT 'organization_code',
  `virtual_code` VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'virtual_code',
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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='发布公告';

ALTER TABLE `ims_superdesk_boardroom_4school_announcement`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `ims_superdesk_boardroom_4school_announcement_reading_member`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `ims_superdesk_boardroom_4school_announcement`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE `ims_superdesk_boardroom_4school_announcement_reading_member`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;


















# sync

//        array(8)
//        {
//            ["id"]=> string(1) "5"
//            ["uniacid"]=> string(2) "16"
//            ["uid"]=> string(2) "23"
//            ["organization_code"]=> string(9) "TJ-TJSWDX"
//            ["virtual_code"]=> string(6) "TJSWDX"
//            ["menus"]=> NULL ["balance"]=> NULL
//            ["commission"]=> string(3) "0.1"
//        }


ALTER TABLE `ims_superdesk_boardroom_4school_s_goods`
ADD `organization_code` VARCHAR(32) NULL DEFAULT '' COMMENT 'organization_code' AFTER `uniacid`,
ADD `virtual_code` VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'virtual_code' AFTER `organization_code`;
UPDATE `ims_superdesk_boardroom_4school_s_goods` SET organization_code = 'TJ-TJSWDX' , virtual_code = 'TJSWDX' WHERE uniacid = 16;

ALTER TABLE `ims_superdesk_boardroom_4school_s_category`
ADD `organization_code` VARCHAR(32) NULL DEFAULT '' COMMENT 'organization_code' AFTER `uniacid`,
ADD `virtual_code` VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'virtual_code' AFTER `organization_code`;
UPDATE `ims_superdesk_boardroom_4school_s_category` SET organization_code = 'TJ-TJSWDX' , virtual_code = 'TJSWDX' WHERE uniacid = 16;

ALTER TABLE `ims_superdesk_boardroom_4school_s_order`
ADD `organization_code` VARCHAR(32) NULL DEFAULT '' COMMENT 'organization_code' AFTER `uniacid`,
ADD `virtual_code` VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'virtual_code' AFTER `organization_code`;
UPDATE `ims_superdesk_boardroom_4school_s_order` SET organization_code = 'TJ-TJSWDX' , virtual_code = 'TJSWDX' WHERE uniacid = 16;

ALTER TABLE `ims_superdesk_boardroom_4school_appointment`
ADD `organization_code` VARCHAR(32) NULL DEFAULT '' COMMENT 'organization_code' AFTER `uniacid`,
ADD `virtual_code` VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'virtual_code' AFTER `organization_code`;
UPDATE `ims_superdesk_boardroom_4school_appointment` SET organization_code = 'TJ-TJSWDX' , virtual_code = 'TJSWDX' WHERE uniacid = 16;

ALTER TABLE `ims_superdesk_boardroom_4school_equipment`
ADD `organization_code` VARCHAR(32) NULL DEFAULT '' COMMENT 'organization_code' AFTER `uniacid`,
ADD `virtual_code` VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'virtual_code' AFTER `organization_code`;
UPDATE `ims_superdesk_boardroom_4school_equipment` SET organization_code = 'TJ-TJSWDX' , virtual_code = 'TJSWDX' WHERE uniacid = 16;

ALTER TABLE `ims_superdesk_boardroom_4school`
ADD `organization_code` VARCHAR(32) NULL DEFAULT '' COMMENT 'organization_code' AFTER `uniacid`,
ADD `virtual_code` VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'virtual_code' AFTER `organization_code`;
UPDATE `ims_superdesk_boardroom_4school` SET organization_code = 'TJ-TJSWDX' , virtual_code = 'TJSWDX' WHERE uniacid = 16;

ALTER TABLE `ims_superdesk_boardroom_4school_building_attribute`
ADD `organization_code` VARCHAR(32) NULL DEFAULT '' COMMENT 'organization_code' AFTER `uniacid`,
ADD `virtual_code` VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'virtual_code' AFTER `organization_code`;
UPDATE `ims_superdesk_boardroom_4school_building_attribute` SET organization_code = 'TJ-TJSWDX' , virtual_code = 'TJSWDX' WHERE uniacid = 16;

ALTER TABLE `ims_superdesk_boardroom_4school_building_structures`
ADD `organization_code` VARCHAR(32) NULL DEFAULT '' COMMENT 'organization_code' AFTER `uniacid`,
ADD `virtual_code` VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'virtual_code' AFTER `organization_code`;
UPDATE `ims_superdesk_boardroom_4school_building_structures` SET organization_code = 'TJ-TJSWDX' , virtual_code = 'TJSWDX' WHERE uniacid = 16;



# sync
ALTER TABLE `ims_superdesk_boardroom_4school` ADD `organization_code` VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'organization_code' AFTER `address`;


ALTER TABLE `ims_superdesk_boardroom_4school_appointment` CHANGE `lable_ymd` `lable_ymd` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'eg:2017-09-09';
ALTER TABLE `ims_superdesk_boardroom_4school_appointment` CHANGE `lable_time` `lable_time` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'eg:00:30-12:00';















# sync
ALTER TABLE `ims_superdesk_boardroom_4school_appointment` ADD `subject` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '会议主题' AFTER `openid`;

ALTER TABLE `ims_superdesk_boardroom_4school`
ADD `desk` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '桌子' AFTER `equipment`,
ADD `chair` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '椅子' AFTER `desk`;

ALTER TABLE `ims_superdesk_boardroom_4school` CHANGE `floor` `structures_parentid` INT(10) NOT NULL DEFAULT '0' COMMENT '楼宇';
ALTER TABLE `ims_superdesk_boardroom_4school` ADD `structures_childid` INT(10) NOT NULL DEFAULT '0' COMMENT '楼层' AFTER `structures_parentid`;

ALTER TABLE `ims_superdesk_boardroom_4school` ADD `attribute` INT(10) NOT NULL DEFAULT '0' COMMENT '属性' AFTER `structures_childid`;