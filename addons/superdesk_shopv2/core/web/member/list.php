<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

/* 企业内购-数据源 start */
include_once(IA_ROOT . '/addons/superdesk_core/model/organization.class.php');
include_once(IA_ROOT . '/addons/superdesk_core/model/virtualarchitecture.class.php');
/* 企业内购-数据源 end */

/* 福利商城-数据源 start */
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/__plugin__/enterprise/enterprise_user.class.php');

/* 福利商城-数据源 start */

class List_SuperdeskShopV2Page extends WebPage
{

    private $_organizationModel;
    private $_virtualarchitectureModel;
    private $_enterprise_userModel;

    public function __construct()
    {
        // mark welfare
        switch (SUPERDESK_SHOPV2_MODE_USER) {
            case 1:// 1 超级前台
                $this->_organizationModel        = new organizationModel();
                $this->_virtualarchitectureModel = new virtualarchitectureModel();
                break;
            case 2:// 2 福利商城
                $this->_enterprise_userModel = new enterprise_userModel();
                break;
        }
    }


    public function main()
    {
        global $_W;
        global $_GPC;

        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;

        $condition = ' and dm.uniacid=:uniacid';
        $params    = array(':uniacid' => $_W['uniacid']);

        if (!empty($_GPC['mid'])) {
            $condition      .= ' and dm.id=:mid';
            $params[':mid'] = intval($_GPC['mid']);
        }

        // 可搜索昵称/姓名/手机号/ID
        if (!empty($_GPC['realname'])) {

            $_GPC['realname'] = trim($_GPC['realname']);

            $condition           .= ' and ( dm.realname like :realname or dm.nickname like :realname or dm.mobile like :realname or dm.id like :realname)';
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

        // 企业筛选 zjh 2018年5月23日 15:58:49
        if ($_GPC['enterprise_id'] != '') {
            $condition .= ' and core_enterprise=' . intval($_GPC['enterprise_id']);
        }

        $join_select = '';
        $join = '';

        if ($_GPC['followed'] != '') {

            if ($_GPC['followed'] == 2) {
                $condition .= ' and f.follow=0 and dm.uid<>0';
            } else {
                $condition .= ' and f.follow=' . intval($_GPC['followed']);
            }

            $join_select = '       f.*, ';
            $join .= ' left join ' . tablename('mc_mapping_fans') . ' f on f.openid=dm.openid';
        }

        $join .=
        ' LEFT JOIN ' . tablename('superdesk_core_organization') . ' as sco on sco.id = dm.core_organization ' .
        ' LEFT JOIN ' . tablename('superdesk_core_virtualarchitecture') . ' as scv on scv.id = dm.core_enterprise ' ;


        if ($_GPC['isblack'] != '') {
            $condition .= ' and dm.isblack=' . intval($_GPC['isblack']);
        }

        $sql =
            ' select '.
            '       dm.*, ' .
            $join_select.
            '       sco.name as core_organization_name, '.
            '       scv.name as core_enterprise_name '.
            ' from ' . tablename('superdesk_shop_member') . ' dm ' . // TODO 标志 楼宇之窗 openid shop_member 不处理
            $join .
            ' where 1 ' .
            $condition .
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
                ' select ' .
                '       id,nickname as agentnickname,avatar as agentavatar ' .
                ' from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid shop_member 不处理
                ' where ' .
                '       id in (' . implode(',', $list_agent) . ')',
                array(),
                'id'
            );
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

            $row['ordercount'] = pdo_fetchcolumn(
                ' select count(*) ' .
                ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
                ' where ' .
                '       uniacid=:uniacid ' .
                '       and openid=:openid ' .
                '       and core_user=:core_user ' .
                '       and status=3',
                array(
                    ':uniacid'   => $_W['uniacid'],
                    ':openid'    => $row['openid'],
                    ':core_user' => $row['core_user'],
                )
            );

            $row['ordermoney'] = pdo_fetchcolumn(
                ' select sum(goodsprice) ' .
                ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
                ' where ' .
                '       uniacid=:uniacid ' .
                '       and openid=:openid ' .
                '       and core_user=:core_user ' .
                '       and status=3',
                array(
                    ':uniacid'   => $_W['uniacid'],
                    ':openid'    => $row['openid'],
                    ':core_user' => $row['core_user'],
                )
            );

            $row['credit1'] = m('member')->getCredit($row['openid'], $row['core_user'], 'credit1');
            $row['credit2'] = m('member')->getCredit($row['openid'], $row['core_user'], 'credit2');
        }

        unset($row);

        if ($_GPC['export'] == '1') {
            plog('member.list', '导出会员数据');


            //2019年3月5日 09:44:15 zjh 应宇迪要求添加
            $enterprise_ids = array_column($list, 'core_enterprise');
            $enterprise_ids = implode(',', $enterprise_ids);


            //2019年3月5日 09:44:15 zjh 应宇迪要求添加
            // mark welfare
            $enterprise_array = array();
            if (!empty($enterprise_ids)) {
                switch (SUPERDESK_SHOPV2_MODE_USER) {
                    case 1:// 1 超级前台
                        $enterprise_array = pdo_fetchall(
                            ' SELECT core_enterprise.id,core_enterprise.name as enterprise_name,organization.name as organization_name ' .
                            ' FROM ' . tablename('superdesk_core_virtualarchitecture') . ' as core_enterprise ' .
                            ' LEFT JOIN ' . tablename('superdesk_core_organization') . ' as organization on organization.id = core_enterprise.organizationId ' .
                            ' WHERE core_enterprise.id IN (' . $enterprise_ids . ')',
                            array(),
                            'id'
                        );

                        break;
                    case 2:// 2 福利商城
                        $enterprise_array = pdo_fetchall(
                            ' SELECT id,enterprise_name,"" as organization_name ' .
                            ' FROM ' . tablename('superdesk_shop_enterprise_user') .
                            ' WHERE id IN (' . $enterprise_ids . ')',
                            array(),
                            'id'
                        );

                        break;
                }
            }



            $cashRoleList = pdo_fetchall(
                'SELECT * ' .
                ' FROM ' . tablename('superdesk_shop_member_cash_role') .
                ' ORDER BY id asc',
                array(),
                'id'
            );

            foreach ($list as &$row) {
                $row['createtime'] = date('Y-m-d H:i', $row['createtime']);
                $row['groupname']  = ((empty($row['groupname']) ? '无分组' : $row['groupname']));
                $row['levelname']  = ((empty($row['levelname']) ? '普通会员' : $row['levelname']));


                //2019年3月5日 09:44:15 zjh 应宇迪要求添加
                $row['enterprise_name']   = $enterprise_array[$row['core_enterprise']]['enterprise_name'];
                $row['organization_name'] = $enterprise_array[$row['core_enterprise']]['organization_name'];

                //2019年4月28日 15:02:00 zjh 应宇迪要求添加
                $row['rolename']   = $cashRoleList[$row['cash_role_id']]['rolename'];
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
                    array('title' => '成交总金额', 'field' => 'ordermoney', 'width' => 12),

                    //2019年3月5日 09:44:15 zjh 应宇迪要求添加
                    array('title' => '所属项目', 'field' => 'enterprise_name', 'width' => 24),
                    array('title' => '所属企业', 'field' => 'organization_name', 'width' => 24),

                    //2019年4月28日 15:03:00 zjh 应宇迪要求添加
                    array('title' => '会员角色', 'field' => 'rolename', 'width' => 24),
                ),
            ));
        }

        $total = pdo_fetchcolumn(
            ' select ' .
            '       count(*) ' .
            ' from' . tablename('superdesk_shop_member') . ' dm ' . // TODO 标志 楼宇之窗 openid shop_member 不处理
            $join .
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

        // mark welfare
        switch (SUPERDESK_SHOPV2_MODE_USER) {
            case 1:// 1 超级前台
                // 项目
                $result_organization   = $this->_organizationModel->querySelector(
                    array(
                        "isEnabled" => 1,
                        "status"    => 1   //0-待审核;1-通过;2-不通过
                    ), 1, 999);
                $selector_organization = $result_organization['data'];

                $selector_virtuals = array();
                if ($_GPC['organization_id'] != '') {

                    //2019年3月14日 16:33:16 zjh 佘司雄 选择后点搜索后不会自动选中 屏蔽掉 contractStatus status
                    $result_virtuals   = $this->_virtualarchitectureModel->queryForUsersAjax(
                        array(
                            "organizationId" => $_GPC['organization_id'],
                            "isEnabled"      => 1,
//                            "contractStatus" => 1,  //1-已签约;0-未签约
//                            "status"         => 1   //0-待审核;1-通过;2-不通过
                        ), 1, 2000);
                    $selector_virtuals = $result_virtuals['data'];
                }
                break;
            case 2:// 2 福利商城
                // 企业
                $selector_virtuals = $this->_enterprise_userModel->getAllByWhere(' status=:status ', array(':status' => 1));
                break;
        }

        include $this->template();
    }

    public function detail()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        $shop          = $_W['shopset']['shop'];
        $hascommission = false;

        $plugin_com = p('commission');
        if ($plugin_com) {
            $plugin_com_set = $plugin_com->getSet();
            $hascommission  = !empty($plugin_com_set['level']);
        }

        $plugin_globonus = p('globonus');
        if ($plugin_globonus) {
            $plugin_globonus_set = $plugin_globonus->getSet();
            $hasglobonus         = !empty($plugin_globonus_set['open']);
        }

        $plugin_author = p('author');
        if ($plugin_author) {
            $plugin_author_set = $plugin_author->getSet();
            $hasauthor         = !empty($plugin_author_set['open']);
        }

        $plugin_abonus = p('abonus');
        if ($plugin_abonus) {
            $plugin_abonus_set = $plugin_abonus->getSet();
            $hasabonus         = !empty($plugin_abonus_set['open']);
        }

        if ($hascommission) {
            $agentlevels = $plugin_com->getLevels();
        }

        // 合伙人
        if ($hasglobonus) {
            $partnerlevels = $plugin_globonus->getLevels();
        }

        if ($hasabonus) {
            $aagentlevels = $plugin_abonus->getLevels();
        }

        $member = m('member')->getMemberById($id);

        if ($hascommission) {
            $member = $plugin_com->getInfo($id, array('total', 'pay'));
        }

        $member['self_ordercount'] = pdo_fetchcolumn(
            ' select ' .
            '       count(*) ' .
            ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where ' .
            '       uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and status=3',
            array(
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $member['openid'],
                ':core_user' => $member['core_user'],
            )
        );

        $member['self_ordermoney'] = pdo_fetchcolumn(
            ' select ' .
            '       sum(price) ' .
            ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where ' .
            '       uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and status=3',
            array(
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $member['openid'],
                ':core_user' => $member['core_user'],
            )
        );

        if (!empty($member['agentid'])) {
            $parentagent = m('member')->getMemberById($member['agentid']);
        }

        $order = pdo_fetch(
            ' select finishtime ' .
            ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where ' .
            '       uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and status>=1 ' .
            ' Limit 1',
            array(
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $member['openid'],
                ':core_user' => $member['core_user'],
            )
        );

        $member['last_ordertime'] = $order['finishtime'];

        if ($hasglobonus) {
            $bonus                = $plugin_globonus->getBonus($member['openid'], $member['core_user'], array('ok'));// TODO 标志 楼宇之窗 openid shop_globonus_billp 已处理
            $member['bonusmoney'] = $bonus['ok'];
        }

        if ($hasabonus) {
            $bonus                     = $plugin_abonus->getBonus($member['openid'], array('ok', 'ok1', 'ok2', 'ok3'));
            $member['abonus_ok']       = $bonus['ok'];
            $member['abonus_ok1']      = $bonus['ok1'];
            $member['abonus_ok2']      = $bonus['ok2'];
            $member['abonus_ok3']      = $bonus['ok3'];
            $member['aagentprovinces'] = iunserializer($member['aagentprovinces']);
            $member['aagentcitys']     = iunserializer($member['aagentcitys']);
            $member['aagentareas']     = iunserializer($member['aagentareas']);
        }

        $plugin_sns = p('sns');

        if ($plugin_sns) {
            $plugin_sns_set = $plugin_sns->getSet();

            $sns_member = pdo_fetch(
                'select * ' .
                ' from ' . tablename('superdesk_shop_sns_member') .
                ' where ' .
                '       openid=:openid ' .
                '       and core_user=:core_user ' .
                '       and uniacid=:uniacid ' .
                ' limit 1',
                array(
                    ':uniacid'   => $_W['uniacid'],
                    ':openid'    => $member['openid'],
                    ':core_user' => $member['core_user'],
                )
            );

            $sns_member['postcount'] = pdo_fetchcolumn(
                'select count(*) ' .
                ' from ' . tablename('superdesk_shop_sns_post') .
                ' where ' .
                '       uniacid=:uniacid ' .
                '       and openid=:openid ' .
                '       and core_user=:core_user ' .
                '       and pid=0 ' .
                '       and deleted = 0 ' .
                '       and checked=1',
                array(
                    ':uniacid'   => $_W['uniacid'],
                    ':openid'    => $member['openid'],
                    ':core_user' => $member['core_user'],
                )
            );

            $sns_member['replycount'] = pdo_fetchcolumn(
                'select count(*) ' .
                ' from ' . tablename('superdesk_shop_sns_post') .
                ' where ' .
                '       uniacid=:uniacid ' .
                '       and openid=:openid ' .
                '       and core_user=:core_user ' .
                '       and pid>0 ' .
                '       and deleted = 0 ' .
                '       and checked=1',
                array(
                    ':uniacid'   => $_W['uniacid'],
                    ':openid'    => $member['openid'],
                    ':core_user' => $member['core_user'],
                )
            );

            $hassns = !empty($sns_member);

            if ($hassns) {
                $snslevels = $plugin_sns->getLevels();
            }
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

        //zjh 2018年9月25日 16:11:48 用户余额变动记录
        $creditLogList = pdo_fetchall(
            ' select * ' .
            ' from ' . tablename('superdesk_shop_member_credit_log') .
            ' where ' .
            '       openid=:openid ' .
            '       and core_user=:core_user ' .
            ' order by createtime desc ',
            array(
                ':openid'    => $member['openid'],
                ':core_user' => $member['core_user'],
            )
        );

        //creditlogType 1是员工导入,2总端充值,3订单,4退款,5购买优惠券
        $creditLogType = array(
            1 => '员工导入',
            2 => '总端充值',
            3 => '订单下单',
            4 => '订单退款',
            5 => '购买优惠券',
        );

        $groups    = m('member')->getGroups();
        $cashroles = m('member')->getCashroles(); //会员角色 zjh 2018年4月23日 17:13:05
        $levels    = m('member')->getLevels();

        socket_log(json_encode($cashroles, JSON_UNESCAPED_UNICODE));

        if ($_W['ispost']) {

            $data = ((is_array($_GPC['data']) ? $_GPC['data'] : array()));

            if (!empty($_W['shopset']['wap']['open'])) {

                if (!empty($data['mobileverify'])) {

                    if (empty($data['mobile'])) {
                        show_json(0, '绑定手机号请先填写用户手机号!');
                    }

                    if (empty($data['core_user'])) {
                        show_json(0, '此用户非楼宇之窗用户!');
                    }

                    $m = pdo_fetch(
                        ' select id ' .
                        ' from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 mobile shop_member 已处理
                        ' where ' .
                        '       mobile=:mobile ' .
                        '       and mobileverify=1 ' .
                        '       and uniacid=:uniaicd ' .
                        ' limit 1 ',
                        array(
                            ':mobile'    => $data['mobile'],
                            ':core_user' => $data['core_user'],
                            ':uniaicd'   => $_W['uniacid'],
                        )
                    );

                    if (!empty($m) && ($m['id'] != $id)) {
                        show_json(0, '此手机号已绑定其他用户!(uid:' . $m['id'] . ')');
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
            }

            pdo_update(
                'superdesk_shop_member', // TODO 标志 楼宇之窗 openid shop_member 不处理
                $data,
                array(
                    'id'      => $id,
                    'uniacid' => $_W['uniacid'],
                )
            );

            $member = m('member')->getMemberById($id);

            plog('member.list.edit',
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
                                plog('commission.agent.changeagent',
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
                                    'agenttime' => $time,
                                ), TM_COMMISSION_BECOME);

                            plog('commission.agent.check',
                                '审核营销商 <br/>营销商信息:  ID: ' . $member['id'] .
                                ' /  ' . $member['openid'] .
                                '/' . $member['nickname'] .
                                '/' . $member['realname'] .
                                '/' . $member['mobile']);
                        }
                        plog('commission.agent.edit', '修改营销商 <br/>营销商信息:  ID: ' . $member['id'] . ' /  ' . $member['openid'] . '/' . $member['nickname'] . '/' . $member['realname'] . '/' . $member['mobile']);

                        pdo_update(
                            'superdesk_shop_member', // TODO 标志 楼宇之窗 openid shop_member 不处理
                            $adata,
                            array(
                                'id'      => $id,
                                'uniacid' => $_W['uniacid'],
                            )
                        );

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

            if ($hasglobonus) {

                if (cv('globonus.partner.check')) {

                    $gdata = ((is_array($_GPC['gdata']) ? $_GPC['gdata'] : array()));

                    if (!empty($gdata)) {

                        if (empty($_GPC['oldpartnerstatus']) && ($gdata['partnerstatus'] == 1)) {

                            $time                 = time();
                            $gdata['partnertime'] = time();
                            $plugin_globonus->sendMessage($member['openid'], array('nickname' => $member['nickname'], 'partnertime' => $time), TM_GLOBONUS_BECOME);
                            plog('globonus.partner.check', '审核股东 <br/>股东信息:  ID: ' . $member['id'] . ' /  ' . $member['openid'] . '/' . $member['nickname'] . '/' . $member['realname'] . '/' . $member['mobile']);
                        }

                        plog('globonus.partner.edit', '修改股东 <br/>股东信息:  ID: ' . $member['id'] . ' /  ' . $member['openid'] . '/' . $member['nickname'] . '/' . $member['realname'] . '/' . $member['mobile']);

                        pdo_update(
                            'superdesk_shop_member', // TODO 标志 楼宇之窗 openid shop_member 不处理
                            $gdata,
                            array(
                                'id'      => $id,
                                'uniacid' => $_W['uniacid'],
                            )
                        );
                    }
                }
            }

            if ($hasauthor) {

                if (cv('author.partner.check')) {

                    $author_data = ((is_array($_GPC['authordata']) ? $_GPC['authordata'] : array()));

                    if (!empty($author_data)) {

                        if (empty($_GPC['oldauthorstatus']) && ($author_data['authorstatus'] == 1)) {

                            $author_data['authortime'] = time();
                            if (method_exists($plugin_author, 'changeAuthorId')) {
                                $plugin_author->changeAuthorId($member['id']);
                            }
                            $plugin_author->sendMessage($member['openid'], array('nickname' => $member['nickname'], 'authortime' => time()), TM_AUTHOR_BECOME);
                            plog('author.partner.check', '审核创始人 <br/>创始人信息:  ID: ' . $member['id'] . ' /  ' . $member['openid'] . '/' . $member['nickname'] . '/' . $member['realname'] . '/' . $member['mobile']);
                        }

                        plog('author.partner.edit', '修改创始人 <br/>创始人信息:  ID: ' . $member['id'] . ' /  ' . $member['openid'] . '/' . $member['nickname'] . '/' . $member['realname'] . '/' . $member['mobile']);

                        pdo_update(
                            'superdesk_shop_member', // TODO 标志 楼宇之窗 openid shop_member 不处理
                            $author_data,
                            array(
                                'id'      => $id,
                                'uniacid' => $_W['uniacid'],
                            )
                        );
                    }
                }
            }

            if ($hasabonus) {

                if (cv('abonus.agent.check')) {

                    $aadata = ((is_array($_GPC['aadata']) ? $_GPC['aadata'] : array()));

                    if (!empty($aadata)) {
                        $aagentprovinces           = ((is_array($_GPC['aagentprovinces']) ? $_GPC['aagentprovinces'] : array()));
                        $aagentcitys               = ((is_array($_GPC['aagentcitys']) ? $_GPC['aagentcitys'] : array()));
                        $aagentareas               = ((is_array($_GPC['aagentareas']) ? $_GPC['aagentareas'] : array()));
                        $aadata['aagentprovinces'] = iserializer($aagentprovinces);
                        $aadata['aagentcitys']     = iserializer($aagentcitys);
                        $aadata['aagentareas']     = iserializer($aagentareas);

                        if ($aadata['aagenttype'] == 2) {
                            $aadata['aagentprovinces'] = iserializer(array());
                        } else if ($aadata['aagenttype'] == 3) {
                            $aadata['aagentprovinces'] = iserializer(array());
                            $aadata['aagentcitys']     = iserializer(array());
                        }

                        $areas = array_merge($aagentprovinces, $aagentcitys, $aagentareas);

                        if (empty($_GPC['oldaagentstatus']) && ($aadata['aagentstatus'] == 1)) {
                            $time                 = time();
                            $aadata['aagenttime'] = time();
                            $plugin_abonus->sendMessage($member['openid'], array('nickname' => $member['nickname'], 'aagenttype' => $aadata['aagenttype'], 'aagenttime' => $time, 'aagentareas' => implode('; ', $areas)), TM_ABONUS_BECOME);
                            plog('abounus.agent.check', '审核代理商 <br/>代理商信息:  ID: ' . $member['id'] . ' /  ' . $member['openid'] . '/' . $member['nickname'] . '/' . $member['realname'] . '/' . $member['mobile']);
                        }

                        $log = '修改代理商 <br/>代理商信息:  ID: ' . $member['id'] . ' /  ' . $member['openid'] . '/' . $member['nickname'];
                        if (is_array($_GPC['aagentprovinces'])) {
                            $log .= '<br/>代理省份:' . implode(',', $_GPC['aagentprovinces']);
                        }

                        if (is_array($_GPC['aagentcitys'])) {
                            $log .= '<br/>代理城市:' . implode(',', $_GPC['aagentcitys']);
                        }

                        if (is_array($_GPC['aagentareas'])) {
                            $log .= '<br/>代理地区:' . implode(',', $_GPC['aagentareas']);
                        }
                        plog('abounus.agent.edit', $log);

                        pdo_update(
                            'superdesk_shop_member', // TODO 标志 楼宇之窗 openid shop_member 不处理
                            $aadata,
                            array(
                                'id'      => $id,
                                'uniacid' => $_W['uniacid'],
                            )
                        );
                    }
                }
            }
            if ($hassns) {

                if (cv('sns.member.edit')) {

                    $snsdata = ((is_array($_GPC['snsdata']) ? $_GPC['snsdata'] : array()));

                    if (!empty($snsdata)) {

                        plog('sns.member.edit', '修改会员资料 ID: ' . $sns_member['id']);

                        pdo_update(
                            'superdesk_shop_sns_member',
                            $snsdata,
                            array(
                                'id'      => $sns_member['id'],
                                'uniacid' => $_W['uniacid'],
                            )
                        );
                    }
                }
            }
            show_json(1);
        }
        if ($hascommission) {
            $agentlevels = $plugin_com->getLevels();
        }
        if ($hasglobonus) {
            $partnerlevels = $plugin_globonus->getLevels();
        }
        if ($hasauthor) {
            $authorlevels = $plugin_author->getLevels();
        }
        if ($hasabonus) {
            $aagentlevels = $plugin_abonus->getLevels();
        }

        $member = m('member')->getMemberById($id);

        if ($hascommission) {
            $member = $plugin_com->getInfo($id, array('total', 'pay'));
        }

        $member['self_ordercount'] = pdo_fetchcolumn(
            ' select count(*) ' .
            ' from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where ' .
            '       uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and status=3',
            array(
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $member['openid'],
                ':core_user' => $member['core_user'],
            )
        );

        $member['self_ordermoney'] = pdo_fetchcolumn(
            ' select sum(goodsprice) ' .
            ' from ' . tablename('superdesk_shop_order') .  // TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where ' .
            '       uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and status=3',
            array(
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $member['openid'],
                ':core_user' => $member['core_user'],
            )
        );

        if (!empty($member['agentid'])) {
            $parentagent = m('member')->getMemberById($member['agentid']);
        }

        $order = pdo_fetch(
            'select finishtime ' .
            ' from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where ' .
            '       uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and status=3 ' .
            ' order by id desc ' .
            ' limit 1',
            array(
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $member['openid'],
                ':core_user' => $member['core_user'],
            )
        );

        $member['last_ordertime'] = $order['finishtime'];

        if ($hasglobonus) {
            $bonus                = $plugin_globonus->getBonus($member['openid'], $member['core_user'], array('ok'));// TODO 标志 楼宇之窗 openid shop_globonus_billp 已处理
            $member['bonusmoney'] = $bonus['ok'];
        }

        if ($hasauthor) {
            $bonus                 = $plugin_author->getBonus($member['openid'], array('ok'));
            $member['authormoney'] = $bonus['ok'];
        }

        if ($hasabonus) {
            $bonus                     = $plugin_abonus->getBonus($member['openid'], array('ok', 'ok1', 'ok2', 'ok3'));
            $member['abonus_ok']       = $bonus['ok'];
            $member['abonus_ok1']      = $bonus['ok1'];
            $member['abonus_ok2']      = $bonus['ok2'];
            $member['abonus_ok3']      = $bonus['ok3'];
            $member['aagentprovinces'] = iunserializer($member['aagentprovinces']);
            $member['aagentcitys']     = iunserializer($member['aagentcitys']);
            $member['aagentareas']     = iunserializer($member['aagentareas']);
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
            if (!empty($member['diyauthordata'])) {
                $diyform_flag_author = 1;
                $authorfields        = iunserializer($member['diyauthordata']);
            }
            if (!empty($member['diyaagentdata'])) {
                $diyform_flag_abonus = 1;
                $aafields            = iunserializer($member['diyaagentfields']);
            }
        }

        $groups = m('member')->getGroups();
        $levels = m('member')->getLevels();

        // mark welfare
        switch (SUPERDESK_SHOPV2_MODE_USER) {
            case 1:// 1 超级前台

                $enterprise = pdo_fetch(
                    'select name,organizationId ' .
                    ' from ' . tablename('superdesk_core_virtualarchitecture') .
                    ' where id=:id',
                    array(
                        ':id' => $member['core_enterprise'],
                    )
                );

                $member['enterprise_name'] = $enterprise['name'];

                $member['organization_name'] = pdo_fetchcolumn(
                    'select name ' .
                    ' from ' . tablename('superdesk_core_organization') .
                    ' where id=:id',
                    array(
                        ':id' => $enterprise['organizationId'],
                    )
                );
                break;
            case 2:// 2 福利商城
                $enterprise                = $this->_enterprise_userModel->getOne($member['core_enterprise']);
                $member['enterprise_name'] = $enterprise['enterprise_name'];
                break;
        }

        include $this->template();
    }

    /**
     * 删除会员
     */
    public function delete()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        if (empty($id)) {
            $id = ((is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0));
        }

        $members = pdo_fetchall(
            'SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid shop_member 不处理
            ' WHERE ' .
            '       id in( ' . $id . ' ) ' .
            '       AND uniacid=' . $_W['uniacid']
        );

        foreach ($members as $member) {

            pdo_delete(
                'superdesk_shop_member', // TODO 标志 楼宇之窗 openid shop_member 不处理
                array('id' => $member['id'])
            );

            plog(
                'member.list.delete',
                '删除会员  ID: ' . $member['id'] .
                ' <br/>会员信息: ' . $member['openid'] . '/' . $member['nickname'] . '/' . $member['realname'] . '/' . $member['mobile']
            );
        }

        show_json(1, array('url' => referer()));
    }

    /**
     * 黑名单
     */
    public function setblack()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        if (empty($id)) {
            $id = ((is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0));
        }

        $members = pdo_fetchall(
            'select ' .
            '       id,openid,nickname,realname,mobile ' .
            ' from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid shop_member 不处理
            ' WHERE ' .
            '       id in( ' . $id . ' ) ' .
            '       AND uniacid=' . $_W['uniacid']
        );

        $black = intval($_GPC['isblack']);

        foreach ($members as $member) {

            if (!empty($black)) {

                pdo_update('superdesk_shop_member', array('isblack' => 1), array('id' => $member['id'])); // TODO 标志 楼宇之窗 openid shop_member 不处理
                plog('member.list.edit', '设置黑名单 <br/>用户信息:  ID: ' . $member['id'] . ' /  ' . $member['openid'] . '/' . $member['nickname'] . '/' . $member['realname'] . '/' . $member['mobile']);

            } else {

                pdo_update('superdesk_shop_member', array('isblack' => 0), array('id' => $member['id'])); // TODO 标志 楼宇之窗 openid shop_member 不处理
                plog('member.list.edit', '取消黑名单 <br/>用户信息:  ID: ' . $member['id'] . ' /  ' . $member['openid'] . '/' . $member['nickname'] . '/' . $member['realname'] . '/' . $member['mobile']);

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
            $condition          .= ' AND ( `nickname` LIKE :keyword or `realname` LIKE :keyword or `mobile` LIKE :keyword )';
            $params[':keyword'] = '%' . $kwd . '%';
        }

        $ds = pdo_fetchall(
            'SELECT id,avatar,nickname,openid,realname,mobile ' .
            ' FROM ' . tablename('superdesk_shop_member') .  // TODO 标志 楼宇之窗 openid shop_member 不处理
            ' WHERE 1 ' .
            $condition .
            ' order by createtime desc',
            $params
        );

        if ($_GPC['suggest']) {
            exit(json_encode(array('value' => $ds)));
        }

        include $this->template();
    }

    public function ajax()
    {

        global $_W;
        global $_GPC;

        $organization_id = $_GPC['organization_id'];

        if ($organization_id) {

            $_result = $this->_virtualarchitectureModel->queryForUsersAjax(array(
                "organizationId" => $organization_id,
            ), 1, 999);

            $virtuals = $_result['data'];

            show_json(1, $virtuals);
        }
    }

    public function ajaxMember()
    {

        global $_W;
        global $_GPC;

        $enterprise_id = $_GPC['enterprise_id'];

        if ($enterprise_id) {

            $member = pdo_fetchall(
                ' select ' .
                '       id,realname,openid ' .
                ' from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid shop_member 不处理
                ' where ' .
                '       uniacid=:uniacid ' .
                '       and core_enterprise=:enterprise_id',
                array(
                    ':uniacid'       => $_W['uniacid'],
                    ':enterprise_id' => $enterprise_id,
                )
            );

            show_json(1, array('member' => $member));
        }
    }

    public function setGroup()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);
        if (empty($id)) {
            $id = ((is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0));
        }

        $members         = pdo_fetchall(
            'select ' .
            '       id,openid,nickname,realname,mobile ' .
            ' from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid shop_member 不处理
            ' WHERE ' .
            '       id in( ' . $id . ' ) AND uniacid=' . $_W['uniacid']
        );
        $select_group_id = intval($_GPC['select_group_id']);

        foreach ($members as $member) {

            pdo_update('superdesk_shop_member', array('groupid' => $select_group_id), array('id' => $member['id'])); // TODO 标志 楼宇之窗 openid shop_member 不处理
            plog('member.list.edit', '批量分组 <br/>用户信息:  ID: ' . $member['id'] . ' /  ' . $member['openid'] . '/' . $member['nickname'] . '/' . $member['realname'] . '/' . $member['mobile'] . '/分组ID:' . $select_group_id);

        }
        show_json(1);
    }
}

