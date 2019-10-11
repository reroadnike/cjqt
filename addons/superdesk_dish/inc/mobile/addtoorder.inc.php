<?php
/**
 * 提交订单
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:28 PM
 */

global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $_GPC['from_user'];
$this->_fromuser = $from_user;

$storeid = intval($_GPC['storeid']);

if (empty($from_user)) {
    $this->showMessageAjax('请重新发送关键字进入系统!', $this->msg_status_bad);
}

if (empty($storeid)) {
    $this->showMessageAjax('请先选择门店!', $this->msg_status_bad);
}

//查询购物车
$cart = pdo_fetchall("SELECT * FROM " . tablename($this->table_cart) . " WHERE weid = :weid AND from_user = :from_user AND storeid=:storeid", array(':weid' => $weid, ':from_user' => $from_user, ':storeid' => $storeid), 'goodsid');

if (empty($cart)) { //购物车为空
    $this->showMessageAjax('请先添加商品!', $this->msg_status_bad);
} else {
    $goods = pdo_fetchall("SELECT id, title, thumb, marketprice, unitname FROM " . tablename($this->table_goods) . " WHERE id IN ('" . implode("','", array_keys($cart)) . "')");
}

$store = pdo_fetch("SELECT * FROM " . tablename($this->table_stores) . " WHERE weid=:weid AND id=:id LIMIT 1", array(':weid' => $weid, ':id' => $storeid));

$guest_name = trim($_GPC['guest_name']); //用户名
$tel = trim($_GPC['tel']); //电话
$sex = trim($_GPC['sex']); //性别
$meal_time = trim($_GPC['meal_time']); //订餐时间
$counts = intval($_GPC['counts']); //预订人数
$seat_type = intval($_GPC['seat_type']); //就餐形式
$carports = intval($_GPC['carports']); //预订车位
$remark = trim($_GPC['remark']); //备注
$address = trim($_GPC['address']); //地址
$tables = intval($_GPC['tables']); //桌号
$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid={$weid} LIMIT 1");
$ordertype = intval($_GPC['ordertype']) == 0 ? 1 : intval($_GPC['ordertype']);

//用户信息判断
if (empty($guest_name)) {
    $this->showMessageAjax('请输入姓名!', $this->msg_status_bad);
}
if (empty($tel)) {
    $this->showMessageAjax('请输入联系电话!', $this->msg_status_bad);
}

if ($ordertype == 1) {//店内
    if ($counts <= 0) {
        $this->showMessageAjax('预订人数必须大于0!', $this->msg_status_bad);
    }
    if ($seat_type == 0) {
        $this->showMessageAjax('请选择就餐形式!', $this->msg_status_bad);
    }
    if ($tables == 0) {
        $this->showMessageAjax('请输入桌号!', $this->msg_status_bad);
    }
} else if ($ordertype == 2) {//外卖
    if (empty($address)) {
        $this->showMessageAjax('请输入联系地址!', $this->msg_status_bad);
    }

//            if ($meal_time == '休息中'){//debug
//                $this->showMessageAjax('休息中，暂不支持外卖!', $this->msg_status_bad);
//            }
}

$user = pdo_fetch("SELECT * FROM " . tablename('superdesk_dish_address') . " WHERE weid = :weid  AND from_user=:from_user ORDER BY `id` DESC limit 1", array(':weid' => $weid, ':from_user' => $from_user));
$fansdata = array('weid' => $weid, 'from_user' => $from_user, 'realname' => $guest_name, 'address' => $address, 'mobile' => $tel);
if (empty($address)) {
    unset($fansdata['address']);
}

if (empty($user)) {
    pdo_insert('superdesk_dish_address', $fansdata);
} else {
    pdo_update('superdesk_dish_address', $fansdata, array('id' => $user['id']));
}

//2.购物车 //a.添加订单、订单产品
$totalnum = 0;
$totalprice = 0;
$goodsprice = 0;
$dispatchprice = 0;
$freeprice = 0;

foreach ($cart as $value) {
    $totalnum = $totalnum + intval($value['total']);
    $goodsprice = $goodsprice + (intval($value['total']) * floatval($value['price']));
}

if ($ordertype == 2) { //外卖
    $dispatchprice = $store['dispatchprice'];
    $freeprice = floatval($store['freeprice']);
    if ($freeprice > 0.00) {
        if ($goodsprice > $freeprice) {
            $dispatchprice = 0;
        }
    }
}

$totalprice = $goodsprice + $dispatchprice;

if ($ordertype == 2) {
    $sendingprice = floatval($store['sendingprice']);
    if ($sendingprice > 0.00) {
        if ($goodsprice < $sendingprice) {
            $this->showMessageAjax('您的购买金额达不到起送价格!', $this->msg_status_bad);
        }
    }
}

$fansid = $_W['fans']['id'];
$data = array(
    'weid' => $weid,
    'from_user' => $from_user,
    'storeid' => $storeid,
    'ordersn' => date('md') . sprintf("%04d", $fansid) . random(4, 1), //订单号
    'totalnum' => $totalnum, //产品数量
    'totalprice' => $totalprice, //总价
    'goodsprice' => $goodsprice,
    'dispatchprice' => $dispatchprice,
    'paytype' => 0, //付款类型
    'username' => $guest_name,
    'tel' => $tel,
    'meal_time' => $meal_time,
    'counts' => $counts,
    'seat_type' => $seat_type,
    'tables' => $tables,
    'carports' => $carports,
    'dining_mode' => $ordertype, //订单类型
    'remark' => $remark, //备注
    'address' => $address, //地址
    'status' => 0, //状态
    'dateline' => TIMESTAMP
);

//保存订单
pdo_insert($this->table_order, $data);
$orderid = pdo_insertid();

$prints = pdo_fetchall("SELECT * FROM " . tablename($this->table_print_setting) . " WHERE storeid = :storeid AND print_status=1", array(':storeid' => $storeid));
foreach ($prints as $key => $value) {
    if ($value['type'] == 'hongxin') { //宏信
        $print_order_data = array(
            'weid' => $weid,
            'orderid' => $orderid,
            'print_usr' => $value['print_usr'],
            'print_status' => -1,
            'dateline' => TIMESTAMP
        );
        $print_order = pdo_fetch("SELECT * FROM " . tablename($this->table_print_order) . " WHERE orderid=:orderid AND print_usr=:usr LIMIT 1", array(':orderid' => $orderid, ':usr' => $value['print_usr']));
        if (empty($print_order)) {
            pdo_insert('superdesk_dish_print_order', $print_order_data);
        }
    }
}

//保存新订单商品
foreach ($cart as $row) {
    if (empty($row) || empty($row['total'])) {
        continue;
    }

    pdo_insert($this->table_order_goods, array(
        'weid' => $_W['uniacid'],
        'storeid' => $row['storeid'],
        'goodsid' => $row['goodsid'],
        'orderid' => $orderid,
        'price' => $row['price'],
        'total' => $row['total'],
        'dateline' => TIMESTAMP,
    ));
}

//清空购物车
pdo_delete($this->table_cart, array('weid' => $weid, 'from_user' => $from_user, 'storeid' => $storeid));
$result['orderid'] = $orderid;
$result['code'] = $this->msg_status_success;
$result['msg'] = '操作成功';
message($result, '', 'ajax');