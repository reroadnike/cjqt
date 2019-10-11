<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Saler_SuperdeskShopV2Page extends ComWebPage
{
    public function __construct($_com = 'verify')
    {
        parent::__construct($_com);
    }

    public function main()
    {
        global $_W;
        global $_GPC;
        $condition = ' s.uniacid = :uniacid';
        $params    = array(':uniacid' => $_W['uniacid']);

        if ($_GPC['status'] != '') {
            $condition         .= ' and s.status = :status';
            $params[':status'] = $_GPC['status'];
        }


        if (!empty($_GPC['keyword'])) {
            $_GPC['keyword']    = trim($_GPC['keyword']);
            $condition          .= ' and ( s.salername like :keyword or m.realname like :keyword or m.mobile like :keyword or m.nickname like :keyword)';
            $params[':keyword'] = '%' . $_GPC['keyword'] . '%';
        }


        $sql  =
            ' SELECT s.*,m.nickname,m.avatar,m.mobile,m.realname,store.storename ' .
            ' FROM ' . tablename('superdesk_shop_saler') . '  s ' .
            ' left join ' . tablename('superdesk_shop_member') . ' m on s.openid=m.openid and s.core_user=m.core_user and m.uniacid = s.uniacid ' . // TODO 标志 楼宇之窗 openid shop_member 已处理
            ' left join ' . tablename('superdesk_shop_store') . ' store on store.id=s.storeid ' .
            ' WHERE ' .
            $condition .
            ' ORDER BY id asc';
        $list = pdo_fetchall($sql, $params);

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

        $item = pdo_fetch(
            'SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_saler') .
            ' WHERE id =:id and uniacid=:uniacid limit 1',
            array(
                ':uniacid' => $_W['uniacid'],
                ':id'      => $id
            )
        );

        if (!empty($item)) {
            $saler = m('member')->getMember($item['openid'], $item['core_user']);
            $store = pdo_fetch(
                'SELECT * ' .
                ' FROM ' . tablename('superdesk_shop_store') .
                ' WHERE id =:id and uniacid=:uniacid limit 1',
                array(
                    ':uniacid' => $_W['uniacid'],
                    ':id'      => $item['storeid']
                )
            );
        }


        if ($_W['ispost']) {

            $data = array(
                'uniacid'   => $_W['uniacid'],
                'storeid'   => intval($_GPC['storeid']),
                'openid'    => trim($_GPC['openid']),
                'core_user' => trim($_GPC['core_user']),
                'status'    => intval($_GPC['status']),
                'salername' => trim($_GPC['salername'])
            );

            $m = m('member')->getMember($data['openid'], $data['core_user']);

            if (!empty($id)) {

                pdo_update(
                    'superdesk_shop_saler',
                    $data,
                    array(
                        'id'      => $id,
                        'uniacid' => $_W['uniacid']
                    )
                );

                plog(
                    'shop.verify.saler.edit',
                    '编辑店员 ID: ' . $id . ' <br/>店员信息: ID: ' . $m['id'] . ' / ' . $m['openid'] . '/' . $m['nickname'] . '/' . $m['realname'] . '/' . $m['mobile'] . ' '
                );

            } else {

                $scount = pdo_fetchcolumn(
                    'SELECT count(*) ' .
                    ' FROM ' . tablename('superdesk_shop_saler') .
                    ' WHERE ' .
                    '       uniacid=:uniacid ' .
                    '       and openid =:openid ' .
                    '       and core_user=:core_user ' .
                    ' limit 1',
                    array(
                        ':uniacid'   => $_W['uniacid'],
                        ':openid'    => $data['openid'],
                        ':core_user' => $data['core_user']
                    )
                );

                if (0 < $scount) {
                    show_json(0, '此会员已经成为店员，没法重复添加');
                }

                pdo_insert('superdesk_shop_saler', $data);
                $id = pdo_insertid();

                plog(
                    'shop.verify.saler.add',
                    '添加店员 ID: ' . $id . '  <br/>店员信息: ID: ' . $m['id'] . ' / ' . $m['openid'] . '/' . $m['nickname'] . '/' . $m['realname'] . '/' . $m['mobile'] . ' '
                );
            }

            show_json(1, array(
                'url' => webUrl('shop/verify/saler')
            ));
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
            ' SELECT id,salername ' .
            ' FROM ' . tablename('superdesk_shop_saler') .
            ' WHERE id in( ' . $id . ' ) ' .
            ' AND uniacid=' . $_W['uniacid']
        );

        foreach ($items as $item) {

            pdo_delete(
                'superdesk_shop_saler',
                array(
                    'id' => $item['id']
                )
            );

            plog('shop.verify.saler.delete', '删除店员 ID: ' . $item['id'] . ' 店员名称: ' . $item['salername'] . ' ');
        }

        show_json(1, array(
            'url' => referer()
        ));
    }

    public function status()
    {
        global $_W;
        global $_GPC;
        $id = intval($_GPC['id']);

        if (empty($id)) {
            $id = ((is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0));
        }

        $items = pdo_fetchall(
            ' SELECT id,salername ' .
            ' FROM ' . tablename('superdesk_shop_saler') .
            ' WHERE id in( ' . $id . ' ) ' .
            '       AND uniacid=' . $_W['uniacid']);

        foreach ($items as $item) {

            pdo_update(
                'superdesk_shop_saler',
                array(
                    'status' => intval($_GPC['status'])
                ),
                array(
                    'id' => $item['id']
                )
            );

            plog('shop.verify.saler.edit', (('修改店员状态<br/>ID: ' . $item['id'] . '<br/>店员名称: ' . $item['salername'] . '<br/>状态: ' . $_GPC['status']) == 1 ? '启用' : '禁用'));
        }

        show_json(1, array('url' => referer()));
    }

    public function query()
    {
        global $_W;
        global $_GPC;
        $kwd                = trim($_GPC['keyword']);
        $params             = array();
        $params[':uniacid'] = $_W['uniacid'];
        $condition          = ' and s.uniacid=:uniacid';

        if (!empty($kwd)) {
            $condition          .= ' AND ( m.nickname LIKE :keyword or m.realname LIKE :keyword or m.mobile LIKE :keyword or store.storename like :keyword )';
            $params[':keyword'] = '%' . $kwd . '%';
        }


        $ds = pdo_fetchall(
            ' SELECT s.*,m.nickname,m.avatar,m.mobile,m.realname,store.storename ' .
            ' FROM ' . tablename('superdesk_shop_saler') . '  s ' .
            ' left join ' . tablename('superdesk_shop_member') . ' m on s.openid=m.openid and s.core_user=m.core_user ' . // TODO 标志 楼宇之窗 openid shop_member 已处理
            ' left join ' . tablename('superdesk_shop_store') . ' store on store.id=s.storeid ' .
            ' WHERE 1 ' . $condition . ' ORDER BY id asc',
            $params
        );

        include $this->template();

        exit();
    }
}


