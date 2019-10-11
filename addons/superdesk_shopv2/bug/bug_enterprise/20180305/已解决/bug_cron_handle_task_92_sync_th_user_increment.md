

导致超级前台用户同步出错

超级前台用户表加字段没通知到我修改


## 已更新到服务器

ALTER TABLE `ims_superdesk_core_tb_user` ADD `isSyncSpaceHome` INT(11) NOT NULL COMMENT 'isSyncSpaceHome' AFTER `isSyncNeigou`;