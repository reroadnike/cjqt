<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/12/18
 * Time: 2:02 PM
 */

namespace Modules\Kafka\Http\Services;

//use Kafka;// 这个是懒的写法?
use Kafka\Config;
use Kafka\Exception;
use Kafka\Producer;
use Kafka\ProducerConfig;
use Kafka\Consumer;
use Kafka\ConsumerConfig;


//use Monolog\Handler\StdoutHandler;
//use Monolog\Logger;

class KafkaService
{
    public function __construct()
    {
        date_default_timezone_set('PRC');

    }

    /*
     * Produce
     * TODO 注意 测试服的kafka最大承受连续发送18次!!!!!!不确定是不是真的.但我本地发送第18次就崩了
     * TODO 案件重演: jd_vop_sdk 2825行 $this->_kafkaProduce->superdeskJdVopLogsHandler($log_data);
     * TODO 2019年2月14日 15:26:44 zjh
     * TODO 原方法 使用的是异步传递 屏蔽时间 2019年3月12日 17:46:05 zjh
     */
//    public function Producer($topic, $value, $key, $url)
//    {
//        $config = ProducerConfig::getInstance();
//        $config->setMetadataRefreshIntervalMs(10000);
//        $config->setMetadataBrokerList($url);
//        $config->setBrokerVersion('1.0.0');
//        $config->setRequiredAck(1);
//        $config->setIsAsyn(false);
//        $config->setProduceInterval(500);
////        $config->setSecurityProtocol(Config::SECURITY_PROTOCOL_SASL_SSL);
////        $config->setSaslMechanism(Config::SASL_MECHANISMS_SCRAM_SHA_256);
////        $config->setSaslUsername('nmred');
////        $config->setSaslPassword('123456');
////        $config->setSaslUsername('alice');
////        $config->setSaslPassword('alice-secret');
////        $config->setSaslKeytab('/etc/security/keytabs/kafkaclient.keytab');
////        $config->setSaslPrincipal('kafka/node1@NMREDKAFKA.COM');
//
//        // if use ssl connect
////        $config->setSslLocalCert('/home/vagrant/code/kafka-php/ca-cert');
////        $config->setSslLocalPk('/home/vagrant/code/kafka-php/ca-key');
////        $config->setSslPassphrase('123456');
////        $config->setSslPeerName('nmred');
//        $send_res = 'info';
//
//        try{
//            //TODO 这个闭包内外的$value 和 $key会不一样..第二次发送的是第一次发送的数据.
//            $producer = new Producer(function () use ($topic, $value, $key) {
//                return [
//                    [
//                        'topic' => $topic,
//                        'value' => $value,
//                        'key'   => $key,
//                    ],
//                ];
//            });
//            $producer->success(function ($result) use (&$send_res){
//                // TODO 成功时把日志记录到 elasticsearch
//                $send_res = 'success';
//                return "success";
//            });
//            $producer->error(function ($errorCode) use (&$send_res){
//                // TODO 错误时把日志记录到 elasticsearch
////                var_dump($errorCode);
////                echo '他喵的发不出去'.$errorCode, PHP_EOL, '<br/>';
////                echo date('H:i:s'), ' 当前内存使用情况: ', (memory_get_usage(true) / 1024 / 1024), " MB", PHP_EOL, '<br/>';
////                echo date('H:i:s'), " 内存使用峰值: ", (memory_get_peak_usage(true) / 1024 / 1024), " MB", PHP_EOL, '<br/>';
//
//                // 这边不要直接输出东西啦.....找死人了了
//                $send_res = 'error:'.$errorCode;
//            });
//            $producer->send(true);
//
//        }catch(Exception $e){
//            // TODO 这里应该处理异常...目前只是阻止抛出异常而导致的程序中断
//            return "exception";
//        }
//
//        return $send_res;
//    }

    /*
     * Produce
     * TODO 注意 测试服的kafka最大承受连续发送18次!!!!!!不确定是不是真的.但我本地发送第18次就崩了
     * TODO 案件重演: jd_vop_sdk 2825行 $this->_kafkaProduce->superdeskJdVopLogsHandler($log_data);
     * TODO 2019年2月14日 15:26:44 zjh
     * TODO 新方法 同步的方式传递 本地测试最多317条就会炸.
     */
    public function Producer($topic, $value, $key, $url)
    {
        $config = ProducerConfig::getInstance();
        $config->setMetadataRefreshIntervalMs(10000);
        $config->setMetadataBrokerList($url);
        $config->setBrokerVersion('1.0.0');
        $config->setRequiredAck(1);
        $config->setIsAsyn(false);
        $config->setProduceInterval(500);
        $send_res = 'info';

        try{
            //TODO 这个闭包内外的$value 和 $key会不一样..第二次发送的是第一次发送的数据.
            $producer = new Producer();

            $producer->send([
                [
                    'topic' => $topic,
                    'value' => $value,
                    'key'   => $key,
                ],
            ]);

        }catch(Exception $e){
            // TODO 这里应该处理异常...目前只是阻止抛出异常而导致的程序中断
            return "exception";
        }

        return $send_res;
    }


    /*
     * Consumer
     */
    public function consumer($groupId, $topics, $metadataBrokerList)
    {

        echo "转入处理程序...";
//        // Create the logger
//        $logger = new Logger('my_logger');
//        // Now add some handlers
//        $logger->pushHandler(new StdoutHandler());

        $config = ConsumerConfig::getInstance();


        $config->setMetadataRefreshIntervalMs(500);
        $config->setMetadataBrokerList($metadataBrokerList);
        $config->setGroupId($groupId);
        $config->setBrokerVersion('1.0.0');
        $config->setTopics([$topics]);
        $config->setOffsetReset('earliest');// #Kafka中没有初始偏移或如果当前偏移在服务器上不再存在时,默认区最新 ，有三个选项 【latest, earliest, none】


        $consumer = new Consumer();
//        $consumer->setLogger($logger);
        $consumer->start(function ($topic, $part, $message) {

//            echo "receive a message...".PHP_EOL;
//            /data/wwwroot/default/superdesk_boss/md/数据结构/Kafka-php_message.md


//            var_dump($message);
//            echo json_encode(json_decode($message['message']['value'],true),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
//
//            app(\Modules\Kafka\Http\Services\ConsumerService::class)->handle($message);  //你的接收处理逻辑

        });

    }
}