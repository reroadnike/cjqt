## 已更新到服务器

-- 订单表添加来源字段
ALTER TABLE `ims_superdesk_shop_order`
ADD `source_from` VARCHAR(6) NOT NULL DEFAULT 'wechat' COMMENT '订单来源(wechat,pc)' AFTER `taskdiscountprice`;