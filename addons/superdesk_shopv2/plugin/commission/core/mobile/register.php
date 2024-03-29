<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}


require SUPERDESK_SHOPV2_PLUGIN . 'commission/core/page_login_mobile.php';

class Register_SuperdeskShopV2Page extends CommissionMobileLoginPage
{
    public function main()
    {
        global $_W;
        global $_GPC;
        $set    = set_medias($this->set, 'regbg');
        $member = m('member')->getMember($_W['openid'], $_W['core_user']);

        if (($member['isagent'] == 1) && ($member['status'] == 1)) {
            header('location: ' . mobileUrl('commission'));
            exit();
        }


        if ($member['agentblack']) {
            include $this->template();
            exit();
        }


        $apply_set                  = array();
        $apply_set['open_protocol'] = $set['open_protocol'];

        if (empty($set['applytitle'])) {
            $apply_set['applytitle'] = '营销商申请协议';
        } else {
            $apply_set['applytitle'] = $set['applytitle'];
        }

        $template_flag  = 0;
        $diyform_plugin = p('diyform');

        if ($diyform_plugin) {
            $set_config              = $diyform_plugin->getSet();
            $commission_diyform_open = $set_config['commission_diyform_open'];

            if ($commission_diyform_open == 1) {
                $template_flag = 1;
                $diyform_id    = $set_config['commission_diyform'];

                if (!empty($diyform_id)) {
                    $formInfo     = $diyform_plugin->getDiyformInfo($diyform_id);
                    $fields       = $formInfo['fields'];
                    $diyform_data = iunserializer($member['diycommissiondata']);
                    $f_data       = $diyform_plugin->getDiyformData($diyform_data, $fields, $member);
                }

            }

        }


        $mid   = intval($_GPC['mid']);
        $agent = false;

        if (!empty($member['fixagentid'])) {
            $mid = $member['agentid'];

            if (!empty($mid)) {
                $agent = m('member')->getMember($member['agentid']);
            }

        } else if (!empty($member['agentid'])) {
            $mid   = $member['agentid'];
            $agent = m('member')->getMember($member['agentid']);
        } else if (!empty($member['inviter'])) {
            $mid   = $member['inviter'];
            $agent = m('member')->getMember($member['inviter']);
        } else if (!empty($mid)) {
            $agent = m('member')->getMember($mid);
        }


        if ($_W['ispost']) {
            if ($set['become'] != '1') {
                show_json(0, '未开启' . $set['texts']['agent'] . '注册!');
            }


            $become_check  = intval($set['become_check']);
            $ret['status'] = $become_check;

            if ($template_flag == 1) {
                $memberdata                    = $_GPC['memberdata'];
                $insert_data                   = $diyform_plugin->getInsertData($fields, $memberdata);
                $data                          = $insert_data['data'];
                $m_data                        = $insert_data['m_data'];
                $mc_data                       = $insert_data['mc_data'];
                $m_data['diycommissionid']     = $diyform_id;
                $m_data['diycommissionfields'] = iserializer($fields);
                $m_data['diycommissiondata']   = $data;
                $m_data['isagent']             = 1;
                $m_data['agentid']             = $mid;
                $m_data['status']              = $become_check;
                $m_data['agenttime']           = ($become_check == 1 ? time() : 0);
                pdo_update('superdesk_shop_member', $m_data, array('id' => $member['id']));

                if ($become_check == 1) {
                    $this->model->sendMessage($member['openid'], array('nickname' => $member['nickname'], 'agenttime' => $m_data['agenttime']), TM_COMMISSION_BECOME);
                }


                if (!empty($member['uid'])) {
                    if (!empty($mc_data)) {
                        m('member')->mc_update($member['uid'], $mc_data);
                    }

                }

            } else {
                $data = array('isagent' => 1, 'agentid' => $mid, 'status' => $become_check, 'realname' => $_GPC['realname'], 'mobile' => $_GPC['mobile'], 'weixin' => $_GPC['weixin'], 'agenttime' => ($become_check == 1 ? time() : 0));
                pdo_update('superdesk_shop_member', $data, array('id' => $member['id']));

                if ($become_check == 1) {
                    $this->model->sendMessage($member['openid'], array('nickname' => $member['nickname'], 'agenttime' => $data['agenttime']), TM_COMMISSION_BECOME);

                    if (!empty($mid)) {
                        $this->model->upgradeLevelByAgent($mid);

                        if (p('globonus')) {
                            p('globonus')->upgradeLevelByAgent($mid);
                        }


                        if (p('author')) {
                            p('author')->upgradeLevelByAgent($mid);
                        }

                    }

                }


                if (!empty($member['uid'])) {
                    m('member')->mc_update($member['uid'], array('realname' => $data['realname'], 'mobile' => $data['mobile']));
                }

            }

            show_json(1, array('check' => $become_check));
        }


        $order_status   = ((intval($set['become_order']) == 0 ? 1 : 3));
        $become_check   = intval($set['become_check']);
        $to_check_agent = false;

        if (empty($set['become'])) {
            if (empty($member['status']) || empty($member['isagent'])) {
                $data = array('isagent' => 1, 'agentid' => $mid, 'status' => $become_check, 'realname' => $_GPC['realname'], 'mobile' => $_GPC['mobile'], 'weixin' => $_GPC['weixin'], 'agenttime' => ($become_check == 1 ? time() : 0));
                pdo_update('superdesk_shop_member', $data, array('id' => $member['id']));

                if ($become_check == 1) {
                    $this->model->sendMessage($member['openid'], array('nickname' => $member['nickname'], 'agenttime' => $data['agenttime']), TM_COMMISSION_BECOME);
                    $this->model->upgradeLevelByAgent($member['id']);

                    if (p('globonus')) {
                        p('globonus')->upgradeLevelByAgent($member['id']);
                    }


                    if (p('author')) {
                        p('author')->upgradeLevelByAgent($member['id']);
                    }

                }


                if (!empty($member['uid'])) {
                    m('member')->mc_update($member['uid'], array('realname' => $data['realname'], 'mobile' => $data['mobile']));
                }


                $member['isagent'] = 1;
                $member['status']  = $become_check;
            }

        } else if ($set['become'] == '2') {
            $status     = 1;
            $ordercount = pdo_fetchcolumn(
                'select count(*) from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 已处理
                ' where uniacid=:uniacid and openid=:openid and core_user=:core_user and status>=' . $order_status .
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
                $to_check_agent = true;
            }
        } else if ($set['become'] == '3') {
            $status     = 1;
            $moneycount = pdo_fetchcolumn(
                'select sum(goodsprice) from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 已处理
                ' where uniacid=:uniacid and openid=:openid and core_user=:core_user and status>=' . $order_status .
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
                $to_check_agent = true;
            }
        } else if ($set['become'] == 4) {
            $goods      = pdo_fetch('select id,title,thumb,marketprice from' . tablename('superdesk_shop_goods') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $set['become_goodsid'], ':uniacid' => $_W['uniacid']));
            $goodscount = pdo_fetchcolumn(
                ' select count(*) from ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 已处理
                ' left join ' . tablename('superdesk_shop_order') . ' o on o.id = og.orderid' .  // TODO 标志 楼宇之窗 openid shop_order 已处理
                ' where og.goodsid=:goodsid and o.openid=:openid and core_user=:core_user and o.status>=' . $order_status .
                ' limit 1',
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
                $to_check_agent = true;
                $status         = 1;
            }
        }
        if ($to_check_agent) {
            if (empty($member['isagent'])) {
                $data              = array('isagent' => 1, 'status' => $become_check, 'agenttime' => time());
                $member['isagent'] = 1;
                $member['status']  = $become_check;
                pdo_update('superdesk_shop_member', $data, array('id' => $member['id']));

                if ($become_check == 1) {
                    $this->model->sendMessage($member['openid'], array('nickname' => $member['nickname'], 'agenttime' => $data['agenttime']), TM_COMMISSION_BECOME);

                    if (!empty($member['agentid'])) {
                        $parent = m('member')->getMember($member['agentid']);

                        if (!empty($parent) && !empty($parent['status']) && !empty($parent['isagent'])) {
                            $this->model->upgradeLevelByAgent($parent['id']);
                        }

                    }

                }

            }

        }


        include $this->template();
    }
}


?>