

bug_数据_清除1_sku对n_goods_id问题


SELECT jd_vop_sku as sku, COUNT(jd_vop_sku) as num, GROUP_CONCAT(id) as ids , GROUP_CONCAT(salesreal) as sales_real
FROM ims_superdesk_shop_goods
WHERE
    1
    and jd_vop_sku >0 GROUP BY jd_vop_sku HAVING num > 1




    salesreal < 1




create table ims_cc_superdesk_shop_goods_cc_sku
as
SELECT jd_vop_sku as sku, COUNT(jd_vop_sku) as num, GROUP_CONCAT(id) as ids
FROM ims_superdesk_shop_goods
WHERE
    1
    and jd_vop_sku >0

GROUP BY jd_vop_sku HAVING num > 1
ORDER BY id ASC




ALTER TABLE `ims_cc_superdesk_shop_goods_cc_sku` ADD PRIMARY KEY(`sku`);
ALTER TABLE `ims_cc_superdesk_shop_goods_cc_sku` ADD `is_delete` INT(2) NOT NULL DEFAULT '0' AFTER `ids`;