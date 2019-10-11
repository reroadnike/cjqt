<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=sll_orders_detail */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_orders_detail.class.php');
$sll_orders_detail = new sll_orders_detailModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $sll_orders_detail->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'orders_detail_id' => $_GPC['orders_detail_id'],// 
    'goodsid' => $_GPC['goodsid'],// 
    'pro_price' => $_GPC['pro_price'],// 单价
    'total_price' => $_GPC['total_price'],// 
    'number' => $_GPC['number'],// 
    'ctime' => $_GPC['ctime'],// 
    'orderid' => $_GPC['orderid'],// 
    'jdcOrder' => $_GPC['jdcOrder'],// 
    'agreementprice' => $_GPC['agreementprice'],// 协议价

        );
        $sll_orders_detail->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('sll_orders_detail', array('op' => 'list')), 'success');


    }
    include $this->template('sll_orders_detail_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $sll_orders_detail->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('sll_orders_detail', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $sll_orders_detail->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('sll_orders_detail_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $sll_orders_detail->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $sll_orders_detail->delete($id);

    message('删除成功！', referer(), 'success');
}

