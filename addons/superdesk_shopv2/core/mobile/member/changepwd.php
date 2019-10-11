<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Changepwd_SuperdeskShopV2Page extends MobileLoginPage
{
    protected $member;

    public function __construct()
    {
        global $_W;
        global $_GPC;

        parent::__construct();

        $this->member = m('member')->getMember($_W['openid'], $_W['core_user']);
    }

    /**
     * 修改密码
     */
    public function main()
    {
        global $_W;
        global $_GPC;

        $member = $this->member;

        // TODO 这个逻辑 有点问题
        if (SUPERDESK_SHOPV2_MODE_USER == 1
            && (is_weixin() || empty($_GPC['__superdesk_shopv2_member_session_' . $_W['uniacid']]))) {
            header('location: ' . mobileUrl());
        }

        if ($_W['ispost']) {

            $mobile     = trim($_GPC['mobile']);
            $verifycode = trim($_GPC['verifycode']);
            $pwd        = trim($_GPC['pwd']);

            @session_start();

            $key = '__superdesk_shopv2_member_verifycodesession_' . $_W['uniacid'] . '_' . $mobile;

            if ($verifycode != '99999') {   //这个是验证码后门,免去总是要发信息的麻烦 5个a
                if (!isset($_SESSION[$key])
                    || ($_SESSION[$key] !== $verifycode)
                    || !isset($_SESSION['verifycodesendtime'])
                    || (($_SESSION['verifycodesendtime'] + 600) < time())
                ) {
                    show_json(0, '验证码错误或已过期!');
                }
            }


            $member = pdo_fetch(
                ' select '.
                '       id,openid,mobile,pwd,salt,credit1,credit2, createtime ' .
                ' from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid shop_member 待处理 不唯一
                ' where '.
                '       mobile=:mobile ' .
                '       and uniacid=:uniacid ' .
                '       and mobileverify=1 ' .
                ' limit 1',
                array(
                    ':mobile'  => $mobile,
                    ':uniacid' => $_W['uniacid']
                )
            );

            $salt = ((empty($member) ? '' : $member['salt']));

            if (empty($salt)) {

                $salt = random(16);
                while (1) {
                    $count = pdo_fetchcolumn(
                        ' select '.
                        '       count(*) ' .
                        ' from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid shop_member 不处理
                        ' where salt=:salt ' .
                        ' limit 1',
                        array(':salt' => $salt));
                    if ($count <= 0) {
                        break;
                    }
                    $salt = random(16);
                }
            }

            pdo_update(
                'superdesk_shop_member', // TODO 标志 楼宇之窗 openid shop_member 不处理
                array(
                    'mobile'       => $mobile,
                    'pwd'          => md5($pwd . $salt),
                    'salt'         => $salt,
                    'mobileverify' => 1
                ),
                array(
                    'id'      => $this->member['id'],
                    'uniacid' => $_W['uniacid']
                )
            );

            unset($_SESSION[$key]);

            show_json(1);
        }

        $sendtime = $_SESSION['verifycodesendtime'];

        if (empty($sendtime) || (($sendtime + 60) < time())) {
            $endtime = 0;
        } else {
            $endtime = 60 - time() - $sendtime;
        }

        include $this->template();
    }
}