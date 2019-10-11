## 已更新到服务器

SELECT * FROM ims_superdesk_shop_member_invoice WHERE invoiceContent = 1

UPDATE ims_superdesk_shop_member_invoice SET invoiceContent = 1 WHERE invoiceContent = 0

ALTER TABLE `ims_superdesk_shop_order` CHANGE `invoicename` `invoice` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' AFTER `invoiceid`;