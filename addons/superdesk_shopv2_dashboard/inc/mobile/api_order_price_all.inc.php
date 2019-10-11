<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 5/16/19
 * Time: 11:27 AM
 *
 * http://localhost/superdesk/app/index.php?i=16&c=entry&m=superdesk_shopv2_dashboard&do=api_order_price_all
 */

global $_GPC,$_W;

include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
$_redis = new RedisUtil();

$redis_key = 'superdesk_shop_order_price_all_cache_' . $_W['uniacid'];

if(!$_redis->isExists($redis_key)){

    $price = pdo_fetchcolumn(
        ' SELECT ifnull(sum(price),0) as cnt FROM ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 不处理
        ' WHERE uniacid=:uniacid and status>=1',
        array(
            ':uniacid' => $_W['uniacid']
        )
    );

    $data = array(
        'price' => $price,
        'datetime' => time()
    );

    $_redis->set($redis_key, json_encode($data));
}else{

    $data = $_redis->get($redis_key);
    $data = json_decode($data,true);

    $nextDate = pdo_fetchcolumn(
        ' SELECT createtime FROM ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 不处理
        ' WHERE uniacid=:uniacid and status>=1 order by id desc',
        array(
            ':uniacid' => $_W['uniacid']
        )
    );

    //假如最新的一条在1天后,则刷新缓存
    if(($data['datetime'] + (60 * 60 * 24)) < $nextDate){

        $price = pdo_fetchcolumn(
            ' SELECT ifnull(sum(price),0) as cnt FROM ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 不处理
            ' WHERE uniacid=:uniacid and status>=1',
            array(
                ':uniacid' => $_W['uniacid']
            )
        );

        $data = array(
            'price' => $price,
            'datetime' => time()
        );

        $_redis->set($redis_key, json_encode($data));
    }

}
$price = $data['price'];

show_json(1,['price' => $price]);