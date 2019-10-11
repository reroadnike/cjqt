## 已更新到服务器

-- 采购经理审核表添加
--添加申请人名称,添加申请人电话,修改审核人openid字段名称,添加审核人名称,添加审核人电话,添加订单金额
ALTER TABLE `ims_superdesk_shop_order_examine`
ADD `realname` VARCHAR(20) DEFAULT '' COMMENT '申请人名称' AFTER `openid`,
ADD `mobile` VARCHAR(11) DEFAULT '' COMMENT '申请人电话' AFTER `realname`,
CHANGE `manager` `manager_openid` VARCHAR(50) DEFAULT '' COMMENT '采购经理openid',
ADD `manager_realname` VARCHAR(20) DEFAULT '' COMMENT '审核人名称' AFTER `manager_openid`,
ADD `manager_mobile` VARCHAR(11) DEFAULT '' COMMENT '审核人电话' AFTER `manager_realname`,
ADD `price` decimal(10,2) DEFAULT '0' COMMENT '订单金额' AFTER `parent_order_id`;


--消息模板表插入两条数据
INSERT INTO `ims_superdesk_shop_member_message_template_type`
(`id`,`name`, `typecode`, `templatecode`, `templatename`, `content`, `typegroup`, `groupname`)
VALUES
('28','订单提交审核通知', 'examine_new', 'OPENTM410946800','订单提交审核通知','{{first.DATA}}单号：{{keyword1.DATA}}金额：{{keyword2.DATA}}采购人：{{keyword3.DATA}}供应商：{{keyword4.DATA}}采购时间：{{keyword5.DATA}}{{remark.DATA}}','sys','系统消息通知'),
('29','订单审核结果通知', 'examine_result', 'OPENTM207196912','订单审核结果通知','{{first.DATA}}订单时间：{{keyword1.DATA}}订单金额：{{keyword2.DATA}}收货地址：{{keyword3.DATA}}{{remark.DATA}}','sys','系统消息通知');
