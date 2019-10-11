


# 已更新到企业正式

-- 保存订单转介给客户的申请.
CREATE TABLE `ims_superdesk_shop_order_transfer_member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `orderid` int(11) NOT NULL DEFAULT '0' COMMENT '订单id',
  `old_openid` varchar(40) NOT NULL DEFAULT '' COMMENT '转让前的openid',
  `old_enterprise_id` int(11) DEFAULT '0',
  `old_enterprise_name` varchar(100) DEFAULT '',
  `old_organization_id` int(11) DEFAULT '0',
  `old_invoice_id` int(11) DEFAULT '0',
  `old_invoice` text,
  `old_address_id` int(11) DEFAULT '0',
  `old_address` text,
  `new_openid` varchar(40) NOT NULL DEFAULT '' COMMENT '转让后的openidid',
  `new_enterprise_id` int(11) DEFAULT '0',
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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

