<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/03/30
 * Time: 15:24
 * http://192.168.1.124/smart_office_building/web/index.php?c=site&a=entry&m=superdesk_jd_vop&do=balance_detail
 */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/balance_detail.class.php');


$_balance_detailModel = new balance_detailModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $_balance_detailModel->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id     = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
            'accountType'   => $_GPC['accountType'],// accountType
            'amount'        => $_GPC['amount'],// amount
            'pin'           => $_GPC['pin'],// pin
            'orderId'       => $_GPC['orderId'],// orderId
            'tradeType'     => $_GPC['tradeType'],// tradeType
            'tradeTypeName' => $_GPC['tradeTypeName'],// tradeTypeName
            'createdDate'   => $_GPC['createdDate'],// createdDate
            'notePub'       => $_GPC['notePub'],// notePub
            'tradeNo'       => $_GPC['tradeNo'],// tradeNo

        );
        $_balance_detailModel->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('balance_detail', array('op' => 'list')), 'success');


    }
    include $this->template('balance_detail_edit');

} elseif ($op == 'list') {


//    $Ymd_start = date('Y-m-d', strtotime("-1 year", time()));//-1 week
//    $Ymd_end   = date('Y-m-d', strtotime("+1 hours", time()));


    $search = $_GPC['search'];


    if ($search == null) {
        $search['start'] = strtotime("-2 year", time());
        $search['end']   = strtotime("+2 hours", time());
    } else {
        $search['start'] = strtotime($search['start']);
        $search['end']   = strtotime($search['end']);
    }

    socket_log(json_encode($search));

    include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/PriceService.class.php');
    $_priceService = new PriceService();

    $balance = $_priceService->getBalance();// 余额


    $where            = array();
    $where['orderId'] = $search['orderId'];
    $where['start']   = date('Y-m-d 00:00:00', $search['start']);
    $where['end']     = date('Y-m-d 23:59:59', $search['end']);
    $where['tradeType'] = $search['tradeType'];



    $page      = $_GPC['page'];
    $page_size = 20;

    $result    = $_balance_detailModel->queryAll($where, $page, $page_size);
    $total     = $result['total'];
    $page      = $result['page'];
    $page_size = $result['page_size'];
    $list      = $result['data'];

    $pager = pagination($total, $page, $page_size);

    $all_tradeType = $_balance_detailModel->getGroupByTradeType();

    include $this->template('balance_detail_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $_balance_detailModel->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $_balance_detailModel->delete($id);

    message('删除成功！', referer(), 'success');
}

