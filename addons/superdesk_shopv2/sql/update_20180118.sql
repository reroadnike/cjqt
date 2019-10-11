





## 已更新到服务器




ALTER TABLE `ims_superdesk_shop_category` ADD `old_shop_cate_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'old_shop_cate_id' AFTER `fiscal_code`;



ALTER TABLE `ims_superdesk_shop_goods`
  DROP `saleupdate32484`,
  DROP `saleupdate33219`,
  DROP `saleupdate35843`,
  DROP `saleupdate36586`,
  DROP `saleupdate37975`,
  DROP `saleupdate40170`,
  DROP `saleupdate51117`,
  DROP `saleupdate53481`;


150 saleupdate32484	tinyint(3)			是	0			 修改 修改	 删除 删除
151	saleupdate33219	tinyint(3)			是	0			 修改 修改	 删除 删除
152	saleupdate35843	tinyint(3)			是	0			 修改 修改	 删除 删除
153	saleupdate36586	tinyint(3)			是	0			 修改 修改	 删除 删除
154	saleupdate37975	tinyint(3)			是	0			 修改 修改	 删除 删除
155	saleupdate40170	tinyint(3)			是	0			 修改 修改	 删除 删除
156	saleupdate51117	tinyint(3)			是	0			 修改 修改	 删除 删除
157	saleupdate53481 tinyint(3)			是	0			 修改 修改	 删除 删除

ALTER TABLE `ims_superdesk_shop_goods` ADD `old_shop_goods_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'old_shop_goods_id' AFTER `jd_vop_page_num`;

ALTER TABLE `ims_superdesk_shop_merch_account`
CHANGE COLUMN `merchid` `merchid` INT(11) NULL DEFAULT '0' COMMENT '商户ID' ,
CHANGE COLUMN `username` `username` VARCHAR(255) NULL DEFAULT '' COMMENT '用户名' ,
CHANGE COLUMN `pwd` `pwd` VARCHAR(255) NULL DEFAULT '' COMMENT '用户密码' ,
CHANGE COLUMN `salt` `salt` VARCHAR(255) NULL DEFAULT '' COMMENT '密码加盐' ,
CHANGE COLUMN `perms` `perms` TEXT NULL DEFAULT NULL COMMENT '权限' ,
CHANGE COLUMN `isfounder` `isfounder` TINYINT(3) NULL DEFAULT '0' COMMENT '是否创始人' ,
CHANGE COLUMN `lastip` `lastip` VARCHAR(255) NULL DEFAULT '' COMMENT '最后IP' ,
CHANGE COLUMN `lastvisit` `lastvisit` VARCHAR(255) NULL DEFAULT '' COMMENT '最后访问' ;


ALTER TABLE `ims_superdesk_shop_merch_adv`
CHANGE COLUMN `advname` `advname` VARCHAR(50) NULL DEFAULT NULL COMMENT '幻灯片名称' ,
CHANGE COLUMN `link` `link` VARCHAR(255) NULL DEFAULT NULL COMMENT '幻灯片链接' ,
CHANGE COLUMN `thumb` `thumb` VARCHAR(255) NULL DEFAULT NULL COMMENT '幻灯片图片' ,
CHANGE COLUMN `displayorder` `displayorder` INT(11) NULL DEFAULT NULL COMMENT '显示顺序' ,
CHANGE COLUMN `enabled` `enabled` INT(11) NULL DEFAULT NULL COMMENT '是否显示' ,
CHANGE COLUMN `merchid` `merchid` INT(11) NULL DEFAULT '0' COMMENT '商户号ID' ;

ALTER TABLE `ims_superdesk_shop_merch_banner`
CHANGE COLUMN `bannername` `bannername` VARCHAR(50) NULL DEFAULT '' COMMENT '广告名字' ,
CHANGE COLUMN `link` `link` VARCHAR(255) NULL DEFAULT '' COMMENT '广告链接' ,
CHANGE COLUMN `thumb` `thumb` VARCHAR(255) NULL DEFAULT '' COMMENT '广告图片' ,
CHANGE COLUMN `displayorder` `displayorder` INT(11) NULL DEFAULT '0' COMMENT '显示顺序' ,
CHANGE COLUMN `enabled` `enabled` INT(11) NULL DEFAULT '0' COMMENT '广告是否显示' ,
CHANGE COLUMN `merchid` `merchid` INT(11) NULL DEFAULT '0' COMMENT '商户ID' ;

ALTER TABLE `ims_superdesk_shop_merch_bill`
CHANGE COLUMN `applyno` `applyno` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '编号' ,
CHANGE COLUMN `merchid` `merchid` INT(11) NOT NULL DEFAULT '0' COMMENT '商户id' ,
CHANGE COLUMN `orderids` `orderids` TEXT NOT NULL COMMENT '订单ID' ,
CHANGE COLUMN `realprice` `realprice` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '申请金额' ,
CHANGE COLUMN `realpricerate` `realpricerate` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '申请抽成后金额' ,
CHANGE COLUMN `finalprice` `finalprice` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '实际打款金额' ,
CHANGE COLUMN `payrateprice` `payrateprice` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '抽成金额' ,
CHANGE COLUMN `payrate` `payrate` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '抽成比例' ,
CHANGE COLUMN `applytime` `applytime` INT(11) NOT NULL DEFAULT '0' COMMENT '申请时间' ,
CHANGE COLUMN `checktime` `checktime` INT(11) NOT NULL DEFAULT '0' COMMENT '确认时间' ,
CHANGE COLUMN `paytime` `paytime` INT(11) NOT NULL DEFAULT '0' COMMENT '打款时间' ,
CHANGE COLUMN `refusetime` `refusetime` INT(11) NOT NULL DEFAULT '0' COMMENT '拒绝时间' ,
CHANGE COLUMN `remark` `remark` TEXT NOT NULL COMMENT '备注' ,
CHANGE COLUMN `status` `status` TINYINT(3) NOT NULL DEFAULT '0' COMMENT '状态' ,
CHANGE COLUMN `ordernum` `ordernum` INT(11) NOT NULL DEFAULT '0' COMMENT '申请订单个数' ,
CHANGE COLUMN `orderprice` `orderprice` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单金额' ,
CHANGE COLUMN `price` `price` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '实际支付金额' ,
CHANGE COLUMN `passrealprice` `passrealprice` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '通过申请金额' ,
CHANGE COLUMN `passrealpricerate` `passrealpricerate` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '通过申请抽成后金额' ,
CHANGE COLUMN `passorderids` `passorderids` TEXT NOT NULL COMMENT '通过申请订单ID' ,
CHANGE COLUMN `passordernum` `passordernum` INT(11) NOT NULL DEFAULT '0' COMMENT '通过申请订单个数' ,
CHANGE COLUMN `passorderprice` `passorderprice` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '通过申请订单价格' ,
CHANGE COLUMN `alipay` `alipay` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '支付宝账号' ,
CHANGE COLUMN `bankname` `bankname` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '银行名称' ,
CHANGE COLUMN `bankcard` `bankcard` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '银行卡号' ,
CHANGE COLUMN `applyrealname` `applyrealname` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '姓名' ,
CHANGE COLUMN `applytype` `applytype` TINYINT(3) NOT NULL DEFAULT '0' COMMENT '提现方式' ;

ALTER TABLE `ims_superdesk_shop_merch_billo`
CHANGE COLUMN `billid` `billid` INT(11) NOT NULL DEFAULT '0' COMMENT '提现申请表ID' ,
CHANGE COLUMN `orderid` `orderid` INT(11) NOT NULL DEFAULT '0' COMMENT '订单ID' ,
CHANGE COLUMN `ordermoney` `ordermoney` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '提现金额' ;

ALTER TABLE `ims_superdesk_shop_merch_category`
CHANGE COLUMN `catename` `catename` VARCHAR(255) NULL DEFAULT '' COMMENT '商户分类名' ,
CHANGE COLUMN `createtime` `createtime` INT(11) NULL DEFAULT '0' COMMENT '分类建立时间' ,
CHANGE COLUMN `status` `status` TINYINT(1) NULL DEFAULT '0' COMMENT '是否显示' ,
CHANGE COLUMN `displayorder` `displayorder` INT(11) NULL DEFAULT '0' COMMENT '分类排序' ,
CHANGE COLUMN `thumb` `thumb` VARCHAR(500) NULL DEFAULT '' COMMENT '分类图片' ,
CHANGE COLUMN `isrecommand` `isrecommand` TINYINT(1) NULL DEFAULT '0' COMMENT '是否首页推荐' ;

ALTER TABLE `ims_superdesk_shop_merch_category_swipe`
CHANGE COLUMN `title` `title` VARCHAR(255) NULL DEFAULT '' COMMENT '标题' ,
CHANGE COLUMN `createtime` `createtime` INT(11) NULL DEFAULT '0' COMMENT '建立时间' ,
CHANGE COLUMN `status` `status` TINYINT(1) NULL DEFAULT '0' COMMENT '是否显示' ,
CHANGE COLUMN `displayorder` `displayorder` INT(11) NULL DEFAULT '0' COMMENT '显示顺序' ,
CHANGE COLUMN `thumb` `thumb` VARCHAR(500) NULL DEFAULT '' COMMENT '分类图片' ;

ALTER TABLE `ims_superdesk_shop_merch_clearing`
CHANGE COLUMN `merchid` `merchid` INT(11) NOT NULL DEFAULT '0' COMMENT '商户ID' ,
CHANGE COLUMN `clearno` `clearno` VARCHAR(64) NOT NULL DEFAULT '' COMMENT '结算编号' ,
CHANGE COLUMN `goodsprice` `goodsprice` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品价格' ,
CHANGE COLUMN `dispatchprice` `dispatchprice` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '物流价格' ,
CHANGE COLUMN `deductprice` `deductprice` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '积分抵扣' ,
CHANGE COLUMN `deductcredit2` `deductcredit2` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '余额抵扣' ,
CHANGE COLUMN `discountprice` `discountprice` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '会员折扣金额' ,
CHANGE COLUMN `deductenough` `deductenough` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '满减金额' ,
CHANGE COLUMN `merchdeductenough` `merchdeductenough` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '商户满减金额' ,
CHANGE COLUMN `isdiscountprice` `isdiscountprice` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '促销金额' ,
CHANGE COLUMN `price` `price` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单实收' ,
CHANGE COLUMN `createtime` `createtime` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '结算生成时间' ,
CHANGE COLUMN `starttime` `starttime` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '订单开始时间' ,
CHANGE COLUMN `endtime` `endtime` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '订单结束时间' ,
CHANGE COLUMN `status` `status` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '结算状态 0 未结算 1 结算中 2 已结算' ,
CHANGE COLUMN `realprice` `realprice` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单应收' ,
CHANGE COLUMN `realpricerate` `realpricerate` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '抽成后金额' ,
CHANGE COLUMN `finalprice` `finalprice` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '最终打款' ,
CHANGE COLUMN `remark` `remark` VARCHAR(2000) NOT NULL DEFAULT '' COMMENT '备注' ,
CHANGE COLUMN `paytime` `paytime` INT(11) NOT NULL DEFAULT '0' COMMENT '支付时间' ,
CHANGE COLUMN `payrate` `payrate` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '抽成比例' ;

ALTER TABLE `ims_superdesk_shop_merch_group`
CHANGE COLUMN `groupname` `groupname` VARCHAR(255) NULL DEFAULT '' COMMENT '商户组名' ,
CHANGE COLUMN `createtime` `createtime` INT(11) NULL DEFAULT '0' COMMENT '建立时间' ,
CHANGE COLUMN `status` `status` TINYINT(3) NULL DEFAULT '0' COMMENT '组状态' ,
CHANGE COLUMN `isdefault` `isdefault` TINYINT(1) NULL DEFAULT '0' COMMENT '是否默认' ,
CHANGE COLUMN `goodschecked` `goodschecked` TINYINT(1) NULL DEFAULT '0' COMMENT '商户添加的商品是否免审核' ,
CHANGE COLUMN `commissionchecked` `commissionchecked` TINYINT(1) NULL DEFAULT '0' COMMENT '商户添加的商品是否可以设置商品佣金' ,
CHANGE COLUMN `changepricechecked` `changepricechecked` TINYINT(1) NULL DEFAULT '0' COMMENT '商户是否可以修改订单价格' ,
CHANGE COLUMN `finishchecked` `finishchecked` TINYINT(1) NULL DEFAULT '0' COMMENT '商户是否可以在后台点击确认收货' ;

ALTER TABLE `ims_superdesk_shop_merch_nav`
CHANGE COLUMN `navname` `navname` VARCHAR(255) NULL DEFAULT '' COMMENT '导航名称' ,
CHANGE COLUMN `icon` `icon` VARCHAR(255) NULL DEFAULT '' COMMENT '图标' ,
CHANGE COLUMN `url` `url` VARCHAR(255) NULL DEFAULT '' COMMENT '链接地址' ,
CHANGE COLUMN `displayorder` `displayorder` INT(11) NULL DEFAULT '0' COMMENT '排序' ,
CHANGE COLUMN `status` `status` TINYINT(3) NULL DEFAULT '0' COMMENT '状态' ,
CHANGE COLUMN `merchid` `merchid` INT(11) NULL DEFAULT '0' COMMENT '商户ID' ;

ALTER TABLE `ims_superdesk_shop_merch_notice`
CHANGE COLUMN `displayorder` `displayorder` INT(11) NULL DEFAULT '0' COMMENT '显示顺序' ,
CHANGE COLUMN `title` `title` VARCHAR(255) NULL DEFAULT '' COMMENT '标题' ,
CHANGE COLUMN `thumb` `thumb` VARCHAR(255) NULL DEFAULT '' COMMENT '图片' ,
CHANGE COLUMN `link` `link` VARCHAR(255) NULL DEFAULT '' COMMENT '链接地址' ,
CHANGE COLUMN `detail` `detail` TEXT NULL DEFAULT NULL COMMENT '详细信息' ,
CHANGE COLUMN `status` `status` TINYINT(3) NULL DEFAULT '0' COMMENT '状态' ,
CHANGE COLUMN `createtime` `createtime` INT(11) NULL DEFAULT NULL COMMENT '建立时间' ,
CHANGE COLUMN `merchid` `merchid` INT(11) NULL DEFAULT '0' COMMENT '商户ID' ;

ALTER TABLE `ims_superdesk_shop_merch_saler`
CHANGE COLUMN `storeid` `storeid` INT(11) NULL DEFAULT '0' COMMENT '商户门店ID' ,
CHANGE COLUMN `openid` `openid` VARCHAR(255) NULL DEFAULT '' COMMENT '店员openid' ,
CHANGE COLUMN `status` `status` TINYINT(3) NULL DEFAULT '0' COMMENT '工作状态 1 启用 0 禁用' ,
CHANGE COLUMN `salername` `salername` VARCHAR(255) NULL DEFAULT '' COMMENT '店员姓名' ,
CHANGE COLUMN `merchid` `merchid` INT(11) NULL DEFAULT '0' COMMENT '商户ID' ;

ALTER TABLE `ims_superdesk_shop_merch_store`
CHANGE COLUMN `storename` `storename` VARCHAR(255) NULL DEFAULT '' COMMENT '门店名称' ,
CHANGE COLUMN `address` `address` VARCHAR(255) NULL DEFAULT '' COMMENT '门店地址' ,
CHANGE COLUMN `tel` `tel` VARCHAR(255) NULL DEFAULT '' COMMENT '电话' ,
CHANGE COLUMN `lat` `lat` VARCHAR(255) NULL DEFAULT '' COMMENT '经度' ,
CHANGE COLUMN `lng` `lng` VARCHAR(255) NULL DEFAULT '' COMMENT '纬度' ,
CHANGE COLUMN `status` `status` TINYINT(3) NULL DEFAULT '0' COMMENT '状态 1 启用 0 禁用' ,
CHANGE COLUMN `type` `type` TINYINT(1) NULL DEFAULT '0' COMMENT '类型 1 自提 2 核销 3 同时支持' ,
CHANGE COLUMN `realname` `realname` VARCHAR(255) NULL DEFAULT '' COMMENT '自提联系人名字' ,
CHANGE COLUMN `mobile` `mobile` VARCHAR(255) NULL DEFAULT '' COMMENT '自提联系电话' ,
CHANGE COLUMN `fetchtime` `fetchtime` VARCHAR(255) NULL DEFAULT '' COMMENT '自提时间' ,
CHANGE COLUMN `logo` `logo` VARCHAR(255) NULL DEFAULT '' COMMENT '门点图片' ,
CHANGE COLUMN `saletime` `saletime` VARCHAR(255) NULL DEFAULT '' COMMENT '营业时间' ,
CHANGE COLUMN `desc` `desc` TEXT NULL DEFAULT NULL COMMENT '门店介绍' ,
CHANGE COLUMN `displayorder` `displayorder` INT(11) NULL DEFAULT '0' COMMENT '显示顺序' ,
CHANGE COLUMN `merchid` `merchid` INT(11) NULL DEFAULT '0' COMMENT '商户ID' ;

ALTER TABLE `ims_superdesk_shop_merch_user`
CHANGE COLUMN `regid` `regid` INT(11) NULL DEFAULT '0' COMMENT '商户注册ID' ,
CHANGE COLUMN `groupid` `groupid` INT(11) NULL DEFAULT '0' COMMENT '商户分组ID' ,
CHANGE COLUMN `merchno` `merchno` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '商户编号' ,
CHANGE COLUMN `merchname` `merchname` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '商户名' ,
CHANGE COLUMN `salecate` `salecate` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '销售类别' ,
CHANGE COLUMN `desc` `desc` VARCHAR(500) NOT NULL DEFAULT '' COMMENT '介绍' ,
CHANGE COLUMN `realname` `realname` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '实名' ,
CHANGE COLUMN `mobile` `mobile` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '手机号' ,
CHANGE COLUMN `status` `status` TINYINT(3) NULL DEFAULT '0' COMMENT '状态 1 允许入驻 2 暂停 3 即将到期' ,
CHANGE COLUMN `accounttime` `accounttime` INT(11) NULL DEFAULT '0' COMMENT '服务时间，默认1年' ,
CHANGE COLUMN `diyformdata` `diyformdata` TEXT NULL DEFAULT NULL COMMENT '自定义数据' ,
CHANGE COLUMN `diyformfields` `diyformfields` TEXT NULL DEFAULT NULL COMMENT '自定义字段' ,
CHANGE COLUMN `applytime` `applytime` INT(11) NULL DEFAULT '0' COMMENT '审核时间' ,
CHANGE COLUMN `accounttotal` `accounttotal` INT(11) NULL DEFAULT '0' COMMENT '可以开多少子帐号' ,
CHANGE COLUMN `remark` `remark` TEXT NULL DEFAULT NULL COMMENT '备注' ,
CHANGE COLUMN `jointime` `jointime` INT(11) NULL DEFAULT '0' COMMENT '加入时间' ,
CHANGE COLUMN `accountid` `accountid` INT(11) NULL DEFAULT '0' COMMENT '帐号表ID' ,
CHANGE COLUMN `sets` `sets` TEXT NULL DEFAULT NULL COMMENT '商家基础设置' ,
CHANGE COLUMN `logo` `logo` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '标志' ,
CHANGE COLUMN `payopenid` `payopenid` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '收款人openid' ,
CHANGE COLUMN `payrate` `payrate` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '抽成利率' ,
CHANGE COLUMN `isrecommand` `isrecommand` TINYINT(1) NULL DEFAULT '0' COMMENT '是否推荐' ,
CHANGE COLUMN `cateid` `cateid` INT(11) NULL DEFAULT '0' COMMENT '商户分类ID' ,
CHANGE COLUMN `address` `address` VARCHAR(255) NULL DEFAULT '' COMMENT '地址' ,
CHANGE COLUMN `tel` `tel` VARCHAR(255) NULL DEFAULT '' COMMENT '电话' ,
CHANGE COLUMN `lat` `lat` VARCHAR(255) NULL DEFAULT '' COMMENT '经度' ,
CHANGE COLUMN `lng` `lng` VARCHAR(255) NULL DEFAULT '' COMMENT '纬度' ;

































