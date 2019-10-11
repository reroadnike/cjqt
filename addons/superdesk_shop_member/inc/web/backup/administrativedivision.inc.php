<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08
 * Time: 17:08
 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=administrativedivision
 */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/administrativedivision.class.php');
$administrativedivision = new administrativedivisionModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $administrativedivision->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id     = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
            'code'        => $_GPC['code'],//
            'name'        => $_GPC['name'],//
            'parent_code' => $_GPC['parent_code'],//
            'state'       => $_GPC['state'],// 0:是1:否

        );
        $administrativedivision->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('administrativedivision', array('op' => 'list')), 'success');


    }
    include $this->template('administrativedivision_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where  = array('id' => $id);

            $administrativedivision->update($params, $where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('administrativedivision', array('op' => 'list')), 'success');
    }

    $page      = $_GPC['page'];
    $page_size = 20;

    $result    = $administrativedivision->queryAll(array(), $page, $page_size);
    $total     = $result['total'];
    $page      = $result['page'];
    $page_size = $result['page_size'];
    $list      = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('administrativedivision_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $administrativedivision->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $administrativedivision->delete($id);

    message('删除成功！', referer(), 'success');
}

