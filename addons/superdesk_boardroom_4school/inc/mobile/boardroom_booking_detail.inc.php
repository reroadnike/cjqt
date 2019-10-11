<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/27/17
 * Time: 6:01 AM
 *
 * 192.168.1.244/superdesk/app/index.php?i=15&c=entry&do=boardroom_booking_success&m=superdesk_boardroomc
 *
 * http://www.avic-s.com/plugins/app/index.php?i=16&c=entry&do=boardroom_booking_success&m=superdesk_boardroomc&out_trade_no=08024584
 */

global $_GPC, $_W;

$title = "预定成功";

$out_trade_no   = $_GPC['out_trade_no'];
$mode           = $_GPC['mode'];

//echo $mode;

$column = array(
    'out_trade_no'  => $out_trade_no,
    'uniacid'       => $_W['uniacid']
);

//var_dump($column);

/******************************************************* 预约服务 *********************************************************/

include_once(MODULE_ROOT . '/model/boardroom_appointment.class.php');
$boardroom_appointment = new boardroom_appointmentModel();

include_once(MODULE_ROOT . '/model/boardroom.class.php');
$boardroom = new boardroomModel();


$_boardroom_appointment = $boardroom_appointment->getOneByColumn($column);
$_boardroom_appointment['boardroom'] = $boardroom->getOne($_boardroom_appointment['boardroom_id']);


$json_str = "{\"items\":[".iunserializer($_boardroom_appointment['boardroom']['equipment'])."]}";
$json = json_decode(htmlspecialchars_decode($json_str), true);
$_boardroom_appointment['boardroom']['equipment'] = $json['items'];
$_boardroom_appointment['boardroom']['carousel'] = iunserializer($_boardroom_appointment['boardroom']['carousel']);
$_boardroom_appointment['boardroom']['thumb'] = tomedia($_boardroom_appointment['boardroom']['thumb']);

/******************************************************* 预约服务 *********************************************************/

/******************************************************* 附加服务 *********************************************************/
include_once(MODULE_ROOT . '/model/boardroom_s_goods.class.php');

$boardroom_s_goods = new boardroom_s_goodsModel();

$goods = pdo_fetchall(
    " SELECT `goodsid`, `total`, `optionid` "
    . " FROM " . tablename('superdesk_boardroom_4school_s_order_goods')
    . " WHERE `out_trade_no` = :out_trade_no AND `uniacid` = :uniacid",
    $column
);

if($goods){
    foreach ($goods as &$item){
        $item['g'] = $boardroom_s_goods->getOne($item['goodsid']);
    }
}
/******************************************************* 附加服务 *********************************************************/

if($mode == 'full_screen'){
    include $this->template('boardroom_booking_detail_full_screen');
} else{
    include $this->template('boardroom_booking_detail');
}

