<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/member/member_address.class.php');


class Address_SuperdeskShopV2Page extends MobileLoginPage
{

    private $_member_addressModel;

    public function __construct()
    {
        parent::__construct();
        $this->_member_addressModel = new member_addressModel();
    }

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
            ':core_user' => $_W['core_user']
        );

        $sql   =
            'SELECT ' .
            '       COUNT(*) ' .
            ' FROM ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
            ' where 1 ' .
            $condition;
        $total = pdo_fetchcolumn($sql, $params);

        $sql =
            ' SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
            ' where 1 ' .
            $condition .
            ' ORDER BY `id` DESC ' .
            ' LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize;

        $list = pdo_fetchall($sql, $params);

        include $this->template();
    }

    public function post()
    {
        global $_W;
        global $_GPC;

        $id      = intval($_GPC['id']);
        $address = pdo_fetch(
            ' select ' .
            '       * ' .
            ' from ' . tablename('superdesk_shop_member_address') .// TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
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
                ':core_user' => $_W['core_user']
            )
        );

        include $this->template();
    }

    public function setdefault()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        $data = pdo_fetch(
            ' select ' .
            '       id ' .
            ' from ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
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

        pdo_update('superdesk_shop_member_address', // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
            array(
                'isdefault' => 0
            ),
            array(
                'uniacid'   => $_W['uniacid'],
                'openid'    => $_W['openid'],
                'core_user' => $_W['core_user']
            )
        );

        pdo_update('superdesk_shop_member_address', // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
            array(
                'isdefault' => 1
            ),
            array(
                'id'        => $id,
                'uniacid'   => $_W['uniacid'],
                'openid'    => $_W['openid'],
                'core_user' => $_W['core_user']
            )
        );

        show_json(1);
    }


    /**
     * 新建与编辑地址
     */
    public function submit()
    {
        global $_W;
        global $_GPC;

        $id             = intval($_GPC['id']);
        $data           = $_GPC['addressdata'];
        $data['mobile'] = trim($data['mobile']);

        $areas            = explode(' ', trim($data['areas']));// 中文 广东 中山市 城区
        $data['province'] = $areas[0];
        $data['city']     = $areas[1];
        $data['area']     = $areas[2];

        $data['jd_vop_province_code'] = intval($_GPC['jd_vop_area']['province'], 0);//京东一级province_code
        $data['jd_vop_city_code']     = intval($_GPC['jd_vop_area']['city'], 0);//京东二级city_code
//        $data['jd_vop_county_code']   = intval($_GPC['jd_vop_area']['district'], 0);//京东三级county_code
//        $data['jd_vop_town_code']     = intval($_GPC['jd_vop_area']['county'], 0);//京东四级town_code
        $data['jd_vop_county_code'] = intval($_GPC['jd_vop_area']['area'], 0);//京东三级county_code
        $data['jd_vop_town_code']   = intval($_GPC['jd_vop_area']['town'], 0);//京东四级town_code

        if (sizeof($areas) == 4) {
            $data['town'] = $areas[3];
//            $data['jd_vop_town_code']     = $_areaModel->getCodeByText($areas[3]);//京东四级town_code
        } else {
            $data['town'] = "";
//            $data['jd_vop_town_code']     = 0;//京东四级town_code
        }

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


        unset($data['areas']);

        $data['uniacid']   = $_W['uniacid'];
        $data['openid']    = $_W['openid'];
        $data['core_user'] = $_W['core_user'];

        if (empty($id)) {
            $address_count = pdo_fetchcolumn(
                ' SELECT ' .
                '       count(*) ' .
                ' FROM ' . tablename('superdesk_shop_member_address') .// TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
                ' where ' .
                '       openid=:openid ' .
                '       and core_user=:core_user ' .
                '       and deleted=0 ' .
                '       and uniacid = :uniacid ',
                array(
                    ':uniacid'   => $_W['uniacid'],
                    ':openid'    => $_W['openid'],
                    ':core_user' => $_W['core_user']
                )
            );

            if ($address_count <= 0) {
                $data['isdefault'] = 1;
            }

            $address_default = $this->_member_addressModel->getByDefault($data['uniacid'], $data['openid'], $data['core_user']);
            if (!$address_default) {
                $data['isdefault'] = 1;
            }

            pdo_insert('superdesk_shop_member_address', $data); // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理

            $id = pdo_insertid();

        } else {

            pdo_update(
                'superdesk_shop_member_address',// TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
                $data,
                array(
                    'id'        => $id,
                    'uniacid'   => $_W['uniacid'],
                    'openid'    => $_W['openid'],
                    'core_user' => $_W['core_user'],
                )
            );
        }
        show_json(1, array('addressid' => $id));
    }

    public function delete()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        $data = pdo_fetch(
            ' select ' .
            '       id,isdefault ' .
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
            'superdesk_shop_member_address', // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 不处理
            array(
                'deleted' => 1
            ),
            array(
                'id' => $id
            )
        );

        if ($data['isdefault'] == 1) {

            pdo_update(
                'superdesk_shop_member_address',// TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
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
                'select ' .
                '       id ' .
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
                    ':core_user' => $_W['core_user']
                )
            );

            if (!empty($data2)) {
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

                show_json(1, array('defaultid' => $data2['id']));
            }
        }
        show_json(1);
    }

    public function selector()
    {
        global $_W;
        global $_GPC;

        $condition =
            ' and openid=:openid ' .
            ' and core_user=:core_user ' .
            ' and deleted=0 ' .
            ' and uniacid = :uniacid ';

        $params = array(
            ':uniacid'   => $_W['uniacid'],
            ':openid'    => $_W['openid'],
            ':core_user' => $_W['core_user']
        );

        $sql =
            ' SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
            ' where 1 ' .
            $condition .
            ' ORDER BY isdefault desc, id DESC ';

        $list = pdo_fetchall($sql, $params);

        include $this->template();

        exit();
    }

    /**
     * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=member.address.foxUICityData
     * static/js/dist/foxui/js/foxui.citydata.min.js
     * 改成动态的 {"code":"code",text":"北京市","children":[]}
     *
     */
    public function foxUICityData()
    {

        global $_W;
        global $_GPC;

        include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/area.class.php');

        $_areaModel = new areaModel();

        $js_array = $_areaModel->foxUICityData();

        include $this->template();

        exit();
    }
}