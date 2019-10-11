



request static/js/app/core.js:
url:./index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=order.create.caculate&mid=94
type:post
data:{"totalprice":null,"addressid":"621","dflag":0,"goods":[{"goodsid":"1433318","total":"1","optionid":"0","marketprice":"61.95","merchid":"8","cates":"13751","discounttype":0,"isdiscountprice":0,"discountprice":0,"isdiscountunitprice":0,"discountunitprice":0},{"goodsid":"1433317","total":"1","optionid":"0","marketprice":"55.65","merchid":"8","cates":"13751","discounttype":0,"isdiscountprice":0,"discountprice":0,"isdiscountunitprice":0,"discountunitprice":0}]}












request

url:./index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=order.create.caculate

type:post

data:

{
"totalprice": 2538.72,
"addressid": "2",
"dflag": 0,
"goods": [
{
"goodsid": "207",
"total": "1",
"optionid": "0",
"marketprice": "67.20",
"merchid": "1",
"cates": "8",
"discounttype": 2,
"isdiscountprice": 0,
"discountprice": 0,
"isdiscountunitprice": 0,
"discountunitprice": 0
},
{
"goodsid": "202",
"total": "2",
"optionid": "0",
"marketprice": "1235.76",
"merchid": "1",
"cates": "3",
"discounttype": 2,
"isdiscountprice": 0,
"discountprice": 0,
"isdiscountunitprice": 0,
"discountunitprice": 0
}
]
}


response

{
"status": 1,
"result": {
"price": 0,
"couponcount": 1,
"realprice": 2538.72,
"deductenough_money": 0,
"deductenough_enough": 0,
"deductcredit2": 0,
"deductcredit": 0,
"deductmoney": 0,
"taskdiscountprice": 0,
"discountprice": 0,
"isdiscountprice": 0,
"merch_showenough": null,
"merch_deductenough_money": null,
"merch_deductenough_enough": null,
"merchs": {
"1": {
"merchid": 1,
"goods": [
"207",
"202"
],
"ggprice": 2538.72
}
},
"buyagain": 0,
"isnodispatch": 0,
"nodispatch": "",
"url": "http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=order.create"
}
}