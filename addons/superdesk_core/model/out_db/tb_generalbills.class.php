<?php
namespace superdesk_core\model\out_db;
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/22
 * Time: 16:20
 */

include_once(IA_ROOT . '/addons/superdesk_core/model/out_db/base_setting/SuperdeskCoreBaseOutDbModel.class.php');

class tb_generalbillsModel extends SuperdeskCoreBaseOutDbModel
{

    public $table_name = "tb_generalbills";

    public $table_column_all = "ID,virtualId,organizationId,outMonth,startTime,endTime,totalFee,payFee,dedFee,payStatus,payType,payTime,billNo,transactionId,makePerson,makeTime,creator,createTime,modifier,modifyTime,isEnabled";

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
    $insert_data['ID'] = '';// ID | NO | int(11) | 
    $insert_data['virtualId'] = '';// 企业ID | YES | int(11) | 
    $insert_data['organizationId'] = '';// 项目ID | YES | int(11) | 
    $insert_data['outMonth'] = '';// 账单月份 | YES | varchar(20) | 
    $insert_data['startTime'] = '';// 计费开始时间 | YES | datetime | 
    $insert_data['endTime'] = '';// 计费结束时间 | YES | datetime | 
    $insert_data['totalFee'] = '';// 本期费用总额 | YES | decimal(11,2) | 
    $insert_data['payFee'] = '';// 实付金额 | YES | decimal(11,2) | 
    $insert_data['dedFee'] = '';// 此时剩余预付款余额 | YES | decimal(11,2) | 
    $insert_data['payStatus'] = '';// 支付状态（1-支付成功、0-未支付、2-支付失败）默认为0 | YES | char(2) | 
    $insert_data['payType'] = '';// 支付方式（cash-现金、wxpay-微信、alipay-支付宝） | YES | char(20) | 
    $insert_data['payTime'] = '';// 支付日期 | YES | datetime | 
    $insert_data['billNo'] = '';// 业务账单号 | YES | varchar(200) | 
    $insert_data['transactionId'] = '';// 支付业务订单号（微信、支付宝） | YES | varchar(200) | 
    $insert_data['makePerson'] = '';// 制单人 | YES | varchar(40) | 
    $insert_data['makeTime'] = '';// 制单时间 | YES | varchar(40) | 
    $insert_data['creator'] = '';// 创建人 | YES | varchar(40) | 
    $insert_data['createTime'] = '';// 账单生成时间 | YES | datetime | 
    $insert_data['modifier'] = '';// 修改人 | YES | varchar(20) | 
    $insert_data['modifyTime'] = '';// 修改时间 | YES | datetime | 
    $insert_data['isEnabled'] = '';// 是否可用或删除 | YES | varchar(2) | 


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