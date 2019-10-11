


CREATE TABLE `ims_superdesk_jd_vop_page_num` (
`id` INT(11) NOT NULL COMMENT 'id' ,
`uniacid` INT(11) NOT NULL COMMENT 'uniacid' ,
`page_num` INT(11) NOT NULL COMMENT 'page_num' ,
`name` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '商品池名字' ,
`state` TINYINT(3) NOT NULL DEFAULT '1' COMMENT 'state' ,
`createtime` INT(11) NOT NULL DEFAULT '0' COMMENT 'createtime' ,
`updatetime` INT(11) NOT NULL COMMENT 'updatetime' ) ENGINE = InnoDB;

ALTER TABLE `ims_superdesk_jd_vop_page_num`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ims_superdesk_jd_vop_page_num`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

初始化数据接口
http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_product_get_page_num
http://www.avic-s.com/plugins/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_product_get_page_num
共 2651 行