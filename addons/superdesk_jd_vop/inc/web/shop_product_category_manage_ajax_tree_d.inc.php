<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 12/4/17
 * Time: 11:23 AM
 */

global $_W;
global $_GPC;
$id = intval($_GPC['id']);
$item = pdo_fetch('SELECT id, name, parentid FROM ' . tablename('superdesk_shop_category') . ' WHERE id = \'' . $id . '\'');

if (empty($item)) {

    show_json(0, '抱歉，分类不存在或是已经被删除！');
}


//pdo_delete('superdesk_shop_category', array('id' => $id, 'parentid' => $id), 'OR');

pdo_delete('superdesk_shop_category', array('id' => $id));
plog('shop.category.delete', '删除分类 ID: ' . $id . ' 分类名称: ' . $item['name']);

show_json(1, array('url' => referer()));