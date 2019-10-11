



## 已更新到服务器

ALTER TABLE `ims_superdesk_jd_vop_logs` ADD FULLTEXT INDEX `full_text_api` (`api`);

ALTER TABLE `ims_superdesk_shop_goods` ADD INDEX(`merchid`);

ALTER TABLE `db_super_desk`.`ims_superdesk_shop_goods` DROP INDEX `merchid` ;



SELECT api,COUNT(api) FROM `ims_superdesk_jd_vop_logs` GROUP BY api

SELECT * FROM `ims_superdesk_jd_vop_logs` WHERE api = '/api/area/getTown'

SELECT * FROM `ims_superdesk_jd_vop_logs` WHERE api = '/api/price/getPrice'



DELETE FROM `ims_superdesk_jd_vop_logs` WHERE api = '/api/area/getTown' and resultCode = 3405