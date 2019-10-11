--福利商城已更新
--企业商城已更新

-- 账单表
CREATE TABLE `ims_superdesk_jd_vop_bill` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `billNo` varchar(32) DEFAULT '' COMMENT '账单编号',
  `billStartDate` bigint(20) DEFAULT '0' COMMENT '账单开始日期',
  `billEndDate` bigint(20) DEFAULT '0' COMMENT '账单结束日期',
  `repayDate` bigint(20) DEFAULT '0' COMMENT '应还款日期',
  `billAmount` decimal(10,2) DEFAULT '0.00' COMMENT '账单金额',
  `status` int(5) DEFAULT '0' COMMENT '还款状态(5001=正常，5002=延期，5003=逾期，5004=已结清，5005=延期已结清，5006=逾期已结清)',
  `overDays` int(5) DEFAULT '0' COMMENT '逾期天数',
  `overAmount` decimal(10,2) DEFAULT '0.00' COMMENT '违约金',
  `billType` int(5) DEFAULT '0' COMMENT '账单类型(5106：月结；5170：周结；5102：文档没写)',
  `execRate` decimal(5,2) DEFAULT '0.00' COMMENT '服务费率（执行利率）',
  `sumPri` decimal(10,2) DEFAULT '0.00' COMMENT '已还本金',
  `sumNrPri` decimal(10,2) DEFAULT '0.00' COMMENT '未还本金',
  `sumDefer` decimal(10,2) DEFAULT '0.00' COMMENT '已还延期服务费',
  `sumNrDefer` decimal(10,2) DEFAULT '0.00' COMMENT '未还延期服务费',
  `sumDelin` decimal(10,2) DEFAULT '0.00' COMMENT '已还违约服务费',
  `sumNrDelin` decimal(10,2) DEFAULT '0.00' COMMENT '未还违约服务费',
  `delinDate` bigint(20) DEFAULT '0' COMMENT '违约开始日期',
  `createtime` int(11) DEFAULT '0' COMMENT '建立时间',
  `updatetime` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- 订单表
CREATE TABLE `ims_superdesk_jd_vop_bill_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `orderNo` bigint(20) DEFAULT '0' COMMENT '订单号',
  `billNo` varchar(32) DEFAULT '' COMMENT '账单编号',
  `orderAmount` decimal(10,2) DEFAULT '0.00' COMMENT '订单金额',
  `settleStatus` int(5) DEFAULT '0' COMMENT '结清状态(2500:未结清；2501:已结清)',
  `settleDate` bigint(20) DEFAULT '0' COMMENT '结算时间',
  `sumRefundAmount` decimal(10,2) DEFAULT '0.00' COMMENT '总退款金额',
  `customerName` varchar(255) DEFAULT '' COMMENT '结算单位',
  `shopOrderSn` varchar(30) DEFAULT '' COMMENT '商城订单编号',
  `shopOrderPrice` decimal(10,2) DEFAULT '0.00' COMMENT '商城订单金额',
  `shopOrderStatus` tinyint(3) DEFAULT '0' COMMENT '商城订单状态',
  `orderFreight` int(11) DEFAULT '0' COMMENT '运费',
  `isRight` tinyint(4) DEFAULT '0' COMMENT '是否正确',
  `createtime` int(11) DEFAULT '0' COMMENT '建立时间',
  `updatetime` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1330 DEFAULT CHARSET=utf8;

