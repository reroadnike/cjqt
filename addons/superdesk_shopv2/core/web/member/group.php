<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Group_SuperdeskShopV2Page extends WebPage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        $list = array(
            array(
                'id'          => 'default',
                'groupname'   => '无分组',
                'membercount' => pdo_fetchcolumn(
                    'select count(*) ' .
                    ' from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid shop_member 不处理
                    ' where ' .
                    '       uniacid=:uniacid ' .
                    '       and groupid=0 ' .
                    ' limit 1',
                    array(
                        ':uniacid' => $_W['uniacid']
                    )
                )
            )
        );

        $condition = ' and uniacid=:uniacid';
        $params    = array(':uniacid' => $_W['uniacid']);

        if (!empty($_GPC['keyword'])) {
            $_GPC['keyword']      = trim($_GPC['keyword']);
            $condition            .= ' and ( groupname like :groupname)';
            $params[':groupname'] = '%' . $_GPC['keyword'] . '%';
        }


        $alllist = pdo_fetchall('SELECT * FROM ' . tablename('superdesk_shop_member_group') . ' WHERE 1 ' . $condition . ' ORDER BY id asc', $params);

        foreach ($alllist as &$row) {
            $row['membercount'] = pdo_fetchcolumn(
                'select count(*) ' .
                ' from ' . tablename('superdesk_shop_member') .// TODO 标志 楼宇之窗 openid shop_member 不处理
                ' where ' .
                '       uniacid=:uniacid ' .
                '       and groupid=:groupid ' .
                ' limit 1',
                array(
                    ':uniacid' => $_W['uniacid'],
                    ':groupid' => $row['id']
                )
            );
        }

        unset($row);

        $list = array_merge($list, $alllist);

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

        $group = pdo_fetch(
            ' SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_member_group') .
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
                'uniacid'   => $_W['uniacid'],
                'groupname' => trim($_GPC['groupname'])
            );

            if (!empty($id)) {

                pdo_update('superdesk_shop_member_group', $data, array('id' => $id, 'uniacid' => $_W['uniacid']));
                plog('member.group.edit', '修改会员分组 ID: ' . $id);

            } else {

                pdo_insert('superdesk_shop_member_group', $data);
                $id = pdo_insertid();
                plog('member.group.add', '添加会员分组 ID: ' . $id);

            }

            show_json(1, array('url' => webUrl('member/group', array('op' => 'display'))));
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
            ' SELECT id,groupname ' .
            ' FROM ' . tablename('superdesk_shop_member_group') .
            ' WHERE id in( ' . $id . ' ) ' .
            '       AND uniacid=' . $_W['uniacid']);

        foreach ($items as $item) {

            pdo_update( // 不根据id更新
                'superdesk_shop_member', // TODO 标志 楼宇之窗 openid shop_member 不处理
                array(
                    'groupid' => 0
                ),
                array(
                    'groupid' => $item['id'],
                    'uniacid' => $_W['uniacid']
                )
            );
            pdo_delete(
                'superdesk_shop_member_group',
                array(
                    'id' => $item['id']
                )
            );

            plog('member.group.delete', '删除分组 ID: ' . $item['id'] . ' 名称: ' . $item['groupname'] . ' ');
        }

        show_json(1, array('url' => referer()));
    }
}


