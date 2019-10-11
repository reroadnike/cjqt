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
class StockService
{

    private $jd_sdk;
    private $_redis;

    private $_areaModel;

    function __construct()
    {
//        $this->_redis     = new RedisUtil();
//        $this->_areaModel = new areaModel();
        $this->jd_sdk     = new JDVIPOpenPlatformSDK();
        $this->jd_sdk->debug = false;
        $this->jd_sdk->init_access_token();
    }

    /**
     * 6.2  批量获取库存接口（建议订单详情页、下单使用）
     *
     * @param $skuNums
     * @param $area
     *
     * @return mixed
     */
    public function getNewStockById($skuNums/*商品和数量 此是个String 再重构吧  [{skuId: 569172,num:101}]*/, $area/*格式：1_0_0 (分别代表1、2、3级地址)*/)
    {

//        socket_log($skuNums);

        $response = $this->jd_sdk->api_stock_get_new_stock_by_id($skuNums, $area);

//        =====$response=====
//        {"success":true,"resultMessage":"","resultCode":"0000","result":"[{\"skuId\":892900,\"areaId\":\"19_1607_3639_0\",\"stockStateId\":33,\"stockStateDesc\":\"有货\",\"remainNum\":-1},{\"skuId\":124157,\"areaId\":\"19_1607_3639_0\",\"stockStateId\":34,\"stockStateDesc\":\"无货\",\"remainNum\":-1},{\"skuId\":1037029,\"areaId\":\"19_1607_3639_0\",\"stockStateId\":33,\"stockStateDesc\":\"有货\",\"remainNum\":-1}]"}
//        {success: false, resultMessage: "3780980不在您的商品池中 ", resultCode: "0010", result: null, code: 200}

//        {
//            "success": false,
//            "resultMessage": "3780980不在您的商品池中 ",
//            "resultCode": "0010",
//            "result": null,
//            "code": 200
//        }
        $response = json_decode($response , true);

//        socket_log($response);

//        show_json(0,$response);

        if(isset($response['success'])){

            if($response['success'] == true){
                $result = json_decode($response['result'],true);

                $transform_result = array();

                foreach ($result as $item) {
                    $transform_result[$item['skuId']] = $item;
                }

                return $transform_result;

            } elseif ($response['success'] == false){

                $transform_result = array();
                $result = json_decode($skuNums,true);


                foreach ($result as $item) {

                    $item['stockStateDesc'] = $response['resultMessage'];
                    $item['stockStateId']   = 10; // 假的返回码 $response['resultCode'] /* 0010 */;
                    $item['remainNum']      = -1; // 为了拉平

                    $transform_result[$item['skuId']] = $item;
                }

                return $transform_result;

            }

        } else {
            return false;
        }
    }

    /**
     * 6.2  批量获取库存接口(建议商品列表页使用)
     *
     * @param $skuNums
     * @param $area
     *
     * @return mixed
     */
    public function getAllStockById($sku/*商品编号 批量以逗号分隔  (最高支持100个商品)*/, $area/*格式：1_0_0 (分别代表1、2、3级地址)*/)
    {

//        show_json(0,$skuNums);

        $response = $this->jd_sdk->api_stock_get_stock_by_id($sku, $area);

//        =====$response=====
//        {"success":true,"resultMessage":"","resultCode":"0000","result":"[{\"skuId\":892900,\"areaId\":\"19_1607_3639_0\",\"stockStateId\":33,\"stockStateDesc\":\"有货\",\"remainNum\":-1},{\"skuId\":124157,\"areaId\":\"19_1607_3639_0\",\"stockStateId\":34,\"stockStateDesc\":\"无货\",\"remainNum\":-1},{\"skuId\":1037029,\"areaId\":\"19_1607_3639_0\",\"stockStateId\":33,\"stockStateDesc\":\"有货\",\"remainNum\":-1}]"}

        $response = json_decode($response , true);

//        show_json(0,$response);

        if(isset($response['success']) && $response['success'] == true){

            $result = json_decode($response['result'],true);

            $transform_result = array();
            foreach ($result as $item) {
                $transform_result[$item['sku']] = $item;
            }

            return $transform_result;
//            return $result;

        } else {
            return false;
        }
    }


}