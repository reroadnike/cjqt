<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=ms_coupon_label */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/ms_coupon_label.class.php');
$ms_coupon_label = new ms_coupon_labelModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $ms_coupon_label->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'coupon_label_id' => $_GPC['coupon_label_id'],// 
    'coupon_label_name' => $_GPC['coupon_label_name'],// 
    'coupon_label_number' => $_GPC['coupon_label_number'],// 
    'e_id' => $_GPC['e_id'],// 

        );
        $ms_coupon_label->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('ms_coupon_label', array('op' => 'list')), 'success');


    }
    include $this->template('ms_coupon_label_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $ms_coupon_label->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('ms_coupon_label', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $ms_coupon_label->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('ms_coupon_label_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $ms_coupon_label->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $ms_coupon_label->delete($id);

    message('删除成功！', referer(), 'success');
}

