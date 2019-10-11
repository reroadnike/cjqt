<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 8/2/17
 * Time: 2:09 PM
 */

global $_GPC, $_W;

$date_start = $_GPC['date_start'];
$time_start = $_GPC['time_start'];
$date_end = $_GPC['date_end'];
$time_end = $_GPC['time_end'];

$now = strtotime("+30 minutes", time());


$timestamp_start = strtotime($date_start);
$timestamp_end = strtotime($date_end);

$situation_arr = array();

$day = 0;

$counter_arr = array();
$counter_arr[] = $timestamp_start;

$situation_arr[] = array(
    'timestamp' => $timestamp_start,
    'lable_full' => date('Y-m-d H:i:s', $timestamp_start),
    'lable' => date('Y-m-d', $timestamp_start),
);


while ($counter_arr[$day] < $timestamp_end) {

    $counter_arr[] = strtotime("+1 day",$counter_arr[$day]);

    $situation_arr[] = array(
        'timestamp' => $counter_arr[$day+1],
        'lable_full' => date('Y-m-d H:i:s', $counter_arr[$day+1]),
        'lable' => date('Y-m-d', $counter_arr[$day+1]),
    );

    $day = $day +1;

}
$day = $day +1;
//var_dump($counter_arr);
// 较正
//foreach ($situation_arr as $item){
////    echo $date_start = date('Y-m-d H:i:s', $item);
//    echo json_encode($item);
//    echo "<br/>";
//}
//echo $day+1;
//echo "<br/>";

//{"timestamp":1502208000,"lable_full":"2017-08-09 00:00:00","lable":"2017-08-09"}
//{"timestamp":1502294400,"lable_full":"2017-08-10 00:00:00","lable":"2017-08-10"}


include_once(MODULE_ROOT . '/model/boardroom.class.php');
$boardroom = new boardroomModel();

$page = $_GPC['page'];
$page_size = 20;

$result = $boardroom->queryByMobile(array(),$page,$page_size);
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

        foreach ($select_time_bar as $__index => $bar){

//            {"index":0,"key":"2017-08-10 00:00:00","timestamp":1502296200,"is_use":0,"lable":"00:00-00:30","lable_start":"00:00","lable_end":"00:30"}
            if($bar['lable_start'] == $time_start){
                $_check_index_start = $__index;
            }

            if($bar['lable_end'] == $time_end){
                $_check_index_end = $__index;
            }

        }

        for($___check_index = $_check_index_start ; $___check_index <= $_check_index_end; $___check_index++){

            if($select_time_bar[$___check_index]['is_use'] == 0){ // is_use = 0 未被占用

                // 模拟第三页手动选中
                $select_time_bar[$___check_index]['check'] = 1;

            } else{
                $_error_msg = $_error_msg . $select_time_bar[$___check_index]['key'] . " " . $select_time_bar[$___check_index]['lable'] . " 已被占用;";
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

}

unset($_boardroom);

$pager = pagination($total, $page, $page_size);

//var_dump($list);

include $this->template('boardroom_async_list');