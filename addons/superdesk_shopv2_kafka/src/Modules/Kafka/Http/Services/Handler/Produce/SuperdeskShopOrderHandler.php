<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/29/18
 * Time: 4:36 PM
 */

namespace Modules\Kafka\Http\Services\Handler\Produce;

use Modules\Kafka\Http\Services\KafkaService;

class SuperdeskShopOrderHandler
{

//    public $value;
//    public function __call($name, $args)
//    {
//        array_unshift($args, $this->value);
//        $this->value = call_user_func_array($name, $args);
//        return $this;
//    }

    // TODO 处理京东日志发送的问题
    // 内部传入 转发到 测试服务器的 kafka服务
    public function handle(KafkaService $kafka, array $value, $key = 'ims_superdesk_shop_order')
    {
        global $_W;

        $topic = $_W['config']['kafka']['backup']['topics'];
        $url   = $_W['config']['kafka']['backup']['ip'];


//        $topic = 'test'; //配置在env中
//        $url   = '47.107.240.183:9092'; //配置在env中


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
        $value = json_encode($value, JSON_FORCE_OBJECT);

//        echo json_encode($value);

        $kafka->Producer($topic, $value, $key, $url);

    }
}