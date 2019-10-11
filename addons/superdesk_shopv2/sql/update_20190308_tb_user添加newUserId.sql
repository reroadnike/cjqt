

-- 已更新到企业正式

ALTER TABLE `ims_superdesk_core_tb_user`
ADD COLUMN `newUserId`  int(11) NULL DEFAULT 0 COMMENT '新的core_user' AFTER `core_user`;