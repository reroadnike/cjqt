<?php

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/accessControl.php";

if (!(defined("IN_IA"))) {
    exit("Access Denied");
}

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/page_login_mobile.php";

class Invoice_SuperdeskShopV2Page extends PcMobileLoginPage
{
    public function main()
    {
        global $_W;
        global $_GPC;
        global $_S;

        $view_invoiceType = array(
            1 => '增值税普票',
            2 => '增值税专票'
        );

        $view_selectedInvoiceTitle = array(
            4 => '个人',
            5 => '单位'
        );

        $view_invoiceContent = array(
            1  => '明细',
            3  => '电脑配件',
            19 => '耗材',
            22 => '办公用品'
        );

        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;

        $condition =
            ' and openid=:openid ' .
            ' and core_user=:core_user ' .
            ' and deleted=0 ' .
            ' and uniacid = :uniacid ';

        $params = array(
            ':uniacid'   => $_W['uniacid'],
            ':openid'    => $_W['openid'],
            ':core_user' => $_W['core_user'],
        );

        $sql   =
            'SELECT ' .
            '       COUNT(*) ' .
            ' FROM ' . tablename('superdesk_shop_member_invoice') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 已处理
            ' where 1 ' .
            $condition;
        $total = pdo_fetchcolumn($sql, $params);

        $sql  = 'SELECT ' .
            '       id,isdefault,companyName,taxpayersIDcode,selectedInvoiceTitle,invoiceType,invoiceContent ' .
            ' FROM ' . tablename('superdesk_shop_member_invoice') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 已处理
            ' where 1 ' .
            $condition .
            ' ORDER BY `isdefault` DESC,`id` DESC ' .
            ' LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize;
        $list = pdo_fetchall($sql, $params);

        foreach ($list as $k => &$v) {
            $v['invoiceType']          = $view_invoiceType[$v['invoiceType']];
            $v['invoiceContent']       = $view_invoiceContent[$v['invoiceContent']];
            $v['selectedInvoiceTitle'] = $view_selectedInvoiceTitle[$v['selectedInvoiceTitle']];
        }

        unset($v);

        show_json(1, compact('list','total'));
    }

    public function setdefault()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        $data = pdo_fetch(
            'select id ' .
            ' from ' . tablename('superdesk_shop_member_invoice') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 不处理
            ' where ' .
            '       id=:id ' .
            '       and deleted=0 ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':uniacid' => $_W['uniacid'],
                ':id'      => $id
            )
        );

        if (empty($data)) {
            show_json(0, '发票未找到');
        }

        pdo_update(
            "superdesk_shop_member_invoice", // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 已处理
            array(
                "isdefault" => 0),
            array(
                "uniacid"   => $_W['uniacid'],
                'openid'    => $_W['openid'],
                'core_user' => $_W['core_user'],
            )
        );

        pdo_update(
            "superdesk_shop_member_invoice", // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 已处理
            array(
                "isdefault" => 1
            ),
            array(
                "id"        => $id,
                'uniacid'   => $_W['uniacid'],
                'openid'    => $_W['openid'],
                'core_user' => $_W['core_user'],
            )
        );

        show_json(1);
    }

    public function delete()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        $data = pdo_fetch(
            'select ' .
            '       id,isdefault ' .
            ' from ' . tablename('superdesk_shop_member_invoice') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 已处理
            ' where 1 ' .
            '       and id=:id ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and deleted=0 ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':id'        => $id,
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user'],
            )
        );

        if (empty($data)) {
            show_json(0, '发票未找到');
        }

        pdo_update(
            "superdesk_shop_member_invoice", // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 不处理
            array(
                "deleted" => 1
            ),
            array(
                "id" => $id
            )
        );

        if ($data['isdefault'] == 1) {

            pdo_update(
                'superdesk_shop_member_invoice', // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 已处理
                array(
                    'isdefault' => 0
                ),
                array(
                    'uniacid'   => $_W['uniacid'],
                    'openid'    => $_W['openid'],
                    'core_user' => $_W['core_user'],
                    'id'        => $id
                )
            );

            $data2 = pdo_fetch(
                'select ' .
                '       id ' .
                ' from ' . tablename('superdesk_shop_member_invoice') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 已处理
                ' where ' .
                '       openid=:openid ' .
                '       and core_user=:core_user ' .
                '       and deleted=0 ' .
                '       and uniacid=:uniacid ' .
                ' order by id desc ' .
                ' limit 1',
                array(
                    ':uniacid'   => $_W['uniacid'],
                    ':openid'    => $_W['openid'],
                    ':core_user' => $_W['core_user'],
                )
            );

            if (!(empty($data2))) {

                pdo_update(
                    'superdesk_shop_member_invoice', // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 已处理
                    array(
                        'isdefault' => 1
                    ),
                    array(
                        'uniacid'   => $_W['uniacid'],
                        'openid'    => $_W['openid'],
                        'core_user' => $_W['core_user'],
                        'id'        => $data2['id']
                    )
                );

                show_json(1, array("defaultid" => $data2['id']));
            }
        }

        show_json(1);
    }

    public function detail()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        $invoice = pdo_fetch(
            'select * ' .
            ' from ' . tablename('superdesk_shop_member_invoice') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 已处理
            ' where ' .
            '       id=:id ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and uniacid=:uniacid ' .
            ' limit 1 ',
            array(
                ':id'        => $id,
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user'],
            )
        );

        if (empty($invoice)) {
            show_json(0);
        }

        show_json(1, $invoice);
    }

    public function submit()
    {
        global $_W;
        global $_GPC;

        $id   = intval($_GPC['id']);
        $data = array(
            'invoiceType'          => $_GPC['invoiceType'],
            'selectedInvoiceTitle' => $_GPC['selectedInvoiceTitle'],
            'companyName'          => $_GPC['companyName'],
            'taxpayersIDcode'      => $_GPC['taxpayersIDcode'],
            'invoiceAddress'       => $_GPC['invoiceAddress'],
            'invoicePhone'         => $_GPC['invoicePhone'],
            'invoiceBank'          => $_GPC['invoiceBank'],
            'invoiceAccount'       => $_GPC['invoiceAccount'],
            'invoiceContent'       => $_GPC['invoiceContent'],
        );

//        companyName
//        invoiceContent
//        invoiceType
//        selectedInvoiceTitle
//        taxpayersIDcode

        $data['uniacid']   = $_W['uniacid'];
        $data['openid']    = $_W['openid'];
        $data['core_user'] = $_W['core_user'];

        if (empty($id)) {

            $invoice_count = pdo_fetchcolumn(
                ' SELECT count(*) ' .
                ' FROM ' . tablename('superdesk_shop_member_invoice') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 已处理
                ' where ' .
                '       openid=:openid ' .
                '       and core_user=:core_user ' .
                '       and deleted=0 ' .
                '       and uniacid = :uniacid ',
                array(
                    ':uniacid'   => $_W['uniacid'],
                    ':openid'    => $_W['openid'],
                    ':core_user' => $_W['core_user'],
                )
            );

            if ($invoice_count <= 0) {
                $data['isdefault'] = 1;
            }

            $invoice_default = $invoice_count = pdo_fetch(
                ' SELECT * ' .
                ' FROM ' . tablename('superdesk_shop_member_invoice') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 已处理
                ' where ' .
                '       openid=:openid ' .
                '       and core_user=:core_user ' .
                '       and deleted=0 ' .
                '       and uniacid = :uniacid ' .
                '       and isdefault=1 ',
                array(
                    ':uniacid'   => $_W['uniacid'],
                    ':openid'    => $_W['openid'],
                    ':core_user' => $_W['core_user'],
                )
            );

            if (!$invoice_default) {
                $data['isdefault'] = 1;
            }

            pdo_insert('superdesk_shop_member_invoice', $data); // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 已处理

            $id = pdo_insertid();

        } else {

            pdo_update(
                'superdesk_shop_member_invoice', // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 已处理
                $data,
                array(
                    'id'        => $id,
                    'uniacid'   => $_W['uniacid'],
                    'openid'    => $_W['openid'],
                    'core_user' => $_W['core_user'],
                )
            );
        }

        show_json(1, array('invoiceid' => $id));
    }
}
