<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=wx_areply */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/wx_areply.class.php');
$wx_areply = new wx_areplyModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $wx_areply->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'keyword' => $_GPC['keyword'],// 关键字
    'content' => $_GPC['content'],// 回复内容
    'uid' => $_GPC['uid'],// 
    'uname' => $_GPC['uname'],// 
    'token' => $_GPC['token'],// 
    'home' => $_GPC['home'],// 
    'check_box' => $_GPC['check_box'],// 图文回复是否选中0 1

        );
        $wx_areply->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('wx_areply', array('op' => 'list')), 'success');


    }
    include $this->template('wx_areply_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $wx_areply->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('wx_areply', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $wx_areply->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('wx_areply_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $wx_areply->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $wx_areply->delete($id);

    message('删除成功！', referer(), 'success');
}

