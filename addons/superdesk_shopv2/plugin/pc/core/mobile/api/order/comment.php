<?php

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/accessControl.php";

if (!(defined("IN_IA"))) {
    exit("Access Denied");
}

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/page_login_mobile.php";

class Comment_SuperdeskShopV2Page extends PcMobileLoginPage
{
    public function __construct()
    {
        parent::__construct();
        $trade = m('common')->getSysset('trade');
        if (!(empty($trade['closecomment']))) {
            $this->message('不允许评论!', '', 'error');
        }
    }

    public function main()
    {
        global $_W;
        global $_GPC;

        $uniacid   = $_W['uniacid'];
        $openid    = $_W['openid'];
        $core_user = $_W['core_user'];

        $orderid = intval($_GPC['id']);

        $order = pdo_fetch(
            'select id,status,iscomment ' .
            ' from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            ' limit 1',
            array(
                ':id'        => $orderid,
                ':uniacid'   => $uniacid,
                ':openid'    => $openid,
                ':core_user' => $core_user,
            )
        );

        if (empty($order)) {
            show_json(0, '未找到订单');
        }

        if (($order['status'] != 3) && ($order['status'] != 4)) {
            show_json(0, '订单未收货，不能评价!');
            //$this->message('订单未收货，不能评价!', mobileUrl('order/detail', array('id' => $orderid)));
        }

        if (2 <= $order['iscomment']) {
            show_json(0, '您已经评价过了!');
            //$this->message('您已经评价过了!', mobileUrl('order/detail', array('id' => $orderid)));
        }

        $goods = pdo_fetchall(
            'select og.id,og.goodsid,og.price,g.title,g.thumb,og.total,g.credit,og.optionid,o.title as optiontitle ' .
            ' from ' . tablename('superdesk_shop_order_goods') . ' og ' . // TODO 标志 楼宇之窗 openid shop_order_goods 不处理
            ' left join ' . tablename('superdesk_shop_goods') . ' g on g.id=og.goodsid ' .
            ' left join ' . tablename('superdesk_shop_goods_option') . ' o on o.id=og.optionid ' .
            ' where ' .
            '       og.orderid=:orderid ' .
            '       and og.uniacid=:uniacid ',
            array(
                ':uniacid' => $uniacid,
                ':orderid' => $orderid
            )
        );

        $goods = set_medias($goods, 'thumb');

        show_json(1, $goods);
    }

    public function submit()
    {
        global $_W;
        global $_GPC;

        $uniacid = $_W['uniacid'];

        $orderid = intval($_GPC['orderid']);

        $order = pdo_fetch(
            'select id,status,iscomment ' .
            ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            ' limit 1',
            array(
                ':id'        => $orderid,
                ':uniacid'   => $uniacid,
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user']
            )
        );

        if (empty($order)) {
            show_json(0, '订单未找到');
        }

        $member = m('member')->getMember($_W['openid'], $_W['core_user']);

        $comments = $_GPC['comments'];

        if (!(is_array($comments))) {
            show_json(0, '数据出错，请重试!');
        }

        $trade = m('common')->getSysset('trade');

        if (!(empty($trade['commentchecked']))) {
            $checked = 0;
        } else {
            $checked = 1;
        }

        foreach ($comments as $c) {

            $old_c = pdo_fetchcolumn(
                'select count(*) ' .
                ' from ' . tablename('superdesk_shop_order_comment') . // TODO 标志 楼宇之窗 openid superdesk_shop_order_comment 不处理
                ' where ' .
                '       uniacid=:uniacid ' .
                '       and orderid=:orderid ' .
                '       and goodsid=:goodsid ' .
                ' limit 1',
                array(
                    ':uniacid' => $_W['uniacid'],
                    ':goodsid' => $c['goodsid'],
                    ':orderid' => $orderid
                )
            );

            if (empty($old_c)) {
                $comment = array(
                    'uniacid'    => $uniacid,
                    'orderid'    => $orderid,
                    'goodsid'    => $c['goodsid'],
                    'level'      => $c['level'],
                    'content'    => trim($c['content']),
                    'images'     => (is_array($c['images']) ? iserializer($c['images']) : iserializer(array())),
                    'openid'     => $_W['openid'],
                    'core_user'  => $_W['core_user'],
                    'nickname'   => $member['nickname'],
                    'headimgurl' => $member['avatar'],
                    'createtime' => time(),
                    'checked'    => $checked
                );

                pdo_insert('superdesk_shop_order_comment', $comment);// TODO 标志 楼宇之窗 openid superdesk_shop_order_comment 已处理

            } else {

                $comment = array(
                    'append_content' => trim($c['content']),
                    'append_images'  => (is_array($c['images']) ? iserializer($c['images']) : iserializer(array())),
                    'replychecked'   => $checked
                );

                pdo_update('superdesk_shop_order_comment',// TODO 标志 楼宇之窗 openid superdesk_shop_order_comment 不处理
                    $comment,
                    array(
                        'uniacid' => $_W['uniacid'],
                        'goodsid' => $c['goodsid'],
                        'orderid' => $orderid
                    )
                );
            }
        }

        // 评价状态 status 3,4 后允许评价 0 可评价 1 可追加评价 2 已评价
        if ($order['iscomment'] <= 0) {
            $d['iscomment'] = 1; // 可追加评价
        } else {
            $d['iscomment'] = 2; // 已评价
        }

        pdo_update('superdesk_shop_order', // TODO 标志 楼宇之窗 openid superdesk_shop_order 已处理
            $d,
            array(
                'id'      => $orderid,
                'uniacid' => $uniacid
            )
        );

        show_json(1);
    }
}