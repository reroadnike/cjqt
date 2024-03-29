<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=sll_orders_record */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_orders_record.class.php');
$sll_orders_record = new sll_orders_recordModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $sll_orders_record->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'newOrdersCount' => $_GPC['newOrdersCount'],// 新增订单数量
    'untreatedOrdersCount' => $_GPC['untreatedOrdersCount'],// 未处理订单数量
    'completedOrdersCount' => $_GPC['completedOrdersCount'],// 已完成订单数量（订单状态为3或5的）
    'ordersPriceSum' => $_GPC['ordersPriceSum'],// 订单总金额
    'callCount' => $_GPC['callCount'],// 来电总数
    'transactionAmount' => $_GPC['transactionAmount'],// 交易金额
    'newGoodsCount' => $_GPC['newGoodsCount'],// 新增商品数
    'storeId' => $_GPC['storeId'],// 水店id
    'ctime' => $_GPC['ctime'],// 日期

        );
        $sll_orders_record->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('sll_orders_record', array('op' => 'list')), 'success');


    }
    include $this->template('sll_orders_record_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $sll_orders_record->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('sll_orders_record', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $sll_orders_record->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('sll_orders_record_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $sll_orders_record->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $sll_orders_record->delete($id);

    message('删除成功！', referer(), 'success');
}

