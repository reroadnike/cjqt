<?php

/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 6/19/17
 * Time: 11:28 AM
 */

include_once(IA_ROOT . '/addons/superdesk_shopv2/service/transmit/TransmitShopOrderGoodsService.class.php');

class order_goodsModel
{

    public $table_name = "superdesk_shop_order_goods";

    public $table_column_all = "id,uniacid,orderid,goodsid,price,total,optionid,createtime,optionname,
    commission1,applytime1,checktime1,paytime1,invalidtime1,deletetime1,status1,content1,
    commission2,applytime2,checktime2,paytime2,invalidtime2,deletetime2,status2,content2,
    commission3,applytime3,checktime3,paytime3,invalidtime3,deletetime3,status3,content3,
    realprice,goodssn,productsn,nocommission,changeprice,oldprice,
    commissions,diyformdata,diyformfields,diyformdataid,openid,diyformid,rstate,refundtime,
    printstate,printstate2,merchid,parentorderid,merchsale,isdiscountprice,canbuyagain,
    return_goods_nun,return_goods_result";

    /**
     * @param $params
     */
    public function insert($params)
    {
        global $_GPC, $_W;

        if (!isset($params['uniacid']) || empty($params['uniacid'])) {
            $params['uniacid'] = $_W['uniacid'];
        }

        if (!isset($params['createtime']) || empty($params['createtime'])) {
            $params['createtime'] = strtotime('now');
        }

        pdo_insert($this->table_name, $params);

        $id = pdo_insertid();

        $__transmitShopOrderGoodsService = new TransmitShopOrderGoodsService();
        $__transmitShopOrderGoodsService->transmitShopOrderGoodsToBackup($this,array('id' => $id, 'uniacid' => $_W['uniacid']));

        return $id;

    }

    /**
     * @param $params
     * @param $id
     */
    public function update($params, $id)
    {
        global $_GPC, $_W;

        $ret = pdo_update($this->table_name, $params, array('id' => $id));

        $__transmitShopOrderGoodsService = new TransmitShopOrderGoodsService();
        $__transmitShopOrderGoodsService->transmitShopOrderGoodsToBackup($this,array('id' => $id, 'uniacid' => $_W['uniacid']));
    }

    public function updateByColumn($params, $column = array())
    {
        global $_GPC, $_W;

        $ret = pdo_update($this->table_name, $params, $column);

        $__transmitShopOrderGoodsService = new TransmitShopOrderGoodsService();
        $__transmitShopOrderGoodsService->transmitShopOrderGoodsToBackup($this,$column);
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

    public function deleteByOrderid($orderid)
    {
        global $_GPC, $_W;

        if (empty($orderid)) {
            return false;
        }

        pdo_delete($this->table_name, array('orderid' => $orderid));
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

        $__transmitShopOrderGoodsService = new TransmitShopOrderGoodsService();
        $__transmitShopOrderGoodsService->transmitShopOrderGoodsToBackup($this,array('id' => $id));

    }

    /**
     * @param       $params
     * @param array $column
     */
    public function saveOrUpdateByColumn($params, $column = array(), $is_log_createtime = false)
    {
        global $_GPC, $_W;

        $_is_exist = $this->getOneByColumn($column);

        // 如果没找到会返回 false
        if (!$_is_exist) {

            $params['uniacid'] = $_W['uniacid'];
            if ($is_log_createtime) {
                $params['createtime'] = strtotime('now');
            }
            $ret = pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }

        } else {
            $ret = pdo_update($this->table_name, $params, $column);
        }

        $__transmitShopOrderGoodsService = new TransmitShopOrderGoodsService();
        $__transmitShopOrderGoodsService->transmitShopOrderGoodsToBackup($this,$column);

    }

    /**
     * @param       $params
     * @param array $column
     * @param bool $is_log_createtime
     */
    public function saveOrUpdateByColumnForSplitSecondTime($params, $column = array(), $is_log_createtime = false)
    {
        global $_GPC, $_W;

        $_is_exist = $this->getOneByColumn($column);

        // 如果没找到会返回 false
        if (!$_is_exist) {

            $params['uniacid'] = $_W['uniacid'];
            $ret               = pdo_insert($this->table_name, $params);
            if (!empty($ret)) {
                $id = pdo_insertid();
            }

        } else {

            unset($params['goodsid']);
            unset($params['createtime']);

            $ret = pdo_update($this->table_name, $params, $column);
        }

        $__transmitShopOrderGoodsService = new TransmitShopOrderGoodsService();
        $__transmitShopOrderGoodsService->transmitShopOrderGoodsToBackup($this,$column);

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

    public function getGoodsIdBySkuIdAndCreatetime($skuId, $createtime, $openid, $core_user)
    {

        global $_GPC, $_W;

        if (empty($skuId)) {
            return 0;
        }

        $result = pdo_get(
            $this->table_name,
            array(
                'goodssn'    => $skuId,
                'createtime' => $createtime,
                'openid'     => $openid,
                //                'core_user'  => $core_user, // 暂时屏蔽
            )
        );

        if ($result) {
            return $result['goodsid'];
        } else {
            return 0;
        }
    }

    public function queryByParentIdForSplitOrderId($parent_order_id)
    {
        global $_GPC, $_W;

        $where_sql .= " WHERE `uniacid` = :uniacid";
        $params    = array(
            ':uniacid' => $_W['uniacid'],
        );

        $where_sql                  .= " AND `parent_order_id` = :parent_order_id";
        $params[':parent_order_id'] = $parent_order_id;

        $list = pdo_fetchall(
            " SELECT * " .
            " FROM " . tablename($this->table_name) . $where_sql .
            " ORDER BY id ASC ", $params); // 不会合成 , "orderid"

        $split_array = array();
        foreach ($list as $item) {

            if (!is_array($split_array[$item['orderid']])) {
                $split_array[$item['orderid']] = array();
            }

            $split_array[$item['orderid']][] = $item;

        }

        return $split_array;

    }

    /**
     * @param array $where
     * @param int $page
     * @param int $page_size
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

        $total = pdo_fetchcolumn(
            " SELECT COUNT(*) " .
            " FROM " . tablename($this->table_name) .
            $where_sql, $params
        );
        $list  = pdo_fetchall(
            " SELECT * " .
            " FROM " . tablename($this->table_name) .
            $where_sql .
            " ORDER BY id ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size,
            $params
        );

        $pager              = array();
        $pager['total']     = $total;
        $pager['page']      = $page;
        $pager['page_size'] = $page_size;
        $pager['data']      = $list;

        return $pager;

    }

    /**
     * @param array $where
     * @param int $page
     * @param int $page_size
     *
     * @return array
     */
    public function queryAllByColumn($column = array())
    {
        global $_GPC, $_W;

        $result = pdo_getall($this->table_name, $column);

        return $result;

    }

    public function transformSubmitOrderGoods($orderid)
    {

        global $_GPC, $_W;//TIMESTAMP

        $order_goods = pdo_fetchall(
            ' select og.id,g.title, og.goodsid,og.optionid,g.total as stock,og.total as buycount,' .
            '       g.status,g.deleted,g.maxbuy,g.usermaxbuy,g.istime,g.timestart,g.timeend,' .
            '       g.buylevels,g.buygroups,g.totalcnf,' .
            '       g.jd_vop_sku,g.jd_vop_page_num ' .
            ' from  ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
            ' left join ' . tablename('superdesk_shop_goods') . ' g on og.goodsid = g.id ' .
            ' where og.orderid=:orderid and og.uniacid=:uniacid ',
            array(
                ':uniacid' => $_W['uniacid'],
                ':orderid' => $orderid,
            )
        );

        // TODO

//        skuMap.put("skuId", sku);
//        skuMap.put("num", buyNumber);
//        skuMap.put("bNeedAnnex", true);
//        skuMap.put("bNeedGift", true);

//        orderPriceSnapMap.put("price", sellPResultMap.get("price"));
//        orderPriceSnapMap.put("skuId", sellPResultMap.get("skuId"));
    }

}


