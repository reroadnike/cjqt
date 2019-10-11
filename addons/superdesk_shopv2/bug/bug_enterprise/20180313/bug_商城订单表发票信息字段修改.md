bug_商城订单表发票信息字段修改

修正
删除 invoicename字段
添加 invoice text 字段
补丁 通过invoiceid 修正 字段invoice值
修正总端,商户,微信相关逻辑


微信端,提交订单,发票相关逻辑修正

此修正要测试,会影响下单流程