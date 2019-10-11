<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 12/14/17
 * Time: 11:20 AM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_product_get_category
 */

global $_W, $_GPC;

/******************************************************* redis *********************************************************/
//microtime_format('Y-m-d H:i:s.x',microtime())
include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
$_redis = new RedisUtil();
/******************************************************* redis *********************************************************/

include_once(IA_ROOT . '/addons/superdesk_jd_vop/sdk/jd_vop_sdk.php');
$jd_sdk = new JDVIPOpenPlatformSDK();
$jd_sdk->init_access_token();

/** test start 获取某一个分类 **/
//$response = $jd_sdk->api_product_get_category(1316);
//echo "<br>";
//die(json_encode(json_decode($response),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
/** test end   **/

$list = pdo_fetchall(
    'select id,name as db_name,level,enabled,jd_vop_page_num,old_shop_cate_id from ' . tablename('superdesk_shop_category') .
    ' where uniacid=:uniacid '
    , array(':uniacid' => $_W['uniacid'])
);

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/ProductService.class.php');
$_productService = new ProductService();

$i      = 1;
$result = $_productService->getCategorys($i, 5000);

/** 这里是添加所有京东商品到一个备份表 **/
//while(!empty($result['result'])){
//    $category = $result['result']['categorys'];
//    foreach($category as $v){
//        $create_data = array(
//            'id' => $v['catId'],
//            'uniacid' => $_W['uniacid'],
//            'name' => $v['name'],
//            'parentid' => $v['parentId'],
//            'description' => $v['name'],
//            'enabled' => $v['state'],
//            'level' => $v['catClass'] + 1,
//            'jd_vop_page_num' => $v['catId']
//        );
//        $check = pdo_fetch('select id from '.tablename('superdesk_shop_category_jd').' where id=:id',array(':id'=>$v['catId']));
//        if(empty($check)){
//            pdo_insert('superdesk_shop_category_jd',$create_data);
//        }
//    }
//
//    $i++;
//    $result = $_productService->getCategorys($i,5000);
//}
//
//show_json(1);
/** 备份结束 **/

//对比错误.
$diff_array   = array();
$category_all = array();
while (!empty($result['result'])) {
    $category = $result['result']['categorys'];

    $category_all = array_merge($category_all, $category);
    $diff_result  = getDiff($category, $list);

    $diff       = $diff_result['diff_array'];
    $diff_array = array_merge($diff_array, $diff);

    $i++;
    $result = $_productService->getCategorys($i, 5000);
}


//这是对比京东有而我们没有的
//京东有而我们没有
$jd_lack_array = array();
$list_id_array = array_column($list, 'id');
foreach ($category_all as $v) {
    if (!in_array($v['catId'], $list_id_array)) {
        $jd_lack_array[] = $v;
    }
}
//京东没有而我们有
$shop_lack_array = array();
$category_id     = array_column($category_all, 'catId');
foreach ($list as $v) {
    if (!in_array($v['id'], $category_id)) {
        $shop_lack_array[] = $v;
    }
}


print_r($diff_array);
echo 'jd_lack<br/>';
print_r($jd_lack_array);
echo 'shop_lack<br/>';
print_r($shop_lack_array);
echo 'list_id<br/>';
$list_id_array = array_column($list, 'id');
print_r($list_id_array);

print_r('jd_lack_count:' . count($jd_lack_array));
print_r('shop_lack_count:' . count($shop_lack_array));
die;

function getDiff($category, $list)
{
    $diff_array = array();
    foreach ($category as $v) {
        foreach ($list as &$l) {
            if ($v['catId'] == $l['id']) {
                $l         = array_merge($l, $v);
                $l['diff'] = '';
                if ($v['catClass'] + 1 != $l['level']) {
                    $l['diff']    .= '等级不对';
                    $diff_array[] = $l;
                }
                if ($v['name'] != $l['db_name']) {
                    $l['diff']    .= '名称不对';
                    $diff_array[] = $l;
                }
            }
        }
    }
    unset($l);

    return compact('diff_array');
}