<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=wx_users */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/wx_users.class.php');
$wx_users = new wx_usersModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $wx_users->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'openid' => $_GPC['openid'],// 
    'agentid' => $_GPC['agentid'],// 
    'inviter' => $_GPC['inviter'],// 
    'gid' => $_GPC['gid'],// 
    'username' => $_GPC['username'],// 
    'mp' => $_GPC['mp'],// 
    'smscount' => $_GPC['smscount'],// 
    'password' => $_GPC['password'],// 
    'email' => $_GPC['email'],// 
    'lasttime' => $_GPC['lasttime'],// 
    'status' => $_GPC['status'],// 0:拉黑1:正常
    'createip' => $_GPC['createip'],// 
    'lastip' => $_GPC['lastip'],// 
    'diynum' => $_GPC['diynum'],// 
    'activitynum' => $_GPC['activitynum'],// 
    'card_num' => $_GPC['card_num'],// 
    'card_create_status' => $_GPC['card_create_status'],// 
    'money' => $_GPC['money'],// 
    'moneybalance' => $_GPC['moneybalance'],// 
    'spend' => $_GPC['spend'],// 
    'viptime' => $_GPC['viptime'],// 
    'connectnum' => $_GPC['connectnum'],// 
    'lastloginmonth' => $_GPC['lastloginmonth'],// 
    'attachmentsize' => $_GPC['attachmentsize'],// 
    'wechat_card_num' => $_GPC['wechat_card_num'],// 
    'serviceUserNum' => $_GPC['serviceUserNum'],// 
    'invitecode' => $_GPC['invitecode'],// 
    'remark' => $_GPC['remark'],// 
    'business' => $_GPC['business'],// 
    'usertplid' => $_GPC['usertplid'],// 
    'sysuser' => $_GPC['sysuser'],// 
    'wxuserid' => $_GPC['wxuserid'],// 
    'parent_id' => $_GPC['parent_id'],// 
    'province' => $_GPC['province'],// 
    'city' => $_GPC['city'],// 

        );
        $wx_users->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('wx_users', array('op' => 'list')), 'success');


    }
    include $this->template('wx_users_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $wx_users->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('wx_users', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $wx_users->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('wx_users_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $wx_users->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $wx_users->delete($id);

    message('删除成功！', referer(), 'success');
}

