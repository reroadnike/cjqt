<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2017/11/09
 * Time: 18:21
 */



class product_priceModel
{

    public $table_name = "superdesk_jd_vop_product_price";

    public $table_column_all = "skuId,productprice,marketprice,costprice,createtime,updatetime";

    /**
     * @param $params
     */
    public function insert($params)
    {
        global $_GPC, $_W;

        $params['uniacid'] = $_W['uniacid'];
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
            $params['uniacid'] = $_W['uniacid'];
            $params['createtime'] = strtotime('now');

            $ret = pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }
        } else {
            $params['updatetime'] = strtotime('now');
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
            $params['createtime'] = strtotime('now');
            $params['updatetime'] = strtotime('now');
            $ret = pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }

        } else {

            $params['updatetime'] = strtotime('now');
            $ret = pdo_update($this->table_name, $params, $column);

        }

    }

    /**
     * 初始化 京东价格 加条记录 callback 再更新价格
     * @param $params
     * @param $skuId
     */
    public function saveOrUpdateByJdVop($skuId)
    {
        global $_GPC, $_W;

        $column = array(
            "skuId" => $skuId
        );
        $_is_exist = $this->getOneByColumn($column);

        // 如果没找到会返回 false
        if (!$_is_exist) {

            $insert_data = array();
            $insert_data['skuId'] = $skuId;// skuId | NO | int(11) |
            $insert_data['createtime'] = strtotime('now');

            $ret = pdo_insert($this->table_name, $insert_data);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }
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
        $where_sql .= " WHERE `uniacid` = :uniacid";
        $params = array(
            ':uniacid' => $_W['uniacid'],
        );

        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_name) . $where_sql, $params);
        $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_name) . $where_sql . " ORDER BY id ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);
//        $pager = pagination($total, $page, $page_size);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }


    public function queryForJdVopApiPriceUpdate($where = array(), $page = 1, $page_size = 100)
    {
        global $_GPC, $_W;//TIMESTAMP

        $page = max(1, intval($page));

        $where_sql = "";
        $where_sql .= " WHERE 1 = 1";
        $params = array();

        $where_sql .= " AND `updatetime` < :updatetime";
        $params[':updatetime'] = TIMESTAMP - (7*86400);// 24小时 = 86400秒 7天后过期 有效期

        $total = pdo_fetchcolumn("SELECT COUNT(skuId) FROM " . tablename($this->table_name) . $where_sql, $params);
        $list = pdo_fetchall(
            " SELECT skuId ".
            " FROM " . tablename($this->table_name) . $where_sql .
            " ORDER BY updatetime ASC ".
            " LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }

    public function callbackForJdVopApiPriceUpdate($skuId/*数组 [1,2,3]*/){

//        $start = microtime(true);
        $glue = 'OR';

        $data['updatetime'] = strtotime('now');

        $where = array(
            "skuId" => $skuId
        );

        $ret = pdo_update($this->table_name, $data, $where, $glue);

//        $end = microtime(true);
//        echo 'callbackForJdVopApiPriceUpdate 耗时'.round($end - $start,4).'秒';
//        echo '<br/>';

    }


}