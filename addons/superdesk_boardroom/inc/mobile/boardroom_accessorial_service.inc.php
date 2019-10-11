<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/27/17
 * Time: 6:00 AM
 */

global $_GPC, $_W;

$title = "会议室详情";

global $_W, $_GPC;
$this->checkAuth();
$op = $_GPC['op'];
if ($op == 'add') {
    $goodsid = intval($_GPC['id']);
    $total = intval($_GPC['total']);
    $total = empty($total) ? 1 : $total;
    $optionid = intval($_GPC['optionid']);
    $goods = pdo_fetch("SELECT id, type, total,marketprice,maxbuy FROM " . tablename('superdesk_boardroom_s_goods') . " WHERE id = :id", array(':id' => $goodsid));
    if (empty($goods)) {
        $result['message'] = '抱歉，该商品不存在或是已经被删除！';
        message($result, '', 'ajax');
    }
    $marketprice = $goods['marketprice'];
    if (!empty($optionid)) {
        $option = pdo_fetch("select marketprice from " . tablename('superdesk_boardroom_s_goods_option') . " where id=:id limit 1", array(":id" => $optionid));
        if (!empty($option)) {
            $marketprice = $option['marketprice'];
        }
    }
    $row = pdo_fetch("SELECT id, total FROM " . tablename('superdesk_boardroom_s_cart') . " WHERE from_user = :from_user AND uniacid = '{$_W['uniacid']}' AND goodsid = :goodsid  and optionid=:optionid", array(':from_user' => $_W['openid'], ':goodsid' => $goodsid, ':optionid' => $optionid));
    if ($row == false) {
        //不存在
        $data = array(
            'uniacid' => $_W['uniacid'],
            'goodsid' => $goodsid,
            'goodstype' => $goods['type'],
            'marketprice' => $marketprice,
            'from_user' => $_W['openid'],
            'total' => $total,
            'optionid' => $optionid
        );
        pdo_insert('superdesk_boardroom_s_cart', $data);
    } else {
        //累加最多限制购买数量
        $t = $total + $row['total'];
        if (!empty($goods['maxbuy'])) {
            if ($t > $goods['maxbuy']) {
                $t = $goods['maxbuy'];
            }
        }
        //存在
        $data = array(
            'marketprice' => $marketprice,
            'total' => $t,
            'optionid' => $optionid
        );
        pdo_update('superdesk_boardroom_s_cart', $data, array('id' => $row['id']));
    }
    //返回数据
    $carttotal = $this->getCartTotal();
    $result = array(
        'result' => 1,
        'total' => $carttotal
    );
    die(json_encode($result));
} else if ($op == 'clear') {

    pdo_delete('superdesk_boardroom_s_cart', array(
        'from_user' => $_W['openid'],
        'uniacid' => $_W['uniacid'])
    );

    die(json_encode(array("result" => 1)));

} else if ($op == 'remove') {
    $id = intval($_GPC['id']);

    pdo_delete('superdesk_boardroom_s_cart', array(
        'from_user' => $_W['openid'],
        'uniacid' => $_W['uniacid'], 'id' => $id)
    );

    die(json_encode(array("result" => 1, "cartid" => $id)));

} else if ($op == 'update') {

    $id = intval($_GPC['id']);
    $num = intval($_GPC['num']);

    $sql = "update " . tablename('superdesk_boardroom_s_cart') . " set total=$num where id=:id";
    pdo_query($sql, array(":id" => $id));

    die(json_encode(array("result" => 1)));

} else {

    // init cart start

    $pindex = max(1, intval($_GPC['page']));
    $psize = 15;
    $condition = ' WHERE `uniacid` = :uniacid AND `deleted` = :deleted';
    $params = array(':uniacid' => $_W['uniacid'], ':deleted' => '0');

    $sql = 'SELECT COUNT(*) FROM ' . tablename('superdesk_boardroom_s_goods') . $condition;
    $total = pdo_fetchcolumn($sql, $params);
    if (!empty($total)) {
        $sql =
             ' SELECT * '
            .' FROM ' . tablename('superdesk_boardroom_s_goods')
            .$condition
            . ' ORDER BY `status` DESC, `displayorder` DESC,
						`id` DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
        $list = pdo_fetchall($sql, $params);
//        $pager = pagination($total, $pindex, $psize);



        foreach ($list as $index => $_goods){
            $goodsid = $_goods['id'];
            $total = 0;
            $total = empty($total) ? 0 : $total;
            $optionid = intval($_GPC['optionid']);
            $goods = pdo_fetch("SELECT id, type, total,marketprice,maxbuy FROM " . tablename('superdesk_boardroom_s_goods') . " WHERE id = :id", array(':id' => $goodsid));
            if (empty($goods)) {
                $result['message'] = '抱歉，该商品不存在或是已经被删除！';
                message($result, '', 'ajax');
            }
            $marketprice = $goods['marketprice'];
//            if (!empty($optionid)) {
//                $option = pdo_fetch("select marketprice from " . tablename('superdesk_boardroom_s_goods_option') . " where id=:id limit 1", array(":id" => $optionid));
//                if (!empty($option)) {
//                    $marketprice = $option['marketprice'];
//                }
//            }

            $params = array(
                ':uniacid' => $_W['uniacid'],
                ':from_user' => $_W['openid'],
                ':goodsid' => $goodsid,
                ':optionid' => $optionid,
            );

//            var_dump($params);
            $row = pdo_fetch("SELECT id, total FROM " . tablename('superdesk_boardroom_s_cart') . " WHERE from_user = :from_user AND uniacid = :uniacid AND goodsid = :goodsid  and optionid=:optionid",
                $params);


//            var_dump($row);

            if ($row == false) {
                //不存在
                $data = array(
                    'uniacid' => $_W['uniacid'],
                    'goodsid' => $goodsid,
                    'goodstype' => $goods['type'],
                    'marketprice' => $marketprice,
                    'from_user' => $_W['openid'],//$_W['openid'],
                    'total' => $total,
                    'optionid' => $optionid
                );
                pdo_insert('superdesk_boardroom_s_cart', $data);
            } else {
                //累加最多限制购买数量
                $t = $total + $row['total'];
//                $t = 1;
                if (!empty($goods['maxbuy'])) {
                    if ($t > $goods['maxbuy']) {
                        $t = $goods['maxbuy'];
                    }
                }
                //存在
                $data = array(
                    'marketprice' => $marketprice,
                    'total' => $t,
                    'optionid' => $optionid
                );
                pdo_update('superdesk_boardroom_s_cart', $data, array('id' => $row['id']));
            }
        }
    }

    // init cart end


    $cart_params = array(
        ':uniacid' => $_W['uniacid'],
        ':from_user' => $_W['openid']//$_W['openid'],
        );

    $cart_list = pdo_fetchall("SELECT * FROM " . tablename('superdesk_boardroom_s_cart') . " WHERE  uniacid = :uniacid AND from_user = :from_user",$cart_params);
    $totalprice = 0;

    if (!empty($cart_list)) {
        foreach ($cart_list as &$item) {
            $goods = pdo_fetch("SELECT  title, thumb, marketprice, unit, total,maxbuy FROM " . tablename('superdesk_boardroom_s_goods') . " WHERE id=:id limit 1", array(":id" => $item['goodsid']));
            //属性
//            $option = pdo_fetch("select title,marketprice,stock from " . tablename("superdesk_boardroom_s_goods_option") . " where id=:id limit 1", array(":id" => $item['optionid']));
//            if ($option) {
//
//                $goods['title'] = $goods['title'];
//                $goods['optionname'] = $option['title'];
//                $goods['marketprice'] = $option['marketprice'];
//                $goods['total'] = $option['stock'];
//
//            }

            $item['goods'] = $goods;
            $item['totalprice'] = (floatval($goods['marketprice']) * intval($item['total']));
            $totalprice += $item['totalprice'];
        }
        unset($item);
    }
//    include $this->template('cart');
    include $this->template('boardroom_accessorial_service');
}

