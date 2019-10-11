
# 已更新到 福利内购
# 已更新到 企业采购


CREATE TABLE `ims_superdesk_jd_vop_configs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` varchar(100) DEFAULT '',
  `client_secret` varchar(100) DEFAULT '',
  `username` varchar(100) DEFAULT '',
  `password` varchar(100) DEFAULT '',
  `is_default` tinyint(3) DEFAULT '0' COMMENT '是否默认(0否1是)',
  `createtime` int(10) DEFAULT NULL,
  `updatetime` int(10) DEFAULT NULL,
  `title` varchar(255) DEFAULT '' COMMENT '标题.作为知道这个是用在哪里?',
  `deleted` tinyint(3) DEFAULT '0' COMMENT '软删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;


# 已更新到 福利内购
# 已更新到 企业采购

INSERT INTO `ims_superdesk_jd_vop_configs` (`id`, `client_id`, `client_secret`, `username`, `password`, `is_default`, `createtime`, `updatetime`, `title`, `deleted`) VALUES
(8, 'FlCa4YWCw3m7XcrD2ki0', 'zWkZHHRc6hwJmCy0XRQq', '中航物业VOP', '123000', 1, 1534733630, 1535364392, '中航物业VOP', 0),
(9, 'nuAeRH1KFlSeiIrfPYTZ', 'ioyqcauADf0v3ayE73I5', '中航物业员工福利', '123000', 0, 1534733651, 1535364370, '中航物业员工福利', 0);