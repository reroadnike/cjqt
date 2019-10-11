


bug_订单_葛东晓_15801523086_拆分单_商品价格变0

葛东晓
15801523086

2018-04-10
10:44:42



1523328282

SELECT * FROM `ims_superdesk_shop_order` where createtime = 1523328282

查m
SELECT parentid , createtime FROM `ims_superdesk_shop_order` where expresssn = '73941669995'
SELECT * FROM `ims_superdesk_shop_order` where id = '1282'

m ---- 
1282 ---- ME20180410104442287758 ---- 72859523157 ---- 1180.06
s ----
1327 ---- ME20180411163203450281 ---- 73941669995
1328 ---- ME20180411163205801676 ---- 73941577614 --- 0.00*1 信发（TRNFA）328 自粘性标签贴纸 不干胶口取纸价格资料分类纸 蓝色38*25mm（65张）5908543
1329 ---- ME20180411163205661866 ---- 73941605806
1330 ---- ME20180411163205082446 ---- 73941709603
1331 ---- ME20180411163206827222 ---- 73941849152
1332 ---- ME20180411163206084354 ---- 73941744584 --- 0.00*1 信发（TRNFA）314 不干胶口取纸价格图书分类纸 自粘性标签贴纸（蓝色65张）29*20mm 5908545



SELECT *
FROM `ims_superdesk_shop_order_goods`
where orderid  = 1282
        or orderid  = 1327
        or orderid  = 1328
        or orderid  = 1329
        or orderid  = 1330
        or orderid  = 1331
        or orderid  = 1332
order by orderid asc ,id asc
;

SELECT *
FROM `ims_superdesk_jd_vop_order_submit_order_sku`
WHERE pOrder = '72859523157'
        or jdOrderId = '73941669995'
        or jdOrderId = '73941577614'
        or jdOrderId = '73941605806'
        or jdOrderId = '73941709603'
        or jdOrderId = '73941849152'
        or jdOrderId = '73941744584'
order by shop_order_id ASC



delete FROM `ims_superdesk_shop_order_goods`
where price = 0.00 and (
    orderid  = 1282
            or orderid  = 1327
            or orderid  = 1328
            or orderid  = 1329
            or orderid  = 1330
            or orderid  = 1331
            or orderid  = 1332
)