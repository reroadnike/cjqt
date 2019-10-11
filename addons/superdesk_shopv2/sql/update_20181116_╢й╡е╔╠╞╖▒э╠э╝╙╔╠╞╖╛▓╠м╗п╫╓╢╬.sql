
-- 已更新到企业正式

ALTER TABLE `ims_superdesk_shop_order_goods`
ADD COLUMN `goods_static`  LONGTEXT COMMENT '商品静态化(即整个商品序列化丢进来)' AFTER `costprice`;