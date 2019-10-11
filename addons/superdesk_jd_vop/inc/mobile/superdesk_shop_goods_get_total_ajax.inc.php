<?php
/**
 * 定时任务 更新 总商户端_商品管理_各状态商品总数问题
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 12/4/17
 * Time: 11:23 AM
 *
 * 企业采购
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=superdesk_shop_goods_get_total_ajax
 * view-source:https://wxm.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=superdesk_shop_goods_get_total_ajax
 */
global $_GPC;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/task_cron.class.php');
$task_cron = new task_cronModel();

$_is_ignore = $task_cron->isIgnore(task_cronModel::superdesk_shop_goods_get_total_ajax);

if ($_is_ignore) {
    die("ignore task" . PHP_EOL);
} else {
    $task_cron->updateLastdo(task_cronModel::superdesk_shop_goods_get_total_ajax);
}

$all_count_type = ['sale', 'out', 'stock', 'cycle', 'check'];
$redis_id       = $_GPC['type'];

if (!empty($redis_id)) {

    if (!in_array($redis_id, $all_count_type)) {
        die('非法传入');
    }

}

$merch_id = $_GPC['merch_id'];

// TODO 较正 传出type 是否在数组中

if (!empty($redis_id)) {

    setRedisgoodsCount($redis_id);

} else {

    foreach ($all_count_type as $k => $v) {

        setRedisGoodsCount($v);

    }

}


//        $_redis->hDel($redis_key,$redis_id);

function setRedisGoodsCount($redis_id)
{

    global $_W;

    include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
    $_redis = new RedisUtil();

    $redis_key = 'superdesk_shop_goods_cache_getGoodsTotals:' . $_W['uniacid'];

    switch ($redis_id) {
        case 'sale' :
            $goodsTotals = pdo_fetchcolumn(
                ' select count(1) ' .
                ' from ' . tablename('superdesk_shop_goods') .
                ' where ' .
                '   status=1 ' .
                '   and checked=0 ' .
                '   and deleted=0 ' .
                '   and total>0 ' .
                '   and uniacid=:uniacid',
                array(
                    ':uniacid' => $_W['uniacid']
                )
            );
            break;
        case 'out' :
            $goodsTotals = pdo_fetchcolumn(
                ' select count(1) ' .
                ' from ' . tablename('superdesk_shop_goods') .
                ' where ' .
                '   status=1 ' .
                '   and deleted=0 ' .
                '   and total=0 ' .
                '   and uniacid=:uniacid',
                array(
                    ':uniacid' => $_W['uniacid']
                )
            );
            break;
        case 'stock' :
            $goodsTotals = pdo_fetchcolumn(
                ' select count(1) ' .
                ' from ' . tablename('superdesk_shop_goods') .
                ' where ' .
                '   (status=0 or checked=1) ' .
                '   and deleted=0 ' .
                '   and uniacid=:uniacid',
                array(
                    ':uniacid' => $_W['uniacid']
                )
            );
            break;
        case 'cycle' :
            $goodsTotals = pdo_fetchcolumn(
                ' select count(1) ' .
                ' from ' . tablename('superdesk_shop_goods') .
                ' where ' .
                '   deleted=1 ' .
                '   and uniacid=:uniacid',
                array(
                    ':uniacid' => $_W['uniacid']
                )
            );
            break;
        case 'check' :
            $goodsTotals = pdo_fetchcolumn(
                ' select count(1) ' .
                ' from ' . tablename('superdesk_shop_goods') .
                ' where ' .
                '   deleted=0 ' .
                '   and checked = 1 ' .
                '   and uniacid=:uniacid',
                array(
                    ':uniacid' => $_W['uniacid']
                )
            );
            break;
    }

    $goodsTotals = [
        'count'      => $goodsTotals,
        'updatetime' => time()
    ];

    $_redis->hset($redis_key, $redis_id, json_encode($goodsTotals));
}

$end = microtime(true);
echo '耗时' . round($end - STARTTIME, 4) . '秒' . PHP_EOL;