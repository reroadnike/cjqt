<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2017/12/18 * Time: 10:18 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_jd_vop&do=order_submit_order_sku */

global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/order_submit_order_sku.class.php');
$order_submit_order_sku = new order_submit_order_skuModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $order_submit_order_sku->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'jdOrderId' => $_GPC['jdOrderId'],// jdOrderId
    'skuId' => $_GPC['skuId'],// skuId
    'num' => $_GPC['num'],// 数量
    'category' => $_GPC['category'],// 分类
    'price' => $_GPC['price'],// 售价
    'name' => $_GPC['name'],// 
    'tax' => $_GPC['tax'],// 
    'taxPrice' => $_GPC['taxPrice'],// 税额
    'nakedPrice' => $_GPC['nakedPrice'],// 裸价
    'type' => $_GPC['type'],// type为 0普通、1附件、2赠品
    'oid' => $_GPC['oid'],// oid为主商品skuid，如果本身是主商品，则oid为0

        );
        $order_submit_order_sku->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('order_submit_order_sku', array('op' => 'list')), 'success');


    }
    include $this->template('order_submit_order_sku_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $order_submit_order_sku->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('order_submit_order_sku', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $order_submit_order_sku->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('order_submit_order_sku_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $order_submit_order_sku->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $order_submit_order_sku->delete($id);

    message('删除成功！', referer(), 'success');
}

