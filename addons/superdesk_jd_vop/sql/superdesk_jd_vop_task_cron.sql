


superdesk_jd_vop_task_cron

DROP TABLE IF EXISTS `ims_superdesk_jd_vop_task_cron`;
CREATE TABLE `ims_superdesk_jd_vop_task_cron` (
  `name` varchar(40) NOT NULL,
  `orde` int(10) NOT NULL DEFAULT '0',
  `file` varchar(100) DEFAULT '' NOT NULL,
  `no` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `desc` text,
  `freq` int(10) NOT NULL DEFAULT '0',
  `lastdo`  int(11) UNSIGNED NOT NULL DEFAULT 0 ,
  `log` text,
  PRIMARY KEY (`name`),
  UNIQUE KEY `name` (`name`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `ims_superdesk_jd_vop_task_cron` (
  `name` varchar(40) NOT NULL,
  `orde` int(10) NOT NULL DEFAULT '0',
  `file` varchar(100) NOT NULL DEFAULT '',
  `no` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `desc` text,
  `freq` int(10) NOT NULL DEFAULT '0',
  `lastdo` int(11) unsigned NOT NULL DEFAULT '0',
  `log` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_superdesk_jd_vop_task_cron`
--

INSERT INTO `ims_superdesk_jd_vop_task_cron` (`name`, `orde`, `file`, `no`, `desc`, `freq`, `lastdo`, `log`) VALUES
('_cron_handle_task_03_sku_detail', 3, '_cron_handle_task_03_sku_detail.inc.php', 0, '从队列中取skuId，call京东VOPapi，导入sku详细与图片', 0, 1514273558, ''),
('_cron_handle_task_01_page_num', 1, '_cron_handle_task_01_page_num.inc.php', 1, '更新京东VOP page_num，队列', 0, 1513937622, ''),
('_cron_handle_task_02_sku_4_page_num', 2, '_cron_handle_task_02_sku_4_page_num.inc.php', 0, '通过更新京东VOP page_num 导入 skuId，进入队列', 0, 1514273398, '');

ALTER TABLE `ims_superdesk_jd_vop_task_cron`
  ADD PRIMARY KEY (`name`),
  ADD UNIQUE KEY `name` (`name`) USING BTREE;