<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:20 PM
 */

global $_W, $_GPC;
$weid = $this->_weid;
$id = intval($_GPC['id']);
$storeid = intval($_GPC['storeid']);

if (empty($storeid)) {
    $url = $this->createWebUrl('setting');
}

pdo_delete('superdesk_dish_mealtime', array('id' => $id, 'weid' => $weid));
message('操作成功', $url, 'success');