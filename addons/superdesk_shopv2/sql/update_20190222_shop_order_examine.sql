

-- 已更新到企业正式

ALTER TABLE `ims_superdesk_shop_order_examine` ADD `manager_core_user` INT(10) NOT NULL DEFAULT '0' COMMENT '审核人core_user' AFTER `manager_openid`;