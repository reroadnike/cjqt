<?php

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/accessControl.php";

if (!(defined("IN_IA"))) {
    exit("Access Denied");
}
require SUPERDESK_SHOPV2_PLUGIN . "pc/core/page_login_mobile.php";

class Address_SuperdeskShopV2Page extends PcMobileLoginPage
{
    public function main()
    {
        global $_W;
        global $_GPC;
        global $_S;

        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;

        $condition =
            ' and openid=:openid ' .
            ' and core_user=:core_user ' .
            ' and deleted=0 ' .
            ' and uniacid = :uniacid ';

        $params = array(
            ':uniacid'   => $_W['uniacid'],
            ':openid'    => $_W['openid'],
            ':core_user' => $_W['core_user'],
        );

        $sql   =
            'SELECT COUNT(*) ' .
            ' FROM ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
            ' where 1 ' .
            $condition;
        $total = pdo_fetchcolumn($sql, $params);

        $sql =
            'SELECT id,isdefault,realname,mobile,province,city,area,town,address ' .
            ' FROM ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
            ' where 1 ' .
            $condition .
            ' ORDER BY `isdefault` DESC,`id` DESC ' .
            ' LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize;

        $list = pdo_fetchall($sql, $params);

        $nav_link_list = array(
            array('link' => mobileUrl('pc'), 'title' => '首页'),
            array('link' => mobileUrl('pc.member'), 'title' => '我的商城'),
            array('title' => '收货地址')
        );

        $ice_menu_array = array(array(
            'menu_key'  => 'index',
            'menu_name' => '收货地址',
            'menu_url'  => mobileUrl('pc.member.address')
        ));

        show_json(1, compact('list','total'));
    }

    public function setdefault()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        $data = pdo_fetch(
            'select id ' .
            ' from ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 不处理
            ' where ' .
            '       id=:id ' .
            '       and deleted=0 ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':uniacid' => $_W['uniacid'],
                ':id'      => $id
            )
        );

        if (empty($data)) {
            show_json(0, '地址未找到');
        }

        pdo_update(
            "superdesk_shop_member_address", // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
            array(
                "isdefault" => 0
            ),
            array(
                "uniacid"   => $_W['uniacid'],
                'openid'    => $_W['openid'],
                'core_user' => $_W['core_user'],
            )
        );

        pdo_update(
            "superdesk_shop_member_address", // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
            array(
                "isdefault" => 1
            ),
            array(
                "id"        => $id,
                'uniacid'   => $_W['uniacid'],
                'openid'    => $_W['openid'],
                'core_user' => $_W['core_user'],
            )
        );
        show_json(1);
    }

    public function delete()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        $data = pdo_fetch(
            'select id,isdefault ' .
            ' from ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
            ' where ' .
            '       id=:id ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and deleted=0 ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user'],
                ':id'        => $id
            )
        );
        if (empty($data)) {
            show_json(0, '地址未找到');
        }

        pdo_update(
            "superdesk_shop_member_address", // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 不处理
            array(
                "deleted" => 1
            ),
            array(
                "id" => $id
            )
        );

        if ($data['isdefault'] == 1) {

            pdo_update(
                'superdesk_shop_member_address', // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
                array(
                    'isdefault' => 0
                ),
                array(
                    'uniacid'   => $_W['uniacid'],
                    'openid'    => $_W['openid'],
                    'core_user' => $_W['core_user'],
                    'id'        => $id
                )
            );

            $data2 = pdo_fetch(
                'select id ' .
                ' from ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
                ' where ' .
                '       openid=:openid ' .
                '       and core_user=:core_user ' .
                '       and deleted=0 ' .
                '       and uniacid=:uniacid ' .
                ' order by id desc ' .
                ' limit 1',
                array(
                    ':uniacid'   => $_W['uniacid'],
                    ':openid'    => $_W['openid'],
                    ':core_user' => $_W['core_user'],
                )
            );

            if (!(empty($data2))) {
                pdo_update(
                    'superdesk_shop_member_address', // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
                    array(
                        'isdefault' => 1
                    ),
                    array(
                        'uniacid'   => $_W['uniacid'],
                        'openid'    => $_W['openid'],
                        'core_user' => $_W['core_user'],
                        'id'        => $data2['id']
                    )
                );

                show_json(1, array("defaultid" => $data2['id']));
            }
        }

        show_json(1);
    }

    public function detail()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        $address = pdo_fetch(
            'select * ' .
            'from ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
            ' where ' .
            '       id=:id ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and uniacid=:uniacid ' .
            ' limit 1 ',
            array(
                ':id'        => $id,
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user'],
            )
        );

        $nav_link_list = array(
//            array('link' => SHOP_SITE_URL, 'title' => '首页'),
//            array('link' => SHOP_SITE_URL . '&act=member&op=home', 'title' => '我的商城'),
            array('title' => '我的收藏')
        );

        if (empty($address)) {
            show_json(0);
        }

        show_json(1, $address);
    }

    public function submit()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);
        //$data = $_GPC['addressdata'];
        $data['realname']             = trim($_GPC['realname']);
        $data['mobile']               = trim($_GPC['mobile']);
        $data['province']             = $_GPC['province'];
        $data['city']                 = $_GPC['city'];
        $data['area']                 = $_GPC['area'];
        $data['town']                 = $_GPC['town'];
        $data['jd_vop_province_code'] = intval($_GPC['jd_vop_province_code'], 0);
        $data['jd_vop_city_code']     = intval($_GPC['jd_vop_city_code'], 0);
        $data['jd_vop_county_code']   = intval($_GPC['jd_vop_county_code'], 0);
        $data['jd_vop_town_code']     = intval($_GPC['jd_vop_town_code'], 0);
        $data['address']              = $_GPC['address'];

        $data['uniacid']   = $_W['uniacid'];
        $data['openid']    = $_W['openid'];
        $data['core_user'] = $_W['core_user'];

        // TODO check area

        include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/AreaService.class.php');
        $_areaService = new AreaService();

        $result = $_areaService->checkArea(
            $data['jd_vop_province_code'],
            $data['jd_vop_city_code'],
            $data['jd_vop_county_code'],
            $data['jd_vop_town_code']
        );


        //用于查库存与下单 格式：1_0_0(分别代表1、2、3级地址)
        $data['jd_vop_area'] = $data['jd_vop_province_code'] . "_" .
            $data['jd_vop_city_code'] . "_" .
            $data['jd_vop_county_code'] . "_" .
            $data['jd_vop_town_code'];

        if ($result['success'] == false) {

            show_json(0, '地址非法，请重新选择');//('.$data['jd_vop_area'].')
        }

        if (empty($id)) {

            $addresscount = pdo_fetchcolumn(
                'SELECT count(*) ' .
                ' FROM ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
                ' where ' .
                '       openid=:openid ' .
                '       and core_user=:core_user ' .
                '       and deleted=0 ' .
                '       and uniacid = :uniacid ',
                array(
                    ':uniacid'   => $_W['uniacid'],
                    ':openid'    => $_W['openid'],
                    ':core_user' => $_W['core_user'],
                )
            );

            if ($addresscount <= 0) {
                $data['isdefault'] = 1;
            }

            pdo_insert("superdesk_shop_member_address", $data); // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理

            $id = pdo_insertid();

        } else {

            pdo_update(
                "superdesk_shop_member_address", // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
                $data,
                array(
                    'id'        => $id,
                    'uniacid'   => $_W['uniacid'],
                    'openid'    => $_W['openid'],
                    'core_user' => $_W['core_user'],
                )
            );
        }

        show_json(1, array("addressid" => $id));
    }

    public function selector()
    {
        global $_W;
        global $_GPC;

        $condition =
            ' and openid=:openid ' .
            ' and core_user=:core_user ' .
            ' and deleted=0 ' .
            ' and uniacid = :uniacid  ';

        $params = array(
            ':uniacid'   => $_W['uniacid'],
            ':openid'    => $_W['openid'],
            ':core_user' => $_W['core_user'],
        );

        $sql =
            'SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
            ' where 1 ' .
            $condition .
            ' ORDER BY isdefault desc, id DESC ';

        $list = pdo_fetchall($sql, $params);

        show_json(1, $list);
    }

    public function allCityData()
    {
        global $_W;
        global $_GPC;

        $parent_code = $_GPC['parent_code'] ? intval($_GPC['parent_code']) : 0;

        include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/AreaService.class.php');
        $_areaService = new AreaService();

        $_areaService->jdVopAreaCascadeClearCache($parent_code);
        $js = $_areaService->jdVopAreaCascade($parent_code);

        show_json(1, json_decode($js, true));
    }
}