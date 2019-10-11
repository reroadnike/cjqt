<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=api_user */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/api_user.class.php');
$api_user = new api_userModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $api_user->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'api_user_id' => $_GPC['api_user_id'],// 
    'api_user_pkcode' => $_GPC['api_user_pkcode'],// 
    'api_username' => $_GPC['api_username'],// 
    'api_password' => $_GPC['api_password'],// 
    'api_roleid' => $_GPC['api_roleid'],// 
    'api_email' => $_GPC['api_email'],// 
    'api_linkman' => $_GPC['api_linkman'],// 
    'api_tel' => $_GPC['api_tel'],// 
    'api_token' => $_GPC['api_token'],// 
    'api_token_endtime' => $_GPC['api_token_endtime'],// 

        );
        $api_user->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('api_user', array('op' => 'list')), 'success');


    }
    include $this->template('api_user_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $api_user->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('api_user', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $api_user->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('api_user_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $api_user->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $api_user->delete($id);

    message('删除成功！', referer(), 'success');
}

