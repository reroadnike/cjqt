<?php

//mark kafka
require_once(IA_ROOT . '/addons/superdesk_shopv2_kafka/vendor/autoload.php');

use Modules\Kafka\Http\Services\ProduceService;

/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 2/12/18
 * Time: 11:20 AM
 */
class TransmitUnifiedOrderRefundService
{

    /**
     * 通过kafka请求
     *
     * @param $orderModel
     * @param $id
     */
    public function transmitOrderRefundToUnified($orderModel,$id)
    {
        return false;
        global $_W;

        //mark kafka
        if (isset($_W['config']['kafka']['unified_order_test']['enable']) && $_W['config']['kafka']['unified_order_test']['enable'] == 1) { //测试
//        if (isset($_W['config']['kafka']['unified_order']['enable']) && $_W['config']['kafka']['unified_order']['enable'] == 1) { //正式
            $order  = $orderModel->getOne($id);

            $kafka_data  = $orderModel->unifiedRefundOrderData($order['refundid']);
            ProduceService::getInstance()->unifiedOrderRefundHandler($kafka_data);
        }

    }
}