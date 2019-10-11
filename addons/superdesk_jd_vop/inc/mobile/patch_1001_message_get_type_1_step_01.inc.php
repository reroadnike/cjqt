<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 2/11/18
 * Time: 9:15 PM
 *
 * 福利内购
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=17&c=entry&m=superdesk_jd_vop&do=patch_1001_message_get_type_1_step_01
 * view-source:https://wxn.avic-s.com/app/index.php?i=17&c=entry&m=superdesk_jd_vop&do=patch_1001_message_get_type_1_step_01
 */

//--1代表订单拆分变更{"id":推送id, "result" : {"pOrder" :父订单id} , "type": 1, "time":推送时间},注意：京东订单可能会被多次拆单； 例如：订单1 首先被拆成订单2、订单3；然后订单2有继续被拆成订单4、订单5；最终订单1的子单是订单3、订单4、订单5；每拆一次单我们都会发送一次拆单消息，但父订单号只会传递订单1（原始单），需要通过查询接口获取到最新所有子单，进行相关更新；

global $_W, $_GPC;

//die;

//mark kafka 为了kafka转成了model执行
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order_goods.class.php');
$_order_goodsModel    = new order_goodsModel();

//将所有挂错单的单的id拉平
//先获取所有挂错的单
$list = pdo_fetchall(
    ' SELECT COUNT(createtime) as num ,createtime, FROM_UNIXTIME(createtime), GROUP_CONCAT(id) as ids,GROUP_CONCAT(expresssn) as expresssns ' .
    ' FROM ' . tablename('superdesk_shop_order')  .// TODO 标志 楼宇之窗 openid shop_order 不处理
    ' WHERE createtime > 1536681600  ' .
    '       and parentid = 0 ' .
    ' GROUP BY createtime HAVING num >1'
);

//单独取出ids这一列
$id_list = array_column($list,'ids');

//拉平ids
$last_id_list = array();
foreach($id_list as $v){
    $last_id_list = array_merge($last_id_list,explode(',',$v));
}


$express_list = array_column($list,'expresssns');
$last_express_list = array();
foreach($express_list as $v){
    $last_express_list = array_merge($last_express_list,explode(',',$v));
}
foreach($last_id_list as $v){

    // TODO 标志 楼宇之窗 openid shop_order 不处理
    //根据id获取该订单下所有order表中的openid与order_goods表中的openid对不上的商品
    $sql = ' SELECT o.id,o.openid,og.openid as ogopenid,o.ordersn,o.status,o.price,o.goodsprice,o.createtime,FROM_UNIXTIME(o.createtime),o.parentid,o.isparent,o.merchid,o.paytype,o.expresssn,og.goodsid,og.total,og.price as ogprice,og.parent_order_id,og.id as ogid ' .
        ' FROM ' . tablename('superdesk_shop_order') . ' as o ' .// TODO 标志 楼宇之窗 openid shop_order 不处理
        ' left join ' . tablename('superdesk_shop_order_goods') . ' as og on o.id = og.orderid and og.goodsid != 0 ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
        ' where o.openid != og.openid ' .
        '       and (o.id=:orderid or o.parentid=:orderid) ';

    $order = pdo_fetchall($sql, array(':orderid'=>$v));

    if(count($order) > 0){
        //遍历这些商品,查找到他们原本所应该挂的订单.
        echo '有openid不对称的情况';
        echo PHP_EOL;
        print_r($order);
        foreach($order as $vv){
            $check_old_order = pdo_fetch(
                ' SELECT id,status,ordersn FROM ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 不处理
                ' WHERE openid=:openid and createtime=:createtime ',
                array(':openid'=>$vv['ogopenid'],':createtime'=>$vv['createtime'])
            );
            echo '查找到原先的单';
            echo PHP_EOL;
            print_r($check_old_order);

            //假如订单为未发货..挂过去..
            if (!empty($check_old_order)) {
                //mark kafka 为了kafka转成了model执行
                $_order_goodsModel->updateByColumn(
                    array(
                        'orderid'          => $check_old_order['id'],
                        'parent_order_id' => 0
                    ),
                    array(
                        'id' => $vv['ogid']
                    )
                );
            } else {

            }
        }
    }
}

print_r($last_id_list);die;

//include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/MessageService.class.php');
//$_messageService = new MessageService();
//
//$_messageService->messageType_1_Get();

$end = microtime(true);
echo $msg . PHP_EOL;
echo '耗时' . round($end - STARTTIME, 4) . '秒' . PHP_EOL;