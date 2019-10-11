<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

/* 企业内购-数据源 start */
include_once(IA_ROOT . '/addons/superdesk_core/model/organization.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/member/member_invoice.class.php');
/* 企业内购-数据源 end */

/* 福利商城-数据源 start */
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/__plugin__/enterprise/enterprise_user.class.php');

/* 福利商城-数据源 start */

class Invoice_SuperdeskShopV2Page extends WebPage
{
    private $member_invoiceModel;
    private $_organizationModel;
    private $_enterprise_userModel;

    public function __construct()
    {
        // mark welfare
        switch (SUPERDESK_SHOPV2_MODE_USER) {
            case 1:// 1 超级前台
                $this->_organizationModel = new organizationModel();
                $this->member_invoiceModel = new member_invoiceModel();
                break;
            case 2:// 2 福利商城
                $this->_enterprise_userModel = new enterprise_userModel();
                break;
        }
    }

    public function main()
    {
        global $_W;
        global $_GPC;

        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;

        $kwd      = trim($_GPC['keyword']);
        $params             = array();
        $where = array();

        if (!empty($kwd)) {
            $condition  = ' AND ( `core_user` LIKE :keyword or `companyName` LIKE :keyword )';
            $params[':keyword'] = '%' . $kwd . '%';
            $where = [
                'sql' => $condition,
                'params' => $params
            ];
        }

        $invoice = $this->member_invoiceModel->queryAll($where, $pindex, $psize);
        $list = $invoice['data'];
        $total = $invoice['total'];

        if($list){
            foreach ($list as $key => $lval) {
                if(isset($lval['core_user']) && $lval['core_user']) {
                    $condition_user = ' AND `core_user` = :core_user';
                    $params_user[':core_user'] = $lval['core_user'];
                    $user_list = pdo_fetch(
                        'SELECT core_user, realname, mobile ' .
                        ' FROM ' . tablename('superdesk_shop_member') .
                        ' WHERE 1 ' .
                        $condition_user .
                        ' order by createtime desc',
                        $params_user
                    );

                    $list[$key]['name'] = isset($user_list['realname']) ? $user_list['realname'] : '';
                }
            }
        }
        $pager          = pagination($total, $pindex, $psize);
        $opencommission = false;

        include $this->template();
    }

    public function add()
    {
        $this->post();
    }

    public function edit()
    {
        $this->post();
    }

    protected function post()
    {
        global $_W;
        global $_GPC;
        $id = intval($_GPC['id']);

        if ($_W['ispost']) {
            $core_user = intval($_GPC['core_user']);

            $condition_post = ' AND `core_user` = :core_user';
            $params_post[':core_user'] = $core_user;
            $res = pdo_fetch(
                'SELECT openid,core_user, realname, mobile ' .
                ' FROM ' . tablename('superdesk_shop_member') .
                ' WHERE 1 ' .
                $condition_post .
                ' order by createtime desc',
                $params_post
            );
            if(!$res){
                show_json(0, '该用户不存在！');
            }

            $data = [
                'uniacid' => $_W['uniacid'],
                'openid' => $res['openid'],
                'core_user' => $_GPC['core_user'],

                'invoiceState' => trim($_GPC['invoiceState']),
                'invoiceType' => trim($_GPC['invoiceType']),
                'selectedInvoiceTitle' => trim($_GPC['selectedInvoiceTitle']),
                'realname' => trim($_GPC['realname']),
                'companyName' => trim($_GPC['companyName']),
                'taxpayersIDcode' => trim($_GPC['taxpayersIDcode']),
                'invoiceName' =>trim($_GPC['invoiceName']),
                'invoicePhone' =>trim($_GPC['invoicePhone']),
                'invoiceAddress' =>trim($_GPC['invoiceAddress']),
                'invoiceBank' =>trim($_GPC['invoiceBank']),
                'invoiceAccount' =>trim($_GPC['invoiceAccount']),
                'isdefault' =>trim($_GPC['isdefault']),
                'updatetime' =>time(),

            ];

            if (!empty($id)) {
                $data['createtime'] = time();
                $this->member_invoiceModel->update($data, $id);
                plog('member.invoice.edit', '修改用户发票ID: ' . $id);
            }
            else {

                $id = $this->member_invoiceModel->insert($data);
                plog('member.invoice.add', '添加用户发票ID: ' . $id);
            }

            show_json(1, array());
        }

        $item = $this->member_invoiceModel->getOne($id);

        $condition_user = ' AND `core_user` = :core_user';
        $params_user[':core_user'] = $item['core_user'];
        $user_list = pdo_fetch(
            'SELECT core_user, realname, mobile ' .
            ' FROM ' . tablename('superdesk_shop_member') .
            ' WHERE 1 ' .
            $condition_user .
            ' order by createtime desc',
            $params_user
        );
        $item['name'] = isset($user_list['realname']) ? $user_list['realname'] : '';
        include $this->template();


    }



    /**
     * 删除发票
     */
    public function delete()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        $this->member_invoiceModel->delete($id);

        show_json(1, array('url' => referer()));
    }


    public function query()
    {
        global $_W;
        global $_GPC;

        $kwd      = trim($_GPC['keyword']);
        $wechatid = intval($_GPC['wechatid']);

        if (empty($wechatid)) {
            $wechatid = $_W['uniacid'];
        }

        $params             = array();
        $params[':uniacid'] = $wechatid;
        $condition          = ' and uniacid=:uniacid';

        if (!empty($kwd)) {
            $condition          .= ' AND ( `nickname` LIKE :keyword or `realname` LIKE :keyword or `mobile` LIKE :keyword )';
            $params[':keyword'] = '%' . $kwd . '%';
        }

        $ds = pdo_fetchall(
            'SELECT id,avatar,nickname,openid,realname,mobile ' .
            ' FROM ' . tablename('superdesk_shop_member') .  // TODO 标志 楼宇之窗 openid shop_member 不处理
            ' WHERE 1 ' .
            $condition .
            ' order by createtime desc',
            $params
        );

        include $this->template();
    }



    public function ajaxMember()
    {

        global $_W;
        global $_GPC;

        $enterprise_id = $_GPC['enterprise_id'];

        if ($enterprise_id) {

            $member = pdo_fetchall(
                ' select ' .
                '       id,realname,openid ' .
                ' from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid shop_member 不处理
                ' where ' .
                '       uniacid=:uniacid ' .
                '       and core_enterprise=:enterprise_id',
                array(
                    ':uniacid'       => $_W['uniacid'],
                    ':enterprise_id' => $enterprise_id,
                )
            );

            show_json(1, array('member' => $member));
        }
    }


}

