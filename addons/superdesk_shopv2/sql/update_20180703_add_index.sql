## 已更新到服务器

ALTER TABLE `ims_superdesk_shop_goods` ADD INDEX(`updatetime`)




# uniacid = :uniacid AND status = 1 AND deleted = 0 AND jd_vop_sku > 0


ALTER TABLE `ims_superdesk_shop_goods`
ADD INDEX `idx_union_4_sku_dto_2_redis_queue` (`uniacid`, `status`, `deleted`, `jd_vop_sku`, `updatetime`) ;

explain SELECT jd_vop_sku,jd_vop_page_num FROM ims_superdesk_shop_goods WHERE uniacid = 16 AND status = 1 AND deleted = 0 AND jd_vop_sku > 0 ORDER BY updatetime ASC LIMIT 0,100

1
SIMPLE
ims_superdesk_shop_goods
index_merge	idx_uniacid,idx_deleted,jd_vop_sku,idx_status,idx_union1,idx_union2,idx_union_4_sku_dto_2_redis_queue	idx_uniacid,idx_deleted,idx_status	5,2,2

96715	Using intersect(idx_uniacid,idx_deleted,idx_status); Using where; Using filesort
