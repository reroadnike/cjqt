<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 2/11/18
 * Time: 9:15 PM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=_cron_handle_task_1015_message_get_type_15
 */

//--15 7天未支付取消消息/未确认取消（cancelType, 1: 7天未支付取消消息; 2: 未确认取消）{"id":推送id, "result":{"orderId": 京东订单编号, "cancelType": 取消类型}， "type" : 15, "time":推送时间}


global $_W, $_GPC;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/task_cron.class.php');
$task_cron = new task_cronModel();

$_is_ignore = $task_cron->isIgnore(task_cronModel::_cron_handle_task_1015_message_get_type_15);

if ($_is_ignore) {
    die("ignore task" . PHP_EOL);
} else {
    $task_cron->updateLastdo(task_cronModel::_cron_handle_task_1015_message_get_type_15);
}

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/MessageService.class.php');
$_messageService = new MessageService();

$_messageService->messageType_15_Get();