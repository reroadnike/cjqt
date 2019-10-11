<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}


include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order_finance.class.php');

/* 企业内购-数据源 start */
include_once(IA_ROOT . '/addons/superdesk_core/model/organization.class.php');
include_once(IA_ROOT . '/addons/superdesk_core/model/virtualarchitecture.class.php');
/* 企业内购-数据源 end */

/* 福利商城-数据源 start */
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/__plugin__/enterprise/enterprise_user.class.php');
/* 福利商城-数据源 start */


/**
 * Class Finance_SuperdeskShopV2Page
 * 订单财务管理类
 * zjh 2018年4月25日 11:09:08
 */
class Finance_SuperdeskShopV2Page extends WebPage
{

    private $_order_financeModel;

    private $_organizationModel;
    private $_virtualarchitectureModel;
    private $_enterprise_userModel;

    public function __construct($_init = true)
    {
        parent::__construct($_init);

        $this->_order_financeModel = new order_financeModel();

        // mark welfare
        switch (SUPERDESK_SHOPV2_MODE_USER) {
            case 1:// 1 超级前台
                $this->_organizationModel        = new organizationModel();
                $this->_virtualarchitectureModel = new virtualarchitectureModel();
                break;
            case 2:// 2 福利商城
                $this->_enterprise_userModel = new enterprise_userModel();
                break;
        }
    }

    protected function orderData($status, $st)
    {
        global $_W;
        global $_GPC;

        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;

        $merch_plugin = p('merch');
        $merch_data   = m('common')->getPluginset('merch');


        if ($merch_plugin && $merch_data['is_openmerch']) {
            $is_openmerch = 1;
        } else {
            $is_openmerch = 0;
        }

        if ($st == 'main') {
            $st = '';
        } else {
            $st = '.' . $st;
        }

        $condition = ' ofi.uniacid = :uniacid';
        $uniacid   = $_W['uniacid'];
        $params    = array(':uniacid' => $uniacid);

        if ($status !== '') {
            if ($status == '1') {
                $condition .= ' AND ofi.status=1';
            } else if ($status == '2') {
                $condition .= ' AND ofi.status=2';
            } else if ($status == '3') {
                $condition .= ' AND ofi.status=2 and ofi.press_status = 1';
            } else if ($status == '4') {
                $condition .= ' AND ofi.status=2 and ofi.press_status = 2';
            }
        }

        if (isset($_GPC['press_type']) && $_GPC['press_type'] != '-1' && $status == '3') {
            $condition             .= ' AND ofi.press_type=:press_type';
            $params[':press_type'] = $_GPC['press_type'];
        }

        // 有填写 请输入关键词
        if (!empty($_GPC['searchfield']) && !empty($_GPC['keyword'])) {

            $searchfield = trim(strtolower($_GPC['searchfield']));

            $_GPC['keyword'] = trim($_GPC['keyword']);

            $params[':keyword'] = htmlspecialchars_decode($_GPC['keyword'], ENT_QUOTES);

            $sqlcondition = '';

            if ($searchfield == 'ordersn') {
                $condition .= ' AND locate(:keyword,o.ordersn)>0';
            } else if ($searchfield == 'invoice_sn') {
                $condition .= ' AND locate(:keyword,ofi.invoice_sn)>0';
            } else if ($searchfield == 'price') {
                $condition .= ' AND ROUND(o.price)=:keyword';
            } else if ($searchfield == 'enterprise_name') {
                $condition .= ' AND (locate(:keyword,core_enterprise.name)>0)';
            } else if ($searchfield == 'member') {
                $condition .= ' AND (locate(:keyword,m.realname)>0 or locate(:keyword,m.mobile)>0 or locate(:keyword,m.nickname)>0)';
            } else if ($searchfield == 'address') {
                $condition .= ' AND ( locate(:keyword,a.realname)>0 or locate(:keyword,a.mobile)>0 or locate(:keyword,o.carrier)>0)';
            }
        }

        if (empty($starttime) || empty($endtime)) {
            $starttime = strtotime('-1 month');
            $endtime   = time();
        }

        $searchtime = trim($_GPC['searchtime']);
        if (!empty($searchtime) && is_array($_GPC['time']) && in_array($searchtime, array('createtime', 'create_invoice_time'))) {

            $starttime = strtotime($_GPC['time']['start']);
            $endtime   = strtotime($_GPC['time']['end']);

            if ($searchtime == 'createtime') {
                $searchtime = 'o.' . $searchtime;
            } else if ($searchtime == 'create_invoice_time') {
                $searchtime = 'ofi.' . $searchtime;
            }

            $condition            .= ' AND ' . $searchtime . ' >= :starttime AND ' . $searchtime . ' <= :endtime ';
            $params[':starttime'] = $starttime;
            $params[':endtime']   = $endtime;
        }

//        if(!empty($_GPC['member_id'])){
//            $condition .= ' AND m.openid=:openid ';
//            $params[':openid'] = $_GPC['member_id'];
//        }

        if (!empty($_GPC['invoice_title'])) {
            $condition                .= ' AND ( locate(:invoice_title,o.invoice)>0 )';
            $params[':invoice_title'] = trim($_GPC['invoice_title']);
        }

        if (!empty($_GPC['merchant_name'])) {
            $condition                .= ' AND ( locate(:merchant_name,mu.merchname)>0 )';
            $params[':merchant_name'] = trim($_GPC['merchant_name']);
        }

        if (!empty($_GPC['member_info'])) {
            $condition              .= ' AND (locate(:member_info,m.realname)>0 or locate(:member_info,m.mobile)>0 or locate(:member_info,m.nickname)>0)';
            $params[':member_info'] = trim($_GPC['member_info']);
        }

        if (!empty($_GPC['address_info'])) {
            $condition               .= ' AND ( locate(:address_info,a.realname)>0 or locate(:address_info,a.mobile)>0 or locate(:address_info,o.carrier)>0)';
            $params[':address_info'] = trim($_GPC['address_info']);
        }

        //2018年11月14日 13:24:10 zjh 杨宇迪增加【按回款时间】排序功能 #1686
        if (empty($_GPC['order']) || $_GPC['order'] == 1) {
            $orderBy = ' o.createtime desc ';
        } elseif ($_GPC['order'] == 2) {
            $orderBy = ' ofi.press_time desc ';
        }

        // mark welfare
        $leftJoinSql = '';
        $selectSql   = '';
        switch (SUPERDESK_SHOPV2_MODE_USER) {
            case 1:// 1 超级前台
                $selectSql   = ' core_enterprise.name as enterprise_name ';
                $leftJoinSql = ' left join ' . tablename('superdesk_core_virtualarchitecture') . ' core_enterprise on core_enterprise.id = o.member_enterprise_id ';
                if (!empty($_GPC['enterprise_name'])) {
                    $condition                  .= ' AND ( locate(:enterprise_name,core_enterprise.name)>0 )';
                    $params[':enterprise_name'] = $_GPC['enterprise_name'];
                }
                break;
            case 2:// 2 福利商城
                $selectSql   = ' core_enterprise.enterprise_name ';
                $leftJoinSql = ' left join ' . tablename('superdesk_shop_enterprise_user') . ' core_enterprise on core_enterprise.id = o.member_enterprise_id ';
                if (!empty($_GPC['enterprise_name'])) {
                    $condition                  .= ' AND ( locate(:enterprise_name,core_enterprise.enterprise_name)>0 )';
                    $params[':enterprise_name'] = $_GPC['enterprise_name'];
                }
                break;
        }

        $sql =
            ' select ofi.id,ofi.orderid,ofi.status as finance_status,ofi.create_invoice_time,ofi.invoice_sn,ofi.remark, ' .
            '        ofi.expressid as ofi_expressid,ofi.express_sn as ofi_expresssn,ofi.express as ofi_express, ' .
            '        ofi.press_type,ofi.press_msg,ofi.press_status,ofi.press_time, ' .
            '        o.ordersn,o.price,o.status,o.paytype,o.createtime,o.finishtime,o.expresssn,o.express,o.dispatchtype, ' .
            '        o.isverify,o.virtual,o.isvirtual,o.carrier,o.address,o.addressid,o.refundtime, ' .
            '        mu.merchname,d.dispatchname,a.realname as arealname,a.mobile as amobile,a.province as aprovince ,a.city as acity , ' .
            '        a.area as aarea,a.town as atown,a.address as aaddress,exp.name as express_name,o.member_enterprise_id, ' .
            '        m.realname as mrealname,m.mobile as mmobile, ' .
            $selectSql .
            ' from ' . tablename('superdesk_shop_order_finance') . ' as ofi ' .
            ' left join ' . tablename('superdesk_shop_order') . ' as o on o.id = ofi.orderid ' .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' left join ' . tablename('superdesk_shop_merch_user') . ' as mu on mu.id = o.merchid ' .
            ' left join ' . tablename('superdesk_shop_dispatch') . ' d on d.id = o.dispatchid ' .
            ' left join ' . tablename('superdesk_shop_member_address') . ' a on a.id=o.addressid ' . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 不处理
            $sqlcondition .
            ' left join ' . tablename('superdesk_shop_member') . ' m on m.openid = o.openid and m.core_user = o.core_user ' . // TODO 标志 楼宇之窗 openid shop_member 已处理
            ' left join ' . tablename('superdesk_shop_express') . ' exp on exp.id = ofi.expressid ' .
            $leftJoinSql .
            ' where ' . $condition .
            ' order by ' . $orderBy;

        if (empty($_GPC['export'])) {
            $sql .= ' LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize;
        }

        $list = pdo_fetchall($sql, $params);

        $total      = 0;
        $totalmoney = 0;
        if (count($list) > 0) {
            $paytype = array(
                0  => array('css' => 'default', 'name' => '未支付'),
                1  => array('css' => 'danger', 'name' => '余额支付'),
                11 => array('css' => 'default', 'name' => '后台付款'),
                2  => array('css' => 'danger', 'name' => '在线支付'),
                21 => array('css' => 'success', 'name' => '微信支付'),
                22 => array('css' => 'warning', 'name' => '支付宝支付'),
                23 => array('css' => 'warning', 'name' => '银联支付'),
                3  => array('css' => 'primary', 'name' => '企业月结'),
            );

            $orderstatus = array(
                -1 => array('css' => 'default', 'name' => '已关闭'),
                0  => array('css' => 'danger', 'name' => '待付款'),
                1  => array('css' => 'info', 'name' => '待发货'),
                2  => array('css' => 'warning', 'name' => '待收货'),
                3  => array('css' => 'success', 'name' => '已完成'),
            );

            $financestatus = array(
                1 => '未开票',
                2 => '已开票',
            );

            $pressstatus = array(
                0 => '未催款',
                1 => '未回款',
                2 => '已回款',
            );

            $presstype = array(
                0 => '未催款',
                1 => '业务催款',
                2 => '财务催款',
            );

            foreach ($list as $k => &$v) {
//                $v['createtime'] = date('Y-m-d H:i:s', $v['createtime']);
//                $v['finishtime'] = empty($v['finishtime']) ? '' : date('Y-m-d H:i:s', $v['finishtime']);
//                $v['create_invoice_time'] = empty($v['create_invoice_time']) ? '' : date('Y-m-d H:i:s', $v['create_invoice_time']);
//                $v['press_time'] = empty($v['press_time']) ? '' : date('Y-m-d H:i:s', $v['press_time']);

                $v['finance_status'] = $financestatus[$v['finance_status']];
                $v['press_status']   = $pressstatus[$v['press_status']];
                $v['press_type']     = $presstype[$v['press_type']];

                $s                = $v['status'];
                $pt               = $v['paytype'];
                $v['statusvalue'] = $s;
                $v['statuscss']   = $orderstatus[$v['status']]['css'];
                $v['status']      = $orderstatus[$v['status']]['name'];

                if (($pt == 3) && empty($v['statusvalue'])) {
                    $v['statuscss'] = $orderstatus[1]['css'];
                    $v['status']    = $orderstatus[1]['name'];
                }

                if ($v['statusvalue'] == -1) {
                    if (!empty($v['refundtime'])) {
                        $v['status'] = '已退款';
                    }
                }

                $v['paytypevalue'] = $pt;
                $v['css']          = $paytype[$pt]['css'];
                $v['paytype']      = $paytype[$pt]['name'];
                $v['dispatchname'] = ((empty($v['addressid']) ? '自提' : $v['dispatchname']));

                if (empty($v['dispatchname'])) {
                    $v['dispatchname'] = '快递';
                }

                if ($pt == 3) {
                    $v['dispatchname'] = '企业月结';
                }

                if (($v['dispatchtype'] == 1) || !empty($v['isverify']) || !empty($v['virtual']) || !empty($v['isvirtual'])) {
                    $v['address'] = '';
                    $carrier      = iunserializer($v['carrier']);
                    if (is_array($carrier)) {
                        $v['addressdata']['realname'] = $v['realname'] = $carrier['carrier_realname'];
                        $v['addressdata']['mobile']   = $v['mobile'] = $carrier['carrier_mobile'];
                    }
                } else {
                    $address = iunserializer($v['address']);
                    $isarray = is_array($address);

                    $v['realname'] = (($isarray ? $address['realname'] : $v['arealname']));
                    $v['mobile']   = (($isarray ? $address['mobile'] : $v['amobile']));

                    $v['province'] = (($isarray ? $address['province'] : $v['aprovince']));
                    $v['city']     = (($isarray ? $address['city'] : $v['acity']));
                    $v['area']     = (($isarray ? $address['area'] : $v['aarea']));
                    $v['town']     = (($isarray ? $address['town'] : $v['town']));

                    $v['address'] = (($isarray ? $address['address'] : $v['aaddress']));

                    $v['address_province'] = $v['province'];
                    $v['address_city']     = $v['city'];
                    $v['address_area']     = $v['area'];
                    $v['address_town']     = $v['town'];

                    $v['address_address'] = $v['address'];
                    $v['address']         = $v['province'] . ' ' . $v['city'] . ' ' . $v['area'] . ' ' . $v['town'] . ' ' . $v['address'];
                    $v['addressdata']     = array(
                        'realname' => $v['realname'],
                        'mobile'   => $v['mobile'],
                        'address'  => $v['address'],
                    );
                }
            }
            unset($v);


            if ($_GPC['export'] == 1) {

                plog('order.finance.export', '导出订单');

                $columns = array(
                    array('title' => '订单编号', 'field' => 'ordersn', 'width' => 24),
                    array('title' => '快递单号', 'field' => 'expresssn', 'width' => 24),
                    array('title' => '商户名称', 'field' => 'merchname', 'width' => 12),
                    array('title' => '企业名称', 'field' => 'enterprise_name', 'width' => 18),
                    array('title' => '买家名称', 'field' => 'realname', 'width' => 12),
                    array('title' => '买家电话', 'field' => 'mobile', 'width' => 12),
                    array('title' => '订单金额', 'field' => 'price', 'width' => 12),
                    array('title' => '创建时间', 'field' => 'createtime', 'width' => 18),
                    array('title' => '完成时间', 'field' => 'finishtime', 'width' => 18),
                    array('title' => '支付方式', 'field' => 'paytype', 'width' => 12),
                    array('title' => '状态', 'field' => 'status', 'width' => 12),
                    array('title' => '配送方式', 'field' => 'dispatchname', 'width' => 12),
                    array('title' => '开票状态', 'field' => 'finance_status', 'width' => 12),
                    array('title' => '开票时间', 'field' => 'create_invoice_time', 'width' => 18),
                    array('title' => '发票号', 'field' => 'invoice_sn', 'width' => 24),
                    array('title' => '开票备注', 'field' => 'remark', 'width' => 24),
                    array('title' => '快递公司', 'field' => 'express_name', 'width' => 24),
                    array('title' => '快递单号', 'field' => 'express_sn', 'width' => 24),
                    array('title' => '催款状态', 'field' => 'press_type', 'width' => 12),
                    array('title' => '催款跟进记录', 'field' => 'press_msg', 'width' => 24),
                    array('title' => '是否回款', 'field' => 'press_status', 'width' => 12),
                    array('title' => '回款时间', 'field' => 'press_time', 'width' => 18),
                );

                foreach ($list as $k => &$v) {
                    $v['createtime']          = date('Y-m-d H:i:s', $v['createtime']);
                    $v['create_invoice_time'] = date('Y-m-d H:i:s', $v['create_invoice_time']);
                    $v['finishtime']          = date('Y-m-d H:i:s', $v['finishtime']);
                    $v['press_time']          = date('Y-m-d H:i:s', $v['press_time']);
                }

                unset($v);
                unset($row);

                m('excel')->export(
                    $list,
                    array(
                        'title'   => '财务跟踪-' . date('Y-m-d-H-i', time()),
                        'columns' => $columns,
                    )
                );
            }

            if ($condition == ' ofi.uniacid = :uniacid') {
                $t = pdo_fetch(
                    ' select COUNT(*) as count, ifnull(sum(o.price),0) as sumprice ' .
                    ' from ' . tablename('superdesk_shop_order_finance') . ' as ofi ' .
                    ' left join ' . tablename('superdesk_shop_order') . ' as o on o.id = ofi.orderid ' .// TODO 标志 楼宇之窗 openid shop_order 已处理
                    ' where ' .
                    $condition .
                    ' order by ofi.createtime desc',
                    $params);
            } else {
                $t = pdo_fetch(
                    ' select COUNT(*) as count, ifnull(sum(o.price),0) as sumprice ' .
                    ' from ' . tablename('superdesk_shop_order_finance') . ' as ofi ' .
                    ' left join ' . tablename('superdesk_shop_order') . ' as o on o.id = ofi.orderid ' .// TODO 标志 楼宇之窗 openid shop_order 已处理
                    ' left join ' . tablename('superdesk_shop_merch_user') . ' as mu on mu.id = o.merchid ' .
                    ' left join ' . tablename('superdesk_shop_dispatch') . ' d on d.id = o.dispatchid ' .
                    ' left join ' . tablename('superdesk_shop_member_address') . ' a on a.id=o.addressid ' . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 不处理
                    ' left join ' . tablename('superdesk_shop_member') . ' m on m.openid = o.openid and m.core_user = o.core_user ' . // TODO 标志 楼宇之窗 openid shop_member 已处理
                    $leftJoinSql .
                    $sqlcondition .
                    ' where ' .
                    $condition,
                    $params);
            }

            $total      = $t['count'];      // 订单数
            $totalmoney = $t['sumprice'];   // 订单金额

        }

        $pager = pagination($total, $pindex, $psize);


        // mark welfare
        switch (SUPERDESK_SHOPV2_MODE_USER) {
            case 1:// 1 超级前台
                // 项目
                $result_organization   = $this->_organizationModel->querySelector(
                    array(
                        "isEnabled" => 1,
                        "status"    => 1   //0-待审核;1-通过;2-不通过
                    ), 1, 999);
                $selector_organization = $result_organization['data'];

                $selector_virtuals = array();
                if ($_GPC['organization_id'] != '') {

                    //2019年3月14日 16:33:16 zjh 佘司雄 选择后点搜索后不会自动选中 屏蔽掉 contractStatus status
                    $result_virtuals   = $this->_virtualarchitectureModel->queryForUsersAjax(
                        array(
                            "organizationId" => $_GPC['organization_id'],
                            "isEnabled"      => 1,
//                            "contractStatus" => 1,  //1-已签约;0-未签约
//                            "status"         => 1   //0-待审核;1-通过;2-不通过
                        ), 1, 2000);
                    $selector_virtuals = $result_virtuals['data'];
                }
                break;
            case 2:// 2 福利商城
                // 企业
                $selector_virtuals = $this->_enterprise_userModel->getAllByWhere(' status=:status ', array(':status' => 1));
                break;
        }

        if ($_GPC['enterprise_id'] != '') {

            $selector_member = pdo_fetchall(
                ' select ' .
                '       id,realname,openid,core_user ' .
                '       from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid shop_member 不处理
                ' where ' .
                '       uniacid=:uniacid ' .
                '       and core_enterprise=:enterprise_id',
                array(
                    ':uniacid'       => $_W['uniacid'],
                    ':enterprise_id' => $_GPC['enterprise_id'],
                )
            );
        }

        load()->func('tpl');

        include $this->template('order/finance');
    }

    public function main()
    {
        global $_W;
        global $_GPC;
        $orderData = $this->orderData('', 'main');
    }

    public function status1()
    {
        global $_W;
        global $_GPC;
        $orderData = $this->orderData(1, 'status1');
    }

    public function status2()
    {
        global $_W;
        global $_GPC;
        $orderData = $this->orderData(2, 'status2');
    }

    public function status3()
    {
        global $_W;
        global $_GPC;
        $orderData = $this->orderData(3, 'status3');
    }

    public function status4()
    {
        global $_W;
        global $_GPC;
        $orderData = $this->orderData(4, 'status4');
    }

    public function edit()
    {
        global $_GPC;
        global $_W;

        $params = array(
            ':id'      => $_GPC['id'],
            ':uniacid' => $_W['uniacid'],
        );

        $item = pdo_fetch(
            ' select * ' .
            ' from ' . tablename('superdesk_shop_order_finance') .
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid',
            $params
        );

        if ($_W['ispost']) {
//            if($_GPC['status'] == 2){
//                if(empty($_GPC['create_invoice_time'])){
//                    show_json(0,'请填写开票时间');
//                }
//
//                if(empty($_GPC['invoice_sn'])){
//                    show_json(0,'请填写发票号');
//                }
//            }
//            if($_GPC['press_status'] == 2){
//                if(empty($_GPC['press_time'])){
//                    show_json(0,'请填写回款时间');
//                }
//            }
//
//            if($_GPC['status'] != 2 && (
//                    !empty($_GPC['expressid']) ||
//                    !empty($_GPC['express_sn']) ||
//                    !empty($_GPC['press_type']) ||
//                    !empty($_GPC['press_msg']) ||
//                    $_GPC['press_status'] != 1 )){
//                show_json(0,'未开票无法填写后续信息');
//            }

            $update_data = array();
            $where       = array(
                'id'      => $item['id'],
                'uniacid' => $_W['uniacid'],
            );
            if ($_GPC['from_type'] == 'draw_a_bill') {
                $update_data = array(
                    'status'              => $_GPC['status'],
                    'create_invoice_time' => strtotime($_GPC['create_invoice_time']),
                    'invoice_sn'          => trim($_GPC['invoice_sn']),
                    'remark'              => trim($_GPC['remark']),
                );
            } else if ($_GPC['from_type'] == 'mail_invoice') {

                $update_data = array(
                    'expressid'  => trim($_GPC['expressid']),
                    'express_sn' => trim($_GPC['express_sn']),
                );

                if (!empty($_GPC['is_all_invoice_sn'])) {
                    if (empty($item['invoice_sn'])) {
                        show_json(0, '发票号为空,请不要批量修改');
                    }
                    $where = array(
                        'invoice_sn' => $item['invoice_sn'],
                        'uniacid'    => $_W['uniacid'],
                    );
                }
            } else if ($_GPC['from_type'] == 'press_for_money') {
                $update_data = array(
                    'press_type'   => trim($_GPC['press_type']),
                    'press_msg'    => trim($_GPC['press_msg']),
                    'press_status' => trim($_GPC['press_status']), // TODO zero 20180727
                    'press_time'   => strtotime($_GPC['press_time']),
                );
            }

            pdo_update('superdesk_shop_order_finance', $update_data, $where);

            show_json(1);
        }

        $expressList = m('express')->getExpressList();

        include $this->template();
    }

    public function detail()
    {

        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);


        $item = pdo_fetch(
            ' SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' WHERE id = :id and uniacid=:uniacid',
            array(
                ':id'      => $id,
                ':uniacid' => $_W['uniacid'],
            )
        );

        $item['statusvalue']  = $item['status'];
        $item['paytypevalue'] = $item['paytype'];

        $shopset = m('common')->getSysset('shop');

        if (empty($item)) {
            $this->message('抱歉，订单不存在!', referer(), 'error');
        }


        $member = m('member')->getMember($item['openid'], $item['core_user']);

        if (empty($item['addressid'])) {

            $user = unserialize($item['carrier']);

        } else {

            $user = iunserializer($item['address']);

            if (!is_array($user)) {

                $user = pdo_fetch(
                    ' SELECT * ' .
                    ' FROM ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 不处理
                    ' WHERE ' .
                    '       id = :id ' .
                    '       and uniacid=:uniacid',
                    array(
                        ':id'      => $item['addressid'],
                        ':uniacid' => $_W['uniacid'],
                    )
                );
            }


            $address_info    = $user['address'];
            $user['address'] = $user['province'] . ' ' . $user['city'] . ' ' . $user['area'] . ' ' . $user['town'] . ' ' . $user['address'];

            $item['addressdata'] = array(
                'realname' => $user['realname'],
                'mobile'   => $user['mobile'],
                'address'  => $user['address'],
            );
        }

        // 发票信息 start

        $view_invoiceType = array(
            1 => '增值税普票',
            2 => '增值税专票',
        );

        $view_selectedInvoiceTitle = array(
            4 => '个人',
            5 => '单位',
        );

        $view_invoiceContent = array(
            1  => '明细',
            3  => '电脑配件',
            19 => '耗材',
            22 => '办公用品',
        );


        if (empty($item['invoiceid'])) {
            $user_invoice = array(
                'companyName'     => '不开发票',
                'taxpayersIDcode' => '',
            );

        } else {

            $user_invoice = iunserializer($item['invoice']);

            if (!is_array($user_invoice)) {

                $user_invoice = pdo_fetch(
                    ' SELECT * ' .
                    ' FROM ' . tablename('superdesk_shop_member_invoice') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 不处理
                    ' WHERE ' .
                    '       id = :id ' .
                    '       and uniacid=:uniacid',
                    array(
                        ':id'      => $item['invoiceid'],
                        ':uniacid' => $_W['uniacid'],
                    )
                );

                if (!$user_invoice) {

                }
            }

            $user_invoice['invoice'] = $user_invoice['companyName'] . ' '
                . $user_invoice['taxpayersIDcode'] . ' ['
                . $view_invoiceType[$user_invoice['invoiceType']] . '] ['
                . $view_selectedInvoiceTitle[$user_invoice['selectedInvoiceTitle']] . '] ['
                . $view_invoiceContent[$user_invoice['invoiceContent']] . ']';
        }

        // 发票信息 end

        $diyformfields = '';

        if (p('diyform')) {
            $diyformfields = ',o.diyformfields,o.diyformdata';
        }

        $child_order_arr    = pdo_fetchall(
            ' SELECT id FROM ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' WHERE parentid=:parentid and uniacid=:uniacid',
            array(':parentid' => $id, ':uniacid' => $_W['uniacid'])
        );
        $child_order_id_arr = array_column($child_order_arr, 'id');

        //假如非父单,即无拆分单
        if (empty($child_order_id_arr)) {
            $child_order_id_arr[] = $id;
        }

        $goods = pdo_fetchall(
            ' SELECT ' .
            '       g.*, ' .
            '       o.goodssn as option_goodssn, o.productsn as option_productsn,o.total,' .
            '       g.type,' .
            '       o.optionname,o.optionid,o.price as orderprice,o.realprice,o.changeprice,o.oldprice,' .
            '       o.commission1,o.commission2,o.commission3,o.commissions,o.return_goods_nun' .
            $diyformfields .
            ' FROM ' . tablename('superdesk_shop_order_goods') . ' o ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
            '   left join ' . tablename('superdesk_shop_goods') . ' g on o.goodsid=g.id ' .
            ' WHERE o.orderid in (' . implode(',', $child_order_id_arr) . ') ' .
            '       and o.uniacid=:uniacid',
            array(
                ':uniacid' => $_W['uniacid'],
            )
        );

        $is_merch = false;

        foreach ($goods as &$r) {
            if (!empty($r['option_goodssn'])) {
                $r['goodssn'] = $r['option_goodssn'];
            }


            if (!empty($r['option_productsn'])) {
                $r['productsn'] = $r['option_productsn'];
            }


            $r['marketprice'] = $r['orderprice'] / $r['total'];

            if (p('diyform')) {
                $r['diyformfields'] = iunserializer($r['diyformfields']);
                $r['diyformdata']   = iunserializer($r['diyformdata']);
            }


            if (!empty($r['merchid'])) {
                $is_merch = true;
            }

        }

        unset($r);
        $item['goods'] = $goods;
        $agents        = array();


        $condition = ' o.uniacid=:uniacid and o.deleted=0';
        $paras     = array(':uniacid' => $_W['uniacid']);
        $totals    = array();
        $coupon    = false;
        if (com('coupon') && !empty($item['couponid'])) {
            $coupon = com('coupon')->getCouponByDataID($item['couponid']);
        }


        $order_fields = false;
        $order_data   = false;

        if (p('diyform')) {
            $diyform_set = p('diyform')->getSet();

            foreach ($goods as $g) {
                if (empty($g['diyformdata'])) {
                    continue;
                }
                break;
            }

            if (!empty($item['diyformid'])) {
                $orderdiyformid = $item['diyformid'];

                if (!empty($orderdiyformid)) {
                    $order_fields = iunserializer($item['diyformfields']);
                    $order_data   = iunserializer($item['diyformdata']);
                }

            }

        }


        if (com('verify')) {
            $verifyinfo = iunserializer($item['verifyinfo']);

            if (!empty($item['verifyopenid'])) {

                $saler = m('member')->getMember($item['verifyopenid'], $item['verifycore_user']);

                $saler['salername'] = pdo_fetchcolumn(
                    ' select salername ' .
                    ' from ' . tablename('superdesk_shop_saler') .
                    ' where openid=:openid ' .
                    '       and uniacid=:uniacid ' .
                    ' limit 1 ',
                    array(
                        ':uniacid' => $_W['uniacid'],
                        ':openid'  => $item['verifyopenid'],
                    )
                );
            }


            if (!empty($item['verifystoreid'])) {
                $store = pdo_fetch(
                    ' select * ' .
                    ' from ' . tablename('superdesk_shop_store') .
                    ' where id=:storeid ' .
                    ' limit 1 ',
                    array(
                        ':storeid' => $item['verifystoreid'],
                    )
                );
            }


            if ($item['isverify']) {
                if (is_array($verifyinfo)) {
                    if (empty($item['dispatchtype'])) {
                        foreach ($verifyinfo as &$v) {
                            if ($v['verified'] || ($item['verifytype'] == 1)) {

                                $v['storename'] = pdo_fetchcolumn(
                                    ' select storename ' .
                                    ' from ' . tablename('superdesk_shop_store') .
                                    ' where id=:id ' .
                                    ' limit 1',
                                    array(
                                        ':id' => $v['verifystoreid'],
                                    )
                                );

                                if (empty($v['storename'])) {
                                    $v['storename'] = '总店';
                                }


                                $v['nickname']  = pdo_fetchcolumn(
                                    ' select nickname ' .
                                    ' from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid shop_member 待处理
                                    ' where openid=:openid ' .
                                    '       and uniacid=:uniacid ' .
                                    ' limit 1',
                                    array(
                                        ':openid'  => $v['verifyopenid'],
                                        ':uniacid' => $_W['uniacid'],
                                    )
                                );
                                $v['salername'] = pdo_fetchcolumn(
                                    ' select salername ' .
                                    ' from ' . tablename('superdesk_shop_saler') .
                                    ' where openid=:openid ' .
                                    '       and uniacid=:uniacid ' .
                                    ' limit 1',
                                    array(
                                        ':openid'  => $v['verifyopenid'],
                                        ':uniacid' => $_W['uniacid'],
                                    )
                                );
                            }

                        }

                        unset($v);
                    }

                }

            }

        }

        include $this->template();
    }

    public function editMore()
    {
        global $_W;
        global $_GPC;

        $ids      = $_GPC['ids'];
        $id_array = explode(',', $ids);

        if ($_W['ispost']) {
            $finances = pdo_fetchall('select id,orderid,ordersn from ' . tablename('superdesk_shop_order_finance') . ' WHERE invoice_sn = "" and id in( ' . $ids . ' ) AND uniacid=' . $_W['uniacid']);

            $remark              = $_GPC['remark'];
            $invoice_sn          = $_GPC['invoice_sn'];
            $create_invoice_time = strtotime($_GPC['create_invoice_time']);

            if (empty($invoice_sn)) {
                show_json(0, '发票号不能为空');
            }

            foreach ($finances as $finance) {
                // TODO mark update_superdesk_shop_member
                pdo_update('superdesk_shop_order_finance',
                    array(
                        'status'              => 2,
                        'invoice_sn'          => $invoice_sn,
                        'create_invoice_time' => $create_invoice_time,
                        'remark'              => $remark,
                    ),
                    array(
                        'id' => $finance['id'],
                    )
                );
                plog('order.finance.edit', '批量开票 <br/>订单信息:  财务跟踪ID: ' . $finance['id'] . ' /订单ID:  ' . $finance['orderid'] . '/订单编号:' . $finance['ordersn'] . '/发票号:' . $invoice_sn);

            }

            show_json(1);
        }

        $params = array(':uniacid' => $_W['uniacid']);

        $finance_list = pdo_fetchall(
            ' select ordersn ' .
            ' from ' . tablename('superdesk_shop_order_finance') .
            ' where id in (' . $ids . ') and uniacid=:uniacid',
            $params);

        $ordersn_all = implode(',', array_column($finance_list, 'ordersn'));

        // mark welfare
        $leftJoinSql = '';
        $selectSql   = '';
        switch (SUPERDESK_SHOPV2_MODE_USER) {
            case 1:// 1 超级前台
                $selectSql   = ' core_enterprise.name as enterprise_name ';
                $leftJoinSql = ' left join ' . tablename('superdesk_core_virtualarchitecture') . ' core_enterprise on core_enterprise.id = o.member_enterprise_id ';
                break;
            case 2:// 2 福利商城
                $selectSql   = ' core_enterprise.enterprise_name ';
                $leftJoinSql = ' left join ' . tablename('superdesk_shop_enterprise_user') . ' core_enterprise on core_enterprise.id = o.member_enterprise_id ';
                break;
        }

        $order = pdo_fetch(
            ' select m.realname,' .
            $selectSql .
            ' from ' . tablename('superdesk_shop_order_finance') . ' as ofi ' .
            ' left join ' . tablename('superdesk_shop_order') . 'as o on o.id=ofi.orderid' .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' left join ' . tablename('superdesk_shop_member') . 'as m on m.core_user=o.core_user' . // TODO 标志 楼宇之窗 openid shop_member 已处理
//            ' left join ' . tablename('superdesk_shop_member') . 'as m on m.openid=o.openid and m.core_user=o.core_user' . // TODO 标志 楼宇之窗 openid shop_member 已处理 //出现了换了openid的情况.这个情况还没处理故而先屏蔽
            $leftJoinSql .
            ' where ofi.uniacid=:uniacid and ofi.id=:id',
            array(':uniacid' => $_W['uniacid'], 'id' => $id_array[0])
        );

        include $this->template();
    }

    /**
     *
     * TODO 补丁是错的
     * 应林贝要求添加5月1日之后的微信订单到财务跟踪表
     * http://localhost/superdesk/web/index.php?c=site&a=entry&m=superdesk_shopv2&do=web&r=order.finance.addWechatData
     * https://wxm.avic-s.com/web/index.php?c=site&a=entry&m=superdesk_shopv2&do=web&r=order.finance.addWechatData
     *
     * 三个等级的订单的区别:
     * 一级订单 假如有一级订单 那肯定是有拆单的
     *   isparent=1 parentid=0
     * 二级订单
     *   有一级订单 即有内部拆单的
     *   isparent=0 parentid>0
     *   没有一级订单 即无内部拆单的
     *   isparent=0 parentid=0
     * 三级订单 假如有三级订单 肯定有二级订单
     *   isparent=0 parentid>0
     *
     * 需求: 需要录入所有的二级订单
     *
     * 排除方法:
     * 通过 isparent=0 可以排除掉一级订单
     * 由于(有内部拆单的二级订单)与(三级订单)的条件都是isparent=0 parentid>0 , 所以需要通过其他的条件来排除掉(三级订单)
     *
     * 假如通过链表的方式.连父订单?然后判断父订单的isparent?
     * 一级订单的话 isparent=null
     * 二级订单的话
     *   有内部拆单的 isparent=1
     *   无内部拆单的 isparent=null
     * 三级订单的话 isparent=0
     */
    public function addWechatData()
    {
        //这是v1版本.只录入了一级订单.和无内部拆单的二级订单,是有问题的.一级订单不该录入
//        $order = pdo_fetchall(
//            ' select id,ordersn,merchid ' .
//            ' from ' . tablename('superdesk_shop_order') .
//            ' where createtime > 1525104000 ' .
//            '       and paytype = 21 ' .
//            '       and parentid=0'
//        );


        // v2版本
        // 余额支付
        // 单商户 | 二级订单
        $order_credit_no_parent = pdo_fetchall(
            ' select o.id,o.ordersn,o.merchid ' .
            ' from ' . tablename('superdesk_shop_order') . ' as o ' .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' left join ' . tablename('superdesk_shop_order_finance') . ' as ofi on ofi.orderid=o.id ' .
            ' where o.createtime > 1525104000 ' .
            '       and o.paytype = 1 ' . // 余额支付
            '       and o.isparent=0 ' .
            '       and o.parentid=0 ' .
            '       and ofi.id is null '     //没有财务跟踪记录的
        );

        // v2版本
        // 余额支付
        // 多商户拆分 | 内部拆单 二级订单
        $order_credit_have_parent = pdo_fetchall(
            ' select o.id,o.ordersn,o.merchid ' .
            ' from ' . tablename('superdesk_shop_order') . ' as o ' .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' left join ' . tablename('superdesk_shop_order') . ' as po on po.id=o.parentid ' .
            ' left join ' . tablename('superdesk_shop_order_finance') . ' as ofi on ofi.orderid=o.id ' .
            ' where o.createtime > 1525104000 ' .   //大于5月1日的
            '       and o.paytype = 1 ' .   // 余额支付
            '       and po.isparent = 1 ' .   // 父订单的 po.isparent=1 必定为一级订单 | isparent 为0代表不是1级订单,parentid>0代表有拆分,
            '       and o.isparent = 0 ' .     // 必定是 二级订单
            '       and o.parentid > 0 ' .     // 必定是 二级订单
            '       and ofi.id is null '     //没有财务跟踪记录的
        );

        // v2版本
        // 微信支付
        // 多商户拆分 | 内部拆单 二级订单
        $order_wechat_have_parent = pdo_fetchall(
            ' select o.id,o.ordersn,o.merchid ' .
            ' from ' . tablename('superdesk_shop_order') . ' as o ' .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' left join ' . tablename('superdesk_shop_order') . ' as po on po.id=o.parentid ' .
            ' left join ' . tablename('superdesk_shop_order_finance') . ' as ofi on ofi.orderid=o.id ' .
            ' where o.createtime > 1525104000 ' .   //大于5月1日的
            '       and o.paytype = 21 ' .   // 微信支付
            '       and po.isparent = 1 ' .   // 父订单的 po.isparent=1 必定为一级订单 | isparent 为0代表不是1级订单,parentid>0代表有拆分,
            '       and o.isparent = 0 ' .     // 必定是 二级订单
            '       and o.parentid > 0 ' .     // 必定是 二级订单
            '       and ofi.id is null '     //没有财务跟踪记录的
        );

        // v2版本
        // 微信支付
        // 单商户 | 二级订单
        $order_wechat_no_parent = pdo_fetchall(
            ' select o.id,o.ordersn,o.merchid ' .
            ' from ' . tablename('superdesk_shop_order') . ' as o ' .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' left join ' . tablename('superdesk_shop_order_finance') . ' as ofi on ofi.orderid=o.id ' .
            ' where o.createtime > 1525104000 ' .
            '       and o.paytype = 21 ' . // 微信支付
            '       and o.isparent=0 ' .
            '       and o.parentid=0 ' .
            '       and ofi.id is null '     //没有财务跟踪记录的
        );

        $order = array_merge($order_wechat_have_parent, $order_credit_no_parent, $order_credit_have_parent, $order_wechat_no_parent);

//        print_r($order_wechat_have_parent);
//        print_r($order_credit_no_parent);
//        print_r($order_credit_have_parent);
//        print_r($order);
//        die;

        foreach ($order as $k => $v) {
            $this->_order_financeModel->addOrderFinanceDefaultPressStatusEQ2($v['id'], $v['ordersn'], $v['merchid']);
        }
    }
}

