<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 1/18/18
 * Time: 3:31 PM
 */


global $_W, $_GPC;


$_overwrite     = false;
$target_cate_id = $_GPC['target_cate_id'];
$classify_id    = $_GPC['classify_id'];
$_overwrite     = $_GPC['overwrite'];

if (empty($target_cate_id)) {
    show_json(0, 'target_cate_id is null');
}


include_once(IA_ROOT . '/addons/superdesk_shopv2/service/CategoryService.class.php');
$_categoryService = new CategoryService();

$_shop_cate = $_categoryService->getByIdAndLevel2($target_cate_id);

if ($_shop_cate) {
    $_categoryService->setCacheOldCateId2NewCateId($classify_id, $target_cate_id);

    show_json(1, $_shop_cate);
} else {
    show_json(0, '保存不成功,没有找到新版商城分类或不是三级分类');
}

