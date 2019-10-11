<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/29/18
 * Time: 5:45 PM
 *
 * http://192.168.1.124/superdesk/addons/superdesk_shopv2_kafka/test.php
 */

// 关闭错误报告
//error_reporting(0);

// 报告 runtime 错误
//error_reporting(E_ERROR | E_WARNING | E_PARSE);

// 报告所有错误
error_reporting(E_ALL);

// 等同 error_reporting(E_ALL);
ini_set("error_reporting", E_ALL);

// 报告 E_NOTICE 之外的所有错误
//error_reporting(E_ALL & ~E_NOTICE);


require '../vendor/autoload.php';

//echo __DIR__ . '/../vendor/autoload.php';

use Modules\Kafka\Http\Services\ProduceService;
$__produce__ = new ProduceService();
$__produce__->superdeskJdVopLogsHandler([
    'id' => '1'
]);