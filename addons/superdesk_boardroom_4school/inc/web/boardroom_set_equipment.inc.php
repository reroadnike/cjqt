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
    $equipment_to_be_insert = iserializer($_GPC['equipment']);

    $equipment_json_str = "{\"items\":[".iunserializer($equipment_to_be_insert)."]}";//    echo $equipment_json_str;
    $equipment_json = json_decode(htmlspecialchars_decode($equipment_json_str), true);//    var_dump($equipment_json['items']);

    $params = array(
        'equipment' => $equipment_to_be_insert,
    );
    $boardroom->saveOrUpdate($params, $id);



    include_once(MODULE_ROOT . '/model/boardroom_4school_x_equipment.class.php');
    $x_equipment_model = new boardroom_4school_x_equipmentModel();

    foreach ($equipment_json['items'] as $index => $_equipment_for_x_insert){

        $_equipment_for_x_insert_column = $_equipment_for_x_insert_params = array(
            "boardroom_id" => $id,
            "equipment_id" => $_equipment_for_x_insert['value']
        );


        $x_equipment_model->saveOrUpdateByColumn($_equipment_for_x_insert_params,$_equipment_for_x_insert_column);
    }

    message('编辑成功！', $this->createWebUrl('boardroom_set_equipment', array('op' => 'edit' , 'id' => $_GPC['id'])), 'success');


}

include $this->template('boardroom_set_equipment');

/******************************************************* 页面 *********************************************************/