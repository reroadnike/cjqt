<?php


include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/sdk/jd_vop_sdk.php');

/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 6/11/18
 * Time: 4:54 PM
 */
class SearchService
{
    private $jd_sdk;
    private $_redis;


    function __construct()
    {

        $this->_redis = new RedisUtil();

        $this->jd_sdk = new JDVIPOpenPlatformSDK();
//        $this->jd_sdk->debug = true;
        $this->jd_sdk->init_access_token();


    }


    public function searchSearch($keyword, $catId = 0, $pageIndex = 1, $pageSize = 1, $min = 0, $max = 0, $brands = '')
    {

        global $_W;
        global $_GPC;

        $response = $this->jd_sdk->api_search_search($keyword, $catId, $pageIndex, $pageSize, $min, $max, $brands);

        $response = json_decode($response, true);

        if(!$response['success']){
            return false;
        }

        $jd_category = $response['result']['categoryAggregate'];

        //这里需要先判断一下.因为有可能出现京东也没有返回的情况. zjh 2018年8月9日 14:40:22
        $new_first = array();
        $new_second = array();
        $new_thrid = array();
        if(!empty($jd_category)){

            $jd_category_first = array_column($jd_category['firstCategory'],'catId');
            $jd_category_second = array_column($jd_category['secondCategory'],'catId');
            $jd_category_thrid = array_column($jd_category['thridCategory'],'catId');
            //查找我们自己的分类,排除掉分类
            $category = m('shop')->getFullCategory();

            foreach($category as $k => $v){
                if(in_array($v['id'],$jd_category_first)){
                    $new_first[] = $v;
                }
            }
            foreach($category as $k => $v){
                if(in_array($v['id'],$jd_category_second)){
                    $new_second[] = $v;
                }
            }
            foreach($category as $k => $v){
                if(in_array($v['id'],$jd_category_thrid)){
                    $new_thrid[] = $v;
                }
            }
        }

        $result = $response['result'];
        $result['categoryAggregate'] = array(
            'firstCategory' => $new_first,
            'secondCategory' => $new_second,
            'thridCategory' => $new_thrid,
        );

        return $result;

    }
}