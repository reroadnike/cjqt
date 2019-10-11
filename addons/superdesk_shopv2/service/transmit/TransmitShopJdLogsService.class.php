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
class TransmitShopJdLogsService
{

    /**
     * 通过kafka请求
     *
     * @param $data
     */
    public function transmitShopJdLogsToBackup($data)
    {

        global $_W;

        //mark kafka
        if (isset($_W['config']['kafka']['backup']['enable']) && $_W['config']['kafka']['backup']['enable'] == 1) {
            ProduceService::getInstance()->superdeskJdVopLogsHandler($data);
        }

    }
}