<?php
namespace superdesk_core\model\out_db;
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/22
 * Time: 16:20
 */

include_once(IA_ROOT . '/addons/superdesk_core/model/out_db/base_setting/SuperdeskCoreBaseOutDbModel.class.php');

class tb_staffModel extends SuperdeskCoreBaseOutDbModel
{

    public $table_name = "tb_staff";

    public $table_column_all = "ID,name,staffNo,userMobile,headUrl,birthDate,idCard,sex,status,jobStatus,position,companyId,deptId,pwd,integralNum,createTime,creator,modifyTime,modifier,isEnabled";

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
    $insert_data['name'] = '';// 姓名 | YES | varchar(50) | 
    $insert_data['staffNo'] = '';// 工号（登录账号） | YES | varchar(100) | 
    $insert_data['userMobile'] = '';// 手机号码 | YES | varchar(100) | 
    $insert_data['headUrl'] = '';// 头像 | YES | varchar(200) | 
    $insert_data['birthDate'] = '';// 出生日期 | YES | varchar(20) | 
    $insert_data['idCard'] = '';// 身份证 | YES | varchar(50) | 
    $insert_data['sex'] = '';// 性别 | YES | char(1) | 
    $insert_data['status'] = '';// 在职状态（1在职、0-离职） | YES | char(1) | 
    $insert_data['jobStatus'] = '';// 工作状态（1上班、0下班） | YES | char(1) | 
    $insert_data['position'] = '';// 职位 | YES | varchar(20) | 
    $insert_data['companyId'] = '';// 服务中心或者公司 | YES | int(11) | 
    $insert_data['deptId'] = '';// 部门或者团队 | YES | int(11) | 
    $insert_data['pwd'] = '';// 登录密码 | YES | varchar(100) | 
    $insert_data['integralNum'] = '';// 累积积分 | YES | int(11) | 
    $insert_data['createTime'] = '';// 创建时间 | YES | datetime | 
    $insert_data['creator'] = '';// 创建人 | YES | varchar(20) | 
    $insert_data['modifyTime'] = '';// 修改时间 | YES | datetime | 
    $insert_data['modifier'] = '';// 修改人 | YES | varchar(20) | 
    $insert_data['isEnabled'] = '';// 是否可用（逻辑删除，1-可用，0-删除不可用） | YES | char(1) | 


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