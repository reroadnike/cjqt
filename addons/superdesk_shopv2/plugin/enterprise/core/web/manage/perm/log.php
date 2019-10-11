<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}


require SUPERDESK_SHOPV2_PLUGIN . 'enterprise/core/inc/page_enterprise.php';

class Log_SuperdeskShopV2Page extends EnterpriseWebPage
{
    public function main()
    {
        global $_W;
        global $_GPC;
        $pindex    = max(1, intval($_GPC['page']));
        $psize     = 20;
        $condition = ' and log.uniacid=:uniacid and log.enterprise_id=:enterprise_id';
        $params    = array(':uniacid' => $_W['uniacid'], ':enterprise_id' => $_W['enterprise_id']);

        if (!empty($_GPC['keyword'])) {
            $_GPC['keyword'] = trim($_GPC['keyword']);
            $condition .= ' and ( log.op like :keyword or u.username like :keyword)';
            $params[':keyword'] = '%' . $_GPC['keyword'] . '%';
        }


        if (!empty($_GPC['logtype'])) {
            $condition .= ' and log.type=:logtype';
            $params[':logtype'] = trim($_GPC['logtype']);
        }


        if (empty($starttime) || empty($endtime)) {
            $starttime = strtotime('-1 month');
            $endtime   = time();
        }


        if (!empty($_GPC['searchtime'])) {
            $starttime = strtotime($_GPC['time']['start']);
            $endtime   = strtotime($_GPC['time']['end']);

            if (!empty($timetype)) {
                $condition .= ' AND log.createtime >= :starttime AND log.createtime <= :endtime ';
                $params[':starttime'] = $starttime;
                $params[':endtime']   = $endtime;
            }

        }


        $list  = pdo_fetchall(
            'SELECT  log.* ,u.username FROM ' . tablename('superdesk_shop_enterprise_perm_log') . ' log  ' .
            ' left join ' . tablename('superdesk_shop_enterprise_account') . ' u on log.enterprise_id = u.enterprise_id  ' .// TODO 标志 楼宇之窗 openid shop_enterprise_account 不处理
            ' WHERE 1 ' . $condition . ' ORDER BY id desc LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize,
            $params
        );

        $total = pdo_fetchcolumn(
            'SELECT count(*) FROM ' . tablename('superdesk_shop_enterprise_perm_log') . ' log  ' .
            ' left join ' . tablename('superdesk_shop_enterprise_account') . ' u on log.enterprise_id = u.enterprise_id  ' .// TODO 标志 楼宇之窗 openid shop_enterprise_account 不处理
            ' WHERE 1 ' . $condition . ' ',
            $params
        );

        $pager = pagination($total, $pindex, $psize);
        $types = p('enterprise')->getLogTypes();
        include $this->template();
    }
}


?>