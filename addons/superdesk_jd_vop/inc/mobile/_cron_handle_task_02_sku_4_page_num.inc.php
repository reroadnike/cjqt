<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 12/25/17
 * Time: 1:34 PM
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=_cron_handle_task_02_sku_4_page_num
 */

global $_W, $_GPC;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/task_cron.class.php');
$task_cron = new task_cronModel();

$_is_ignore = $task_cron->isIgnore(task_cronModel::_cron_handle_task_02_sku_4_page_num);

if ($_is_ignore) {
    die("ignore task" . PHP_EOL);
} else {
    $task_cron->updateLastdo(task_cronModel::_cron_handle_task_02_sku_4_page_num);
}




$_DEBUG = isset($_GPC['_DEBUG']) ? true : false;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/ProductService.class.php');
$_productService = new ProductService();

$result = $_productService->runQueryGetSkuByPageForTask($_DEBUG);

echo json_encode($result,JSON_UNESCAPED_UNICODE);
$end = microtime(true);
echo '耗时' . round($end - STARTTIME, 4) . '秒'.PHP_EOL;




