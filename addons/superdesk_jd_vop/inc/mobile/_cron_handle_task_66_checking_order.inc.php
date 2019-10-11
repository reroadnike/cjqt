<?php
/**
 *
 * 订单反查，再次确认订单是否成功
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 1/29/18
 * Time: 12:45 PM
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=_cron_handle_task_66_checking_order
 */

global $_W, $_GPC;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/task_cron.class.php');
$task_cron = new task_cronModel();

$_is_ignore = $task_cron->isIgnore(task_cronModel::_cron_handle_task_66_checking_order);


if ($_is_ignore) {
    die("ignore task" . PHP_EOL);
} else {
    $task_cron->updateLastdo(task_cronModel::_cron_handle_task_66_checking_order);
}




include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/OrderService.class.php');
$_orderService = new OrderService();

$msg = $_orderService->runPendingJdOrderByCheckingOrder();

//$response = $_orderService->selectJdOrderIdByThirdOrder('ME20180117190343444566');// JD拆单
//$response = $_orderService->selectJdOrderIdByThirdOrder('ME20171225205534392127');//舒
//$response = $_orderService->selectJdOrderIdByThirdOrder('ME20171221164343219404');//电话退单　已完成状态　
//$response = $_orderService->selectJdOrderIdByThirdOrder('ME20180123175329424204');//　已关闭状态　


$end = microtime(true);
echo $msg . PHP_EOL;
echo '耗时' . round($end - STARTTIME, 4) . '秒' . PHP_EOL;




//die(json_encode($response , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));