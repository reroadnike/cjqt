<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

//mark kafka 为了kafka转成了model执行
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order.class.php');

class Pay_Alipay_SuperdeskShopV2Page extends MobilePage
{
    private $_orderModel;


    public function __construct()
    {

        global $_W;
        global $_GPC;
        parent::__construct();

        $this->_orderModel = new orderModel();
    }

    public function main()
    {
        global $_W;
        global $_GPC;
        $url = urldecode($_GPC['url']);

        if (!is_weixin()) {
            header('location: ' . $url);
            exit();
        }

        include $this->template('order/alipay');
    }

    public function complete()
    {
        global $_GPC;
        global $_W;

        $set        = m('common')->getSysset(array('shop', 'pay'));
        $fromwechat = intval($_GPC['fromwechat']);
        $tid        = $_GPC['out_trade_no'];

        if (is_h5app()) {

            $sec = m('common')->getSec();
            $sec = iunserializer($sec['sec']);

            $public_key = $sec['app_alipay']['public_key'];

            if (empty($set['pay']['app_alipay']) || empty($public_key)) {
                $this->message('支付出现错误，请重试(1)!', mobileUrl('order'));
            }

            $alidata = base64_decode($_GET['alidata']);
            $alidata = json_decode($alidata, true);

            $alisign = m('finance')->RSAVerify($alidata, $public_key, false);

            $tid = $this->str($alidata['out_trade_no']);

            if ($alisign == 0) {
                $this->message('支付出现错误，请重试(2)!', mobileUrl('order'));
            }

        } else {
            if (empty($set['pay']['alipay'])) {
                $this->message('未开启支付宝支付!', mobileUrl('order'));
            }


            if (!m('finance')->isAlipayNotify($_GET)) {

                $log = pdo_fetch(
                    'SELECT * ' .
                    ' FROM ' . tablename('core_paylog') .
                    ' WHERE ' .
                    '       `uniacid`=:uniacid ' .
                    '       AND `module`=:module ' .
                    '       AND `tid`=:tid ' .
                    ' limit 1',
                    array(
                        ':uniacid' => $_W['uniacid'],
                        ':module'  => 'superdesk_shopv2',
                        ':tid'     => $tid
                    )
                );

                if (($log['status'] == 1) && ($log['fee'] == $_GPC['total_fee'])) {
                    if ($fromwechat) {
                        $this->message(
                            array(
                                'message'       => '请返回微信查看支付状态',
                                'title'         => '支付成功!',
                                'buttondisplay' => false
                            ), NULL, 'success');
                    } else {
                        $this->message(
                            array(
                                'message' => '请返回商城查看支付状态',
                                'title'   => '支付成功!'
                            ),
                            mobileUrl('order'),
                            'success'
                        );
                    }
                }


                $this->message(
                    array(
                        'message'       => '支付出现错误，请重试(支付验证失败)!',
                        'buttondisplay' => ($fromwechat ? false : true)
                    ),
                    ($fromwechat ? NULL : mobileUrl('order'))
                );
            }

        }

        $log = pdo_fetch(
            'SELECT * ' .
            ' FROM ' . tablename('core_paylog') .
            ' WHERE ' .
            '       `uniacid`=:uniacid ' .
            '       AND `module`=:module ' .
            '       AND `tid`=:tid ' .
            ' limit 1',
            array(
                ':uniacid' => $_W['uniacid'],
                ':module'  => 'superdesk_shopv2',
                ':tid'     => $tid
            )
        );

        if (empty($log)) {
            $this->message(
                array(
                    'message'       => '支付出现错误，请重试(支付验证失败2)!',
                    'buttondisplay' => ($fromwechat ? false : true)
                ),
                ($fromwechat ? NULL : mobileUrl('order'))
            );
        }


        if (is_h5app()) {
            $alidatafee    = $this->str($alidata['total_fee']);
            $alidatastatus = $this->str($alidata['success']);
            if (($log['fee'] != $alidatafee) || !$alidatastatus) {
                $this->message('支付出现错误，请重试(4)!', mobileUrl('order'));
            }
        }


        if ($log['status'] != 1) {

            $record           = array();
            $record['status'] = '1';
            $record['type']   = 'alipay';
            pdo_update('core_paylog', $record, array('plid' => $log['plid']));


            $ret            = array();
            $ret['result']  = 'success';
            $ret['type']    = 'alipay';
            $ret['from']    = 'return';
            $ret['tid']     = $log['tid'];
            $ret['user']    = $log['openid'];
            $ret['fee']     = $log['fee'];
            $ret['weid']    = $log['weid'];
            $ret['uniacid'] = $log['uniacid'];
            m('order')->payResult($ret);
        }


        $orderid = pdo_fetchcolumn(
            ' select id from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 不处理
            ' where ordersn=:ordersn and uniacid=:uniacid',
            array(
                ':ordersn' => $log['tid'],
                ':uniacid' => $_W['uniacid']
            )
        );

        if (!empty($orderid)) {
            m('order')->setOrderPayType($orderid, 22);

            if (is_h5app()) {
                //mark kafka 为了kafka转成了model执行
                $this->_orderModel->updateByColumn(
                    array(
                        'apppay' => 1
                    ),
                    array(
                        'id' => $orderid
                    )
                );
            }

        }


        if (is_h5app()) {
            $url = mobileUrl('order/detail', array('id' => $orderid), true);
            exit('<script>top.window.location.href=\'' . $url . '\'</script>');
            return;
        }
        if ($fromwechat) {
            $this->message(array('message' => '请返回微信查看支付状态', 'title' => '支付成功!', 'buttondisplay' => false), NULL, 'success');
            return;
        }


        $this->message(array('message' => '请返回商城查看支付状态', 'title' => '支付成功!'), mobileUrl('order'), 'success');
    }

    public function recharge_complete()
    {
        global $_W;
        global $_GPC;

        $fromwechat = intval($_GPC['fromwechat']);
        $logno      = trim($_GPC['out_trade_no']);
        $notify_id  = trim($_GPC['notify_id']);
        $sign       = trim($_GPC['sign']);
        $set        = m('common')->getSysset(array('shop', 'pay'));

        if (is_h5app()) {
            $sec        = m('common')->getSec();
            $sec        = iunserializer($sec['sec']);
            $public_key = $sec['app_alipay']['public_key'];

            if (empty($_GET['alidata'])) {
                $this->message('支付出现错误，请重试(1)!', mobileUrl('member'));
            }


            if (empty($set['pay']['app_alipay']) || empty($public_key)) {
                $this->message('支付出现错误，请重试(2)!', mobileUrl('order'));
            }


            $alidata = base64_decode($_GET['alidata']);
            $alidata = json_decode($alidata, true);
            $alisign = m('finance')->RSAVerify($alidata, $public_key, false);
            $logno   = $this->str($alidata['out_trade_no']);

            if ($alisign == 0) {
                $this->message('支付出现错误，请重试(3)!', mobileUrl('member'));
            }

        } else {

            if (empty($logno)) {
                $this->message(array('message' => '支付出现错误，请重试(支付验证失败1)!', 'buttondisplay' => ($fromwechat ? false : true)), ($fromwechat ? NULL : mobileUrl('member')));
            }


            if (empty($set['pay']['alipay'])) {
                $this->message(array('message' => '支付出现错误，请重试(未开启支付宝支付)!', 'buttondisplay' => ($fromwechat ? false : true)), ($fromwechat ? NULL : mobileUrl('member')));
            }


            if (!m('finance')->isAlipayNotify($_GET)) {

                $log = pdo_fetch(
                    'SELECT * ' .
                    ' FROM ' . tablename('superdesk_shop_member_log') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_log 不处理
                    ' WHERE ' .
                    '       `logno`=:logno ' .
                    '       and `uniacid`=:uniacid ' .
                    ' limit 1',
                    array(
                        ':uniacid' => $_W['uniacid'],
                        ':logno'   => $logno
                    )
                );

                if (!empty($log) && !empty($log['status'])) {
                    if ($fromwechat) {
                        $this->message(array('message' => '请返回微信查看支付状态', 'title' => '支付成功!', 'buttondisplay' => false), NULL, 'success');
                    } else {
                        $this->message(array('message' => '请返回商城查看支付状态', 'title' => '支付成功!'), mobileUrl('member'), 'success');
                    }
                }


                $this->message(array('message' => '支付出现错误，请重试(支付验证失败2)!', 'buttondisplay' => ($fromwechat ? false : true)), ($fromwechat ? NULL : mobileUrl('member')));
            }

        }

        $log = pdo_fetch(
            'SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_member_log') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_log 不处理
            ' WHERE ' .
            '       `logno`=:logno ' .
            '       and `uniacid`=:uniacid ' .
            ' limit 1',
            array(
                ':uniacid' => $_W['uniacid'],
                ':logno'   => $logno
            )
        );

        if (!empty($log) && empty($log['status'])) {

            pdo_update(
                'superdesk_shop_member_log', // TODO 标志 楼宇之窗 openid superdesk_shop_member_log 不处理
                array(
                    'status'       => 1,
                    'rechargetype' => 'alipay',
                    'apppay'       => (is_h5app() ? 1 : 0)
                ),
                array(
                    'id' => $log['id']
                )
            );

            m('member')->setCredit($log['openid'], $log['core_user'],
                'credit2', $log['money'], array(0, $_W['shopset']['shop']['name'] . '会员充值:alipayreturn:credit2:' . $log['money']));
            m('member')->setRechargeCredit($log['openid'], $log['core_user'], $log['money']);

            com_run('sale::setRechargeActivity', $log);
            com_run('coupon::useRechargeCoupon', $log);

            m('notice')->sendMemberLogMessage($log['id']);
        }


        if (is_h5app()) {
            $url = mobileUrl('member', NULL, true);
            exit('<script>top.window.location.href=\'' . $url . '\'</script>');
            return;
        }
        if ($fromwechat) {
            $this->message(array('message' => '请返回微信查看支付状态', 'title' => '支付成功!', 'buttondisplay' => false), NULL, 'success');
            return;
        }


        $this->message(
            array(
                'message' => '请返回商城查看支付状态',
                'title'   => '支付成功!'
            ),
            mobileUrl('member'),
            'success'
        );
    }

    protected function str($str)
    {
        $str = str_replace('"', '', $str);
        $str = str_replace('\'', '', $str);
        return $str;
    }
}


