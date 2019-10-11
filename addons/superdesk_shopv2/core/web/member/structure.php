<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

include_once(IA_ROOT . '/addons/superdesk_core/model/organization.class.php');
include_once(IA_ROOT . '/addons/superdesk_core/model/virtualarchitecture.class.php');

class Structure_SuperdeskShopV2Page extends WebPage
{

    private $_organizationModel;
    private $_virtualarchitectureModel;

    public function __construct()
    {
        $this->_organizationModel        = new organizationModel();
        $this->_virtualarchitectureModel = new virtualarchitectureModel();
    }

    public function main()
    {
        global $_W;
        global $_GPC;

        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;

        $condition =
            ' and m.uniacid=:uniacid';

        $params = array(
            ':uniacid' => $_W['uniacid']
        );

        if ($_GPC['noSet'] != '') {
            if ($_GPC['noSet'] == 0) {
                $condition .= ' and noset_count is null';
            } else {
                $condition .= ' and noset_count>0';
            }
        }

        if ($_GPC['addMan'] != '') {
            if ($_GPC['addMan'] == 0) {
                $condition .= ' and man_count is null';
            } else {
                $condition .= ' and man_count>0';
            }
        }

        if ($_GPC['addManager'] != '') {
            if ($_GPC['addManager'] == 0) {
                $condition .= ' and manager_count is null';
            } else {
                $condition .= ' and manager_count>0';
            }
        }

        if (!empty($_GPC['organization_id'])) {
            $condition .= ' and m.core_organization=' . intval($_GPC['organization_id']);
        }

        if (!empty($_GPC['enterprise_id'])) { // 企业筛选 zjh 2018年5月23日 15:58:49
            $condition .= ' and m.core_enterprise=' . intval($_GPC['enterprise_id']);
        }

        $list = pdo_fetchall(
            ' SELECT '.
            '       DISTINCT m.core_enterprise,'.
            '       m.core_organization,'.
            '       v.name as core_enterprise_name,'.
            '       o.name as core_organization_name,'.
            '       ifnull(m1.noset_count,0) AS noset_count,ifnull(m2.man_count,0) AS man_count,ifnull(m3.manager_count,0) AS manager_count ' .
            ' FROM ' . tablename('superdesk_shop_member') . ' AS m ' .
            ' LEFT JOIN ' . tablename('superdesk_core_virtualarchitecture') . ' AS v ON v.id = m.core_enterprise ' .
            ' LEFT JOIN ' . tablename('superdesk_core_organization') . ' AS o ON o.id = m.core_organization ' .
            ' LEFT JOIN (' .
            '           SELECT core_enterprise,COUNT(*) AS noset_count ' .
            '           FROM ' . tablename('superdesk_shop_member') .
            '           WHERE core_enterprise > 0 AND cash_role_id = 0 GROUP BY core_enterprise' .
            '           ) AS m1 ON m.core_enterprise = m1.core_enterprise  ' .
            ' LEFT JOIN (' .
            '           SELECT core_enterprise,COUNT(*) AS man_count ' .
            '           FROM ' . tablename('superdesk_shop_member') .
            '           WHERE core_enterprise > 0 AND cash_role_id = 1 GROUP BY core_enterprise' .
            '           ) AS m2 ON m.core_enterprise = m2.core_enterprise  ' .
            ' LEFT JOIN (' .
            '           SELECT core_enterprise,COUNT(*) AS manager_count ' .
            '           FROM ' . tablename('superdesk_shop_member') .
            '           WHERE core_enterprise > 0 AND cash_role_id = 2 GROUP BY core_enterprise' .
            '           ) AS m3 ON m.core_enterprise = m3.core_enterprise  ' .
            ' WHERE m.core_enterprise > 0 ' .
            $condition .
            ' ORDER BY m.core_enterprise ' .
            ' LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize,
            $params
        );

        if ($condition != ' and m.uniacid=:uniacid') {

            $total = pdo_fetchcolumn(
                ' SELECT ' .
                '       COUNT(DISTINCT m.core_enterprise) ' .
                ' FROM ' . tablename('superdesk_shop_member') . ' AS m ' .
                ' LEFT JOIN ' . tablename('superdesk_core_virtualarchitecture') . ' AS v ON v.id = m.core_enterprise ' .
                ' LEFT JOIN (' .
                '            SELECT core_enterprise,COUNT(*) AS noset_count ' .
                '            FROM ' . tablename('superdesk_shop_member') .
                '            WHERE core_enterprise > 0 AND cash_role_id = 0 ' .
                '            GROUP BY core_enterprise' .
                '           ) AS m1 ON m.core_enterprise = m1.core_enterprise  ' .
                ' LEFT JOIN (' .
                '           SELECT core_enterprise,COUNT(*) AS man_count ' .
                '           FROM ' . tablename('superdesk_shop_member') .
                '           WHERE core_enterprise > 0 AND cash_role_id = 1 GROUP BY core_enterprise' .
                '           ) AS m2 ON m.core_enterprise = m2.core_enterprise  ' .
                ' LEFT JOIN (' .
                '           SELECT core_enterprise,COUNT(*) AS manager_count ' .
                '           FROM ' . tablename('superdesk_shop_member') .
                '           WHERE core_enterprise > 0 AND cash_role_id = 2 GROUP BY core_enterprise' .
                '           ) AS m3 ON m.core_enterprise = m3.core_enterprise  ' .
                ' WHERE m.core_enterprise > 0 ' .
                $condition,
                $params
            );
        } else {

            $total = pdo_fetchcolumn(
                ' SELECT COUNT(DISTINCT core_enterprise) ' .
                ' FROM ' . tablename('superdesk_shop_member') . ' AS m WHERE m.core_enterprise > 0 ' .
                $condition,
                $params
            );
        }

        $pager = pagination($total, $pindex, $psize);

        // 项目
        $result_organization   = $this->_organizationModel->querySelector(
            array(
                "isEnabled" => 1,
                "status"    => 1   //0-待审核;1-通过;2-不通过
            ), 1, 1000);
        $selector_organization = $result_organization['data'];

        // 企业
        $selector_virtuals = array();
        if ($_GPC['organization_id'] != '') {

            //2019年3月14日 16:33:16 zjh 佘司雄 选择后点搜索后不会自动选中 屏蔽掉 contractStatus status
            $result_virtuals   = $this->_virtualarchitectureModel->queryForUsersAjax(
                array(
                    "organizationId" => $_GPC['organization_id'],
                    "isEnabled"      => 1,
//                    "contractStatus" => 1,  //1-已签约;0-未签约
//                    "status"         => 1   //0-待审核;1-通过;2-不通过
                ), 1, 4000);
            $selector_virtuals = $result_virtuals['data'];
        }

        include $this->template();
    }

    public function add()
    {
        $this->post();
    }

    public function edit()
    {
        $this->post();
    }

    protected function post()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        $cashrole = pdo_fetch(
            ' SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_member_cash_role') .
            ' WHERE id =:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':id'      => $id,
                ':uniacid' => $_W['uniacid']
            )
        );

        if ($_W['ispost']) {

            $data = array(
                'uniacid'  => $_W['uniacid'],
                'rolename' => trim($_GPC['rolename'])
            );

            if (!empty($id)) {

                pdo_update('superdesk_shop_member_cash_role', $data, array('id' => $id, 'uniacid' => $_W['uniacid']));
                plog('member.cashrole.edit', '修改会员角色 ID: ' . $id);

            } else {

                pdo_insert('superdesk_shop_member_cash_role', $data);
                $id = pdo_insertid();
                plog('member.cashrole.add', '添加会员角色 ID: ' . $id);

            }

            show_json(1, array('url' => webUrl('member/cashrole', array('op' => 'display'))));
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


        $items = pdo_fetchall(
            ' SELECT id,rolename ' .
            ' FROM ' . tablename('superdesk_shop_member_cash_role') .
            ' WHERE id in( ' . $id . ' ) ' .
            '       AND uniacid=' . $_W['uniacid']
        );

        foreach ($items as $item) {

            pdo_update( // 不根据id更新
                'superdesk_shop_member', // TODO 标志 楼宇之窗 openid shop_member 不处理
                array(
                    'cash_role_id' => 0
                ),
                array(
                    'cash_role_id' => $item['id'],
                    'uniacid'      => $_W['uniacid']
                )
            );
            pdo_delete(
                'superdesk_shop_member_cash_role',
                array(
                    'id' => $item['id']
                )
            );

            plog('member.group.delete', '删除角色 ID: ' . $item['id'] . ' 名称: ' . $item['rolename'] . ' ');
        }

        show_json(1, array('url' => referer()));
    }

    public function ajax()
    {

        global $_W;
        global $_GPC;

        $organization_id = $_GPC['organization_id'];

        if ($organization_id) {

            $_result = $this->_virtualarchitectureModel->queryForUsersAjax(array(
                "organizationId" => $organization_id
            ), 1, 999);

            $virtuals = $_result['data'];

            show_json(1, $virtuals);
        }
    }
}


