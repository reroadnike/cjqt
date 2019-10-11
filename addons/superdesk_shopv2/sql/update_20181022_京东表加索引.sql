
-- 已更新到企业正式

ALTER TABLE `ims_superdesk_jd_vop_order_submit_order_sku`
ADD INDEX `index_order_id` (`shop_order_id`, `shop_goods_id`) ;

