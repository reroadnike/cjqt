<?php

class Favorite_SuperdeskShopV2Page extends PluginMobileLoginPage
{
    public function get_list()
    {
        global $_W;
        global $_GPC;

        $pindex = max(1, intval($_GPC['page']));
        $psize  = 10;

        $condition =
            ' and f.uniacid = :uniacid ' .
            ' and f.openid=:openid ' .
            ' and f.core_user=:core_user ' .
            ' and f.deleted=0 ' .
            ' and f.type=1';

        $params = array(
            ':uniacid'   => $_W['uniacid'],
            ':openid'    => $_W['openid'],
            ':core_user' => $_W['core_user'],
        );

        $sql   =
            'SELECT ' .
            '       COUNT(*) ' .
            ' FROM ' . tablename('superdesk_shop_member_favorite') . ' f ' . // TODO 标志 楼宇之窗 openid superdesk_shop_member_favorite 已处理
            ' where 1 ' .
            $condition;
        $total = pdo_fetchcolumn($sql, $params);
        $list  = array();

        if (!empty($total)) {
            $sql  =
                'SELECT ' .
                '       f.id,f.enterprise_id,g.enterprise_name,g.logo,g.desc ' .
                ' FROM ' . tablename('superdesk_shop_member_favorite') . ' f ' . // TODO 标志 楼宇之窗 openid superdesk_shop_member_favorite 已处理
                '       left join ' . tablename('superdesk_shop_enterprise_user') . ' g on f.enterprise_id = g.id ' .
                ' where 1 ' .
                $condition .
                ' ORDER BY `id` DESC ' .
                ' LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize;
            $list = pdo_fetchall($sql, $params);
            $list = set_medias($list, 'logo');

            $enterprise_plugin = p('enterprise');
            $enterprise_data   = m('common')->getPluginset('enterprise');

            if (!empty($list) && $enterprise_plugin && $enterprise_data['is_openenterprise']) {
                $enterprise_user = pdo_fetchall(
                    'select ' .
                    '       id,enterprise_name ' .
                    ' from ' . tablename('superdesk_shop_enterprise_user') .
                    ' where id in(' . implode(',', array_unique(array_column($list, 'enterprise_id'))) . ')',
                    array(),
                    'id'
                );

                foreach ($list as &$row) {
                    $row['enterprise_name'] = ($enterprise_user[$row['enterprise_id']]['enterprise_name'] ? $enterprise_user[$row['enterprise_id']]['enterprise_name'] : $_W['shopset']['shop']['name']);
                }

                unset($row);
            }

        }


        show_json(1, array('list' => $list, 'total' => $total, 'pagesize' => $psize));
    }

    public function toggle()
    {
        global $_W;
        global $_GPC;

        $id         = intval($_GPC['id']);
        $isfavorite = intval($_GPC['isfavorite']);

        $goods = pdo_fetch('select * from ' . tablename('superdesk_shop_goods') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $id, ':uniacid' => $_W['uniacid']));

        if (empty($goods)) {
            show_json(0, '商品未找到');
        }


        $data = pdo_fetch(
            'select ' .
            '       id,deleted ' .
            ' from ' . tablename('superdesk_shop_member_favorite') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_favorite 已处理
            ' where ' .
            '       uniacid=:uniacid ' .
            '       and enterprise_id=:id ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            ' limit 1',
            array(
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user'],
                ':id'        => $id
            )
        );

        if (empty($data)) {
            if (!empty($isfavorite)) {
                $data = array(
                    'uniacid'       => $_W['uniacid'],
                    'enterprise_id' => $id,
                    'openid'        => $_W['openid'],
                    'core_user'     => $_W['core_user'],
                    'createtime'    => time()
                );
                pdo_insert('superdesk_shop_member_favorite', $data); // TODO 标志 楼宇之窗 openid superdesk_shop_member_favorite 已处理
            }

        } else {
            pdo_update(
                'superdesk_shop_member_favorite', // TODO 标志 楼宇之窗 openid superdesk_shop_member_favorite 不处理
                array(
                    'deleted' => ($isfavorite ? 0 : 1)
                ),
                array(
                    'id'      => $data['id'],
                    'uniacid' => $_W['uniacid']
                )
            );
        }

        show_json(1, array('isfavorite' => $isfavorite == 1));
    }
}


?>