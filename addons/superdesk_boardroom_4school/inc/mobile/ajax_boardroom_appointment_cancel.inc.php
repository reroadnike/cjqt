<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 8/16/17
 * Time: 8:47 PM
 */
global $_GPC, $_W;

/************ 4 test start ************/
//$data = array();
//$data['code'] = 200;
//$data['msg'] = "取消预定成功！";
//$data['data'] = 1;
//die(json_encode($data));
/************ 4 test end ************/

include_once(MODULE_ROOT . '/model/boardroom_appointment.class.php');
$boardroom_appointment = new boardroom_appointmentModel();

$id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

$_boardroom_appointment = $boardroom_appointment->getOne($_GPC['id']);

if (empty($_boardroom_appointment)) {
    $data = array();
    $data['code'] = 500;
    $data['msg'] = "抱歉，该信息不存在或是已经被删除！";
    $data['data'] = 0;
    die(json_encode($data));
}

// 数组对象

$situation_select = json_decode($_boardroom_appointment['situation'],true);

// 数据结构参考
//            var_dump($situation_select['stbs']);exit(0);
foreach ($situation_select['stbs'] as $index_key => $_situation_val){

    $situation_target = $this->get_boardroom_situation($_boardroom_appointment['boardroom_id'],$_situation_val['lable']);

    //    {
    //        ["index"]=> int(0)
    //        ["key"]=> string(19) "2017-07-31 00:00:00"
    //        ["timestamp"]=> int(1501432200)
    //        ["is_use"]=> int(0)
    //        ["lable"]=> string(11) "00:00-00:30"
    //        ["checked"]=> int(0)
    //    }

    foreach($_situation_val['select_time_bar'] as $index => &$_situation){
        // 0-23
        if($_situation['index'] >= 0 && $_situation['index'] <= 23){
            $situation_target['am'][$_situation['index']]['is_use'] = 0;
        }
        // 24-47
        if($_situation['index'] >= 24 && $_situation['index'] <= 47){
            $situation_target['pm'][$_situation['index']-24]['is_use'] = 0;
        }
    }

    $this->set_boardroom_situation($_boardroom_appointment['boardroom_id'],$_situation_val['lable'],$situation_target);
}

$params = array("status" => -1);
$boardroom_appointment->update($params,$id);


$data = array();
$data['code'] = 200;
$data['msg'] = "取消预定成功！";
$data['data'] = 1;
die(json_encode($data));

//referer()