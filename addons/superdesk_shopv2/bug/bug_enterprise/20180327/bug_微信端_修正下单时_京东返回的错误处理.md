

bug_微信端_修正下单时_京东返回的错误处理

//success false;
//resultCode 3004;
//resultMessage [5729524]商品已下架，不能下单 ;


//success false;
//resultCode 3008;
//resultMessage 编号为2586166的赠品无货，主商品为:929693;

3.10 查询赠品信息接口
URL: https://bizapi.jd.com/api/product/getSkuGift
HTTPS请求方式：POST
请求参数
 参数名称	参数选项	意义 
token	 必须	 授权时获取的access token
skuId	必须	商品编号，只支持单个查询
province	必须	京东一级地址编号
city	必须	京东二级地址编号
county	必须	京东三级地址编号
town	必须	京东四级地址编号，存在四级地址必填，若不存在，则填0
返回结果
{
    "success": true,
    "resultMessage": "",
    "resultCode": null,
    "result": {
        "gifts": [
            {
                "skuId": 980693,
                "num": 1,
                "giftType": 1
            },
            {
                "skuId": 1273115,
                "num": 1,
                "giftType": 2
            },
            {
                "skuId": 1273104,
                "num": 1,
                "giftType": 2
            },
            {
                "skuId": 801392,
                "num": 1,
                "giftType": 2
            },
            {
                "skuId": 1117823,
                "num": 1,
                "giftType": 2
            }
        ],
        "maxNum": 0,
        "minNum": 0,
        "promoStartTime": 1427083126357,
        "promoEndTime": 1451632141000
    }
}
gifts为赠品附件列表，其中
num：赠品数量
giftType属性：1附件 2赠品
maxNum：赠品要求最多购买数量（为0表示没配置）
minNum：赠品要求最少购买数量 （为0表示没配置）
promoStartTime：促销开始时间
promoEndTime：促销结束时间
购买数量大于赠品要求最多购买数量，不加赠品
购买数量小于赠品要求最少购买数量，不加赠品
下单时间不在促销时间范围内，不加赠品
需要计算赠品量的倍数=主商品/促销要求主商品最少数（minNum为0时，赠品数量倍数为主商品数量）

//ME20180327105258974110
//success false;
//resultCode 3009;
//resultMessage 【4838382】商品在该配送区域内受限;

3.8 商品区域购买限制查询
URL: https://bizapi.jd.com/api/product/checkAreaLimit
HTTPS请求方式：POST
请求参数
 参数名称	参数选项 	意义 
 token	必须	 授权时获取的access token
skuIds	必须	商品编号，支持批量，以’,’分隔  (最高支持100个商品)
province	必须	京东一级地址编号
city	必须	京东二级地址编号
county	必须	京东三级地址编号
town	必须	京东四级地址编号


返回结果
 参数名称	意义 
success	检查是否成功，true:成功；false:失败
resultMessage	若检查失败，则该字段会显示失败原因
resultCode	错误码，暂未使用，默认为null
result	若检查成功，则该字段显示商品区域限制检测结果，检验结果格式如下：
result:[
{skuId:12345,isAreaRestrict:true},
{skuId:12344,isAreaRestrict:false},
{skuId:12345,isAreaRestrict:false}
]
说明
skuId  商品编号
isAreaRestrict true 代表区域受限 false 区域不受限

检测成功返回示例：
{"success":true,"
resultMessage":"",
"resultCode":null,"
result":"[{\"skuId\":102194,\"isAreaRestrict\":true}]"}
{"success":true,"
resultMessage":"","
resultCode":null,"
result":"[{\"skuId\":102194,\" isAreaRestrict \":true},{\"skuId\":107164,\" isAreaRestrict \":false}]"}
用户不具备该商品购买权限：
{"success":true,
"resultMessage":"您不具有如下商品购买权限skuIds:[\"102194\"]",
"resultCode":null,
"result":null
}
{"success":true,"
resultMessage":"您不具有如下商品购买权限skuIds:[1,2,3]",
"resultCode":null,
"result":"[{\"skuId\":107164,\"isAreaRestrict\":false}]"
}
检测失败返回示例：
参数有误：
{"success":false,"resultMessage":"待检查的skuIds输入为空! 省份信息输入不能为空! 市级信息输入不能为空! 县级信息输入不能为空!","resultCode":null,"result":null}
校验商品数量超过规定数量
{"success":false,"resultMessage":"待检测商品数量超过100个！","resultCode":null,"result":null}


//success false;
//resultCode 3017;
//resultMessage 您的余额不足;
        
        
这些问题在微信端与后端会体现为

订单跟踪
处理时间	处理信息	操作人
2018-03-27 14:37:31	订单号不能为空	系统
