





# 已更新到 福利内购
# 已更新到 企业采购
-- 京东余额处理表
CREATE TABLE `ims_superdesk_jd_vop_balance_detail_processing` (
  `id` int(11) NOT NULL,
  `processing` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0未处理,1已处理',
  `process_result` varchar(1000) NOT NULL DEFAULT '' COMMENT '处理结果',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='京东余额处理表';


# 已更新到 企业采购
# 已更新到 福利内购
ALTER TABLE `ims_superdesk_jd_vop_balance_detail` CHANGE `id` `id` BIGINT(20) NOT NULL COMMENT '余额明细 ID';



# 已更新到 企业采购
# 已更新到 福利内购
ALTER TABLE `ims_superdesk_jd_vop_balance_detail_processing` CHANGE `id` `id` BIGINT(20) NOT NULL;












# 已更新到 福利内购
# 已更新到 企业采购
ALTER TABLE `ims_superdesk_jd_vop_balance_detail`
  DROP `processing`,
  DROP `process_result`;


# 已更新到 企业采购
UPDATE `ims_superdesk_jd_vop_order_submit_order_sku` set return_goods_nun = 0 WHERE return_goods_nun < 0