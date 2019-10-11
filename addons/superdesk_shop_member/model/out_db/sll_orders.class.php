<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/11
 * Time: 17:02
 */

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/base_setting/SuperdeskShopMemberBaseOutDbModel.class.php');

class sll_ordersModel extends SuperdeskShopMemberBaseOutDbModel
{

    public $table_name = "sll_orders";

    public $table_column_all = "orders_id,fansid,openid,tel,payconf,ctime,orderPrice,isPay,state,type,scheduleTime,handletime,endTime,linkname,address,trackingname,paytime,orderid,payNumber,remark,kfRemark,user_id,wx_users_id,isNew,main_id,returnMsg,order_coupin_price,order_original_price,order_integral_price,jdorderid,jdstate,cancelTime,freight,freight_result,e_id";

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
    $insert_data['orders_id'] = '';//  | NO | int(11) | 
    $insert_data['fansid'] = '';//  | YES | int(20) | 
    $insert_data['openid'] = '';//  | YES | varchar(60) | 
    $insert_data['tel'] = '';//  | YES | varchar(15) | 
    $insert_data['payconf'] = '';// 付款方式,0：表示微信，1：货到付款，2：来电支付，3：银行卡 | YES | int(11) | 
    $insert_data['ctime'] = '';// 下单时间 | YES | datetime | 
    $insert_data['orderPrice'] = '';//  | YES | float(10,2) | 
    $insert_data['isPay'] = '';// 是否支付 0:未支付  1：已支付 ,2:已退款 | YES | int(10) | 
    $insert_data['state'] = '';// 1：未配送，2：配送中，3：表示已配送 ,4:确认收货 0:取消订单 | YES | int(11) | 
    $insert_data['type'] = '';// 0 : 微信  1：后台 | YES | int(10) | 
    $insert_data['scheduleTime'] = '';// 预定时间 | YES | datetime | 
    $insert_data['handletime'] = '';// 配送时间 | YES | datetime | 
    $insert_data['endTime'] = '';// 确认收货时间 | YES | datetime | 
    $insert_data['linkname'] = '';// 联系人姓名 | YES | varchar(100) | 
    $insert_data['address'] = '';// 用户地址 | YES | varchar(500) | 
    $insert_data['trackingname'] = '';// 配送人id | YES | varchar(100) | 
    $insert_data['paytime'] = '';// 支付时间 | YES | datetime | 
    $insert_data['orderid'] = '';// 订单ID | YES | varchar(100) | 
    $insert_data['payNumber'] = '';// 支付流水号 | YES | varchar(50) | 
    $insert_data['remark'] = '';// 备注 | YES | varchar(500) | 
    $insert_data['kfRemark'] = '';// 客服备注 | YES | varchar(500) | 
    $insert_data['user_id'] = '';//  | YES | int(11) | 
    $insert_data['wx_users_id'] = '';// 市级id | YES | int(11) | 
    $insert_data['isNew'] = '';// 1：新订单，未点击过。0：点击过 | YES | int(10) | 
    $insert_data['main_id'] = '';// 主订单的订单id（非自增长id） | YES | varchar(255) | 
    $insert_data['returnMsg'] = '';// 退款返回信息 | YES | varchar(1000) | 
    $insert_data['order_coupin_price'] = '0.00';// 优惠券优惠金额 | YES | float(10,2) | 0.00
    $insert_data['order_original_price'] = '0.00';// 订单原始金额 | YES | float(10,2) | 0.00
    $insert_data['order_integral_price'] = '0.00';// 积分优惠金额 | YES | float(10,2) | 0.00
    $insert_data['jdorderid'] = '';//  | YES | varchar(100) | 
    $insert_data['jdstate'] = '';// 0：待扫描，1：已扫描,2:京东下单失败 | YES | varchar(255) | 
    $insert_data['cancelTime'] = '';// 取消时间 | YES | datetime | 
    $insert_data['freight'] = '';// 运费 | YES | float(11,0) | 
    $insert_data['freight_result'] = '';// 调取京东运费接口返回数据 | YES | varchar(1000) | 
    $insert_data['e_id'] = '';// 项目id | YES | int(10) | 


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