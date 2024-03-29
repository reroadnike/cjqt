<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}


global $_W;
global $_GPC;

//check_shop_auth('http://120.26.212.219/api.php');

$id  = intval($_GPC['id']);
$log = pdo_fetch(
    'select * ' .
    ' from ' . tablename('superdesk_shop_creditshop_log') . // TODO 标志 楼宇之窗 openid shop_creditshop_log 不处理
    ' where ' .
    '       id=:id ' .
    '       and uniacid=:uniacid ' .
    ' limit 1',
    array(
        ':id'      => $id,
        ':uniacid' => $_W['uniacid']
    )
);

$store   = false;
$address = false;
$carrier = false;

if ($log['isverify']) {
    if (!empty($log['storeid'])) {
        $store   = pdo_fetch(
            'select ' .
            '       id,storename,address ' .
            ' from ' . tablename('superdesk_shop_store') .
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':id'      => $log['storeid'],
                ':uniacid' => $_W['uniacid']
            )
        );
        $carrier = array(
            'realname'  => $row['realname'],
            'mobile'    => $row['mobile'],
            'storename' => $store['storename'],
            'address'   => $store['address']
        );
    }

} else {

    $address = iunserializer($log['address']);

    if (!is_array($address)) {
        $address = pdo_fetch(
            'select ' .
            '       realname,mobile,address,province,city,area ' .
            ' from ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 不处理
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':id'      => $row['addressid'],
                ':uniacid' => $_W['uniacid']
            )
        );
    }

}

load()->func('tpl');
include $this->template('exchange');