<?php

/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 2/24/18
 * Time: 4:27 PM
 */

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order_goods.class.php');

class ShopOrderService
{
    private $_orderModel;
    private $_order_goodsModel;


    public function __construct()
    {

        global $_W;
        global $_GPC;

        $this->_orderModel = new orderModel();
        $this->_order_goodsModel = new order_goodsModel();
    }


    public function message($success = true, $resultMessage = '', $resultCode = '0000', $result = false, $code = 200)
    {

        $data                  = array();
        $data['success']       = $success;
        $data['resultMessage'] = $resultMessage;
        $data['resultCode']    = $resultCode;
        $data['result']        = $result;
        $data['code']          = $code;

        return $data;


    }


    /************ data/wwwroot/default/superdesk/addons/superdesk_shopv2/core/mobile/order/refund.php **********/


    // 售后
    protected function refundGlobalData($uniacid, $openid, $core_user, $orderid)// TODO 标志 楼宇之窗 openid shop_order 已处理
    {
        global $_W;
        global $_GPC;

//        $uniacid = $_W['uniacid'];
//        $openid  = $_W['openid'];

//        $orderid = intval($_GPC['id']);

        $order = pdo_fetch(
            ' select ' .
            '       id,status,price,refundid,goodsprice,dispatchprice,deductprice,deductcredit2,finishtime,isverify,`virtual`,refundstate,merchid,paytype ' .
            ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where id=:id and uniacid=:uniacid and openid=:openid and core_user=:core_user limit 1',
            array(
                ':id'        => $orderid,
                ':uniacid'   => $uniacid,
                ':openid'    => $openid,
                ':core_user' => $core_user
            )
        );

        if (empty($order)) {
            // TODO
//            if (!$_W['isajax']) {
//                header('location: ' . mobileUrl('order'));
//                exit();
//            } else {
//                show_json(0, '订单未找到');
//            }

            echo PHP_EOL . 'ERROR ';
            echo '订单未找到';
            return;
        } else {
            echo json_encode($order, JSON_UNESCAPED_UNICODE);
        }

        $_err = '';

        if ($order['status'] == 0) {

            $_err = '订单未付款，不能申请退款!';

        } else if ($order['status'] == 3) {

            if (!empty($order['virtual']) || ($order['isverify'] == 1)) {

                $_err = '此订单不允许退款!';

            } else if ($order['refundstate'] == 0) {

                $tradeset   = m('common')->getSysset('trade');
                $refunddays = intval($tradeset['refunddays']);

                //$refunddays 说明
                // 交易设置->退款申请->完成订单几天后->订单完成后 ，用户在x天内可以发起退款申请，设置0天不允许完成订单退款
                // 为了使自动退款可用 default $refunddays = 100

                $refunddays = 100;

                if (0 < $refunddays) {

                    $days = intval((time() - $order['finishtime']) / 3600 / 24);

                    if ($refunddays < $days) {
                        $_err = '订单完成已超过 ' . $refunddays . ' 天, 无法发起退款申请!';
                    }

                } else {
                    $_err = '订单完成, 无法申请退款!';
                }
            }
        }


        if (!empty($_err)) {
            // TODO

            echo PHP_EOL . 'ERROR ';
            echo $_err;
            echo ' Mark refundGlobalData';
            return;
        }


        $order['cannotrefund'] = false;

        $goods = pdo_fetchall(
            ' select og.id, og.goodsid, og.price, og.total, og.optionname, og.realprice, og.refundid, g.cannotrefund, g.thumb, g.title ' .
            ' from' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
            '       left join ' . tablename('superdesk_shop_goods') . ' g on g.id=og.goodsid ' .
            ' where og.orderid=' . $order['id']);
        if ($order['status'] == 2) {

            if (!empty($goods)) {
                foreach ($goods as $g) {
                    if (!($g['cannotrefund'] == 1)) {
                        continue;
                    }
                    $order['cannotrefund'] = true;
                    break;
                }
            }

        }

        if ($order['cannotrefund']) {
//            show_json(0, '此订单不可退换货');

            echo PHP_EOL . 'ERROR ';
            echo '此订单不可退换货';
            return;
        }

        $order['refundprice'] = $order['price'] + $order['deductcredit2'];

        if (2 <= $order['status']) {
            $order['refundprice'] -= $order['dispatchprice'];
        }

        $order['refundprice'] = round($order['refundprice'], 2);

        return array(
            'uniacid'   => $uniacid,
            'openid'    => $openid,
            'core_user' => $core_user,
            'orderid'   => $orderid,
            'order'     => $order,
            'goods'     => $goods
        );
    }

    // 售后
    public function refundMain($uniacid, $openid, $core_user, $orderid)
    {
        global $_W;
        global $_GPC;

        extract($this->refundGlobalData($uniacid, $openid, $core_user, $orderid));

        if ($order['status'] == '-1') {
            return $this->message(false, 'ERROR 请不要重复提交');
        }


        $refund = false;
        $imgnum = 0;

        foreach ($goods as $k => $v) {

            if (!empty($v['refundid'])) {

                $refund = pdo_fetch(
                    ' select * ' .
                    ' from ' . tablename('superdesk_shop_order_refund') .
                    ' where id=:id ' .
                    '       and uniacid=:uniacid ' .
                    '       and orderid=:orderid ' .
                    ' limit 1',
                    array(
                        ':id'      => $v['refundid'],
                        ':uniacid' => $uniacid,
                        ':orderid' => $orderid,
                        ':order_goods_id' => $v['id']
                    )
                );

                if (!empty($refund['refundaddress'])) {
                    $refund['refundaddress'] = iunserializer($refund['refundaddress']);
                }

                if (!empty($refund['imgs'])) {
                    $refund['imgs'] = iunserializer($refund['imgs']);
                }
            }

        }


        if (empty($refund)) {
            $show_price = round($order['refundprice'], 2);
        } else {
            $show_price = round($refund['applyprice'], 2);
        }

        return $this->message(true, '', '0000', $show_price);


    }

    // 售后
    public function refundSubmit($uniacid, $openid, $core_user, $orderid, $price, $rtype = 0, $reason = '', $content = '')
    {
        global $_W;
        global $_GPC;

        extract($this->refundGlobalData($uniacid, $openid, $core_user, $orderid));

        if ($order['status'] == '-1') {
            return $this->message(false, 'ERROR 订单已经处理完毕');
        }


//        $price = trim($_GPC['price']);
//        $rtype = intval($_GPC['rtype']);// 处理方式 1 退货退款 2 换货 0 退款(仅退款不退货)

        if ($rtype != 2) {

//            if (empty($price) && ($order['deductprice'] == 0)) {
//                return $this->message(false, 'ERROR 退款金额不能为0元');
//            }


//            if ($order['refundprice'] < $price) {
//                return $this->message(false, 'ERROR ' . '退款金额不能超过' . $order['refundprice'] . '元');
//            }

        }


        $refund = array(
            'uniacid'    => $uniacid,
            'merchid'    => $order['merchid'],
            'reason'     => trim($reason),//退款原因 不想要了 | 卖家缺货 | 拍错了/订单信息错误 | 其它
            'content'    => trim($content),//退款说明(选填)
            'imgs'       => iserializer($_GPC['images'])
        );

        if ($order['paytype'] == 3) {
            $refundstate = 2;
            $rtype = 0;
        } else {
            $refundstate = 1;
            $rtype = 3;
        }


        foreach ($goods as $k => $v) {

            if (empty($v['refundid'])) {

                $refund['createtime'] = time();
                $refund['orderid']    = $orderid;
                $refund['rtype']    = $rtype;
                $refund['applyprice'] = $goods['realprice'];
                $refund['orderprice'] = $order['refundprice'];
                $refund['refundno']   = m('common')->createNO('order_refund', 'refundno', 'SR');
                pdo_insert('superdesk_shop_order_refund', $refund);

                $refundid = pdo_insertid();

                //mark kafka 为了kafka转成了model执行
                $this->_order_goodsModel->updateByColumn(
                    array(
                        'refundid'    => $refundid,
                        'rstate' => $refundstate
                    ),
                    array(
                        'id'      => $orderid,
                        'uniacid' => $uniacid
                    )
                );
            } else {
                //mark kafka 为了kafka转成了model执行
                $this->_order_goodsModel->updateByColumn(
                    array(
                        'rstate' => $refundstate
                    ),
                    array(
                        'id'      => $orderid,
                        'uniacid' => $uniacid
                    )
                );
                pdo_update('superdesk_shop_order_refund', $refund, array('id' => $v['refundid'], 'uniacid' => $uniacid));
            }

        }

        m('notice')->sendOrderMessage($orderid, true);

        return $this->message(true, 'SUCCESS');
    }

    /************ data/wwwroot/default/superdesk/addons/superdesk_shopv2/core/mobile/order/refund.php **********/
}