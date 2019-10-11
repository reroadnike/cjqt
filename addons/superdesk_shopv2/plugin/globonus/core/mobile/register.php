<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

require SUPERDESK_SHOPV2_PLUGIN . 'globonus/core/page_login_mobile.php';

class Register_SuperdeskShopV2Page extends GlobonusMobileLoginPage
{
    public function main()
    {
        global $_W;
        global $_GPC;
        $set    = set_medias($this->set, 'regbg');
        $member = m('member')->getMember($_W['openid'], $_W['core_user']);

        if (($member['ispartner'] == 1) && ($member['partnerstatus'] == 1)) {
            header('location: ' . mobileUrl('globonus'));
            exit();
        }


        if ($member['agentblack'] || $member['partnerstatus']) {
            include $this->template();
            exit();
        }

        $apply_set                  = array();
        $apply_set['open_protocol'] = $set['open_protocol'];

        if (empty($set['applytitle'])) {
            $apply_set['applytitle'] = '股东申请协议';
        } else {
            $apply_set['applytitle'] = $set['applytitle'];
        }

        $template_flag  = 0;
        $diyform_plugin = p('diyform');

        if ($diyform_plugin) {

            $set_config = $diyform_plugin->getSet();

            $globonus_diyform_open = $set_config['globonus_diyform_open'];

            if ($globonus_diyform_open == 1) {

                $template_flag = 1;
                $diyform_id    = $set_config['globonus_diyform'];

                if (!empty($diyform_id)) {

                    $formInfo     = $diyform_plugin->getDiyformInfo($diyform_id);
                    $fields       = $formInfo['fields'];
                    $diyform_data = iunserializer($member['diyglobonusdata']);
                    $f_data       = $diyform_plugin->getDiyformData($diyform_data, $fields, $member);
                }
            }
        }


        if ($_W['ispost']) {

            // 成为股东条件
            // 后台指定 0 为不开启 自主注册
            if ($set['become'] != '1') {
                show_json(0, '未开启' . $set['texts']['partner'] . '注册!');
            }

            // 成为股东条件 申请 1
            $become_check  = intval($set['become_check']);

            $ret['status'] = $become_check;

            if ($template_flag == 1) {

                $memberdata = $_GPC['memberdata'];

                $insert_data = $diyform_plugin->getInsertData($fields, $memberdata);

                $data = $insert_data['data'];

                $m_data = $insert_data['m_data'];

                $mc_data = $insert_data['mc_data'];

                $m_data['diyglobonusid']     = $diyform_id;
                $m_data['diyglobonusfields'] = iserializer($fields);
                $m_data['diyglobonusdata']   = $data;
                $m_data['ispartner']         = 1;
                $m_data['partnerstatus']     = $become_check;
                $m_data['partnertime']       = ($become_check == 1 ? time() : 0);

                pdo_update(
                    'superdesk_shop_member',
                    $m_data,
                    array(
                        'id' => $member['id']
                    )
                );

                if ($become_check == 1) {

                    $this->model->sendMessage($member['openid'], array('nickname' => $member['nickname'], 'partnertime' => $m_data['partnertime']), TM_GLOBONUS_BECOME);
                }


                if (!empty($member['uid'])) {

                    if (!empty($mc_data)) {
                        m('member')->mc_update($member['uid'], $mc_data);
                    }
                }

            } else {

                $data = array(
                    'ispartner'     => 1,
                    'partnerstatus' => $become_check,
                    'realname'      => $_GPC['realname'],
                    'mobile'        => $_GPC['mobile'],
                    'weixin'        => $_GPC['weixin'],
                    'partnertime'   => ($become_check == 1 ? time() : 0)
                );

                pdo_update('superdesk_shop_member', $data, array('id' => $member['id']));

                if (!empty($member['uid'])) {
                    m('member')->mc_update($member['uid'], array('realname' => $data['realname'], 'mobile' => $data['mobile']));
                }

            }

            show_json(1, array('check' => $become_check));
        }

        // 应用 -> 基础设置 -> 股东资格 -> 成为股东条件 -> 消费次数  消费金额  购买商品 -> 消费条件统计的方式 付款后 1 完成后 3
        $order_status = ((intval($set['become_order']) == 0 ? 1 : 3));

        // 应用 -> 基础设置 -> 股东资格 -> 成为股东条件 -> 消费次数  消费金额  购买商品 -> 是否需要审核
        $become_check = intval($set['become_check']);

        $to_check_partner = false;


        // 成为股东条件
        // 后台指定 0
        // 申请 1
        // 消费次数 2
        // 消费金额 3
        // 购买商品 4
        if ($set['become'] == '2') {

            $status = 1;

            $ordercount = pdo_fetchcolumn(
                'select count(*) ' .
                ' from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 已处理
                ' where ' .
                '       uniacid=:uniacid ' .
                '       and openid=:openid ' .
                '       and core_user=:core_user ' .
                '       and status>=' . $order_status .
                ' limit 1',
                array(
                    ':uniacid'   => $_W['uniacid'],
                    ':openid'    => $_W['openid'],
                    ':core_user' => $_W['core_user']
                )
            );

            if ($ordercount < intval($set['become_ordercount'])) {
                $status           = 0;
                $order_count      = number_format($ordercount, 0);
                $order_totalcount = number_format($set['become_ordercount'], 0);
            } else {
                $to_check_partner = true;
            }

        } else if ($set['become'] == '3') {

            $status = 1;

            $moneycount = pdo_fetchcolumn(
                ' select sum(goodsprice) ' .
                ' from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 已处理
                ' where ' .
                '       uniacid=:uniacid ' .
                '       and openid=:openid ' .
                '       and core_user=:core_user ' .
                '       and status>=' . $order_status .
                ' limit 1',
                array(
                    ':uniacid'   => $_W['uniacid'],
                    ':openid'    => $_W['openid'],
                    ':core_user' => $_W['core_user']
                )
            );

            if ($moneycount < floatval($set['become_moneycount'])) {

                $status           = 0;
                $money_count      = number_format($moneycount, 2);
                $money_totalcount = number_format($set['become_moneycount'], 2);

            } else {
                $to_check_partner = true;
            }

        } else if ($set['become'] == 4) {

            $goods = pdo_fetch(
                'select id,title,thumb,marketprice ' .
                ' from' . tablename('superdesk_shop_goods') .
                ' where ' .
                '       id=:id ' .
                '       and uniacid=:uniacid ' .
                ' limit 1',
                array(
                    ':id'      => $set['become_goodsid'],
                    ':uniacid' => $_W['uniacid']
                )
            );

            $goodscount = pdo_fetchcolumn(
                'select count(*) ' .
                ' from ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 已处理
                ' left join ' . tablename('superdesk_shop_order') . ' o on o.id = og.orderid' . // TODO 标志 楼宇之窗 openid shop_order 已处理
                ' where ' .
                '       og.goodsid=:goodsid ' .
                '       and o.openid=:openid ' .
                '       and o.core_user=:core_user ' .
                '       and o.status>=' . $order_status .
                '  limit 1',
                array(
                    ':goodsid'   => $set['become_goodsid'],
                    ':openid'    => $_W['openid'],
                    ':core_user' => $_W['core_user']
                )
            );

            if ($goodscount <= 0) {

                $status    = 0;
                $buy_goods = $goods;

            } else {

                $to_check_partner = true;
                $status           = 1;
            }
        }

        if ($to_check_partner) {

            if (empty($member['isparnter'])) {

                $data = array(
                    'ispartner'     => 1,
                    'partnerstatus' => $become_check,
                    'partnertime'   => time()
                );

                $member['ispartner']     = 1;
                $member['partnerstatus'] = $become_check;

                pdo_update(
                    'superdesk_shop_member',
                    $data,
                    array(
                        'id' => $member['id']
                    )
                );

                if ($become_check == 1) {

                    $this->model->sendMessage($member['openid'], array('nickname' => $member['nickname'], 'partner' => $data['partnertime']), TM_GLOBONUS_BECOME);
                }

            }

        }

        include $this->template();
    }
}