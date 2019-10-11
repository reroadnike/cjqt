<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=ms_coupon_data */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/ms_coupon_data.class.php');
$ms_coupon_data = new ms_coupon_dataModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $ms_coupon_data->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'coupon_data_id' => $_GPC['coupon_data_id'],// 
    'coupon_standard_pkcode' => $_GPC['coupon_standard_pkcode'],// 对应优惠券规则PKCODE
    'coupon_data_name' => $_GPC['coupon_data_name'],// 优惠券名称
    'coupon_data_type' => $_GPC['coupon_data_type'],// 规则类型，0：表示抵扣次数，1：优惠券
    'coupon_data_pkcode' => $_GPC['coupon_data_pkcode'],// 优惠券对外的唯一id
    'coupon_data_usetime' => $_GPC['coupon_data_usetime'],// 规则类型为0 时，这个值有效，抵扣次数值
    'coupon_data_condition' => $_GPC['coupon_data_condition'],// 若规则条件为1时，此字段为满多少的值
    'coupon_data_mjprice' => $_GPC['coupon_data_mjprice'],// 
    'coupon_data_expiretype' => $_GPC['coupon_data_expiretype'],// 到期类型：0:表示每天到期，1：每月到期，2：每年到期，3：自定到期时间
    'coupon_data_begintime' => $_GPC['coupon_data_begintime'],// 若到期类型为3时，这个为券有效时间的开始时间
    'coupon_data_endtime' => $_GPC['coupon_data_endtime'],// 若到期类型为2或者3时，这个为券有效时间的结束时间
    'coupon_data_ctime' => $_GPC['coupon_data_ctime'],// 
    'coupon_data_useproducttype' => $_GPC['coupon_data_useproducttype'],// 适用商品规则，0：全场通用，1：指定供应商
    'coupon_data_state' => $_GPC['coupon_data_state'],// 0：表示未启用，1：表示可使用，2：已使用，3：已过期，4：已失效
    'coupon_m_id' => $_GPC['coupon_m_id'],// 会员ID
    'coupon_use_time' => $_GPC['coupon_use_time'],// 优惠券使用时间

        );
        $ms_coupon_data->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('ms_coupon_data', array('op' => 'list')), 'success');


    }
    include $this->template('ms_coupon_data_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $ms_coupon_data->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('ms_coupon_data', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $ms_coupon_data->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('ms_coupon_data_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $ms_coupon_data->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $ms_coupon_data->delete($id);

    message('删除成功！', referer(), 'success');
}

