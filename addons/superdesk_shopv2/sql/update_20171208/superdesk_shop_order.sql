

ALTER TABLE `ims_superdesk_shop_order`
ADD `invoiceid` INT(11) NOT NULL DEFAULT '0' COMMENT '发票ID' AFTER `verifycodes`;