<?php
/**
 * 商城分类财务代码
 * User: linjinyu
 * Date: 1/4/18
 * Time: 11:38 AM
 */

global $_W;
global $_GPC;

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/category.class.php');
$_categoryModel = new categoryModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $id   = $_GPC['id'];
    $item = $_categoryModel->getOne($_GPC['id']);

    if (empty($item)) {
        show_json(0,'抱歉，该信息不存在或是已经被删除！');
    }

    if(empty($_GPC['fiscal_code'])){
        show_json(0,'抱歉，代码未填写');
    }


    $params = array(
        'fiscal_code' => $_GPC['fiscal_code'],// fiscal_code

    );
    $ret = $_categoryModel->saveOrUpdate($params, $id);

    show_json(1,$ret);

//    if (checksubmit('submit')) {
//
//        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
//        $params = array(
//            'code' => $_GPC['code'],// code
//            'parent_code' => $_GPC['parent_code'],// parent_code
//            'level' => $_GPC['level'],// level
//            'text' => $_GPC['text'],// text
//            'state' => $_GPC['state'],// state
//            'remark' => $_GPC['remark'],// 标注
//
//        );
//        $_categoryModel->saveOrUpdate($params, $id);
//
//        message('成功！', $this->createWebUrl('area', array('op' => 'list')), 'success');
//
//    }

} elseif ($op == 'list') {

//    if (!empty($_GPC['displayorder'])) {
//        foreach ($_GPC['displayorder'] as $id => $displayorder) {
//
//            $params = array('displayorder' => $displayorder);
//            $where = array('id' => $id);
//
//            $area->update($params,$where);
//        }
//        message('显示顺序更新成功！', $this->createWebUrl('area', array('op' => 'list')), 'success');
//    }

    $page = $_GPC['page'];
    $page_size = 10000;

    $result = $_categoryModel->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];


    $category = $result['data'];

    $children = array();
    foreach ($category as $index => $row) {
        if (!empty($row['parentid'])) {
            $children[$row['parentid']][] = $row;
            unset($category[$index]);
        }
    }



    $pager = pagination($total, $page, $page_size);

    include $this->template('shop_product_category_2_fiscal');

} elseif ($op == 'delete') {

//    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
//
//    $item = $area->getOne($_GPC['id']);
//
//    if (empty($item)) {
//        message('抱歉，该信息不存在或是已经被删除！');
//    }
//
//    $area->delete($id);
//
//    message('删除成功！', referer(), 'success');

}