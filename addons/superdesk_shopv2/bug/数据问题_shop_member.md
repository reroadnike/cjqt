



正在显示第 150 - 174 行 (共 365 行, 查询花费 0.0077 秒。) [id: 1002 - 910]
SELECT * FROM `ims_superdesk_shop_member` WHERE core_user = 0 AND logintime =0 ORDER BY `ims_superdesk_shop_member`.`id` DESC




------------------------------------数据清理------------------------------------


MySQL 返回的查询结果为空 (即零行)。 (查询花费 0.0025 秒。)
SELECT m.*,FROM_UNixtime(m.createtime),FROM_UNixtime(m.logintime) 
FROM ims_superdesk_shop_member m

WHERE 
	m.core_user = 0 AND m.logintime =0 AND m.uid= 0
     
ORDER BY m.createtime ASC

正在显示第 0 - 10 行 (共 11 行, 查询花费 0.0002 秒。) [createtime: 1516083401 - 1533610417]
SELECT m.*,FROM_UNixtime(m.createtime),FROM_UNixtime(m.logintime) 
FROM ims_superdesk_shop_member m

WHERE 
	m.core_user = 0 AND m.logintime >0 AND m.uid= 0
     
ORDER BY m.createtime ASC
------------------------------------数据清理------------------------------------




------------------------------------待修复------------------------------------
SELECT m.*,FROM_UNixtime(m.createtime),FROM_UNixtime(m.logintime) 
FROM ims_superdesk_shop_member m

WHERE 
	m.mobile = '18827414287' and m.openid not like '%wap_user%'
     
ORDER BY m.createtime ASC

删除了 20 行。 (查询花费 0.0109 秒。)
DELETE FROM `ims_superdesk_shop_member` WHERE mobile = '18827414287' AND logintime = 0


----------------->
正在显示第 0 - 12 行 (共 13 行, 查询花费 0.0029 秒。) [createtime: 1516083400 - 1533610417]

------------------------------------待修复------------------------------------


验证待修复有没有订单

-- 已经没了
SELECT * FROM ims_superdesk_shop_order WHERE openid in ( 
    SELECT m.openid
    FROM ims_superdesk_shop_member m
    WHERE 
    	m.mobile = '18827414287' and m.openid not like '%wap_user%'
    ) and price >1
    
    
    
    
SELECT * FROM ims_superdesk_shop_order WHERE openid in ( 
    SELECT m.openid
    FROM ims_superdesk_shop_member m
    WHERE 
    	m.mobile = '18827414287' and m.openid not like '%wap_user%'
    ) and price >1
    
验证待修复有没有订单


删除不带订单的 shop member

删除之前看一下

SELECT * FROM ims_superdesk_shop_member WHERE openid in (
'oX8KYwvSy722YROfeotESy6FZaho',
'oX8KYwp3lrwiPimMxXXdNE16WxN4',
'oX8KYwpg9EsBS6tW4uYtoLJ0OMSI',
'oX8KYwohWwFF66Y3MsdZc1xzF6Cw',
'oX8KYwg5KsS_UpL6BSwM5cm8-dZ4',
'oX8KYwtPafDVAC2Uv44T7UVYsV84',
'oX8KYwt2M3C5P9ZBsXO-vJ7BELmA',
'oX8KYwqlGL_xrbcoFLwNNtdC4MH0',
'oX8KYwn9Ifm_AsZLtjjXm38k4_S4',
'oX8KYwt_nRpOoZf3nMOMO-Kg2Nys',
'oX8KYwngCpbtIE7fAHlwAIX1lGKI',
'oX8KYwmIi2Fnn16MURtS6i62QUus'
)

DELETE FROM ims_superdesk_shop_member WHERE openid in (
'oX8KYwvSy722YROfeotESy6FZaho',
'oX8KYwp3lrwiPimMxXXdNE16WxN4',
'oX8KYwpg9EsBS6tW4uYtoLJ0OMSI',
'oX8KYwohWwFF66Y3MsdZc1xzF6Cw',
'oX8KYwg5KsS_UpL6BSwM5cm8-dZ4',
'oX8KYwtPafDVAC2Uv44T7UVYsV84',
'oX8KYwt2M3C5P9ZBsXO-vJ7BELmA',
'oX8KYwqlGL_xrbcoFLwNNtdC4MH0',
'oX8KYwn9Ifm_AsZLtjjXm38k4_S4',
'oX8KYwt_nRpOoZf3nMOMO-Kg2Nys',
'oX8KYwngCpbtIE7fAHlwAIX1lGKI',
'oX8KYwmIi2Fnn16MURtS6i62QUus'
)






---- 吕法茂 ----

SELECT * FROM `ims_superdesk_shop_member_address` WHERE id in (726,1479) 

oX8KYwkcrF8L5BcZFqqnfmRotWMI addressid 726 吕法茂 18669821657 重点保 core_user = 3409 项目 145 企业 547

订单 正在显示第 0 - 3 行 (共 4 行, 查询花费 0.0054 秒。)
SELECT *,FROM_UNixtime(createtime) FROM ims_superdesk_shop_order WHERE openid in ( 
    SELECT openid FROM ims_superdesk_shop_member m WHERE m.core_user = 0 AND m.logintime >0 AND m.uid= 0 
    ) AND price >1 AND canceltime = 0
SELECT *,FROM_UNixtime(createtime) FROM ims_superdesk_shop_order WHERE openid in ( 
    SELECT openid FROM ims_superdesk_shop_member m WHERE m.core_user = 3409 AND m.logintime >0 AND m.uid= 0 
    ) AND price >1 AND canceltime = 0

order 已修正
member 已修正
---- 吕法茂 ----

---- 王海婉 ----
oX8KYwsWjVgmDEHCyerScOFhZAUw addressid 1479 王海婉 13662598408 core_user = 4563 (项目 10 企业 63), 10446 (项目 626 企业 2997) 

SELECT *
FROM ims_superdesk_shop_member 

WHERE 
	openid = 'oX8KYwsWjVgmDEHCyerScOFhZAUw'
	
order 已修正
member 已修正



---- 王海婉 ----


---- 不知道 who ----
oX8KYwqihSVFz7wc8Fn01AhzTcpY uid = 

SELECT * FROM ims_mc_mapping_fans WHERE openid = 'oX8KYwqihSVFz7wc8Fn01AhzTcpY'
uid = 4271
---- 不知道 who ----




----冯梅----

oX8KYwh5q-pGzX4pH5DLm2fLYGTU

SELECT * FROM ims_mc_mapping_fans WHERE openid = 'oX8KYwh5q-pGzX4pH5DLm2fLYGTU'
uid = 4244
冯梅	18841167195    1283	6 oX8KYwh5q-pGzX4pH5DLm2fLYGTU 
shop_member id = 115

SELECT * FROM ims_superdesk_core_tb_user WHERE 1 AND userName = '冯梅'
core_user = 3125 (项目 626 企业 1283) 

order 已修正
member 已修正
----冯梅----



订单 正在显示第 0 - 5 行 (共 6 行, 查询花费 0.0017 秒。) 2018-02-05
SELECT *,FROM_UNixtime(createtime) FROM ims_superdesk_shop_order WHERE openid in ( SELECT openid FROM ims_superdesk_shop_member m WHERE m.core_user = 0 AND m.logintime =0 AND m.uid> 0 AND m.realname = '何志伟' ) AND price >1



正在显示第 0 - 9 行 (共 10 行, 查询花费 0.0050 秒。)
SELECT * FROM ims_superdesk_shop_order WHERE openid in ( 
    SELECT openid FROM ims_superdesk_shop_member m WHERE m.core_user = 0 AND m.logintime >0 AND m.uid= 0 
    )


SELECT * FROM ims_superdesk_shop_order WHERE openid in (
	'oX8KYwsWjVgmDEHCyerScOFhZAUw', ---- 有订单 王海婉 
    'oX8KYwkcrF8L5BcZFqqnfmRotWMI', ---- 有订单 吕法茂
    'oX8KYwqVl3YLwEzJ8-mwnFhkbL9s',
    'oX8KYwqihSVFz7wc8Fn01AhzTcpY',
    'oX8KYwh5q-pGzX4pH5DLm2fLYGTU', ---- 有订单
    'oX8KYwuFkpUn0-p91w9p4jNMxeuM'
)



正在显示第 0 - 19 行 (共 20 行, 查询花费 0.0010 秒。)
SELECT * FROM ims_superdesk_shop_member m WHERE m.core_user = 0 AND m.logintime =0 AND m.uid> 0 AND m.realname = '何志伟'

SELECT *,FROM_UNixtime(m.createtime),FROM_UNixtime(m.logintime)  
FROM ims_superdesk_shop_member m 
WHERE m.core_user = 0 AND m.logintime =0 AND m.uid> 0 AND m.realname = '何志伟'

再看一下这些人有没有订单

SELECT * FROM ims_superdesk_shop_order WHERE openid in ( 
SELECT openid
FROM ims_superdesk_shop_member m 
WHERE m.core_user = 0 AND m.logintime =0 AND m.uid> 0 AND m.realname = '何志伟'

)
















正在显示第 0 - 24 行 (共 3766 行, 查询花费 0.0005 秒。)
SELECT * FROM `ims_superdesk_shop_member`




























