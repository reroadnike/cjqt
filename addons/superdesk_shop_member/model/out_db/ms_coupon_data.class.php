<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/11
 * Time: 17:02
 */

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/base_setting/SuperdeskShopMemberBaseOutDbModel.class.php');

class ms_coupon_dataModel extends SuperdeskShopMemberBaseOutDbModel
{

    public $table_name = "ms_coupon_data";

    public $table_column_all = "coupon_data_id,coupon_standard_pkcode,coupon_data_name,coupon_data_type,coupon_data_pkcode,coupon_data_usetime,coupon_data_condition,coupon_data_mjprice,coupon_data_expiretype,coupon_data_begintime,coupon_data_endtime,coupon_data_ctime,coupon_data_useproducttype,coupon_data_state,coupon_m_id,coupon_use_time";

    /**
     * @param $params
     */
    public function insert($params)
    {
        global $_GPC, $_W;

        $params['uniacid'] = $_W['uniacid'];
        $params['createtime'] = strtotime('now');

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

        $params['updatetime'] = strtotime('now');
        $ret = $this->pdo_update($this->table_name, $params, array('id' => $id));
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
        $this->pdo_delete($this->table_name, array('id' => $id));
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

            $ret = $this->pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = $this->pdo_insertid();
            }
        } else {
            $params['updatetime'] = strtotime('now');
            $ret = $this->pdo_update($this->table_name, $params, array('id' => $id));
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

            $params['uniacid'] = $_W['uniacid'];
            $params['createtime'] = strtotime('now');

            $ret = $this->pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = $this->pdo_insertid();
            }

        } else {

            $params['updatetime'] = strtotime('now');

            $ret = $this->pdo_update($this->table_name, $params, $column);

        }

    }

    public function saveOrUpdateByJdVop($params , $sku)
    {
        global $_GPC, $_W;


        $column = array(
            "jd_vop_sku" => $sku
        );
        $_is_exist = $this->getOneByColumn($column);

        // 如果没找到会返回 false
        if (!$_is_exist) {

            $insert_data = array();

            // TODO
    $insert_data['coupon_data_id'] = '';//  | NO | int(11) | 
    $insert_data['coupon_standard_pkcode'] = '';// 对应优惠券规则PKCODE | YES | varchar(255) | 
    $insert_data['coupon_data_name'] = '';// 优惠券名称 | YES | varchar(255) | 
    $insert_data['coupon_data_type'] = '';// 规则类型，0：表示抵扣次数，1：优惠券 | YES | int(2) | 
    $insert_data['coupon_data_pkcode'] = '';// 优惠券对外的唯一id | YES | varchar(255) | 
    $insert_data['coupon_data_usetime'] = '';// 规则类型为0 时，这个值有效，抵扣次数值 | YES | int(10) | 
    $insert_data['coupon_data_condition'] = '';// 若规则条件为1时，此字段为满多少的值 | YES | varchar(100) | 
    $insert_data['coupon_data_mjprice'] = '';//  | YES | varchar(100) | 
    $insert_data['coupon_data_expiretype'] = '';// 到期类型：0:表示每天到期，1：每月到期，2：每年到期，3：自定到期时间 | YES | varchar(255) | 
    $insert_data['coupon_data_begintime'] = '';// 若到期类型为3时，这个为券有效时间的开始时间 | YES | datetime | 
    $insert_data['coupon_data_endtime'] = '';// 若到期类型为2或者3时，这个为券有效时间的结束时间 | YES | datetime | 
    $insert_data['coupon_data_ctime'] = '';//  | YES | datetime | 
    $insert_data['coupon_data_useproducttype'] = '';// 适用商品规则，0：全场通用，1：指定供应商 | YES | int(2) | 
    $insert_data['coupon_data_state'] = '';// 0：表示未启用，1：表示可使用，2：已使用，3：已过期，4：已失效 | YES | int(2) | 
    $insert_data['coupon_m_id'] = '';// 会员ID | YES | int(11) | 
    $insert_data['coupon_use_time'] = '';// 优惠券使用时间 | YES | datetime | 


            $insert_data['uniacid'] = $_W['uniacid'];
            $insert_data['createtime'] = strtotime('now');

            $ret = $this->pdo_insert($this->table_name, $insert_data);
            if (!empty($ret)) {
                $id = $this->pdo_insertid();
            }

        } else {

            $update_data = array();

            // TODO

            $update_data['updatetime'] = strtotime('now');

            $ret = $this->pdo_update($this->table_name, $update_data, $column);

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

        $result = $this->pdo_get($this->table_name, array('id' => $id));

        return $result;

    }

    /**
     * @param array $column
     * @return bool
     */
    public function getOneByColumn($column = array())
    {
        global $_GPC, $_W;

        $result = $this->pdo_get($this->table_name, $column);

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

        $where_sql .= " WHERE `uniacid` = :uniacid";
        $params = array(
            ':uniacid' => $_W['uniacid'],
        );

        $total = $this->pdo_fetchcolumn("SELECT COUNT(*) FROM " . $this->tablename($this->table_name) . $where_sql, $params);
        $list = $this->pdo_fetchall("SELECT * FROM " . $this->tablename($this->table_name) . $where_sql . " ORDER BY id ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);
//        $pager = pagination($total, $page, $page_size);

        $pager = array();
        $pager['total'] = $total;
        $pager['page'] = $page;
        $pager['page_size'] = $page_size;
        $pager['data'] = $list;

        return $pager;

    }
}