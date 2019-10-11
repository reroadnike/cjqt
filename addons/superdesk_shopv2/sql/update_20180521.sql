## 已更新到服务器

CREATE TABLE `ims_superdesk_shop_order_transfer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `orderid` int(11) NOT NULL DEFAULT '0' COMMENT '订单id',
  `old_id` int(11) NOT NULL DEFAULT '0' COMMENT '转让前的merchid',
  `new_id` int(11) NOT NULL DEFAULT '0' COMMENT '转让后的merchid',
  `createtime` int(12) NOT NULL DEFAULT '0' COMMENT '订单的createtime',
  `updatetime` int(12) NOT NULL DEFAULT '0' COMMENT '转让时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

