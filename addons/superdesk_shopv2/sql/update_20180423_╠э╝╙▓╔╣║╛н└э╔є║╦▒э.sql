## 已更新到服务器

-- 添加审核表
CREATE TABLE `ims_superdesk_shop_order_examine` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `orderid` int(11) DEFAULT '0' COMMENT '订单id',
  `openid` varchar(50) DEFAULT '' COMMENT '采购专员',
  `manager` varchar(50) DEFAULT '' COMMENT '采购经理',
  `status` tinyint(4) DEFAULT '0' COMMENT '状态(0:未审核,1:审核通过,2:不通过)',
  `examinetime` int(11) DEFAULT '0' COMMENT '审核时间',
  `createtime` int(11) DEFAULT '0' COMMENT '创建时间',
  `enterprise` int(11) DEFAULT '0' COMMENT '企业id',
  `parent_order_id` int(11) DEFAULT '0' COMMENT '父订单id',
  PRIMARY KEY (`id`)
) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='企业月结审核表';


-- 添加用户角色表
CREATE TABLE `ims_superdesk_shop_member_cash_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rolename` varchar(50) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='会员角色表';


-- 用户表新添加一个角色id,关联角色表
ALTER TABLE `ims_superdesk_shop_member`
ADD `cash_role_id` INT(11) NOT NULL DEFAULT '0' COMMENT '角色id(对应superdesk_shop_member_cash_role)' AFTER `logintime`;