<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=sll_street */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_street.class.php');
$sll_street = new sll_streetModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $sll_street->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'street_id' => $_GPC['street_id'],// 
    'street_name' => $_GPC['street_name'],// 
    'pCode' => $_GPC['pCode'],// 
    'street_code' => $_GPC['street_code'],// 
    'street_state' => $_GPC['street_state'],// 0:是1:否

        );
        $sll_street->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('sll_street', array('op' => 'list')), 'success');


    }
    include $this->template('sll_street_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $sll_street->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('sll_street', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $sll_street->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('sll_street_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $sll_street->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $sll_street->delete($id);

    message('删除成功！', referer(), 'success');
}

