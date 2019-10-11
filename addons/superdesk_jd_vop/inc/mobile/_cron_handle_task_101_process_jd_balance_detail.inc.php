<?php
/**
 * 用于增量同步
 * User: linjinyu
 * Date: 3/30/18
 * Time: 3:11 PM
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=_cron_handle_task_101_process_jd_balance_detail
 * view-source:https://wxm.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=_cron_handle_task_101_process_jd_balance_detail
 */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/task_cron.class.php');
$task_cron = new task_cronModel();

$_is_ignore = $task_cron->isIgnore(task_cronModel::_cron_handle_task_101_process_jd_balance_detail);


if ($_is_ignore) {
    die("ignore task" . PHP_EOL);
} else {
    $task_cron->updateLastdo(task_cronModel::_cron_handle_task_101_process_jd_balance_detail);
}

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/PriceService.class.php');
$_priceService = new PriceService();

$page = 1;
$page_size = 500;


$_priceService->businessProcessingBalanceDetailTradeType_416($page,$page_size);

