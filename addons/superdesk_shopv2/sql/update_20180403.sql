## 已更新到服务器

ALTER TABLE `ims_superdesk_shop_merch_user`
ADD `is_default_see` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '是否默认对所有商户和客户可见(0:否,1:是)' AFTER `isrecommand`;