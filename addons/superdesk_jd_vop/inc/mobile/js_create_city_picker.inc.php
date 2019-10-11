<?php


/**
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=js_create_city_picker
 *
 * /data/wwwroot/default/superdesk/addons/superdesk_shopv2/static/js/dist/city-picker/docs/js/city-picker.data.参考.js
 */
global $_W, $_GPC;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/area.class.php');
$_areaModel = new areaModel();

$js = $_areaModel->cityPickerData();

echo $js;