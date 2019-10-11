## 已更新到服务器

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;



CREATE TABLE `ims_elasticsearch_dictionary` (
  `id` int(11) NOT NULL,
  `uniacid` int(11) DEFAULT '0' COMMENT '所属帐号',
  `word` varchar(50) DEFAULT NULL COMMENT '词',

  `pcate` int(11) DEFAULT '0' COMMENT '一级分类ID',
  `ccate` int(11) DEFAULT '0' COMMENT '二级分类ID',
  `tcate` int(11) DEFAULT '0' COMMENT '三级分类ID',

  `cates` text COMMENT '多重分类数据集',

  `pcates` text COMMENT '一级多重分类',
  `ccates` text COMMENT '二级多重分类',
  `tcates` text COMMENT '三级多重分类',

  `enabled` tinyint(1) DEFAULT '1' COMMENT '是否开启'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `ims_elasticsearch_dictionary_category` (
  `id` int(11) NOT NULL,
  `uniacid` int(11) DEFAULT '0' COMMENT '所属帐号',
  `parentid` int(11) DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `name` varchar(50) DEFAULT NULL COMMENT '分类名称',
  `description` varchar(500) DEFAULT NULL COMMENT '分类介绍',
  `displayorder` tinyint(3) UNSIGNED DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) DEFAULT '1' COMMENT '是否开启',
  `level` tinyint(3) DEFAULT NULL COMMENT '分类是在几级'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


ALTER TABLE `ims_elasticsearch_dictionary`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ims_elasticsearch_dictionary_category`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `ims_elasticsearch_dictionary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=1;


ALTER TABLE `ims_elasticsearch_dictionary_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=1;


COMMIT;