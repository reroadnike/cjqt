<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 2/11/18
 * Time: 9:15 PM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_message_get_type_1
 */

//--6代表添加、删除商品池内商品{"id":推送id, "result":{"skuId": 商品编号, "page_num":商品池编号, "state":"1添加，2删除"}, "type" : 6, "time":推送时间}，


global $_W, $_GPC;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/MessageService.class.php');
$_messageService = new MessageService();

$_messageService->messageType_6_Get();