<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/29/17
 * Time: 4:57 AM
 */

global $_W, $_GPC;


$title = "订单列表";

$this->checkAuth();
$op = $_GPC['op'];

if ($op == 'confirm') {

    $orderid = intval($_GPC['orderid']);
    $order = pdo_fetch("SELECT * FROM " . tablename('superdesk_boardroom_4school_s_order') . " WHERE id = :id AND from_user = :from_user AND uniacid = :uniacid", array(':id' => $orderid, ':from_user' => $_W['openid'], ':uniacid' => $_W['uniacid']));
    if (empty($order)) {
        message('抱歉，您的订单不存或是已经被取消！', $this->createMobileUrl('myorder'), 'error');
    }
    pdo_update('superdesk_boardroom_4school_s_order', array('status' => 3), array('id' => $orderid, 'from_user' => $_W['openid']));
    message('确认收货完成！', $this->createMobileUrl('myorder'), 'success');

} else if ($op == 'detail') {

    $orderid = intval($_GPC['orderid']);
    $item = pdo_fetch("SELECT * FROM " . tablename('superdesk_boardroom_4school_s_order') . " WHERE uniacid = '{$_W['uniacid']}' AND from_user = '{$_W['openid']}' and id='{$orderid}' limit 1");
    if (empty($item)) {
        message('抱歉，您的订单不存或是已经被取消！', $this->createMobileUrl('myorder'), 'error');
    }
    $goodsid = pdo_fetch("SELECT goodsid,total FROM " . tablename('superdesk_boardroom_4school_s_order_goods') . " WHERE orderid = '{$orderid}'", array(), 'goodsid');
    $goods = pdo_fetchall("SELECT g.id, g.title, g.thumb, g.unit, g.marketprice, o.total,o.optionid FROM " . tablename('superdesk_boardroom_4school_s_order_goods')
        . " o left join " . tablename('superdesk_boardroom_4school_s_goods') . " g on o.goodsid=g.id " . " WHERE o.orderid='{$orderid}'");
    foreach ($goods as &$g) {
        //属性
        $option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("superdesk_boardroom_4school_s_goods_option") . " where id=:id limit 1", array(":id" => $g['optionid']));
        if ($option) {
            $g['title'] = "[" . $option['title'] . "]" . $g['title'];
            $g['marketprice'] = $option['marketprice'];
        }
    }
    unset($g);
    $dispatch = pdo_fetch("SELECT id,dispatchname,enabled FROM " . tablename('superdesk_boardroom_4school_s_dispatch') . ' WHERE id=:id ', array(":id" => $item['dispatch']));
    include $this->template('order_detail');

} else {

    /******************************************************* 预约服务 *********************************************************/


    $url_ajax_boardroom_appointment_cancel = $this->createMobileUrl('ajax_boardroom_appointment_cancel');

    include_once(MODULE_ROOT . '/model/boardroom_appointment.class.php');
    $boardroom_appointment = new boardroom_appointmentModel();

    include_once(MODULE_ROOT . '/model/boardroom.class.php');
    $boardroom = new boardroomModel();

//    '-1取消，0待审，1待审已付款，3已审'

    $status = $_GPC['status'];
    $is_overdue = $_GPC['is_overdue'];

    $where_appointment = array(
        'status' => $status,
        'is_overdue' => $is_overdue,
        'openid' => $_W['openid'],
        'uniacid' => $_W['uniacid']
    );

//    var_dump($where_appointment);

    $page = max(1, intval($_GPC['page']));
    $page_size = 100;

    $result = $boardroom_appointment->queryByMobile($where_appointment,$page,$page_size);

    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $my_boardroom_appointment = $result['data'];

    foreach ($my_boardroom_appointment as $index => &$_boardroom_appointment){

        $_tmp_boardroom = $boardroom->getOne($_boardroom_appointment['boardroom_id']);

        if($_tmp_boardroom){
            $_boardroom_appointment["boardroom"] = $_tmp_boardroom;
        }else{
            $boardroom_appointment->delete($_boardroom_appointment['id']);
            unset($my_boardroom_appointment[$index]);
            $total = $total - 1;
        }


    }
    unset($_boardroom_appointment);

    /******************************************************* 预约服务 *********************************************************/

    /******************************************************* 附加服务 *********************************************************/
//    include_once(MODULE_ROOT . '/model/boardroom_s_goods.class.php');
//
//    $boardroom_s_goods = new boardroom_s_goodsModel();
//
//    $where_order_goods = array(
//        'out_trade_no' => $out_trade_no,
//        'uniacid' => $_W['uniacid']
//    );
//
//    $goods = pdo_fetchall(
//        " SELECT `goodsid`, `total`, `optionid` "
//        . " FROM " . tablename('superdesk_boardroom_4school_s_order_goods')
//        . " WHERE `out_trade_no` = :out_trade_no AND `uniacid` = :uniacid",
//        $column
//    );
//
//    if($goods){
//        foreach ($goods as &$item){
//            $item['g'] = $boardroom_s_goods->getOne($item['goodsid']);
//        }
//        unset($item);
//    }
    /******************************************************* 附加服务 *********************************************************/

    //include_once(MODULE_ROOT . '/model/department.class.php');
    //$department = new departmentModel();
    //
    //
    //$page = $_GPC['page'];
    //$page_size = 100;
    //
    //$result = $department->queryByMobile(array(),$page,$page_size);
    //$total = $result['total'];
    //$page = $result['page'];
    //$page_size = $result['page_size'];
    //$list = $result['data'];
    //
    //
    //$pager = pagination($total, $page, $page_size);
    //
    //
    //
    //$url_api_query_doctor = $this->createMobileUrl('api_query_doctor');

//    $pindex = max(1, intval($_GPC['page']));
//    $psize = 20;
//    $status = intval($_GPC['status']);
//    $where = " uniacid = '{$_W['uniacid']}' AND from_user = '{$_W['openid']}'";
//
//    if ($status == 2) {
//        $where.=" and ( status=1 or status=2 )";
//    } else {
//        $where.=" and status=$status";
//    }
//
//    $list = pdo_fetchall("SELECT * FROM " . tablename('superdesk_boardroom_4school_s_order') . " WHERE $where ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id');
//    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('superdesk_boardroom_4school_s_order') . " WHERE uniacid = '{$_W['uniacid']}' AND from_user = '{$_W['openid']}'");
//    $pager = pagination($total, $pindex, $psize);
//
//    if (!empty($list)) {
//        foreach ($list as &$row) {
//            $goodsid = pdo_fetchall("SELECT goodsid,total FROM " . tablename('superdesk_boardroom_4school_s_order_goods') . " WHERE orderid = '{$row['id']}'", array(), 'goodsid');
//            $goods = pdo_fetchall("SELECT g.id, g.title, g.thumb, g.unit, g.marketprice,o.total,o.optionid FROM " . tablename('superdesk_boardroom_4school_s_order_goods') . " o left join " . tablename('superdesk_boardroom_4school_s_goods') . " g on o.goodsid=g.id "
//                . " WHERE o.orderid='{$row['id']}'");
//            foreach ($goods as &$item) {
//
//                //属性
//                $option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("superdesk_boardroom_4school_s_goods_option") . " where id=:id limit 1", array(":id" => $item['optionid']));
//                if ($option) {
//                    $item['title'] = "[" . $option['title'] . "]" . $item['title'];
//                    $item['marketprice'] = $option['marketprice'];
//                }
//            }
//            unset($item);
//            $row['goods'] = $goods;
//            $row['total'] = $goodsid;
//            $row['dispatch'] = pdo_fetch("select id,dispatchname from " . tablename('superdesk_boardroom_4school_s_dispatch') . " where id=:id limit 1", array(":id" => $row['dispatch']));
//        }
//    }
    include $this->template('myorder');

}