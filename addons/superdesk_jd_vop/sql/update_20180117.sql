

## 已更新到服务器

ALTER TABLE `ims_superdesk_jd_vop_logs` ADD INDEX(`api`);
ALTER TABLE `ims_superdesk_jd_vop_logs` ADD INDEX(`success`);

SELECT api FROM `ims_superdesk_jd_vop_logs` GROUP BY api

/api/area/checkArea
/api/area/getTown
/api/order/cancel
/api/order/confirmOrder
/api/order/getFreight
/api/order/orderTrack
/api/order/submitOrder
/api/price/getBalance
/api/price/getBalanceDetail
/api/price/getPrice
/api/product/getCategory
/api/product/getDetail
/api/product/getSku
/api/product/skuImage
/api/stock/getNewStockById
/oauth2/refreshToken

SELECT COUNT(success) FROM `ims_superdesk_jd_vop_logs` WHERE success = 0

3299
