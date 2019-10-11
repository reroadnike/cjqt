






#sync

ALTER TABLE `ims_superdesk_core_provincecity` CHANGE `ID` `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'ID';



#sync

UPDATE `ims_superdesk_core_organization` SET `uniacid`= 16;
UPDATE `ims_superdesk_core_provincecity` SET `uniacid`= 16;
UPDATE `ims_superdesk_core_build` SET `uniacid`= 16;
UPDATE `ims_superdesk_core_dictionary_group` SET `uniacid`= 16;
UPDATE `ims_superdesk_core_dictionary_item` SET `uniacid`= 16;
UPDATE `ims_superdesk_core_virtualarchitecture` SET `uniacid`= 16;




UPDATE `ims_superdesk_core_organization` SET `uniacid`= 15;
UPDATE `ims_superdesk_core_provincecity` SET `uniacid`= 15;
UPDATE `ims_superdesk_core_build` SET `uniacid`= 15;
UPDATE `ims_superdesk_core_dictionary_group` SET `uniacid`= 15;
UPDATE `ims_superdesk_core_dictionary_item` SET `uniacid`= 15;
UPDATE `ims_superdesk_core_virtualarchitecture` SET `uniacid`= 15;






`uniacid` int(10) NOT NULL DEFAULT '0' COMMENT 'uniacid'
`createtime` int(10) NOT NULL COMMENT '创建时间',
`updatetime` int(10) NOT NULL COMMENT '修改时间',
`enabled` TINYINT(4) NOT NULL DEFAULT '1' COMMENT 'Enabled';


ALTER TABLE `ims_superdesk_core_organization` CHANGE `ID` `id` INT(11) NOT NULL AUTO_INCREMENT;





#ims_superdesk_core_organization
ALTER TABLE `ims_superdesk_core_organization` ADD `createtime_` INT(11) NOT NULL DEFAULT '0' COMMENT '创建时间';
ALTER TABLE `ims_superdesk_core_organization` ADD `updatetime` INT(11) NOT NULL DEFAULT '0' COMMENT '修改时间';
ALTER TABLE `ims_superdesk_core_organization` ADD `enabled` TINYINT(4) NOT NULL DEFAULT '1' COMMENT '是否可用或删除';
ALTER TABLE `ims_superdesk_core_organization` ADD `uniacid` INT(10) NOT NULL DEFAULT '0' COMMENT 'uniacid' AFTER `enabled`;


#ims_superdesk_core_provincecity
ALTER TABLE `ims_superdesk_core_provincecity` ADD `createtime_` INT(11) NOT NULL DEFAULT '0' COMMENT '创建时间';
ALTER TABLE `ims_superdesk_core_provincecity` ADD `updatetime` INT(11) NOT NULL DEFAULT '0' COMMENT '修改时间';
ALTER TABLE `ims_superdesk_core_provincecity` ADD `enabled` TINYINT(4) NOT NULL DEFAULT '1' COMMENT '是否可用或删除';
ALTER TABLE `ims_superdesk_core_provincecity` ADD `uniacid` INT(10) NOT NULL DEFAULT '0' COMMENT 'uniacid' AFTER `enabled`;


#ims_superdesk_core_build
ALTER TABLE `ims_superdesk_core_build` ADD `createtime_` INT(11) NOT NULL DEFAULT '0' COMMENT '创建时间';
ALTER TABLE `ims_superdesk_core_build` ADD `updatetime` INT(11) NOT NULL DEFAULT '0' COMMENT '修改时间';
ALTER TABLE `ims_superdesk_core_build` ADD `enabled` TINYINT(4) NOT NULL DEFAULT '1' COMMENT '是否可用或删除';
ALTER TABLE `ims_superdesk_core_build` ADD `uniacid` INT(10) NOT NULL DEFAULT '0' COMMENT 'uniacid' AFTER `enabled`;


#ims_superdesk_core_dictionary_group
ALTER TABLE `ims_superdesk_core_dictionary_group` ADD `createtime_` INT(11) NOT NULL DEFAULT '0' COMMENT '创建时间';
ALTER TABLE `ims_superdesk_core_dictionary_group` ADD `updatetime` INT(11) NOT NULL DEFAULT '0' COMMENT '修改时间';
ALTER TABLE `ims_superdesk_core_dictionary_group` ADD `enabled` TINYINT(4) NOT NULL DEFAULT '1' COMMENT '是否可用或删除';
ALTER TABLE `ims_superdesk_core_dictionary_group` ADD `uniacid` INT(10) NOT NULL DEFAULT '0' COMMENT 'uniacid' AFTER `enabled`;


#ims_superdesk_core_dictionary_item
ALTER TABLE `ims_superdesk_core_dictionary_item` ADD `createtime_` INT(11) NOT NULL DEFAULT '0' COMMENT '创建时间';
ALTER TABLE `ims_superdesk_core_dictionary_item` ADD `updatetime` INT(11) NOT NULL DEFAULT '0' COMMENT '修改时间';
ALTER TABLE `ims_superdesk_core_dictionary_item` ADD `enabled` TINYINT(4) NOT NULL DEFAULT '1' COMMENT '是否可用或删除';
ALTER TABLE `ims_superdesk_core_dictionary_item` ADD `uniacid` INT(10) NOT NULL DEFAULT '0' COMMENT 'uniacid' AFTER `enabled`;


#ims_superdesk_core_virtualarchitecture
ALTER TABLE `ims_superdesk_core_virtualarchitecture` ADD `createtime_` INT(11) NOT NULL DEFAULT '0' COMMENT '创建时间';
ALTER TABLE `ims_superdesk_core_virtualarchitecture` ADD `updatetime` INT(11) NOT NULL DEFAULT '0' COMMENT '修改时间';
ALTER TABLE `ims_superdesk_core_virtualarchitecture` ADD `enabled` TINYINT(4) NOT NULL DEFAULT '1' COMMENT '是否可用或删除';
ALTER TABLE `ims_superdesk_core_virtualarchitecture` ADD `uniacid` INT(10) NOT NULL DEFAULT '0' COMMENT 'uniacid' AFTER `enabled`;
