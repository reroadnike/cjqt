<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/11
 * Time: 17:02
 */

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/base_setting/SuperdeskShopMemberBaseOutDbModel.class.php');

class sll_main_ordersModel extends SuperdeskShopMemberBaseOutDbModel
{

    public $table_name = "sll_main_orders";

    public $table_column_all = "main_id,main_tel,main_fansid,main_openid,main_orderid,main_orderPrice,main_pay_time,main_payNumber,main_isPay,main_ctime,main_payconf,main_type,main_payMsg,main_e_id,main_invoiceType,main_selectedInvoiceTitle,main_companyName,main_invoiceContent,main_invoiceName,main_invoicePhone,main_make_out_invoice_state,main_taxpayer_identification_number,main_invoiceBank,main_invoiceAccount,main_invoiceAddress";

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
    $insert_data['main_id'] = '';//  | NO | int(11) | 
    $insert_data['main_tel'] = '';//  | YES | varchar(255) | 
    $insert_data['main_fansid'] = '';//  | YES | int(11) | 
    $insert_data['main_openid'] = '';//  | YES | varchar(255) | 
    $insert_data['main_orderid'] = '';// 主订单id | YES | varchar(255) | 
    $insert_data['main_orderPrice'] = '';// 总价 | YES | float(11,2) | 
    $insert_data['main_pay_time'] = '';//  | YES | datetime | 
    $insert_data['main_payNumber'] = '';// 支付流水号 | YES | varchar(50) | 
    $insert_data['main_isPay'] = '';// 是否支付 0:未支付  1：已支付 | YES | int(10) | 
    $insert_data['main_ctime'] = '';//  | YES | datetime | 
    $insert_data['main_payconf'] = '';// 付款方式,0：表示微信，1：货到付款，2：来电支付，3：银行卡 | YES | int(11) | 
    $insert_data['main_type'] = '';// 0 : 微信  1：后台 | YES | int(10) | 
    $insert_data['main_payMsg'] = '';// 支付返回信息 | YES | varchar(1000) | 
    $insert_data['main_e_id'] = '';// 项目id | YES | int(10) | 
    $insert_data['main_invoiceType'] = '';// 1 普通发票 2 增值税发票 | YES | int(2) | 
    $insert_data['main_selectedInvoiceTitle'] = '';// 发票类型：1:个人，2 :单位 | YES | int(2) | 
    $insert_data['main_companyName'] = '';// 发票抬头   | YES | varchar(255) | 
    $insert_data['main_invoiceContent'] = '';// 0:明细，1：电脑配件，2:耗材，3：办公用品(备注:若增值发票则只能选 0 明细)  | YES | int(2) | 
    $insert_data['main_invoiceName'] = '';// 增值票收票人姓名 | YES | varchar(255) | 
    $insert_data['main_invoicePhone'] = '';// 增值票注册电话 | YES | varchar(255) | 
    $insert_data['main_make_out_invoice_state'] = '';// 订单开票状态0：不开票，1：未开票，2：已开票，3：已退票，发票信息错误，4：已退票，发票类型错误，5：已退票，退款 | YES | int(2) | 
    $insert_data['main_taxpayer_identification_number'] = '';// 纳税人识别号 | YES | varchar(255) | 
    $insert_data['main_invoiceBank'] = '';// 增值票开户银行 | YES | varchar(255) | 
    $insert_data['main_invoiceAccount'] = '';// 增值票开户帐号 | YES | varchar(255) | 
    $insert_data['main_invoiceAddress'] = '';// 增值票注册地址 | YES | varchar(255) | 


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