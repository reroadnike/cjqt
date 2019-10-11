<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/11
 * Time: 17:02
 */

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/base_setting/SuperdeskShopMemberBaseOutDbModel.class.php');

class sll_water_storeModel extends SuperdeskShopMemberBaseOutDbModel
{

    public $table_name = "sll_water_store";

    public $table_column_all = "store_id,beginStoreTime,endTime,store_name,store_address,store_code,ctime,status,userid,store_city,store_account,phone,store_user,bank_card_number,bank_name,bank_account,phone_type,store_province,endStoreTime,store_pwd,store_imageUrl1,store_imageUrl2,store_imageUrl3,store_codeUrl1,telmunber,store_district,store_street,store_addre_id,store_json";

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
            $insert_data['store_id']         = '';//  | NO | int(11) |
            $insert_data['beginStoreTime']   = '';// 上班时间: | YES | datetime |
            $insert_data['endTime']          = '';// 结束时间(到期时间) | YES | datetime |
            $insert_data['store_name']       = '';// 店名 | YES | varchar(255) |
            $insert_data['store_address']    = '';// 水店地址 | YES | varchar(255) |
            $insert_data['store_code']       = '';// 水店编号 | YES | varchar(255) |
            $insert_data['ctime']            = '';// 创建时间 | YES | datetime |
            $insert_data['status']           = '';// 0:正常1:到期2:暂停400:删除 | YES | varchar(255) |
            $insert_data['userid']           = '';// 外键/市级ID | YES | int(11) |
            $insert_data['store_city']       = '';// 所属城市 | YES | varchar(255) |
            $insert_data['store_account']    = '';// 水店账号 | YES | varchar(255) |
            $insert_data['phone']            = '';// 电话 | YES | varchar(255) |
            $insert_data['store_user']       = '';// 店主 | YES | varchar(255) |
            $insert_data['bank_card_number'] = '';// 银行账号 | YES | varchar(255) |
            $insert_data['bank_name']        = '';// 开户行 | YES | varchar(255) |
            $insert_data['bank_account']     = '';// 开户人 | YES | varchar(255) |
            $insert_data['phone_type']       = '';// 联系方式 | YES | int(255) |
            $insert_data['store_province']   = '';// 所属省 | YES | varchar(255) |
            $insert_data['endStoreTime']     = '';// 下班时间 | YES | datetime |
            $insert_data['store_pwd']        = '';// 密码 | YES | varchar(255) |
            $insert_data['store_imageUrl1']  = '';// 门店图片 | YES | varchar(255) |
            $insert_data['store_imageUrl2']  = '';// 门店图片 | YES | varchar(255) |
            $insert_data['store_imageUrl3']  = '';// 门店图片 | YES | varchar(255) |
            $insert_data['store_codeUrl1']   = '';// 二维码url | YES | varchar(255) |
            $insert_data['telmunber']        = '';// 固定电话 | YES | varchar(255) |
            $insert_data['store_district']   = '';// 所属区县 | YES | varchar(255) |
            $insert_data['store_street']     = '';// 所属街道 | YES | varchar(255) |
            $insert_data['store_addre_id']   = '';// 地址外键 | YES | varchar(255) |
            $insert_data['store_json']       = '';// 数据格式 | YES | varchar(2000) |


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

    public function getOneByIdForImport($id)
    {
        global $_GPC, $_W;

        if (empty($id)) {
            return null;
        }
        $fields = array(
            'store_id',
            'store_account',
            'store_name',
            'store_user',
            'store_address',
            'phone',
            'store_code',
            'ctime',
            'endTime',
            'status'
        );


        $result = $this->pdo_get($this->table_name, array('store_id' => $id),$fields);

        return $result;
    }

    public function queryForImport($where = array(), $page = 0, $page_size = 50)
    {
        global $_GPC, $_W;//TIMESTAMP

        $page = max(1, intval($page));

        $where_sql = "";

//        $where_sql .= " WHERE `uniacid` = :uniacid";
//        $params = array(
//            ':uniacid' => $_W['uniacid'],
//        );

        $total = $this->pdo_fetchcolumn("SELECT COUNT(0) FROM " . $this->tablename($this->table_name) . $where_sql, $params);
        $list  = $this->pdo_fetchall(
            " SELECT ".
            "       store_id,".
            "       store_account,store_name,store_user,store_address,phone,store_code,".
            "       ctime,endTime,status".
//            "       userid,".//这个只是说明是那个登陆用户去录入的，费
//            "       beginStoreTime,endStoreTime,".
//            "       bank_card_number,bank_name,bank_account,".
//            "       phone_type,telmunber,".
//            "       store_province,store_city,store_district,store_street,store_addre_id,".
//            "       store_imageUrl1,store_imageUrl2,store_imageUrl3,store_codeUrl1,".
//            "       store_pwd,".
//            "       store_json" .
            " FROM " . $this->tablename($this->table_name) .
            $where_sql .
            " ORDER BY store_id ASC ".
            " LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

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
        $list  = $this->pdo_fetchall("SELECT * FROM " . $this->tablename($this->table_name) . $where_sql . " ORDER BY store_id ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);
//        $pager = pagination($total, $page, $page_size);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }
}