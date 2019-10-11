<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/11
 * Time: 17:02
 */

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/base_setting/SuperdeskShopMemberBaseOutDbModel.class.php');

class zc_invoiceModel extends SuperdeskShopMemberBaseOutDbModel
{

    public $table_name = "zc_invoice";

    public $table_column_all = "zi_id,zi_invoiceType,zi_selectedInvoiceTitle,zi_companyName,zi_invoiceContent,zi_invoiceAddress,zi_invoicePhone,zi_state,zi_fansid,zi_ctime,zi_taxpayer_identification_number,zi_invoiceBank,zi_invoiceAccount,zi_invoiceName";

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
            $insert_data['zi_id']                             = '';//  | NO | int(10) |
            $insert_data['zi_invoiceType']                    = '';// 1 普通发票 2 增值税发票 | YES | int(2) |
            $insert_data['zi_selectedInvoiceTitle']           = '';// 发票类型：1:个人，2 :单位 | YES | int(2) |
            $insert_data['zi_companyName']                    = '';// 发票抬头   | YES | varchar(255) |
            $insert_data['zi_invoiceContent']                 = '';// 0:明细，1：电脑配件，2:耗材，3：办公用品(备注:若增值发票则只能选 0 明细)  | YES | int(2) |
            $insert_data['zi_invoiceAddress']                 = '';// 增值票注册地址 | YES | varchar(255) |
            $insert_data['zi_invoicePhone']                   = '';// 增值票注册电话 | YES | varchar(255) |
            $insert_data['zi_state']                          = '';// 0:失效，1：有效 | YES | int(2) |
            $insert_data['zi_fansid']                         = '';// 粉丝id | YES | int(10) |
            $insert_data['zi_ctime']                          = '';// 创建时间 | YES | datetime |
            $insert_data['zi_taxpayer_identification_number'] = '';// 纳税人识别号 | YES | varchar(255) |
            $insert_data['zi_invoiceBank']                    = '';// 增值票开户银行 | YES | varchar(255) |
            $insert_data['zi_invoiceAccount']                 = '';// 增值票开户帐号 | YES | varchar(255) |
            $insert_data['zi_invoiceName']                    = '';// 增值票收票人 | YES | varchar(255) |


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

        $zi_fansid = isset($where['zi_fansid']) ? intval($where['zi_fansid']) : 0;
        if ($zi_fansid != 0) {
            $where_sql .= " AND `zi_fansid` = :zi_fansid ";
            $params[':zi_fansid'] = $zi_fansid;
        }

        $list = $this->pdo_fetchall(
            " SELECT * " .
            " FROM " . $this->tablename($this->table_name) .
            $where_sql .
            " ORDER BY zi_fansid ASC ",
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
        $list  = $this->pdo_fetchall("SELECT * FROM " . $this->tablename($this->table_name) . $where_sql . " ORDER BY zi_fansid ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);
//        $pager = pagination($total, $page, $page_size);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }
}