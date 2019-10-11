<?php

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/member/member.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/member/member_cash_role.class.php');

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order.class.php');

include_once(IA_ROOT . '/framework/library/logs/LogsUtil.class.php');

/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 2/2/18
 * Time: 2:25 PM
 */
class MemberService
{
    private $_memberModel;
    private $_member_cash_roleModel;
    private $_orderModel;

    public function __construct()
    {
        $this->_memberModel           = new memberModel();
        $this->_member_cash_roleModel = new member_cash_roleModel();

        $this->_orderModel = new orderModel();
    }

    /**
     * @param $_shop_member_id
     * @param $virtualArchId
     *
     * @return bool
     */
    public function bindShopMemberCoreEnterprise($_shop_member_id, $virtualArchId)
    {
        global $_W;
        global $_GPC;

        $params = array(
//            'realname'        => $_tb_user['userName'],
//            'mobile'          => $_tb_user['userMobile'],
            'core_enterprise' => $virtualArchId,
//            'mobileverify'    => 1
        );


        $this->_memberModel->update($params, $_shop_member_id);
        $_shop_member = $this->_memberModel->getOne($_shop_member_id);
        return $_shop_member;

    }

    public function updateRealname($shop_member_id, $tb_usr_userName)
    {

        global $_W;
        global $_GPC;

        $this->_memberModel->updateByColumn(array(
            'realname' => $tb_usr_userName
        ), array(
            'id' => $shop_member_id
        ));
    }

    /**
     * @param $shop_member_id
     */
    public function updateLoginTime($shop_member_id)
    {

        global $_W;
        global $_GPC;

        $this->_memberModel->updateByColumn(array(
            'logintime' => time()
        ), array(
            'id' => $shop_member_id
        ));
    }

    public function isBindMember($mobile, $openid)
    {

        global $_W;
        global $_GPC;

        $where  = array(
            'mobile' => $mobile,
            'openid' => $openid
        );
        $isBind = $this->_memberModel->getOneByColumn($where);

        return $isBind;

    }

    public function getOneByMobile($mobile)
    {
        global $_W;
        global $_GPC;

        return $this->_memberModel->getOneByMobile($mobile);

    }

    public function getOneByOpenid($openid)
    {
        global $_W;
        global $_GPC;

        return $this->_memberModel->getOneByOpenid($openid);

    }

    public function getSalt()
    {
        return $this->_memberModel->getSalt();
    }


    public function syncTbUser($_shop_member_id, $_tb_user)
    {
        global $_W;
        global $_GPC;

//        socket_log("checkEnterpriseUserLogin:" . json_encode($_tb_user, JSON_UNESCAPED_UNICODE));

        // 将tb_user 的信息更新到 shop_member
        // realname <= userName
        // mobile   <= userMobile
        // salt     <=
        // pwd      <= 123456
        // comeform <= mobile
        // core_enterprise <= virtualArchId


        $params = array(
            'realname'        => $_tb_user['userName'],
            'mobile'          => $_tb_user['userMobile'],
            'core_enterprise' => $_tb_user['virtualArchId'],
            'mobileverify'    => 1
        );


        $this->_memberModel->update($params, $_shop_member_id);

        $_shop_member = $this->_memberModel->getOne($_shop_member_id);

        if (empty($_shop_member['pwd'])) {

            $pwd  = '123456';
            $salt = $this->getSalt();

            $params = array(
                'pwd'  => md5($pwd . $salt),
                'salt' => $salt
            );

            $this->_memberModel->update($params, $_shop_member_id);
        }


    }

    public function iswxm($member = array())
    {
        if (empty($member) || !is_array($member)) {
            return true;
        }

        if (strexists($member['openid'], 'sns_wx_')
            || strexists($member['openid'], 'sns_qq_')
            || strexists($member['openid'], 'wap_user_')
        ) {
            return false;
        }

        return true;
    }


    private function msg($code, $msg)
    {


        $data = array(
            'code' => $code,
            'msg'  => $msg
        );

//        socket_log(json_encode($data,JSON_UNESCAPED_UNICODE));

        return $data;

    }

    /**
     * $a 合并到 $b , 删除 $a
     *
     * @param array $a
     * @param array $b
     *
     * @return array
     */
    public function merge($a = array(), $b = array())
    {
        global $_W;

        if (empty($a)
            || empty($b)
            || ($a['id'] == $b['id'])
        ) {
            return $this->msg(0, 'params error');

        }


        $createtime = (($b['createtime'] < $a['createtime'] ? $b['createtime'] : $a['createtime']));

        $childtime = (($b['childtime'] < $a['childtime'] ? $b['childtime'] : $a['childtime']));

        $comparelevel = m('member')->compareLevel(array($a['level'], $b['level']));

        $level = (($comparelevel ? $b['level'] : $a['level']));

        $isblack = ((!empty($a['isblack']) || !empty($b['isblack']) ? 1 : 0));

        $openid_qq = ((!empty($b['openid_qq']) && empty($a['openid_qq']) ? $b['openid_qq'] : $a['openid_qq']));

        $openid_wx = ((!empty($b['openid_wx']) && empty($a['openid_wx']) ? $b['openid_wx'] : $a['openid_wx']));

        if (!empty($a['isagent']) && empty($b['isagent'])) {

            $isagent    = 1;
            $agentid    = $a['agentid'];
            $status     = ((!empty($a['status']) ? 1 : 0));
            $agenttime  = $a['agenttime'];
            $agentlevel = $a['agentlevel'];
            $agentblack = $a['agentblack'];
            $fixagentid = $a['fixagentid'];

        } else if (!empty($b['isagent']) && empty($a['isagent'])) {

            $isagent    = 1;
            $agentid    = $b['agentid'];
            $status     = ((!empty($b['status']) ? 1 : 0));
            $agenttime  = $b['agenttime'];
            $agentlevel = $b['agentlevel'];
            $agentblack = $b['agentblack'];
            $fixagentid = $b['fixagentid'];

        } else if (!empty($b['isagent']) && !empty($a['isagent'])) {

            $compare = p('commission')->compareLevel(array($a['agentlevel'], $b['agentlevel']));
            $isagent = 1;

            if ($compare) {
                $agentid    = $b['agentid'];
                $status     = ((!empty($b['status']) ? 1 : 0));
                $agentblack = ((!empty($b['agentblack']) ? 1 : 0));
                $fixagentid = ((!empty($b['fixagentid']) ? 1 : 0));
            } else {
                $agentid    = $a['agentid'];
                $status     = ((!empty($a['status']) ? 1 : 0));
                $agentblack = ((!empty($a['agentblack']) ? 1 : 0));
                $fixagentid = ((!empty($a['fixagentid']) ? 1 : 0));
            }

            $agenttime  = (($compare ? $b['agenttime'] : $a['agenttime']));
            $agentlevel = (($compare ? $b['agentlevel'] : $a['agentlevel']));

        }


        if (!empty($a['isauthor']) && empty($b['isauthor'])) {

            $isauthor     = $a['isauthor'];
            $authorstatus = ((!empty($a['authorstatus']) ? 1 : 0));
            $authortime   = $a['authortime'];
            $authorlevel  = $a['authorlevel'];
            $authorblack  = $a['authorblack'];

        } else if (!empty($b['isauthor']) && empty($a['isauthor'])) {

            $isauthor     = $b['isauthor'];
            $authorstatus = ((!empty($b['authorstatus']) ? 1 : 0));
            $authortime   = $b['authortime'];
            $authorlevel  = $b['authorlevel'];
            $authorblack  = $b['authorblack'];

        } else if (!empty($b['isauthor']) && !empty($a['isauthor'])) {

            return $this->msg(0, '此手机号已绑定另一用户(a1)<br>请联系管理员');

        }

        if (!empty($a['ispartner']) && empty($b['ispartner'])) {

            $ispartner     = 1;
            $partnerstatus = ((!empty($a['partnerstatus']) ? 1 : 0));
            $partnertime   = $a['partnertime'];
            $partnerlevel  = $a['partnerlevel'];
            $partnerblack  = $a['partnerblack'];

        } else if (!empty($b['ispartner']) && empty($a['ispartner'])) {

            $ispartner     = 1;
            $partnerstatus = ((!empty($b['partnerstatus']) ? 1 : 0));
            $partnertime   = $b['partnertime'];
            $partnerlevel  = $b['partnerlevel'];
            $partnerblack  = $b['partnerblack'];

        } else if (!empty($b['ispartner']) && !empty($a['ispartner'])) {

            return $this->msg(0, '此手机号已绑定另一用户(p)<br>请联系管理员');
        }


        if (!empty($a['isaagent']) && empty($b['isaagent'])) {

            $isaagent        = $a['isaagent'];
            $aagentstatus    = ((!empty($a['aagentstatus']) ? 1 : 0));
            $aagenttime      = $a['aagenttime'];
            $aagentlevel     = $a['aagentlevel'];
            $aagenttype      = $a['aagenttype'];
            $aagentprovinces = $a['aagentprovinces'];
            $aagentcitys     = $a['aagentcitys'];
            $aagentareas     = $a['aagentareas'];

        } else if (!empty($b['isaagent']) && empty($a['isaagent'])) {

            $isaagent        = $b['isaagent'];
            $aagentstatus    = ((!empty($b['aagentstatus']) ? 1 : 0));
            $aagenttime      = $b['aagenttime'];
            $aagentlevel     = $b['aagentlevel'];
            $aagenttype      = $b['aagenttype'];
            $aagentprovinces = $b['aagentprovinces'];
            $aagentcitys     = $b['aagentcitys'];
            $aagentareas     = $b['aagentareas'];

        } else if (!empty($b['isaagent']) && !empty($a['isaagent'])) {

            return $this->msg(0, '此手机号已绑定另一用户(a2)<br>请联系管理员');
        }


        $arr = array();

        if (isset($createtime)) {
            $arr['createtime'] = $createtime;
        }


        if (isset($childtime)) {
            $arr['childtime'] = $childtime;
        }


        if (isset($level)) {
            $arr['level'] = $level;
        }


        if (isset($groupid)) {
            $arr['groupid'] = $groupid;
        }


        if (isset($isblack)) {
            $arr['isblack'] = $isblack;
        }


        if (isset($openid_qq)) {
            $arr['openid_qq'] = $openid_qq;
        }


        if (isset($openid_wx)) {
            $arr['openid_wx'] = $openid_wx;
        }


        if (isset($status)) {
            $arr['status'] = $status;
        }


        if (isset($isagent)) {
            $arr['isagent'] = $isagent;
        }


        if (isset($agentid)) {
            $arr['agentid'] = $agentid;
        }


        if (isset($agenttime)) {
            $arr['agenttime'] = $agenttime;
        }


        if (isset($agentlevel)) {
            $arr['agentlevel'] = $agentlevel;
        }


        if (isset($agentblack)) {
            $arr['agentblack'] = $agentblack;
        }


        if (isset($fixagentid)) {
            $arr['fixagentid'] = $fixagentid;
        }


        if (isset($isauthor)) {
            $arr['isauthor'] = $isauthor;
        }


        if (isset($authorstatus)) {
            $arr['authorstatus'] = $authorstatus;
        }


        if (isset($authortime)) {
            $arr['authortime'] = $authortime;
        }


        if (isset($authorlevel)) {
            $arr['authorlevel'] = $authorlevel;
        }


        if (isset($authorblack)) {
            $arr['authorblack'] = $authorblack;
        }


        if (isset($ispartner)) {
            $arr['ispartner'] = $ispartner;
        }


        if (isset($partnerstatus)) {
            $arr['partnerstatus'] = $partnerstatus;
        }


        if (isset($partnertime)) {
            $arr['partnertime'] = $partnertime;
        }


        if (isset($partnerlevel)) {
            $arr['partnerlevel'] = $partnerlevel;
        }


        if (isset($partnerblack)) {
            $arr['partnerblack'] = $partnerblack;
        }


        if (isset($isaagent)) {
            $arr['isaagent'] = $isaagent;
        }


        if (isset($aagentstatus)) {
            $arr['aagentstatus'] = $aagentstatus;
        }


        if (isset($aagenttime)) {
            $arr['aagenttime'] = $aagenttime;
        }


        if (isset($aagentlevel)) {
            $arr['aagentlevel'] = $aagentlevel;
        }


        if (isset($aagenttype)) {
            $arr['aagenttype'] = $aagenttype;
        }


        if (isset($aagentprovinces)) {
            $arr['aagentprovinces'] = $aagentprovinces;
        }


        if (isset($aagentcitys)) {
            $arr['aagentcitys'] = $aagentcitys;
        }


        if (isset($aagentareas)) {
            $arr['aagentareas'] = $aagentareas;
        }

        // ADD core_enterprise
        $arr['core_enterprise'] = $a['core_enterprise'];
        // ADD cash_role_id
        $arr['cash_role_id'] = $a['cash_role_id'];


        if (!empty($arr) && is_array($arr)) {
            pdo_update(
                'superdesk_shop_member', // TODO 标志 楼宇之窗 openid shop_member 不处理
                $arr,
                array(
                    'id' => $b['id']
                )
            );
        }


        pdo_update(
            'superdesk_shop_commission_apply',
            array(
                'mid' => $b['id']
            ),
            array(
                'uniacid' => $_W['uniacid'],
                'mid'     => $a['id']
            )
        );

        //mark kafka 为了kafka转成了model执行
        $this->_orderModel->updateByColumn(
            array(
                'agentid' => $b['id']
            ),
            array(
                'agentid' => $a['id']
            )
        );

        pdo_update(
            'superdesk_shop_member', // TODO 标志 楼宇之窗 openid shop_member 不处理
            array(
                'agentid' => $b['id']
            ),
            array(
                'agentid' => $a['id']
            )
        );

        if (0 < $a['credit1']) {
            m('member')->setCredit($b['openid'], $b['core_user'],
                'credit1', abs($a['credit1']), '全网通会员数据合并增加积分 +' . $a['credit1']);
        }


        if (0 < $a['credit2']) {
            m('member')->setCredit($b['openid'], $b['core_user'],
                'credit2', abs($a['credit2']), '全网通会员数据合并增加余额 +' . $a['credit2']);
        }

        pdo_delete(
            'superdesk_shop_member', // TODO 标志 楼宇之窗 openid shop_member 不处理
            array(
                'id'      => $a['id'],
                'uniacid' => $_W['uniacid']
            )
        );

        $tables = pdo_fetchall('SHOW TABLES like \'%_superdesk_shop_%\'');

        foreach ($tables as $k => $v) {

            $v = array_values($v);

            $tablename = str_replace($_W['config']['db']['tablepre'], '', $v[0]);

            if (pdo_fieldexists($tablename, 'openid') && pdo_fieldexists($tablename, 'uniacid')) {
                pdo_update($tablename, array(
                    'openid' => $b['openid']),
                    array(
                        'uniacid' => $_W['uniacid'],
                        'openid'  => $a['openid']
                    )
                );
            }

            if (pdo_fieldexists($tablename, 'openid') && pdo_fieldexists($tablename, 'acid')) {
                pdo_update($tablename, array(
                    'openid' => $b['openid']),
                    array(
                        'acid'   => $_W['acid'],
                        'openid' => $a['openid']
                    )
                );
            }

            if (pdo_fieldexists($tablename, 'mid') && pdo_fieldexists($tablename, 'uniacid')) {
                pdo_update($tablename, array(
                    'mid' => $b['id']), array(
                        'uniacid' => $_W['uniacid'],
                        'mid'     => $a['id']
                    )
                );
            }

        }

        return $this->msg(1, '合并成功');
    }

    public function mergeOpenidByCoreUser($core_user,$old_openid,$new_openid){
        global $_W;

        LogsUtil::logging('info', 'update all table openid...START', 'openid_change');



        try{

            $tables = pdo_fetchall('SHOW TABLES like \'%_superdesk_shop_%\'');

            foreach ($tables as $k => $v) {

                $v = array_values($v);

                $tablename = str_replace($_W['config']['db']['tablepre'], '', $v[0]);

                if (pdo_fieldexists($tablename, 'openid') && pdo_fieldexists($tablename, 'core_user') && pdo_fieldexists($tablename, 'uniacid')) {
                    pdo_update(
                        $tablename,
                        array(
                            'openid' => $new_openid
                        ),
                        array(
                            'uniacid' => $_W['uniacid'],
                            'core_user' => $core_user,
                            'openid'  => $old_openid
                        )
                    );
                }

            }

        } catch (\Exception $e){

            //日志记录
            LogsUtil::logging('error', 'update all table openid error core_user:' . $core_user . ',old_openid: ' . $old_openid . ',new_openid: ' . $new_openid, 'openid_change');

        }



        LogsUtil::logging('info', 'update all table openid...END', 'openid_change');

    }

    /**
     * 根据 企业ID 查出 采购经理
     *
     * @param $core_enterprise
     *
     * @return array
     */
    public function getMemberListByCashRule($core_enterprise)
    {
        global $_W;

        $role = $this->_member_cash_roleModel->getOneByColumn(
            array(
                'rolename' => '采购经理'
            )
        );

        $roleid = $role['id'];

        $manager_arr = pdo_fetchall(
            ' select openid,core_user,core_enterprise,core_organization,mobile ' .
            ' from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid shop_member 不处理
            ' where 1 ' .
            '       and core_enterprise=:core_enterprise ' .
            '       and uniacid=:uniacid ' .
            '       and cash_role_id=:roleid ',
            array(
                ':uniacid'         => $_W['uniacid'],
                ':core_enterprise' => $core_enterprise,
                ':roleid'          => $roleid
            )
        );

        return $manager_arr;
    }

}