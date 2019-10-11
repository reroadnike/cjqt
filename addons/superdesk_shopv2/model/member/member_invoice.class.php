<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2017/12/06 * Time: 21:12 */


class member_invoiceModel
{

    public $table_name = "superdesk_shop_member_invoice";

    public $table_column_all = "id,uniacid,openid,realname,invoiceState,invoiceType,selectedInvoiceTitle,companyName,invoiceContent,invoiceName,invoicePhone,invoiceProvice,invoiceCity,invoiceCounty,invoiceAddress,isdefault,deleted";

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

        $ret = pdo_update($this->table_name, $params, array('id' => $id));
    }

    public function updateByColumn($params, $column = array())
    {
        pdo_update(
            'superdesk_shop_member_invoice',
            $params,
            $column
        );
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

            $params['updatetime'] = strtotime('now');
            $ret                  = pdo_update($this->table_name, $params, $column);

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
            $insert_data['openid']               = '0';//  | YES | varchar(50) | 0
            $insert_data['realname']             = '';// 个人发票抬头 | YES | varchar(20) |
            $insert_data['invoiceState']         = '1';// 开票方式(1为随货开票，0为订单预借，2为集中开票 ) | YES | int(11) | 1
            $insert_data['invoiceType']          = '1';// 1普通发票 2增值税发票 | YES | int(4) | 1
            $insert_data['selectedInvoiceTitle'] = '4';// 发票类型：4个人，5单位 | YES | int(4) | 4
            $insert_data['companyName']          = '';// 发票抬头  (如果selectedInvoiceTitle=5则此字段必须) | NO | varchar(250) |
            $insert_data['invoiceContent']       = '1';// 1:明细，3：电脑配件，19:耗材，22：办公用品备注:若增值发票则只能选1 明细 | NO | int(4) | 1
            $insert_data['invoiceName']          = '';// 增值票收票人姓名 备注：当invoiceType=2 且invoiceState=1时则此字段必填 | NO | varchar(64) |
            $insert_data['invoicePhone']         = '';// 增值票收票人电话 备注：当invoiceType=2 且invoiceState=1时则此字段必填 | NO | varchar(64) |
            $insert_data['invoiceProvice']       = '0';// 增值票收票人所在省(京东地址编码) 备注：当invoiceType=2 且invoiceState=1时则此字段必填 | NO | int(11) | 0
            $insert_data['invoiceCity']          = '0';// 增值票收票人所在市(京东地址编码) 备注：当invoiceType=2 且invoiceState=1时则此字段必填 | NO | int(11) | 0
            $insert_data['invoiceCounty']        = '0';// 增值票收票人所在区/县(京东地址编码) 备注：当invoiceType=2 且invoiceState=1时则此字段必填 | NO | int(11) | 0
            $insert_data['invoiceAddress']       = '';// 增值票收票人所在地址 备注：当invoiceType=2 且invoiceState=1时则此字段必填 | NO | varchar(512) |
            $insert_data['isdefault']            = '0';//  | YES | tinyint(1) | 0
            $insert_data['deleted']              = '0';//  | YES | tinyint(1) | 0


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

    public function getOneByIdVerStrict($invoice_id, $uniacid, $openid, $core_user = 0)
    {

        return pdo_fetch(
            ' select * ' .
            ' from ' . tablename('superdesk_shop_member_invoice') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 已处理
            ' where id=:id ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':id'        => $invoice_id,
                ':uniacid'   => $uniacid,
                ':openid'    => $openid,
                ':core_user' => $core_user,
            )
        );
    }


    public function getByDefault($uniacid, $openid, $core_user = 0)
    {
        $invoice = pdo_fetch(
            ' select ' . $this->table_column_all . ' ' .
            ' from ' . tablename($this->table_name) .
            ' where ' .
            '       uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and deleted=0 ' .
            '       and isdefault=1  ' .
            ' limit 1',
            array(
                ':uniacid'   => $uniacid,
                ':openid'    => $openid,
                ':core_user' => $core_user,
            )
        );

        return $invoice;
    }

    /**
     * 返回帐号上挂载的 (int)可用数
     *
     * @param     $uniacid
     * @param     $openid
     * @param int $core_user
     *
     * @return int
     */
    public function count($uniacid, $openid, $core_user = 0): int
    {
        $invoice_count = pdo_fetchcolumn(
            ' SELECT count(*) ' .
            ' FROM ' . tablename('superdesk_shop_member_invoice') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 已处理
            ' where ' .
            '       uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and deleted=0 ',
            array(
                ':uniacid'   => $uniacid,
                ':openid'    => $openid,
                ':core_user' => $core_user,
            )
        );
        return $invoice_count;
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

        $where_sql .= " WHERE `uniacid` = :uniacid";
        $params    = array(
            ':uniacid' => $_W['uniacid'],
        );
        if(isset($where['sql']) && $where['sql']){
            $where_sql .= $where['sql'];
            $params = array_merge($params, $where['params']);
        }

        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_name) . $where_sql, $params);
        $list  = pdo_fetchall("SELECT * FROM " . tablename($this->table_name) . $where_sql . " ORDER BY id DESC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);
//        $pager = pagination($total, $page, $page_size);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }
}