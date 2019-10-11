<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 2/28/18
 * Time: 3:53 PM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=js_create_jd_vop_area_cascade
 */
global $_W, $_GPC;

$parent_code = intval($_GPC['parent_code']);

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/AreaService.class.php');
$_areaService = new AreaService();

$_areaService->jdVopAreaCascadeClearCache($parent_code);
$js = $_areaService->jdVopAreaCascade($parent_code);

// 美化 测试时 查看
//die(json_encode(json_decode($js), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
// 实际时 使用
die($js);