<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 1/22/18
 * Time: 7:34 PM
 */


global $_W, $_GPC;


$_overwrite  = false;
$page_num    = $_GPC['page_num'];
$classify_id = $_GPC['classify_id'];
$_overwrite  = $_GPC['overwrite'];

if (empty($page_num)) {
    show_json(0, 'page_num is null');
}


include_once(IA_ROOT . '/addons/superdesk_shopv2/service/CategoryService.class.php');
$_categoryService = new CategoryService();


$_shop_cate = $_categoryService->getByPageNum($page_num);


if ($_shop_cate) {
    $_categoryService->setCacheOldCateId2NewCateId($classify_id, $_shop_cate['id']);

    show_json(1, $_shop_cate);
} else {
    show_json(0, '保存不成功,没有找到新版商城分类或不是三级分类');
}

