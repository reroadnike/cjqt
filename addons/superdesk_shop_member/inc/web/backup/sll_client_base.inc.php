<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=sll_client_base */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_client_base.class.php');
$sll_client_base = new sll_client_baseModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $sll_client_base->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'client_id' => $_GPC['client_id'],// 
    'client_name' => $_GPC['client_name'],// 店铺名称
    'address' => $_GPC['address'],// 
    'x' => $_GPC['x'],// 
    'y' => $_GPC['y'],// 
    'pic' => $_GPC['pic'],// 图片
    'begin_time' => $_GPC['begin_time'],// 上班时间
    'end_time' => $_GPC['end_time'],// 下班时间
    'qrcode' => $_GPC['qrcode'],// 二维码
    'user_id' => $_GPC['user_id'],// 水店ID

        );
        $sll_client_base->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('sll_client_base', array('op' => 'list')), 'success');


    }
    include $this->template('sll_client_base_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $sll_client_base->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('sll_client_base', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $sll_client_base->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('sll_client_base_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $sll_client_base->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $sll_client_base->delete($id);

    message('删除成功！', referer(), 'success');
}

