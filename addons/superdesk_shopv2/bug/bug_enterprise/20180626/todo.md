





bug_订单_财务跟踪_逻辑_还是会有微信支付的没有跟踪记录

修正api:
> https://wxm.avic-s.com/web/index.php?c=site&a=entry&m=superdesk_shopv2&do=web&r=order.finance.addWechatData
api文件
> /data/wwwroot/default/superdesk/addons/superdesk_shopv2/core/web/order/finance.php


bug_订单_拆分单_如果输入的订单号没子单会报错_in_

```
SQL: 
select o.* , a.realname as arealname,a.mobile as amobile,a.province as aprovince ,a.city as acity , a.area as aarea,a.town as atown,a.address as aaddress, d.dispatchname, m.nickname,m.id as mid,m.realname as mrealname,m.mobile as mmobile, sm.id as salerid,sm.nickname as salernickname,s.salername, r.rtype,r.status as rstatus from `ims_superdesk_shop_order` o left join `ims_superdesk_shop_order_refund` r on r.id =o.refundid left join `ims_superdesk_shop_member` m on m.openid=o.openid and m.uniacid = o.uniacid left join `ims_superdesk_shop_member_address` a on a.id=o.addressid left join `ims_superdesk_shop_dispatch` d on d.id = o.dispatchid left join `ims_superdesk_shop_saler` s on s.openid = o.verifyopenid and s.uniacid=o.uniacid left join `ims_superdesk_shop_member` sm on sm.openid = s.openid and sm.uniacid=s.uniacid where o.uniacid = :uniacid AND o.id in () GROUP BY o.id ORDER BY o.createtime ASC

Params: 
array ( ':uniacid' => 16, )
SQL Error: 
You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ') GROUP BY o.id ORDER BY o.createtime ASC' at line 1
Traces: 
file: /framework/class/db.class.php; line: 158; 
file: /framework/function/pdo.func.php; line: 44; 
file: /addons/superdesk_shopv2/core/web/order/list.php; line: 1055; 
file: /addons/superdesk_shopv2/core/model/route.php; line: 158; 
file: /addons/superdesk_shopv2/site.php; line: 21; 
file: /web/source/site/entry.ctrl.php; line: 76; 
file: /web/index.php; line: 172; 
```

bug_订单_拆分单_逻辑_parent_ordersn_children_ordersn_都能查出数据结构


children order sn 

ME20180605165102211363

SELECT * FROM ims_superdesk_shop_order WHERE ordersn = 'ME20180605165102211363'

parent order id 10996

SELECT * FROM ims_superdesk_shop_order WHERE id = 10996

ME20180605165019426969




bug_订单_财务跟踪_搜索_添加_收件人信息_条件

bug_订单_财务跟踪_以搜索结果为导向_批量编辑_04160415

bug_订单_财务跟踪_记录最后的修改时间与修改人

