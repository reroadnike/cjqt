<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=ms_coupon_useproduct */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/ms_coupon_useproduct.class.php');
$ms_coupon_useproduct = new ms_coupon_useproductModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $ms_coupon_useproduct->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'coupon_useproduct_id' => $_GPC['coupon_useproduct_id'],// 
    'coupon_standard_pkcode' => $_GPC['coupon_standard_pkcode'],// 
    'classify_id' => $_GPC['classify_id'],// 产品系列id
    'coupon_useproduct_state' => $_GPC['coupon_useproduct_state'],// 0:表示无效，1：表示有效
    'classify_name' => $_GPC['classify_name'],// 系列名
    'coupon_useproduct_ctime' => $_GPC['coupon_useproduct_ctime'],// 创建时间
    'coupon_useproduct_supplierID' => $_GPC['coupon_useproduct_supplierID'],// 供应商ID
    'coupon_useproduct_supplierName' => $_GPC['coupon_useproduct_supplierName'],// 

        );
        $ms_coupon_useproduct->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('ms_coupon_useproduct', array('op' => 'list')), 'success');


    }
    include $this->template('ms_coupon_useproduct_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $ms_coupon_useproduct->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('ms_coupon_useproduct', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $ms_coupon_useproduct->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('ms_coupon_useproduct_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $ms_coupon_useproduct->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $ms_coupon_useproduct->delete($id);

    message('删除成功！', referer(), 'success');
}

