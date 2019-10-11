<?php

/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 6/19/17
 * Time: 11:28 AM
 */

include_once(IA_ROOT . '/addons/superdesk_shopv2/service/transmit/TransmitShopOrderService.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/service/transmit/TransmitUnifiedOrderService.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/service/transmit/TransmitUnifiedOrderRefundService.class.php');

class orderModel
{

    public $table_name = "superdesk_shop_order";

    public $table_column_all = "id,uniacid,openid,core_user,agentid,ordersn,price,goodsprice,discountprice,status,paytype,transid,remark,addressid,dispatchprice,dispatchid,createtime,dispatchtype,carrier,refundid,iscomment,creditadd,deleted,userdeleted,finishtime,paytime,expresscom,expresssn,express,sendtime,fetchtime,cash,canceltime,cancelpaytime,refundtime,isverify,verified,verifyopenid,verifytime,verifycode,verifystoreid,deductprice,deductcredit,deductcredit2,deductenough,virtual,virtual_info,virtual_str,address,sysdeleted,ordersn2,changeprice,changedispatchprice,oldprice,olddispatchprice,isvirtual,couponid,couponprice,diyformdata,diyformfields,diyformid,storeid,closereason,remarksaler,printstate,printstate2,address_send,refundstate,remarkclose,remarksend,ismr,isdiscountprice,isvirtualsend,virtualsend_info,verifyinfo,verifytype,verifycodes,merchid,invoice,ismerch,parentid,isparent,grprice,merchshow,merchdeductenough,couponmerchid,isglobonus,merchapply,isabonus,isborrow,borrowopenid,merchisdiscountprice,apppay,authorid,isauthor,coupongoodprice,buyagainprice,ispackage,packageid,taskdiscountprice";

    /**
     * @param $params
     */
    public function insert($params)
    {
        global $_GPC, $_W;

//        $params['uniacid']    = $_W['uniacid'];
//        $params['createtime'] = strtotime('now');

        pdo_insert($this->table_name, $params);

        $id = pdo_insertid();

//        $__transmitShopOrderService = new TransmitShopOrderService();
//        $__transmitShopOrderService->transmitShopOrderToBackup($this,array('id' => $id, 'uniacid' => $_W['uniacid']));

        $__transmitUnifiedOrderService = new TransmitUnifiedOrderService();
        $__transmitUnifiedOrderService->transmitOrderToUnified($this,array('id' => $id, 'uniacid' => $_W['uniacid']));

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

//        $__transmitShopOrderService = new TransmitShopOrderService();
//        $__transmitShopOrderService->transmitShopOrderToBackup($this,array('id' => $id, 'uniacid' => $_W['uniacid']));

        $__transmitUnifiedOrderService = new TransmitUnifiedOrderService();
        $__transmitUnifiedOrderService->transmitOrderToUnified($this,array('id' => $id, 'uniacid' => $_W['uniacid']));
    }

    /**
     * @param $params
     * @param $id
     */
    public function updateByColumn($params, $column)
    {
        global $_GPC, $_W;

        $ret = pdo_update($this->table_name, $params, $column);

        //TODO zjh 2019年3月1日 17:21:48 同时发送多个kafka消息到不同的服务端可能会导致后面的无法发送????
//        $__transmitShopOrderService = new TransmitShopOrderService();
//        $__transmitShopOrderService->transmitShopOrderToBackup($this,$column);


        //mark kafka java那边的统一订单中心 订单完成 提供者
        //2019年3月12日 10:06:50 zjh 去掉已完成才推送的判断
//        if (isset($params['finishtime']) && $params['finishtime'] > 0) {
            $__transmitUnifiedOrderService = new TransmitUnifiedOrderService();
            $__transmitUnifiedOrderService->transmitOrderToUnified($this,$column);
//        }

        //mark kafka java那边的统一订单中心 订单退款完成 提供者
//        if (isset($params['refundtime']) && $params['refundtime'] > 0) {
//            $__transmitUnifiedOrderRefundService = new TransmitUnifiedOrderRefundService();
//            $__transmitUnifiedOrderRefundService->transmitOrderRefundToUnified($this,$column['id']);
//        }
    }

    public function updateDeletedForSplit($parent_id, $spec_ids)
    {
        global $_GPC, $_W;

        asort($spec_ids);
        $spec_ids = array_unique($spec_ids);

        echo PHP_EOL;
        echo ' update ' . tablename($this->table_name) .
            ' set deleted = 1 ' .
            ' where uniacid=' . $_W['uniacid'] .
            '       and parentid=' . $parent_id .
            '       and id NOT IN (' . implode(',', $spec_ids) . ')';
        echo PHP_EOL;
        echo ' update ' . tablename($this->table_name) .
            ' set deleted = 0 ' .
            ' where uniacid=' . $_W['uniacid'] .
            '       and parentid=' . $parent_id .
            '       and id IN (' . implode(',', $spec_ids) . ')';

        if (!is_array($spec_ids)) {
            return false;
        }


//        update `ims_superdesk_shop_order` set deleted = 1 where parentid = 756  and id NOT IN(839,843,844);

        $ret_d_1 = pdo_query(
            ' update ' . tablename($this->table_name) .
            ' set deleted = 1 ' .
            ' where uniacid=' . $_W['uniacid'] .
            '       and parentid=' . $parent_id .
            '       and id NOT IN (' . implode(',', $spec_ids) . ')'
        );
        $ret_d_0 = pdo_query(
            ' update ' . tablename($this->table_name) .
            ' set deleted = 0 ' .
            ' where uniacid=' . $_W['uniacid'] .
            '       and parentid=' . $parent_id .
            '       and id IN (' . implode(',', $spec_ids) . ')'
        );

//        $__transmitShopOrderService = new TransmitShopOrderService();
//        $__transmitShopOrderService->transmitShopOrderToBackup($this,array('uniacid'=>$_W['uniacid'],'parentid'=>$parent_id));

        $__transmitUnifiedOrderService = new TransmitUnifiedOrderService();
        $__transmitUnifiedOrderService->transmitOrderToUnified($this,array('uniacid'=>$_W['uniacid'],'parentid'=>$parent_id));

//        return $row_count;

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

    public function deleteByParentId($parentid)
    {
        global $_GPC, $_W;
        if (empty($parentid)) {
            return false;
        }
        pdo_delete(
            $this->table_name,
            array(
                'parentid' => $parentid,
                'isparent' => 1,
            )
        );
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
            $ret = pdo_update($this->table_name, $params, array('id' => $id));
        }

//        $__transmitShopOrderService = new TransmitShopOrderService();
//        $__transmitShopOrderService->transmitShopOrderToBackup($this,array('id' => $id, 'uniacid' => $_W['uniacid']));

        $__transmitUnifiedOrderService = new TransmitUnifiedOrderService();
        $__transmitUnifiedOrderService->transmitOrderToUnified($this,array('id' => $id, 'uniacid' => $_W['uniacid']));

    }

    public function recoveryByOrderId($params, $column = array())
    {
        global $_GPC, $_W;

        $_is_exist = $this->getOneByColumn($column);


        // 如果没找到会返回 false
        if (!$_is_exist) {
            // TODO


        } else {

            if ($_is_exist['id'] > 10000 && $_is_exist['id'] != $column['id']) {
                pdo_update(
                    $this->table_name,
                    $params,
                    $column
                );

//                $__transmitShopOrderService = new TransmitShopOrderService();
//                $__transmitShopOrderService->transmitShopOrderToBackup($this,$column);

                $__transmitUnifiedOrderService = new TransmitUnifiedOrderService();
                $__transmitUnifiedOrderService->transmitOrderToUnified($this,$column);
            }


        }

    }

    /**
     * 拆单专用
     *
     * @param       $params
     * @param array $column
     *
     * @return array
     */
    public function saveOrUpdateByColumn($params, $column = array())
    {
        global $_GPC, $_W;

        $_is_exist = $this->getOneByColumn($column);

        // 如果没找到会返回 false
        if (!$_is_exist) {

            $params['uniacid'] = $_W['uniacid'];
            $params['ordersn'] = m('common')->createNO('order', 'ordersn', 'ME');// "ordersn": "ME20180117190343444566",

//            $params['createtime'] = strtotime('now');

            $ret = pdo_insert($this->table_name, $params);

            $return_result = array(
                'id'      => pdo_insertid(),
                'ordersn' => $params['ordersn'],
            );

        } else {
            pdo_update($this->table_name, $params, $column);

            $return_result = array(
                'id'      => $_is_exist['id'],
                'ordersn' => $_is_exist['ordersn'],
            );

        }

//        $__transmitShopOrderService = new TransmitShopOrderService();
//        $__transmitShopOrderService->transmitShopOrderToBackup($this,$column);

        $__transmitUnifiedOrderService = new TransmitUnifiedOrderService();
        $__transmitUnifiedOrderService->transmitOrderToUnified($this,$column);

        return $return_result;

    }

    public function getOrderIdByJdOrderId($JdOrderId)
    {

        global $_GPC, $_W;

        if (empty($JdOrderId)) {
            return 0;
        }

        $result = pdo_get($this->table_name, array('expresssn' => $JdOrderId));

        if ($result) {
            return $result['id'];
        } else {
            return 0;
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


    public function queryIdByParentId($parentid)
    {

        global $_GPC, $_W;

        $where_sql .= " WHERE `uniacid` = :uniacid";
        $params    = array(
            ':uniacid' => $_W['uniacid'],
        );

        $where_sql           .= " AND `parentid` = :parentid";
        $params[':parentid'] = $parentid;

        $list = pdo_fetchall("SELECT id FROM " . tablename($this->table_name) . $where_sql, $params);

        return $list;
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
        global $_GPC, $_W;

        $page = max(1, intval($page));

        $where_sql .= " WHERE `uniacid` = :uniacid";
        $params    = array(
            ':uniacid' => $_W['uniacid'],
        );

        $total = pdo_fetchcolumn("SELECT COUNT(id) FROM " . tablename($this->table_name) . $where_sql, $params);
        $list  = pdo_fetchall("SELECT * FROM " . tablename($this->table_name) . $where_sql . " ORDER BY id ASC LIMIT " . ($page - 1) * $page_size . ',' . $page_size, $params);

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

    /**
     * @param $JdOrderId
     * 根据京东单号查找商城订单
     */
    public function getOrderByJdOrderId($JdOrderId)
    {

        if (empty($JdOrderId)) {
            return [];
        }

        $result = pdo_get($this->table_name, array('expresssn' => $JdOrderId));

        if ($result) {
            return $result;
        } else {
            return [];
        }
    }

    /**
     * [{
     * "skuId": 商品编号,
     * "num": 商品数量,
     * "bNeedAnnex": true,
     * "bNeedGift": true,
     * "price": 100,
     * "yanbao": [{
     * "skuId": 商品编号
     * }]
     * }](最高支持50种商品)
     *
     * @param $orderid
     */
    public function transformSubmitOrder($orderid)
    {

        global $_GPC, $_W;//TIMESTAMP

        $order = $this->getOne($orderid);

//        $order for check

        include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order_goods.class.php');

        $_order_goodsModel = new order_goodsModel();

        $_order_goodsModel->transformSubmitOrderGoods($orderid);

    }

    /**
     * 提供java统一订单中心所需数据
     */
    public function unifiedOrderData($id)
    {


        $order = pdo_fetch(
            ' select o.id as orderid, o.parentid as parent_order_id, o.ordersn, o.expresssn, o.dispatchprice, o.price, o.paytype, o.remark, o.deleted, ' .
            '        o.createtime, o.sendtime, o.finishtime, o.paytime, o.status, o.invoiceid, o.invoice, ' .
            '        o.isverify, o.isvirtual, o.virtual, o.dispatchtype, o.carrier, o.address, o.addressid, ' .
            '        o.member_enterprise_id as enterpriseId, o.member_organization_id as organizationId,org.name as organization_name, o.member_enterprise_name as enterprise_name, ' .
            '        d.dispatchname,' .
            '        po.ordersn as parent_ordersn,' .
            '        m.openid,m.core_user as realnameId,m.realname,m.mobile,' .
            '        a.realname as arealname,a.mobile as amobile,a.province as aprovince ,a.city as acity , a.area as aarea,a.town as atown,a.address as aaddress, ' .
            '        oe.manager_openid as examineId,oe.manager_realname as examine_name, ' .
            '        merch.merchname as merch_name ' .
            ' from ' . tablename('superdesk_shop_order') . ' o' .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' left join ' . tablename('superdesk_shop_order') . ' po on po.id=o.parentid and o.parentid > 0 ' .// and m.core_user=o.core_user
            ' left join ' . tablename('superdesk_shop_member') . ' m on m.core_user=o.core_user and m.uniacid =  o.uniacid ' .
//            ' left join ' . tablename('superdesk_shop_member') . ' m on m.openid=o.openid and m.core_user=o.core_user and m.uniacid =  o.uniacid ' . //出现了换了openid的情况.这个情况还没处理故而先屏蔽
            ' left join ' . tablename('superdesk_shop_member_address') . ' a on a.id=o.addressid ' . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 不处理
            ' left join ' . tablename('superdesk_shop_dispatch') . ' d on d.id = o.dispatchid ' .
            ' left join ' . tablename('superdesk_shop_order_examine') . ' oe on oe.orderid = o.id and o.paytype=3' .// TODO 标志 楼宇之窗 openid superdesk_shop_order_examine 不处理
            ' left join ' . tablename('superdesk_shop_merch_user') . ' merch on merch.id = o.merchid ' .
            ' left join ' . tablename('superdesk_core_organization') . ' org on org.id = o.member_organization_id ' .
            ' where o.id=:orderid ',
            array(
                ':orderid' => $id,
            )
        );

        $order['dispatchname'] = ((empty($order['addressid']) ? '自提' : $order['dispatchname']));

        if (empty($order['dispatchname'])) {
            $order['dispatchname'] = '快递';
        }

        if ($order['paytype'] == 3) {
            $order['dispatchname'] = '企业月结';
        } else if ($order['isverify'] == 1) {
            $order['dispatchname'] = '线下核销';
        } else if ($order['isvirtual'] == 1) {
            $order['dispatchname'] = '虚拟物品';
        } else if (!empty($order['virtual'])) {
            $order['dispatchname'] = '虚拟物品(卡密)<br/>自动发货';
        }

        if (($order['dispatchtype'] == 1) || !empty($order['isverify']) || !empty($order['virtual']) || !empty($order['isvirtual'])) {
            $order['address_address'] = '';
            $carrier                  = iunserializer($order['carrier']);
            if (is_array($carrier)) {
                $order['address_realname'] = $carrier['carrier_realname'];
                $order['address_mobile']   = $carrier['carrier_mobile'];
            }
        } else {
            $address = iunserializer($order['address']);
            $isarray = is_array($address);

            $order['address_realname'] = (($isarray ? $address['realname'] : $order['arealname']));
            $order['address_mobile']   = (($isarray ? $address['mobile'] : $order['amobile']));

            $order['address_province'] = (($isarray ? $address['province'] : $order['aprovince']));
            $order['address_city']     = (($isarray ? $address['city'] : $order['acity']));
            $order['address_area']     = (($isarray ? $address['area'] : $order['aarea']));
            $order['address_town']     = (($isarray ? $address['town'] : $order['atown']));

            $order['address_address'] = (($isarray ? $address['address'] : $order['aaddress']));

            $order['address_address'] = $order['address_province'] . ' ' . $order['address_city'] . ' ' . $order['address_area'] . ' ' . $order['address_town'] . ' ' . $order['address_address'];
        }

        if (empty($order['invoiceid'])) {
            $user_invoice = array(
                'companyName'     => '不开发票',
                'taxpayersIDcode' => '',
                'invoiceType'     => '0',
            );

        } else {

            $user_invoice = iunserializer($order['invoice']);

            if (!is_array($user_invoice)) {

                $user_invoice = pdo_fetch(
                    ' SELECT * ' .
                    ' FROM ' . tablename('superdesk_shop_member_invoice') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 不处理
                    ' WHERE id = :id ',
                    array(
                        ':id' => $order['invoiceid'],
                    )
                );
            }

        }

        $order['companyName']     = $user_invoice['companyName'];
        $order['taxpayersIDcode'] = $user_invoice['taxpayersIDcode'];
        $order['invoiceType']     = $user_invoice['invoiceType'] . '';
        $order['invoiceBank']     = $user_invoice['invoiceBank'];
        $order['invoiceAccount']  = $user_invoice['invoiceAccount'];

        $goods = pdo_fetchall(
            ' select g.title,g.unit,og.total,og.realprice as price,og.realprice/og.total as unit_price,og.optionname, ' .
            '        c.fiscal_code, ' .
            '        IFNULL(jd_exts.taxCode,"") as taxCode ' .
            ' from ' . tablename('superdesk_shop_order_goods') . ' as og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
            ' left join ' . tablename('superdesk_shop_goods') . ' as g on g.id = og.goodsid ' .
            ' left join ' . tablename('superdesk_shop_category') . ' c on g.tcate=c.id ' .
            ' left join ' . tablename('superdesk_jd_vop_product_exts') . ' jd_exts on g.jd_vop_sku = jd_exts.sku ' .
            ' where orderid=:orderid ',
            array(
                ':orderid' => $order['orderid'],
            )
        );

        $order['goods'] = $goods;

        $order['order_type'] = '1';//
        $order['createtime'] = date('Y-m-d H:i:s', $order['createtime']);
        $order['finishtime'] = date('Y-m-d H:i:s', $order['finishtime']);
        $order['sendtime']   = date('Y-m-d H:i:s', $order['sendtime']);
        $order['paytime']    = date('Y-m-d H:i:s', $order['paytime']);

        $order['detail_url'] = webUrl('order.detail', array('id' => $order['orderid']));

        $order['orderid']         = intval($order['orderid']);
        $order['parent_order_id'] = intval($order['parent_order_id']);

        return $order;

    }

    /**
     * 提供java统一订单中心所需退款数据
     */
    public function unifiedRefundOrderData($refundid)
    {
        $data = pdo_fetch(
            ' select orderid,refundno,price,reason,content,createtime,refundtime,applyprice ' .
            ' from ' . tablename('superdesk_shop_order_refund') .
            ' where ' .
            '		id=:id ',
            array(
                ':id' => $refundid,
            )
        );

        $data['createtime'] = date('Y-m-d H:i:s', $data['createtime']);
        $data['refundtime'] = date('Y-m-d H:i:s', $data['refundtime']);

        return $data;
    }

    /**
     * 获取子订单数量
     */
    public function getChildOrderCount($orderid){
        return pdo_fetchcolumn(
            ' SELECT COUNT(*) FROM ' . tablename($this->table_name) .
            ' WHERE parentid = :parentid ',
            array(
                ':parentid' => $orderid
            )
        );
    }
}