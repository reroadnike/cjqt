-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2018-08-07 10:43:25
-- 服务器版本： 5.6.33-log
-- PHP Version: 7.1.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_super_desk`
--

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_jd_vop_area`
--

CREATE TABLE `ims_superdesk_jd_vop_area` (
  `code` int(11) NOT NULL COMMENT 'code',
  `parent_code` int(11) NOT NULL COMMENT 'parent_code',
  `level` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'level',
  `text` varchar(128) NOT NULL DEFAULT '' COMMENT 'text',
  `state` tinyint(3) NOT NULL DEFAULT '1' COMMENT 'state',
  `remark` varchar(128) NOT NULL DEFAULT '' COMMENT '标注',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT 'createtime',
  `updatetime` int(11) NOT NULL COMMENT 'updatetime'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_jd_vop_balance_detail`
--

CREATE TABLE `ims_superdesk_jd_vop_balance_detail` (
  `id` int(11) NOT NULL COMMENT '余额明细 ID',
  `accountType` tinyint(4) NOT NULL DEFAULT '0' COMMENT '账户类型',
  `amount` decimal(10,2) NOT NULL COMMENT '金额(元)',
  `pin` varchar(256) NOT NULL DEFAULT '' COMMENT '京东 Pin',
  `orderId` bigint(13) NOT NULL DEFAULT '0' COMMENT '订单号',
  `tradeType` int(11) NOT NULL DEFAULT '0' COMMENT '业务类型',
  `tradeTypeName` varchar(1000) NOT NULL COMMENT '业务类型名称',
  `createdDate` datetime NOT NULL COMMENT '余额变动日期',
  `notePub` text NOT NULL COMMENT '备注信息',
  `tradeNo` bigint(13) NOT NULL DEFAULT '0' COMMENT '业务号',
  `processing` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0未处理,1已处理',
  `process_result` varchar(1000) NOT NULL DEFAULT '' COMMENT '处理结果'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_jd_vop_logs`
--

CREATE TABLE `ims_superdesk_jd_vop_logs` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT 'createtime',
  `url` varchar(500) NOT NULL COMMENT 'url',
  `api` varchar(500) NOT NULL COMMENT 'api',
  `method` varchar(16) NOT NULL COMMENT 'method',
  `post_fields` text NOT NULL COMMENT 'post_fields',
  `curl_info` text NOT NULL COMMENT 'curl_info',
  `success` int(1) NOT NULL COMMENT 'success',
  `resultMessage` varchar(500) NOT NULL COMMENT 'url',
  `resultCode` varchar(16) NOT NULL COMMENT 'resultCode',
  `result` text NOT NULL COMMENT 'result'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_jd_vop_order_submit_order`
--

CREATE TABLE `ims_superdesk_jd_vop_order_submit_order` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `isparent` tinyint(3) NOT NULL DEFAULT '0' COMMENT '1为父',
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
  `jd_vop_result_orderTaxPrice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单税额',
  `checking_by_third` int(8) NOT NULL DEFAULT '0' COMMENT '订单反查标记',
  `jd_vop_recheck_orderState` int(11) NOT NULL DEFAULT '0' COMMENT '反查之后更新orderState',
  `jd_vop_recheck_submitState` int(11) NOT NULL DEFAULT '0' COMMENT '反查之后更新submitState',
  `jd_vop_recheck_pOrder` varchar(32) NOT NULL DEFAULT '' COMMENT '用于拆单'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_jd_vop_order_submit_order_sku`
--

CREATE TABLE `ims_superdesk_jd_vop_order_submit_order_sku` (
  `id` int(11) NOT NULL,
  `pOrder` varchar(32) NOT NULL DEFAULT '' COMMENT '主订单号',
  `jdOrderId` varchar(32) NOT NULL COMMENT 'jdOrderId',
  `skuId` int(11) NOT NULL COMMENT 'skuId',
  `num` int(11) NOT NULL COMMENT '数量',
  `category` int(11) NOT NULL COMMENT '分类',
  `price` decimal(10,2) NOT NULL COMMENT '售价',
  `name` varchar(512) NOT NULL,
  `tax` int(11) NOT NULL,
  `taxPrice` decimal(10,2) NOT NULL COMMENT '税额',
  `nakedPrice` decimal(10,2) NOT NULL COMMENT '裸价',
  `type` int(11) NOT NULL COMMENT 'type为 0普通、1附件、2赠品',
  `oid` int(11) NOT NULL COMMENT 'oid为主商品skuid，如果本身是主商品，则oid为0',
  `shop_order_id` int(11) NOT NULL DEFAULT '0' COMMENT '商城订单ID',
  `shop_order_sn` varchar(64) NOT NULL DEFAULT '' COMMENT 'shop_order_sn',
  `shop_goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商城商品ID',
  `return_goods_nun` int(11) NOT NULL DEFAULT '0' COMMENT '京东退货数量',
  `return_goods_result` text NOT NULL COMMENT '京东退货信息'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_jd_vop_page_num`
--

CREATE TABLE `ims_superdesk_jd_vop_page_num` (
  `id` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL COMMENT 'uniacid',
  `page_num` int(11) NOT NULL COMMENT 'page_num',
  `name` varchar(128) NOT NULL DEFAULT '' COMMENT '商品池名字',
  `state` tinyint(3) NOT NULL DEFAULT '1' COMMENT 'state',
  `deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT '删除标记',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT 'createtime',
  `updatetime` int(11) NOT NULL COMMENT 'updatetime'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_jd_vop_product_detail`
--

CREATE TABLE `ims_superdesk_jd_vop_product_detail` (
  `sku` int(11) NOT NULL COMMENT '单品',
  `name` varchar(512) NOT NULL COMMENT '商品名称',
  `page_num` varchar(16) NOT NULL DEFAULT '' COMMENT 'page_num',
  `category` varchar(32) NOT NULL COMMENT '类别',
  `upc` varchar(128) NOT NULL COMMENT '条形码',
  `saleUnit` varchar(16) NOT NULL DEFAULT '' COMMENT '销售单位',
  `weight` decimal(10,2) NOT NULL COMMENT '重量',
  `productArea` varchar(256) NOT NULL DEFAULT '' COMMENT '产地',
  `wareQD` varchar(256) NOT NULL COMMENT '商品清单',
  `imagePath` varchar(512) NOT NULL DEFAULT '' COMMENT '主图地址',
  `param` text NOT NULL,
  `brandName` varchar(256) NOT NULL DEFAULT '' COMMENT '品牌',
  `state` tinyint(3) NOT NULL COMMENT '上下架状态',
  `shouhou` text NOT NULL COMMENT '售后',
  `introduction` text NOT NULL COMMENT 'web介绍',
  `appintroduce` text NOT NULL COMMENT 'app介绍',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT 'createtime',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT 'updatetime'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_jd_vop_product_price`
--

CREATE TABLE `ims_superdesk_jd_vop_product_price` (
  `skuId` int(11) NOT NULL COMMENT 'skuId',
  `productprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '京东价格',
  `marketprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '客户购买价格',
  `costprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '协议价格',
  `createtime` int(11) NOT NULL COMMENT 'createtime',
  `updatetime` int(11) NOT NULL COMMENT 'updatetime'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_jd_vop_search`
--

CREATE TABLE `ims_superdesk_jd_vop_search` (
  `id` int(11) NOT NULL,
  `openid` varchar(50) DEFAULT NULL,
  `has_keyword` tinyint(2) DEFAULT '0' COMMENT '是否有关键字(0否1是)',
  `keyword` varchar(512) DEFAULT '' COMMENT '搜索关键字',
  `has_pricebetween` tinyint(2) DEFAULT '0' COMMENT '是否有价格区间(0否1是)',
  `minprice` decimal(10,2) DEFAULT '0.00' COMMENT '最低价格',
  `maxprice` decimal(10,2) DEFAULT '0.00' COMMENT '最高价格',
  `has_filter` tinyint(2) DEFAULT '0' COMMENT '是否有筛选(0否1是)',
  `filters` varchar(255) DEFAULT '' COMMENT '筛选(以逗号分隔)',
  `has_cate` tinyint(2) DEFAULT '0' COMMENT '是否有选择分类(0否1是)',
  `cate` int(11) DEFAULT '0' COMMENT '分类id',
  `has_order` tinyint(2) DEFAULT '0' COMMENT '是否有排序(0否1是)',
  `order_by` varchar(64) DEFAULT '' COMMENT '排序',
  `createtime` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ims_superdesk_jd_vop_task_cron`
--

CREATE TABLE `ims_superdesk_jd_vop_task_cron` (
  `name` varchar(128) NOT NULL,
  `orde` int(10) NOT NULL DEFAULT '0',
  `file` varchar(100) NOT NULL DEFAULT '',
  `no` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `desc` text,
  `freq` int(10) NOT NULL DEFAULT '0',
  `lastdo` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `log` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ims_superdesk_jd_vop_area`
--
ALTER TABLE `ims_superdesk_jd_vop_area`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `ims_superdesk_jd_vop_balance_detail`
--
ALTER TABLE `ims_superdesk_jd_vop_balance_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_jd_vop_logs`
--
ALTER TABLE `ims_superdesk_jd_vop_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `success` (`success`),
  ADD KEY `api` (`api`(255)),
  ADD KEY `idx_union_search_0` (`api`(255),`success`),
  ADD KEY `idx_union_search_1` (`api`(255),`success`,`createtime`),
  ADD KEY `idx_union_search_2` (`api`(255),`success`,`resultCode`,`createtime`);

--
-- Indexes for table `ims_superdesk_jd_vop_order_submit_order`
--
ALTER TABLE `ims_superdesk_jd_vop_order_submit_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`) USING BTREE,
  ADD KEY `thirdOrder` (`thirdOrder`) USING BTREE,
  ADD KEY `checking_by_third` (`checking_by_third`) USING BTREE,
  ADD KEY `jd_vop_result_jdOrderId_2` (`jd_vop_result_jdOrderId`) USING BTREE;

--
-- Indexes for table `ims_superdesk_jd_vop_order_submit_order_sku`
--
ALTER TABLE `ims_superdesk_jd_vop_order_submit_order_sku`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_jd_vop_page_num`
--
ALTER TABLE `ims_superdesk_jd_vop_page_num`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_jd_vop_product_detail`
--
ALTER TABLE `ims_superdesk_jd_vop_product_detail`
  ADD PRIMARY KEY (`sku`),
  ADD KEY `idx_sku` (`sku`);

--
-- Indexes for table `ims_superdesk_jd_vop_product_price`
--
ALTER TABLE `ims_superdesk_jd_vop_product_price`
  ADD PRIMARY KEY (`skuId`),
  ADD KEY `idx_sku` (`skuId`);

--
-- Indexes for table `ims_superdesk_jd_vop_search`
--
ALTER TABLE `ims_superdesk_jd_vop_search`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ims_superdesk_jd_vop_task_cron`
--
ALTER TABLE `ims_superdesk_jd_vop_task_cron`
  ADD PRIMARY KEY (`name`),
  ADD UNIQUE KEY `name` (`name`) USING BTREE;

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `ims_superdesk_jd_vop_logs`
--
ALTER TABLE `ims_superdesk_jd_vop_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=9642440;

--
-- 使用表AUTO_INCREMENT `ims_superdesk_jd_vop_order_submit_order`
--
ALTER TABLE `ims_superdesk_jd_vop_order_submit_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=2026;

--
-- 使用表AUTO_INCREMENT `ims_superdesk_jd_vop_order_submit_order_sku`
--
ALTER TABLE `ims_superdesk_jd_vop_order_submit_order_sku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4647;

--
-- 使用表AUTO_INCREMENT `ims_superdesk_jd_vop_page_num`
--
ALTER TABLE `ims_superdesk_jd_vop_page_num`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2653;

--
-- 使用表AUTO_INCREMENT `ims_superdesk_jd_vop_search`
--
ALTER TABLE `ims_superdesk_jd_vop_search`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37307;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
