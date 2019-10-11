<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/27/17
 * Time: 6:01 AM
 */

global $_GPC, $_W;

$title = "确定订单";


// 传入参数,不能变
$id                 = $_GPC['id'];//echo $id ;
$select_time_bar    = $_GPC['select_time_bar'];


// TODO
$people_num = 10;
$now = time();
$Ymd = date('Y-m-d', $now);

$out_trade_no = date('md') . random(4, 1);


/******************************************************* 预约会议室 入库与显示 *********************************************************/

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

$time_arr = explode("-",$check_start_end);
$starttime = strtotime($Ymd . " " . $time_arr[0]);
$endtime = strtotime($Ymd . " " . $time_arr[1]);
$params = array(

    "boardroom_id" => $id,
    "out_trade_no" => $out_trade_no,
    "quantity" => $num_hour,
    "price" => $_boardroom['price'],
    "total" => $total_price_boardroom_booking,
    "lable_ymd" => $Ymd,
    "lable_time" => $check_start_end,
    "situation" => json_encode($situation),
    "openid" => $_W['openid'],
    "client_name" => "",
    "client_telphone" => "",
    "status" => 0,      //'-1取消状态，0普通状态，1为已付款，2为已发货，3为成功'
    "relate_id" => '',  // 用于微信模板消息通知 openid
    "people_num" => $people_num,

    // TODO
    "starttime"     => $starttime,
    "endtime"       => $endtime,

    "uniacid"       => $_W['uniacid'],

);

$_appointment_id = $boardroom_appointment->insert($params);

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
$optionid = intval($_GPC['optionid']);

if (!empty($order_goodsid)) {
    // 从购物车购买
    $goodids = $order_goodsid;

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
}

// 购物车上有，才会进来 生成订单 入库
if (count($allgoods) > 0) {

//  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功',
//  `sendtype` tinyint(1) unsigned NOT NULL COMMENT '1为快递，2为自提',
//  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付',
//  `transaction_id` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号',
//  `goodstype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1 真实　2 虚拟',


    $sendtype = 2;      // 自提
    $dispatchprice = 0; // 运费


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
            'out_trade_no'  => $out_trade_no,
            'uniacid'       => $_W['uniacid'],
            'goodsid'       => $row['id'],
            'orderid'       => $orderid,
            'total'         => $row['total'],
            'price'         => $row['marketprice'],
            'createtime'    => TIMESTAMP,
            'optionid'      => $row['optionid']
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
}

/******************************************************* 附加服务 入库与显示 *********************************************************/

$total_price_all = $total_price_boardroom_booking + $total_price_accessorial_service;

/******************************************************* pay 生成 *********************************************************/


$this->checkAuth();


$params['tid'] = $out_trade_no;
$params['user'] = $_W['openid'];
$params['title'] = $_boardroom['name'] . " " . $Ymd . " ". $check_start_end . "使用";
$params['ordersn'] = $out_trade_no;
$params['virtual'] = true;

// 卡
$we7_coupon_info = module_fetch('we7_coupon');
if (!empty($we7_coupon_info) && pdo_tableexists('mc_card')) {

    if (!function_exists('card_discount_fee')) {
        $params['fee'] = $total_price_all;
    } else {
        load() -> model('card');
        $params['fee'] = card_discount_fee($total_price_all);
    }

} else {
    $params['fee'] = $total_price_all;

}
//$this->pay($params);
//$params = array();
$mine = array();

if(!$this->inMobile) {
    message('支付功能只能在手机上使用');
}


$params['module'] = $this->module['name'];

$pars = array();
$pars[':uniacid'] = $_W['uniacid'];
$pars[':module'] = $params['module'];
$pars[':tid'] = $params['tid'];

if($params['fee'] <= 0) {
    $pars['from'] = 'return';
    $pars['result'] = 'success';
    $pars['type'] = '';
    $pars['tid'] = $params['tid'];
    $site = WeUtility::createModuleSite($pars[':module']);
    $method = 'payResult';
    if (method_exists($site, $method)) {
        exit($site->$method($pars));
    }
}



$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid';
$log = pdo_fetch($sql, $pars);
if(!empty($log) && $log['status'] == '1') {
    message('这个订单已经支付成功, 不需要重复支付.');
}


$setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));
if(!is_array($setting['payment'])) {
    message('没有有效的支付方式, 请联系网站管理员.');
}


$pay = $setting['payment'];
if (empty($_W['member']['uid'])) {
    $pay['credit']['switch'] = false;
}


if (!empty($pay['credit']['switch'])) {
    $credtis = mc_credit_fetch($_W['member']['uid']);
}

$you = 0;


if($pay['card']['switch'] == 2 && !empty($_W['openid'])) {
    if($_W['card_permission'] == 1 && !empty($params['module'])) {
        $cards = pdo_fetchall('SELECT a.id,a.card_id,a.cid,b.type,b.title,b.extra,b.is_display,b.status,b.date_info FROM ' . tablename('coupon_modules') . ' AS a LEFT JOIN ' . tablename('coupon') . ' AS b ON a.cid = b.id WHERE a.acid = :acid AND a.module = :modu AND b.is_display = 1 AND b.status = 3 ORDER BY a.id DESC', array(':acid' => $_W['acid'], ':modu' => $params['module']));
        $flag = 0;
        if(!empty($cards)) {
            foreach($cards as $temp) {
                $temp['date_info'] = iunserializer($temp['date_info']);
                if($temp['date_info']['time_type'] == 1) {
                    $starttime = strtotime($temp['date_info']['time_limit_start']);
                    $endtime = strtotime($temp['date_info']['time_limit_end']);
                    if(TIMESTAMP < $starttime || TIMESTAMP > $endtime) {
                        continue;
                    } else {
                        $param = array(
                            ':acid' => $_W['acid']
                        , ':openid' => $_W['openid']
                        , ':card_id' => $temp['card_id']
                        );
                        $num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('coupon_record') . ' WHERE acid = :acid AND openid = :openid AND card_id = :card_id AND status = 1', $param);
                        if($num <= 0) {
                            continue;
                        } else {
                            $flag = 1;
                            $card = $temp;
                            break;
                        }
                    }
                } else {
                    $deadline = intval($temp['date_info']['deadline']);
                    $limit = intval($temp['date_info']['limit']);
                    $param = array(':acid' => $_W['acid'], ':openid' => $_W['openid'], ':card_id' => $temp['card_id']);
                    $record = pdo_fetchall('SELECT addtime,id,code FROM ' . tablename('coupon_record') . ' WHERE acid = :acid AND openid = :openid AND card_id = :card_id AND status = 1', $param);
                    if(!empty($record)) {
                        foreach($record as $li) {
                            $time = strtotime(date('Y-m-d', $li['addtime']));
                            $starttime = $time + $deadline * 86400;
                            $endtime = $time + $deadline * 86400 + $limit * 86400;
                            if(TIMESTAMP < $starttime || TIMESTAMP > $endtime) {
                                continue;
                            } else {
                                $flag = 1;
                                $card = $temp;
                                break;
                            }
                        }
                    }
                    if($flag) {
                        break;
                    }
                }
            }
        }


        if($flag) {


            if($card['type'] == 'discount') {
                $you = 1;
                $card['fee'] = sprintf("%.2f", ($params['fee'] * ($card['extra'] / 100)));
            } elseif($card['type'] == 'cash') {
                $cash = iunserializer($card['extra']);
                if($params['fee'] >= $cash['least_cost']) {
                    $you = 1;
                    $card['fee'] = sprintf("%.2f", ($params['fee'] -  $cash['reduce_cost']));
                }
            }


            load()->classs('coupon');
            $acc = new coupon($_W['acid']);
            $card_id = $card['card_id'];
            $time = TIMESTAMP;
            $randstr = random(8);
            $sign = array($card_id, $time, $randstr, $acc->account['key']);
            $signature = $acc->SignatureCard($sign);
            if(is_error($signature)) {
                $you = 0;
            }
        }
    }
}

if($pay['card']['switch'] == 3 && $_W['member']['uid']) {
    $cards = array();
    if(!empty($params['module'])) {
        $cards = pdo_fetchall('SELECT a.id,a.couponid,b.type,b.title,b.discount,b.condition,b.starttime,b.endtime FROM ' . tablename('activity_coupon_modules') . ' AS a LEFT JOIN ' . tablename('activity_coupon') . ' AS b ON a.couponid = b.couponid WHERE a.uniacid = :uniacid AND a.module = :modu AND b.condition <= :condition AND b.starttime <= :time AND b.endtime >= :time  ORDER BY a.id DESC', array(':uniacid' => $_W['uniacid'], ':modu' => $params['module'], ':time' => TIMESTAMP, ':condition' => $params['fee']), 'couponid');
        if(!empty($cards)) {
            foreach($cards as $key => &$card) {
                $has = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('activity_coupon_record') . ' WHERE uid = :uid AND uniacid = :aid AND couponid = :cid AND status = 1' . $condition, array(':uid' => $_W['member']['uid'], ':aid' => $_W['uniacid'], ':cid' => $card['couponid']));
                if($has > 0){
                    if($card['type'] == '1') {
                        $card['fee'] = sprintf("%.2f", ($params['fee'] * $card['discount']));
                        $card['discount_cn'] = sprintf("%.2f", $params['fee'] * (1 - $card['discount']));
                    } elseif($card['type'] == '2') {
                        $card['fee'] = sprintf("%.2f", ($params['fee'] -  $card['discount']));
                        $card['discount_cn'] = $card['discount'];
                    }
                } else {
                    unset($cards[$key]);
                }
            }
        }
    }
    if(!empty($cards)) {
        $cards_str = json_encode($cards);
    }
}

include $this->template('boardroom_firm_order');

/******************************************************* pay 生成 *********************************************************/

