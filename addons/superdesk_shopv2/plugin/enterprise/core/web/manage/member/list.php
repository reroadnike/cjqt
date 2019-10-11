<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

require SUPERDESK_SHOPV2_PLUGIN . 'enterprise/core/inc/page_enterprise.php';

class List_SuperdeskShopV2Page extends EnterpriseWebPage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;

        $condition = ' and dm.uniacid=:uniacid and core_enterprise=:core_enterprise';
        $params    = array(':uniacid' => $_W['uniacid'], ':core_enterprise' => $_W['enterprise_id']);

        if (!empty($_GPC['mid'])) {
            $condition .= ' and dm.id=:mid';
            $params[':mid'] = intval($_GPC['mid']);
        }

        // 可搜索昵称/姓名/手机号/ID
        if (!empty($_GPC['realname'])) {

            $_GPC['realname'] = trim($_GPC['realname']);

            $condition .= ' and ( dm.realname like :realname or dm.nickname like :realname or dm.mobile like :realname or dm.id like :realname)';
            $params[':realname'] = '%' . $_GPC['realname'] . '%';
        }

        if (empty($starttime) || empty($endtime)) {

            $starttime = strtotime('-1 month');
            $endtime   = time();
        }

        // 注册时间
        if (!empty($_GPC['time']['start']) && !empty($_GPC['time']['end'])) {

            $starttime = strtotime($_GPC['time']['start']);
            $endtime   = strtotime($_GPC['time']['end']);

            $condition .= ' AND dm.createtime >= :starttime AND dm.createtime <= :endtime ';

            $params[':starttime'] = $starttime;
            $params[':endtime']   = $endtime;
        }

        // 会员等级
        if ($_GPC['level'] != '') {
            $condition .= ' and level=' . intval($_GPC['level']);
        }

        // 会员分组
        if ($_GPC['groupid'] != '') {
            $condition .= ' and dm.groupid=' . intval($_GPC['groupid']);
        }

        // 会员角色 zjh 2018年4月23日 17:13:05
        if ($_GPC['cash_role_id'] != '') {
            $condition .= ' and cash_role_id=' . intval($_GPC['cash_role_id']);
        }

        $join = '';

        if ($_GPC['followed'] != '') {

            if ($_GPC['followed'] == 2) {
                $condition .= ' and f.follow=0 and dm.uid<>0';
            } else {
                $condition .= ' and f.follow=' . intval($_GPC['followed']);
            }

            $join .= ' left join ' . tablename('mc_mapping_fans') . ' f on f.openid=dm.openid';
        }

        if ($_GPC['isblack'] != '') {
            $condition .= ' and dm.isblack=' . intval($_GPC['isblack']);
        }

        $sql =
            ' select * ' .
            ' from ' . tablename('superdesk_shop_member') . ' dm ' . $join .
            ' where 1 ' . $condition .
//            ' ORDER BY id DESC';
            ' ORDER BY logintime DESC , id DESC';

        if (empty($_GPC['export'])) {
            $sql .= ' limit ' . (($pindex - 1) * $psize) . ',' . $psize;
        }

        $list = pdo_fetchall($sql, $params);

        $list_group = array();
        $list_level = array();
        $list_agent = array();
        $list_fans  = array();

        foreach ($list as $val) {
            $list_group[] = trim($val['groupid'], ',');
            $list_level[] = trim($val['level'], ',');
            $list_agent[] = trim($val['agentid'], ',');
            $list_fans[]  = trim($val['openid'], ',');
        }

        $memberids = array_keys($list);

        isset($list_group) && ($list_group = array_values(array_filter($list_group)));

        if (!empty($list_group)) {
            $res_group = pdo_fetchall(
                ' select id,groupname ' .
                ' from ' . tablename('superdesk_shop_member_group') .
                ' where id in (' . implode(',', $list_group) . ')', array(), 'id');
        }

        isset($list_level) && ($list_level = array_values(array_filter($list_level)));

        if (!empty($list_level)) {
            $res_level = pdo_fetchall(
                ' select id,levelname ' .
                ' from ' . tablename('superdesk_shop_member_level') .
                ' where id in (' . implode(',', $list_level) . ')', array(), 'id');
        }

        isset($list_agent) && ($list_agent = array_values(array_filter($list_agent)));

        if (!empty($list_agent)) {
            $res_agent = pdo_fetchall(
                ' select id,nickname as agentnickname,avatar as agentavatar ' .
                ' from ' . tablename('superdesk_shop_member') .
                ' where id in (' . implode(',', $list_agent) . ')', array(), 'id');
        }
        isset($list_fans) && ($list_fans = array_values(array_filter($list_fans)));

        if (!empty($list_fans)) {
            $res_fans = pdo_fetchall(
                ' select fanid,openid,follow as followed ' .
                ' from ' . tablename('mc_mapping_fans') .
                ' where openid in (\'' . implode('\',\'', $list_fans) . '\')', array(), 'openid');
        }

        $shop = $_W['shopset']['shop'];

        foreach ($list as &$row) {
            $row['groupname']     = ((isset($res_group[$row['groupid']]) ? $res_group[$row['groupid']]['groupname'] : ''));
            $row['levelname']     = ((isset($res_level[$row['level']]) ? $res_level[$row['level']]['levelname'] : ''));
            $row['agentnickname'] = ((isset($res_agent[$row['agentid']]) ? $res_agent[$row['agentid']]['agentnickname'] : ''));
            $row['agentavatar']   = ((isset($res_agent[$row['agentid']]) ? $res_agent[$row['agentid']]['agentavatar'] : ''));
            $row['followed']      = ((isset($res_fans[$row['openid']]) ? $res_fans[$row['openid']]['followed'] : ''));
            $row['fanid']         = ((isset($res_fans[$row['openid']]) ? $res_fans[$row['openid']]['fanid'] : ''));
            $row['levelname']     = ((empty($row['levelname']) ? ((empty($shop['levelname']) ? '普通会员' : $shop['levelname'])) : $row['levelname']));
            $row['ordercount']    = pdo_fetchcolumn(
                'select count(*) from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 已处理
                ' where uniacid=:uniacid and openid=:openid and core_user=:core_user and status=3',
                array(
                    ':uniacid'   => $_W['uniacid'],
                    ':openid'    => $row['openid'],
                    ':core_user' => $row['core_user']
                )
            );

            $row['ordermoney']    = pdo_fetchcolumn(
                'select sum(goodsprice) from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
                ' where uniacid=:uniacid and openid=:openid and core_user=:core_user and status=3',
                array(
                    ':uniacid'   => $_W['uniacid'],
                    ':openid'    => $row['openid'],
                    ':core_user' => $row['core_user']
                )
            );

            $row['credit1']       = m('member')->getCredit($row['openid'], 'credit1');
            $row['credit2']       = m('member')->getCredit($row['openid'], 'credit2');
        }

        unset($row);

        if ($_GPC['export'] == '1') {
            mplog('member.list', '导出会员数据');
            foreach ($list as &$row) {
                $row['createtime'] = date('Y-m-d H:i', $row['createtime']);
                $row['groupname']  = ((empty($row['groupname']) ? '无分组' : $row['groupname']));
                $row['levelname']  = ((empty($row['levelname']) ? '普通会员' : $row['levelname']));
            }
            unset($row);
            m('excel')->export($list, array(
                'title'   => '会员数据-' . date('Y-m-d-H-i', time()),
                'columns' => array(
                    array('title' => '昵称', 'field' => 'nickname', 'width' => 12),
                    array('title' => '姓名', 'field' => 'realname', 'width' => 12),
                    array('title' => '手机号', 'field' => 'mobile', 'width' => 12),
                    array('title' => 'openid', 'field' => 'openid', 'width' => 24),
                    array('title' => '会员等级', 'field' => 'levelname', 'width' => 12),
                    array('title' => '会员分组', 'field' => 'groupname', 'width' => 12),
                    array('title' => '注册时间', 'field' => 'createtime', 'width' => 12),
                    array('title' => '积分', 'field' => 'credit1', 'width' => 12),
                    array('title' => '余额', 'field' => 'credit2', 'width' => 12),
                    array('title' => '成交订单数', 'field' => 'ordercount', 'width' => 12),
                    array('title' => '成交总金额', 'field' => 'ordermoney', 'width' => 12)
                )
            ));
        }

        $total          = pdo_fetchcolumn(
            ' select count(*) ' .
            ' from' . tablename('superdesk_shop_member') . ' dm ' . $join .
            ' where 1 ' . $condition . ' ', $params);
        $pager          = pagination($total, $pindex, $psize);
        $opencommission = false;

        $plug_commission = p('commission');
        if ($plug_commission) {

            $comset = $plug_commission->getSet();

            if (!empty($comset)) {
                $opencommission = true;
            }
        }

        $groups = m('member')->getGroups();
        $levels = m('member')->getLevels();
        include $this->template();
    }

    public function detail()
    {
        global $_W;
        global $_GPC;

        $shop          = $_W['shopset']['shop'];
        $hascommission = false;

        $plugin_com = p('commission');
        if ($plugin_com) {
            $plugin_com_set = $plugin_com->getSet();
            $hascommission  = !empty($plugin_com_set['level']);
        }

        $id = intval($_GPC['id']);
        if ($hascommission) {
            $agentlevels = $plugin_com->getLevels();
        }

        $member = m('member')->getMember($id);

        if ($hascommission) {
            $member = $plugin_com->getInfo($id, array('total', 'pay'));
        }

        $diyform_flag            = 0;
        $diyform_flag_commission = 0;
        $diyform_flag_globonus   = 0;
        $diyform_flag_abonus     = 0;
        $diyform_plugin          = p('diyform');

        if ($diyform_plugin) {

            if (!empty($member['diymemberdata'])) {
                $diyform_flag = 1;
                $fields       = iunserializer($member['diymemberfields']);
            }

            if (!empty($member['diycommissiondata'])) {
                $diyform_flag_commission = 1;
                $cfields                 = iunserializer($member['diycommissionfields']);
            }

            if (!empty($member['diyglobonusdata'])) {
                $diyform_flag_globonus = 1;
                $gfields               = iunserializer($member['diyglobonusfields']);
            }

            if (!empty($member['diyaagentdata'])) {
                $diyform_flag_abonus = 1;
                $aafields            = iunserializer($member['diyaagentfields']);
            }
        }
        $groups    = m('member')->getGroups();
        $cashroles = m('member')->getCashroles(); //会员角色 zjh 2018年4月23日 17:13:05
        $levels    = m('member')->getLevels();

        socket_log(json_encode($cashroles,JSON_UNESCAPED_UNICODE));

        if ($_W['ispost']) {

            $data = ((is_array($_GPC['data']) ? $_GPC['data'] : array()));

            if (!empty($_W['shopset']['wap']['open'])) {

                if (!empty($data['mobileverify'])) {

                    if (empty($data['mobile'])) {
                        show_json(0, '绑定手机号请先填写用户手机号!');
                    }

                    $m = pdo_fetch(
                        ' select id ' .
                        ' from ' . tablename('superdesk_shop_member') .
                        ' where mobile=:mobile ' .
                        '       and mobileverify=1 ' .
                        '       and uniacid=:uniaicd ' .
                        ' limit 1 ',
                        array(
                            ':mobile'  => $data['mobile'],
                            ':uniaicd' => $_W['uniacid']
                        )
                    );

                    if (!empty($m) && ($m['id'] != $id)) {
                        show_json(0, '此手机号已绑定其他用户!(uid:' . $m['id'] . ')');
                    }
                }
            }

            $data['pwd'] = trim($data['pwd']);

            if (!empty($data['pwd'])) {

                $salt = $member['salt'];

                if (empty($salt)) {
                    $salt = m('account')->getSalt();
                }

                $data['pwd']  = md5($data['pwd'] . $salt);
                $data['salt'] = $salt;

            } else {

                unset($data['pwd']);
                unset($data['salt']);

            }

            // TODO mark update_superdesk_shop_member
            pdo_update('superdesk_shop_member', $data, array('id' => $id, 'uniacid' => $_W['uniacid']));

            $member = m('member')->getMember($id);

            mplog('member.list.edit',
                '修改会员资料  ID: ' . $member['id'] .
                ' <br/> 会员信息:  ' . $member['openid'] .
                '/' . $member['nickname'] .
                '/' . $member['realname'] .
                '/' . $member['mobile']);

            if ($hascommission) {
                if (cv('commission.agent.edit')) {
                    $adata = ((is_array($_GPC['adata']) ? $_GPC['adata'] : array()));
                    if (!empty($adata)) {
                        if ($adata['agentid'] != $member['agentid']) {
                            if (cv('commission.agent.changeagent')) {
                                mplog('commission.agent.changeagent',
                                    '修改上级营销商 <br/> 会员信息:  ' . $member['openid'] .
                                    '/' . $member['nickname'] .
                                    '/' . $member['realname'] .
                                    '/' . $member['mobile'] .
                                    ' <br/>上级ID: ' . $member['agentid'] .
                                    ' -> 新上级ID: ' . $adata['agentid'] . '; <br/> 固定上级: ' . (($member['fixagentid'] ? '是' : '否')) .
                                    ' -> ' . (($adata['fixagentid'] ? '是' : '否')));
                            } else {
                                $adata['agentid'] = $member['agentid'];
                            }
                        }
                        if (empty($_GPC['oldstatus']) && ($adata['status'] == 1)) {

                            $time               = time();
                            $adata['agenttime'] = time();

                            $plugin_com->sendMessage($member['openid'],
                                array(
                                    'nickname'  => $member['nickname'],
                                    'agenttime' => $time
                                ), TM_COMMISSION_BECOME);

                            mplog('commission.agent.check',
                                '审核营销商 <br/>营销商信息:  ID: ' . $member['id'] .
                                ' /  ' . $member['openid'] .
                                '/' . $member['nickname'] .
                                '/' . $member['realname'] .
                                '/' . $member['mobile']);
                        }
                        mplog('commission.agent.edit', '修改营销商 <br/>营销商信息:  ID: ' . $member['id'] . ' /  ' . $member['openid'] . '/' . $member['nickname'] . '/' . $member['realname'] . '/' . $member['mobile']);

                        // TODO mark update_superdesk_shop_member
                        pdo_update('superdesk_shop_member', $adata, array('id' => $id, 'uniacid' => $_W['uniacid']));

                        if (empty($_GPC['oldstatus']) && ($adata['status'] == 1)) {
                            if (!empty($member['agentid'])) {
                                $plugin_com->upgradeLevelByAgent($member['agentid']);
                                if (p('globonus')) {
                                    p('globonus')->upgradeLevelByAgent($member['agentid']);
                                }
                                if (p('author')) {
                                    p('author')->upgradeLevelByAgent($member['agentid']);
                                }
                            }
                        }
                    }
                }
            }

            show_json(1);
        }
        if ($hascommission) {
            $agentlevels = $plugin_com->getLevels();
        }

        $member = m('member')->getMember($id);
        if ($hascommission) {
            $member = $plugin_com->getInfo($id, array('total', 'pay'));
        }

        include $this->template();
    }

    public function delete()
    {
        global $_W;
        global $_GPC;
        $id = intval($_GPC['id']);
        if (empty($id)) {
            $id = ((is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0));
        }
        $members = pdo_fetchall('SELECT * FROM ' . tablename('superdesk_shop_member') . ' WHERE id in( ' . $id . ' ) AND uniacid=' . $_W['uniacid']);
        foreach ($members as $member) {
            pdo_delete('superdesk_shop_member', array('id' => $member['id']));
            mplog('member.list.delete', '删除会员  ID: ' . $member['id'] . ' <br/>会员信息: ' . $member['openid'] . '/' . $member['nickname'] . '/' . $member['realname'] . '/' . $member['mobile']);
        }
        show_json(1, array('url' => referer()));
    }

    public function setblack()
    {
        global $_W;
        global $_GPC;
        $id = intval($_GPC['id']);
        if (empty($id)) {
            $id = ((is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0));
        }
        $members = pdo_fetchall('select id,openid,nickname,realname,mobile from ' . tablename('superdesk_shop_member') . ' WHERE id in( ' . $id . ' ) AND uniacid=' . $_W['uniacid']);
        $black   = intval($_GPC['isblack']);
        foreach ($members as $member) {
            if (!empty($black)) {

                // TODO mark update_superdesk_shop_member
                pdo_update('superdesk_shop_member', array('isblack' => 1), array('id' => $member['id']));
                mplog('member.list.edit', '设置黑名单 <br/>用户信息:  ID: ' . $member['id'] . ' /  ' . $member['openid'] . '/' . $member['nickname'] . '/' . $member['realname'] . '/' . $member['mobile']);
            } else {

                // TODO mark update_superdesk_shop_member
                pdo_update('superdesk_shop_member', array('isblack' => 0), array('id' => $member['id']));
                mplog('member.list.edit', '取消黑名单 <br/>用户信息:  ID: ' . $member['id'] . ' /  ' . $member['openid'] . '/' . $member['nickname'] . '/' . $member['realname'] . '/' . $member['mobile']);
            }
        }
        show_json(1);
    }

    public function query()
    {
        global $_W;
        global $_GPC;
        $kwd      = trim($_GPC['keyword']);
        $wechatid = intval($_GPC['wechatid']);
        if (empty($wechatid)) {
            $wechatid = $_W['uniacid'];
        }
        $params             = array();
        $params[':uniacid'] = $wechatid;
        $condition          = ' and uniacid=:uniacid';
        if (!empty($kwd)) {
            $condition .= ' AND ( `nickname` LIKE :keyword or `realname` LIKE :keyword or `mobile` LIKE :keyword )';
            $params[':keyword'] = '%' . $kwd . '%';
        }
        $ds = pdo_fetchall('SELECT id,avatar,nickname,openid,realname,mobile FROM ' . tablename('superdesk_shop_member') . ' WHERE 1 ' . $condition . ' order by createtime desc', $params);
        if ($_GPC['suggest']) {
            exit(json_encode(array('value' => $ds)));
        }
        include $this->template();
    }
}


?>