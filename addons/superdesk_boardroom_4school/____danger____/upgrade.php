<?php


$sql = "
CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_s_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `realname` varchar(20) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `province` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `area` varchar(30) NOT NULL,
  `address` varchar(300) NOT NULL,
  `isdefault` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE if not exists  `ims_superdesk_boardroom_s_dispatch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `dispatchname` varchar(50) DEFAULT '',
  `dispatchtype` int(11) default 0,
  `displayorder` int(11) DEFAULT '0',
  `firstprice` decimal(10,2) DEFAULT '0.00',
  `secondprice` decimal(10,2) DEFAULT '0.00',
  `firstweight` int(11) DEFAULT '0',
  `secondweight` int(11) DEFAULT '0',
  `express` int(11) DEFAULT '0',
  `enabled` int(11) NOT NULL DEFAULT '0',
  `description` text,
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE if not exists  `ims_superdesk_boardroom_s_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `express_name` varchar(50) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `express_price` varchar(10) DEFAULT '',
  `express_area` varchar(100) DEFAULT '',
  `express_url` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE if not exists `ims_superdesk_boardroom_s_goods_option` (
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
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`goodsid`),KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		
CREATE TABLE if not exists `ims_superdesk_boardroom_s_goods_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `value` text,
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),KEY `indx_goodsid` (`goodsid`),KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE if not exists `ims_superdesk_boardroom_s_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) default 0,
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),KEY `indx_uniacid` (`uniacid`),KEY `indx_enabled` (`enabled`),KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_superdesk_boardroom_s_spec` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `displaytype` tinyint(3) unsigned NOT NULL,
  `content` text NOT NULL,
  `goodsid` int(11) default 0,
  `displayorder` int(11) default 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE if not exists `ims_superdesk_boardroom_s_spec_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) default 0,
  `specid` int(11) default 0,
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `show` int(11) default 0,
  `displayorder` int(11) default 0,
  PRIMARY KEY (`id`),KEY `indx_uniacid` (`uniacid`),KEY `indx_specid` (`specid`),KEY `indx_show` (`show`),KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

";

pdo_run($sql);

if(pdo_fieldexists('superdesk_boardroom_s_goods', 'marketprice')) {
	pdo_query("ALTER TABLE  ".tablename('superdesk_boardroom_4school_s_goods')." CHANGE `marketprice` `marketprice` decimal(10,2) NOT NULL DEFAULT '0';");
}
if(pdo_fieldexists('superdesk_boardroom_s_goods', 'productprice')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_goods')." CHANGE `productprice` `productprice` decimal(10,2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'costprice')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_goods')." ADD `costprice` decimal(10,2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'weight')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_goods')." ADD `weight` decimal(10,2) NOT NULL DEFAULT '0';");
}
 if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'totalcnf')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_goods')." ADD `totalcnf` tinyint(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'credit')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_goods')." ADD `credit` int(11) NOT NULL DEFAULT '0';");
}
 if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'hasoption')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_goods')." ADD `hasoption` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'maxbuy')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_goods')." ADD `maxbuy` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods_option', 'productprice')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_goods_option')." ADD `productprice` decimal(10,2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'thumb_url')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_goods')." ADD `thumb_url` text;");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'dispatch')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_goods')." ADD `dispatch` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'isrecommand')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_goods')." ADD `isrecommand` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'isnew')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_goods')." ADD `isnew` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'ishot')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_goods')." ADD `ishot` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'istime')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_goods')." ADD `istime` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'timestart')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_goods')." ADD `timestart` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'timeend')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_goods')." ADD `timeend` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_category', 'thumb')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_category')." ADD `thumb` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_category', 'isrecommand')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_category')." ADD `isrecommand` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_category', 'enabled')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_category')." ADD `enabled` tinyint(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_cart', 'optionid')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_cart')." ADD `optionid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_cart', 'marketprice')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_cart')." ADD `marketprice` decimal(10,2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_order', 'dispatchprice')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_order')." ADD `dispatchprice` decimal(10,2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_order', 'goodsprice')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_order')." ADD `goodsprice` decimal(10,2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_order', 'dispatch')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_order')." ADD `dispatch` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_order', 'express')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_order')." ADD `express` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_order_goods', 'optionid')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_order_goods')." ADD `optionid` int(11) NOT NULL DEFAULT '0';");
}

if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'isdiscount')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_goods')." ADD `isdiscount` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'viewcount')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_goods')." ADD `viewcount` int(11) NOT NULL DEFAULT '0';");
}

if(pdo_fieldexists('superdesk_boardroom_s_adv', 'link')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_adv')." CHANGE `link` `link` varchar(255) NOT NULL DEFAULT '';");
}

if(!pdo_fieldexists('superdesk_boardroom_s_dispatch', 'dispatchtype')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_dispatch')." ADD `dispatchtype` int(11) NOT NULL DEFAULT '0';");
}

if(!pdo_fieldexists('superdesk_boardroom_s_category', 'description')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_category')." ADD `description` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'deleted')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_goods')." ADD `deleted` tinyint(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_address', 'deleted')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_address')." ADD `deleted` tinyint(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_order_goods', 'price')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_order_goods')." ADD `price` decimal(10,2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_spec', 'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_spec')." ADD `goodsid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_spec', 'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_spec')." ADD `displayorder` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superdesk_boardroom_s_order_goods', 'optionname')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_order_goods')." ADD `optionname` text;");
}
if(!pdo_fieldexists('superdesk_boardroom_s_goods_option', 'specs')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_goods_option')." ADD `specs` text;");
}
global $_W;
//更新原有规格
$goods = pdo_fetchall("select id from ".tablename('superdesk_boardroom_4school_s_goods')." where uniacid=:uniacid",array(":uniacid"=>$_W['uniacid']));
$optionids = array();
foreach($goods as $o){
	
	$goods_options  =pdo_fetchall("select * from ".tablename('superdesk_boardroom_4school_s_goods_option')." where goodsid=:goodsid and specs='' order by id asc",array(":goodsid"=>$o['id']));
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
$goods = pdo_fetchall("select id,costprice,productprice from ".tablename('superdesk_boardroom_4school_s_goods')." where uniacid=:uniacid",array(":uniacid"=>$_W['uniacid']));
foreach($goods as $o){
	
	$costprice = $o['costprice'];
	$productprice = $o['productprice'];
	pdo_update("superdesk_boardroom_s_goods_option",array("costprice"=>$productprice,"productprice"=>$costprice),array("id"=>$o['id']));
	
}

//更新options
$options  =pdo_fetchall("select id, specs from ".tablename('superdesk_boardroom_4school_s_goods_option')." where specs<>''");
foreach($options as $o){
	
	$specs =explode("_",$o['specs']);
	$titles = array();
	foreach($specs as $sp){
		$item = pdo_fetch("select title from ".tablename('superdesk_boardroom_4school_s_spec_item')." where id=:id limit 1",array(":id"=>$sp));
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
	pdo_query("ALTER TABLE  ".tablename('superdesk_boardroom_4school_s_goods')." CHANGE `thumb` `thumb` varchar(255) DEFAULT '';");
}

if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'spec')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_goods')." ADD `spec` varchar(5000) NOT NULL DEFAULT '';");
}

if(!pdo_fieldexists('superdesk_boardroom_s_goods', 'originalprice')) {
	pdo_query("ALTER TABLE ".tablename('superdesk_boardroom_4school_s_goods')." ADD `originalprice` DECIMAL(10, 2) NOT NULL DEFAULT '0.00' COMMENT '原价' AFTER `costprice`;");
}

if (!pdo_fieldexists('superdesk_boardroom_s_order', 'paydetail')) {
	pdo_query('ALTER TABLE ' . tablename('superdesk_boardroom_4school_s_order') . " ADD `paydetail` VARCHAR(255) NOT NULL COMMENT '支付详情' AFTER `dispatch`;");
}

if (!pdo_fieldexists('superdesk_boardroom_s_goods', 'usermaxbuy')) {
	pdo_query('ALTER TABLE ' . tablename('superdesk_boardroom_4school_s_goods') . " ADD `usermaxbuy` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户最多购买数量' AFTER `maxbuy`;");
}

if (pdo_fieldexists('superdesk_boardroom_s_goods', 'total')) {
	pdo_query('ALTER TABLE ' . tablename('superdesk_boardroom_4school_s_goods') . " CHANGE `total` `total` INT(10) UNSIGNED NOT NULL DEFAULT '0';");
}

if (pdo_fieldexists('superdesk_boardroom_s_goods', 'credit')) {
	pdo_query('ALTER TABLE ' . tablename('superdesk_boardroom_4school_s_goods') . " CHANGE `credit` `credit` DECIMAL(10,2) NOT NULL DEFAULT '0.00';");
}

if (!pdo_fieldexists('superdesk_boardroom_s_address', 'zipcode')) {
	pdo_query('ALTER TABLE ' . tablename('superdesk_boardroom_4school_s_address') . " ADD `zipcode` VARCHAR(6) NOT NULL COMMENT '邮政编码' AFTER `mobile`;");
}

// 删除收货地址表，共享个人中心收货地址
$sql = 'DROP TABLE IF EXISTS ' . tablename('superdesk_boardroom_4school_s_address');
pdo_query($sql);

// 订单数据表收货地址信息更新
if (!pdo_fieldexists('superdesk_boardroom_s_order', 'address')) {
	pdo_query('ALTER TABLE ' . tablename('superdesk_boardroom_4school_s_order') . " CHANGE `addressid` `address` VARCHAR(1024) NOT NULL DEFAULT '' COMMENT '收货地址信息'");
}
$sql = 'SELECT `id`, `from_user`, `address` FROM ' . tablename('superdesk_boardroom_4school_s_order');
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
    pdo_query('ALTER TABLE ' . tablename('superdesk_boardroom_4school_s_dispatch') . " ADD `enabled` int(11) NOT NULL DEFAULT '0';");
}