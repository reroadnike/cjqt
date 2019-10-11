-- 订单商品表进货价补丁.  还写了一个程序层面的补丁.superdesk_jd_vop->patch_superdesk_shop_order_goods_insert_costprice
update ims_superdesk_shop_order_goods as og
LEFT JOIN ims_superdesk_shop_goods as g on og.goodsid = g.id
set og.costprice = IFNULL(g.costprice,0)
where og.costprice = 0