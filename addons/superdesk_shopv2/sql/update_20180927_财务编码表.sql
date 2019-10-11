

# 已更新到 企业采购
CREATE TABLE `ims_superdesk_shop_goods_exts` (
  `sku` bigint(20) NOT NULL COMMENT 'skuId',
  `category` varchar(32) NOT NULL COMMENT 'sku所属分类',
  `taxCode` varchar(32) NOT NULL COMMENT '税务编码',
  `isFactoryShip` int(11) NOT NULL COMMENT '是否厂商直送',
  `isEnergySaving` int(11) NOT NULL COMMENT '是否政府节能',
  `contractSkuExt` varchar(64) NOT NULL COMMENT '定制商品池开关',
  `ChinaCatalog` varchar(64) NOT NULL COMMENT '中图法分类号',
  `createtime` int(11) NOT NULL COMMENT 'createtime',
  `updatetime` int(11) NOT NULL COMMENT 'updatetime',
  PRIMARY KEY (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

