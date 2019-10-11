



ALTER TABLE `ims_superdesk_jd_vop_order_submit_order_sku` CHANGE `skuId` `skuId` BIGINT(20) NOT NULL COMMENT 'skuId';
ALTER TABLE `ims_superdesk_jd_vop_order_submit_order_sku` CHANGE `oid` `oid` BIGINT(20) NOT NULL COMMENT 'oid为主商品skuid，如果本身是主商品，则oid为0';
ALTER TABLE `ims_superdesk_jd_vop_product_detail` CHANGE `sku` `sku` BIGINT(20) NOT NULL COMMENT '单品';
ALTER TABLE `ims_superdesk_jd_vop_product_price` CHANGE `skuId` `skuId` BIGINT(20) NOT NULL COMMENT 'skuId';
ALTER TABLE `ims_superdesk_shop_goods` CHANGE `jd_vop_sku` `jd_vop_sku` BIGINT(20) NOT NULL DEFAULT '0';


-- 福利已更新
-- 企业已更新

-- 福利测试更新 表已经好了
-- 企业测试更新 表已经好了


ims_superdesk_jd_vop_order_submit_order_sku // 已经是 skuId bigint(20)

ims_superdesk_jd_vop_order_submit_order_sku // 已经是 oid bigint(20)

ims_superdesk_jd_vop_product_detail // 已经是 sku bigint(20)

ims_superdesk_jd_vop_product_exts // 已经是 sku bigint(20)

ims_superdesk_jd_vop_product_price // 已经是 skuId bigint(20)



ims_superdesk_shop_goods // 已经是 jd_vop_sku bigint(20)

ims_superdesk_shop_goods_exts // 已经是 sku bigint(20)



/data/wwwroot/default/superdesk/addons/superdesk_jd_vop/service/MessageService.class.php ->

messageType_2_Get // 已
messageType_4_Get // 已
messageType_6_Get // 已
messageType_16_Get // 已


/data/wwwroot/default/superdesk/addons/superdesk_jd_vop/service/ProductService.class.php ->

runQueryGetSkuByPageForTask
businessProcessingGetSkuByPageForManual