<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2017/11/27 * Time: 16:18 */



class order_submit_orderModel
{

    public $table_name = "superdesk_jd_vop_order_submit_order";

    public $table_column_all =
        "id,order_id,thirdOrder,sku,name,province,city,county,town,address,zip,phone,mobile,email,remark,invoiceState,invoiceType,selectedInvoiceTitle,companyName,invoiceContent,paymentType,isUseBalance,submitState,invoiceName,invoicePhone,invoiceProvice,invoiceCity,invoiceCounty,invoiceAddress,doOrderPriceMode,orderPriceSnap,reservingDate,installDate,needInstall,promiseDate,promiseTimeRange,promiseTimeRangeCode,createtime,updatetime,response,
    jd_vop_success,
    jd_vop_resultMessage,
    jd_vop_resultCode,
    jd_vop_code,
    jd_vop_result_jdOrderId,
    jd_vop_result_freight,
    jd_vop_result_orderPrice,
    jd_vop_result_orderNakedPrice,
    jd_vop_result_orderTaxPrice,
    checking_by_third
    
    ";

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
            return $id;
        } else {
            return $ret;
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

    public function updateByColumn($params, $column = array())
    {
        global $_GPC, $_W;

        $params['updatetime'] = strtotime('now');
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

            $params['createtime'] = strtotime('now');

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

        $where_sql = "WHERE 1 = 1 ";
//        $where_sql .= " WHERE `uniacid` = :uniacid";
//        $params = array(
//            ':uniacid' => $_W['uniacid'],
//        );

        if (isset($where['checking_by_third'])) {
            $where_sql .= " AND `checking_by_third` = :checking_by_third";
            $params[':checking_by_third'] = intval($where['checking_by_third']);
        }

        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_name) . $where_sql, $params);
        $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_name) . $where_sql . " ORDER BY id ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);
//        $pager = pagination($total, $page, $page_size);

        $pager = array();
        $pager['total'] = $total;
        $pager['page'] = $page;
        $pager['page_size'] = $page_size;
        $pager['data'] = $list;

        return $pager;

    }

    /**
     * 提交JD订单订后，未反查订单
     * @param int $page
     * @param int $page_size
     *
     * @return array
     */
    public function queryForJdVopPendingJdOrderByCheckingOrder($page = 0, $page_size = 50){

        global $_GPC, $_W;//TIMESTAMP

        $where = array(
            'checking_by_third' => 0
        );
        
        return $this->queryAll($where,$page,$page_size);

    }


    /**
     * 反查成功后，看一下有没有被JD拆单
     * @param int $page
     * @param int $page_size
     *
     * @return array
     */
    public function queryForJdVopReadyJdOrderByCheckingOrder($page = 0, $page_size = 50){

        global $_GPC, $_W;//TIMESTAMP

        $where = array(
            'checking_by_third' => 1
        );

        return $this->queryAll($where,$page,$page_size);

    }
}