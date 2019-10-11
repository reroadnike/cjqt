CREATE TABLE `ims_superdesk_jd_vop_order_track` (
`id` INT(11) NOT NULL COMMENT 'id' ,
`order_id` INT(11) NOT NULL COMMENT 'order_id' ,
`jdOrderId` INT(11) NOT NULL COMMENT 'jdOrderId' ,
`content` VARCHAR(1000) NOT NULL DEFAULT '' COMMENT 'content' ,
`msgTime` VARCHAR(128) NOT NULL DEFAULT '' COMMENT 'msgTime' ,
`operator` VARCHAR(128) NOT NULL DEFAULT '' COMMENT 'operator'

) ENGINE = InnoDB;

ALTER TABLE `ims_superdesk_jd_vop_order_track`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ims_superdesk_jd_vop_order_track`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;