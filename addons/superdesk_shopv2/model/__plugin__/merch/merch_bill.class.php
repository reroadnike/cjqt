<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/18
 * Time: 18:30
 */

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/base_setting/SuperdeskShopv2BaseModel.class.php');

class merch_billModel extends SuperdeskShopv2BaseModel
{

    public $table_name = "superdesk_shop_merch_bill";

    public $table_column_all = "id,uniacid,applyno,merchid,orderids,realprice,realpricerate,finalprice,payrateprice,payrate,money,applytime,checktime,paytime,invalidtime,refusetime,remark,status,ordernum,orderprice,price,passrealprice,passrealpricerate,passorderids,passordernum,passorderprice,alipay,bankname,bankcard,applyrealname,applytype,handpay";

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
    $insert_data['applyno'] = '';// 编号 | NO | varchar(255) | 
    $insert_data['merchid'] = '0';// 商户id | NO | int(11) | 0
    $insert_data['orderids'] = '';// 订单ID | NO | text | 
    $insert_data['realprice'] = '0.00';// 申请金额 | NO | decimal(10,2) | 0.00
    $insert_data['realpricerate'] = '0.00';// 申请抽成后金额 | NO | decimal(10,2) | 0.00
    $insert_data['finalprice'] = '0.00';// 实际打款金额 | NO | decimal(10,2) | 0.00
    $insert_data['payrateprice'] = '0.00';// 抽成金额 | NO | decimal(10,2) | 0.00
    $insert_data['payrate'] = '0.00';// 抽成比例 | NO | decimal(10,2) | 0.00
    $insert_data['money'] = '0.00';//  | NO | decimal(10,2) | 0.00
    $insert_data['applytime'] = '0';// 申请时间 | NO | int(11) | 0
    $insert_data['checktime'] = '0';// 确认时间 | NO | int(11) | 0
    $insert_data['paytime'] = '0';// 打款时间 | NO | int(11) | 0
    $insert_data['invalidtime'] = '0';//  | NO | int(11) | 0
    $insert_data['refusetime'] = '0';// 拒绝时间 | NO | int(11) | 0
    $insert_data['remark'] = '';// 备注 | NO | text | 
    $insert_data['status'] = '0';// 状态 | NO | tinyint(3) | 0
    $insert_data['ordernum'] = '0';// 申请订单个数 | NO | int(11) | 0
    $insert_data['orderprice'] = '0.00';// 订单金额 | NO | decimal(10,2) | 0.00
    $insert_data['price'] = '0.00';// 实际支付金额 | NO | decimal(10,2) | 0.00
    $insert_data['passrealprice'] = '0.00';// 通过申请金额 | NO | decimal(10,2) | 0.00
    $insert_data['passrealpricerate'] = '0.00';// 通过申请抽成后金额 | NO | decimal(10,2) | 0.00
    $insert_data['passorderids'] = '';// 通过申请订单ID | NO | text | 
    $insert_data['passordernum'] = '0';// 通过申请订单个数 | NO | int(11) | 0
    $insert_data['passorderprice'] = '0.00';// 通过申请订单价格 | NO | decimal(10,2) | 0.00
    $insert_data['alipay'] = '';// 支付宝账号 | NO | varchar(50) | 
    $insert_data['bankname'] = '';// 银行名称 | NO | varchar(50) | 
    $insert_data['bankcard'] = '';// 银行卡号 | NO | varchar(50) | 
    $insert_data['applyrealname'] = '';// 姓名 | NO | varchar(50) | 
    $insert_data['applytype'] = '0';// 提现方式 | NO | tinyint(3) | 0
    $insert_data['handpay'] = '0';//  | NO | tinyint(3) | 0


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