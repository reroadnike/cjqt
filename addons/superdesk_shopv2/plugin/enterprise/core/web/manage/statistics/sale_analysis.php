<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

require SUPERDESK_SHOPV2_PLUGIN . 'enterprise/core/inc/page_enterprise.php';

class Sale_analysis_SuperdeskShopV2Page extends EnterpriseWebPage
{
    public function main()
    {
        function sale_analysis_count($sql)
        {
            $c = pdo_fetchcolumn($sql);
            return intval($c);
        }

        global $_W;
        global $_GPC;

        $member_count = sale_analysis_count(
            ' select count(*) from ' . tablename('superdesk_shop_member') .
            ' where uniacid=' . $_W['uniacid'] .
            '        and  core_user in ( SELECT distinct core_user from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 已处理
            '                          WHERE uniacid = \'' . $_W['uniacid'] . '\' and enterprise_id=\'' . $_W['enterprise_id'] . '\'  )');
//		$member_count = sale_analysis_count(
//			' select count(*) from ' . tablename('superdesk_shop_member') .
//			' where uniacid=' . $_W['uniacid'] .
//			'        and  openid in ( SELECT distinct openid from ' . tablename('superdesk_shop_order') .
//			'                          WHERE uniacid = \'' . $_W['uniacid'] . '\' and enterprise_id=\'' . $_W['enterprise_id'] . '\'  )');
        $orderprice      = sale_analysis_count(
            ' SELECT sum(price) FROM ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 不处理
            ' WHERE  status>=1 and uniacid = \'' . $_W['uniacid'] . '\' and enterprise_id=\'' . $_W['enterprise_id'] . '\' ');
        $ordercount      = sale_analysis_count(
            ' SELECT count(*) FROM ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 不处理
            ' WHERE status>=1 and uniacid = \'' . $_W['uniacid'] . '\' and enterprise_id=\'' . $_W['enterprise_id'] . '\'');
        $viewcount       = sale_analysis_count(
            ' SELECT sum(viewcount) FROM ' . tablename('superdesk_shop_goods') .
            ' WHERE uniacid = \'' . $_W['uniacid'] . '\' and enterprise_id=\'' . $_W['enterprise_id'] . '\'');
        $member_buycount = sale_analysis_count(
            ' select count(*) from ' . tablename('superdesk_shop_member') .
            ' where uniacid=' . $_W['uniacid'] .
            '        and  core_user in ( SELECT distinct core_user from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 已处理
            '                          WHERE uniacid = \'' . $_W['uniacid'] . '\' and enterprise_id=\'' . $_W['enterprise_id'] . '\' and status>=1 )');
//		$member_buycount = sale_analysis_count(
//			' select count(*) from ' . tablename('superdesk_shop_member') .
//			' where uniacid=' . $_W['uniacid'] .
//			'        and  openid in ( SELECT distinct openid from ' . tablename('superdesk_shop_order') .
//			'                          WHERE uniacid = \'' . $_aW['uniacid'] . '\' and enterprise_id=\'' . $_W['enterprise_id'] . '\' and status>=1 )');

        include $this->template('statistics/sale_analysis');
    }
}


