<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2017/11/27 * Time: 19:32 */



class order_submit_order_skuModel
{

    public $table_name = "superdesk_jd_vop_order_submit_order_sku";

    public $table_column_all =
        "id,jdOrderId,skuId,num,category,price,name,tax,taxPrice,nakedPrice,type,oid,
        shop_order_id,shop_order_sn,shop_goods_id,
        return_goods_nun,return_goods_result";

    /**
     * @param $params
     */
    public function insert($params)
    {
        global $_GPC, $_W;

//        $params['uniacid'] = $_W['uniacid'];
//        $params['createtime'] = strtotime('now');

        $ret = pdo_insert($this->table_name, $params);
        if (!empty($ret)) {
            $id = pdo_insertid();
        }

    }

    /**
     * @param $params
     * @param $id
     */
    public function update($params, $id)
    {
        global $_GPC, $_W;

        $ret = pdo_update($this->table_name, $params, array('id' => $id));
    }

    public function updateByColumn($params, $column = array()){

        global $_GPC, $_W;
        $ret = pdo_update($this->table_name, $params, $column);
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        global $_GPC, $_W;
        if (empty($id)) {
            return false;
        }
        pdo_delete($this->table_name, array('id' => $id));
    }

    /**
     * @param $params
     * @param string $id
     */
    public function saveOrUpdate($params, $id = '')
    {
        global $_GPC, $_W;

        if (empty($id)) {
//            $params['uniacid'] = $_W['uniacid'];
//            $params['createtime'] = strtotime('now');

            $ret = pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }
        } else {
//            $params['updatetime'] = strtotime('now');
            $ret = pdo_update($this->table_name, $params, array('id' => $id));
        }

    }

    /**
     * @param $params
     * @param array $column
     */
    public function saveOrUpdateByColumn($params, $column = array())
    {
        global $_GPC, $_W;

        $_is_exist = $this->getOneByColumn($column);

        // 如果没找到会返回 false
        if (!$_is_exist) {

//            $params['uniacid'] = $_W['uniacid'];
//            $params['createtime'] = strtotime('now');

            $ret = pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }

        } else {

//            $params['updatetime'] = strtotime('now');

            $ret = pdo_update($this->table_name, $params, $column);

        }

    }

    

    /**
     * @param $id
     * @return bool
     */
    public function getOne($id)
    {
        global $_GPC, $_W;

        if(empty($id)){
            return null;
        }

        $result = pdo_get($this->table_name, array('id' => $id));

        return $result;

    }

    /**
     * @param array $column
     * @return bool
     */
    public function getOneByColumn($column = array())
    {
        global $_GPC, $_W;

        $result = pdo_get($this->table_name, $column);

        return $result;

    }


    public function queryBypOrder($pOrder)
    {
        $where_sql .= " WHERE 1 = 1 ";

        $where_sql .= " AND `pOrder` = :pOrder";
        $params[':pOrder'] = $pOrder;

        $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_name) . $where_sql . " ORDER BY jdOrderId ASC ", $params);

        return $list;

    }

    /**
     * @param array $where
     * @param int $page
     * @param int $page_size
     * @return array
     */
    public function queryAll($where = array(), $page = 0, $page_size = 50)
    {
        global $_GPC, $_W;//TIMESTAMP

        $page = max(1, intval($page));

        $where_sql = " WHERE 1 = 1 ";



        if(isset($where['shop_order_id'])){
            $where_sql .= " AND `shop_order_id` = :shop_order_id";
            $params[':shop_order_id'] = intval($where['shop_order_id']);
        }

        if(isset($where['shop_goods_id'])){
            $where_sql .= " AND `shop_goods_id` = :shop_goods_id";
            $params[':shop_goods_id'] = intval($where['shop_goods_id']);
        } else {
            $where_sql .= " AND `shop_goods_id` > 0 ";
        }

        $total = pdo_fetchcolumn(
            " SELECT COUNT(*) ".
            " FROM " . tablename($this->table_name) .
            $where_sql, $params);
        $list = pdo_fetchall(
            " SELECT * ".
            " FROM " . tablename($this->table_name) .
            $where_sql .
            " ORDER BY id ASC ".
            " LIMIT " . ($page - 1) * $page_size . ',' . $page_size,
            $params);

        $pager = array();
        $pager['total'] = $total;
        $pager['page'] = $page;
        $pager['page_size'] = $page_size;
        $pager['data'] = $list;

        return $pager;

    }
}