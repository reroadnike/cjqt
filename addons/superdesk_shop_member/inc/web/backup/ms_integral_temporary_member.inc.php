<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=ms_integral_temporary_member */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/ms_integral_temporary_member.class.php');
$ms_integral_temporary_member = new ms_integral_temporary_memberModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $ms_integral_temporary_member->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'temporary_member_id' => $_GPC['temporary_member_id'],// 
    'phone' => $_GPC['phone'],// 
    'ctime' => $_GPC['ctime'],// 
    'pk_code' => $_GPC['pk_code'],// 
    'state' => $_GPC['state'],// 
    'mid' => $_GPC['mid'],// 会员id

        );
        $ms_integral_temporary_member->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('ms_integral_temporary_member', array('op' => 'list')), 'success');


    }
    include $this->template('ms_integral_temporary_member_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $ms_integral_temporary_member->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('ms_integral_temporary_member', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $ms_integral_temporary_member->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('ms_integral_temporary_member_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $ms_integral_temporary_member->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $ms_integral_temporary_member->delete($id);

    message('删除成功！', referer(), 'success');
}

