
## 已更新到服务器

ALTER TABLE `ims_superdesk_jd_vop_order_submit_order_sku` ADD `pOrder` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '主订单号' AFTER `id`;