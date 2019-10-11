
## 已更新到服务器


CREATE TABLE IF NOT EXISTS `ims_superdesk_shop_merch_x_enterprise` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `merchid` int(11) NOT NULL DEFAULT 0,
  `enterprise_id` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) DEFAULT '1',
  `createtime` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT AUTO_INCREMENT=1 ;

ALTER TABLE `ims_superdesk_shop_merch_x_enterprise` ADD INDEX(`merchid`);
ALTER TABLE `ims_superdesk_shop_merch_x_enterprise` ADD INDEX(`enterprise_id`);