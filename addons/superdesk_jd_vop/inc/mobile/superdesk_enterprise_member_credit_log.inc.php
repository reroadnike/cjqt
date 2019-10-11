<?php
/**
 * Created by PhpStorm.
 * User: zjh
 * Date: 2018年9月8日 15:04:02
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=superdesk_enterprise_member_credit_log
 */

global $_W;
global $_GPC;

//6 超级前台湘江内购,8 中航善达股份有限公司,9 内购平台体验企业,
$enterprise_id = 9;

$memberAll = pdo_fetchall(
    ' select openid,core_user,realname,mobile,credit2 from ' . tablename('superdesk_shop_member') .// TODO 标志 楼宇之窗 openid shop_order 已处理
    ' where uniacid=:uniacid and core_enterprise=:core_enterprise',
    array(':uniacid' => $_W['uniacid'], ':core_enterprise' => $enterprise_id)
);

$check_1 = array();

foreach ($memberAll as $k => $v) {

    $importMoney = pdo_fetchall(
        ' select price from ' . tablename('superdesk_shop_enterprise_import_log') .// TODO 标志 楼宇之窗 openid shop_enterprise_import_log 已处理
        ' where uniacid=:uniacid and openid=:openid and core_user=:core_user group by openid,core_user,uniacid,mobile,price',// TODO 标志 楼宇之窗 openid shop_order 已处理
        array(':uniacid' => $_W['uniacid'], ':openid' => $v['openid'], ':core_user' => $v['core_user'])
    );

    $importMoney = array_sum(array_column($importMoney, 'price'));

    if ($v['credit2'] != $importMoney) {
        $return_one                 = $v;
        $return_one['import_price'] = $importMoney;
        $return_one['total_price']  = $importMoney;
        $check_1[]                  = $return_one;
    }
}

//show_json(1,$check_1);

$check_2 = array();

foreach ($check_1 as $k => $v) {
    $rechargeMoney = pdo_fetchcolumn(
        ' select sum(money) from ' . tablename('superdesk_shop_member_log') .
        ' where uniacid=:uniacid and openid=:openid and core_user=:core_user and status=1',// TODO 标志 楼宇之窗 openid shop_order 已处理
        array(':uniacid' => $_W['uniacid'], ':openid' => $v['openid'], ':core_user' => $v['core_user'])
    );

    if ($v['credit2'] != round(floatval($v['total_price'] + $rechargeMoney), 2)) {
        $return_one                   = $v;
        $return_one['recharge_price'] = $rechargeMoney;
        $return_one['total_price']    = round(floatval($v['total_price'] + $rechargeMoney), 2);
        $check_2[]                    = $return_one;
    }
}

//show_json(1,$check_2);

$check_3 = array();

foreach ($check_2 as $k => $v) {
    $orderMoney = pdo_fetchcolumn(
        ' select sum(price) from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
        ' where uniacid=:uniacid and openid=:openid and core_user=:core_user and paytype=1 and status > 0 and parentid = 0',
        array(':uniacid' => $_W['uniacid'], ':openid' => $v['openid'], ':core_user' => $v['core_user'])
    );

    if ($v['credit2'] != round(floatval($v['total_price'] - $orderMoney), 2)) {
        $return_one                      = $v;
        $return_one['order_price']       = $orderMoney;
        $return_one['total_price']       = round(floatval($v['total_price'] - $orderMoney), 2);
        $return_one['cha_price']         = round(floatval($return_one['total_price'] - $v['credit2']), 2);
        $return_one['real_import_price'] = round(floatval($return_one['import_price'] - $return_one['cha_price']), 2);

        $check_3[] = $return_one;
    }
}

//show_json(1,$check_3);

$check_4 = array();

foreach ($check_3 as $k => $v) {
    $refundMoney = pdo_fetchcolumn(
        ' select sum(orf.applyprice) from ' . tablename('superdesk_shop_order') . ' as o ' .// TODO 标志 楼宇之窗 openid shop_order 已处理
        ' left join ' . tablename('superdesk_shop_order_refund') . ' as orf on orf.orderid = o.id ' .
        ' where o.uniacid=:uniacid and o.openid=:openid and core_user=:core_user and o.paytype=1 and o.refundid > 0',
        array(':uniacid' => $_W['uniacid'], ':openid' => $v['openid'], ':core_user' => $v['core_user'])
    );

    $return_one                 = $v;
    $return_one['refund_price'] = $refundMoney;
    $check_4[]                  = $return_one;
}

show_json(1, $check_4);

foreach ($memberAll as $k => $v) {

    $importMoney = pdo_fetchall(
        ' select id,price,createtime,old_price from ' . tablename('superdesk_shop_enterprise_import_log') .// TODO 标志 楼宇之窗 openid shop_enterprise_import_log 已处理
        ' where uniacid=:uniacid and openid=:openid and core_user=:core_user group by openid,core_user,uniacid,mobile,price',// TODO 标志 楼宇之窗 openid shop_order 已处理
        array(':uniacid' => $_W['uniacid'], ':openid' => $v['openid'], ':core_user' => $v['core_user'])
    );


    foreach ($importMoney as $ik => $iv) {
        $insert = array(
            'openid'       => $v['openid'],
            'core_user'    => $v['core_user'],
            'mobile'       => $v['mobile'],
            'price'        => $iv['price'],
            'type'         => 1,
            'finish_time' => $iv['createtime'],
            'createtime'  => $iv['createtime'],
            'orderid'     => $iv['id'],
            'old_price'   => $iv['old_price'],
        );

        pdo_insert('superdesk_shop_member_credit_log', $insert);
    }

    $rechargeMoney = pdo_fetchall(
        ' select id,money,status from ' . tablename('superdesk_shop_member_log') .
        ' where uniacid=:uniacid and openid=:openid and core_user=:core_user',// TODO 标志 楼宇之窗 openid shop_order 已处理
        array(':uniacid' => $_W['uniacid'], ':openid' => $v['openid'], ':core_user' => $v['core_user'])
    );

    foreach ($rechargeMoney as $rk => $rv) {
        $insert = array(
            'openid'    => $v['openid'],
            'core_user' => $v['core_user'],
            'mobile'    => $v['mobile'],
            'price'     => $rv['money'],
            'type'      => 2,
            'status'    => $rv['status'],
            'orderid'  => $rv['id']
        );

        pdo_insert('superdesk_shop_member_credit_log', $insert);
    }

    $orderMoney = pdo_fetchall(
        ' select id,price,finishtime,createtime,paytime,refundid,refundstate,refundtime,status from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
        ' where uniacid=:uniacid and openid=:openid and core_user=:core_user and paytype=1',
        array(':uniacid' => $_W['uniacid'], ':openid' => $v['openid'], ':core_user' => $v['core_user'])
    );

    foreach ($orderMoney as $ok => $ov) {
        $insert = array(
            'openid'       => $v['openid'],
            'core_user'    => $v['core_user'],
            'mobile'       => $v['mobile'],
            'price'        => $ov['price'],
            'type'         => 3,
            'finish_time' => $ov['paytime'],
            'createtime'  => $ov['createtime'],
            'orderid'     => $ov['id'],
            'refundid'    => $ov['refundid'],
            'refundstate' => $ov['refundstate'],
            'refundtime'  => $ov['refundtime'],
            'status'      => $ov['status']
        );

        pdo_insert('superdesk_shop_member_credit_log', $insert);

        if (!empty($ov['refundid'])) {
            $refundMoney = pdo_fetchall(
                ' select id,applyprice,createtime,refundtime,status from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
                ' where uniacid=:uniacid and openid=:openid and core_user=:core_user and rtype != 2 and refundtype == 0',
                array(':uniacid' => $_W['uniacid'], ':openid' => $v['openid'], ':core_user' => $v['core_user'])
            );

            foreach ($rechargeMoney as $rfk => $rfv) {
                $insert = array(
                    'openid'       => $v['openid'],
                    'core_user'    => $v['core_user'],
                    'mobile'       => $v['mobile'],
                    'price'        => $rfv['applyprice'],
                    'type'         => 4,
                    'finish_time' => $rfv['refundtime'],
                    'createtime'  => $rfv['createtime'],
                    'orderid'     => $rfv['id'],
                    'status'      => $rfv['status']
                );

                pdo_insert('superdesk_shop_member_credit_log', $insert);
            }


        }

    }

}

show_json(1, $memberAll);