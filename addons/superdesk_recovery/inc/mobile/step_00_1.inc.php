<?php
/**
 * 第一步:更新shop_order 10000以上记录
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 5/11/18
 * Time: 6:18 AM
 *
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_recovery&do=step_00_1
 * view-source:https://wxm.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_recovery&do=step_00_1
 *
 *
 */
global $_W, $_GPC;
//2018-04-20
//11:42:04

//2018-04-27
//10:25:05

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order_goods.class.php');
$_order_goodsModel = new order_goodsModel();

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/ProductService.class.php');
$_productService = new ProductService();

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/goods/goods.class.php');
$_goodsModel = new goodsModel();



//> SELECT * FROM ims_superdesk_jd_vop_order_submit_order_sku 3,415
//
//> skuId => shop_goods_id
//
//> shop_order_id shop_order_sn
//
//> price 用于检查 sku 有没有价格变动
//
//> price num jdOrderId name

//SELECT * FROM `ims_superdesk_shop_goods` WHERE updatetime > 1525996818
//DELETE FROM `ims_superdesk_shop_goods` WHERE updatetime > 1525996818

$where     = array();
$page      = max(1, intval($_GPC['page']));
$page_size = 1000;

$result = $_order_goodsModel->queryAll($where, $page, $page_size);


$source_total     = $result['total'];
$source_page      = $result['page'];
$source_page_size = $result['page_size'];
$source_list      = $result['data'];


//{
//    "id": "2",
//    "uniacid": "16",
//    "parent_order_id": "0",
//    "orderid": "2",
//    "goodsid": "201",
//    "price": "0.10",
//    "total": "1",
//    "optionid": "0",
//    "createtime": "1504696361",
//    "optionname": "",
//    "commission1": "a:1:{s:7:\"default\";d:0.01;}",
//    "applytime1": "0",
//    "checktime1": "0",
//    "paytime1": "0",
//    "invalidtime1": "0",
//    "deletetime1": "0",
//    "status1": "0",
//    "content1": null,
//    "commission2": "a:1:{s:7:\"default\";i:0;}",
//    "applytime2": "0",
//    "checktime2": "0",
//    "paytime2": "0",
//    "invalidtime2": "0",
//    "deletetime2": "0",
//    "status2": "0",
//    "content2": null,
//    "commission3": "a:1:{s:7:\"default\";i:0;}",
//    "applytime3": "0",
//    "checktime3": "0",
//    "paytime3": "0",
//    "invalidtime3": "0",
//    "deletetime3": "0",
//    "status3": "0",
//    "content3": null,
//    "realprice": "0.07",
//    "goodssn": "",
//    "productsn": "",
//    "nocommission": "0",
//    "changeprice": "0.00",
//    "oldprice": "0.07",
//    "commissions": "a:3:{s:6:\"level1\";d:0.01;s:6:\"level2\";i:0;s:6:\"level3\";i:0;}",
//    "diyformdata": "a:0:{}",
//    "diyformfields": "a:0:{}",
//    "diyformdataid": "0",
//    "openid": "oX8KYwuFkpUn0-p91w9p4jNMxeuM",
//    "diyformid": "0",
//    "rstate": "0",
//    "refundtime": "0",
//    "printstate": "0",
//    "printstate2": "0",
//    "merchid": "0",
//    "parentorderid": "0",
//    "merchsale": "0",
//    "isdiscountprice": "0.00",
//    "canbuyagain": "0",
//    "return_goods_nun": "0",
//    "return_goods_result": ""
//}


$overwrite = 1;
$page_num = 0;
foreach ($source_list as $index => $item) {

//    if($index ==0){
//        echo json_encode($item,JSON_UNESCAPED_UNICODE);
//        echo PHP_EOL;
//        break;
//    }

    if(!empty($item['goodssn'])){
        $column    = array(
            "jd_vop_sku" => $item['goodssn']
        );
        $_is_exist = $_goodsModel->getOneByColumn($column);

        if(!$_is_exist){

            echo "##### => " . $item['goodssn'];

            $result = $_productService->businessProcessingGetDetailOne($item['goodssn'],$page_num,$overwrite,$item['goodsid']);

            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            echo PHP_EOL;
        } else {
            echo "exist => " . $item['goodssn'];
            echo PHP_EOL;
        }
    }


    
}