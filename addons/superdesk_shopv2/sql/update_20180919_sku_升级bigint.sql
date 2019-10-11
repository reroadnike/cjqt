





ALTER TABLE `ims_superdesk_jd_vop_product_detail` CHANGE `sku` `sku` BIGINT(20) NOT NULL COMMENT '单品';
ALTER TABLE `ims_superdesk_shop_goods` CHANGE `jd_vop_sku` `jd_vop_sku` BIGINT(20) NOT NULL DEFAULT '0';



# 弃

ALTER TABLE `ims_superdesk_jd_vop_product_detail` ADD `taxCode` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '税务编码' AFTER `appintroduce`;
ALTER TABLE `ims_superdesk_shop_goods` ADD `jd_vop_tax_code` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '税务编码' AFTER `jd_vop_sku`;