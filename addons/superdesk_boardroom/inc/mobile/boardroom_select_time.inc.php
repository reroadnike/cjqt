<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/27/17
 * Time: 5:54 AM
 */
global $_GPC, $_W;

$title  = "会议室详情";
$id     = $_GPC['id'];
$Ymd    = $_GPC['ymd'];

$now = strtotime("+30 minutes", time());

include_once(MODULE_ROOT . '/model/boardroom.class.php');
$boardroom = new boardroomModel();

$item = $boardroom->getOne($id);
$json_str = "{\"items\":[".iunserializer($item['equipment'])."]}";
$json = json_decode(htmlspecialchars_decode($json_str), true);
$item['equipment']  = $json['items'];
$item['carousel']   = iunserializer($item['carousel']);
$item['thumb']      = tomedia($item['thumb']);
$item['situation']  = $this->get_boardroom_situation($item['id'],$Ymd);

$select_time_bar= array();
foreach ($item['situation']['am'] as $index => $_item){

    $_item['checked'] = 0;
    $select_time_bar[] = $_item;
}
foreach ($item['situation']['pm'] as $index => $_item){
    $_item['checked'] = 0;
    $select_time_bar[] = $_item;
}

$select_time_bar = json_encode($select_time_bar);

include $this->template('boardroom_select_time');