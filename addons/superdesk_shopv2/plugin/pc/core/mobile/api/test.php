<?php

//error_reporting(0);

if (!(defined("IN_IA"))) {
    exit("Access Denied");
}
include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/accessControl.php";
require SUPERDESK_SHOPV2_PLUGIN . "pc/core/page_mobile.php";

include_once(IA_ROOT . '/addons/superdesk_shopv2/service/transmit/TransmitUnifiedOrderService.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order.class.php');



class Test_SuperdeskShopV2Page extends PcMobilePage
{
    private $_orderModel;

    private $_redis;

    public function __construct()
    {
        parent::__construct();

        $this->_orderModel = new orderModel();

        $this->_redis = new RedisUtil();
    }

    /**
     * 楼宇之窗借权测试
     * http://localhost/superdesk/app/index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=pc.api.test
     */
    public function main()
    {
        global $_W;
        global $_GPC;

        try {
            pdo_update('superdesk_shop_order',
                array('status' => 2),
                array('id' => 32217)
            );

            m('order')->setStocksAndCredits(32217, 1);

            m('notice')->sendOrderMessage(32217);
        } catch(\Exception $e){
            show_json(0, 'c');
        }



        show_json(1);

        $data = $this->_orderModel->unifiedOrderData(31237);

        show_json(1,$data);

//        $express   = trim($_GPC['express']);
//        $yxPackageId = trim($_GPC['yxPackageId']);
//
//        $expresssn = trim($_GPC['expresssn']);
//        $list      = m('util')->getExpressList($express, $expresssn, $yxPackageId);
//
//        $this->_redis->set('test','test',60);


        $key = 'superdesk_shopv2_' . 'express_kdniao_' . $_W['uniacid'] . ':' . 300289784631;
        $result = $this->_redis->get($key);
        $result = json_decode($result, true);

        var_dump($result);die;

//        $tt = '超宝(CHAOBAO) 14&quot; 涂水器毛头 加厚毛套玻璃清洁工具t架黄条上水器毛头罩 （单位：个）';

//        var_dump(htmlspecialchars_decode($tt));die;

//        $ip = $this->GetIpLookup();

//        var_dump($ip);die;

//        $tt = m('notice')->test(26009);
//        var_dump($tt);die;

//        m('notice')->sendExamineCreateNotice('oX8KYwiEaMSg0C7jR0VSLWCc1B7s',            /* 采购经理_审核人 openid */
//            10380,         /* 采购经理_审核人 core_user */
//            2500,   /* 采购经理_审核人 core_enterprise */
//            615, /* 采购经理_审核人 core_organization */
//            '13510109273',            /* 采购经理_审核人 mobile */
//            '冯鑫', 'ME20190109171628820373', 89.9, 20287);

        // 审核通过,推送给采购员?以及其他采购经理?
//        m('notice')->sendExamineResultNotice(
//            123,
//            10380,
//            '13510109273',
//            11,
//            22,
//            1504871018,
//            'zz',
//            1,/* approve*/
//            'qweqw'
//        );


        m('notice')->sendOrderMessage(29354);

        show_json(1);


        include_once(IA_ROOT . '/addons/superdesk_shopv2/model/goods/goods.class.php');
        $_goodsModel    = new goodsModel();

        $_goodsModel->test();

        include_once(IA_ROOT . '/addons/superdesk_core/service/UserInfoService.class.php');
        $_uservice           = new UserInfoService();
        $superdesk_core_data = $_uservice->getOneByUserMobile($_GPC['core_user']);

        m('notice')->sendErrorMessage('cs',$_GPC['core_user']);

        var_dump($superdesk_core_data);
        die;

        m('notice')->sendExamineCreateNotice(5168,            /* 采购经理_审核人 openid */
            10357,         /* 采购经理_审核人 core_user */
            2500,   /* 采购经理_审核人 core_enterprise */
            615, /* 采购经理_审核人 core_organization */
            '13510986797',            /* 采购经理_审核人 mobile */
            '冯鑫', 'ME20190109171628820373', 89.9, 20287);

        var_dump($superdesk_core_data);
        die;

        $data = array(
//            'orgId' => 1,
//            'source' => 1,
            'moduleCode' => 'QYNG',
            'redirect_uri' => 'http://wx.palmnest.com/super_service/wechat/pay/testUnifiedOrder'
//            'redirect_uri' => 'https://wxn.avic-s.com/app/index.php?i=17&c=entry&m=superdesk_shopv2&do=mobile'
        );

        //        $url = 'https://www.baidu.com';
        $url = 'http://wx.palmnest.com/super_service/wechat/pay/authUrl';
        // 1. 初始化
        $ch = curl_init();
        // 2. 设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
//        if($output === FALSE ){
//            echo "CURL Error:".curl_error($ch);
//        }
        // 4. 释放curl句柄
        curl_close($ch);

        print_r(json_decode($output, true));
        print_r(123);


        die;

        show_json(1, compact('slides', 'recommends', 'friend_link'));
    }

    public function testApi()
    {
        global $_GPC;
//        print_r(webUrl('order.detail',array('id'=>1)));die;

        $id = $_GPC['id'];
        if(empty($id)){
            //2019年3月14日 10:54:39 zjh 文礼要求推所有.故而把status=3改成status!=-1
            //为了测试,随机拿一条数据
            $id = pdo_fetchcolumn(
                ' SELECT id ' .
                ' FROM ' . tablename('superdesk_shop_order') .
                ' where ' .
                '       status != -1 ' .
                '       and invoiceid>0 ' .
                '       and member_organization_id>0 ' .
                '       and deleted=0 ' .
                ' order by rand() ' .
                ' limit 1'
            );
        }


        $_orderModel = new orderModel();
        $__transmitUnifiedOrderService = new TransmitUnifiedOrderService();
        $__transmitUnifiedOrderService->transmitOrderToUnified($_orderModel,['id' => $id]);
        show_json(1, $id);

//        $url = 'https://www.baidu.com';
        $url = 'https://fmt.superdesk.cn/app/index.php?i=17&c=entry&m=superdesk_shopv2&do=mobile&r=pc.api.goods';
        // 1. 初始化
        $ch = curl_init();
        // 2. 设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
//        if($output === FALSE ){
//            echo "CURL Error:".curl_error($ch);
//        }
        // 4. 释放curl句柄
        curl_close($ch);

        print_r($output);
        print_r(123);


        die;


//        $__produce__ = new ProduceService();
//        $__produce__->superdeskShopOrdersHandler([
//            'id' => '1'
//        ]);

//        $data = pdo_fetch(
//            ' select goods_static from ' . tablename('superdesk_shop_order_goods') .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
//            ' order by createtime desc limit 1'
//        );

//        print_r(iunserializer($data['goods_static']));die;

//        $sql =
//            ' SELECT '.
//            ' id as goodsid,type,title,weight,issendfree,isnodiscount, ' .
//            ' thumb,marketprice,storeids,isverify,deduct,' .
//            ' manydeduct,`virtual`,maxbuy,usermaxbuy,discounts,total as stock,deduct2,showlevels,' .
//            ' ednum,edmoney,edareas,' .
//            ' diyformtype,diyformid,diymode,dispatchtype,dispatchid,dispatchprice,cates,minbuy, ' .
//            ' isdiscount,isdiscount_time,isdiscount_discounts, ' .
//            ' virtualsend,invoice,needfollow,followtip,followurl,merchid,checked,merchsale, ' .
//            ' buyagain,buyagain_islong,buyagain_condition, buyagain_sale' .
//            ' jd_vop_sku,jd_vop_page_num ' .
//            ' FROM ' . tablename('superdesk_shop_goods') .
//            ' where jd_vop_sku=:jd_vop_sku ' .
//            '       and uniacid=:uniacid ' .
//            ' limit 1';
//
//
//        $data = pdo_fetchall($sql, array(
//            ':uniacid'    => 17,
//            ':jd_vop_sku' => 0
//        ));
//
//        print_r($data);die;

        //编号为7965591的附件无货，主商品为:7792550
        //resultMessage 编号为2586166的赠品无货，主商品为:929693;
        //resultMessage 编号为7788763的商品无货;
        //

//        $result = array();
//        $str = '编号为7788763的商品无货';
//        if(!empty(strstr($str,'，'))){
//            $str = strstr($str,'，');
//        }
//        preg_match('/\d+/',$str,$result);
//        print_r($result[0]);die;

//        print_r($result);die;
    }

    /**
     * http://localhost/superdesk/app/index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=pc.api.test.unifiedOrderJanuaryAfterFor
     */
    public function unifiedOrderJanuaryAfterFor()
    {
        global $_GPC;
        global $_W;

        $psize = $_GPC['psize'] ? $_GPC['psize'] : 1100;
        $page = $_GPC['page'] ? $_GPC['page'] : 1;

        //2019年3月14日 10:54:39 zjh 文礼要求推所有.故而把status=3改成status!=-1
        $order_ids = pdo_fetchall(
            ' select id ' .
            ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where uniacid=:uniacid ' .
            '   and status!=-1 ' .
            '   and deleted=0 ' .
            '   and isparent=0 ' .
            '   and createtime > 1546272000 ' .
            ' ORDER BY id ASC LIMIT ' . ($page - 1) * $psize . ',' . $psize,
            array(
                ':uniacid' => $_W['uniacid']
            )
        );

        $sql = '';
        $allTotal = 0;
        if(!empty($_GPC['showSql'])){
            $sql = ' select id ' .
                ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
                ' where uniacid=:uniacid ' .
                '   and status!=-1 ' .
                '   and deleted=0 ' .
                '   and isparent=0 ' .
                '   and createtime > 1546272000 ' .
                ' ORDER BY id ASC LIMIT ' . ($page - 1) * $psize . ',' . $psize;

            $allTotal = pdo_fetchcolumn(' select count(*) as tt ' .
                ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
                ' where uniacid=:uniacid ' .
                '   and status!=-1 ' .
                '   and deleted=0 ' .
                '   and isparent=0 ' .
                '   and createtime > 1546272000 ',
                array(
                    ':uniacid' => $_W['uniacid']
                ));
        }

        $_orderModel = new orderModel();
        $__transmitUnifiedOrderService = new TransmitUnifiedOrderService();
        foreach ($order_ids as $index => $order_id) {
            $__transmitUnifiedOrderService->transmitOrderToUnified($_orderModel,$order_id);
        }

        show_json(1, ['data' => $order_ids, 'total' => count($order_ids), 'sql' => $sql, 'allTotal' => $allTotal]);
    }

    /**
     * http://localhost/superdesk/app/index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=pc.api.test.unifiedOrderJiaTao
     */
    public function unifiedOrderJiaTao()
    {
        global $_GPC;
        global $_W;

        $psize = $_GPC['psize'] ? $_GPC['psize'] : 1100;
        $page = $_GPC['page'] ? $_GPC['page'] : 1;

        //2019年3月14日 10:54:39 zjh 文礼要求推所有.故而把status=3改成status!=-1
        $order_ids = pdo_fetchall(
            ' select id ' .
            ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where uniacid=:uniacid ' .
            '   and category_enterprise_discount > 0 ' .
            ' ORDER BY id ASC LIMIT ' . ($page - 1) * $psize . ',' . $psize,
            array(
                ':uniacid' => $_W['uniacid']
            )
        );

        $sql = '';
        $allTotal = 0;
        if(!empty($_GPC['showSql'])){
            $sql = ' select id ' .
                ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
                ' where uniacid=:uniacid ' .
                '   and status!=-1 ' .
                '   and deleted=0 ' .
                '   and isparent=0 ' .
                '   and createtime > 1546272000 ' .
                ' ORDER BY id ASC LIMIT ' . ($page - 1) * $psize . ',' . $psize;

            $allTotal = pdo_fetchcolumn(' select count(*) as tt ' .
                ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
                ' where uniacid=:uniacid ' .
                '   and status!=-1 ' .
                '   and deleted=0 ' .
                '   and isparent=0 ' .
                '   and createtime > 1546272000 ',
                array(
                    ':uniacid' => $_W['uniacid']
                ));
        }

        $_orderModel = new orderModel();
        $__transmitUnifiedOrderService = new TransmitUnifiedOrderService();
        foreach ($order_ids as $index => $order_id) {
            $__transmitUnifiedOrderService->transmitOrderToUnified($_orderModel,$order_id);
        }

        show_json(1, ['data' => $order_ids, 'total' => count($order_ids), 'sql' => $sql, 'allTotal' => $allTotal]);
    }

    /**
     * 弃用
     */
    public function unifiedOrderJanuaryAfter()
    {
        global $_W;

        $order = pdo_fetchall(
            ' select o.id as orderid, o.parentid as parent_order_id, o.ordersn, o.expresssn, o.dispatchprice, o.price, o.paytype, o.remark, ' .
            '        o.createtime, o.sendtime, o.finishtime, o.paytime, o.status, o.invoiceid, o.invoice, ' .
            '        o.isverify, o.isvirtual, o.virtual, o.dispatchtype, o.carrier, o.address, o.addressid, ' .
            '        o.member_enterprise_id as enterpriseId, o.member_organization_id as organizationId,org.name as organization_name, o.member_enterprise_name as enterprise_name, ' .
            '        d.dispatchname,' .
            '        po.ordersn as parent_ordersn,' .
            '        m.id as realnameId,m.realname,m.mobile,' .
            '        a.realname as arealname,a.mobile as amobile,a.province as aprovince ,a.city as acity , a.area as aarea,a.town as atown,a.address as aaddress, ' .
            '        oe.manager_openid as examineId,oe.manager_realname as examine_name, ' .
            '        merch.merchname as merch_name ' .
            ' from ' . tablename('superdesk_shop_order') . ' o' .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' left join ' . tablename('superdesk_shop_order') . ' po on po.id=o.parentid and o.parentid > 0 ' .// and m.core_user=o.core_user
            ' left join ' . tablename('superdesk_shop_member') . ' m on m.openid=o.openid and m.uniacid =  o.uniacid ' .
            ' left join ' . tablename('superdesk_shop_member_address') . ' a on a.id=o.addressid ' . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 不处理
            ' left join ' . tablename('superdesk_shop_dispatch') . ' d on d.id = o.dispatchid ' .
            ' left join ' . tablename('superdesk_shop_order_examine') . ' oe on oe.orderid = o.id and o.paytype=3' .// TODO 标志 楼宇之窗 openid superdesk_shop_order_examine 不处理
            ' left join ' . tablename('superdesk_shop_merch_user') . ' merch on merch.id = o.merchid ' .
            ' left join ' . tablename('superdesk_core_organization') . ' org on org.id = o.member_organization_id ' .
            ' where o.uniacid=:uniacid ' .
            '   and o.status = 3 ' .
            '   and o.deleted=0 ' .
            '   and o.createtime > 1546272000 ',
            array(
                ':uniacid' => $_W['uniacid']
            )
        );

        foreach ($order as $k => &$v) {
            $v['dispatchname'] = ((empty($v['addressid']) ? '自提' : $v['dispatchname']));

            if (empty($v['dispatchname'])) {
                $v['dispatchname'] = '快递';
            }

            if ($v['paytype'] == 3) {
                $v['dispatchname'] = '企业月结';
            } else if ($v['isverify'] == 1) {
                $v['dispatchname'] = '线下核销';
            } else if ($v['isvirtual'] == 1) {
                $v['dispatchname'] = '虚拟物品';
            } else if (!empty($v['virtual'])) {
                $v['dispatchname'] = '虚拟物品(卡密)<br/>自动发货';
            }

            if (($v['dispatchtype'] == 1) || !empty($v['isverify']) || !empty($v['virtual']) || !empty($v['isvirtual'])) {
                $v['address_address'] = '';
                $carrier = iunserializer($v['carrier']);
                if (is_array($carrier)) {
                    $v['address_realname'] = $carrier['carrier_realname'];
                    $v['address_mobile'] = $carrier['carrier_mobile'];
                }
            } else {
                $address = iunserializer($v['address']);
                $isarray = is_array($address);

                $v['address_realname'] = (($isarray ? $address['realname'] : $v['arealname']));
                $v['address_mobile'] = (($isarray ? $address['mobile'] : $v['amobile']));

                $v['address_province'] = (($isarray ? $address['province'] : $v['aprovince']));
                $v['address_city'] = (($isarray ? $address['city'] : $v['acity']));
                $v['address_area'] = (($isarray ? $address['area'] : $v['aarea']));
                $v['address_town'] = (($isarray ? $address['town'] : $v['atown']));

                $v['address_address'] = (($isarray ? $address['address'] : $v['aaddress']));

                $v['address_address'] = $v['address_province'] . ' ' . $v['address_city'] . ' ' . $v['address_area'] . ' ' . $v['address_town'] . ' ' . $v['address_address'];
            }

            if (empty($v['invoiceid'])) {
                $user_invoice = array(
                    'companyName' => '不开发票',
                    'taxpayersIDcode' => '',
                    'invoiceType' => '0',
                );

            } else {

                $user_invoice = iunserializer($v['invoice']);

                if (!is_array($user_invoice)) {

                    $user_invoice = pdo_fetch(
                        ' SELECT * ' .
                        ' FROM ' . tablename('superdesk_shop_member_invoice') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 不处理
                        ' WHERE id = :id ',
                        array(
                            ':id' => $v['invoiceid']
                        )
                    );
                }

            }

            $v['companyName'] = $user_invoice['companyName'];
            $v['taxpayersIDcode'] = $user_invoice['taxpayersIDcode'];
            $v['invoiceType'] = $user_invoice['invoiceType'];
            $v['invoiceBank'] = $user_invoice['invoiceBank'];
            $v['invoiceAccount'] = $user_invoice['invoiceAccount'];

            $goods = pdo_fetchall(
                ' select g.title,g.unit,og.total,og.price,og.price/og.total as unit_price,og.optionname, ' .
                '        c.fiscal_code, ' .
                '        IFNULL(jd_exts.taxCode,"") as taxCode ' .
                ' from ' . tablename('superdesk_shop_order_goods') . ' as og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
                ' left join ' . tablename('superdesk_shop_goods') . ' as g on g.id = og.goodsid ' .
                ' left join ' . tablename('superdesk_shop_category') . ' c on g.tcate=c.id ' .
                ' left join ' . tablename('superdesk_jd_vop_product_exts') . ' jd_exts on g.jd_vop_sku = jd_exts.sku ' .
                ' where orderid=:orderid ',
                array(
                    ':orderid' => $v['orderid']
                )
            );

            $v['goods'] = $goods;

            $v['order_type'] = '1';
            $v['createtime'] = date('Y-m-d H:i:s', $v['createtime']);
            $v['finishtime'] = date('Y-m-d H:i:s', $v['finishtime']);
            $v['sendtime'] = date('Y-m-d H:i:s', $v['sendtime']);
            $v['paytime'] = date('Y-m-d H:i:s', $v['paytime']);

            $v['detail_url'] = webUrl('order.detail', array('id' => $v['orderid']));

            $v['orderid'] = intval($v['orderid']);
            $v['parent_order_id'] = intval($v['parent_order_id']);
        }
        unset($v);

        show_json(1, $order);

        $__produce__ = new ProduceService();
        $__produce__->unifiedOrderHandler($order);

    }
}
