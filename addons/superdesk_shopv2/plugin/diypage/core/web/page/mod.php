<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

/**
 * 模板
 * Class Mod_SuperdeskShopV2Page
 */
class Mod_SuperdeskShopV2Page extends PluginWebPage
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


        $result = $this->model->getPageList('mod', $condition, $_GPC['page']);
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

        $result = $this->model->verify($do, 'mod');
        extract($result);

        $allpagetype = $this->model->getPageType();

        $typename = $allpagetype[$type]['name'];

        if ($_W['ispost']) {
            $data = $_GPC['data'];
            $this->model->savePage($id, $data);
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

        $this->model->delPage($id);
    }

    public function query()
    {
        global $_W;
        global $_GPC;

        $result = $this->model->getPageList('mod');

        extract($result);

        include $this->template();
    }
}