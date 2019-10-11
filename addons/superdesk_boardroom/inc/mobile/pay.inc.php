<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/29/17
 * Time: 4:47 AM
 */

global $_W, $_GPC;

$this->checkAuth();

$orderid = intval($_GPC['orderid']);
$order = pdo_fetch("SELECT * FROM " . tablename('superdesk_boardroom_s_order') . " WHERE id = :id AND uniacid = :uniacid",
    array(
        ':id' => $orderid,
        ':uniacid' => $_W['uniacid']
    )
);

if ($order['status'] != '0') {
    message('抱歉，您的订单已经付款或是被关闭，请重新进入付款！', $this->createMobileUrl('myorder'), 'error');
}



if (checksubmit('codsubmit')) {


    $ordergoods = pdo_fetchall("SELECT goodsid, total,optionid FROM " . tablename('superdesk_boardroom_s_order_goods') . " WHERE orderid = '{$orderid}'", array(), 'goodsid');
    if (!empty($ordergoods)) {
        $goods = pdo_fetchall("SELECT id, title, thumb, marketprice, unit, total,credit FROM " . tablename('superdesk_boardroom_s_goods') . " WHERE id IN ('" . implode("','", array_keys($ordergoods)) . "')");
    }

    //邮件提醒
    if (!empty($this->module['config']['noticeemail'])) {
//				$address = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE id = :id", array(':id' => $order['addressid']));
        $address = explode('|', $order['address']);
        $body = "<h3>购买商品清单</h3> <br />";
        if (!empty($goods)) {
            foreach ($goods as $row) {
                //属性
                $option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("superdesk_boardroom_s_goods_option") . " where id=:id limit 1", array(":id" => $ordergoods[$row['id']]['optionid']));
                if ($option) {
                    $row['title'] = "[" . $option['title'] . "]" . $row['title'];
                }
                $body .= "名称：{$row['title']} ，数量：{$ordergoods[$row['id']]['total']} <br />";
            }
        }
        $paytype = $order['paytype']=='3'?'货到付款':'已付款';
        $body .= "<br />总金额：{$order['price']}元 （{$paytype}）<br />";
        $body .= "<h3>购买用户详情</h3> <br />";
        $body .= "真实姓名：$address[0] <br />";
        $body .= "地区：$address[3] - $address[4] - $address[5]<br />";
        $body .= "详细地址：$address[6] <br />";
        $body .= "手机：$address[1] <br />";
        load()->func('communication');
        ihttp_email($this->module['config']['noticeemail'], '微商城订单提醒', $body);
    }
    pdo_update('superdesk_boardroom_s_order', array('status' => '1', 'paytype' => '3'), array('id' => $orderid, 'uniacid' => $_W['uniacid']));
    message('订单提交成功，请您收到货时付款！', $this->createMobileUrl('myorder'), 'success');
}


if (checksubmit()) {


    if ($order['paytype'] == 1 && $_W['fans']['credit2'] < $order['price']) {
        message('抱歉，您帐户的余额不够支付该订单，请充值！', create_url('mobile/module/charge', array('name' => 'member', 'uniacid' => $_W['uniacid'])), 'error');
    }

    if ($order['price'] == '0') {
        $this->payResult(array('tid' => $orderid, 'from' => 'return', 'type' => 'credit2'));
        exit;
    }
    
}


// 商品编号
$sql = 'SELECT `goodsid` FROM ' . tablename('superdesk_boardroom_s_order_goods') . " WHERE `orderid` = :orderid";
$goodsId = pdo_fetchcolumn($sql, array(':orderid' => $orderid));

// 商品名称
$sql = 'SELECT `title` FROM ' . tablename('superdesk_boardroom_s_goods') . " WHERE `id` = :id";
$goodsTitle = pdo_fetchcolumn($sql, array(':id' => $goodsId));


$params['tid'] = $orderid;
$params['user'] = $_W['openid'];
$params['title'] = $goodsTitle;
$params['out_trade_no'] = $order['out_trade_no'];
$params['virtual'] = $order['goodstype'] == 2 ? true : false;


$we7_coupon_info = module_fetch('we7_coupon');


if (!empty($we7_coupon_info) && pdo_tableexists('mc_card')) {

    if (!function_exists('card_discount_fee')) {
        $params['fee'] = $order['price'];
    } else {
        load() -> model('card');
        $params['fee'] = card_discount_fee($order['price']);
    }

} else {

    $params['fee'] = $order['price'];

}
include $this->template('pay');