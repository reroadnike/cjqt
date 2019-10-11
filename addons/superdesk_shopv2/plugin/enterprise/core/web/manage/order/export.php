<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

include_once(IA_ROOT . '/addons/superdesk_shopv2/service/CategoryService.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/service/plugin/EnterpriseService.class.php');
include_once(IA_ROOT . '/addons/superdesk_core/service/TbuserService.class.php');
include_once(IA_ROOT . '/addons/superdesk_core/service/VirtualarchitectureService.class.php');
require SUPERDESK_SHOPV2_PLUGIN . 'enterprise/core/inc/page_enterprise.php';

class Export_SuperdeskShopV2Page extends EnterpriseWebPage
{

    private $_categoryService;
    private $_plugin_enterprise_Service;
    private $_tbuserService;//
    private $_virtualarchitectureService;

    public function __construct($_init = true)
    {
        parent::__construct($_init);

        $this->_categoryService     = new CategoryService();
        $this->_plugin_enterprise_Service = new EnterpriseService();


        $this->_tbuserService              = new TbuserService();
        $this->_virtualarchitectureService = new VirtualarchitectureService();

    }

    protected function field_index($columns, $field)
    {
        $index = -1;

        foreach ($columns as $i => $v) {
            if (!($v['field'] == $field)) {
                continue;
            }
            $index = $i;
            break;
        }

        return $index;
    }

    protected function defaultColumns()
    {
        return array(
            array('title' => '父单编号', 'field' => 'parent_ordersn', 'width' => 24),// A 父单编号
            array('title' => '父单金额', 'field' => 'parent_price', 'width' => 12),// A 父单金额
            array('title' => '订单编号', 'field' => 'ordersn', 'width' => 24),// A 订单号


            array('title' => '订单金额', 'field' => 'price', 'width' => 12),// 应收款 B 订单金额
            array('title' => '订单改价', 'field' => 'changeprice', 'width' => 12),

            array('title' => '付款时间', 'field' => 'paytime', 'width' => 24),// C 支付状态　未支付 已支付

            array('title' => '支付方式', 'field' => 'paytype', 'width' => 12),// D 支付方式

            array('title' => '订单状态', 'field' => 'status', 'width' => 12),// E 订单状态 TODO 有歧异
            array('title' => '订单自定义信息', 'field' => 'order_diyformdata', 'width' => 36),


            array('title' => '客户姓名', 'field' => 'realname', 'width' => 12),// 收货姓名(或自提人) F 客户姓名
            array('title' => '粉丝昵称', 'field' => 'nickname', 'width' => 12),


            array('title' => '联系电话', 'field' => 'mobile', 'width' => 12),// G 联系电话


            array('title' => '收货地址', 'subtitle' => '收货地址(省市区合并)', 'field' => 'address', 'width' => 24),// H 收货地址
//            array('title' => '收货地址', 'subtitle' => '收货地址(省市区分离)', 'field' => 'address_province', 'width' => 12),


            array('title' => '下单时间', 'field' => 'createtime', 'width' => 24),// I 下单时间


            array('title' => '供应商名称', 'field' => 'enterprise_name', 'width' => 12),// J 供应商名称
//            array('title' => '所属项目', 'field' => 'core_enterprise_1', 'width' => 12),// K 所属项目


            array('title' => '买家备注', 'field' => 'remark', 'width' => 36),
            array('title' => '卖家备注', 'field' => 'remarksaler', 'width' => 36),
            array('title' => '商品信息', 'subtitle' => '商品信息(信息分离)', 'field' => 'goods_title', 'width' => 24),// L 商品名称
//            array('title' => '商品信息', 'subtitle' => '商品信息(信息合并)', 'field' => 'goods_str', 'width' => 36),
            // M 商品价格 单价 下边有
            // N 商品数量 下边有


            array('title' => '商品自定义信息', 'field' => 'goods_diyformdata', 'width' => 36),
            array('title' => '商品小计', 'field' => 'goodsprice', 'width' => 12),

            array('title' => '积分抵扣', 'field' => 'deductprice', 'width' => 12),
            array('title' => '余额抵扣', 'field' => 'deductcredit2', 'width' => 12),

            array('title' => '满额立减', 'field' => 'deductenough', 'width' => 12),
            array('title' => '优惠券优惠', 'field' => 'couponprice', 'width' => 12),

            array('title' => '扣除佣金后利润', 'field' => 'commission4', 'width' => 12),
            array('title' => '扣除佣金及运费后利润', 'field' => 'profit', 'width' => 12),

            array('title' => '京东订单号', 'field' => 'jd_order_id', 'width' => 12),// O 京东订单号


            array('title' => '会员名称', 'field' => 'mrealname', 'width' => 12),// 会员姓名 Q 会员名称
            array('title' => '会员手机号', 'field' => 'mmobile', 'width' => 18),// R 会员手机号
            array('title' => '会员所属项目', 'field' => 'core_enterprise_2', 'width' => 18),// S 会员所属项目


            array('title' => '发票', 'field' => 'invoice_invoiceType', 'width' => 12),// T 发票 // 发票类型 增值税普票 1 增值税专票 2
            array('title' => '类型', 'field' => 'invoice_selectedInvoiceTitle', 'width' => 12),// U 类型 //发票抬头 个人 4 单位 5
            array('title' => '抬头', 'field' => 'invoice_companyName', 'width' => 24),// V 抬头 //请在此填写发票抬头
            array('title' => '内容', 'field' => 'invoice_invoiceContent', 'width' => 12),// W 内容 // 发票内容 明细 1 电脑配件 3 耗材 19 办公用品 22
            array('title' => '纳税人识别号', 'field' => 'invoice_taxpayersIDcode', 'width' => 24),// X 纳税人识别号 //请在此填写纳税人识别号
            array('title' => '注册地址', 'field' => 'invoice_invoiceAddress', 'width' => 24),// Y 注册地址 //请在此填写注册地址
            array('title' => '注册号码', 'field' => 'invoice_invoicePhone', 'width' => 24),// Z 注册号码 //请在此填写注册电话
            array('title' => '开户银行', 'field' => 'invoice_invoiceBank', 'width' => 24),// AA 开户银行 //请在此填写开户银行
            array('title' => '开户帐号', 'field' => 'invoice_invoiceAccount', 'width' => 24),// AB 开户帐号 //请在此填写银行账号


            array('title' => '发票状态', 'field' => 'invoiceid', 'width' => 12),// AC 发票状态 default 未开票 不开票


            array('title' => '发货时间', 'field' => 'sendtime', 'width' => 24),
            array('title' => '配送方式', 'field' => 'dispatchname', 'width' => 12),
            array('title' => '运费', 'field' => 'dispatchprice', 'width' => 12),
            array('title' => '运费改价', 'field' => 'changedispatchprice', 'width' => 12),
            array('title' => '快递公司', 'field' => 'expresscom', 'width' => 24),
            array('title' => '快递单号', 'field' => 'expresssn', 'width' => 24),

            array('title' => '完成时间', 'field' => 'finishtime', 'width' => 24),

//            array('title' => '核销员', 'field' => 'salerinfo', 'width' => 24),
//            array('title' => '核销门店', 'field' => 'storeinfo', 'width' => 36),



            array('title' => '一级佣金', 'field' => 'commission1', 'width' => 12),
            array('title' => '二级佣金', 'field' => 'commission2', 'width' => 12),
            array('title' => '三级佣金', 'field' => 'commission3', 'width' => 12),
            array('title' => '佣金总额', 'field' => 'commission', 'width' => 12)


//            array('title' => 'openid', 'field' => 'openid', 'width' => 24),
        );
    }

    public function main()
    {
        global $_W;
        global $_GPC;

        message('系统维护');
        exit();

        $plugin_diyform = p('diyform');
        $sets           = $this->model->getSet();
        $shop_set       = $sets['shop'];
        $dflag          = intval($_GPC['dflag']);
        $level          = 0;
        $pc             = p('commission');

        if ($pc) {
            $pset  = $pc->getSet();
            $level = intval($pset['level']);
        }

        $default_columns = $this->defaultColumns();
        $templates       = ((isset($shop_set['ordertemplates']) ? $shop_set['ordertemplates'] : array()));
        $columns         = ((isset($shop_set['ordercolumns']) ? $shop_set['ordercolumns'] : array()));

        if (empty($columns)) {

            if ($dflag == 0) {
                $columns = $default_columns;
            }
        }

        foreach ($default_columns as &$dc) {
            $dc['select'] = false;
            foreach ($columns as $c) {
                if (!($dc['field'] == $c['field'])) {
                    continue;
                }
                $dc['select'] = true;
                break;
            }
        }
        unset($dc);

        $view_invoiceType = array(
            1 => '增值税普票',
            2 => '增值税专票'
        );

        $view_selectedInvoiceTitle = array(
            4 => '个人',
            5 => '单位'
        );

        $view_invoiceContent = array(
            1  => '明细',
            3  => '电脑配件',
            19 => '耗材',
            22 => '办公用品'
        );

        $paytype = array(
            0  => array('css' => 'default', 'name' => '未支付'),
            1  => array('css' => 'danger', 'name' => '余额支付'),
            11 => array('css' => 'default', 'name' => '后台付款'),
            2  => array('css' => 'danger', 'name' => '在线支付'),
            21 => array('css' => 'success', 'name' => '微信支付'),
            22 => array('css' => 'warning', 'name' => '支付宝支付'),
            23 => array('css' => 'warning', 'name' => '银联支付'),
            3  => array('css' => 'primary', 'name' => '企业月结')
        );


        $orderstatus = array(
            -1 => array('css' => 'default', 'name' => '已关闭'),
            0  => array('css' => 'danger', 'name' => '待付款'),
            1  => array('css' => 'info', 'name' => '待发货'),
            2  => array('css' => 'warning', 'name' => '待收货'),
            3  => array('css' => 'success', 'name' => '已完成')
        );


        if ($_GPC['export'] == 1) {

            $address2index = $this->field_index($columns, 'address_province');

            if ($address2index != -1) {

                array_splice($columns, $address2index + 1, 0, array(
                        array('title' => '市', 'field' => 'address_city', 'width' => 12),
                        array('title' => '区', 'field' => 'address_area', 'width' => 12),
                        array('title' => '地址', 'field' => 'address_address', 'width' => 24)
                    )
                );
            }

            $goodsindex = $this->field_index($columns, 'goods_title');

            if ($goodsindex != -1) {

                array_splice($columns, $goodsindex + 1, 0, array(
                        array('title' => '商品编码', 'field' => 'goods_goodssn', 'width' => 12),
                        array('title' => '商品条码', 'field' => 'goods_productsn', 'width' => 12),
                        array('title' => '商品规格', 'field' => 'goods_optiontitle', 'width' => 12),
                        array('title' => '商品数量', 'field' => 'goods_total', 'width' => 12),
                        array('title' => '退货数量', 'field' => 'goods_return_goods_nun', 'width' => 12),
                        array('title' => '商品单价(折扣前)', 'field' => 'goods_price1', 'width' => 12),
                        array('title' => '商品单价(折扣后)', 'field' => 'goods_price2', 'width' => 12),
                        array('title' => '京东协议价', 'field' => 'goods_jd_vop_costprice', 'width' => 12),// P 京东协议价
                        array('title' => '商品价格(折扣前)', 'field' => 'goods_rprice1', 'width' => 12),
                        array('title' => '商品价格(折扣后)', 'field' => 'goods_rprice2', 'width' => 12),

                        array('title' => '京东SKU', 'field' => 'goods_jd_vop_sku', 'width' => 12),// 商品编码(京东SKU) jd_vop_sku
                        array('title' => '商品单位', 'field' => 'goods_unit', 'width' => 12),// 商品单位 unit
                        array('title' => '商品一级分类', 'field' => 'goods_pcate', 'width' => 12),// 一级分类 pcate
                        array('title' => '商品二级分类', 'field' => 'goods_ccate', 'width' => 12),// 二级分类 ccate
                        array('title' => '商品三级分类', 'field' => 'goods_tcate', 'width' => 12),// 三级分类 tcate
                        array('title' => '财务代码', 'field' => 'goods_fiscal_code', 'width' => 24) // 财务代码 fiscal_code
                    )
                );
            }


            mplog('order.export', '导出订单');

            $status = $_GPC['status'];

            $condition =
                ' o.uniacid = :uniacid ' .
                ' and o.enterprise_id = :enterprise_id ' .
                ' and o.isparent=0 ' .
                ' and o.deleted=0 ';

            $paras = array(
                ':uniacid' => $_W['uniacid'],
                ':enterprise_id' => $_W['enterprise_id']
            );

            if (!empty($_GPC['time']['start'])
                && !empty($_GPC['time']['end'])
            ) {
                $starttime = strtotime($_GPC['time']['start']);
                $endtime   = strtotime($_GPC['time']['end']);
                $condition .=
                    ' AND o.createtime >= :starttime ' .
                    ' AND o.createtime <= :endtime ';
                $paras[':starttime'] = $starttime;
                $paras[':endtime']   = $endtime;
            }

            if ($_GPC['paytype'] != '') {
                if ($_GPC['paytype'] == '2') {
                    $condition .= ' AND ( o.paytype =21 or o.paytype=22 or o.paytype=23 )';
                } else {
                    $condition .= ' AND o.paytype =' . intval($_GPC['paytype']);
                }
            }

            if (!empty($_GPC['keyword'])) {
                $_GPC['keyword'] = trim($_GPC['keyword']);
                $condition .= ' AND o.ordersn LIKE \'%' . $_GPC['keyword'] . '%\'';
            }

            if (!empty($_GPC['expresssn'])) {
                $_GPC['expresssn'] = trim($_GPC['expresssn']);
                $condition .= ' AND o.expresssn LIKE \'%' . $_GPC['expresssn'] . '%\'';
            }

            if (!empty($_GPC['member'])) {
                $_GPC['member'] = trim($_GPC['member']);
                $condition .= ' AND (m.realname LIKE \'%' . $_GPC['member'] . '%\' or m.mobile LIKE \'%' . $_GPC['member'] . '%\' or m.nickname LIKE \'%' . $_GPC['member'] . '%\' ' . ' or a.realname LIKE \'%' . $_GPC['member'] . '%\' or a.mobile LIKE \'%' . $_GPC['member'] . '%\' or o.carrier LIKE \'%' . $_GPC['member'] . '%\')';
            }

            if (!empty($_GPC['saler'])) {
                $_GPC['saler'] = trim($_GPC['saler']);
                $condition .= ' AND (sm.realname LIKE \'%' . $_GPC['saler'] . '%\' or sm.mobile LIKE \'%' . $_GPC['saler'] . '%\' or sm.nickname LIKE \'%' . $_GPC['saler'] . '%\' ' . ' or s.salername LIKE \'%' . $_GPC['saler'] . '%\' )';
            }

            if (!empty($_GPC['storeid'])) {
                $_GPC['storeid'] = trim($_GPC['storeid']);
                $condition .= ' AND o.verifystoreid=' . intval($_GPC['storeid']);
            }

            $export_dispatch = $_GPC['export_dispatch'];
            $export_since    = $_GPC['export_since'];
            $export_verify   = $_GPC['export_verify'];
            $export_virtual  = $_GPC['export_virtual'];

            if (($export_dispatch == 1)
                || ($export_since == 1)
                || ($export_verify == 1)
                || ($export_virtual == 1)
            ) {
                $condition .= ' AND ( ';
                if ($export_dispatch == 1) {
                    $condition .= ' o.addressid <> 0 or';
                }
                if ($export_since == 1) {
                    $condition .= ' (o.addressid = 0 and o.isverify = 0 and o.isvirtual = 0) or';
                }
                if ($export_verify == 1) {
                    $condition .= '  o.isverify = 1 or';
                }
                if ($export_virtual == 1) {
                    $condition .= ' o.isvirtual = 1';
                }
                $condition = rtrim($condition, 'or');
                $condition .= ' )';
            }

            $statuscondition = '';

            if ($status != '') {
                if ($status == '-1') {
                    $statuscondition = ' AND o.status=-1 and o.refundtime=0';
                } else if ($status == '4') {
                    $statuscondition = ' AND o.refundstate>0 and o.refundid<>0';
                } else if ($status == '5') {
                    $statuscondition = ' AND o.refundtime<>0';
                } else if ($status == '1') {
                    $statuscondition = ' AND ( o.status = 1 or (o.status=0 and o.paytype=3) )';
                } else if ($status == '0') {
                    $statuscondition = ' AND o.status = 0 and o.paytype<>3';
                } else {
                    $statuscondition = ' AND o.status = ' . intval($status);
                }
            }


            $sql =
                ' select o.* , core_enterprise.name as core_enterprise_2, ' .
                '       po.ordersn as parent_ordersn,po.price as parent_price,'.
                '       a.realname as arealname,a.mobile as amobile,' .
                '       a.province as aprovince ,a.city as acity , a.area as aarea , a.town as atown ,a.address as aaddress, ' .
                '       d.dispatchname,' .
                '       m.nickname,m.id as mid,m.realname as mrealname,m.mobile as mmobile,m.core_enterprise,' .
                '       sm.id as salerid,sm.nickname as salernickname,s.salername ' .
                ' from ' . tablename('superdesk_shop_order') . ' o' .// TODO 标志 楼宇之窗 openid shop_order 已处理
                ' left join ' . tablename('superdesk_shop_order') . ' po on po.id =o.parentid ' .
                ' left join ' . tablename('superdesk_shop_order_refund') . ' r on r.id =o.refundid ' .
                ' left join ' . tablename('superdesk_shop_member') . ' m on m.openid=o.openid and m.core_user=o.core_user and m.uniacid =  o.uniacid ' .
                ' left join ' . tablename('superdesk_shop_member_address') . ' a on a.id=o.addressid ' . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 不处理
                ' left join ' . tablename('superdesk_shop_dispatch') . ' d on d.id = o.dispatchid ' .
                ' left join ' . tablename('superdesk_shop_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' .// TODO 标志 楼宇之窗 openid shop_order 待处理
                ' left join ' . tablename('superdesk_shop_enterprise_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' .// TODO 标志 楼宇之窗 openid shop_order 待处理
                ' left join ' . tablename('superdesk_core_virtualarchitecture') . ' core_enterprise on core_enterprise.id = m.core_enterprise ' .
                ' where ' .
                $condition . ' ' .
                $statuscondition .
                ' ORDER BY o.createtime DESC,o.status DESC  ';

            $list       = pdo_fetchall($sql, $paras);
            $goodscount = 0;

            foreach ($list as &$value) {

                $agentid              = $value['agentid'];
                $s                    = $value['status'];
                $pt                   = $value['paytype'];
                $value['statusvalue'] = $s;
                $value['statuscss']   = $orderstatus[$value['status']]['css'];
                $value['status']      = $orderstatus[$value['status']]['name'];

                if (($pt == 3) && empty($value['statusvalue'])) {
                    $value['statuscss'] = $orderstatus[1]['css'];
                    $value['status']    = $orderstatus[1]['name'];
                }

                if ($s == 1) {
                    if ($value['isverify'] == 1) {
                        $value['status'] = '待使用';
                    } else if (empty($value['addressid'])) {
                        $value['status'] = '待取货';
                    }
                }

                if ($s == -1) {
                    if (!empty($value['refundtime'])) {
                        $value['status'] = '已退款';
                    }
                }

                $value['paytypevalue'] = $pt;
                $value['css']          = $paytype[$pt]['css'];
                $value['paytype']      = $paytype[$pt]['name'];
                $value['dispatchname'] = (empty($value['addressid']) ? '自提' : $value['dispatchname']);

                if (empty($value['dispatchname'])) {
                    $value['dispatchname'] = '快递';
                }

                if ($value['isverify'] == 1) {
                    $value['dispatchname'] = '线下核销';
                } else if ($value['isvirtual'] == 1) {
                    $value['dispatchname'] = '虚拟物品';
                } else if (!empty($value['virtual'])) {
                    $value['dispatchname'] = '虚拟物品(卡密)<br/>自动发货';
                }



                // 收货信息 start
                if (($value['dispatchtype'] == 1)
                    || !empty($value['isverify'])
                    || !empty($value['virtual'])
                    || !empty($value['isvirtual'])
                ) {

                    $value['address'] = '';
                    $carrier          = iunserializer($value['carrier']);

                    if (is_array($carrier)) {
                        $value['addressdata']['realname'] = $value['realname'] = $carrier['carrier_realname'];
                        $value['addressdata']['mobile']   = $value['mobile'] = $carrier['carrier_mobile'];
                    }
                } else {

                    $address = iunserializer($value['address']);
                    $isarray = is_array($address);

                    $value['realname'] = ($isarray ? $address['realname'] : $value['arealname']);
                    $value['mobile']   = ($isarray ? $address['mobile'] : $value['amobile']);
                    $value['province'] = ($isarray ? $address['province'] : $value['aprovince']);
                    $value['city']     = ($isarray ? $address['city'] : $value['acity']);
                    $value['area']     = ($isarray ? $address['area'] : $value['aarea']);
                    $value['town']     = ($isarray ? $address['town'] : $value['atown']);
                    $value['address']  = ($isarray ? $address['address'] : $value['aaddress']);

                    $value['address_province'] = $value['province'];
                    $value['address_city']     = $value['city'];
                    $value['address_area']     = $value['area'];
                    $value['address_town']     = $value['town'];

                    $value['address_address'] = $value['address'];

                    $value['address'] =
                        $value['province'] . ' ' .
                        $value['city'] . ' ' .
                        $value['area'] . ' ' .
                        $value['town'] . ' ' .
                        $value['address'];
                }
                // 收货信息 end



                // 发票信息 start
                $invoice = iunserializer($value['invoice']);// 结构 invoice
                if (!is_array($invoice)) {

                    $invoice = pdo_fetch(
                        ' SELECT * ' .
                        ' FROM ' . tablename('superdesk_shop_member_invoice') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 不处理
                        ' WHERE id = :id and uniacid=:uniacid',
                        array(
                            ':id'      => $item['invoiceid'],
                            ':uniacid' => $_W['uniacid']
                        )
                    );// 结构 invoice

                }
                $value['invoice_invoiceType']          = $view_invoiceType[$invoice['invoiceType']];// T 发票 // 发票类型 增值税普票 1 增值税专票 2
                $value['invoice_selectedInvoiceTitle'] = $view_selectedInvoiceTitle[$invoice['selectedInvoiceTitle']];// U 类型 //发票抬头 个人 4 单位 5
                $value['invoice_companyName']          = $invoice['companyName'];// V 抬头 //请在此填写发票抬头
                $value['invoice_invoiceContent']       = $view_invoiceContent[$invoice['invoiceContent']];// W 内容 // 发票内容 明细 1 电脑配件 3 耗材 19 办公用品 22
                $value['invoice_taxpayersIDcode']      = $invoice['taxpayersIDcode'];// X 纳税人识别号 //请在此填写纳税人识别号
                $value['invoice_invoiceAddress']       = $invoice['invoiceAddress'];// Y 注册地址 //请在此填写注册地址
                $value['invoice_invoicePhone']         = $invoice['invoicePhone'];// Z 注册号码 //请在此填写注册电话
                $value['invoice_invoiceBank']          = $invoice['invoiceBank'];// AA 开户银行 //请在此填写开户银行
                $value['invoice_invoiceAccount']       = $invoice['invoiceAccount'];// AB 开户帐号 //请在此填写银行账号
                // 发票信息 end


                // O 京东订单号 start
                if ($value['isenterprise'] == 1 /*是企业*/
                    && $value['enterprise_id'] == SUPERDESK_SHOPV2_JD_VOP_enterprise_id
                ) {
                    $value['jd_order_id'] = $value['expresssn'];
                }
                // O 京东订单号 end

                $value['enterprise_name'] = $this->_plugin_enterprise_Service->enterprise_UserGetenterprise_nameById($value['enterprise_id']);// J 供应商名称 转换

                $value['core_enterprise_1'] = $this->_virtualarchitectureService->getenterprise_nameById($value['core_enterprise']);// K 所属项目 TODO 转换
                $value['core_enterprise_1'] = $this->_virtualarchitectureService->getenterprise_nameById($value['core_enterprise']);// S 会员所属项目 TODO 转换

                $value['invoiceid'] = ($value['invoiceid'] == 0 ? '不开票' : '未开票');// AC 发票状态 default 未开票 不开票

                // 分销 start
                $commission1 = 0;
                $commission2 = 0;
                $commission3 = 0;

                $m1 = false;
                $m2 = false;
                $m3 = false;

                if (!empty($level)) {

                    if (!empty($value['agentid'])) {

                        $m1 = m('member')->getMember($value['agentid']);

                        if (!empty($m1['agentid'])) {

                            $m2 = m('member')->getMember($m1['agentid']);

                            if (!empty($m2['agentid'])) {

                                $m3 = m('member')->getMember($m2['agentid']);
                            }
                        }
                    }
                }
                // 分销 end


                $order_goods = pdo_fetchall(
                    ' select ' .
                    '       g.id,g.title,g.thumb,g.goodssn,og.goodssn as option_goodssn, g.productsn, g.unit,' .
                    '       g.costprice,' .
                    '       jd_order_sku.price as jd_costprice,' .
                    '       g.pcate,g.ccate,g.tcate,' .
                    '       g.jd_vop_sku,' .
                    '       c.name,c.fiscal_code,' .
                    '       og.productsn as option_productsn, og.total, og.return_goods_nun, og.price,og.optionname as optiontitle, ' .
                    '       og.realprice,og.changeprice,og.oldprice,' .
                    '       og.commission1,og.commission2,og.commission3,og.commissions,' .
                    '       og.diyformdata,og.diyformfields ' .
                    ' from ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
                    ' left join ' . tablename('superdesk_shop_goods') . ' g on g.id=og.goodsid ' .
                    ' left join ' . tablename('superdesk_shop_category') . ' c on g.tcate=c.id ' .
                    ' left join ' . tablename('superdesk_jd_vop_order_submit_order_sku') . ' jd_order_sku on og.orderid = jd_order_sku.shop_order_id and og.goodsid = jd_order_sku.shop_goods_id ' .
                    ' where og.uniacid=:uniacid ' .
                    '       and og.enterprise_id=:enterprise_id ' .
                    '       and og.orderid=:orderid ',
                    array(
                        ':uniacid' => $_W['uniacid'],
                        ':enterprise_id' => $_W['enterprise_id'],
                        ':orderid' => $value['id']
                    )
                );

                $goods = '';
                $goodscount += count($order_goods);

                foreach ($order_goods as &$og) {

                    if (!empty($level) && !empty($agentid)) {

                        $commissions = iunserializer($og['commissions']);

                        if (!empty($m1)) { // 一级分销

                            if (is_array($commissions)) {
                                $commission1 += ((isset($commissions['level1']) ? floatval($commissions['level1']) : 0));
                            } else {
                                $c1 = iunserializer($og['commission1']);

                                // 修正
                                if(empty($c1['default']) || !isset($c1['default'])){
                                    $c1['default'] = 0;
                                }

                                $l1 = $pc->getLevel($m1['openid'],$m1['core_user']);
                                $commission1 += ((isset($c1['level' . $l1['id']]) ? $c1['level' . $l1['id']] : $c1['default']));
                            }
                        }

                        if (!empty($m2)) { // 二级分销
                            if (is_array($commissions)) {
                                $commission2 += ((isset($commissions['level2']) ? floatval($commissions['level2']) : 0));
                            } else {
                                $c2 = iunserializer($og['commission2']);

                                // 修正
                                if(empty($c2['default']) || !isset($c2['default'])){
                                    $c2['default'] = 0;
                                }

                                $l2 = $pc->getLevel($m2['openid'],$m2['core_user']);
                                $commission2 += ((isset($c2['level' . $l2['id']]) ? $c2['level' . $l2['id']] : $c2['default']));
                            }
                        }

                        if (!empty($m3)) { // 三级分销
                            if (is_array($commissions)) {
                                $commission3 += ((isset($commissions['level3']) ? floatval($commissions['level3']) : 0));
                            } else {
                                $c3 = iunserializer($og['commission3']);

                                // 修正
                                if(empty($c3['default']) || !isset($c3['default'])){
                                    $c3['default'] = 0;
                                }

                                $l3 = $pc->getLevel($m3['openid'],$m3['core_user']);
                                $commission3 += ((isset($c3['level' . $l3['id']]) ? $c3['level' . $l3['id']] : $c3['default']));
                            }
                        }
                    }

                    $goods .= '' . $og['title'] . "\r\n";

                    if (!empty($og['optiontitle'])) {
                        $goods .= ' 规格: ' . $og['optiontitle'];
                    }

                    if (!empty($og['option_goodssn'])) {
                        $og['goodssn'] = $og['option_goodssn'];
                    }

                    if (!empty($og['option_productsn'])) {
                        $og['productsn'] = $og['option_productsn'];
                    }

                    if (!empty($og['goodssn'])) {
                        $goods .= ' 商品编号: ' . $og['goodssn'];
                    }

                    if (!empty($og['productsn'])) {
                        $goods .= ' 商品条码: ' . $og['productsn'];
                    }

                    $goods .=
                        ' 单价: ' . ($og['price'] / $og['total']) .
                        ' 折扣后: ' . ($og['realprice'] / $og['total']) .
                        ' 数量: ' . $og['total'] .
                        ' 总价: ' . $og['price'] .
                        ' 折扣后: ' . $og['realprice'] . "\r\n" .
                        ' ';

                    if ($plugin_diyform && !empty($og['diyformfields']) && !empty($og['diyformdata'])) {

                        $diyformdata_array = $plugin_diyform->getDatas(
                            iunserializer($og['diyformfields']),
                            iunserializer($og['diyformdata'])
                        );
                        $diyformdata       = '';

                        foreach ($diyformdata_array as $da) {
                            $diyformdata .= $da['name'] . ': ' . $da['value'] . "\r\n";
                        }

                        $og['goods_diyformdata'] = $diyformdata;
                    }
                }
                unset($og);

                $value['goods']      = $order_goods;
                $value['goodscount'] = count($order_goods);
                $goodscount += $value['goodscount'];
                $value['commission']  = $commission1 + $commission2 + $commission3;
                $value['commission1'] = $commission1;
                $value['commission2'] = $commission2;
                $value['commission3'] = $commission3;
                $value['commission4'] = number_format($value['price'] - ($commission1 + $commission2 + $commission3),2);
                $value['profit']      = number_format($value['price'] - $value['dispatchprice'] - ($commission1 + $commission2 + $commission3),2);
                $value['goods_str']   = $goods;
                $value['ordersn']     = $value['ordersn'] . ' ';

                if (0 < $value['deductprice']) {
                    $value['deductprice'] = '-' . $value['deductprice'];

                    $value['deductprice'] = number_format($value['deductprice'],2);
                }

                if (0 < $value['deductcredit2']) {
                    $value['deductcredit2'] = '-' . $value['deductcredit2'];

                    $value['deductcredit2'] = number_format($value['deductcredit2'],2);
                }

                if (0 < $value['deductenough']) {
                    $value['deductenough'] = '-' . $value['deductenough'];
                }

                if ($value['changeprice'] < 0) {
                    $value['changeprice'] = '-' . $value['changeprice'];
                } else if (0 < $value['changeprice']) {
                    $value['changeprice'] = '+' . $value['changeprice'];
                }

                if ($value['changedispatchprice'] < 0) {
                    $value['changedispatchprice'] = '-' . $value['changedispatchprice'];
                } else if (0 < $value['changedispatchprice']) {
                    $value['changedispatchprice'] = '+' . $value['changedispatchprice'];
                }

                if (0 < $value['couponprice']) {
                    $value['couponprice'] = '-' . $value['couponprice'];
                }

                $value['expresssn']  = $value['expresssn'] . ' ';
                $value['createtime'] = date('Y-m-d H:i:s', $value['createtime']);
                $value['paytime']    = (!empty($value['paytime']) ? date('Y-m-d H:i:s', $value['paytime']) : '');
                $value['sendtime']   = (!empty($value['sendtime']) ? date('Y-m-d H:i:s', $value['sendtime']) : '');
                $value['finishtime'] = (!empty($value['finishtime']) ? date('Y-m-d H:i:s', $value['finishtime']) : '');
                $value['salerinfo']  = '';
                $value['storeinfo']  = '';

                if (!empty($value['verifyopenid'])) {
                    $value['salerinfo'] = '[' . $value['salerid'] . ']' . $value['salername'] . '(' . $value['salernickname'] . ')';
                }


                if (!empty($value['verifystoreid'])) {
                    $value['storeinfo'] = pdo_fetchcolumn(
                        ' select storename ' .
                        ' from ' . tablename('superdesk_shop_enterprise_store') .
                        ' where id=:storeid ' .
                        ' limit 1 ',
                        array(
                            ':storeid' => $value['verifystoreid']
                        )
                    );
                }


                if ($plugin_diyform
                    && !empty($value['diyformfields'])
                    && !empty($value['diyformdata'])
                ) {

                    $diyformdata_array = p('diyform')->getDatas(
                        iunserializer($value['diyformfields']),
                        iunserializer($value['diyformdata'])
                    );

                    $diyformdata = '';

                    foreach ($diyformdata_array as $da) {

                        $diyformdata .= $da['name'] . ': ' . $da['value'] . "\r\n";

                    }

                    $value['order_diyformdata'] = $diyformdata;
                }

//                if($value['parentid'] != 0){
//                    $parentorder = pdo_fetch('select ordersn,price from '.tablename('superdesk_shop_order').' where id=:id',array(':id'=>$value['parentid']));
//                    $value['parent_ordersn'] = $parentorder['ordersn'];
//                    $value['parent_price'] = $parentorder['price'];
//                }
            }
            unset($value);

            $exportlist = array();

            if ($this->field_index($columns, 'goods_title') != -1) {
                $i = 0;

                while ($i < $goodscount) {
                    $exportlist['row' . $i] = array();
                    ++$i;
                }

                $rowindex = 0;

                foreach ($list as $index => $r) {

                    $exportlist['row' . $rowindex] = $r;
                    $goodsindex                    = $rowindex;

                    foreach ($r['goods'] as $g) {

                        $exportlist['row' . $goodsindex] = $r;

                        $exportlist['row' . $goodsindex]['goods_title']       = $g['title'];
                        $exportlist['row' . $goodsindex]['goods_goodssn']     = $g['goodssn'];
                        $exportlist['row' . $goodsindex]['goods_productsn']   = $g['productsn'];
                        $exportlist['row' . $goodsindex]['goods_optiontitle'] = $g['optiontitle'];
                        $exportlist['row' . $goodsindex]['goods_total']       = $g['total'];
                        $exportlist['row' . $goodsindex]['goods_return_goods_nun'] = $g['return_goods_nun'];

                        $exportlist['row' . $goodsindex]['goods_price1']      = number_format($g['price'] / $g['total'],2);
                        $exportlist['row' . $goodsindex]['goods_price2']      = number_format($g['realprice'] / $g['total'],2);
                        $exportlist['row' . $goodsindex]['goods_rprice1']     = $g['price'];
                        $exportlist['row' . $goodsindex]['goods_rprice2']     = $g['realprice'];
                        $exportlist['row' . $goodsindex]['goods_diyformdata'] = $g['goods_diyformdata'];

                        // 补充信息 李华锐要求
//                        $exportlist['row' . $goodsindex]['goods_jd_vop_costprice'] = $g['costprice'];// P 京东协议价 成本价有可会变
                        $exportlist['row' . $goodsindex]['goods_jd_vop_costprice'] = $g['jd_costprice'];// P 京东协议价 已修正 只有京东
                        $exportlist['row' . $goodsindex]['goods_jd_vop_sku']       = $g['jd_vop_sku'];// 商品编码(京东SKU) jd_vop_sku
                        $exportlist['row' . $goodsindex]['goods_unit']             = $g['unit'];// 商品单位 unit
                        $exportlist['row' . $goodsindex]['goods_pcate']            = $this->_categoryService->getNameById($g['pcate']);// 商品一级分类 pcate
                        $exportlist['row' . $goodsindex]['goods_ccate']            = $this->_categoryService->getNameById($g['ccate']);// 商品二级分类 ccate
                        $exportlist['row' . $goodsindex]['goods_tcate']            = $this->_categoryService->getNameById($g['tcate']);// 商品三级分类 tcate
                        $exportlist['row' . $goodsindex]['goods_fiscal_code']      = $g['fiscal_code'];// 财务代码 fiscal_code

                        ++$goodsindex;
                    }
                    $nextindex = 0;
                    $i         = 0;

                    while ($i <= $index) {
                        $nextindex += $list[$i]['goodscount'];
                        ++$i;
                    }

                    $rowindex = $nextindex;
                }
            } else {
                foreach ($list as $r) {
                    $exportlist[] = $r;
                }
            }


//            print_r($exportlist);

            m('excel')->export(
                $exportlist,
                array(
                    'title'   => '订单数据-' . date('Y-m-d_H_i', time()),
                    'columns' => $columns
                )
            );
        }
        if (empty($starttime) || empty($endtime)) {
            $starttime = strtotime('-1 month');
            $endtime   = time();
        }

        $stores = pdo_fetchall(
            ' select id,storename ' .
            ' from ' . tablename('superdesk_shop_enterprise_store') .
            ' where uniacid=:uniacid ' .
            '       and enterprise_id = :enterprise_id',
            array(
                ':uniacid' => $_W['uniacid'],
                ':enterprise_id' => $_W['enterprise_id']
            )
        );

        include $this->template();
    }

    public function save()
    {
        global $_W;
        global $_GPC;
        $columns = $_GPC['columns'];

        if (!is_array($columns)) {
            exit();
        }


        $sets           = $this->model->getSet();
        $data     = array(
            'ordertemplates' => $sets['shop']['ordertemplates']
        );
        $tempname = trim($_GPC['tempname']);

        if (!empty($tempname)) {
            $data['ordertemplates'][$tempname] = $columns;
        }


        $data['ordercolumns'] = $columns;
        $this->model->updateSet(array('shop' => $data));

        if (!empty($tempname)) {
            exit(json_encode(array(
                'templates' => array_keys($data['ordertemplates']),
                'tempname' => $tempname
            )));
        }


        exit(json_encode(array()));
    }

    public function delete()
    {
        global $_W;
        global $_GPC;
        $sets     = $this->model->getSet();
        $data     = array('ordertemplates' => $sets['shop']['ordertemplates']);
        $tempname = trim($_GPC['tempname']);

        if (!empty($tempname)) {
            unset($data['ordertemplates'][$tempname]);
        }

        $this->model->updateSet(array('shop' => $data));

        exit(json_encode(array(
            'templates' => array_keys($data['ordertemplates'])
        )));
    }

    public function gettemplate()
    {
        global $_W;
        global $_GPC;
        $sets            = $this->model->getSet();
        $shop_set        = $sets['shop'];
        $tempname        = trim($_GPC['tempname']);
        $default_columns = $this->defaultColumns();

        if (empty($tempname)) {
            $columns = array();
        } else {
            $columns = $shop_set['ordertemplates'][$tempname];
        }

        if (!is_array($columns)) {
            $columns = array();
        }

        $others = array();

        $selectcolumns = array();
        foreach ($default_columns as $dc) {

            $hascolumn = false;

            foreach ($columns as $c) {
                if (!($dc['field'] == $c['field'])) {
                    continue;
                }
                $selectcolumns[] = $dc;
                $hascolumn = true;
                break;
            }

            if (!$hascolumn) {
                $others[] = $dc;
            }

        }
        $data['ordercolumns'] = $selectcolumns;

        $this->model->updateSet(array('shop' => $data));

        exit(json_encode(array(
            'columns' => $columns,
            'others'  => $others
        )));
    }

    public function reset()
    {
        global $_W;
        global $_GPC;

        $data['ordercolumns'] = array();

        $this->model->updateSet(array('shop' => $data));

        show_json(1);
    }
}


?>