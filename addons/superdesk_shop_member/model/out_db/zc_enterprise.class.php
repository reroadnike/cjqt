<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/11
 * Time: 17:02
 */

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/base_setting/SuperdeskShopMemberBaseOutDbModel.class.php');

class zc_enterpriseModel extends SuperdeskShopMemberBaseOutDbModel
{

    public $table_name = "zc_enterprise";

    public $table_column_all = "e_id,e_number,e_name,e_status,e_end_time,e_ctime,e_business_license_address,e_office_address,e_switchboard,e_fax,e_business_license,e_tax_registration_certificate,e_organization_code_certificate,e_uniform_credit_code,e_province_name,e_city_name,e_area_name,e_street_name,e_province_code,e_city_code,e_area_code,e_street_code";

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
            $insert_data['e_id']                            = '';//  | NO | int(11) |
            $insert_data['e_number']                        = '';// 企业编号 | YES | varchar(22) |
            $insert_data['e_name']                          = '';// 企业名称 | YES | varchar(255) |
            $insert_data['e_status']                        = '';// 企业状态1:正常；0：过期 | YES | int(11) |
            $insert_data['e_end_time']                      = '';// 到期时间 | YES | datetime |
            $insert_data['e_ctime']                         = '';// 创建时间 | YES | datetime |
            $insert_data['e_business_license_address']      = '';// 营业执照地址 | YES | varchar(255) |
            $insert_data['e_office_address']                = '';// 办公地址 | YES | varchar(255) |
            $insert_data['e_switchboard']                   = '';// 总机 | YES | varchar(255) |
            $insert_data['e_fax']                           = '';// 传真 | YES | varchar(255) |
            $insert_data['e_business_license']              = '';// 营业执照 | YES | varchar(255) |
            $insert_data['e_tax_registration_certificate']  = '';// 税务登记证 | YES | varchar(255) |
            $insert_data['e_organization_code_certificate'] = '';// 组织机构代码证 | YES | varchar(255) |
            $insert_data['e_uniform_credit_code']           = '';// 统一信用代码 | YES | varchar(255) |
            $insert_data['e_province_name']                 = '';// 省 | YES | varchar(255) |
            $insert_data['e_city_name']                     = '';// 市 | YES | varchar(255) |
            $insert_data['e_area_name']                     = '';// 区 | YES | varchar(255) |
            $insert_data['e_street_name']                   = '';// 街道 | YES | varchar(255) |
            $insert_data['e_province_code']                 = '';// 省code | YES | varchar(255) |
            $insert_data['e_city_code']                     = '';// 市code | YES | varchar(255) |
            $insert_data['e_area_code']                     = '';// 区code | YES | varchar(255) |
            $insert_data['e_street_code']                   = '';// 街道code | YES | varchar(255) |


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
        $list  = $this->pdo_fetchall("SELECT * FROM " . $this->tablename($this->table_name) . $where_sql . " ORDER BY e_id ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);
//        $pager = pagination($total, $page, $page_size);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }
}