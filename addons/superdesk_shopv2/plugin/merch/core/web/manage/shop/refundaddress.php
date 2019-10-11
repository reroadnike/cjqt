<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

require SUPERDESK_SHOPV2_PLUGIN . 'merch/core/inc/page_merch.php';

class Refundaddress_SuperdeskShopV2Page extends MerchWebPage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;

        $condition = ' and uniacid=:uniacid and merchid=:merchid';
        $params    = array(':uniacid' => $_W['uniacid'], ':merchid' => $_W['merchid']);

        if ($_GPC['enabled'] != '') {
            $condition .= ' and enabled=' . intval($_GPC['enabled']);
        }


        if (!empty($_GPC['keyword'])) {
            $_GPC['keyword']    = trim($_GPC['keyword']);
            $condition          .= ' and title  like :keyword';
            $params[':keyword'] = '%' . $_GPC['keyword'] . '%';
        }


        $list = pdo_fetchall(
            'SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_refund_address') . // TODO 标志 楼宇之窗 openid shop_refund_address 不处理
            ' WHERE 1 ' .
            $condition .
            ' ORDER BY id DESC ' .
            ' limit ' . (($pindex - 1) * $psize) . ',' . $psize,
            $params
        );

        $total = pdo_fetchcolumn(
            'SELECT count(1) ' .
            ' FROM ' . tablename('superdesk_shop_refund_address') . // TODO 标志 楼宇之窗 openid shop_refund_address 不处理
            ' WHERE 1 ' .
            $condition,
            $params
        );

        $pager = pagination($total, $pindex, $psize);

        include $this->template();
    }

    public function add()
    {
        $this->post();
    }

    public function edit()
    {
        $this->post();
    }

    protected function post()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        if ($_W['ispost']) {

            $data = array();

            $data['uniacid']   = $_W['uniacid'];
            $data['title']     = trim($_GPC['title']);
            $data['name']      = trim($_GPC['name']);
            $data['tel']       = trim($_GPC['tel']);
            $data['mobile']    = trim($_GPC['mobile']);
            $data['zipcode']   = trim($_GPC['zipcode']);
            $data['province']  = trim($_GPC['province']);
            $data['city']      = trim($_GPC['city']);
            $data['area']      = trim($_GPC['area']);
            $data['address']   = trim($_GPC['address']);
            $data['isdefault'] = $_GPC['isdefault'];
            $data['merchid']   = $_W['merchid'];

            if ($data['isdefault']) {
                pdo_update('superdesk_shop_refund_address', // TODO 标志 楼宇之窗 openid shop_refund_address 不处理
                    array(
                        'isdefault' => 0
                    ),
                    array(
                        'uniacid' => $_W['uniacid'],
                        'merchid' => $_W['merchid']
                    )
                );
            }

            if (!empty($id)) {

                pdo_update(
                    'superdesk_shop_refund_address', // TODO 标志 楼宇之窗 openid shop_refund_address 不处理
                    $data,
                    array(
                        'id'      => $id,
                        'merchid' => $_W['merchid']
                    )
                );

                mplog('shop.refundaddress.edit', '修改退货地址 ID: ' . $id);

            } else {

                pdo_insert(
                    'superdesk_shop_refund_address', // TODO 标志 楼宇之窗 openid shop_refund_address 不处理
                    $data
                );
                $id = pdo_insertid();

                mplog('shop.refundaddress.add', '添加退货地址 ID: ' . $id);
            }

            show_json(1, array(
                'url' => merchUrl('shop/refundaddress', array('op' => 'display'))
            ));
        }

        if (!empty($id)) {

            $item = pdo_fetch(
                'SELECT * ' .
                ' FROM ' . tablename('superdesk_shop_refund_address') . // TODO 标志 楼宇之窗 openid shop_refund_address 不处理
                ' WHERE ' .
                '       id = \'' . $id . '\' ' .
                '       and uniacid = \'' . $_W['uniacid'] . '\' ' .
                '       and merchid=' . $_W['merchid']
            );
        }


        include $this->template();
    }

    public function delete()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        if (empty($id)) {
            $id = ((is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0));
        }

        $items = pdo_fetchall(
            'SELECT id,title ' .
            ' FROM ' . tablename('superdesk_shop_refund_address') . // TODO 标志 楼宇之窗 openid shop_refund_address 不处理
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            '       and merchid=:merchid ' .
            ' limit 1',
            array(
                ':id'      => $id,
                ':uniacid' => $_W['uniacid'],
                ':merchid' => $_W['merchid']
            )
        );


        foreach ($items as $item) {

            pdo_delete(
                'superdesk_shop_refund_address',  // TODO 标志 楼宇之窗 openid shop_refund_address 不处理
                array(
                    'id'      => $item['id'],
                    'merchid' => $_W['merchid']
                )
            );

            mplog('shop.refundaddress.delete', '删除配送方式 ID: ' . $item['id'] . ' 标题: ' . $item['title'] . ' ');
        }

        show_json(1, array(
            'url' => referer()
        ));
    }

    public function setdefault()
    {
        global $_W;
        global $_GPC;
        $id = intval($_GPC['id']);

        if (empty($id)) {
            $id = ((is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0));
        }

        if ($_GPC['isdefault'] == 1) {
            pdo_update('superdesk_shop_refund_address', // TODO 标志 楼宇之窗 openid shop_refund_address 不处理
                array('isdefault' => 0),
                array('uniacid' => $_W['uniacid'], 'merchid' => $_W['merchid'])
            );
        }

        $items = pdo_fetchall(
            'SELECT id,title ' .
            ' FROM ' . tablename('superdesk_shop_refund_address') . // TODO 标志 楼宇之窗 openid shop_refund_address 不处理
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            '       and merchid=:merchid ' .
            ' limit 1',
            array(
                ':id'      => $id,
                ':uniacid' => $_W['uniacid'],
                ':merchid' => $_W['merchid']
            )
        );

        foreach ($items as $item) {

            pdo_update(
                'superdesk_shop_refund_address', // TODO 标志 楼宇之窗 openid shop_refund_address 不处理
                array(
                    'isdefault' => intval($_GPC['isdefault'])
                ),
                array(
                    'id'      => $item['id'],
                    'merchid' => $_W['merchid']
                )
            );

            mplog('shop.refundaddress.edit', (('修改配送方式默认状态<br/>ID: ' . $item['id'] . '<br/>标题: ' . $item['title'] . '<br/>状态: ' . $_GPC['isdefault']) == 1 ? '是' : '否'));
        }

        show_json(1, array('url' => referer()));
    }

    public function tpl()
    {
        global $_W;
        global $_GPC;

        $random = random(16);

        ob_clean();
        ob_start();
        include $this->template('shop/refundaddress/tpl');
        $contents = ob_get_contents();
        ob_clean();

        exit(json_encode(array('random' => $random, 'html' => $contents)));
    }
}