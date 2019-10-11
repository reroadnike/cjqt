<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2017/12/18 * Time: 10:18 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_jd_vop&do=product_detail */

global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/product_detail.class.php');
$product_detail = new product_detailModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $product_detail->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'sku' => $_GPC['sku'],// 单品
    'name' => $_GPC['name'],// 商品名称
    'page_num' => $_GPC['page_num'],// page_num
    'category' => $_GPC['category'],// 类别
    'upc' => $_GPC['upc'],// 条形码
    'saleUnit' => $_GPC['saleUnit'],// 销售单位
    'weight' => $_GPC['weight'],// 重量
    'productArea' => $_GPC['productArea'],// 产地
    'wareQD' => $_GPC['wareQD'],// 商品清单
    'imagePath' => $_GPC['imagePath'],// 主图地址
    'param' => $_GPC['param'],// 
    'brandName' => $_GPC['brandName'],// 品牌
    'state' => $_GPC['state'],// 上下架状态
    'shouhou' => $_GPC['shouhou'],// 售后
    'introduction' => $_GPC['introduction'],// web介绍
    'appintroduce' => $_GPC['appintroduce'],// app介绍

        );
        $product_detail->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('product_detail', array('op' => 'list')), 'success');


    }
    include $this->template('product_detail_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $product_detail->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('product_detail', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $product_detail->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('product_detail_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $product_detail->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $product_detail->delete($id);

    message('删除成功！', referer(), 'success');
}

