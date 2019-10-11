<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 2/11/18
 * Time: 9:15 PM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=_cron_handle_task_1016_message_get_type_16
 *
 * view-source:http://www.avic-s.com/plugins/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=_cron_handle_task_1016_message_get_type_16
 */

//--16 商品介绍及规格参数变更消息{"id":推送id, "result":{"skuId" : 商品编号 } "type" : 16, "time":推送时间}}


global $_W, $_GPC;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/task_cron.class.php');
$task_cron = new task_cronModel();

$_is_ignore = $task_cron->isIgnore(task_cronModel::_cron_handle_task_1016_message_get_type_16);

if ($_is_ignore) {
    die("ignore task" . PHP_EOL);
} else {
    $task_cron->updateLastdo(task_cronModel::_cron_handle_task_1016_message_get_type_16);
}

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/MessageService.class.php');
$_messageService = new MessageService();

$_messageService->messageType_16_Get();


$end = microtime(true);
echo $msg . PHP_EOL;
echo '耗时' . round($end - STARTTIME, 4) . '秒' . PHP_EOL;