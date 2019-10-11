<?php

//namespace sdk\jd_vop\service;
//use sdk\jd_vop\service;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/sdk/jd_vop_sdk.php');
include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/product_detail.class.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/product_price.class.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/product_exts.class.php');

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/page_num.class.php');

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/goods/goods.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/category.class.php');

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/PriceService.class.php');


/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/23/17
 * Time: 4:56 PM
 */
class ProductService
{
    private $jd_sdk;
    private $_redis;

    private $_product_detailModel;
    private $_product_priceModel;
    private $_product_extsModel;

    private $_page_numModel;

    private $_goodsModel;
    private $_categoryModel;

    private $_priceService;

    function __construct()
    {

        $this->_redis = new RedisUtil();

        $this->jd_sdk        = new JDVIPOpenPlatformSDK();
        $this->jd_sdk->debug = false;
        $this->jd_sdk->init_access_token();

        $this->_product_detailModel = new product_detailModel();
        $this->_product_priceModel  = new product_priceModel();
        $this->_product_extsModel   = new product_extsModel();

        $this->_page_numModel = new page_numModel();

        $this->_goodsModel    = new goodsModel();
        $this->_categoryModel = new categoryModel();

        $this->_priceService = new PriceService();
    }


    /**
     * @param     $sku
     * @param     $page_num
     * @param int $overwrite
     *
     * @return mixed
     */
    public function businessProcessingGetDetailOne($sku, $page_num, $overwrite = 0, $specify_id = 0)
    {
        global $_W, $_GPC;

        $product_service_business_processing_get_detail_one_start = microtime(true);

        $table_key             = 'superdesk_jd_vop_' . 'api_product_get_detail' . ':' . $_W['uniacid'] . ':' . $page_num;// 详情记录
        $table_key_overwrite_0 = 'superdesk_jd_vop_' . 'api_product_get_detail_overwrite_0' . '_' . $_W['uniacid'];

        $is_insert                = false;
        $result_get_detail_decode = false;

        $has_update = 0;
        $has_detail = 0;


        if ($overwrite == 1) { // 京东更新 必定

            // call 3.4  获取商品详细信息接口
            $response = $this->getDetail($sku/*只支持单个*/, true);
//            $this->_redis->hset($table_key, $sku, $response);// TODO 屏蔽商品详情缓存
            $result_get_detail_decode = json_decode($response, true);


            // DEBUG

//            die(json_encode($result_get_detail_decode,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));

            $is_insert = true;

        } else if ($overwrite == 0) { // 京东更新 看情况

            $has_update = $this->_redis->ishExists($table_key_overwrite_0, $sku);

            // mode (overwrite = 0) redis (最近更新 isExists == 1) : 不作任何事情
            if ($has_update == 1) {

            } // mode (overwrite = 0) redis (最近更新 isExists == 0) :
            else {

                $has_detail = $this->_redis->ishExists($table_key, $sku);

                // mode (overwrite = 0) redis (最近更新(isExists == 0)) redis (详情缓存(isExists == 1))
                if ($has_detail == 1) {

                    $response                 = $this->_redis->hget($table_key, $sku);
                    $result_get_detail_decode = json_decode($response, true);

                    // redis 缓存是不正确的
                    if (
                    (!isset($result_get_detail_decode['success']) // 当没有字段 时为 true
                        || $result_get_detail_decode['success'] == false)
                    ) {

                        // call 3.4  获取商品详细信息接口
                        $response = $this->getDetail($sku/*只支持单个*/, true);
//                    $this->_redis->hset($table_key, $sku, $response);// TODO 屏蔽商品详情缓存
                        $result_get_detail_decode = json_decode($response, true);
                        $is_insert                = true;
                    } // redis 缓存是正确的
                    else {
                        // 不用做事
                    }
                } // mode (overwrite = 0) redis (最近更新(isExists == 0)) redis (详情缓存(isExists == 0))
                else {

                    // call 3.4  获取商品详细信息接口
                    $response = $this->getDetail($sku/*只支持单个*/, true);
//                    $this->_redis->hset($table_key, $sku, $response);// TODO 屏蔽商品详情缓存
                    $result_get_detail_decode = json_decode($response, true);
                    $is_insert                = true;

                }
            }
        }

// debug
//1153795
//{
//    "status": 1,
//    "result": {
//    "success": false,
//        "resultMessage": "服务异常，请稍后重试",
//        "resultCode": "5001",
//        "code": 200,
//        "url": "http:\/\/192.168.1.124\/superdesk\/web\/index.php?c=site&a=entry&eid=1466"
//    }
//}

        if ($is_insert == true && $result_get_detail_decode) {

            if ($result_get_detail_decode['success'] == true) {

                $skuId[] = $sku;


                $where        = array();
                $where['sku'] = $sku;


                $result_get_detail_decode['result']['page_num'] = $page_num;
                $_product_info_unpack                           = $this->getDetailUnpack($result_get_detail_decode);


//                die(json_encode($_product_info_unpack,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));


                // save to ims_superdesk_jd_vop_product_detail table start
                $this->_product_detailModel->saveOrUpdateByColumn($_product_info_unpack['detail'], $where);

                // save to ims_superdesk_jd_vop_product_exts table start
                $this->_product_extsModel->saveOrUpdateByColumn($_product_info_unpack['exts'], $where);


                // load from temp table
                $_product_detail = $this->_product_detailModel->getOneBySku($sku);

                // 处理分类
                $jd_vop_category = $_product_detail['category'];
                $jd_vop_category = explode(";", $jd_vop_category);

                $this->syncCategoryFormSkuDetail($jd_vop_category, $page_num);

                // 分销商城sku表入库
                $this->_goodsModel->saveOrUpdateByJdVopApiProductGetDetail(
                    $_product_detail,
                    $_product_detail['sku'],
                    $specify_id
                );

                // skuImage
                $this->skuImage($_product_detail['sku']);

                // 京东商品临时表 update updatetime
                $this->_product_detailModel->callbackForJdVopApiProductDetailUpdate($skuId);

                // 初始化 京东价格 加条记录
                $this->_product_priceModel->saveOrUpdateByJdVop($sku);

                // 从京东API更新价格
                $this->_priceService->getPrice(array($sku));

                $this->_redis->hset($table_key_overwrite_0, $sku, 1);// $this->_redis->setex($key, 3600, 1);// TODO 此方法有可以key还存在,里边的东西没有了


            }

        } else {

            $result_get_detail_decode                   = array();
            $result_get_detail_decode["success"]        = true;
            $result_get_detail_decode["result"]['name'] = "mode (overwrite = $overwrite) redis (最近更新(isExists == $has_update)) redis (详情缓存(isExists == $has_detail)) sku($sku) : 不从京东同步";

        }

//        $product_service_business_processing_get_detail_one_end = microtime(true);
//        socket_log('ProductService->businessProcessingGetDetailOne 耗时'.round($product_service_business_processing_get_detail_one_end - $product_service_business_processing_get_detail_one_start,4).'秒');


        return $result_get_detail_decode;

    }

    /**
     * @param $result_get_detail_decode
     *
     * @return array
     */
    protected function getDetailUnpack($result_get_detail_decode)
    {

        global $_W, $_GPC;

        $_product_detail = $result_get_detail_decode['result'];


        $_product_exts                   = [];
        $_product_exts['category']       = $_product_detail['category'];
        $_product_exts['sku']            = $_product_detail['sku'];
        $_product_exts['taxCode']        = $_product_detail['taxCode'];
        $_product_exts['isFactoryShip']  = $_product_detail['isFactoryShip'];
        $_product_exts['isEnergySaving'] = $_product_detail['isEnergySaving'];
        $_product_exts['contractSkuExt'] = $_product_detail['contractSkuExt'];
        $_product_exts['ChinaCatalog']   = $_product_detail['ChinaCatalog'];

//        `taxCode` VARCHAR(32) NOT NULL COMMENT '税务编码' ,
//        `isFactoryShip` INT(11) NOT NULL COMMENT '是否厂商直送' ,
//        `isEnergySaving` INT(11) NOT NULL COMMENT '是否政府节能' ,
//        `contractSkuExt` VARCHAR(64) NOT NULL COMMENT '定制商品池开关' ,
//        `ChinaCatalog` VARCHAR(64) NOT NULL COMMENT '中图法分类号'

        unset($_product_detail['taxCode']);
        unset($_product_detail['isFactoryShip']);
        unset($_product_detail['isEnergySaving']);
        unset($_product_detail['contractSkuExt']);
        unset($_product_detail['ChinaCatalog']);


        $result           = [];
        $result['detail'] = $_product_detail;
        $result['exts']   = $_product_exts;


        return $result;

    }

    /**
     * 3.4  获取商品详细信息接口
     *
     * @param      $sku
     * @param bool $isShow
     * @param bool $isDecode
     *
     * @return mixed
     */
    public function getDetail($sku, $isShow = false, $isDecode = false)
    {
        global $_W, $_GPC;

        $response = $this->jd_sdk->api_product_get_detail($sku/*只支持单个*/, $isShow);

        if ($isDecode) {
            $response = json_decode($response, true);
        }

        return $response;
    }

    public function getSimilarSku($sku){


        global $_W, $_GPC;

        $response = $this->jd_sdk->api_product_get_similar_sku($sku/*只支持单个*/);
        $response = json_decode($response, true);

        if (isset($response['success']) && $response['success'] == true) {

            $result = $response['result'];

            foreach ($result as &$_dim_sale) {

                foreach ($_dim_sale['saleAttrList'] as &$_dim_sale_attr){

                    foreach ($_dim_sale_attr['skuIds'] as &$_dim_sale_attr_sku_id){

                        $_sku = $_dim_sale_attr_sku_id;

                        $_dim_sale_attr_sku_id = [
                            'sku' => $_sku,
                            'gid' => $this->_goodsModel->getGoodsIdBySkuId($_sku) // 如果查不到,返回 0 TODO 可能要unset
                        ];
                    }

                    unset($_dim_sale_attr_sku_id);

                }

                unset($_dim_sale_attr);

            }
            unset($_dim_sale);

            return $result;

        } else {
            return false;
        }

    }

    /**
     * @param $jd_vop_category
     * @param $page_num
     */
    public function syncCategoryFormSkuDetail($jd_vop_category, $page_num)
    {


        foreach ($jd_vop_category as $key => $cate_id) {
//            echo $key . ' ';
//            echo $cate_id . '<br>';
            $this->getCategory($cate_id, $page_num);

        }

    }

    /**
     * 3.13  商品可售验证接口
     *
     * @param $skuIds
     *
     * @return mixed
     */
    public function check(/*$skuIds*/ $skuArr)
    {
        global $_GPC, $_W;

        $skuStr = implode(",", $skuArr);

        $response = $this->jd_sdk->api_product_check($skuStr);
        $response = json_decode($response, true);

        die(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return $response;
    }

    /**
     * 3.15  查询分类信息接口
     *
     * @param $cid
     *
     * @return mixed
     */
    public function getCategory($cid /*Long | 必须 | 分类id（可通过商品详情接口查询）*/, $page_num = 0)
    {

        global $_W, $_GPC;


        /** test start **/
//        $response = $this->jd_sdk->api_product_get_category($cid);
//        echo "<br>";
//        die(json_encode(json_decode($response),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
        /** test end   **/


        $key = 'superdesk_jd_vop_' . 'api_product_get_category_tmp' . '_' . $_W['uniacid'] . ':' . $cid;

        $result_get_category_decode = false;

        if ($this->_redis->isExists($key) == 1) {

            $response                   = $this->_redis->get($key);
            $result_get_category_decode = json_decode($response, true);

        } else {
            $response = $this->jd_sdk->api_product_get_category($cid);
            $this->_redis->set($key, $response, 1);// $this->_redis->setex($key, 3600, $response);// TODO 此方法有可以key还存在,里边的东西没有了
            $result_get_category_decode = json_decode($response, true);
        }

        if ($result_get_category_decode
            && isset($result_get_category_decode['success'])
            && $result_get_category_decode['success'] == true
        ) {

//            die(json_encode($result_get_category_decode,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
//            {"success":true,"resultMessage":"\u64cd\u4f5c\u6210\u529f","resultCode":"0000","result":{"catId":670,"parentId":0,"name":"\u7535\u8111\u3001\u529e\u516c","catClass":0,"state":1},"code":200}
            $this->_categoryModel->saveOrUpdateByJdVop($result_get_category_decode['result'], $page_num);
        }


    }

    /**
     * 3.16  查询分类列表信息接口
     *
     * @param $pageNo   pageNo | Integer | 必须 | 页号，从1开始；
     * @param $pageSize pageSize | Integer | 必须 | 页大小，最大值5000；
     * @param $parentId parentId | Integer | 非必须 | 父ID
     * @param $catClass catClass | Integer | 非必须 | 分类等级（0:一级； 1:二级；2：三级）
     *
     * @return mixed
     */
    public function getCategorys($pageNo = 1, $pageSize = 5000, $parentId = null, $catClass = null)
    {

        $response = $this->jd_sdk->api_product_get_categorys($pageNo, $pageSize, $parentId, $catClass);

        $result_get_categorys_decode = json_decode($response, true);

        return $result_get_categorys_decode;
    }

    /**
     * 3.6  获取所有图片信息
     *
     * @param $sku 商品编号，支持批量，以，分隔  (最高支持100个商品)
     *
     * @return mixed
     */
    public function skuImage($sku)
    {

        $response               = $this->jd_sdk->api_product_sku_image($sku);
        $result_skuImage_decode = json_decode($response, true);
//        die(json_encode($result_skuImage_decode, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));

        if (
            isset($result_skuImage_decode['success'])
            && $result_skuImage_decode['success'] == true
        ) {
            $this->_goodsModel->saveOrUpdateByJdVopSkuImage($result_skuImage_decode['result']);
        }


        return $result_skuImage_decode;
    }

    /**
     * 3.5  商品上下架状态接口
     *
     * @param $skuArr
     *
     * @return array|bool
     */
    public function skuState($skuArr)
    {

        global $_GPC, $_W;

        $sku = implode(',', $skuArr);


        $response = $this->jd_sdk->api_product_sku_state($sku);
        $response = json_decode($response, true);
//        =====$response=====
//        { "success": true, "resultMessage": "操作成功", "resultCode": "0000", "result": [ { "state": 0, "sku": 5729524 } ], "code": 200 }


        if (isset($response['success']) && $response['success'] == true) {

            $result = $response['result'];

            $transform_result = array();

            foreach ($result as $item) {
                $transform_result[$item['sku']] = $item;
            }

            // { "5729524": { "state": 0, "sku": 5729524 } }
            return $transform_result;

        } else {
            return false;
        }
    }


    /**
     * 3.1  获取商品池编号接口
     *
     * @return mixed
     */
    public function getPageNum()
    {

        global $_GPC, $_W;

        $result = $this->jd_sdk->api_product_get_page_num();
        $result = json_decode($result, true);
        return $result;
    }

    /**
     * 3.3  获取池内商品编号接口-品类商品池（兼容老接口）
     *
     * @param $page_num
     * @param $page
     *
     * @return mixed
     */
    public function getSkuByPage($page_num, $page)
    {

        global $_GPC, $_W;
        $response = $this->jd_sdk->api_product_get_sku_by_page($page_num, $page);
        $response = json_decode($response, true);

//        echo json_encode($response,JSON_UNESCAPED_UNICODE);

        return $response;
    }

    /**
     * 计划任务
     *
     * @param bool $_DEBUG
     */
    public function runQueryPageNumForTask($_DEBUG = false)
    {
        global $_GPC, $_W;

        $result       = $this->getPageNum();
        $result_local = $this->_page_numModel->queryByCronComparison();


        $result_diff_for_insert = array_diff_assoc_recursive($result['result'], $result_local);
        echo 'For INSERT ' . PHP_EOL;
        echo json_encode($result_diff_for_insert, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . PHP_EOL;

        $result_diff_for_delete = array_diff_assoc_recursive($result_local, $result['result']);
        echo 'For Delete ' . PHP_EOL;
        echo json_encode($result_diff_for_delete, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . PHP_EOL;

//{
//    "success": true,
//  "resultMessage": "",
//  "resultCode": "0000",
//  "result": [
//    {
//        "name": "办公设备",
//      "page_num": "333333"
//    },
//    {
//        "name": "IT设备",
//      "page_num": "87654321"
//    }
//  ]
//}


        foreach ($result_diff_for_insert as $index => $item_insert) {
            $this->_page_numModel->saveOrUpdateByJdVop($item_insert, $item_insert['page_num']);
        }

        foreach ($result_diff_for_delete as $index => $item_delete) {

            $item_delete['deleted'] = 1;
            $item_delete['state']   = 0;

            $this->_page_numModel->updateByColumn(
                $item_delete, array(
                'page_num' => $item_delete['page_num']
            ));
        }


        $_cron_handle_task_01_page_num = '_cron_handle_task_01_page_num:' . $_W['uniacid'];

        // 如果上次加入队列的还没处理完，就不加新的了
        if ($this->_redis->isExists($_cron_handle_task_01_page_num) == 0
            || $this->_redis->lLen($_cron_handle_task_01_page_num) == 0
        ) {

            $result_cron = $this->_page_numModel->queryByCronComparison(array(
                'deleted' => 0,
                'state'   => 1
            ));

            foreach ($result_cron as $index => $item_cron) {

                $item_cron['page'] = 1;
                $this->_redis->rPush($_cron_handle_task_01_page_num, json_encode($item_cron, JSON_UNESCAPED_UNICODE));

            }
        }


        if ($_DEBUG) {
            $this->_redis->del($_cron_handle_task_01_page_num);
        }
    }

    /**
     * call page /data/wwwroot/default/superdesk/addons/superdesk_jd_vop/inc/mobile/api_product_get_sku_one.inc.php
     * @param     $page_num
     * @param int $page
     *
     * @return array
     */
    public function businessProcessingGetSkuByPageForManual($page_num, $page = 1)
    {

        global $_GPC, $_W;

        $skuIds_merge = array();

        $fnBuilder = function ($page, $retry = 0) use (&$fnBuilder, &$skuIds_merge, $page_num) {


            $skuIds_sub_page = array();
            $response        = $this->getSkuByPage($page_num, $page);
//            echo json_encode($response,JSON_UNESCAPED_UNICODE);
//            echo PHP_EOL;

            // {"success":true,"resultMessage":"操作成功","resultCode":"0000","result":{"pageCount":1,"skuIds":[105227,119313,163277]},"code":200}
            if ($response['success'] == true) {

//                echo $page_num . ' ' . $page . ' = ';
//                echo sizeof($response['result']['skuIds']);
//                echo PHP_EOL;echo PHP_EOL;echo PHP_EOL;

                $_ignore = false;
                // 成功的情况下加入队列
                foreach ($response['result']['skuIds'] as $sku) {

                    if (strlen($sku) < 8) {

                    } elseif (strlen($sku) == 8) {
                        $_ignore = true;
                        break;// 终止循环
                    } elseif (strlen($sku) > 8) {
                        $_ignore = true;
                        break;// 终止循环
                    }

                    if ($page == 1) {
                        $skuIds_merge[$page][] = $sku;
                    } else {
                        $skuIds_sub_page[] = $sku;
                    }
                }
                if ($_ignore) {
                    $this->_page_numModel->updateByColumn(array(
                        'deleted' => 1,
                        'state'   => 0
                    ), array(
                        'page_num' => $page_num
                    ));
                }
            } // {"success":false,"resultMessage":"pageNum不存在","resultCode":"0010","result":null}
            elseif ($response['success'] == false) {

                if ($response['resultMessage'] == "pageNum不存在"
                    && $response['resultCode'] == "0010"
                ) {

                    if ($retry < 2) {

                        $retry               = $retry + 1;
                        $skuIds_merge[$page] = $fnBuilder($page, $retry);
                    } elseif ($retry == 2) {

                        $this->_page_numModel->updateByColumn(array(
                            'deleted' => 1,
                            'state'   => 0
                        ), array(
                            'page_num' => $page_num
                        ));

                    }
                }

            }

            // 如果还有页码，加入page num 队列
            if ($page < $response['result']['pageCount']) {
                $page = $page + 1;

                $skuIds_merge[$page] = $fnBuilder($page);
            }

            return $skuIds_sub_page;
        };

        $fnBuilder($page);

//        print_r($skuIds_merge);


        $arr_split = array();
        foreach ($skuIds_merge as $skuIds_page) {
            foreach ($skuIds_page as $skuId) {
                $arr_split[] = $skuId;
            }
        }

        $data             = array();
        $data['page_num'] = $page_num;
        $data['total']    = sizeof($arr_split);
        $data['list']     = $arr_split;
        return $data;


    }

    /**
     * 计划任务,得到的skuIds是加入队列
     *
     * @param bool $_DEBUG
     *
     * @return bool|mixed
     */
    public function runQueryGetSkuByPageForTask($_DEBUG = false)
    {

        global $_GPC, $_W;

//        include_once(IA_ROOT . '/addons/superdesk_jd_vop/sdk/sfc.functions.php');

        $_cron_handle_task_01_page_num       = '_cron_handle_task_01_page_num:' . $_W['uniacid'];
        $_cron_handle_task_02_sku_4_page_num = '_cron_handle_task_02_sku_4_page_num:' . $_W['uniacid'];

        $queue_lenght = $this->_redis->lLen($_cron_handle_task_01_page_num);// 获取现有消息队列的长度


        if ($queue_lenght > 0 || $_DEBUG) {

            if ($_DEBUG) {
                // $page_num_str = '{"name":"","page_num":"6911","page":2}';
                $page_num_str = isset($_GPC['page_num_str']) ? htmlspecialchars_decode($_GPC['page_num_str']) : '';
            } else {
                $page_num_str = $this->_redis->lPop($_cron_handle_task_01_page_num);// 出队列
            }

            $page_num_dto = json_decode($page_num_str, true);

            $page_num = $page_num_dto['page_num'];

            $response = $this->getSkuByPage($page_num_dto['page_num'], $page_num_dto['page']);


            // {"success":true,"resultMessage":"操作成功","resultCode":"0000","result":{"pageCount":1,"skuIds":[105227,119313,163277]},"code":200}
            if ($response['success'] == true) {

                $_ignore = false;

                // 成功的情况下加入队列
                foreach ($response['result']['skuIds'] as $sku) {

                    if (strlen($sku) < 8) {

                    } elseif (strlen($sku) == 8) {
                        $_ignore = true;
                        break;// 终止循环
                    } elseif (strlen($sku) > 8) {
                        $_ignore = true;
                        break;// 终止循环
                    }

                    $task_sku_dto = array(
                        'sku'       => $sku,
                        'page_num'  => $page_num,
                        'overwrite' => 1
                    );

                    $this->_redis->rPush($_cron_handle_task_02_sku_4_page_num, json_encode($task_sku_dto));
                }

                if ($_ignore) {
                    $this->_page_numModel->updateByColumn(array(
                        'deleted' => 1,
                        'state'   => 0
                    ), array(
                        'page_num' => $page_num
                    ));
                } else {
                    // 如果还有页码，加入page num 队列
                    if ($response['result']['pageCount'] > 1 && $page_num_dto['page'] == 1) {

                        //          for ($_page = $page_num_dto['page'] + 1; $_page <= $response['result']['pageCount']; $_page++) {
                        for ($_page = $response['result']['pageCount']; $_page >= 2; $_page--) {

                            $push_page_num = array(
                                "name"     => $page_num_dto['name'],
                                "page_num" => $page_num_dto['page_num'],
                                "page"     => $_page
                            );

                            // try_1 start this is error
                            $this->_redis->lPush($_cron_handle_task_01_page_num, json_encode($push_page_num, JSON_UNESCAPED_UNICODE));
                            // try_1 end


                            // try_2 start queue has error
                            //                $query = array(
                            //                    '_DEBUG'       => 1,
                            //                    'page_num_str' => json_encode($page_num_dto)
                            //                );
                            //                $url = $this->createAngularJsUrl('_cron_handle_task_02_sku_4_page_num',$query);
                            //                if(!sendRequest($url)) {
                            //                    die("在调用 fsockopen() 时失败，请检查主机是否支持此函数");
                            //                }
                            // try_2 end
                        }
                    }
                }

                $page_num_dto['size']          = sizeof($response['result']['skuIds']);
                $page_num_dto['ignore']        = $_ignore;
                $page_num_dto['resultMessage'] = $response['resultMessage'];


            } // {"success":false,"resultMessage":"pageNum不存在","resultCode":"0010","result":null}
            elseif ($response['success'] == false) {

                if ($response['resultMessage'] == "pageNum不存在"
                    && $response['resultCode'] == "0010"
                ) {

                    if (!isset($page_num_dto['retry'])) {
                        $page_num_dto['retry'] = 0;
                        $this->_redis->lPush($_cron_handle_task_01_page_num, json_encode($page_num_dto, JSON_UNESCAPED_UNICODE));
                    } elseif ($page_num_dto['retry'] < 2) {
                        $page_num_dto['retry'] = $page_num_dto['retry'] + 1;
                        $this->_redis->lPush($_cron_handle_task_01_page_num, json_encode($page_num_dto, JSON_UNESCAPED_UNICODE));
                    } elseif ($page_num_dto['retry'] == 2) {

                        $this->_page_numModel->updateByColumn(array(
                            'deleted' => 1,
                            'state'   => 0
                        ), array(
                            'page_num' => $page_num
                        ));

                    }
                    $page_num_dto['resultMessage'] = $response['resultMessage'];
                }

            }

            return $page_num_dto;


        } else {
            return false;
        }
    }
}