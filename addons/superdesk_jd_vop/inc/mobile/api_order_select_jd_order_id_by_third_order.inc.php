<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 1/29/18
 * Time: 11:56 AM
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_order_select_jd_order_id_by_third_order
 */

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/OrderService.class.php');
$_orderService = new OrderService();

//$response = $_orderService->selectJdOrderIdByThirdOrder('ME20180117190343444566');// JD拆单
//$response = $_orderService->selectJdOrderIdByThirdOrder('ME20171225205534392127');//舒
//$response = $_orderService->selectJdOrderIdByThirdOrder('ME20171221164343219404');//电话退单　已完成状态　
//$response = $_orderService->selectJdOrderIdByThirdOrder('ME20180123175329424204');//　已关闭状态　



$response = $_orderService->selectJdOrderIdByThirdOrder('ME20180413171402486019');//　已关闭状态　










die(json_encode($response , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));

//{
//    "success": true,
//    "resultMessage": "",
//    "resultCode": "0000",
//    "result": "71411883073",
//    "code": 200
//}


//{
//    "success": true,
//    "resultMessage": "",
//    "resultCode": "0000",
//    "result": "70703127395",
//    "code": 200
//}


//{
//    "success": false,
//    "resultMessage": "ME20171221164343219404订单不存在",
//    "resultCode": "3401",
//    "result": null,
//    "code": 200
//}


//{
//    "success": false,
//    "resultMessage": "ME20180123175329424204订单不存在",
//    "resultCode": "3401",
//    "result": null,
//    "code": 200
//}

//如果
//success 为true  则代表下单成功，result值为京东的订单号
//success 为false 则代表京东订单号不存在，
//背景：由于下单反馈超时时，有可能已下单成功，也有可能下单失败，需要反查查看实际情况。
//使用场景：
//	调用下单接口下单时，当反馈抄送时，需要调用反馈查接口查询订单实际处理情况
//1、 当反查接口反馈true时，关联申请单，无需再次掉下单接口下单
//2、 当反查接口反馈false时，需要重新调下单接口下单，并关联审批单。