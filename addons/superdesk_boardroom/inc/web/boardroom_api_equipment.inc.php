<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/28/17
 * Time: 5:20 PM
 */

global $_GPC, $_W;


include_once(MODULE_ROOT . '/model/boardroom_equipment.class.php');
$boardroom_equipment = new boardroom_equipmentModel();



$page = intval($_GPC['page'],1);
$page_size = 100;


$where = array();

$result = $boardroom_equipment->queryByApi4AjaxTypehead($where,$page,$page_size);
$total = $result['total'];
$page = $result['page'];
$page_size = $result['page_size'];
$list = $result['data'];

die(json_encode($list));

//$pager = pagination($total, $page, $page_size);
