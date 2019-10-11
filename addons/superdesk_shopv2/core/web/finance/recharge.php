<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Recharge_SuperdeskShopV2Page extends WebPage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        $type = trim($_GPC['type']);

        if (!cv('finance.recharge.' . $type)) {
            $this->message('你没有相应的权限查看');
        }

        $rechargeTypeArray = array(
            1 => '饭堂充值',
            2 => '营销费用',
            3 => '其他',
            4 => '手动退款充值'
        );

        $id = intval($_GPC['id']);

        $shop_member = m('member')->getMemberById($id);

        if ($_W['ispost']) {

            $type_str      = (($type == 'credit1' ? '积分' : '余额'));
            $num          = floatval($_GPC['num']);
            $remark       = trim($_GPC['remark']);
            $rechargeType = trim($_GPC['rechargeType']);

            if ($num <= 0) {
                show_json(0, array('message' => '请填写大于0的数字!'));
            }

            if ($rechargeType == 1) {
                $remark = $rechargeTypeArray[$rechargeType];
            } else {
                $remark = $rechargeTypeArray[$rechargeType] . '：' . $remark;
            }

            $changetype = intval($_GPC['changetype']);

            if ($changetype == 2) {
                $num -= $shop_member[$type];
            } else if ($changetype == 1) {
                $num = -$num;
            }


            m('member')->setCredit($shop_member['openid'], $shop_member['core_user'],
                $type,
                $num,
                array(
                    $_W['uid'],
                    '后台会员充值' . $type_str . ' ' . $remark
                ),
                array(
                    'type'       => 2,
                    'createtime' => TIMESTAMP,
                )
            );

            if ($type == 'credit2') { // 余额

                $set   = m('common')->getSysset('shop');
                $logno = m('common')->createNO('member_log', 'logno', 'RC');

                $data = array(
                    'openid'       => $shop_member['openid'],
                    'core_user'    => $shop_member['core_user'],
                    'logno'        => $logno,
                    'uniacid'      => $_W['uniacid'],
                    'type'         => '0',
                    'createtime'   => TIMESTAMP,
                    'status'       => '1',
                    'title'        => $set['name'] . '会员充值',
                    'money'        => $num,
                    'remark'       => $remark,
                    'rechargetype' => 'system'
                );

                pdo_insert('superdesk_shop_member_log', $data); // TODO 标志 楼宇之窗 openid superdesk_shop_member_log 待处理
                $logid = pdo_insertid();

                m('notice')->sendMemberLogMessage($logid);
            }


            plog(
                'finance.recharge.' . $type,
                '充值' . $type_str . ': ' . $_GPC['num'] . ' <br/>会员信息: ID: ' . $shop_member['id'] . ' /  ' . $shop_member['openid'] . '/' . $shop_member['nickname'] . '/' . $shop_member['realname'] . '/' . $shop_member['mobile']
            );

            show_json(1, array(
                'url' => referer()
            ));
        }

        include $this->template();
    }
}