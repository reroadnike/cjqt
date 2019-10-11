## 已更新到服务器

SHOW PROCESSLIST
-- copy to tmp table 大问题
ALTER TABLE `db_super_desk`.`ims_superdesk_shop_goods`
CHANGE COLUMN `cates` `cates` TEXT NULL DEFAULT NULL COMMENT '多重分类数据集' AFTER `tcate`,
CHANGE COLUMN `updatetime` `updatetime` INT(11) NULL DEFAULT '0' COMMENT '更新时间' AFTER `createtime`,
CHANGE COLUMN `deleted` `deleted` TINYINT(3) NULL DEFAULT '0' COMMENT '是否删除' AFTER `updatetime`;


ALTER TABLE `ims_superdesk_shop_goods` CHANGE `pcates` `pcates` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '一级多重分类';
ALTER TABLE `ims_superdesk_shop_goods` CHANGE `ccates` `ccates` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '二级多重分类';