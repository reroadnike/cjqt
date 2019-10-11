<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

require SUPERDESK_SHOPV2_PLUGIN . 'enterprise/core/inc/page_enterprise.php';

class Test_SuperdeskShopV2Page extends EnterpriseWebPage
{
    public function main()
    {
        global $_W;
        global $_GPC;
        die;
        //6 超级前台湘江内购,8 中航善达股份有限公司,9 内购平台体验企业,
        $enterprise_id = 6;

        $memberAll = pdo_fetchall(
            ' select ' .
            '       openid,realname,mobile,credit2 ' .
            ' from ' . tablename('superdesk_shop_member') .
            ' where ' .
            '       uniacid=:uniacid and core_enterprise=:core_enterprise',
            array(
                ':uniacid'         => $_W['uniacid'],
                ':core_enterprise' => $enterprise_id
            )
        );

        foreach ($memberAll as $k => $v) {

            $importMoney = pdo_fetchall(
                ' select id,price,createtime,old_price from ' . tablename('superdesk_shop_enterprise_import_log') .// TODO 标志 楼宇之窗 openid shop_enterprise_import_log 已处理
                ' where uniacid=:uniacid and openid=:openid group by openid,uniacid,mobile,price',
                array(':uniacid' => $_W['uniacid'], ':openid' => $v['openid'])
            );

//			print_r($importMoney);die;

            foreach ($importMoney as $ik => $iv) {
                $insert = array(
                    'openid'      => $v['openid'],
                    'mobile'      => $v['mobile'],
                    'price'       => $iv['price'],
                    'type'        => 1,
                    'finish_time' => $iv['createtime'],
                    'createtime'  => $iv['createtime'],
                    'orderid'     => $iv['id'],
                    'old_price'   => $iv['old_price'],
                );

                pdo_insert('superdesk_shop_member_credit_log', $insert);
            }

            $rechargeMoney = pdo_fetchall(
                ' select ' .
                '       id,money,status ' .
                ' from ' . tablename('superdesk_shop_member_log') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_log 待处理
                ' where uniacid=:uniacid and openid=:openid',
                array(
                    ':uniacid' => $_W['uniacid'],
                    ':openid'  => $v['openid'])

            );

            foreach ($rechargeMoney as $rk => $rv) {
                $insert = array(
                    'openid'  => $v['openid'],
                    'mobile'  => $v['mobile'],
                    'price'   => $rv['money'],
                    'type'    => 2,
                    'status'  => $rv['status'],
                    'orderid' => $rv['id']
                );

                pdo_insert('superdesk_shop_member_credit_log', $insert);
            }

            $orderMoney = pdo_fetchall(
                ' select id,price,finishtime,createtime,paytime,refundid,refundstate,refundtime,status ' .
                ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
                ' where uniacid=:uniacid and openid=:openid and core_user=:core_user and paytype=1',
                array(
                    ':uniacid'   => $_W['uniacid'],
                    ':openid'    => $v['openid'],
                    ':core_user' => $v['core_user']
                )
            );

            foreach ($orderMoney as $ok => $ov) {
                $insert = array(
                    'openid'      => $v['openid'],
                    'mobile'      => $v['mobile'],
                    'price'       => $ov['price'],
                    'type'        => 3,
                    'finish_time' => $ov['paytime'],
                    'createtime'  => $ov['createtime'],
                    'orderid'     => $ov['id'],
                    'refundid'    => $ov['refundid'],
                    'refundstate' => $ov['refundstate'],
                    'refundtime'  => $ov['refundtime'],
                    'status'      => $ov['status']
                );

                pdo_insert('superdesk_shop_member_credit_log', $insert);
            }

        }

        show_json(1, $memberAll);
    }
}


?>