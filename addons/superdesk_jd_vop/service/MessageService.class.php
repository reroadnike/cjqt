<?php

//namespace sdk\jd_vop\service;
//use sdk\jd_vop\service;

include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');

include_once(IA_ROOT . '/addons/superdesk_jd_vop/sdk/jd_vop_sdk.php');

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/OrderService.class.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/PriceService.class.php');


include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/page_num.class.php');

include_once(IA_ROOT . '/addons/superdesk_shopv2/service/goods/ShopGoodsService.class.php');

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/area.class.php');


/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/23/17
 * Time: 4:57 PM
 */
class MessageService
{
    private $jd_sdk;
    private $_redis;

    private $_orderService;
    private $_priceService;

    private $_shopGoodsService;
    private $_page_numModel;

    private $_areaModel;

    function __construct()
    {
        $this->_redis = new RedisUtil();

        $this->jd_sdk        = new JDVIPOpenPlatformSDK();
        $this->jd_sdk->debug = false;
        $this->jd_sdk->init_access_token();

        $this->_orderService = new OrderService();
        $this->_priceService = new PriceService();

        $this->_shopGoodsService = new ShopGoodsService();
        $this->_page_numModel    = new page_numModel();

        $this->_areaModel = new areaModel();
    }

    const message_type_1 = 1;//--1 代表订单拆分变更{"id":推送id, "result" : {"pOrder" :父订单id} , "type": 1, "time":推送时间},注意：京东订单可能会被多次拆单； 例如：订单1 首先被拆成订单2、订单3；然后订单2有继续被拆成订单4、订单5；最终订单1的子单是订单3、订单4、订单5；每拆一次单我们都会发送一次拆单消息，但父订单号只会传递订单1（原始单），需要通过查询接口获取到最新所有子单，进行相关更新；
    const message_type_2 = 2;//--2 代表商品价格变更{"id":推送id, "result":{"skuId" : 商品编号 }, "type": 2, "time":推送时间},
    const message_type_4 = 4;//--4 商品上下架变更消息{"id":推送id, "result":{"skuId" : 商品编号 }, "type": 4 "time":推送时间},
    const message_type_5 = 5;//--5 代表该订单已妥投（买断模式代表外单已妥投或外单已拒收）{"id":推送id, "result":{"orderId":"京东订单编号", "state":"1是妥投，2是拒收"}, "type" : 5, "time":推送时间}，
    const message_type_6 = 6;//--6 代表添加、删除商品池内商品{"id":推送id, "result":{"skuId": 商品编号, "page_num":商品池编号, "state":"1添加，2删除"}, "type" : 6, "time":推送时间}，
    const message_type_10 = 10;//--10 代表订单取消（不区分取消原因）{"id":推送id, "result":{" orderId": 京东订单编号 }, "type" : 10, "time":推送时间}，
    const message_type_12 = 12;//--12 代表配送单生成（打包完成后推送，仅提供给买卖宝类型客户）{"id":推送id, "result":{" orderId": 京东订单编号 }, "type" : 12, "time":推送时间}，
    const message_type_13 = 13;//--13 换新订单生成（换新单下单后推送，仅提供给买卖宝类型客户）{"id":推送id, "result":{"afsServiceId": 服务单号, " orderId":换新订单号}, "type" : 13, "time":推送时间}
    const message_type_14 = 14;//--14 支付失败消息{"id":推送id, "result":{" orderId": 京东订单编号}, "type" : 14, "time":推送时间}
    const message_type_15 = 15;//--15 7天未支付取消消息/未确认取消（cancelType, 1: 7天未支付取消消息; 2: 未确认取消）{"id":推送id, "result":{"orderId": 京东订单编号, "cancelType": 取消类型}， "type" : 15, "time":推送时间}
    const message_type_16 = 16;//--16 商品介绍及规格参数变更消息{"id":推送id, "result":{"skuId" : 商品编号 } "type" : 16, "time":推送时间}}
    const message_type_17 = 17;//--17 赠品促销变更消息{"id":推送id, "result":{"skuId" : 商品编号 } "type" : 17, "time":推送时间}}
    const message_type_25 = 25;//--25 新订单消息{"id":推送id, "result":{"orderId":京东订单号, "pin":"京东账号"} "type" : 25, "time":推送时间(订单创建时间)}}
    const message_type_50 = 50;//--50 京东地址变更消息推送
//[{
//"id": "推送id",
//"result": {
//"areaId": "京东地址编码",
//"areaName": "京东地址名称",
//"parentId": "父京东ID编码",
//"areaLevel": “地址等级(行政级别：国家(1)、省(2)、市(3)、县(4)、镇(5))”,
//"operateType":”操作类型(插入数据为1，更新时为2，删除时为3)}”,
//"time":"消息推送时间",
//         “type":”消息类型”
//    }
//]

//如：
//[{"id":1468773,"result":{"areaId":36151,"areaName":"qunge_test","parentId":1930,"areaLevel":5,"operateType":3},"time":"2015-12-09 16:49:59","type":50},

    /**
     * 9.1  信息推送接口
     *
     * @param $type
     *
     * @return mixed
     */
    public function messageGet($type)
    {

        global $_W, $_GPC;

        $response = $this->jd_sdk->api_message_get($type);
        $response = json_decode($response, true);
        return $response;
    }

    /**
     * 9.2  根据推送id，删除推送信息接口
     *
     * @param $id
     *
     * @return mixed
     */
    public function messageDel($id)
    {
        global $_W, $_GPC;

        $response = $this->jd_sdk->api_message_del($id);
        $response = json_decode($response, true);

        echo(PHP_EOL . "DELETE:" . json_encode($response, JSON_UNESCAPED_UNICODE));

        return $response;
    }


    /**
     * --1代表订单拆分变更{"id":推送id, "result" : {"pOrder" :父订单id} , "type": 1, "time":推送时间},注意：京东订单可能会被多次拆单； 例如：订单1 首先被拆成订单2、订单3；然后订单2有继续被拆成订单4、订单5；最终订单1的子单是订单3、订单4、订单5；每拆一次单我们都会发送一次拆单消息，但父订单号只会传递订单1（原始单），需要通过查询接口获取到最新所有子单，进行相关更新；
     */
    public function messageType_1_Get()
    {
        global $_W, $_GPC;

        $result = $this->messageGet(MessageService::message_type_1);
//        die(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
//        {
//            "id": 3612991444,
//            "result": {
//                "pOrder": 70573771278
//            },
//            "time": "2017-12-21 18:08:01",
//            "type": 1
//        }

        if ($result['success'] == true) {
            foreach ($result['result'] as $index => $_message) {

                echo(json_encode($_message, JSON_UNESCAPED_UNICODE) . "  处理::");

                $isExist = $this->_orderService->checkOrderByjdOrderId($_message['result']['pOrder']);

                if ($isExist) {
                    echo PHP_EOL;
                    echo 'START====>';
                    echo PHP_EOL;
                    $this->_orderService->businessProcessingSelectJdOrder($_message['result']['pOrder']);
                    echo PHP_EOL;
                    echo '<======END';
                    echo PHP_EOL;
                    $this->messageDel($_message['id']);
                } else {
//                    $this->messageDel($_message['id']);
                    echo '本地库不存在,不是本系统订单 ';
                }

                echo PHP_EOL;


            }
        }
    }

    /**
     * --2代表商品价格变更{"id":推送id, "result":{"skuId" : 商品编号 }, "type": 2, "time":推送时间},
     */
    public function messageType_2_Get()
    {
        global $_W, $_GPC;

        $result = $this->messageGet(MessageService::message_type_2);
//        die(json_encode($result,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
//        {
//            "id": 4389650875,
//            "result": {
//                "price": "",
//                "skuId": 18188914506,
//                "jdPrice": 119
//            },
//            "time": "2018-02-22 13:43:50",
//            "type": 2
//        }
        if ($result['success'] == true) {

            $skuId = array();
            $delId = array();
            foreach ($result['result'] as $index => $_message) {
                echo(json_encode($_message, JSON_UNESCAPED_UNICODE) . "  处理::");

                if (strlen($_message['result']['skuId']) < 8) {

                    echo 'sku<8';
                    $skuId[] = $_message['result']['skuId'];
                    $delId[] = $_message['id'];
                    echo json_encode($_message['result']['skuId'], JSON_UNESCAPED_UNICODE);

                } elseif (strlen($_message['result']['skuId']) == 8) {

                    echo 'sku=8,ignore';
                    $this->messageDel($_message['id']);

                } elseif (strlen($_message['result']['skuId']) < 11
                    && strlen($_message['result']['skuId']) > 8) {

                    echo '11>sku>8,ignore';
                    $this->messageDel($_message['id']);

                } elseif (strlen($_message['result']['skuId']) == 11) {

                    echo 'sku=11';
                    $skuId[] = $_message['result']['skuId'];
                    $delId[] = $_message['id'];
                    echo json_encode($_message['result']['skuId'], JSON_UNESCAPED_UNICODE);

                } elseif (strlen($_message['result']['skuId']) == 12) {

                    echo 'sku=12';
                    $skuId[] = $_message['result']['skuId'];
                    $delId[] = $_message['id'];
                    echo json_encode($_message['result']['skuId'], JSON_UNESCAPED_UNICODE);

                }
                echo PHP_EOL;

            }

            echo PHP_EOL;
            echo 'START====>';
            echo PHP_EOL;
            echo 'update price :: ' . json_encode($skuId);
            $this->_priceService->getPrice($skuId);
            echo PHP_EOL;
            echo '<======END';
            echo PHP_EOL;

            echo PHP_EOL;
            echo 'START====>';
            echo PHP_EOL;
            foreach ($delId as $_message_id) {
                $this->messageDel($_message_id);
            }
            echo PHP_EOL;
            echo '<======END';
            echo PHP_EOL;


        }


    }

    /**
     * --4商品上下架变更消息{"id":推送id, "result":{"skuId" : 商品编号 }, "type": 4 "time":推送时间},
     */
    public function messageType_4_Get()
    {
        global $_W, $_GPC;


        $result = $this->messageGet(MessageService::message_type_4);

//        die(json_encode($result,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
//        {
//            "id": 4206002777,
//            "result": {
//                "state": 0,
//                "skuId": 4607021
//            },
//            "time": "2018-02-04 00:00:51",
//            "type": 4
//        }
        if ($result['success'] == true) {


            foreach ($result['result'] as $index => $_message) {

                $__start = microtime(true);

                echo(json_encode($_message, JSON_UNESCAPED_UNICODE) . "  处理::");

                if (strlen($_message['result']['skuId']) < 8) {

                    echo 'sku<8';
                    $this->messageType_4_Processing($_message);
                    $this->messageDel($_message['id']);

                } elseif (strlen($_message['result']['skuId']) == 8) {
                    echo 'sku=8,ignore';
                    $this->messageDel($_message['id']);

                } elseif (strlen($_message['result']['skuId']) < 11
                    && strlen($_message['result']['skuId']) > 8) {

                    echo '11>sku>8,ignore';
                    $this->messageDel($_message['id']);

                } elseif (strlen($_message['result']['skuId']) == 11) {

                    echo 'sku=11';
                    $this->messageType_4_Processing($_message);
                    $this->messageDel($_message['id']);

                } elseif (strlen($_message['result']['skuId']) == 12) {

                    echo 'sku=12';
                    $this->messageType_4_Processing($_message);
                    $this->messageDel($_message['id']);

                }

                $__end = microtime(true);
                echo '    耗时' . round($__end - $__start, 4) . '秒';
                echo PHP_EOL;


            }
        }
    }

    /**
     * 上下架
     *
     * @param $_message
     */
    public function messageType_4_Processing($_message)
    {

        $_cron_handle_task_02_sku_4_page_num = '_cron_handle_task_02_sku_4_page_num:' . $_W['uniacid'];

        $msg = $this->_shopGoodsService->updateByStatus($_message['result']);

        if ($msg) {
            echo $msg;
        } else {

            if ($_message['result']['state'] == 1) {

                $task_sku_dto = array(
                    'sku'       => $_message['result']['skuId'],
                    'page_num'  => 0,
                    'overwrite' => 1,
                );
                $this->_redis->rPush($_cron_handle_task_02_sku_4_page_num, json_encode($task_sku_dto));

                echo '本地库不存在,加入同步队列    ';
                echo json_encode($task_sku_dto, JSON_UNESCAPED_UNICODE);
            } else {
                echo '本地库不存在且下架,消息删除';
            }
        }
    }

    /**
     * --5代表该订单已妥投（买断模式代表外单已妥投或订单已拒收）{"id":推送id, "result":{"orderId":"京东订单编号", "state":"1是妥投，2是拒收"}, "type" : 5, "time":推送时间}，
     */
    public function messageType_5_Get()
    {
        global $_W, $_GPC;

        $result = $this->messageGet(MessageService::message_type_5);
//        die(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
//        {
//            "id": 3283471578,
//            "result": {
//                "state": 1,//1是妥投，2是拒收
//                "orderId": 69186727540
//            },
//            "time": "2017-11-25 09:34:30",
//            "type": 5
//        }

        if ($result['success'] == true) {

            foreach ($result['result'] as $index => $_message) {

                echo(json_encode($_message, JSON_UNESCAPED_UNICODE) . "  处理::");

                // check jd_vop_submit_order
                $isExist = $this->_orderService->checkOrderByjdOrderId($_message['result']['orderId']);

                if ($isExist) {
                    echo PHP_EOL;
                    echo 'START====>';
                    echo PHP_EOL;

                    if($_message['result']['state'] == 1) {

                        $result_cancel = $this->_orderService->businessProcessingCompleteJdOrder($_message['result']['orderId']);

                        if ($result_cancel['success']) {
                            echo PHP_EOL;
                            $this->messageDel($_message['id']);
                            echo(json_encode($_message, JSON_UNESCAPED_UNICODE) . "  清理");
                        }

                    } else if($_message['result']['state'] == 2){

                        $result_cancel = $this->_orderService->businessProcessingCancelJdOrder($_message['result']['orderId'],'系统提交:京东订单已拒收');

                        if ($result_cancel['success']) {
                            echo PHP_EOL;
                            $this->messageDel($_message['id']);
                            echo(json_encode($_message, JSON_UNESCAPED_UNICODE) . "  清理");
                        }
                    }


                    echo PHP_EOL;
                    echo '<======END';
                    echo PHP_EOL;
                } else {

                    echo '本地库不存在,不是本系统订单 ';
                    $this->messageDel($_message['id']);
                }

                echo PHP_EOL;


            }
        }
    }

    /**
     * --6代表添加、删除商品池内商品{"id":推送id, "result":{"skuId": 商品编号, "page_num":商品池编号, "state":"1添加，2删除"}, "type" : 6, "time":推送时间}，
     */
    public function messageType_6_Get()
    {
        global $_W, $_GPC;

        $result = $this->messageGet(MessageService::message_type_6);

//        die(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
//        {
//            "id": 3012276727,
//            "result": {
//                "skuId": 5811452,
//                "page_num": 136000701,
//                "state": 1
//            },
//            "time": "2017-11-06 11:15:14",
//            "type": 6
//        }


        if ($result['success'] == true) {

            foreach ($result['result'] as $index => $_message) {

                echo json_encode($_message, JSON_UNESCAPED_UNICODE) . "  处理::";

                if (strlen($_message['result']['skuId']) < 8) {

                    echo 'sku<8';
                    $this->messageType_6_Processing($_message);
                    $this->messageDel($_message['id']);

                } elseif (strlen($_message['result']['skuId']) == 8) {

                    echo 'sku=8,ignore';
                    $this->messageDel($_message['id']);

                } elseif (strlen($_message['result']['skuId']) < 11
                    && strlen($_message['result']['skuId']) > 8) {

                    echo '11>sku>8,ignore';
                    $this->messageDel($_message['id']);

                } elseif (strlen($_message['result']['skuId']) == 11) {

                    echo 'sku=11';
                    $this->messageType_6_Processing($_message);
                    $this->messageDel($_message['id']);

                } elseif (strlen($_message['result']['skuId']) == 12) {

                    echo 'sku=12';
                    $this->messageType_6_Processing($_message);
                    $this->messageDel($_message['id']);

                }

                echo PHP_EOL;


            }
        }
    }

    public function messageType_6_Processing(array $_message)
    {

        $_cron_handle_task_02_sku_4_page_num = '_cron_handle_task_02_sku_4_page_num:' . $_W['uniacid'];

        // 检查本地商品池 扩展功能 主要业务模块 - 超级前台-京东VOP平台 京东商品池策略管理
        $_check_one = $this->_page_numModel->getOneByColumn(array(
            'state'    => 1, // state [0:排除 1:同步]
            'deleted'  => 0, // deleted [0:可用 1:删除]
            'page_num' => $_message['result']['page_num'],
        ));

        // "state":"1添加，2删除"
        if ($_check_one) {
            // 添加
            if ($_message['result']['state'] == 1) {

                $task_sku_dto = array(
                    'sku'       => $_message['result']['skuId'],
                    'page_num'  => $_message['result']['page_num'],
                    'overwrite' => 1,
                );

                $this->_redis->rPush($_cron_handle_task_02_sku_4_page_num, json_encode($task_sku_dto));
                echo '加入同步队列 ';

            } // 删除
            elseif ($_message['result']['state'] == 2) {

                $task_sku_dto = array(
                    "state" => 0,
                    "skuId" => $_message['result']['skuId'],
                );

                $msg = $this->_shopGoodsService->updateByStatus($task_sku_dto);
                echo json_encode($msg, JSON_UNESCAPED_UNICODE);
            }
        } else {
            echo '商品池:' . $_message['result']['page_num'] . '为不同步或已删除状态 ';
        }

    }

    /**
     * --10代表订单取消（不区分取消原因）{"id":推送id, "result":{" orderId": 京东订单编号 }, "type" : 10, "time":推送时间}，
     */
    public function messageType_10_Get()
    {
        global $_W, $_GPC;

        $result = $this->messageGet(MessageService::message_type_10);
//        die(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
//        {
//            "id": 3172920363,
//            "result": {
//                "orderId": 64774258846
//            },
//            "time": "2017-11-15 09:37:34",
//            "type": 10
//        }


        if ($result['success'] == true) {

            foreach ($result['result'] as $index => $_message) {

                echo(json_encode($_message, JSON_UNESCAPED_UNICODE) . "  处理::");

                $isExist = $this->_orderService->checkOrderByjdOrderId($_message['result']['orderId']);

                if ($isExist) {
                    echo PHP_EOL;
                    echo 'START====>';
                    $result_cancel = $this->_orderService->businessProcessingCancelJdOrder($_message['result']['orderId'],'系统提交:京东订单取消(不区分取消原因)');

                    if ($result_cancel['success']) {

                        echo PHP_EOL;
                        $this->messageDel($_message['id']);
                        echo(json_encode($_message, JSON_UNESCAPED_UNICODE) . "  清理");
                    }

                    echo PHP_EOL;
                    echo '<======END';
                    echo PHP_EOL;


                } else {
                    echo '本地库不存在,不是本系统订单 ';
                    $this->messageDel($_message['id']);
                }

                echo PHP_EOL;
                echo PHP_EOL;
                echo PHP_EOL;
                echo PHP_EOL;

            }
        }
    }

    /**
     * --12 代表配送单生成（打包完成后推送，仅提供给买卖宝类型客户）{"id":推送id, "result":{" orderId": 京东订单编号 }, "type" : 12, "time":推送时间}，
     */
    public function messageType_12_Get()
    {
        global $_W, $_GPC;

        $result = $this->messageGet(MessageService::message_type_12);

        die(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * --13 换新订单生成（换新单下单后推送，仅提供给买卖宝类型客户）{"id":推送id, "result":{"afsServiceId": 服务单号, " orderId":换新订单号}, "type" : 13, "time":推送时间}
     */
    public function messageType_13_Get()
    {
        global $_W, $_GPC;

        $result = $this->messageGet(MessageService::message_type_13);

        die(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * --14 支付失败消息{"id":推送id, "result":{" orderId": 京东订单编号}, "type" : 14, "time":推送时间}
     */
    public function messageType_14_Get()
    {
        global $_W, $_GPC;

        $result = $this->messageGet(MessageService::message_type_14);

        die(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * --15 7天未支付取消消息/未确认取消（cancelType, 1: 7天未支付取消消息; 2: 未确认取消）{"id":推送id, "result":{"orderId": 京东订单编号, "cancelType": 取消类型}， "type" : 15, "time":推送时间}
     */
    public function messageType_15_Get()
    {
        global $_W, $_GPC;

        $result = $this->messageGet(MessageService::message_type_15);

        die(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * --16 商品介绍及规格参数变更消息{"id":推送id, "result":{"skuId" : 商品编号 } "type" : 16, "time":推送时间}}
     */
    public function messageType_16_Get()
    {
        global $_W, $_GPC;


        $result = $this->messageGet(MessageService::message_type_16);
//        die(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
//        {
//            "id": 4229575447,
//            "result": {
//                 "skuId": 25536283206
//            },
//            "time": "2018-02-06 01:35:30",
//            "type": 16
//        }
        if ($result['success'] == true) {
            foreach ($result['result'] as $index => $_message) {

                echo json_encode($_message, JSON_UNESCAPED_UNICODE) . "  处理::";

                if (strlen($_message['result']['skuId']) < 8) {

                    echo 'sku<8';
                    $this->messageType_16_Processing($_message);
                    $this->messageDel($_message['id']);

                } elseif (strlen($_message['result']['skuId']) == 8) {

                    echo 'sku=8,ignore';
                    $this->messageDel($_message['id']);

                } elseif (strlen($_message['result']['skuId']) < 11
                    && strlen($_message['result']['skuId']) > 8) {

                    echo '11>sku>8,ignore';
                    $this->messageDel($_message['id']);

                } elseif (strlen($_message['result']['skuId']) == 11) {

                    echo 'sku=11';
                    $this->messageType_16_Processing($_message);
                    $this->messageDel($_message['id']);

                } elseif (strlen($_message['result']['skuId']) == 12) {

                    echo 'sku=12';
                    $this->messageType_16_Processing($_message);
                    $this->messageDel($_message['id']);

                }

                echo PHP_EOL;


            }
        }
    }

    public function messageType_16_Processing($_message)
    {

        $_cron_handle_task_02_sku_4_page_num = '_cron_handle_task_02_sku_4_page_num:' . $_W['uniacid'];

        $_check_one = $this->_shopGoodsService->getOneBySkuId($_message['result']['skuId']);

        if ($_check_one) {

            $task_sku_dto = array(
                'sku'       => $_message['result']['skuId'],
                'page_num'  => $_check_one['jd_vop_page_num'],
                'overwrite' => 1,
            );
            $this->_redis->rPush($_cron_handle_task_02_sku_4_page_num, json_encode($task_sku_dto));
            echo '加入同步队列 ';

        } else {

            $task_sku_dto = array(
                'sku'       => $_message['result']['skuId'],
                'page_num'  => 0,
                'overwrite' => 1,
            );
            $this->_redis->rPush($_cron_handle_task_02_sku_4_page_num, json_encode($task_sku_dto));

            echo '本地库不存在,加入同步队列';
            echo PHP_EOL;
            echo json_encode($task_sku_dto, JSON_UNESCAPED_UNICODE);

        }
    }

    /**
     * --17 赠品促销变更消息{"id":推送id, "result":{"skuId" : 商品编号 } "type" : 17, "time":推送时间}}
     */
    public function messageType_17_Get()
    {
        global $_W, $_GPC;

        $result = $this->messageGet(MessageService::message_type_17);

        die(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * --25新订单消息{"id":推送id, "result":{"orderId":京东订单号, "pin":"京东账号"} "type" : 25, "time":推送时间(订单创建时间)}}
     */
    public function messageType_25_Get()
    {
        global $_W, $_GPC;

        $result = $this->messageGet(MessageService::message_type_25);

        die(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * --50 京东地址变更消息推送 TODO
     * [
     * {
     * "id": "推送id",
     * "result": {
     * "areaId": "京东地址编码",
     * "areaName": "京东地址名称",
     *  "parentId": "父京东ID编码",
     * "areaLevel": “地址等级(行政级别：国家(1)、省(2)、市(3)、县(4)、镇(5))”,
     *  "operateType":”操作类型(插入数据为1，更新时为2，删除时为3)}”,
     *   "time":"消息推送时间",
     *   “type":”消息类型”
     * }
     * ]
     *
     * 如：
     * [{"id":1468773,"result":{"areaId":36151,"areaName":"qunge_test","parentId":1930,"areaLevel":5,"operateType":3},"time":"2015-12-09 16:49:59","type":50},
     */
    public function messageType_50_Get()
    {
        global $_W, $_GPC;

        $result = $this->messageGet(MessageService::message_type_50);

        //$result = $_GPC['__input'];
        //die(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        if ($result['success'] == true) {

            foreach ($result['result'] as $index => $_message) {

                echo(json_encode($_message, JSON_UNESCAPED_UNICODE) . "  处理::");

                echo PHP_EOL;
                echo 'START====>';
                echo PHP_EOL;

                $where = array('code' => $_message['result']['areaId']);

                // operateType - 操作类型
                // 插入数据为 1
                // 更新数据为 2
                if ($_message['result']['operateType'] == 1 || $_message['result']['operateType'] == 2) {

                    $data = array(
                        'code'        => $_message['result']['areaId'],
                        'parent_code' => $_message['result']['parentId'],
                        'level'       => $_message['result']['areaLevel'] - 2,
                        'text'        => $_message['result']['areaName'],
                    );
                    $this->_areaModel->saveOrUpdateByColumn($data, $where);

                } // 删除数据为3
                else if ($_message['result']['operateType'] == 3) {

                    $_is_exist = $this->_areaModel->getOneByColumn($where);

                    if ($_is_exist) {
                        $data = array(
                            'state'      => 0,
                            'updatetime' => strtotime('now'),
                        );
                        $ret  = pdo_update($this->_areaModel->table_name, $data, $where);
                    }

                }

                echo PHP_EOL;
                echo '<======END';
                echo PHP_EOL;

                echo PHP_EOL;

            }
        }
    }
}