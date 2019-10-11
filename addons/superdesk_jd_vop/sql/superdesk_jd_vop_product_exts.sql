


# 已更新到 福利内购
# 已更新到 企业采购

CREATE TABLE `ims_superdesk_jd_vop_product_exts` (
`sku` BIGINT(20) NOT NULL COMMENT 'skuId' ,
`category` VARCHAR(32) NOT NULL COMMENT 'sku所属分类',
`taxCode` VARCHAR(32) NOT NULL COMMENT '税务编码' ,
`isFactoryShip` INT(11) NOT NULL COMMENT '是否厂商直送' ,
`isEnergySaving` INT(11) NOT NULL COMMENT '是否政府节能' ,
`contractSkuExt` VARCHAR(64) NOT NULL COMMENT '定制商品池开关' ,
`ChinaCatalog` VARCHAR(64) NOT NULL COMMENT '中图法分类号',
`createtime` INT(11) NOT NULL COMMENT 'createtime' ,
`updatetime` INT(11) NOT NULL COMMENT 'updatetime'
) ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;

ALTER TABLE `ims_superdesk_jd_vop_product_exts` ADD PRIMARY KEY(`sku`);



ALTER TABLE `ims_superdesk_jd_vop_product_exts` ADD `category` VARCHAR(32) NOT NULL COMMENT 'sku所属分类' AFTER `updatetime`;