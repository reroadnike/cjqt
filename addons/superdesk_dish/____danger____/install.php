<?php


$sql = "
CREATE TABLE `ims_superdesk_dish_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `realname` varchar(20) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `address` varchar(300) NOT NULL,
  `dateline` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=403 DEFAULT CHARSET=utf8;
CREATE TABLE `ims_superdesk_dish_area` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '区域名称',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

CREATE TABLE `ims_superdesk_dish_blacklist` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(100) DEFAULT '' COMMENT '用户ID',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`from_user`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

CREATE TABLE `ims_superdesk_dish_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `storeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店id',
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

CREATE TABLE `ims_superdesk_dish_collection` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `dateline` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;

CREATE TABLE `ims_superdesk_dish_intelligent` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `storeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店id',
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` int(10) NOT NULL DEFAULT '0' COMMENT '适用人数',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '菜品',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

CREATE TABLE `ims_superdesk_dish_mealtime` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `begintime` varchar(20) DEFAULT '09:00' COMMENT '开始时间',
  `endtime` varchar(20) DEFAULT '18:00' COMMENT '结束时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

CREATE TABLE `ims_superdesk_dish_print_order` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `print_usr` varchar(50) DEFAULT '',
  `print_status` tinyint(1) DEFAULT '-1',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;

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
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

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

CREATE TABLE `ims_superdesk_dish_store_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `order_enable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '订餐开启',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
CREATE TABLE  `ims_superdesk_dish_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '类型名称',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
";

pdo_run($sql);


// 修正例子
// -------------------------------------------------------------------------------------------------------------------

if(pdo_fieldexists('superdesk_boardroom_s_goods', 'marketprice')) {
	pdo_query("ALTER TABLE  ".tablename('superdesk_boardroom_s_goods')." CHANGE `marketprice` `marketprice` decimal(10,2) NOT NULL DEFAULT '0';");
}
if(pdo_fieldexists('superdesk_boardroom_s_goods', 'productprice')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_goods')." CHANGE `productprice` `productprice` decimal(10,2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'costprice')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_goods')." ADD `costprice` decimal(10,2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'weight')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_goods')." ADD `weight` decimal(10,2) NOT NULL DEFAULT '0';");
}
 if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'totalcnf')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_goods')." ADD `totalcnf` tinyint(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'credit')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_goods')." ADD `credit` int(11) NOT NULL DEFAULT '0';");
}
 if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'hasoption')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_goods')." ADD `hasoption` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'maxbuy')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_goods')." ADD `maxbuy` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods_option', 'productprice')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_goods_option')." ADD `productprice` decimal(10,2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'thumb_url')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_goods')." ADD `thumb_url` text;");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'dispatch')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_goods')." ADD `dispatch` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'isrecommand')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_goods')." ADD `isrecommand` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'isnew')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_goods')." ADD `isnew` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'ishot')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_goods')." ADD `ishot` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'istime')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_goods')." ADD `istime` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'timestart')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_goods')." ADD `timestart` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'timeend')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_goods')." ADD `timeend` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_category', 'thumb')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_category')." ADD `thumb` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_category', 'isrecommand')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_category')." ADD `isrecommand` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_category', 'enabled')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_category')." ADD `enabled` tinyint(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_cart', 'optionid')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_cart')." ADD `optionid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_cart', 'marketprice')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_cart')." ADD `marketprice` decimal(10,2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_order', 'dispatchprice')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_order')." ADD `dispatchprice` decimal(10,2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_order', 'goodsprice')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_order')." ADD `goodsprice` decimal(10,2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_order', 'dispatch')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_order')." ADD `dispatch` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_order', 'express')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_order')." ADD `express` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_order_goods', 'optionid')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_order_goods')." ADD `optionid` int(11) NOT NULL DEFAULT '0';");
}

if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'isdiscount')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_goods')." ADD `isdiscount` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'viewcount')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_goods')." ADD `viewcount` int(11) NOT NULL DEFAULT '0';");
}

if(pdo_fieldexists('superdesk_boardroom_s_adv', 'link')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_adv')." CHANGE `link` `link` varchar(255) NOT NULL DEFAULT '';");
}

if(!pdo_fieldexists('superdesk_boardroom_s_dispatch', 'dispatchtype')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_dispatch')." ADD `dispatchtype` int(11) NOT NULL DEFAULT '0';");
}

if(!pdo_fieldexists('superdesk_boardroom_s_category', 'description')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_category')." ADD `description` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'deleted')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_goods')." ADD `deleted` tinyint(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_address', 'deleted')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_address')." ADD `deleted` tinyint(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_order_goods', 'price')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_order_goods')." ADD `price` decimal(10,2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_spec', 'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_spec')." ADD `goodsid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_spec', 'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_spec')." ADD `displayorder` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_order_goods', 'optionname')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_order_goods')." ADD `optionname` text;");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods_option', 'specs')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_goods_option')." ADD `specs` text;");
}
global $_W;
//更新原有规格
$goods = pdo_fetchall("select id from ".tablename('superdesk_boardroom_s_goods')." where uniacid=:uniacid",array(":uniacid"=>$_W['uniacid']));
$optionids = array();
foreach($goods as $o){
	
	$goods_options  =pdo_fetchall("select * from ".tablename('superdesk_boardroom_s_goods_option')." where goodsid=:goodsid and specs='' order by id asc",array(":goodsid"=>$o['id']));
	if(count($goods_options)<=0){
		continue;
	}
	
	//生成spec
	$spec = array(
		"uniacid"=>$_W['uniacid'],
		"title"=>"规格",
		"goodsid"=>$o['id'],
		"description"=>"",
		"displaytype"=>0,
		"content"=>  serialize(array()),
		"displayorder"=>0
	);
	pdo_insert("superdesk_boardroom_s_spec",$spec);
	$specid = pdo_insertid();
	$n = 0;
	$spec_item_ids = array();
	foreach ($goods_options as $go){
	
		$spec_item  = array(
			"uniacid"=>$_W['uniacid'],
			"specid"=>$specid,
			"title"=>$go['title'],
			"show"=>1,
			"displayorder"=>$n,
			"thumb"=>$go['thumb']
		);
		pdo_insert("superdesk_boardroom_s_spec_item",$spec_item);
		$spec_item_id = pdo_insertid();
	
		pdo_update("superdesk_boardroom_s_goods_option",array("specs"=>$spec_item_id),array("id"=>$go['id']));
	}
	
}

//交换成本价和市场价位置
$goods = pdo_fetchall("select id,costprice,productprice from ".tablename('superdesk_boardroom_s_goods')." where uniacid=:uniacid",array(":uniacid"=>$_W['uniacid']));
foreach($goods as $o){
	
	$costprice = $o['costprice'];
	$productprice = $o['productprice'];
	pdo_update("superdesk_boardroom_s_goods_option",array("costprice"=>$productprice,"productprice"=>$costprice),array("id"=>$o['id']));
	
}

//更新options
$options  =pdo_fetchall("select id, specs from ".tablename('superdesk_boardroom_s_goods_option')." where specs<>''");
foreach($options as $o){
	
	$specs =explode("_",$o['specs']);
	$titles = array();
	foreach($specs as $sp){
		$item = pdo_fetch("select title from ".tablename('superdesk_boardroom_s_spec_item')." where id=:id limit 1",array(":id"=>$sp));
		if($item){
			$titles[] = $item['title'];
		}
	}
	$titles =implode("+",$titles);
	pdo_update("superdesk_boardroom_s_goods_option",array("title"=>$titles),array("id"=>$o['id']));
	pdo_update("superdesk_boardroom_s_order_goods",array("optionname"=>$titles),array("optionid"=>$o['id']));
	
}

//字段长度
if(pdo_fieldexists('superdesk_boardroom_s_goods', 'thumb')) {
	pdo_query("ALTER TABLE  ".tablename('superdesk_boardroom_s_goods')." CHANGE `thumb` `thumb` varchar(255) DEFAULT '';");
}

if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'spec')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_goods')." ADD `spec` varchar(5000) NOT NULL DEFAULT '';");
}

if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'originalprice')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_s_goods')." ADD `originalprice` DECIMAL(10, 2) NOT NULL DEFAULT '0.00' COMMENT '原价' AFTER `costprice`;");
}

if (!pdo_fieldexists('superdesk_boardroom_s_order', 'paydetail')) {
	pdo_query('ALTER TABLE ' . tablename('superdesk_boardroom_s_order') . " ADD `paydetail` VARCHAR(255) NOT NULL COMMENT '支付详情' AFTER `dispatch`;");
}

if (!pdo_fieldexists('superdesk_boardroom_s_goods', 'usermaxbuy')) {
	pdo_query('ALTER TABLE ' . tablename('superdesk_boardroom_s_goods') . " ADD `usermaxbuy` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户最多购买数量' AFTER `maxbuy`;");
}

if (pdo_fieldexists('superdesk_boardroom_s_goods', 'total')) {
	pdo_query('ALTER TABLE ' . tablename('superdesk_boardroom_s_goods') . " CHANGE `total` `total` INT(10) UNSIGNED NOT NULL DEFAULT '0';");
}

if (pdo_fieldexists('superdesk_boardroom_s_goods', 'credit')) {
	pdo_query('ALTER TABLE ' . tablename('superdesk_boardroom_s_goods') . " CHANGE `credit` `credit` DECIMAL(10,2) NOT NULL DEFAULT '0.00';");
}

if (!pdo_fieldexists('superdesk_boardroom_s_address', 'zipcode')) {
	pdo_query('ALTER TABLE ' . tablename('superdesk_boardroom_s_address') . " ADD `zipcode` VARCHAR(6) NOT NULL COMMENT '邮政编码' AFTER `mobile`;");
}

// 删除收货地址表，共享个人中心收货地址
$sql = 'DROP TABLE IF EXISTS ' . tablename('superdesk_boardroom_s_address');
pdo_query($sql);

// 订单数据表收货地址信息更新
if (!pdo_fieldexists('superdesk_boardroom_s_order', 'address')) {
	pdo_query('ALTER TABLE ' . tablename('superdesk_boardroom_s_order') . " CHANGE `addressid` `address` VARCHAR(1024) NOT NULL DEFAULT '' COMMENT '收货地址信息'");
}
$sql = 'SELECT `id`, `from_user`, `address` FROM ' . tablename('superdesk_boardroom_s_order');
$orders = pdo_fetchall($sql);

load()->model('mc');
$update = array();

foreach ($orders as $order) {
	if (is_numeric($order['address'])) {
		$sql = 'SELECT * FROM ' . tablename('mc_member_address') . ' WHERE `id` = :id';
		$address = pdo_fetch($sql, array(':id' => intval($order['address'])));
		if (empty($address)) {
			$sql = 'SELECT * FROM ' . tablename('mc_member_address') . ' WHERE `uid` = :uid';
			$address = pdo_fetch($sql, array(':uid' => mc_openid2uid($order['from_user'])));
		}
		if (empty($address)) {
			$address = mc_fetch($order['from_user']);
			$update['address'] = $address['realname'] . '|' . $address['mobile'] . '|' . $address['zipcode']
				. '|' . $address['resideprovince'] . '|' . $address['residecity'] . '|' .
				$address['residedist'] . '|' . $address['address'];
		} else {
			$update['address'] = $address['username'] . '|' . $address['mobile'] . '|' . $address['zipcode']
				. '|' . $address['province'] . '|' . $address['city'] . '|' .
				$address['district'] . '|' . $address['address'];
		}

		// 更新订单表收货字段信息
		pdo_update('superdesk_boardroom_s_order', $update, array('id' => $order['id']));
	}
}
if (!pdo_fieldexists('superdesk_boardroom_s_dispatch', 'enabled')) {
    pdo_query('ALTER TABLE ' . tablename('superdesk_boardroom_s_dispatch') . " ADD `enabled` int(11) NOT NULL DEFAULT '0';");
}