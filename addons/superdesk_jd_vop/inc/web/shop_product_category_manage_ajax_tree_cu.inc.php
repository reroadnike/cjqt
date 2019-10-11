<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 12/4/17
 * Time: 11:23 AM
 */

global $_W;
global $_GPC;



$parentid = intval($_GPC['parentid']);
$id       = intval($_GPC['id']);



if (!empty($id)) {

    $item     = pdo_fetch('SELECT * FROM ' . tablename('superdesk_shop_category') . ' WHERE id = \'' . $id . '\' limit 1');
//    $parentid = $item['parentid'];

} else {

    $item = array('displayorder' => 0);

}

if (!empty($parentid)) {

    $parent = pdo_fetch('SELECT id, parentid, name FROM ' . tablename('superdesk_shop_category') . ' WHERE id = \'' . $parentid . '\' limit 1');

    if (empty($parent)) {
        show_json(0, '抱歉，上级分类不存在或是已经被删除！');
    }

    if (!empty($parent['parentid'])) {
        $parent1 = pdo_fetch('SELECT id, name FROM ' . tablename('superdesk_shop_category') . ' WHERE id = \'' . $parent['parentid'] . '\' limit 1');
    }

}


if (empty($parent)) {
    $level = 1;
} else if (empty($parent['parentid'])) {
    $level = 2;
} else {
    $level = 3;
}

if (!empty($item)) {
    $item['url'] = mobileUrl('goods', array('cate' => $item['id']), 1);
}


if ($_W['ispost']) {
    $data = array(
        'uniacid'      => $_W['uniacid'],
        'name'         => trim($_GPC['catename']),
        'enabled'      => intval($_GPC['enabled']),
        'displayorder' => intval($_GPC['displayorder']),
        'isrecommand'  => intval($_GPC['isrecommand']),
        'ishome'       => intval($_GPC['ishome']),
        'description'  => $_GPC['description'],
        'parentid'     => intval($parentid),
        'thumb'        => save_media($_GPC['thumb']),
        'advimg'       => save_media($_GPC['advimg']),
        'advurl'       => trim($_GPC['advurl']),
        'level'        => $level
    );

    if (!empty($id)) {

//        show_json(0,$data);
//        unset($data['parentid']);
        pdo_update('superdesk_shop_category', $data, array('id' => $id));
        load()->func('file');
        file_delete($_GPC['thumb_old']);
        plog('shop.category.edit', '修改分类 ID: ' . $id);

    } else {

        pdo_insert('superdesk_shop_category', $data);
        $id = pdo_insertid();
        plog('shop.category.add', '添加分类 ID: ' . $id);

        $data['id'] = $id;

    }

    show_json(1, $data);
}