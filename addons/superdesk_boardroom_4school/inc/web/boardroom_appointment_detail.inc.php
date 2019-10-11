<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/13/17
 * Time: 8:34 PM
 */
global $_GPC, $_W;

$out_trade_no = $_GPC['out_trade_no'];

$column = array(
    'out_trade_no' => $out_trade_no,
    'uniacid' => $_W['uniacid']
);

/******************************************************* 预约服务 *********************************************************/

include_once(MODULE_ROOT . '/model/boardroom_appointment.class.php');
$boardroom_appointment = new boardroom_appointmentModel();

include_once(MODULE_ROOT . '/model/boardroom.class.php');
$boardroom = new boardroomModel();


$_boardroom_appointment = $boardroom_appointment->getOneByColumn($column);
$_boardroom_appointment['boardroom'] = $boardroom->getOne($_boardroom_appointment['boardroom_id']);


$json_str = "{\"items\":[".iunserializer($_boardroom_appointment['boardroom']['equipment'])."]}";
$json = json_decode(htmlspecialchars_decode($json_str), true);
$_boardroom_appointment['boardroom']['equipment'] = $json['items'];
$_boardroom_appointment['boardroom']['carousel'] = iunserializer($_boardroom_appointment['boardroom']['carousel']);
$_boardroom_appointment['boardroom']['thumb'] = tomedia($_boardroom_appointment['boardroom']['thumb']);

/******************************************************* 预约服务 *********************************************************/

/******************************************************* 附加服务 *********************************************************/
include_once(MODULE_ROOT . '/model/boardroom_s_goods.class.php');

$boardroom_s_goods = new boardroom_s_goodsModel();

$goods = pdo_fetchall(
    " SELECT `goodsid`, `total`, `optionid` "
    . " FROM " . tablename('superdesk_boardroom_4school_s_order_goods')
    . " WHERE `out_trade_no` = :out_trade_no AND `uniacid` = :uniacid",
    $column
);

if($goods){
    foreach ($goods as &$item){
        $item['g'] = $boardroom_s_goods->getOne($item['goodsid']);
    }
}
/******************************************************* 附加服务 *********************************************************/



if(!empty($_boardroom_appointment['openid'])){
    $_W['openid'] = $_boardroom_appointment['openid'];
    $superdesk_user_info = $this->superdesk_core_user_mobile();
}








$__order__ = pdo_fetch(
    " SELECT * ".
    " FROM " . tablename('superdesk_boardroom_4school_s_order') .
    " WHERE out_trade_no = :out_trade_no AND uniacid = :uniacid",
    array(':out_trade_no' => $out_trade_no, ':uniacid' => $_W['uniacid']));

//echo " SELECT * ".
//    " FROM " . tablename('superdesk_boardroom_4school_s_order') .
//    " WHERE out_trade_no = :out_trade_no AND uniacid = :uniacid";
//
//echo "<br/>";
//var_dump(array(':out_trade_no' => $out_trade_no, ':uniacid' => $_W['uniacid']));
//echo "<br/>";
//var_dump($__order__);

//SELECT * FROM `ims_superdesk_boardroom_4school_s_order` WHERE out_trade_no = '08265838' AND uniacid = 16
//exit(0);


//if (empty($__order__)) {
//    message("抱歉，订单不存在!", referer(), "error");
//}

//var_dump($__order__);






if (checksubmit('confirmsend')) {
    if (!empty($_GPC['isexpress']) && empty($_GPC['expresssn'])) {
        message('请输入快递单号！');
    }
    $item = pdo_fetch("SELECT transaction_id FROM " . tablename('superdesk_boardroom_4school_s_order') . " WHERE id = :id", array(':id' => $id));
    if (!empty($item['transaction_id'])) {
        $this->changeWechatSend($id, 1);
    }
    pdo_update(
        'superdesk_boardroom_4school_s_order',
        array(
            'status' => 2,
            'remark' => $_GPC['remark'],
            'express' => $_GPC['express'],
            'expresscom' => $_GPC['expresscom'],
            'expresssn' => $_GPC['expresssn'],
        ),
        array('id' => $id)
    );
    message('发货操作成功！', referer(), 'success');
}

if (checksubmit('cancelsend')) {
    $item = pdo_fetch("SELECT transaction_id FROM " . tablename('superdesk_boardroom_4school_s_order') . " WHERE id = :id AND uniacid = :uniacid", array(':id' => $id, ':uniacid' => $_W['uniacid']));
    if (!empty($item['transaction_id'])) {
        $this->changeWechatSend($id, 0, $_GPC['cancelreson']);
    }
    pdo_update(
        'superdesk_boardroom_4school_s_order',
        array(
            'status' => 1,
            'remark' => $_GPC['remark'],
        ),
        array('id' => $id)
    );
    message('取消发货操作成功！', referer(), 'success');
}

if (checksubmit('finish')) {
    pdo_update('superdesk_boardroom_4school_s_order', array('status' => 3, 'remark' => $_GPC['remark']), array('id' => $id, 'uniacid' => $_W['uniacid']));
    message('订单操作成功！', referer(), 'success');
}

if (checksubmit('cancel')) {
    pdo_update('superdesk_boardroom_4school_s_order', array('status' => 1, 'remark' => $_GPC['remark']), array('id' => $id, 'uniacid' => $_W['uniacid']));
    message('取消完成订单操作成功！', referer(), 'success');
}

if (checksubmit('cancelpay')) {
    pdo_update('superdesk_boardroom_4school_s_order', array('status' => 0, 'remark' => $_GPC['remark']), array('id' => $id, 'uniacid' => $_W['uniacid']));
    //设置库存
    $this->setOrderStock($id, false);
    //减少积分
    $this->setOrderCredit($id, false);

    message('取消订单付款操作成功！', referer(), 'success');
}

if (checksubmit('confrimpay')) {
    pdo_update('superdesk_boardroom_4school_s_order', array('status' => 1, 'paytype' => 2, 'remark' => $_GPC['remark']), array('id' => $id, 'uniacid' => $_W['uniacid']));
    //设置库存
    $this->setOrderStock($id);
    //增加积分
    $this->setOrderCredit($id);
    message('确认订单付款操作成功！', referer(), 'success');
}

if (checksubmit('close')) {
    $item = pdo_fetch("SELECT transaction_id FROM " . tablename('superdesk_boardroom_4school_s_order') . " WHERE id = :id AND uniacid = :uniacid", array(':id' => $id, ':uniacid' => $_W['uniacid']));
    if (!empty($item['transaction_id'])) {
        $this->changeWechatSend($id, 0, $_GPC['reson']);
    }
    pdo_update('superdesk_boardroom_4school_s_order', array('status' => -1, 'remark' => $_GPC['remark']), array('id' => $id, 'uniacid' => $_W['uniacid']));
    message('订单关闭操作成功！', referer(), 'success');
}

if (checksubmit('open')) {
    pdo_update('superdesk_boardroom_4school_s_order', array('status' => 0, 'remark' => $_GPC['remark']), array('id' => $id, 'uniacid' => $_W['uniacid']));
    message('开启订单操作成功！', referer(), 'success');
}

// 订单取消
if (checksubmit('cancelorder')) {
    if ($item['status'] == 1) {
        load()->model('mc');
        $memberId = mc_openid2uid($item['from_user']);
        mc_credit_update($memberId, 'credit2', $item['price'], array($_W['uid'], '微商城取消订单退款说明'));
    }
    pdo_update('superdesk_boardroom_4school_s_order', array('status' => '-1'), array('id' => $item['id']));
    message('订单取消操作成功！', referer(), 'success');
}



if($__order__){

    $dispatch = pdo_fetch("SELECT * FROM " . tablename('superdesk_boardroom_4school_s_dispatch') . " WHERE id = :id", array(':id' => $__order__['dispatch']));
    if (!empty($dispatch) && !empty($dispatch['express'])) {
        $express = pdo_fetch("select * from " . tablename('superdesk_boardroom_4school_s_express') . " WHERE id=:id limit 1", array(":id" => $dispatch['express']));
    }

    // 收货地址信息
    $__order__['user'] = explode('|', $__order__['address']);

    $goods = pdo_fetchall(
        " SELECT g.*, o.total,g.type,o.optionname,o.optionid,o.price as orderprice ".
        " FROM " . tablename('superdesk_boardroom_4school_s_order_goods') . " o ".
        " left join " . tablename('superdesk_boardroom_4school_s_goods') . " g on o.goodsid=g.id " .
//    " WHERE o.orderid='{$id}'");
        " WHERE o.out_trade_no='{$out_trade_no}'");

    $__order__['goods'] = $goods;
}




include $this->template('boardroom_appointment_detail');