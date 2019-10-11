<?php

require SUPERDESK_SHOPV2_PLUGIN . 'merch/core/inc/page_merch.php';

class Index_SuperdeskShopV2Page extends MerchWebPage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        $user = pdo_fetch(
            ' select `id`,`logo`,`merchname`,`desc` ' .
            ' from ' . tablename('superdesk_shop_merch_user') .
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':id'      => $_W['uniaccount']['merchid'],
                ':uniacid' => $_W['uniacid']
            )
        );

        $order_sql =
            ' select id,ordersn,createtime,price,address,addressid,invoice,invoiceid ' .
            ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' where ' .
            '       uniacid = :uniacid ' .
            '		and merchid=:merchid ' .
            '		and isparent=0 ' .
            '		and deleted=0 ' .
            '		AND ( status = 1 or (status=0 and paytype=3) ) ' .
            ' ORDER BY createtime ASC LIMIT 20';

        $order = pdo_fetchall(
            $order_sql,
            array(
                ':uniacid' => $_W['uniacid'],
                ':merchid' => $_W['merchid']
            )
        );

        foreach ($order as &$value) {
            $value['address'] = iunserializer($value['address']);
        }

        unset($value);

        $order_ok = $order;

        $merchid = $_W['merchid'];

        $url = mobileUrl('merch', array('merchid' => $merchid), true);

        include $this->template();
    }

    public function ajax()
    {
        global $_W;
        global $_GPC;

        $paras = array(
            ':uniacid' => $_W['uniacid'],
            ':merchid' => $_W['merchid']
        );

        $goods_totals = pdo_fetchcolumn(
            ' SELECT COUNT(1) ' .
            ' FROM ' . tablename('superdesk_shop_goods') .
            ' WHERE ' .
            '       uniacid = :uniacid ' .
            '       and merchid = :merchid ' .
            '       and status=1 ' .
            '       and deleted=0 ' .
            '       and total<=0 ' .
            '       and total<>-1  ',
            $paras
        );

        show_json(1, array(
            'goods_totals' => $goods_totals
        ));
    }
}