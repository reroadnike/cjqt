<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=wx_invitation */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/wx_invitation.class.php');
$wx_invitation = new wx_invitationModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $wx_invitation->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'picurl' => $_GPC['picurl'],// 图片地址，用逗号隔开
    'title' => $_GPC['title'],// 统一标题，多个标题用逗号隔开
    'ctime' => $_GPC['ctime'],// 创建时间
    'visitcount' => $_GPC['visitcount'],// 浏览次数
    'visitcode' => $_GPC['visitcode'],// 访问ID
    'token' => $_GPC['token'],// 
    'sharepic' => $_GPC['sharepic'],// 分享图片
    'sharemusic' => $_GPC['sharemusic'],// 分享音乐
    'sharetitle' => $_GPC['sharetitle'],// 分享标题
    'sharecontext' => $_GPC['sharecontext'],// 分享内容

        );
        $wx_invitation->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('wx_invitation', array('op' => 'list')), 'success');


    }
    include $this->template('wx_invitation_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $wx_invitation->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('wx_invitation', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $wx_invitation->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('wx_invitation_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $wx_invitation->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $wx_invitation->delete($id);

    message('删除成功！', referer(), 'success');
}

