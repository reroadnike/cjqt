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
class TransmitShopOrderGoodsService
{

    /**
     * 通过kafka请求
     *
     * @param $_order_goodsModel
     * @param $column
     */
    public function transmitShopOrderGoodsToBackup($_order_goodsModel,$column)
    {

        global $_W;

        //mark kafka
        if (isset($_W['config']['kafka']['backup']['enable']) && $_W['config']['kafka']['backup']['enable'] == 1) {
            $kafka_data  = $_order_goodsModel->queryAllByColumn($column);
            ProduceService::getInstance()->superdeskShopOrderGoodsHandler($kafka_data);
        }

    }
}