<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/__plugin__/enterprise/enterprise_group.class.php');

class Group_SuperdeskShopV2Page extends PluginWebPage
{

    private $_enterprise_groupModel;

    public function __construct()
    {

        global $_W;
        global $_GPC;
        parent::__construct();

        $this->_enterprise_groupModel = new enterprise_groupModel();
    }

    public function main()
    {
        global $_W;
        global $_GPC;

        $data = $this->_enterprise_groupModel->queryAll();
        $total = $data['total'];
        $list = $data['data'];

        $pager = pagination($total, $data['page'], $data['page_size']);
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
            $data = array('uniacid' => $_W['uniacid'], 'groupname' => trim($_GPC['groupname']), 'status' => intval($_GPC['status']), 'isdefault' => intval($_GPC['isdefault']));

            if ($data['isdefault'] == 1) {
                $this->_enterprise_groupModel->updateByColumn(array('isdefault' => 0), array('uniacid' => $_W['uniacid'], 'isdefault' => 1));
            }


            if (!empty($id)) {
                plog('enterprise.group.edit', '修改企业分组 ID: ' . $id);
            } else {
                $data['createtime'] = time();
                plog('enterprise.group.add', '添加企业分组 ID: ' . $id);
            }
            $id = $this->_enterprise_groupModel->saveOrUpdate($data,$id);

            show_json(1, array('url' => webUrl('enterprise/group')));
        }


        $item = $this->_enterprise_groupModel->getOneByColumn(array('id' => $id, 'uniacid' => $_W['uniacid']));
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

        $where = ' WHERE id in( ' . $id . ' ) AND uniacid=:uniacid ';
        $params = array(':uniacid' => $_W['uniacid']);
        $items = $this->_enterprise_groupModel->getAllByWhere($where,$params);

        foreach ($items as $item) {
            $this->_enterprise_groupModel->delete($item['id']);
            plog('enterprise.group.delete', '删除企业分组 ID: ' . $item['id'] . ' 标题: ' . $item['groupname'] . ' ');
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



        $where = ' WHERE id in( ' . $id . ' ) AND uniacid=:uniacid ';
        $params = array(':uniacid' => $_W['uniacid']);
        $items = $this->_enterprise_groupModel->getAllByWhere($where,$params);

        foreach ($items as $item) {
            $this->_enterprise_groupModel->update(array('status' => intval($_GPC['status'])),$id);
            plog('enterprise.group.edit', (('修改企业分组状态<br/>ID: ' . $item['id'] . '<br/>分组名称: ' . $item['groupname'] . '<br/>状态: ' . $_GPC['status']) == 1 ? '显示' : '隐藏'));
        }

        show_json(1, array('url' => referer()));
    }

    public function setdefault()
    {
        global $_W;
        global $_GPC;
        $id    = intval($_GPC['id']);
        $group = $this->_enterprise_groupModel->getOne($id);

        if (empty($group)) {
            show_json(0, '抱歉，企业分组不存在或是已经被删除！');
        }

        $this->_enterprise_groupModel->updateByColumn(array('isdefault' => 0), array('uniacid' => $_W['uniacid'], 'isdefault' => 1));
        $this->_enterprise_groupModel->update(array('isdefault' => 1),$group['id']);
        plog('enterprise.group.setdefault', '设置默认企业分组 ID: ' . $id . ' 分组名称: ' . $group['groupname']);
        show_json(1);
    }
}


?>