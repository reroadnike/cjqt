<?php
namespace superdesk_core\model\out_db;
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/22
 * Time: 16:20
 */

include_once(IA_ROOT . '/addons/superdesk_core/model/out_db/base_setting/SuperdeskCoreBaseOutDbModel.class.php');

class tb_visitorinfoModel extends SuperdeskCoreBaseOutDbModel
{

    public $table_name = "tb_visitorinfo";

    public $table_column_all = "visitorId,visitorOrderId,visitorName,visitorPhone,userHeadUrl,visitorStartTime,visitorEndTime,visitorCreateTime,visitorUpdateTime,visitorIsEnabled,visitorWelcoming,visitorSubjectId,visitorAccount,visitorPassword,visitorStatus,visitorOpenId,visitorIdentification,visitorType,visitorCompanyName";

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
    $insert_data['visitorId'] = '';// 用户ID | NO | int(11) | 
    $insert_data['visitorOrderId'] = '';// 用户ID | YES | int(11) | 
    $insert_data['visitorName'] = '';// 用户姓名 | YES | varchar(40) | 
    $insert_data['visitorPhone'] = '';// 电话号码 | YES | varchar(40) | 
    $insert_data['userHeadUrl'] = '';// 用户头像 | YES | varchar(40) | 
    $insert_data['visitorStartTime'] = '';//  | YES | datetime | 
    $insert_data['visitorEndTime'] = '';// 用户openId | YES | datetime | 
    $insert_data['visitorCreateTime'] = '';// 创建时间 | YES | datetime | 
    $insert_data['visitorUpdateTime'] = '';// 修改时间 | YES | datetime | 
    $insert_data['visitorIsEnabled'] = '';// 0-不可用,1-可用 | YES | varchar(40) | 
    $insert_data['visitorWelcoming'] = '';//  | YES | varchar(400) | 
    $insert_data['visitorSubjectId'] = '';//  | YES | int(11) | 
    $insert_data['visitorAccount'] = '';// 修改次数 | YES | int(11) | 
    $insert_data['visitorPassword'] = '';//  | YES | varchar(40) | 
    $insert_data['visitorStatus'] = '';// 访客状态1-审核中，2-审核通过，3-审核不通过 | YES | varchar(40) | 
    $insert_data['visitorOpenId'] = '';//  | YES | varchar(40) | 
    $insert_data['visitorIdentification'] = '';//  | YES | varchar(40) | 
    $insert_data['visitorType'] = '';//  | YES | varchar(40) | 
    $insert_data['visitorCompanyName'] = '';//  | YES | varchar(40) | 


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