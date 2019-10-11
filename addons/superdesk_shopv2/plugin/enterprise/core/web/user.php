<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/__plugin__/enterprise/enterprise_account.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/__plugin__/enterprise/enterprise_user.class.php');

class User_SuperdeskShopV2Page extends PluginWebPage
{

    private $_enterprise_accountModel;
    private $_enterprise_userModel;

    public function __construct()
    {

        global $_W;
        global $_GPC;
        parent::__construct();

        $this->_enterprise_accountModel = new enterprise_accountModel();
        $this->_enterprise_userModel = new enterprise_userModel();
    }

    public function main()
    {
        global $_W;
        global $_GPC;

        $groups    = $this->model->getGroups();

        $data = $this->_enterprise_userModel->queryAll();

        $list = $data['data'];
        $total = $data['total'];
        $pager = pagination($total, $data['page'], $data['page_size']);

        load()->func('tpl');

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

        if (empty($id)) {
            $max_flag = $this->model->checkMaxEnterpriseUser(1);
            if ($max_flag == 1) {
                $this->message('已经达到最大企业数量,不能再添加企业', webUrl('enterprise/user'), 'error');
            }
        }

        $item = $this->_enterprise_userModel->getOneByColumn(array('id'=>$id,'uniacid'=>$_W['uniacid']));

        if (!empty($item['openid'])) {
            $member = m('member')->getMember($item['openid']);
        }

        if (!empty($item['payopenid'])) {
            $user = m('member')->getMember($item['payopenid']);
        }

        if (empty($item) || empty($item['accounttime'])) {
            $accounttime = strtotime('+365 day');
        } else {
            $accounttime = $item['accounttime'];
        }

        if (!empty($item['accountid'])) {
            $account = $this->_enterprise_accountModel->getOneByColumn(array('id'=>$item['accountid'],'uniacid'=>$_W['uniacid']));
        }

        $diyform_flag   = 0;
        $diyform_plugin = p('diyform');

        $f_data = array();
        if ($diyform_plugin && !empty($_W['shopset']['enterprise']['apply_diyform'])) {
            if (!empty($item['diyformdata'])) {
                $diyform_flag = 1;
                $fields       = iunserializer($item['diyformfields']);
                $f_data       = iunserializer($item['diyformdata']);
            } else {
                $diyform_id = $_W['shopset']['enterprise']['apply_diyformid'];
                if (!empty($diyform_id)) {
                    $formInfo = $diyform_plugin->getDiyformInfo($diyform_id);
                    if (!empty($formInfo)) {
                        $diyform_flag = 1;
                        $fields       = $formInfo['fields'];
                    }
                }
            }
        }

        if ($_W['ispost']) {

            $fdata = array();

            if ($diyform_flag) {
                $fdata = p('diyform')->getPostDatas($fields);
                if (is_error($fdata)) {
                    show_json(0, $fdata['message']);
                }
            }

            $status    = intval($_GPC['status']);
            $username  = trim($_GPC['username']);
            $checkUser = false;

            if (0 < $status) {
                $checkUser = true;
            }

            if (empty($_GPC['groupid'])) {
                show_json(0, '请选择企业组!');
            }

            if ($checkUser) {

                if (empty($username)) {
                    show_json(0, '请填写账户名!');
                }

                if (empty($account) && empty($_GPC['pwd'])) {
                    show_json(0, '请填写账户密码!');
                }

                $where  = ' username=:username';
                $params = array(':username' => $username);
                if (!empty($account)) {
                    $where .= ' and id<>:id';
                    $params[':id'] = $account['id'];
                }

                $usercount = $this->_enterprise_accountModel->getCounts($where,$params);

                if (0 < $usercount) {
                    show_json(0, '账户名 ' . $username . ' 已经存在!');
                }

                if (!empty($account)) {
                    if (empty($account['pwd']) && empty($_GPC['pwd'])) {
                        show_json(0, '请填写账户密码!');
                    }
                }
            }

            $salt = '';
            $pwd  = '';

            if (empty($account)
                || empty($account['salt'])
                || !empty($_GPC['pwd'])
            ) {
                $salt = random(8);
                while (1) {
                    $saltcount = $this->_enterprise_accountModel->getCounts(
                        ' salt=:salt ',
                        array(
                            ':salt' => $salt
                        )
                    );

                    if ($saltcount <= 0) {
                        break;
                    }

                    $salt = random(8);
                }

                $pwd = md5(trim($_GPC['pwd']) . $salt);

            } else {
                $salt = $account['salt'];
                $pwd  = $account['pwd'];
            }

            $data = array(
                'uniacid'      => $_W['uniacid'],
                'enterprise_name'    => trim($_GPC['enterprise_name']),
                'realname'     => trim($_GPC['realname']),
                'mobile'       => trim($_GPC['mobile']),
                'address'      => trim($_GPC['address']),
                'tel'          => trim($_GPC['tel']),
                'lng'          => $_GPC['map']['lng'],
                'lat'          => $_GPC['map']['lat'],
                'accounttime'  => strtotime($_GPC['accounttime']),
                'accounttotal' => intval($_GPC['accounttotal']),
                'groupid'      => intval($_GPC['groupid']),
                'remark'       => trim($_GPC['remark']),
                'status'       => $status,
                'desc'         => trim($_GPC['desc1']),
                'logo'         => save_media($_GPC['logo'])
            );

            if ($diyform_flag) {
                $data['diyformdata']   = iserializer($fdata);
                $data['diyformfields'] = iserializer($fields);
            }

            if (empty($item['jointime']) && ($status == 1)) {
                $data['jointime'] = time();
            }

            $account = array(
                'uniacid'   => $_W['uniacid'],
                'enterprise_id'   => $id,
                'username'  => $username,
                'pwd'       => $pwd,
                'salt'      => $salt,
                'status'    => 1,
                'perms'     => serialize(array()),
                'isfounder' => 1
            );

            if (empty($item)) {
                $item['applytime'] = time();

                $id = $this->_enterprise_userModel->insert($data);

                $account['enterprise_id'] = $id;
                $accountid = $this->_enterprise_accountModel->insert($account);

                $this->_enterprise_userModel->update(
                    array(
                        'accountid' => $accountid
                    ),
                    $id
                );

                plog(
                    'enterprise.user.add',
                    '添加企业 ID: ' . $data['id'] .
                    ' 企业名: ' . $data['enterprise_name'] .
                    '<br/>帐号: ' . $data['username'] .
                    '<br/>子帐号数: ' . $data['accounttotal'] .
                    '<br/>到期时间: ' . date('Y-m-d', $data['accounttime'])
                );
            } else {

                $this->_enterprise_userModel->update($data,$id);

                if (!empty($item['accountid'])) {

                    $this->_enterprise_accountModel->update($account,$item['accountid']);

                } else {

                    $accountid = $this->_enterprise_accountModel->insert($account);
                    $this->_enterprise_userModel->update(array('accountid' => $accountid),$id);
                }

                plog('enterprise.user.edit',
                    '编辑企业 ID: ' . $data['id'] .
                    ' 企业名: ' . $item['enterprise_name'] . ' -> ' . $data['enterprise_name'] .
                    '<br/>帐号: ' . $item['username'] . ' -> ' . $data['username'] .
                    '<br/>子帐号数: ' . $item['accounttotal'] . ' -> ' . $data['accounttotal'] .
                    '<br/>到期时间: ' . date('Y-m-d', $item['accounttime']) . ' -> ' . date('Y-m-d', $data['accounttime'])
                );

            }

            show_json(1, array('url' => webUrl('enterprise/user', array('status' => $item['status']))));
        }

        $groups   = $this->model->getGroups();
        $category = $this->model->getCategory();

        include $this->template();
    }

    public function status()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        if (empty($id)) {
            $id = ((is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0));
        }

        $where = ' id in( ' . $id . ' ) AND uniacid=:uniacid ';
        $params = array(':uniacid'=>$_W['uniacid']);
        $items = $this->_enterprise_userModel->getAllByWhere($where,$params);

        foreach ($items as $item) {
            $this->_enterprise_userModel->update(array('status' => intval($_GPC['status'])),$item['id']);
            plog('enterprise.group.edit',
                (('修改企业分组账户状态' .
                    '<br/>ID: ' . $item['id'] .
                    '<br/>企业名称: ' . $item['enterprise_name'] .
                    '<br/>状态: ' . $_GPC['status']) == 1 ? '启用' : '禁用')
            );
        }
        show_json(1);
    }

    public function delete()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        if (empty($id)) {
            $id = ((is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0));
        }

        $uniacid     = $_W['uniacid'];
        $change_data = array();

        $change_data['enterprise_id'] = 0;
        $change_data['status']  = 0;

        $where = ' id in( ' . $id . ' ) AND uniacid=:uniacid ';
        $params = array(':uniacid'=>$_W['uniacid']);
        $items = $this->_enterprise_userModel->getAllByWhere($where,$params);

        foreach ($items as $item) {
            $this->_enterprise_accountModel->deleteByColumn(array('enterprise_id' => $item['id'], 'uniacid' => $uniacid));
            $this->_enterprise_userModel->deleteByColumn(array('id' => $item['id'], 'uniacid' => $uniacid));
            plog('enterprise.user.delete',
                '删除`企业 ' .
                '<br/>企业:  ID: ' . $item['id'] .
                ' / 名称:   ' . $item['enterprise_name']
            );
        }
        show_json(1);
    }

    public function query()
    {
        global $_W;
        global $_GPC;
        $kwd = trim($_GPC['keyword']);

        $params = array();

        $params[':uniacid'] = $_W['uniacid'];
        $condition          = 'uniacid=:uniacid AND status=1';

        if (!empty($kwd)) {
            $condition .= ' AND `enterprise_name` LIKE :keyword';
            $params[':keyword'] = '%' . $kwd . '%';
        }

        $ds = $this->_enterprise_userModel->getAllByWhere($condition,$params);

        include $this->template();

        exit();
    }
}

?>