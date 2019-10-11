



# 已更新到 福利内购
# 已更新到 企业采购
--  订单表添加固定的项目名称
ALTER TABLE `ims_superdesk_shop_order`
ADD `member_enterprise_name` varchar(30) DEFAULT '' COMMENT '下单时的项目名称' AFTER `source_from`;



# 已更新到 福利内购
# 已更新到 企业采购
--  订单表添加下单时的企业id,下单时的项目id
ALTER TABLE `ims_superdesk_shop_order`
ADD `member_enterprise_id` int(11) DEFAULT 0 COMMENT '下单时的企业id' AFTER `member_enterprise_name`;

ALTER TABLE `ims_superdesk_shop_order`
ADD `member_organization_id` int(11) DEFAULT 0 COMMENT '下单时的项目id' AFTER `member_enterprise_id`;