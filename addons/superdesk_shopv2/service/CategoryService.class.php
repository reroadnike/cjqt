<?php

include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/category.class.php');

/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 1/22/18
 * Time: 7:09 PM
 */
class CategoryService
{

    private $_redis;
    private $_categoryModel;

    function __construct()
    {
        $this->_redis = new RedisUtil();

        $this->_categoryModel = new categoryModel();

    }

    public function getByPageNum($page_num)
    {

        return $this->_categoryModel->getOneByColumn(array(
            'jd_vop_page_num' => $page_num
        ));
    }

    public function getByIdAndLevel2($id)
    {
        return $this->_categoryModel->getOneByColumn(array(
            'id'    => $id,
            'level' => 2
        ));
    }

    public function getNameById($id)
    {

        // TODO cache
        return $this->_categoryModel->getNameById($id);
    }

    public function setCacheOldCateId2NewCateId($old_cate_id, $new_cate_id)
    {

        $cache_key = 'superdesk_shop_member_' . 'cache_oldcateid2newcateid' . ':' . $_W['uniacid'];

        $this->_redis->hset($cache_key, $old_cate_id, $new_cate_id);


    }

    public function getCacheOldCateId2NewCateId($old_cate_id)
    {

        $cache_key = 'superdesk_shop_member_' . 'cache_oldcateid2newcateid' . ':' . $_W['uniacid'];

        if ($this->_redis->ishExists($cache_key, $old_cate_id) == 1) {

            $new_cate_id = $this->_redis->hget($cache_key, $old_cate_id);

            return $new_cate_id;

        } else {
            return 0;
        }
    }
}