



ALTER TABLE `ims_business_dongyuantangguoyiguang_dictionary_group` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'ID';
ALTER TABLE `ims_business_dongyuantangguoyiguang_dictionary_group` CHANGE `createtime` `createtime` INT(11) NOT NULL DEFAULT '0' COMMENT '创建时间';
ALTER TABLE `ims_business_dongyuantangguoyiguang_dictionary_group` CHANGE `updatetime` `updatetime` INT(11) NOT NULL DEFAULT '0' COMMENT '更新时间';



UPDATE `ims_business_dongyuantangguoyiguang_dictionary_group` SET uniacid = 15