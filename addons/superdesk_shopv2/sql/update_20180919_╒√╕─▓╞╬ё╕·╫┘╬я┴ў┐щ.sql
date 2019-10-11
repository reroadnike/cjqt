


# 已更新到 福利内购
# 已更新到 企业采购
-- 财务跟踪表 增加物流express字段
ALTER TABLE `ims_superdesk_shop_order_finance`
ADD `express` varchar(50) DEFAULT '' COMMENT '快递' AFTER `expressid`;


# 已更新到 福利内购
# 已更新到 企业采购
-- 财务跟踪表 根据expressid插入express
update ims_superdesk_shop_order_finance as ofi
LEFT JOIN ims_superdesk_shop_express as e on ofi.expressid = e.id set ofi.express = e.express where ofi.expressid != 0