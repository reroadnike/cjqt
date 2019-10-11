<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

require SUPERDESK_SHOPV2_PLUGIN . 'globonus/core/page_login_mobile.php';

class Index_SuperdeskShopV2Page extends GlobonusMobileLoginPage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        $set       = $this->getSet();

        $member    = m('member')->getMember($_W['openid'], $_W['core_user']);

        $bonus     = $this->model->getBonus($_W['openid'], $_W['core_user'], array('ok', 'lock', 'total'));// TODO 标志 楼宇之窗 openid shop_globonus_billp 已处理

        $levelname = ((empty($set['levelname']) ? '默认等级' : $set['levelname']));

        $level     = $this->model->getLevel($_W['openid'], $_W['core_user']);

        if (!empty($level)) {
            $levelname = $level['levelname'];
        }


        $bonus_wait = 0;
        $year       = date('Y');
        $month      = intval(date('m'));
        $week       = 0;

        if ($set['paytype'] == 2) {
            $ds   = explode('-', date('Y-m-d'));
            $day  = intval($ds[2]);
            $week = ceil($day / 7);
        }

        $bonusall   = $this->model->getBonusData($year, $month, $week, $_W['openid']);
        $bonus_wait = $bonusall['partners'][0]['bonusmoney_send'];

        include $this->template();
    }
}