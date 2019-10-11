SELECT * FROM `ims_superdesk_shop_goods` WHERE merchid = 40 and status = 1


济南艺瑕商贸有限公司 [40]

新加商户商品updatetime是没问题的,则能同步

发现商户分类未设置  

搜索关键字问题

要先加关键词,再录入商品


补

UPDATE ims_superdesk_shop_goods set updatetime = UNIX_TIMESTAMP() WHERE deleted = 0 and merchid = 40 and status = 1

影响了 7 行。 (查询花费 0.0364 秒。)