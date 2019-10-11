<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=ms_integral_sendresult */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/ms_integral_sendresult.class.php');
$ms_integral_sendresult = new ms_integral_sendresultModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $ms_integral_sendresult->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'integral_sendresult_id' => $_GPC['integral_sendresult_id'],// 
    'integral_sendresult_ctime' => $_GPC['integral_sendresult_ctime'],// 发送时间
    'e_id' => $_GPC['e_id'],// 企业ID
    'integral_sendresult_type' => $_GPC['integral_sendresult_type'],// 发送方式，0：自动，1：手动
    'integral_standard_pkcode' => $_GPC['integral_standard_pkcode'],// 优惠券唯一ID
    'integral_sendresult_state' => $_GPC['integral_sendresult_state'],// 1:完成，2：失败
    'integral_send_id' => $_GPC['integral_send_id'],// 发送id
    'm_id' => $_GPC['m_id'],// 用户id

        );
        $ms_integral_sendresult->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('ms_integral_sendresult', array('op' => 'list')), 'success');


    }
    include $this->template('ms_integral_sendresult_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $ms_integral_sendresult->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('ms_integral_sendresult', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $ms_integral_sendresult->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('ms_integral_sendresult_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $ms_integral_sendresult->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $ms_integral_sendresult->delete($id);

    message('删除成功！', referer(), 'success');
}

