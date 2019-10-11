<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 1/24/18
 * Time: 2:22 PM
 */

global $_W, $_GPC;


$_overwrite    = false;
$enterprise_id = $_GPC['enterprise_id'];
$merchid       = $_GPC['merchid'];
$_overwrite    = $_GPC['overwrite'];

if (empty($enterprise_id)) {
    show_json(0, 'enterprise_id is null');
}
if (empty($merchid)) {
    show_json(0, 'merchid is null');
}

include_once(IA_ROOT . '/addons/superdesk_shopv2/service/plugin/MerchService.class.php');
$_plugin_merchService = new MerchService();

$_plugin_merchService->mappingMerchXEnterprise($enterprise_id, $merchid);

show_json(1,"SUCCESS");