<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=zc_invoice */

global $_GPC, $_W;
$active='zc_invoice';

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/zc_invoice.class.php');
$zc_invoice = new zc_invoiceModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $zc_invoice->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'zi_id' => $_GPC['zi_id'],// 
    'zi_invoiceType' => $_GPC['zi_invoiceType'],// 1 普通发票 2 增值税发票
    'zi_selectedInvoiceTitle' => $_GPC['zi_selectedInvoiceTitle'],// 发票类型：1:个人，2 :单位
    'zi_companyName' => $_GPC['zi_companyName'],// 发票抬头  
    'zi_invoiceContent' => $_GPC['zi_invoiceContent'],// 0:明细，1：电脑配件，2:耗材，3：办公用品(备注:若增值发票则只能选 0 明细) 
    'zi_invoiceAddress' => $_GPC['zi_invoiceAddress'],// 增值票注册地址
    'zi_invoicePhone' => $_GPC['zi_invoicePhone'],// 增值票注册电话
    'zi_state' => $_GPC['zi_state'],// 0:失效，1：有效
    'zi_fansid' => $_GPC['zi_fansid'],// 粉丝id
    'zi_ctime' => $_GPC['zi_ctime'],// 创建时间
    'zi_taxpayer_identification_number' => $_GPC['zi_taxpayer_identification_number'],// 纳税人识别号
    'zi_invoiceBank' => $_GPC['zi_invoiceBank'],// 增值票开户银行
    'zi_invoiceAccount' => $_GPC['zi_invoiceAccount'],// 增值票开户帐号
    'zi_invoiceName' => $_GPC['zi_invoiceName'],// 增值票收票人

        );
        $zc_invoice->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('zc_invoice', array('op' => 'list')), 'success');


    }
    include $this->template('zc_invoice_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $zc_invoice->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('zc_invoice', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $zc_invoice->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('zc_invoice_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $zc_invoice->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $zc_invoice->delete($id);

    message('删除成功！', referer(), 'success');
}

