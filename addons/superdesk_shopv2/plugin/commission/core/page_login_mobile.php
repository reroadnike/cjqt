<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

class CommissionMobileLoginPage extends PluginMobileLoginPage
{
    public function __construct()
    {
        parent::__construct();

        global $_W;
        global $_GPC;

//传播类
//发送给朋友: "menuItem:share:appMessage"
//分享到朋友圈: "menuItem:share:timeline"
//分享到QQ: "menuItem:share:qq"
//分享到Weibo: "menuItem:share:weiboApp"
//收藏: "menuItem:favorite"
//分享到FB: "menuItem:share:facebook"
//分享到 QQ 空间/menuItem:share:QZone

        $_W['shopshare']['hideMenus'][] = 'menuItem:share:appMessage';
        $_W['shopshare']['hideMenus'][] = 'menuItem:share:timeline';
        $_W['shopshare']['hideMenus'][] = 'menuItem:share:qq';
        $_W['shopshare']['hideMenus'][] = 'menuItem:share:weiboApp';
        $_W['shopshare']['hideMenus'][] = 'menuItem:share:QZone';
        $_W['shopshare']['hideMenus'][] = 'menuItem:copyUrl';

        if (($_W['action'] != 'register') && ($_W['action'] != 'myshop') && ($_W['action'] != 'share')) {

            $member = m('member')->getMember($_W['openid'], $_W['core_user']);

            if (($member['isagent'] != 1) || ($member['status'] != 1)) {

                header('location:' . mobileUrl('commission/register'));
                exit();
            }
        }
    }
}