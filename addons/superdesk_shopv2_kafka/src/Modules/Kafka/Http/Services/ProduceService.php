<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/12/18
 * Time: 2:04 PM
 */

namespace Modules\Kafka\Http\Services;

use Modules\Kafka\Http\Services\KafkaService;
use Modules\Kafka\Http\Services\Handler\Produce\SuperdeskJdVopLogsHandler;
use Modules\Kafka\Http\Services\Handler\Produce\SuperdeskShopOrderHandler;
use Modules\Kafka\Http\Services\Handler\Produce\SuperdeskShopOrderGoodsHandler;
use Modules\Kafka\Http\Services\Handler\Produce\UnifiedOrderHandler;
use Modules\Kafka\Http\Services\Handler\Produce\UnifiedOrderRefundHandler;

class ProduceService
{
    private static $kafka;

    private static $instance;

    public function __construct()
    {
        if (empty(self::$kafka)){
            self::$kafka = new KafkaService();
        }



    }

    public static function getInstance(){

        if(is_null(self::$instance)) {
            self::$instance = new static();

        }
        return self::$instance;
    }


    public function superdeskJdVopLogsHandler($value)
    {

        $value['createtime'] = strtotime('now');

        $__handle__ = new SuperdeskJdVopLogsHandler();
        $__handle__->handle(self::$kafka, $value);
    }

    public function superdeskShopOrdersHandler($value)
    {


        $__handle__ = new SuperdeskShopOrderHandler();
        $__handle__->handle(self::$kafka, $value);
    }

    public function superdeskShopOrderGoodsHandler($value)
    {


        $__handle__ = new SuperdeskShopOrderGoodsHandler();
        $__handle__->handle(self::$kafka, $value);
    }

    public function unifiedOrderHandler($value)
    {


        $__handle__ = new UnifiedOrderHandler();
        return $__handle__->handle(self::$kafka, $value);
    }

    public function unifiedOrderRefundHandler($value)
    {


        $__handle__ = new UnifiedOrderRefundHandler();
        $__handle__->handle(self::$kafka, $value);
    }

    // TODO ...

}