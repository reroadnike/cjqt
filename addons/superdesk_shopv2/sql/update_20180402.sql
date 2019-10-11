## 已更新到服务器

ALTER TABLE `ims_superdesk_shop_order_goods`
ADD `return_goods_nun` INT(11) NOT NULL DEFAULT '0' COMMENT '京东退货数量' AFTER `canbuyagain`,
ADD `return_goods_result` TEXT NOT NULL COMMENT '京东退货信息' AFTER `return_goods_nun`;



ALTER TABLE `ims_superdesk_jd_vop_order_submit_order_sku`
ADD `return_goods_nun` INT(11) NOT NULL DEFAULT '0' COMMENT '京东退货数量' AFTER `shop_goods_id`,
ADD `return_goods_result` TEXT NOT NULL COMMENT '京东退货信息' AFTER `return_goods_nun`;