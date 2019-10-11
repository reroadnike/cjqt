<?php

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/accessControl.php";

if (!(defined("IN_IA"))) {
    exit("Access Denied");
}

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/page_login_mobile.php";

class Index_SuperdeskShopV2Page extends MobileLoginPage
{
    public function main()
    {
        global $_W;
        global $_GPC;

//		header("location: " . mobileUrl("pc/member/cart"));
//		exit();

        $member = m('member')->getMember($_W['openid'], $_W['core_user']);// TODO 标志 m('member')->getMember true
        $level  = m('member')->getLevel($_W['openid'], $_W['core_user']);

        $open_creditshop = p('creditshop') && $_W['shopset']['creditshop']['centeropen'];

        $params = array(
            ':uniacid'   => $_W['uniacid'],
            ':openid'    => $_W['openid'],
            ':core_user' => $_W['core_user']
        );

        $merch_plugin = p('merch');
        $merch_data   = m('common')->getPluginset('merch');

        if ($merch_plugin && $merch_data['is_openmerch']) {

            $statics = array(
                'order_0'  => pdo_fetchcolumn(
                    'select count(*) from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
                    ' where openid=:openid and core_user=:core_user and status=0 and isparent=0 and uniacid=:uniacid limit 1',
                    $params
                ),
                'order_1'  => pdo_fetchcolumn(
                    'select count(*) from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
                    ' where openid=:openid and core_user=:core_user and status=1 and isparent=0 and refundid=0 and uniacid=:uniacid limit 1',
                    $params
                ),
                'order_2'  => pdo_fetchcolumn(
                    'select count(*) from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
                    ' where openid=:openid and core_user=:core_user and status=2 and isparent=0 and refundid=0 and uniacid=:uniacid limit 1',
                    $params
                ),
                'order_4'  => pdo_fetchcolumn(
                    'select count(*) from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
                    ' where openid=:openid and core_user=:core_user and refundstate=1 and isparent=0 and uniacid=:uniacid limit 1',
                    $params
                ),
                'cart'     => pdo_fetchcolumn(
                    'select ifnull(sum(total),0) from ' . tablename('superdesk_shop_member_cart') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_cart 已处理
                    ' where uniacid=:uniacid and openid=:openid and core_user=:core_user and deleted=0 ',
                    $params
                ),
                'favorite' => pdo_fetchcolumn(
                    'select count(*) from ' . tablename('superdesk_shop_member_favorite') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_favorite 已处理
                    ' where uniacid=:uniacid and openid=:openid and core_user=:core_user and deleted=0 ',
                    $params
                )
            );
        } else {
            $statics = array(
                'order_0'  => pdo_fetchcolumn(
                    'select count(*) from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 已处理
                    ' where openid=:openid and core_user=:core_user and ismr=0 and status=0 and isparent=0 and uniacid=:uniacid and isparent=0 limit 1',
                    $params
                ),
                'order_1'  => pdo_fetchcolumn(
                    'select count(*) from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 已处理
                    ' where openid=:openid and core_user=:core_user and ismr=0 and status=1 and isparent=0 and refundid=0 and uniacid=:uniacid and isparent=0 limit 1',
                    $params
                ),
                'order_2'  => pdo_fetchcolumn(
                    'select count(*) from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 已处理
                    ' where openid=:openid and core_user=:core_user and ismr=0 and status=2 and isparent=0 and refundid=0 and uniacid=:uniacid and isparent=0 limit 1',
                    $params
                ),
                'order_4'  => pdo_fetchcolumn(
                    'select count(*) from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 已处理
                    ' where openid=:openid and core_user=:core_user and ismr=0 and refundstate=1 and isparent=0 and uniacid=:uniacid and isparent=0 limit 1',
                    $params
                ),
                'cart'     => pdo_fetchcolumn(
                    'select ifnull(sum(total),0) from ' . tablename('superdesk_shop_member_cart') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_cart 已处理
                    ' where uniacid=:uniacid and openid=:openid and core_user=:core_user and deleted=0 and selected = 1',
                    $params
                ),
                'favorite' => ($merch_plugin && $merch_data['is_openmerch'] ?
                    pdo_fetchcolumn(
                        'select count(*) from ' . tablename('superdesk_shop_member_favorite') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_favorite 已处理
                        ' where uniacid=:uniacid and openid=:openid and core_user=:core_user and deleted=0 and `type`=0',
                        $params
                    ) : pdo_fetchcolumn(
                        'select count(*) from ' . tablename('superdesk_shop_member_favorite') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_favorite 已处理
                        ' where uniacid=:uniacid and openid=:openid and core_user=:core_user and deleted=0',
                        $params
                    )
                )
            );
        }

        $hascoupon       = false;
        $hascouponcenter = false;

        $plugin_coupon = com('coupon');
        if ($plugin_coupon) {

            $time = time();

            $sql = 'select ' .
                '       count(*) ' .
                ' from ' . tablename('superdesk_shop_coupon_data') . ' d' .// TODO 标志 楼宇之窗 openid shop_coupon_data 已处理
                ' left join ' . tablename('superdesk_shop_coupon') . ' c on d.couponid = c.id' .
                ' where '.
                '       d.openid=:openid '.
                '       and d.core_user=:core_user '.
                '       and d.uniacid=:uniacid '.
                '       and d.used=0 ' .
                '       and (   (c.timelimit = 0 and ( c.timedays=0 or c.timedays*86400 + d.gettime >=unix_timestamp() ) )  or  (c.timelimit =1 and c.timestart<=' . $time . ' && c.timeend>=' . $time . ')) ' .
                ' order by d.gettime desc';

            $statics['coupon'] = pdo_fetchcolumn(
                $sql,
                array(
                    ':openid'    => $_W['openid'],
                    ':core_user' => $_W['core_user'],
                    ':uniacid'   => $_W['uniacid']
                )
            );

            $pcset = $_W['shopset']['coupon'];

            if (empty($pcset['closemember'])) {
                $hascoupon = true;
            }

            if (empty($pcset['closecenter'])) {
                $hascouponcenter = true;
            }
        }

        $hasglobonus     = false;
        $plugin_globonus = p('globonus');
        if ($plugin_globonus) {

            $plugin_globonus_set = $plugin_globonus->getSet();

            $hasglobonus = !(empty($plugin_globonus_set['open'])) && !(empty($plugin_globonus_set['openmembercenter']));
        }

        $hasauthor     = false;
        $plugin_author = p('author');
        if ($plugin_author) {

            $plugin_author_set = $plugin_author->getSet();

            $hasauthor = !(empty($plugin_author_set['open'])) && !(empty($plugin_author_set['openmembercenter']));
        }

        $hasabonus     = false;
        $plugin_abonus = p('abonus');
        if ($plugin_abonus) {

            $plugin_abonus_set = $plugin_abonus->getSet();

            $hasabonus = !(empty($plugin_abonus_set['open'])) && !(empty($plugin_abonus_set['openmembercenter']));
        }

        $hasqa     = false;
        $plugin_qa = p('qa');
        if ($plugin_qa) {

            $plugin_qa_set = $plugin_qa->getSet();

            if (!(empty($plugin_qa_set['showmember']))) {
                $hasqa = true;
            }
        }

        $hassign  = false;
        $com_sign = p('sign');
        if ($com_sign) {

            $com_sign_set = $com_sign->getSet();

            if (!(empty($com_sign_set['iscenter']))) {

                $hassign = ((empty($_W['shopset']['trade']['credittext']) ? '积分' : $_W['shopset']['trade']['credittext']));
                $hassign .= ((empty($com_sign_set['textsign']) ? '签到' : $com_sign_set['textsign']));
            }
        }

        $wapset = m('common')->getSysset('wap');

        $ice_menu_array = array(array(
            'menu_key'  => 'index',
            'menu_name' => '我的商城',
            'menu_url'  => mobileUrl('pc.member')
        ));

        $nav_link_list = array(
            array(
                'link'  => mobileUrl('pc'),
                'title' => '首页'
            ),
            array(
                'link'  => mobileUrl('pc.member'),
                'title' => '我的商城'
            )
        );

        include $this->template();
    }
}