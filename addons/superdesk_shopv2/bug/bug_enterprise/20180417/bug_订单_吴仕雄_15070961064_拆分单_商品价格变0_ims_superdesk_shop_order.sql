SELECT *
FROM `ims_superdesk_shop_order`
where expresssn = '72915601854'
		or expresssn = '72958472952'
		or expresssn = '72952727574'
        or expresssn = '72952549584'
        or expresssn = '72952652319'
        or expresssn = '72952611831'
        or expresssn = '72952552850'
        or expresssn = '72952805073'
        or expresssn = '72957466266'
order by id
;

SELECT *
FROM `ims_superdesk_shop_order`
where sendtime = 1523515247;

SELECT id,createtime , deleted
FROM `ims_superdesk_shop_order`
where id  = 756
		or id  = 838
		or id  = 839
		or id  = 843
        or id  = 844 ;