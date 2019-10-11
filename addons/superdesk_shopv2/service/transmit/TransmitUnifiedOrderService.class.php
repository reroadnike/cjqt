<?php

//mark kafka
require_once(IA_ROOT . '/addons/superdesk_shopv2_kafka/vendor/autoload.php');

use Modules\Kafka\Http\Services\ProduceService;

include_once(IA_ROOT . '/framework/library/logs/LogsUtil.class.php');

/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 2/12/18
 * Time: 11:20 AM
 */
class TransmitUnifiedOrderService
{
    public $_orderModel = null;

    public function transmitOrderToUnified($orderModel,$column)
    {
        $this->_orderModel = $orderModel;

        if(empty($column) || !is_array($column)){
            return false;
        }

        if(key_exists('id',$column)){
            $this->handleOrder($column['id']);
        }else{
            $orderList  = $this->_orderModel->queryAllByColumn($column);
            $orderIds = array_column($orderList,'id');
            foreach($orderIds as $k => $v){
                $this->handleOrder($v);
            }
        }

    }

    /**
     * 通过kafka请求
     *
     * @param $id
     * @param $isApi
     */
    public function handleOrder($id,$isApi = 0){
        global $_W;

        //日志记录
        LogsUtil::logging('info', json_encode('统一订单中心进入,id:'.$id, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), 'kafka_unified');

        //mark kafka
//        if (isset($_W['config']['kafka']['unified_order_test']['enable']) && $_W['config']['kafka']['unified_order_test']['enable'] == 1) { //测试
        if (isset($_W['config']['kafka']['unified_order']['enable']) && $_W['config']['kafka']['unified_order']['enable'] == 1) { //正式
            $kafka_data  = $this->_orderModel->unifiedOrderData($id);


            $send_result = ProduceService::getInstance()->unifiedOrderHandler($kafka_data);

            $topic = $_W['config']['kafka']['unified_order']['topics'];
            $url   = $_W['config']['kafka']['unified_order']['ip'];
            $send_result = $send_result . '---' . $topic . '---' . $url;

            //日志记录
            LogsUtil::logging($send_result, json_encode($kafka_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), 'kafka_unified');
        }

        if($isApi){
            $kafka_data  = $this->_orderModel->unifiedOrderData($id);

            echo '订单ID: ' . $kafka_data['orderid'] . '#';
            echo ' #[goods] ' . (empty($kafka_data['goods']) ? '空' : '有');
            echo ' #[order_type] ' . $kafka_data['order_type'];
            echo ' #[invoiceType] ' . $kafka_data['invoiceType'];
            echo ' #[invoiceTypeTTTTT] ' . gettype($kafka_data['invoiceType']);



            $headers = array(
                //正式
                'token:eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxIiwiaWF0IjoxNTUxMzQxMjE3LCJleHAiOjE1NTE0Mjc2MTd9.HMA-ANagaZoHQqdqkJwoExGtSGgkoUEJIYvpqsCYADk'

                //章银本地
//                'token:eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxIiwiaWF0IjoxNTUxMzM1NjIxLCJleHAiOjE1NTE5NDA0MjF9.no6uclOYHkoMs2AfHtlmlwEqGoD9kSzQURZ9XQmR-fE'
            );
            $post_data = array(
                'orderJsonStr' => json_encode($kafka_data)
            );

            //正式
            $url = 'http://120.79.31.216:8089/web/kafka/sendOrder';

            //章银本地
//            $url = 'http://192.168.1.148:8089/web/kafka/sendOrder';

            // 1. 初始化
            $ch = curl_init();
            // 2. 设置选项，包括URL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            // 3. 执行并获取HTML文档内容
            $output = curl_exec($ch);

            print_r(' java回传:');
            print_r($output);

//            print_r($output);die;
//        if($output === FALSE ){
//            echo "CURL Error:".curl_error($ch);
//        }
            // 4. 释放curl句柄
            curl_close($ch);

            echo '<br/>';

            //日志记录
            LogsUtil::logging('info', json_encode($kafka_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), 'api_unified');
        }

    }
}