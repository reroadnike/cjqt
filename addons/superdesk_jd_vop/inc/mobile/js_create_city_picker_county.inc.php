<?php


/**
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=js_create_city_picker_county&code
 */
global $_W, $_GPC;

$code = $_GPC['code'];

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/area.class.php');
$_areaModel = new areaModel();

$js = $_areaModel->cityPickerDataLevel3($code);

echo $js;