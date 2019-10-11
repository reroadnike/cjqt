<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}


require SUPERDESK_SHOPV2_PLUGIN . 'enterprise/core/inc/page_enterprise.php';

class Role_SuperdeskShopV2Page extends EnterpriseWebPage
{
    public function main()
    {
        global $_W;
        global $_GPC;
        $pindex    = max(1, intval($_GPC['page']));
        $psize     = 20;
        $status    = $_GPC['status'];
        $condition = ' and uniacid = :uniacid and deleted=0 and enterprise_id=:enterprise_id';
        $params    = array(':uniacid' => $_W['uniacid'], ':enterprise_id' => $_W['enterprise_id']);

        if (!empty($_GPC['keyword'])) {
            $_GPC['keyword'] = trim($_GPC['keyword']);
            $condition .= ' and rolename like :keyword';
            $params[':keyword'] = '%' . $_GPC['keyword'] . '%';
        }


        if ($_GPC['status'] != '') {
            $condition .= ' and status=' . intval($_GPC['status']);
        }


        $list  = pdo_fetchall('SELECT *  FROM ' . tablename('superdesk_shop_enterprise_perm_role') . ' WHERE 1 ' . $condition . ' ORDER BY id desc LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize, $params);
        $total = pdo_fetchcolumn('SELECT count(*) FROM ' . tablename('superdesk_shop_enterprise_perm_role') . '  WHERE 1 ' . $condition . ' ', $params);
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
        $id         = intval($_GPC['id']);
        $item       = pdo_fetch('SELECT * FROM ' . tablename('superdesk_shop_enterprise_perm_role') . ' WHERE id =:id and deleted=0 and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':id' => $id));
        $perms      = p('enterprise')->formatPerms();
        $role_perms = array();
        $user_perms = array();

        if (!empty($item)) {
            $role_perms = explode(',', $item['perms']);
        }


        if ($_W['ispost']) {
            $data = array('uniacid' => $_W['uniacid'], 'enterprise_id' => $_W['enterprise_id'], 'rolename' => trim($_GPC['rolename']), 'status' => intval($_GPC['status']), 'perms' => (is_array($_GPC['perms']) ? implode(',', $_GPC['perms']) : ''));

            if (!empty($id)) {
                pdo_update('superdesk_shop_enterprise_perm_role', $data, array('id' => $id, 'uniacid' => $_W['uniacid'], 'enterprise_id' => $_W['enterprise_id']));
                mplog('perm.role.edit', '修改角色 ID: ' . $id);
            } else {
                pdo_insert('superdesk_shop_enterprise_perm_role', $data);
                $id = pdo_insertid();
                mplog('perm.role.add', '添加角色 ID: ' . $id . ' ');
            }

            show_json(1);
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


        $items = pdo_fetchall('SELECT id,rolename FROM ' . tablename('superdesk_shop_enterprise_perm_role') . ' WHERE id in( ' . $id . ' ) AND uniacid=' . $_W['uniacid']);

        foreach ($items as $item) {
            pdo_delete('superdesk_shop_enterprise_perm_role', array('id' => $item['id']));
            mplog('perm.role.delete', '删除角色 ID: ' . $item['id'] . ' 角色名称: ' . $item['rolename'] . ' ');
        }

        show_json(1, array('url' => referer()));
    }

    public function status()
    {
        global $_W;
        global $_GPC;
        $id = intval($_GPC['id']);

        if (empty($id)) {
            $id = ((is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0));
        }


        $status = intval($_GPC['status']);
        $items  = pdo_fetchall('SELECT id,rolename FROM ' . tablename('superdesk_shop_enterprise_perm_role') . ' WHERE id in( ' . $id . ' ) AND uniacid=' . $_W['uniacid']);

        foreach ($items as $item) {
            pdo_update('superdesk_shop_enterprise_perm_role', array('status' => $status), array('id' => $item['id']));
            mplog('perm.role.edit', '修改角色状态 ID: ' . $item['id'] . ' 角色名称: ' . $item['rolename'] . ' 状态: ' . (($status == 0 ? '禁用' : '启用')));
        }

        show_json(1, array('url' => referer()));
    }

    public function query()
    {
        global $_GPC;
        global $_W;
        $kwd                = trim($_GPC['keyword']);
        $params             = array();
        $params[':uniacid'] = $_W['uniacid'];
        $params[':enterprise_id'] = $_W['enterprise_id'];   //20180403zjh 添加企业id筛选
        $condition          = ' and uniacid=:uniacid and enterprise_id=:enterprise_id and deleted=0';

        if (!empty($kwd)) {
            $condition .= ' AND `rolename` LIKE :keyword';
            $params[':keyword'] = '%' . $kwd . '%';
        }


        $ds = pdo_fetchall('SELECT id,rolename,perms FROM ' . tablename('superdesk_shop_enterprise_perm_role') . ' WHERE status=1 ' . $condition . ' order by id asc', $params);
        include $this->template();
        exit();
    }
}


?>