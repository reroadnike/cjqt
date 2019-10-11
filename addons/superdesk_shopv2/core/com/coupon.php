<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Coupon_SuperdeskShopV2ComModel extends ComModel
{

    public function get_last_count($couponid = 0)
    {
        global $_W;

        $coupon = pdo_fetch(
            'SELECT id,total ' .
            ' FROM ' . tablename('superdesk_shop_coupon') .
            ' WHERE ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ',
            array(
                ':id'      => $couponid,
                ':uniacid' => $_W['uniacid']
            )
        );

        if (empty($coupon)) {
            return 0;
        }

        if ($coupon['total'] == -1) {
            return -1;
        }

        $gettotal = pdo_fetchcolumn(
            'select count(*) ' .
            ' from ' . tablename('superdesk_shop_coupon_data') .// TODO 标志 楼宇之窗 openid shop_coupon_data 不处理
            ' where ' .
            '       couponid=:couponid ' .
            '       and uniacid=:uniacid ',
            array(
                ':couponid' => $couponid,
                ':uniacid'  => $_W['uniacid']
            )
        );

        return $coupon['total'] - $gettotal;
    }

    /**
     * 信贷商店
     *
     * @param int $logid
     */
    public function creditshop($logid = 0)
    {
        global $_W;
        global $_GPC;
        $pcreditshop = p('creditshop');
        if (!$pcreditshop) {
            return;
        }
        $log = pdo_fetch(
            'SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_creditshop_log') . // TODO 标志 楼宇之窗 openid shop_creditshop_log 不处理
            ' WHERE ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':uniacid' => $_W['uniacid'],
                ':id'      => $logid
            )
        );

        if (!empty($log)) {
            $member = m('member')->getMember($log['openid'], $log['core_user']);
            $goods  = $pcreditshop->getGoods($log['couponid'], $member);

            $couponlog = array(
                'uniacid'      => $_W['uniacid'],
                'openid'       => $log['openid'],
                'core_user'    => $log['core_user'],
                'logno'        => m('common')->createNO('coupon_log', 'logno', 'CC'),
                'couponid'     => $log['couponid'],
                'status'       => 1,
                'paystatus'    => (0 < $goods['money'] ? 0 : -1),
                'creditstatus' => (0 < $goods['credit'] ? 0 : -1),
                'createtime'   => time(),
                'getfrom'      => 2
            );

            pdo_insert('superdesk_shop_coupon_log', $couponlog);// TODO 标志 楼宇之窗 openid superdesk_shop_coupon_log 已处理

            $data = array(
                'uniacid'   => $_W['uniacid'],
                'openid'    => $log['openid'],
                'core_user' => $log['core_user'],
                'couponid'  => $log['couponid'],
                'gettype'   => 2,
                'gettime'   => time()
            );
            pdo_insert('superdesk_shop_coupon_data', $data);// TODO 标志 楼宇之窗 openid shop_coupon_data 已处理

            $coupon = pdo_fetch(
                'select * ' .
                ' from ' . tablename('superdesk_shop_coupon') .
                ' where ' .
                '       id=:id ' .
                ' limit 1',
                array(
                    ':id' => $log['couponid']
                )
            );
            $coupon = $this->setCoupon($coupon, time());

            $this->sendMessage($coupon, 1, $member);

            pdo_update(
                'superdesk_shop_creditshop_log', // TODO 标志 楼宇之窗 openid shop_creditshop_log 不处理
                array(
                    'status' => 3
                ),
                array(
                    'id' => $logid
                )
            );
        }
    }

    /**
     * 任务海报
     *
     * @param $member
     * @param $couponid
     * @param $couponnum
     */
    public function taskposter($member, $couponid, $couponnum)
    {
        global $_W;
        global $_GPC;

        $pposter = p('poster');
        if (!$pposter) {
            return;
        }

        $coupon = $this->getCoupon($couponid);
        if (empty($coupon)) {
            return;
        }

        $i = 1;
        while ($i <= $couponnum) {
            $couponlog = array(
                'uniacid'      => $_W['uniacid'],
                'openid'       => $member['openid'],
                'core_user'    => $member['core_user'],
                'logno'        => m('common')->createNO('coupon_log', 'logno', 'CC'),
                'couponid'     => $couponid,
                'status'       => 1,
                'paystatus'    => -1,
                'creditstatus' => -1,
                'createtime'   => time(),
                'getfrom'      => 3
            );

            pdo_insert('superdesk_shop_coupon_log', $couponlog);// TODO 标志 楼宇之窗 openid superdesk_shop_coupon_log 已处理

            $data = array(
                'uniacid'   => $_W['uniacid'],
                'openid'    => $member['openid'],
                'core_user' => $member['core_user'],
                'couponid'  => $couponid,
                'gettype'   => 3,
                'gettime'   => time(),
                'nocount'   => 1
            );
            pdo_insert('superdesk_shop_coupon_data', $data);// TODO 标志 楼宇之窗 openid shop_coupon_data 已处理
            ++$i;
        }
    }

    /**
     * 海报
     *
     * @param $member
     * @param $couponid
     * @param $couponnum
     */
    public function poster($member, $couponid, $couponnum)
    {
        global $_W;
        global $_GPC;

        $pposter = p('poster');
        if (!$pposter) {
            return;
        }

        $coupon = $this->getCoupon($couponid);
        if (empty($coupon)) {
            return;
        }

        $i = 1;
        while ($i <= $couponnum) {
            $couponlog = array(
                'uniacid'      => $_W['uniacid'],
                'openid'       => $member['openid'],
                'core_user'    => $member['core_user'],
                'logno'        => m('common')->createNO('coupon_log', 'logno', 'CC'),
                'couponid'     => $couponid,
                'status'       => 1,
                'paystatus'    => -1,
                'creditstatus' => -1,
                'createtime'   => time(),
                'getfrom'      => 3
            );

            pdo_insert('superdesk_shop_coupon_log', $couponlog);// TODO 标志 楼宇之窗 openid superdesk_shop_coupon_log 已处理

            $data = array(
                'uniacid'   => $_W['uniacid'],
                'openid'    => $member['openid'],
                'core_user' => $member['core_user'],
                'couponid'  => $couponid,
                'gettype'   => 3,
                'gettime'   => time()
            );
            pdo_insert('superdesk_shop_coupon_data', $data);// TODO 标志 楼宇之窗 openid shop_coupon_data 已处理

            ++$i;
        }
        $set = m('common')->getPluginset('coupon');

        $this->sendMessage($coupon, $couponnum, $member);
    }

    /**
     * 获取可用优惠券
     *
     * @param       $type
     * @param int   $money
     * @param       $merch_array
     * @param array $goods_array
     *
     * @return array
     */
    public function getAvailableCoupons($type, $money = 0, $merch_array, $goods_array = array())
    {
        global $_W;
        global $_GPC;
        $time                = time();
        $param               = array();
        $param[':openid']    = $_W['openid'];
        $param[':core_user'] = $_W['core_user'];
        $param[':uniacid']   = $_W['uniacid'];

        $sql = ' select d.id,d.couponid,d.gettime,c.timelimit,c.timedays,c.timestart,c.timeend,c.thumb,c.couponname,c.enough,c.backtype,c.deduct, ' .
            ' c.discount,c.backmoney,c.backcredit,c.backredpack,c.bgcolor,c.thumb,c.merchid,c.limitgoodcatetype,c.limitgoodtype,c.limitgoodcateids, ' .
            ' c.limitgoodids ' .
            ' from ' . tablename('superdesk_shop_coupon_data') . ' d' .// TODO 标志 楼宇之窗 openid superdesk_shop_coupon_data 已处理
            ' left join ' . tablename('superdesk_shop_coupon') . ' c on d.couponid = c.id' .
            ' where ' .
            '       d.openid=:openid ' .
            '       and d.core_user=:core_user ' .
            '       and d.uniacid=:uniacid ' .
            '       and c.merchid=0 ' .
            '       and d.merchid=0 ' .
            '       and c.coupontype=' . $type .
            '       and d.used=0 ';

        if ($type == 1) {
            $sql .= 'and ' . $money . '>=c.enough ';
        }

        $sql  .= ' and (   (c.timelimit = 0 and ( c.timedays=0 or c.timedays*86400 + d.gettime >=unix_timestamp() ) )  or  (c.timelimit =1 and c.timestart<=' . $time . ' && c.timeend>=' . $time . ')) order by d.gettime desc';
        $list = pdo_fetchall($sql, $param);

        if (!empty($merch_array)) {

            foreach ($merch_array as $key => $value) {

                $merchid = $key;

                if (0 < $merchid) {

                    $param[':merchid'] = $merchid;

                    $sql = ' select d.id,d.couponid,d.gettime,c.timelimit,c.timedays,c.timestart,c.timeend,c.thumb,c.couponname,c.enough,c.backtype,c.deduct, ' .
                        ' c.discount,c.backmoney,c.backcredit,c.backredpack,c.bgcolor,c.thumb,c.merchid,c.limitgoodcatetype,c.limitgoodtype,c.limitgoodcateids, ' .
                        ' c.limitgoodids  ' .
                        ' from ' . tablename('superdesk_shop_coupon_data') . ' d' .// TODO 标志 楼宇之窗 openid superdesk_shop_coupon_data 已处理
                        ' left join ' . tablename('superdesk_shop_coupon') . ' c on d.couponid = c.id' .
                        ' where d.openid=:openid ' .
                        '   and d.core_user=:core_user ' .
                        '   and d.uniacid=:uniacid ' .
                        '   and c.merchid=:merchid ' .
                        '   and d.merchid=:merchid ' .
                        '   and c.coupontype=' . $type .
                        '   and d.used=0 ' .
                        '   and (   ' .
                        '         ( c.timelimit = 0 ' .
                        '           and ( c.timedays=0 ' .
                        '                or c.timedays*86400 + d.gettime >=unix_timestamp() ' .
                        '              ) ' .
                        '         )  ' .
                        '         or  ' .
                        '         ( c.timelimit =1 ' .
                        '           and c.timestart<=' . $time . ' && c.timeend>=' . $time .
                        '         ) ' .
                        '       ) ' .
                        ' order by d.gettime desc';

                    $merch_list = pdo_fetchall($sql, $param);

                    if (!empty($merch_list)) {
                        $list = array_merge($list, $merch_list);
                    }
                }
            }
        }

        $goodlist = array();

        if (!empty($goods_array)) {

            foreach ($goods_array as $key => $value) {

                $goodparam[':uniacid'] = $_W['uniacid'];
                $goodparam[':id']      = $value['goodsid'];

                $sql =
                    'select id,cates,marketprice,merchid   ' .
                    ' from ' . tablename('superdesk_shop_goods') .
                    ' where ' .
                    '       uniacid=:uniacid ' .
                    '       and id =:id ' .
                    ' order by id desc ' .
                    ' LIMIT 1 ';

                $good = pdo_fetch($sql, $goodparam);

                $good['saletotal'] = $value['total'];
                $good['optionid']  = $value['optionid'];

                if (!empty($good)) {
                    $goodlist[] = $good;
                }
            }
        }
        if ($type == 0) {
            $list = $this->checkcouponlimit($list, $goodlist);
        }

        $list = set_medias($list, 'thumb');

        if (!empty($list)) {

            foreach ($list as &$row) {

                $row['thumb']   = tomedia($row['thumb']);
                $row['timestr'] = '永久有效';

                if (empty($row['timelimit'])) {
                    if (!empty($row['timedays'])) {
                        $row['timestr'] = date('Y-m-d H:i', $row['gettime'] + ($row['timedays'] * 86400));
                    }
                } else if ($time <= $row['timestart']) {
                    $row['timestr'] = date('Y-m-d H:i', $row['timestart']) . '-' . date('Y-m-d H:i', $row['timeend']);
                } else {
                    $row['timestr'] = date('Y-m-d H:i', $row['timeend']);
                }

                if ($row['backtype'] == 0) {
                    $row['backstr']   = '立减';
                    $row['css']       = 'deduct';
                    $row['backmoney'] = $row['deduct'];
                    $row['backpre']   = true;
                    if ($row['enough'] == '0') {
                        $row['color'] = 'org ';
                    } else {
                        $row['color'] = 'blue';
                    }
                } else if ($row['backtype'] == 1) {
                    $row['backstr']   = '折';
                    $row['css']       = 'discount';
                    $row['backmoney'] = $row['discount'];
                    $row['color']     = 'red ';
                } else if ($row['backtype'] == 2) {
                    if ($row['coupontype'] == '0') {
                        $row['color'] = 'red ';
                    } else {
                        $row['color'] = 'pink ';
                    }
                    if (0 < $row['backredpack']) {
                        $row['backstr']   = '返现';
                        $row['css']       = 'redpack';
                        $row['backmoney'] = $row['backredpack'];
                        $row['backpre']   = true;
                    } else if (0 < $row['backmoney']) {
                        $row['backstr']   = '返利';
                        $row['css']       = 'money';
                        $row['backmoney'] = $row['backmoney'];
                        $row['backpre']   = true;
                    } else if (!empty($row['backcredit'])) {
                        $row['backstr']   = '返积分';
                        $row['css']       = 'credit';
                        $row['backmoney'] = $row['backcredit'];
                    }
                }
            }
            unset($row);
        }
        return $list;
    }

    public function checkcouponlimit($list, $goodlist)
    {
        global $_W;

        foreach ($list as $key => $row) {

            $pass   = 0;
            $enough = 0;

            if (($row['limitgoodcatetype'] == 0) && ($row['limitgoodtype'] == 0) && ($row['enough'] == 0)) {

                $pass = 1;

            } else {
                foreach ($goodlist as $good) {

                    if ((0 < $row['merchid']) && (0 < $good['merchid']) && ($row['merchid'] != $good['merchid'])) {
                        continue;
                    }

                    $p            = 0;
                    $cates        = explode(',', $good['cates']);
                    $limitcateids = explode(',', $row['limitgoodcateids']);
                    $limitgoodids = explode(',', $row['limitgoodids']);

                    if (($row['limitgoodcatetype'] == 0) && ($row['limitgoodtype'] == 0)) {
                        $p = 1;
                    }

                    if ($row['limitgoodcatetype'] == 1) {
                        $result = array_intersect($cates, $limitcateids);
                        if (0 < count($result)) {
                            $p = 1;
                        }
                    }

                    if ($row['limitgoodtype'] == 1) {
                        $isin = in_array($good['id'], $limitgoodids);
                        if ($isin) {
                            $p = 1;
                        }
                    }

                    if ($p == 1) {
                        $pass = 1;
                    }

                    if ((0 < $row['enough']) && ($p == 1)) {

                        if (0 < $good['optionid']) {

                            $optionparam[':uniacid'] = $_W['uniacid'];
                            $optionparam[':id']      = $good['optionid'];

                            $sql =
                                'select  marketprice  ' .
                                ' from ' . tablename('superdesk_shop_goods_option') .
                                ' where ' .
                                '       uniacid=:uniacid ' .
                                '       and id =:id ' .
                                '       order by id desc ' .
                                ' LIMIT 1 ';

                            $option = pdo_fetch($sql, $optionparam);
                            if (!empty($option)) {
                                $enough += (double)$option['marketprice'] * $good['saletotal'];
                            }
                        } else {
                            $enough += (double)$good['marketprice'] * $good['saletotal'];
                        }
                    }
                }
                if ((0 < $row['enough']) && ($enough < $row['enough'])) {
                    $pass = 0;
                }
            }
            if ($pass == 0) {
                unset($list[$key]);
            }
        }
        return array_values($list);
    }

    public function payResult($logno)
    {
        global $_W;

        if (empty($logno)) {
            return error(-1);
        }

        $log = pdo_fetch(
            'SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_coupon_log') . // TODO 标志 楼宇之窗 openid superdesk_shop_coupon_log 不处理
            ' WHERE ' .
            '       logno=:logno ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':uniacid' => $_W['uniacid'],
                ':logno'   => $logno
            )
        );

        if (empty($log)) {
            return error(-1, '服务器错误!');
        }

        if (1 <= $log['status']) {
            return true;
        }

        $coupon = pdo_fetch(
            'select * ' .
            ' from ' . tablename('superdesk_shop_coupon') .
            ' where ' .
            '       id=:id ' .
            ' limit 1',
            array(
                ':id' => $log['couponid']
            )
        );

        $coupon = $this->setCoupon($coupon, time());

        if (empty($coupon['gettype'])) {
            return error(-1, '无法领取');
        }

        if ($coupon['total'] != -1) {
            if ($coupon['total'] <= 0) {
                return error(-1, '优惠券数量不足');
            }
        }

        if (!$coupon['canget']) {
            return error(-1, '您已超出领取次数限制');
        }

        if (empty($log['status'])) {

            $update = array();

            if ((0 < $coupon['credit']) && empty($log['creditstatus'])) {

                m('member')->setCredit($log['openid'], $log['core_user'],
                    'credit1', -$coupon['credit'], '购买优惠券扣除积分 ' . $coupon['credit']);

                $update['creditstatus'] = 1;
            }

            if ((0 < $coupon['money']) && empty($log['paystatus'])) {

                if ($log['paytype'] == 0) {

                    m('member')->setCredit($log['openid'], $log['core_user'],
                        'credit2', -$coupon['money'],
                        '购买优惠券扣除余额 ' .
                        $coupon['money'],
                        array(
                            'type'       => 5,
                            'createtime' => time(),
                            'orderid'    => $log['id']
                        )
                    );
                }
                $update['paystatus'] = 1;
            }

            $update['status'] = 1;

            pdo_update(
                'superdesk_shop_coupon_log', $update, array('id' => $log['id']));// TODO 标志 楼宇之窗 openid superdesk_shop_coupon_log 不处理

            $data = array(
                'uniacid'   => $_W['uniacid'],
                'merchid'   => $log['merchid'],
                'openid'    => $log['openid'],
                'core_user' => $log['core_user'],
                'couponid'  => $log['couponid'],
                'gettype'   => $log['getfrom'],
                'gettime'   => time()
            );
            pdo_insert('superdesk_shop_coupon_data', $data);// TODO 标志 楼宇之窗 openid shop_coupon_data 已处理

            $member = m('member')->getMember($log['openid'], $log['core_user']);

            $set = m('common')->getPluginset('coupon');

            $this->sendMessage($coupon, 1, $member);
        }

        $url = mobileUrl('member', NULL, true);

        if ($coupon['coupontype'] == 0) {
            $coupon['url'] = mobileUrl('goods', NULL, true);
        } else {
            $coupon['url'] = mobileUrl('member/recharge', NULL, true);
        }

        return $coupon;
    }

    public function sendMessage($coupon, $send_total, $member, $account = NULL)
    {
        global $_W;

        $articles = array();

        $title = str_replace('[nickname]', $member['nickname'], $coupon['resptitle']);
        $desc  = str_replace('[nickname]', $member['nickname'], $coupon['respdesc']);
        $title = str_replace('[total]', $send_total, $title);
        $desc  = str_replace('[total]', $send_total, $desc);
        $url   = ((empty($coupon['respurl']) ? mobileUrl('sale/coupon/my', NULL, true) : $coupon['respurl']));

        if (!empty($coupon['resptitle'])) {
            $articles[] = array(
                'title'       => urlencode($title),
                'description' => urlencode($desc),
                'url'         => $url,
                'picurl'      => tomedia($coupon['respthumb'])
            );
        }

        if (!empty($articles)) {

            $resp = m('message')->sendNews($member['openid'], $articles, $account);

            if (is_error($resp)) {

                $msg = array(
                    'keyword1' => array('value' => $title, 'color' => '#73a68d'),
                    'keyword2' => array('value' => $desc, 'color' => '#73a68d')
                );

                $ret = m('message')->sendCustomNotice($member['openid'], $msg, $url, $account);

                if (is_error($ret)) {

                    m('message')->sendCustomNotice($member['openid'], $msg, $url, $account);
                }
            }
        }
    }

    /**
     * 返钱信息
     *
     * @param $openid
     * @param $core_user
     * @param $coupon
     * @param $gives
     */
    public function sendBackMessage($openid, $core_user, $coupon, $gives)
    {
        global $_W;

        if (empty($gives)) {
            return;
        }

        $set = m('common')->getPluginset('coupon');

        $templateid = $set['templateid'];

        $content = '您的优惠券【' . $coupon['couponname'] . '】已返利 ';
        $givestr = '';

        if (isset($gives['credit'])) {
            $givestr .= ' ' . $gives['credit'] . '个积分';
        }

        if (isset($gives['money'])) {
            if (!empty($givestr)) {
                $givestr .= '，';
            }
            $givestr .= $gives['money'] . '元余额';
        }

        if (isset($gives['redpack'])) {
            if (!empty($givestr)) {
                $givestr .= '，';
            }
            $givestr .= $gives['redpack'] . '元现金';
        }

        $content .= $givestr;

        $content .= '，请查看您的账户，谢谢!';

        $msg = array(
            'keyword1' => array('value' => '优惠券返利', 'color' => '#73a68d'),
            'keyword2' => array('value' => $content, 'color' => '#73a68d')
        );

        $url = mobileUrl('member', NULL, true);

        if (!empty($templateid)) {
            m('message')->sendTplNotice($openid, $templateid, $msg, $url);
            return;
        }

        m('message')->sendCustomNotice($openid, $msg, $url);
    }

    public function sendReturnMessage($openid, $core_user, $coupon)// TODO 标志 楼宇之窗 openid shop_coupon_data 已处理
    {
        global $_W;

        $set = m('common')->getPluginset('coupon');

        $templateid = $set['templateid'];

        $msg = array(
            'keyword1' => array('value' => '优惠券退回', 'color' => '#73a68d'),
            'keyword2' => array('value' => '您的优惠券【' . $coupon['couponname'] . '】已退回您的账户，您可以再次使用, 谢谢!', 'color' => '#73a68d')
        );

        $url = mobileUrl('sale/coupon/my', NULL, true);

        if (!empty($templateid)) {
            m('message')->sendTplNotice($openid, $templateid, $msg, $url);
            return;
        }

        m('message')->sendCustomNotice($openid, $msg, $url);
    }

    public function useRechargeCoupon($log)
    {
        global $_W;

        if (empty($log['couponid'])) {
            return;
        }

        $data = pdo_fetch(
            'select id,openid,couponid,used ' .
            ' from ' . tablename('superdesk_shop_coupon_data') . // TODO 标志 楼宇之窗 openid shop_coupon_data 不处理
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':id'      => $log['couponid'],
                ':uniacid' => $_W['uniacid']
            )
        );

        if (empty($data)) {
            return;
        }

        if (!empty($data['used'])) {
            return;
        }

        $coupon = pdo_fetch(
            'select enough,backcredit,backmoney,backredpack,couponname ' .
            ' from ' . tablename('superdesk_shop_coupon') .
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':id'      => $data['couponid'],
                ':uniacid' => $_W['uniacid']
            )
        );

        if (empty($coupon)) {
            return;
        }

        if ((0 < $coupon['enough']) && ($log['money'] < $coupon['enough'])) {
            return;
        }

        $gives = array();

        $backcredit = $coupon['backcredit'];
        if (!empty($backcredit)) {
            if (strexists($backcredit, '%')) {
                $backcredit = intval((floatval(str_replace('%', '', $backcredit)) / 100) * $log['money']);
            } else {
                $backcredit = intval($backcredit);
            }
            if (0 < $backcredit) {
                $gives['credit'] = $backcredit;
                // TODO 标志 楼宇之窗 openid shop_order 待处理
                m('member')->setCredit($data['openid'], $data['core_user'],
                    'credit1',
                    $backcredit,
                    array(0, '充值优惠券返积分')
                );
            }
        }

        $backmoney = $coupon['backmoney'];
        if (!empty($backmoney)) {
            if (strexists($backmoney, '%')) {
                $backmoney = round(floatval((floatval(str_replace('%', '', $backmoney)) / 100) * $log['money']), 2);
            } else {
                $backmoney = round(floatval($backmoney), 2);
            }
            if (0 < $backmoney) {
                $gives['money'] = $backmoney;
                // TODO 标志 楼宇之窗 openid shop_order 待处理
                m('member')->setCredit($data['openid'], $data['core_user'],
                    'credit2',
                    $backmoney,
                    array(0, '充值优惠券返利')
                );
            }
        }
        $backredpack = $coupon['backredpack'];
        if (!empty($backredpack)) {

            if (strexists($backredpack, '%')) {
                $backredpack = round(floatval((floatval(str_replace('%', '', $backredpack)) / 100) * $log['money']), 2);
            } else {
                $backredpack = round(floatval($backredpack), 2);
            }

            if (0 < $backredpack) {
                $gives['redpack'] = $backredpack;
                $backredpack      = intval($backredpack * 100);

                // TODO 标志 楼宇之窗 openid shop_order 待处理
                m('finance')->pay($data['openid'], $data['core_user'],
                    1, $backredpack, '', '充值优惠券-返现金', false);
            }
        }

        pdo_update('superdesk_shop_coupon_data',// TODO 标志 楼宇之窗 openid shop_coupon_data 不处理
            array(
                'used'    => 1,
                'usetime' => time(),
                'ordersn' => $log['logno']
            ),
            array(
                'id' => $data['id']
            )
        );

        $this->sendBackMessage($log['openid'], $log['core_user'],
            $coupon,
            $gives
        );
    }

    public function consumeCouponCount($openid, $core_user = 0, $money = 0, $merch_array, $goods_array)// TODO 标志 楼宇之窗 openid superdesk_shop_coupon_data 已处理
    {
        global $_W;
        global $_GPC;

        $time  = time();
        $param = array();

        $param[':openid']    = $openid;
        $param[':core_user'] = $core_user;
        $param[':uniacid']   = $_W['uniacid'];

        $sql  = 'select d.id,d.couponid,c.enough,c.merchid,c.limitgoodtype,c.limitgoodcatetype,c.limitgoodcateids,c.limitgoodids  ' .
            'from ' . tablename('superdesk_shop_coupon_data') . ' d';// TODO 标志 楼宇之窗 openid superdesk_shop_coupon_data 已处理
        $sql  .= ' left join ' . tablename('superdesk_shop_coupon') . ' c on d.couponid = c.id';
        $sql  .= ' where d.openid=:openid and d.core_user=:core_user and d.uniacid=:uniacid and c.merchid=0 and d.merchid=0 and c.coupontype=0 and d.used=0 ';
        $sql  .= ' and (   (c.timelimit = 0 and ( c.timedays=0 or c.timedays*86400 + d.gettime >=unix_timestamp() ) )  or  (c.timelimit =1 and c.timestart<=' . $time . ' && c.timeend>=' . $time . ')) order by d.gettime desc';
        $list = pdo_fetchall($sql, $param);

        if (!empty($merch_array)) {

            foreach ($merch_array as $key => $value) {

                $merchid = $key;

                if (0 < $merchid) {
                    $ggprice           = $value['ggprice'];
                    $param[':merchid'] = $merchid;
                    $sql               = 'select d.id,d.couponid,c.enough,c.merchid,c.limitgoodtype,c.limitgoodcatetype,c.limitgoodcateids,c.limitgoodids ' .
                        ' from ' . tablename('superdesk_shop_coupon_data') . ' d';// TODO 标志 楼宇之窗 openid superdesk_shop_coupon_data 已处理
                    $sql               .= ' left join ' . tablename('superdesk_shop_coupon') . ' c on d.couponid = c.id';
                    $sql               .= ' where d.openid=:openid and d.core_user=:core_user and d.uniacid=:uniacid and c.merchid=:merchid and d.merchid=:merchid and c.coupontype=0  and d.used=0 ';
                    $sql               .= ' and (   (c.timelimit = 0 and ( c.timedays=0 or c.timedays*86400 + d.gettime >=unix_timestamp() ) )  or  (c.timelimit =1 and c.timestart<=' . $time . ' && c.timeend>=' . $time . '))';
                    $merch_list        = pdo_fetchall($sql, $param);
                    if (!empty($merch_list)) {
                        $list = array_merge($list, $merch_list);
                    }
                }
            }
        }

        $goodlist = array();

        if (!empty($goods_array)) {

            foreach ($goods_array as $key => $value) {

                $goodparam[':uniacid'] = $_W['uniacid'];
                $goodparam[':id']      = $value['goodsid'];

                $sql =
                    'select id,cates,marketprice,merchid  ' .
                    ' from ' . tablename('superdesk_shop_goods') .
                    ' where ' .
                    '       uniacid=:uniacid ' .
                    '       and id =:id ' .
                    ' order by id desc ' .
                    ' LIMIT 1 ';

                $good = pdo_fetch($sql, $goodparam);

                $good['saletotal'] = $value['total'];
                $good['optionid']  = $value['optionid'];

                if (!empty($good)) {
                    $goodlist[] = $good;
                }
            }
        }

        $list = $this->checkcouponlimit($list, $goodlist);

        return count($list);
    }

    public function rechargeCouponCount($openid, $core_user, $money = 0)// TODO 标志 楼宇之窗 openid superdesk_shop_coupon_data 已处理
    {
        global $_W;
        global $_GPC;

        $time = time();
        $sql  =
            'select count(*) ' .
            ' from ' . tablename('superdesk_shop_coupon_data') . ' d ' .// TODO 标志 楼宇之窗 openid superdesk_shop_coupon_data 已处理
            '       left join ' . tablename('superdesk_shop_coupon') . ' c on d.couponid = c.id ' .
            ' where ' .
            '       d.openid=:openid ' .
            '       and d.core_user=:core_user ' .
            '       and d.uniacid=:uniacid ' .
            '       and c.coupontype=1 ' .
            '       and ' . $money . '>=c.enough ' .
            '       and d.used=0 ' .
            '       and (   (c.timelimit = 0 and ( c.timedays=0 or c.timedays*86400 + d.gettime >=unix_timestamp() ) )  or  (c.timelimit =1 and c.timestart<=' . $time . ' && c.timeend>=' . $time . '))';

        return pdo_fetchcolumn(
            $sql,
            array(
                ':openid'    => $openid,
                ':core_user' => $core_user,
                ':uniacid'   => $_W['uniacid']
            )
        );
    }

    public function useConsumeCoupon($orderid = 0)
    {
        global $_W;
        global $_GPC;

        if (empty($orderid)) {
            return;
        }

        $order = pdo_fetch(
            'select ordersn,createtime,couponid ' .
            ' from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 不处理
            ' where ' .
            '       id=:id ' .
            '       and status>=0 ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':id'      => $orderid,
                ':uniacid' => $_W['uniacid']
            )
        );

        if (empty($order)) {
            return;
        }

        $coupon = false;
        if (!empty($order['couponid'])) {
            $coupon = $this->getCouponByDataID($order['couponid']);
        }

        if (empty($coupon)) {
            return;
        }

        pdo_update('superdesk_shop_coupon_data', // TODO 标志 楼宇之窗 openid shop_coupon_data 不处理
            array(
                'used'    => 1,
                'usetime' => $order['createtime'],
                'ordersn' => $order['ordersn']
            ),
            array(
                'id' => $order['couponid']
            )
        );
    }

    /**
     * 返还 消费的coupon
     *
     * @param $order
     */
    public function returnConsumeCoupon($order)
    {
        global $_W;

        if (!is_array($order)) {
            $order = pdo_fetch(
                'select id,openid,core_user,ordersn,createtime,couponid,status,finishtime ' .
                ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
                ' where ' .
                '       id=:id ' .
                '       and status>=0 ' .
                '       and uniacid=:uniacid ' .
                ' limit 1',
                array(
                    ':id'      => intval($order),
                    ':uniacid' => $_W['uniacid']
                )
            );
        }

        if (empty($order)) {
            return;
        }

        $coupon = $this->getCouponByDataID($order['couponid']);

        if (empty($coupon)) {
            return;
        }

        if (!empty($coupon['returntype'])) {

            if (!empty($coupon['used'])) {

                pdo_update('superdesk_shop_coupon_data', // TODO 标志 楼宇之窗 openid shop_coupon_data 不处理
                    array(
                        'used'    => 0,
                        'usetime' => 0,
                        'ordersn' => ''
                    ),
                    array(
                        'id' => $order['couponid']
                    )
                );

                // TODO 标志 楼宇之窗 openid shop_order 待处理
                $this->sendReturnMessage($order['openid'], $order['core_user'], $coupon);
            }
        }
    }

    /**
     * 退还 消费的coupon
     *
     * @param $orderid
     */
    public function backConsumeCoupon($orderid)
    {
        global $_W;

        if (!is_array($orderid)) {
            $order = pdo_fetch(
                ' select id,openid,core_user,ordersn,createtime,couponid,couponmerchid,status,finishtime,`virtual`,isparent,parentid,coupongoodprice  ' .
                ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
                ' where ' .
                '       id=:id ' .
                '       and status>=0 ' .
                '       and couponid >0 ' .
                '       and uniacid=:uniacid ' .
                ' limit 1',
                array(
                    ':id'      => intval($orderid),
                    ':uniacid' => $_W['uniacid']
                )
            );
        }

        if (empty($order)) {
            return;
        }

        $couponid      = $order['couponid'];
        $couponmerchid = $order['couponmerchid'];
        $isparent      = $order['isparent'];
        $parentid      = $order['parentid'];
        $finishtime    = $order['finishtime'];

        if (empty($couponid)) {
            return;
        }

        $coupon = $this->getCouponByDataID($order['couponid']);

        if (empty($coupon)) {
            return;
        }

        if (!empty($coupon['back'])) {
            return;
        }

        $coupongoodprice = 0;
        if ($parentid == 0) {
            $coupongoodprice = $order['coupongoodprice'];
        }

        if (($isparent == 1) || ($parentid != 0)) {

            $all_done = 1;

            if ($isparent == 1) { // 主单

                if (0 < $couponmerchid) {

                    $sql =
                        ' select id,openid,core_user,ordersn,createtime,couponid,couponmerchid,status,finishtime,`virtual`,isparent,parentid ' .
                        ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
                        ' where ' .
                        '       parentid=:parentid ' .
                        '       and couponmerchid=:couponmerchid ' .
                        '       and status>=0 ' .
                        '       and uniacid=:uniacid ' .
                        ' limit 1';

                    $order = pdo_fetch(
                        $sql,
                        array(
                            ':parentid'      => $orderid,
                            ':couponmerchid' => $couponmerchid,
                            ':uniacid'       => $_W['uniacid']
                        )
                    );

                    if (empty($order)) {
                        return;
                    }

                    if ($order['status'] != 3) {
                        $all_done = 0;
                    } else {
                        $finishtime = $order['finishtime'];
                    }

                } else {

                    $list = m('order')->getChildOrder($orderid);

                }

            } else if (0 < $couponmerchid) {

                if ($order['status'] != 3) {
                    $all_done = 0;
                } else {
                    $finishtime = $order['finishtime'];
                }

            } else {

                $list = m('order')->getChildOrder($parentid);

            }
            if (!empty($list)) {

                foreach ($list as $k => $v) {

                    if (($v['status'] != 3) && (0 < $v['couponid'])) {
                        $all_done = 0;
                    } else if ($finishtime < $v['finishtime']) {
                        $finishtime = $v['finishtime'];
                    }
                }
            }
        }

        if (($parentid != 0) && ($couponmerchid == 0)) {

            if ($all_done == 1) {

                $sql =
                    ' select id,openid,core_user,ordersn,createtime,couponid,couponmerchid,status,finishtime,`virtual`,isparent,parentid ' .
                    ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
                    ' where ' .
                    '       id=:id ' .
                    '       and status>=0 ' .
                    '       and uniacid=:uniacid ' .
                    ' limit 1';

                $order = pdo_fetch(
                    $sql,
                    array(
                        ':id'      => $parentid,
                        ':uniacid' => $_W['uniacid']
                    )
                );

                if (empty($order)) {
                    return;
                }
            }
        }

        $backcredit  = $coupon['backcredit'];
        $backmoney   = $coupon['backmoney'];
        $backredpack = $coupon['backredpack'];

        $gives   = array();
        $canback = false;

        if (($order['status'] == 1) && ($coupon['backwhen'] == 2)) {

            $canback = true;

        } else {

            $is_done = 0;

            if (($isparent == 1) || ($parentid != 0)) {
                if ($all_done == 1) {
                    $is_done = 1;
                }
            } else if ($order['status'] == 3) {
                $is_done = 1;
            }

            if ($is_done == 1) {

                if (!empty($order['virtual'])) {

                    $canback = true;

                } else if ($coupon['backwhen'] == 1) {

                    $canback = true;

                } else if ($coupon['backwhen'] == 0) {

                    $canback    = true;
                    $tradeset   = m('common')->getSysset('trade');
                    $refunddays = intval($tradeset['refunddays']);

                    if (0 < $refunddays) {
                        $days = intval((time() - $finishtime) / 3600 / 24);
                        if ($days <= $refunddays) {
                            $canback = false;
                        }
                    }
                }
            }
        }

        if ($canback) {

            if (0 < $parentid) {

                $ordermoney = pdo_fetchcolumn(
                    'select coupongoodprice ' .
                    ' from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 不处理
                    ' where ' .
                    '       id=:id ' .
                    '       and status>=0 ' .
                    '       and couponid >0 ' .
                    '       and uniacid=:uniacid ' .
                    ' limit 1',
                    array(
                        ':id'      => intval($parentid),
                        ':uniacid' => $_W['uniacid']
                    )
                );
            } else {
                $ordermoney = $coupongoodprice;
            }

            if ($ordermoney == 0) {

                $sql =
                    'select ifnull( sum(og.realprice),0) ' .
                    'from ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 已处理
                    ' left join ' . tablename('superdesk_shop_order') . ' o on';// TODO 标志 楼宇之窗 openid shop_order 已处理

                if (($couponmerchid == 0) && ($isparent == 1)) {
                    $sql .= ' o.id=og.parentorderid ';
                } else {
                    $sql .= ' o.id=og.orderid ';
                }

                $sql .=
                    ' where ' .
                    '       o.id=:orderid ' .
                    '       and o.openid=:openid ' .
                    '       and o.core_user=:core_user ' .
                    '       and o.uniacid=:uniacid ';

                $ordermoney = pdo_fetchcolumn(
                    $sql,
                    array(
                        ':uniacid'   => $_W['uniacid'],
                        ':openid'    => $order['openid'],
                        ':core_user' => $order['core_user'],
                        ':orderid'   => $order['id'])
                );
            }

            if (!empty($backcredit)) {

                if (strexists($backcredit, '%')) {
                    $backcredit = intval((floatval(str_replace('%', '', $backcredit)) / 100) * $ordermoney);
                } else {
                    $backcredit = intval($backcredit);
                }

                if (0 < $backcredit) {

                    $gives['credit'] = $backcredit;

                    m('member')->setCredit($order['openid'], $order['core_user'],
                        'credit1',
                        $backcredit,
                        array(0, '充值优惠券返积分')
                    );
                }
            }

            if (!empty($backmoney)) {

                if (strexists($backmoney, '%')) {
                    $backmoney = round(floatval((floatval(str_replace('%', '', $backmoney)) / 100) * $ordermoney), 2);
                } else {
                    $backmoney = round(floatval($backmoney), 2);
                }

                if (0 < $backmoney) {

                    $gives['money'] = $backmoney;

                    m('member')->setCredit($order['openid'], $order['core_user'],
                        'credit2',
                        $backmoney,
                        array(0, '购物优惠券返利')
                    );
                }
            }

            if (!empty($backredpack)) {

                if (strexists($backredpack, '%')) {

                    $backredpack = round(floatval((floatval(str_replace('%', '', $backredpack)) / 100) * $ordermoney), 2);

                } else {

                    $backredpack = round(floatval($backredpack), 2);
                }

                if (0 < $backredpack) {

                    $gives['redpack'] = $backredpack;
                    $backredpack      = intval($backredpack * 100);

                    m('finance')->pay($order['openid'], $order['core_user'],
                        1,
                        $backredpack,
                        '',
                        '购物优惠券-返现金',
                        false
                    );
                }
            }

            pdo_update(
                'superdesk_shop_coupon_data', // TODO 标志 楼宇之窗 openid shop_coupon_data 不处理
                array(
                    'back'     => 1,
                    'backtime' => time()
                ),
                array(
                    'id' => $order['couponid']
                )
            );

            // TODO 标志 楼宇之窗 openid shop_order 已处理
            $this->sendBackMessage($order['openid'], $order['core_user'], $coupon, $gives);
        }
    }

    public function getCoupon($couponid = 0)
    {
        global $_W;
        return pdo_fetch(
            'select * ' .
            ' from ' . tablename('superdesk_shop_coupon') .
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':id'      => $couponid,
                ':uniacid' => $_W['uniacid']
            )
        );
    }

    public function getCouponByDataID($dataid = 0)
    {
        global $_W;

        $data = pdo_fetch(
            'select ' .
            '       id,openid,couponid,used,back,backtime ' .
            ' from ' . tablename('superdesk_shop_coupon_data') . // TODO 标志 楼宇之窗 openid shop_coupon_data 不处理
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':id'      => $dataid,
                ':uniacid' => $_W['uniacid']
            )
        );

        if (empty($data)) {
            return false;
        }

        $coupon = pdo_fetch(
            'select * ' .
            ' from ' . tablename('superdesk_shop_coupon') .
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':id'      => $data['couponid'],
                ':uniacid' => $_W['uniacid']
            )
        );

        if (empty($coupon)) {
            return false;
        }

        $coupon['back']     = $data['back'];
        $coupon['backtime'] = $data['backtime'];
        $coupon['used']     = $data['used'];
        $coupon['usetime']  = $data['usetime'];

        return $coupon;
    }

    public function setCoupon($row, $time, $withOpenid = true)
    {
        global $_W;

        if ($withOpenid) {
            $openid    = $_W['openid'];
            $core_user = $_W['core_user'];
        }

        $row['free']      = false;
        $row['past']      = false;
        $row['thumb']     = tomedia($row['thumb']);
        $row['merchname'] = '';
        $row['total']     = $this->get_last_count($row['id']);

        if (0 < $row['merchid']) {
            $merch_plugin = p('merch');
            if ($merch_plugin) {
                $merch_user = $merch_plugin->getListUserOne($row['merchid']);
                if (!empty($merch_user)) {
                    $row['merchname'] = $merch_user['merchname'];
                }
            }
        }

        if ((0 < $row['money']) && (0 < $row['credit'])) {
            $row['getstatus']  = 0;
            $row['gettypestr'] = '购买';
        } else if (0 < $row['money']) {
            $row['getstatus']  = 1;
            $row['gettypestr'] = '购买';
        } else if (0 < $row['credit']) {
            $row['getstatus']  = 2;
            $row['gettypestr'] = '兑换';
        } else {
            $row['getstatus']  = 3;
            $row['gettypestr'] = '领取';
        }

        $row['timestr'] = '0';

        if (empty($row['timelimit'])) {

            if (!empty($row['timedays'])) {
                $row['timestr'] = 1;
            }

        } else if ($time <= $row['timestart']) {

            $row['timestr'] = date('Y-m-d', $row['timestart']) . '-' . date('Y-m-d', $row['timeend']);

        } else {

            $row['timestr'] = date('Y-m-d', $row['timeend']);

        }

        $row['css'] = 'deduct';

        if ($row['backtype'] == 0) {

            $row['backstr']    = '立减';
            $row['css']        = 'deduct';
            $row['backpre']    = true;
            $row['_backmoney'] = $row['deduct'];

        } else if ($row['backtype'] == 1) {

            $row['backstr']    = '折';
            $row['css']        = 'discount';
            $row['_backmoney'] = $row['discount'];

        } else if ($row['backtype'] == 2) {

            if (!empty($row['backredpack'])) {
                $row['backstr']    = '返现';
                $row['css']        = 'redpack';
                $row['backpre']    = true;
                $row['_backmoney'] = $row['backredpack'];
            } else if (!empty($row['backmoney'])) {
                $row['backstr']    = '返利';
                $row['css']        = 'money';
                $row['backpre']    = true;
                $row['_backmoney'] = $row['backmoney'];
            } else if (!empty($row['backcredit'])) {
                $row['backstr']    = '返积分';
                $row['css']        = 'credit';
                $row['_backmoney'] = $row['backcredit'];
            }
        }

        if ($withOpenid) {

            $row['cangetmax'] = -1;
            $row['canget']    = true;

            if (($row['total'] != -1) && ($row['total'] <= 0)) {
                $row['canget']    = false;
                $row['cangetmax'] = -2;
                return $row;
            }
            if (0 < $row['getmax']) {

                $gets = pdo_fetchcolumn(
                    'select count(*) ' .
                    ' from ' . tablename('superdesk_shop_coupon_data') .// TODO 标志 楼宇之窗 openid superdesk_shop_coupon_data 已处理
                    ' where ' .
                    '       couponid=:couponid ' .
                    '       and uniacid=:uniacid ' .
                    '       and openid=:openid ' .
                    '       and core_user=:core_user ' .
                    '       and gettype=1 ' .
                    ' limit 1',
                    array(
                        ':couponid'  => $row['id'],
                        ':uniacid'   => $_W['uniacid'],
                        ':openid'    => $openid,
                        ':core_user' => $core_user,
                    )
                );

                $row['cangetmax'] = $row['getmax'] - $gets;
                if ($row['cangetmax'] <= 0) {
                    $row['cangetmax'] = 0;
                    $row['canget']    = false;
                }
            }
        }

        return $row;
    }

    public function setMyCoupon($row, $time)
    {
        global $_W;

        $row['past']      = false;
        $row['thumb']     = tomedia($row['thumb']);
        $row['merchname'] = '';

        if (0 < $row['merchid']) {
            $merch_plugin = p('merch');
            if ($merch_plugin) {
                $merch_user = $merch_plugin->getListUserOne($row['merchid']);
                if (!empty($merch_user)) {
                    $row['merchname'] = $merch_user['merchname'];
                }
            }
        }

        $row['timestr'] = '';

        if (empty($row['timelimit'])) {

            if (!empty($row['timedays'])) {
                $row['timestr'] = date('Y-m-d', $row['gettime'] + ($row['timedays'] * 86400));
                if (($row['gettime'] + ($row['timedays'] * 86400)) < $time) {
                    $row['past'] = true;
                }
            }

        } else {

            if ($time <= $row['timestart']) {
                $row['timestr'] = date('Y-m-d H:i', $row['timestart']) . '-' . date('Y-m-d', $row['timeend']);
            } else {
                $row['timestr'] = date('Y-m-d H:i', $row['timeend']);
            }
            if ($row['timeend'] < $time) {
                $row['past'] = true;
            }
        }

        $row['css'] = 'deduct';

        if ($row['backtype'] == 0) {

            $row['backstr']    = '立减';
            $row['css']        = 'deduct';
            $row['backpre']    = true;
            $row['_backmoney'] = $row['deduct'];

        } else if ($row['backtype'] == 1) {

            $row['backstr']    = '折';
            $row['css']        = 'discount';
            $row['_backmoney'] = $row['discount'];

        } else if ($row['backtype'] == 2) {

            if (!empty($row['backredpack'])) {
                $row['backstr']    = '返现';
                $row['css']        = 'redpack';
                $row['backpre']    = true;
                $row['_backmoney'] = $row['backredpack'];
            } else if (!empty($row['backmoney'])) {
                $row['backstr']    = '返利';
                $row['css']        = 'money';
                $row['backpre']    = true;
                $row['_backmoney'] = $row['backmoney'];
            } else if (!empty($row['backcredit'])) {
                $row['backstr']    = '返积分';
                $row['css']        = 'credit';
                $row['_backmoney'] = $row['backcredit'];
            }
        }

        if ($row['past']) {
            $row['css'] = 'past';
        }

        return $row;
    }

    public function setShare()
    {
        global $_W;
        global $_GPC;

        $set = m('common')->getPluginset('coupon');

        $url = mobileUrl('sale/coupon', NULL, true);

        $_W['shopshare'] = array(
            'title'  => $set['title'],
            'imgUrl' => tomedia($set['icon']),
            'desc'   => $set['desc'],
            'link'   => $url
        );

        if (p('commission')) {

            $pset = p('commission')->getSet();

            if (!empty($pset['level'])) {

                $member = m('member')->getMember($_W['openid'], $_W['core_user']);

                if (!empty($member) && ($member['status'] == 1) && ($member['isagent'] == 1)) {

                    $_W['shopshare']['link'] = $url . '&mid=' . $member['id'];

                    if (empty($member['realname']) || empty($member['mobile'])) {
                        $trigger = true;
                    }

                } else if (!empty($_GPC['mid'])) {

                    $_W['shopshare']['link'] = $url . '&mid=' . $_GPC['id'];

                }
            }
        }
    }

    public function perms()
    {
        return array(
            'coupon' => array(
                'text'     => $this->getName(),
                'isplugin' => true,
                'child'    => array(
                    'coupon'   => array(
                        'text'   => '优惠券',
                        'view'   => '查看',
                        'add'    => '添加优惠券-log',
                        'edit'   => '编辑优惠券-log',
                        'delete' => '删除优惠券-log',
                        'send'   => '发放优惠券-log'
                    ),
                    'category' => array(
                        'text'   => '分类',
                        'view'   => '查看',
                        'add'    => '添加分类-log',
                        'edit'   => '编辑分类-log',
                        'delete' => '删除分类-log'
                    ),
                    'log'      => array(
                        'text'   => '优惠券记录',
                        'view'   => '查看',
                        'export' => '导出-log'
                    ),
                    'center'   => array(
                        'text' => '领券中心设置',
                        'view' => '查看设置',
                        'save' => '保存设置-log'
                    ),
                    'set'      => array(
                        'text' => '基础设置',
                        'view' => '查看设置',
                        'save' => '保存设置-log')
                )
            )
        );
    }
}