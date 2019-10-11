
## 已更新到服务器

CREATE TABLE `ims_superdesk_jd_vop_search` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(50) DEFAULT NULL,
  `has_keyword` tinyint(2) DEFAULT '0' COMMENT '是否有关键字(0否1是)',
  `keyword` varchar(512) DEFAULT '' COMMENT '搜索关键字',
  `has_pricebetween` tinyint(2) DEFAULT '0' COMMENT '是否有价格区间(0否1是)',
  `minprice` decimal(10,2) DEFAULT '0.00' COMMENT '最低价格',
  `maxprice` decimal(10,2) DEFAULT '0.00' COMMENT '最高价格',
  `has_filter` tinyint(2) DEFAULT '0' COMMENT '是否有筛选(0否1是)',
  `filters` varchar(255) DEFAULT '' COMMENT '筛选(以逗号分隔)',
  `has_cate` tinyint(2) DEFAULT '0' COMMENT '是否有选择分类(0否1是)',
  `cate` int(11) DEFAULT '0' COMMENT '分类id',
  `has_order` tinyint(2) DEFAULT '0' COMMENT '是否有排序(0否1是)',
  `order_by` varchar(64) DEFAULT '' COMMENT '排序',
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
















ALTER TABLE `ims_superdesk_jd_vop_search` CHANGE `order` `order_by` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '排序';