<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Index_SuperdeskShopV2Page extends PluginWebPage
{
    public function main()
    {
        header('location: ' . webUrl('diypage'));
    }

    public function create()
    {
        global $_W;
        global $_GPC;

        $tid_member = pdo_fetchcolumn(
            'select id ' .
            ' from' . tablename('superdesk_shop_diypage_template') .
            ' where ' .
            '       tplid=9 ' .
            ' limit 1'
        );

        $tid_commission = pdo_fetchcolumn(
            'select id ' .
            ' from' . tablename('superdesk_shop_diypage_template') .
            ' where ' .
            '       tplid=10 ' .
            ' limit 1'
        );

        $tid_detail = pdo_fetchcolumn(
            'select id ' .
            ' from' . tablename('superdesk_shop_diypage_template') .
            ' where ' .
            '       tplid=11 ' .
            ' limit 1'
        );

        include $this->template('diypage/page/create');
    }

    public function keyword()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        $keyword = trim($_GPC['keyword']);

        if (!empty($keyword)) {

            $result = m('common')->keyExist($keyword);

            if (!empty($result)) {
                if ($result['name'] != 'superdesk_shopv2:diypage:' . $id) {
                    show_json(0);
                }
            }
        }

        show_json(1);
    }

    public function preview()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        if (empty($id)) {
            header('location: ' . webUrl('diypage'));
        }

        $pagetype = '';

        $page = $this->model->getPage($id);

        if (!empty($page)) {
            if ($page['type'] == 1) {
                $pagetype = 'diy';
            } else if ((1 < $page['type']) && ($page['type'] < 99)) {
                $pagetype = 'sys';
            } else if ($page['type'] == 99) {
                $pagetype = 'mod';
            }
        }
        include $this->template();
    }
}