
## 已更新到服务器

ALTER TABLE `ims_superdesk_jd_vop_task_cron` CHANGE `name` `name` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;



INSERT INTO `ims_superdesk_jd_vop_task_cron` (`name`, `orde`, `file`, `no`, `desc`, `freq`, `lastdo`, `log`) VALUES
('_cron_handle_task_03_sku_detail', 3, '_cron_handle_task_03_sku_detail.inc.php', 1, '从队列中取skuId，call京东VOPapi，导入sku详细与图片', 0, 1514273558, ''),
('_cron_handle_task_01_page_num', 1, '_cron_handle_task_01_page_num.inc.php', 1, '更新京东VOP page_num，队列', 0, 1513937622, ''),
('_cron_handle_task_02_sku_4_page_num', 2, '_cron_handle_task_02_sku_4_page_num.inc.php', 1, '通过更新京东VOP page_num 导入 skuId，进入队列', 0, 1514273398, ''),
('_cron_handle_task_04_sku_price_update_100', 4, '_cron_handle_task_04_sku_price_update_100.inc.php', 0, '更新京东商品价格', 0, 1516968986, '');