

1536681600


SELECT COUNT(createtime) as num ,createtime, GROUP_CONCAT(id) as ids FROM `ims_superdesk_shop_order` wh group by createtime HAVING num >1



SELECT COUNT(createtime) as num ,createtime, GROUP_CONCAT(id) as ids 
FROM `ims_superdesk_shop_order` 
WHERE createtime > 1536681600 
group by createtime HAVING num >1


SELECT 
	COUNT(createtime) as num ,createtime, 
    GROUP_CONCAT(id) as ids , 
    GROUP_CONCAT(expresssn) as expresssns ,
    GROUP_CONCAT(express) as expresss
FROM `ims_superdesk_shop_order` 
WHERE  parentid = 0

group by createtime HAVING num >1



测试 
77823801917

SELECT * FROM `ims_superdesk_shop_order` WHERE ordersn = 'ME20180912091440204734'



Array
(
    [1] => 77818866002 d
    [2] => 77818322556 d
    [3] => 77819200852 d
    [4] => 77820352989 d
    [7] => 77820123199 d
    [9] => 77826681594 d
    [11] => 77823223863 d
    [12] => 77828022106 d
    [13] => 77816723929 d
    [14] => 77823596272 d
    [15] => 77822708127 d
    [16] => 77817465689 d
    [17] => 77823008351 d
    [18] => 77824208213 d
    [19] => 77823213533 d
    [20] => 77824422259 d
    [21] => 77824414133 d
    [22] => 77824391094 d
    [23] => 77809671707 d
    [25] => 77824090846 d
    [26] => 77826235474 d
    [27] => 77831198138 d
    [29] => 77834502648 d
    [30] => 77830549267 d
    [31] => 77830448119 d
    [32] => 77830833200 d
)



礼包销售量排名统计

SELECT COUNT(packageid) num , packageid FROM `ims_superdesk_shop_order` WHERE packageid >0 GROUP BY packageid ORDER BY num DESC


SELECT a.*,b.title FROM (SELECT COUNT(packageid) num , packageid FROM `ims_superdesk_shop_order` WHERE packageid >0 GROUP BY packageid ORDER BY num DESC) as a LEFT JOIN ims_superdesk_shop_package as b ON a.packageid = b.id

SELECT a.*,b.title FROM (
    SELECT COUNT(packageid) num , packageid FROM `ims_superdesk_shop_order` 
    WHERE packageid >0 AND status > 0
    GROUP BY packageid 
    ORDER BY num DESC
) as a LEFT JOIN ims_superdesk_shop_package as b ON a.packageid = b.id


采购金额排名统计

SELECT openid , SUM(price) as total_price FROM ims_superdesk_shop_order WHERE parentid = 0 and packageid > 0 GROUP BY openid ORDER BY total_price DESC


SELECT a.*,b.realname FROM (SELECT openid , SUM(price) as total_price FROM ims_superdesk_shop_order WHERE parentid = 0 and packageid > 0 GROUP BY openid ORDER BY total_price DESC) as a LEFT JOIN ims_superdesk_shop_member as b on a.openid = b.openid 

SELECT a.*,b.realname FROM (
    SELECT openid , SUM(price) as total_price FROM ims_superdesk_shop_order 
    WHERE parentid = 0 and packageid > 0 AND status > 0
    GROUP BY openid 
    ORDER BY total_price DESC
) as a LEFT JOIN ims_superdesk_shop_member as b on a.openid = b.openid