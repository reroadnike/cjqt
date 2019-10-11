<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/23
 * Time: 16:08
 */

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/base_setting/SuperdeskShopv2BaseModel.class.php');

class merch_x_enterpriseModel extends SuperdeskShopv2BaseModel
{

    public $table_name = "superdesk_shop_merch_x_enterprise";

    public $table_column_all = "id,uniacid,merchid,enterprise_id,status,createtime";

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

//        $params['updatetime'] = strtotime('now');
        $ret = pdo_update($this->table_name, $params, array('id' => $id));
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
//            $params['updatetime'] = strtotime('now');
//            $ret = pdo_update($this->table_name, $params, $column);
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
            $insert_data['merchid']       = '0';//  | NO | int(11) | 0
            $insert_data['enterprise_id'] = '0';//  | NO | int(11) | 0
            $insert_data['status']        = '1';//  | YES | tinyint(1) | 1


            $insert_data['uniacid']    = $_W['uniacid'];
            $insert_data['createtime'] = strtotime('now');

            $ret = pdo_insert($this->table_name, $insert_data);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }

        } else {

            $update_data = array();

            // TODO

//            $update_data['updatetime'] = strtotime('now');

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

        $where_sql = "";

        $where_sql .= " WHERE x.uniacid = :uniacid";
        $params = array(
            ':uniacid' => $_W['uniacid'],
        );


        $status = isset($where['status']) ? intval($where['status']) : 1; // 0 禁用 1 启用
        if ($status != -1) {
            $where_sql .= " AND x.status = :status";
            $params[':status'] = $status;
        }

        $merchid = isset($where['merchid']) ? intval($where['merchid']) : 0;
        if ($merchid != 0) {
            $where_sql .= " AND x.merchid = :merchid";
            $params[':merchid'] = $merchid;
        }

        $enterprise_id = isset($where['enterprise_id']) ? intval($where['enterprise_id']) : 0;
        if ($enterprise_id != 0) {
            $where_sql .= " AND x.enterprise_id = :enterprise_id";
            $params[':enterprise_id'] = $enterprise_id;
        }

        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_name) . " x " . $where_sql, $params);

        // mark welfare
        switch (SUPERDESK_SHOPV2_MODE_USER) {
            case 1:// 1 超级前台
                $sql = " SELECT x.*,enterprise.name,enterprise.codeNumber FROM " . tablename($this->table_name) . " x " .
                       " left join " . tablename('superdesk_core_virtualarchitecture') . " enterprise on enterprise.id = x.enterprise_id " ;
                break;
            case 2:// 2 福利商城
                $sql = " SELECT x.*,enterprise.enterprise_name,enterprise.enterprise_no FROM " . tablename($this->table_name) . " x " .
                       " left join " . tablename('superdesk_shop_enterprise_user') . " enterprise on enterprise.id = x.enterprise_id " ;
                break;
        }

        $list  = pdo_fetchall(
            $sql . $where_sql .
            " ORDER BY x.createtime DESC " .
            " LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }

    public function getMerchByEnterpriseId($enterprise_id)
    {
        global $_GPC, $_W;//TIMESTAMP

        $where_sql = " WHERE 1 = 1 ";
        $params = array();

        if ($enterprise_id != 0) {
            $where_sql .= " AND enterprise_id = :enterprise_id";
            $params[':enterprise_id'] = $enterprise_id;
        }

        $list  = pdo_fetchall(
            " SELECT merchid".
            " FROM " . tablename($this->table_name) .
            $where_sql .
            " ORDER BY createtime DESC " , $params,'enterprise_id');

        return $list;
    }

}