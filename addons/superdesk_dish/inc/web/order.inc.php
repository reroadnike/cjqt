<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:14 PM
 */

global $_W, $_GPC;
$GLOBALS['frames'] = $this->getNaveMenu();

load()->func('tpl');
$action = 'order';
$title = $this->actions_titles[$action];
$storeid = intval($_GPC['storeid']);

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {

    $commoncondition = " weid = '{$_W['uniacid']}' ";
    if ($storeid != 0) {
        $commoncondition .= " AND storeid={$storeid} ";
    }

    $commonconditioncount = " weid = '{$_W['uniacid']}' ";
    if ($storeid != 0) {
        $commonconditioncount .= " AND storeid={$storeid} ";
    }

    if (!empty($_GPC['time'])) {
        $starttime = strtotime($_GPC['time']['start']);
        $endtime = strtotime($_GPC['time']['end']) + 86399;
        $commoncondition .= " AND dateline >= :starttime AND dateline <= :endtime ";
        $paras[':starttime'] = $starttime;
        $paras[':endtime'] = $endtime;
    }

    if (empty($starttime) || empty($endtime)) {
        $starttime = strtotime('-1 month');
        $endtime = time();
    }

    $pindex = max(1, intval($_GPC['page']));
    $psize = 10;

    if (!empty($_GPC['ordersn'])) {
        $commoncondition .= " AND ordersn LIKE '%{$_GPC['ordersn']}%' ";
    }

    if (!empty($_GPC['tel'])) {
        $commoncondition .= " AND tel LIKE '%{$_GPC['tel']}%' ";
    }

    if (!empty($_GPC['username'])) {
        $commoncondition .= " AND username LIKE '%{$_GPC['username']}%' ";
    }

    if (isset($_GPC['status']) && $_GPC['status'] != 0) {
        $commoncondition .= " AND status = '" . intval($_GPC['status']) . "'";
    }

    if (isset($_GPC['paytype']) && $_GPC['paytype'] != '') {
        $commoncondition .= " AND paytype = '" . intval($_GPC['paytype']) . "'";
    }

    if ($_GPC['out_put'] == 'output') {
        $sql = "select * from " . tablename($this->table_order)
            . " WHERE $commoncondition ORDER BY status DESC, dateline DESC ";
        $list = pdo_fetchall($sql, $paras);
        $orderstatus = array(
            '-1' => array('css' => 'default', 'name' => '已取消'),
            '0' => array('css' => 'danger', 'name' => '待付款'),
            '1' => array('css' => 'info', 'name' => '已确认'),
            '2' => array('css' => 'warning', 'name' => '已付款'),
            '3' => array('css' => 'success', 'name' => '已完成')
        );

        $paytypes = array(
            '0' => array('css' => 'danger', 'name' => '未支付'),
            '1' => array('css' => 'info', 'name' => '余额支付'),
            '2' => array('css' => 'warning', 'name' => '在线支付'),
            '3' => array('css' => 'success', 'name' => '现金支付')
        );

        $i = 0;
        foreach ($list as $key => $value) {
            $arr[$i]['ordersn'] = $value['ordersn'];
            $arr[$i]['transid'] = $value['transid'];
            $arr[$i]['paytype'] = $paytypes[$value['paytype']]['name'];
            $arr[$i]['status'] = $orderstatus[$value['status']]['name'];
            $arr[$i]['totalprice'] = $value['totalprice'];
            $arr[$i]['username'] = $value['username'];
            $arr[$i]['tel'] = $value['tel'];
            $arr[$i]['address'] = $value['address'];
            $arr[$i]['dateline'] = date('Y-m-d H:i:s', $value['dateline']);
            $i++;
        }

        $this->exportexcel($arr, array('订单号', '商户订单号', '支付方式', '状态', '总价', '真实姓名', '电话号码', '地址', '时间'), time());
        exit();
    }

    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_order) . " WHERE $commoncondition ORDER BY id desc, dateline DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $paras);

    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_order) . " WHERE $commoncondition", $paras);

    $pager = pagination($total, $pindex, $psize);

    if (!empty($list)) {
        foreach ($list as $row) {
            $userids[$row['from_user']] = $row['from_user'];
        }
    }

    $order_count_all = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_order) . "  WHERE {$commonconditioncount} ");
    $order_count_confirm = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_order) . "  WHERE {$commonconditioncount} AND status=1");
    $order_count_pay = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_order) . "  WHERE {$commonconditioncount} AND status=2");
    $order_count_finish = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_order) . "  WHERE {$commonconditioncount} AND status=3");
    $order_count_cancel = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_order) . "  WHERE {$commonconditioncount} AND status=-1");

    $users = fans_search($userids, array('realname', 'resideprovince', 'residecity', 'residedist', 'address', 'mobile', 'qq'));

    //打印数量
    $print_order_count = pdo_fetchall("SELECT orderid,COUNT(1) as count FROM " . tablename($this->table_print_order) . "  GROUP BY orderid,weid having weid = :weid", array(':weid' => $_W['weid']), 'orderid');

    //黑名单
    $blacklist = pdo_fetchall("SELECT * FROM " . tablename($this->table_blacklist) . " WHERE weid = :weid", array(':weid' => $_W['weid']), 'from_user');

    //门店列表
    $storelist = pdo_fetchall("SELECT * FROM " . tablename($this->table_stores) . " WHERE weid = :weid", array(':weid' => $_W['weid']), 'id');

} elseif ($operation == 'detail') {
    
    //流程 第一步确认付款 第二步确认订单 第三步，完成订单
    $id = intval($_GPC['id']);
    $order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id=:id LIMIT 1", array(':id' => $id));

    if (checksubmit('confrimsign')) {
        pdo_update($this->table_order, array('remark' => $_GPC['remark'], 'sign' => $_GPC['sign'], 'reply' => $_GPC['reply']), array('id' => $id));
        message('操作成功！', referer(), 'success');
    }
    if (checksubmit('finish')) {
        //isfinish
        if ($order['isfinish'] == 0) {
            //计算积分
            $this->setOrderCredit($order['id']);
            pdo_update($this->table_order, array('isfinish' => 1), array('id' => $id));
        }
        pdo_update($this->table_order, array('status' => 3, 'remark' => $_GPC['remark']), array('id' => $id));
        message('订单操作成功！', referer(), 'success');
    }
    if (checksubmit('cancel')) {
        pdo_update($this->table_order, array('status' => 1, 'remark' => $_GPC['remark']), array('id' => $id));
        message('取消完成订单操作成功！', referer(), 'success');
    }
    if (checksubmit('confirm')) {
        pdo_update($this->table_order, array('status' => 1, 'remark' => $_GPC['remark']), array('id' => $id));
        message('确认订单操作成功！', referer(), 'success');
    }
    if (checksubmit('cancelpay')) {
        pdo_update($this->table_order, array('status' => 0, 'remark' => $_GPC['remark']), array('id' => $id));
        message('取消订单付款操作成功！', referer(), 'success');
    }
    if (checksubmit('confrimpay')) {
        pdo_update($this->table_order, array('status' => 2, 'remark' => $_GPC['remark']), array('id' => $id));
        message('确认订单付款操作成功！', referer(), 'success');
    }
    if (checksubmit('close')) {
        pdo_update($this->table_order, array('status' => -1, 'remark' => $_GPC['remark']), array('id' => $id));
        message('订单关闭操作成功！', referer(), 'success');
    }
    if (checksubmit('open')) {
        pdo_update($this->table_order, array('status' => 0, 'remark' => $_GPC['remark']), array('id' => $id));
        message('开启订单操作成功！', referer(), 'success');
    }

    $item = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id = :id", array(':id' => $id));

    $item['user'] = fans_search($item['from_user'], array('realname', 'resideprovince', 'residecity', 'residedist', 'address', 'mobile', 'qq'));

    $goodsid = pdo_fetchall("SELECT goodsid, total FROM " . tablename($this->table_order_goods) . " WHERE orderid = '{$item['id']}'", array(), 'goodsid');

    $goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . "  WHERE id IN ('" . implode("','", array_keys($goodsid)) . "')");
    $item['goods'] = $goods;

} else if ($operation == 'delete') {

    $id = $_GPC['id'];
    pdo_delete($this->table_order, array('id' => $id));
    pdo_delete($this->table_order_goods, array('orderid' => $id));
    message('删除成功！', $this->createWebUrl('order', array('op' => 'display', 'storeid' => $storeid)), 'success');

} else if ($operation == 'print') {

    $id = $_GPC['id'];//订单id
    $flag = false;

    $prints = pdo_fetchall("SELECT * FROM " . tablename($this->table_print_setting) . " WHERE weid = :weid AND storeid=:storeid", array(':weid' => $_W['uniacid'], ':storeid' => $storeid));

    if (empty($prints)) {
        message('请先添加打印机或者开启打印机！');
    }

    foreach ($prints as $key => $value) {
        if ($value['print_status'] == 1 && $value['type'] == 'hongxin') {
            $data = array(
                'weid' => $_W['uniacid'],
                'orderid' => $id,
                'print_usr' => $value['print_usr'],
                'print_status' => -1,
                'dateline' => TIMESTAMP
            );
            pdo_insert('superdesk_dish_print_order', $data);
        }
    }
    $this->feiyinSendFreeMessage($id);
    message('操作成功！', $this->createWebUrl('order', array('op' => 'display', 'storeid' => $storeid)), 'success');

} else if ($operation == 'black') {

    $id = $_GPC['id'];//订单id
    $order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id=:id AND weid=:weid  LIMIT 1", array(':id' => $id, ':weid' => $_W['uniacid']));

    if (empty($order)) {
        message('数据不存在!');
    }

    $data = array(
        'weid' => $_W['uniacid'],
        'from_user' => $order['from_user'],
        'status' => 0,
        'dateline' => TIMESTAMP
    );

    $blacker = pdo_fetch("SELECT * FROM " . tablename($this->table_blacklist) . " WHERE from_user=:from_user AND weid=:weid  LIMIT 1", array(':from_user' => $order['from_user'], ':weid' => $_W['uniacid']));

    if (!empty($blacker)) {
        message('该用户已经在黑名单中!', $this->createWebUrl('order', array('op' => 'display', 'storeid' => $storeid)));
    }

    pdo_insert('superdesk_dish_blacklist', $data);
    message('操作成功！', $this->createWebUrl('order', array('op' => 'display', 'storeid' => $storeid)), 'success');
}

include $this->template('order');