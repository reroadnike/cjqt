<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2017/12/18 * Time: 10:18 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_jd_vop&do=product_price */

global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/product_price.class.php');
$product_price = new product_priceModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $product_price->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'skuId' => $_GPC['skuId'],// skuId
    'productprice' => $_GPC['productprice'],// 京东价格
    'marketprice' => $_GPC['marketprice'],// 客户购买价格
    'costprice' => $_GPC['costprice'],// 协议价格

        );
        $product_price->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('product_price', array('op' => 'list')), 'success');


    }
    include $this->template('product_price_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $product_price->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('product_price', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $product_price->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('product_price_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $product_price->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $product_price->delete($id);

    message('删除成功！', referer(), 'success');
}

