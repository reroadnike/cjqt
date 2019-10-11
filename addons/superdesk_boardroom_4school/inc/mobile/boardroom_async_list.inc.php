<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 8/2/17
 * Time: 2:09 PM
 */

global $_GPC, $_W;

$superdesk_user_info    = $this->superdesk_core_user_mobile();

$date_selected          = $_GPC['date_selected'];
$time_start             = $_GPC['time_start'];
$time_end               = $_GPC['time_end'];

$structures_parentid    = isset($_GPC['structures_parentid'])?$_GPC['structures_parentid']:0;
$structures_childid     = isset($_GPC['structures_childid'])?$_GPC['structures_childid']:0;
$attribute              = isset($_GPC['attribute'])?$_GPC['attribute']:0;

$equipment_tags         = urldecode($_GPC['equipment_tags']);

//$equipment_json_str = "{\"items\":[".iunserializer($equipment_to_be_insert)."]}";//    echo $equipment_json_str;
$equipment_json_str     = "{\"items\":".$equipment_tags."}";//    echo $equipment_json_str;
$equipment_json         = json_decode(htmlspecialchars_decode($equipment_json_str), true);//    var_dump($equipment_json['items']);

//var_dump($equipment_json['items']);


$expect_people          = intval($_GPC['expect_people']);

$now                    = strtotime("+1 hours", time());



if(empty($date_selected)){
    $date_selected_array = array();
}else{
    $date_selected_array = explode(",",$date_selected);
    sort($date_selected_array);
}
if(sizeof($date_selected_array) == 0 ){
    $date_selected_array[] = date('Y-m-d', strtotime("+1 hours", time()));
}
$day = sizeof($situation_arr);



if(empty($time_start)){
    $time_start = "00:00";
}

if(empty($time_end)){
    $time_end = "23:00";
}



$situation_arr = array();
foreach ($date_selected_array as $_selected){

    $_timestamp = strtotime($_selected);
    $situation_arr[] = array(
        'timestamp' => $_timestamp,
        'lable_full' => date('Y-m-d H:i:s', $_timestamp),
        'lable' => date('Y-m-d', $_timestamp),
    );
}


include_once(MODULE_ROOT . '/model/boardroom.class.php');
$boardroom_model = new boardroomModel();



$page = $_GPC['page'];
$page_size = 99;

$out_where = array(
    "structures_parentid"   => $structures_parentid,
    "structures_childid"    => $structures_childid,
    "attribute"             => $attribute,
    "equipment_tags"        => $equipment_json['items']
);

//echo "expect_people::".$expect_people;
if(!empty($expect_people)){
    $numerical_arrays = $boardroom_model->getMaxNumNumericalArrays($expect_people);
//    var_dump($numerical_arrays);

    $out_where['expect_people']       = $numerical_arrays;
}

if($superdesk_user_info){
    $out_where['organization_code']   = $superdesk_user_info['organizationCode'];
    $out_where['virtual_code']        = $superdesk_user_info['virtualCode'];
}


//SELECT a.*
//FROM `ims_superdesk_boardroom_4school` as a
//inner join `ims_superdesk_boardroom_4school_x_equipment` as b on a.id = b.boardroom_id
//
//WHERE
//`a.uniacid` = :uniacid
//AND `a.enabled` = :enabled
//AND `a.structures_parentid` = :structures_parentid
//AND `a.organization_code` = :organization_code
//AND `a.virtual_code` = :virtual_code
//AND b.equipment_id = :b_equipment_id
//ORDER BY id ASC LIMIT 0,99

//SELECT *
//FROM `ims_superdesk_boardroom_4school` as a
//inner join `ims_superdesk_boardroom_4school_x_equipment` as b on a.id = b.boardroom_id
//WHERE b.equipment_id = 12 or b.equipment_id = 13

$result = $boardroom_model->queryByMobile($out_where,$page,$page_size);
$total = $result['total'];
$page = $result['page'];
$page_size = $result['page_size'];
$list = $result['data'];

foreach ($list as $index => &$_boardroom){

    $out_situation = array();

    foreach ($situation_arr as $_index => $_situation){
//        {"timestamp":1502208000,"lable_full":"2017-08-09 00:00:00","lable":"2017-08-09"}

        $out_situation[$_index]['timestamp'] = $_situation['timestamp'];
        $out_situation[$_index]['lable_full'] =$_situation['lable_full'];
        $out_situation[$_index]['lable'] =$_situation['lable'];

        $out_situation[$_index]['situation'] = $this->get_boardroom_situation($_boardroom['id'],$_situation['lable']);


        /**************************************** array merge and init  ****************************************/
        // TODO 待重构
        $select_time_bar= array();
        foreach ($out_situation[$_index]['situation']['am'] as $index => $_item){
            $_item['checked'] = 0;
            $select_time_bar[] = $_item;
        }
        foreach ($out_situation[$_index]['situation']['pm'] as $index => $_item){
            $_item['checked'] = 0;
            $select_time_bar[] = $_item;
        }
        /**************************************** array merge ****************************************/

//        $select_time_bar = json_encode($select_time_bar);

        /**************************************** check booking ****************************************/
        $_check_index_start = 0;
        $_check_index_end = 0;
        $_check_can_booking = 0;//0 不能预定
        $_error_msg = "";

        // 得到 time_start time_end 的起始与结束 index
        foreach ($select_time_bar as $__index => $bar){

//            {"index":0,"key":"2017-08-10 00:00:00","timestamp":1502296200,"is_use":0,"lable":"00:00-00:30","lable_start":"00:00","lable_end":"00:30"}
            if($bar['lable_start'] == $time_start){
                $_check_index_start = $__index;
            }

            if($bar['lable_end'] == $time_end){
                $_check_index_end = $__index;
            }

        }

        // 模拟第三页手动选中
        for($___check_index = $_check_index_start ; $___check_index <= $_check_index_end; $___check_index++){

            if($select_time_bar[$___check_index]['is_use'] == 0){ // is_use = 0 未被占用
                $select_time_bar[$___check_index]['checked'] = 1;
            } else if($select_time_bar[$___check_index]['is_use'] == 1){
                $__Ymd__ = explode(" ",$select_time_bar[$___check_index]['key']);
                $_error_msg = $_error_msg . $__Ymd__[0] . " " . $select_time_bar[$___check_index]['lable'] . ",";
                unset($select_time_bar[$___check_index]);
            }
        }

        foreach ($select_time_bar as $___index => $bar){

//            {"index":0,"key":"2017-08-10 00:00:00","timestamp":1502296200,"is_use":0,"lable":"00:00-00:30","lable_start":"00:00","lable_end":"00:30"}
            if($bar['checked'] != 1){
                unset($select_time_bar[$___index]);
            }
        }

        if(empty($_error_msg)){
            $_check_can_booking = 1;
        }

        $out_situation[$_index]['can_booking'] = $_check_can_booking;
        $out_situation[$_index]['error_msg'] = $_error_msg;
        $out_situation[$_index]['select_time_bar'] = json_encode($select_time_bar);
//        $out_situation[$_index]['select_time_bar'] = $select_time_bar;
        /**************************************** check booking ****************************************/

    }

    $_boardroom['situation'] = $out_situation;

    $json_str = "{\"items\":[".iunserializer($_boardroom['equipment'])."]}";
    $json = json_decode(htmlspecialchars_decode($json_str), true);
    $_boardroom['equipment'] = $json['items'];

}

unset($_boardroom);

$pager = pagination($total, $page, $page_size);

//var_dump($list);

include $this->template('boardroom_async_list');