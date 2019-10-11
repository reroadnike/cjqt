<?php
//namespace sdk\jd_vop\service;
//use sdk\jd_vop\service;

include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/sdk/jd_vop_sdk.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/area.class.php');


/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/23/17
 * Time: 4:57 PM
 */
class AreaService
{


    private $jd_sdk;
    private $_redis;

    private $_areaModel;

    function __construct()
    {

        $this->_redis = new RedisUtil();

        $this->jd_sdk = new JDVIPOpenPlatformSDK();
//        $this->jd_sdk->debug = true;
        $this->jd_sdk->init_access_token();

        $this->_areaModel = new areaModel();

    }

    /**
     * 4.5  验证四级地址是否正确
     * @param     $provinceId
     * @param     $cityId
     * @param int $countyId
     * @param int $townId
     *
     * @return mixed
     */
    public function checkArea($provinceId, $cityId, $countyId = 0, $townId = 0)
    {

        global $_W;
        global $_GPC;

        $response = $this->jd_sdk->api_area_check_area($provinceId, $cityId, $countyId, $townId);

        $response = json_decode($response, true);

        return $response;

    }

    public function jdVopAreaCascade($parent_code = 0)
    {
        global $_W;
        global $_GPC;

        $table_redis = 'superdesk_jd_vop_' . 'api_area' . '_' . $_W['uniacid'];

        $has_cache = $this->_redis->ishExists($table_redis, $parent_code);

        if ($has_cache == 1) {
            $js = $this->_redis->hget($table_redis, $parent_code);
        } else {
            $js = $this->_areaModel->jdVopAreaCascade($parent_code);
            $this->_redis->hset($table_redis, $parent_code, $js);
        }

        return $js;

    }
    
    public function jdVopAreaCascadeClearCache($parent_code = 0){

        global $_W;
        global $_GPC;

        $table_redis = 'superdesk_jd_vop_' . 'api_area' . '_' . $_W['uniacid'];

        $this->_redis->hDel($table_redis,$parent_code);
    }
}