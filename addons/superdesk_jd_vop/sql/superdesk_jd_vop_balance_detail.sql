


## 已更新到服务器

CREATE TABLE `ims_superdesk_jd_vop_balance_detail` (
  `id` int(11) NOT NULL COMMENT '余额明细 ID',
  `accountType` tinyint(4) NOT NULL DEFAULT '0' COMMENT '账户类型',
  `amount` decimal(10,2) NOT NULL COMMENT '金额(元)',
  `pin` varchar(256) NOT NULL DEFAULT '' COMMENT '京东 Pin',
  `orderId` BIGINT(13) NOT NULL DEFAULT '0' COMMENT '订单号',
  `tradeType` int(11) NOT NULL DEFAULT '0' COMMENT '业务类型',
  `tradeTypeName` varchar(1000) NOT NULL COMMENT '业务类型名称',
  `createdDate` datetime NOT NULL COMMENT '余额变动日期',
  `notePub` text NOT NULL COMMENT '备注信息',
  `tradeNo` BIGINT(13) NOT NULL DEFAULT '0' COMMENT '业务号'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `ims_superdesk_jd_vop_balance_detail`
  ADD PRIMARY KEY (`id`);
COMMIT;

ALTER TABLE `ims_superdesk_jd_vop_balance_detail` ADD `processing` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '0未处理,1已处理' AFTER `tradeNo`;
ALTER TABLE `ims_superdesk_jd_vop_balance_detail` ADD `process_result` VARCHAR(1000) NOT NULL DEFAULT '' COMMENT '处理结果' AFTER `processing`;



-- miss

ALTER TABLE `ims_superdesk_jd_vop_balance_detail`
CHANGE `id` `id` INT(11) NOT NULL COMMENT '余额明细 ID',
CHANGE `accountType` `accountType` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '账户类型',
CHANGE `amount` `amount` DECIMAL(10,2) NOT NULL COMMENT '金额(元)',
CHANGE `pin` `pin` VARCHAR(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '京东 Pin',
CHANGE `orderId` `orderId` INT(11) NOT NULL DEFAULT '0' COMMENT '订单号',
CHANGE `tradeType` `tradeType` INT(11) NOT NULL DEFAULT '0' COMMENT '业务类型',
CHANGE `tradeTypeName` `tradeTypeName` VARCHAR(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '业务类型名称',
CHANGE `createdDate` `createdDate` DATETIME NOT NULL COMMENT '余额变动日期',
CHANGE `notePub` `notePub` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '备注信息',
CHANGE `tradeNo` `tradeNo` INT(11) NOT NULL DEFAULT '0' COMMENT '业务号';

ALTER TABLE `ims_superdesk_jd_vop_balance_detail`
CHANGE `orderId` `orderId` VARCHAR(16) NOT NULL DEFAULT '' COMMENT '订单号';

ALTER TABLE `ims_superdesk_jd_vop_balance_detail` CHANGE `tradeNo` `tradeNo` TINYINT(11) NOT NULL DEFAULT '0' COMMENT '业务号';
ALTER TABLE `ims_superdesk_jd_vop_balance_detail` CHANGE `orderId` `orderId` BIGINT(13) NOT NULL DEFAULT '0' COMMENT '订单号';