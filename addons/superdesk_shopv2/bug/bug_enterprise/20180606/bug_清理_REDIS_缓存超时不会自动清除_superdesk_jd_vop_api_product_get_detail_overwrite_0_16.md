
bug_清理_REDIS_缓存超时不会自动清除_superdesk_jd_vop_api_product_get_detail_overwrite_0_16


superdesk_jd_vop_api_product_get_detail_overwrite_0_16


_cron_handle_task_777_clean_cache_detail_overwrite_0.inc



Uncaught RangeError: Maximum call stack size exceeded
    at s (jquery.js?v1-1-1:3)
    at m (jquery.js?v1-1-1:3)
    at Function.m [as find] (jquery.js?v1-1-1:3)
    at init.find (jquery.js?v1-1-1:3)
    at new init (jquery.js?v1-1-1:2)
    at e (jquery.js?v1-1-1:2)
    at HTMLDocument.<anonymous> (index.js?v1-1-1:2)
    at o (jquery.js?v1-1-1:2)
    at Object.fireWith (jquery.js?v1-1-1:2)
    at Function.ready (jquery.js?v1-1-1:2)

已清整




# 工具修正
phpRedisAdmin

siteroot

http://39.108.133.103/phpRedisAdmin/

deltree

phpRedisAdmin/js/index.js

```
$('.deltree').click(function(e) {
    e.preventDefault();

    if (confirm('Are you sure you want to delete this whole tree and all it\'s keys?')) {
      $.ajax({
        type: "POST",
        url: this.href,
        data: 'post=1',
        success: function(url) {
          top.location.href = top.location.pathname+url;
        }
      });
    }
  });
```


```
delete.php?s=0&d=0&tree=superdesk_jd_vop_api_product_get_detail_overwrite_0_16:
```

# 程序修正

/addons/superdesk_jd_vop/service/ProductService.class.php

```
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

            $is_insert = true;

        } else if ($overwrite == 0) { // 京东更新 看情况

            $has_update = $this->_redis->ishExists($table_key_overwrite_0,$sku);

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

                // save to temp table start
                $where                                          = array();
                $where['sku']                                   = $sku;
                $result_get_detail_decode['result']['page_num'] = $page_num;
                $this->_product_detailModel->saveOrUpdateByColumn($result_get_detail_decode['result'], $where);
                // save to temp table end

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

            $result_get_detail_decode            = array();
            $result_get_detail_decode["success"] = true;
            $result_get_detail_decode["result"]['name'] = "mode (overwrite = $overwrite) redis (最近更新(isExists == $has_update)) redis (详情缓存(isExists == $has_detail)) sku($sku) : 不从京东同步";

        }

//        $product_service_business_processing_get_detail_one_end = microtime(true);
//        socket_log('ProductService->businessProcessingGetDetailOne 耗时'.round($product_service_business_processing_get_detail_one_end - $product_service_business_processing_get_detail_one_start,4).'秒');


        return $result_get_detail_decode;

    }
```