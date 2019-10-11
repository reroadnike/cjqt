<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=wx_usersummary_report */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/wx_usersummary_report.class.php');
$wx_usersummary_report = new wx_usersummary_reportModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $wx_usersummary_report->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'subscribed' => $_GPC['subscribed'],// 关注人数
    'cancle' => $_GPC['cancle'],// 取关人数
    'newadd' => $_GPC['newadd'],// 新增人数
    'cumulate' => $_GPC['cumulate'],// 累积增长人数
    'days' => $_GPC['days'],// 
    'token' => $_GPC['token'],// 

        );
        $wx_usersummary_report->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('wx_usersummary_report', array('op' => 'list')), 'success');


    }
    include $this->template('wx_usersummary_report_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $wx_usersummary_report->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('wx_usersummary_report', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $wx_usersummary_report->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('wx_usersummary_report_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $wx_usersummary_report->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $wx_usersummary_report->delete($id);

    message('删除成功！', referer(), 'success');
}

