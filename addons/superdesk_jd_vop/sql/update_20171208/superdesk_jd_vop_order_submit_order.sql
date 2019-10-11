--
-- 表的结构 `ims_superdesk_jd_vop_order_submit_order`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_jd_vop_order_submit_order` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `order_id` int(11) NOT NULL DEFAULT '0',
  `thirdOrder` varchar(64) NOT NULL DEFAULT '' COMMENT '第三方的订单单号',
  `sku` text NOT NULL COMMENT '[{"skuId":商品编号, "num":商品数量,"bNeedAnnex":true, "bNeedGift":true, "price":100, "yanbao":[{"skuId":商品编号}]}] (最高支持50种商品)',
  `name` varchar(128) NOT NULL COMMENT '收货人',
  `province` int(11) NOT NULL COMMENT '一级地址',
  `city` int(11) NOT NULL COMMENT '二级地址',
  `county` int(11) NOT NULL COMMENT '三级地址',
  `town` int(11) NOT NULL COMMENT '四级地址 (如果该地区有四级地址，则必须传递四级地址，没有四级地址则传0)',
  `address` varchar(512) NOT NULL COMMENT '详细地址',
  `zip` varchar(16) DEFAULT NULL COMMENT '邮编',
  `phone` varchar(16) DEFAULT NULL COMMENT '座机号',
  `mobile` varchar(16) NOT NULL COMMENT '手机号',
  `email` varchar(32) NOT NULL COMMENT '邮箱',
  `remark` varchar(200) NOT NULL COMMENT '备注（少于100字）',
  `invoiceState` int(11) NOT NULL COMMENT '开票方式(1为随货开票，0为订单预借，2为集中开票 )',
  `invoiceType` int(11) NOT NULL COMMENT '1普通发票2增值税发票',
  `selectedInvoiceTitle` int(11) NOT NULL COMMENT '发票类型：4个人，5单位',
  `companyName` varchar(256) NOT NULL COMMENT '发票抬头 (如果selectedInvoiceTitle=5则此字段必须)',
  `invoiceContent` int(11) NOT NULL COMMENT '1:明细，3：电脑配件，19:耗材，22：办公用品 备注:若增值发票则只能选1 明细',
  `paymentType` int(11) NOT NULL COMMENT '支付方式 (1：货到付款，2：邮局付款，4：在线支付，5：公司转账，6：银行转账，7：网银钱包，101：金采支付)',
  `isUseBalance` int(11) NOT NULL COMMENT '使用余额paymentType=4时，此值固定是1 其他支付方式0',
  `submitState` int(11) NOT NULL COMMENT '是否预占库存，0是预占库存（需要调用确认订单接口），1是不预占库存 金融支付必须预占库存传0',
  `invoiceName` int(11) DEFAULT NULL COMMENT '增值票收票人姓名 备注：当invoiceType=2 且invoiceState=1时则此字段必填',
  `invoicePhone` int(11) DEFAULT NULL COMMENT '增值票收票人电话 备注：当invoiceType=2 且invoiceState=1时则此字段必填',
  `invoiceProvice` int(11) DEFAULT NULL COMMENT '增值票收票人所在省(京东地址编码) 备注：当invoiceType=2 且invoiceState=1时则此字段必填',
  `invoiceCity` int(11) DEFAULT NULL COMMENT '增值票收票人所在市(京东地址编码) 备注：当invoiceType=2 且invoiceState=1时则此字段必填',
  `invoiceCounty` int(11) DEFAULT NULL COMMENT '增值票收票人所在区/县(京东地址编码) 备注：当invoiceType=2 且invoiceState=1时则此字段必填',
  `invoiceAddress` int(11) DEFAULT NULL COMMENT '增值票收票人所在地址 备注：当invoiceType=2 且invoiceState=1时则此字段必填',
  `doOrderPriceMode` int(11) NOT NULL COMMENT '"0: 客户端订单价格快照不做验证对比，还是以京东价格正常下单; 1:必需验证客户端订单价格快照，如果快照与京东价格不一致返回下单失败，需要更新商品价格后，重新下单;"',
  `orderPriceSnap` text NOT NULL COMMENT '客户端订单价格快照	 "Json格式的数据，格式为:[{""price"":21.30,"skuId":123123 },{ "price":99.55, "skuId":22222 }] //商品价格 ,类型：BigDecimal" //商品编号,类型：long',
  `reservingDate` int(11) DEFAULT NULL COMMENT '大家电配送日期	 | 	默认值为-1，0表示当天，1表示明天，2：表示后天; 如果为-1表示不使用大家电预约日历',
  `installDate` int(11) DEFAULT NULL COMMENT '大家电安装日期	 | 	不支持默认按-1处理，0表示当天，1表示明天，2：表示后天',
  `needInstall` int(11) NOT NULL COMMENT '大家电是否选择了安装	 | 	是否选择了安装，默认为true，选择了“暂缓安装”，此为必填项，必填值为false。',
  `promiseDate` varchar(64) NOT NULL COMMENT '中小件配送预约日期	 | 	格式：yyyy-MM-dd',
  `promiseTimeRange` varchar(64) NOT NULL COMMENT '中小件配送预约时间段	 | 	时间段如： 9:00-15:00',
  `promiseTimeRangeCode` int(11) NOT NULL COMMENT '中小件预约时间段的标记',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT 'createtime',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT 'updatetime',
  `response` text NOT NULL COMMENT 'api response',
  `jd_vop_success` tinyint(1) NOT NULL,
  `jd_vop_resultMessage` varchar(128) NOT NULL DEFAULT '',
  `jd_vop_resultCode` varchar(8) NOT NULL DEFAULT '',
  `jd_vop_code` int(11) NOT NULL,
  `jd_vop_result_jdOrderId` varchar(32) NOT NULL COMMENT '京东订单编号',
  `jd_vop_result_freight` int(11) NOT NULL DEFAULT '0' COMMENT '运费(合同配置了才返回此字段)',
  `jd_vop_result_orderPrice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品总价格',
  `jd_vop_result_orderNakedPrice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单裸价',
  `jd_vop_result_orderTaxPrice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单税额'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ims_superdesk_jd_vop_order_submit_order`
--
ALTER TABLE `ims_superdesk_jd_vop_order_submit_order`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ims_superdesk_jd_vop_order_submit_order`
--
ALTER TABLE `ims_superdesk_jd_vop_order_submit_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID';
