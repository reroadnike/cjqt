<?php
/**
 * 第一步:更新shop_order 10000以上记录
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 5/11/18
 * Time: 6:18 AM
 *
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_recovery&do=step_01
 */

exit(0);

global $_W, $_GPC;


include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/order_submit_order.class.php');
$_order_submit_orderModel = new order_submit_orderModel();

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order.class.php');
$_orderModel = new orderModel();

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order_goods.class.php');
$_order_goodsModel = new order_goodsModel();


//> select * from ims_superdesk_jd_vop_order_submit_order 1,513
//
//> thirdOrder => order_id
//
//> update ims_superdesk_shop_order set id = #order_id# where ordersn = #thirdOrder#


$where     = array();
$page      = 0;
$page_size = 5000;

$result = $_order_submit_orderModel->queryAll($where, $page, $page_size);


$source_total     = $result['total'];
$source_page      = $result['page'];
$source_page_size = $result['page_size'];
$source_list      = $result['data'];

//{
//    "id": "2",
//    "isparent": "0",
//    "order_id": "248",
//    "thirdOrder": "ME20171215121519999688",
//    "sku": "[{\"skuId\":\"5326765\",\"num\":1,\"bNeedAnnex\":true,\"bNeedGift\":true}]",
//    "name": "苏莉安宇雨",
//    "province": "19",
//    "city": "1607",
//    "county": "3639",
//    "town": "0",
//    "address": "深南中路3024号航空大厦32楼超级前台",
//    "zip": null,
//    "phone": null,
//    "mobile": "13422832499",
//    "email": "13760000000@qq.com",
//    "remark": "",
//    "invoiceState": "2",
//    "invoiceType": "2",
//    "selectedInvoiceTitle": "5",
//    "companyName": "前海超级前台（深圳）信息技术有限公司",
//    "invoiceContent": "1",
//    "paymentType": "4",
//    "isUseBalance": "1",
//    "submitState": "0",
//    "invoiceName": null,
//    "invoicePhone": null,
//    "invoiceProvice": null,
//    "invoiceCity": null,
//    "invoiceCounty": null,
//    "invoiceAddress": null,
//    "doOrderPriceMode": "1",
//    "orderPriceSnap": "[{\"price\":\"15.00\",\"skuId\":\"5326765\"}]",
//    "reservingDate": null,
//    "installDate": null,
//    "needInstall": "0",
//    "promiseDate": "",
//    "promiseTimeRange": "",
//    "promiseTimeRangeCode": "0",
//    "createtime": "1513311319",
//    "updatetime": "1517222401",
//    "response": "{\"success\":true,\"resultMessage\":\"\\u4e0b\\u5355\\u6210\\u529f\\uff01\",\"resultCode\":\"0001\",\"result\":{\"jdOrderId\":70430984204,\"freight\":6,\"orderPrice\":15,\"orderNakedPrice\":12.82,\"sku\":[{\"skuId\":5326765,\"num\":1,\"category\":4837,\"price\":15,\"name\":\"\\u6668\\u5149\\uff08M&G\\uff09\\u70ab\\u5f69\\u8ba2\\u4e66\\u673a12\\u53f7\\u989c\\u8272\\u968f\\u673aABS91641\",\"tax\":17,\"taxPrice\":2.18,\"nakedPrice\":12.82,\"type\":0,\"oid\":0}],\"orderTaxPrice\":2.18},\"code\":200}",
//    "jd_vop_success": "1",
//    "jd_vop_resultMessage": "下单成功！",
//    "jd_vop_resultCode": "0001",
//    "jd_vop_code": "200",
//    "jd_vop_result_jdOrderId": "70430984204",
//    "jd_vop_result_freight": "6",
//    "jd_vop_result_orderPrice": "15.00",
//    "jd_vop_result_orderNakedPrice": "12.82",
//    "jd_vop_result_orderTaxPrice": "2.18",
//    "checking_by_third": "3401",
//    "jd_vop_recheck_orderState": "0",
//    "jd_vop_recheck_submitState": "0",
//    "jd_vop_recheck_pOrder": ""
//}

foreach ($source_list as $item) {

//    echo json_encode($item,JSON_UNESCAPED_UNICODE);
//    echo PHP_EOL;

    echo $item['thirdOrder'];
    echo " => ";
    echo $item['order_id'];
    echo PHP_EOL;

//    UPDATE `ims_superdesk_shop_order` SET `id` =  1857 WHERE `ordersn` =  'ME20180430184702428856'
//    SELECT * FROM `ims_superdesk_shop_order` WHERE ordersn = 'ME20180508143603950878'
//    DELETE FROM `ims_superdesk_shop_order` WHERE id = 10173
//    SELECT ordersn , count(ordersn) as t FROM `ims_superdesk_shop_order` GROUP by ordersn HAVING t > 1


    $_orderModel->recoveryByOrderId(
        array(
            'id' => $item['order_id']
        ),
        array(
            'ordersn' => $item['thirdOrder'],
        )
    );

}




