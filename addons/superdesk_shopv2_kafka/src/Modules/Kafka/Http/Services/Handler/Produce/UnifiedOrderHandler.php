<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/29/18
 * Time: 4:36 PM
 */

namespace Modules\Kafka\Http\Services\Handler\Produce;

use Modules\Kafka\Http\Services\KafkaService;

class UnifiedOrderHandler
{

//    public $value;
//    public function __call($name, $args)
//    {
//        array_unshift($args, $this->value);
//        $this->value = call_user_func_array($name, $args);
//        return $this;
//    }

    // TODO java那边的统一订单中心
    // 内部传入 转发到 青章银服务器的 kafka服务
    public function handle(KafkaService $kafka, array $value, $key = 'order')
    {
        global $_W;

        //统一订单中心 正式
        $topic = $_W['config']['kafka']['unified_order']['topics'];
        $url   = $_W['config']['kafka']['unified_order']['ip'];

        //统一订单中心 测试
//        $topic = $_W['config']['kafka']['unified_order_test']['topics'];
//        $url   = $_W['config']['kafka']['unified_order_test']['ip'];

//        $value =
//            [
//                'code'         => 'test',
//                'data_type'    => 'personal',
//                'action'       => 'update',
//                'data'         =>
//                    [
//                        'id'     => 1,
//                        'name'   => 'tom',
//                        'gender' => 2
//                    ],
//                'redirect_url' => '',
//                'operator'     => 'system',
//            ];
        $value = json_encode($value);

//        echo json_encode($value);

        $send_result = $kafka->Producer($topic, $value, $key, $url);

        return $send_result;

//        print_r(json_decode($value,true)['orderid']);echo PHP_EOL;

//        print_r(json_encode($value, JSON_FORCE_OBJECT));die;
    }
}