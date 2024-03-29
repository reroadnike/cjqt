<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

include_once(IA_ROOT . '/addons/superdesk_core/service/UserInfoService.class.php');

class Notice_SuperdeskShopV2Model
{
    public function __construct()
    {

    }

    protected function getUrl($do, $query = NULL)
    {
        $url = mobileUrl($do, $query, true);

        if (strexists($url, '/addons/superdesk_shopv2/')) {
            $url = str_replace('/addons/superdesk_shopv2/', '/', $url);
        }

        if (strexists($url, '/core/mobile/order/')) {
            $url = str_replace('/core/mobile/order/', '/', $url);
        }

        return $url;
    }

    /**
     * 拼团发送订单通知
     *
     * @param string $orderid
     * @param bool   $delRefund
     *
     * @return null
     */
    public function sendTeamMessage($orderid = '0', $delRefund = false)
    {
        global $_W;

        $orderid = intval($orderid);

        if (empty($orderid)) {
            return NULL;
        }

        $order = pdo_fetch(
            ' select * ' .
            ' from ' . tablename('superdesk_shop_groups_order') .
            ' where id=:id limit 1',
            array(':id' => $orderid)
        );

        if (empty($order)) {
            return NULL;
        }

        $openid    = $order['openid'];
        $core_user = $order['core_user'];

        if (intval($order['teamid'])) {

            $url = $this->getUrl('groups/team/detail', array('orderid' => $orderid, 'teamid' => intval($order['teamid'])));
        } else {

            $url = $this->getUrl('groups/orders/detail', array('orderid' => $orderid));
        }

        $order_goods = pdo_fetch(
            ' select * ' .
            ' from ' . tablename('superdesk_shop_groups_goods') .
            ' where uniacid=:uniacid ' .
            '       and id=:id ',
            array(
                ':uniacid' => $_W['uniacid'],
                ':id'      => intval($order['goodid'])
            )
        );

        $goodsprice = ((!empty($order['is_team']) ? number_format($order_goods['groupsprice'], 2) : number_format($order_goods['singleprice'], 2)));

        $price = number_format(($order['price'] - $order['creditmoney']) + $order['freight'], 2);

        $goods = ' (单价: ¥' . $goodsprice . '元 数量: 1 总价: ¥' . $order['price'] . '元); ';

        $orderpricestr = ' ¥' . $price . '元 (包含运费: ¥' . $order['freight'] . '元，积分抵扣: ¥' . $order['creditmoney'] . '元)';

        $member = m('member')->getMember($openid, $core_user);

        $datas = array(
            array('name' => '商城名称', 'value' => $_W['shopset']['shop']['name']),
            array('name' => '粉丝昵称', 'value' => $member['nickname']),
            array('name' => '订单号', 'value' => $order['orderno']),
            array('name' => '订单金额', 'value' => ($order['price'] - $order['creditmoney']) + $order['freight']),
            array('name' => '运费', 'value' => $order['freight']),
            array('name' => '商品详情', 'value' => $goods),
            array('name' => '快递公司', 'value' => $order['expresscom']),
            array('name' => '快递单号', 'value' => $order['expresssn']),
            array('name' => '下单时间', 'value' => date('Y-m-d H:i', $order['createtime'])),
            array('name' => '支付时间', 'value' => date('Y-m-d H:i', $order['paytime'])),
            array('name' => '发货时间', 'value' => date('Y-m-d H:i', $order['sendtime'])),
            array('name' => '收货时间', 'value' => date('Y-m-d H:i', $order['finishtime']))
        );

        $usernotice = unserialize($member['noticeset']);

        if (!is_array($usernotice)) {
            $usernotice = array();
        }

        $set  = $set = m('common')->getSysset();
        $shop = $set['shop'];
        $tm   = $set['notice'];

        if ($delRefund == true) {

            $refundtype = '';

            if ($order['pay_type'] == 'credit') {
                $refundtype = ', 已经退回您的余额账户，请留意查收！';
            } else if ($order['pay_type'] == 'wechat') {
                $refundtype = ', 已经退回您的对应支付渠道（如银行卡，微信钱包等, 具体到账时间请您查看微信支付通知)，请留意查收！';
            } else {
                $refundtype = ', 请联系客服进行退款事项！';
            }

            $msg = array(
                'first'    => array('value' => '您的订单已经完成退款！', 'color' => '#4a5077'),
                'keyword1' => array('title' => '退款金额', 'value' => '¥' . $price . '元', 'color' => '#4a5077'),
                'keyword2' => array('title' => '商品详情', 'value' => $goods . $orderpricestr, 'color' => '#4a5077'),
                'keyword3' => array('title' => '订单编号', 'value' => $order['orderno'], 'color' => '#4a5077'),
                'remark'   => array('value' => '退款金额 ¥' . $price . $refundtype . "\r\n" . ' 【' . $shop['name'] . '】期待您再次购物！', 'color' => '#4a5077')
            );

            $this->sendGroupsNotice(array(
                'openid'  => $openid,
                'tag'     => 'groups_refund',
                'default' => $msg,
                'datas'   => $datas
            ));

            return NULL;
        }

        if ($order['status'] == 1) {

            if ($order['success'] == 1) {

                $order = pdo_fetchall(
                    ' select * ' .
                    ' from ' . tablename('superdesk_shop_groups_order') .
                    ' where teamid = :teamid ' .
                    '       and success = 1 ' .
                    '       and status = 1 ',
                    array(
                        ':teamid' => $order['teamid']
                    )
                );

                $remark = '您参加的拼团已经成功，我们将尽快为您配送~~';

                foreach ($order as $key => $value) {
                    $msg = array(
                        'first'    => array('value' => '您参加的拼团已经成功组团！', 'color' => '#4a5077'),
                        'keyword1' => array('title' => '订单号', 'value' => $value['orderno'], 'color' => '#4a5077'),
                        'keyword2' => array('title' => '时间', 'value' => date('Y-m-d H:i', $value['paytime']), 'color' => '#4a5077'),
                        'keyword3' => array('title' => '商品', 'value' => $order_goods['title'], 'color' => '#4a5077'),
                        'remark'   => array('value' => $remark, 'color' => '#4a5077')
                    );
                    $this->sendGroupsNotice(array(
                            'openid'  => $value['openid'],
                            'tag'     => 'groups_success',
                            'default' => $msg,
                            'datas'   => $datas)
                    );
                }

                $tm = m('common')->getSysset('notice');

                $remarkteam = '拼团成功了，准备发货';

                $msgteam = array(
                    'first'    => array('value' => '拼团已经成功组团！', 'color' => '#4a5077'),
                    'keyword1' => array('title' => '商品信息', 'value' => $goods, 'color' => '#4a5077'),
                    'keyword2' => array('title' => '付款金额', 'value' => $orderpricestr, 'color' => '#4a5077'),
                    'keyword3' => array('title' => '预计发货时间', 'value' => date('Y-m-d H:i:s', time()), 'color' => '#4a5077'),
                    'remark'   => array('value' => $remarkteam, 'color' => '#4a5077')
                );

                $business = explode(',', $tm['openid']);
                $business = array_unique($business);  //2019年6月6日 18:25:53 zjh 去重

                foreach ($business as $value) {
                    $this->sendGroupsNotice(array('openid' => $value, 'tag' => 'groups_teamsend', 'default' => $msgteam, 'datas' => $datas));
                }
                return NULL;
            }

            if ($order['success'] == -1) {

                $order  = pdo_fetchall(
                    ' select * ' .
                    ' from ' . tablename('superdesk_shop_groups_order') .
                    ' where teamid = :teamid ' .
                    '       and success = -1 ' .
                    '       and status = 1 ',
                    array(':teamid' => $order['teamid'])
                );
                $remark = '很抱歉，您所在的拼团为能成功组团，系统会在24小时之内自动退款。如有疑问请联系卖家，谢谢您的参与！';

                $msg = array(
                    'first'    => array('value' => '您参加的拼团组团失败！', 'color' => '#4a5077'),
                    'keyword1' => array('title' => '店铺', 'value' => $shop['name'], 'color' => '#4a5077'),
                    'keyword2' => array('title' => '通知时间', 'value' => date('Y-m-d H:i:s', time()), 'color' => '#4a5077'),
                    'keyword3' => array('title' => '商品', 'value' => $order_goods['title'], 'color' => '#4a5077'),
                    'remark'   => array('value' => $remark, 'color' => '#4a5077')
                );

                foreach ($order as $key => $value) {
                    $this->sendGroupsNotice(array(
                        'openid'  => $value['openid'],
                        'tag'     => 'groups_error',
                        'default' => $msg,
                        'datas'   => $datas
                    ));
                }

                return NULL;
            }

            if ($order['success'] == 0) {

                if (!empty($order['addressid'])) {
                    if ($order['is_team']) {
                        $remark = "\r\n" . '您的订单我们已经收到，请耐心等待其他团员付款~~';
                    } else {
                        $remark = "\r\n" . '您的订单我们已经收到，我们将尽快配送~~';
                    }
                }
                $msg = array(
                    'first'    => array('value' => '您的订单已提交成功！', 'color' => '#4a5077'),
                    'keyword1' => array('title' => '店铺', 'value' => $shop['name'], 'color' => '#4a5077'),
                    'keyword2' => array('title' => '下单时间', 'value' => date('Y-m-d H:i:s', $order['createtime']), 'color' => '#4a5077'),
                    'keyword3' => array('title' => '商品', 'value' => $goods, 'color' => '#4a5077'),
                    'keyword4' => array('title' => '金额', 'value' => $orderpricestr, 'color' => '#4a5077'),
                    'remark'   => array('value' => $remark, 'color' => '#4a5077')
                );

                $this->sendGroupsNotice(array(
                    'openid'  => $openid,
                    'tag'     => 'groups_pay',
                    'default' => $msg,
                    'url'     => $url,
                    'datas'   => $datas
                ));

                if (!$order['is_team']) {
                    $tm = m('common')->getSysset('notice');

                    $remarkteam = '单购订单成功了，准备发货';

                    $msgteam = array(
                        'first'    => array('value' => '单购订单成功了！', 'color' => '#4a5077'),
                        'keyword1' => array('title' => '商品信息', 'value' => $goods, 'color' => '#4a5077'),
                        'keyword2' => array('title' => '付款金额', 'value' => $orderpricestr, 'color' => '#4a5077'),
                        'keyword3' => array('title' => '预计发货时间', 'value' => date('Y-m-d H:i:s', time()), 'color' => '#4a5077'),
                        'remark'   => array('value' => $remarkteam, 'color' => '#4a5077')
                    );

                    $business = explode(',', $tm['openid']);
                    $business = array_unique($business);  //2019年6月6日 18:25:53 zjh 去重
                    foreach ($business as $value) {
                        $this->sendGroupsNotice(array(
                            'openid'  => $value,
                            'tag'     => 'groups_teamsend',
                            'default' => $msgteam,
                            'datas'   => $datas
                        ));
                    }

                    return NULL;
                }
            }
        } else if ($order['status'] == 2) {

            if (!empty($order['addressid'])) {
                $remark = '您的订单已发货，请注意查收！';
            }

            $msg = array(
                'first'    => array('value' => '您的订单已发货！', 'color' => '#4a5077'),
                'keyword1' => array('title' => '店铺', 'value' => $shop['name'], 'color' => '#4a5077'),
                'keyword2' => array('title' => '发货时间', 'value' => date('Y-m-d H:i:s', $order['sendtime']), 'color' => '#4a5077'),
                'keyword3' => array('title' => '商品', 'value' => $order_goods['title'], 'color' => '#4a5077'),
                'keyword4' => array('title' => '快递公司', 'value' => $order['expresscom'], 'color' => '#4a5077'),
                'keyword5' => array('title' => '快递单号', 'value' => $order['expresssn'], 'color' => '#4a5077'),
                'remark'   => array('value' => $remark, 'color' => '#4a5077')
            );

            $this->sendGroupsNotice(array(
                'openid'  => $openid,
                'tag'     => 'groups_send',
                'default' => $msg,
                'datas'   => $datas
            ));

        }
    }

    /**
     * @param array $params
     *
     * @return null
     */
    public function sendGroupsNotice(array $params)
    {
        global $_W;
        global $_GPC;

        $tag    = ((isset($params['tag']) ? $params['tag'] : ''));
        $touser = ((isset($params['openid']) ? $params['openid'] : ''));

        if (empty($touser)) {
            return NULL;
        }

        $tm = $_W['shopset']['notice'];
        if (empty($tm)) {
            $tm = m('common')->getSysset('notice');
        }

        $templateid       = (($tm['is_advanced'] ? $tm[$tag . '_template'] : $tm[$tag]));
        $default_message  = ((isset($params['default']) ? $params['default'] : array()));
        $url              = ((isset($params['url']) ? $params['url'] : ''));
        $account          = ((isset($params['account']) ? $params['account'] : m('common')->getAccount()));
        $datas            = ((isset($params['datas']) ? $params['datas'] : array()));
        $advanced_message = false;

        if ($tm['is_advanced']) {

            if (!empty($tm[$tag . '_close_advanced'])) {
                return NULL;
            }

            if (!empty($templateid)) {
                $advanced_template = pdo_fetch(
                    ' select * ' .
                    ' from ' . tablename('superdesk_shop_member_message_template') .
                    ' where id=:id ' .
                    '       and uniacid=:uniacid ' .
                    ' limit 1',
                    array(
                        ':id'      => $templateid,
                        ':uniacid' => $_W['uniacid']
                    )
                );

                if (!empty($advanced_template)) {

                    $advanced_message = array(
                        'first'  => array('value' => $this->replaceTemplate($advanced_template['first'], $datas), 'color' => $advanced_template['firstcolor']),
                        'remark' => array('value' => $this->replaceTemplate($advanced_template['remark'], $datas), 'color' => $advanced_template['remarkcolor'])
                    );

                    $data = iunserializer($advanced_template['data']);

                    foreach ($data as $d) {
                        $advanced_message[$d['keywords']] = array('value' => $this->replaceTemplate($d['value'], $datas), 'color' => $d['color']);
                    }

                    $ret = m('message')->sendTplNotice($touser, $advanced_template['template_id'], $advanced_message, $url, $account);
                    if (is_error($ret)) {
                        $ret = m('message')->sendCustomNotice($touser, $advanced_message, $url, $account);
                        if (is_error($ret)) {
                            $ret = m('message')->sendCustomNotice($touser, $advanced_message, $url, $account);
                            return NULL;
                        }
                    }
                } else {
                    m('message')->sendCustomNotice($touser, $default_message, $url, $account);
                    return NULL;
                }
            } else {
                m('message')->sendCustomNotice($touser, $default_message, $url, $account);
                return NULL;
            }

        } else if (!empty($tm[$tag . '_close_normal'])) {

            return NULL;

        } else {

            $ret = m('message')->sendTplNotice($touser, $templateid, $default_message, $url, $account);
            if (is_error($ret)) {
                m('message')->sendCustomNotice($touser, $default_message, $url, $account);
            }
        }
    }

    /**
     * 发送订单通知
     *
     * @param string $orderid
     * @param bool   $delRefund
     *
     * @return null
     */
    public function sendOrderMessage($orderid = '0', $delRefund = false)
    {
        global $_W;

        if (empty($orderid)) {
            return NULL;
        }

        $order = pdo_fetch(
            'select * ' .
            ' from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 不处理
            ' where ' .
            '       id=:id ' .
            ' limit 1',
            array(
                ':id' => $orderid
            )
        );

        if (empty($order)) {
            return NULL;
        }

        $is_merch = 0;

        $openid    = $order['openid'];
        $core_user = $order['core_user'];

        $url    = $this->getUrl('order/detail', array('id' => $orderid));
        $appurl = '/pages/order/detail/index?id=' . $orderid;

        $param             = array();
        $param[':uniacid'] = $_W['uniacid'];

        if ($order['isparent'] == 1) {
            $scondition              = ' og.parentorderid=:parentorderid';
            $param[':parentorderid'] = $orderid;
        } else {
            $scondition        = ' og.orderid=:orderid';
            $param[':orderid'] = $orderid;
        }

        $order_goods = pdo_fetchall(
            ' select ' .
            '       g.id,g.title,' .
            '       og.realprice,og.total,og.price,og.optionname as optiontitle,' .
            '       g.noticeopenid,g.noticetype' .
//            '       og.sendtype,'.
//            '       og.expresscom,og.expresssn,og.sendtime '.
            ' from ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods v处理
            '       left join ' . tablename('superdesk_shop_goods') . ' g on g.id=og.goodsid ' .
            ' where ' .
            $scondition .
            ' and og.uniacid=:uniacid ',
            $param
        );

        $goods     = '';
        $goodsname = '';
        $goodsnum  = 0;

        foreach ($order_goods as $og) {
            $goods .= "\n\n" . $og['title'] . '( ';

            if (!empty($og['optiontitle'])) {
                $goods .= ' 规格: ' . $og['optiontitle'];
            }

            $goods     .= ' 单价: ' . ($og['realprice'] / $og['total']) . ' 数量: ' . $og['total'] . ' 总价: ' . $og['realprice'] . '); ';
            $goodsname .= $og['title'] . " \n\n";
            $goodsnum  += $og['total'];
        }

        $orderpricestr = ' 订单总价: ' . $order['price'] . '(包含运费:' . $order['dispatchprice'] . ')';

        $member  = m('member')->getMember($openid, $core_user);
        $carrier = false;
        $store   = false;

        if (!empty($order['storeid'])) {

            if (0 < $order['merchid']) {

                $store = pdo_fetch(
                    'select * from ' . tablename('superdesk_shop_merch_store') .
                    ' where id=:id and uniacid=:uniacid and merchid = :merchid limit 1',
                    array(
                        ':id'      => $order['storeid'],
                        ':uniacid' => $_W['uniacid'],
                        ':merchid' => $order['merchid']
                    )
                );

            } else {
                $store = pdo_fetch(
                    'select * ' .
                    ' from ' . tablename('superdesk_shop_store') .
                    ' where ' .
                    '       id=:id ' .
                    '       and uniacid=:uniacid ' .
                    ' limit 1',
                    array(
                        ':id'      => $order['storeid'],
                        ':uniacid' => $_W['uniacid']
                    )
                );
            }
        }

        $buyerinfo        = '';
        $buyerinfo_name   = '';
        $buyerinfo_mobile = '';
        $addressinfo      = '';

        if (!empty($order['address'])) {

            $address = iunserializer($order['address_send']);

            if (!is_array($address)) {

                $address = iunserializer($order['address']);

                if (!is_array($address)) {

                    $address = pdo_fetch(
                        'select ' .
                        '       id,realname,mobile,address,province,city,area ' .
                        ' from ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 不处理
                        ' where ' .
                        '       id=:id ' .
                        '       and uniacid=:uniacid ' .
                        ' limit 1',
                        array(
                            ':id'      => $order['addressid'],
                            ':uniacid' => $_W['uniacid']
                        )
                    );
                }
            }

            if (!empty($address)) {
                $addressinfo      = $address['province'] . $address['city'] . $address['area'] . ' ' . $address['address'];
                $buyerinfo        = '收件人: ' . $address['realname'] . "\n联系电话: " . $address['mobile'] . "\n收货地址: " . $addressinfo;
                $buyerinfo_name   = $address['realname'];
                $buyerinfo_mobile = $address['mobile'];
            }
        } else {
            $carrier = iunserializer($order['carrier']);

            if (is_array($carrier)) {
                $buyerinfo        = '联系人: ' . $carrier['carrier_realname'] . "\n联系电话: " . $carrier['carrier_mobile'];
                $buyerinfo_name   = $carrier['carrier_realname'];
                $buyerinfo_mobile = $carrier['carrier_mobile'];
            }
        }

        $datas = array(
            array('name' => '商城名称', 'value' => $_W['shopset']['shop']['name']),
            array('name' => '粉丝昵称', 'value' => $member['nickname']),
            array('name' => '订单号', 'value' => $order['ordersn']),
            array('name' => '订单金额', 'value' => $order['price']),
            array('name' => '运费', 'value' => $order['dispatchprice']),
            array('name' => '商品详情', 'value' => $goods),
            array('name' => '快递公司', 'value' => $order['expresscom']),
            array('name' => '快递单号', 'value' => $order['expresssn']),
            array('name' => '购买者姓名', 'value' => $buyerinfo_name),
            array('name' => '购买者电话', 'value' => $buyerinfo_mobile),
            array('name' => '收货地址', 'value' => $addressinfo),
            array('name' => '下单时间', 'value' => date('Y-m-d H:i', $order['createtime'])),
            array('name' => '支付时间', 'value' => date('Y-m-d H:i', $order['paytime'])),
            array('name' => '发货时间', 'value' => date('Y-m-d H:i', $order['sendtime'])),
            array('name' => '收货时间', 'value' => date('Y-m-d H:i', $order['finishtime'])),
            array('name' => '取消时间', 'value' => date('Y-m-d H:i', $order['canceltime'])),
            array('name' => '门店', 'value' => !empty($store) ? $store['storename'] : ''),
            array('name' => '门店地址', 'value' => !empty($store) ? $store['address'] : ''),
            array('name' => '门店联系人', 'value' => !empty($store) ? $store['realname'] . '/' . $store['mobile'] : ''),
            array('name' => '门店营业时间', 'value' => !empty($store) ? (empty($store['saletime']) ? '全天' : $store['saletime']) : ''),
            array('name' => '虚拟物品自动发货内容', 'value' => $order['virtualsend_info']),
            array('name' => '虚拟卡密自动发货内容', 'value' => $order['virtual_str']),
            array('name' => '自提码', 'value' => $order['verifycode']),
            array('name' => '备注信息', 'value' => $order['remark']),
            array('name' => '商品数量', 'value' => $goodsnum),
            array('name' => '商品名称', 'value' => $goodsname),
            array('name' => '项目名称', 'value' => $this->getMemberCoreEnterpriseName($core_user)),
	    
	    //2019年6月20日 11:15:13 zjh 因模板消息整改而调整
            array('name' => '订单编号', 'value' => $order['ordersn']),
            array('name' => '物流公司', 'value' => $order['expresscom']),
            array('name' => '物流单号', 'value' => $order['expresssn']),
            array('name' => '详细地址', 'value' => $addressinfo),
            array('name' => '发货日期', 'value' => date('Y-m-d H:i', $order['sendtime'])),
            array('name' => '订单详情', 'value' => $order_goods[0]['title'] . '....等'),
        );

        $usernotice = unserialize($member['noticeset']);

        if (!is_array($usernotice)) {
            $usernotice = array();
        }

        $set = m('common')->getSysset();

        if (!empty($set)) {
            $shop = $set['shop'];

            if (!empty($shop)) {
                $shopname = $shop['name'];
            }
        }

        $tm = $set['notice'];
        if (!empty($order['merchid']) && p('merch')) {
            $is_merch = 1;
            $merch_tm = p('merch')->getSet('notice', $order['merchid']);
        }

        if ($delRefund) {

            $r_type = array('退款', '退货退款', '换货');

            if (!empty($order['refundid'])) {

                $refund = pdo_fetch(
                    'select * ' .
                    ' from ' . tablename('superdesk_shop_order_refund') .
                    ' where ' .
                    '       id=:id ' .
                    ' limit 1',
                    array(
                        ':id' => $order['refundid']
                    )
                );

                if (empty($refund)) {
                    return NULL;
                }

                $datas[] = array('name' => '售后类型', 'value' => $r_type[$refund['rtype']]);
                $datas[] = array('name' => '申请金额', 'value' => $refund['rtype'] == 2 ? '-' : $refund['applyprice']);
                $datas[] = array('name' => '退款金额', 'value' => $refund['price']);
                $datas[] = array('name' => '换货快递公司', 'value' => $refund['rexpresscom']);
                $datas[] = array('name' => '换货快递单号', 'value' => $refund['rexpresssn']);

                if ($refund['status'] == 5) {

                    if ($refund['rtype'] == 2) {

                        if (empty($address)) {
                            return NULL;
                        }

                        $remark = '<a href=\'' . $url . '\'>点击快速查询物流信息</a>';

                        $text = "您申请换货的宝贝已经成功发货，请注意查收 \n\n订单编号：\n[订单号]\n快递公司：[换货快递公司]\n快递单号：[换货快递单号]\n\n" . $remark;

                        $msg = array(
                            'first'    => array('value' => "您申请换货的宝贝已经成功发货，请注意查收！\n", 'color' => '#ff0000'),
                            'keyword1' => array('title' => '订单编号', 'value' => $order['ordersn'], 'color' => '#000000'),
                            'keyword2' => array('title' => '快递公司', 'value' => $refund['rexpresscom'], 'color' => '#000000'),
                            'keyword3' => array('title' => '快递单号', 'value' => $refund['rexpresssn'], 'color' => '#000000'),
                            'remark'   => array('value' => "\n点击快速查询物流信息", 'color' => '#000000')
                        );

                        $this->sendNotice(array(
                            'openid'     => $openid,
                            'core_user'  => $core_user,
                            'tag'        => 'refund4',
                            'default'    => $msg,
                            'cusdefault' => $text,
                            'url'        => $url,
                            'datas'      => $datas,
                            'appurl'     => $appurl
                        ));

                        com_run('sms::callsms', array(
                            'tag'    => 'refund4',
                            'datas'  => $datas,
                            'mobile' => $member['mobile']
                        ));
                    }
                } else if ($refund['status'] == 3) {

                    if (($refund['rtype'] == 2) || ($refund['rtype'] == 1)) {

                        if (0 < $order['merchid']) {

                            $raddress = iunserializer($refund['refundaddress']);

                            if (!empty($raddress)) {
                                $datas[] = array('name' => '卖家收货地址', 'value' => $raddress['province'] . $raddress['city'] . $raddress['area'] . ' ' . $raddress['address']);
                                $datas[] = array('name' => '卖家联系电话', 'value' => $raddress['mobile']);
                                $datas[] = array('name' => '卖家收货人', 'value' => $raddress['name']);
                            }

                        } else {

                            $salerefund = pdo_fetch(
                                'select * ' .
                                ' from ' . tablename('superdesk_shop_refund_address') . // TODO 标志 楼宇之窗 openid shop_refund_address 不处理
                                ' where ' .
                                '       uniacid=:uniacid ' .
                                '       and isdefault=1 ' .
                                ' limit 1',
                                array(
                                    ':uniacid' => $_W['uniacid']
                                )
                            );

                            $datas[] = array('name' => '卖家收货地址', 'value' => $salerefund['province'] . $salerefund['city'] . $salerefund['area'] . ' ' . $salerefund['address']);
                            $datas[] = array('name' => '卖家联系电话', 'value' => $salerefund['mobile']);
                            $datas[] = array('name' => '卖家收货人', 'value' => $salerefund['name']);
                        }

                        if (!empty($usernotice['refund3'])) {
                            return NULL;
                        }

                        $text = "您好，您的换货申请已经通过，请您及时发送快递。\n\n申请换货订单号：\n[订单号]\n请将快递发送到以下地址，并随包裹填写您的订单编号以及联系方式，我们将尽快为您处理\n邮寄地址：[卖家收货地址]\n联系电话：[卖家联系电话]\n收货人：[卖家收货人]\n\n感谢您关注，如有疑问请联系在线客服或<a href='" . $url . '\'>点击查看详情</a>';

                        $remark2 = "请将快递发送到以下地址，并随包裹填写您的订单编号以及联系方式，我们将尽快为您处理\n\n邮寄地址：" . $salerefund['province'] . $salerefund['city'] . $salerefund['area'] . ' ' . $salerefund['address'] . "\n联系电话：" . $salerefund['mobile'] . "\n收货人：" . $salerefund['name'] . "\n\n感谢您关注，如有疑问请联系在线客服或点击查看详情";

                        $msg = array(
                            'first'    => array('value' => "您好，您的换货申请已经通过，请您及时发送快递。\n", 'color' => '#ff0000'),
                            'keyword1' => array('title' => '任务名称', 'value' => '退换货申请', 'color' => '#000000'),
                            'keyword2' => array('title' => '通知类型', 'value' => '换货通过', 'color' => '#4b9528'),
                            'remark'   => array('value' => $remark2, 'color' => '#000000')
                        );

                        $this->sendNotice(array(
                            'openid'     => $openid,
                            'core_user'  => $core_user,//2019年6月20日 11:15:13 zjh 因模板消息整改而调整
                            'tag'        => 'refund3',
                            'default'    => $msg,
                            'cusdefault' => $text,
                            'url'        => $url,
                            'datas'      => $datas,
                            'appurl'     => $appurl
                        ));

                        com_run('sms::callsms', array(
                            'tag'    => 'refund3',
                            'datas'  => $datas,
                            'mobile' => $member['mobile']
                        ));
                    }

                } else if ($refund['status'] == 1) {

                    if (($refund['rtype'] == 0) || ($refund['rtype'] == 1)) {
                        if (!empty($usernotice['refund1'])) {
                            return NULL;
                        }

                        $refundtype = '';

                        if (empty($refund['refundtype'])) {
                            $refundtype = '余额账户';
                        } else if ($refund['refundtype'] == 1) {
                            $refundtype = '您的对应支付渠道（如银行卡，微信钱包等, 具体到账时间请您查看微信支付通知)';
                        } else {
                            $refundtype = ' 请联系客服进行退款事项！';
                        }

                        $text = "您好，您有一笔退款已经成功，[退款金额].元已经退回您的申请退款账户内，请及时查看 。\n\n订单编号：\n[订单号]\n退款金额：[退款金额]元\n退款原因：[售后类型]\n退款去向：" . $refundtype . "\n\n感谢您关注，如有疑问请联系在线客服或<a href='" . $url . '\'>点击查看详情</a>';
                        $msg  = array(
                            'first'             => array('value' => '您好，您有一笔退款已经成功，' . $refund['price'] . '元已经退回您的申请退款账户内，请及时查看 。', 'color' => '#ff0000'),
                            'orderProductPrice' => array('title' => '退款金额', 'value' => $refund['price'] . '元', 'color' => '#000000'),
                            'orderProductName'  => array('title' => '商品名称', 'value' => str_replace("\n", '', $goodsname), 'color' => '#000000'),
                            'orderName'         => array('title' => '订单编号', 'value' => $order['ordersn'], 'color' => '#000000'),
                            'remark'            => array('value' => "\n感谢您关注，如有疑问请联系在线客服或点击查看详情", 'color' => '#000000')
                        );
                        $this->sendNotice(array(
                            'openid' => $openid,
                            'core_user'  => $core_user,//2019年6月20日 11:15:13 zjh 因模板消息整改而调整
                            'tag' => 'refund1',
                            'default' => $msg,
                            'cusdefault' => $text,
                            'url' => $url,
                            'datas' => $datas,
                            'appurl' => $appurl
                        ));
                        com_run('sms::callsms', array('tag' => 'refund1', 'datas' => $datas, 'mobile' => $member['mobile']));
                    }
                } else {

                    if ($refund['status'] == -1) {

                        if (!empty($usernotice['refund2'])) {
                            return NULL;
                        }

                        $remark  = "\n感谢您关注，如有疑问请联系在线客服或<a href='" . $url . '\'>点击查看详情</a>';
                        $text    = '您好，你那有一笔' . $r_type[$refund['rtype']] . "被驳回，您可以与我们取得联系！\n\n退款金额：[申请金额]元\n订单编号：\n[订单号]\n" . $remark;
                        $remark2 = '商品详情：' . substr_replace(str_replace("\n\n", "\n", $goodsname), '', strrpos($goodsname, "\n"), strlen("\n")) . '订单编号：' . $order['ordersn'] . "\n退款金额：" . ($refund['rtype'] == 2 ? '-' : $refund['applyprice']) . '元' . "\n\n感谢您关注，如有疑问请联系在线客服或点击查看详情";
                        $msg     = array(
                            'first'    => array('value' => '您好，你有一笔' . $r_type[$refund['rtype']] . "被驳回，您可以与我们取得联系！\n", 'color' => '#ff0000'),
                            'keyword1' => array('title' => '任务名称', 'value' => '退换货申请', 'color' => '#000000'),
                            'keyword2' => array('title' => '通知类型', 'value' => '驳回通知', 'color' => '#ff0000'),
                            'remark'   => array('value' => $remark2, 'color' => '#000000')
                        );
                        $this->sendNotice(array(
                            'openid'     => $openid,
                            'core_user'  => $core_user,//2019年6月20日 11:15:13 zjh 因模板消息整改而调整
                            'tag'        => 'refund2',
                            'default'    => $msg,
                            'cusdefault' => $text,
                            'url'        => $url,
                            'datas'      => $datas,
                            'mobile'     => $buyerinfo_mobile,
                            'appurl'     => $appurl
                        ));

                        com_run('sms::callsms', array(
                            'tag'    => 'refund2',
                            'datas'  => $datas,
                            'mobile' => $member['mobile']
                        ));
                    }
                }
            }

            return NULL;
        }

        if ($order['status'] == -1) {

            if (!empty($usernotice['cancel'])) {
                return NULL;
            }

            $remark = '，或<a href=\'' . $url . '\'>点击查看详情</a>';

            $text = "您好，您的订单由于主动取消或长时间未付款已经关闭！！！\n\n商品名称：" . substr_replace($goodsname, '', strrpos($goodsname, "\n\n"), strlen("\n\n")) . "\n订单编号：\n[订单号]\n订单金额：[订单金额]\n下单时间：[下单时间]\n关闭时间：[取消时间]\n\n感谢您的关注，如有疑问请联系在线客服咨询" . $remark;
            $msg  = array(
                'first'    => array('value' => '您好，您的订单由于主动取消或长时间未付款已经关闭！！！', 'color' => '#ff0000'),
                'keyword1' => array('title' => '订单商品', 'value' => substr_replace($goodsname, '', strrpos($goodsname, "\n\n"), strlen("\n\n")), 'color' => '#000000'),
                'keyword2' => array('title' => '订单编号', 'value' => $order['ordersn'], 'color' => '#000000'),
                'keyword3' => array('title' => '下单时间', 'value' => date('Y-m-d H:i', $order['createtime']), 'color' => '#000000'),
                'keyword4' => array('title' => '订单金额', 'value' => $order['price'], 'color' => '#000000'),
                'keyword5' => array('title' => '关闭时间', 'value' => date('Y-m-d H:i', $order['canceltime']), 'color' => '#000000'),
                'remark'   => array('value' => "\n感谢您关注，如有疑问请联系在线客服或点击查看详情！", 'color' => '#000000')
            );

            $this->sendNotice(array(
                'openid'     => $openid,
                'core_user'  => $core_user,//2019年6月20日 11:15:13 zjh 因模板消息整改而调整
                'tag'        => 'cancel',
                'default'    => $msg,
                'cusdefault' => $text,
                'url'        => $url,
                'datas'      => $datas,
                'mobile'     => $buyerinfo_mobile,
                'appurl'     => $appurl
            ));

            com_run('sms::callsms', array(
                'tag'    => 'cancel',
                'datas'  => $datas,
                'mobile' => $member['mobile']
            ));

        } else {

            if (($order['status'] == 0) && ($order['paytype'] == 3)) {

                $is_send = 0;

                if (empty($is_merch)) {
                    if (empty($usernotice['saler_pay'])) {
                        $is_send = 1;
                    }
                } else {
                    if (!empty($merch_tm) && empty($merch_tm['saler_pay_close_advanced'])) {
                        $is_send      = 1;
                        $tm['openid'] = $merch_tm['openid'];
                    }
                }

                $buyText = "您的订单已经成功支付，我们将尽快为您安排发货！！ \n\n订单号：\n[订单号]\n商品名称：\n[商品名称]商品数量：[商品数量]\n下单时间：[下单时间]\n订单金额：[订单金额]\n" . $remark . $cusurl;

                $buyMsg = array(
                    'first'    => array('value' => '您的订单已于' . date('Y-m-d H:i', $order['paytime']) . "成功支付，我们将尽快为您安排发货！!\n", 'color' => '#4b9528'),
                    'keyword1' => array('title' => '订单编号', 'value' => $order['ordersn'], 'color' => '#000000'),
                    'keyword2' => array('title' => '商品名称', 'value' => substr_replace($goodsname, "\n", strrpos($goodsname, "\n\n"), strlen("\n\n")), 'color' => '#000000'),
                    'keyword3' => array('title' => '商品数量', 'value' => $goodsnum, 'color' => '#000000'),
                    'keyword4' => array('title' => '支付金额', 'value' => $order['price'], 'color' => '#000000'),
                    'remark'   => array('value' => $remark, 'color' => '#000000')
                );

                //2019年6月20日 11:15:13 zjh 因模板消息整改而调整
                $datas[] = array('name' => '头部标题', 'value' => '订单编号 ' . $order['ordersn']);
                $datas[] = array('name' => '订单详情', 'value' => '');
                $datas[] = array('name' => '尾部描述', 'value' => '');

                $this->sendNotice(array(
                    'openid'     => $openid,
                    'core_user'  => $core_user,//2019年6月20日 11:15:13 zjh 因模板消息整改而调整
                    'tag'        => 'pay',
                    'default'    => $buyMsg,
                    'cusdefault' => $buyText,
                    'url'        => $url,
                    'datas'      => $datas,
                    'appurl'     => $appurl
                ));

                if (!empty($is_send)) {

                    $msg = array(
                        'first'    => array('value' => '您有新的企业月结订单于' . date('Y-m-d H:i', $order['createtime']) . "已下单！！\n请登录后台查看详情并及时安排发货。", 'color' => '#ff0000'),
                        'keyword1' => array('title' => '订单编号', 'value' => $order['ordersn'], 'color' => '#000000'),
                        'keyword2' => array('title' => '商品名称', 'value' => $goods, 'color' => '#000000'),
                        'keyword3' => array('title' => '商品数量', 'value' => $goodsnum, 'color' => '#000000'),
                        'keyword4' => array('title' => '支付金额', 'value' => $order['price'], 'color' => '#000000')
                    );

                    $text = "您有新的企业月结订单！！\n请及时安排发货。\n\n订单号：\n[订单号]\n订单金额：[订单金额]\n下单时间：[下单时间]\n---------------------\n购买商品信息：[商品详情]\n备注信息：[备注信息]\n---------------------\n收货人：[购买者姓名]\n收货人电话:[购买者电话]\n收货地址:[收货地址]\n\n请及时安排发货";

                    $account = m('common')->getAccount();

                    if (!empty($tm['openid'])) {
                        $openids = explode(',', $tm['openid']);
                        $openids = array_unique($openids);  //2019年6月6日 18:25:53 zjh 去重

                        foreach ($openids as $tmopenid) {
                            if (empty($tmopenid)) {
                                continue;
                            }

                            $this->sendNotice(array(
                                'openid'     => $tmopenid,
//                                'core_user'  => $core_user,//2019年6月20日 11:15:13 zjh 因模板消息整改而调整
                                'tag'        => 'saler_pay',
                                'default'    => $msg,
                                'cusdefault' => $text,
                                'datas'      => $datas,
                                'is_merch'   => $is_merch,
                                'merch_tm'   => $merch_tm
                            ));
                        }
                    }
                }

                if (!empty($tm['mobile']) && empty($tm['saler_pay_close_sms']) && empty($is_merch)) {

                    $mobiles = explode(',', $tm['mobile']);

                    foreach ($mobiles as $mobile) {
                        if (empty($mobile)) {
                            continue;
                        }

                        com_run('sms::callsms', array(
                            'tag'    => 'saler_pay',
                            'datas'  => $datas,
                            'mobile' => $mobile
                        ));
                    }
                }

                $i = 0;

                foreach ($order_goods as $og) {

                    if (!empty($og['noticeopenid']) && !empty($og['noticetype'])) {

                        $noticetype = explode(',', $og['noticetype']);

                        if (($og['noticetype'] == '1') || (is_array($noticetype) && in_array('1', $noticetype))) {

                            ++$i;
                            $goodstr = $og['title'] . '( ';

                            if (!empty($og['optiontitle'])) {
                                $goodstr     .= ' 规格: ' . $og['optiontitle'];
                                $optiontitle = '( 规格: ' . $og['optiontitle'] . ')';
                            }

                            $goodstr .= ' 单价: ' . ($og['price'] / $og['total']) . ' 数量: ' . $og['total'] . ' 总价: ' . $og['price'] . '); ';

                            $text = "您有新的企业月结订单！！\n请及时安排发货。\n\n订单号：\n[订单号]\n订单金额：[订单金额]\n下单时间：[下单时间]\n---------------------\n购买商品信息：[单品详情]\n备注信息：[备注信息]\n---------------------\n收货人：[购买者姓名]\n收货人电话:[购买者电话]\n收货地址:[收货地址]\n\n请及时安排发货";

                            $remark = "订单号：\n" . $order['ordersn'] . "\n商品详情：" . $goodstr;

                            $msg                 = array(
                                'first'    => array('value' => '您有新的企业月结订单于' . date('Y-m-d H:i', $order['createtime']) . "已下单！！\n请登录后台查看详情并及时安排发货。\n", 'color' => '#ff0000'),
                                'keyword1' => array('title' => '任务名称', 'value' => '商品付款通知', 'color' => '#000000'),
                                'keyword2' => array('title' => '通知类型', 'value' => '已付款', 'color' => '#000000'),
                                'remark'   => array('value' => $remark, 'color' => '#000000')
                            );
                            $datas['gooddetail'] = array('name' => '单品详情', 'value' => $goodstr);
                            $noticeopenids       = explode(',', $og['noticeopenid']);

                            foreach ($noticeopenids as $noticeopenid) {
                                $this->sendNotice(array(
                                    'openid'     => $noticeopenid,
//                                    'core_user'  => $core_user,//2019年6月20日 11:15:13 zjh 因模板消息整改而调整
                                    'tag'        => 'saler_goodpay',
                                    'cusdefault' => $text,
                                    'default'    => $msg,
                                    'datas'      => $datas
                                ));
                            }
                        }
                    }
                }
            } else {

                if (($order['status'] == 1) && !empty($order['istrade'])) {

                    $item = pdo_fetch(
                        ' select og.trade_time,g.title,s.storename,s.mobile,p.nickname,s.id as storeid ' .
                        ' from ' . tablename('superdesk_shop_order_goods') .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
                        ' left join ' . tablename('superdesk_shop_order') . ' o  on  og.orderid = o.id  ' .// TODO 标志 楼宇之窗 openid shop_order 不处理
                        ' left join ' . tablename('superdesk_shop_goods') . ' g  on  og.goodsid = g.id ' .
                        ' left join ' . tablename('superdesk_shop_store') . ' s  on  o.storeid = s.id ' .
                        ' left join ' . tablename('superdesk_shop_newstore_people') . ' p  on  og.peopleid = p.id ' .
                        ' where o.id =:id ' .
                        ' limit 1',
                        array(
                            ':id' => $order['id']
                        )
                    );

                    $datas[] = array('name' => '预约商品名称', 'value' => $item['title']);
                    $datas[] = array('name' => '预约时间', 'value' => date('Y-m-d H:i:s', $item['trade_time']));
                    $datas[] = array('name' => '预约门店', 'value' => $item['storename']);
                    $datas[] = array('name' => '技师名称', 'value' => $item['nickname']);
                    $datas[] = array('name' => '门店联系电话', 'value' => $item['mobile']);
                    $notice  = m('common')->getSysset('notice', false);

                    if (empty($notice['o2o_bnorder_close_advanced'])) {

                        $url = $this->getUrl('newstore/norder/detail', array('id' => $order['id']));

                        $text = "您已成功预约！\n预约项目： [预约商品名称] \n预约时间：[预约时间] \n预约门店：[预约门店]\n服务人：[技师名称]\n联系电话：[门店联系电话]\n为了完美的服务体验，请提前安排好您的时间，如您需要取消预约或修改预约时间,请拨打[门店联系电话]进行咨询！\n<a href='" . $url . '\'>点击快速查询预约信息</a>';

                        $msg = array(
                            'first'    => array('value' => "您已成功预约！\n", 'color' => '#ff0000'),
                            'keyword1' => array('title' => '预约项目', 'value' => $item['title'], 'color' => '#000000'),
                            'keyword2' => array('title' => '预约时间', 'value' => date('Y-m-d H:i:s', $item['trade_time']), 'color' => '#4b9528'),
                            'remark'   => array('value' => '预约门店：' . $item['storename'] . "\n服务人：" . $item['nickname'] . "\n联系电话" . $item['mobile'] . "\n为了完美的服务体验，请提前安排好您的时间，如您需要取消预约或修改预约时间,请拨打" . $item['mobile'] . '进行咨询！', 'color' => '#000000')
                        );

                        $this->sendNotice(array(
                            'openid'     => $openid,
                            'core_user'  => $core_user,//2019年6月20日 11:15:13 zjh 因模板消息整改而调整
                            'tag'        => 'o2o_bnorder',
                            'default'    => $msg,
                            'cusdefault' => $text,
                            'url'        => $url,
                            'datas'      => $datas
                        ));

                        com_run('sms::callsms', array(
                            'tag'    => 'o2o_bverify',
                            'datas'  => $datas,
                            'mobile' => $member['mobile']
                        ));
                    }

                    if (empty($notice['o2o_snorder_close_advanced'])) {

                        $member  = m('member')->getMember($openid, $core_user);
                        $datas[] = array('name' => '预约人姓名', 'value' => $member['nickname']);
                        $datas[] = array('name' => '预约人联系电话', 'value' => $member['mobile']);
                        $text    = "您有一个新的预约！\n预约项目： [预约商品名称] \n预约时间：[预约时间] \n预约门店：[预约门店]\n服务人：[技师名称]\n预约人：[技师名称]\n联系电话：[预约人联系电话]\n如有变动可登录管理后台进行操作！！";
                        $msg     = array(
                            'first'    => array('value' => "您有一个新的预约！\n", 'color' => '#ff0000'),
                            'keyword1' => array('title' => '预约项目', 'value' => $item['title'], 'color' => '#000000'),
                            'keyword2' => array('title' => '预约时间', 'value' => date('Y-m-d H:i:s', $item['trade_time']), 'color' => '#4b9528'),
                            'remark'   => array('value' => '预约门店：' . $item['storename'] . "\n服务人：" . $item['nickname'] . "\n联系电话:" . $item['mobile'] . "\n如有变动可登录管理后台进行操作！！", 'color' => '#000000')
                        );

                        $salers = pdo_fetchall(
                            ' select * ' .
                            ' from ' . tablename('superdesk_shop_saler') . ' sr ' .
                            '       inner join ' . tablename('superdesk_shop_store') . ' s on s.id = sr.storeid  ' .
                            ' where  s.id=:id and sr.getnotice =1',
                            array(':id' => $item['storeid']));

                        foreach ($salers as $saler) {

                            $this->sendNotice(array(
                                'openid'     => $saler['openid'],
                                'core_user'  => $saler['core_user'],//2019年6月20日 11:15:13 zjh 因模板消息整改而调整
                                'tag'        => 'o2o_snorder',
                                'default'    => $msg,
                                'cusdefault' => $text,
                                'url'        => '',
                                'datas'      => $datas
                            ));

                            com_run('sms::callsms', array(
                                'tag'    => 'o2o_bverify',
                                'datas'  => $datas,
                                'mobile' => $saler['mobile']
                            ));
                        }
                    }

                } else {

                    if (($order['status'] == 1) && empty($order['sendtype'])) {

                        $is_send = 0;

                        if (empty($is_merch)) {
                            if (empty($usernotice['saler_pay'])) {
                                $is_send = 1;
                            }
                        } else {
                            if (!empty($merch_tm) && empty($merch_tm['saler_pay_close_advanced'])) {
                                $is_send      = 1;
                                $tm['openid'] = $merch_tm['openid'];
                            }
                        }

                        if (!empty($is_send)) {
                            $msg     = array(
                                'first'    => array('value' => '您有新的订单于' . date('Y-m-d H:i', $order['paytime']) . "已付款！！\n请登录后台查看详情并及时安排发货。", 'color' => '#ff0000'),
                                'keyword1' => array('title' => '订单编号', 'value' => $order['ordersn'], 'color' => '#000000'),
                                'keyword2' => array('title' => '商品名称', 'value' => $goods, 'color' => '#000000'),
                                'keyword3' => array('title' => '商品数量', 'value' => $goodsnum, 'color' => '#000000'),
                                'keyword4' => array('title' => '支付金额', 'value' => $order['price'], 'color' => '#000000')
                            );
                            $text    = "您有新的已付款订单！！\n
                            请及时安排发货。\n\n
                            订单号：\n[订单号]\n订单金额：[订单金额]\n支付时间：[支付时间]\n---------------------\n购买商品信息：[商品详情]\n备注信息：[备注信息]\n---------------------\n收货人：[购买者姓名]\n收货人电话:[购买者电话]\n收货地址:[收货地址]\n\n请及时安排发货";
                            $account = m('common')->getAccount();

                            if (!empty($tm['openid'])) {
                                $openids = explode(',', $tm['openid']);
                                $openids = array_unique($openids);  //2019年6月6日 18:25:53 zjh 去重

                                foreach ($openids as $tmopenid) {

                                    if (empty($tmopenid)) {
                                        continue;
                                    }

                                    $this->sendNotice(array(
                                        'openid'     => $tmopenid,
//                                        'core_user'  => $core_user,//2019年6月20日 11:15:13 zjh 因模板消息整改而调整
                                        'tag'        => 'saler_pay',
                                        'default'    => $msg,
                                        'cusdefault' => $text,
                                        'datas'      => $datas,
                                        'is_merch'   => $is_merch,
                                        'merch_tm'   => $merch_tm
                                    ));
                                }
                            }
                        }

                        if (!empty($tm['mobile']) && empty($tm['saler_pay_close_sms']) && empty($is_merch)) {

                            $mobiles = explode(',', $tm['mobile']);

                            foreach ($mobiles as $mobile) {

                                if (empty($mobile)) {
                                    continue;
                                }

                                com_run('sms::callsms', array(
                                    'tag'    => 'saler_pay',
                                    'datas'  => $datas,
                                    'mobile' => $mobile
                                ));
                            }
                        }

                        $i = 0;

                        foreach ($order_goods as $og) {
                            if (!empty($og['noticeopenid']) && !empty($og['noticetype'])) {

                                $noticetype = explode(',', $og['noticetype']);

                                if (($og['noticetype'] == '1') || (is_array($noticetype) && in_array('1', $noticetype))) {

                                    ++$i;
                                    $goodstr = $og['title'] . '( ';

                                    if (!empty($og['optiontitle'])) {
                                        $goodstr     .= ' 规格: ' . $og['optiontitle'];
                                        $optiontitle = '( 规格: ' . $og['optiontitle'] . ')';
                                    }

                                    $goodstr .= ' 单价: ' . ($og['price'] / $og['total']) . ' 数量: ' . $og['total'] . ' 总价: ' . $og['price'] . '); ';

                                    $text = "您有新的已付款订单！！\n请及时安排发货。\n\n订单号：\n[订单号]\n订单金额：[订单金额]\n支付时间：[支付时间]\n---------------------\n购买商品信息：[单品详情]\n备注信息：[备注信息]\n---------------------\n收货人：[购买者姓名]\n收货人电话:[购买者电话]\n收货地址:[收货地址]\n\n请及时安排发货";

                                    $remark = "订单号：\n" . $order['ordersn'] . "\n商品详情：" . $goodstr;

                                    $msg = array(
                                        'first'    => array('value' => '您有新的订单于' . date('Y-m-d H:i', $order['paytime']) . "已付款！！\n请登录后台查看详情并及时安排发货。\n", 'color' => '#ff0000'),
                                        'keyword1' => array('title' => '任务名称', 'value' => '商品付款通知', 'color' => '#000000'),
                                        'keyword2' => array('title' => '通知类型', 'value' => '已付款', 'color' => '#000000'),
                                        'remark'   => array('value' => $remark, 'color' => '#000000')
                                    );

                                    $datas['gooddetail'] = array('name' => '单品详情', 'value' => $goodstr);

                                    $noticeopenids = explode(',', $og['noticeopenid']);


                                    foreach ($noticeopenids as $noticeopenid) {

                                        $this->sendNotice(array(
                                            'openid'     => $noticeopenid,
//                                            'core_user'  => $core_user,//2019年6月20日 11:15:13 zjh 因模板消息整改而调整
                                            'tag'        => 'saler_goodpay',
                                            'cusdefault' => $text,
                                            'default'    => $msg,
                                            'datas'      => $datas
                                        ));
                                    }
                                }
                            }
                        }

                        if (empty($usernotice['pay'])) {

                            $remark = "\n";

                            if ($order['isverify']) {
                                $remark = "\n点击订单详情查看可消费门店, 【" . $shopname . "】欢迎您的再次购物！\n";
                            } else {
                                if ($order['dispatchtype']) {
                                    $remark = "\n您可以到选择的自提点进行取货了,【" . $shopname . "】欢迎您的再次购物！\n";
                                }
                            }

                            $cusurl = '<a href=\'' . $url . '\'>点击查看详情</a>';

                            $text = "您的订单已经成功支付，我们将尽快为您安排发货！！ \n\n订单号：\n[订单号]\n商品名称：\n[商品名称]商品数量：[商品数量]\n下单时间：[下单时间]\n订单金额：[订单金额]\n" . $remark . $cusurl;

                            $msg = array(
                                'first'    => array('value' => '您的订单已于' . date('Y-m-d H:i', $order['paytime']) . "成功支付，我们将尽快为您安排发货！!\n", 'color' => '#4b9528'),
                                'keyword1' => array('title' => '订单编号', 'value' => $order['ordersn'], 'color' => '#000000'),
                                'keyword2' => array('title' => '商品名称', 'value' => substr_replace($goodsname, "\n", strrpos($goodsname, "\n\n"), strlen("\n\n")), 'color' => '#000000'),
                                'keyword3' => array('title' => '商品数量', 'value' => $goodsnum, 'color' => '#000000'),
                                'keyword4' => array('title' => '支付金额', 'value' => $order['price'], 'color' => '#000000'),
                                'remark'   => array('value' => $remark, 'color' => '#000000')
                            );

                            //2019年6月20日 11:15:13 zjh 因模板消息整改而调整
                            $datas[] = array('name' => '头部标题', 'value' => '订单编号 ' . $order['ordersn']);
                            $datas[] = array('name' => '订单详情', 'value' => '');
                            $datas[] = array('name' => '尾部描述', 'value' => '');

                            $this->sendNotice(array(
                                'openid'     => $openid,
                                'core_user'  => $core_user,//2019年6月20日 11:15:13 zjh 因模板消息整改而调整
                                'tag'        => 'pay',
                                'default'    => $msg,
                                'cusdefault' => $text,
                                'url'        => $url,
                                'datas'      => $datas,
                                'appurl'     => $appurl
                            ));
                            com_run('sms::callsms', array(
                                'tag'    => 'pay',
                                'datas'  => $datas,
                                'mobile' => !empty($buyerinfo_mobile) && $order['isverify'] ? $buyerinfo_mobile : $member['mobile']
                            ));
                        }

                        if (($order['dispatchtype'] == 1) && empty($order['isverify'])) {
                            if (!empty($usernotice['carrier'])) {
                                return NULL;
                            }

                            if (!$carrier || !$store) {
                                return NULL;
                            }

                            $remark = "\n请您到选择的自提点进行取货, 自提联系人: " . $store['realname'] . ' 联系电话: ' . $store['mobile'] . "\n\n<a href='" . $url . '\'>点击查看详情</a>';

                            $text = "自提订单提交成功!！\n自提码：[自提码]\n商品详情：[商品详情]\n提货地址：[门店地址]\n提货时间：[门店营业时间]\n" . $remark;

                            $msg = array(
                                'first'    => array('value' => '自提订单提交成功!', 'color' => '#000000'),
                                'keyword1' => array('title' => '自提码', 'value' => $order['verifycode'], 'color' => '#000000'),
                                'keyword2' => array('title' => '商品详情', 'value' => $goods . $orderpricestr, 'color' => '#000000'),
                                'keyword3' => array('title' => '提货地址', 'value' => $store['address'], 'color' => '#000000'),
                                'keyword4' => array('title' => '提货时间', 'value' => $store['saletime'], 'color' => '#000000'),
                                'remark'   => array('value' => "\n请您到选择的自提点进行取货, 自提联系人: " . $store['realname'] . ' 联系电话: ' . $store['mobile'], 'color' => '#000000')
                            );

                            $this->sendNotice(array(
                                'openid'     => $openid,
                                'core_user'  => $core_user,//2019年6月20日 11:15:13 zjh 因模板消息整改而调整
                                'tag'        => 'carrier',
                                'default'    => $msg,
                                'cusdefault' => $text,
                                'url'        => $url,
                                'datas'      => $datas,
                                'appurl'     => $appurl
                            ));
                            com_run('sms::callsms', array(
                                'tag'    => 'carrier',
                                'datas'  => $datas,
                                'mobile' => !empty($buyerinfo_mobile) ? $buyerinfo_mobile : $member['mobile']
                            ));

                        }

                    } else {

                        if (($order['status'] == 2) || (($order['status'] == 1) && !empty($order['sendtype']))) {

                            $isonlyverify = m('order')->checkisonlyverifygoods($orderid);

                            if (empty($order['dispatchtype']) && !$isonlyverify) {

                                if (!empty($usernotice['send'])) {
                                    return NULL;
                                }

                                $datas[] = array('name' => '发货类型', 'value' => empty($order['sendtype']) ? '按订单发货' : '按包裹发货');

                                if (empty($order['sendtype'])) {
                                    if (empty($address)) {
                                        return NULL;
                                    }

                                    $remark          = '<a href=\'' . $url . '\'>点击快速查询物流信息</a>';
                                    $text            = "您的宝贝已经成功发货！ \n商品名称：[商品详情]\n快递公司：[快递公司]\n快递单号：[快递单号]\n";
                                    $msg             = array();
                                    $msg['first']    = array('value' => '您的宝贝已经发货！', 'color' => '#000000');
                                    $msg['keyword1'] = array('title' => '订单编号', 'value' => $order['ordersn'], 'color' => '#000000');
                                    $msg['keyword2'] = array('title' => '快递公司', 'value' => $order['expresscom'], 'color' => '#000000');
                                    $msg['keyword3'] = array('title' => '快递单号', 'value' => $order['expresssn'], 'color' => '#000000');
                                    $remark_value    = '';

                                    if (0 < $order['merchid']) {
                                        $merch_user = p('merch')->getListUserOne($order['merchid']);

                                        if (!empty($merch_user['mobile'])) {
                                            $datas[]      = array('name' => '商户电话', 'value' => $merch_user['mobile']);
                                            $text         .= "\n商户电话：[商户电话]";
                                            $remark_value .= "\n商户电话：" . $merch_user['mobile'];
                                        }

                                        if (!empty($merch_user['address'])) {
                                            $datas[]      = array('name' => '商户地址', 'value' => $merch_user['address']);
                                            $text         .= "\n商户地址：[商户地址]";
                                            $remark_value .= "\n商户地址：" . $merch_user['address'];
                                        }
                                    }

                                    $text          .= $remark;
                                    $remark_value  .= "\n我们正加速送到您的手上，请您耐心等候";
                                    $msg['remark'] = array('value' => $remark_value, 'color' => '#000000');

                                    //2019年6月20日 11:15:13 zjh 因模板消息整改而调整
                                    $datas[] = array('name' => '头部标题', 'value' => $member['realname'] . ',您购买的商品已经发货了 ');
                                    $datas[] = array('name' => '物流费用', 'value' => '');
                                    $datas[] = array('name' => '尾部描述', 'value' => '');

                                    $this->sendNotice(array(
                                        'openid'     => $openid,
                                        'core_user'  => $core_user,//2019年6月20日 11:15:13 zjh 因模板消息整改而调整
                                        'tag'        => 'send',
                                        'default'    => $msg,
                                        'cusdefault' => $text,
                                        'url'        => $url,
                                        'datas'      => $datas,
                                        'appurl'     => $appurl
                                    ));

                                    com_run('sms::callsms', array(
                                        'tag'    => 'send',
                                        'datas'  => $datas,
                                        'mobile' => $member['mobile']
                                    ));

                                } else {

                                    $package_goods       = array();
                                    $package_expresscom  = '';
                                    $package_expresssn   = '';
                                    $package_sendtime    = '';
                                    $package_goodsdetail = '';
                                    $package_goodsname   = '';

                                    foreach ($order_goods as $og) {

                                        if ($og['sendtype'] == $order['sendtype']) {

                                            $package_goods[] = $og;

                                            if (empty($package_expresscom)) {
                                                $package_expresscom = $og['expresscom'];
                                            }

                                            if (empty($package_expresssn)) {
                                                $package_expresssn = $og['expresssn'];
                                            }

                                            if (empty($package_sendtime)) {
                                                $package_sendtime = $og['sendtime'];
                                            }

                                            $package_goodsdetail .= "\n\n" . $og['title'] . '( ';

                                            if (!empty($og['optiontitle'])) {
                                                $package_goodsdetail .= ' 规格: ' . $og['optiontitle'];
                                            }

                                            $package_goodsdetail .= ' 单价: ' . ($og['realprice'] / $og['total']) . ' 数量: ' . $og['total'] . ' 总价: ' . $og['realprice'] . '); ';
                                            $package_goodsname   .= $og['title'] . " \n\n";
                                        }
                                    }

                                    if (empty($package_goods)) {
                                        return NULL;
                                    }

                                    $datas[] = array('name' => '包裹快递公司', 'value' => $package_expresscom);
                                    $datas[] = array('name' => '包裹快递单号', 'value' => $package_expresssn);
                                    $datas[] = array('name' => '包裹发送时间', 'value' => date('Y-m-d H:i', $package_sendtime));
                                    $datas[] = array('name' => '包裹商品详情', 'value' => $package_goodsdetail);
                                    $datas[] = array('name' => '包裹商品名称', 'value' => $package_goodsname);
                                    $remark  = '<a href=\'' . $url . '\'>点击快速查询物流信息</a>';
                                    $text    = "您的包裹已经成功发货！ \n商品名称：[包裹商品名称]快递公司：[包裹快递公司]\n快递单号：[包裹快递单号]\n" . $remark;
                                    $msg     = array(
                                        'first'    => array('value' => '您的包裹已经发货！', 'color' => '#000000'),
                                        'keyword1' => array('title' => '订单编号', 'value' => $order['ordersn'], 'color' => '#000000'),
                                        'keyword2' => array('title' => '快递公司', 'value' => $package_expresscom, 'color' => '#000000'),
                                        'keyword3' => array('title' => '快递单号', 'value' => $package_expresssn, 'color' => '#000000'),
                                        'remark'   => array('value' => "\n我们正加速送到您的手上，请您耐心等候。", 'color' => '#000000')
                                    );
                                    $this->sendNotice(array(
                                        'openid'     => $openid,
                                        'core_user'  => $core_user,//2019年6月20日 11:15:13 zjh 因模板消息整改而调整
                                        'tag'        => 'send',
                                        'default'    => $msg,
                                        'cusdefault' => $text,
                                        'url'        => $url,
                                        'datas'      => $datas,
                                        'appurl'     => $appurl
                                    ));

                                    com_run('sms::callsms', array(
                                        'tag'    => 'send',
                                        'datas'  => $datas,
                                        'mobile' => $member['mobile']
                                    ));
                                }
                            }

                            if ($isonlyverify) {
                                $is_send = 0;

                                if (empty($is_merch)) {
                                    $is_send = 1;
                                } else {
                                    if (!empty($merch_tm) && empty($merch_tm['saler_pay_close_advanced'])) {
                                        $is_send      = 1;
                                        $tm['openid'] = $merch_tm['openid'];
                                    }
                                }

                                if (!empty($is_send)) {

                                    $msg = array(
                                        'first'    => array('value' => '您有新的记次时商品订单于' . date('Y-m-d H:i', $order['paytime']) . "已付款！\n请登录后台查看详情。", 'color' => '#ff0000'),
                                        'keyword1' => array('title' => '订单编号', 'value' => $order['ordersn'], 'color' => '#000000'),
                                        'keyword2' => array('title' => '商品名称', 'value' => $goods, 'color' => '#000000'),
                                        'keyword3' => array('title' => '商品数量', 'value' => $goodsnum, 'color' => '#000000'),
                                        'keyword4' => array('title' => '支付金额', 'value' => $order['price'], 'color' => '#000000')
                                    );

                                    $text = "您有新的已付款记次时商品订单！\n请登录后台查看详情。\n\n订单号：\n[订单号]\n订单金额：[订单金额]\n支付时间：[支付时间]\n---------------------\n购买商品信息：[商品详情]\n备注信息：[备注信息]";

                                    $account = m('common')->getAccount();

                                    if (!empty($tm['openid'])) {
                                        $openids = explode(',', $tm['openid']);
                                        $openids = array_unique($openids);  //2019年6月6日 18:25:53 zjh 去重

                                        foreach ($openids as $tmopenid) {

                                            if (empty($tmopenid)) {
                                                continue;
                                            }

                                            $this->sendNotice(array(
                                                'openid'     => $tmopenid,
//                                                'core_user'  => $core_user,//2019年6月20日 11:15:13 zjh 因模板消息整改而调整
                                                'tag'        => 'saler_pay',
                                                'default'    => $msg,
                                                'cusdefault' => $text,
                                                'datas'      => $datas,
                                                'is_merch'   => $is_merch,
                                                'merch_tm'   => $merch_tm
                                            ));
                                        }
                                    }
                                }

                                if (!empty($tm['mobile']) && empty($tm['saler_pay_close_sms']) && empty($is_merch)) {

                                    $mobiles = explode(',', $tm['mobile']);

                                    foreach ($mobiles as $mobile) {
                                        if (empty($mobile)) {
                                            continue;
                                        }

                                        com_run('sms::callsms', array(
                                            'tag'    => 'saler_pay',
                                            'datas'  => $datas,
                                            'mobile' => $mobile
                                        ));
                                    }
                                }

                                $i = 0;

                                foreach ($order_goods as $og) {
                                    if (!empty($og['noticeopenid']) && !empty($og['noticetype'])) {
                                        $noticetype = explode(',', $og['noticetype']);
                                        if (($og['noticetype'] == '1') || (is_array($noticetype) && in_array('1', $noticetype))) {
                                            ++$i;
                                            $goodstr = $og['title'] . '( ';

                                            if (!empty($og['optiontitle'])) {
                                                $goodstr     .= ' 规格: ' . $og['optiontitle'];
                                                $optiontitle = '( 规格: ' . $og['optiontitle'] . ')';
                                            }

                                            $goodstr .= ' 单价: ' . ($og['price'] / $og['total']) . ' 数量: ' . $og['total'] . ' 总价: ' . $og['price'] . '); ';
                                            $text    = "您有新的已付款记次时商品订单！！\n请及时安排发货。\n\n订单号：\n[订单号]\n订单金额：[订单金额]\n支付时间：[支付时间]\n---------------------\n购买商品信息：[单品详情]\n备注信息：[备注信息]";
                                            $remark  = "订单号：\n" . $order['ordersn'] . "\n商品详情：" . $goodstr;

                                            $msg = array(
                                                'first'    => array('value' => '您有新的记次时商品订单于' . date('Y-m-d H:i', $order['paytime']) . "已付款！！\n请登录后台查看详情。\n", 'color' => '#ff0000'),
                                                'keyword1' => array('title' => '任务名称', 'value' => '商品付款通知', 'color' => '#000000'),
                                                'keyword2' => array('title' => '通知类型', 'value' => '已付款', 'color' => '#000000'),
                                                'remark'   => array('value' => $remark, 'color' => '#000000')
                                            );

                                            $datas['gooddetail'] = array('name' => '单品详情', 'value' => $goodstr);

                                            $noticeopenids = explode(',', $og['noticeopenid']);

                                            foreach ($noticeopenids as $noticeopenid) {

                                                $this->sendNotice(
                                                    array(
                                                        'openid'     => $noticeopenid,
//                                                        'core_user'  => $core_user,//2019年6月20日 11:15:13 zjh 因模板消息整改而调整
                                                        'tag'        => 'saler_goodpay',
                                                        'cusdefault' => $text,
                                                        'default'    => $msg,
                                                        'datas'      => $datas
                                                    )
                                                );

                                            }
                                        }
                                    }
                                }
                            }

                        } else {

                            if ($order['status'] == 3) {

                                $pv = com('virtual');

                                if ($pv && !empty($order['virtual'])) {

                                    if (empty($usernotice['virtualsend'])) {

                                        $text = "您的商品已购买成功，以下为您的购物信息。\n\n商品名称:" . str_replace("\n", '', $goodsname) . "\n订单金额：[订单金额]\n卡密信息：<a href='" . $url . '\'>请点击查看</a>';
                                        $msg  = array(
                                            'first'    => array('value' => '您的商品已购买成功，以下为您的购物信息。', 'color' => '#4b9528'),
                                            'keyword1' => array('title' => '商品名称', 'value' => str_replace("\n", '', $goodsname), 'color' => '#000000'),
                                            'keyword2' => array('title' => '订单号', 'value' => $order['ordersn'], 'color' => '#000000'),
                                            'keyword3' => array('title' => '订单金额', 'value' => '¥' . $order['price'] . '元', 'color' => '#000000'),
                                            'keyword4' => array('title' => '卡密信息', 'value' => '点击查看详情', 'color' => '#ff0000')
                                        );

                                        $this->sendNotice(
                                            array(
                                                'openid'     => $openid,
                                                'core_user'  => $core_user,//2019年6月20日 11:15:13 zjh 因模板消息整改而调整
                                                'tag'        => 'virtualsend',
                                                'default'    => $msg,
                                                'cusdefault' => $text,
                                                'url'        => $url,
                                                'datas'      => $datas,
                                                'appurl'     => $appurl
                                            )
                                        );

                                        com_run(
                                            'sms::callsms',
                                            array(
                                                'tag'    => 'virtualsend',
                                                'datas'  => $datas,
                                                'mobile' => !empty($buyerinfo_mobile) ? $buyerinfo_mobile : $member['mobile']
                                            )
                                        );
                                    }

                                    $first = "买家购买的商品已经自动发货!\n";

                                    $remark = '订单号：' . $order['ordersn'] . "\n收货时间：" . date('Y-m-d H:i', $order['finishtime']) . "\n商品详情：" . $goods . "\n\n购买者信息:\n" . $buyerinfo;

                                    $text = $first . "\n订单号：" . $order['ordersn'] . "\n收货时间：" . date('Y-m-d H:i', $order['finishtime']) . "\n商品详情：" . $goods . "\n\n购买者信息:\n" . $buyerinfo;

                                    $is_send = 0;

                                    if (empty($is_merch)) {
                                        if (empty($usernotice['saler_finish'])) {
                                            $is_send = 1;
                                        }
                                    } else {
                                        if (!empty($merch_tm) && empty($merch_tm['saler_finish_close_advanced'])) {
                                            $is_send       = 1;
                                            $tm['openid2'] = $merch_tm['openid2'];
                                        }
                                    }

                                    if (!empty($is_send)) {

                                        $msg = array(
                                            'first'    => array('value' => $first, 'color' => '#4b9528'),
                                            'keyword1' => array('title' => '任务名称', 'value' => '订单收货通知', 'color' => '#000000'),
                                            'keyword2' => array('title' => '通知类型', 'value' => '虚拟物品及卡密自动发货', 'color' => '#000000'),
                                            'remark'   => array('title' => '', 'value' => $remark, 'color' => '#000000')
                                        );

                                        $account = m('common')->getAccount();

                                        if (!empty($tm['openid2'])) {
                                            $openids = explode(',', $tm['openid2']);

                                            foreach ($openids as $tmopenid) {
                                                if (empty($tmopenid)) {
                                                    continue;
                                                }

                                                $this->sendNotice(array(
                                                    'openid'     => $tmopenid,
//                                                    'core_user'  => $core_user,//2019年6月20日 11:15:13 zjh 因模板消息整改而调整
                                                    'tag'        => 'saler_finish',
                                                    'cusdefault' => $text,
                                                    'default'    => $msg,
                                                    'datas'      => $datas,
                                                    'is_merch'   => $is_merch,
                                                    'merch_tm'   => $merch_tm
                                                ));
                                            }
                                        }
                                    }

                                    if (!empty($tm['mobile2']) && empty($tm['saler_finish_close_sms'])) {

                                        $mobiles = explode(',', $tm['mobile2']);

                                        foreach ($mobiles as $mobile) {
                                            if (empty($mobile)) {
                                                continue;
                                            }

                                            com_run('sms::callsms',
                                                array(
                                                    'tag'    => 'saler_finish',
                                                    'datas'  => $datas,
                                                    'mobile' => $mobile
                                                ));
                                        }
                                    }

                                    foreach ($order_goods as $og) {

                                        $noticetype = explode(',', $og['noticetype']);

                                        if (($og['noticetype'] == '2') || (is_array($noticetype) && in_array('2', $noticetype))) {

                                            $goodstr = $og['title'] . '( ';

                                            if (!empty($og['optiontitle'])) {
                                                $goodstr .= ' 规格: ' . $og['optiontitle'];
                                            }

                                            $goodstr .= ' 单价: ' . ($og['price'] / $og['total']) . ' 数量: ' . $og['total'] . ' 总价: ' . $og['price'] . '); ';

                                            $remark = '订单号：' . $order['ordersn'] . "\n收货时间：" . date('Y-m-d H:i', $order['finishtime']) . "\n商品详情：" . $goodstr . "\n\n购买者信息:\n" . $buyerinfo;

                                            $text = $first . "\n订单号：" . $order['ordersn'] . "\n收货时间：" . date('Y-m-d H:i', $order['finishtime']) . "\n商品详情：" . $goodstr . "\n\n购买者信息:\n" . $buyerinfo;

                                            $msg = array(
                                                'first'    => array('value' => $first, 'color' => '#4b9528'),
                                                'keyword1' => array('title' => '任务名称', 'value' => '订单收货通知', 'color' => '#000000'),
                                                'keyword2' => array('title' => '通知类型', 'value' => '虚拟物品及卡密自动发货', 'color' => '#000000'),
                                                'remark'   => array('title' => '', 'value' => $remark, 'color' => '#000000')
                                            );

                                            $datas[] = array('name' => '单品详情', 'value' => $goodstr);

                                            $noticeopenids = explode(',', $og['noticeopenid']);

                                            foreach ($noticeopenids as $noticeopenid) {

                                                $this->sendNotice(
                                                    array(
                                                        'openid'     => $noticeopenid,
//                                                        'core_user'  => $core_user,//2019年6月20日 11:15:13 zjh 因模板消息整改而调整
                                                        'tag'        => 'saler_finish',
                                                        'cusdefault' => $text,
                                                        'default'    => $msg,
                                                        'datas'      => $datas
                                                    )
                                                );

                                            }
                                        }
                                    }
                                } else if ($order['isvirtualsend']) {

                                    if (empty($usernotice['virtualsend'])) {

                                        $text = "您的商品已购买成功，以下为您的购物信息。\n\n商品名称:" . str_replace("\n", '', $goodsname) . "\n订单金额：[订单金额]\n卡密信息：<a href='" . $url . '\'> 点击查看</a>';

                                        $msg = array(
                                            'first'    => array('value' => '您的商品已购买成功，以下为您的购物信息。', 'color' => '#4b9528'),
                                            'keyword1' => array('title' => '商品名称', 'value' => str_replace("\n", '', $goodsname), 'color' => '#000000'),
                                            'keyword2' => array('title' => '订单号', 'value' => $order['ordersn'], 'color' => '#000000'),
                                            'keyword3' => array('title' => '订单金额', 'value' => '¥' . $order['price'] . '元', 'color' => '#000000'),
                                            'keyword4' => array('title' => '卡密信息', 'value' => '点击查看详情', 'color' => '#ff0000')
                                        );

                                        $this->sendNotice(array(
                                            'openid'     => $openid,
                                            'core_user'  => $core_user,//2019年6月20日 11:15:13 zjh 因模板消息整改而调整
                                            'tag'        => 'virtualsend',
                                            'default'    => $msg,
                                            'cusdefault' => $text,
                                            'url'        => $url,
                                            'datas'      => $datas,
                                            'appurl'     => $appurl
                                        ));

                                        com_run('sms::callsms',
                                            array(
                                                'tag'    => 'virtualsend',
                                                'datas'  => $datas,
                                                'mobile' => !empty($buyerinfo_mobile) ? $buyerinfo_mobile : $member['mobile']
                                            ));
                                    }

                                    $first = "买家购买的商品已经自动发货!\n";

                                    $remark = '订单号：' . $order['ordersn'] . "\n收货时间：" . date('Y-m-d H:i', $order['finishtime']) . "\n商品详情：" . $goods . "\n\n购买者信息:\n" . $buyerinfo;

                                    $text = $first . "\n订单号：" . $order['ordersn'] . "\n收货时间：" . date('Y-m-d H:i', $order['finishtime']) . "\n商品详情：" . $goods . "\n\n购买者信息:\n" . $buyerinfo;

                                    $is_send = 0;

                                    if (empty($is_merch)) {

                                        if (empty($usernotice['saler_finish'])) {
                                            $is_send = 1;
                                        }

                                    } else {

                                        if (!empty($merch_tm) && empty($merch_tm['saler_finish_close_advanced'])) {
                                            $is_send       = 1;
                                            $tm['openid2'] = $merch_tm['openid2'];
                                        }

                                    }

                                    if (!empty($is_send)) {

                                        $msg = array(
                                            'first'    => array('value' => $first, 'color' => '#4b9528'),
                                            'keyword1' => array('title' => '任务名称', 'value' => '订单收货通知', 'color' => '#000000'),
                                            'keyword2' => array('title' => '通知类型', 'value' => '商品自动发货', 'color' => '#000000'),
                                            'remark'   => array('title' => '', 'value' => $remark, 'color' => '#000000')
                                        );

                                        $account = m('common')->getAccount();


                                        if (!empty($tm['openid2'])) {
                                            $openids = explode(',', $tm['openid2']);

                                            foreach ($openids as $tmopenid) {
                                                if (empty($tmopenid)) {
                                                    continue;
                                                }

                                                $this->sendNotice(array(
                                                    'openid'     => $tmopenid,
//                                                    'core_user'  => $core_user,//2019年6月20日 11:15:13 zjh 因模板消息整改而调整
                                                    'tag'        => 'saler_finish',
                                                    'cusdefault' => $text,
                                                    'default'    => $msg,
                                                    'datas'      => $datas,
                                                    'is_merch'   => $is_merch,
                                                    'merch_tm'   => $merch_tm
                                                ));
                                            }
                                        }
                                    }

                                    if (!empty($tm['mobile2']) && empty($tm['saler_finish_close_sms'])) {

                                        $mobiles = explode(',', $tm['mobile2']);

                                        foreach ($mobiles as $mobile) {
                                            if (empty($mobile)) {
                                                continue;
                                            }

                                            com_run(
                                                'sms::callsms',
                                                array(
                                                    'tag'    => 'saler_finish',
                                                    'datas'  => $datas,
                                                    'mobile' => $mobile
                                                )
                                            );
                                        }
                                    }

                                    foreach ($order_goods as $og) {

                                        $noticetype = explode(',', $og['noticetype']);

                                        if (($og['noticetype'] == '2') || (is_array($noticetype) && in_array('2', $noticetype))) {

                                            $goodstr = $og['title'] . '( ';

                                            if (!empty($og['optiontitle'])) {
                                                $goodstr .= ' 规格: ' . $og['optiontitle'];
                                            }

                                            $goodstr .= ' 单价: ' . ($og['price'] / $og['total']) . ' 数量: ' . $og['total'] . ' 总价: ' . $og['price'] . '); ';

                                            $remark = '订单号：' . $order['ordersn'] . "\n收货时间：" . date('Y-m-d H:i', $order['finishtime']) . "\n商品详情：" . $goodstr . "\n\n购买者信息:\n" . $buyerinfo;

                                            $text = $first . "\n订单号：" . $order['ordersn'] . "\n收货时间：" . date('Y-m-d H:i', $order['finishtime']) . "\n商品详情：" . $goodstr . "\n\n购买者信息:\n" . $buyerinfo;

                                            $msg = array(
                                                'first'    => array('value' => $first, 'color' => '#4b9528'),
                                                'keyword1' => array('title' => '任务名称', 'value' => '订单收货通知', 'color' => '#000000'),
                                                'keyword2' => array('title' => '通知类型', 'value' => '虚拟物品及卡密自动发货', 'color' => '#000000'),
                                                'remark'   => array('title' => '', 'value' => $remark, 'color' => '#000000')
                                            );

                                            $datas[] = array('name' => '单品详情', 'value' => $goodstr);

                                            $noticeopenids = explode(',', $og['noticeopenid']);

                                            foreach ($noticeopenids as $noticeopenid) {

                                                $this->sendNotice(array(
                                                    'openid'     => $noticeopenid,
//                                                    'core_user'  => $core_user,//2019年6月20日 11:15:13 zjh 因模板消息整改而调整
                                                    'tag'        => 'saler_finish',
                                                    'cusdefault' => $text,
                                                    'default'    => $msg,
                                                    'datas'      => $datas
                                                ));
                                            }
                                        }
                                    }

                                } else {

                                    $first = "买家购买的商品已经确认收货!\n";

                                    if ($order['isverify'] == 1) {
                                        $first = "买家购买的商品已经确认核销!\n";
                                    }

                                    $text   = $first . "\n订单号：\n" . $order['ordersn'] . "\n收货时间：" . date('Y-m-d H:i', $order['finishtime']) . "\n商品详情：" . $goods;
                                    $remark = '订单号：' . $order['ordersn'] . "\n收货时间：" . date('Y-m-d H:i', $order['finishtime']) . "\n商品详情：" . $goods;

                                    if (!empty($buyerinfo)) {
                                        $remark = $remark . "\n购买者信息:\n" . $buyerinfo;
                                        $text   = $text . "\n\n购买者信息:\n" . $buyerinfo;
                                    }

                                    $is_send = 0;

                                    if (empty($is_merch)) {
                                        if (empty($usernotice['saler_finish'])) {
                                            $is_send = 1;
                                        }
                                    } else {
                                        if (!empty($merch_tm) && empty($merch_tm['saler_finish_close_advanced'])) {
                                            $is_send       = 1;
                                            $tm['openid2'] = $merch_tm['openid2'];
                                        }
                                    }

                                    if (!empty($is_send)) {

                                        $msg = array(
                                            'first'    => array('value' => $first, 'color' => '#4b9528'),
                                            'keyword1' => array('title' => '任务名称', 'value' => '订单收货通知', 'color' => '#000000'),
                                            'keyword2' => array('title' => '通知类型', 'value' => '商品确认收货', 'color' => '#000000'),
                                            'remark'   => array('title' => '', 'value' => $remark, 'color' => '#000000')
                                        );

                                        $account = m('common')->getAccount();

                                        if (!empty($tm['openid2'])) {
                                            $openids = explode(',', $tm['openid2']);

                                            foreach ($openids as $tmopenid) {
                                                if (empty($tmopenid)) {
                                                    continue;
                                                }

                                                $this->sendNotice(array(
                                                    'openid'     => $tmopenid,
//                                                    'core_user'  => $core_user,//2019年6月20日 11:15:13 zjh 因模板消息整改而调整
                                                    'tag'        => 'saler_finish',
                                                    'cusdefault' => $text,
                                                    'default'    => $msg,
                                                    'datas'      => $datas,
                                                    'is_merch'   => $is_merch,
                                                    'merch_tm'   => $merch_tm));
                                            }
                                        }
                                    }

                                    if (!empty($tm['mobile2']) && empty($tm['saler_finish_close_sms']) && empty($is_merch)) {

                                        $mobiles = explode(',', $tm['mobile2']);

                                        foreach ($mobiles as $mobile) {

                                            if (empty($mobile)) {
                                                continue;
                                            }

                                            com_run('sms::callsms', array(
                                                'tag'    => 'saler_finish',
                                                'datas'  => $datas,
                                                'mobile' => $mobile
                                            ));
                                        }
                                    }

                                    foreach ($order_goods as $og) {

                                        $noticetype = explode(',', $og['noticetype']);

                                        if (($og['noticetype'] == '2') || (is_array($noticetype) && in_array('2', $noticetype))) {

                                            $goodstr = $og['title'] . '( ';

                                            if (!empty($og['optiontitle'])) {
                                                $goodstr .= ' 规格: ' . $og['optiontitle'];
                                            }

                                            $goodstr .= ' 单价: ' . ($og['price'] / $og['total']) . ' 数量: ' . $og['total'] . ' 总价: ' . $og['price'] . '); ';
                                            $remark  =
                                                '订单号：' . $order['ordersn'] .
                                                "\n收货时间：" . date('Y-m-d H:i', $order['finishtime']) .
                                                "\n商品详情：" . $goods;

                                            $text =
                                                $first .
                                                "\n订单号：\n" . $order['ordersn'] .
                                                "\n收货时间：" . date('Y-m-d H:i', $order['finishtime']) .
                                                "\n商品详情：" . $goods;

                                            if (!empty($buyerinfo)) {
                                                $remark = $remark . "\n购买者信息:\n" . $buyerinfo;
                                                $text   = $text . "\n\n购买者信息:\n" . $buyerinfo;
                                            }

                                            $msg = array(
                                                'first'    => array('value' => $first, 'color' => '#4b9528'),
                                                'keyword1' => array('title' => '任务名称', 'value' => '订单收货通知', 'color' => '#000000'),
                                                'keyword2' => array('title' => '通知类型', 'value' => '虚拟物品及卡密自动发货', 'color' => '#000000'),
                                                'remark'   => array('title' => '', 'value' => $remark, 'color' => '#000000')
                                            );

                                            $datas[] = array('name' => '单品详情', 'value' => $goodstr);

                                            $noticeopenids = explode(',', $og['noticeopenid']);

                                            foreach ($noticeopenids as $noticeopenid) {

                                                $this->sendNotice(
                                                    array(
                                                        'openid'     => $noticeopenid,
//                                                        'core_user'  => $core_user,//2019年6月20日 11:15:13 zjh 因模板消息整改而调整
                                                        'tag'        => 'saler_finish',
                                                        'cusdefault' => $text,
                                                        'default'    => $msg,
                                                        'datas'      => $datas
                                                    )
                                                );

                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }


    /**
     * 会员升级提醒
     *
     * @param string $openid
     * @param null   $oldlevel
     * @param null   $level
     *
     * @return null
     */
    public function sendMemberUpgradeMessage($openid = '', $core_user = 0, $oldlevel = NULL, $level = NULL)
    {
        global $_W;
        global $_GPC;

        $member = m('member')->getMember($openid, $core_user);

        $detailurl = $this->getUrl('member');

        $usernotice = unserialize($member['noticeset']);

        if (!is_array($usernotice)) {
            $usernotice = array();
        }

        if (!empty($usernotice['upgrade'])) {
            return NULL;
        }

        if (!$level) {
            $level = m('member')->getLevel($openid, $core_user);
        }

        $oldlevelname = ((empty($oldlevel['levelname']) ? '普通会员' : $oldlevel['levelname']));

        $message = array(
            'first'    => array('value' => '亲爱的' . $member['nickname'] . ', 恭喜您成功升级！', 'color' => '#4a5077'),
            'keyword1' => array('title' => '任务名称', 'value' => '会员升级', 'color' => '#4a5077'),
            'keyword2' => array('title' => '通知类型', 'value' => '您会员等级从 ' . $oldlevelname . ' 升级为 ' . $level['levelname'] . ', 特此通知!', 'color' => '#4a5077'),
            'remark'   => array('value' => "\r\n" . '您即可享有' . $level['levelname'] . '的专属优惠及服务！', 'color' => '#4a5077')
        );

        $datas = array(
            array('name' => '商城名称', 'value' => $_W['shopset']['shop']['name']),
            array('name' => '粉丝昵称', 'value' => $member['nickname']),
            array('name' => '旧等级', 'value' => $oldlevelname),
            array('name' => '新等级', 'value' => $level['levelname'])
        );

        $this->sendNotice(array(
            'openid'  => $openid,
            'tag'     => 'upgrade',
            'default' => $message,
            'url'     => $detailurl,
            'datas'   => $datas
        ));

        com_run(
            'sms::callsms',
            array(
                'tag'    => 'upgrade',
                'datas'  => $datas,
                'mobile' => $member['mobile']
            )
        );

    }

    /**
     * @param string $log_id
     *
     * @return null
     */
    public function sendMemberLogMessage($log_id = '')
    {
        global $_W;
        global $_GPC;

        $log_info = pdo_fetch(
            ' select * ' .
            ' from ' . tablename('superdesk_shop_member_log') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_log 不处理
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':id'      => $log_id,
                ':uniacid' => $_W['uniacid']
            )
        );

        $member = m('member')->getMember($log_info['openid'], $log_info['core_user']);

        $usernotice = unserialize($member['noticeset']);

        if (!is_array($usernotice)) {
            $usernotice = array();
        }

        $account = m('common')->getAccount();

        if (!$account) {
            return NULL;
        }

        $datas = array(
            array('name' => '商城名称', 'value' => $_W['shopset']['shop']['name']),
            array('name' => '粉丝昵称', 'value' => $member['nickname'])
        );

        $log_info['gives'] = floatval($log_info['gives']);
        $log_info['money'] = floatval($log_info['money']);

        if ($log_info['type'] == 0) {

            $type = '后台充值';

            if ($log_info['rechargetype'] == 'wechat') {

                $type = '微信支付';

            } else if ($log_info['rechargetype'] == 'alipay') {

                $type = '支付宝';

            }

            $datas[] = array('name' => '支付方式', 'value' => $type);
            $datas[] = array('name' => '充值金额', 'value' => $log_info['money']);
            $datas[] = array('name' => '充值时间', 'value' => date('Y-m-d H:i', $log_info['createtime']));
            $datas[] = array('name' => '赠送金额', 'value' => $log_info['gives']);
            $datas[] = array('name' => '到帐金额', 'value' => $log_info['money'] + $log_info['gives']);
            $datas[] = array('name' => '实际到账', 'value' => $log_info['money'] + $log_info['gives']);
            $datas[] = array('name' => '退款金额', 'value' => $log_info['money'] + $log_info['gives']);

            if ($log_info['status'] == 1) {

                if (!empty($usernotice['recharge_ok'])) {
                    return NULL;
                }

                $money = '¥' . $log_info['money'] . '元';
                if (0 < $log_info['gives']) {
                    $totalmoney = $log_info['money'] + $log_info['gives'];
                    $money      .= '，系统赠送' . $log_info['gives'] . '元，合计:' . $totalmoney . '元';
                }
                $message = array(
                    'first'   => array('value' => '恭喜您充值成功!', 'color' => '#4a5077'),
                    'money'   => array('title' => '充值金额', 'value' => '¥' . $log_info['money'] . '元', 'color' => '#4a5077'),
                    'product' => array('title' => '充值方式', 'value' => $type, 'color' => '#4a5077'),
                    'remark'  => array('value' => "\r\n" . '谢谢您对我们的支持！', 'color' => '#4a5077')
                );

                $this->sendNotice(array(
                    'openid'  => $log_info['openid'],
                    'tag'     => 'recharge_ok',
                    'default' => $message,
                    'url'     => $this->getUrl('member'),
                    'datas'   => $datas
                ));

                com_run(
                    'sms::callsms',
                    array(
                        'tag'    => 'recharge_ok',
                        'datas'  => $datas,
                        'mobile' => $member['mobile']
                    )
                );

                return NULL;
            }

            if ($log_info['status'] == 3) {

                if (!empty($usernotice['recharge_fund'])) {
                    return NULL;
                }

                $message = array(
                    'first'  => array('value' => '充值退款成功!', 'color' => '#4a5077'),
                    'reason' => array('title' => '退款原因', 'value' => '【' . $_W['shopset']['shop']['name'] . '】充值退款', 'color' => '#4a5077'),
                    'refund' => array('title' => '退款金额', 'value' => '¥' . $log_info['money'] . '元', 'color' => '#4a5077'),
                    'remark' => array('value' => "\r\n" . '退款成功，请注意查收! 谢谢您对我们的支持！', 'color' => '#4a5077')
                );
                $this->sendNotice(array(
                    'openid'  => $log_info['openid'],
                    'tag'     => 'recharge_refund',
                    'default' => $message,
                    'url'     => $this->getUrl('member'),
                    'datas'   => $datas
                ));

                com_run(
                    'sms::callsms',
                    array(
                        'tag'    => 'recharge_refund',
                        'datas'  => $datas,
                        'mobile' => $member['mobile']
                    )
                );

                return NULL;

            }

        } else if ($log_info['type'] == 1) {

            $datas[] = array('name' => '提现金额', 'value' => $log_info['money']);
            $datas[] = array('name' => '提现时间', 'value' => date('Y-m-d H:i', $log_info['createtime']));

            if ($log_info['deductionmoney'] == 0) {
                $realmoeny = $log_info['money'];
            } else {
                $realmoeny = $log_info['realmoney'];
            }

            if ($log_info['status'] == 0) {

                if (!empty($usernotice['withdraw'])) {
                    return NULL;
                }

                $message = array(
                    'first'  => array('value' => '提现申请已经成功提交!', 'color' => '#4a5077'),
                    'money'  => array('title' => '提现金额/到账金额', 'value' => '¥' . $log_info['money'] . '元/¥' . $realmoeny . '元', 'color' => '#4a5077'),
                    'timet'  => array('title' => '提现时间', 'value' => date('Y-m-d H:i:s', $log_info['createtime']), 'color' => '#4a5077'),
                    'remark' => array('value' => "\r\n" . '请等待我们的审核并打款！', 'color' => '#4a5077')
                );

                $this->sendNotice(array(
                    'openid'  => $log_info['openid'],
                    'tag'     => 'withdraw',
                    'default' => $message,
                    'url'     => $this->getUrl('member/log', array('type' => 1)),
                    'datas'   => $datas
                ));

                com_run(
                    'sms::callsms',
                    array(
                        'tag'    => 'withdraw',
                        'datas'  => $datas,
                        'mobile' => $member['mobile']
                    )
                );

                return NULL;
            }

            if ($log_info['status'] == 1) {

                if (!empty($usernotice['withdraw_ok'])) {
                    return NULL;
                }

                $message = array(
                    'first'  => array('value' => '恭喜您成功提现!', 'color' => '#4a5077'),
                    'money'  => array('title' => '提现金额/到账金额', 'value' => '¥' . $log_info['money'] . '元/¥' . $realmoeny . '元', 'color' => '#4a5077'),
                    'timet'  => array('title' => '提现时间', 'value' => date('Y-m-d H:i:s', $log_info['createtime']), 'color' => '#4a5077'),
                    'remark' => array('value' => "\r\n" . '感谢您的支持！', 'color' => '#4a5077')
                );

                $this->sendNotice(array(
                    'openid'  => $log_info['openid'],
                    'tag'     => 'withdraw_ok',
                    'default' => $message,
                    'url'     => $this->getUrl('member/log', array('type' => 1)),
                    'datas'   => $datas
                ));

                com_run(
                    'sms::callsms',
                    array(
                        'tag'    => 'withdraw_ok',
                        'datas'  => $datas,
                        'mobile' => $member['mobile']
                    )
                );

                return NULL;
            }

            if ($log_info['status'] == -1) {

                if (!empty($usernotice['withdraw_fail'])) {
                    return NULL;
                }

                $message = array(
                    'first'  => array('value' => '抱歉，提现申请审核失败!', 'color' => '#4a5077'),
                    'money'  => array('title' => '提现金额/到账金额', 'value' => '¥' . $log_info['money'] . '元/¥' . $realmoeny . '元', 'color' => '#4a5077'),
                    'timet'  => array('title' => '提现时间', 'value' => date('Y-m-d H:i:s', $log_info['createtime']), 'color' => '#4a5077'),
                    'remark' => array('value' => "\r\n" . '有疑问请联系客服，谢谢您的支持！', 'color' => '#4a5077')
                );

                $this->sendNotice(array(
                    'openid'  => $log_info['openid'],
                    'tag'     => 'withdraw_fail',
                    'default' => $message,
                    'url'     => $this->getUrl('member/log', array('type' => 1)),
                    'datas'   => $datas
                ));

                com_run('sms::callsms', array(
                    'tag'    => 'withdraw_fail',
                    'datas'  => $datas,
                    'mobile' => $member['mobile']
                ));
            }
        }
    }

    /**
     * @param array $params
     *
     * @return null
     */
    public function sendNotice(array $params)
    {
        global $_W;
        global $_GPC;

        socket_log("TEST POINT: notice->sendNotice(params): " . json_encode($params, JSON_UNESCAPED_UNICODE));

        $tag    = (isset($params['tag']) ? $params['tag'] : '');
        $touser = (isset($params['openid']) ? $params['openid'] : '');

        if (empty($touser)) {
            return NULL;
        }

//        var_dump($params);

	//2019年6月20日 11:15:13 zjh 因模板消息整改而调整
        $template_global_type = m('common')->getSysset('template_global_type', false);
        if($template_global_type == 'notice_superdesk'){
            if(in_array($tag,array('pay','send'))){
                $this->superdeskCoreSendNotice($params);
//                var_dump(1);
                return;
            }
        }

//        var_dump(2);
//        var_dump($template_global_type);
//        die;

        $tm = $_W['shopset']['notice'];

        if (empty($tm)) {
            $tm = m('common')->getSysset('notice');
        }

//        socket_log("TEST_POINT_sendNotice__system_set_notice: " . json_encode($tm,JSON_UNESCAPED_UNICODE));

        $data = m('common')->getSysset('app');


//        socket_log("TEST_POINT_sendNotice__system_set_app: " . json_encode($data));

        // 小程序
        $miniprogram = array();
        if (p('app') && !empty($data) && empty($data['closetext']) && !empty($data['appid']) && !empty($params['appurl'])) {
            $miniprogram['appid']    = $data['appid'];
            $miniprogram['pagepath'] = $params['appurl'];
        }

//        $data = m('common')->getSysset('app');

        $tm_temp = $tm[$tag . '_template']; // 没数据
        $tm_tag  = $tm[$tag]; // 有数据

        if (($tag == 'saler_submit') || ($tag == 'saler_pay') || ($tag == 'saler_finish')) {
            $tm_tag = $tm['new'];
        }

//        $templateid         = $tm_temp;
        $templateid         = ($tm['is_advanced'] ? $tm_temp : $tm_tag);
        $datas              = (isset($params['datas']) ? $params['datas'] : array());
        $default_message    = (isset($params['default']) ? $params['default'] : array());
        $cusdefault_message = $this->replaceTemplate(isset($params['cusdefault']) ? $params['cusdefault'] : '', $datas);
        $url                = (isset($params['url']) ? $params['url'] : '');
        $account            = (isset($params['account']) ? $params['account'] : m('common')->getAccount());
        $is_merch           = intval($params['is_merch']);


//        socket_log("TEST_POINT_sendNotice__#datas: " . json_encode($datas, JSON_UNESCAPED_UNICODE));


        // 商户-新加-有问题-start
        if (empty($is_merch)) {
            if (!empty($tm[$tag . '_close_advanced'])) {
                return NULL;
            }
        } else {
            $merch_tm = (isset($params['merch_tm']) ? $params['merch_tm'] : '');

            if (!empty($merch_tm[$tag . '_close_advanced'])) {
                return NULL;
            }
        }
        // 商户-新加-有问题-end

//        socket_log("TEST_POINT_sendNotice__templateid: " . $templateid);

        if (!empty($templateid)) {

            $template = pdo_fetch(
                ' select * ' .
                ' from ' . tablename('superdesk_shop_member_message_template') .
                ' where id=:id ' .
//                ' where template_id=:template_id ' .
                '       and uniacid=:uniacid ' .
                ' limit 1',
                array(
//                    ':template_id' => $templateid,
                    ':id'      => $templateid,
                    ':uniacid' => $_W['uniacid']
                )
            );

            socket_log("TEST_POINT_sendNotice_template: " . json_encode($template));

            if (!empty($template)) {

                $messagetype = $template['messagetype'];
                $messagetype = 1;// 写死

                if (empty($messagetype)) {// 一定为null 未完成

                    $template_message = array(
                        'first'  => array('value' => $this->replaceTemplate($template['first'], $datas), 'color' => $template['firstcolor']),
                        'remark' => array('value' => $this->replaceTemplate($template['remark'], $datas), 'color' => $template['remarkcolor'])
                    );

                    $data = iunserializer($template['data']);

                    foreach ($data as $d) {
                        $template_message[$d['keywords']] = array(
                            'value' => $this->replaceTemplate($d['value'], $datas),
                            'color' => $d['color']
                        );
                    }

                    $Custom_message = $this->replaceTemplate($template['send_desc'], $datas);// 这个是错的
                    $Custom_message = htmlspecialchars_decode($Custom_message, ENT_QUOTES);

                    $ret = m('message')->sendTexts($touser, $Custom_message, $url, $account);

                    if (is_error($ret)) {

                        $ret = m('message')->sendTplNotice(
                            $touser,
                            $template['template_id'],
                            $template_message,
                            $url,
                            $account,
                            $miniprogram
                        );

                    }

                } else if ($messagetype == 1) {

                    $template_message = array(
                        'first'  => array('value' => $this->replaceTemplate($template['first'], $datas), 'color' => $template['firstcolor']),
                        'remark' => array('value' => $this->replaceTemplate($template['remark'], $datas), 'color' => $template['remarkcolor'])
                    );

                    $data = iunserializer($template['data']);

                    foreach ($data as $d) {
                        $template_message[$d['keywords']] = array('value' => $this->replaceTemplate($d['value'], $datas), 'color' => $d['color']);
                    }

                    $ret = m('message')->sendTplNotice($touser, $template['template_id'], $template_message, $url, $account, $miniprogram);

                } else {

                    if ($messagetype == 2) {

                        $Custom_message = $this->replaceTemplate($template['send_desc'], $datas);
                        $Custom_message = htmlspecialchars_decode($Custom_message, ENT_QUOTES);

                        $ret = m('message')->sendTexts($touser, $Custom_message, $url, $account);
                    }

                }
            } else {

                $templatetype = pdo_fetch(
                    ' select templateid ' .
                    ' from ' . tablename('superdesk_shop_member_message_template_default') .
                    ' where typecode=:typecode ' .
                    '       and uniacid=:uniacid ' .
                    ' limit 1',
                    array(
                        ':typecode' => $tag,
                        ':uniacid'  => $_W['uniacid']
                    )
                );

                if (!empty($templatetype['templateid'])) {

                    $ret = m('message')->sendTplNotice(
                        $touser,
                        $templatetype['templateid'],
                        $default_message,
                        $url,
                        $account,
                        $miniprogram
                    );

                    if (is_error($ret)) {

                        $ret = m('message')->sendTexts(
                            $touser,
                            $cusdefault_message,
                            '',
                            $account
                        );

                    }
                }
            }

        } else {

            $ret = m('message')->sendTexts(
                $touser,
                $cusdefault_message,
                '',
                $account
            );

            if (is_error($ret)) {

                $templatetype = pdo_fetch(
                    ' select templateid ' .
                    ' from ' . tablename('superdesk_shop_member_message_template_default') .
                    ' where typecode=:typecode ' .
                    '       and uniacid=:uniacid ' .
                    ' limit 1', array(':typecode' => $tag, ':uniacid' => $_W['uniacid']));

                if (!empty($templatetype['templateid'])) {

                    $ret = m('message')->sendTplNotice(
                        $touser,
                        $templatetype['templateid'],
                        $default_message,
                        $url,
                        $account,
                        $miniprogram
                    );

                }
            }
        }
    }

    /**
     * 将 [name] 替换成 ME20180428032656772838
     *
     * @param       $str
     * @param array $datas
     *
     * @return mixed
     */
    protected function replaceTemplate($str, $datas = array())
    {

//[
//{"name":"单号","value":"ME20180428032656772838"},
//{"name":"金额","value":"418.95"},
//{"name":"采购人","value":"安先生"},
//{"name":"供应商","value":"超级前台"},
//{"name":"采购时间","value":"2018-04-28 04:01:25"}
//]
        foreach ($datas as $d) {
            $str = str_replace('[' . $d['name'] . ']', $d['value'], $str);
        }

        return $str;
    }

    /**
     * @param $openid
     * @param $params
     * @param $type
     *
     * @return bool
     */
    public function sendMessage($openid, $core_user, $params, $type)
    {
        global $_W;

        if (empty($openid)) {
            return false;
        }

        $member = m('member')->getMember($openid, $core_user);

        if ($type == 'orderstatus') {

            $datas = array(
                array('name' => '粉丝昵称', 'value' => $member['nickname']),
                array('name' => '修改时间', 'value' => time()),
                array('name' => '订单编号', 'value' => $params['ordersn']),
                array('name' => '原收货地址', 'value' => $params['olddata']),
                array('name' => '新收货地址', 'value' => $params['data']),
                array('name' => '订单原价格', 'value' => $params['olddata']),
                array('name' => '订单新价格', 'value' => $params['data']),
                array('name' => '项目名称', 'value' => $this->getMemberCoreEnterpriseName($core_user)),
            );

            $msg = array(
                'first'       => array('value' => $params['title'] . ' 修改提醒！', 'color' => '#4a5077'),
                'OrderSn'     => array('title' => '订单编号', 'value' => $params['ordersn'], 'color' => '#4a5077'),
                'OrderStatus' => array('title' => '订单状态', 'value' => '已修改', 'color' => '#4a5077'),
                'remark'      => array('value' => "\r\n" . '原收货地址 : ' . $params['olddata'] . "\r\n" . '新收货地址 : ' . $params['data'], 'color' => '#4a5077')
            );

            if ($params['type'] == '1') {

                $msg['remark'] = array('value' => "\r\n" . '订单原价格 : ' . $params['olddata'] . '元,' . "\r\n" . '订单新价格 : ' . $params['data'] . '元.', 'color' => '#4a5077');

            }

            $this->sendNotice(array(
                'openid'  => $openid,
                'tag'     => 'orderstatus',
                'default' => $msg,
                'url'     => $params['url'],
                'datas'   => $datas
            ));

            com_run('sms::callsms', array(
                'tag'    => 'orderstatus',
                'datas'  => $datas,
                'mobile' => $member['mobile']
            ));

        }
    }


    /**
     * 推送企业月结订单采购经理审核
     * zjh 2018年4月23日 17:12:33
     * ljy 2018年4月25日 检查修改
     * ljy 2019年2月22日 BuildWindow 修正
     * 买家通知 - 订单通知 - 订单提交审核通知 - OPENTM410946800
     *
     * @param $openid
     * @param $core_user
     * @param $core_enterprise
     * @param $core_organization
     * @param $mobile
     * @param $username
     * @param $ordersn
     * @param $price
     * @param $orderid
     *
     * @return bool
     */
    public function sendExamineCreateNotice($openid,            /* 采购经理_审核人 openid */
                                            $core_user,         /* 采购经理_审核人 core_user */
                                            $core_enterprise,   /* 采购经理_审核人 core_enterprise */
                                            $core_organization, /* 采购经理_审核人 core_organization */
                                            $mobile,            /* 采购经理_审核人 mobile */
                                            $username, $ordersn, $price, $orderid)
    {
        global $_W;
        global $_GPC;

//        $socket_log_data = array(
//            'openid'   => $openid,
//            'orderid'  => $orderid,
//            'ordersn'  => $ordersn,
//            'price'    => $price,
//            'username' => $username,/*采购专员*/
//            'mobile'   => $mobile,
//        );
//        socket_log("TEST POINT: 推送给采购经理-item-模板: " . json_encode($socket_log_data));
//        {"openid":"oX8KYwkxwNW6qzHF4cF-tGxYTcPg","orderid":"1818","ordersn":"ME20180428032656772838","price":"418.95","username":"\u5b89\u5148\u751f","mobile":"13422832499"}

        if (empty($openid)) {
            // TODO log
            return false;
        }


        if (empty($core_user)) {
            // TODO log
            return false;
        }

        if (!SUPERDESK_SHOPV2_WECHAT_IS_NOTICE) {

            socket_log("TEST POINT: 微信通知----屏蔽: ");
            return true;
//            exit('微信通知----屏蔽');
        }

        $detailurl = $this->getUrl('examine/detail', array(
            'core_user'         => $core_user,
            'core_enterprise'   => $core_enterprise,
            'core_organization' => $core_organization,
            'userMobile'        => $mobile,
            'orderid'           => $orderid
        ));


        $datas = array(
            array('name' => '头部标题', 'value' => "采购专员 " . $username . " 提交了订单，请及时审核！"),
            array('name' => '单号', 'value' => $ordersn),
            array('name' => '金额', 'value' => $price),
            array('name' => '采购人', 'value' => $username),
            array('name' => '供应商', 'value' => $this->getMemberCoreEnterpriseName($core_user)),
            array('name' => '采购时间', 'value' => date('Y-m-d H:i:s', time())),
            array('name' => '项目名称', 'value' => $this->getMemberCoreEnterpriseName($core_user)),
            array('name' => '尾部描述', 'value' => "\r\n" . '请及时审核!')
        );
        // 由于超级前台的模板记录是按服务站的变量名去做的.所以以下是错的
//        $datas = array(
//            array('name' => '头部标题', 'value' => "采购专员 " . $username . " 提交了订单！"),
//            array('name' => '订单编号', 'value' => $ordersn),
//            array('name' => '订单金额', 'value' => $price),
//            array('name' => '客户名称', 'value' => $username),
//            array('name' => '申请备注', 'value' => '超级前台'),
//            array('name' => '申请时间', 'value' => date('Y-m-d H:i:s', time())),
//            array('name' => '尾部描述', 'value' => "\r\n" . '请及时审核!')
//        );


        // 公众号内部发消息
        /**----------------------------------------------------------------------------------------------------------**/
//        $message = array(
//            'first'    => array('value' => '采购专员 ' . $username . ' 提交了订单！', 'color' => '#4a5077'),
//            'keyword1' => array('title' => '单号', 'value' => $ordersn, 'color' => '#4a5077'),
//            'keyword2' => array('title' => '金额', 'value' => $price, 'color' => '#4a5077'),
//            'keyword3' => array('title' => '采购人', 'value' => $username, 'color' => '#4a5077'),
//            'keyword4' => array('title' => '供应商', 'value' => '超级前台', 'color' => '#4a5077'),
//            'keyword5' => array('title' => '采购时间', 'value' => date('Y-m-d H:i:s', time()), 'color' => '#4a5077'),
//            'remark'   => array('value' => "\r\n" . '请及时审核!', 'color' => '#4a5077')
//        );
//        $this->sendNotice(array(
//            'openid'  => $openid,
//            'tag'     => 'examine_new',
//            'default' => $message,
//            'url'     => $detailurl,
//            'datas'   => $datas
//        ));
        /**----------------------------------------------------------------------------------------------------------**/


        // 公众号外部发消息
        /**----------------------------------------------------------------------------------------------------------**/
        $message = array(
            'first'    => array('value' => '采购专员 ' . $username . ' 提交了订单！', 'color' => '#4a5077'),
            'keyword1' => array('title' => '订单编号', 'value' => $ordersn, 'color' => '#4a5077'),
            'keyword2' => array('title' => '订单金额', 'value' => $price, 'color' => '#4a5077'),
            'keyword3' => array('title' => '客户名称', 'value' => $username, 'color' => '#4a5077'),
            'keyword4' => array('title' => '申请备注', 'value' => $this->getMemberCoreEnterpriseName($core_user), 'color' => '#4a5077'),
            'keyword5' => array('title' => '申请时间', 'value' => date('Y-m-d H:i:s', time()), 'color' => '#4a5077'),
            'remark'   => array('value' => "\r\n" . '请及时审核!', 'color' => '#4a5077')
        );

        $this->superdeskCoreSendNotice(array(
            'mobile'     => $mobile,
            'core_user'     => $core_user,
            'tag'        => 'examine_new',
//            'templateid' => 'pFFVbqq87XDv06SESF1iMsUwAAMaIDDOM_lXsHme9R0',//2019年6月20日 11:15:13 zjh 因模板消息整改而调整
            'default'    => $message,
            'url'        => $detailurl,
            'datas'      => $datas
        ));
        /**----------------------------------------------------------------------------------------------------------**/
        return true;
    }


    /**
     * 推送企业月结订单采购经理审核结果
     * zjh 2018年4月23日 17:12:33
     * ljy 2018年4月25日 检查修改
     *
     * 买家通知 - 订单通知 - 订单审核结果通知 - OPENTM207196912
     *
     * @param      $openid
     * @param      $type
     * @param      $username
     * @param null $orderid
     */
    public function sendExamineResultNotice(
        $openid,            /* 采购专员 信息  */
        $core_user,         /* 采购专员 信息  */
        $mobile,            /* 采购专员 信息  */
        $orderid ,          /* 订单 信息  */
        $price,             /* 订单 信息  */
        $times,             /* 申请 时间   */
        $username,          /* 审批人 信息  */
        $type,               /* 审批人 操作  */
        $ordersn	/* 订单 编号 2019年6月20日 11:15:13 zjh 因模板消息整改而调整 */ 
    )
    {
        global $_W;
        global $_GPC;

        if (empty($openid)) {
            // TODO log
            return false;
        }

        if (empty($core_user)) {
            // TODO log
            return false;
        }

        if (!SUPERDESK_SHOPV2_WECHAT_IS_NOTICE) {

            socket_log("TEST POINT: 微信通知----屏蔽: ");
            return true;
            //exit('微信通知----屏蔽');
        }

        $type_msg = $type == 1 ? '通过' : '不通过';

        // 当前这个是 申请人 采购专员
        $member = m('member')->getMember($openid, $core_user , ' id,openid,mobile,core_user,core_enterprise,core_organization,pwd,salt ');

        $detailurl = $this->getUrl('examine/detail', array(
            'core_user'         => $member['core_user'],
            'core_enterprise'   => $member['core_enterprise'],
            'core_organization' => $member['core_organization'],
            'userMobile'        => $member['mobile'],
            'orderid'           => $orderid
        ));


        // 超级前台的模板服务站
//        $datas = array(
//            array('name' => '头部标题', 'value' => '采购经理 ' . $username . ' 已审核订单！'),
//            array('name' => '订单时间', 'value' => date('Y-m-d H:i:s', $times)),
//            array('name' => '订单金额', 'value' => $price),
//            array('name' => '收货地址', 'value' => ''),
//            array('name' => '尾部描述', 'value' => "\r\n" . '审核结果为：' . $type_msg),
//        );
        // 超级前台的模板
        $datas = array(
            array('name' => '头部标题', 'value' => '采购经理 ' . $username . ' 已审核订单！'),
            array('name' => '下单时间', 'value' => date('Y-m-d H:i:s', $times)),
            array('name' => '订单总金额', 'value' => $price),
            array('name' => '订单详情', 'value' => ''),
            array('name' => '详细地址', 'value' => ''),
            array('name' => '审核状态', 'value' => $type_msg),
            array('name' => '项目名称', 'value' => $this->getMemberCoreEnterpriseName($core_user)),
            array('name' => '尾部描述', 'value' => ''),

	    //2019年6月20日 11:15:13 zjh 因模板消息整改而调整
            array('name' => '订单编号', 'value' => $ordersn),
            array('name' => '订单状态', 'value' => $type_msg),
            array('name' => '订单类型', 'value' => ''),
            array('name' => '到货时间', 'value' => ($type == 1 ? '预计3至7天，请耐心等候。' : '')),
        );

        // 公众号内部发消息
        /**----------------------------------------------------------------------------------------------------------**/
//        $message = array(
//            'first'    => array('value' => '采购经理 ' . $username . ' 已审核订单！', 'color' => '#4a5077'),
//            'keyword1' => array('title' => '订单时间', 'value' => date('Y-m-d H:i:s', $times), 'color' => '#4a5077'),
//            'keyword2' => array('title' => '订单金额', 'value' => $price, 'color' => '#4a5077'),
//            'keyword3' => array('title' => '收货地址', 'value' => '', 'color' => '#4a5077'),
//            'remark'   => array('value' => "\r\n" . '审核结果为：' . $type_value, 'color' => '#4a5077')
//        );
//        $this->sendNotice(array(
//            'openid'  => $openid,
//            'tag'     => 'examine_result',
//            'default' => $message,
//            'url'     => $detailurl,
//            'datas'   => $datas
//        ));
        /**----------------------------------------------------------------------------------------------------------**/

        // 公众号外部发消息
        /**----------------------------------------------------------------------------------------------------------**/
        //2019年6月20日 14:39:21 zjh 模板调整
//        $message = array(
//            'first'    => array('value' => '采购经理 ' . $username . ' 已审核订单！', 'color' => '#4a5077'),
//            'keyword1' => array('title' => '下单时间', 'value' => date('Y-m-d H:i:s', $times), 'color' => '#4a5077'),
//            'keyword2' => array('title' => '订单总金额', 'value' => $price, 'color' => '#4a5077'),
//            'keyword3' => array('title' => '订单详情', 'value' => '', 'color' => '#4a5077'),
//            'keyword4' => array('title' => '详细地址', 'value' => '', 'color' => '#4a5077'),
//            'remark'   => array('value' => "\r\n" . '审核结果为：' . $type_msg, 'color' => '#4a5077')
//        );

        $message = array(
            'first'    => array('value' => '采购经理 ' . $username . ' 已审核订单！', 'color' => '#4a5077'),
            'keyword1' => array('title' => '订单编号', 'value' => $ordersn, 'color' => '#4a5077'),
            'keyword2' => array('title' => '订单状态', 'value' => $type_msg, 'color' => '#4a5077'),
            'keyword3' => array('title' => '订单类型', 'value' => '', 'color' => '#4a5077'),
            'keyword4' => array('title' => '下单时间', 'value' => date('Y-m-d H:i:s', $times), 'color' => '#4a5077'),
            'keyword5' => array('title' => '到货时间', 'value' => '', 'color' => '#4a5077'),
            'remark'   => array('value' => '', 'color' => '#4a5077')
        );

        $this->superdeskCoreSendNotice(array(
            'mobile'     => $mobile,
            'core_user'     => $core_user,
            'tag'        => 'examine_result',
//            'templateid' => 'W4p3SgNmW9eQKpgzG4Xi5mRM0LMeBUR9RBjpsIIsOjo',//2019年6月20日 11:15:13 zjh 因模板消息整改而调整
            'default'    => $message,
            'url'        => $detailurl,
            'datas'      => $datas
        ));
        /**----------------------------------------------------------------------------------------------------------**/
        return true;
    }





    /*******************************      以下为调用超级前台接口获取access_token与openid然后推送模板消息到超级前台公众号      **********************************/

    /**
     * @param array $params
     *
     * @return null
     * 由上面的function sendNotice复制过来.
     * 差别在于该方法调用了$this->superdeskCoreSendCustomNotice  $this->superdeskCoreSendTplNotice
     * touser改成了用户的mobile而不是openid
     */
    public function superdeskCoreSendNotice(array $params)
    {
        global $_W;
        global $_GPC;

//        socket_log("TEST POINT: notice->superdeskCoreSendNotice(params): " . json_encode($params, JSON_UNESCAPED_UNICODE));

        $tag        = (isset($params['tag']) ? $params['tag'] : '');
        $templateid = (isset($params['templateid']) ? $params['templateid'] : '');
        $touser     = (isset($params['mobile']) ? $params['mobile'] : '');
        $core_user     = (isset($params['core_user']) ? $params['core_user'] : '');

	//2019年6月20日 11:15:13 zjh 因模板消息整改而调整
        if(empty($templateid)){
            $tm = m('common')->getSysset('notice_superdesk');

            $tm_temp = $tm[$tag . '_template']; // 没数据
            $tm_tag  = $tm[$tag]; // 有数据

            $templateid         = ($tm['is_advanced'] ? $tm_temp : $tm_tag);

            $template = pdo_fetch(
                ' select * ' .
                ' from ' . tablename('superdesk_shop_member_message_template') .
                ' where id=:id ' .
//                ' where template_id=:template_id ' .
                '       and uniacid=:uniacid ' .
                ' limit 1',
                array(
//                    ':template_id' => $templateid,
                    ':id'      => $templateid,
                    ':uniacid' => $_W['uniacid']
                )
            );

            if(!empty($template)){
                $templateid = $template['template_id'];
            }
        }

        if (empty($core_user)) {
            return NULL;
        }

        $data = m('common')->getSysset('app');

        $datas              = (isset($params['datas']) ? $params['datas'] : array());
        $default_message    = (isset($params['default']) ? $params['default'] : array());
        $cusdefault_message = $this->replaceTemplate(isset($params['cusdefault']) ? $params['cusdefault'] : '', $datas);
        $url                = (isset($params['url']) ? $params['url'] : '');
        $account            = (isset($params['account']) ? $params['account'] : m('common')->getAccount());
        $is_merch           = intval($params['is_merch']);


//        socket_log("TEST_POINT_superdeskCoreSendNotice__#datas: " . json_encode($datas, JSON_UNESCAPED_UNICODE));


        if (!empty($templateid)) {
//            var_dump($templateid);die;

            $template = pdo_fetch(
                ' select * ' .
                ' from ' . tablename('superdesk_shop_member_message_template') .
                ' where uniacid=:uniacid and template_id=:template_id limit 1',
                array(
                    ':uniacid'     => $_W['uniacid'],
                    ':template_id' => $templateid
                )
            );

//            socket_log("TEST_POINT_superdeskCoreSendNotice__template: " . json_encode($template,JSON_UNESCAPED_UNICODE));

            $template_message = array(
                'first'  => array('value' => $this->replaceTemplate($template['first'], $datas), 'color' => $template['firstcolor']),
                'remark' => array('value' => $this->replaceTemplate($template['remark'], $datas), 'color' => $template['remarkcolor'])
            );

//            socket_log("TEST_POINT_superdeskCoreSendNotice__template_message: " . json_encode($template_message));

            $data = iunserializer($template['data']);

//            socket_log("TEST_POINT_superdeskCoreSendNotice__template_[data]: " . json_encode($data,JSON_UNESCAPED_UNICODE));

            foreach ($data as $d) {
                $template_message[$d['keywords']] = array('value' => $this->replaceTemplate($d['value'], $datas), 'color' => $d['color']);
            }

            socket_log("TEST_POINT_superdeskCoreSendNotice__template_message: " . json_encode($template_message, JSON_UNESCAPED_UNICODE));


            $ret = $this->superdeskCoreSendTplNotice(
                $touser,
                $core_user,
                $templateid,
                $template_message,
                $url
//                , $account
            );


        }
    }

    /**
     * @param array $params
     *
     * @return null
     * 由framework/class/weixin.account.class.php中的funciton sendTplNotice复制过来.
     * 差别在于该方法调用了不是拿自己的acces_token而是像超级前台拿.并且接收的touser是手机号而不是openid
     */
    public function superdeskCoreSendTplNotice(
        $toUserMobile /* 这里传个手机号 */,
        $toCoreUser,
        $template_id,
        $post_data,
        $url = '',
        $topcolor = '#FF683F'
    )
    {

        $socket_log_data = array(
            'toUserMobile' => $toUserMobile,
            'template_id'  => $template_id,
            'post_data'    => $post_data,
            'url'          => $url,
            'topcolor'     => $topcolor,
            'toCoreUser'     => $toCoreUser,
        );

        socket_log("TEST_POINT_superdeskCoreSendTplNotice: " . json_encode($socket_log_data, JSON_UNESCAPED_UNICODE));

        if (empty($toCoreUser)) {
            return error(-1, '参数错误,粉丝ID不能为空');
        }

        if (empty($template_id)) {
            return error(-1, '参数错误,模板标示不能为空');
        }

        if (empty($post_data) || !is_array($post_data)) {
            return error(-1, '参数错误,请根据模板规则完善消息内容');
        }


        $_userInfoService = new UserInfoService();

        $superdesk_core_data = $_userInfoService->getOneByCoreUser($toCoreUser);


        $superdesk_openid = $superdesk_core_data['open_id'];
        $access_token     = $superdesk_core_data['access_token'];

        if (empty($superdesk_openid)) {
            $firstMessage = $post_data['first']['value'];
            $error = "title:{$firstMessage} \n core_user:{$toCoreUser} \n mobile:{$toUserMobile} \n 楼宇之窗获取openid失败";
            $this->sendErrorMessage($error,$toCoreUser);

            return true;
        }

        $data                = array();
        $data['touser']      = $superdesk_openid;
        $data['template_id'] = trim($template_id);
        $data['url']         = trim($url);
        $data['topcolor']    = trim($topcolor);
        $data['data']        = $post_data;
        $data                = json_encode($data);
        $post_url            = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$access_token}";
        $response            = ihttp_request($post_url, $data);

        socket_log("超级前台:" . json_encode($response, JSON_UNESCAPED_UNICODE));


        if (is_error($response)) {

            $error = $data . $access_token . "访问公众平台接口失败, 错误: {$response['message']}";
            $this->sendErrorMessage($error,$toCoreUser);

            return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
        }

        $result = @json_decode($response['content'], true);

        if (empty($result)) {

            $error = $data . $access_token . "接口调用失败, 元数据: {$response['meta']}";
            $this->sendErrorMessage($error,$toCoreUser);

            return error(-1, "接口调用失败, 元数据: {$response['meta']}");

        } elseif (!empty($result['errcode'])) {

            $error = $data . $access_token . "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},信息详情：{$this->error_code($result['errcode'])}";
            $this->sendErrorMessage($error,$toCoreUser);

            return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},信息详情：{$this->error_code($result['errcode'])}");

        }
        return true;
    }


    /**
     * @deprecated
     *
     * @param $data
     *
     * @return array|mixed
     * 'touser'  => $openid,
     * 'msgtype' => 'text',
     * 'text'    => array('content' => urlencode($content))
     * 由framework/class/weixin.account.class.php中的funciton sendTplNotice复制过来.
     * 差别在于该方法调用了不是拿自己的acces_token而是像超级前台拿.并且接收的touser是手机号而不是openid
     */
    public function superdeskCoreSendCustomNotice($data)
    {
        if (empty($data)) {
            return error(-1, '参数错误');
        }

        $_uservice           = new UserInfoService();
        $superdesk_core_data = $_uservice->getOneByCoreUser($data['touser']);
        $data['touser']      = $superdesk_core_data['user']['returnDTO']['openId'];
        $token               = $superdesk_core_data['access_token']['returnDTO']['accessToken'];

        $url      = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$token}";
        $response = ihttp_request($url, urldecode(json_encode($data)));

        if (is_error($response)) {
            return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
        }

        $result = @json_decode($response['content'], true);

        if (empty($result)) {
            return error(-1, "接口调用失败, 元数据: {$response['meta']}");
        } elseif (!empty($result['errcode'])) {
            return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->error_code($result['errcode'])}");
        }

        return $result;
    }


    /**
     * 代客下单转介推送通知
     * zjh 2018年11月12日 11:50:31
     *
     * 买家通知 - 订单通知 - 订单提交审核通知 - OPENTM410946800
     *
     * @param      $openid
     * @param      $type
     * @param      $username
     * @param null $orderid
     */
    public function sendTransferCreateNotice($openid/*客户_openid*/, $mobile, $core_user, $username, $ordersn, $price, $transfer_id, $customName)
    {
        global $_W;
        global $_GPC;

//        $socket_log_data = array(
//            'openid'   => $openid,
//            'orderid'  => $orderid,
//            'ordersn'  => $ordersn,
//            'price'    => $price,
//            'username' => $username,/*采购专员*/
//            'mobile'   => $mobile,
//        );
//        socket_log("TEST POINT: 推送给采购经理-item-模板: " . json_encode($socket_log_data));
//        {"openid":"oX8KYwkxwNW6qzHF4cF-tGxYTcPg","orderid":"1818","ordersn":"ME20180428032656772838","price":"418.95","username":"\u5b89\u5148\u751f","mobile":"13422832499"}

        if (empty($openid)) {
            // TODO log
            return false;
        }


        if (empty($core_user)) {
            // TODO log
            return false;
        }

        if (!SUPERDESK_SHOPV2_WECHAT_IS_NOTICE) {

            socket_log("TEST POINT: 微信通知----屏蔽: ");
            return true;
//            exit('微信通知----屏蔽');
        }

        $detailurl = $this->getUrl('order/transfer', array('id' => $transfer_id));


        $datas = array(
            array('name' => '头部标题', 'value' => "采购专员 " . $customName . " 提交了订单！"),
            array('name' => '单号', 'value' => $ordersn),
            array('name' => '金额', 'value' => $price),
            array('name' => '采购人', 'value' => $username),
            array('name' => '供应商', 'value' => '超级前台代客下单'),
            array('name' => '采购时间', 'value' => date('Y-m-d H:i:s', time())),
            array('name' => '尾部描述', 'value' => "\r\n" . '请及时确认!')
        );
        // 由于超级前台的模板记录是按服务站的变量名去做的.所以以下是错的
//        $datas = array(
//            array('name' => '头部标题', 'value' => "采购专员 " . $username . " 提交了订单！"),
//            array('name' => '订单编号', 'value' => $ordersn),
//            array('name' => '订单金额', 'value' => $price),
//            array('name' => '客户名称', 'value' => $username),
//            array('name' => '申请备注', 'value' => '超级前台'),
//            array('name' => '申请时间', 'value' => date('Y-m-d H:i:s', time())),
//            array('name' => '尾部描述', 'value' => "\r\n" . '请及时审核!')
//        );


        // 公众号内部发消息
        /**----------------------------------------------------------------------------------------------------------**/
//        $message = array(
//            'first'    => array('value' => '采购专员 ' . $username . ' 提交了订单！', 'color' => '#4a5077'),
//            'keyword1' => array('title' => '单号', 'value' => $ordersn, 'color' => '#4a5077'),
//            'keyword2' => array('title' => '金额', 'value' => $price, 'color' => '#4a5077'),
//            'keyword3' => array('title' => '采购人', 'value' => $username, 'color' => '#4a5077'),
//            'keyword4' => array('title' => '供应商', 'value' => '超级前台', 'color' => '#4a5077'),
//            'keyword5' => array('title' => '采购时间', 'value' => date('Y-m-d H:i:s', time()), 'color' => '#4a5077'),
//            'remark'   => array('value' => "\r\n" . '请及时审核!', 'color' => '#4a5077')
//        );
//        $this->sendNotice(array(
//            'openid'  => $openid,
//            'tag'     => 'examine_new',
//            'default' => $message,
//            'url'     => $detailurl,
//            'datas'   => $datas
//        ));
        /**----------------------------------------------------------------------------------------------------------**/


        // 公众号外部发消息
        /**----------------------------------------------------------------------------------------------------------**/
        $message = array(
            'first'    => array('value' => '采购专员 ' . $customName . ' 提交了订单！', 'color' => '#4a5077'),
            'keyword1' => array('title' => '订单编号', 'value' => $ordersn, 'color' => '#4a5077'),
            'keyword2' => array('title' => '订单金额', 'value' => $price, 'color' => '#4a5077'),
            'keyword3' => array('title' => '客户名称', 'value' => $username, 'color' => '#4a5077'),
            'keyword4' => array('title' => '申请备注', 'value' => '超级前台代客下单', 'color' => '#4a5077'),
            'keyword5' => array('title' => '申请时间', 'value' => date('Y-m-d H:i:s', time()), 'color' => '#4a5077'),
            'remark'   => array('value' => "\r\n" . '请及时确认!', 'color' => '#4a5077')
        );
        $this->superdeskCoreSendNotice(array(
            'mobile'     => $mobile,
            'core_user'     => $core_user,
            'tag'        => 'examine_new',
            'templateid' => 'pFFVbqq87XDv06SESF1iMsUwAAMaIDDOM_lXsHme9R0',
            'default'    => $message,
            'url'        => $detailurl,
            'datas'      => $datas
        ));
        /**----------------------------------------------------------------------------------------------------------**/
        return true;
    }

    public function sendErrorMessage($error,$core_user){

        $openids = array(
            'oX8KYwkxwNW6qzHF4cF-tGxYTcPg', // 雨
            'oX8KYwkSD0lIzocZBwC0knuTAoi0', // 冯鑫
            'oX8KYwiEaMSg0C7jR0VSLWCc1B7s', // 詹
            'oX8KYwuPV2L6MyPMMGQ4Bgd7XBao', // 何衍清
            'oX8KYwpS1msvDrahQ31LeEwnOe6c', // 王秀芝
            'oX8KYwh9oGzJ6Vdcl9XnW13FFSXU', // 谢艳君
            'oX8KYwlACQw6ykIlLl6cXhlt5woE', // 赵燕霜
        );
        $member = m('member')->getMemberByCoreUser($core_user);

//        include_once(IA_ROOT . '/addons/superdesk_core/service/VirtualarchitectureService.class.php');
//        $_virtualarchitectureService = new VirtualarchitectureService();
//        $curr_virtualarchitecture = $_virtualarchitectureService->getOneByVirtualArchId($member['core_enterprise']);
//        $_W['virtualarchitecture_name'] = $curr_virtualarchitecture['name'];
        include_once(IA_ROOT . '/addons/superdesk_core/service/OrganizationService.class.php');
        $_organizationService        = new OrganizationService();
        $curr_organization = $_organizationService->getOneByOrganizationId($member['core_organization']);
        $organization_name = $curr_organization['name'];

        $datas = array(
            array('name' => '警告', 'value' => '楼宇之窗推送错误'),
            array('name' => '项目名称', 'value' => '项目名称:' . $organization_name),
            array('name' => '原因', 'value' => $error)
        );

        $msg = array();

        foreach($openids as $k => $v){
            $this->sendNotice(array(
                'openid'  => $v,
                'tag'     => '',
                'default' => $msg,
                'url'     => '',
                'cusdefault' => '[警告]\n\n [项目名称] \n [原因]',
                'datas'   => $datas
            ));
        }

    }

    public function getMemberCoreEnterpriseName($core_user){

        if(empty($core_user)){
            return '';
        }

        $member = m('member')->getMemberByCoreUser($core_user);

        if(empty($member)){
            return '';
        }

        include_once(IA_ROOT . '/addons/superdesk_core/service/VirtualarchitectureService.class.php');
        $_virtualarchitectureService     = new VirtualarchitectureService();
        $curr_virtualarchitecture        = $_virtualarchitectureService->getOneByVirtualArchId($member['core_enterprise']);
        $core_enterprise_name            = $curr_virtualarchitecture['name'];

        return $core_enterprise_name;

    }
}