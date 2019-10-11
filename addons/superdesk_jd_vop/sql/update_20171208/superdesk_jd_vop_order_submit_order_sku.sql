

--
-- 表的结构 `ims_superdesk_jd_vop_order_submit_order_sku`
--

CREATE TABLE IF NOT EXISTS `ims_superdesk_jd_vop_order_submit_order_sku` (
  `id` int(11) NOT NULL,
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
  `oid` int(11) NOT NULL COMMENT 'oid为主商品skuid，如果本身是主商品，则oid为0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ims_superdesk_jd_vop_order_submit_order_sku`
--
ALTER TABLE `ims_superdesk_jd_vop_order_submit_order_sku`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ims_superdesk_jd_vop_order_submit_order_sku`
--
ALTER TABLE `ims_superdesk_jd_vop_order_submit_order_sku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
