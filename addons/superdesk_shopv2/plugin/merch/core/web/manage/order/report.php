<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}
require SUPERDESK_SHOPV2_PLUGIN . 'merch/core/inc/page_merch.php';

class Report_SuperdeskShopV2Page extends MerchWebPage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        $params = array(':uniacid' => $_W['uniacid'],':merchid' => $_W['merchid']);

        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;

        $condition = '';
        $wherecondition = '';
        $condition .=  ' LEFT JOIN ' . tablename('superdesk_shop_merch_user') . ' m ON m.`id`= r.`merchid` ' ;

        //时间搜索
        if (empty($starttime) || empty($endtime)) {
            $starttime = strtotime('-1 month');
            $endtime   = time();
        }


        if (!empty($_GPC['time']['start']) && !empty($_GPC['time']['end'])) {

            $starttime = strtotime($_GPC['time']['start']);
            $endtime   = strtotime($_GPC['time']['end']);

            $wherecondition .=
                ' AND r.`createtime` >= :starttime ' .
                ' AND r.`createtime` <= :endtime ';

            $params[':starttime'] = $starttime;
            $params[':endtime']   = $endtime;
        }

        $wherecondition .= ' AND r.merchid = :merchid ';

        if(empty($_PGC['export'])) {
            $report = pdo_fetchall('SELECT r.*,m.uniacid,m.merchname FROM ' . tablename('superdesk_shop_comments_report') . ' r ' . $condition . ' WHERE ' . ' m.uniacid = :uniacid ' . $wherecondition . ' LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize, $params);
        } else {
            $report = pdo_fetchall('SELECT r.*,m.uniacid,m.merchname FROM ' . tablename('superdesk_shop_comments_report') . ' r ' . $condition . ' WHERE ' . ' m.uniacid = :uniacid ' . $wherecondition , $params);

        }

        //导出excel
        if ($_GPC['export'] == '1') {
            plog('order.report', '导出商户报表数据');

            foreach ($report as &$v) {
                $v['createtime'] = date('Y-m-d H:i:s',$v['createtime']);
            }

            m('excel')->export($report,array(
                'title' => '商户报表数据-' . date('Y-m-d-H-i', time()),
                'columns' => array(
                    array('title' => '商户名', 'field' => 'merchname', 'width' => 12),
                    array('title' => '综合物流', 'field' => 'com_logis', 'width' => 12),
                    array('title' => '综合服务', 'field' => 'com_service', 'width' => 12),
                    array('title' => '综合描述', 'field' => 'com_describes', 'width' => 24),
                    array('title' => '综合以上三项', 'field' => 'compr', 'width' => 24),
                    array('title' => '备注', 'field' => 'remark', 'width' => 24),
                    array('title' => '统计时间', 'field' => 'createtime', 'width' => 24),
                )
            ));

        }


        $total = pdo_fetchcolumn('SELECT count(1) FROM ' . tablename('superdesk_shop_comments_report') . ' r ' . $condition .  ' WHERE ' . ' m.uniacid = :uniacid '  . $wherecondition ,$params);

        $pager = pagination($total, $pindex, $psize);

        include $this->template();

    }


}