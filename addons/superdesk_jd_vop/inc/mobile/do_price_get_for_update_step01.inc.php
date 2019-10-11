<?php
/**
 *
 * 关于价格
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/9/17
 * Time: 6:05 PM
 *
 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_jd_vop&do=do_price_get_for_update_step01
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_product_get_sku
 */

echo "测试 reset";
echo "<br>";
echo "执行 UPDATE `ims_superdesk_jd_vop_product_price` set updatetime = 0";
echo "<br><br> ↓　<br><br>";


echo "从数据库 临时表 得到　24小时内未更新过价格的数据 默认 x 100 skuId";

echo "<br><br> ↓　<br><br>";

echo "组装skuId => [1,2,3] , 调用京东VOP api 5.2  批量查询协议价价格";

echo "<br><br> ↓　<br><br>";

echo "调整价格";

echo "<br>";
echo " 协议价格 = 成本价 = 结算价";

echo "<br>";
echo " 京东价格 = 原本价 = 商品原价";

echo "<br>";
echo " 客户购买价格 = 现价 = 商品现价";

echo "<br><br> ↓　<br><br>";

echo "数据保存";

echo "<br>";
echo "_product_priceModel->saveOrUpdateByColumn // 临时价格保存 superdesk_jd_vop_product_price | ";
echo "<br>";
echo "_goodsModel->updateByJdVopApiPriceUpdate // 正式商品保存 superdesk_shop_goods | ";


echo "<br><br> ↓　<br><br>";

echo "更新updatetime";

echo "<br>";
echo "_product_priceModel->callbackForJdVopApiPriceUpdate";
echo "<br><br> ↓　<br><br>";

global $_W, $_GPC;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/product_detail.class.php');
$_product_detailModel = new product_detailModel();

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/product_price.class.php');
$_product_priceModel = new product_priceModel();

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/goods/goods.class.php');
$_goodsModel = new goodsModel();

include_once(IA_ROOT . '/addons/superdesk_jd_vop/sdk/jd_vop_sdk.php');
$jd_sdk = new JDVIPOpenPlatformSDK();
$jd_sdk->init_access_token();



// http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_jd_vop&do=do_price_get_for_update_step01

// 从数据库 临时表 得到　24小时内未更新过价格的数据 默认 x 100 sku
$result    = $_product_priceModel->queryForJdVopApiPriceUpdate();
$total     = $result['total'];
$page      = $result['page'];
$page_size = $result['page_size'];
$list      = $result['data'];

if($total == 0 ){

    echo "Task end : There's no more data in 24 hours";
    return;
}else {
    echo "Task start ... :: total " . $total . "|" . $page_size;
    echo "<br>";
    var_dump($list);
    echo "<br><br> ↓　<br><br>";
}

//{"success":false,"resultMessage":"参数不能为空","resultCode":"1001","result":null}


$skuId = array();
foreach ($list as $index => $item){

//    echo $index . " ";
//    echo json_encode($item);
//    echo "<br/>";

    $skuId[] = $item['skuId'];

}

$skuStr = implode(",",$skuId);


//5.1  批量查询京东价价格
//$response_api_price_get_jd_price = $jd_sdk->api_price_get_jd_price($skuStr);

//5.2  批量查询协议价价格
$response_api_price_get_price = $jd_sdk->api_price_get_price($skuStr);
$json_api_price_get_price = json_decode($response_api_price_get_price,true);

//5.3  批量查询商品售卖价
$response_api_price_get_sell_price = $jd_sdk->api_price_get_sell_price($skuStr);
$json_api_price_get_sell_price = json_decode($response_api_price_get_sell_price,true);


//{"skuId":111111, "price":客户购买价格or协议价价格, "jdPrice":京东价格}






$params = array();
if($json_api_price_get_price['success'] == true
//    && $json_api_price_get_sell_price['success'] == true
){


    // 合并数据
    foreach ($json_api_price_get_price['result'] as $index => $sku_price){

        $jdPrice = $sku_price['jdPrice'];// jdvop_京东价
        $price = $sku_price['price'];// jdvop_协议价

        $jdPrice = floatval($jdPrice);// jdvop_京东价
        $price = floatval($price);// jdvop_协议价


        $params[$sku_price['skuId']] = array();
        $params[$sku_price['skuId']]['skuId'] = $sku_price['skuId'];

        $params[$sku_price['skuId']]['costprice'] = $price;// 协议价格 成本 | NO | decimal(10,2) | 0.00 结算价 agreementprice 在企业购那是不能修改的

//        $params[$sku_price['skuId']]['productprice'] = $sku_price['jdPrice'];// 京东价格 原价 | NO | decimal(10,2) | 0.00 商品原价 proPrice => oldprice
//        $params[$sku_price['skuId']]['marketprice'] = $sku_price['price'];// 客户购买价格 现价 | NO | decimal(10,2) | 0.00 商品现价 productPrice =>referprice

        // 原价的计算 round() 函数对浮点数进行四舍五入。
        if ($jdPrice > $price) {
            $referprice = round((($jdPrice - $price) * 0.7 + $price) * 100) / 100;//membershop_商品现价
            $params[$sku_price['skuId']]['productprice'] = $jdPrice;//membershop_商品原价
        } else {
            // TODO 这情况不就是现价与原价同价?
            $referprice = round(($price * 1.05) * 100) / 100;//membershop_商品现价
            $params[$sku_price['skuId']]['productprice'] = $referprice;//membershop_商品原价
        }

        $params[$sku_price['skuId']]['marketprice'] = $referprice;

    }

//    foreach ($json_api_price_get_sell_price['result'] as $index => $sku_price){
//        $params[$sku_price['skuId']]['costprice'] = $sku_price['price'];// 协议价格 成本 | NO | decimal(10,2) | 0.00 结算价 agreementprice 在企业购那是不能修改的
//    }

} else {
    // TODO 出错信息
}

//print_r($params);

foreach ($params as $key => $sku_price_4_jd_tmp_insert){

    // {"skuId":270669,"productprice":99,"marketprice":82,"costprice":82}
//    echo json_encode($sku_price_4_jd_tmp_insert);
//    echo "<br/>";

    $column = array(
        "skuId" => $sku_price_4_jd_tmp_insert['skuId']
    );

    // 临时价格保存 superdesk_jd_vop_product_price
    $_product_priceModel->saveOrUpdateByColumn(
        $sku_price_4_jd_tmp_insert/*保存的数据*/,
        $column/*sku*/);



    // 正式商品保存 superdesk_shop_goods
    $_goodsModel->updateByJdVopApiPriceUpdate(
        $sku_price_4_jd_tmp_insert/*保存的数据*/,
        $sku_price_4_jd_tmp_insert['skuId']/*sku*/);

}

$_product_priceModel->callbackForJdVopApiPriceUpdate($skuId);

