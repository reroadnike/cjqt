<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 3/15/18
 * Time: 11:04 AM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=_cron_handle_task_777_clean_cache_detail_overwrite_0
 *
 * view-source:http://www.avic-s.com/plugins/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=_cron_handle_task_777_clean_cache_detail_overwrite_0
 */

global $_W, $_GPC;

//include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/task_cron.class.php');
//$task_cron = new task_cronModel();
//
//$_is_ignore = $task_cron->isIgnore(task_cronModel::_cron_handle_task_777_clean_cache_detail_overwrite_0);
//
//if ($_is_ignore) {
//    die("ignore task" . PHP_EOL);
//} else {
//    $task_cron->updateLastdo(task_cronModel::_cron_handle_task_777_clean_cache_detail_overwrite_0);
//}

/******************************************************* redis *********************************************************/
include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
$_redis = new RedisUtil();
/******************************************************* redis *********************************************************/


$key_check = 'superdesk_jd_vop_' . 'api_product_get_detail_overwrite_0' . '_' . $_W['uniacid'];


$keys = $_redis->keyAll($key_check . "*");// 获取现有消息队列的长度


foreach ($keys as $key){
    echo $key;
//    $_redis->del($key);

//    if($_redis->get($key) == 1){
//
//        echo "=>ALIVE";
//    } else {
//        $_redis->del($key);
//        echo "=>DELETE";
//
//    }
    echo PHP_EOL;
}
