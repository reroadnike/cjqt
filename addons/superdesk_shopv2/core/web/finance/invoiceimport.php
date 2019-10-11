<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Invoiceimport_SuperdeskShopV2Page extends WebPage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        if ($_W['ispost']) {

            $rows      = m('excel')->import('excelfile');
            $num       = count($rows);
            $time      = time();
            $i         = 0;
            $err_array = array();
            $new_array = array();
            foreach ($rows as $k => $v) {
                if (!empty($v[0]) && (!isset($new_array[$v[0]]) || empty($new_array[$v[0]]))) {
                    $new_array[$v[0]] = $v[1];
                }
            }

            foreach ($new_array as $k => $v) {
                $id         = trim($k);
                $invoice_sn = trim($v);

                if (empty($id)) {
                    continue;
                }


                if (empty($invoice_sn)) {
                    $err_array[] = $id;
                    continue;
                }


                $finance = pdo_fetch(
                    'select id,status ' .
                    ' from ' . tablename('superdesk_shop_order_finance') .
                    ' where ' .
                    '       id=:id ' .
                    '       and uniacid=:uniacid ' .
                    '       and status=1',
                    array(
                        ':id'      => $id,
                        ':uniacid' => $_W['uniacid']
                    )
                );

                if (!empty($finance)) {

                    $data = array(
                        'status'              => 2,
                        'create_invoice_time' => $time,
                        'invoice_sn'          => $invoice_sn
                    );

                    pdo_update('superdesk_shop_order_finance', $data, array('id' => $id));

                    plog('finance.import', '财务跟踪导入开票号 ID: ' . $id . ' 开票号: ' . $invoice_sn);


                    ++$i;
                } else {
                    $err_array[] = $id;
                }
            }

            $tip = '';
            $msg = $i . '个财务跟踪导入开票号成功！';

            if ($i < $num) {
                $url = '';

                if (!empty($err_array)) {
                    $j   = 1;
                    $tip .= '<br>' . count($err_array) . '个财务跟踪导入开票号失败,失败的id: <br>';

                    foreach ($err_array as $k => $v) {
                        $tip .= $v . ' ';

                        if (($j % 10) == 0) {
                            $tip .= '<br>';
                        }


                        ++$j;
                    }
                }

            } else {
                $url = webUrl('finance/invoiceimport');
            }

            $this->message($msg . $tip, $url, '');
        }


        $express_list = m('express')->getExpressList();
        include $this->template();
    }

    public function import()
    {
        $columns = array();

        $columns[] = array('title' => '订单编号', 'field' => '', 'width' => 32);
        $columns[] = array('title' => '快递单号', 'field' => '', 'width' => 32);

        m('excel')->temp('批量发货数据模板', $columns);
    }
}