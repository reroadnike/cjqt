


<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/29/17
 * Time: 2:01 PM
 */

global $_GPC, $_W;

$_DEBUG = true;

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/category.class.php');
$_categoryModel = new categoryModel();

/******************************************************* redis *********************************************************/
//microtime_format('Y-m-d H:i:s.x',microtime())
include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
$_redis = new RedisUtil();


/******************************************************* redis *********************************************************/

$key     = 'superdesk_jd_vop_' . 'web_jd_vop_product_page_num_manage' . ':' . $_W['uniacid'];


if($_DEBUG){
    $_redis->del($key);
}


if ($_redis->isExists($key) == 1) {

    $zNodes = $_redis->get($key);

} else {
    $zNodes = $_categoryModel->zTreeV3Data4Test();
    $_redis->set($key, $zNodes);
}




include $this->template('shop_product_category_manage');









