SELECT *
FROM `ims_superdesk_shop_order_goods`
where orderid  = 1335
		or orderid  = 1378
		or orderid  = 1372
		or orderid  = 1377
		or orderid  = 1371 
        or orderid  = 1376
		or orderid  = 1373
		or orderid  = 1374
		or orderid  = 1375 
order by order id asc , id asc
;




delete FROM `ims_superdesk_shop_order_goods`
where price = 0.00 and (orderid  = 1402
		or orderid  = 1433
		or orderid  = 1434
		or orderid  = 1435)