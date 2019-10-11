<?php
namespace superdesk_core\model\out_db;
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/22
 * Time: 16:20
 */

include_once(IA_ROOT . '/addons/superdesk_core/model/out_db/base_setting/SuperdeskCoreBaseOutDbModel.class.php');

class tb_app_userModel extends SuperdeskCoreBaseOutDbModel
{

    public $table_name = "tb_app_user";

    public $table_column_all = "ID,USERNAME,PASSWORD,PASSWORD_HINT,TELEPHONE,FULL_NAME,CREATED_TIME,LAST_LOGIN_TIME,LAST_UPDATED_TIME,LAST_UPDATED_BY,CITYSYSID,USER_CODE,SEX,AGE,POSITION,PROFESSION,SKILL_LV,PHOTO_URL,EMP_DATE,ACCOUNT_EXPIRED,ACCOUNT_LOCKED,ACCOUNT_ENABLED,CREDENTIALS_EXPIRED,VERSION,EP_ID,IDENTIFY_NUM,REMARK,EXPIRED_TIME,CHANNEL,ERRORNUM,ISPHONECHECK,USER_TYPE,ISENABLED,shop_Password";

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
    $insert_data['ID'] = '';//  | NO | int(11) | 
    $insert_data['USERNAME'] = '';// 登录用户名 | NO | varchar(50) | 
    $insert_data['PASSWORD'] = '';// 密码 | NO | varchar(128) | 
    $insert_data['PASSWORD_HINT'] = '';// 密码提示 | YES | varchar(128) | 
    $insert_data['TELEPHONE'] = '';// 电话 | YES | varchar(128) | 
    $insert_data['FULL_NAME'] = '';// 姓名 | YES | varchar(50) | 
    $insert_data['CREATED_TIME'] = '';// 创建时间 | YES | datetime | 
    $insert_data['LAST_LOGIN_TIME'] = '';// 最近登录时间 | YES | datetime | 
    $insert_data['LAST_UPDATED_TIME'] = '';// 最后更新时间 | YES | datetime | 
    $insert_data['LAST_UPDATED_BY'] = '';// 最后更改人 | YES | varchar(40) | 
    $insert_data['CITYSYSID'] = '';// 城市ID | YES | varchar(50) | 
    $insert_data['USER_CODE'] = '';// 用户工号 | YES | varchar(28) | 
    $insert_data['SEX'] = '';//  | YES | varchar(2) | 
    $insert_data['AGE'] = '';// 年龄 | YES | int(11) | 
    $insert_data['POSITION'] = '';// 职位 | YES | varchar(30) | 
    $insert_data['PROFESSION'] = '';// 工种 | YES | varchar(30) | 
    $insert_data['SKILL_LV'] = '';// 技能等级 | YES | varchar(30) | 
    $insert_data['PHOTO_URL'] = '';// 员工照片URL地址 | YES | varchar(50) | 
    $insert_data['EMP_DATE'] = '';// 入职时间 | YES | datetime | 
    $insert_data['ACCOUNT_EXPIRED'] = '';// 是否过期 | YES | int(11) | 
    $insert_data['ACCOUNT_LOCKED'] = '';// 是否锁定 | YES | int(11) | 
    $insert_data['ACCOUNT_ENABLED'] = '';// 有效标志 | YES | int(11) | 
    $insert_data['CREDENTIALS_EXPIRED'] = '';// 认证过期 | YES | int(11) | 
    $insert_data['VERSION'] = '';// 版本 | YES | int(11) | 
    $insert_data['EP_ID'] = '';//  | YES | varchar(20) | 
    $insert_data['IDENTIFY_NUM'] = '';// 验证次数 | YES | varchar(255) | 
    $insert_data['REMARK'] = '';// 备注信息 | YES | varchar(40) | 
    $insert_data['EXPIRED_TIME'] = '';// 到期时间 | YES | datetime | 
    $insert_data['CHANNEL'] = '';// 渠道 | YES | varchar(50) | 
    $insert_data['ERRORNUM'] = '';// 错误次数 | YES | int(11) | 
    $insert_data['ISPHONECHECK'] = '';// 是否电话验证 | YES | int(11) | 
    $insert_data['USER_TYPE'] = '';// 用户类型（1-管理员，2-员工） | YES | varchar(2) | 
    $insert_data['ISENABLED'] = '';// 1-有效 0-无效 | YES | char(1) | 
    $insert_data['shop_Password'] = '';// 密码：用于商城登录用 | YES | varchar(128) | 


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