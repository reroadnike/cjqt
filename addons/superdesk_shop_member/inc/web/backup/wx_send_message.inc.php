<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=wx_send_message */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/wx_send_message.class.php');
$wx_send_message = new wx_send_messageModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $wx_send_message->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'msg_id' => $_GPC['msg_id'],// 
    'title' => $_GPC['title'],// 
    'token' => $_GPC['token'],// 
    'msgtype' => $_GPC['msgtype'],// 
    'text' => $_GPC['text'],// 
    'imgids' => $_GPC['imgids'],// 
    'mediasrc' => $_GPC['mediasrc'],// 
    'mediaid' => $_GPC['mediaid'],// 
    'reachcount' => $_GPC['reachcount'],// 
    'groupid' => $_GPC['groupid'],// 
    'openid' => $_GPC['openid'],// 
    'status' => $_GPC['status'],// 
    'send_type' => $_GPC['send_type'],// 

        );
        $wx_send_message->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('wx_send_message', array('op' => 'list')), 'success');


    }
    include $this->template('wx_send_message_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $wx_send_message->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('wx_send_message', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $wx_send_message->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('wx_send_message_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $wx_send_message->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $wx_send_message->delete($id);

    message('删除成功！', referer(), 'success');
}

