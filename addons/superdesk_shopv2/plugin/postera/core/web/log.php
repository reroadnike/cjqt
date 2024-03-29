<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Log_SuperdeskShopV2Page extends PluginWebPage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        $pindex = max(1, intval($_GPC['page']));
        $psize  = 10;

        $params = array(
            ':uniacid' => $_W['uniacid']
        );

        $condition =
            ' and log.uniacid=:uniacid ' .
            ' and posterid=' . intval($_GPC['id']);

        $searchfield = strtolower(trim($_GPC['searchfield']));

        $keyword = trim($_GPC['keyword']);

        if (!empty($searchfield) && !empty($keyword)) {

            if ($searchfield == 'rec') {

                $condition .= ' AND ( m.nickname LIKE :keyword or m.realname LIKE :keyword or m.mobile LIKE :keyword ) ';
            } else if ($searchfield == 'sub') {

                $condition .= ' AND ( m1.nickname LIKE :keyword or m1.realname LIKE :keyword or m1.mobile LIKE :keyword ) ';
            }

            $params[':keyword'] = '%' . $keyword . '%';
        }

        if (!empty($_GPC['time']['start']) && !empty($_GPC['time']['end'])) {

            $starttime = strtotime($_GPC['time']['start']);
            $endtime   = strtotime($_GPC['time']['end']);

            $condition .=
                ' AND log.createtime >= :starttime ' .
                ' AND log.createtime <= :endtime ';

            $params[':starttime'] = $starttime;
            $params[':endtime']   = $endtime;
        }

        $list = pdo_fetchall(
            'SELECT log.*, m.avatar,m.nickname,m.realname,m.mobile,m1.avatar as avatar1,m1.nickname as nickname1,m1.realname as realname1,m1.mobile as mobile1 ' .
            ' FROM ' . tablename('superdesk_shop_postera_log') . ' log ' .// TODO 标志 楼宇之窗 openid shop_postera_log 已处理
            ' left join ' . tablename('superdesk_shop_member') . ' m1 on m1.openid = log.openid and m1.core_user = log.core_user and m1.uniacid = log.uniacid ' .
            ' left join ' . tablename('superdesk_shop_member') . ' m on m.openid = log.from_openid  and m.uniacid = log.uniacid' .// TODO 标志 楼宇之窗 openid shop_postera_log 待处理
            ' WHERE 1 ' .
            $condition .
            ' ORDER BY log.createtime desc ' .
            ' LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize,
            $params
        );

        $total = pdo_fetchcolumn(
            'SELECT count(*) ' .
            ' FROM ' . tablename('superdesk_shop_postera_log') . ' log ' .// TODO 标志 楼宇之窗 openid shop_postera_log 已处理
            ' left join ' . tablename('superdesk_shop_member') . ' m1 on m1.openid = log.openid and m1.core_user = log.core_user and m1.uniacid = log.uniacid ' .
            ' left join ' . tablename('superdesk_shop_member') . ' m on m.openid = log.from_openid and m.uniacid = log.uniacid ' .// TODO 标志 楼宇之窗 openid shop_postera_log 待处理
            ' where 1 ' .
            $condition . '  ',
            $params
        );

        foreach ($list as &$row) {
            $row['times'] = pdo_fetchcolumn(
                'select count(*) ' .
                ' from ' . tablename('superdesk_shop_postera_log') .// TODO 标志 楼宇之窗 openid shop_postera_log 不处理
                ' where ' .
                '       from_openid=:from_openid ' .
                '       and posterid=:posterid ' .
                '       and uniacid=:uniacid ',
                array(
                    ':from_openid' => $row['from_openid'],// TODO 标志 楼宇之窗 openid shop_postera_log 待处理
                    ':posterid'    => intval($_GPC['id']),
                    ':uniacid'     => $_W['uniacid']
                )
            );
        }

        unset($row);

        $pager = pagination($total, $pindex, $psize);

        load()->func('tpl');

        include $this->template();
    }
}