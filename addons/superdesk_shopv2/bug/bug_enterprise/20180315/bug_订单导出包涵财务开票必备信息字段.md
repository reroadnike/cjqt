
bug_订单导出包涵财务开票必备信息字段

在原有欧法信商城订单明细基础

// O 京东订单号 ----已增加

````
// O 京东订单号 start
if ($value['ismerch'] == 1 /*是商户*/
    && $value['merchid'] == SUPERDESK_SHOPV2_JD_VOP_MERCHID
) {
    $value['jd_order_id'] = $value['expresssn'] ;
}
// O 京东订单号 end
````
                    
                    
// P 京东协议价 ----已增加 
````
'productprice' => $_GPC['productprice'],// 京东价格
'marketprice' => $_GPC['marketprice'],// 客户购买价格
'costprice' => $_GPC['costprice'],// 协议价格
````


// S 会员所属项目 TODO
````
core_enterprise
````


// AC 发票状态 default 未开票 不开票 TODO

````
invoiceid = 0 不开票 
````


// J 供应商名称 TODO

````
merchid
````


// K 所属项目 与S相同



-------------------------------------------------------------------------
table -- ims_superdesk_shop_category
增加商品编码（京东sku）   ----已增加
商品单位                ----已增加
一二三级分类              ----已增加
财务代码--fiscal_code   ----已增加





发票信息（抬头、识别号、地址、电话、银行、账号） ----已增加


array('title' => '供应商名称', 'field' => 'merchid', 'width' => 12)// J 供应商名称 TODO
array('title' => '所属项目', 'field' => 'core_enterprise_1', 'width' => 12),// K 所属项目 TODO 客户
array('title' => '会员所属项目', 'field' => 'core_enterprise_2', 'width' => 12),// S 会员所属项目 TODO
array('title' => '发票状态', 'field' => 'invoiceid', 'width' => 12),// AC 发票状态 default 未开票 不开票