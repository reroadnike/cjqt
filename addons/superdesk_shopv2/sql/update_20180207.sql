






## 已更新到服务器





ALTER TABLE `ims_superdesk_jd_vop_order_submit_order`
ADD `jd_vop_recheck_orderState` INT(11) NOT NULL DEFAULT '0' COMMENT '反查之后更新orderState' AFTER `checking_by_third`,
ADD `jd_vop_recheck_submitState` INT(11) NOT NULL DEFAULT '0' COMMENT '反查之后更新submitState' AFTER `jd_vop_recheck_orderState`;



ALTER TABLE `ims_superdesk_jd_vop_order_submit_order` DROP INDEX checking_by_third_2;
ALTER TABLE `ims_superdesk_jd_vop_order_submit_order` DROP INDEX thirdOrder_2;
ALTER TABLE `ims_superdesk_jd_vop_order_submit_order` DROP INDEX order_id_2;
ALTER TABLE `ims_superdesk_jd_vop_order_submit_order` DROP INDEX jd_vop_result_jdOrderId;


ALTER TABLE `ims_superdesk_jd_vop_order_submit_order` ADD `isparent` TINYINT(3) NOT NULL DEFAULT '0' COMMENT '1为父' AFTER `id`;

ALTER TABLE `ims_superdesk_jd_vop_order_submit_order` ADD `jd_vop_recheck_pOrder` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '用于拆单' AFTER `jd_vop_recheck_submitState`;