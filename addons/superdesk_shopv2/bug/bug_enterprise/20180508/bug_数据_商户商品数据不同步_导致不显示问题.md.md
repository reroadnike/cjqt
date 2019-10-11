

bug_数据_商户商品数据不同步_导致不显示问题.md

13793113117

http://www.avic-s.com/plugins/app/index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&userMobile=13793113117

UNIX_TIMESTAMP() ---- 1525753885

NOW() ---- 2018-05-08 12:31:43

UPDATE ims_superdesk_shop_goods set updatetime = UNIX_TIMESTAMP() WHERE merchid = 35 and updatetime = 0


SELECT * from ims_superdesk_shop_goods WHERE merchid = 35




SELECT * from ims_superdesk_shop_goods WHERE updatetime = 0


SELECT SQL_CALC_FOUND_ROWS * from ims_superdesk_shop_goods WHERE updatetime =0 LIMIT 1


SELECT * FROM ims_superdesk_shop_goods WHERE deleted = 0 and jd_vop_sku = 0 and updatetime = 0

-- 强制更新一下非京东商户
UPDATE ims_superdesk_shop_goods set updatetime = UNIX_TIMESTAMP() WHERE deleted = 0 and jd_vop_sku = 0 and updatetime = 0

UPDATE ims_superdesk_shop_goods set updatetime = UNIX_TIMESTAMP() WHERE deleted = 0 and jd_vop_sku = 0

影响了 493 行。 (查询花费 53.4491 秒。)



-- 强制更新一下京东商户
UPDATE ims_superdesk_shop_goods set updatetime = UNIX_TIMESTAMP() WHERE deleted = 0 and updatetime = 0

影响了 162938 行。 (查询花费 43.2271 秒。)

山东赛迪贸易有限公司 [25]

SELECT FROM_UNIXTIME(updatetime) FROM ims_superdesk_shop_goods WHERE merchid=25 ORDER BY updatetime DESC

济南塔头商贸有限公司 [46]

SELECT FROM_UNIXTIME(updatetime) FROM ims_superdesk_shop_goods WHERE merchid=46 ORDER BY updatetime DESC


java.lang.OutOfMemoryError: Java heap space
Dumping heap to java_pid11751.hprof ...
Heap dump file created [1759059078 bytes in 24.035 secs]
Error: Your application used more memory than the safety cap of 1G.
Specify -J-Xmx####M to increase it (#### = cap size in MB).

1529424785


SELECT * FROM `ims_superdesk_shop_goods` WHERE jd_vop_sku = 3761626 or jd_vop_sku = 2828950