

createtime date 如何设置当前插入时间为默认


```
CREATE TABLE `ims_core_paylog` (
  `plid` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `openid` varchar(40) NOT NULL,
  `tid` varchar(64) NOT NULL,
  `fee` decimal(10,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `module` varchar(50) NOT NULL,
  `tag` varchar(2000) NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `is_usecard` tinyint(3) unsigned NOT NULL,
  `card_type` tinyint(3) unsigned NOT NULL,
  `card_id` varchar(50) NOT NULL,
  `card_fee` decimal(10,2) unsigned NOT NULL,
  `encrypt_code` varchar(100) NOT NULL,
  `uniontid` varchar(50) NOT NULL,
  `createtime` date NOT NULL,
  `eso_tag` varchar(2000) NOT NULL DEFAULT '',
  PRIMARY KEY (`plid`),
  KEY `idx_openid` (`openid`) USING BTREE,
  KEY `idx_tid` (`tid`) USING BTREE,
  KEY `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=7109 DEFAULT CHARSET=utf8;


```