

ALTER TABLE `ims_superdesk_core_virtualarchitecture` CHANGE `phone` `phone` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '联系电话';


ALTER TABLE `ims_superdesk_core_tb_user` CHANGE `isSyncSpaceHome` `isSyncSpaceHome` INT(11) NOT NULL DEFAULT '0' COMMENT 'isSyncSpaceHome';