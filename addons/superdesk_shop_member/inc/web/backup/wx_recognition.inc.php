<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=wx_recognition */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/wx_recognition.class.php');
$wx_recognition = new wx_recognitionModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $wx_recognition->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'token' => $_GPC['token'],// 
    'title' => $_GPC['title'],// 
    'attention_num' => $_GPC['attention_num'],// 
    'keyword' => $_GPC['keyword'],// 
    'code_url' => $_GPC['code_url'],// 
    'scene_id' => $_GPC['scene_id'],// 
    'status' => $_GPC['status'],// 
    'groupid' => $_GPC['groupid'],// 
    'groupname' => $_GPC['groupname'],// 

        );
        $wx_recognition->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('wx_recognition', array('op' => 'list')), 'success');


    }
    include $this->template('wx_recognition_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $wx_recognition->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('wx_recognition', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $wx_recognition->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('wx_recognition_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $wx_recognition->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $wx_recognition->delete($id);

    message('删除成功！', referer(), 'success');
}

