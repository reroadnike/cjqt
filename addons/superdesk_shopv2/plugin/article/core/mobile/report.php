<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Report_SuperdeskShopV2Page extends PluginMobileLoginPage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        $aid = intval($_GPC['aid']);

        include $this->template();
    }

    public function post()
    {
        global $_W;
        global $_GPC;

        $aid     = intval($_GPC['aid']);
        $cate    = trim($_GPC['cate']);
        $content = trim($_GPC['content']);

        $mid = m('member')->getMid();

        $openid    = $_W['openid'];
        $core_user = $_W['core_user'];

        if (!empty($aid) && !empty($cate) && !empty($content) && !empty($aid) && !empty($openid)) {

            $insert = array(
                'mid'       => $mid,
                'openid'    => $openid,
                'core_user' => $core_user,
                'aid'       => $aid,
                'cate'      => $cate,
                'cons'      => $content,
                'uniacid'   => $_W['uniacid']
            );

            pdo_insert(
                'superdesk_shop_article_report', // TODO 标志 楼宇之窗 openid shop_article_report 已处理
                $insert
            );
            show_json(1);
        }
        show_json(0);
    }
}