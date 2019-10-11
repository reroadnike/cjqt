


SELECT parent_order_id , GROUP_CONCAT(distinct orderid) 
FROM ims_superdesk_shop_order_goods 
WHERE 
parent_order_id >0 
and merchid = 8
and createtime > 0
GROUP BY parent_order_id



SELECT * FROM ims_superdesk_shop_order 
WHERE id = 756 
or id in (844,839)
ORDER by id ASC