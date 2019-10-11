<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=wsd_team_invite */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/wsd_team_invite.class.php');
$wsd_team_invite = new wsd_team_inviteModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $wsd_team_invite->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'team_invite_id' => $_GPC['team_invite_id'],// 
    'team_invite_ctime' => $_GPC['team_invite_ctime'],// 
    'team_invite_etime' => $_GPC['team_invite_etime'],// 
    'team_invite_state' => $_GPC['team_invite_state'],// 
    'team_invite_openid' => $_GPC['team_invite_openid'],// 
    'team_invite_code' => $_GPC['team_invite_code'],// 
    'team_invite_tel' => $_GPC['team_invite_tel'],// 
    'team_invite_token' => $_GPC['team_invite_token'],// 
    'team_invite_relation' => $_GPC['team_invite_relation'],// 

        );
        $wsd_team_invite->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('wsd_team_invite', array('op' => 'list')), 'success');


    }
    include $this->template('wsd_team_invite_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $wsd_team_invite->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('wsd_team_invite', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $wsd_team_invite->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('wsd_team_invite_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $wsd_team_invite->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $wsd_team_invite->delete($id);

    message('删除成功！', referer(), 'success');
}

