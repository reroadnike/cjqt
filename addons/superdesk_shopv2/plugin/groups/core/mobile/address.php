<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Address_SuperdeskShopV2Page extends PluginMobileLoginPage
{
    public function main()
    {
        global $_W;
        global $_GPC;
        global $_S;

        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;

        $condition =
            ' and openid=:openid ' .
            ' and core_user=:core_user ' .
            ' and deleted=0 ' .
            ' and uniacid = :uniacid ';

        $params = array(
            ':uniacid'   => $_W['uniacid'],
            ':openid'    => $_W['openid'],
            ':core_user' => $_W['core_user'],
        );

        $sql   = 'SELECT ' .
            '       COUNT(*) ' .
            ' FROM ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
            ' where 1 ' .
            $condition;
        $total = pdo_fetchcolumn($sql, $params);

        $sql  = 'SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
            ' where 1 ' .
            $condition .
            ' ORDER BY `id` DESC ' .
            ' LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize;
        $list = pdo_fetchall($sql, $params);

        include $this->template();
    }

    public function post()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        $address = pdo_fetch(
            'select * ' .
            ' from ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
            ' where ' .
            '       id=:id ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and uniacid=:uniacid ' .
            ' limit 1 ',
            array(
                ':id'        => $id,
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user'],
            )
        );

        $shareaddress_config = false;

        if ($_W['shopset']['trade']['shareaddress'] && is_weixin()) {

            $account = WeAccount::create();

            if (method_exists($account, 'getShareAddressConfig')) {

                $shareaddress_config = $account->getShareAddressConfig();
            }

        }

        include $this->template();
    }

    public function setdefault()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        $data = pdo_fetch(
            'select id ' .
            ' from ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 不处理
            ' where ' .
            '       id=:id ' .
            '       and deleted=0 ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':uniacid' => $_W['uniacid'],
                ':id'      => $id
            )
        );

        if (empty($data)) {
            show_json(0, '地址未找到');
        }

        pdo_update(
            'superdesk_shop_member_address', // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
            array(
                'isdefault' => 0
            ),
            array(
                'uniacid'   => $_W['uniacid'],
                'openid'    => $_W['openid'],
                'core_user' => $_W['core_user'],
            )
        );

        pdo_update(
            'superdesk_shop_member_address', // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
            array(
                'isdefault' => 1
            ),
            array(
                'id'        => $id,
                'uniacid'   => $_W['uniacid'],
                'openid'    => $_W['openid'],
                'core_user' => $_W['core_user'],
            )
        );

        show_json(1);
    }

    public function submit()
    {
        global $_W;
        global $_GPC;

        $id    = intval($_GPC['id']);
        $data  = $_GPC['addressdata'];
        $areas = explode(' ', $data['areas']);

        $data['province'] = $areas[0];
        $data['city']     = $areas[1];
        $data['area']     = $areas[2];

        unset($data['areas']);

        $data['openid']    = $_W['openid'];
        $data['uniacid']   = $_W['uniacid'];
        $data['core_user'] = $_W['core_user'];

        if (empty($id)) {

            $addresscount = pdo_fetchcolumn(
                'SELECT ' .
                '       count(*) ' .
                ' FROM ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
                ' where ' .
                '       openid=:openid ' .
                '       and core_user=:core_user ' .
                '       and deleted=0 ' .
                '       and uniacid = :uniacid ',
                array(
                    ':uniacid'   => $_W['uniacid'],
                    ':openid'    => $_W['openid'],
                    ':core_user' => $_W['core_user'],
                )
            );

            if ($addresscount <= 0) {
                $data['isdefault'] = 1;
            }

            pdo_insert('superdesk_shop_member_address', $data); // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理

            $id = pdo_insertid();

        } else {

            pdo_update(
                'superdesk_shop_member_address', // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
                $data,
                array(
                    'id'        => $id,
                    'uniacid'   => $_W['uniacid'],
                    'openid'    => $_W['openid'],
                    'core_user' => $_W['core_user'],
                )
            );
        }

        show_json(1, array('addressid' => $id));
    }

    public function delete()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        $data = pdo_fetch(
            'select id,isdefault ' .
            ' from ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
            ' where ' .
            '       id=:id ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and deleted=0 ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user'],
                ':id'        => $id
            )
        );

        if (empty($data)) {
            show_json(0, '地址未找到');
        }

        pdo_update(
            'superdesk_shop_member_address', // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 不处理
            array(
                'deleted' => 1
            ),
            array(
                'id' => $id
            )
        );

        if ($data['isdefault'] == 1) {

            pdo_update(
                'superdesk_shop_member_address', // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
                array(
                    'isdefault' => 0
                ),
                array(
                    'uniacid'   => $_W['uniacid'],
                    'openid'    => $_W['openid'],
                    'core_user' => $_W['core_user'],
                    'id'        => $id
                )
            );

            $data2 = pdo_fetch(
                'select id ' .
                ' from ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
                ' where ' .
                '       openid=:openid ' .
                '       and core_user=:core_user ' .
                '       and deleted=0 ' .
                '       and uniacid=:uniacid ' .
                ' order by id desc ' .
                ' limit 1',
                array(
                    ':uniacid'   => $_W['uniacid'],
                    ':openid'    => $_W['openid'],
                    ':core_user' => $_W['core_user'],
                )
            );

            if (!empty($data2)) {

                pdo_update(
                    'superdesk_shop_member_address', // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
                    array(
                        'isdefault' => 1
                    ),
                    array(
                        'uniacid'   => $_W['uniacid'],
                        'openid'    => $_W['openid'],
                        'core_user' => $_W['core_user'],
                        'id'        => $data2['id']
                    )
                );

                show_json(1, array('defaultid' => $data2['id']));
            }
        }

        show_json(1);
    }

    public function selector()
    {
        global $_W;
        global $_GPC;

        $condition =
            ' and openid=:openid ' .
            ' and core_user=:core_user ' .
            ' and deleted=0 ' .
            ' and uniacid = :uniacid ';

        $params = array(
            ':uniacid'   => $_W['uniacid'],
            ':openid'    => $_W['openid'],
            ':core_user' => $_W['core_user'],
        );

        $sql =
            'SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
            ' where 1 ' .
            $condition .
            ' ORDER BY isdefault desc, id DESC ';

        $list = pdo_fetchall($sql, $params);

        include $this->template();

        exit();
    }
}