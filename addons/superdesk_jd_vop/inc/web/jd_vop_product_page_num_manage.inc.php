<?php
/**
 * 京东商品池同步商品
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/29/17
 * Time: 2:01 PM
 */

global $_GPC, $_W;

$_DEBUG = true;

//include_once(IA_ROOT . '/addons/superdesk_shopv2/model/category.class.php');
//$_categoryModel = new categoryModel();

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/page_num.class.php');
$_page_numModel = new page_numModel();

/******************************************************* redis *********************************************************/
//microtime_format('Y-m-d H:i:s.x',microtime())
include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
$_redis = new RedisUtil();


/******************************************************* redis *********************************************************/

$key = 'superdesk_jd_vop_' . 'web_shop_product_category_manage' . ':' . $_W['uniacid'];


if ($_DEBUG) {
    $_redis->del($key);
}


if ($_redis->isExists($key) == 1) {

    $zNodes = $_redis->get($key);

} else {
    $zNodes = $_page_numModel->zTreeV3Data(array(
        'deleted' => 0,
        'state'   => 1
    ));
    $_redis->set($key, $zNodes);
}

//{"id":"666","tId":"666","pId":"0","name":"防爆工具","page_num":"132004301"}


include $this->template('jd_vop_product_page_num_manage');