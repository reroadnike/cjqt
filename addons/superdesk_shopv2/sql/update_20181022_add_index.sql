


ALTER TABLE `ims_superdesk_jd_vop_order_submit_order_sku`
ADD INDEX `idx_union_export` (`uniacid`, `status`, `deleted`, `checked`, `shop_order_id`,`shop_goods_id`) ;