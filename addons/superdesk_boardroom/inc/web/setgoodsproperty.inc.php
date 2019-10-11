<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/29/17
 * Time: 4:37 AM
 */

global $_GPC, $_W;
$id = intval($_GPC['id']);
$type = $_GPC['type'];
$data = intval($_GPC['data']);
if (in_array($type, array('new', 'hot', 'recommand', 'discount'))) {
    $data = ($data==1?'0':'1');
    pdo_update("superdesk_boardroom_s_goods", array("is" . $type => $data), array("id" => $id, "uniacid" => $_W['uniacid']));
    die(json_encode(array("result" => 1, "data" => $data)));
}
if (in_array($type, array('status'))) {
    $data = ($data==1?'0':'1');
    pdo_update("superdesk_boardroom_s_goods", array($type => $data), array("id" => $id, "uniacid" => $_W['uniacid']));
    die(json_encode(array("result" => 1, "data" => $data)));
}
if (in_array($type, array('type'))) {
    $data = ($data==1?'2':'1');
    pdo_update("superdesk_boardroom_s_goods", array($type => $data), array("id" => $id, "uniacid" => $_W['uniacid']));
    die(json_encode(array("result" => 1, "data" => $data)));
}
die(json_encode(array("result" => 0)));