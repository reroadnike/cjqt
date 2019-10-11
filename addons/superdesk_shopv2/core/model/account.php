<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

include_once(IA_ROOT . '/addons/superdesk_core/service/TbuserService.class.php');
include_once(IA_ROOT . '/addons/superdesk_core/service/VirtualarchitectureService.class.php');
include_once(IA_ROOT . '/addons/superdesk_core/service/OrganizationService.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/__plugin__/enterprise/enterprise_user.class.php');


include_once(IA_ROOT . '/addons/superdesk_shopv2/service/member/MemberService.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/service/plugin/MerchService.class.php');


class Account_SuperdeskShopV2Model
{
    private $_tbuserService;
    private $_virtualarchitectureService;
    private $_organizationService;
    private $_enterprise_userModel;

    private $_memberService;
    private $_plugin_merchService;

    public function __construct()
    {
        // mark welfare
        switch (SUPERDESK_SHOPV2_MODE_USER) {
            case 1:// 1 超级前台
                $this->_tbuserService              = new TbuserService();
                $this->_virtualarchitectureService = new VirtualarchitectureService();
                $this->_organizationService        = new OrganizationService();
                break;
            case 2:// 2 福利商城
                $this->_enterprise_userModel = new enterprise_userModel();
                break;
        }

        $this->_memberService       = new MemberService();
        $this->_plugin_merchService = new MerchService();

    }

    /**
     * 此方法 修正
     * cookie 2 _W['superdesk_shop_member_2_merch_ids']
     *
     * @param $member
     */
    public function setFilterMerch($member = array())
    {
        global $_W;
        global $_GPC;

        $superdesk_shop_member_2_merch_ids       = $this->_plugin_merchService->getMerchByEnterpriseId($member['core_enterprise']);
        $_W['superdesk_shop_member_2_merch_ids'] = $superdesk_shop_member_2_merch_ids;
//        socket_log("account.setFilterMerch::" . "商户ids" . "::" . $superdesk_shop_member_2_merch_ids . ";企业id" . "::" . $member['core_enterprise']);

        // 此方式 弃用
//        $_cookie_key = '__superdesk_shopv2_merchids_session_' . $_W['uniacid'];
//        isetcookie($_cookie_key, $merchids);


    }

    /**
     * 更新登陆时间 // TODO 将要 弃用
     *
     * @param $shop_member_id
     */
    public function updateLoginTime($shop_member_id)
    {

        global $_W;
        global $_GPC;

        $this->_memberService->updateLoginTime($shop_member_id);

    }

    /**
     * 楼宇之窗
     * checkEnterpriseUserLogin checkWelfareUserLogin checkBuildWindowUserLogin 平行
     */
    public function checkBuildWindowUserLogin()
    {
        global $_W;
        global $_GPC;

        if (empty($_W['core_user'])) {
            $this->error('帐号异常');
        }

        socket_log("m(account)->checkBuildWindowUserLogin : " . $_W['openid']);

        $userMobile        = $_GPC['userMobile'];  // 手机号码
        $core_organization = $_GPC['core_organization'];       // 企业ID
        $core_enterprise   = $_GPC['core_enterprise'];       // 项目ID
        $core_user         = $_GPC['core_user'];      // 用户ID

        $curr_virtualarchitecture = $this->_virtualarchitectureService->getOneByVirtualArchId($_W['core_enterprise']);

        $_W['virtualarchitecture_name'] = $curr_virtualarchitecture['name'];

        $curr_organization = $this->_organizationService->getOneByOrganizationId($_W['core_organization']);

        $_W['organization_name'] = $curr_organization['name'];

        //2019年3月22日 15:38:01 zjh 微信端商品列表没有检查服务企业
        $member = m('member')->getMember($_W['openid'], $_W['core_user']);
        $this->setFilterMerch($member);
        $this->updateLoginTime($member['id']);

        // TODO 处理入口 带参

        // TODO |---- setLogin($member)

        // TODO 业务页面 不带参

        // TODO 处理异常

    }

    /**
     * 福得内购
     * checkEnterpriseUserLogin checkWelfareUserLogin checkBuildWindowUserLogin 平行
     */
    public function checkWelfareUserLogin()
    {
        global $_W;
        global $_GPC;

        $_cookie_key = '__superdesk_shopv2_mobile_session_' . $_W['uniacid'];

        $userMobile = empty($_GPC['userMobile']) ? $_COOKIE[$_W['config']['cookie']['pre'] . $_cookie_key] : $_GPC['userMobile'];

        $member = $this->_memberService->getOneByOpenid($_W['openid']);

        $enterprise                     = $this->_enterprise_userModel->getOne($member['core_enterprise']);
        $_W['virtualarchitecture_name'] = $enterprise['enterprise_name'];

        $this->setFilterMerch($member);
        $this->updateLoginTime($member['id']);
    }

    /**
     * 企业采购 同步帐号 与 合并帐号
     * checkEnterpriseUserLogin checkWelfareUserLogin checkBuildWindowUserLogin 平行
     */
    public function checkEnterpriseUserLogin()
    {
        global $_W;
        global $_GPC;

        $_cookie_key = '__superdesk_shopv2_mobile_session_' . $_W['uniacid'];

        $userMobile = empty($_GPC['userMobile']) ? $_COOKIE[$_W['config']['cookie']['pre'] . $_cookie_key] : $_GPC['userMobile'];

        // userMobile手机号码，virId企业ID，orgId项目ID

//        socket_log("checkEnterpriseUserLogin:" . $userMobile);

        // check mobile
        if (empty($userMobile)) {

//            $diemsg = '请在超级前台注册登陆';
//            $this->error($diemsg);
            header('location: ' . 'http://www.avic-s.com/super_reception/wechat/appUser/bindphone');
            exit();

        } else {


            isetcookie($_cookie_key, $userMobile, time() + 7 * 3600 * 24);


            // 临时存放超级前台同步过的用户
            $_tb_user = $this->_tbuserService->getOneByUserMobile($userMobile);

//            organizationId
//            virtualArchId

            if ($_tb_user) {

                // status:0-未审核;1-已认证;2-未认证
                // isEnabled:1-可用;0-删除
                // 视情况做处理

            } else {
                // 从中航超级前台过来，但未注册的，转跳到超级前台注册
                // OR
                // 同步数据

//                if(SUPERDESK_SHOPV2_DEBUG/*true 为 debug false 为正式*/)
                $diemsg = '请转跳到超级前台注册或请求管理员同步数据';
                $this->error($diemsg);


            }

            $curr_virtualarchitecture = $this->_virtualarchitectureService->getOneByVirtualArchId($_tb_user['virtualArchId']);

            $_W['virtualarchitecture_name'] = $curr_virtualarchitecture['name'];

            $curr_organization = $this->_organizationService->getOneByOrganizationId($_tb_user['organizationId']);

            $_W['organization_name'] = $curr_organization['name'];

            $isBind = $this->_memberService->isBindMember($userMobile, $_W['openid']);

//            socket_log("account:checkEnterpriseUserLogin:isBind:" . json_encode($isBind,JSON_UNESCAPED_UNICODE));

            if ($isBind) {

//                SELECT * FROM `ims_superdesk_shop_member` WHERE core_enterprise = 0 241
//                SELECT * FROM `ims_superdesk_shop_member` WHERE core_enterprise = 0 and openid LIKE 'wap_user_16_%' 2
//                SELECT * FROM `ims_superdesk_shop_member` WHERE core_enterprise = 0 and openid NOT LIKE 'wap_user_16_%' 239
//                SELECT * FROM `ims_superdesk_shop_member` WHERE core_enterprise = 0 and openid NOT LIKE 'wap_user_16_%' AND mobile = '' 187


                // 更新真实姓名
                $this->_memberService->updateRealname($isBind['id'], $_tb_user['userName']);

                // 更新企业
                if (empty($isBind['core_enterprise'])) {

                    $isBind = $this->_memberService->bindShopMemberCoreEnterprise($isBind['id'], $_tb_user['virtualArchId']);

                } elseif ($isBind['core_enterprise'] != $_tb_user['virtualArchId']) {

                    $isBind = $this->_memberService->bindShopMemberCoreEnterprise($isBind['id'], $_tb_user['virtualArchId']);

                }


                $this->setFilterMerch($isBind);
                $this->updateLoginTime($isBind['id']);

                return;
            }

            // 以下为合并帐号处理 start

            // debug // 在浏览器登陆会出显 $_shop_member_openid 为 false 原因是 $_W['openid'] 为 '' 此情况在 企业采购-正式 中测出
            $_shop_member_openid = $this->_memberService->getOneByOpenid($_W['openid']); // query shop member by openid
            $_shop_member_mobile = $this->_memberService->getOneByMobile($userMobile); // query shop member by mobile


            if ($_shop_member_mobile) {

                //
                if ($_shop_member_mobile['openid'] == $_W['openid']) {// 正常用户

                    // 将tb_user 的信息更新到 shop_member ，　因为那些人有可以更改公司

                    $this->_memberService->syncTbUser($_shop_member_mobile['id'], $_tb_user);

                } else if (
                    $this->_memberService->iswxm($_shop_member_mobile)
                    && $_shop_member_mobile['openid'] != $_W['openid']) {// 换了微信号的帐号

                    socket_log('浏览器中会误报:换了微信号的帐号情况:openid: |' . $_W['openid'] . '|');

                    if (!empty($_W['openid']) && $_shop_member_openid) { // 保证是在微信中去做这个事

                        socket_log('换了微信号的帐号-情况');

                        // debug // 在浏览器登陆会出显 $_shop_member_openid 为 false 原因是 $_W['openid'] 为 '' 此情况在 企业采购-正式 中测出
                        socket_log("account:checkEnterpriseUserLogin:openid: | " . $_W['openid'] . ' | ' . json_encode($_shop_member_openid, JSON_UNESCAPED_UNICODE));
                        socket_log("account:checkEnterpriseUserLogin:mobile: | " . json_encode($_shop_member_mobile, JSON_UNESCAPED_UNICODE));

                        if ($this->_memberService->iswxm($_shop_member_mobile)) { // 判定 这个帐号的来源是微信吗? 如果是微信才合并
                            $this->_memberService->merge($_shop_member_mobile, $_shop_member_openid);
                        }
                    }

                } else { // 异常:同样电话的信息

                    // bind => 合并帐号
                    // 将tb_user 的信息更新到 shop_member

                    // SELECT * FROM `ims_superdesk_shop_member` WHERE openid LIKE 'wap_user_%' 还有1005未合并帐号
//                    socket_log("account:checkEnterpriseUserLogin:openid:" . json_encode($_shop_member_openid, JSON_UNESCAPED_UNICODE));
//                    socket_log("account:checkEnterpriseUserLogin:mobile:" . json_encode($_shop_member_mobile, JSON_UNESCAPED_UNICODE));

                    if (!$this->_memberService->iswxm($_shop_member_mobile)) { // 判定 这个帐号的来源是渠道吗? 如果是渠道才合并
                        $this->_memberService->merge($_shop_member_mobile, $_shop_member_openid);
                    }

                }

            } else if ($_shop_member_openid) { // 在浏览器登陆会出显 $_shop_member_openid 为 false
                // 将tb_user 的信息更新到 shop_member
                // realname
                // mobile
                // salt
                // pwd
                // comeform mobile
                // core_enterprise

//                if($_shop_member_openid['id'] == $_shop_member_mobile['id']){ // 此情况就是电话没到位的问题

                $this->_memberService->syncTbUser($_shop_member_openid['id'], $_tb_user);

//                } else {
//                    // bind => 合并帐号
//                    // 将tb_user 的信息更新到 shop_member
//                }
            }

            // 以下为合并帐号处理 end

            // TODO
        }
    }

    public function error($diemsg)
    {
        exit('<!DOCTYPE html>' .
            "\r\n" . '<html>' .
            "\r\n" . '    <head>' .
            "\r\n" . '        <meta name=\'viewport\' content=\'width=device-width, initial-scale=1, user-scalable=0\'>' .
            "\r\n" . '        <title>抱歉，出错了</title><meta charset=\'utf-8\'><meta name=\'viewport\' content=\'width=device-width, initial-scale=1, user-scalable=0\'><link rel=\'stylesheet\' type=\'text/css\' href=\'https://res.wx.qq.com/connect/zh_CN/htmledition/style/wap_err1a9853.css\'>' .
            "\r\n" . '    </head>' .
            "\r\n" . '    <body>' .
            "\r\n" . '    <div class=\'page_msg\'><div class=\'inner\'><span class=\'msg_icon_wrp\'><i class=\'icon80_smile\'></i></span><div class=\'msg_content\'><h4>' . $diemsg . '</h4></div></div></div>' .
            "\r\n" . '    </body>' .
            "\r\n" . '</html>');
    }

    public function checkLogin()
    {
        global $_W;
        global $_GPC;

        socket_log('m(account)->checkLogin() : called');

        if (empty($_W['openid'])) {

            switch (SUPERDESK_SHOPV2_IS_BUILD_WINDOW) {
                case 0:// 0 没有绑定楼宇之窗
                    $openid = $this->checkOpenid();
                    break;
                case 1:// 1 关联绑定楼宇之窗
                    $openid = $this->checkCoreUser();
                    break;
            }

            socket_log("Account :: checkLogin :: " . $openid);

            $url = urlencode(base64_encode($_SERVER['QUERY_STRING']));

            $key = '__superdesk_shopv2_redirect_back_url_' . $_W['uniacid'];

            if (empty(strpos($_SERVER['QUERY_STRING'], 'member.changepwd'))) {
                @session_start();
                $_SESSION[$key] = $url;
            } else {
                $url = $_SESSION[$key];
            }

            if (!empty($openid)) {

                $member = m('member')->getMember($openid, $_W['core_user']);

                if ($member['pwd'] == md5('12345678' . $member['salt']) && $_GPC['r'] != 'member.changepwd') {
                    $changeUrl = mobileUrl('member/changepwd',
                        array(
                            'mid' => $_GPC['mid']
                        )
                    );

                    if ($_W['isajax']) {
                        show_json(0, array('url' => $changeUrl, 'message' => '初始密码必须先修改!'));
                    }

                    socket_log("Account :: checkLogin :: " . $changeUrl);

                    header('location: ' . $changeUrl);
                    exit();
                }

                return $openid;
            }

            $loginurl = mobileUrl('account/login',
                array(
                    'mid'     => $_GPC['mid'],
                    'backurl' => ($_W['isajax'] ? '' : $url)
                )
            );

            if ($_W['isajax']) {

                show_json(0, array(
                    'url'     => $loginurl,
                    'message' => '请先登录!'
                ));
            }

//            socket_log("Account :: checkLogin :: " . $loginurl);

            header('location: ' . $loginurl);

            exit();
        }
    }

    public function checkOpenid()
    {
        global $_W;
        global $_GPC;

        socket_log("m(account)->checkOpenid : called");

        $key = '__superdesk_shopv2_member_session_' . $_W['uniacid'];

        if (isset($_GPC[$key])) { // 先去cookie那查

//            socket_log("Account :: checkOpenid :: " . base64_decode($_GPC[$key]));

            $session = json_decode(base64_decode($_GPC[$key]), true);

            if (is_array($session)) {

                $member = m('member')->getMember($session['openid'], $session['core_user']);

                if (is_array($member)
                    && ($session['superdesk_shopv2_member_hash'] == md5($member['pwd'] . $member['salt']))
                ) {

                    $GLOBALS['_W']['superdesk_shopv2_member_hash'] = md5($member['pwd'] . $member['salt']);
                    $GLOBALS['_W']['superdesk_shopv2_member']      = $member;

                    $GLOBALS['_W']['openid']    = $member['openid'];
                    $GLOBALS['_W']['core_user'] = $member['core_user']; // TODO 这东西在这有可能为 0

                    return $member['openid'];
                }

                socket_log("m(account)->checkOpenid : " . "isetcookie set false ");

                isetcookie($key, false, -100);
            }
        }
    }

    /**
     * 与 checkOpenid 平行
     *
     * @return mixed
     */
    public function checkCoreUser()
    {
        global $_W;
        global $_GPC;

        socket_log("m(account)->checkCoreUser : called");

        $key = '__superdesk_shopv2_member_session_' . $_W['uniacid'];

        if (isset($_GPC[$key])) { // 先去cookie那查

//            socket_log("Account :: checkOpenid :: " . base64_decode($_GPC[$key]));

            $session = json_decode(base64_decode($_GPC[$key]), true);

            if (is_array($session)) {

                $member = m('member')->getMemberByCoreUser($session['core_user']);

                if (is_array($member)
                    && ($session['superdesk_shopv2_member_hash'] == md5($member['pwd'] . $member['salt']))
                ) {

                    $GLOBALS['_W']['superdesk_shopv2_member_hash'] = md5($member['pwd'] . $member['salt']);
                    $GLOBALS['_W']['superdesk_shopv2_member']      = $member;

                    $GLOBALS['_W']['openid']    = $member['openid'];
                    $GLOBALS['_W']['core_user'] = $member['core_user']; // TODO 这东西在这如果为 0 就是错的

                    return $member['openid'];
                }

                socket_log("m(account)->checkOpenid : " . "isetcookie set false ");

                isetcookie($key, false, -100);
            }
        }
    }

    public function getLogin()
    {

        global $_W;
        global $_GPC;

        $key = '__superdesk_shopv2_member_session_' . $_W['uniacid'];

        if (isset($_GPC[$key])) { // 先去cookie那查

            $_cookies_shop_member = json_decode(base64_decode($_GPC[$key]), true);

            return $_cookies_shop_member;

        }

        return false;
    }

    /**
     * @param $member array | int | string
     */
    public function setLogin($member)
    {
        global $_W;

        socket_log("m(account)->setLogin : " . json_encode($member));

        if (!is_array($member)) {
            $member = m('member')->getMember($member);
        }

        if (!empty($member)) {

            // 如果是 PC端 作的补充 微信端 等价
            $_W['openid']    = $member['openid'];
            $_W['core_user'] = $member['core_user'];

            // 上边代码的写法不同而已
//            $GLOBALS['_W']['openid']    = $member['openid'];
//            $GLOBALS['_W']['core_user'] = $member['core_user'];

            $member['superdesk_shopv2_member_hash'] = md5($member['pwd'] . $member['salt']);

            $key = '__superdesk_shopv2_member_session_' . $_W['uniacid'];

            $cookie = base64_encode(json_encode($member));

            isetcookie($key, $cookie, 7 * 86400);
        }
    }

    public function getSalt()
    {
        return $this->_memberService->getSalt();
    }

    public function updateOpenidAllTable($core_user,$old_openid,$new_openid){
        $this->_memberService->mergeOpenidByCoreUser($core_user, $old_openid, $new_openid);
    }


}