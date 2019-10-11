<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=wx_invitation_member */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/wx_invitation_member.class.php');
$wx_invitation_member = new wx_invitation_memberModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $wx_invitation_member->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'phone' => $_GPC['phone'],// 
    'name' => $_GPC['name'],// 
    'remarks' => $_GPC['remarks'],// 备注
    'token' => $_GPC['token'],// 
    'visitcode' => $_GPC['visitcode'],// 
    'job' => $_GPC['job'],// 职位
    'ctime' => $_GPC['ctime'],// 当前时间

        );
        $wx_invitation_member->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('wx_invitation_member', array('op' => 'list')), 'success');


    }
    include $this->template('wx_invitation_member_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $wx_invitation_member->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('wx_invitation_member', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $wx_invitation_member->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('wx_invitation_member_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $wx_invitation_member->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $wx_invitation_member->delete($id);

    message('删除成功！', referer(), 'success');
}

