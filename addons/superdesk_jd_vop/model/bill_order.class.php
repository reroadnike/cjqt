<?php

/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/03/30
 * Time: 15:24
 */
class bill_orderModel
{

    public $table_name = "superdesk_jd_vop_bill_order";

    public $table_column_all = "id,uniacid,orderNo,billNo,orderAmount,settleStatus,settleDate,sumRefundAmount,customerName,
        shopOrderSn,shopOrderPrice,shopOrderStatus,orderFreight,isRight,createtime,updatetime
    ";

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
     * @param $params
     * @param $column
     */
    public function updateByColumn($params, $column = array())
    {
        global $_GPC, $_W;

        $ret = pdo_update($this->table_name, $params, $column);
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

    /**
     * @param array $where
     * @param int   $page
     * @param int   $page_size
     *
     * @return array
     */
    public function queryAll($where = array(), $page = 0, $page_size = 20)
    {
        global $_GPC, $_W;//TIMESTAMP

        $page = max(1, intval($page));

        $where_sql = " WHERE 1 = 1 ";
        $params    = array();


        $orderId = isset($where['billNo']) ? $where['billNo'] : 0;
        if (!empty($orderId)) {
            $where_sql .= " AND billNo = :billNo";
            $params[':billNo'] = $orderId;
        }

        if (isset($where['settleStatus'])) {
            $where_sql .= " AND settleStatus = :settleStatus";
            $params[':settleStatus'] = $where['settleStatus'];
        }

        if (isset($where['isRight'])) {
            $where_sql .= " AND isRight = :isRight";
            $params[':isRight'] = $where['isRight'];
        }

        $total = pdo_fetchcolumn(
            " SELECT COUNT(id) " .
            " FROM " . tablename($this->table_name) .
            $where_sql,
            $params
        );

        $list = pdo_fetchall(
            " SELECT * " .
            " FROM " . tablename($this->table_name) .
            $where_sql .
            " ORDER BY settleStatus ASC,id DESC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }

    /**
     * @param $JdOrderId
     * 根据京东单号查找商城订单
     */
    public function getOrderByJdOrderId($JdOrderId)
    {

        if (empty($JdOrderId)) {
            return [];
        }

        $result = pdo_fetch(
            ' SELECT o.ordersn,o.status,sum(og.costprice * og.total) as costprice,jd_vop_o.jd_vop_result_freight ' .
            ' FROM ' . tablename('superdesk_shop_order_goods') . ' as og ' .
            ' LEFT JOIN ' . tablename('superdesk_shop_order') . ' as o on og.orderid = o.id ' .
            ' LEFT JOIN ' . tablename('superdesk_jd_vop_order_submit_order') . ' as jd_vop_o on o.expresssn = jd_vop_o.jd_vop_result_jdOrderId ' .
            ' WHERE o.expresssn=:JdOrderId GROUP BY o.ordersn,o.status ',
            array(':JdOrderId' => $JdOrderId)
        );

        if ($result) {
            return $result;
        } else {
            return [];
        }
    }
}