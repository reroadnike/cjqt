<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/28/17
 * Time: 4:07 PM
 */

global $_GPC, $_W;

$id = $_GPC['id'];

/******************************************************* 设备 *********************************************************/
include_once(MODULE_ROOT . '/model/boardroom_equipment.class.php');
$boardroom_equipment = new boardroom_equipmentModel();

$page = 1;
$page_size = 100;
$where = array(
    "enabled" => 1
);
$result = $boardroom_equipment->queryByApi4AjaxTypehead($where,$page,$page_size);
$total = $result['total'];
$page = $result['page'];
$page_size = $result['page_size'];
$list = $result['data'];

$url_boardroom_api_equipment = $this->createWebUrl('boardroom_api_equipment', array());
/******************************************************* 设备 *********************************************************/


/******************************************************* 页面 *********************************************************/
include_once(MODULE_ROOT . '/model/boardroom.class.php');
$boardroom = new boardroomModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'edit';

$item = $boardroom->getOne($_GPC['id']);

$init_equipment = iunserializer($item['equipment']);

//var_dump($init_equipment);

$init_equipment = "[".$init_equipment."]";

//echo $init_equipment;


//$init_equipment = json_decode(json_encode($init_equipment));

//var_dump(json_decode($init_equipment));


$pager = pagination($total, $page, $page_size);


if (checksubmit('submit')) {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
    $params = array(
        'equipment' => iserializer($_GPC['equipment']),
    );
    $boardroom->saveOrUpdate($params, $id);

    message('编辑成功！', $this->createWebUrl('boardroom_set_equipment', array('op' => 'edit' , 'id' => $_GPC['id'])), 'success');


}

include $this->template('boardroom_set_equipment');

/******************************************************* 页面 *********************************************************/