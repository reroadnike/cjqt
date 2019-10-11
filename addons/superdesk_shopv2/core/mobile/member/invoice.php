<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/member/member_invoice.class.php');

class Invoice_SuperdeskShopV2Page extends MobileLoginPage
{

    private $_member_invoiceModel;

    public function __construct()
    {
        parent::__construct();

        $this->_member_invoiceModel = new member_invoiceModel();
    }

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
            ' and deleted=0 ' .
            ' and openid=:openid ' .
            ' and core_user=:core_user ' .
            ' and uniacid = :uniacid  ';

        $params = array(
            ':uniacid'   => $_W['uniacid'],
            ':openid'    => $_W['openid'],
            ':core_user' => $_W['core_user']
        );

        $sql   = 'SELECT ' .
            '       COUNT(*) ' .
            ' FROM ' . tablename('superdesk_shop_member_invoice') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 已处理
            ' where 1 ' . $condition;
        $total = pdo_fetchcolumn($sql, $params);


        $sql  =
            ' SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_member_invoice') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 已处理
            ' where 1 ' .
            $condition .
            ' ORDER BY `id` DESC ' .
            ' LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize;
        $list = pdo_fetchall($sql, $params);

        include $this->template();
    }

    public function post()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        $invoice = $this->_member_invoiceModel->getOneByIdVerStrict($id, $_W['uniacid'], $_W['openid'], $_W['core_user']);

        if (!$invoice) {
            $invoice = array(
                'invoiceType'          => 1, // 增值税普票
                'selectedInvoiceTitle' => 4, // 个人
                'invoiceContent'       => 1  // 明细
            );
        }

        include $this->template();
    }

    public function setdefault()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        $data = pdo_fetch(
            ' select id ' .
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

        $this->_member_invoiceModel->updateByColumn(
            array(
                'isdefault' => 0
            ),
            array(
                'uniacid'   => $_W['uniacid'],
                'openid'    => $_W['openid'],
                'core_user' => $_W['core_user'],
            )
        );

        $this->_member_invoiceModel->updateByColumn(
            array(
                'isdefault' => 1
            ),
            array(
                'id'        => $id,
                'uniacid'   => $_W['uniacid'],
                'openid'    => $_W['openid'],
                'core_user' => $_W['core_user'],
            )
        );

        show_json(1);
    }


    /**
     * 新建与编辑发票
     */
    public function submit()
    {
        global $_W;
        global $_GPC;

        $id   = intval($_GPC['id']);
        $data = $_GPC['invoicedata'];

//        companyName
//        invoiceContent
//        invoiceType
//        selectedInvoiceTitle
//        taxpayersIDcode

        $data['uniacid']   = $_W['uniacid'];
        $data['openid']    = $_W['openid'];
        $data['core_user'] = $_W['core_user'];

        if (empty($id)) {

            $invoice_count = $this->_member_invoiceModel->count($data['uniacid'], $data['openid'], $data['core_user']);

            if ($invoice_count <= 0) {
                $data['isdefault'] = 1;
            }

            $invoice_default = $this->_member_invoiceModel->getByDefault($data['uniacid'], $data['openid'], $data['core_user']);

            if (!$invoice_default) {
                $data['isdefault'] = 1;
            }

            pdo_insert('superdesk_shop_member_invoice', $data);// TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 已处理

            $id = pdo_insertid();

        } else {

            $this->_member_invoiceModel->updateByColumn(
                $data,
                array(
                    'id'        => $id,
                    'uniacid'   => $_W['uniacid'],
                    'openid'    => $_W['openid'],
                    'core_user' => $_W['core_user']
                )
            );
        }

        show_json(1, array('invoiceid' => $id));
    }

    public function delete()
    {
        global $_W;
        global $_GPC;

        $id   = intval($_GPC['id']);
        $data = pdo_fetch(
            ' select id,isdefault ' .
            ' from ' . tablename('superdesk_shop_member_invoice') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 已处理
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and deleted=0 ' .
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
            'superdesk_shop_member_invoice', // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 不处理
            array(
                'deleted' => 1
            ),
            array(
                'id' => $id
            )
        );

        if ($data['isdefault'] == 1) {

            pdo_update(
                'superdesk_shop_member_invoice', // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 已处理
                array(
                    'isdefault' => 0
                ),
                array(
                    'id'        => $id,
                    'uniacid'   => $_W['uniacid'],
                    'openid'    => $_W['openid'],
                    'core_user' => $_W['core_user'],

                )
            );

            $data2 = pdo_fetch(
                'select id ' .
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

            if (!empty($data2)) {
                pdo_update(
                    'superdesk_shop_member_invoice', // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 不处理
                    array(
                        'isdefault' => 1
                    ),
                    array(
                        'id'         => $data2['id'],
                        'uniacid'    => $_W['uniacid'],
                        'openid'     => $_W['openid'],
                        ':core_user' => $_W['core_user'],

                    )
                );
                show_json(1, array('defaultid' => $data2['id']));
            }
        }

        show_json(1);
    }

    public function selector()
    {
        global $_W;
        global $_GPC;

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

        $condition =
            ' and uniacid = :uniacid ' .
            ' and openid=:openid ' .
            ' and core_user=:core_user ' .
            ' and deleted=0 ';

        $params = array(
            ':uniacid'   => $_W['uniacid'],
            ':openid'    => $_W['openid'],
            ':core_user' => $_W['core_user']
        );

        $sql =
            ' SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_member_invoice') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 已处理
            ' where 1 ' .
            $condition .
            ' ORDER BY isdefault desc, id DESC ';

        $list = pdo_fetchall($sql, $params);

        include $this->template();

        exit();
    }

}