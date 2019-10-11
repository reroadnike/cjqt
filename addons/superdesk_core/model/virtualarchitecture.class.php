<?php

/**
 *
 * 虚拟建筑;虚拟体系结构
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 6/19/17
 * Time: 11:28 AM
 */
class virtualarchitectureModel
{

    public $table_name = "superdesk_core_virtualarchitecture";

    //    对映:
    // createtime_,updatetime,enabled,
    public $table_column_all =
        "id,name,organizationId,type,code,parentCode,remark,codeNumber,customerNumber,phone,address,contacts,employees,reserveBalance,customerType,contractStatus,status,reviewRemark,wxUserId,creator,createTime,modifier,modifyTime,isEnabled,uniacid";
//      "id,name,organizationId,type,code,parentCode,remark,codeNumber,customerNumber,phone,address,contacts,employees,reserveBalance,customerType,contractStatus,status,reviewRemark,wxUserId,creator,createTime,modifier,modifyTime,isEnabled";

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

    public function getOneByCodeNumber($codeNumber)
    {

        global $_GPC, $_W;

        $column = array(
            'codeNumber' => $codeNumber
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
     * 根据ID查 enterprise_name
     * @param $id
     *
     * @return string
     */
    public function getEnterpriseNameById($id)
    {

        global $_GPC, $_W;

        $params = array(
            "id" => $id
        );

        $result = pdo_fetch(
            ' select name ' .//	organizationId 如果还要项目
            ' from ' . tablename($this->table_name) .
            ' where id=:id '.
            ' limit 1',
            $params
        );

        if (empty($result)) {
            return '';
        } else {
            return $result['merchname'];
        }


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

        $where_sql .= " WHERE 1 = 1";

//        $where_sql .= " AND isEnabled = 1 ";


        $type = isset($where['type']) ? $where['type'] : 1;
        if (!empty($type)) {
            $where_sql .= " AND `type` = :type";
            $params[':type'] = $type;

//            if($type == 2){
//                $where_sql .= " AND `cityCode` is not NULL ";
////                $params[':type'] = $type;
//            }
        }

        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_name) . $where_sql, $params);
        $list  = pdo_fetchall("SELECT * FROM " . tablename($this->table_name) . $where_sql . " ORDER BY modifyTime DESC,createTime DESC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);
//        $pager = pagination($total, $page, $page_size);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }


    public function queryForUsersAjax($where = array(), $page = 0, $page_size = 50)
    {
        global $_GPC, $_W;//TIMESTAMP

        $page = max(1, intval($page));

        //zjh 2019年3月26日 17:55:25 可能搜索不到.而且假如真的合并.同一数据源的情况下已经没用了
//        $where_sql .= " WHERE `uniacid` = :uniacid";
//        $params = array(
//            ':uniacid' => $_W['uniacid'],
//        );

        $where_sql = ' WHERE 1 = 1 ';
        $params = array();

//        $type = isset($where['type']) ? $where['type'] : 1;//1企业 2部门
//        if (!empty($type)) {
//            $where_sql .= " AND `type` = :type";
//            $params[':type'] = $type;
//        }

        if (isset($where['isEnabled'])) {
            $where_sql .= " AND `isEnabled` = :isEnabled";
            $params[':isEnabled'] = isset($where['isEnabled']) ? $where['isEnabled'] : 1;//1可用 0删除
        }

        if(isset($where['organizationId'])){
            $where_sql .= " AND `organizationId` = :organizationId";
            $params[':organizationId'] = $where['organizationId'];
        }

        // 1-已签约;0-未签约
        if (isset($where['contractStatus'])) {
            $where_sql .= " AND `contractStatus` = :contractStatus";
            $params[':contractStatus'] = $where['contractStatus'];
        }

        // 0-待审核;1-通过;2-不通过
        if (isset($where['status'])) {
            $where_sql .= " AND `status` = :status";
            $params[':status'] = $where['status'];
        }


        $organizationId = isset($where['organizationId']) ? $where['organizationId'] : 0;
        if (!empty($organizationId)) {
            $where_sql .= " AND `organizationId` = :organizationId";
            $params[':organizationId'] = $organizationId;
        }

//        $where_sql .= " AND `codeNumber` != ''";
//        $codeNumber = isset($where['codeNumber']) ? $where['codeNumber'] : "";
//        if (!empty($codeNumber)) {
//            $where_sql .= " AND `codeNumber` = :codeNumber";
//            $params[':codeNumber'] = $codeNumber;
//        }

        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_name) . $where_sql, $params);
        $list  = pdo_fetchall(
            " SELECT ".
            "       id,name,organizationId,code,parentCode,codeNumber ".
            " FROM " . tablename($this->table_name) .
            $where_sql .
            " ORDER BY modifyTime DESC,createTime DESC ".
            " LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);

        socket_log(" SELECT ".
            "       id,name,organizationId,code,parentCode,codeNumber ".
            " FROM " . tablename($this->table_name) .
            $where_sql .
            " ORDER BY modifyTime DESC,createTime DESC ".
            " LIMIT " . ($page - 1) * $page_size . ',' . $page_size);

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