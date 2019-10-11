<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/07/24
 * Time: 17:10
 */


class order_financeModel
{

    public $table_name = "superdesk_shop_order_finance";

    public $table_column_all = "id,uniacid,merchid,orderid,ordersn,status,create_invoice_time,invoice_sn,remark,expressid,express_sn,press_type,press_msg,press_status,press_time,createtime";


    /**
     * 订单-财务跟踪  zjh 20180424 添加
     *
     * @param $order
     * 添加订单财务表数据
     *
     * 添加的情况:
     * 一级订单 不添加
     * 二级订单 拆分单 微信 自动发货添加 orderModal->setChildOrderPayResult
     * 二级订单 拆分单 企业月结 余额 手动发货添加 op->send
     * 二级订单 无拆分 微信 自动发货添加 orderModal->payResult
     * 二级订单 无拆分 企业月结 余额 手动发货添加 op->send
     * 三级订单 不添加
     */
    public function addOrderFinance($params)
    {
        global $_GPC, $_W;

        $_is_exist = $this->getOneByColumn(array('orderid'=>$params['orderid']));
        if(!$_is_exist){
            pdo_insert($this->table_name,
                array(
                    'uniacid'      => $_W['uniacid'],
                    'orderid'      => $params['orderid'],
                    'ordersn'      => $params['ordersn'],
                    'merchid'      => $params['merchid'],
                    'createtime'   => time(),
                    'press_status' => empty($params['press_status']) ? $params['press_status'] : 1, // TODO zero 20180727
                    'press_time'   => isset($params['press_time']) ? $params['press_time'] : 0,
                )
            );
        }
    }


    public function addOrderFinanceDefaultPressStatusEQ2(
        $id,
        $orderSn,
        $merchId,
        $press_status = 2/* 1 未回款 2 已回款 */
        )
    {

        global $_GPC, $_W;

        // TODO 插入订单财务表 zjh 2018年5月21日 16:34:21 添加
        $this->addOrderFinance(array(
                'orderid'      => $id,
                'ordersn'      => $orderSn,
                'merchid'      => $merchId,
                'press_status' => $press_status, // 默认为 已回款
                'press_time'   => strtotime('now'))
        );
    }

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
            $insert_data['merchid']             = '0';// 商户id | YES | int(11) | 0
            $insert_data['orderid']             = '0';// 订单id | YES | int(11) | 0
            $insert_data['ordersn']             = '';// 订单编号 | YES | varchar(30) |
            $insert_data['status']              = '1';// 状态(1:未开票,2:已开票) | YES | tinyint(4) | 1
            $insert_data['create_invoice_time'] = '0';// 开票时间 | YES | int(11) | 0
            $insert_data['invoice_sn']          = '';// 发票号 | YES | varchar(50) |
            $insert_data['remark']              = '';// 备注 | YES | text |
            $insert_data['expressid']           = '0';// 快递id | YES | int(11) | 0
            $insert_data['express_sn']          = '';// 快递单号 | YES | varchar(50) |
            $insert_data['press_type']          = '0';// 催款类型(1:业务催款,2:财务催款) | YES | tinyint(4) | 0
            $insert_data['press_msg']           = '';// 催款跟进记录 | YES | text |
            $insert_data['press_status']        = '1';// 是否回款(1:未回款,2:已回款) | YES | tinyint(4) | 1
            $insert_data['press_time']          = '0';// 回款时间 | YES | int(11) | 0


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

        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_name) . $where_sql, $params);
        $list  = pdo_fetchall("SELECT * FROM " . tablename($this->table_name) . $where_sql . " ORDER BY id ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);
//        $pager = pagination($total, $page, $page_size);

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }
}