<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Favorite_SuperdeskShopV2Page extends MobileLoginPage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        $merch_plugin = p('merch');
        $merch_data   = m('common')->getPluginset('merch');

        if ($merch_plugin && $merch_data['is_openmerch']) {

            include $this->template('merch/member/favorite');
            return NULL;
        }

        include $this->template();
    }

    public function get_list()
    {
        global $_W;
        global $_GPC;

        $merch_plugin = p('merch');
        $merch_data   = m('common')->getPluginset('merch');

        $pindex = max(1, intval($_GPC['page']));
        $psize  = 10;

        $condition =
            ' and f.uniacid = :uniacid ' .
            ' and f.openid=:openid ' .
            ' and f.core_user=:core_user ' .
            ' and f.deleted=0';

        if ($merch_plugin && $merch_data['is_openmerch']) {
            $condition =
                ' and f.uniacid = :uniacid ' .
                ' and f.openid=:openid ' .
                ' and f.core_user=:core_user ' .
                ' and f.deleted=0 ' .
                ' and f.type=0';
        }

        //2018年12月18日 15:04:44 zjh 微信端的失效商品展示
        //' AND g.`status` > 0 and g.`checked`=0 and g.`total`>0 and g.`deleted`=0';// 出售中
        if ($_GPC['status'] == -2) {
            $condition .= ' AND (g.`status` <= 0 or g.`checked` != 0 or g.`total` = 0 or g.`deleted` = 1) '; //有效商品
        } else {
            $condition .= ' AND g.`status` > 0 and g.`checked`=0 and g.`total`>0 and g.`deleted`=0 '; //有效商品
        }

        $params = array(
            ':uniacid'   => $_W['uniacid'],
            ':openid'    => $_W['openid'],
            ':core_user' => $_W['core_user'],
        );

        $sql    =
            'SELECT ' .
            '       COUNT(*) ' .
            ' FROM ' . tablename('superdesk_shop_member_favorite') . ' f ' . // TODO 标志 楼宇之窗 openid superdesk_shop_member_favorite 已处理
            '       left join ' . tablename('superdesk_shop_goods') . ' g on f.goodsid = g.id ' .
            ' where 1 ' .
            $condition;

        $total  = pdo_fetchcolumn($sql, $params);

        $list       = array();
        $error_list = array();

        if (!empty($total)) {
            $sql =
                ' SELECT f.id,f.goodsid,g.title,g.thumb,g.marketprice,g.productprice,g.merchid,g.status,g.checked,g.total,g.deleted,g.tcate,g.costprice ' .
                ' FROM ' . tablename('superdesk_shop_member_favorite') . ' f ' . // TODO 标志 楼宇之窗 openid superdesk_shop_member_favorite 已处理
                '   left join ' . tablename('superdesk_shop_goods') . ' g on f.goodsid = g.id ' .
                ' where 1 ' .
                $condition .
                ' ORDER BY `id` DESC ' .
                ' LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize;

            $list = pdo_fetchall($sql, $params);
            $list = set_medias($list, 'thumb');

            if (!empty($list) && $merch_plugin && $merch_data['is_openmerch']) {

                $merch_user = $merch_plugin->getListUser($list, 'merch_user');

                foreach ($list as &$row) {
                    $row['merchname'] = ($merch_user[$row['merchid']]['merchname'] ? $merch_user[$row['merchid']]['merchname'] : $_W['shopset']['shop']['name']);
                }
                unset($row);
            }

            //2019年5月24日 16:48:51 zjh 文礼 价套
            $list = m('goods')->getGoodsCategoryEnterpriseDiscount($list);
        }

        show_json(1, array(
            'list'       => $list,
            'total'      => $total,
            'pagesize'   => $psize,
            'error_list' => $error_list
        ));

    }

    public function toggle()
    {
        global $_W;
        global $_GPC;

        $id         = intval($_GPC['id']);
        $isfavorite = intval($_GPC['isfavorite']);

        $goods = pdo_fetch(
            'select ' .
            '       * ' .
            ' from ' . tablename('superdesk_shop_goods') .
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':id'      => $id,
                ':uniacid' => $_W['uniacid']
            )
        );

        if (empty($goods)) {
            show_json(0, '商品未找到');
        }

        $data = pdo_fetch(
            'select ' .
            '       id,deleted ' .
            ' from ' . tablename('superdesk_shop_member_favorite') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_favorite 已处理
            ' where ' .
            '       goodsid=:id ' .
            '       and uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            ' limit 1',
            array(
                ':id'        => $id,
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user'],
            )
        );

        if (empty($data)) {

            if (!empty($isfavorite)) {

                $data = array(
                    'goodsid'    => $id,
                    'uniacid'    => $_W['uniacid'],
                    'openid'     => $_W['openid'],
                    'core_user'  => $_W['core_user'],
                    'createtime' => time()
                );
                pdo_insert(
                    'superdesk_shop_member_favorite',
                    $data
                ); // TODO 标志 楼宇之窗 openid superdesk_shop_member_favorite 已处理
            }
        } else {

            pdo_update(
                'superdesk_shop_member_favorite',  // TODO 标志 楼宇之窗 openid superdesk_shop_member_favorite 不处理
                array(
                    'deleted' => ($isfavorite ? 0 : 1)
                ),
                array(
                    'id'      => $data['id'],
                    'uniacid' => $_W['uniacid']
                )
            );
        }

        show_json(1, array(
            'isfavorite' => $isfavorite == 1
        ));
    }

    public function remove()
    {
        global $_W;
        global $_GPC;

        $ids = $_GPC['ids'];

        if (empty($ids) || !is_array($ids)) {
            show_json(0, '参数错误');
        }

        $sql =
            'update ' . tablename('superdesk_shop_member_favorite') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_favorite 已处理
            ' set ' .
            '       deleted=1 ' .
            ' where ' .
            '       openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and id in (' . implode(',', $ids) . ')';

        pdo_query(
            $sql,
            array(
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user'],
            )
        );

        show_json(1);
    }
}