
# 已更新到企业正式

CREATE TABLE `ims_superdesk_shop_goods_similar` (
  `id` int(11) NOT NULL COMMENT '对应商品表的id',
  `uniacid` int(11) DEFAULT '0',
  `similar_id` varchar(255) DEFAULT NULL,
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

