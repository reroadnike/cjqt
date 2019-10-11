7.6 订单反查接口

URL: https://bizapi.jd.com/api/order/selectJdOrderIdByThirdOrder
HTTPS 请求方式：POST
	请求参数

参数名	类型	参数选项	描述
token	String	必填	授权时获取的 access token
thirdOrder	String	必填	客户系统订单号

	返回结果

参数名		类型	描述
result	String	京东订单号

	返回示例

说明：如果 success 为 true 则代表下单成功，success 为 false，则代表京东订单号不存在。
{
"success":true, "resultMessage":null, "resultCode":null, "result":"4656565656"
}

Page 63
# 背景：由于下单反馈超时时，有可能已下单成功，也有可能下单失败，需要反查查看实际情况。 使用场景：
调用下单接口下单时，当反馈抄送时，需要调用反馈查接口查询订单实际处理情况 当反查接口反馈 true 时，关联申请单，无需再次下单接口下单
当反查接口反馈 false 时，需要重新调下单接口下单，并关联审批单。
