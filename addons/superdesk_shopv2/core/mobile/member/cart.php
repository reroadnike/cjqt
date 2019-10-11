<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Cart_SuperdeskShopV2Page extends MobileLoginPage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        $condition =
            ' and f.uniacid= :uniacid ' .
            ' and f.openid=:openid ' .
            ' and f.core_user=:core_user ' .
            ' and f.deleted=0';
        $params    = array(
            ':uniacid'   => $_W['uniacid'],
            ':openid'    => $_W['openid'],
            ':core_user' => $_W['core_user'],
        );


        $list       = array();
        $total      = 0;
        $totalprice = 0;
        $ischeckall = true;
        $level      = m('member')->getLevel($_W['openid'],$_W['core_user']);
        $sql        =
            'SELECT ' .
            ' f.id,f.total,f.goodsid,f.selected,f.optionid,f.merchid, ' .
            ' g.total as stock, g.productprice, g.maxbuy,g.title,g.thumb,g.minbuy,g.maxbuy,g.unit,g.checked, ' .
            ' g.isdiscount_discounts,g.isdiscount,g.isdiscount_time,g.isnodiscount,g.discounts,g.merchsale,g.tcate,ifnull(o.costprice, g.costprice) as costprice, ' .
            ' ifnull(o.marketprice, g.marketprice) as marketprice,o.stock as optionstock,o.title as optiontitle,o.specs ' .
            'FROM ' . tablename('superdesk_shop_member_cart') . ' f ' . // TODO 标志 楼宇之窗 openid superdesk_shop_member_cart 已处理
            ' left join ' . tablename('superdesk_shop_goods') . ' g on f.goodsid = g.id ' .
            ' left join ' . tablename('superdesk_shop_goods_option') . ' o on f.optionid = o.id ' .
            ' where 1 ' .
            $condition .
            ' ORDER BY `id` DESC ';
        $list       = pdo_fetchall($sql, $params);


        foreach ($list as &$g) {

            if (!empty($g['optionid'])) {
                $g['stock'] = $g['optionstock'];
                if (!empty($g['specs'])) {
                    $thumb = m('goods')->getSpecThumb($g['specs']);
                    if (!empty($thumb)) {
                        $g['thumb'] = $thumb;
                    }
                }
            }
            $prices           = m('order')->getGoodsDiscountPrice($g, $level, 1);

            //2019年5月29日 14:33:31 zjh 价套
            $prices = m('order')->getGoodsCategoryEnterpriseDiscount($g,$prices, 1);
            $g['CEDiscountPrice'] = $prices['CEDiscountPrice'];

            $g['marketprice'] = $g['ggprice'] = $prices['price'];

            if ($g['selected']) {
                $totalprice       += $g['marketprice'] * $g['total'];
                $total            += $g['total'];
            }

            $totalmaxbuy = $g['stock'];
            if (0 < $g['maxbuy']) {
                if ($totalmaxbuy != -1) {
                    if ($g['maxbuy'] < $totalmaxbuy) {
                        $totalmaxbuy = $g['maxbuy'];
                    }
                } else {
                    $totalmaxbuy = $g['maxbuy'];
                }
            }

            if (0 < $g['usermaxbuy']) {

                $order_goodscount = pdo_fetchcolumn(
                    ' select ' .
                    '       ifnull(sum(og.total),0)  ' .
                    ' from ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 已处理
                    '       left join ' . tablename('superdesk_shop_order') . ' o on og.orderid=o.id ' .// TODO 标志 楼宇之窗 openid shop_order 已处理
                    ' where ' .
                    '       og.goodsid=:goodsid ' .
                    '       and o.status>=1 ' .
                    '       and o.openid=:openid ' .
                    '       and o.core_user=:core_user ' .
                    '       and og.uniacid=:uniacid ',
                    array(
                        ':goodsid'   => $g['goodsid'],
                        ':uniacid'   => $_W['uniacid'],
                        ':openid'    => $_W['openid'],
                        ':core_user' => $_W['core_user']
                    )
                );

                $last = $g['usermaxbuy'] - $order_goodscount;

                if ($last <= 0) {
                    $last = 0;
                }

                if ($totalmaxbuy != -1) {
                    if ($last < $totalmaxbuy) {
                        $totalmaxbuy = $last;
                    }
                } else {
                    $totalmaxbuy = $last;
                }
            }


            if (0 < $g['minbuy']) {
                if ($totalmaxbuy < $g['minbuy']) {
                    $g['minbuy'] = $totalmaxbuy;
                }
            }


            $g['totalmaxbuy'] = $totalmaxbuy;
            $g['unit']        = ((empty($data['unit']) ? '件' : $data['unit']));
            if (empty($g['selected'])) {
                $ischeckall = false;
            }
        }


        unset($g);

        $list = set_medias($list, 'thumb');

        $merch_plugin = p('merch');
        $merch_data   = m('common')->getPluginset('merch');

        if ($merch_plugin && $merch_data['is_openmerch']) {

            $getListUser = $merch_plugin->getListUser($list);
            $merch_user  = $getListUser['merch_user'];
            $merch       = $getListUser['merch'];

            include $this->template('merch/member/cart');
            exit();
        }

        include $this->template();
    }

    public function select()
    {
        global $_W;
        global $_GPC;

        $id     = intval($_GPC['id']);
        $select = intval($_GPC['select']);

        if (!empty($id)) {
            $data = pdo_fetch(
                'select ' .
                '       id,goodsid,optionid, total ' .
                ' from ' . tablename('superdesk_shop_member_cart') . ' ' . // TODO 标志 楼宇之窗 openid superdesk_shop_member_cart 已处理
                ' where ' .
                '       id=:id ' .
                '       and uniacid=:uniacid ' .
                '       and openid=:openid ' .
                '       and core_user=:core_user ' .
                ' limit 1 ',
                array(
                    ':id'        => $id,
                    ':uniacid'   => $_W['uniacid'],
                    ':openid'    => $_W['openid'],
                    ':core_user' => $_W['core_user'],
                )
            );
            if (!empty($data)) {
                pdo_update(
                    'superdesk_shop_member_cart', // TODO 标志 楼宇之窗 openid superdesk_shop_member_cart 不处理
                    array(
                        'selected' => $select
                    ),
                    array(
                        'id'      => $id,
                        'uniacid' => $_W['uniacid']
                    )
                );
            }
        } else {
            pdo_update(
                'superdesk_shop_member_cart', // TODO 标志 楼宇之窗 openid superdesk_shop_member_cart 已处理
                array(
                    'selected' => $select
                ),
                array(
                    'uniacid'   => $_W['uniacid'],
                    'openid'    => $_W['openid'],
                    'core_user' => $_W['core_user'],
                )
            );
        }
        show_json(1);
    }

    public function update()
    {
        global $_W;
        global $_GPC;

        $id         = intval($_GPC['id']);
        $goodstotal = intval($_GPC['total']);
        $optionid   = intval($_GPC['optionid']);

        empty($goodstotal) && ($goodstotal = 1);

        $data = pdo_fetch(
            ' select ' .
            '       id,goodsid,optionid, total ' .
            ' from ' . tablename('superdesk_shop_member_cart') . ' ' . // TODO 标志 楼宇之窗 openid superdesk_shop_member_cart 已处理
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            ' limit 1 ',
            array(
                ':id'        => $id,
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user'],
            )
        );

        if (empty($data)) {
            show_json(0, '无购物车记录');
        }

        $goods = pdo_fetch(
            ' select ' .
            '       id,maxbuy,minbuy,total,unit ' .
            ' from ' . tablename('superdesk_shop_goods') .
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            '       and status=1 ' .
            '       and deleted=0',
            array(
                ':id'      => $data['goodsid'],
                ':uniacid' => $_W['uniacid']
            )
        );

        if (empty($goods)) {
            show_json(0, '商品未找到或已经下架');
        }

        pdo_update(
            'superdesk_shop_member_cart', // TODO 标志 楼宇之窗 openid superdesk_shop_member_cart 已处理
            array(
                'total'    => $goodstotal,
                'optionid' => $optionid
            ),
            array(
                'id'        => $id,
                'uniacid'   => $_W['uniacid'],
                'openid'    => $_W['openid'],
                'core_user' => $_W['core_user'],
            )
        );

        show_json(1);
    }

    public function add()
    {
        global $_W;
        global $_GPC;

        $id    = intval($_GPC['id']);
        $total = intval($_GPC['total']);
        ($total <= 0) && ($total = 1);
        $optionid = intval($_GPC['optionid']);

        $goods = pdo_fetch(
            'select ' .
            '       id,marketprice,diyformid,diyformtype,diyfields, isverify, `type`,merchid, cannotrefund ' .
            ' from ' . tablename('superdesk_shop_goods') .
            ' where id=:id and uniacid=:uniacid limit 1',
            array(
                ':id'      => $id,
                ':uniacid' => $_W['uniacid']
            )
        );

        if (empty($goods)) {
            show_json(0, '商品未找到或已经下架');
        }

        $member = m('member')->getMember($_W['openid'], $_W['core_user']);

        if (!empty($_W['shopset']['wap']['open']) && !empty($_W['shopset']['wap']['mustbind']) && empty($member['mobileverify'])) {
            show_json(0, array('message' => '请先绑定手机', 'url' => mobileUrl('member/bind', NULL, true)));
        }

        if (($goods['isverify'] == 2) || ($goods['type'] == 2) || ($goods['type'] == 3) || !empty($goods['cannotrefund'])) {
            show_json(0, '此商品不可加入购物车<br>请直接点击立刻购买');
        }

        $giftid = intval($_GPC['giftid']);

        $gift = pdo_fetch(
            'select * ' .
            ' from ' . tablename('superdesk_shop_gift') .
            ' where uniacid = ' . $_W['uniacid'] .
            ' and id = ' . $giftid .
            ' and starttime >= ' . time() .
            ' and endtime <= ' . time() .
            ' and status = 1 '
        );

        $diyform_plugin = p('diyform');
        $diyformid      = 0;
        $diyformfields  = iserializer(array());
        $diyformdata    = iserializer(array());

        if ($diyform_plugin) {

            $diyformdata = $_GPC['diyformdata'];

            if (!empty($diyformdata) && is_array($diyformdata)) {

                $diyformfields = false;

                if ($goods['diyformtype'] == 1) {
                    $diyformid = intval($goods['diyformid']);
                    $formInfo  = $diyform_plugin->getDiyformInfo($diyformid);
                    if (!empty($formInfo)) {
                        $diyformfields = $formInfo['fields'];
                    }
                } else if ($goods['diyformtype'] == 2) {
                    $diyformfields = iunserializer($goods['diyfields']);
                }

                if (!empty($diyformfields)) {
                    $insert_data   = $diyform_plugin->getInsertData($diyformfields, $diyformdata);
                    $diyformdata   = $insert_data['data'];
                    $diyformfields = iserializer($diyformfields);
                }
            }
        }

        $data = pdo_fetch(
            'select ' .
            '       id,total,diyformid ' .
            ' from ' . tablename('superdesk_shop_member_cart') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_cart 已处理
            ' where ' .
            '       goodsid=:id ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and optionid=:optionid ' .
            '       and deleted=0 ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user'],
                ':optionid'  => $optionid,
                ':id'        => $id
            )
        );

        if (empty($data)) {

            $data = array(
                'uniacid'       => $_W['uniacid'],
                'openid'        => $_W['openid'],
                'core_user'     => $_W['core_user'],
                'goodsid'       => $id,
                'optionid'      => $optionid,
                'merchid'       => $goods['merchid'],
                'marketprice'   => $goods['marketprice'],
                'total'         => $total,
                'selected'      => 1,
                'diyformid'     => $diyformid,
                'diyformdata'   => $diyformdata,
                'diyformfields' => $diyformfields,
                'createtime'    => time()
            );

            pdo_insert('superdesk_shop_member_cart', $data); // TODO 标志 楼宇之窗 openid superdesk_shop_member_cart 已处理

        } else {

            $data['diyformid']     = $diyformid;
            $data['diyformdata']   = $diyformdata;
            $data['diyformfields'] = $diyformfields;
            $data['total']         += $total;

            pdo_update(
                'superdesk_shop_member_cart', // TODO 标志 楼宇之窗 openid superdesk_shop_member_cart 不处理
                $data,
                array(
                    'id' => $data['id']
                )
            );
        }

        $cartcount = pdo_fetchcolumn(
            'select ' .
            '       sum(total) ' .
            ' from ' . tablename('superdesk_shop_member_cart') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_cart 已处理
            ' where ' .
            '       openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and deleted=0 ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user'],
            )
        );

        show_json(1, array(
            'isnew'     => false,
            'cartcount' => $cartcount
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
            'update ' . tablename('superdesk_shop_member_cart') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_cart 已处理
            ' set ' .
            '       deleted=1 ' .
            ' where ' .
            '       uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and id in (' . implode(',', $ids) . ')';
        pdo_query(
            $sql, array(
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user'],
            )
        );

        show_json(1);
    }

    public function tofavorite()
    {
        global $_W;
        global $_GPC;

        $ids = $_GPC['ids'];

        if (empty($ids) || !is_array($ids)) {
            show_json(0, '参数错误');
        }

        foreach ($ids as $id) {

            $goodsid = pdo_fetchcolumn(
                'select ' .
                '       goodsid ' .
                ' from ' . tablename('superdesk_shop_member_cart') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_cart 已处理
                ' where ' .
                '       id=:id ' .
                '       and uniacid=:uniacid ' .
                '       and openid=:openid ' .
                '       and core_user=:core_user ' .
                ' limit 1 ',
                array(
                    ':id'        => $id,
                    ':uniacid'   => $_W['uniacid'],
                    ':openid'    => $_W['openid'],
                    ':core_user' => $_W['core_user']
                )
            );

            if (!empty($goodsid)) {

                $fav = pdo_fetchcolumn(
                    'select ' .
                    '       count(*) ' .
                    ' from ' . tablename('superdesk_shop_member_favorite') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_favorite 已处理
                    ' where ' .
                    '       goodsid=:goodsid ' .
                    '       and uniacid=:uniacid ' .
                    '       and openid=:openid ' .
                    '       and core_user=:core_user ' .
                    '       and deleted=0 ' .
                    ' limit 1 ',
                    array(
                        ':goodsid'   => $goodsid,
                        ':uniacid'   => $_W['uniacid'],
                        ':openid'    => $_W['openid'],
                        ':core_user' => $_W['core_user'],
                    )
                );

                if ($fav <= 0) {

                    $fav = array(
                        'goodsid'    => $goodsid,
                        'uniacid'    => $_W['uniacid'],
                        'openid'     => $_W['openid'],
                        'core_user'  => $_W['core_user'],
                        'deleted'    => 0,
                        'createtime' => time()
                    );

                    pdo_insert('superdesk_shop_member_favorite', $fav); // TODO 标志 楼宇之窗 openid superdesk_shop_member_favorite 已处理

                }
            }
        }

        $sql =
            'update ' . tablename('superdesk_shop_member_cart') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_cart 已处理
            ' set ' .
            '       deleted=1 ' .
            ' where ' .
            '       uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and id in (' . implode(',', $ids) . ')';
        pdo_query(
            $sql,
            array(
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user'],
            )
        );

        show_json(1);
    }
}