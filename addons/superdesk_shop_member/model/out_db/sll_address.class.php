<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/11
 * Time: 17:02
 */

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/base_setting/SuperdeskShopMemberBaseOutDbModel.class.php');

class sll_addressModel extends SuperdeskShopMemberBaseOutDbModel
{

    public $table_name = "sll_address";

    public $table_column_all = "address_id,address_name,fansid,phone,user_name,create_time,token,address_state,address_default,province,city,country,pkCode,street,citycode,community_id,community_name,community_code,provinceCode,countryCode,streetCode";

    /**
     * @param $params
     */
    public function insert($params)
    {
        global $_GPC, $_W;

//        $params['uniacid'] = $_W['uniacid'];
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
        $ret = $this->pdo_update($this->table_name, $params, array('id' => $id));
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
//            $params['uniacid'] = $_W['uniacid'];
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

//            $params['uniacid'] = $_W['uniacid'];
//            $params['createtime'] = strtotime('now');

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
            $insert_data['address_id']      = '';//  | NO | int(11) |
            $insert_data['address_name']    = '';// 地址 | YES | varchar(255) |
            $insert_data['fansid']          = '';// 用户id | YES | int(11) |
            $insert_data['phone']           = '';// 联系电话 | YES | varchar(255) |
            $insert_data['user_name']       = '';// 收件人 | YES | varchar(255) |
            $insert_data['create_time']     = '';// 创建时间 | YES | datetime |
            $insert_data['token']           = '';//  | YES | varchar(255) |
            $insert_data['address_state']   = '0';// 0：使用：1停止 | YES | int(11) | 0
            $insert_data['address_default'] = '';// 1:默认地址,0:非默认地址 | YES | int(10) |
            $insert_data['province']        = '';// 省 | YES | varchar(255) |
            $insert_data['city']            = '';// 市 | YES | varchar(255) |
            $insert_data['country']         = '';// 区/县 | YES | varchar(255) |
            $insert_data['pkCode']          = '';// 主键编码 | YES | varchar(255) |
            $insert_data['street']          = '';// 街道 | YES | varchar(255) |
            $insert_data['citycode']        = '';//  | YES | varchar(255) |
            $insert_data['community_id']    = '';//  | YES | int(11) |
            $insert_data['community_name']  = '';//  | YES | varchar(255) |
            $insert_data['community_code']  = '';//  | YES | varchar(255) |
            $insert_data['provinceCode']    = '';//  | YES | varchar(255) |
            $insert_data['countryCode']     = '';//  | YES | varchar(255) |
            $insert_data['streetCode']      = '';//  | YES | varchar(255) |


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

    public function queryByColumn($where = array()){

        global $_GPC, $_W;//TIMESTAMP

        $where_sql = " WHERE 1=1 ";

        $fansid = isset($where['fansid']) ? intval($where['fansid']) : 0;
        if ($fansid != 0) {
            $where_sql .= " AND `fansid` = :fansid ";
            $params[':fansid'] = $fansid;
        }

        $list = $this->pdo_fetchall(
            " SELECT * " .
            " FROM " . $this->tablename($this->table_name) .
            $where_sql .
            " ORDER BY fansid ASC ",
            $where
        );
        
        return $list;

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
        $list  = $this->pdo_fetchall("SELECT * FROM " . $this->tablename($this->table_name) . $where_sql . " ORDER BY fansid ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);
//        $pager = pagination($total, $page, $page_size);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }
}