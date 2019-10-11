<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 12/15/17
 * Time: 7:18 PM
 *
 *
 */

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/OrderService.class.php');
$_orderService = new OrderService();

//$_orderService->orderTrack(70420843945);
$response = $_orderService->orderTrack(7043962150);

die(json_encode($response , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));

//{
//    "success": true,
//    "resultMessage": "",
//    "resultCode": "0000",
//    "result": {
//    "orderTrack": [
//            {
//                "content": "您提交了订单，请等待系统确认",
//                "msgTime": "2017-12-15 16:42:44",
//                "operator": "客户"
//            }
//        ],
//        "jdOrderId": 70439621543
//    },
//    "code": 200
//}


//{
//    "success": false,
//    "resultMessage": "该订单没有配送信息",
//    "resultCode": "3401",
//    "result": null,
//    "code": 200
//}