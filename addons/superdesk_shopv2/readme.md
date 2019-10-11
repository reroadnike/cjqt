

# siteroot
http://wxm.avic-s.com/



# 成为营销商
app/index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=commission.register

# 商家进驻
app/index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=merch.register

# PC端
app/index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=pc



# 补丁


## 财务跟踪

> v1 修正 应林贝要求添加5月1日之后的微信订单到财务跟踪表
* http://localhost/superdesk/web/index.php?c=site&a=entry&m=superdesk_shopv2&do=web&r=order.finance.addWechatData
* https://wxm.avic-s.com/web/index.php?c=site&a=entry&m=superdesk_shopv2&do=web&r=order.finance.addWechatData

> v2 修正

```
// v2版本
// 余额支付
// 单商户 | 二级订单
$order_credit_no_parent = pdo_fetchall(
    ' select o.id,o.ordersn,o.merchid ' .
    ' from ' . tablename('superdesk_shop_order') . ' as o ' .
    ' left join ' . tablename('superdesk_shop_order_finance') . ' as ofi on ofi.orderid=o.id ' .
    ' where o.createtime > 1525104000 ' .
    '       and o.paytype = 1 ' . // 余额支付
    '       and o.isparent=0 ' .
    '       and o.parentid=0 '.
    '       and ofi.id is null '     //没有财务跟踪记录的
);

// v2版本
// 余额支付
// 多商户拆分 | 内部拆单 二级订单
$order_credit_have_parent = pdo_fetchall(
    ' select o.id,o.ordersn,o.merchid ' .
    ' from ' . tablename('superdesk_shop_order') . ' as o ' .
    ' left join ' . tablename('superdesk_shop_order') . ' as po on po.id=o.parentid ' .
    ' left join ' . tablename('superdesk_shop_order_finance') . ' as ofi on ofi.orderid=o.id ' .
    ' where o.createtime > 1525104000 ' .   //大于5月1日的
    '       and o.paytype = 1 ' .   // 余额支付
    '       and po.isparent = 1 ' .   // 父订单的 po.isparent=1 必定为一级订单 | isparent 为0代表不是1级订单,parentid>0代表有拆分,
    '       and o.isparent = 0 '.     // 必定是 二级订单
    '       and o.parentid > 0 '.     // 必定是 二级订单
    '       and ofi.id is null '     //没有财务跟踪记录的
);

// v2版本
// 微信支付
// 多商户拆分 | 内部拆单 二级订单
$order_wechat_have_parent = pdo_fetchall(
    ' select o.id,o.ordersn,o.merchid ' .
    ' from ' . tablename('superdesk_shop_order') . ' as o ' .
    ' left join ' . tablename('superdesk_shop_order') . ' as po on po.id=o.parentid ' .
    ' left join ' . tablename('superdesk_shop_order_finance') . ' as ofi on ofi.orderid=o.id ' .
    ' where o.createtime > 1525104000 ' .   //大于5月1日的
    '       and o.paytype = 21 ' .   // 微信支付
    '       and po.isparent = 1 ' .   // 父订单的 po.isparent=1 必定为一级订单 | isparent 为0代表不是1级订单,parentid>0代表有拆分,
    '       and o.isparent = 0 '.     // 必定是 二级订单
    '       and o.parentid > 0 '.     // 必定是 二级订单
    '       and ofi.id is null '     //没有财务跟踪记录的
);

$order = array_merge($order_wechat_have_parent,$order_credit_no_parent,$order_credit_have_parent);
```