<?php

include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/__plugin__/merch/merch_account.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/__plugin__/merch/merch_user.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/__plugin__/merch/merch_x_enterprise.class.php');

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/category.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/goods/goods.class.php');

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_goods.class.php');
include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_classify.class.php');

include_once(IA_ROOT . '/addons/superdesk_shopv2/service/CategoryService.class.php');


/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 1/18/18
 * Time: 9:49 PM
 */
class MerchService
{
    private $_categoryModel;
    private $_goodsModel;

    private $_sll_goodsModel;
    private $_sll_classifyModel;

    private $_merch_accountModel;
    private $_merch_userModel;
    private $_merch_x_enterpriseModel;

    private $_categoryService;

    private $_redis;

    function __construct()
    {
        $this->_redis = new RedisUtil();

        $this->_categoryModel = new categoryModel();
        $this->_goodsModel    = new goodsModel();

        $this->_sll_goodsModel    = new sll_goodsModel();
        $this->_sll_classifyModel = new sll_classifyModel();

        $this->_merch_accountModel      = new merch_accountModel();
        $this->_merch_userModel         = new merch_userModel();
        $this->_merch_x_enterpriseModel = new merch_x_enterpriseModel();


        $this->_categoryService = new CategoryService();
    }

    public function merchUserGetOne($id)
    {
        global $_W, $_GPC;

        return $this->_merch_userModel->getOne($id);
    }

    /**
     * 根据ID查商户名字
     *
     * @param $id
     *
     * @return string
     */
    public function merchUserGetMerchnameById($id)
    {
        global $_W, $_GPC;
        return $this->_merch_userModel->getMerchnameById($id);
    }


    public function getCacheStore2Merch($store_id)
    {
        global $_W, $_GPC;

        $cache_key = 'superdesk_shop_member_' . 'cache_store2merch' . ':' . $_W['uniacid'];

        if ($this->_redis->ishExists($cache_key, $store_id) == 1) {

            $cache_data = $this->_redis->hget($cache_key, $store_id);
            $cache_data = json_decode($cache_data, true);

            $cached_merch_user_id    = $cache_data['merch_user_id'];
            $cached_merch_account_id = $cache_data['merch_account_id'];

            return $cached_merch_user_id;
        } else {

            return 0;
        }
    }

    public function importMerchFromOldShop($data_source_store)
    {

        global $_W, $_GPC;

//        echo "************************************************* 商户数据处理::" . $data_source_store['store_name'] . " START *************************************************";
//        echo '<br/>';
//        echo json_encode($data_source_store, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
//        echo '<br/>';


//        $data_source
//        {
//            "store_id": "143",
//            "store_account": "jd",
//            "store_name": "京东商城",
//            "store_user": "刘强东",
//            "store_address": "京东",
//            "phone": "13910011001",
//            "store_code": null,
//            "ctime": "2017-03-07 11:34:29",
//            "endTime": "2017-12-09 00:00:00",
//            "status": "400"
//        }

        $cached_merch_user_id    = 0;
        $cached_merch_account_id = 0;

        $cache_key = 'superdesk_shop_member_' . 'cache_store2merch' . ':' . $_W['uniacid'];

        if ($this->_redis->ishExists($cache_key, $data_source_store['store_id']) == 1) {

            $cache_data = $this->_redis->hget($cache_key, $data_source_store['store_id']);
            $cache_data = json_decode($cache_data, true);


//            echo '<br/>';
//            echo "cache_key::" . $cache_key . ' ==> 存在' . '::';
//            echo $data_source_store['store_id'];
//            echo '::';
//            echo json_encode($cache_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
//            echo '<br/>';

            $cached_merch_user_id    = $cache_data['merch_user_id'];
            $cached_merch_account_id = $cache_data['merch_account_id'];
        } else {
//            echo '<br/>';
//            echo "cache_key::" . $cache_key . ' ==> 不存在' . '<br/>';
        }

        $merch_user_id                = $this->_merch_userModel->saveOrUpdateByOldShop($data_source_store, $cached_merch_user_id);
        $data_source_store['merchid'] = $merch_user_id;
        $merch_account_id             = $this->_merch_accountModel->saveOrUpdateByOldShop($data_source_store, $cached_merch_account_id);

        pdo_update(
            'superdesk_shop_merch_user',
            array(
                'accountid' => $merch_account_id
            ), array(
            'id' => $merch_user_id
        ));

        if (!empty($merch_user_id) && !empty($merch_account_id)) {

            $cache_save_data = array(
                'merch_user_id'    => $merch_user_id,
                'merch_account_id' => $merch_account_id

            );
//            $cache_save_data = array();
//            $cache_save_data['merch_user_id']    = $merch_user_id;
//            $cache_save_data['merch_account_id'] = $merch_account_id;

//            echo '<br/>';
//            echo "cache_key::" . $cache_key . ' ==> 写入' . '::';
//            echo $data_source_store['store_id'];
//            echo '::';
//            echo json_encode($cache_save_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
//            echo '<br/>';
//            echo '<br/>';

            $this->_redis->hset($cache_key, $data_source_store['store_id'], json_encode($cache_save_data));
        }

        return $data_source_store;

//        echo "************************************************* 商户数据处理::" . $data_source_store['store_name'] . " END   *************************************************";
//        echo '<br/>';
//
//        echo '<br/>';
//        echo '<br/>';
//        $this->importMerchGoodsFromOldShop($data_source_store);

    }

    public function importMerchGoodsFromOldShop($merchid, $_goods_id)
    {
        global $_W, $_GPC;

        $_goods = $this->_sll_goodsModel->getOne($_goods_id);

        if ($_goods['status'] == "400") {
            $this->_goodsModel->deleteByOldShopGoodsId($_goods['goods_id']);

            return "删除::状态为400";
        }

        $_goods['merchid'] = $merchid;

//            {
//                "goods_id": "51",
//                "name": "DELL燃7000 R1605S14.0英寸微边框笔记本电脑",
//                "referprice": "299.00",
//                "status": "400",
//                "pic": "http:\/\/www.avic-s.com\/memberShop-admin\/images\/pic\/1488868351749.jpg",
//                "introductions": "i5-7200U 8GB 256GB SSD HD620 Win10",
//                "model": "a",
//                "type": "0",
//                "classify_id": "45",
//                "stock": "10",
//                "unit_id": "13",
//                "user_id": "143",
//                "oldprice": "399.00"
//            }

        $_new_cate_id = $this->_categoryService->getCacheOldCateId2NewCateId($_goods['classify_id']);

//
//                echo "商品 :: " . $_goods['name']." :: 商品";

        if (!empty($_new_cate_id)) {

            // 处理分类
            $_goods['category'] = "0;0;" . $_new_cate_id;

            // 处理图片
            if (!empty($_goods['pic'])) {

                //$ext       = '.jpg';
                //$file_name = time() . $ext;

                $file_name = basename($_goods['pic']);


                $uniacid = intval($_W['uniacid']);
                $path    = "images/{$uniacid}/" . date('Y/00');// 'Y/m'

                $save_to = ATTACHMENT_ROOT . $path . '/' . $file_name;

                if (!file_exists(dirname($save_to))) {
                    mkdir(dirname($save_to), 0777, true);
                }

                $in  = fopen($_goods['pic'], "rb");
                $out = fopen($save_to, "wb");

                while ($chunk = fread($in, 8192)) {
                    fwrite($out, $chunk, 8192);
                }

                fclose($in);
                fclose($out);

                $_goods['pic'] = $path . '/' . $file_name;

            }

            $this->_goodsModel->saveOrUpdateByImportMerchGoodsFromOldShop($_goods);

            return "成功::Old Id=>" . $_goods['classify_id'] . "New Id=>" . $_new_cate_id . "";
        } else {

            $_tongji[] = $_goods['classify_id'];
            return "错误::Old Id=>" . $_goods['classify_id'] . " 没找到映射分类";
        }


    }

    public function mappingMerchXEnterprise($enterprise_id, $merchid)
    {

        global $_W, $_GPC;

        $params = array(
            'merchid'       => $merchid,
            'enterprise_id' => $enterprise_id
        );

        $this->_merch_x_enterpriseModel->saveOrUpdateByColumn($params, $params);
    }

    public function getMerchByEnterpriseId($enterprise_id = 0)
    {
        global $_W, $_GPC;

        $cache_key = 'superdesk_shop_v2_' . 'cache_enterprise2virtualarchitecture' . ':' . $_W['uniacid'];

        if ($this->_redis->ishExists($cache_key, $enterprise_id) == 1) {

            $cache_data = $this->_redis->hget($cache_key, $enterprise_id);
            return $cache_data;

        } else {

            // 获取 默认 可见 商户
            $merch_default_see = pdo_fetchall(
                ' SELECT id ' .
                ' FROM ' . tablename('superdesk_shop_merch_user') .
                ' WHERE uniacid=:uniacid and is_default_see = 1 ',
                array(
                    ':uniacid' => $_W['uniacid']
                )
            );
            $merch_default_see = array_column($merch_default_see, 'id');
            socket_log(json_encode($merch_default_see));
            if (!is_array($merch_default_see)) {
                $merch_default_see = array();
            }

            if (!empty($enterprise_id)) {
                // 获取 个别 可见
                $_merch_ids = $this->_merch_x_enterpriseModel->getMerchByEnterpriseId($enterprise_id);
                $_merch_ids = array_column($_merch_ids, 'merchid');
                socket_log(json_encode($_merch_ids));
                if (!is_array($_merch_ids)) {
                    $_merch_ids = array();
                }


                $merch_ids = array_merge($_merch_ids, $merch_default_see); // 合并
            } else {
                $merch_ids = $merch_default_see;
            }

            asort($merch_ids); // 排序
            $merch_ids     = array_unique($merch_ids); // 去重
            $merch_ids_str = implode(',', $merch_ids);

            $this->_redis->hset($cache_key, $enterprise_id, $merch_ids_str);

            return $merch_ids_str;
        }

    }

    public function deleteCacheMerchByEnterpriseId($enterprise_id)
    {

        global $_W, $_GPC;

        $cache_key = 'superdesk_shop_v2_' . 'cache_enterprise2virtualarchitecture' . ':' . $_W['uniacid'];

        if ($enterprise_id == 'default') {
            $this->_redis->del($cache_key);
        } else {
            $this->_redis->hDel($cache_key, $enterprise_id);
        }

    }

    public function merchXEnterpriseQueryAll($where = array(), $page = 0, $page_size = 50)
    {
        global $_W, $_GPC;

        return $this->_merch_x_enterpriseModel->queryAll($where, $page, $page_size);
    }

    public function merchXEnterpriseSaveOrUpdateByColumn($params, $column = array())
    {
        global $_W, $_GPC;

//        socket_log(json_encode($params,JSON_UNESCAPED_UNICODE));

//        {"merchid":141,"enterprise_id":1414}

        $this->deleteCacheMerchByEnterpriseId($params['enterprise_id']);

        $this->_merch_x_enterpriseModel->saveOrUpdateByColumn($params, $column);

    }

    public function merchXEnterpriseDelete($id)
    {
        global $_W, $_GPC;

        $_merchXEnterprise = $this->_merch_x_enterpriseModel->getOne($id);

//        {"id":"8","uniacid":"16","merchid":"141","enterprise_id":"66","status":"1","createtime":"1516703163"}

//        socket_log(json_encode($_merchXEnterprise,JSON_UNESCAPED_UNICODE));

        $this->deleteCacheMerchByEnterpriseId($_merchXEnterprise['enterprise_id']);

        $this->_merch_x_enterpriseModel->delete($id);

    }

}