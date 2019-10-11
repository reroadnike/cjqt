<?php
/**
 * 关于商品
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/7/17
 * Time: 5:58 PM
 *
 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_jd_vop&do=do_product_get_detail_step02
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_product_get_sku
 */

global $_W, $_GPC;

echo "测试 reset";
echo "<br>";
echo "执行 UPDATE `ims_superdesk_jd_vop_product_detail` set updatetime = 0";
echo "<br><br> ↓　<br><br>";

echo "更新updatetime";

echo "<br>";
echo "_product_detailModel->callbackForJdVopApiProductDetailUpdate";
echo "<br><br> ↓　<br><br>";

/******************************************************* redis *********************************************************/
//microtime_format('Y-m-d H:i:s.x',microtime())
include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
$_redis = new RedisUtil();
/******************************************************* redis *********************************************************/

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/goods/goods.class.php');
$_goodsModel = new goodsModel();

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/product_detail.class.php');
$_product_detailModel = new product_detailModel();

//include_once(IA_ROOT . '/addons/superdesk_jd_vop/sdk/jd_vop_sdk.php');
//$jd_sdk = new JDVIPOpenPlatformSDK();
//$jd_sdk->init_access_token();




$result = $_product_detailModel->queryForJdVopApiProductDetailUpdate();

$total = $result['total'];
$page = $result['page'];
$page_size = $result['page_size'];
$list = $result['data'];


if($total == 0 ){
    echo "Task end : There's no more data in 24 hours";
    return;
} else {
    echo "Task start ... :: total " . $total . "|" . $page_size;
    echo "<br>";
    var_dump($list);
    echo "<br><br> ↓　<br><br>";
}

$skuId = array();
foreach ($list as $index => $item){



    $skuId[] = $item['sku'];

    $_product_detail = $_product_detailModel->getOneBySku($item['sku']);

//    echo $index . " " . $item['sku'] . " " . json_encode($item);
//    echo json_encode($_product_detail);
//    echo "<br/>";

    // 分销商城sku表入库 TODO 目前分类是没有设置的
    $_goodsModel->saveOrUpdateByJdVopApiProductGetDetail(
        $_product_detail,
        $_product_detail['sku']
    );

}

//$skuStr = implode(",",$skuId);

$_product_detailModel->callbackForJdVopApiProductDetailUpdate($skuId);



