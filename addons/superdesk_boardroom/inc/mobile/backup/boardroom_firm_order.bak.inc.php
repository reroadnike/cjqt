<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/27/17
 * Time: 6:01 AM
 */

global $_GPC, $_W;

$title = "会议室详情";


// 传入参数,不能变
$id                 = $_GPC['id'];//
$select_time_bar    = $_GPC['select_time_bar'];



$people_num = 10;
$now = time();
$Ymd = date('Y-m-d', $now);

$out_trade_no = date('md') . random(4, 1);


/******************************************************* 预约会议室 入库与显示 *********************************************************/
//echo $id ;


//echo "{\"situation\":".$select_time_bar."}";

$situation = json_decode("{\"situation\":".htmlspecialchars_decode($select_time_bar)."}",true);

$check_start_index = 0;
$check_end_index = 0;

foreach ($situation['situation'] as $index => &$_situation){
//    {
//        ["index"]=> int(0)
//        ["key"]=> string(19) "2017-07-31 00:00:00"
//        ["timestamp"]=> int(1501432200)
//        ["is_use"]=> int(0)
//        ["lable"]=> string(11) "00:00-00:30"
//        ["checked"]=> int(0)
//    }

    if($_situation['checked'] == 0){
        unset($situation['situation'][$index]);
    }

    if($_situation['checked'] == 1 && $check_start_index == 0){
        $check_start_index = $index;
    }

    if($_situation['checked'] == 1 && $check_start_index != 0){
        $check_end_index = $index;
    }


}

//echo $check_start_index;
//echo $check_end_index;

$check_start_lable = $situation['situation'][$check_start_index]['lable'];
$check_end_lable = $situation['situation'][$check_end_index]['lable'];

$check_start_split = explode("-",$check_start_lable);
$check_end_split = explode("-",$check_end_lable);

//var_dump($situation['situation']);

$check_start_end = $check_start_split[0]."-".$check_end_split[1];


//var_dump($situation);
//exit(0);

include_once(MODULE_ROOT . '/model/boardroom.class.php');
$boardroom = new boardroomModel();

$_boardroom = $boardroom->getOne($id);

$num_hour = floatval(sizeof($situation['situation']))/2;

$total_price_boardroom_booking = 0;
$total_price_boardroom_booking = floatval($_boardroom['price']*$num_hour);


include_once(MODULE_ROOT . '/model/boardroom_appointment.class.php');
$boardroom_appointment = new boardroom_appointmentModel();

$params = array(

    "boardroom_id" => $id,
    "out_trade_no" => $out_trade_no,
    "quantity" => $num_hour,
    "price" => $_boardroom['price'],
    "total" => $total_price_boardroom_booking,
    "lable_ymd" => $Ymd,
    "lable_time" => $check_start_end,
    "situation" => $select_time_bar,
    "openid" => $_W['openid'],
    "client_name" => "",
    "client_telphone" => "",
    "state" => 1,//审核状态(1同意)
    "relate_id" => '',// 用于微信模板消息通知 openid
    "people_num" => $people_num,
    "starttime" => "",
    "endtime" => "",
    "uniacid" => $_W['uniacid'],

);

$boardroom_appointment->insert($params);

//  `id` int(10) NOT NULL,
//  `boardroom_id` int(10) NOT NULL COMMENT '会议室ID',
//
//  `out_trade_no` varchar(32) NOT NULL DEFAULT '' COMMENT 'out_trade_no',
//  `transaction_id` varchar(32) NOT NULL DEFAULT '' COMMENT '微信支付订单号',
//
//  `price` decimal(10,2) DEFAULT '0.00' COMMENT '成交价',
//  `total` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '成交数量',
//
//  `lable_ymd` varchar(64) NOT NULL DEFAULT '' COMMENT 'eg:2017-09-09',
//  `lable_time` varchar(25) NOT NULL DEFAULT '' COMMENT 'eg:00:30-12:00',
//
//  `situation` text NOT NULL DEFAULT '' COMMENT 'situation JSON',
//
//
//  `openid` varchar(64) NOT NULL COMMENT 'openid',
//
//  `client_name` varchar(64) NOT NULL COMMENT '客户名字',
//  `client_telphone` varchar(25) NOT NULL COMMENT '客户电话',
//
//  `deleted` int(10) NOT NULL DEFAULT '0' COMMENT '删除(1已删除)',
//  `state` int(1) NOT NULL DEFAULT '0' COMMENT '审核状态(1同意)',
//  `relate_id` varchar(80) NOT NULL DEFAULT '0' COMMENT '关联会员表',
//  `people_num` int(1) NOT NULL DEFAULT '1' COMMENT '会议人数',
//  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
//  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
//  `starttime` int(10) NOT NULL DEFAULT '0' COMMENT '预约开始时间',
//  `endtime` int(10) NOT NULL DEFAULT '0' COMMENT '预约结束时间',
//  `uniacid` int(10) NOT NULL DEFAULT '0' COMMENT 'uniacid'

/******************************************************* 预约会议室 入库与显示  *********************************************************/



/******************************************************* 附加服务 入库与显示 *********************************************************/

$order_goodsid      = $_GPC['order_goodsid'];

$total_price_accessorial_service = 0;

$allgoods = array();

$id = intval($_GPC['id']);

$optionid = intval($_GPC['optionid']);

$total = intval($_GPC['total']);

if ((empty($total)) || ($total < 1)) {
    $total = 1;
}
$direct = false; //是否是直接购买
$returnUrl = ''; //当前连接

if (!$direct) {
    //如果不是直接购买（从购物车购买）
    $goodids = $_GPC['order_goodsid'];

    $condition = empty($goodids) ? '' : 'AND id IN (' . $goodids . ")";
    $list = pdo_fetchall(
          " SELECT * "
        . " FROM " . tablename('superdesk_boardroom_s_cart')
        . " WHERE  uniacid = '{$_W['uniacid']}' AND from_user = '{$_W['openid']}' {$condition}");

    // 购物车上有
    if (!empty($list)) {
        foreach ($list as &$g) {
            $item = pdo_fetch(
                  " select id,thumb,title,weight,marketprice,total,type,totalcnf,sales,unit "
                . " from " . tablename("superdesk_boardroom_s_goods")
                . " where id=:id limit 1",
                array(
                    ":id" => $g['goodsid']
                )
            );
            //属性
            $option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("superdesk_boardroom_s_goods_option") . " where id=:id limit 1", array(":id" => $g['optionid']));
            if ($option) {
                $item['optionid'] = $g['optionid'];
                $item['title'] = $item['title'];
                $item['optionname'] = $option['title'];
                $item['marketprice'] = $option['marketprice'];
                $item['weight'] = $option['weight'];
            }
            $item['stock'] = $item['total'];
            $item['total'] = $g['total'];
            $item['totalprice'] = $g['total'] * $item['marketprice'];
            $allgoods[] = $item;
            $total_price_accessorial_service += $item['totalprice'];
            if ($item['type'] == 1) {
                $needdispatch = true;
            }
        }
        unset($g);
    }
//    $returnUrl = $this->createMobileUrl("confirm");
}

// 购物车上有，才会进来 生成订单 入库
if (count($allgoods) > 0) {

    //  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功',
//  `sendtype` tinyint(1) unsigned NOT NULL COMMENT '1为快递，2为自提',
//  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付',
//  `transaction_id` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号',
//  `goodstype` tinyint(1) unsigned NOT NULL DEFAULT '1',

// 是否自提
    $sendtype = 2;
    $dispatchprice = 0;// 运费
//    $address = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE id = :id", array(':id' => intval($_GPC['address'])));
//    if ($_GPC['goodstype'] != '2') {
//        if (empty($address)) {
//            message('抱歉，请您填写收货地址！');
//        }
//        // 运费
//        $dispatchid = intval($_GPC['dispatch']);
//        $dispatchprice = 0;
//        foreach ($dispatch as $d) {
//            if ($d['id'] == $dispatchid) {
//                $dispatchprice = $d['price'];
//                $sendtype = $d['dispatchtype'];
//            }
//        }
//    } else {
//        $sendtype = '3';
//    }

    //配送方式
//    $dispatch = pdo_fetchall("select id,dispatchname,dispatchtype,firstprice,firstweight,secondprice,secondweight from " . tablename("superdesk_boardroom_s_dispatch") . " WHERE uniacid = {$_W['uniacid']} order by displayorder desc");
//    foreach ($dispatch as &$d) {
//        $weight = 0;
//        foreach ($allgoods as $g) {
//            $weight += $g['weight'] * $g['total'];
//        }
//        $price = 0;
//        if ($weight <= $d['firstweight']) {
//            $price = $d['firstprice'];
//        } else {
//            $price = $d['firstprice'];
//            $secondweight = $weight - $d['firstweight'];
//            if ($secondweight % $d['secondweight'] == 0) {
//                $price += (int)($secondweight / $d['secondweight']) * $d['secondprice'];
//            } else {
//                $price += (int)($secondweight / $d['secondweight'] + 1) * $d['secondprice'];
//            }
//        }
//        $d['price'] = $price;
//    }
//    unset($d);

    // 商品价格
    $goodsprice = 0;
    foreach ($allgoods as $row) {
        $goodsprice += $row['totalprice'];
    }

    $data = array(
        'uniacid' => $_W['uniacid'],
        'from_user' => $_W['openid'],
        'out_trade_no' => $out_trade_no,
        'price' => $goodsprice + $dispatchprice,
        'dispatchprice' => $dispatchprice,
        'goodsprice' => $goodsprice,
        'status' => 0,
        'sendtype' => intval($sendtype),
        'dispatch' => $dispatchid,
        'goodstype' => intval($item['type']),
        'remark' => $_GPC['remark'],
        'address' => $address['username'] . ' | '
            . $address['mobile'] . ' | '
            . $address['zipcode'] . ' | '
            . $address['province'] . ' | '
            . $address['city'] . ' | '
            . $address['district'] . ' | '
            . $address['address'],
        'createtime' => TIMESTAMP
    );
    pdo_insert('superdesk_boardroom_s_order', $data);
    $orderid = pdo_insertid();
    //插入订单商品
    foreach ($allgoods as $row) {
        if (empty($row)) {
            continue;
        }
        $d = array(
            'uniacid' => $_W['uniacid'],
            'goodsid' => $row['id'],
            'orderid' => $orderid,
            'total' => $row['total'],
            'price' => $row['marketprice'],
            'createtime' => TIMESTAMP,
            'optionid' => $row['optionid']
        );
        $o = pdo_fetch("select title from " . tablename('superdesk_boardroom_s_goods_option') . " where id=:id limit 1", array(":id" => $row['optionid']));
        if (!empty($o)) {
            $d['optionname'] = $o['title'];
        }
        pdo_insert('superdesk_boardroom_s_order_goods', $d);
    }


// 清空购物车
    if (!$direct) {
        pdo_delete("superdesk_boardroom_s_cart", array("uniacid" => $_W['uniacid'], "from_user" => $_W['openid']));
    }


// 变更商品库存
    if (empty($item['totalcnf'])) {
        $this->setOrderStock($orderid);
    }




//    $carttotal = $this->getCartTotal();
//    $profile = fans_search($_W['openid'], array('resideprovince', 'residecity', 'residedist', 'address', 'realname', 'mobile'));
//    $row = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE isdefault = 1 and uid = :uid limit 1", array(':uid' => $_W['member']['uid']));
}

/******************************************************* 附加服务 入库与显示 *********************************************************/

$total_price_all = $total_price_boardroom_booking + $total_price_accessorial_service;

/******************************************************* pay 生成 *********************************************************/

//    message('提交订单成功,现在跳转到付款页面...', $this->createMobileUrl('pay', array('orderid' => $orderid)), 'success');

// pay.inc.php start

$this->checkAuth();

//    $orderid = intval($_GPC['orderid']);

$order = pdo_fetch("SELECT * FROM " . tablename('superdesk_boardroom_s_order') . " WHERE out_trade_no = :out_trade_no AND uniacid = :uniacid",
    array(
        ':out_trade_no' => $out_trade_no,
        ':uniacid' => $_W['uniacid']
    )
);


// TODO
//if ($order['status'] != '0') {
//    message('抱歉，您的订单已经付款或是被关闭，请重新进入付款！', $this->createMobileUrl('myorder'), 'error');// TODO
//}



// 货时付款
if (checksubmit('codsubmit')) {


    $ordergoods = pdo_fetchall("SELECT goodsid, total,optionid FROM " . tablename('superdesk_boardroom_s_order_goods') . " WHERE orderid = '{$orderid}'",
        array(), 'goodsid'
    );

    if (!empty($ordergoods)) {
        $goods = pdo_fetchall(
            " SELECT id, title, thumb, marketprice, unit, total,credit "
            ." FROM " . tablename('superdesk_boardroom_s_goods') . " "
            ." WHERE id IN ('" . implode("','", array_keys($ordergoods)) . "')");
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
$params['ordersn'] = $order['out_trade_no'];
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

    $params['fee'] = $total_price_all;//$order['price'];

}
// include $this->template('pay');
// 以上替代为以下
$this->pay($params);

// pay.inc.php end

/******************************************************* pay 生成 *********************************************************/

