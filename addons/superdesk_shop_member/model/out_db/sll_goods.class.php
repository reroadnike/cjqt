<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/11
 * Time: 17:02
 */

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/base_setting/SuperdeskShopMemberBaseOutDbModel.class.php');
include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');


class sll_goodsModel extends SuperdeskShopMemberBaseOutDbModel
{

    public $table_name = "sll_goods";

    public $table_column_all = "goods_id,brand_id,name,referprice,status,pic,introductions,model,content,number,type,toGoodsid,sort,classify_id,stock,unit_id,ctime,user_id,oldprice,origin,sku,agreementprice,modified_at";


    private $_redis;

    public function __construct()
    {
        $this->_redis = new RedisUtil();
    }

    /**
     * @param $params
     */
    public function insert($params)
    {
        global $_GPC, $_W;

//        $params['uniacid']    = $_W['uniacid'];
//        $params['createtime'] = strtotime('now');

        $ret = $this->pdo_insert($this->table_name, $params);
        if (!empty($ret)) {
            $id = $this->pdo_insertid();
        }

    }

    /**
     * @param $params
     * @param $id
     */
    public function update($params, $id)
    {
        global $_GPC, $_W;

//        $params['updatetime'] = strtotime('now');
        $ret = $this->pdo_update($this->table_name, $params, array('id' => $id));
    }


    /**
     * @param $id
     *
     * @return bool
     */
    public function delete($id)
    {
        global $_GPC, $_W;
        if (empty($id)) {
            return false;
        }
        $this->pdo_delete($this->table_name, array('id' => $id));
    }

    /**
     * @param        $params
     * @param string $id
     */
    public function saveOrUpdate($params, $id = '')
    {
        global $_GPC, $_W;

        if (empty($id)) {
//            $params['uniacid']    = $_W['uniacid'];
//            $params['createtime'] = strtotime('now');

            $ret = $this->pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = $this->pdo_insertid();
            }
        } else {
//            $params['updatetime'] = strtotime('now');
            $ret = $this->pdo_update($this->table_name, $params, array('id' => $id));
        }

    }

    /**
     * @param       $params
     * @param array $column
     */
    public function saveOrUpdateByColumn($params, $column = array())
    {
        global $_GPC, $_W;

        $_is_exist = $this->getOneByColumn($column);

        // 如果没找到会返回 false
        if (!$_is_exist) {

//            $params['uniacid']    = $_W['uniacid'];
//            $params['createtime'] = strtotime('now');

            $ret = $this->pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = $this->pdo_insertid();
            }

        } else {

//            $params['updatetime'] = strtotime('now');

            $ret = $this->pdo_update($this->table_name, $params, $column);

        }

    }

    public function saveOrUpdateByJdVop($params, $sku)
    {
        global $_GPC, $_W;


        $column    = array(
            "jd_vop_sku" => $sku
        );
        $_is_exist = $this->getOneByColumn($column);

        // 如果没找到会返回 false
        if (!$_is_exist) {

            $insert_data = array();

            // TODO
            $insert_data['goods_id']       = '';//  | NO | int(11) |
            $insert_data['brand_id']       = '';// 品牌 | YES | int(11) |
            $insert_data['name']           = '';//  | YES | varchar(500) |
            $insert_data['referprice']     = '';// 参考价 | YES | float(20,2) |
            $insert_data['status']         = '0';// 0:下架，1:上架 400：删除 2:审核不通过 | NO | int(10) | 0
            $insert_data['pic']            = '';//  | YES | varchar(500) |
            $insert_data['introductions']  = '';// 简介 | YES | varchar(1000) |
            $insert_data['model']          = '';// 型号 | YES | varchar(500) |
            $insert_data['content']        = '';// 详情页 | YES | longtext |
            $insert_data['number']         = '';// 数量（水票专用） | YES | int(10) |
            $insert_data['type']           = '';// 1：精选  2：特价  0：普通 | YES | int(10) |
            $insert_data['toGoodsid']      = '';// 水票对应的商品id | YES | int(10) |
            $insert_data['sort']           = '0';// 排序 | YES | int(11) | 0
            $insert_data['classify_id']    = '';// 分类id | YES | int(11) |
            $insert_data['stock']          = '';// 库存 | YES | int(11) |
            $insert_data['unit_id']        = '';// 单位 | YES | int(11) |
            $insert_data['ctime']          = '';// 创建时间 | YES | datetime |
            $insert_data['user_id']        = '';// 拥有者id ( 0:总端 ) | YES | int(11) |
            $insert_data['oldprice']       = '';// 原价 | YES | float(20,2) |
            $insert_data['origin']         = '1';// 来源1：本商城，2：京东 | YES | int(10) | 1
            $insert_data['sku']            = '';// 京东商品sku | YES | varchar(255) |
            $insert_data['agreementprice'] = '0.00';// 京东商品协议价 | YES | float(20,2) | 0.00
            $insert_data['modified_at']    = 'CURRENT_TIMESTAMP';//  | NO | timestamp | CURRENT_TIMESTAMP


//            $insert_data['uniacid']    = $_W['uniacid'];
//            $insert_data['createtime'] = strtotime('now');

            $ret = $this->pdo_insert($this->table_name, $insert_data);
            if (!empty($ret)) {
                $id = $this->pdo_insertid();
            }

        } else {

            $update_data = array();

            // TODO

//            $update_data['updatetime'] = strtotime('now');

            $ret = $this->pdo_update($this->table_name, $update_data, $column);

        }

    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function getOne($id)
    {
        global $_GPC, $_W;

        if (empty($id)) {
            return null;
        }

        $result = $this->pdo_get($this->table_name, array('goods_id' => $id));

        return $result;

    }

    /**
     * @param array $column
     *
     * @return bool
     */
    public function getOneByColumn($column = array())
    {
        global $_GPC, $_W;

        $result = $this->pdo_get($this->table_name, $column);

        return $result;

    }

    public function countByOriginEq1AndUserId($user_id)
    {
        global $_GPC, $_W;

        $_total = 0;

        $redis_key = 'superdesk_shop_member_' . 'count_store2goods' . ':' . $_W['uniacid'];

        if ($this->_redis->ishExists($redis_key, $user_id) == 1) {

            $_total = $this->_redis->hget($redis_key, $user_id);

        } else {

            $_total = $this->countByParams(array(
                'user_id' => $user_id,
                'origin'  => 1
            ));

            $this->_redis->hset($redis_key, $user_id, $_total);
        }

        return $_total;

    }

    public function countByOriginEq1AndClassifyId($classify_id)
    {
        global $_GPC, $_W;

        $_total = 0;

        $redis_key = 'superdesk_shop_member_' . 'count_cate2goods' . ':' . $_W['uniacid'];

        if ($this->_redis->ishExists($redis_key, $classify_id) == 1) {

            $_total = $this->_redis->hget($redis_key, $classify_id);

        } else {

            $_total = $this->countByParams(array(
                'classify_id' => $classify_id,
                'origin'      => 1
            ));

            $this->_redis->hset($redis_key, $classify_id, $_total);
        }

        return $_total;

    }

    public function countByParams($where = array())
    {
        global $_GPC, $_W;//TIMESTAMP

        $where_sql = " WHERE 1=1 ";
        $params    = array();

        // 来源 1:本商城 2:京东
        $origin = isset($where['origin']) ? intval($where['origin']) : 0;
        if ($origin != 0) {
            $where_sql .= " AND `origin` = :origin ";
            $params[':origin'] = $origin;
        }

        // 分类ID 要验证是否有商品挂到一类分类
        $classify_id = isset($where['classify_id']) ? intval($where['classify_id']) : 0;
        if ($classify_id != 0) {
            $where_sql .= " AND `classify_id` = :classify_id ";
            $params[':classify_id'] = $classify_id;
        }

        // 商户ID
        $user_id = isset($where['user_id']) ? intval($where['user_id']) : 0;
        if ($user_id != 0) {
            $where_sql .= " AND `user_id` = :user_id ";
            $params[':user_id'] = $user_id;
        }

        $total = $this->pdo_fetchcolumn("SELECT COUNT(0) FROM " . $this->tablename($this->table_name) . $where_sql, $params);

        return $total;

    }

    public function queryByMerch($user_id = 0, $origin = 0/*1:本商城 2:京东*/)
    {

        global $_GPC, $_W;//TIMESTAMP

        $where_sql = " WHERE 1=1 ";
        $params    = array();

        // 商户ID
        if ($user_id != 0) {
            $where_sql .= " AND `user_id` = :user_id ";
            $params[':user_id'] = $user_id;
        }

        if ($origin != 0) {
            $where_sql .= " AND `origin` = :origin ";
            $params[':origin'] = $origin;
        }

        // content
        $list = $this->pdo_fetchall(
//            " SELECT goods_id,brand_id,name,referprice,status,pic,introductions,content,model,number,type,toGoodsid,sort,classify_id,stock,unit_id,ctime,user_id,oldprice,origin,sku,agreementprice,modified_at " .
            " SELECT goods_id,name,referprice,status,pic,introductions,model,type,classify_id,stock,unit_id,oldprice,content " .
            " FROM " . $this->tablename($this->table_name) .
            $where_sql .
            " ORDER BY goods_id ASC ", $params);

        return $list;
    }

    /**
     * @param array $where
     * @param int   $page
     * @param int   $page_size
     *
     * @return array
     */
    public function queryAll($where = array(), $page = 0, $page_size = 50)
    {
        global $_GPC, $_W;//TIMESTAMP

        $page = max(1, intval($page));

        $where_sql = " WHERE 1=1 ";

        // 1:本商城 2:京东
        $origin = isset($where['origin']) ? intval($where['origin']) : 0;
        if ($origin != 0) {
            $where_sql .= " AND `origin` = :origin ";
            $params[':origin'] = $origin;
        }

//        $where_sql .= " WHERE `uniacid` = :uniacid";
//        $params = array(
//            ':uniacid' => $_W['uniacid'],
//        );

        $total = $this->pdo_fetchcolumn("SELECT COUNT(*) FROM " . $this->tablename($this->table_name) . $where_sql, $params);
        $list  = $this->pdo_fetchall("SELECT * FROM " . $this->tablename($this->table_name) . $where_sql . " ORDER BY user_id ASC ,goods_id ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);
//        $pager = pagination($total, $page, $page_size);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }
}