
## 已更新到服务器

ALTER TABLE `ims_superdesk_jd_vop_product_detail`
ADD INDEX `idx_sku` (`sku`) ;

ALTER TABLE `ims_superdesk_jd_vop_product_price`
ADD INDEX `idx_sku` (`skuId`) ;



ALTER TABLE ims_superdesk_jd_vop_logs DROP INDEX full_text_api;




ALTER TABLE `ims_superdesk_jd_vop_logs`
ADD INDEX `idx_union_search_0` (`api`, `success`) ;

ALTER TABLE `ims_superdesk_jd_vop_logs`
ADD INDEX `idx_union_search_1` (`api`, `success`, `createtime`) ;

ALTER TABLE `ims_superdesk_jd_vop_logs`
ADD INDEX `idx_union_search_2` (`api`, `success`, `resultCode`, `createtime`) ;

