<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 12/25/17
 * Time: 11:37 AM
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=_cron_handle_task_01_page_num
 *
 * view-source:https://fm.superdesk.cn/app/index.php?i=17&c=entry&m=superdesk_jd_vop&do=_cron_handle_task_01_page_num
 */

global $_W, $_GPC;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/task_cron.class.php');
$task_cron = new task_cronModel();

$_is_ignore = $task_cron->isIgnore(task_cronModel::_cron_handle_task_01_page_num);

if ($_is_ignore) {
    die("ignore task" . PHP_EOL);
} else {
    $task_cron->updateLastdo(task_cronModel::_cron_handle_task_01_page_num);
}

$_DEBUG = false;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/ProductService.class.php');
$_productService = new ProductService();

$_productService->runQueryPageNumForTask($_DEBUG);



$end = microtime(true);
echo '耗时' . round($end - STARTTIME, 4) . '秒'.PHP_EOL;