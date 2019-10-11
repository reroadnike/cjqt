<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=sll_classify */

global $_GPC, $_W;

$filter_total_0 = intval($_GPC['filter_total_0']);


$active = 'sll_classify' . '_' . $filter_total_0;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_classify.class.php');
$_sll_classifyModel = new sll_classifyModel();

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_goods.class.php');
$_sll_goodsModel = new sll_goodsModel();

//include_once(IA_ROOT . '/addons/superdesk_shopv2/model/category.class.php');
//$_categoryModel = new categoryModel();

include_once(IA_ROOT . '/addons/superdesk_shopv2/service/CategoryService.class.php');
$_categoryService = new CategoryService();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $_sll_classifyModel->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id     = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
            'classify_id'       => $_GPC['classify_id'],//
            'classify_name'     => $_GPC['classify_name'],// 分类名称
            'classify_status'   => $_GPC['classify_status'],// 0：废弃 1：使用
            'classify_pic'      => $_GPC['classify_pic'],// 图片
            'classify_sort'     => $_GPC['classify_sort'],// 排序
            'classify_ctime'    => $_GPC['classify_ctime'],//
            'classify_pid'      => $_GPC['classify_pid'],// 父类id
            'page_num'          => $_GPC['page_num'],//
            'classify_isshow'   => $_GPC['classify_isshow'],// 是否显示，1显示0不显示
            'classify_main_pic' => $_GPC['classify_main_pic'],// 主图片

        );
        $_sll_classifyModel->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('sll_classify', array('op' => 'list')), 'success');


    }

    include $this->template('sll_classify_edit');

} elseif ($op == 'list') {

    $page      = $_GPC['page'];
    $page_size = 666;

    $result    = $_sll_classifyModel->queryAll(array(), $page, $page_size);
    $total     = $result['total'];
    $page      = $result['page'];
    $page_size = $result['page_size'];
    $list      = $result['data'];

    $count_goods = 0;

    foreach ($list as $index => $__classify) {

        $list[$index]['target_cate_id'] = 0;
        $list[$index]['total']          = $_sll_goodsModel->countByOriginEq1AndClassifyId($__classify['classify_id']);
        $count_goods                    = $count_goods + $list[$index]['total'];

        if ($filter_total_0 == 1 & $list[$index]['total'] != 0) {

            $list[$index]['target_cate_id'] = $_categoryService->getCacheOldCateId2NewCateId($__classify['classify_id']);

        }
    }

    $pager = pagination($total, $page, $page_size);

    include $this->template('sll_classify_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $_sll_classifyModel->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $_sll_classifyModel->delete($id);

    message('删除成功！', referer(), 'success');
}

