# 已记录PM

20180720_财务追踪_修正_催单登记-是否回款 1 未回款 2 已回款

SELECT * FROM `ims_superdesk_shop_order_finance` WHERE press_status= 0


SELECT *,FROM_UNIXTIME(createtime) FROM `ims_superdesk_shop_order_finance` WHERE press_status= 0


UPDATE `ims_superdesk_shop_order_finance` SET press_status= 1 WHERE press_status= 0

