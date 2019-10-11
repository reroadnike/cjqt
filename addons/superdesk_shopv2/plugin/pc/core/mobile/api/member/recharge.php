<?php

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/accessControl.php";

if (!(defined("IN_IA"))) {
    exit("Access Denied");
}

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/page_login_mobile.php";

class Recharge_SuperdeskShopV2Page extends PcMobileLoginPage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        $set                      = $_W['shopset'];
        $set['pay']['weixin']     = (!empty($set['pay']['weixin_sub']) ? 1 : $set['pay']['weixin']);
        $set['pay']['weixin_jie'] = (!empty($set['pay']['weixin_jie_sub']) ? 1 : $set['pay']['weixin_jie']);
        $sec                      = m('common')->getSec();
        $sec                      = iunserializer($sec['sec']);
        $status                   = 1;

        if (!empty($set['trade']['closerecharge'])) {
            show_json(0, '未开启充值');
        }

        //这个是最低充值金额
        if (empty($set['trade']['minimumcharge'])) {
            $minimumcharge = 0;
        } else {
            $minimumcharge = $set['trade']['minimumcharge'];
        }

        $payinfo = array(
            'wechat'        => (!empty($sec['app_wechat']['merchname']) && !empty($set['pay']['app_wechat']) && !empty($sec['app_wechat']['appid']) && !empty($sec['app_wechat']['appsecret']) && !empty($sec['app_wechat']['merchid']) && !empty($sec['app_wechat']['apikey']) ? true : false),
            'alipay'        => (!empty($set['pay']['app_alipay']) && !empty($sec['app_alipay']['public_key']) ? true : false),
            'mcname'        => $sec['app_wechat']['merchname'],    //不知道干嘛的,出现在h5app.js,可能是H5微信应用名称
            'aliname'       => (empty($_W['shopset']['shop']['name']) ? $sec['app_wechat']['merchname'] : $_W['shopset']['shop']['name']),    //不知道干嘛的,出现在h5app.js,可能是H5阿里应用名称
            'logno'         => NULL,
            'money'         => NULL,
            'attach'        => $_W['uniacid'] . ':1',  //不知道干嘛的,也没看到html那边有用上
            'type'          => 1,
            'minimumcharge' => $minimumcharge
        );

        $acts = com_run('sale::getRechargeActivity');   //充值活动


        show_json(1, compact('payinfo', 'acts'));
    }

    public function submit()
    {
        global $_W;
        global $_GPC;

        $set = $_W['shopset'];

        if (empty($set['trade']['minimumcharge'])) {
            $minimumcharge = 0;
        } else {
            $minimumcharge = $set['trade']['minimumcharge'];
        }

        $money = floatval($_GPC['money']);
        if ($money <= 0) {
            show_json(0, '充值金额必须大于0!');
        }

        if (($money < $minimumcharge) && (0 < $minimumcharge)) {
            show_json(0, '最低充值金额为' . $minimumcharge . '元!');
        }

        if (empty($money)) {
            show_json(0, '请填写充值金额!');
        }

        pdo_delete(
            'superdesk_shop_member_log', // TODO 标志 楼宇之窗 openid superdesk_shop_member_log 已处理
            array(
                'openid'    => $_W['openid'],
                'core_user' => $_W['core_user'],
                'status'    => 0,
                'type'      => 0,
                'uniacid'   => $_W['uniacid']
            )
        );

        $logno = m('common')->createNO('member_log', 'logno', 'RC');

        $log = array(
            'uniacid'    => $_W['uniacid'],
            'logno'      => $logno,
            'title'      => $set['shop']['name'] . '会员充值',
            'openid'     => $_W['openid'],
            'core_user'  => $_W['core_user'],
            'money'      => $money,
            'type'       => 0,
            'createtime' => time(),
            'status'     => 0,
            'couponid'   => intval($_GPC['couponid'])
        );

        pdo_insert('superdesk_shop_member_log', $log); // TODO 标志 楼宇之窗 openid superdesk_shop_member_log 已处理
        $logid = pdo_insertid();

        $type = $_GPC['type'];

        if ($type == 'wechat') {

            $set                      = m('common')->getSysset(array('shop', 'pay'));
            $set['pay']['weixin']     = (!empty($set['pay']['weixin_sub']) ? 1 : $set['pay']['weixin']);
            $set['pay']['weixin_jie'] = (!empty($set['pay']['weixin_jie_sub']) ? 1 : $set['pay']['weixin_jie']);

            if (!is_weixin()) {
                show_json(0, '非微信环境!');
            }

            if (empty($set['pay']['weixin']) && empty($set['pay']['weixin_jie'])) {
                show_json(0, '未开启微信支付!');
            }

            $wechat = array('success' => false);
            $jie    = intval($_GPC['jie']);

            $params = array();

            $params['tid']   = $log['logno'];
            $params['user']  = $_W['openid'];
            $params['fee']   = $money;
            $params['title'] = $log['title'];

            if (isset($set['pay']) && ($set['pay']['weixin'] == 1) && ($jie !== 1)) {

                load()->model('payment');
                $setting = uni_setting($_W['uniacid'], array('payment'));
                $options = array();

                if (is_array($setting['payment'])) {
                    $options           = $setting['payment']['wechat'];
                    $options['appid']  = $_W['account']['key'];
                    $options['secret'] = $_W['account']['secret'];
                }

                $wechat = m('common')->wechat_build($params, $options, 1, 'NATIVE');

                if (!is_error($wechat)) {
                    $wechat['weixin']  = true;
                    $wechat['success'] = true;
                }
            }

            if ((isset($set['pay']) && ($set['pay']['weixin_jie'] == 1) && !$wechat['success']) || ($jie === 1)) {

                $params['tid'] = $params['tid'] . '_borrow';
                $sec           = m('common')->getSec();
                $sec           = iunserializer($sec['sec']);

                $options = array();

                $options['appid']  = $sec['appid'];
                $options['mchid']  = $sec['mchid'];
                $options['apikey'] = $sec['apikey'];

                $wechat = m('common')->wechat_native_build($params, $options, 1);

                if (!is_error($wechat)) {
                    $wechat['success']    = true;
                    $wechat['weixin_jie'] = true;
                }
            }

            $wechat['jie'] = $jie;

            if (!$wechat['success']) {
                show_json(0, '微信支付参数错误!');
            }

            show_json(1, array('wechat' => $wechat, 'logno' => $logno));

        } else if ($type == 'alipay') {

            $alipay = array('success' => false);
            $params = array();

            $params['tid']   = $log['logno'];
            $params['user']  = $_W['openid'];
            $params['fee']   = $money;
            $params['title'] = $log['title'];

            load()->func('communication');
            load()->model('payment');

            $setting = uni_setting($_W['uniacid'], array('payment'));

            if (is_array($setting['payment'])) {

                $options = $setting['payment']['alipay'];
                $alipay  = m('common')->alipay_build($params, $options, 1, $_W['openid']);

                if (!empty($alipay['url'])) {
                    $alipay['url']     = urlencode($alipay['url']);
                    $alipay['success'] = true;
                }
            }

            show_json(1, array('alipay' => $alipay, 'logno' => $logno));
        }

        show_json(0, '未找到支付方式');
    }

    public function getstatus()
    {
        global $_W;
        global $_GPC;

        $logno = $_GPC['logno'];

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
            show_json(1);
        }

        show_json(0);
    }
}