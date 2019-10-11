
ALTER TABLE `ims_superdesk_shop_order_refund`
DROP COLUMN `order_goods_id`,
ADD COLUMN `order_goods_id`  int(11) NULL DEFAULT 0 COMMENT '订单商品id(对应shop_order_goods表)' AFTER `merchid`;

ALTER TABLE `ims_superdesk_shop_order_refund`
ADD COLUMN `refund_total`  int(5) NULL DEFAULT 0 COMMENT '退货数量' AFTER `order_goods_id`;



ALTER TABLE `ims_superdesk_shop_order_goods`
ADD COLUMN `refundid`  int(11) NULL DEFAULT 0 COMMENT '退款id' AFTER `return_goods_result`;

ALTER TABLE `ims_superdesk_shop_order_goods`
DROP COLUMN `refund_status`,
ADD COLUMN `refund_status`  tinyint(3) NULL DEFAULT 0 COMMENT '退款状态(对应order_refund表的status)' AFTER `refundid`;

