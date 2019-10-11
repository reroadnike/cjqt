















# sync

ALTER TABLE `ims_superdesk_boardroom` CHANGE `equipment` `equipment` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '设备 序列化的设备编号数组';

ALTER TABLE `ims_superdesk_boardroom_appointment` CHANGE `state` `status` INT(1) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功';

ALTER TABLE `ims_superdesk_boardroom_appointment`
ADD `paydetail` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '支付详情' AFTER `uniacid`,
ADD `paytype` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '1为余额，2为在线，3为到付' AFTER `paydetail`;















# sync

ALTER TABLE `ims_superdesk_boardroom_s_order_goods` ADD `out_trade_no` VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'out_trade_no' AFTER `orderid`;















# sync


ALTER TABLE `ims_superdesk_boardroom_s_order` CHANGE `transid` `transaction_id` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '微信支付单号';
ALTER TABLE `ims_superdesk_boardroom_s_order` CHANGE `ordersn` `out_trade_no` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'out_trade_no';




ALTER TABLE `ims_superdesk_boardroom_appointment`

ADD `out_trade_no` VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'out_trade_no' AFTER `uniacid`,
ADD `transaction_id` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '微信支付订单号' AFTER `out_trade_no`,

ADD `situation` TEXT NOT NULL COMMENT 'situation JSON' AFTER `transaction_id`,
ADD `price` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '成交价' AFTER `situation`,
ADD `quantity` DECIMAL(10,1) NOT NULL DEFAULT '0' COMMENT '数量/小时' AFTER `price`,
ADD `total` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '成交总价' AFTER `quantity`,

ADD `lable_ymd` VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'eg:2017-09-09' AFTER `total`,
ADD `lable_time` VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'eg:00:30-12:00' AFTER `lable_ymd`;

















# sync




ALTER TABLE `ims_superdesk_boardroom` CHANGE `deleted` `enabled` TINYINT(4) NOT NULL DEFAULT '1' COMMENT 'Enabled';
ALTER TABLE `ims_superdesk_boardroom_equipment` CHANGE `deleted` `enabled` TINYINT(4) NOT NULL DEFAULT '1' COMMENT 'Enabled';

ALTER TABLE `ims_superdesk_boardroom` CHANGE `images` `thumb` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '图片';