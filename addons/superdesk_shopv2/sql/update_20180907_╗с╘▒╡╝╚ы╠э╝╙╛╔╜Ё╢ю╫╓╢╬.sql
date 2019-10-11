





# 已更新到 福利内购
# 已更新到 企业采购

ALTER TABLE `ims_superdesk_shop_enterprise_import_log`
ADD `old_price` decimal(10,2) DEFAULT '0' COMMENT '原金额' AFTER `account_id`;


