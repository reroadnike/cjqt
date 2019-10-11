<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 12/6/17
 * Time: 3:57 PM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_area_check_area&province=0&city=0&county=0&town=0
 */

global $_W, $_GPC;

$provinceId = intval($_GPC['province'], 0);
$cityId     = intval($_GPC['city'], 0);
$countyId   = intval($_GPC['county'], 0);
$townId     = intval($_GPC['town'], 0);


include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/AreaService.class.php');
$_areaService = new AreaService();

$result = $_areaService->checkArea($provinceId, $cityId, $countyId, $townId);


// 正确的
//{"success":true,"resultMessage":"","resultCode":null,"result":{"success":true,"resultCode":1,"addressId":0,"message":null}}

// 错误的
//{"success":false,"resultMessage":"地址非法-非京东地址!","resultCode":"3554","result":null}

if($result['success'] == false){
    show_json(0,'地址非法，请重新选择');
} else {
    show_json(1,'地址合法');
}

