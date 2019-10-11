<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}


require SUPERDESK_SHOPV2_PLUGIN . 'merch/core/inc/page_merch.php';

class Member_increase_SuperdeskShopV2Page extends MerchWebPage
{
    public function main()
    {
        global $_W;
        global $_GPC;
        $days = intval($_GPC['days']);

        if (empty($_GPC['search'])) {
            $days = 7;
        }


        $years        = array();
        $current_year = date('Y');
        $year         = $_GPC['year'];
        $i            = $current_year - 10;

        while ($i <= $current_year) {
            $years[] = array('data' => $i, 'selected' => $i == $year);
            ++$i;
        }

        $months        = array();
        $current_month = date('m');
        $month         = $_GPC['month'];
        $i             = 1;

        while ($i <= 12) {
            $months[] = array('data' => $i, 'selected' => $i == $month);
            ++$i;
        }

        $timefield = ((empty($isagent) ? 'createtime' : 'agenttime'));
        $datas     = array();
        $title     = '';

        if (!empty($days)) {
            $charttitle = '最近' . $days . '天增长趋势图';
            $i          = $days;

            while (0 <= $i) {
                $time      = date('Y-m-d', strtotime('-' . $i . ' day'));
                $condition =
                    ' and uniacid=:uniacid ' .
                    ' and ' . $timefield . '>=:starttime ' .
                    ' and ' . $timefield . '<=:endtime ' .
                    ' and  core_user in ( SELECT distinct core_user from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 已处理
                    '                  WHERE uniacid =:uniacid and merchid=:merchid )';
//				$condition =
//					' and uniacid=:uniacid ' .
//					' and ' . $timefield . '>=:starttime ' .
//					' and ' . $timefield . '<=:endtime ' .
//					' and  openid in ( SELECT distinct openid from ' . tablename('superdesk_shop_order') .
//					'                  WHERE uniacid =:uniacid and merchid=:merchid )';

                $params = array(
                    ':uniacid'   => $_W['uniacid'],
                    ':merchid'   => $_W['merchid'],
                    ':starttime' => strtotime($time . ' 00:00:00'),
                    ':endtime'   => strtotime($time . ' 23:59:59')
                );

                $datas[] = array(
                    'date'   => $time,
                    'mcount' => pdo_fetchcolumn(
                        'select count(*) '.
                        ' from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid superdesk_shop_member 已处理
                        ' where isagent=0  ' .
                        $condition,
                        $params
                    ),
                    'acount' => pdo_fetchcolumn(
                        'select count(*) '.
                        ' from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid superdesk_shop_member 已处理
                        ' where isagent=1  ' .
                        $condition,
                        $params
                    )
                );

                --$i;
            }
        } else if (!empty($year) && !empty($month)) {
            $charttitle = $year . '年' . $month . '月增长趋势图';
            $lastday    = get_last_day($year, $month);
            $d          = 1;

            while ($d <= $lastday) {
                $condition =
                    ' and uniacid=:uniacid ' .
                    ' and ' . $timefield . '>=:starttime ' .
                    ' and ' . $timefield . '<=:endtime ' .
                    ' and  core_user in ( SELECT distinct core_user from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 已处理
                    '                  WHERE uniacid =:uniacid and merchid=:merchid )';
//				$condition =
//					' and uniacid=:uniacid ' .
//					' and ' . $timefield . '>=:starttime ' .
//					' and ' . $timefield . '<=:endtime ' .
//					' and  openid in ( SELECT distinct openid from ' . tablename('superdesk_shop_order') .
//					'                  WHERE uniacid =:uniacid and merchid=:merchid )';
                $params = array(
                    ':uniacid'   => $_W['uniacid'],
                    ':merchid'   => $_W['merchid'],
                    ':starttime' => strtotime($year . '-' . $month . '-' . $d . ' 00:00:00'),
                    ':endtime'   => strtotime($year . '-' . $month . '-' . $d . ' 23:59:59')
                );

                $datas[] = array(
                    'date'   => $d . '日',
                    'mcount' => pdo_fetchcolumn(
                        'select count(*) '.
                        ' from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid superdesk_shop_member 已处理
                        ' where isagent=0  ' .
                        $condition,
                        $params
                    ),
                    'acount' => pdo_fetchcolumn(
                        'select count(*) '.
                        ' from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid superdesk_shop_member 已处理
                        ' where isagent=1  ' .
                        $condition,
                        $params
                    )
                );

                ++$d;
            }
        } else if (!empty($year)) {

            $charttitle = $year . '年增长趋势图';

            foreach ($months as $m) {
                $lastday   = get_last_day($year, $m['data']);
                $condition =
                    ' and uniacid=:uniacid ' .
                    ' and ' . $timefield . '>=:starttime ' .
                    ' and ' . $timefield . '<=:endtime ' .
                    ' and  core_user in ( SELECT distinct core_user from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 已处理
                    '                  WHERE uniacid =:uniacid and merchid=:merchid )';
//				$condition =
//					' and uniacid=:uniacid ' .
//					' and ' . $timefield . '>=:starttime ' .
//					' and ' . $timefield . '<=:endtime ' .
//					' and  openid in ( SELECT distinct openid from ' . tablename('superdesk_shop_order') .
//					'                  WHERE uniacid =:uniacid and merchid=:merchid )';

                $params = array(
                    ':uniacid'   => $_W['uniacid'],
                    ':merchid'   => $_W['merchid'],
                    ':starttime' => strtotime($year . '-' . $m['data'] . '-01 00:00:00'),
                    ':endtime'   => strtotime($year . '-' . $m['data'] . '-' . $lastday . ' 23:59:59')
                );

                $datas[] = array(
                    'date'   => $m['data'] . '月',
                    'mcount' => pdo_fetchcolumn(
                        'select count(*) '.
                        ' from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid superdesk_shop_member 已处理
                        ' where isagent=0  ' .
                        $condition,
                        $params
                    ),
                    'acount' => pdo_fetchcolumn(
                        'select count(*) '.
                        ' from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid superdesk_shop_member 已处理
                        ' where isagent=1  ' .
                        $condition,
                        $params
                    )
                );
            }
        }

        load()->func('tpl');

        include $this->template('statistics/member_increase');
    }
}