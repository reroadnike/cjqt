

SELECT SUM(price) , member_enterprise_id

FROM `ims_superdesk_shop_order`

WHERE createtime > 1545580800 and paytype = 11 and canceltime is null and status = 1

GROUP BY member_enterprise_id







SELECT *

FROM `ims_superdesk_shop_order`

WHERE createtime > 1545580800 and paytype = 11 and canceltime is null and status = 1


正在显示第 0 - 24 行 (共 1086 行, 查询花费 0.0012 秒。)





SELECT *

FROM `ims_superdesk_shop_order`

WHERE createtime > 1545580800 and paytype = 11 and canceltime is null and status = 1 and parentid > 0

