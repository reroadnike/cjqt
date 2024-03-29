<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

require SUPERDESK_SHOPV2_PLUGIN . 'merch/core/inc/page_merch.php';

class Category_SuperdeskShopV2Page extends MerchWebPage
{
    public function __construct($_com = 'virtual')
    {
        parent::__construct($_com);
    }

    public function main()
    {
        global $_W;
        global $_GPC;

        if (!empty($_GPC['catname'])) {

            mca('goods.virtual.category.edit');

            foreach ($_GPC['catname'] as $id => $catname) {
                $catname = trim($catname);

                if (empty($catname)) {
                    continue;
                }

                if ($id == 'new') {

                    pdo_insert(
                        'superdesk_shop_virtual_category',
                        array(
                            'name'    => $catname,
                            'uniacid' => $_W['uniacid'],
                            'merchid' => $_W['merchid']
                        )
                    );
                    $insert_id = pdo_insertid();

                    mplog('goods.virtual.category.add', '添加分类 ID: ' . $insert_id);
                } else {

                    pdo_update(
                        'superdesk_shop_virtual_category',
                        array(
                            'name' => $catname
                        ),
                        array(
                            'id'      => $id,
                            'uniacid' => $_W['uniacid'],
                            'merchid' => $_W['merchid']
                        )
                    );

                    mplog('goods.virtual.category.edit', '修改分类 ID: ' . $id);
                }
            }

            mplog('goods.virtual.category.edit', '批量修改分类');

            show_json(1, array('url' => merchUrl('goods/virtual/category')));
        }

        $list = pdo_fetchall(
            'SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_virtual_category') .
            ' WHERE ' .
            '       uniacid = \'' . $_W['uniacid'] . '\' ' .
            '       and merchid = \'' . $_W['merchid'] . '\' ' .
            ' ORDER BY id DESC'
        );

        include $this->template();
    }

    public function delete()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        $item = pdo_fetch(
            'SELECT id,name ' .
            ' FROM ' . tablename('superdesk_shop_virtual_category') .
            ' WHERE ' .
            '       id = \'' . $id . '\' ' .
            '       AND uniacid=' . $_W['uniacid'] .
            '       AND merchid=' . $_W['merchid']);

        if (empty($item)) {

            $this->message('抱歉，分类不存在或是已经被删除！', merchUrl('goods/virtual/category', array('op' => 'display')), 'error');
        }


        pdo_delete('superdesk_shop_virtual_category', array('id' => $id));

        mplog('goods.virtual.category.delete', '删除分类 ID: ' . $id . ' 标题: ' . $item['name'] . ' ');

        show_json(1, array(
            'url' => merchUrl('goods/virtual/category', array('op' => 'display'))
        ));
    }
}