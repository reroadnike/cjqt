

## 已更新到服务器

UPDATE ims_superdesk_jd_vop_order_submit_order SET checking_by_third = 0

ALTER TABLE `ims_superdesk_jd_vop_order_submit_order` ADD `checking_by_third` TINYINT(8) NOT NULL COMMENT '订单反查标记' AFTER `jd_vop_result_orderTaxPrice`;
ALTER TABLE `ims_superdesk_jd_vop_order_submit_order` CHANGE `checking_by_third` `checking_by_third` INT(8) NOT NULL COMMENT '订单反查标记';


ALTER TABLE `ims_superdesk_jd_vop_order_submit_order` CHANGE `checking_by_third` `checking_by_third` INT(8) NOT NULL DEFAULT '0' COMMENT '订单反查标记';

checking_by_third
0 待验证
1 已验证
3401 已退单



ALTER TABLE `ims_superdesk_jd_vop_order_submit_order` ADD INDEX(`order_id`);

ALTER TABLE `ims_superdesk_jd_vop_order_submit_order` ADD INDEX(`thirdOrder`);

ALTER TABLE `ims_superdesk_jd_vop_order_submit_order` ADD INDEX(`jd_vop_result_jdOrderId`);

ALTER TABLE `ims_superdesk_jd_vop_order_submit_order` ADD INDEX(`checking_by_third`);


INSERT INTO `ims_superdesk_jd_vop_task_cron` (`name`, `orde`, `file`, `no`, `desc`, `freq`, `lastdo`, `log`) VALUES
('_cron_handle_task_66_checking_order', 66, '_cron_handle_task_66_checking_order.inc.php', 0, '订单反查，再次确认订单是否成功', 0, 1517204986, ''),
('_cron_handle_task_67_checking_order_state', 67, '_cron_handle_task_67_checking_order_state.inc.php', 0, 'JD订单信息，有可能会被拆单', 0, 1517216981, '');