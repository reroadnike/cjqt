<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2017/11/08
 * Time: 11:30
 */

class product_detailModel
{

    public $table_name = "superdesk_jd_vop_product_detail";

    public $table_column_all = "sku,page_num,name,category,upc,saleUnit,weight,productArea,wareQD,imagePath,param,brandName,state,shouhou,introduction,appintroduce,createtime,updatetime";

    /**
     * @param $params
     */
    public function insert($params)
    {
        global $_GPC, $_W;

//        $params['uniacid'] = $_W['uniacid'];
        $params['createtime'] = strtotime('now');

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

        $params['updatetime'] = strtotime('now');
        $ret = pdo_update($this->table_name, $params, array('id' => $id));
    }


    /**
     * @param $id
     * @return bool
     */
    public function delete($sku)
    {
        global $_GPC, $_W;
        if (empty($sku)) {
            return false;
        }
        pdo_delete($this->table_name, array('sku' => $sku));
    }

    /**
     * @param $params
     * @param string $id
     */
    public function saveOrUpdate($params, $sku = '')
    {
        global $_GPC, $_W;

        if (empty($sku)) {
            $params['createtime'] = strtotime('now');

            $ret = pdo_insert($this->table_name, $params);

        } else {
            $params['updatetime'] = strtotime('now');
            $ret = pdo_update($this->table_name, $params, array('sku' => $sku));
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

            $params['createtime'] = strtotime('now');

            $ret = pdo_insert($this->table_name, $params);

        } else {

            $params['updatetime'] = strtotime('now');
            $ret = pdo_update($this->table_name, $params, $column);

        }

    }

    /**
     * @param $id
     * @return bool
     */
    public function getOne($sku)
    {
        global $_GPC, $_W;

        if(empty($sku)){
            return null;
        }

        $result = pdo_get($this->table_name, array('sku' => $sku));

        return $result;

    }

    /**
     * @param $skuId
     *
     * @return bool
     */
    public function getOneBySku($skuId)
    {
        global $_GPC, $_W;

        $column = array(
            "sku" => $skuId
        );

        return $this-> getOneByColumn($column);

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

        $where_sql = "";
        $where_sql .= " WHERE 1 = 1";
        $params = array();

        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_name) . $where_sql, $params);
        $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_name) . $where_sql . " ORDER BY sku ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }

    public function queryForJdVopApiProductDetailUpdate($where = array(), $page = 1, $page_size = 100)
    {
        global $_GPC, $_W;//TIMESTAMP

        $page = max(1, intval($page));

        $where_sql = "";
        $where_sql .= " WHERE 1 = 1";
        $params = array();

        $where_sql .= " AND `updatetime` < :updatetime";// 86400秒后过期，即24小时有效期
        $params[':updatetime'] = TIMESTAMP - 86400;

        $total = pdo_fetchcolumn("SELECT COUNT(sku) FROM " . tablename($this->table_name) . $where_sql, $params);
        $list = pdo_fetchall("SELECT sku FROM " . tablename($this->table_name) . $where_sql . " ORDER BY sku ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }

    public function callbackForJdVopApiProductDetailUpdate($skuId/*数组 [1,2,3]*/){

        $glue = 'OR';

        $data['updatetime'] = strtotime('now');

        $where = array(
            "sku" => $skuId
        );

        $ret = pdo_update($this->table_name, $data, $where, $glue);

    }
}