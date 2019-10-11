<?php

/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/03/30
 * Time: 15:24
 */
class balance_detailModel
{

    public $table_name = "superdesk_jd_vop_balance_detail";

//    public $table_column_all = "id,accountType,amount,pin,orderId,tradeType,tradeTypeName,createdDate,notePub,tradeNo,processing,process_result";
    public $table_column_all = "id,accountType,amount,pin,orderId,tradeType,tradeTypeName,createdDate,notePub,tradeNo";

    /**
     * @param $params
     */
    public function insert($params)
    {
        global $_GPC, $_W;

        $params['uniacid']    = $_W['uniacid'];
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

        $ret = pdo_update($this->table_name, $params, array('id' => $id));
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
        pdo_delete($this->table_name, array('id' => $id));
    }


    /**
     *
     * @param        $params
     * @param string $id
     */
    public function replace($params, $id = '')
    {
        global $_GPC, $_W;

//        pdo_insert($this->table_name, $params, true); // 这个是会全行替换的

        $_is_exist = $this->getOne($id);

        if (!$_is_exist) {
            $ret = pdo_insert($this->table_name, $params);
        }
    }

    /**
     * @param        $params
     * @param string $id
     */
    public function saveOrUpdate($params, $id = '')
    {
        global $_GPC, $_W;

        $_is_exist = $this->getOne($id);

        if (!$_is_exist) {
            $ret = pdo_insert($this->table_name, $params);
        } else {
            $ret = pdo_update($this->table_name, $params, array('id' => $id));
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

            $ret = pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }

        } else {

            $ret = pdo_update($this->table_name, $params, $column);

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
            $insert_data['accountType']   = '0';// accountType | NO | tinyint(4) | 0
            $insert_data['amount']        = '';// amount | NO | decimal(10,2) |
            $insert_data['pin']           = '';// pin | NO | varchar(256) |
            $insert_data['orderId']       = '0';// orderId | NO | int(11) | 0
            $insert_data['tradeType']     = '0';// tradeType | NO | int(11) | 0
            $insert_data['tradeTypeName'] = '';// tradeTypeName | NO | varchar(1000) |
            $insert_data['createdDate']   = '';// createdDate | NO | datetime |
            $insert_data['notePub']       = '';// notePub | NO | text |
            $insert_data['tradeNo']       = '0';// tradeNo | NO | int(11) | 0


            $insert_data['uniacid']    = $_W['uniacid'];
            $insert_data['createtime'] = strtotime('now');

            $ret = pdo_insert($this->table_name, $insert_data);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }

        } else {

            $update_data = array();

            // TODO

            $update_data['updatetime'] = strtotime('now');

            $ret = pdo_update($this->table_name, $update_data, $column);

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

        $result = pdo_get($this->table_name, array('id' => $id));

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

        $result = pdo_get($this->table_name, $column);

        return $result;

    }

    public function checkSyncCreatedDate()
    {
        global $_GPC, $_W;

        $page      = 1;
        $page_size = 1;

        $params    = array();
        $where_sql = " WHERE 1 = 1 ";

        $list = pdo_fetchall(
            " SELECT createdDate " .
            " FROM " . tablename($this->table_name) . $where_sql .
            " ORDER BY createdDate DESC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);
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

        $where_sql = " WHERE 1 = 1 ";
        $params    = array();


        $orderId = isset($where['orderId']) ? $where['orderId'] : 0;
        if (!empty($orderId)) {
            $where_sql .= " AND bd.orderId = :orderId";
            $params[':orderId'] = $orderId;
        }

        $start = isset($where['start']) ? $where['start'] : "";
        if (!empty($start)) {
            $where_sql .= " AND bd.createdDate >= :start ";
            $params[':start'] = $start;
        }

        $end = isset($where['end']) ? $where['end'] : "";
        if (!empty($end)) {
            $where_sql .= " AND bd.createdDate <= :end ";
            $params[':end'] = $end;
        }

        if (isset($where['tradeType']) && !empty($where['tradeType'])) {
            $where_sql .= " AND bd.tradeType = :tradeType ";
            $params[':tradeType'] = $where['tradeType'];
        }

        if (isset($where['processing'])) {
            $where_sql .= " AND bdp.processing = :processing ";
            $params[':processing'] = $where['processing'];
        }


        $total = pdo_fetchcolumn(
            " SELECT COUNT(bd.id) " .
            " FROM " . tablename($this->table_name) . ' as bd ' .
            " LEFT JOIN " . tablename('superdesk_jd_vop_balance_detail_processing') . ' as bdp on bdp.id = bd.id ' .
            $where_sql,
            $params
        );

        //2018年9月11日 16:38:30 zjh 原先一个表.现在拆分成两个表.连多一个表.
        $list = pdo_fetchall(
            " SELECT bd.*,bdp.processing,bdp.process_result " .
            " FROM " . tablename($this->table_name) . ' as bd ' .
            " LEFT JOIN " . tablename('superdesk_jd_vop_balance_detail_processing') . ' as bdp on bdp.id = bd.id ' .
            $where_sql .
            " ORDER BY bd.createdDate DESC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);

        socket_log(" SELECT bd.*,bdp.processing,bdp.process_result " .
            " FROM " . tablename($this->table_name) . ' as bd ' .
            " LEFT JOIN " . tablename('superdesk_jd_vop_balance_detail_processing') . ' as bdp on bdp.id = bd.id ' .
            $where_sql .
            " ORDER BY bd.createdDate DESC LIMIT " . ($page - 1) * $page_size . ',' . $page_size);

        socket_log(json_encode($params));

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }

    public function getGroupByTradeType()
    {
        $all_tradeType = pdo_fetchall(
            " SELECT CONCAT(tradeType , '-', tradeTypeName ) as tradeType " .
            " FROM " . tablename($this->table_name) .
            " GROUP BY tradeType ORDER BY tradeType ASC ");

        $all_tradeType = array_column($all_tradeType, 'tradeType');

        return $all_tradeType;
    }
}