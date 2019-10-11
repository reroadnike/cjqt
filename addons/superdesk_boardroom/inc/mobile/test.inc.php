<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 8/2/17
 * Time: 4:44 AM
 */

/******************************************************* 预约服务 *********************************************************/
// TODO 更新会议预约表与redis中的会议室情况

include_once(MODULE_ROOT . '/model/boardroom_appointment.class.php');
$boardroom_appointment = new boardroom_appointmentModel();

// 数组对象
$situation_target = $this->get_boardroom_situation($_boardroom_appointment['boardroom_id'],$_boardroom_appointment['lable_ymd']);
$situation_select = json_decode(htmlspecialchars_decode($_boardroom_appointment['situation']),true);

foreach ($situation_select['situation'] as $index => &$_situation){

//                0-23
    if($_situation['index'] >= 0 && $_situation['index'] <= 23){
        $situation_target['am'][$_situation['index']]['is_use'] = 1;
    }
//                24-47
    if($_situation['index'] >= 24 && $_situation['index'] <= 47){
        $situation_target['pm'][$_situation['index']]['is_use'] = 1;
    }
}

$this->set_boardroom_situation($_boardroom_appointment['boardroom_id'],$_boardroom_appointment['lable_ymd'],$situation_target);

$column_appointment = array(
    'out_trade_no' => $params['tid'],
    'uniacid' => $_W['uniacid']
);
$boardroom_appointment->saveOrUpdateByColumn($data,$column_appointment);

$_boardroom_appointment = $boardroom_appointment->getOneByColumn($column_appointment);

/******************************************************* 预约服务 *********************************************************/