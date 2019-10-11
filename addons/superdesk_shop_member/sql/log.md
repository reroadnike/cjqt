欧法信商城

全部商品
select count(0) FROM sll_goods WHERE status = 1 ; 
;上架
;444341

select count(0) FROM sll_goods WHERE status = 0 ; 
;下架
;7448

select count(0) FROM sll_goods WHERE status = 400 ;
;删除
;565

select count(0) FROM sll_goods WHERE status = 2 ;
;审核不通过
;0


京东商品
select count(0) FROM sll_goods WHERE status = 1 and sku is not NULL ;
;443678

select count(0) FROM sll_goods WHERE status = 0 and sku is not NULL ;
;7278

select count(0) FROM sll_goods WHERE status = 400 and sku is not NULL ;
;506

select count(0) FROM sll_goods WHERE status = 2 and sku is not NULL ;
;0