

 CREATE TABLE `ims_superdesk_jd_vop_logs`(
`id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'ID' ,
`createtime` INT(11) NOT NULL DEFAULT '0' COMMENT 'createtime',
`url` VARCHAR(500) NOT NULL COMMENT 'url' ,
`method` VARCHAR(16) NOT NULL COMMENT 'method' ,
`post_fields` TEXT NOT NULL COMMENT 'post_fields' ,
`curl_info` TEXT NOT NULL COMMENT 'curl_info' ,
`response` TEXT NOT NULL COMMENT 'response' ,
 PRIMARY KEY (`id`)) ENGINE = InnoDB;



 {"success":false,"resultMessage":"pageNum不存在","resultCode":"0010","result":null}


CREATE TABLE `ims_superdesk_jd_vop_logs`(
`id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'ID' ,
`createtime` INT(11) NOT NULL DEFAULT '0' COMMENT 'createtime',
`url` VARCHAR(500) NOT NULL COMMENT 'url' ,
`api` VARCHAR(500) NOT NULL COMMENT 'api' ,
`method` VARCHAR(16) NOT NULL COMMENT 'method' ,
`post_fields` TEXT NOT NULL COMMENT 'post_fields' ,
`curl_info` TEXT NOT NULL COMMENT 'curl_info' ,
`success` INT(1) NOT NULL COMMENT 'success' ,
`resultMessage` VARCHAR(500) NOT NULL COMMENT 'url' ,
`resultCode` VARCHAR(16) NOT NULL COMMENT 'resultCode' ,
`result` TEXT NOT NULL COMMENT 'result' ,
 PRIMARY KEY (`id`)) ENGINE = InnoDB;