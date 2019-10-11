<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/20/17
 * Time: 7:23 PM
 *
 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_boardroom&do=patch_fix_boardroom_x_equipment
 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/boardroom.class.php');
$boardroom = new boardroomModel();
include_once(MODULE_ROOT . '/model/boardroom_4school_x_equipment.class.php');
$x_equipment_model = new boardroom_4school_x_equipmentModel();

$page       = $_GPC['page'];
$page_size  = 999999;

$result     = $boardroom->queryAllByCoreUser($_where, $page, $page_size);
$total      = $result['total'];
$page       = $result['page'];
$page_size  = $result['page_size'];
$list       = $result['data'];

foreach ($list as $index => $_item){
    $json_str = "{\"items\":[".iunserializer($_item['equipment'])."]}";
    $json = json_decode(htmlspecialchars_decode($json_str), true);

    foreach ($json['items'] as $index => $_equipment_for_x_insert){

        $_equipment_for_x_insert_column = $_equipment_for_x_insert_params = array(
            "boardroom_id" => $_item['id'],
            "equipment_id" => $_equipment_for_x_insert['value']
        );


        $x_equipment_model->saveOrUpdateByColumn($_equipment_for_x_insert_params,$_equipment_for_x_insert_column);
    }

}











