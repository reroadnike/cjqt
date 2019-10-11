<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=wx_wechat_group_list */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/wx_wechat_group_list.class.php');
$wx_wechat_group_list = new wx_wechat_group_listModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $wx_wechat_group_list->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'g_id' => $_GPC['g_id'],// 
    'nickname' => $_GPC['nickname'],// 
    'sex' => $_GPC['sex'],// 
    'province' => $_GPC['province'],// 
    'city' => $_GPC['city'],// 
    'headimgurl' => $_GPC['headimgurl'],// 
    'subscribe_time' => $_GPC['subscribe_time'],// 
    'token' => $_GPC['token'],// 
    'openid' => $_GPC['openid'],// 
    'status' => $_GPC['status'],// 

        );
        $wx_wechat_group_list->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('wx_wechat_group_list', array('op' => 'list')), 'success');


    }
    include $this->template('wx_wechat_group_list_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $wx_wechat_group_list->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('wx_wechat_group_list', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $wx_wechat_group_list->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('wx_wechat_group_list_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $wx_wechat_group_list->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $wx_wechat_group_list->delete($id);

    message('删除成功！', referer(), 'success');
}

