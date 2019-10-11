# 已记录PM

20180718_订单审批_采购经理显示问题


superdesk_shop_order_examine



SELECT orderid, COUNT(orderid) as c_order_id FROM ims_superdesk_shop_order_examine GROUP BY orderid HAVING c_order_id > 1




# 过滤出大于1条记录的
```
SELECT a.orderid from (
SELECT orderid, COUNT(orderid) as c_order_id FROM ims_superdesk_shop_order_examine GROUP BY orderid HAVING c_order_id > 1
) a
```



# 通过过滤的带出审核 记录明细
```
SELECT * FROM ims_superdesk_shop_order_examine WHERE orderid in (SELECT a.orderid from (
                                                                 SELECT orderid, COUNT(orderid) as c_order_id FROM ims_superdesk_shop_order_examine GROUP BY orderid HAVING c_order_id > 1
                                                                 ) a)
```

# 结论

要做一个从tb_user2shopmember的补丁程序 主要是修正 userName2realname 的问题(可以是不做的)

开启 
> /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/inc/mobile/superdesk_shop_tb_user_2_member.inc.php

当超级前台有修改用户 1分钟后要同步修改 主要是要加上 userName2realname

[]()