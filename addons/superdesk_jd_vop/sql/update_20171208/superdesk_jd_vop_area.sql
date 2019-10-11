CREATE TABLE `ims_superdesk_jd_vop_area` (
`code` INT(11) NOT NULL COMMENT 'code' ,
`parent_code` INT(11) NOT NULL COMMENT 'parent_code' ,
`text` VARCHAR(128) NOT NULL DEFAULT '' COMMENT 'text' ,
`state` TINYINT(3) NOT NULL DEFAULT '1' COMMENT 'state' ,
`createtime` INT(11) NOT NULL DEFAULT '0' COMMENT 'createtime' ,
`updatetime` INT(11) NOT NULL COMMENT 'updatetime' ) ENGINE = InnoDB;

ALTER TABLE `ims_superdesk_jd_vop_area` ADD PRIMARY KEY(`code`);
ALTER TABLE `ims_superdesk_jd_vop_area` ADD `level` TINYINT(4) NOT NULL DEFAULT '0' COMMENT 'level' AFTER `parent_code`;
ALTER TABLE `ims_superdesk_jd_vop_area` ADD `remark` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '标注' AFTER `state`;