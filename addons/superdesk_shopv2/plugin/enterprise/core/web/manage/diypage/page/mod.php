<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}
require SUPERDESK_SHOPV2_PLUGIN . 'enterprise/core/inc/page_enterprise.php';

class Mod_SuperdeskShopV2Page extends EnterpriseWebPage
{
    public function main()
    {
        global $_W;
        global $_GPC;
        $pagetype = 'mod';
        if (!empty($_GPC['keyword'])) {
            $keyword   = '%' . trim($_GPC['keyword']) . '%';
            $condition = ' and name like \'' . $keyword . '\' ';
        }
        $diypage_plugin = p('diypage');
        $result         = $diypage_plugin->getPageList('mod', $condition, $_GPC['page']);
        extract($result);
        include $this->template('diypage/page/list');
    }

    public function edit()
    {
        $this->post('edit');
    }

    public function add()
    {
        $this->post('add');
    }

    protected function post($do)
    {
        global $_W;
        global $_GPC;
        $diypage_plugin = p('diypage');
        $result         = $diypage_plugin->verify($do, 'mod');
        extract($result);
        $allpagetype = $diypage_plugin->getPageType();
        $typename    = $allpagetype[$type]['name'];
        if ($_W['ispost']) {
            $data = $_GPC['data'];
            $diypage_plugin->savePage($id, $data);
        }
        include $this->template('diypage/page/post');
    }

    public function delete()
    {
        global $_W;
        global $_GPC;
        $id = intval($_GPC['id']);
        if (empty($id)) {
            $id = ((is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0));
        }
        $diypage_plugin = p('diypage');
        $diypage_plugin->delPage($id);
    }

    public function query()
    {
        global $_W;
        global $_GPC;
        $diypage_plugin = p('diypage');
        $result         = $diypage_plugin->getPageList('mod');
        extract($result);
        include $this->template();
    }
}

?>