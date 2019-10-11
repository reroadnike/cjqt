



http://192.168.1.124/superdesk/web/superdesk_shopv2_merchant.php?c=site&a=entry&m=superdesk_shopv2&do=web&r=goods.edit&id=144436&goodsfrom=sale



144436

p 1320
c 1583
t 1590

ps 
cs 
ts 1590

cates 1590

两个分类

p 1320
c 1583
t 1590

ps 
cs 
ts 1590,5022

cates 1590,5022


ALTER TABLE `db_super_desk`.`ims_superdesk_shop_goods` 
CHANGE COLUMN `cates` `cates` TEXT NULL DEFAULT NULL COMMENT '多重分类数据集' AFTER `tcate`,
CHANGE COLUMN `updatetime` `updatetime` INT(11) NULL DEFAULT '0' COMMENT '更新时间' AFTER `createtime`,
CHANGE COLUMN `deleted` `deleted` TINYINT(3) NULL DEFAULT '0' COMMENT '是否删除' AFTER `updatetime`;


ALTER TABLE `ims_superdesk_shop_goods` CHANGE `pcates` `pcates` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '一级多重分类';
ALTER TABLE `ims_superdesk_shop_goods` CHANGE `ccates` `ccates` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '二级多重分类';