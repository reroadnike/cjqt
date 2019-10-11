## 已更新到服务器

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
  `express_sn` varchar(50) DEFAULT '' COMMENT '快递单号',
  `press_type` tinyint(4) DEFAULT '0' COMMENT '催款类型(1:业务催款,2:财务催款)',
  `press_msg` text COMMENT '催款跟进记录',
  `press_status` tinyint(4) DEFAULT '1' COMMENT '是否回款(1:未回款,2:已回款)',
  `press_time` int(11) DEFAULT '0' COMMENT '回款时间',
  `createtime` int(11) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='订单财务表(开票,寄票,催款)';

