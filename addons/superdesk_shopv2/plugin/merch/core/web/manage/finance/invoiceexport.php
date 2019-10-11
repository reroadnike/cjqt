<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

require SUPERDESK_SHOPV2_PLUGIN . 'merch/core/inc/page_merch.php';

class Invoiceexport_SuperdeskShopV2Page extends MerchWebPage
{
    protected function field_index($columns, $field)
    {
        $index = -1;
        foreach ($columns as $i => $v) {
            if ($v['field'] == $field) {
                $index = $i;
                break;
            }
        }
        return $index;
    }

    protected function defaultColumns()
    {
        return array(
            array('title' => '一级订单编号', 'field' => 'parent_ordersn', 'width' => 24),// A 父单编号
            array('title' => '一级金额', 'field' => 'parent_price', 'width' => 12),// A 父单金额
            array('title' => '下单时间', 'field' => 'createtime', 'width' => 24),// A 下单时间
            array('title' => '完成时间', 'field' => 'finishtime', 'width' => 24),// A 完成时间
            array('title' => '商户名', 'field' => 'merchname', 'width' => 18),// A 商户名
            array('title' => '二级订单编号', 'field' => 'ordersn', 'width' => 24),// A 订单编号
            array('title' => '三级订单编号', 'field' => 'third_ordersn', 'width' => 24),// A 订单编号
            array('title' => '京东单号', 'field' => 'expresssn', 'width' => 24),// A 单据号
            array('title' => '单据号', 'field' => 'invoicesn', 'width' => 24),// A 单据号

            array('title' => '商品行数', 'field' => 'goods_num', 'width' => 12),// B 商品行数
            array('title' => '购方名称（开票抬头）', 'field' => 'invoice_companyName', 'width' => 36),

            array('title' => '购方税号', 'field' => 'invoice_taxpayersIDcode', 'width' => 24),// C 购方税号

            array('title' => '购方地址电话', 'field' => 'invoice_invoiceAddress', 'width' => 72),// D 购方地址电话

            array('title' => '购方银行帐号', 'field' => 'invoice_invoiceBank', 'width' => 48),// E 购方银行帐号
            array('title' => '备注（项目名称）', 'field' => 'enterprise_name', 'width' => 24),


            array('title' => '复核人', 'field' => 'reviewer', 'width' => 12),// 复核人
            array('title' => '收款人', 'field' => 'payee', 'width' => 12),


            array('title' => '清单行商品名称', 'field' => 'order_goods_name', 'width' => 12),// G 清单行商品名称


            array('title' => '单据日期（开票日期）', 'field' => 'create_invoice_time', 'width' => 18),// H 单据日期（开票日期）


            array('title' => '销方银行帐号', 'field' => 'sale_bank', 'width' => 48),// I 销方银行帐号


            array('title' => '销方地址电话', 'field' => 'sale_address', 'width' => 72),// J 销方地址电话


            array('title' => '单据号', 'field' => 'invoicesn2', 'width' => 24),
            array('title' => '序号', 'field' => 'sn', 'width' => 24),
            array('title' => '货物名称', 'field' => 'goodsname', 'width' => 78),// L 商品名称


            array('title' => '计量单位', 'field' => 'unit', 'width' => 12),
            array('title' => '规格', 'field' => 'spec', 'width' => 12),

            array('title' => '数量', 'field' => 'goodscount', 'width' => 12),
            array('title' => '退货数量', 'field' => 'return_goods_nun', 'width' => 12),
            array('title' => '商品金额', 'field' => 'goodsprice', 'width' => 12),
            array('title' => '实际商品金额', 'field' => 'realprice', 'width' => 12),

            array('title' => '税率', 'field' => 'taxrate', 'width' => 12),
            array('title' => '商品税目', 'field' => 'taxitem', 'width' => 12),

            array('title' => '折扣金额', 'field' => 'discount_price', 'width' => 12),
            array('title' => '税额', 'field' => 'taxprice', 'width' => 12),

            array('title' => '折扣税额', 'field' => 'discount_tax_price', 'width' => 12),// O 折扣税额


            array('title' => '折扣率', 'field' => 'discount', 'width' => 12),// 折扣率
            array('title' => '单价', 'field' => 'marketprice', 'width' => 12),// R 单价
            array('title' => '价格方式', 'field' => 'pricetype', 'width' => 12),// S 价格方式
            array('title' => '商品编码库版本号', 'field' => 'versionsn', 'width' => 12),

            array('title' => '商品编码', 'field' => 'fiscal_code', 'width' => 12),
            array('title' => '商品编码2', 'field' => 'taxCode', 'width' => 18),
            array('title' => '企业商品编码', 'field' => 'company_goods_sn', 'width' => 12),
            array('title' => '使用优惠政策标识', 'field' => 'policy_logo', 'width' => 12),
            array('title' => '零税率标识', 'field' => 'tax_rate_logo', 'width' => 24),
            array('title' => '优惠政策说明', 'field' => 'policy_desc', 'width' => 24),

            array('title' => '支付方式', 'field' => 'paytype', 'width' => 12),// D 支付方式

            array('title' => '买家备注', 'field' => 'remark', 'width' => 36),
            array('title' => '卖家备注', 'field' => 'remarksaler', 'width' => 36),
            array('title' => '发票', 'field' => 'invoice_invoiceType', 'width' => 12),// T 发票 // 发票类型 增值税普票 1 增值税专票 2
            array('title' => '订单状态', 'field' => 'status', 'width' => 12),//
            array('title' => '发票号', 'field' => 'invoice_sn', 'width' => 24),//
            array('title' => 'ID', 'field' => 'finance_id', 'width' => 12),//


            //2018年7月23日 10:00:41 zjh 添加
            array('title' => '客户姓名', 'field' => 'realname', 'width' => 12),// 收货姓名(或自提人) F 客户姓名
            array('title' => '粉丝昵称', 'field' => 'nickname', 'width' => 12),
            array('title' => '联系电话', 'field' => 'mobile', 'width' => 12),// G 联系电话
            array('title' => '收货地址', 'subtitle' => '收货地址(省市区合并)', 'field' => 'address', 'width' => 24),// H 收货地址
            array('title' => '会员名称', 'field' => 'mrealname', 'width' => 12),// 会员姓名 Q 会员名称
            array('title' => '会员手机号', 'field' => 'mmobile', 'width' => 18),// R 会员手机号

            //2018年9月6日 16:37:01 zjh 应吴玉芳要求添加
            array('title' => '运费', 'field' => 'dispatchprice', 'width' => 12),
            array('title' => '运费改价', 'field' => 'changedispatchprice', 'width' => 12),

            //2018年10月11日 10:58:22 zjh 应吴玉芳要求添加
            array('title' => '订单金额', 'field' => 'order_price', 'width' => 12),
            array('title' => '退款金额(退款是跟着订单走的)', 'field' => 'refund_price', 'width' => 12),
            array('title' => '减去退款后金额', 'field' => 'reduce_refund_price', 'width' => 12),
        );
    }


    public function main()
    {
        global $_W;
        global $_GPC;

//        message('系统维护');
//        exit();

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
        $templates       = ((isset($shop_set['invoice_export_templates']) ? $shop_set['invoice_export_templates'] : array()));
        $columns         = ((isset($shop_set['invoice_export_columns']) ? $shop_set['invoice_export_columns'] : array()));

        if (empty($columns)) {

            if ($dflag == 0) {
                $columns = $default_columns;
            }
        }

        foreach ($default_columns as &$dc) {
            $dc['select'] = false;
            foreach ($columns as $c) {
                if ($dc['field'] == $c['field']) {
                    $dc['select'] = true;
                    break;
                }
            }
        }
        unset($dc);


        if ($_GPC['export'] == 1) {
            plog('finance.invoiceexport', '导出订单');

            $condition = ' o.uniacid = :uniacid and ofi.merchid = :merchid and ofi.status = 2 ';

            $params = array(
                ':uniacid' => $_W['uniacid'],
                ':merchid' => $_W['merchid']
            );

            if (!empty($_GPC['createtime']['start'])
                && !empty($_GPC['createtime']['end'])
            ) {

                $createstarttime = strtotime($_GPC['createtime']['start']);
                $createendtime   = strtotime($_GPC['createtime']['end']);

                $condition .=
                    ' AND o.createtime >= :createstarttime ' .
                    ' AND o.createtime <= :createendtime ';
                $params[':createstarttime'] = $createstarttime;
                $params[':createendtime']   = $createendtime;
            }

            if (!empty($_GPC['finishtime']['start'])
                && !empty($_GPC['finishtime']['end'])
            ) {

                $finishstarttime = strtotime($_GPC['finishtime']['start']);
                $finishendtime   = strtotime($_GPC['finishtime']['end']);

                $condition .=
                    ' AND o.finishtime >= :finishstarttime ' .
                    ' AND o.finishtime <= :finishendtime ';
                $params[':finishstarttime'] = $finishstarttime;
                $params[':finishendtime']   = $finishendtime;
            }

            $status = $_GPC['status'];
            if ($status != '') {
                $condition .= ' AND ofi.status=:status';
                $params[':status']   = $status;
            }

            // mark welfare
            $leftJoinSql = '';
            $selectSql = '';

            switch (SUPERDESK_SHOPV2_MODE_USER) {
                case 1:// 1 超级前台
                    $selectSql   = ' core_enterprise.name as enterprise_name, ';
                    $leftJoinSql = ' left join ' . tablename('superdesk_core_virtualarchitecture') . ' core_enterprise on core_enterprise.id = o.member_enterprise_id ';
                    break;
                case 2:// 2 福利商城
                    $selectSql   = ' core_enterprise.enterprise_name, ';
                    $leftJoinSql = ' left join ' . tablename('superdesk_shop_enterprise_user') . ' core_enterprise on core_enterprise.id = o.member_enterprise_id ';
                    break;
            }

            // TODO 可优化
            $sql =
                ' select o.*, ofi.id as finance_id, ofi.create_invoice_time,ofi.invoice_sn, ' .
                $selectSql .
                '        mu.merchname, ' .
                '        po.ordersn as parent_ordersn,po.price as parent_price, ' .
                '        m.nickname,m.id as mid,m.realname as mrealname,m.mobile as mmobile,m.core_enterprise,' .
                '        a.realname as arealname,a.mobile as amobile,' .
                '        a.province as aprovince ,a.city as acity , a.area as aarea , a.town as atown ,a.address as aaddress, ' .
                '        orf.price as refund_price ' .
                ' from ' . tablename('superdesk_shop_order_finance') . ' ofi' .
                '       left join ' . tablename('superdesk_shop_order') . ' o on o.id = ofi.orderid ' . // TODO 标志 楼宇之窗 openid shop_order 已处理
                '       left join ' . tablename('superdesk_shop_order') . ' po on po.id = o.parentid ' .
                '       left join ' . tablename('superdesk_shop_merch_user') . ' mu on mu.id = o.merchid ' .
                '       left join ' . tablename('superdesk_shop_member') . ' m on m.openid = o.openid and m.core_user = o.core_user ' .
                '       left join ' . tablename('superdesk_shop_member_address') . ' a on a.id=o.addressid ' . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 不处理
                '       left join ' . tablename('superdesk_shop_order_refund') . ' orf on orf.id=o.refundid and orf.status = 1 ' .
                $leftJoinSql .
                ' where ' .
                $condition .
                ' ORDER BY o.createtime ASC  ';

            $list       = pdo_fetchall($sql, $params);
            $goodscount = 0;

            $view_invoiceType = array(
                1 => '增值税普票',
                2 => '增值税专票'
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

            foreach ($list as &$value) {


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
                            ':id'      => $value['invoiceid'],
                            ':uniacid' => $_W['uniacid']
                        )
                    );// 结构 invoice

                }
                $value['invoice_invoiceType']          = $view_invoiceType[$invoice['invoiceType']];// T 发票 // 发票类型 增值税普票 1 增值税专票 2
                $value['invoice_companyName']          = $invoice['companyName'];// V 抬头 //请在此填写发票抬头
                $value['invoice_taxpayersIDcode']      = $invoice['taxpayersIDcode'];// X 纳税人识别号 //请在此填写纳税人识别号
                $value['invoice_invoiceAddress']       = $invoice['invoiceAddress']. ' '.$invoice['invoicePhone'];// Y 注册地址 //请在此填写注册地址
                //$value['invoice_invoicePhone']         = ;// Z 注册号码 //请在此填写注册电话
                $value['invoice_invoiceBank']          = $invoice['invoiceBank'].' '.$invoice['invoiceAccount'];// AA 开户银行 //请在此填写开户银行
                //$value['invoice_invoiceAccount']       = $invoice['invoiceAccount'];// AB 开户帐号 //请在此填写银行账号
                // 发票信息 end


                $order_goods = pdo_fetchall(
                    ' select ' .
                    '       g.id,g.title,g.thumb,g.goodssn,og.goodssn as option_goodssn, g.productsn, g.unit,' .
                    '       g.jd_vop_sku,' .
                    '       c.name,c.fiscal_code,' .
                    '       og.productsn as option_productsn, og.total, og.return_goods_nun, og.price,og.optionname as optiontitle, ' .
                    '       og.realprice,' .
                    '       po.status,po.ordersn,po.refundtime, ' .
                    '       IFNULL(jd_exts.taxCode,"") as taxCode ' .
                    ' from ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
                    ' left join ' . tablename('superdesk_shop_order') . ' po on po.id=og.orderid and og.parent_order_id > 0 ' .// TODO 标志 楼宇之窗 openid shop_order 不处理
                    ' left join ' . tablename('superdesk_shop_goods') . ' g on g.id=og.goodsid ' .
                    ' left join ' . tablename('superdesk_shop_category') . ' c on g.tcate=c.id ' .
                    ' left join ' . tablename('superdesk_jd_vop_product_exts') . ' jd_exts on g.jd_vop_sku = jd_exts.sku ' .
                    ' where og.uniacid=:uniacid ' .
                    '       and (og.orderid=:orderid or og.parent_order_id=:orderid) ',
                    array(
                        ':uniacid' => $_W['uniacid'],
                        ':orderid' => $value['id']
                    )
                );

                $goods = '';
                $goodscount += count($order_goods);

                foreach ($order_goods as &$og) {

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
                }
                unset($og);

                $value['goods']      = $order_goods;
                $value['goodscount'] = count($order_goods);
                unset($order_goods);
                $goodscount += $value['goodscount'];
                $value['goods_str']   = $goods;
                $value['ordersn']     = $value['ordersn'] . ' ';

                $value['expresssn']  = $value['expresssn'] . ' ';

                $value['create_invoice_time'] = date('Y-m-d H:i:s',$value['create_invoice_time']);

                $value['createtime'] = date('Y-m-d H:i:s',$value['createtime']);
                $value['finishtime'] = date('Y',$value['finishtime']) == '1970' ? '' : date('Y-m-d H:i:s',$value['finishtime']);

                if ($value['changedispatchprice'] < 0) {
                    $value['changedispatchprice'] = '-' . $value['changedispatchprice'];
                } else if (0 < $value['changedispatchprice']) {
                    $value['changedispatchprice'] = '+' . $value['changedispatchprice'];
                }
            }
            unset($value);

            $exportlist = array();


            foreach ($list as $r) {
                foreach ($r['goods'] as $g) {
                    //TODO 2018年10月11日 11:31:01 zjh 这里预留一下.退款金额如果是京东的要去检查一下京东那边有没有退款
                    $r['order_price'] = $r['price'];
                    $r['reduce_refund_price'] = !empty($r['refund_price']) ? number_format($r['price'] - $r['refund_price'],2) : $r['price'];
                    $part_arr                 = $r;

                    $part_arr['goodsname']        = str_replace(' ', '', $g['title']);
                    $part_arr['goodsname']        = preg_replace("/\(.*?\)/", '', $part_arr['goodsname']);
                    $part_arr['goodsname']        = preg_replace("/\（.*?\）/", '', $part_arr['goodsname']);

                    $part_arr['goodscount']   = $g['total'];
                    $part_arr['goodsprice']   = $g['price'];
                    $part_arr['marketprice']  = number_format($g['price'] / $g['total'], 2);
                    $part_arr['price']        = $g['price'];
                    $part_arr['unit']         = $g['unit'];
                    $part_arr['fiscal_code']  = $g['fiscal_code'];
                    $part_arr['return_goods_nun']  = $g['return_goods_nun'];
                    $part_arr['taxCode']           = $g['taxCode'];
                    $part_arr['reviewer']     = '姜瑶瑶';
                    $part_arr['payee']        = '吴玉芳';
                    $part_arr['sale_bank']    = '交通银行深圳华富支行 443899991010006010434';
                    $part_arr['sale_address'] = '深圳市前海深港合作区前湾一路1号A栋201室 075588600765';
                    $part_arr['taxrate']      = '0.16';
                    $part_arr['policy_logo']  = '0';

                    $status = $r['status'];
                    $part_arr['third_ordersn'] = '';
                    $refundtime = $r['refundtime'];
                    if(isset($g['status'])){
                        $status = $g['status'];
                        $part_arr['third_ordersn'] = $g['ordersn'];
                        $refundtime = $g['refundtime'];
                    }

                    $pt = $r['paytype'];
                    $part_arr['paytype'] = $paytype[$r['paytype']]['name'];

                    $part_arr['status']  = $pt == 3 && empty($status) ? $orderstatus[1]['name'] : $orderstatus[$status]['name'];

                    if ($status == -1) {
                        if (!empty($refundtime)) {
                            $part_arr['status'] = '维权中';
                        }
                    }

                    $exportlist[]             = $part_arr;
                    unset($part_arr);
                }
            }
            unset($list);
            //print_r(memory_get_usage());die;

            //<b>Fatal error</b>:  Allowed memory size of 268435456 bytes exhausted (tried to allocate 20480 bytes) in <b>E:\project\superdesk\framework\library\phpexcel\PHPExcel\CachedObjectStorage\CacheBase.php</b> on line 286
            m('excel')->export(
                $exportlist,
                array(
                    'title'   => '开票数据-' . date('Y-m-d_H_i', time()),
                    'columns' => $columns
                )
            );
        }

        if (empty($starttime) || empty($endtime)) {
            $starttime = strtotime('-1 month');
            $endtime   = time();
        }

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
            'invoice_export_templates' => $sets['shop']['invoice_export_templates']
        );
        $tempname = trim($_GPC['tempname']);

        if (!empty($tempname)) {
            $data['invoice_export_templates'][$tempname] = $columns;
        }

        $data['invoice_export_columns'] = $columns;

        $this->model->updateSet(array('shop' => $data));

        if (!empty($tempname)) {
            exit(json_encode(array('templates' => array_keys($data['invoice_export_templates']), 'tempname' => $tempname)));
        }
        exit(json_encode(array()));
    }

    public function delete()
    {
        global $_W;
        global $_GPC;

        $sets           = $this->model->getSet();

        $data = array(
            'invoice_export_templates' => $sets['shop']['invoice_export_templates']
        );

        $tempname = trim($_GPC['tempname']);

        if (!empty($tempname)) {
            unset($data['invoice_export_templates'][$tempname]);
        }

        $this->model->updateSet(array('shop' => $data));

        exit(json_encode(array(
            'templates' => array_keys($data['invoice_export_templates'])
        )));
    }

    public function gettemplate()
    {
        global $_W;
        global $_GPC;

        $sets           = $this->model->getSet();
        $tempname = trim($_GPC['tempname']);

        $default_columns = $this->defaultColumns();

        if (empty($tempname)) {
            $columns = array();
        } else {
            $columns = $sets['shop']['invoice_export_templates'][$tempname];
        }

        if (!is_array($columns)) {
            $columns = array();
        }

        $others = array();

        $selectcolumns = array();

        foreach ($default_columns as $dc) {

            $hascolumn = false;

            foreach ($columns as $c) {
                if ($dc['field'] == $c['field']) {
                    $hascolumn = true;
                    $selectcolumns[] = $dc;
                    break;
                }
            }

            if (!$hascolumn) {
                $others[] = $dc;
            }
        }

        $data['invoice_export_columns'] = $selectcolumns;

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

        $data['invoice_export_columns'] = array();

        $this->model->updateSet(array('shop' => $data));

        show_json(1);
    }
}