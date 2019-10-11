## 已更新到服务器

ALTER TABLE `ims_superdesk_shop_goods`
ADD INDEX `idx_union1` (`uniacid`, `status`, `deleted`, `minprice`, `checked`, `displayorder`,`createtime`) ;


ALTER TABLE `ims_superdesk_shop_goods`
ADD INDEX `idx_union2` (`uniacid`, `status`, `deleted`, `checked`, `displayorder`,`createtime`) ;











