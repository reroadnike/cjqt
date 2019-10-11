<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

class MobilePage extends Page
{
    public    $footer     = array();
    public    $followBar  = false;
    protected $merch_user = array();

    public function __construct()
    {
        global $_W;
        global $_GPC;

        m('shop')->checkClose();

        $wap = m('common')->getSysset('wap');

        if ($wap['open'] && !is_weixin()) {

            if ($this instanceof MobileLoginPage || $this instanceof PluginMobileLoginPage) {

                if (empty($_W['openid'])) {
                    $_W['openid'] = m('account')->checkLogin();
                }

            } else {

                switch (SUPERDESK_SHOPV2_IS_BUILD_WINDOW) {
                    case 0:// 0 没有绑定楼宇之窗
                        $_W['openid'] = m('account')->checkOpenid();
                        break;
                    case 1:// 1 关联绑定楼宇之窗
                        $_W['openid'] = m('account')->checkCoreUser();
                        break;
                }

            }

        } else if ($wap['open'] && is_weixin()) { // MARK welfare 冲突

            switch (SUPERDESK_SHOPV2_MODE_USER) {
                case 1:// 1 超级前台

                    break;
                case 2:// 2 福利商城

                    socket_log('page_mobile -> wap[open]==true AND is_weixin == true : ' . $_W['openid']);

                    if ($this instanceof MobileLoginPage || $this instanceof PluginMobileLoginPage) {

                        if (empty($_W['openid'])) {
                            $_W['openid'] = m('account')->checkLogin();
                        }

                    } else {

                        switch (SUPERDESK_SHOPV2_IS_BUILD_WINDOW) {
                            case 0:// 0 没有绑定楼宇之窗
                                $_W['openid'] = m('account')->checkOpenid();
                                break;
                            case 1:// 1 关联绑定楼宇之窗
                                $_W['openid'] = m('account')->checkCoreUser();
                                break;
                        }


                    }

                    socket_log('page_mobile -> wap[open]==true AND is_weixin == true : ' . $_W['openid']);

//                    if (strexists($_W['openid'], 'sns_wx_') // ADD 在微信端 而不连接微信的问题
//                        || strexists($_W['openid'], 'sns_qq_')
//                        || strexists($_W['openid'], 'wap_user_')
//                    ) {
//                        $_W['openid'] = m('account')->checkLogin();
//                    }

                    break;
            }

        } else {

            // 预览
            if (($_GPC['preview'] == '1') && !is_weixin()) {
                $_W['openid'] = SUPERDESK_SHOPV2_DEBUG_OPENID;
            }
            if (SUPERDESK_SHOPV2_DEBUG) {
                $_W['openid'] = SUPERDESK_SHOPV2_DEBUG_OPENID;
            }
        }

        switch (SUPERDESK_SHOPV2_IS_BUILD_WINDOW) {
            case 0:// 0 没有绑定楼宇之窗
                $shop_member = m('member')->checkMember();
                break;
            case 1:// 1 关联绑定楼宇之窗
                $shop_member = m('member')->checkMemberBuildWindow();
                break;
        }


        $_W['mid']     = ((!empty($shop_member) ? $shop_member['id'] : 0));
        $_W['mopenid'] = ((!empty($shop_member) ? $shop_member['openid'] : ''));

        $_W['mobile'] = ((!empty($shop_member) ? $shop_member['mobile'] : ''));

        $_W['core_user']         = ((!empty($shop_member) ? $shop_member['core_user'] : 0));
        $_W['core_enterprise']   = ((!empty($shop_member) ? $shop_member['core_enterprise'] : 0)); //加一个企业id. zjh 2018年8月7日 16:22:15 福利商城
        $_W['core_organization'] = ((!empty($shop_member) ? $shop_member['core_organization'] : 0));


        // TODO 查检企业 mark welfare
        switch (SUPERDESK_SHOPV2_MODE_USER) {
            case 1:// 1 超级前台


                switch (SUPERDESK_SHOPV2_IS_BUILD_WINDOW) {
                    case 0:// 0 没有绑定楼宇之窗
                        m('account')->checkEnterpriseUserLogin();
                        break;
                    case 1:// 1 关联绑定楼宇之窗
                        m('account')->checkBuildWindowUserLogin();
                        break;
                }

                break;
            case 2:// 2 福利商城
                m('account')->checkWelfareUserLogin();
                break;
        }


        $merch_plugin = p('merch');
        $merch_data   = m('common')->getPluginset('merch');

        if (!empty($_GPC['merchid']) && $merch_plugin && $merch_data['is_openmerch']) {

            $this->merch_user = pdo_fetch(
                ' select * ' .
                ' from ' . tablename('superdesk_shop_merch_user') .
                ' where id=:id limit 1',
                array(
                    ':id' => intval($_GPC['merchid'])
                )
            );
        }

    }

    public function followBar($diypage = false)
    {
        global $_W;
        global $_GPC;

        if (is_h5app() || !is_weixin()) {
            return;
        }

        $openid = $_W['openid'];

        $followed = m('user')->followed($openid);

        $mid = intval($_GPC['mid']);

        $shop_member_id = m('member')->getMid();

        if (p('diypage')) {
            $diypagedata  = m('common')->getPluginset('diypage');
            $diyfollowbar = $diypagedata['followbar'];
        }

        @session_start();
        if ((!$followed && ($shop_member_id != $mid))
            || (!empty($diyfollowbar['params']['showtype'])
                && !empty($diyfollowbar['params']['isopen']))
        ) {

            $set = $_W['shopset'];

            $followbar = array(
                'followurl'    => $set['share']['followurl'],
                'shoplogo'     => tomedia($set['shop']['logo']),
                'shopname'     => $set['shop']['name'],
                'qrcode'       => tomedia($set['share']['followqrcode']),
                'share_member' => false
            );

            $friend = false;

            if (!empty($mid) && ($shop_member_id != $mid)) {

                if (!empty($_SESSION[SUPERDESK_SHOPV2_PREFIX . '_shareid'])
                    && ($_SESSION[SUPERDESK_SHOPV2_PREFIX . '_shareid'] == $mid)
                ) {
                    $mid = $_SESSION[SUPERDESK_SHOPV2_PREFIX . '_shareid'];
                }

                $member = m('member')->getMemberById($mid);

                if (!empty($member)) {

                    $_SESSION[SUPERDESK_SHOPV2_PREFIX . '_shareid'] = $mid;

                    $friend = true;

                    $followbar['share_member'] = array(
                        'id'       => $member['id'],
                        'nickname' => $member['nickname'],
                        'realname' => $member['realname'],
                        'avatar'   => $member['avatar']
                    );
                }
            }

            $showdiyfollowbar = false;

            if (p('diypage')) {
                if ((!empty($diyfollowbar) && !empty($diyfollowbar['params']['isopen'])) || (!empty($diyfollowbar) && $diypage)) {

                    $showdiyfollowbar = true;

                    if (!empty($followbar['share_member'])) {

                        if (!empty($diyfollowbar['params']['sharetext'])) {
                            $touser = m('member')->getMemberById($shop_member_id);

                            $diyfollowbar['text'] = str_replace('[商城名称]', '<span style="color:' . $diyfollowbar['style']['highlight'] . ';">' . $set['shop']['name'] . '</span>', $diyfollowbar['params']['sharetext']);
                            $diyfollowbar['text'] = str_replace('[邀请人]', '<span style="color:' . $diyfollowbar['style']['highlight'] . ';">' . $followbar['share_member']['nickname'] . '</span>', $diyfollowbar['text']);
                            $diyfollowbar['text'] = str_replace('[访问者]', '<span style="color:' . $diyfollowbar['style']['highlight'] . ';">' . $touser['nickname'] . '</span>', $diyfollowbar['text']);
                        } else {
                            $diyfollowbar['text'] = '来自好友<span class="text-danger">' . $followbar['share_member']['nickname'] . '</span>的推荐<br>' . '关注公众号，享专属服务';
                        }
                    } else if (!empty($diyfollowbar['params']['defaulttext'])) {
                        $diyfollowbar['text'] = str_replace('[商城名称]', '<span style="color:' . $diyfollowbar['style']['highlight'] . ';">' . $set['shop']['name'] . '</span>', $diyfollowbar['params']['defaulttext']);
                    } else {
                        $diyfollowbar['text'] = '欢迎进入<span class="text-danger">' . $set['shop']['name'] . '</span><br>' . '关注公众号，享专属服务';
                    }

                    $diyfollowbar['text'] = nl2br($diyfollowbar['text']);
                    $diyfollowbar['logo'] = tomedia($set['shop']['logo']);

                    if (($diyfollowbar['params']['icontype'] == 1) && !empty($followbar['share_member'])) {
                        $diyfollowbar['logo'] = tomedia($followbar['share_member']['avatar']);
                    } else if (($diyfollowbar['params']['icontype'] == 3) && !empty($diyfollowbar['params']['iconurl'])) {
                        $diyfollowbar['logo'] = tomedia($diyfollowbar['params']['iconurl']);
                    }

                    if (empty($diyfollowbar['params']['btnclick'])) {

                        if (empty($diyfollowbar['params']['btnlinktype'])) {
                            $diyfollowbar['link'] = $set['share']['followurl'];
                        } else {
                            $diyfollowbar['link'] = $diyfollowbar['params']['btnlink'];
                        }
                    } else if (empty($diyfollowbar['params']['qrcodetype'])) {

                        $diyfollowbar['qrcode'] = tomedia($set['share']['followqrcode']);
                    } else {

                        $diyfollowbar['qrcode'] = tomedia($diyfollowbar['params']['qrcodeurl']);
                    }
                }
            }

            if ($showdiyfollowbar) {
                include $this->template('diypage/followbar');
                return;
            }

            include $this->template('_followbar');
        }
    }

    public function footerMenus($diymenuid = NULL, $p = NULL)
    {
        global $_W;
        global $_GPC;

        $params = array(
            ':uniacid'   => $_W['uniacid'],
            ':openid'    => $_W['openid'],
            ':core_user' => $_W['core_user']
        );

        $cartcount = pdo_fetchcolumn(
            'select ' .
            '       ifnull(sum(total),0) ' .
            ' from ' . tablename('superdesk_shop_member_cart') .// TODO 标志 楼宇之窗 openid superdesk_shop_member_cart 已处理
            ' where ' .
            '       uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and deleted=0 ' .
            '       and selected =1',
            $params
        );

        $commission = array();
        if (p('commission') && intval(0 < $_W['shopset']['commission']['level'])) {

            $member = m('member')->getMember($_W['openid'], $_W['core_user']);

            if (!$member['agentblack']) {

                if (($member['isagent'] == 1) && ($member['status'] == 1)) {
                    $commission = array(
                        'url'  => mobileUrl('commission'),
                        'text' => (empty($_W['shopset']['commission']['texts']['center']) ? '营销中心' : $_W['shopset']['commission']['texts']['center'])
                    );
                } else {
                    $commission = array(
                        'url'  => mobileUrl('commission/register'),
                        'text' => (empty($_W['shopset']['commission']['texts']['become']) ? '成为营销商' : $_W['shopset']['commission']['texts']['become'])
                    );
                }
            }
        }

        $showdiymenu = false;

        $routes     = explode('.', $_W['routes']);
        $controller = $routes[0];

        if (($controller == 'member') || ($controller == 'cart') || ($controller == 'order') || ($controller == 'goods')) {
            $controller = 'shop';
        }

        if (empty($diymenuid)) {
            $diypagedata = m('common')->getPluginset('diypage');
            if (!empty($diypagedata['menu'])) {
                $pageid    = ((!empty($controller) ? $controller : 'shop'));
                $pageid    = (($pageid == 'index' ? 'shop' : $pageid));
                $diymenuid = $diypagedata['menu'][$pageid];
                if (!is_weixin() || is_h5app()) {
                    $diymenuid = $diypagedata['menu'][$pageid . '_wap'];
                }
            }
        }

        if (!empty($diymenuid)) {
            $menu = pdo_fetch('SELECT * FROM ' . tablename('superdesk_shop_diypage_menu') . ' WHERE id=:id and uniacid=:uniacid limit 1 ', array(':id' => $diymenuid, ':uniacid' => $_W['uniacid']));
            if (!empty($menu)) {
                $menu        = $menu['data'];
                $menu        = base64_decode($menu);
                $diymenu     = json_decode($menu, true);
                $showdiymenu = true;
            }
        }

        if ($showdiymenu) {
            include $this->template('diypage/menu');
            return;
        }

        if (($controller == 'commission') && ($routes[1] != 'myshop')) {
            include $this->template('commission/_menu');
            return;
        }

        if ($controller == 'creditshop') {
            include $this->template('creditshop/_menu');
            return;
        }

        if ($controller == 'groups') {
            include $this->template('groups/_groups_footer');
            return;
        }

        if ($controller == 'merch') {
            include $this->template('merch/_menu');
            return;
        }

        if ($controller == 'mr') {
            include $this->template('mr/_menu');
            return;
        }

        if ($controller == 'newmr') {
            include $this->template('newmr/_menu');
            return;
        }

        if ($controller == 'sign') {
            include $this->template('sign/_menu');
            return;
        }

        if ($controller == 'sns') {
            include $this->template('sns/_menu');
            return;
        }

        include $this->template('_menu');
    }

    public function shopShare()
    {
        global $_W;
        global $_GPC;

        $trigger = false;

        if (empty($_W['shopshare'])) {

            $set = $_W['shopset'];

            $_W['shopshare'] = array(
                'title'  => (empty($set['share']['title']) ? $set['shop']['name'] : $set['share']['title']),
                'imgUrl' => (empty($set['share']['icon']) ? tomedia($set['shop']['logo']) : tomedia($set['share']['icon'])),
                'desc'   => (empty($set['share']['desc']) ? $set['shop']['description'] : $set['share']['desc']),
                'link'   => (empty($set['share']['url']) ? mobileUrl('', NULL, true) : $set['share']['url'])
            );

            $plugin_commission = p('commission');

            if ($plugin_commission) {

                $set = $plugin_commission->getSet();

                if (!empty($set['level'])) {

                    $member = m('member')->getMember($_W['openid'], $_W['core_user']);

                    if (!empty($member) && ($member['status'] == 1) && ($member['isagent'] == 1)) {

                        if (empty($set['closemyshop'])) {

                            $myshop = $plugin_commission->getShop($member['id']);

                            $_W['shopshare'] = array(
                                'title'  => $myshop['name'],
                                'imgUrl' => tomedia($myshop['logo']),
                                'desc'   => $myshop['desc'],
                                'link'   => mobileUrl('commission/myshop', array('mid' => $member['id']), true)
                            );

                        } else {
                            $_W['shopshare']['link'] = ((empty($_W['shopset']['share']['url']) ? mobileUrl('', array('mid' => $member['id']), true) : $_W['shopset']['share']['url']));
                        }

                        if (empty($set['become_reg']) && (empty($member['realname']) || empty($member['mobile']))) {
                            $trigger = true;
                        }

                    } else if (!empty($_GPC['mid'])) {

                        $m = m('member')->getMemberById($_GPC['mid']);

                        if (!empty($m) && ($m['status'] == 1) && ($m['isagent'] == 1)) {

                            if (empty($set['closemyshop'])) {

                                $myshop = $plugin_commission->getShop($_GPC['mid']);

                                $_W['shopshare'] = array(
                                    'title'  => $myshop['name'],
                                    'imgUrl' => tomedia($myshop['logo']),
                                    'desc'   => $myshop['desc'],
                                    'link'   => mobileUrl('commission/myshop', array('mid' => $member['id']), true)
                                );

                            } else {
                                $_W['shopshare']['link'] = ((empty($_W['shopset']['share']['url']) ? mobileUrl('', array('mid' => $_GPC['mid']), true) : $_W['shopset']['share']['url']));
                            }

                        } else {
                            $_W['shopshare']['link'] = ((empty($_W['shopset']['share']['url']) ? mobileUrl('', array('mid' => $_GPC['mid']), true) : $_W['shopset']['share']['url']));
                        }
                    }
                }
            }
        }
        return $trigger;
    }

    public function diyPage($tpye)
    {
        global $_W;
        global $_GPC;

        if (empty($tpye) || !p('diypage')) {
            return false;
        }

        $diypagedata = m('common')->getPluginset('diypage');

        if (!empty($diypagedata)) {

            $diypageid = $diypagedata['page'][$tpye];

            if (!empty($diypageid)) {

                $page = p('diypage')->getPage($diypageid, true);

                if (!empty($page)) {

                    p('diypage')->setShare($page);
                    $diyitems = $page['data']['items'];
                    include $this->template('diypage');

                    exit();
                }
            }
        }
    }

    public function diyLayer($v = false, $diy = false)
    {
        global $_W;
        global $_GPC;

        if (!p('diypage') || $diy) {
            return;
        }

        $diypagedata = m('common')->getPluginset('diypage');

        if (!empty($diypagedata)) {

            $diylayer = $diypagedata['layer'];

            if (!$diylayer['params']['isopen'] && $v) {
                return;
            }

            include $this->template('diypage/layer');
        }
    }

    public function wapQrcode()
    {
        global $_W;
        global $_GPC;

        $currenturl = '';

        if (!is_mobile()) {
            $currenturl = $_W['siteroot'] . 'app/index.php?' . $_SERVER['QUERY_STRING'];
        }

        $shop = m('common')->getSysset('shop');

        $shopname = $shop['name'];

        include $this->template('_wapqrcode');
    }
}
