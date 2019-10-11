<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/29/17
 * Time: 4:46 AM
 */

global $_W, $_GPC;
$this->checkauth();
$totalprice = 0;
$allgoods = array();
$id = intval($_GPC['id']);
$optionid = intval($_GPC['optionid']);
$total = intval($_GPC['total']);
if ((empty($total)) || ($total < 1)) {
    $total = 1;
}
$direct = false; //是否是直接购买
$returnUrl = ''; //当前连接
if (!empty($id)) {
    $sql = 'SELECT `id`, `thumb`, `title`, `weight`, `marketprice`, `total`, `type`, `totalcnf`, `sales`, `unit`, `istime`, `timeend`, `usermaxbuy`
					FROM ' . tablename('superdesk_boardroom_4school_s_goods') . ' WHERE `id` = :id';
    $item = pdo_fetch($sql, array(':id' => $id));

    if (empty($item)) {
        message('商品不存在或已经下架', $this->createMobileUrl('detail', array('id' => $id)), 'error');
    }
    if ($item['istime'] == 1) {
        if (time() > $item['timeend']) {
            $backUrl = $this->createMobileUrl('detail', array('id' => $id));
            $backUrl = $_W['siteroot'] . 'app' . ltrim($backUrl, '.');
            message('抱歉，商品限购时间已到，无法购买了！', $backUrl, "error");
        }
    }
    if ($item['total'] - $total < 0) {
        message('抱歉，[' . $item['title'] . ']库存不足！', $this->createMobileUrl('confirm'), 'error');
    }

    if (!empty($optionid)) {
        $option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("superdesk_boardroom_4school_s_goods_option") . " where id=:id limit 1", array(":id" => $optionid));
        if ($option) {
            $item['optionid'] = $optionid;
            $item['title'] = $item['title'];
            $item['optionname'] = $option['title'];
            $item['marketprice'] = $option['marketprice'];
            $item['weight'] = $option['weight'];
        }
    }
    $item['stock'] = $item['total'];
    $item['total'] = $total;
    $item['totalprice'] = $total * $item['marketprice'];
    $allgoods[] = $item;
    $totalprice += $item['totalprice'];
    if ($item['type'] == 1) {
        $needdispatch = true;
    }
    $direct = true;

    // 检查用户最多购买数量
    $sql = 'SELECT SUM(`og`.`total`) AS `orderTotal` FROM ' . tablename('superdesk_boardroom_4school_s_order_goods') . ' AS `og` JOIN ' . tablename('superdesk_boardroom_4school_s_order') .
        ' AS `o` ON `og`.`orderid` = `o`.`id` WHERE `og`.`goodsid` = :goodsid AND `o`.`from_user` = :from_user';
    $params = array(':goodsid' => $id, ':from_user' => $_W['openid']);
    $orderTotal = pdo_fetchcolumn($sql, $params);
    if ((($orderTotal + $item['total']) > $item['usermaxbuy']) && (!empty($item['usermaxbuy']))) {
        message('您已经超过购买数量了', $this->createMobileUrl('detail', array('id' => $id)), 'error');
    }

    $returnUrl = urlencode($_W['siteurl']);
}
if (!$direct) {
    //如果不是直接购买（从购物车购买）
    $goodids = $_GPC['goodids'];
    $condition = empty($goodids) ? '' : 'AND id IN (' . $goodids . ")";
    $list = pdo_fetchall("SELECT * FROM " . tablename('superdesk_boardroom_4school_s_cart') . " WHERE  uniacid = '{$_W['uniacid']}' AND from_user = '{$_W['openid']}' {$condition}");
    if (!empty($list)) {
        foreach ($list as &$g) {
            $item = pdo_fetch("select id,thumb,title,weight,marketprice,total,type,totalcnf,sales,unit from " . tablename("superdesk_boardroom_4school_s_goods") . " where id=:id limit 1", array(":id" => $g['goodsid']));
            //属性
            $option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("superdesk_boardroom_4school_s_goods_option") . " where id=:id limit 1", array(":id" => $g['optionid']));
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
            $totalprice += $item['totalprice'];
            if ($item['type'] == 1) {
                $needdispatch = true;
            }
        }
        unset($g);
    }
    $returnUrl = $this->createMobileUrl("confirm");
}
if (count($allgoods) <= 0) {
    header("location: " . $this->createMobileUrl('myorder'));
    exit();
}

//配送方式
$dispatch = pdo_fetchall("select id,dispatchname,dispatchtype,firstprice,firstweight,secondprice,secondweight from " . tablename("superdesk_boardroom_4school_s_dispatch") . " WHERE uniacid = {$_W['uniacid']} order by displayorder desc");
foreach ($dispatch as &$d) {
    $weight = 0;
    foreach ($allgoods as $g) {
        $weight += $g['weight'] * $g['total'];
    }
    $price = 0;
    if ($weight <= $d['firstweight']) {
        $price = $d['firstprice'];
    } else {
        $price = $d['firstprice'];
        $secondweight = $weight - $d['firstweight'];
        if ($secondweight % $d['secondweight'] == 0) {
            $price += (int)($secondweight / $d['secondweight']) * $d['secondprice'];
        } else {
            $price += (int)($secondweight / $d['secondweight'] + 1) * $d['secondprice'];
        }
    }
    $d['price'] = $price;
}
unset($d);

if (checksubmit('submit')) {
    // 是否自提
    $sendtype = 1;
    $address = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE id = :id", array(':id' => intval($_GPC['address'])));
    if ($_GPC['goodstype'] != '2') {
        if (empty($address)) {
            message('抱歉，请您填写收货地址！');
        }
        // 运费
        $dispatchid = intval($_GPC['dispatch']);
        $dispatchprice = 0;
        foreach ($dispatch as $d) {
            if ($d['id'] == $dispatchid) {
                $dispatchprice = $d['price'];
                $sendtype = $d['dispatchtype'];
            }
        }
    } else {
        $sendtype = '3 ';
    }
    // 商品价格
    $goodsprice = 0;
    foreach ($allgoods as $row) {
        $goodsprice += $row['totalprice'];
    }

    $data = array(
        'uniacid' => $_W['uniacid'],
        'from_user' => $_W['openid'],
        'out_trade_no' => date('md') . random(4, 1),
        'price' => $goodsprice + $dispatchprice,
        'dispatchprice' => $dispatchprice,
        'goodsprice' => $goodsprice,
        'status' => 0,
        'sendtype' => intval($sendtype),
        'dispatch' => $dispatchid,
        'goodstype' => intval($item['type']),
        'remark' => $_GPC['remark'],
        'address' => $address['username'] . '|' . $address['mobile'] . '|' . $address['zipcode']
            . '|' . $address['province'] . '|' . $address['city'] . '|' .
            $address['district'] . '|' . $address['address'],
        'createtime' => TIMESTAMP
    );
    pdo_insert('superdesk_boardroom_4school_s_order', $data);
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
        $o = pdo_fetch("select title from " . tablename('superdesk_boardroom_4school_s_goods_option') . " where id=:id limit 1", array(":id" => $row['optionid']));
        if (!empty($o)) {
            $d['optionname'] = $o['title'];
        }
        pdo_insert('superdesk_boardroom_4school_s_order_goods', $d);
    }
    // 清空购物车
    if (!$direct) {
        pdo_delete("superdesk_boardroom_4school_s_cart", array("uniacid" => $_W['uniacid'], "from_user" => $_W['openid']));
    }
    // 变更商品库存
    if (empty($item['totalcnf'])) {
        $this->setOrderStock($orderid);
    }
    message('提交订单成功,现在跳转到付款页面...', $this->createMobileUrl('pay', array('orderid' => $orderid)), 'success');
}
$carttotal = $this->getCartTotal();
$profile = fans_search($_W['openid'], array('resideprovince', 'residecity', 'residedist', 'address', 'realname', 'mobile'));
$row = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE isdefault = 1 and uid = :uid limit 1", array(':uid' => $_W['member']['uid']));
include $this->template('confirm');