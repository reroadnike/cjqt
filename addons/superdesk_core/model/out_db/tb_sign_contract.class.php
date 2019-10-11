<?php
namespace superdesk_core\model\out_db;
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/22
 * Time: 16:20
 */

include_once(IA_ROOT . '/addons/superdesk_core/model/out_db/base_setting/SuperdeskCoreBaseOutDbModel.class.php');

class tb_sign_contractModel extends SuperdeskCoreBaseOutDbModel
{

    public $table_name = "tb_sign_contract";

    public $table_column_all = "ID,organizationId,virtualarchitectureId,autograph,paymentMethod,serviceTypeInfoId,serviceTypeInCode,status,prepayment,pack,effectiveTime,startTime,endTime,signUserType,remarks,staffId,creator,createTime,modifier,modifyTime,isEnabled,isRenewalContract";

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
    $insert_data['organizationId'] = '';// 项目ID | YES | int(11) | 
    $insert_data['virtualarchitectureId'] = '';// 企业id | YES | int(11) | 
    $insert_data['autograph'] = '';// 签名信息（图片存储） | YES | varchar(200) | 
    $insert_data['paymentMethod'] = '';// 结算方式 | YES | int(2) | 
    $insert_data['serviceTypeInfoId'] = '';// 服务内容ID | YES | int(11) | 
    $insert_data['serviceTypeInCode'] = '';// 服务编码 | YES | varchar(20) | 
    $insert_data['status'] = '';// 协议状态（0-待审核、1-生效、2-失效过期） | YES | int(2) | 
    $insert_data['prepayment'] = '';// 预付费金额 | YES | decimal(11,2) | 
    $insert_data['pack'] = '';// 协议详情 | YES | text | 
    $insert_data['effectiveTime'] = '';// 协议生效时间 | YES | datetime | 
    $insert_data['startTime'] = '';// 协议开始时间 | YES | datetime | 
    $insert_data['endTime'] = '';// 协议结束时间 | YES | datetime | 
    $insert_data['signUserType'] = '';// 签约用户类型： 0 前台用户，1 后台管理员 | YES | int(2) | 
    $insert_data['remarks'] = '';// 备注 | YES | varchar(400) | 
    $insert_data['staffId'] = '';// 项目员工ID | YES | int(11) | 
    $insert_data['creator'] = '';// 创建人 | YES | varchar(20) | 
    $insert_data['createTime'] = '';// 创建时间 | YES | datetime | 
    $insert_data['modifier'] = '';// 修改人 | YES | varchar(20) | 
    $insert_data['modifyTime'] = '';// 修改时间 | YES | datetime | 
    $insert_data['isEnabled'] = '';// 是否可用或删除：0 禁用，1 可用 | YES | varchar(2) | 
    $insert_data['isRenewalContract'] = '0';// 是否续约：0 否，1 是 | YES | int(2) | 0


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