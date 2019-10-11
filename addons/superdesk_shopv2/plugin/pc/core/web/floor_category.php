<?php
if (!(defined("IN_IA"))) {
    exit("Access Denied");
}

class Floor_category_SuperdeskShopV2Page extends PluginWebPage
{
    public function main()
    {
        global $_W;
        global $_GPC;
        $pindex    = max(1, intval($_GPC['page']));
        $psize     = 20;
        $condition = ' and fc.uniacid=:uniacid';
        $params    = array(':uniacid' => $_W['uniacid']);
        if ($_GPC['enabled'] != '') {
            $condition          .= ' and fc.enabled=:enabled';
            $params[':enabled'] = intval($_GPC['enabled']);
        }
        if (!(empty($_GPC['keyword']))) {
            $_GPC['keyword']    = trim($_GPC['keyword']);
            $condition          .= ' and c.name like :keyword';
            $params[':keyword'] = '%' . $_GPC['keyword'] . '%';
        }
        $list  = pdo_fetchall(
            ' SELECT fc.*,c.name FROM ' . tablename('superdesk_shop_pc_floor_category') . ' as fc ' .
            ' LEFT JOIN ' . tablename('superdesk_shop_category') . ' as c on c.id=fc.category_id ' .
            ' WHERE 1 ' . $condition . '  ORDER BY fc.displayorder DESC limit ' . (($pindex - 1) * $psize) . ',' . $psize,
            $params
        );
        $total = pdo_fetchcolumn(
            ' SELECT count(*) FROM ' . tablename('superdesk_shop_pc_floor_category') . ' as fc ' .
            ' LEFT JOIN ' . tablename('superdesk_shop_category') . ' as c on c.id=fc.category_id ' .
            ' WHERE 1 ' . $condition,
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

            $data = array(
                'uniacid'      => $_W['uniacid'],
                'category_id'  => trim($_GPC['category_id']),
                'enabled'      => intval($_GPC['enabled']),
                'displayorder' => intval($_GPC['displayorder'])
            );

            if (!(empty($id))) {

                $data['updatetime'] = time();
                pdo_update('superdesk_shop_pc_floor_category', $data, array('id' => $id));
                plog("pc.floor_categoryedit", '修改楼层分类设置 ID: ' . $id);

            } else {

                $data['createtime'] = time();
                pdo_insert("superdesk_shop_pc_floor_category", $data);
                $id = pdo_insertid();
                plog("pc.floor_categoryadd", '添加楼层分类设置 ID: ' . $id);

            }
            m('shop')->getRefreshFloorCategory();
            show_json(1, array("url" => webUrl("pc/floor_category")));
        }
        $item = pdo_fetch(
            ' select * '.
            ' from ' . tablename('superdesk_shop_pc_floor_category') .
            ' where '.
            '   id=:id '.
            '   and uniacid=:uniacid '.
            ' limit 1',
            array(
                ':id' => $id,
                ':uniacid' => $_W['uniacid']
            )
        );

        $category = pdo_fetchall(
            ' select id,name ' .
            ' from ' . tablename('superdesk_shop_category') .
            ' where ' .
//            '   level = 1 ' .
            '   parentid=0 ' .
            '   and enabled=1 '.
            '   and uniacid=:uniacid',
            array(
                ':uniacid' => $_W['uniacid']
            )
        );

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
        $items = pdo_fetchall('SELECT id,category_id FROM ' . tablename('superdesk_shop_pc_floor_category') . ' WHERE id in( ' . $id . ' ) AND uniacid=' . $_W['uniacid']);
        foreach ($items as $item) {
            pdo_delete('superdesk_shop_pc_floor_category', array('id' => $item['id']));
            plog("pc.floor_categorydelete", '删除楼层分类设置 ID: ' . $item['id'] . ' 分类id: ' . $item['category_id'] . ' ');
        }

        m('shop')->getRefreshFloorCategory();
        show_json(1, array("url" => referer()));
    }

    public function displayorder()
    {
        global $_W;
        global $_GPC;
        $id           = intval($_GPC['id']);
        $displayorder = intval($_GPC['value']);
        $item         = pdo_fetchall('SELECT id,category_id FROM ' . tablename('superdesk_shop_pc_floor_category') . ' WHERE id in( ' . $id . ' ) AND uniacid=' . $_W['uniacid']);
        if (!(empty($item))) {
            pdo_update('superdesk_shop_pc_floor_category', array('displayorder' => $displayorder), array('id' => $id));
            plog("pc.floor_categoryedit", '修改楼层分类设置排序 ID: ' . $item['id'] . ' 分类id: ' . $item['category_id'] . ' 排序: ' . $displayorder . ' ');
        }

        m('shop')->getRefreshFloorCategory();
        show_json(1);
    }

    public function enabled()
    {
        global $_W;
        global $_GPC;
        $id = intval($_GPC['id']);
        if (empty($id)) {
            $id = ((is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0));
        }
        $items = pdo_fetchall('SELECT id,category_id FROM ' . tablename('superdesk_shop_pc_floor_category') . ' WHERE id in( ' . $id . ' ) AND uniacid=' . $_W['uniacid']);
        foreach ($items as $item) {
            pdo_update('superdesk_shop_pc_floor_category', array('enabled' => intval($_GPC['enabled'])), array('id' => $item['id']));
            plog("pc.floor_categoryedit", (('修改楼层分类设置状态<br/>ID: ' . $item['id'] . '<br/>分类id: ' . $item['category_id'] . '<br/>状态: ' . $_GPC['enabled']) == 1 ? '显示' : '隐藏'));
        }

        m('shop')->getRefreshFloorCategory();
        show_json(1, array("url" => referer()));
    }
}
?>