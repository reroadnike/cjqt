清除了列表更新价格

快了8s ,加了索引,快了2s


ALTER TABLE `ims_superdesk_shop_goods`
ADD INDEX `idx_union1` (`uniacid`, `status`, `deleted`, `minprice`, `checked`, `displayorder`,`createtime`) ;


ALTER TABLE `ims_superdesk_shop_goods`
ADD INDEX `idx_union2` (`uniacid`, `status`, `deleted`, `checked`, `displayorder`,`createtime`) ;