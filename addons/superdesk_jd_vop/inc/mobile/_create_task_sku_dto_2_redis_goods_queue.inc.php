<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 12/25/17
 * Time: 1:34 PM
 *
 * 企业采购
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=_create_task_sku_dto_2_redis_goods_queue&page=1
 * view-source:https://wxm.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=_create_task_sku_dto_2_redis_goods_queue&page=1
 *
 * 福得内购
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=17&c=entry&m=superdesk_jd_vop&do=_create_task_sku_dto_2_redis_goods_queue&page=1
 * view-source:https://wxn.avic-s.com/app/index.php?i=17&c=entry&m=superdesk_jd_vop&do=_create_task_sku_dto_2_redis_goods_queue&page=1
 */

global $_W, $_GPC;

//include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/task_cron.class.php');
//$task_cron = new task_cronModel();
//
//$_is_ignore = $task_cron->isIgnore(task_cronModel::_cron_handle_task_02_sku_4_page_num);
//
//if ($_is_ignore) {
//    die("ignore task" . PHP_EOL);
//} else {
//    $task_cron->updateLastdo(task_cronModel::_cron_handle_task_02_sku_4_page_num);
//}

//  ------------ 从_cron_handle_task_02_sku_4_page_num复制过来..以上没有改动

/******************************************************* redis *********************************************************/
include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
$_redis = new RedisUtil();
/******************************************************* redis *********************************************************/


$_cron_handle_task_02_sku_4_page_num = '_cron_handle_task_02_sku_4_page_num:' . $_W['uniacid'];

$_DEBUG = isset($_GPC['_DEBUG']) ? true : false;


$queue_lenght = $_redis->lLen($_cron_handle_task_02_sku_4_page_num);// 获取现有消息队列的长度

echo '队列长度为:'.$queue_lenght;
echo PHP_EOL;



//904800

$page = $_GPC['page'];
$page = max(1, intval($page));
$page_size = 100000;

$where_sql = " WHERE uniacid = :uniacid AND status = 1 AND deleted = 0 AND jd_vop_sku > 0";

$params = array(
    ':uniacid' => $_W['uniacid'],
);

$list  = pdo_fetchall(
    " SELECT jd_vop_sku,jd_vop_page_num " .
    " FROM " . tablename('superdesk_shop_goods') .
    $where_sql .
    " ORDER BY updatetime ASC " .
    " LIMIT " . ($page - 1) * $page_size . ',' . $page_size,
    $params
);

foreach($list as $k => $v){
    $task_sku_dto = array(
        'sku'       => $v['jd_vop_sku'],
        'page_num'  => $v['jd_vop_page_num'],
        'overwrite' => 1
    );
    $_redis->rPush($_cron_handle_task_02_sku_4_page_num, json_encode($task_sku_dto));
}

$end = microtime(true);
echo '耗时' . round($end - STARTTIME, 4) . '秒' . PHP_EOL;






