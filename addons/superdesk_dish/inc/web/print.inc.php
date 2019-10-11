<?php
/**
 *
 * 打印数据
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:24 PM
 */

global $_W, $_GPC;
$weid = $_W['uniacid'];
$usr = !empty($_GET['usr']) ? $_GET['usr'] : '355839026790719';
$ord = !empty($_GET['ord']) ? $_GET['ord'] : 'no';
$sgn = !empty($_GET['sgn']) ? $_GET['sgn'] : 'no';

header('Content-type: text/html; charset=gbk');

$print_type_confirmed = 0;
$print_type_payment = 1;

//更新打印状态
if (isset($_GET['sta'])) {
    $id = intval($_GPC['id']); //订单id
    $sta = intval($_GPC['sta']); //状态

    pdo_update($this->table_print_order, array('print_status' => $sta), array('orderid' => $id, 'print_usr' => $usr));
    //id —— 平台下发打印数据的id号,打印机打印后回复打印是否成功带此id号。
    //usr -- 打印机终端系统的IMEI号码或SIM卡的IMSI号码
    //sta —— 打印机状态(0为打印成功, 1为过热,3为缺纸卡纸等)
    exit;
}

//打印机配置信息
$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_print_setting) . " WHERE print_usr = :usr AND print_status=1 AND type='hongxin'", array(':usr' => $usr));
if ($setting == false) {
    exit;
}

//门店id
$storeid = $setting['storeid'];

$condition = "";
if ($setting['print_type'] == $print_type_confirmed) {
    //已确认订单 //status == 1
    $condition = ' AND status=1 ';
} else if ($setting['print_type'] == $print_type_payment) {
    //已付款订单 //已完成
    $condition = ' AND (status=2 or status=3) ';
}

//根据订单id读取相关订单
$order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE  id IN(SELECT orderid FROM ims_superdesk_dish_print_order WHERE print_status=-1 AND print_usr=:print_usr) AND storeid = :storeid {$condition} ORDER BY id DESC limit 1", array(':storeid' => $storeid, ':print_usr' => $usr));

//没有新订单
if ($order == false) {
    message('no data!');
    exit;
}

//商品id数组
$goodsid = pdo_fetchall("SELECT goodsid, total FROM " . tablename($this->table_order_goods) . " WHERE orderid = '{$order['id']}'", array(), 'goodsid');

//商品
$goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . "  WHERE id IN ('" . implode("','", array_keys($goodsid)) . "')");
$order['goods'] = $goods;

if (!empty($setting['print_top'])) {
    $content = "%10" . $setting['print_top'] . "\n";
} else {
    $content = '';
}

$paytype = array('0' => '线下付款', '1' => '余额支付', '2' => 在线支付, '3' => '货到付款');
$content .= '%00单号:' . $order['ordersn'] . "\n";
$content .= '支付方式:' . $paytype[$order['paytype']] . "\n";
$content .= '下单日期:' . date('Y-m-d H:i:s', $order['dateline']) . "\n";
$content .= '预约时间:' . $order['meal_time'] . "\n";
if (!empty($order['seat_type'])) {
    $seat_type = $order['seat_type'] == 1 ? '大厅' : '包间';
    $content .= '%10位置类型:' . $seat_type . "\n";
}
if (!empty($order['tables'])) {
    $content .= '%10桌号:' . $order['tables'] . "\n";
}

if (!empty($order['remark'])) {
    $content .= '%10备注:' . $order['remark'] . "\n";
}
$content .= "%00\n名称              数量  单价 \n";
$content .= "----------------------------\n%10";

$content1 = '';
foreach ($order['goods'] as $v) {
    if ($v['isspecial'] == 2) {
        $money = intval($v['marketprice']) == 0 ? $v['productprice'] : $v['marketprice'];
    } else {
        $money = $v['productprice'];
    }

    $content1 .= $this->stringformat($v['title'], 16) . $this->stringformat($goodsid[$v['id']]['total'], 4, false) . $this->stringformat(number_format($money, 2), 7, false) . "\n\n";
}

$content2 = "----------------------------\n";
$content2 .= "%10总数量:" . $order['totalnum'] . "   总价:" . number_format($order['totalprice'], 2) . "元\n%00";
if (!empty($order['username'])) {
    $content2 .= '姓名:' . $order['username'] . "\n";
}
if (!empty($order['tel'])) {
    $content2 .= '手机:' . $order['tel'] . "\n";
}
if (!empty($order['address'])) {
    $content2 .= '地址:' . $order['address'] . "\n";
}

if (!empty($setting['qrcode_status'])) {
    $qrcode_url = trim($setting['qrcode_url']);
    if (!empty($qrcode_url)) {
        $content2 .= "%%%50372C" . $qrcode_url . "\n";
    }
}

//$content2 .= "%%%50372Chttp://www.weisrc.com\n";

if (!empty($setting['print_bottom'])) {
    $content2 .= "%10" . $setting['print_bottom'] . "\n%00";
}

$content = iconv("UTF-8", "GB2312//IGNORE", $content);
$content1 = iconv("UTF-8", "GB2312//IGNORE", $content1);
$content2 = iconv("UTF-8", "GB2312//IGNORE", $content2);

$setting = '<setting>124:' . $setting['print_nums'] . '|134:0</setting>';
$setting = iconv("UTF-8", "GB2312//IGNORE", $setting);
echo '<?xml version="1.0" encoding="GBK"?><r><id>' . $order['id'] . '</id><time>' . date('Y-m-d H:i:s', $order['dateline']) . '</time><content>' . $content . $content1 . $content2 . '</content>' . $setting . '</r>';