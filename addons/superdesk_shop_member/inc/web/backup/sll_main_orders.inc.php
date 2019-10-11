<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=sll_main_orders */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_main_orders.class.php');
$sll_main_orders = new sll_main_ordersModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $sll_main_orders->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'main_id' => $_GPC['main_id'],// 
    'main_tel' => $_GPC['main_tel'],// 
    'main_fansid' => $_GPC['main_fansid'],// 
    'main_openid' => $_GPC['main_openid'],// 
    'main_orderid' => $_GPC['main_orderid'],// 主订单id
    'main_orderPrice' => $_GPC['main_orderPrice'],// 总价
    'main_pay_time' => $_GPC['main_pay_time'],// 
    'main_payNumber' => $_GPC['main_payNumber'],// 支付流水号
    'main_isPay' => $_GPC['main_isPay'],// 是否支付 0:未支付  1：已支付
    'main_ctime' => $_GPC['main_ctime'],// 
    'main_payconf' => $_GPC['main_payconf'],// 付款方式,0：表示微信，1：货到付款，2：来电支付，3：银行卡
    'main_type' => $_GPC['main_type'],// 0 : 微信  1：后台
    'main_payMsg' => $_GPC['main_payMsg'],// 支付返回信息
    'main_e_id' => $_GPC['main_e_id'],// 项目id
    'main_invoiceType' => $_GPC['main_invoiceType'],// 1 普通发票 2 增值税发票
    'main_selectedInvoiceTitle' => $_GPC['main_selectedInvoiceTitle'],// 发票类型：1:个人，2 :单位
    'main_companyName' => $_GPC['main_companyName'],// 发票抬头  
    'main_invoiceContent' => $_GPC['main_invoiceContent'],// 0:明细，1：电脑配件，2:耗材，3：办公用品(备注:若增值发票则只能选 0 明细) 
    'main_invoiceName' => $_GPC['main_invoiceName'],// 增值票收票人姓名
    'main_invoicePhone' => $_GPC['main_invoicePhone'],// 增值票注册电话
    'main_make_out_invoice_state' => $_GPC['main_make_out_invoice_state'],// 订单开票状态0：不开票，1：未开票，2：已开票，3：已退票，发票信息错误，4：已退票，发票类型错误，5：已退票，退款
    'main_taxpayer_identification_number' => $_GPC['main_taxpayer_identification_number'],// 纳税人识别号
    'main_invoiceBank' => $_GPC['main_invoiceBank'],// 增值票开户银行
    'main_invoiceAccount' => $_GPC['main_invoiceAccount'],// 增值票开户帐号
    'main_invoiceAddress' => $_GPC['main_invoiceAddress'],// 增值票注册地址

        );
        $sll_main_orders->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('sll_main_orders', array('op' => 'list')), 'success');


    }
    include $this->template('sll_main_orders_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $sll_main_orders->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('sll_main_orders', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $sll_main_orders->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('sll_main_orders_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $sll_main_orders->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $sll_main_orders->delete($id);

    message('删除成功！', referer(), 'success');
}

