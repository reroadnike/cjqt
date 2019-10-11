<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 2/11/18
 * Time: 9:15 PM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_message_get_type_1
 */

//--13 换新订单生成（换新单下单后推送，仅提供给买卖宝类型客户）{"id":推送id, "result":{"afsServiceId": 服务单号, " orderId":换新订单号}, "type" : 13, "time":推送时间}


global $_W, $_GPC;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/MessageService.class.php');
$_messageService = new MessageService();

$_messageService->messageType_13_Get();