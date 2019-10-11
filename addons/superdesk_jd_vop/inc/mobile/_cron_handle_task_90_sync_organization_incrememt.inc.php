<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 6/19/17
 * Time: 11:28 AM
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=_cron_handle_task_90_sync_organization_incrememt
 */
global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/task_cron.class.php');
$task_cron = new task_cronModel();

$_is_ignore = $task_cron->isIgnore(task_cronModel::_cron_handle_task_90_sync_organization_incrememt);


if ($_is_ignore) {
    die("ignore task" . PHP_EOL);
} else {
    $task_cron->updateLastdo(task_cronModel::_cron_handle_task_90_sync_organization_incrememt);
}




include_once(IA_ROOT . '/addons/superdesk_core/service/OrganizationService.class.php');
$_organizationService = new OrganizationService();


$_organizationService->syncIncrementCreateTime();
$_organizationService->syncIncrementModifyTime();



$end = microtime(true);
echo '耗时' . round($end - STARTTIME, 4) . '秒'.PHP_EOL;