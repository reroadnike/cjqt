<?php

if (!(defined("IN_IA"))) {
    exit("Access Denied");
}

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/common.php";

class PcMobileLoginPage extends PluginMobileLoginPage
{
    public function __construct()
    {
        global $_W;
        global $_GPC;

        m("shop")->checkClose();

        $preview = intval($_GPC['preview']);

        $wap = m('common')->getSysset('wap');

//        if ($wap['open'] && !(is_weixin()) && empty($preview)) {
        $member = check_login();

        if ($this instanceof MobileLoginPage || $this instanceof PluginMobileLoginPage) {

            if (empty($member)) {
                show_json(0, '未登录');
            }
        }
//        }

        $_W['mid']       = ((!(empty($member)) ? $member['id'] : 0));
        $_W['openid']    = ((!(empty($member)) ? $member['openid'] : ''));
        $_W['core_user'] = ((!(empty($member)) ? $member['core_user'] : 0));
        $_W['core_enterprise'] = ((!(empty($member)) ? $member['core_enterprise'] : 0));

        $merch_plugin = p('merch');
        $merch_data   = m('common')->getPluginset('merch');

        if (!(empty($_GPC['merchid'])) && $merch_plugin && $merch_data['is_openmerch']) {

            $this->merch_user = pdo_fetch(
                ' select * ' .
                ' from ' . tablename('superdesk_shop_merch_user') .
                ' where ' .
                '       id=:id ' .
                ' limit 1',
                array(
                    ':id' => intval($_GPC['merchid'])
                )
            );
        }

        $this->model = m('plugin')->loadModel($GLOBALS['_W']['plugin']);

        $this->set = $this->model->getSet();

        m('account')->setFilterMerch($member);

        m('account')->updateLoginTime($member['id']);
    }
}