<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/29/17
 * Time: 4:42 AM
 */

global $_W, $_GPC;
load()->func('tpl');
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

$core_user = $this->superdesk_core_user();
//var_dump($core_user);

if ($operation == 'display') {/*订单列表*/
    $pindex = max(1, intval($_GPC['page']));
    $psize = 15;
    $status = $_GPC['status'];
    $sendtype = !isset($_GPC['sendtype']) ? 0 : $_GPC['sendtype'];
    $condition = " o.uniacid = :uniacid";
    $paras = array(':uniacid' => $_W['uniacid']);

    if (empty($starttime) || empty($endtime)) {
        $starttime = strtotime('-1 month');
        $endtime = TIMESTAMP;
    }
    if (!empty($_GPC['time'])) {
        $starttime = strtotime($_GPC['time']['start']);
        $endtime = strtotime($_GPC['time']['end']) + 86399;
        $condition .= " AND o.createtime >= :starttime AND o.createtime <= :endtime ";
        $paras[':starttime'] = $starttime;
        $paras[':endtime'] = $endtime;
    }
    if (!empty($_GPC['paytype'])) {
        $condition .= " AND o.paytype = '{$_GPC['paytype']}'";
    } elseif ($_GPC['paytype'] === '0') {
        $condition .= " AND o.paytype = '{$_GPC['paytype']}'";
    }
    if (!empty($_GPC['keyword'])) {
        $condition .= " AND o.out_trade_no LIKE '%{$_GPC['keyword']}%'";
    }
    if (!empty($_GPC['member'])) {
        $condition .= " AND o.address LIKE '%{$_GPC['member']}%'";
    }
    if ($status != '') {
        $condition .= " AND o.status = '" . intval($status) . "'";
    }
    if (!empty($sendtype)) {
        $condition .= " AND o.sendtype = '" . intval($sendtype) . "' AND status != '3'";
    }


    if($core_user){
        $organization_code = isset($core_user['organization_code']) ? $core_user['organization_code'] : "" ;
        if (!empty($organization_code)) {
            $condition .= " AND `organization_code` = :organization_code ";
            $paras[':organization_code'] = $organization_code;
        }

        $virtual_code = isset($core_user['virtual_code']) ? $core_user['virtual_code'] : "" ;
        if (!empty($virtual_code)) {
            $condition .= " AND `virtual_code` = :virtual_code ";
            $paras[':virtual_code'] = $virtual_code;
        }
    }



    $sql = 'SELECT COUNT(*) FROM ' . tablename('superdesk_boardroom_4school_s_order') . ' AS `o` WHERE ' . $condition;
    $total = pdo_fetchcolumn($sql, $paras);
    if ($total > 0) {

        if ($_GPC['export'] != 'export') {
            $limit = ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
        }

        $sql = 'SELECT * FROM ' . tablename('superdesk_boardroom_4school_s_order') . ' AS `o` WHERE ' . $condition . ' ORDER BY
						`o`.`status` DESC, `o`.`createtime` DESC ' . $limit;
        $list = pdo_fetchall($sql,$paras);
        $pager = pagination($total, $pindex, $psize);

        $paytype = array (
            '0' => array('css' => 'default', 'name' => '未支付'),
            '1' => array('css' => 'danger','name' => '余额支付'),
            '2' => array('css' => 'info', 'name' => '在线支付'),
            '3' => array('css' => 'warning', 'name' => '货到付款'),
            '4' => array('css' => 'info', 'name' => '无需支付')
        );
        $orderstatus = array (
            '-1' => array('css' => 'default', 'name' => '已取消'),
            '0' => array('css' => 'danger', 'name' => '待付款'),
            '1' => array('css' => 'info', 'name' => '待发货'),
            '2' => array('css' => 'warning', 'name' => '待收货'),
            '3' => array('css' => 'success', 'name' => '已完成')
        );

        foreach ($list as &$value) {
            $s = $value['status'];
            $value['statuscss'] = $orderstatus[$value['status']]['css'];
            $value['status'] = $orderstatus[$value['status']]['name'];
            $value['dispatch'] = pdo_fetchcolumn("SELECT `dispatchname` FROM " . tablename('superdesk_boardroom_4school_s_dispatch') . " WHERE id = :id", array(':id' => $value['dispatch']));

            // 收货地址信息
            list($value['username'], $value['mobile'], $value['zipcode']) = explode('|', $value['address']);

            if ($s < 1) {
                $value['css'] = $paytype[$s]['css'];
                $value['paytype'] = $paytype[$s]['name'];
                continue;
            }
            $value['css'] = $paytype[$value['paytype']]['css'];
            if ($value['paytype'] == 2) {
                if (empty($value['transaction_id'])) {
                    $value['paytype'] = '支付宝支付';
                } else {
                    $value['paytype'] = '微信支付';
                }
            } else {
                $value['paytype'] = $paytype[$value['paytype']]['name'];
            }
        }

        if ($_GPC['export'] != '') {
            /* 输入到CSV文件 */
            $html = "\xEF\xBB\xBF";

            /* 输出表头 */
            $filter = array(
                'out_trade_no' => '订单号',
                'goods_title' => '商品',
                'username' => '姓名',
                'mobile' => '电话',
                'paytype' => '支付方式',
                'dispatch' => '配送方式',
                'dispatchprice' => '运费',
                'price' => '总价',
                'status' => '状态',
                'createtime' => '下单时间',
                'zipcode' => '邮政编码',
                'address' => '收货地址信息'
            );

            foreach ($filter as $key => $title) {
                $html .= $title . "\t,";
            }
            $html .= "\n";
            foreach ($list as $k => $v) {
                foreach ($filter as $key => $title) {
                    $good = pdo_get('superdesk_boardroom_4school_s_order_goods', array('orderid' => $v['id']));
                    $good = pdo_get('superdesk_boardroom_4school_s_goods', array('id' => $good['goodsid']));
                    $v['goods_title'] = $good['title'];
                    if ($key == 'createtime') {
                        $html .= date('Y-m-d H:i:s', $v[$key]) . "\t, ";
                    } elseif ($key == 'address') {
                        $address = explode('|', $v[$key]);
                        $html .= $address[0]. " ". $address[3].$address[4].$address[5].$address[6] . "\t, ";
                    } else {
                        $html .= $v[$key] . "\t, ";
                    }
                }
                $html .= "\n";
            }
            /* 输出CSV文件 */
            header("Content-type:text/csv");
            header("Content-Disposition:attachment; filename=全部数据.csv");
            echo $html;
            exit();

        }

    }
} elseif ($operation == 'detail') {/*订单明细*/

    $id = intval($_GPC['id']);
    $item = pdo_fetch(
        " SELECT * ".
        " FROM " . tablename('superdesk_boardroom_4school_s_order') .
        " WHERE id = :id AND uniacid = :uniacid", array(':id' => $id, ':uniacid' => $_W['uniacid']));
    if (empty($item)) {
        message("抱歉，订单不存在!", referer(), "error");
    }

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

    $dispatch = pdo_fetch("SELECT * FROM " . tablename('superdesk_boardroom_4school_s_dispatch') . " WHERE id = :id", array(':id' => $item['dispatch']));
    if (!empty($dispatch) && !empty($dispatch['express'])) {
        $express = pdo_fetch("select * from " . tablename('superdesk_boardroom_4school_s_express') . " WHERE id=:id limit 1", array(":id" => $dispatch['express']));
    }

    // 收货地址信息
    $item['user'] = explode('|', $item['address']);

    $goods = pdo_fetchall(
        " SELECT g.*, o.total,g.type,o.optionname,o.optionid,o.price as orderprice ".
        " FROM " . tablename('superdesk_boardroom_4school_s_order_goods') . " o ".
        " left join " . tablename('superdesk_boardroom_4school_s_goods') . " g on o.goodsid=g.id " .
        " WHERE o.orderid='{$id}'");

    $item['goods'] = $goods;
    
} elseif ($operation == 'delete') {/*订单删除*/

    $orderid = intval($_GPC['id']);
    if (pdo_delete('superdesk_boardroom_4school_s_order', array('id' => $orderid, 'uniacid' => $_W['uniacid']))) {
        message('订单删除成功', $this->createWebUrl('order', array('op' => 'display')), 'success');
    } else {
        message('订单不存在或已被删除', $this->createWebUrl('order', array('op' => 'display')), 'error');
    }
}
include $this->template('order');