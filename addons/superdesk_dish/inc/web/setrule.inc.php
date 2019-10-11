<?php
/**
 * 入口设置
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:13 PM
 */

global $_W;
$rule = pdo_fetch("SELECT id FROM " . tablename('rule') . " WHERE module = 'superdesk_dish' AND weid = '{$_W['uniacid']}' order by id desc");
if (empty($rule)) {
    header('Location: ' . $_W['siteroot'] . create_url('rule/post', array('module' => 'superdesk_dish', 'name' => '微点餐')));
    exit;
} else {
    header('Location: ' . $_W['siteroot'] . create_url('rule/post', array('module' => 'superdesk_dish', 'id' => $rule['id'])));
    exit;
}