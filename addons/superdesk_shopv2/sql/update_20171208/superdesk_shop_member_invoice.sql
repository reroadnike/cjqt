





CREATE TABLE IF NOT EXISTS `ims_superdesk_shop_member_invoice` (
  `id` int(11) NOT NULL,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '',

  `realname` varchar(20) DEFAULT '' COMMENT '个人发票抬头',
  `invoiceState` int(11) DEFAULT 1 COMMENT '开票方式(1为随货开票，0为订单预借，2为集中开票 )',
  `invoiceType` int(4) DEFAULT 1 COMMENT '1普通发票 2增值税发票',
  `selectedInvoiceTitle` int(4) DEFAULT 4 COMMENT '发票类型：4个人，5单位',

  `companyName` varchar(250) NOT NULL DEFAULT '' COMMENT '发票抬头  (如果selectedInvoiceTitle=5则此字段必须)',
  `taxpayersIDcode` varchar(250) NOT NULL DEFAULT '' COMMENT '纳税人识别码 备注 开企业抬头发票，请填写 纳税人识别号 ,以免影响您报销',

  `invoiceContent` int(4) NOT NULL DEFAULT 1 COMMENT '1:明细，3：电脑配件，19:耗材，22：办公用品 备注:若增值发票则只能选1 明细',

  `invoiceName` varchar(64) NOT NULL DEFAULT '' COMMENT '增值票收票人姓名 备注：当invoiceType=2 且invoiceState=1时则此字段必填',
  `invoicePhone` varchar(64) NOT NULL DEFAULT '' COMMENT '增值票收票人电话 备注：当invoiceType=2 且invoiceState=1时则此字段必填',

  `invoiceProvice` int(11) NOT NULL DEFAULT 0 COMMENT '增值票收票人所在省(京东地址编码) 备注：当invoiceType=2 且invoiceState=1时则此字段必填',
  `invoiceCity` int(11) NOT NULL DEFAULT 0 COMMENT '增值票收票人所在市(京东地址编码) 备注：当invoiceType=2 且invoiceState=1时则此字段必填',
  `invoiceCounty` int(11) NOT NULL DEFAULT 0 COMMENT '增值票收票人所在区/县(京东地址编码) 备注：当invoiceType=2 且invoiceState=1时则此字段必填',


  `invoiceAddress` varchar(512) NOT NULL DEFAULT '' COMMENT '增值票收票人所在地址 备注：当invoiceType=2 且invoiceState=1时则此字段必填',

  `invoiceBank` VARCHAR(512) NOT NULL DEFAULT '' COMMENT '增值票开户银行' ,
  `invoiceAccount` VARCHAR(512) NOT NULL DEFAULT '' COMMENT '增值票开户帐号' ,

  `isdefault` tinyint(1) DEFAULT '0',
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



ALTER TABLE `ims_superdesk_shop_member_invoice` ADD PRIMARY KEY(`id`);
ALTER TABLE `ims_superdesk_shop_member_invoice` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `ims_superdesk_shop_member_invoice`
ADD `taxpayersIDcode` VARCHAR(250) NOT NULL DEFAULT '' COMMENT '纳税人识别码 备注 开企业抬头发票，请填写 纳税人识别号 ,以免影响您报销' AFTER `companyName`;