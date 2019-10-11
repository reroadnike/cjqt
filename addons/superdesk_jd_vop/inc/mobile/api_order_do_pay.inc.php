<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 1/29/18
 * Time: 11:56 AM
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_order_do_pay
 */

global $_GPC;

$orderid = $_GPC['orderid'];
if($_GPC['test'] != 1 || empty($orderid)){
    die;
}

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/OrderService.class.php');
$_orderService = new OrderService();

$response = $_orderService->confirmOrderByShopOrderId($orderid);//　'17242'已关闭状态　
//$response = $_orderService->doPay('83017890784');//　已关闭状态　




//
//include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/ProductService.class.php');
//$_productService = new ProductService();
//
//$response = $_productService->getDetail('7788763',true);//　已关闭状态　

print_r(json_decode($response));die;

//die(json_encode($response));
