<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 1/23/18
 * Time: 11:56 AM
 */

global $_W, $_GPC;

$_merchid  = $_GPC['merchid'];
$_goods_id = $_GPC['goods_id'];

include_once(IA_ROOT . '/addons/superdesk_shopv2/service/plugin/MerchService.class.php');
$_plugin_merchService = new MerchService();

$msg = $_plugin_merchService->importMerchGoodsFromOldShop($_merchid, $_goods_id);


show_json(1, $msg);