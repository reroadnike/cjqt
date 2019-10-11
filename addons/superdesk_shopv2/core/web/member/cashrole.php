<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Cashrole_SuperdeskShopV2Page extends WebPage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        $condition =
            ' and uniacid=:uniacid';

        $params = array(
            ':uniacid' => $_W['uniacid']
        );

        if (!empty($_GPC['keyword'])) {
            $_GPC['keyword']     = trim($_GPC['keyword']);
            $condition           .= ' and ( rolename like :rolename)';
            $params[':rolename'] = '%' . $_GPC['keyword'] . '%';
        }

        $list = pdo_fetchall(
            'SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_member_cash_role') .
            ' WHERE 1 ' .
            $condition .
            ' ORDER BY id asc',
            $params
        );

        foreach ($list as &$row) {
            $row['membercount'] = pdo_fetchcolumn(
                'select count(*) ' .
                ' from ' . tablename('superdesk_shop_member') .// TODO 标志 楼宇之窗 openid shop_member 不处理
                ' where ' .
                '       uniacid=:uniacid ' .
                '       and cash_role_id=:cash_role_id ' .
                ' limit 1',
                array(
                    ':uniacid'      => $_W['uniacid'],
                    ':cash_role_id' => $row['id']
                )
            );
        }

        unset($row);

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

        $cashrole = pdo_fetch(
            ' SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_member_cash_role') .
            ' WHERE id =:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':id'      => $id,
                ':uniacid' => $_W['uniacid']
            )
        );

        if ($_W['ispost']) {

            $data = array(
                'uniacid'  => $_W['uniacid'],
                'rolename' => trim($_GPC['rolename'])
            );

            if (!empty($id)) {

                pdo_update('superdesk_shop_member_cash_role', $data, array('id' => $id, 'uniacid' => $_W['uniacid']));
                plog('member.cashrole.edit', '修改会员角色 ID: ' . $id);

            } else {

                pdo_insert('superdesk_shop_member_cash_role', $data);
                $id = pdo_insertid();
                plog('member.cashrole.add', '添加会员角色 ID: ' . $id);

            }

            show_json(1, array('url' => webUrl('member/cashrole', array('op' => 'display'))));
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
            ' SELECT id,rolename ' .
            ' FROM ' . tablename('superdesk_shop_member_cash_role') .
            ' WHERE id in( ' . $id . ' ) ' .
            '       AND uniacid=' . $_W['uniacid']);

        foreach ($items as $item) {

            pdo_update( // 不根据id更新
                'superdesk_shop_member', // TODO 标志 楼宇之窗 openid shop_member 不处理
                array(
                    'cash_role_id' => 0
                ),
                array(
                    'cash_role_id' => $item['id'],
                    'uniacid'      => $_W['uniacid']
                )
            );
            pdo_delete(
                'superdesk_shop_member_cash_role',
                array(
                    'id' => $item['id']
                )
            );

            plog('member.group.delete', '删除角色 ID: ' . $item['id'] . ' 名称: ' . $item['rolename'] . ' ');
        }

        show_json(1, array('url' => referer()));
    }
}


