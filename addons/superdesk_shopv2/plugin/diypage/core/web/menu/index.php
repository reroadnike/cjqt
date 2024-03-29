<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Index_SuperdeskShopV2Page extends PluginWebPage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        if (!empty($_GPC['keyword'])) {
            $keyword   = '%' . trim($_GPC['keyword']) . '%';
            $condition = ' and name like \'' . $keyword . '\' ';
        }

        $pindex = max(1, intval($_GPC['page']));
        $psize  = 15;

        $list = pdo_fetchall(
            'select id, name, createtime, lastedittime ' .
            ' from ' . tablename('superdesk_shop_diypage_menu') .
            ' where uniacid=:uniacid ' .
            $condition .
            ' order by id desc ' .
            ' limit ' . (($pindex - 1) * $psize) . ',' . $psize,
            array(
                ':uniacid' => $_W['uniacid']
            )
        );

        $total = pdo_fetchcolumn(
            'SELECT COUNT(*) ' .
            ' FROM ' . tablename('superdesk_shop_diypage_menu') .
            ' where ' .
            '       uniacid=:uniacid ' .
            $condition,
            array(
                ':uniacid' => $_W['uniacid']
            )
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

        if (!empty($id)) {

            $menu = pdo_fetch(
                'SELECT * ' .
                ' FROM ' . tablename('superdesk_shop_diypage_menu') .
                ' WHERE ' .
                '       id=:id ' .
                '       and uniacid=:uniacid ' .
                ' limit 1 ',
                array(
                    ':id'      => $id,
                    ':uniacid' => $_W['uniacid']
                )
            );

            if (!empty($menu)) {
                $menu['data'] = base64_decode($menu['data']);
                $menu['data'] = json_decode($menu['data'], true);
            }
        }

        if ($_W['ispost']) {

            $data = $_GPC['menu'];

            $menudata = array(
                'name'         => $data['name'],
                'data'         => base64_encode(json_encode($data)),
                'lastedittime' => time()
            );

            if (!empty($id)) {

                pdo_update('superdesk_shop_diypage_menu', $menudata, array('id' => $id, 'uniacid' => $_W['uniacid']));

                plog('diypage.menu.edit', '更新自定义菜单 id: ' . $id . '  名称:' . $menudata['name']);

            } else {

                $menudata['uniacid']    = $_W['uniacid'];
                $menudata['createtime'] = time();

                pdo_insert('superdesk_shop_diypage_menu', $menudata);
                $id = pdo_insertid();

                plog('diypage.menu.add', '添加自定义菜单 id: ' . $id . '  名称:' . $menudata['name']);
            }

            show_json(1, array('id' => $id));
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
            'SELECT id,name ' .
            ' FROM ' . tablename('superdesk_shop_diypage_menu') .
            ' WHERE ' .
            '       id in( ' . $id . ' ) ' .
            '       and uniacid=:uniacid ',
            array(
                ':uniacid' => $_W['uniacid']
            )
        );

        foreach ($items as $item) {

            pdo_delete('superdesk_shop_diypage_menu', array('id' => $item['id'], 'uniacid' => $_W['uniacid']));

            plog('diypage.menu.delete', '删除自定义菜单 id: ' . $item['id'] . '  名称:' . $item['name']);
        }

        show_json(1);
    }
}