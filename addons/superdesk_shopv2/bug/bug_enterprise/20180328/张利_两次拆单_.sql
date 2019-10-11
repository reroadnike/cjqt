-- 表修正

ALTER TABLE `ims_superdesk_jd_vop_order_submit_order_sku`
ADD `shop_order_id` INT(11) NOT NULL DEFAULT '0' COMMENT '商城订单ID' AFTER `oid`,
ADD `shop_order_sn` VARCHAR(64) NOT NULL DEFAULT '' COMMENT 'shop_order_sn' AFTER `shop_order_id`,
ADD `shop_goods_id` INT(11) NOT NULL DEFAULT '0' COMMENT '商城商品ID' AFTER `shop_order_sn`;

ALTER TABLE `ims_superdesk_shop_order_goods`
ADD `parent_order_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'parent_order_id' AFTER `uniacid`;



SELECT *
FROM `ims_superdesk_jd_vop_order_submit_order`
WHERE jd_vop_result_jdOrderId = '73529866606'
		or jd_vop_result_jdOrderId = '73651713326'
		or jd_vop_result_jdOrderId = '73651919682'
        or jd_vop_result_jdOrderId = '73652235650'
        or jd_vop_result_jdOrderId = '73652149646';


SELECT *
FROM `ims_superdesk_jd_vop_order_submit_order_sku`
WHERE pOrder = '73529866606'
		or jdOrderId = '73651713326'
		or jdOrderId = '73651919682'
		or jdOrderId = '73652235650'
		or jdOrderId = '73652149646'
order by jdOrderId asc


-- 做数据
--  * 张利
--  * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_product_get_detail_one&page_num=0&sku=6433455
--  * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_product_get_detail_one&page_num=0&sku=4296630
--  * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_product_get_detail_one&page_num=0&sku=6034416


update ims_superdesk_shop_goods set id = 296958 where id = 168158;
update ims_superdesk_shop_goods set id = 269038 where id = 168160;
update ims_superdesk_shop_goods set id = 219199 where id = 168159;


-- 756
-- 838
-- 839
-- 843
-- 844

SELECT *
FROM `ims_superdesk_shop_order`
where expresssn  = '73529866606'
		or expresssn = '73651713326'
		or expresssn  = '73651919682'
		or expresssn  = '73652235650'
		or expresssn  = '73652149646' ;

SELECT id,createtime , deleted
FROM `ims_superdesk_shop_order`
where id  = 756
		or id  = 838
		or id  = 839
		or id  = 843
        or id  = 844 ;

update `ims_superdesk_shop_order`  set parentid = 756 where id = 838;

-- 原始
-- orderid
-- 756
-- ordersn
-- ME20180323170122806142

SELECT *
FROM `ims_superdesk_shop_order`
where ordersn  = 'ME20180323170122806142' ;


SELECT *
FROM `ims_superdesk_shop_order_goods`
where orderid  = 756
		or orderid  = 838
		or orderid  = 839
		or orderid  = 843
		or orderid  = 844 ;

delete from `ims_superdesk_shop_order_goods` where id = 1282 or id = 1281 ;

SELECT *
FROM `ims_superdesk_shop_order_goods`
where orderid  = 0 ;

SELECT *
FROM `ims_superdesk_shop_order_goods`
where createtime  = 1521795682 ;

select id from `ims_superdesk_shop_order` where parentid = 756 and id NOT IN(839,843,844);



update `ims_superdesk_shop_order` set deleted = 1 where parentid = 756  and id NOT IN(839,843,844);

        