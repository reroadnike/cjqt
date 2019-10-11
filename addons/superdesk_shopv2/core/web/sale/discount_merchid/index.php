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
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/category_enterprise_discount.class.php');

class Index_SuperdeskShopV2Page extends WebPage
{

    private $_organizationModel;
    private $_virtualarchitectureModel;
    private $_enterprise_userModel;
    private $_category_enterprise_discountModel;

    public function __construct()
    {
        parent::__construct();

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

        $this->_category_enterprise_discountModel = new category_enterprise_discountModel();
    }

    public function main()
    {
        global $_W;
        global $_GPC;

        $condition = ' WHERE uniacid = :uniacid';
        $params = array(
            ':uniacid' => $_W['uniacid']
        );

        $sql =
            ' SELECT * FROM ' . tablename('superdesk_shop_discount_merchid') .
            $condition .
            ' ORDER BY discount_id DESC';

        $list = pdo_fetchall($sql,$params);

        include $this->template();
    }

    public function edit()
    {
        global $_GPC,$_W;
        $discount_id = intval($_GPC['discount_id']);

        if ($_W['ispost']) {

            $params = [
                'discount_name' => trim($_GPC['discount_name']),
                'status' => trim($_GPC['status']),
                'price_type' => intval($_GPC['price_type']) ? intval($_GPC['price_type']) : 1,
                'remark' => trim($_GPC['remark']),
                'uniacid' => $_W['uniacid'],
            ];
            //折扣
            $discount_merchid = [];
            $group = $_GPC['group'];
            if(is_array($group) && !empty($group)){
                foreach ($group as $gkey => $gval) {
                    if(isset($gval['checked']) && $gval['checked']){
                        $discount_merchid[] = [
                            'groupid' => $gkey,
                            'value' => $gval['discount'],
                        ];
                    }
                }
            }
            $merchid = $_GPC['merchid'];
            if(is_array($merchid) && !empty($merchid)){
                foreach ($merchid as $mkey => $mval) {
                    if(isset($mval['checked']) && $mval['checked']){
                        $discount_merchid[] = [
                            'merchid' => $mkey,
                            'value' => $mval['discount'],
                        ];
                    }
                }
            }
            $params['discount_merchid'] = json_encode($discount_merchid);

            $params['operator_id'] = $_W['uid'];
            $params['operator_name'] = $_W['username'];
            $params['createtime'] = time();

            //更新
            if($discount_id) {
                $params['updatetime'] = time();
                $ret = pdo_update('superdesk_shop_discount_merchid', $params, ['discount_id'=>$discount_id]);

                show_json(1);
            } else {
                //插入
                $params['discount_id'] = base_convert(uniqid(), 16, 10);
                $ret = pdo_insert('superdesk_shop_discount_merchid', $params);
                if (!empty($ret)) {
                    $id = pdo_insertid();
                }

                show_json(1);
            }

        }

        $condition = ' WHERE discount_id = :discount_id and uniacid = :uniacid';
        $params = array(
            ':discount_id' => $discount_id,
            ':uniacid' => $_W['uniacid']
        );
        $sql =
            ' SELECT * FROM ' . tablename('superdesk_shop_discount_merchid') .
            $condition .
            ' ORDER BY discount_id DESC';
        $item = pdo_fetch($sql,$params);

        $groupList = pdo_fetchall(
            ' SELECT id as groupid, groupname FROM ' . tablename('superdesk_shop_merch_group') .
            ' WHERE status = 1 '
        );

        $merchList = pdo_fetchall(
            ' SELECT id, merchname, cateid, address, groupid FROM ' . tablename('superdesk_shop_merch_user') .
            ' WHERE status = 1 '
        );
        //print_r($merchList);exit();
        $discount_merchid = json_decode($item['discount_merchid'], true);

        if($discount_merchid) {
            foreach ($discount_merchid as $dval) {

                foreach ($groupList as &$gval) {
                    if(isset($dval['groupid']) && isset($gval['groupid']) && $gval['groupid'] > 0 && $dval['groupid'] == $gval['groupid']) {
                        $gval['checked'] = 1;
                        $gval['discount'] = $dval['value'];
                    }
                }

                foreach ($merchList as &$mval) {
                    if(isset($dval['merchid']) && isset($mval['id']) && $mval['id'] > 0 && $dval['merchid'] == $mval['id']) {
                        $mval['checked'] = 1;
                        $mval['discount'] = $dval['value'];
                    }
                }

            }
        }

        include $this->template();
    }

    /**
     * 删除价格配置
     *
     */
    public function delete()
    {
        global $_GPC, $_W;
        $discount_id = intval($_GPC['discount_id']);

        if ($_W['ispost']) {

            pdo_delete('superdesk_shop_discount_merchid',['discount_id'=>$discount_id]);

            show_json(1);
        }
    }

    public function delMerchDiscount(){
        global $_GPC,$_W;

        $core_enterprise = $_GPC['core_enterprise'];
        $merchid = empty($_GPC['merchid']) ? 0 : $_GPC['merchid'];

        if(empty($core_enterprise)){
            show_json(0, '请先选择企业');
        }

        if(empty($merchid)){
            show_json(0, '请先选择商户');
        }

        $this->_category_enterprise_discountModel->deleteByColumn(array('core_enterprise' => $core_enterprise, 'merchid' => $merchid));

        m('goods')->delCategoryEnterpriseDiscountRedis($core_enterprise, $merchid);

        show_json(1);
    }
}


