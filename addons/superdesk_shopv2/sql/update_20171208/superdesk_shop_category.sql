------------------------------------------ ims_superdesk_shop_category ------------------------------------------


ALTER TABLE `ims_superdesk_shop_category`
ADD `jd_vop_page_num` INT(11) NOT NULL DEFAULT '0' COMMENT 'jd_vop_page_num' AFTER `level`;

ALTER TABLE `ims_superdesk_shop_category`
CHANGE COLUMN `isrecommand` `isrecommand` INT(10) NULL DEFAULT '0' COMMENT '是否推荐' ,
CHANGE COLUMN `ishome` `ishome` TINYINT(3) NULL DEFAULT '0' COMMENT '是否首页显示' ,
CHANGE COLUMN `advimg` `advimg` VARCHAR(255) NULL DEFAULT '' COMMENT '广告图片' ,
CHANGE COLUMN `advurl` `advurl` VARCHAR(500) NULL DEFAULT '' COMMENT '广告链接' ,
CHANGE COLUMN `level` `level` TINYINT(3) NULL DEFAULT NULL COMMENT '分类是在几级' ;


------------------------------------------ ims_superdesk_shop_category ------------------------------------------