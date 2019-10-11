<?php

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/accessControl.php";

if (!defined('IN_IA')) {
    exit('Access Denied');
}

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/page_login_mobile.php";

class My_SuperdeskShopV2Page extends PcMobileLoginPage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        $openid    = $_W['openid'];
        $core_user = $_W['core_user'];

        $cate = trim($_GPC['cate']);

        $imgname = 'ling';

        $check = 0;

        if (!empty($cate)) {
            if ($cate == 'used') {
                $used    = 1;
                $imgname = 'used';
                $check   = 1;
            } else {
                $past    = 1;
                $imgname = 'past';
                $check   = 2;
            }
        }

        $pindex = max(1, intval($_GPC['page']));
        $psize  = 10;

        $time = time();

        $totalSql =
            'select count(*) ' .
            ' from ' . tablename('superdesk_shop_coupon_data') . ' d';// TODO 标志 楼宇之窗 openid shop_coupon_data 已处理

        $sql =
            ' select d.id,d.couponid,d.gettime,c.timelimit,c.coupontype,c.timedays,c.timestart,c.timeend,c.thumb,c.couponname,c.enough, ' .
            '        c.backtype,c.deduct,c.discount,c.backmoney,c.backcredit,c.backredpack,c.bgcolor,c.thumb,c.merchid,c.tagtitle, ' .
            '        c.settitlecolor,c.titlecolor ' .
            ' from ' . tablename('superdesk_shop_coupon_data') . ' d';// TODO 标志 楼宇之窗 openid shop_coupon_data 已处理

        $WhereSql = ' left join ' . tablename('superdesk_shop_coupon') . ' c on d.couponid = c.id';

        $WhereSql .= ' where d.openid=:openid and d.core_user=:core_user and d.uniacid=:uniacid ';

        if (!empty($past)) {
            $WhereSql .= ' and  ( (c.timelimit =0 and c.timedays<>0 and  c.timedays*86400 + d.gettime <unix_timestamp()) or (c.timelimit=1 and c.timeend<unix_timestamp() ))';
        } else if (!empty($used)) {
            $WhereSql .= ' and d.used =1 ';
        } else if (empty($used)) {
            $WhereSql .= ' and (   (c.timelimit = 0 and ( c.timedays=0 or c.timedays*86400 + d.gettime >=unix_timestamp() ) )  or  (c.timelimit =1 and c.timeend>=' . $time . ')) and  d.used =0 ';
        }

        $totalSql .= $WhereSql;

        $total = pdo_fetchcolumn(
            $totalSql,
            array(
                ':openid'    => $openid,
                ':core_user' => $core_user,
                ':uniacid'   => $_W['uniacid']
            )
        );

        $sql .= $WhereSql;
        $sql .= ' order by d.gettime desc  LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize;

        $coupons = set_medias(pdo_fetchall(
            $sql,
            array(
                ':openid'    => $openid,
                ':core_user' => $core_user,
                ':uniacid'   => $_W['uniacid']
            )),
            'thumb'
        );

        pdo_update('superdesk_shop_coupon_data',// TODO 标志 楼宇之窗 openid shop_coupon_data 已处理
            array(
                'isnew' => 0
            ),
            array(
                'uniacid'   => $_W['uniacid'],
                'openid'    => $_W['openid'],
                'core_user' => $_W['core_user']
            )
        );

        if (empty($coupons)) {
            $coupons = array();
        }

        foreach ($coupons as $i => &$row) {

            $row    = com('coupon')->setMyCoupon($row, $time);
            $title2 = '';

            if ($row['coupontype'] == '0') {

                if (0 < $row['enough']) {
                    $title2 = '消费满' . (double)$row['enough'] . '元';
                } else {
                    $title2 = '消费';
                }

            } else if ($row['coupontype'] == '1') {

                if (0 < $row['enough']) {
                    $title2 = '充值满' . (double)$row['enough'] . '元';
                } else {
                    $title2 = '充值';
                }

            }

            if ($row['backtype'] == 0) {

                $title2 = $title2 . '立减' . (double)$row['deduct'] . '元';
                if ($row['enough'] == '0') {
                    $row['color'] = 'org ';
                    $tagtitle     = '代金券';
                } else {
                    $row['color'] = 'blue';
                    $tagtitle     = '满减券';
                }

            }

            if ($row['backtype'] == 1) {
                $row['color'] = 'red ';
                $title2       = $title2 . '打' . (double)$row['discount'] . '折';
                $tagtitle     = '打折券';
            }

            if ($row['backtype'] == 2) {

                if ($row['coupontype'] == '0') {
                    $row['color'] = 'red ';
                    $tagtitle     = '购物返现券';
                } else {
                    $row['color'] = 'pink ';
                    $tagtitle     = '充值返现券';
                }

                if (!empty($row['backmoney']) && (0 < $row['backmoney'])) {
                    $title2 = $title2 . '送' . $row['backmoney'] . '元余额';
                }

                if (!empty($row['backcredit']) && (0 < $row['backcredit'])) {
                    $title2 = $title2 . '送' . $row['backcredit'] . '积分';
                }

                if (!empty($row['backredpack']) && (0 < $row['backredpack'])) {
                    $title2 = $title2 . '送' . $row['backredpack'] . '元红包';
                }
            }

            if ($row['tagtitle'] == '') {
                $row['tagtitle'] = $tagtitle;
            }

            if ($past == 1) {
                $row['color'] = 'disa';
            }

            $row['imgname'] = $imgname;
            $row['check']   = $check;
            $row['title2']  = $title2;
        }
        unset($row);

        $set = m('common')->getPluginset('coupon');

        show_json(1, array(
            'list'      => $coupons,
            'pagesize'  => $psize,
            'total'     => $total,
            'couponSet' => $set
        ));
    }

    public function detail()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        $data = pdo_fetch(
            'select * ' .
            ' from ' . tablename('superdesk_shop_coupon_data') . // TODO 标志 楼宇之窗 openid shop_coupon_data 不处理
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':id'      => $id,
                ':uniacid' => $_W['uniacid']
            )
        );

        if (empty($data)) {
            show_json(0, '无数据');
        }

        $coupon = pdo_fetch(
            'select * ' .
            ' from ' . tablename('superdesk_shop_coupon') .
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':id'      => $data['couponid'],
                ':uniacid' => $_W['uniacid']
            )
        );

        if (empty($coupon)) {
            show_json(0, '无数据');
        }

        $coupon['gettime']  = $data['gettime'];
        $coupon['back']     = $data['back'];
        $coupon['backtime'] = $data['backtime'];
        $coupon['used']     = $data['used'];
        $coupon['usetime']  = $data['usetime'];
        $coupon['thumb']    = tomedia($coupon['thumb']);

        $time = time();

        $coupon = com('coupon')->setMyCoupon($coupon, $time);

        $commonset = m('common')->getPluginset('coupon');

        if ($coupon['descnoset'] == '0') {
            if ($coupon['coupontype'] == '0') {
                $coupon['desc'] = $commonset['consumedesc'];
            } else {
                $coupon['desc'] = $commonset['rechargedesc'];
            }
        }

        $title2 = '';
        $title3 = '';

        if ($coupon['coupontype'] == '0') {

            if (0 < $coupon['enough']) {
                $title2 = '满' . (double)$coupon['enough'] . '元';
            } else {
                $title2 = '购物任意金额';
            }

        } else if ($coupon['coupontype'] == '1') {

            if (0 < $coupon['enough']) {
                $title2 = '充值满' . (double)$coupon['enough'] . '元';
            } else {
                $title2 = '充值任意金额';
            }

        }

        if ($coupon['backtype'] == 0) {
            if ($coupon['enough'] == '0') {
                $coupon['color'] = 'org ';
            } else {
                $coupon['color'] = 'blue';
            }
            $title3 = '减' . (double)$coupon['deduct'] . '元';
        }

        if ($coupon['backtype'] == 1) {
            $coupon['color'] = 'red ';
            $title3          = '打' . (double)$coupon['discount'] . '折 ';
        }

        if ($coupon['backtype'] == 2) {

            if ($coupon['coupontype'] == '0') {
                $coupon['color'] = 'red ';
            } else {
                $coupon['color'] = 'pink ';
            }

            if (!empty($coupon['backmoney']) && (0 < $coupon['backmoney'])) {
                $title3 = $title3 . '送' . $coupon['backmoney'] . '元余额 ';
            }

            if (!empty($coupon['backcredit']) && (0 < $coupon['backcredit'])) {
                $title3 = $title3 . '送' . $coupon['backcredit'] . '积分 ';
            }

            if (!empty($coupon['backredpack']) && (0 < $coupon['backredpack'])) {
                $title3 = $title3 . '送' . $coupon['backredpack'] . '元红包 ';
            }
        }

        if ($coupon['past'] || !empty($data['used'])) {
            $coupon['color'] = 'disa';
        }

        $coupon['title2'] = $title2;
        $coupon['title3'] = $title3;

        $goods    = array();
        $category = array();

        if ($coupon['limitgoodtype'] != 0) {

            if (!empty($coupon['limitgoodids'])) {
                $where = 'and id in(' . $coupon['limitgoodids'] . ')';
            }

            $goods = pdo_fetchall(
                'select `title` ' .
                ' from ' . tablename('superdesk_shop_goods') .
                ' where 1 ' .
                '       and uniacid=:uniacid ' .
                $where,
                array(
                    ':uniacid' => $_W['uniacid']
                ),
                'id'
            );
        }

        if ($coupon['limitgoodcatetype'] != 0) {

            if (!empty($coupon['limitgoodcateids'])) {
                $where = 'and id in(' . $coupon['limitgoodcateids'] . ')';
            }

            $category = pdo_fetchall(
                'select `name` ' .
                ' from ' . tablename('superdesk_shop_category') .
                ' where 1 ' .
                '       and uniacid=:uniacid   ' .
                $where,
                array(
                    ':uniacid' => $_W['uniacid']
                ),
                'id'
            );
        }

        $canuse = !$coupon['past'] && empty($data['used']);

        com('coupon')->setShare();

        show_json(1, compact('coupon', 'goods', 'category'));
    }
}