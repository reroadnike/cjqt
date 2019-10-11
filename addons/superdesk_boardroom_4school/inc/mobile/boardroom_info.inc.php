<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/27/17
 * Time: 5:25 AM
 *
 * 192.168.1.244/superdesk/app/index.php?i=15&c=entry&do=boardroom&m=superdesk_boardroom
 */

global $_GPC, $_W;

$title = "会议室详情";


/************ from 表单数据 ************/
$id = $_GPC['id'];



//$select_time_bar = $_GPC['select_time_bar'];
//
//$order_goodsid = $_GPC['order_goodsid'];
//
////$date_start = $_GPC['date_start'];
////$date_end = $_GPC['date_end'];
//
//$date_selected = $_GPC['date_selected'];
//
//$time_start = $_GPC['time_start'];
//$time_end = $_GPC['time_end'];

//$_COOKIES
/************ from 表单数据 ************/

include_once(MODULE_ROOT . '/model/boardroom.class.php');
$boardroom = new boardroomModel();

$item = $boardroom->getOne($id);

$json_str = "{\"items\":[".iunserializer($item['equipment'])."]}";
$json = json_decode(htmlspecialchars_decode($json_str), true);
$item['equipment'] = $json['items'];
$item['carousel'] = iunserializer($item['carousel']);
$item['thumb'] = tomedia($item['thumb']);

$url_boardroom_info_map = $this->createMobileUrl('boardroom_info_map',array('id'=>$id));

include $this->template('boardroom_info');