
## 已更新到服务器

MyISAM

ims_superdesk_shop_goods

ALTER TABLE `ims_superdesk_shop_goods` DROP INDEX `merchid`;

ALTER TABLE `ims_superdesk_shop_goods` DROP INDEX `idx_scate`;

ALTER TABLE `ims_superdesk_shop_goods` ADD INDEX `idx_status` (`status`) USING BTREE;

ALTER TABLE `ims_superdesk_shop_goods` CHANGE `type` `type` TINYINT(2) NULL DEFAULT '1' COMMENT '类型 1 实体物品 2 虚拟物品 3 虚拟物品(卡密) 4 批发 10 话费流量充值 20 充值卡';


