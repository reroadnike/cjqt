<?php
/**
 * 京东四级地址
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/29/17
 * Time: 2:01 PM
 */

global $_GPC, $_W;

$_DEBUG = true;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/area.class.php');
$_areaModel = new areaModel();

/******************************************************* redis *********************************************************/
//microtime_format('Y-m-d H:i:s.x',microtime())
include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
$_redis = new RedisUtil();


/******************************************************* redis *********************************************************/

$key     = 'superdesk_jd_vop_' . 'web_jd_vop_area_manage' . ':' . $_W['uniacid'];


if($_DEBUG){
    $_redis->del($key);
}


if ($_redis->isExists($key) == 1) {

    $zNodes = $_redis->get($key);

} else {

    $zNodes = $_areaModel->zTreeV3CityData();
    $_redis->set($key, $zNodes);
}




include $this->template('jd_vop_area_manage');