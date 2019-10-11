INSERT INTO `ims_superdesk_shop_plugin` (`id`, `displayorder`, `identity`, `name`, `version`, `author`, `status`, `category`, `isv2`, `thumb`, `desc`, `iscom`, `deprecated`) VALUES
(99, 99, 'enterprise', '多企业', '1.0', '官方', 1, 'biz', 1, '../addons/superdesk_shopv2/static/images/enterprise.jpg', '', 0, 0);


CREATE TABLE `ims_superdesk_shop_enterprise_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `enterprise_id` int(11) DEFAULT '0' COMMENT '企业ID',
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
  KEY `idx_merchid` (`enterprise_id`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `ims_superdesk_shop_enterprise_import_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT NULL,
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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='企业端员工导入记录表';


CREATE TABLE `ims_superdesk_shop_enterprise_perm_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `name` varchar(255) DEFAULT '',
  `type` varchar(255) DEFAULT '',
  `op` text,
  `ip` varchar(255) DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  `enterprise_id` int(11) DEFAULT '0',
  `updatetime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_createtime` (`createtime`) USING BTREE,
  KEY `idx_uniacid` (`uniacid`) USING BTREE,
  KEY `idx_merchid` (`enterprise_id`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

CREATE TABLE `ims_superdesk_shop_enterprise_perm_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `enterprise_id` int(11) DEFAULT '0',
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
  KEY `merchid` (`enterprise_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

CREATE TABLE `ims_superdesk_shop_enterprise_reg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
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

CREATE TABLE `ims_superdesk_shop_enterprise_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `regid` int(11) DEFAULT '0' COMMENT '企业注册ID',
  `groupid` int(11) DEFAULT '0' COMMENT '企业分组ID',
  `cateid` int(11) DEFAULT '0' COMMENT '企业分类ID',
  `openid` varchar(255) NOT NULL DEFAULT '',
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


DROP TABLE `ims_superdesk_shop_enterprise_account`, `ims_superdesk_shop_enterprise_category`, `ims_superdesk_shop_enterprise_group`, `ims_superdesk_shop_enterprise_perm_log`, `ims_superdesk_shop_enterprise_perm_role`, `ims_superdesk_shop_enterprise_reg`, `ims_superdesk_shop_enterprise_user`;


