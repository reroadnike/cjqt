<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/05/15
 * Time: 15:09
 * http://192.168.1.124/smart_office_building/web/index.php?c=site&a=entry&m=superdesk_recovery&do=cc_superdesk_shop_goods_cc_sku
 */

global $_GPC, $_W;

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
$_redis = new RedisUtil();
$key = $_W['uniacid'] . '_cc_superdesk_shop_area_check';

if ($op == 'list') {

    include $this->template('cc_superdesk_shop_area_check');

}else if($op == 'edit'){
    include_once(IA_ROOT . '/addons/superdesk_jd_vop/sdk/jd_vop_sdk.php');
    $jd_sdk = new JDVIPOpenPlatformSDK();
    $jd_sdk->init_access_token();

//4.1  获取一级地址
    $response_province = $jd_sdk->api_area_get_province();
    $jd_province_array = json_decode($response_province,true);
    $jd_province_array = $jd_province_array['result'];

    $diff_province = array();
    $diff_city = array();
    $diff_county = array();
    $diff_town = array();
    $diff_province = getDiff($jd_province_array);
    foreach ($jd_province_array as $_index => $province) {

        //4.2  获取二级地址
        $response_city = $jd_sdk->api_area_get_city($province);
        $jd_city_array = json_decode($response_city,true);
        $jd_city_array = $jd_city_array['result'];


        $diff_city_part = array();
        $diff_city_part = getDiff($jd_city_array,$province);
        $diff_city = array_merge($diff_city,$diff_city_part);

        foreach ($jd_city_array as $__index => $city) {

            //4.3  获取三级地址
            $response_county = $jd_sdk->api_area_get_county($city);
            $jd_county_array = json_decode($response_county,true);
            $jd_county_array = $jd_county_array['result'];

            $diff_county_part = array();
            $diff_county_part = getDiff($jd_county_array,$city);
            $diff_county = array_merge($diff_county,$diff_county_part);

            foreach ($jd_county_array as $___index => $county) {

                //4.4  获取四级地址
                $response_town = $jd_sdk->api_area_get_town($county);
                $jd_town_array = json_decode($response_town,true);
                $jd_town_array = $jd_town_array['result'];

                $diff_town_part = array();
                $diff_town_part = getDiff($jd_town_array,$county);
                $diff_town = array_merge($diff_town,$diff_town_part);

            }
        }
    }

    $diff_province = json_encode($diff_province);
    $diff_city = json_encode($diff_city);
    $diff_county = json_encode($diff_county);
    $diff_town = json_encode($diff_town);

    message('成功！', $this->createWebUrl('cc_superdesk_shop_area_check', array('op' => 'list')), $diff_province.$diff_city.$diff_county.$diff_town);

}

function getDiff($jd,$parentId = 0){

    $diff = array();
    if(!empty($jd)){
        $diff = pdo_fetchall('select code,text from ' . tablename('superdesk_jd_vop_area') . ' where `state` = 1 `parent_code` = ' . $parentId . ' and code not in (' . implode(",", $jd) . ')');

        include_once(IA_ROOT . '/framework/library/logs/LogsUtil.class.php');
        LogsUtil::logging('info',json_encode($diff, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),'jd_area');

        pdo_update('superdesk_jd_vop_area',array('state'=>0),array('parent_code'=>$parentId));

        $rs = pdo_query('update ' . tablename('superdesk_jd_vop_area') . ' set `state` = 1 where `parent_code` = ' . $parentId . ' and code in (' . implode(",", $jd) . ')');
    }


    return $diff;
}
