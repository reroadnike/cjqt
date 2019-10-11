<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/18
 * Time: 18:30
 */

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/base_setting/SuperdeskShopv2BaseModel.class.php');

class merch_clearingModel extends SuperdeskShopv2BaseModel
{

    public $table_name = "superdesk_shop_merch_clearing";

    public $table_column_all = "id,uniacid,merchid,clearno,goodsprice,dispatchprice,deductprice,deductcredit2,discountprice,deductenough,merchdeductenough,isdiscountprice,price,createtime,starttime,endtime,status,realprice,realpricerate,finalprice,remark,paytime,payrate";

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

            $params['uniacid'] = $_W['uniacid'];
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
    $insert_data['merchid'] = '0';// 商户ID | NO | int(11) | 0
    $insert_data['clearno'] = '';// 结算编号 | NO | varchar(64) | 
    $insert_data['goodsprice'] = '0.00';// 商品价格 | NO | decimal(10,2) | 0.00
    $insert_data['dispatchprice'] = '0.00';// 物流价格 | NO | decimal(10,2) | 0.00
    $insert_data['deductprice'] = '0.00';// 积分抵扣 | NO | decimal(10,2) | 0.00
    $insert_data['deductcredit2'] = '0.00';// 余额抵扣 | NO | decimal(10,2) | 0.00
    $insert_data['discountprice'] = '0.00';// 会员折扣金额 | NO | decimal(10,2) | 0.00
    $insert_data['deductenough'] = '0.00';// 满减金额 | NO | decimal(10,2) | 0.00
    $insert_data['merchdeductenough'] = '0.00';// 商户满减金额 | NO | decimal(10,2) | 0.00
    $insert_data['isdiscountprice'] = '0.00';// 促销金额 | NO | decimal(10,2) | 0.00
    $insert_data['price'] = '0.00';// 订单实收 | NO | decimal(10,2) | 0.00
    $insert_data['starttime'] = '0';// 订单开始时间 | NO | int(10) unsigned | 0
    $insert_data['endtime'] = '0';// 订单结束时间 | NO | int(10) unsigned | 0
    $insert_data['status'] = '0';// 结算状态 0 未结算 1 结算中 2 已结算 | NO | tinyint(1) | 0
    $insert_data['realprice'] = '0.00';// 订单应收 | NO | decimal(10,2) | 0.00
    $insert_data['realpricerate'] = '0.00';// 抽成后金额 | NO | decimal(10,2) | 0.00
    $insert_data['finalprice'] = '0.00';// 最终打款 | NO | decimal(10,2) | 0.00
    $insert_data['remark'] = '';// 备注 | NO | varchar(2000) | 
    $insert_data['paytime'] = '0';// 支付时间 | NO | int(11) | 0
    $insert_data['payrate'] = '0.00';// 抽成比例 | NO | decimal(10,2) | 0.00


            $insert_data['uniacid'] = $_W['uniacid'];
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

        $where_sql .= " WHERE `uniacid` = :uniacid";
        $params = array(
            ':uniacid' => $_W['uniacid'],
        );

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
}