CREATE TABLE IF NOT EXISTS `ims_superdesk_jd_vop_product_detail` (
  `sku` int(11) NOT NULL,
  `name` varchar(512) NOT NULL,
  `category` varchar(32) NOT NULL,
  `upc` varchar(128) NOT NULL,
  `saleUnit` varchar(16) NOT NULL,
  `weight` decimal(10,2) NOT NULL,
  `productArea` varchar(256) NOT NULL,
  `wareQD` varchar(256) NOT NULL,
  `imagePath` varchar(512) NOT NULL,
  `param` text NOT NULL,
  `brandName` varchar(256) NOT NULL,
  `state` tinyint(3) NOT NULL,
  `shouhou` text NOT NULL,
  `introduction` text NOT NULL,
  `appintroduce` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



ALTER TABLE `ims_superdesk_jd_vop_product_detail` ADD PRIMARY KEY(`sku`);
ALTER TABLE `ims_superdesk_jd_vop_product_detail` CHANGE `shouhou` `shouhou` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '';


ALTER TABLE `ims_superdesk_jd_vop_product_detail` CHANGE `introduction` `introduction` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '';
ALTER TABLE `ims_superdesk_jd_vop_product_detail` CHANGE `productArea` `productArea` VARCHAR(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '';
ALTER TABLE `ims_superdesk_jd_vop_product_detail` CHANGE `saleUnit` `saleUnit` VARCHAR(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '';
ALTER TABLE `ims_superdesk_jd_vop_product_detail` CHANGE `imagePath` `imagePath` VARCHAR(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '';
ALTER TABLE `ims_superdesk_jd_vop_product_detail` CHANGE `param` `param` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '';
ALTER TABLE `ims_superdesk_jd_vop_product_detail` CHANGE `brandName` `brandName` VARCHAR(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '';
ALTER TABLE `ims_superdesk_jd_vop_product_detail` CHANGE `appintroduce` `appintroduce` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '';


ALTER TABLE `ims_superdesk_jd_vop_product_detail`
CHANGE COLUMN `sku` `sku` INT(11) NOT NULL COMMENT '单品' ,
CHANGE COLUMN `name` `name` VARCHAR(512) NOT NULL COMMENT '商品名称' ,
CHANGE COLUMN `category` `category` VARCHAR(32) NOT NULL COMMENT '类别' ,
CHANGE COLUMN `upc` `upc` VARCHAR(128) NOT NULL COMMENT '条形码' ,
CHANGE COLUMN `saleUnit` `saleUnit` VARCHAR(16) NOT NULL DEFAULT '' COMMENT '销售单位' ,
CHANGE COLUMN `weight` `weight` DECIMAL(10,2) NOT NULL COMMENT '重量' ,
CHANGE COLUMN `productArea` `productArea` VARCHAR(256) NOT NULL DEFAULT '' COMMENT '产地' ,
CHANGE COLUMN `wareQD` `wareQD` VARCHAR(256) NOT NULL COMMENT '商品清单' ,
CHANGE COLUMN `imagePath` `imagePath` VARCHAR(512) NOT NULL DEFAULT '' COMMENT '主图地址' ,
CHANGE COLUMN `brandName` `brandName` VARCHAR(256) NOT NULL DEFAULT '' COMMENT '品牌' ,
CHANGE COLUMN `state` `state` TINYINT(3) NOT NULL COMMENT '上下架状态' ,
CHANGE COLUMN `shouhou` `shouhou` TEXT NOT NULL COMMENT '售后' ,
CHANGE COLUMN `introduction` `introduction` TEXT NOT NULL COMMENT 'web介绍' ,
CHANGE COLUMN `appintroduce` `appintroduce` TEXT NOT NULL COMMENT 'app介绍' ;

ALTER TABLE `ims_superdesk_jd_vop_product_detail`
ADD `createtime` INT(11) NOT NULL DEFAULT '0' COMMENT 'createtime' AFTER `appintroduce`,
ADD `updatetime` INT(11) NOT NULL DEFAULT '0' COMMENT 'updatetime' AFTER `createtime`,
ADD `page_num` VARCHAR(16) NOT NULL DEFAULT '' COMMENT 'page_num' AFTER `name`;