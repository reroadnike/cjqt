<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=sll_brand */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_brand.class.php');
$sll_brand = new sll_brandModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $sll_brand->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'brand_id' => $_GPC['brand_id'],// 
    'brand_name' => $_GPC['brand_name'],// 品牌名称
    'status' => $_GPC['status'],// 0：废弃 1：使用 

        );
        $sll_brand->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('sll_brand', array('op' => 'list')), 'success');


    }
    include $this->template('sll_brand_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $sll_brand->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('sll_brand', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $sll_brand->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('sll_brand_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $sll_brand->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $sll_brand->delete($id);

    message('删除成功！', referer(), 'success');
}

