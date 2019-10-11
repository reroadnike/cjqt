<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 1/29/18
 * Time: 11:01 AM
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_order_select_jd_order
 */

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/OrderService.class.php');
$_orderService = new OrderService();

//$response = $_orderService->selectJdOrder(71411883073);// JD拆单
//$response = $_orderService->selectJdOrder(70703127395);// 舒 小老板 Taokaenoi 泰国进口 零食 即食 海苔片 香辣味2g*8




// 张利 bug 多次拆单

//拆单信息1
//{"id":4970881176,"result":{"pOrder":73529866606},"time":"2018-03-28 11:23:04","type":1}
//73529866606->(73651713326,73651919682)
//
//
//
//拆单信息2
//{"id":4971067753,"result":{"pOrder":73529866606},"time":"2018-03-28 11:31:35","type":1}
//73529866606->(73651919682,73652235650,73652149646)



//$response = $_orderService->selectJdOrder(73529866606);

// 张利 bug 多次拆单


$response = $_orderService->selectJdOrder(72973756535);





die(json_encode($response , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));


// JD拆单
//{
//    "success": true,
//    "resultMessage": "",
//    "resultCode": "0000",
//    "result": {
//    "pOrder": {
//        "jdOrderId": 71411883073,
//            "freight": 0,
//            "orderPrice": 283.82,
//            "orderNakedPrice": 242.59,
//            "sku": [
//                {
//                    "category": 7371,
//                    "num": 2,
//                    "price": 8.05,
//                    "tax": 17,
//                    "oid": 0,
//                    "name": "得力(deli)76×76mm\/400页便签纸\/便签本\/便利贴\/百事贴\/易事贴4色 7151",
//                    "taxPrice": 1.17,
//                    "skuId": 1140673,
//                    "nakedPrice": 6.88,
//                    "type": 0
//                },
//                {
//                    "category": 1671,
//                    "num": 5,
//                    "price": 49.9,
//                    "tax": 17,
//                    "oid": 0,
//                    "name": "洁柔（C&S）卷纸 蓝精品3层140g卫生纸*27卷（细腻光滑 整箱销售）",
//                    "taxPrice": 7.25,
//                    "skuId": 1495690,
//                    "nakedPrice": 42.65,
//                    "type": 0
//                },
//                {
//                    "category": 2603,
//                    "num": 2,
//                    "price": 9.11,
//                    "tax": 17,
//                    "oid": 0,
//                    "name": "真彩(TRUECOLOR)0.5mm黑色中性笔 12支\/盒009",
//                    "taxPrice": 1.32,
//                    "skuId": 813770,
//                    "nakedPrice": 7.79,
//                    "type": 0
//                }
//            ],
//            "orderTaxPrice": 41.23
//        },
//        "orderState": 1,
//        "cOrder": [
//            {
//                "pOrder": 71411883073,
//                "orderState": 1,
//                "jdOrderId": 70612405692,
//                "state": 1,
//                "freight": 0,
//                "submitState": 1,
//                "orderPrice": 249.5,
//                "orderNakedPrice": 213.25,
//                "type": 2,
//                "sku": [
//                    {
//                        "category": 1671,
//                        "num": 5,
//                        "price": 49.9,
//                        "tax": 17,
//                        "oid": 0,
//                        "name": "洁柔（C&S）卷纸 蓝精品3层140g卫生纸*27卷（细腻光滑 整箱销售）",
//                        "taxPrice": 7.25,
//                        "skuId": 1495690,
//                        "nakedPrice": 42.65,
//                        "type": 0
//                    }
//                ],
//                "orderTaxPrice": 36.25
//            },
//            {
//                "pOrder": 71411883073,
//                "orderState": 1,
//                "jdOrderId": 70612405724,
//                "state": 1,
//                "freight": 0,
//                "submitState": 1,
//                "orderPrice": 34.32,
//                "orderNakedPrice": 29.34,
//                "type": 2,
//                "sku": [
//                    {
//                        "category": 7371,
//                        "num": 2,
//                        "price": 8.05,
//                        "tax": 17,
//                        "oid": 0,
//                        "name": "得力(deli)76×76mm\/400页便签纸\/便签本\/便利贴\/百事贴\/易事贴4色 7151",
//                        "taxPrice": 1.17,
//                        "skuId": 1140673,
//                        "nakedPrice": 6.88,
//                        "type": 0
//                    },
//                    {
//                        "category": 2603,
//                        "num": 2,
//                        "price": 9.11,
//                        "tax": 17,
//                        "oid": 0,
//                        "name": "真彩(TRUECOLOR)0.5mm黑色中性笔 12支\/盒009",
//                        "taxPrice": 1.32,
//                        "skuId": 813770,
//                        "nakedPrice": 7.79,
//                        "type": 0
//                    }
//                ],
//                "orderTaxPrice": 4.98
//            }
//        ],
//        "submitState": 1,
//        "type": 1
//    },
//    "code": 200
//}



//{
//    "success": true,
//    "resultMessage": "",
//    "resultCode": "0000",
//    "result": {
//    "pOrder": 0,
//        "orderState": 1,
//        "jdOrderId": 70703127395,
//        "freight": 6,
//        "state": 1,
//        "submitState": 1,
//        "orderPrice": 15.8,
//        "orderNakedPrice": 13.5,
//        "sku": [
//            {
//                "category": 5022,
//                "num": 1,
//                "price": 15.8,
//                "tax": 17,
//                "oid": 0,
//                "name": "小老板 Taokaenoi 泰国进口 零食 即食 海苔片 香辣味2g*8",
//                "taxPrice": 2.3,
//                "skuId": 5867657,
//                "nakedPrice": 13.5,
//                "type": 0
//            }
//        ],
//        "type": 2,
//        "orderTaxPrice": 2.3
//    },
//    "code": 200
//}