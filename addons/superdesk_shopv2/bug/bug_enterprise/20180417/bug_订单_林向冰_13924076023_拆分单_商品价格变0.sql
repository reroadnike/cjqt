

1402 ---- 72973756535 ---- ME20180413171402852208 ---- 1523586753
1433 ---- 72991762229 ---- ME20180413171402852208 ---- 1523586753
1434 ---- 72991989629 ---- ME20180413171402852208 ---- 1523586753
1435 ---- 72991801428 ---- ME20180413171402852208 ---- 1523586753

SELECT *
FROM `ims_superdesk_shop_order`
where id  = 1435
		
        
        
SELECT *
FROM `ims_superdesk_shop_order`
where id = 1402 or parentid = 1402 


SELECT *
FROM `ims_superdesk_shop_order_goods`
where orderid  = 1402
		or orderid  = 1433
		or orderid  = 1434
		or orderid  = 1435
order by orderid asc ,id asc
;


delete FROM `ims_superdesk_shop_order_goods`
where price = 0.00 and (orderid  = 1402
		or orderid  = 1433
		or orderid  = 1434
		or orderid  = 1435)


-- 本来是　1523586754


update `ims_superdesk_shop_order_goods` 
set createtime = 1523586753 
where orderid  = 1402
		or orderid  = 1433
		or orderid  = 1434
		or orderid  = 1435


