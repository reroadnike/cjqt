<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Notice_SuperdeskShopV2Page extends MobileLoginPage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        $member = m('member')->getMember($_W['openid'], $_W['core_user']);

        $notice = iunserializer($member['noticeset']);

        $hascommission = false;
        if (p('commission')) {
            $cset          = p('commission')->getSet();
            $hascommission = !empty($cset['level']);
        }

        if ($_W['ispost']) {

            $type = trim($_GPC['type']);

            if (empty($type)) {
                show_json(0, '参数错误');
            }

            $checked = intval($_GPC['checked']);

            if (empty($checked)) {
                $notice[$type] = 1;
            } else {
                unset($notice[$type]);
            }

            pdo_update( // 不根据id更新
                'superdesk_shop_member', // TODO 标志 楼宇之窗 openid shop_member 已处理
                array(
                    'noticeset' => iserializer($notice)
                ),
                array(
                    'uniacid'   => $_W['uniacid'],
                    'openid'    => $_W['openid'],
                    'core_user' => $_W['core_user'],

                )
            );
            show_json(1);
        }
        include $this->template();
    }
}