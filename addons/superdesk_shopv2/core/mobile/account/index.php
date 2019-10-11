<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Index_SuperdeskShopV2Page extends MobilePage
{
    protected function getWapSet()
    {
        global $_W;
        global $_GPC;
        $set                 = m('common')->getSysset(array('shop', 'wap'));
        $set['wap']['color'] = (empty($set['wap']['color']) ? '#fff' : $set['wap']['color']);
        $params              = array();
        if (!empty($_GPC['mid'])) {
            $params['mid'] = $_GPC['mid'];
        }
        if (!empty($_GPC['backurl'])) {
            $params['backurl'] = $_GPC['backurl'];
        }
        $set['wap']['loginurl']  = mobileUrl('account/login', $params);
        $set['wap']['regurl']    = mobileUrl('account/register', $params);
        $set['wap']['forgeturl'] = mobileUrl('account/forget', $params);
        return $set;
    }


    public function login()
    {
        global $_W;
        global $_GPC;

        $key = '__superdesk_shopv2_member_session_' . $_W['uniacid'];


        // MARK welfare 冲突
        if (SUPERDESK_SHOPV2_MODE_USER == 1 && (is_weixin() || !empty($_GPC[$key]))) {
            header('location: ' . mobileUrl());
        }

        if ($_W['ispost']) {

            $mobile = trim($_GPC['mobile']);
            $pwd    = trim($_GPC['pwd']);

            $member = pdo_fetch(
                ' select ' .
                '   id,openid,mobile,core_user,core_enterprise,core_organization,pwd,salt ' .
                ' from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid shop_member 待处理
                ' where mobile=:mobile ' .
                '       and mobileverify=1 ' .
                '       and uniacid=:uniacid ' .
                ' limit 1',
                array(
                    ':mobile'  => $mobile,
                    ':uniacid' => $_W['uniacid']
                )
            );

            if (empty($member)) {
                show_json(0, '用户不存在');
            }

            if (md5($pwd . $member['salt']) !== $member['pwd']) {
                show_json(0, '用户或密码错误');
            }

            m('account')->setLogin($member);

            show_json(1, '登录成功');
        }

        $set = $this->getWapSet();

        $backurl = '';


        if (!empty($_GPC['backurl'])) {

            $backurl = $_W['siteroot'] . 'app/index.php?' . base64_decode(urldecode($_GPC['backurl']));

        } else {

            $key = '__superdesk_shopv2_redirect_back_url_' . $_W['uniacid'];

            $session_url = $_SESSION[$key];

            if (!empty($session_url)) {

                $backurl = $_W['siteroot'] . 'app/index.php?' . base64_decode(urldecode($session_url));

            }
            unset($_SESSION[$key]);
        }

        $wapset = $_W['shopset']['wap'];
        $sns    = $wapset['sns'];

        include $this->template('login', NULL, true);
    }

    /**
     *  福利卡快捷登录
     *  2019-08-29 luoxt
     *
     */
    public function enter()
    {
        global $_W;
        global $_GPC;
        $key = '__superdesk_shopv2_member_session_' . $_W['uniacid'];

        // MARK welfare 冲突
        if (SUPERDESK_SHOPV2_MODE_USER == 1 && (is_weixin() || !empty($_GPC[$key]))) {
            header('location: ' . mobileUrl());
        }

        ###判断KEY
        $key = trim($_GPC['key']);
        if(!$key){
            exit('对不起，请求无效！');
        }

        //获取福利卡信息
        $params = ['enter_key' => trim($key)];
        $enter_res = pdo_fetch(
            ' SELECT * FROM ' . tablename('superdesk_shop_member_enter') .
            ' WHERE  enter_key = :enter_key', $params);

        if(!isset($enter_res['enter_id']) || !$enter_res['enter_id']) {
            exit('对不起，数据不存在！');
        }

        $now = time();
        $expiretime = $enter_res['expiretime'];
        if($expiretime < $now) {
            exit('对不起，已过期（'.date('Y-m-d', $expiretime).'）！');
        }

        if($enter_res['status']==0 || $enter_res['isdelete']==1) {
            exit('对不起，未激活或已删除！');
        }

        $member = pdo_fetch(
            ' select id,openid,mobile,pwd,salt ' .
            ' from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid shop_member 待处理
            ' where id=:id and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':id'  => $enter_res['enter_uid'],
                ':uniacid' => $_W['uniacid']
            )
        );

        if (empty($member)) {
            exit('用户不存在');
        }

        m('account')->setLogin($member);

        header('location: ' . mobileUrl());
        exit();
    }


    public function register()
    {
        $this->rf(0);
    }


    public function forget()
    {
        $this->rf(1);
    }


    protected function rf($type)
    {
        global $_W;
        global $_GPC;

        if (SUPERDESK_SHOPV2_MODE_USER == 1 && (is_weixin() || !empty($_GPC['__superdesk_shopv2_member_session_' . $_W['uniacid']]))) {
            header('location: ' . mobileUrl());
        }


        if ($_W['ispost']) {

            $mobile     = trim($_GPC['mobile']);
            $verifycode = trim($_GPC['verifycode']);
            $pwd        = trim($_GPC['pwd']);

            if (empty($mobile)) {
                show_json(0, '请输入正确的手机号');
            }

            if (empty($verifycode)) {
                show_json(0, '请输入验证码');
            }

            if (empty($pwd)) {
                show_json(0, '请输入密码');
            }

            $key = '__superdesk_shopv2_member_verifycodesession_' . $_W['uniacid'] . '_' . $mobile;

            if ($verifycode != 99999) {
                if (!isset($_SESSION[$key])
                    || ($_SESSION[$key] !== $verifycode)
                    || !isset($_SESSION['verifycodesendtime'])
                    || (($_SESSION['verifycodesendtime'] + 600) < time())
                ) {
                    show_json(0, '验证码错误或已过期!');
                }
            }

            // TODO 标志 楼宇之窗返回多 shop_member 待处理
            $member = pdo_fetch(
                ' select ' .
                '   id,openid,mobile,core_user,core_enterprise,core_organization,pwd,salt ' .
                ' from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid shop_member 待处理
                ' where mobile=:mobile ' .
                '       and mobileverify=1 ' .
                '       and uniacid=:uniacid ' .
                ' limit 1',
                array(
                    ':mobile'  => $mobile,
                    ':uniacid' => $_W['uniacid']
                )
            );

            if (empty($type)) {

                if (!empty($member)) {
                    show_json(0, '此手机号已注册, 请直接登录');
                }

                $salt = ((empty($member) ? '' : $member['salt']));
                if (empty($salt)) {
                    $salt = m('account')->getSalt();
                }

                $openid    = ((empty($member) ? '' : $member['openid']));
                $core_user = ((empty($member) ? '' : $member['core_user']));
                $nickname  = ((empty($member) ? '' : $member['nickname']));

                if (empty($openid)) {
                    $openid   = 'wap_user_' . $_W['uniacid'] . '_' . $mobile;
                    $nickname = substr($mobile, 0, 3) . 'xxxx' . substr($mobile, 7, 4);
                }

                $data = array(
                    'uniacid'      => $_W['uniacid'],
                    'mobile'       => $mobile,
                    'nickname'     => $nickname,
                    'openid'       => $openid,
                    'pwd'          => md5($pwd . $salt),
                    'salt'         => $salt,
                    'createtime'   => time(),
                    'mobileverify' => 1,
                    'comefrom'     => 'mobile'
                );

            } else {

                if (empty($member)) {
                    show_json(0, '此手机号未注册');
                }

                $salt = m('account')->getSalt();
                $data = array('salt' => $salt, 'pwd' => md5($pwd . $salt));

            }

            if (empty($member)) {
                pdo_insert(
                    'superdesk_shop_member', // TODO 标志 楼宇之窗 openid shop_member 不处理
                    $data
                );
            } else {
                pdo_update(
                    'superdesk_shop_member', // TODO 标志 楼宇之窗 openid shop_member 不处理
                    $data,
                    array(
                        'id' => $member['id']
                    )
                );
            }

            if (p('commission')) {
                p('commission')->checkAgent($openid, $core_user);
            }

            unset($_SESSION[$key]);
            show_json(1, (empty($type) ? '注册成功' : '密码重置成功'));
        }

        $sendtime = $_SESSION['verifycodesendtime'];


        if (empty($sendtime) || (($sendtime + 60) < time())) {

            $endtime = 0;

        } else {

            $endtime = 60 - time() - $sendtime;

        }

        $set = $this->getWapSet();

        include $this->template('rf', NULL, true);
    }


    public function logout()
    {
        global $_W;
        global $_GPC;
        $key = '__superdesk_shopv2_member_session_' . $_W['uniacid'];
        isetcookie($key, false, -100);
        header('location: ' . mobileUrl());
        exit();
    }


    public function sns()
    {
        global $_W;
        global $_GPC;

        if (SUPERDESK_SHOPV2_MODE_USER == 1 && (is_weixin() || !empty($_GPC['__superdesk_shopv2_member_session_' . $_W['uniacid']]))) {
            header('location: ' . mobileUrl());
        }

        $sns = trim($_GPC['sns']);

        if ($_W['ispost'] && !empty($sns) && !empty($_GPC['openid'])) {
            m('member')->checkMemberSNS($sns);
        }

        if ($_GET['openid']) {
            if ($sns == 'qq') {
                $_GET['openid'] = 'sns_qq_' . $_GET['openid'];
            }
            if ($sns == 'wx') {
                $_GET['openid'] = 'sns_wx_' . $_GET['openid'];
            }
            m('account')->setLogin($_GET['openid']);
        }

        $backurl = '';

        if (!empty($_GPC['backurl'])) {
            $backurl = $_W['siteroot'] . 'app/index.php?' . base64_decode(urldecode($_GPC['backurl']));
        }

        $backurl = ((empty($backurl) ? mobileUrl(NULL, NULL, true) : trim($backurl)));

        header('location: ' . $backurl);
    }


    public function verifycode()
    {
        global $_W;
        global $_GPC;

        $mobile = trim($_GPC['mobile']);
        $temp   = trim($_GPC['temp']);

        if (empty($mobile) || empty($temp)) {
            show_json(0, '参数错误');
        }

        $data = m('common')->getSysset('wap');

        $sms_id = $data[$temp];

        if (empty($sms_id)) {
            show_json(0, '短信发送失败(NOSMSID)');
        }

        $key = '__superdesk_shopv2_member_verifycodesession_' . $_W['uniacid'] . '_' . $mobile;

        @session_start();

        $code = random(5, true);

        $shopname = $_W['shopset']['shop']['name'];

        $ret = com('sms')->send($mobile, $sms_id, array('验证码' => $code, '商城名称' => (!empty($shopname) ? $shopname : '商城名称')));

        if ($ret['status']) {

            $_SESSION[$key]                 = $code;
            $_SESSION['verifycodesendtime'] = time();
            show_json(1, '短信发送成功');
        }

        show_json(0, $ret['message']);
    }
}

