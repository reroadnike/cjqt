<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=sll_orders */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_orders.class.php');
$sll_orders = new sll_ordersModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $sll_orders->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'orders_id' => $_GPC['orders_id'],// 
    'fansid' => $_GPC['fansid'],// 
    'openid' => $_GPC['openid'],// 
    'tel' => $_GPC['tel'],// 
    'payconf' => $_GPC['payconf'],// 付款方式,0：表示微信，1：货到付款，2：来电支付，3：银行卡
    'ctime' => $_GPC['ctime'],// 下单时间
    'orderPrice' => $_GPC['orderPrice'],// 
    'isPay' => $_GPC['isPay'],// 是否支付 0:未支付  1：已支付 ,2:已退款
    'state' => $_GPC['state'],// 1：未配送，2：配送中，3：表示已配送 ,4:确认收货 0:取消订单
    'type' => $_GPC['type'],// 0 : 微信  1：后台
    'scheduleTime' => $_GPC['scheduleTime'],// 预定时间
    'handletime' => $_GPC['handletime'],// 配送时间
    'endTime' => $_GPC['endTime'],// 确认收货时间
    'linkname' => $_GPC['linkname'],// 联系人姓名
    'address' => $_GPC['address'],// 用户地址
    'trackingname' => $_GPC['trackingname'],// 配送人id
    'paytime' => $_GPC['paytime'],// 支付时间
    'orderid' => $_GPC['orderid'],// 订单ID
    'payNumber' => $_GPC['payNumber'],// 支付流水号
    'remark' => $_GPC['remark'],// 备注
    'kfRemark' => $_GPC['kfRemark'],// 客服备注
    'user_id' => $_GPC['user_id'],// 
    'wx_users_id' => $_GPC['wx_users_id'],// 市级id
    'isNew' => $_GPC['isNew'],// 1：新订单，未点击过。0：点击过
    'main_id' => $_GPC['main_id'],// 主订单的订单id（非自增长id）
    'returnMsg' => $_GPC['returnMsg'],// 退款返回信息
    'order_coupin_price' => $_GPC['order_coupin_price'],// 优惠券优惠金额
    'order_original_price' => $_GPC['order_original_price'],// 订单原始金额
    'order_integral_price' => $_GPC['order_integral_price'],// 积分优惠金额
    'jdorderid' => $_GPC['jdorderid'],// 
    'jdstate' => $_GPC['jdstate'],// 0：待扫描，1：已扫描,2:京东下单失败
    'cancelTime' => $_GPC['cancelTime'],// 取消时间
    'freight' => $_GPC['freight'],// 运费
    'freight_result' => $_GPC['freight_result'],// 调取京东运费接口返回数据
    'e_id' => $_GPC['e_id'],// 项目id

        );
        $sll_orders->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('sll_orders', array('op' => 'list')), 'success');


    }
    include $this->template('sll_orders_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $sll_orders->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('sll_orders', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $sll_orders->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('sll_orders_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $sll_orders->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $sll_orders->delete($id);

    message('删除成功！', referer(), 'success');
}

