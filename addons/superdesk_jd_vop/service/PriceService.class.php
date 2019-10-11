<?php


//namespace sdk\jd_vop\service;
//use sdk\jd_vop\service;


include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/product_detail.class.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/product_price.class.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/balance_detail.class.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/balance_detail_processing.class.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/sdk/jd_vop_sdk.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/OrderService.class.php');

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/order_submit_order.class.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/order_submit_order_sku.class.php');

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/goods/goods.class.php');

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order_goods.class.php');

//include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order_refund.class.php');


include_once(IA_ROOT . '/addons/superdesk_shopv2/service/goods/ShopGoodsService.class.php');

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/BalanceDetailService.class.php');


/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/23/17
 * Time: 1:55 PM
 */
class PriceService
{
    private $jd_sdk;
    private $_redis;

//    private $_areaModel;
    private $_product_detailModel;
    private $_product_priceModel;
    private $_balance_detailModel;
    private $_balance_detail_processingModel;
    private $_balanceDetailService;

    private $_order_submit_orderModel;
    private $_order_submit_order_skuModel;

    private $_orderService;

    private $_goodsModel;

    private $_orderModel;
    private $_order_goodsModel;
//    private $_order_refundModel;

    private $_shopGoodsService;

    function __construct()
    {
        $this->_product_detailModel = new product_detailModel();
        $this->_product_priceModel  = new product_priceModel();
        $this->_balance_detailModel = new balance_detailModel();
        $this->_balance_detail_processingModel = new balance_detail_processingModel();
        $this->_balanceDetailService = new BalanceDetailService();

        $this->_orderService = new OrderService();

        $this->_orderModel        = new orderModel();
        $this->_order_goodsModel  = new order_goodsModel();
        $this->_order_refundModel = new order_refundModel();

        $this->_order_submit_orderModel     = new order_submit_orderModel();
        $this->_order_submit_order_skuModel = new order_submit_order_skuModel();

        $this->_goodsModel       = new goodsModel();
        $this->_shopGoodsService = new ShopGoodsService();

        $this->jd_sdk        = new JDVIPOpenPlatformSDK();
        $this->jd_sdk->debug = false;
        $this->jd_sdk->init_access_token();
    }


    /**
     * @param array $list
     * 数据结构为 $select_fields =
     * " id,title,thumb,marketprice,productprice,minprice,maxprice,isdiscount,isdiscount_time,isdiscount_discounts,sales,total,description,bargain,jd_vop_sku ";
     *
     * @return array
     */
    public function businessProcessingUpdateJdVopPriceForShopList($list = array())
    {
        if (empty($list)) {
            return array();
        }

        $skuArr = array();

        foreach ($list as $index => $item) {


            if ($item['jd_vop_sku'] != 0) { // 不为0才是京东商品
                $skuArr[$index] = $item['jd_vop_sku'];
            }
        }

        $api_price_result = $this->getPrice($skuArr);

//        socket_log('update price ::' . json_encode($skuArr));
//        socket_log('result price ::' . json_encode($api_price_result));

        foreach ($skuArr as $list_index => $target_sku) {

            $__item__ = $api_price_result[$target_sku];

//          "124157": {
//            "skuId": 124157,
//            "costprice": 64,// 协议价格 成本
//            "productprice": 67.2, // 商品原价
//            "marketprice": 67.2 // 商品现价
//          }

            $list[$list_index]['costprice']    = $__item__['costprice'];
            $list[$list_index]['productprice'] = $__item__['productprice'];
            $list[$list_index]['marketprice']  = $__item__['marketprice'];
            $list[$list_index]['minprice']     = $__item__['marketprice'];
            $list[$list_index]['maxprice']     = $__item__['marketprice'];
        }

        return $list;
    }

    /**
     * 计划任务
     *
     * @return string
     */
    public function runQuerySkuForUpdatePrice()
    {

        global $_GPC, $_W;
//        $start = microtime(true);

        // 从数据库 临时表 得到　(24小时*7)内未更新过价格的数据 默认 x 100 sku
//        $result    = $this->_product_priceModel->queryForJdVopApiPriceUpdate();// 这个会有问题
        $result = $this->_goodsModel->queryForJdVopApiPriceUpdate();

        $total     = $result['total'];
        $page      = $result['page'];
        $page_size = $result['page_size'];
        $list      = $result['data'];

        if ($total == 0) {
            return "Task end : There's no more data in (24 hours) * 2";
        } else {

            $skuId = array();
            foreach ($list as $index => $item) {
                $skuId[] = $item['skuId'];
            }

//            $end = microtime(true);
//            echo '耗时'.round($end - $start,4).'秒';
//            echo '<br/>';

            $this->getPrice($skuId);

            return "Task total " . $total . "/" . $page_size . "|" . json_encode($skuId, JSON_UNESCAPED_UNICODE);
        }
    }


    /**
     * 5.1  批量查询京东价价格
     *
     * @param $skuArr
     *
     * @return array
     */
    public function getPrice($skuArr)
    {

        global $_GPC, $_W;
//        $price_service_get_price_start = microtime(true);

        $skuStr = implode(",", $skuArr);

//        print_r($skuStr);
//        echo PHP_EOL;//ok

        //5.1  批量查询京东价价格
        //$response_api_price_get_jd_price = $this->jd_sdk->api_price_get_jd_price($skuStr);

        //5.2  批量查询协议价价格
        $response_api_price_get_price = $this->jd_sdk->api_price_get_price($skuStr);

//        echo $response_api_price_get_price;
        $json_api_price_get_price = json_decode($response_api_price_get_price, true);
//        print_r($json_api_price_get_price);
//        echo json_encode($response_api_price_get_price,JSON_UNESCAPED_UNICODE);

        // 事实证明jd的是很快的 0.3s
//        $price_service_get_price_after_jd = microtime(true);
//        socket_log('PriceService->getPrice step_after_jd 耗时'.round($price_service_get_price_after_jd - $price_service_get_price_start,4).'秒');

        //5.3  批量查询商品售卖价
//        $response_api_price_get_sell_price = $this->jd_sdk->api_price_get_sell_price($skuStr);
//        $json_api_price_get_sell_price = json_decode($response_api_price_get_sell_price,true);


        //{"skuId":111111, "price":客户购买价格or协议价价格, "jdPrice":京东价格}


        $params = array();

        if ($json_api_price_get_price['success'] == true
            //    && $json_api_price_get_sell_price['success'] == true
        ) {

            $error_sku = explode('，', $json_api_price_get_price['resultMessage']);

            foreach ($error_sku as $error_sku) {
//                echo $error_sku;
//                echo intval($error_sku);
//                echo PHP_EOL;

                if (intval($error_sku) != 0) {
                    // 不在您的商品池中
                    $this->_shopGoodsService->processingJdApiPriceGetPriceErrorNotInYourCommodityPool(intval($error_sku));
                }
            }

//            $price_service_get_price_after_error = microtime(true);
//            socket_log('PriceService->getPrice step_after_error 耗时'.round($price_service_get_price_after_error - $price_service_get_price_start,4).'秒');


            // 合并数据
            foreach ($json_api_price_get_price['result'] as $index => $sku_price) {

//                echo json_encode($sku_price,JSON_UNESCAPED_UNICODE);
//                echo PHP_EOL;

                $jdPrice = $sku_price['jdPrice'];// jdvop_京东价
                $price   = $sku_price['price'];// jdvop_协议价

                $jdPrice = floatval($jdPrice);// jdvop_京东价
                $price   = floatval($price);// jdvop_协议价



                $params[$sku_price['skuId']]              = array();
                $params[$sku_price['skuId']]['skuId']     = $sku_price['skuId'];
                $params[$sku_price['skuId']]['costprice'] = $price;// 协议价格 成本 | NO | decimal(10,2) | 0.00 结算价 agreementprice 在企业购那是不能修改的
                // $params[$sku_price['skuId']]['productprice'] = $sku_price['jdPrice'];// 京东价格 原价 | NO | decimal(10,2) | 0.00 商品原价 proPrice => oldprice
                // $params[$sku_price['skuId']]['marketprice'] = $sku_price['price'];// 客户购买价格 现价 | NO | decimal(10,2) | 0.00 商品现价 productPrice =>referprice



                // 如果 本地协议价与请求协议价 不一样时 设置为 true 才会更新
                $is_update_database = false;

                $_goods_from_db = $this->_shopGoodsService->getOneBySkuId($sku_price['skuId']);

                if ($_goods_from_db) {
                    $is_update_database = $_goods_from_db['costprice'] == $price ? false : true;
                }

                if ($is_update_database) {


                    switch (SUPERDESK_SHOPV2_MODE_USER) {
                        case 1:// 企业内购

                            /* start */
                            // 原价的计算 round() 函数对浮点数进行四舍五入。
                            if ($jdPrice > $price) {

                                $referprice = round((($jdPrice - $price) * 0.7 + $price) * 100) / 100;// membershop_商品现价

                                // 判断 公式一 与 公式二 的大小区别,假如 公式一 比 公式二 大 的话就使用公式二

                                $secondprice = round(($price * 1.05) * 100) / 100;

                                if ($referprice > $secondprice) {
                                    $referprice = $secondprice;
                                }

                                $params[$sku_price['skuId']]['productprice'] = $jdPrice;//membershop_商品原价

                            } else {

                                // TODO 这情况不就是现价与原价同价?
                                $referprice                                  = round(($price * 1.05) * 100) / 100;// membershop_商品现价
                                $params[$sku_price['skuId']]['productprice'] = $referprice;// membershop_商品原价

                            }

                            $params[$sku_price['skuId']]['marketprice'] = $referprice;
                            /* end  */

                            break;
                        case 2:// 福利商城

                            /* start */
                            // 原价的计算 round() 函数对浮点数进行四舍五入。
                            if ($jdPrice > $price) {

                                // init 初始 保低8%
                                $referprice = round(($price * 1.08) * 100) / 100;// membershop_商品现价


                                // 新逻辑 start  https://pm.avic-s.com/project/11/task/1655
                                $discount_rate =  (floatval($jdPrice) - floatval($price)) / floatval($jdPrice)  *100;
                                $discount_rate = round($discount_rate,0);

                                // 商品折扣率为15%以上商品，商品定价=协议价*115% 即15个点毛利率
                                if ($discount_rate > 15) {
                                    $referprice = round(($price * 1.15) * 100) / 100;// membershop_商品现价
                                }
                                // 商品折扣率为8%-15%以内商品，商品定价=协议价*112% 即12个点毛利率
                                elseif ($discount_rate <= 15 && $discount_rate >= 8) {
                                    $referprice = round(($price * 1.12) * 100) / 100;// membershop_商品现价
                                }
                                // 商品折扣率为8%以下商品，商品定价=协议价*108% 即8个点毛利率
                                elseif ($discount_rate < 8) {
                                    $referprice = round(($price * 1.08) * 100) / 100;// membershop_商品现价
                                }
                                // 新逻辑 end  https://pm.avic-s.com/project/11/task/1655


                                if ($referprice > $jdPrice) { // 非正常情况 如果 协议价+12个点后大于京东价,显示加点后的价
                                    $params[$sku_price['skuId']]['productprice'] = $referprice;//membershop_商品原价
                                } else {
                                    $params[$sku_price['skuId']]['productprice'] = $jdPrice;//membershop_商品原价
                                }

//                                $secondprice = round(($price * 1.08) * 100) / 100;
//                                if ($secondprice > $jdPrice) { // TODO 利润点达不到8个点 下架 | 放入回收站
//
//                                    $this->_shopGoodsService->updateByStatus(array(
//                                        "state" => 0,
//                                        "skuId" => $sku_price['skuId']
//                                    ));
//
//                                } else {
//                                    // TODO　上架 本来
//                                }


                            } else {

                                // TODO 这情况不就是现价与原价同价?
                                $referprice                                  = round(($price * 1.08) * 100) / 100;// membershop_商品现价
                                $params[$sku_price['skuId']]['productprice'] = $referprice;// membershop_商品原价

                                // TODO 利润点达不到8个点 下架 | 放入回收站
//                                $this->_shopGoodsService->updateByStatus(array(
//                                    "state" => 0,
//                                    "skuId" => $sku_price['skuId']
//                                ));

                            }

                            $params[$sku_price['skuId']]['marketprice'] = $referprice;
                            /* end  */

                            break;

                    }
                } else {
                    // 不更新数据库时要把数据库的值返回


                    $params[$sku_price['skuId']]['productprice'] = $_goods_from_db['productprice'];// 商品原价
                    $params[$sku_price['skuId']]['marketprice'] = $_goods_from_db['marketprice']; // 现价
                }


            }

//    foreach ($json_api_price_get_sell_price['result'] as $index => $sku_price){
//        $params[$sku_price['skuId']]['costprice'] = $sku_price['price'];// 协议价格 成本 | NO | decimal(10,2) | 0.00 结算价 agreementprice 在企业购那是不能修改的
//    }

        } else {
            // TODO 出错信息
        }

//        echo PHP_EOL;
//        print_r($params);

//        $_start = microtime(true);
        foreach ($params as $key => $sku_price_4_jd_tmp_insert) {

            // {"skuId":270669,"productprice":99,"marketprice":82,"costprice":82}
            //    echo json_encode($sku_price_4_jd_tmp_insert);
            //    echo "<br/>";

            $column = array(
                "skuId" => $sku_price_4_jd_tmp_insert['skuId']
            );

            // 临时价格保存 superdesk_jd_vop_product_price
            $this->_product_priceModel->saveOrUpdateByColumn(
                $sku_price_4_jd_tmp_insert/*保存的数据*/,
                $column/*sku*/);

//            $price_service_get_price_after_update_price_tmp = microtime(true);
//            socket_log('PriceService->getPrice step_after_update_price_tmp 耗时'.round($price_service_get_price_after_update_price_tmp - $price_service_get_price_start,4).'秒');


//            displayorder int(11) 显示顺序
//
//            costprice 协议价格
//            productprice 商品现价
//            marketprice 商品原价

//            $sku_price_4_jd_tmp_insert['displayorder'] = intval(round(($sku_price_4_jd_tmp_insert['productprice'] - $sku_price_4_jd_tmp_insert['costprice'])));


//            echo json_encode($sku_price_4_jd_tmp_insert);
//            echo PHP_EOL;

            // 正式商品保存 superdesk_shop_goods
            $this->_goodsModel->updateByJdVopApiPriceUpdate(
                $sku_price_4_jd_tmp_insert/*保存的数据*/,
                $sku_price_4_jd_tmp_insert['skuId']/*sku*/);

//            $price_service_get_price_after_update_price_release = microtime(true);
//            socket_log('PriceService->getPrice step_after_update_price_release 耗时'.round($price_service_get_price_after_update_price_release - $price_service_get_price_start,4).'秒');

        }
//        $_end = microtime(true);
//        echo '临时价格保存 & 正式商品保存 耗时'.round($_end - $_start,4).'秒';
//        echo '<br/>';

        $this->_product_priceModel->callbackForJdVopApiPriceUpdate($skuArr);

//        $price_service_get_price_end = microtime(true);
//        socket_log('PriceService->getPrice after_call_back_jd_price_tmp 耗时'.round($price_service_get_price_end - $price_service_get_price_start,4).'秒');

        return $params;
//        {
//          "124157": {
//            "skuId": 124157,
//            "costprice": 64,// 协议价格 成本
//            "productprice": 67.2, // 商品原价
//            "marketprice": 67.2 // 商品现价
//          },
//          "892900": {
//            "skuId": 892900,
//            "costprice": 1399,
//            "productprice": 1468.95,
//            "marketprice": 1468.95
//          },
//          "1037029": {
//            "skuId": 1037029,
//            "costprice": 149,
//            "productprice": 156.45,
//            "marketprice": 156.45
//          }
//        }


    }

    /**
     * 7.9  统一余额查询接口
     *
     * @param int $payType
     *
     * @return mixed
     */
    public function getBalance($payType = 4)
    {

        global $_GPC, $_W;

        $response = $this->jd_sdk->api_price_get_balance($payType/*支付类型 4：余额 7：网银钱包 101：金采支付*/);
        $response = json_decode($response, true);

//        die(json_encode($response , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        return $response;
    }

    /**
     * 7.11  余额明细查询接口
     *
     * @param int $pageNum
     * @param int $pageSize
     * @param     $orderId
     * @param     $startDate
     * @param     $endDate
     *
     * @return mixed
     */
    public function getBalanceDetail($pageNum = 1, $pageSize = 20, $orderId = '', $startDate = '', $endDate = '')
    {

        global $_GPC, $_W;

        $response = $this->jd_sdk->api_price_get_balance_detail($pageNum, $pageSize, $orderId, $startDate, $endDate);
        $response = json_decode($response, true);

//        die(json_encode($response , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        return $response;
    }

    public function syncBalanceDetailAll($pageNum = 1, $pageSize = 20, $orderId = '', $startDate = '', $endDate = '')
    {
        global $_GPC, $_W;

        $result            = $this->getBalanceDetail($pageNum, $pageSize, $orderId, $startDate, $endDate);
        $source_total      = $result['result']['total'];
        $source_page       = $result['result']['pageNo'];
        $source_page_size  = $result['result']['pageSize'];
        $source_page_count = $result['result']['pageCount'];
        $source_list       = $result['result']['data'];


        echo '同步 total > ' . $source_total;
        echo PHP_EOL;
        echo '同步 page > ' . $source_page;
        echo PHP_EOL;
        echo '同步 pageSize > ' . $source_page_size;
        echo PHP_EOL;
        echo '同步 pageCount > ' . $source_page_count;
        echo PHP_EOL;
//        echo json_encode($source_list,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

        $this->businessProcessingBalanceDetailReplace($source_list);
    }


    /**
     * @return String 非必填 开始日期，格式必须：yyyyMMdd
     */
    public function checkSyncBalanceDetailCreatedDate()
    {
        global $_GPC, $_W;

        $result = $this->_balance_detailModel->checkSyncCreatedDate();

        if (sizeof($result) > 0) {
            return date('Ymd', strtotime($result[0]['createdDate']));
        } else {
            return '19700101';
        }
    }

    /**
     * 按 余额变动日期 同步
     * 做不到 只能估算一天大概条数
     *
     * @param $separate_time
     */
    public function syncIncrementBalanceDetailCreatedDate($pageNum = 1, $pageSize = 1000, $orderId = '', $startDate = '', $endDate = '')
    {
        global $_GPC, $_W;

        $startDate = $this->checkSyncBalanceDetailCreatedDate();

        echo '同步 CreateTime > ' . $startDate;
        echo PHP_EOL;


        $result            = $this->getBalanceDetail($pageNum, $pageSize, $orderId, $startDate, $endDate);
        $source_total      = $result['result']['total'];
        $source_page       = $result['result']['pageNo'];
        $source_page_size  = $result['result']['pageSize'];
        $source_page_count = $result['result']['pageCount'];
        $source_list       = $result['result']['data'];


        echo '同步 total > ' . $source_total;
        echo PHP_EOL;
        echo '同步 page > ' . $source_page;
        echo PHP_EOL;
        echo '同步 pageSize > ' . $source_page_size;
        echo PHP_EOL;
        echo '同步 pageCount > ' . $source_page_count;
        echo PHP_EOL;
//        echo json_encode($source_list,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

        $this->businessProcessingBalanceDetailReplace($source_list);
    }

    private function businessProcessingBalanceDetailReplace($source_list)
    {

        global $_GPC, $_W;

        foreach ($source_list as $index => $target_record) {


            $_id = $target_record['id'];

            echo json_encode($target_record, JSON_UNESCAPED_UNICODE);
            echo PHP_EOL;

            $this->_balanceDetailService->insertBlanceDetailAndProcessing($target_record, $_id);

        }
    }

    public function businessProcessingBalanceDetailTradeType_416($page = 1, $page_size = 500)
    {

        global $_GPC, $_W;

//        SELECT * FROM `ims_superdesk_jd_vop_balance_detail` WHERE tradeType = 416 ORDER BY `createdDate` DESC


        $where               = array();
        $where['tradeType']  = 416;
        $where['processing'] = 0;


        $result = $this->_balance_detailModel->queryAll($where, $page, $page_size);

        $source_total     = $result['total'];
        $source_page      = $result['page'];
        $source_page_size = $result['page_size'];
        $source_list      = $result['data'];


        echo '同步 total > ' . $source_total;
        echo PHP_EOL;
        echo '同步 page > ' . $source_page;
        echo PHP_EOL;
        echo '同步 pageSize > ' . $source_page_size;
        echo PHP_EOL;
//        echo json_encode($source_list,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        echo PHP_EOL;

        foreach ($source_list as $index => $item_balance_detail) {

            echo(json_encode($item_balance_detail, JSON_UNESCAPED_UNICODE) . "  处理::");

            $isExist = $this->_orderService->checkOrderByjdOrderId($item_balance_detail['orderId']);

            echo PHP_EOL;
            echo 'START====>';
            echo PHP_EOL;
            if ($isExist) {


                // 分析备注信息
                echo '分析备注信息';
                echo PHP_EOL;
                $_balance_detail_416 = $this->transformNotePub($item_balance_detail['notePub']);
                echo json_encode($_balance_detail_416, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                echo PHP_EOL;

                // _order_submit_orderModel getOneByColumn jd_vop_result_jdOrderId
                echo '_order_submit_orderModel getOneByColumn (jd_vop_result_jdOrderId '. $_balance_detail_416['订单号'] . ')';
                echo PHP_EOL;
                $_jd_vop_submit_order = $this->_order_submit_orderModel->getOneByColumn(array(
                    'jd_vop_result_jdOrderId' => $_balance_detail_416['订单号']
                ));
                echo json_encode($_jd_vop_submit_order,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                echo PHP_EOL;


                echo PHP_EOL;
                $_shop_order = $this->_orderModel->getOneByColumn(array(
                    'ordersn'   => $_jd_vop_submit_order['thirdOrder'],
                    'id'        => $_jd_vop_submit_order['order_id'],
                    'expresssn' => $_balance_detail_416['订单号']
                ));
                echo json_encode($_shop_order,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                echo PHP_EOL;


                $_jd_vop_submit_order_sku = $this->_order_submit_order_skuModel->getOneByColumn(array(
                    'jdOrderId' => $_balance_detail_416['订单号'],
                    'skuId'     => $_balance_detail_416['商品编号']
                ));

                // 这里作个修正 有个情况就是没拆单 退的记录中是赠品的
                $_oid = $_balance_detail_416['商品编号'];
                if($_jd_vop_submit_order_sku['oid'] > 0){
                    $_oid = $_jd_vop_submit_order_sku['oid'];
                    $_jd_vop_submit_order_sku = $this->_order_submit_order_skuModel->getOneByColumn(array(
                        'jdOrderId' => $_balance_detail_416['订单号'],
                        'skuId'     => $_oid
                    ));
                }


                $_jd_vop_submit_order_sku['shop_order_id'] = $_shop_order['id'];
                $_jd_vop_submit_order_sku['shop_order_sn'] = $_shop_order['ordersn'];
                $_jd_vop_submit_order_sku['shop_goods_id'] = $this->_goodsModel->getGoodsIdBySkuId($_oid);// 正常

                $_jd_vop_submit_order_sku['return_goods_nun'] = $_jd_vop_submit_order_sku['return_goods_nun'] - 1;



                // TODO 有可能的BUG start return_goods_result 最变态的是一个很便宜的东西有几十个 超长了
                if (empty($_jd_vop_submit_order_sku['return_goods_result'])) {
                    $_jd_vop_submit_order_sku['return_goods_result'] = array();
                } else {
                    $_jd_vop_submit_order_sku['return_goods_result'] = iunserializer($_jd_vop_submit_order_sku['return_goods_result']);
                }
                $_jd_vop_submit_order_sku['return_goods_result'][] = $item_balance_detail;
                $_jd_vop_submit_order_sku['return_goods_result'] = iserializer($_jd_vop_submit_order_sku['return_goods_result']);
                // TODO 有可能的BUG end

                echo json_encode($_jd_vop_submit_order_sku, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                echo PHP_EOL;

                unset($_jd_vop_submit_order_sku['id']);
                unset($_jd_vop_submit_order_sku['pOrder']);
                unset($_jd_vop_submit_order_sku['jdOrderId']);
                unset($_jd_vop_submit_order_sku['skuId']);
                unset($_jd_vop_submit_order_sku['num']);
                unset($_jd_vop_submit_order_sku['category']);
                unset($_jd_vop_submit_order_sku['price']);
                unset($_jd_vop_submit_order_sku['name']);
                unset($_jd_vop_submit_order_sku['tax']);
                unset($_jd_vop_submit_order_sku['taxPrice']);
                unset($_jd_vop_submit_order_sku['nakedPrice']);
                unset($_jd_vop_submit_order_sku['type']);
                unset($_jd_vop_submit_order_sku['oid']);

                $this->_order_submit_order_skuModel->saveOrUpdateByColumn($_jd_vop_submit_order_sku, array(
                    'jdOrderId' => $_balance_detail_416['订单号'],
                    'skuId'     => $_oid
                ));


                $_shop_order_goods = $this->_order_goodsModel->getOneByColumn(array(
                    'orderid' => $_jd_vop_submit_order_sku['shop_order_id'],
                    'goodsid' => $_jd_vop_submit_order_sku['shop_goods_id'],
                ));

                if ($_shop_order_goods) {
                    $this->_order_goodsModel->update(array(
                        'return_goods_nun'    => $_jd_vop_submit_order_sku['return_goods_nun'],
                        'return_goods_result' => $_jd_vop_submit_order_sku['return_goods_result']
                    ), $_shop_order_goods['id']);
                }

                echo json_encode($_shop_order_goods, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                echo PHP_EOL;

//                $result_cancel = $this->_orderService->businessProcessingCancelJdOrder($item_balance_detail['orderId']);

//                if ($result_cancel['success']) {
//                    $this->messageDel($_message['id']);
//                }

                $result_msg = '系统订单 ';
                echo $result_msg;
                //2018年9月11日 16:39:24 zjh _balance_detailModel -> _balance_detail_processingModel
                $this->_balance_detail_processingModel->saveOrUpdateToId(array(
                    'processing'     => 1,
                    'process_result' => $result_msg,
                ), $item_balance_detail['id']);


            } else {

                $result_msg = '本地库不存在,不是本系统订单 ';
                echo $result_msg;
                //2018年9月11日 16:39:24 zjh _balance_detailModel -> _balance_detail_processingModel
                $this->_balance_detail_processingModel->saveOrUpdateToId(array(
                    'processing'     => 1,
                    'process_result' => $result_msg,
                ), $item_balance_detail['id']);
            }
            echo PHP_EOL;
            echo '<======END';
            echo PHP_EOL;

            echo PHP_EOL;
        }


    }

    /**
     * @param $notePub
     *
     * @return array
     */
    public function transformNotePub($notePub)
    {

        $notePub = str_replace('退货返款:', '', $notePub);
        $notePub = str_replace('：', ':', $notePub);

        $notePub_arr_01 = explode(',', $notePub);
        $notePub_arr_02 = array();

        foreach ($notePub_arr_01 as &$item) {
            $item = explode(':', $item);

            $notePub_arr_02[$item[0]] = $item[1];
        }

        return $notePub_arr_02;
//        {
//            "订单号": "72409711861",
//            "商品编号": "1033206",
//            "商品名称": "得力（deli） 0416 轻便型厚层订书机 金属材质 50页 颜色随机",
//            "申请理由": "null",
//            "退款金额": "17.250",
//            "服务单": "407770201",
//            "退款方式": "原返"
//        }

    }


}