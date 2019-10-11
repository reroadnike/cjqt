<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

class User_SuperdeskShopV2Model
{
    private $sessionid;

    public function __construct()
    {
        global $_W;

        $this->sessionid = '__cookie_superdesk_shop_201507200000_' . $_W['uniacid'];
    }

    public function getOpenid()
    {
        $userinfo = $this->getInfo(false, true);
        return $userinfo['openid'];
    }

    public function getCoreUser(){

        global $_W;

        return $_W['core_user'];
    }

    public function getInfo($base64 = false, $debug = false)
    {
        global $_W;
        global $_GPC;

        $userinfo = array();

        if (SUPERDESK_SHOPV2_DEBUG) {

            $userinfo = array(
                'openid'     => SUPERDESK_SHOPV2_DEBUG_OPENID,
                'nickname'   => '雨',
                'headimgurl' => '',
                'province'   => '广东',
                'city'       => '深圳'
            );

        } else {

            load()->model('mc');

            $userinfo = mc_oauth_userinfo();

//            socket_log('mark :: addons/superdesk_shopv2/core/model/user.php');
//            socket_log(json_encode($userinfo, JSON_UNESCAPED_UNICODE));

            $need_openid = true;

            if ($_W['container'] != 'wechat') {

                if (($_GPC['do'] == 'order') && ($_GPC['p'] == 'pay')) {
                    $need_openid = false;
                }

                if (($_GPC['do'] == 'member') && ($_GPC['p'] == 'recharge')) {
                    $need_openid = false;
                }

                if (($_GPC['do'] == 'plugin') && ($_GPC['p'] == 'article') && ($_GPC['preview'] == '1')) {
                    $need_openid = false;
                }

            }
        }

        if ($base64) {
            return urlencode(base64_encode(json_encode($userinfo)));
        }

        return $userinfo;
    }

    public function followed($openid = '')
    {
        global $_W;

        $followed = !empty($openid);

        if ($followed) {

            $mf = pdo_fetch(
                ' select follow ' .
                ' from ' . tablename('mc_mapping_fans') .
                ' where openid=:openid ' .
                '       and uniacid=:uniacid ' .
                ' limit 1',
                array(
                    ':openid'  => $openid,
                    ':uniacid' => $_W['uniacid']
                )
            );

            $followed = $mf['follow'] == 1;
        }
        
        return $followed;
    }
}