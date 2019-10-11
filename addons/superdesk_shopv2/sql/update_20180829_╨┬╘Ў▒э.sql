-- 京东分类添加是否京东字段is_jd	tinyint	3	0	-1	0	0	0	0	0	0	是否京东分类(0否1是)				0	0
ALTER TABLE `ims_superdesk_shop_category`
ADD `is_jd` tinyint(3) DEFAULT '0' COMMENT '是否京东分类(0否1是)' AFTER `old_shop_cate_id`;

CREATE TABLE `ims_superdesk_shop_enterprise_import_address_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `import_sn` varchar(50) DEFAULT '' COMMENT '导入excel名称',
  `openid` varchar(50) DEFAULT NULL,
  `realname` varchar(50) DEFAULT '' COMMENT '导入时的真实姓名',
  `mobile` varchar(11) DEFAULT '0' COMMENT '电话号码',
  `account_id` int(11) DEFAULT '0' COMMENT '操作员id',
  `province` varchar(30) DEFAULT '',
  `city` varchar(30) DEFAULT '',
  `area` varchar(30) DEFAULT '',
  `town` varchar(30) NOT NULL DEFAULT '' COMMENT '四级地址',
  `address` varchar(300) DEFAULT '',
  `jd_vop_province_code` int(11) NOT NULL DEFAULT '0' COMMENT '京东一级province_code',
  `jd_vop_city_code` int(11) NOT NULL DEFAULT '0' COMMENT '京东二级city_code',
  `jd_vop_county_code` int(11) NOT NULL DEFAULT '0' COMMENT '京东三级county_code',
  `jd_vop_town_code` int(11) NOT NULL DEFAULT '0' COMMENT '京东四级town_code',
  `jd_vop_area` varchar(64) NOT NULL DEFAULT '' COMMENT '用于查库存与下单 格式：1_0_0 (分别代表1、2、3级地址)',
  `createtime` int(11) DEFAULT '0',
  `updatetime` int(11) DEFAULT '0',
  `create_user` tinyint(3) DEFAULT '0' COMMENT '是否有创建用户(0无1有)',
  `enterprise_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='企业端员工地址导入记录表';

