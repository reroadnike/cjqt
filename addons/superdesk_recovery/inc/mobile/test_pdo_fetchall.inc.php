<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 6/7/18
 * Time: 5:59 PM
 *
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_recovery&do=test_pdo_fetchall
 */



$manager_arr = pdo_fetchall(
    ' select openid,mobile ' .
    ' from ' . tablename('superdesk_shop_member') .
    ' where core_enterprise=:core_enterprise ' .
    '       and uniacid=:uniacid ' .
    '       and cash_role_id=:roleid ',
    array(
        ':uniacid'         => 16,
        ':core_enterprise' => 56,
        ':roleid'          => 9
    )
);

var_dump($manager_arr);


//array(1) {
//    [0]=>
//  array(2) {
//        ["openid"]=>
//    string(28) "oX8KYwiNR5JrUFaIs5TZPDL9yrXI"
//        ["mobile"]=>
//    string(11) "18938773714"
//  }
//}



//array(0) {
//}
