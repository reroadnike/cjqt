<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 12/25/17
 * Time: 6:08 PM
 *
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=_cron_handle_task_03_sku_detail
 * view-source:http://www.avic-s.com/plugins/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=_cron_handle_task_03_sku_detail
 */
global $_W, $_GPC;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/task_cron.class.php');
$task_cron = new task_cronModel();

$_is_ignore = $task_cron->isIgnore(task_cronModel::_cron_handle_task_03_sku_detail);

if ($_is_ignore) {
    die("ignore task" . PHP_EOL);
} else {
    $task_cron->updateLastdo(task_cronModel::_cron_handle_task_03_sku_detail);
}



/******************************************************* redis *********************************************************/
include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
$_redis = new RedisUtil();
/******************************************************* redis *********************************************************/

//include_once(IA_ROOT . '/addons/superdesk_jd_vop/sdk/sfc.functions.php');

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/ProductService.class.php');
$_productService = new ProductService();



$_cron_handle_task_02_sku_4_page_num = '_cron_handle_task_02_sku_4_page_num:' . $_W['uniacid'];

$_DEBUG = false;


$queue_lenght = $_redis->lLen($_cron_handle_task_02_sku_4_page_num);// 获取现有消息队列的长度

//    DELETE FROM `ims_superdesk_shop_goods` WHERE jd_vop_sku = 280217
//    SELECT *  FROM `ims_superdesk_shop_goods` WHERE jd_vop_sku = 280217


echo '队列长度为:'.$queue_lenght;
echo PHP_EOL;

if ($queue_lenght > 0 || $_DEBUG) {

    $count = 0;

//    while ($count < 10 && $count < $queue_lenght) { echo $count ;

    if ($_DEBUG) {
        $sku_str = '{"sku":280217,"page_num":"333333","overwrite":1}';
    } else {
        $sku_str = $_redis->lPop($_cron_handle_task_02_sku_4_page_num);
    }

    $sku_dto = json_decode($sku_str, true);

//    $query = array(
//        'overwrite' => $sku_dto['overwrite'],
//        'page_num'  => $sku_dto['page_num'],
//        'sku'       => $sku_dto['sku']
//    );

    $sku       = $sku_dto['sku'];
    $page_num  = $sku_dto['page_num'];
    $overwrite = intval($sku_dto['overwrite']);

    $result = $_productService->businessProcessingGetDetailOne($sku,$page_num,$overwrite);

    if($result['success'] == true){
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
    }
    echo PHP_EOL;

    $end = microtime(true);
    echo '耗时' . round($end - STARTTIME, 4) . '秒' . PHP_EOL;

//    $url = $this->createAngularJsUrl('api_product_get_detail_one', $query);
//
//    if (!sendRequest($url)) {
//        $end = microtime(true);
//        echo '耗时' . round($end - STARTTIME, 4) . '秒'.PHP_EOL;
//        die("在调用 fsockopen() 时失败，请检查主机是否支持此函数");
//    } else {
//        $end = microtime(true);
//        echo '耗时' . round($end - STARTTIME, 4) . '秒'.PHP_EOL;
//        die(json_encode($query, JSON_UNESCAPED_UNICODE) . " async " . $count . "::" . $queue_lenght . PHP_EOL);
//    }
//        $count = $count + 1;
//    }


}

