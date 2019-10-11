<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/27/17
 * Time: 5:54 AM
 */
global $_GPC, $_W;

$title  = "会议室详情";


/************ from 表单数据 ************/
$id = $_GPC['id'];

//echo $id;
//$select_time_bar = $_GPC['select_time_bar'];
//
//$order_goodsid = $_GPC['order_goodsid'];
//
//$date_start = $_GPC['date_start'];
//$date_end = $_GPC['date_end'];
//
//$time_start = $_GPC['time_start'];
//$time_end = $_GPC['time_end'];
/************ from 表单数据 ************/

// 改用local storage 后木有这个了 start
//$timestamp_date_start = strtotime($date_start);
//$timestamp_date_end = strtotime($date_end);
//
//$show_date_start = date('Y/m/d', $timestamp_date_start);
//$show_date_end = date('Y/m/d', $timestamp_date_end);
//
//$show_date = "";
//if($timestamp_date_start == $timestamp_date_end){
//    $show_date = $show_date_start;
//} else{
//    $show_date = $show_date_start . " - " . $show_date_end;
//}
// 改用local storage 后木有这个了 end

/************ init 页面数据 start ************/
$now = strtotime("+30 minutes", time());

include_once(MODULE_ROOT . '/model/boardroom.class.php');
$boardroom = new boardroomModel();

$_boardroom = $boardroom->getOne($id);

//echo json_encode($_boardroom);

$json_str = "{\"items\":[".iunserializer($_boardroom['equipment'])."]}";
$json = json_decode(htmlspecialchars_decode($json_str), true);
$_boardroom['equipment']  = $json['items'];
$_boardroom['carousel']   = iunserializer($_boardroom['carousel']);
$_boardroom['thumb']      = tomedia($_boardroom['thumb']);

/************ init 页面数据 end   ************/


/** 新版本不用这个了 **/
//$_boardroom['situation']  = $this->get_boardroom_situation($_boardroom['id'],$Ymd);
//
//$select_time_bar= array();
//foreach ($_boardroom['situation']['am'] as $index => $_item){
//    $_item['checked'] = 0;
//    $select_time_bar[] = $_item;
//}
//foreach ($_boardroom['situation']['pm'] as $index => $_item){
//    $_item['checked'] = 0;
//    $select_time_bar[] = $_item;
//}
//
//$select_time_bar = json_encode($select_time_bar);

//include $this->template('boardroom_select_time');
/** 新版本不用这个了 **/



/************ 附加服务 start ************/
// init cart start

$pindex = max(1, intval($_GPC['page']));
$psize = 15;
$condition = ' WHERE `uniacid` = :uniacid AND `deleted` = :deleted';
$params = array(':uniacid' => $_W['uniacid'], ':deleted' => '0');

$sql = 'SELECT COUNT(*) FROM ' . tablename('superdesk_boardroom_4school_s_goods') . $condition;
$total = pdo_fetchcolumn($sql, $params);
if (!empty($total)) {
    $sql =
        ' SELECT * '
        .' FROM ' . tablename('superdesk_boardroom_4school_s_goods')
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
        $goods = pdo_fetch("SELECT id, type, total,marketprice,maxbuy FROM " . tablename('superdesk_boardroom_4school_s_goods') . " WHERE id = :id", array(':id' => $goodsid));
        if (empty($goods)) {
            $result['message'] = '抱歉，该商品不存在或是已经被删除！';
            message($result, '', 'ajax');
        }
        $list[$index]['thumb'] = !empty($_goods['thumb'])?tomedia($_goods['thumb']):'';
        $marketprice = $goods['marketprice'];
//            if (!empty($optionid)) {
//                $option = pdo_fetch("select marketprice from " . tablename('superdesk_boardroom_4school_s_goods_option') . " where id=:id limit 1", array(":id" => $optionid));
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
        $row = pdo_fetch("SELECT id, total FROM " . tablename('superdesk_boardroom_4school_s_cart') . " WHERE from_user = :from_user AND uniacid = :uniacid AND goodsid = :goodsid  and optionid=:optionid",
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
            pdo_insert('superdesk_boardroom_4school_s_cart', $data);
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
            pdo_update('superdesk_boardroom_4school_s_cart', $data, array('id' => $row['id']));
        }
    }
}

// init cart end


$cart_params = array(
    ':uniacid' => $_W['uniacid'],
    ':from_user' => $_W['openid']
);

$cart_list = pdo_fetchall("SELECT * FROM " . tablename('superdesk_boardroom_4school_s_cart') . " WHERE  uniacid = :uniacid AND from_user = :from_user",$cart_params);
$totalprice = 0;

if (!empty($cart_list)) {
    foreach ($cart_list as &$cart_item) {
        $goods = pdo_fetch("SELECT  title, thumb, marketprice, unit, total,maxbuy FROM " . tablename('superdesk_boardroom_4school_s_goods') . " WHERE id=:id limit 1", array(":id" => $cart_item['goodsid']));
        //属性
//            $option = pdo_fetch("select title,marketprice,stock from " . tablename("superdesk_boardroom_4school_s_goods_option") . " where id=:id limit 1", array(":id" => $cart_item['optionid']));
//            if ($option) {
//
//                $goods['title'] = $goods['title'];
//                $goods['optionname'] = $option['title'];
//                $goods['marketprice'] = $option['marketprice'];
//                $goods['total'] = $option['stock'];
//
//            }

        $cart_item['goods'] = $goods;
        $cart_item['totalprice'] = (floatval($goods['marketprice']) * intval($cart_item['total']));
        $totalprice += $cart_item['totalprice'];
    }
    unset($cart_item);
}
//    include $this->template('cart');
//include $this->template('boardroom_accessorial_service');
/************ 附加服务 end ************/












include $this->template('boardroom_confirm');

