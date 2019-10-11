## 已更新到服务器

-- N次拆单

ALTER TABLE `ims_superdesk_jd_vop_order_submit_order_sku`
ADD `shop_order_id` INT(11) NOT NULL DEFAULT '0' COMMENT '商城订单ID' AFTER `oid`,
ADD `shop_order_sn` VARCHAR(64) NOT NULL DEFAULT '' COMMENT 'shop_order_sn' AFTER `shop_order_id`,
ADD `shop_goods_id` INT(11) NOT NULL DEFAULT '0' COMMENT '商城商品ID' AFTER `shop_order_sn`;

ALTER TABLE `ims_superdesk_shop_order_goods`
ADD `parent_order_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'parent_order_id' AFTER `uniacid`;

update `ims_superdesk_shop_order`  set parentid = 756 where id = 838;