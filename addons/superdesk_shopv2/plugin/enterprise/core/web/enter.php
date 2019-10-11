<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/__plugin__/enterprise/enterprise_enter.class.php');

class Enter_SuperdeskShopV2Page extends PluginWebPage
{

    private $_enterprise_enterModel;

    public function __construct()
    {

        global $_W;
        global $_GPC;
        parent::__construct();

        $this->_enterprise_enterModel = new enterprise_enterModel();
    }

    public function main()
    {
        global $_W;
        global $_GPC;

        $data = $this->_enterprise_enterModel->queryAll();
        $total = $data['total'];
        $list = $data['data'];

        $pager = pagination($total, $data['page'], $data['page_size']);
        include $this->template();
    }

    public function bind()
    {
        global $_W;
        global $_GPC;

        $params    = array(':uniacid' => $_W['uniacid']);
        $condition = ' and a.isdelete = 0';
        $sql =
            ' select a.*,m.id as mid, m.realname, m.mobile,m.credit2, m.nickname '.
            ' from ' . tablename('superdesk_shop_member_enter') . ' as a' .
            ' left join ' . tablename('superdesk_shop_member') . 'as m on a.enter_uid = m.id ' .
            ' where a.uniacid=:uniacid ' . $condition;
        $list  = pdo_fetchall($sql, $params);

        foreach ($list as $item) {
            $mid = 0;
            $params = [
                'uniacid' => $_W['uniacid'],
                'openid' => 'wap_user_17_'.$item['enter_id'],
                'realname' => '心意卡会员',
                'nickname' => $item['enter_id'],
                'mobile' => '11000000000'+$item['id'],
                'mobileverify' => 1,
                'core_enterprise' => 12
            ];

            //没有用户信息
            if(!isset($item['mid']) || !$item['mid']) {
                $params['credit2'] = $item['enter_balance'];
                $params['createtime'] = time();
                $ret = pdo_insert('superdesk_shop_member', $params);
                if (!empty($ret)) {
                    $mid = pdo_insertid();
                }
            } else {
                $mid = $item['mid'];
                $params['updatetime'] = time();
                $ret = pdo_update('superdesk_shop_member', $params, ['id'=>$mid]);
            }

            //绑定用户ID
            if($mid) {
                $this->_enterprise_enterModel->update(['enter_uid' => $mid], $item['id']);
            }
        }

        header('location: ' . webUrl('enterprise/enter'));
        exit();
    }

    /**
     *  重置配额
     *
     */
    protected function setbalance()
    {
        global $_W;
        global $_GPC;

        if ($_W['ispost']) {
            $balance_text = $_GPC['balance_text'];
            socket_log();
            foreach ($balance_text as $bitem) {

                $enter_id = $bitem['enter_id'];
                if($enter_id) {

                }
                $enter_balance = $bitem['enter_balance'];


                $enter_res = $this->_enterprise_enterModel->getOneByColumn(['enter_id' => $bitem['enter_id']]);
                if(!$enter_res) {
                    show_json(0, '该福利卡不存在！');
                }

                //福利卡更新
                $expiretime = strtotime($_GPC['expiretime']);
                $data = array(
                    'uniacid' => $_W['uniacid'],
                    'expiretime' => $expiretime,
                    'status' => 1,
                    'enter_balance' => intval($enter_balance),
                );
                $id = $this->_enterprise_enterModel->saveOrUpdateByColumn($data, ['enter_id' => $bitem['enter_id']]);

                //用户修改
                if(!$enter_res['enter_balance']) {
                    $params = [
                        'updatetime' => time(),
                        'credit2' => $_GPC['enter_balance']
                    ];
                    $ret = pdo_update('superdesk_shop_member', $params, ['id'=>$enter_res['enter_uid']]);
                    if(!$ret) {
                        show_json(0, '更新用户信息失败，请直接给用户充值！');
                    }
                }
            }

            show_json(1, array('url' => webUrl('enterprise/enter')));
        }

        $item = $this->_enterprise_enterModel->getOneByColumn(array('id' => $id, 'uniacid' => $_W['uniacid']));
        include $this->template();
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
            $enter_res = $this->_enterprise_enterModel->getOne($id);
            if(!$enter_res) {
                show_json(0, '该福利卡不存在！');
            }

            //福利卡更新
            $expiretime = strtotime($_GPC['expiretime']);
            $data = array(
                'uniacid' => $_W['uniacid'],
                'expiretime' => $expiretime,
                'status' => intval($_GPC['status']),
                'enter_balance' => intval($_GPC['enter_balance']),
            );
            $id = $this->_enterprise_enterModel->saveOrUpdate($data, $id);

            //用户修改
            if(!$enter_res['enter_balance']) {
                $params = [
                    'updatetime' => time(),
                    'credit2' => $_GPC['enter_balance']
                ];
                $ret = pdo_update('superdesk_shop_member', $params, ['id'=>$enter_res['enter_uid']]);
                if(!$ret) {
                    show_json(0, '更新用户信息失败，请直接给用户充值！');
                }
            }

            show_json(1, array('url' => webUrl('enterprise/enter')));
        }

        $item = $this->_enterprise_enterModel->getOneByColumn(array('id' => $id, 'uniacid' => $_W['uniacid']));
        include $this->template();
    }

    public function delete()
    {
        global $_W;
        global $_GPC;
        $id = intval($_GPC['id']);

        if (empty($id)) {
            $id = ((is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0));
        }

        $where = ' WHERE id in( ' . $id . ' ) AND uniacid=:uniacid ';
        $params = array(':uniacid' => $_W['uniacid']);
        $items = $this->_enterprise_enterModel->getAllByWhere($where,$params);

        foreach ($items as $item) {
            $params = ['isdelete' => 1];
            $this->_enterprise_enterModel->update($params, $item['id']);
            plog('enterprise.enter.delete', '删除福利卡ID: ' . $item['id'] . ' 卡号: ' . $item['enter_id'] . ' ');
        }

        show_json(1, array('url' => referer()));
    }

    public function status()
    {
        global $_W;
        global $_GPC;
        $id = intval($_GPC['id']);

        if (empty($id)) {
            $id = ((is_array($_GPC['ids']) ? implode(' ,', $_GPC['ids']) : 0));
        }

        $where = ' id in( ' . $id . ' ) AND uniacid=:uniacid ';
        $params = array(
            ':uniacid' => $_W['uniacid']
        );
        $items = $this->_enterprise_enterModel->getAllByWhere($where, $params);

        foreach ($items as $item) {
            $id = $item['id'];
            $this->_enterprise_enterModel->update(['status' => intval($_GPC['status'])], $id);
        }

        show_json(1, array('url' => referer()));
    }

}


?>