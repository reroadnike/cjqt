<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

include_once(IA_ROOT . '/framework/library/logs/LogsUtil.class.php');

class Member_SuperdeskShopV2Model
{

    public function getInfoById()
    {

    }

    public function getInfoByCoreUser()
    {

    }

    /**
     * 获取会员资料
     *
     * @param string $openid
     *
     * @return bool
     */
    public function getInfo($openid = '', $core_user = 0)
    {
        global $_W;
        $uid = intval($openid);

        // 传入 openid 情况
        if ($uid == 0) {

            $info = pdo_fetch(
                ' select * ' .
                ' from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid shop_member 已处理
                ' where openid=:openid ' .
                '       and core_user=:core_user ' .
                '       and uniacid=:uniacid ' .
                ' limit 1',
                array(
                    ':uniacid'   => $_W['uniacid'],
                    ':openid'    => $openid,
                    ':core_user' => $core_user
                )
            );

            socket_log($info);
        } // 传入 uid 情况
        else {
            $info = pdo_fetch(
                ' select * ' .
                ' from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid shop_member 不处理
                ' where ' .
                '       id=:id ' .
                '       and uniacid=:uniacid ' .
                ' limit 1',
                array(
                    ':uniacid' => $_W['uniacid'],
                    ':id'      => $uid
                )
            );
        }

        if (!empty($info['uid'])) {

            load()->model('mc');

            $uid = mc_openid2uid($info['openid']);

            $fans = mc_fetch($uid, array(
                    'credit1', 'credit2', 'birthyear', 'birthmonth', 'birthday', 'gender',
                    'avatar', 'resideprovince', 'residecity', 'nickname')
            );

            $info['credit1']    = $fans['credit1'];
            $info['credit2']    = $fans['credit2'];
            $info['birthyear']  = (empty($info['birthyear']) ? $fans['birthyear'] : $info['birthyear']);
            $info['birthmonth'] = (empty($info['birthmonth']) ? $fans['birthmonth'] : $info['birthmonth']);
            $info['birthday']   = (empty($info['birthday']) ? $fans['birthday'] : $info['birthday']);
            $info['nickname']   = (empty($info['nickname']) ? $fans['nickname'] : $info['nickname']);
            $info['gender']     = (empty($info['gender']) ? $fans['gender'] : $info['gender']);
            $info['sex']        = $info['gender'];
            $info['avatar']     = (empty($info['avatar']) ? $fans['avatar'] : $info['avatar']);
            $info['headimgurl'] = $info['avatar'];
            $info['province']   = (empty($info['province']) ? $fans['resideprovince'] : $info['province']);
            $info['city']       = (empty($info['city']) ? $fans['residecity'] : $info['city']);
        }


        if (!empty($info['birthyear'])
            && !empty($info['birthmonth'])
            && !empty($info['birthday'])
        ) {

            $info['birthday'] = $info['birthyear'] . '-' . ((strlen($info['birthmonth']) <= 1 ? '0' . $info['birthmonth'] : $info['birthmonth'])) . '-' . ((strlen($info['birthday']) <= 1 ? '0' . $info['birthday'] : $info['birthday']));
        }


        if (empty($info['birthday'])) {
            $info['birthday'] = '';
        }

        return $info;
    }

//    public function getMemberByUserMobileVirIdOrgId($userMobile, $virId, $orgId, $selectField = ' * ')
//    {
//        global $_W;
//
//        $shop_member = pdo_fetch(
//            ' select ' . $selectField . ' ' .
//            ' from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid shop_member 不处理
//            ' where mobile=:mobile ' .
//            '       and core_organization=:core_organization ' .
//            '       and core_enterprise=:core_enterprise ' .
//            '       and uniacid=:uniacid ' .
//            ' limit 1',
//            array(
//                ':uniacid'           => $_W['uniacid'],
//                ':mobile'            => $userMobile,
//                ':core_organization' => $orgId,
//                ':core_enterprise'   => $virId,
//            )
//        );
//
//        if ($shop_member == false) {
//            return $this->createMemberByBuildWindow($userMobile, $virId, $orgId, $selectField);
//        }
//
//        return $shop_member;
//
//    }

    public function getMemberByCoreUserEnterprise($core_user, $core_enterprise, $selectField = ' * ')
    {
        global $_W;

        $shop_member = pdo_fetch(
            ' select ' . $selectField . ' ' .
            ' from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid shop_member 不处理
            ' where core_user=:core_user ' .
            '       and core_enterprise=:core_enterprise ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':uniacid'   => $_W['uniacid'],
                ':core_user' => $core_user,
                ':core_enterprise' => $core_enterprise
            )
        );

        if ($shop_member == false) {
            return $this->createMemberByBuildWindow($core_user, $selectField);
        }

        return $shop_member;
    }

    public function getMemberByCoreUser($core_user, $selectField = ' * ')
    {
        global $_W;

        $shop_member = pdo_fetch(
            ' select ' . $selectField . ' ' .
            ' from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid shop_member 不处理
            ' where core_user=:core_user ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':uniacid'   => $_W['uniacid'],
                ':core_user' => $core_user
            )
        );

        if ($shop_member == false) {

            //日志记录
            LogsUtil::logging('info', 'getMemberByCoreUser create member: ' . $core_user, 'openid_create');

            return $this->createMemberByBuildWindow($core_user, $selectField);
        }

        return $shop_member;
    }


    public function getMemberById($shop_member_id, $selectField = ' * ')
    {
        global $_W;

        $shop_member = pdo_fetch(
            ' select ' .
            $selectField .
            ' from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid shop_member 不处理
            ' where id=:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':uniacid' => $_W['uniacid'],
                ':id'      => $shop_member_id
            )
        );

        return $shop_member;
    }

    /**
     * 获取会员信息
     * // TODO 调用错误 CORE_USER ADD
     *
     * @param string $openid
     *
     * @return bool
     */
    public function getMember($openid = '', $core_user = 0, $selectField = ' * ')
    {
        global $_W;

        $shop_member = pdo_fetch(
            ' select ' .
            $selectField .
            ' from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid shop_member 已处理
            ' where openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and uniacid=:uniacid ' .
            ' order by createtime desc ' .
            ' limit 1',
            array(
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $openid,
                ':core_user' => $core_user
            )
        );

//        if (empty($shop_member)) {
//
//            if (strexists($openid, 'sns_qq_')) {
//
//                $openid    = str_replace('sns_qq_', '', $openid);
//                $condition = ' openid_qq=:openid ';
//                $bindsns   = 'qq';
//
//            } else if (strexists($openid, 'sns_wx_')) {
//
//                $openid    = str_replace('sns_wx_', '', $openid);
//                $condition = ' openid_wx=:openid ';
//                $bindsns   = 'wx';
//
//            }
//
//            if (!empty($condition)) {
//
//                $shop_member = pdo_fetch(
//                    ' select ' .
//                    $selectField .
//                    ' from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid shop_member 已处理
//                    ' where ' .
//                    $condition .
//                    '       and uniacid=:uniacid ' .
//                    ' limit 1',
//                    array(
//                        ':uniacid'   => $_W['uniacid'],
//                        ':openid'    => $openid,
//                        ':core_user' => $core_user,
//                    )
//                );
//
//                if (!empty($shop_member)) {
//                    $shop_member['bindsns'] = $bindsns;
//                }
//            }
//        }


//        if (!empty($shop_member)) {
//
//            $openid = $shop_member['openid'];
//
//            if (empty($shop_member['uid'])) {
//
//                $followed = m('user')->followed($openid);
//
//                if ($followed) {
//
//                    load()->model('mc');
//
//                    $uid = mc_openid2uid($openid);
//
//                    if (!empty($uid)) {
//
//                        $shop_member['uid'] = $uid;
//
//                        $upgrade            = array('uid' => $uid);
//
//                        if (0 < $shop_member['credit1']) {
//                            mc_credit_update($uid, 'credit1', $shop_member['credit1']);
//                            $upgrade['credit1'] = 0;
//                        }
//
//
//                        if (0 < $shop_member['credit2']) {
//                            mc_credit_update($uid, 'credit2', $shop_member['credit2']);
//                            $upgrade['credit2'] = 0;
//                        }
//
//
//                        if (!empty($upgrade)) {
//
//                            pdo_update(
//                                'superdesk_shop_member', // TODO 标志 楼宇之窗 openid shop_member 不处理
//                                $upgrade,
//                                array(
//                                    'id' => $shop_member['id']
//                                )
//                            );
//                        }
//                    }
//                }
//            }
//
//            $credits = $this->getCredits($openid);
//
//            $shop_member['credit1'] = $credits['credit1'];
//            $shop_member['credit2'] = $credits['credit2'];
//        }

        return $shop_member;
    }

    /**
     * @return mixed
     */
    public function getMid()
    {
        global $_W;

        $member = $this->getMember($_W['openid'], $_W['core_user']);

        return $member['id'];
    }

    public function setCreditById($mid, $credittype = 'credit1', $credits = 0, $log = array())
    {


        $value = pdo_fetchcolumn(
            ' SELECT ' . $credittype .
            ' FROM ' . tablename('mc_members') .
            ' WHERE `uid` = :uid',
            array(
                ':uid' => $uid
            )
        );

        $newcredit = $credits + $value;

        if ($newcredit <= 0) {
            $newcredit = 0;
        }

        $rs = pdo_update('mc_members',
            array(
                $credittype => $newcredit
            ),
            array(
                'uid' => $uid
            )
        );

        if (empty($log)) {
            $log = array($uid, '未记录');
        } else if (!is_array($log)) {
            $log = array(0, $log);
        }


        $data = array(
            'uid'        => $uid,
            'credittype' => $credittype,
            'uniacid'    => $_W['uniacid'],
            'num'        => $credits,
            'createtime' => TIMESTAMP,
            'module'     => 'superdesk_shopv2',
            'operator'   => intval($log[0]),
            'remark'     => $log[1]
        );

        pdo_insert('mc_credits_record', $data);

        if (!empty($credit2Log) && !empty($rs)) {

            $this->insertCreditLog(
                $openid,
                $core_user,
                $credit2Log['type'],
                $credit2Log['createtime'],
                $credits,
                $value,
                $credit2Log['orderid']
            );
        }

        return $rs;
    }

    /**
     * @param string $openid
     * @param string $credittype
     * @param int    $credits
     * @param array  $log
     *
     * @return null
     */
    public function setCredit($openid = '', $core_user = 0, $credittype = 'credit1', $credits = 0, $log = array(), $credit2Log = array())
    {
        global $_W;

//        load()->model('mc');
//        $uid = mc_openid2uid($openid);

        $credit_cur = pdo_fetchcolumn(
            ' SELECT ' . $credittype .
            ' FROM ' . tablename('superdesk_shop_member') .// TODO 标志 楼宇之窗 openid shop_member 待处理
            ' WHERE ' .
            '       uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            ' limit 1',
            array(
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $openid,
                ':core_user' => $core_user,
            )
        );

        $credit_new = $credits + $credit_cur;

        if ($credit_new <= 0) {
            $credit_new = 0;
        }

        $rs = pdo_update( // 不根据id更新
            'superdesk_shop_member',// TODO 标志 楼宇之窗 openid shop_member 待处理
            array(
                $credittype => $credit_new
            ),
            array(
                'uniacid'   => $_W['uniacid'],
                'openid'    => $openid,
                'core_user' => $core_user,
            )
        );

//        socket_log('m(member)->setCredit');
//        socket_log($rs);
//        socket_log($credit2Log);

        if (!empty($credit2Log) && !empty($rs)) {
            $this->insertCreditLog(
                $openid,
                $core_user,
                $credit2Log['type'],
                $credit2Log['createtime'],
                $credits,
                $credit_cur,
                $credit2Log['orderid']
            );
        }

        return $rs;
    }

    /**
     * @param string $openid
     * @param string $credittype
     *
     * @return bool
     */
    public function getCredit($openid = '', $core_user = 0, $credittype = 'credit1')
    {
        global $_W;

//        load()->model('mc');
//        $uid = mc_openid2uid($openid);

//        if (!empty($uid)) {
//            return pdo_fetchcolumn(
//                ' SELECT ' . $credittype .
//                ' FROM ' . tablename('mc_members') .
//                ' WHERE `uid` = :uid',
//                array(':uid' => $uid));
//        }


        return pdo_fetchcolumn(
            ' SELECT ' . $credittype .
            ' FROM ' . tablename('superdesk_shop_member') .// TODO 标志 楼宇之窗 openid shop_member 待处理
            ' WHERE ' .
            '       uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            ' limit 1',
            array(
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $openid,
                ':core_user' => $core_user,
            )
        );
    }

    /**
     * @param string $openid
     * @param array  $credittypes
     *
     * @return bool
     */
    public function getCredits($openid = '', $core_user = 0, $credittypes = array('credit1', 'credit2'))
    {
        global $_W;

//        load()->model('mc');
//        $uid = mc_openid2uid($openid);

        $types = implode(',', $credittypes);

//        if (!empty($uid)) {
//
//            return pdo_fetch(
//                'SELECT ' . $types .
//                ' FROM ' . tablename('mc_members') .
//                ' WHERE ' .
//                '       uid = :uid ' .
//                ' limit 1',
//                array(
//                    ':uid' => $uid
//                )
//            );
//        }

        return pdo_fetch(
            ' SELECT ' . $types .
            ' FROM ' . tablename('superdesk_shop_member') .// TODO 标志 楼宇之窗 openid shop_member 待处理
            ' WHERE ' .
            '       uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .

            ' limit 1',
            array(
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $openid,
                ':core_user' => $core_user,
            )
        );
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

    /**
     * 基于微信openid
     * 检测用户
     * 注册用户
     *
     * @return array|bool
     */
    public function checkMember($selectField = ' * ')
    {
        global $_W;
        global $_GPC;

        $member = array();

        $shopset = m('common')->getSysset(array('shop', 'wap'));

        if (($_W['routes'] == 'order.pay_alipay')
            || ($_W['routes'] == 'order.pay_alipay.recharge_complete')
            || ($_W['routes'] == 'order.pay_alipay.complete')
        ) {
            return NULL;
        }

        if ($shopset['wap']['open']) {

            if (($shopset['wap']['inh5app'] && is_h5app())
                || (empty($shopset['wap']['inh5app']) && empty($_W['openid']))
            ) {
                return NULL;
            }
        }

        if (empty($_W['openid']) && !SUPERDESK_SHOPV2_DEBUG) {

            $diemsg = ((is_h5app() ? 'APP正在维护, 请到公众号中访问' : '请在微信客户端打开链接'));
            $this->error($diemsg);

        }

        $member = $this->getMember($_W['openid'], $_W['core_user'], $selectField);

//        socket_log('mark :: addons/superdesk_shopv2/core/model/member.php => checkMember');
//        socket_log(json_encode($member, JSON_UNESCAPED_UNICODE));


        $followed = m('user')->followed($_W['openid']);

        $uid = 0;
        $mc  = array();

        load()->model('mc');

        if ($followed
            || empty($shopset['shop']['getinfo'])
            || ($shopset['shop']['getinfo'] == 1)
        ) {

            $uid = mc_openid2uid($_W['openid']);

            if (!SUPERDESK_SHOPV2_DEBUG) {

                $userinfo = mc_oauth_userinfo();

//                socket_log('mark :: addons/superdesk_shopv2/core/model/member.php => mc_oauth_userinfo');
//                socket_log(json_encode($userinfo, JSON_UNESCAPED_UNICODE));

            } else {

                $userinfo = array(
                    'openid'     => $member['openid'],
                    'nickname'   => $member['nickname'],
                    'headimgurl' => $member['avatar'],
                    'gender'     => $member['gender'],
                    'province'   => $member['province'],
                    'city'       => $member['city']
                );
            }

            $mc = array();

            $mc['nickname']       = $userinfo['nickname'];
            $mc['avatar']         = $userinfo['headimgurl'];
            $mc['gender']         = $userinfo['sex'];
            $mc['resideprovince'] = $userinfo['province'];
            $mc['residecity']     = $userinfo['city'];
        }


        if (empty($member) && !empty($_W['openid'])) {

            $member = array(
                'uid'        => $uid,
                'uniacid'    => $_W['uniacid'],
                'openid'     => $_W['openid'],
                'core_user'  => $_W['core_user'],
                'realname'   => (!empty($mc['realname']) ? $mc['realname'] : ''),
                'mobile'     => (!empty($mc['mobile']) ? $mc['mobile'] : ''),
                'nickname'   => (!empty($mc['nickname']) ? $mc['nickname'] : ''),
                'avatar'     => (!empty($mc['avatar']) ? $mc['avatar'] : ''),
                'gender'     => (!empty($mc['gender']) ? $mc['gender'] : '-1'),
                'province'   => (!empty($mc['resideprovince']) ? $mc['resideprovince'] : ''),
                'city'       => (!empty($mc['residecity']) ? $mc['residecity'] : ''),
                'area'       => (!empty($mc['residedist']) ? $mc['residedist'] : ''),
                'createtime' => time(),
                'status'     => 0
            );

            pdo_insert(
                'superdesk_shop_member', // TODO 标志 楼宇之窗 openid shop_member 不处理
                $member
            );

            $member['id'] = pdo_insertid();

        } else {

            if ($member['isblack'] == 1) {
                show_message('暂时无法访问，请稍后再试!');
            }

            $upgrade = array();

            // 更新昵称
            if (isset($mc['nickname']) && ($member['nickname'] != $mc['nickname'])) {
                $upgrade['nickname'] = $mc['nickname'];
            }

            // 更新头像
            if (isset($mc['avatar']) && ($member['avatar'] != $mc['avatar'])
                && (strpos($member['avatar'], 'attachment') === false || strpos($member['avatar'], 'attachment') === false)
            ) {
                $upgrade['avatar'] = $mc['avatar'];
            }

            // 更新性别
            if (isset($mc['gender']) && ($member['gender'] != $mc['gender'])) {
                $upgrade['gender'] = $mc['gender'];
            }

            if (!empty($upgrade)) {

                pdo_update(
                    'superdesk_shop_member',
                    $upgrade,
                    array(
                        'id' => $member['id']
                    )
                );// TODO 标志 楼宇之窗 openid shop_member 不处理
            }
        }

        if (p('commission')) {
            p('commission')->checkAgent($_W['openid'], $_W['core_user']);
        }

        if (p('poster')) {
            p('poster')->checkScan($_W['openid'], $_W['core_user']);// TODO 标志 楼宇之窗 openid shop_poster_scan 已处理
        }

        if (empty($member)) {
            return false;
        }

        return array(
            'id'        => $member['id'],
            'uid'       => $member['uid'],
            'openid'    => $member['openid'],
            'core_user' => $member['core_user'],
            'core_enterprise' => $member['core_enterprise'],
        );
    }

    /**
     *
     * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&userMobile=13422832499&virId=975&orgId=10
     * 基于楼宇之窗
     * ----userMobile 手机号码
     * ----virId      企业ID
     * ----orgId      项目ID
     * 检测用户
     * 注册用户
     */
    public function checkMemberBuildWindow()
    {

        global $_W;
        global $_GPC;

        $shop_member = [];

        $userMobile        = $_GPC['userMobile'];  // 手机号码
        $core_enterprise   = $_GPC['core_enterprise'];       // 企业ID
        $core_organization = $_GPC['core_organization'];       // 项目ID
        $core_user         = $_GPC['core_user'];      // Tb_user ID
        $openid            = $_W['openid'];


        // 信息 这三个参数较正 start

        // 非 首次进入
        $isCheckCookie = false;
        if (empty($userMobile) || empty($core_enterprise) || empty($core_organization) || empty($core_user)) {

            $_debug = compact('userMobile', 'core_enterprise', 'core_organization', 'core_user', 'openid');
            socket_log('首次 ## m(member)->checkMemberBuildWindow() : ' . json_encode($_debug));

            $isCheckCookie = true;
        } // 首次进入 如果都不会空
        else {
            $_W['userMobile'] = $userMobile;  // 手机号码
            // core_organization
            $_W['core_organization'] = $core_organization;       // 项目ID
            // core_enterprise
            $_W['core_enterprise'] = $core_enterprise;       // 企业ID
            // core_user
            $_W['core_user'] = $core_user;      // Tb_user ID

//            $shop_member = $_db_shop_member = $this->getMemberByUserMobileVirIdOrgId(
//                $userMobile,
//                $core_enterprise,
//                $core_organization,
//                ' id,openid,mobile,core_user,core_enterprise,core_organization,pwd,salt '
//            );

            $shop_member = $_db_shop_member = $this->getMemberByCoreUser(
                $core_user,
                ' id,openid,mobile,core_user,core_enterprise,core_organization,pwd,salt '
            );

            $_debug = [
                'userMobile'        => $userMobile,
                'core_enterprise'   => $core_enterprise,
                'core_organization' => $core_organization,
                'core_user'         => $core_user,
                'openid'            => $openid,
            ];
            socket_log('再次 ## m(member)->checkMemberBuildWindow() : ' . json_encode($_debug));
//            socket_log($_db_shop_member);

            if ($_db_shop_member == false) {

//                $diemsg = '请联系楼宇之窗管理员,帐号未添加到白名单';
                $diemsg = '服务升级<br/>超级前台已升级为楼宇之窗！<br/>请联系楼宇之窗管理员，帐号未添加到白名单';
                $this->error($diemsg);

            } else {
                m('account')->setLogin($_db_shop_member);
            }

        }

        // 非首次进入 ,所以來查cookie
        if ($isCheckCookie) {

            $shop_member = $_cookies_shop_member = m('account')->getLogin();

            if ($_cookies_shop_member == false) {
                $diemsg = '服务升级<br/>超级前台已升级为楼宇之窗！';
                $this->error($diemsg);
            } else {
                $_W['userMobile']        = $_cookies_shop_member['mobile'];  // 手机号码
                $_W['core_organization'] = $_cookies_shop_member['core_organization'];       // 项目ID
                $_W['core_enterprise']   = $_cookies_shop_member['core_enterprise'];       // 企业ID
                $_W['core_user']         = $_cookies_shop_member['core_user'];       // 企业ID

            }
        }


        $this->updateMemberByBuildWindow($_W['core_user']);
        // 信息 这三个参数较正 end

        $shopset = m('common')->getSysset(array('shop', 'wap'));

        if (($_W['routes'] == 'order.pay_alipay')
            || ($_W['routes'] == 'order.pay_alipay.recharge_complete')
            || ($_W['routes'] == 'order.pay_alipay.complete')
        ) {
            return NULL;
        }

        if ($shopset['wap']['open']) {

            if (($shopset['wap']['inh5app'] && is_h5app())
                || (empty($shopset['wap']['inh5app']) && empty($openid))
            ) {
                return NULL;
            }

        }

        if (empty($openid) && !SUPERDESK_SHOPV2_DEBUG) {
            $diemsg = ((is_h5app() ? 'APP正在维护, 请到公众号中访问' : '请在微信客户端打开链接'));
            $this->error($diemsg);
        }

        // TODO
//        逻辑放到别的地方了
        // TODO

        if (p('commission')) {
            p('commission')->checkAgent($_W['openid'], $_W['core_user']);
        }

        if (p('poster')) {
            p('poster')->checkScan($_W['openid'], $_W['core_user']);// TODO 标志 楼宇之窗 openid shop_poster_scan 已处理
        }

        if (empty($shop_member)) {
            return false;
        }

        return $shop_member;

    }

    public function checkMemberFromPlatform($openid = '')
    {
        global $_W;

        $acc = WeiXinAccount::create($_W['acid']);

        $userinfo = $acc->fansQueryInfo($openid);

//        socket_log('mark :: addons/superdesk_shopv2/core/model/member.php :: checkMemberFromPlatform');
//        socket_log(json_encode($userinfo,JSON_UNESCAPED_UNICODE));

        $userinfo['avatar'] = $userinfo['headimgurl'];

        load()->model('mc');

        $uid = mc_openid2uid($openid);

        if (!empty($uid)) {
            pdo_update(
                'mc_members',
                array(
                    'nickname'       => $userinfo['nickname'],
                    'gender'         => $userinfo['sex'],
                    'nationality'    => $userinfo['country'],
                    'resideprovince' => $userinfo['province'],
                    'residecity'     => $userinfo['city'],
                    'avatar'         => $userinfo['headimgurl']
                ),
                array(
                    'uid' => $uid
                )
            );
        }

        pdo_update(
            'mc_mapping_fans',
            array(
                'nickname' => $userinfo['nickname']
            ),
            array(
                'uniacid' => $_W['uniacid'],
                'openid'  => $openid
            )
        );

        $member = $this->getMember($openid);

        if (empty($member)) {

            $mc = mc_fetch($uid, array('realname', 'nickname', 'mobile', 'avatar', 'resideprovince', 'residecity', 'residedist'));

            $member = array(
                'uniacid'    => $_W['uniacid'],
                'uid'        => $uid,
                'openid'     => $openid,
                'realname'   => $mc['realname'],
                'mobile'     => $mc['mobile'],
                'nickname'   => (!empty($mc['nickname']) ? $mc['nickname'] : $userinfo['nickname']),
                'avatar'     => (!empty($mc['avatar']) ? $mc['avatar'] : $userinfo['avatar']),
                'gender'     => (!empty($mc['gender']) ? $mc['gender'] : $userinfo['sex']),
                'province'   => (!empty($mc['resideprovince']) ? $mc['resideprovince'] : $userinfo['province']),
                'city'       => (!empty($mc['residecity']) ? $mc['residecity'] : $userinfo['city']),
                'area'       => $mc['residedist'],
                'createtime' => time(),
                'status'     => 0
            );

            pdo_insert('superdesk_shop_member', $member);// TODO 标志 楼宇之窗 openid shop_member 不处理

            $member['id'] = pdo_insertid();

            $member['isnew'] = true;

        } else {

            $member['nickname'] = $userinfo['nickname'];
            $member['avatar']   = $userinfo['headimgurl'];
            $member['province'] = $userinfo['province'];
            $member['city']     = $userinfo['city'];

            pdo_update('superdesk_shop_member', $member, array('id' => $member['id']));// TODO 标志 楼宇之窗 openid shop_member 不处理

            $member['isnew'] = false;
        }

        return $member;
    }

    public function checkMemberSNS($sns)
    {
        global $_W;
        global $_GPC;

        if (empty($sns)) {
            $sns = $_GPC['sns'];
        }

        if (empty($sns)) {
            return NULL;
        }

        if (($sns == 'wx') && !empty($_GPC['token'])) {

            $snsurl = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $_GPC['token'] . '&openid=' . $_GPC['openid'] . '&lang=zh_CN';

            $userinfo           = file_get_contents($snsurl);
            $userinfo           = json_decode($userinfo, true);
            $userinfo['openid'] = 'sns_wx_' . $userinfo['openid'];

        } else if ($sns == 'qq') {

            $userinfo               = htmlspecialchars_decode($_GPC['userinfo']);
            $userinfo               = json_decode($userinfo, true);
            $userinfo['openid']     = 'sns_qq_' . $_GPC['openid'];
            $userinfo['headimgurl'] = $userinfo['figureurl_qq_2'];
            $userinfo['gender']     = ($userinfo['gender'] == '男' ? 1 : 2);

        }

        $data = array(
            'openid'   => $userinfo['openid'],
            'nickname' => $userinfo['nickname'],
            'avatar'   => $userinfo['headimgurl'],
            'province' => $userinfo['province'],
            'city'     => $userinfo['city'],
            'gender'   => $userinfo['sex'],
            'uniacid'  => $_W['uniacid'],
            'comefrom' => 'h5app_sns_' . $sns
        );

        $openid = trim($_GPC['openid']);

        if ($sns == 'qq') {
            $data['openid_qq'] = trim($_GPC['openid']);
            $openid            = 'sns_qq_' . trim($_GPC['openid']);
        }


        if ($sns == 'wx') {
            $data['openid_wx'] = trim($_GPC['openid']);
            $openid            = 'sns_wx_' . trim($_GPC['openid']);
        }


        $member = $this->getMember($openid);

        if (empty($member)) {

            $data['comefrom']   = 'sns_' . $sns;
            $data['createtime'] = time();
            $data['salt']       = m('account')->getSalt();
            $data['pwd']        = rand(10000, 99999) . $data['salt'];

            pdo_insert('superdesk_shop_member', $data);// TODO 标志 楼宇之窗 openid shop_member 不处理

            return NULL;
        }


        if (empty($member['bindsns'])) {
            pdo_update(
                'superdesk_shop_member', // TODO 标志 楼宇之窗 openid shop_member 不处理
                $data,
                array(
                    'id' => $member['id']
                )
            );
        }

    }

    /**
     * 获取所有会员等级
     *
     * @param bool $all
     *
     * @return array
     */
    public function getLevels($all = true)
    {
        global $_W;

        $condition = '';

        if (!$all) {
            $condition = ' and enabled=1';
        }

        return pdo_fetchall(
            ' select * ' .
            ' from ' . tablename('superdesk_shop_member_level') .
            ' where ' .
            '       uniacid=:uniacid' .
            $condition .
            ' order by level asc',
            array(
                ':uniacid' => $_W['uniacid']
            )
        );
    }

    /**
     * @param $openid
     *
     * @return array|bool
     */
    public function getLevel($openid, $core_user = 0)
    {
        global $_W;
        global $_S;

        if (empty($openid)) {
            return false;
        }

        // TODO
        if (empty($core_user)) {
            return false;
        }

        $member = $this->getMember($openid, $core_user);

        if (!empty($member) && !empty($member['level'])) {

            $level = pdo_fetch(
                ' select * ' .
                ' from ' . tablename('superdesk_shop_member_level') .
                ' where id=:id ' .
                ' and uniacid=:uniacid ' .
                ' limit 1',
                array(
                    ':id'      => $member['level'],
                    ':uniacid' => $_W['uniacid']
                )
            );

            if (!empty($level)) {
                return $level;
            }

        }

        return array(
            'levelname' => (empty($_S['shop']['levelname']) ? '普通会员' : $_S['shop']['levelname']),
            'discount'  => (empty($_S['shop']['leveldiscount']) ? 10 : $_S['shop']['leveldiscount'])
        );
    }

    /**
     * 会员升级
     *
     * @param $openid
     *
     * @return null
     */
    public function upgradeLevel($openid, $core_user = 0)
    {
        global $_W;

        if (empty($openid)) {
            return NULL;
        }

        if (empty($core_user)) {
            return NULL;
        }

        $shopset = m('common')->getSysset('shop');

        $leveltype = intval($shopset['leveltype']);

        $shop_member = $this->getMember($openid, $core_user);

        if (empty($shop_member)) {
            return NULL;
        }


        $level = false;

        if (empty($leveltype)) {

            $ordermoney = pdo_fetchcolumn(
                ' select ifnull( sum(og.realprice),0) ' .
                ' from ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 已处理
                ' left join ' . tablename('superdesk_shop_order') . ' o on o.id=og.orderid ' .// TODO 标志 楼宇之窗 openid shop_order 已处理
                ' where ' .
                '       o.openid=:openid ' .
                '       and o.core_user=:core_user ' .
                '       and o.status=3 ' .
                '       and o.uniacid=:uniacid ',
                array(
                    ':uniacid'   => $_W['uniacid'],
                    ':openid'    => $shop_member['openid'],
                    ':core_user' => $shop_member['core_user'],
                )
            );

            $level = pdo_fetch(
                ' select * ' .
                ' from ' . tablename('superdesk_shop_member_level') .
                ' where ' .
                '       uniacid=:uniacid ' .
                '       and enabled=1 ' .
                '       and ' . $ordermoney . ' >= ordermoney ' .
                '       and ordermoney>0 ' .
                ' order by level desc ' .
                ' limit 1',
                array(':uniacid' => $_W['uniacid'])
            );

        } else if ($leveltype == 1) {

            $ordercount = pdo_fetchcolumn(
                ' select count(*) ' .
                ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
                ' where ' .
                '       openid=:openid ' .
                '       and core_user=:core_user ' .
                '       and status=3 ' .
                '       and uniacid=:uniacid ',
                array(
                    ':uniacid'   => $_W['uniacid'],
                    ':openid'    => $shop_member['openid'],
                    ':core_user' => $shop_member['core_user'],
                )
            );

            $level = pdo_fetch(
                ' select * ' .
                ' from ' . tablename('superdesk_shop_member_level') .
                ' where uniacid=:uniacid ' .
                '       and enabled=1 ' .
                '       and ' . $ordercount . ' >= ordercount ' .
                '       and ordercount>0 ' .
                ' order by level desc ' .
                ' limit 1',
                array(
                    ':uniacid' => $_W['uniacid']
                )
            );
        }


        if (empty($level)) {
            return NULL;
        }

        if ($level['id'] == $shop_member['level']) {
            return NULL;
        }

        $oldlevel = $this->getLevel($openid, $core_user);

        $canupgrade = false;

        if (empty($oldlevel['id'])) {

            $canupgrade = true;

        } else if ($oldlevel['level'] < $level['level']) {

            $canupgrade = true;

        }

        if ($canupgrade) {

            pdo_update(
                'superdesk_shop_member',// TODO 标志 楼宇之窗 openid shop_member 不处理
                array(
                    'level' => $level['id']
                ), array(
                'id' => $shop_member['id']
            ));

            m('notice')->sendMemberUpgradeMessage($openid, $oldlevel, $level);
        }

    }

    /**
     * 获取所有会员分组
     *
     * @global type $_W
     * @return type
     */
    public function getGroups()
    {
        global $_W;

        return pdo_fetchall(
            ' select * ' .
            ' from ' . tablename('superdesk_shop_member_group') .
            ' where ' .
            '       uniacid=:uniacid ' .
            ' order by id asc',
            array(
                ':uniacid' => $_W['uniacid']
            )
        );
    }

    /**
     * 获取所有会员角色
     * zjh 2018年4月23日 17:12:22
     *
     * @global type $_W
     * @return type
     */
    public function getCashroles()
    {
        global $_W;

        return pdo_fetchall(
            ' select * ' .
            ' from ' . tablename('superdesk_shop_member_cash_role') .
            ' where uniacid=:uniacid ' .
            ' order by id asc',
            array(
                ':uniacid' => $_W['uniacid']
            )
        );
    }

    public function getGroup($openid, $core_user = 0)
    {
        if (empty($openid)) {
            return false;
        }

        $member = $this->getMember($openid, $core_user);

        return $member['groupid'];
    }

    /**
     * 设置 -> 交易 -> 交易设置 -> 余额积分设置 -> 账户充值
     * 用户充值获得的积分,累计形式 比如 充值1元 送1积分,充值200，就送200积分
     *
     * @param string $openid
     * @param int    $core_user
     * @param int    $money
     *
     * @return null
     */
    public function setRechargeCredit($openid = '', $core_user = 0, $money = 0)
    {
        if (empty($openid)) {
            return NULL;
        }
        if (empty($core_user)) {
            return NULL;
        }

        global $_W;

        $credit = 0;

        $set = m('common')->getSysset(array('trade', 'shop'));

        if ($set['trade']) {

            $tmoney  = floatval($set['trade']['money']);
            $tcredit = intval($set['trade']['credit']);

            if ($tmoney <= $money) {
                if (($money % $tmoney) == 0) {
                    $credit = intval($money / $tmoney) * $tcredit;
                } else {
                    $credit = (intval($money / $tmoney) + 1) * $tcredit;
                }
            }
        }

        if (0 < $credit) {

            $this->setCredit(
                $openid,
                $core_user,
                'credit1',
                $credit,
                array(
                    0,
                    $set['shop']['name'] . '会员充值积分:credit2:' . $credit
                )
            );
        }

    }

    public function getCalculateMoney($money, $set_array)
    {
        $charge = $set_array['charge'];
        $begin  = $set_array['begin'];
        $end    = $set_array['end'];

        $array = array();

        $array['deductionmoney'] = round(($money * $charge) / 100, 2);

        if (($begin <= $array['deductionmoney']) && ($array['deductionmoney'] <= $end)) {
            $array['deductionmoney'] = 0;
        }

        $array['realmoney'] = round($money - $array['deductionmoney'], 2);

        if ($money == $array['realmoney']) {
            $array['flag'] = 0;
        } else {
            $array['flag'] = 1;
        }

        return $array;
    }


    /**
     * 这个方法很有问题 TODO
     *
     * @param $mid
     * @param $data
     *
     * @return null
     */
    public function mc_update($mid, $data)
    {
        global $_W;

        if (empty($mid) || empty($data)) {
            return NULL;
        }

        $wapset = m('common')->getSysset('wap');

        $member = $this->getMember($mid);

        if (!empty($wapset['open']) && isset($data['mobile']) && ($data['mobile'] != $member['mobile'])) {

            unset($data['mobile']);

        }

        load()->model('mc');

        mc_update($this->member['uid'], $data);
    }


    /**
     * @param array $level  数组中有两个参数 一个是等级,一个是新等级
     * @param array $levels 营销商等级数组
     *
     * @return bool 如果新等级大于 原等级 则返回true 否则 false
     */
    public function compareLevel(array $level, array $levels = array())
    {
        global $_W;

        $levels = ((!empty($levels) ? $levels : $this->getLevels()));

        $old_key = -1;
        $new_key = -1;

        foreach ($levels as $kk => $vv) {

            if ($vv['id'] == $level[0]) {
                $old_key = $vv['level'];
            }

            if ($vv['id'] == $level[1]) {
                $new_key = $vv['level'];
            }
        }

        return $old_key < $new_key;
    }

    /**
     * @param $mobile
     * @param $realname
     * @param $nickname
     * @param $creadit2
     * 创建shop_member表,mc_members表,mc_mapping_fans表
     */
    public function createMcMemberByWap($mobile, $realname, $nickname = '', $creadit2 = 0, $enterprise_id = 0)
    {
        global $_W;

        $pwd             = "123456";
        $salt            = m('account')->getSalt();
        $pwd             = md5($pwd . $salt);
        $openid          = 'wap_user_' . $_W['uniacid'] . '_' . $mobile;
        $nickname        = !empty($nickname) ? $nickname : substr($mobile, 0, 3) . 'xxxx' . substr($mobile, 7, 4);
        $default_groupid = cache_load("defaultgroupid:{$_W['uniacid']}");
        $time            = time();

        $mc_member = array(
            'uniacid'    => $_W['uniacid'],
            'email'      => md5($openid) . '@163.com',
            'salt'       => $salt,
            'password'   => $pwd,
            'groupid'    => $default_groupid,
            'createtime' => $time,
            'mobile'     => $mobile,
            'credit2'    => $creadit2,
            'realname'   => $realname,
            'nickname'   => $nickname
        );
        pdo_insert('mc_members', $mc_member);
        $mc_member_id = pdo_insertid();


        $fans = array(
            'uid'          => $mc_member_id,
            'acid'         => $_W['acid'],
            'uniacid'      => $_W['uniacid'],
            'openid'       => $openid,
            'salt'         => $salt,
            'follow'       => 0,
            'unfollowtime' => 0,
            'nickname'     => $nickname,
        );

        pdo_insert('mc_mapping_fans', $fans);
    }

    /**
     * 非微信创建用户
     */
    public function createMemberByNoWechat($mobile, $realname, $nickname = '', $enterprise_id = 0)
    {
        global $_W;

        $pwd      = "123456";
        $salt     = m('account')->getSalt();
        $pwd      = md5($pwd . $salt);
        $openid   = 'wap_user_' . $_W['uniacid'] . '_' . $mobile;
        $nickname = !empty($nickname) ? $nickname : substr($mobile, 0, 3) . 'xxxx' . substr($mobile, 7, 4);
        $time     = time();

        $shop_member = array(
            'uniacid'         => $_W['uniacid'],
            'realname'        => $realname,
            'nickname'        => $nickname,
            'mobile'          => $mobile,
            'mobileverify'    => 1,
            'pwd'             => $pwd,
            'salt'            => $salt,
            'openid'          => $openid,
            'core_enterprise' => $enterprise_id,
            'comefrom'        => 'mobile',
            'createtime'      => $time
        );
        pdo_insert('superdesk_shop_member', $shop_member);// TODO 标志 楼宇之窗 openid shop_member 不处理

        return $openid;
    }

    public function createMemberByBuildWindow($core_user, $selectField = ' * ')
    {
        global $_W;

        include_once(IA_ROOT . '/addons/superdesk_core/service/TbuserService.class.php');
        $_tbuserService = new TbuserService();

        $tb_user = $_tbuserService->getOneByCoreUser($core_user);

        if ($tb_user == false) {
            return false;
        }

        $openid = $_W['openid'];

        // 操作 mc_mapping_fans
        $followed = m('user')->followed($openid);

        $uid = 0;
        $mc  = array();

        load()->model('mc');

        if ($followed
            || empty($shopset['shop']['getinfo'])
            || ($shopset['shop']['getinfo'] == 1)
        ) {

            $uid = mc_openid2uid($openid);

            if (!SUPERDESK_SHOPV2_DEBUG) {

                $userinfo = mc_oauth_userinfo();

            } else {

                $userinfo = array(
                    'openid'     => $shop_member['openid'],
                    'nickname'   => $shop_member['nickname'],
                    'headimgurl' => $shop_member['avatar'],
                    'gender'     => $shop_member['gender'],
                    'province'   => $shop_member['province'],
                    'city'       => $shop_member['city']
                );
            }

            $mc = array();

            $mc['nickname']       = $userinfo['nickname'];
            $mc['avatar']         = $userinfo['headimgurl'];
            $mc['gender']         = $userinfo['sex'];
            $mc['resideprovince'] = $userinfo['province'];
            $mc['residecity']     = $userinfo['city'];
        }


        $pwd  = "123456";
        $salt = m('account')->getSalt();
        $pwd  = md5($pwd . $salt);

        if(empty($openid)){
            $openid = 'bmw_user_' . $_W['uniacid'] . '_' . $tb_user['userMobile'];
        }

        // 注册 shop_member
        $shop_member = array(
            'uniacid'           => $_W['uniacid'],
            'uid'               => $uid,
            'realname'          => $tb_user['userName'],
            'nickname'          => $tb_user['nickName'],
            'mobileverify'      => 1,
            'pwd'               => $pwd,
            'salt'              => $salt,
            'openid'            => $openid,
            'core_user'         => $tb_user['id'],
            'mobile'            => $tb_user['userMobile'],
            'core_enterprise'   => $tb_user['virtualArchId'],
            'core_organization' => $tb_user['organizationId'],
            'comefrom'          => 'buildWindow',
            'avatar'            => (!empty($mc['avatar']) ? $mc['avatar'] : ''),
            'gender'            => (!empty($mc['gender']) ? $mc['gender'] : '-1'),
            'province'          => (!empty($mc['resideprovince']) ? $mc['resideprovince'] : ''),
            'city'              => (!empty($mc['residecity']) ? $mc['residecity'] : ''),
            'area'              => (!empty($mc['residedist']) ? $mc['residedist'] : ''),
            'createtime'        => time(),
            'status'            => 0
        );

        pdo_insert('superdesk_shop_member', $shop_member);// TODO 标志 楼宇之窗 openid shop_member 已处理
        $shop_member['id'] = pdo_insertid();

        $shop_member = $this->getMemberByCoreUser($core_user, $selectField);

        return $shop_member;
    }

    public function updateMemberByBuildWindow($core_user)
    {
        global $_W;
        global $_GPC;

        $openid = $_W['openid'];

        if(empty($openid)){
            return;
        }

        if(empty($core_user)){
            return;
        }

        $shop_member = $this->getMemberByCoreUser($core_user);

        // 操作 mc_mapping_fans
        $followed = m('user')->followed($openid);

        $uid = 0;
        $mc  = array();

        load()->model('mc');
        $shopset = m('common')->getSysset(array('shop', 'wap'));

        if ($followed
            || empty($shopset['shop']['getinfo'])
            || ($shopset['shop']['getinfo'] == 1)
        ) {
            $uid = mc_openid2uid($openid);
            if (!SUPERDESK_SHOPV2_DEBUG) {
                $userinfo = mc_oauth_userinfo();
            } else {

                $userinfo = array(
                    'openid'     => $shop_member['openid'],
                    'nickname'   => $shop_member['nickname'],
                    'headimgurl' => $shop_member['avatar'],
                    'gender'     => $shop_member['gender'],
                    'province'   => $shop_member['province'],
                    'city'       => $shop_member['city']
                );
            }

            $mc = array();

            $mc['nickname']       = $userinfo['nickname'];
            $mc['avatar']         = $userinfo['headimgurl'];
            $mc['gender']         = $userinfo['sex'];
            $mc['resideprovince'] = $userinfo['province'];
            $mc['residecity']     = $userinfo['city'];
        }

        // 黑名单
//        if ($shop_member['isblack'] == 1) {
//            show_message('暂时无法访问，请稍后再试!');
//        }

        $upgrade = array();

        $upgrade['uid']    = $uid;
        $upgrade['openid'] = $openid;

        if (isset($mc['nickname']) && ($shop_member['nickname'] != $mc['nickname'])) {
            $upgrade['nickname'] = $mc['nickname'];
        }

        if (isset($mc['avatar'])
            && ($shop_member['avatar'] != $mc['avatar'])
            && (strpos($shop_member['avatar'], 'attachment') === false || strpos($shop_member['avatar'], 'attachment') === false)) {
            $upgrade['avatar'] = $mc['avatar'];
        }

        if (isset($mc['gender']) && ($shop_member['gender'] != $mc['gender'])) {
            $upgrade['gender'] = $mc['gender'];
        }

        if (!empty($upgrade) && !empty($core_user)) {   //zjh 2019年2月26日 19:55:02 判断core_user是否不为空

            pdo_update( // 不根据id更新
                'superdesk_shop_member', // TODO 标志 楼宇之窗 openid shop_member 已处理
                $upgrade,
                array(
                    'core_user' => $core_user
                )
            );
        }

        //2019年7月15日 18:26:55 zjh 可能是第二次进入后微信openid覆盖了伪造的openid.然后会导致数据无法查找的问题.. 尝试这样能否修复
        if($shop_member['openid'] != $openid){
            $new_member = $this->getMemberByCoreUser($core_user);

            m('account')->setLogin($new_member);

            //openid变更后修改其它表的openid
            if(!empty($shop_member['openid'])){
                m('account')->updateOpenidAllTable($core_user, $shop_member['openid'], $openid);
            }

            //日志记录
            LogsUtil::logging('info', '更新openid...core_user:' . $core_user . ',old_openid: ' . $shop_member['openid'] . ',new_openid: ' . $openid, 'openid_change');
        }

    }

    /**
     * @福利商城
     * 根据手机号与企业id拿openid
     * 这个方法 是用于 企业端 导入时 建不建帐号的问题
     *
     * @param $mobile
     * @param $enterprise_id
     *
     * @return bool
     */
    public function getOpenidByMobileAndEnterprise($mobile, $enterprise_id)
    {

        $openid = pdo_fetchcolumn(
            ' select openid ' .
            ' from ' . tablename('superdesk_shop_member') .// TODO 标志 楼宇之窗 openid shop_member 待处理 这个是企业端上的逻辑
            ' where ' .
            '       mobile=:mobile ' .
            '       and core_enterprise=:enterprise_id',
            array(
                ':mobile'        => $mobile,
                ':enterprise_id' => $enterprise_id
            )
        );

        //假如没找到.尝试不用企业id,只用手机号查找
        if (empty($openid)) {
            $openid = pdo_fetchcolumn(
                ' select openid ' .
                ' from ' . tablename('superdesk_shop_member') .// TODO 标志 楼宇之窗 openid shop_member 待处理 这个是企业端上的逻辑
                ' where ' .
                '       mobile=:mobile',
                array(
                    ':mobile' => $mobile
                )
            );

            //假如找到了.那就是另一个企业的.强行把他的企业改成当前企业
            if (!empty($openid)) {
                pdo_update( // 不根据id更新
                    'superdesk_shop_member', // TODO 标志 楼宇之窗 openid shop_member 待处理
                    array(
                        'core_enterprise' => $enterprise_id
                    ),
                    array(
                        'openid' => $openid
                    )
                );
            }
        }

        return $openid;
    }

    /**
     * @福利商城
     * 流水记录
     *
     * @param     $openid
     * @param     $core_user
     * @param     $type
     * @param     $times
     * @param     $price
     * @param     $old_price
     * @param int $orderid
     */
    private function insertCreditLog($openid, $core_user, $type, $times, $price, $old_price, $orderid = 0)
    {
        global $_W;

        $new_price = pdo_fetchcolumn(
            ' SELECT credit2 ' .
            ' FROM ' . tablename('superdesk_shop_member') .// TODO 标志 楼宇之窗 openid shop_member 待处理
            ' WHERE ' .
            '       uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            ' limit 1',
            array(
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $openid,
                ':core_user' => $core_user,
            )
        );

        $credit2Log = array(
            'openid'      => $openid,
            'core_user'   => $core_user,
            'type'        => $type,
            'createtime'  => $times, // 类型(1.是员工导入,2.后端充值,3.订单,4.退款)
            'finish_time' => $times,
            'old_price'   => $old_price,
            'new_price'   => $new_price,
            'orderid'     => !empty($orderid) ? $orderid : 0
        );

//        socket_log('m(member)->insertCreditLog');
//        socket_log($credit2Log);

        if ($price > 0) {
            $credit2Log['add_price']    = $price;
            $credit2Log['reduce_price'] = 0;
        } else {
            $credit2Log['add_price']    = 0;
            $credit2Log['reduce_price'] = $price;
        }

        pdo_insert('superdesk_shop_member_credit_log', $credit2Log);
    }

    /**
     * 根据手机号拿用户信息
     *
     * @param $mobile
     * @param $core_enterprise
     *
     * @return bool
     */
    public function getMemberByMobile($mobile)
    {

        $member = pdo_fetch(
            ' select * ' .
            ' from ' . tablename('superdesk_shop_member') .// TODO 标志 楼宇之窗 openid shop_member 不处理
            ' where ' .
            '       mobile=:mobile',
            array(
                ':mobile' => $mobile
            )
        );

        return $member;
    }
}