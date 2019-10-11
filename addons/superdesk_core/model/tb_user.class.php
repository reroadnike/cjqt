<?php

/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/30
 * Time: 16:38
 */
class tb_userModel
{

    public $table_name = "superdesk_core_tb_user";

    public $table_column_all = "id,userName,nickName,userMobile,userType,userSex,userCardNo,birthday,userPhotoUrl,password,status,suggestion,address,imageUrl01,imageUrl02,imageUrl03,organizationId,virtualArchId,userNumber,enteringTime,positionName,departmentId,facePlusUserId,roleType,noticePower,creator,createTime,modifier,modifyTime,isEnabled,isSyncNeigou,uniacid";

    /**
     * @param $params
     */
    public function insert($params)
    {
        global $_GPC, $_W;

        $params['uniacid']    = $_W['uniacid'];
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
        $ret                  = pdo_update($this->table_name, $params, array('id' => $id));
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
        pdo_delete($this->table_name, array('id' => $id));
    }

    public function replace($params, $id = '')
    {
        global $_GPC, $_W;

        $params['uniacid'] = $_W['uniacid'];

        pdo_insert($this->table_name, $params, true);
    }

    /**
     * @param        $params
     * @param string $id
     */
    public function saveOrUpdate($params, $id = '')
    {
        global $_GPC, $_W;

        if (empty($id)) {
            $params['uniacid']    = $_W['uniacid'];
            $params['createtime'] = strtotime('now');

            $ret = pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }
        } else {
            $params['updatetime'] = strtotime('now');
            $ret                  = pdo_update($this->table_name, $params, array('id' => $id));
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

            $params['uniacid']    = $_W['uniacid'];
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
            $insert_data['userName']       = '';// 姓名 | YES | varchar(40) |
            $insert_data['nickName']       = '';// 昵称 | YES | varchar(40) |
            $insert_data['userMobile']     = '';// 手机号码 | YES | varchar(11) |
            $insert_data['userType']       = '';// 用户类型 | YES | varchar(2) |
            $insert_data['userSex']        = '';// 性别 | YES | varchar(2) |
            $insert_data['userCardNo']     = '';// 学生号/身份证 | YES | varchar(40) |
            $insert_data['birthday']       = '';// 生日 | YES | varchar(20) |
            $insert_data['userPhotoUrl']   = '';// 头像 | YES | varchar(200) |
            $insert_data['password']       = '';// 密码 | YES | varchar(100) |
            $insert_data['status']         = '';// 认证状态 | YES | varchar(2) |
            $insert_data['suggestion']     = '';// 审核建议 | YES | varchar(250) |
            $insert_data['address']        = '';// 详细地址 | YES | varchar(200) |
            $insert_data['imageUrl01']     = '';// 证件照片1 | YES | varchar(200) |
            $insert_data['imageUrl02']     = '';// 证件照片2 | YES | varchar(200) |
            $insert_data['imageUrl03']     = '';//  | YES | varchar(200) |
            $insert_data['organizationId'] = '';// 用户所属组织 | YES | int(11) |
            $insert_data['virtualArchId']  = '';// 学院/系部ID | YES | int(11) |
            $insert_data['userNumber']     = '';// 员工编号 | YES | varchar(40) |
            $insert_data['enteringTime']   = '';// 入司时间 | YES | date |
            $insert_data['positionName']   = '';// 职位名称 | YES | varchar(40) |
            $insert_data['departmentId']   = '';// 部门ID | YES | int(11) |
            $insert_data['facePlusUserId'] = '';// face++用户唯一标识 | YES | int(11) |
            $insert_data['roleType']       = '';// 企业用户角色（1-管理员，2-普通用户） | YES | varchar(2) |
            $insert_data['noticePower']    = '';// 接受审核通知（0-不接收用户申请通知，关，1-接收用户申请通知，开） | YES | varchar(2) |
            $insert_data['creator']        = '';// 创建者 | YES | varchar(20) |
            $insert_data['createTime']     = '';// 创建时间 | YES | datetime |
            $insert_data['modifier']       = '';// 修改人 | YES | varchar(20) |
            $insert_data['modifyTime']     = '';// 修改时间 | YES | datetime |
            $insert_data['isEnabled']      = '';// 是否可用 | YES | varchar(2) |
            $insert_data['isSyncNeigou']   = '';// 是否同步内购网 | YES | int(11) |


            $insert_data['uniacid']    = $_W['uniacid'];
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
     *
     * @return bool
     */
    public function getOne($id)
    {
        global $_GPC, $_W;

        if (empty($id)) {
            return null;
        }

        $result = pdo_get($this->table_name, array('id' => $id));

        return $result;

    }

    public function getOneByUserMobile($userMobile)
    {
        global $_GPC, $_W;

//        status:0-未审核;1-已认证;2-未认证
//        isEnabled:1-可用;0-删除

        $column = array(
            'isEnabled'  => 1,
            'userMobile' => $userMobile
        );
        return $this->getOneByColumn($column);
    }

    public function getOneByUserMobileVirIdOrgId($userMobile, $virId, $orgId)
    {
        global $_GPC, $_W;

//        status:0-未审核;1-已认证;2-未认证
//        isEnabled:1-可用;0-删除

        $column = array(
            'isEnabled'      => 1,
            'userMobile'     => $userMobile,
            'virtualArchId'  => $virId,
            'organizationId' => $orgId
        );
        return $this->getOneByColumn($column);
    }

    /**
     * @param array $column
     *
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
     * @param int   $page
     * @param int   $page_size
     *
     * @return array
     */
    public function queryAll($where = array(), $page = 0, $page_size = 50)
    {
        global $_GPC, $_W;//TIMESTAMP

        $page = max(1, intval($page));

//        $where_sql .= " WHERE `uniacid` = :uniacid";
//        $params = array(
//            ':uniacid' => $_W['uniacid'],
//        );

        $where_sql .= " WHERE 1 = 1 ";


        if (isset($where['keywords'])) {

            $params[':keywords'] = '%' . trim($where['keywords']) . '%';

            $condition = '';
            $condition .= ' userName LIKE :keywords ';
            $condition .= ' OR nickName LIKE :keywords ';
            $condition .= ' OR userMobile LIKE :keywords ';

            $where_sql .= ' AND (' . $condition . ') ';
        }



        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_name) . $where_sql, $params);
        $list  = pdo_fetchall("SELECT * FROM " . tablename($this->table_name) . $where_sql . " ORDER BY id ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }

    public function checkSyncCreateTime()
    {
        global $_GPC, $_W;

        $page      = 1;
        $page_size = 1;
        $params    = array();

        $where_sql = " WHERE 1 = 1 ";

        $list = pdo_fetchall(
            " SELECT createTime " .
            " FROM " . tablename($this->table_name) . $where_sql .
            " ORDER BY createTime DESC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);
        return $list;
    }

    public function checkSyncModifyTime()
    {
        global $_GPC, $_W;

        $page      = 1;
        $page_size = 1;
        $params    = array();

        $where_sql = " WHERE 1 = 1 ";

        $list = pdo_fetchall(
            " SELECT modifyTime " .
            " FROM " . tablename($this->table_name) . $where_sql .
            " ORDER BY modifyTime DESC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);
        return $list;
    }
}