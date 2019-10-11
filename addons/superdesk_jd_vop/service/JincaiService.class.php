<?php


//namespace sdk\jd_vop\service;
//use sdk\jd_vop\service;
//        include(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/sdk/jd_vop_sdk.php');


/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/21/17
 * Time: 8:33 PM
 */
class JincaiService
{

    private $jd_sdk;
    private $_redis;

    function __construct()
    {
//        $this->_redis     = new RedisUtil();
//        $this->_areaModel = new areaModel();
        $this->jd_sdk     = new JDVIPOpenPlatformSDK();
        $this->jd_sdk->debug = false;
        $this->jd_sdk->init_access_token();
    }

    /**
     *
     * 获取金彩账单明细接口
     *
     * 错误码
     * 9000	操作成功 | 查询相关接口，调用成功时返回
     * 9001	获取用户 pin 失败 | 获取用户 pin 失败
     * 9002	该 pin 下未开通金采或者客户是老金采， 不适用该接口 | 该 pin 下未开通金采或者客户是老金采， 不适用该接口
     * 9003	账单 pin 校验失败！ | 账单 pin 校验失败！
     * 9004	订单 pin 校验失败！	| 订单 pin 校验失败！
     *
     * 不同的传参的返回数据差别:
     * 1 账单号 billId 为空，订单号 orderId 为空：baseData、billInfo 详见 获取金彩账单明细.type1.json
     * 2 账单号 billId 不为空，订单号 orderId 为空：baseData、billDetail、orderStatusDetail 详见 获取金彩账单明细.type2.json
     * 3 账单号 billId 不为空，订单号 orderId 不为空：baseData、orderDetail 详见 获取金彩账单明细.type3.json
     * 4 账单号 billId 为空，订单号 orderId 不为空：baseData、orderDetail 详见 获取金彩账单明细.type4.json
     *
     * 具体返回结构参见:获取金彩账单明细.json
     *
     */
    public function getBillDetail(
        $billId   = '', /* 账单号 */
        $orderId  = '', /* 订单号 */
        $pageNo   = 1, /* 页码 */
        $pageSize = 10 /* 条数 */)
    {

//        socket_log($skuNums);

        $response = $this->jd_sdk->decare_http_json_jincai_get_bill_detail($billId, $orderId, $pageNo, $pageSize);

//        =====$response=====
//        除了情况2以外,为空的时候都会返回这样的结果...贼鸡儿空..
//        {
//          "code": 200
//        }
        $response = json_decode($response , true);

        if(!isset($response['resultCode'])){
            return $this->message(false,'数据为空');
        }

        if($response['resultCode'] != 9000){
            return $this->message(false,$response['resultMessage'] ? $response['resultMessage'] : '数据为空');
        }

        return $response;
    }


}