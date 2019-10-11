<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 1/29/18
 * Time: 4:15 PM
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=_cron_handle_task_67_checking_order_state
 */


include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/task_cron.class.php');
$task_cron = new task_cronModel();

$_is_ignore = $task_cron->isIgnore(task_cronModel::_cron_handle_task_67_checking_order_state);


if ($_is_ignore) {
    die("ignore task" . PHP_EOL);
} else {
    $task_cron->updateLastdo(task_cronModel::_cron_handle_task_67_checking_order_state);
}

global $_W, $_GPC;


include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/OrderService.class.php');
$_orderService = new OrderService();

$msg = $_orderService->runReadyJdOrderByCheckingOrder(1, 1);

$end = microtime(true);
echo $msg . PHP_EOL;
echo '耗时' . round($end - STARTTIME, 4) . '秒' . PHP_EOL;