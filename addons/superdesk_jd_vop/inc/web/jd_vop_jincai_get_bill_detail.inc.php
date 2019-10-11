<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/03/30
 * Time: 15:24
 * http://192.168.1.124/smart_office_building/web/index.php?c=site&a=entry&m=superdesk_jd_vop&do=balance_detail
 */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/JincaiService.class.php');
$_jincaiService = new JincaiService();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'billList';

$billId  = $_GPC['billId'] ? $_GPC['billId'] : '';
$orderId = $_GPC['orderId'] ? $_GPC['orderId'] : '';
$page      = $_GPC['page'];
$page_size = 20;

$repayStatus = array(
    '5001' => '正常',
    '5002' => '延期',
    '5003' => '逾期',
    '5004' => '已结清',
    '5005' => '延期已结清',
    '5006' => '逾期已结清',
);
$billType = array(
    '5106' => '月结',
    '5170' => '周结',
    '5102' => '文档没写'
);
$orderStatus = array(
    '2500' => '未结清',
    '2501' => '已结清',
);
$isRightAll = array(
    0 => array('text'=>'错误','css'=>'#ff0500'),
    1 => array('text'=>'正确','css'=>'#333'),
);
$shopOrderStatus = array(
    -1 => '已关闭',
    0  => '待付款',
    1  => '待发货',
    2  => '待收货',
    3  => '已完成'
);

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/bill.class.php');
$_billModel = new billModel();

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/bill_order.class.php');
$_bill_orderModel = new bill_orderModel();

if ($op == 'billList') {
    //1 账单号 billId 为空，订单号 orderId 为空：baseData、billInfo 详见 获取金彩账单明细.type1.json

    $result = $_jincaiService->getBillDetail($billId, $orderId, $page, $page_size);

    $baseData = $result['result']['baseData'];
    $billInfo = $result['result']['billInfo'];

    $pager = pagination($billInfo['totalRecord'], $page, $page_size);

    $billInfoData = $billInfo['results'];

    foreach($billInfoData as $k => &$v){

        //数据库更新或保存
        $insert_data = $v;
        $insert_data['sumNrPri'] = $insert_data['nrRepayPri'];
        $insert_data['uniacid'] = $_W['uniacid'];
        unset($insert_data['repayStatus']);
        unset($insert_data['nrRepayPri']);
        $_billModel->saveOrUpdateByColumn($insert_data,$insert_data);


        $v['billStartDate'] = date('Y-m-d',$v['billStartDate'] / 1000);
        $v['billEndDate']   = date('Y-m-d',$v['billEndDate'] / 1000);
        $v['repayDate']     = date('Y-m-d',$v['repayDate'] / 1000);
        $v['repayStatus']   = $repayStatus[$v['repayStatus']];
    }
    unset($v);

    include $this->template('jincai_bill_list');

}elseif($op == 'billDetail'){
    //账单详情
    $billDetail = $_billModel->getOneByColumn(array('uniacid'=>$_W['uniacid'],'billNo'=>$billId));

    $billDetail['repayDate'] = date('Y-m-d',$billDetail['repayDate'] / 1000);
    $billDetail['delinDate'] = date('Y-m-d',$billDetail['delinDate'] / 1000);
    $billDetail['status']    = $repayStatus[$billDetail['status']];
    $billDetail['billType']  = $billType[$billDetail['billType']];

    //订单列表
    $where = array('billNo'=>$billId);

    $search = $_GPC['search'];

    if(isset($search['settleStatus']) && $search['settleStatus'] != -1){
        $where['settleStatus'] = intval($search['settleStatus']);
    }

    if(isset($search['isRight']) && $search['isRight'] != -1){
        $where['isRight'] = intval($search['isRight']);
    }

    $billOrderData = $_bill_orderModel->queryAll($where,$page,$page_size);

    $orderList = $billOrderData['data'];

    foreach($orderList as $k => &$v){

        //格式处理
        $v['settleDate']   = $v['settleDate'] ? date('Y-m-d',$v['settleDate'] / 1000) : 0;
        $v['settleStatus'] = $orderStatus[$v['settleStatus']];
        $v['shopOrderStatus'] = $shopOrderStatus[$v['shopOrderStatus']];
        $v['isRight'] = $isRightAll[$v['isRight']];

    }
    unset($v);

    $pager = pagination($billOrderData['total'], $page, $page_size);

    include $this->template('jincai_bill_detail');

}elseif($op == 'constant'){ //同步账单下所有订单数据 即请求京东接口.然后更新数据库
    @set_time_limit(0);
    @ini_set("max_execution_time", 0);
    ini_set("memory_limit", "1024M");  // 根据电脑配置不够继续增加

    //先查账单表
    include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/bill.class.php');
    $_billModel = new billModel();

    $databaseBill = $_billModel->getOneByColumn(array('uniacid'=>$_W['uniacid'],'billNo'=>$billId));

    //是否已经有在数据库保存
    if(!empty($databaseBill)){
        //有.则先判断一下是否已经结清
        if($databaseBill['status'] < 5004){
            //没结清,重新拉取并覆盖拉取的这部分数据
            $jdBillDetail = $_jincaiService->getBillDetail($billId, $orderId, $page, $page_size);

            $billDetail = $jdBillDetail['result']['billDetail'];
            $_billModel->updateByColumn($billDetail,array('uniacid'=>$_W['uniacid'],'billNo'=>$billId));
        }
    }else{
        //没找到.新增
        $jdBillDetail = $_jincaiService->getBillDetail($billId, $orderId, $page, $page_size);

        $billDetail = $jdBillDetail['result']['billDetail'];
        $_billModel->insert($billDetail);
    }


    $constPage = 1;
    $constPageSize = 100;
    $pageTotal = 0;

    //请求订单列表
    do {
        $result = $_jincaiService->getBillDetail($billId, $orderId, $constPage, $constPageSize);

        //抽出京东订单列表,订单总数
        $orderStatusDetail = $result['result']['orderStatusDetail'];
        $total = $orderStatusDetail['totalRecord'];
        $orderStatusDetailData = $orderStatusDetail['results'];

        foreach($orderStatusDetailData as $k => $v){
            //逐个查找数据库订单是否存在
            $databaseBillOrder = $_bill_orderModel->getOneByColumn(array('uniacid'=>$_W['uniacid'],'orderNo'=>$v['orderNo']));

            if(!empty($databaseBillOrder) && $databaseBillOrder['settleStatus'] == 2501){
                //有保存 已结清 跳过不处理
                continue;
            }else{
                //没保存或者没结清 更新或保存 输出
                $orderDetail = getOrderDetail($_jincaiService,$_bill_orderModel,$v['orderNo'],$databaseBillOrder);
            }
        }

        $pageTotal = ceil($total/$constPageSize);   //总页数
        $constPage++;   //当前页
    } while($pageTotal >= $constPage);

    $processing_end    = microtime(true);
    $processing = number_format($processing_end - STARTTIME, 3) . ' second(s)';

    message('同步成功！耗时:'.$processing, $this->createWebUrl('jd_vop_jincai_get_bill_detail', array('op' => 'billList')), 'success');
}

function getOrderDetail($_jincaiService,$_bill_orderModel,$orderId,$databaseBillOrder){
    //3 订单号 orderId 不为空：baseData、orderDetail 详见 获取金彩账单明细.type3.json

    global $_W;

    //请求京东接口 获取订单详情
    $result = $_jincaiService->getBillDetail('', $orderId);

    $orderDetail = $result['result']['orderDetail'];

    //假如数据库中没记录
    if(empty($databaseBillOrder)){
        //获取商城对应订单的编号,状态,价格,运费 保存
        $shopOrder = $_bill_orderModel->getOrderByJdOrderId($orderDetail['orderNo']);

        $orderDetail['shopOrderSn'] = $shopOrder['ordersn'];
        $orderDetail['shopOrderStatus'] = $shopOrder['status'];
        $orderDetail['shopOrderPrice'] = $shopOrder['costprice'];
        $orderDetail['orderFreight'] = $shopOrder['jd_vop_result_freight'];

        if($orderDetail['orderAmount'] == $orderDetail['shopOrderPrice'] || $orderDetail['orderAmount'] == $orderDetail['shopOrderPrice'] + $orderDetail['orderFreight']){
            $orderDetail['isRight'] = 1;
        }else{
            $orderDetail['isRight'] = 0;
        }

        $_bill_orderModel->insert($orderDetail);
    }else{
        //判断是否有变更.有则更新
        if(
            $orderDetail['orderAmount'] != $databaseBillOrder['orderAmount'] ||
            (!empty($orderDetail['settleDate']) && $orderDetail['settleDate'] != $databaseBillOrder['settleDate']) ||
            $orderDetail['settleStatus'] != $databaseBillOrder['settleStatus'] ||
            $orderDetail['sumRefundAmount'] != $databaseBillOrder['sumRefundAmount'] ||
            $orderDetail['customerName'] != $databaseBillOrder['customerName']
        ){

            $_bill_orderModel->updateByColumn($orderDetail,array('uniacid'=>$_W['uniacid'],'orderNo'=>$orderId));
        }
    }

    return true;
}