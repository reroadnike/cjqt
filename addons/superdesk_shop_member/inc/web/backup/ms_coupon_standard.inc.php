<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=ms_coupon_standard */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/ms_coupon_standard.class.php');
$ms_coupon_standard = new ms_coupon_standardModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $ms_coupon_standard->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'coupon_standard_id' => $_GPC['coupon_standard_id'],// 
    'coupon_standard_pkcode' => $_GPC['coupon_standard_pkcode'],// 规则编号
    'coupon_standard_name' => $_GPC['coupon_standard_name'],// 优惠券规则名称
    'coupon_standard_type' => $_GPC['coupon_standard_type'],// 规则类型，0：表示抵扣次数，1：抵扣金额
    'coupon_standard_usetime' => $_GPC['coupon_standard_usetime'],// 规则类型为0 时，这个值有效，抵扣次数值
    'coupon_standard_condition' => $_GPC['coupon_standard_condition'],// 若规则条件为1时，此字段为满多少的值
    'coupon_standard_mjprice' => $_GPC['coupon_standard_mjprice'],// 若规则条件为1时，此字段为减多少的金额
    'coupon_standard_expiretype' => $_GPC['coupon_standard_expiretype'],// 到期类型：0:表示每天到期，1：每月到期，2：每年到期，3：自定到期时间
    'coupon_standard_begintime' => $_GPC['coupon_standard_begintime'],// 若到期类型为3时，这个为券有效时间的开始时间
    'coupon_standard_endtime' => $_GPC['coupon_standard_endtime'],// 若到期类型为2或者3时，这个为券有效时间的结束时间
    'coupon_standard_ctime' => $_GPC['coupon_standard_ctime'],// 规则创建时间
    'coupon_standard_useproducttype' => $_GPC['coupon_standard_useproducttype'],// 适用商品规则，0：全场通用，1：指定供应商
    'coupon_standard_state' => $_GPC['coupon_standard_state'],// 状态，0：无效，1：有效
    'jsons' => $_GPC['jsons'],// 供应商的json格式

        );
        $ms_coupon_standard->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('ms_coupon_standard', array('op' => 'list')), 'success');


    }
    include $this->template('ms_coupon_standard_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $ms_coupon_standard->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('ms_coupon_standard', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $ms_coupon_standard->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('ms_coupon_standard_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $ms_coupon_standard->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $ms_coupon_standard->delete($id);

    message('删除成功！', referer(), 'success');
}

