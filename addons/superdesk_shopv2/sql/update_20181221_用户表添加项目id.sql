

-- 已更新到企业正式

ALTER TABLE `ims_superdesk_shop_member`
ADD COLUMN `core_organization`  int(11) NULL DEFAULT 0 COMMENT '项目id' AFTER `core_enterprise`;

-- 不用执行.填充shop_member中的新增字段core_organization的补丁,作为转程序补丁的依据
-- update ims_superdesk_shop_member as m LEFT JOIN ims_superdesk_core_virtualarchitecture as enterprise on enterprise.id = m.core_enterprise
-- set m.core_organization = IFNULL(enterprise.organizationId,0)