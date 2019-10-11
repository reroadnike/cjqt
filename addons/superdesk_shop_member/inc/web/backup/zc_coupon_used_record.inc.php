<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=zc_coupon_used_record */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/zc_coupon_used_record.class.php');
$zc_coupon_used_record = new zc_coupon_used_recordModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $zc_coupon_used_record->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'r_id' => $_GPC['r_id'],// 
    'r_coupon_id' => $_GPC['r_coupon_id'],// 优惠券id
    'r_coupon_type' => $_GPC['r_coupon_type'],// 优惠券类型 0：抵扣券  1：满减券
    'r_coupon_price' => $_GPC['r_coupon_price'],// 优惠金额
    'r_goods_id' => $_GPC['r_goods_id'],// 商品id
    'r_user_id' => $_GPC['r_user_id'],// 供应商id
    'r_orderid' => $_GPC['r_orderid'],// 订单号
    'r_ctime' => $_GPC['r_ctime'],// 记录时间
    'r_type' => $_GPC['r_type'],// 记录类型 1：使用   2：退还
    'r_m_id' => $_GPC['r_m_id'],// 会员id

        );
        $zc_coupon_used_record->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('zc_coupon_used_record', array('op' => 'list')), 'success');


    }
    include $this->template('zc_coupon_used_record_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $zc_coupon_used_record->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('zc_coupon_used_record', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $zc_coupon_used_record->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('zc_coupon_used_record_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $zc_coupon_used_record->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $zc_coupon_used_record->delete($id);

    message('删除成功！', referer(), 'success');
}

