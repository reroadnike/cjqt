<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/11
 * Time: 17:02
 */

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/base_setting/SuperdeskShopMemberBaseOutDbModel.class.php');

class zc_import_memberModel extends SuperdeskShopMemberBaseOutDbModel
{

    public $table_name = "zc_import_member";

    public $table_column_all = "m_id,m_name,m_mobile,m_id_card,m_status,m_registration_time,m_account,m_poll_code,m_account_pwd,m_fansId,m_e_id,m_company,m_branch_company,m_department,m_team,m_position,m_welfare_level,m_import_time,m_card_num,m_job_num,m_e_code";

    /**
     * @param $params
     */
    public function insert($params)
    {
        global $_GPC, $_W;

//        $params['uniacid']    = $_W['uniacid'];
//        $params['createtime'] = strtotime('now');

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

//        $params['updatetime'] = strtotime('now');
        $ret                  = $this->pdo_update($this->table_name, $params, array('id' => $id));
    }


    /**
     * @param $id
     *
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
     * @param        $params
     * @param string $id
     */
    public function saveOrUpdate($params, $id = '')
    {
        global $_GPC, $_W;

        if (empty($id)) {
//            $params['uniacid']    = $_W['uniacid'];
//            $params['createtime'] = strtotime('now');

            $ret = $this->pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = $this->pdo_insertid();
            }
        } else {
            $params['updatetime'] = strtotime('now');
            $ret                  = $this->pdo_update($this->table_name, $params, array('id' => $id));
        }

    }

    /**
     * @param       $params
     * @param array $column
     */
    public function saveOrUpdateByColumn($params, $column = array())
    {
        global $_GPC, $_W;

        $_is_exist = $this->getOneByColumn($column);

        // 如果没找到会返回 false
        if (!$_is_exist) {

//            $params['uniacid']    = $_W['uniacid'];
//            $params['createtime'] = strtotime('now');

            $ret = $this->pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = $this->pdo_insertid();
            }

        } else {

//            $params['updatetime'] = strtotime('now');

            $ret = $this->pdo_update($this->table_name, $params, $column);

        }

    }

    public function saveOrUpdateByJdVop($params, $sku)
    {
        global $_GPC, $_W;


        $column    = array(
            "jd_vop_sku" => $sku
        );
        $_is_exist = $this->getOneByColumn($column);

        // 如果没找到会返回 false
        if (!$_is_exist) {

            $insert_data = array();

            // TODO
            $insert_data['m_id']                = '';//  | NO | int(11) |
            $insert_data['m_name']              = '';// 会员名称 | YES | varchar(255) |
            $insert_data['m_mobile']            = '';// 手机号码 | YES | varchar(20) |
            $insert_data['m_id_card']           = '';// 身份证 | YES | varchar(255) |
            $insert_data['m_status']            = '';// 会员状态0：黑名单，1：正常 2:未注册 | YES | int(11) |
            $insert_data['m_registration_time'] = '';// 注册时间 | YES | datetime |
            $insert_data['m_account']           = '';// 会员帐号 | YES | varchar(255) |
            $insert_data['m_poll_code']         = '';// 帐号注册码 | YES | varchar(255) |
            $insert_data['m_account_pwd']       = '';// 帐号密码 | YES | varchar(255) |
            $insert_data['m_fansId']            = '';// 对应粉丝表粉丝的id | YES | int(11) |
            $insert_data['m_e_id']              = '';// 所属企业id | YES | int(11) |
            $insert_data['m_company']           = '';// 所属企业名称 | YES | varchar(255) |
            $insert_data['m_branch_company']    = '';// 所属分公司 | YES | varchar(255) |
            $insert_data['m_department']        = '';// 所属部门 | YES | varchar(255) |
            $insert_data['m_team']              = '';// 所属小组 | YES | varchar(255) |
            $insert_data['m_position']          = '';// 所属职务 | YES | varchar(255) |
            $insert_data['m_welfare_level']     = '';// 所属福利等级 | YES | varchar(255) |
            $insert_data['m_import_time']       = '';// 导入时间 | YES | datetime |
            $insert_data['m_card_num']          = '';// 福利卡号 | YES | varchar(255) |
            $insert_data['m_job_num']           = '';// 工号 | YES | varchar(255) |
            $insert_data['m_e_code']            = '';// 企业编号 | YES | varchar(255) |


//            $insert_data['uniacid']    = $_W['uniacid'];
//            $insert_data['createtime'] = strtotime('now');

            $ret = $this->pdo_insert($this->table_name, $insert_data);
            if (!empty($ret)) {
                $id = $this->pdo_insertid();
            }

        } else {

            $update_data = array();

            // TODO

//            $update_data['updatetime'] = strtotime('now');

            $ret = $this->pdo_update($this->table_name, $update_data, $column);

        }

    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function getOne($id)
    {
        global $_GPC, $_W;

        if (empty($id)) {
            return null;
        }

        $result = $this->pdo_get($this->table_name, array('id' => $id));

        return $result;

    }

    /**
     * @param array $column
     *
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
     * @param int   $page
     * @param int   $page_size
     *
     * @return array
     */
    public function queryAll($where = array(), $page = 0, $page_size = 50)
    {
        global $_GPC, $_W;//TIMESTAMP

        $page = max(1, intval($page));

        $where_sql = "";

//        $where_sql .= " WHERE `uniacid` = :uniacid";
//        $params = array(
//            ':uniacid' => $_W['uniacid'],
//        );

        $total = $this->pdo_fetchcolumn("SELECT COUNT(*) FROM " . $this->tablename($this->table_name) . $where_sql, $params);
        $list  = $this->pdo_fetchall("SELECT * FROM " . $this->tablename($this->table_name) . $where_sql . " ORDER BY m_id ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);
//        $pager = pagination($total, $page, $page_size);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }
}