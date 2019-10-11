<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 1/26/18
 * Time: 7:47 PM
 * SELECT COUNT(0) FROM `ims_superdesk_jd_vop_product_price` WHERE updatetime <>0
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=_cron_handle_task_04_sku_price_update_100
 *
 * http://www.avic-s.com/plugins/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=_cron_handle_task_04_sku_price_update_100
 */
global $_W, $_GPC;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/task_cron.class.php');
$task_cron = new task_cronModel();

$_is_ignore = $task_cron->isIgnore(task_cronModel::_cron_handle_task_04_sku_price_update_100);


if ($_is_ignore) {
    die("ignore task" . PHP_EOL);
} else {
    $task_cron->updateLastdo(task_cronModel::_cron_handle_task_04_sku_price_update_100);
}



include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/PriceService.class.php');
$_priceService = new PriceService();// ok


$msg = $_priceService->runQuerySkuForUpdatePrice();

$end = microtime(true);
echo $msg . PHP_EOL;
echo '耗时' . round($end - STARTTIME, 4) . '秒' . PHP_EOL;
