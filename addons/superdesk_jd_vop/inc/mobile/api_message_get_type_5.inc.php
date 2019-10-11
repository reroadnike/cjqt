<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 2/11/18
 * Time: 9:15 PM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_message_get_type_1
 */

//--5代表该订单已妥投（买断模式代表外单已妥投或外单已拒收）{"id":推送id, "result":{"orderId":"京东订单编号", "state":"1是妥投，2是拒收"}, "type" : 5, "time":推送时间}，


global $_W, $_GPC;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/MessageService.class.php');
$_messageService = new MessageService();

$_messageService->messageType_5_Get();