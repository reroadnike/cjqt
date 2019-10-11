<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=sll_call_record */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_call_record.class.php');
$sll_call_record = new sll_call_recordModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $sll_call_record->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'call_record_id' => $_GPC['call_record_id'],// 
    'call_record_mobile' => $_GPC['call_record_mobile'],// 
    'call_record_store_id' => $_GPC['call_record_store_id'],// 
    'call_record_ctime' => $_GPC['call_record_ctime'],// 

        );
        $sll_call_record->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('sll_call_record', array('op' => 'list')), 'success');


    }
    include $this->template('sll_call_record_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $sll_call_record->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('sll_call_record', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $sll_call_record->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('sll_call_record_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $sll_call_record->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $sll_call_record->delete($id);

    message('删除成功！', referer(), 'success');
}

