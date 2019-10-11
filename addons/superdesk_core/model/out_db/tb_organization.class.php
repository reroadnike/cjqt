<?php
namespace superdesk_core\model\out_db;
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/22
 * Time: 16:20
 */

include_once(IA_ROOT . '/addons/superdesk_core/model/out_db/base_setting/SuperdeskCoreBaseOutDbModel.class.php');

class tb_organizationModel extends SuperdeskCoreBaseOutDbModel
{

    public $table_name = "tb_organization";

    public $table_column_all =
        "ID,name,code,type,telephone,provinceCode,provinceName,cityCode,cityName,address,lng,lat,status,applicantName,applicantPhone,reviewRemark,applicantIdentity,wxUserId,createTime,creator,modifyTime,modifier,isEnabled,isSyncNeigou";

    /**
     * @param $params
     */
    public function insert($params)
    {
        global $_GPC, $_W;

        $params['uniacid']    = $_W['uniacid'];
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
            $params['uniacid']    = $_W['uniacid'];
            $params['createtime'] = strtotime('now');

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

            $params['uniacid']    = $_W['uniacid'];
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
            $insert_data['ID']                = '';//  | NO | int(11) |
            $insert_data['name']              = '';// 名称 | YES | varchar(64) |
            $insert_data['code']              = '';// 编码 | YES | varchar(128) |
            $insert_data['type']              = '';// 项目类型（高校校园、小区住宅、CBD写字楼） | YES | varchar(20) |
            $insert_data['telephone']         = '';// 服务热线 | YES | varchar(32) |
            $insert_data['provinceCode']      = '';// 所在省份编码 | YES | varchar(32) |
            $insert_data['provinceName']      = '';// 所在省份名称 | YES | varchar(40) |
            $insert_data['cityCode']          = '';// 所在城市编码 | YES | varchar(32) |
            $insert_data['cityName']          = '';// 所在城市名称 | YES | varchar(40) |
            $insert_data['address']           = '';// 详细地址 | YES | varchar(256) |
            $insert_data['lng']               = '';// 经度 | YES | decimal(20,4) |
            $insert_data['lat']               = '';// 纬度 | YES | decimal(20,4) |
            $insert_data['status']            = '';// 项目状态（0-待审核，1-通过，2-不通过） | YES | varchar(2) |
            $insert_data['applicantName']     = '';// 申请人姓名 | YES | varchar(40) |
            $insert_data['applicantPhone']    = '';// 申请人电话 | YES | varchar(12) |
            $insert_data['reviewRemark']      = '';// 审核信息说明 | YES | varchar(200) |
            $insert_data['applicantIdentity'] = '';// 申请人身份 | YES | varchar(40) |
            $insert_data['wxUserId']          = '';// 申请人的微信信息ID | YES | int(11) |
            $insert_data['createTime']        = '';// 创建时间 | YES | datetime |
            $insert_data['creator']           = '';// 创建者 | YES | varchar(20) |
            $insert_data['modifyTime']        = '';// 修改时间 | YES | datetime |
            $insert_data['modifier']          = '';// 修改人 | YES | varchar(20) |
            $insert_data['isEnabled']         = '';// 是否可用或删除 | YES | varchar(1) |
            $insert_data['isSyncNeigou']      = '';// 是否同步内购网 | YES | int(11) |


            $insert_data['uniacid']    = $_W['uniacid'];
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

        $where_sql = " WHERE 1 = 1 ";

        $total = $this->pdo_fetchcolumn(
            " SELECT COUNT(*) " .
            " FROM " . $this->tablename($this->table_name) .
            $where_sql,
            $params);
        $list  = $this->pdo_fetchall(
            " SELECT * " .
            " FROM " . $this->tablename($this->table_name) . $where_sql .
            " ORDER BY createTime ASC ,ID ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }


    public function syncAll($page = 1, $page_size = 1000)
    {
        global $_GPC, $_W;//TIMESTAMP

        return $this->queryAll(array(), $page, $page_size);
    }

    public function syncIncrementCreateTime($separate_time, $page = 1, $page_size = 1000)
    {
        global $_GPC, $_W;//TIMESTAMP

        $params = array();

        $page = max(1, intval($page));

        $where_sql = " WHERE 1 = 1 ";

        if (!empty($separate_time)) {

            if($separate_time == '0000-00-00 00:00:00'){
                $where_sql .= " AND `createTime` >= :createTime";
            } else {
                $where_sql .= " AND `createTime` > :createTime";
            }
            $params[':createTime'] = $separate_time;
        }

        $total = $this->pdo_fetchcolumn(
            " SELECT COUNT(*) " .
            " FROM " . $this->tablename($this->table_name) .
            $where_sql,
            $params);
        $list  = $this->pdo_fetchall(
            " SELECT * " .
            " FROM " . $this->tablename($this->table_name) . $where_sql .
            " ORDER BY createTime ASC ,ID ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);


        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }

    public function syncIncrementModifyTime($separate_time, $page = 1, $page_size = 1000)
    {
        global $_GPC, $_W;//TIMESTAMP

        $params = array();

        $page = max(1, intval($page));

        $where_sql = " WHERE 1 = 1 ";

        if (!empty($separate_time)) {

            if($separate_time == '0000-00-00 00:00:00'){
                $where_sql .= " AND `modifyTime` >= :modifyTime";
            } else {
                $where_sql .= " AND `modifyTime` > :modifyTime";
            }

            $params[':modifyTime'] = $separate_time;
        }

        $total = $this->pdo_fetchcolumn(
            " SELECT COUNT(*) " .
            " FROM " . $this->tablename($this->table_name) .
            $where_sql,
            $params);
        $list  = $this->pdo_fetchall(
            " SELECT * " .
            " FROM " . $this->tablename($this->table_name) . $where_sql .
            " ORDER BY modifyTime ASC ,ID ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }
}