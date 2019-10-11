

## 已更新到服务器

ALTER TABLE `ims_superdesk_shop_member_invoice`
ADD `invoiceBank` VARCHAR(512) NOT NULL DEFAULT '' COMMENT '增值票开户银行' AFTER `invoiceAddress`,
ADD `invoiceAccount` VARCHAR(512) NOT NULL DEFAULT '' COMMENT '增值票开户帐号' AFTER `invoiceBank`;


ALTER TABLE `ims_superdesk_shop_member_invoice`
ADD `createtime` INT(11) NOT NULL DEFAULT '0' COMMENT 'createtime' AFTER `deleted`,
ADD `updatetime` INT(11) NOT NULL DEFAULT '0' COMMENT 'updatetime' AFTER `createtime`;


ALTER TABLE `ims_superdesk_shop_member_address`
ADD `createtime` INT(11) NOT NULL DEFAULT '0' COMMENT 'createtime' AFTER `deleted`,
ADD `updatetime` INT(11) NOT NULL DEFAULT '0' COMMENT 'updatetime' AFTER `createtime`;

ALTER TABLE `ims_superdesk_shop_member_address` CHANGE `mobile` `mobile` VARCHAR(18) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '+86 134 1111 1111';





ALTER TABLE `ims_superdesk_shop_member`
ADD `core_enterprise` INT NOT NULL DEFAULT '0' COMMENT '超级前台_企业ID' AFTER `openid_wx`;

ALTER TABLE `ims_superdesk_shop_member`
ADD `updatetime` INT(11) NOT NULL DEFAULT '0' COMMENT 'updatetime' AFTER `openid_wx`;

