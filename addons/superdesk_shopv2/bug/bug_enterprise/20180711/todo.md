
# 已加入pm

20180711_整理_task_01_价格计算公式修正_陈文礼
20180711_整理_task_02_筛选出_京东_上架_未被删除_商品_插入任务队列



task_01_价格计算公式修正_陈文礼


/data/wwwroot/default/superdesk/addons/superdesk_jd_vop/service/PriceService.class.php

public function getPrice($skuArr)


```
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

//        $params[$sku_price['skuId']]['productprice'] = $sku_price['jdPrice'];// 京东价格 原价 | NO | decimal(10,2) | 0.00 商品原价 proPrice => oldprice
//        $params[$sku_price['skuId']]['marketprice'] = $sku_price['price'];// 客户购买价格 现价 | NO | decimal(10,2) | 0.00 商品现价 productPrice =>referprice

                // 原价的计算 round() 函数对浮点数进行四舍五入。
                if ($jdPrice > $price) {
                    $referprice                                  = round((($jdPrice - $price) * 0.7 + $price) * 100) / 100;//membershop_商品现价
                    $params[$sku_price['skuId']]['productprice'] = $jdPrice;//membershop_商品原价
                } else {
                    // TODO 这情况不就是现价与原价同价?
                    $referprice                                  = round(($price * 1.05) * 100) / 100;//membershop_商品现价
                    $params[$sku_price['skuId']]['productprice'] = $referprice;//membershop_商品原价
                }

                $params[$sku_price['skuId']]['marketprice'] = $referprice;
```



task_02_筛选出_京东_上架_未被删除_商品_插入任务队列

## step 01 
```
table shop_goods 按 where deleted = 0 and status = 上架 and jd_vop_sku > 0 ordery by updatetime asc 

$where_sql .= " AND jd_vop_sku > 0 ";
$where_sql .= " AND deleted = 0 ";
$where_sql .= " AND status = 1 "; // 0 下架 1 上架 2 增品

goods.jd_vop_sku
goods.jd_vop_page_num | default 0 (number|integer)
overwrite default 1
```

## step 02
```
$_cron_handle_task_02_sku_4_page_num = '_cron_handle_task_02_sku_4_page_num:' . $_W['uniacid'];

$task_sku_dto = array(
    'sku'       => goods.jd_vop_sku,
    'page_num'  => goods.jd_vop_page_num,
    'overwrite' => 1
);
$this->_redis->rPush($_cron_handle_task_02_sku_4_page_num, json_encode($task_sku_dto));
echo '加入同步队列 ';
```

