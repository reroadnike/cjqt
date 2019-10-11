<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 2/11/18
 * Time: 9:15 PM
 *
 * 福利内购
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=17&c=entry&m=superdesk_jd_vop&do=patch_1001_message_get_type_1_step_02
 * view-source:https://wxn.avic-s.com/app/index.php?i=17&c=entry&m=superdesk_jd_vop&do=patch_1001_message_get_type_1_step_02
 */

//--1代表订单拆分变更{"id":推送id, "result" : {"pOrder" :父订单id} , "type": 1, "time":推送时间},注意：京东订单可能会被多次拆单； 例如：订单1 首先被拆成订单2、订单3；然后订单2有继续被拆成订单4、订单5；最终订单1的子单是订单3、订单4、订单5；每拆一次单我们都会发送一次拆单消息，但父订单号只会传递订单1（原始单），需要通过查询接口获取到最新所有子单，进行相关更新；

global $_W, $_GPC;

//die;

//将所有挂错单的单的id拉平
//先获取所有挂错的单
$list = pdo_fetchall(
    ' SELECT COUNT(createtime) as num ,createtime, FROM_UNIXTIME(createtime), GROUP_CONCAT(id) as ids ,GROUP_CONCAT(expresssn) as expresssns ' .
    ' FROM ' . tablename('superdesk_shop_order')  .// TODO 标志 楼宇之窗 openid shop_order 不处理
    ' WHERE createtime > 1536681600  ' .
    '       and parentid = 0 ' .
    ' GROUP BY createtime HAVING num >1'
);

//单独取出ids这一列
$expresssns_list = array_column($list,'expresssns');

//拉平ids
$last_id_list = array();
foreach($expresssns_list as $v){

   $last_id_list = array_merge($last_id_list,explode(',',$v));


}

// 除空
$last_id_list = array_filter($last_id_list);
print_r($last_id_list);
//die;



//Array
//(
//    [1] => 77818866002 d
//    [2] => 77818322556 d
//    [3] => 77819200852 d
//    [4] => 77820352989 d
//    [7] => 77820123199 d
//    [9] => 77826681594 d
//    [11] => 77823223863 d
//    [12] => 77828022106 d
//    [13] => 77816723929 d
//    [14] => 77823596272 d
//    [15] => 77822708127 d
//    [16] => 77817465689 d
//    [17] => 77823008351 d
//    [18] => 77824208213 d
//    [19] => 77823213533 d
//    [20] => 77824422259 d
//    [21] => 77824414133 d
//    [22] => 77824391094 d
//    [23] => 77809671707 d
//    [25] => 77824090846 d
//    [26] => 77826235474 d
//    [27] => 77831198138 d
//    [29] => 77834502648 d
//    [30] => 77830549267 d
//    [31] => 77830448119 d
//    [32] => 77830833200 d
//)



include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/OrderService.class.php');
$_orderService = new OrderService();

foreach ($last_id_list as $index => $__expresssn) {
    $_orderService->businessProcessingSelectJdOrder($__expresssn);
}



$end = microtime(true);
echo $msg . PHP_EOL;
echo '耗时' . round($end - STARTTIME, 4) . '秒' . PHP_EOL;