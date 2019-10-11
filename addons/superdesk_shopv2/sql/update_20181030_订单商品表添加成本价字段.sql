
-- 已更新到企业正式

ALTER TABLE `ims_superdesk_shop_order_goods`
ADD COLUMN `costprice`  decimal(10,2) NULL DEFAULT 0.00 COMMENT '商品购买时的成本价' AFTER `realprice`;



正在显示第 0 - 24 行 (共 13844 行, 查询花费 0.0008 秒。)
SELECT * FROM ims_superdesk_shop_order_goods WHERE costprice = 0.00