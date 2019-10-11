<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

require SUPERDESK_SHOPV2_PLUGIN . 'enterprise/core/inc/page_enterprise.php';

class Import_address_SuperdeskShopV2Page extends EnterpriseWebPage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        //进来时清空缓存
        cache_delete('enterprise:member_import_address_' . $_W['uniacid'] . '_' . $_W['enterprise_id'] . '_' . $_W['uniaccount']['id']);
        cache_delete('enterprise:member_import_address_name_' . $_W['uniacid'] . '_' . $_W['enterprise_id'] . '_' . $_W['uniaccount']['id']);
        $this->cacheImportError(2);

        if ($_W['ispost']) {
            $filename = time() . 'rd' . rand(1000, 9999) . 'id' . $_W['uniacid'] . $_W['enterprise_id'];
            $rows     = m('excel')->importByPath('excelfile', 'enterprise', 'address', $filename);

            //2018年9月14日 16:10:32 zjh 可能会算入空白行..判断一下..
            $rows_check = array();
            foreach ($rows as $v) {
                if (!empty($v[0])) {
                    $rows_check[] = $v;
                }
            }
            $rows = $rows_check;

            $num = count($rows);
            if ($num > 50) {
                show_json(0, '最大只能导入50条');
            }

            foreach ($rows as $k => &$v) {
                $price    = number_format(floatval($v[2]), 2);
                $v['css'] = false;
                if ($price < 0) {
                    $v['css'] = true;
                }
            }
            unset($v);

            cache_write('enterprise:member_import_address_' . $_W['uniacid'] . '_' . $_W['enterprise_id'] . '_' . $_W['uniaccount']['id'], $rows);
            cache_write('enterprise:member_import_address_name_' . $_W['uniacid'] . '_' . $_W['enterprise_id'] . '_' . $_W['uniaccount']['id'], $filename);
            show_json(1, $rows);
        }

        include $this->template();
    }

    /**
     * 这是适应延迟异步一个个更新的方法
     */
    public function submitOne()
    {
        global $_W, $_GPC;

        $type     = $_GPC['type'];
        $filename = cache_load('enterprise:member_import_address_name_' . $_W['uniacid'] . '_' . $_W['enterprise_id'] . '_' . $_W['uniaccount']['id']);

        if ($type == 1) {
            //Excel导入整体数据
            $keys = $_GPC['keys'];
            $rows = cache_load('enterprise:member_import_address_' . $_W['uniacid'] . '_' . $_W['enterprise_id'] . '_' . $_W['uniaccount']['id']);
            //$num = count($rows);

            //当遍历更新到最后一个的时候。删除掉cache
//			if($keys >= $num-1){
//				cache_delete('enterprise:member_import_address_' . $_W['uniacid'] . '_' . $_W['enterprise_id'] . '_' . $_W['uniaccount']['id']);
//				cache_delete('enterprise:member_import_address_name_' . $_W['uniacid'] . '_' . $_W['enterprise_id'] . '_' . $_W['uniaccount']['id']);
//			}

            $mobile      = trim($rows[$keys][0]);
            $ausername   = trim($rows[$keys][1]);
            $amobile     = trim($rows[$keys][2]);
            $province    = trim($rows[$keys][3]);
            $province_id = trim($rows[$keys][4]);
            $city        = trim($rows[$keys][5]);
            $city_id     = trim($rows[$keys][6]);
            $area        = trim($rows[$keys][7]);
            $area_id     = trim($rows[$keys][8]);
            $town        = trim($rows[$keys][9]);
            $town_id     = trim($rows[$keys][10]);
            $address     = trim($rows[$keys][11]);
        } else if ($type == 2) {
            //单条数据修改后提交
            $mobile      = $_GPC['mobile'];
            $ausername   = $_GPC['ausername'];
            $amobile     = $_GPC['amobile'];
            $province    = $_GPC['province'];
            $province_id = $_GPC['province_id'];
            $city        = $_GPC['city'];
            $city_id     = $_GPC['city_id'];
            $area        = $_GPC['area'];
            $area_id     = $_GPC['area_id'];
            $town        = $_GPC['town'];
            $town_id     = $_GPC['town_id'];
            $address     = $_GPC['address'];

            $error_key = 'e' . $mobile;

            $error = $this->cacheImportError();
            $data  = $error[$error_key];

            if (empty($data)) {
                show_json(0, '请先通过Excel提交');
            }
        } else {
            show_json(0, '别乱来！');
        }

        if (empty($mobile)) {
            show_json(0, '手机号不能为空');
        }

        $openid = m('member')->getOpenidByMobileAndEnterprise($mobile, $_W['enterprise_id']);

        $create_user = 0;
        if (empty($openid)) {
            $openid      = m('member')->createMemberByNoWechat($mobile, $ausername, $ausername, $_W['enterprise_id']);
            $create_user = 1;
        }

        pdo_insert('superdesk_shop_member_address', // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 待处理
            array(
                'uniacid'              => $_W['uniacid'],
                'openid'               => $openid,
                'realname'             => $ausername,
                'mobile'               => $amobile,
                'province'             => $province,
                'city'                 => $city,
                'area'                 => $area,
                'town'                 => $town,
                'address'              => $address,
                'jd_vop_province_code' => $province_id,
                'jd_vop_city_code'     => $city_id,
                'jd_vop_county_code'   => $area_id,
                'jd_vop_town_code'     => $town_id,
                'jd_vop_area'          => $province_id . '_' . $city_id . '_' . $area_id . ($town_id ? '_' . $town_id : ''),
                'createtime'           => time()
            )
        );

        $id = pdo_insertid();

        if (empty($id)) {
            $this->cacheImportError(3, $mobile,
                array(
                    'realname' => $ausername,
                    'mobile'   => $amobile
                )
            );

            show_json(0, '插入失败');
        }

        //插入记录表
        pdo_insert('superdesk_shop_enterprise_import_address_log',
            array(
                'uniacid'              => $_W['uniacid'],
                'enterprise_id'        => $_W['enterprise_id'],
                'openid'               => $openid,
                'realname'             => $ausername,
                'mobile'               => $amobile,
                'province'             => $province,
                'city'                 => $city,
                'area'                 => $area,
                'town'                 => $town,
                'address'              => $address,
                'jd_vop_province_code' => $province_id,
                'jd_vop_city_code'     => $city_id,
                'jd_vop_county_code'   => $area_id,
                'jd_vop_town_code'     => $town_id,
                'jd_vop_area'          => $province_id . '_' . $city_id . '_' . $area_id . ($town_id ? '_' . $town_id : ''),
                'createtime'           => time(),
                'import_sn'            => $filename,
                'account_id'           => $_W['uniaccount']['id'],
                'create_user'          => $create_user
            )
        );

        //单条数据修改提交状况
        //充值成功
        //删除该条数据缓存
        if ($type == 2) {
            $this->cacheImportError(2, $mobile);
        }

        show_json(1);
    }

    /**
     * 需要导入的数据member->mobile,address->name,mobile,province,city,area,town,address
     * 需要填写固定值的数据 除了第四级都是必填
     */
    public function importTpl()
    {
        $columns   = array();
        $columns[] = array('title' => '用户', 'field' => '', 'width' => 18);
        $columns[] = array('title' => '收件人', 'field' => '', 'width' => 12);
        $columns[] = array('title' => '收件人电话', 'field' => '', 'width' => 18);
        $columns[] = array('title' => '一级地址', 'field' => '', 'width' => 12);
        $columns[] = array('title' => '一级地址ID', 'field' => '', 'width' => 12);
        $columns[] = array('title' => '二级地址', 'field' => '', 'width' => 12);
        $columns[] = array('title' => '二级地址ID', 'field' => '', 'width' => 12);
        $columns[] = array('title' => '三级地址', 'field' => '', 'width' => 12);
        $columns[] = array('title' => '三级地址ID', 'field' => '', 'width' => 12);
        $columns[] = array('title' => '四级地址', 'field' => '', 'width' => 12);
        $columns[] = array('title' => '四级地址ID', 'field' => '', 'width' => 12);
        $columns[] = array('title' => '详细地址', 'field' => '', 'width' => 60);
        m('excel')->temp('批量导入员工数据模板', $columns);
    }

    /**
     * @param       $type      1:获取，2:删除,3:写入
     * @param       $mobile    手机号 作为键
     * @param array $data      值 目前只有nickname,realname 以数组形式存入cache
     *
     */
    private function cacheImportError($type = 1, $mobile = null, $data = array())
    {
        global $_W;

        $error     = cache_load('enterprise:member_import_address_error_' . $_W['uniacid'] . '_' . $_W['enterprise_id'] . '_' . $_W['uniaccount']['id']);
        $error_key = 'e' . $mobile;
        if ($type == 1) {
            return $error;
        } else if ($type == 2) {
            if (empty($mobile)) {
                cache_delete('enterprise:member_import_address_error_' . $_W['uniacid'] . '_' . $_W['enterprise_id'] . '_' . $_W['uniaccount']['id']);
                return true;
            }

            if (isset($error[$error_key])) {
                unset($error[$error_key]);
            }

            if (!empty($error)) {
                cache_write('enterprise:member_import_address_error_' . $_W['uniacid'] . '_' . $_W['enterprise_id'] . '_' . $_W['uniaccount']['id'], $error);
            } else {
                cache_delete('enterprise:member_import_address_error_' . $_W['uniacid'] . '_' . $_W['enterprise_id'] . '_' . $_W['uniaccount']['id']);
            }
        } else if ($type == 3) {
            $error = !empty($error) ? $error : array();

            $error[$error_key] = $data;
            cache_write('enterprise:member_import_address_error_' . $_W['uniacid'] . '_' . $_W['enterprise_id'] . '_' . $_W['uniaccount']['id'], $error);
        }
    }
}


?>