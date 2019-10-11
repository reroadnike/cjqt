<?php

/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/5/17
 * Time: 7:49 PM
 * http://localhost/superdesk/web/superdesk_shopv2_enterprise.php?c=site&a=entry&m=superdesk_shopv2&do=web
 */


define('IN_SYS', true);
require '../framework/bootstrap.inc.php';
load()->web('common');
load()->web('template');
header('Content-Type: text/html; charset=UTF-8');
$uniacid = intval($_GPC['i']);
$cookie = $_GPC['__uniacid'];

if (empty($uniacid) && empty($cookie)) {
    exit('Access Denied.');
}


session_start();

$_W['template'] = 'default';

if (!empty($uniacid)) {
    $_SESSION['__enterprise_uniacid'] = $uniacid;
    isetcookie('__uniacid', $uniacid, 7 * 86400);
}

$site = WeUtility::createModuleSite('superdesk_shopv2');

if (!is_error($site)) {
    $method = 'doWebWeb';
    $site->uniacid = $uniacid;
    $site->inMobile = false;

    if (method_exists($site, $method)) {
        $site->$method();
        exit();
    }

}

