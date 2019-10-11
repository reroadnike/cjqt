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

        //试用用户端计算价格
//        $category_enterprise_discount = m('goods')->getCategoryEnterpriseDiscountRedis(2500,'67222');
//        $cediscount = $category_enterprise_discount['discount'];
//        $price = 100;
//        $gprice = 100;
//        $CEDiscountPrice = 0;
//
//        if($cediscount > 0){
//            $price = $price * $cediscount;
//            $CEDiscountPrice = $gprice - $price;
//        }

        if ($_W['ispost']) {

            $this->post();

        }

        $condition = ' WHERE uniacid = :uniacid';
        $params = array(
            ':uniacid' => $_W['uniacid']
        );

        $sql =
            ' SELECT * FROM ' . tablename('superdesk_shop_category') .
            $condition .
            ' ORDER BY parentid ASC, displayorder DESC';

        $category = pdo_fetchall($sql,$params);

        $children = array();
        foreach ($category as $index => $row) {
            if (!empty($row['parentid'])) {
                $children[$row['parentid']][] = $row;
                unset($category[$index]);
            }

        }

        $currentType = 1;
        $category_enterprise_discount = array();
        $core_enterprise = $_GPC['core_enterprise'];
        $merchid = empty($_GPC['merchid']) ? 0 : $_GPC['merchid'];
        if ($core_enterprise != '') {
            $category_enterprise_discount = pdo_fetchall(
                ' SELECT * FROM ' . tablename('superdesk_shop_category_enterprise_discount') .
                ' WHERE core_enterprise = :core_enterprise ' .
                '   AND merchid = :merchid ',
                array(
                    ':core_enterprise' => $core_enterprise,
                    ':merchid'          => $merchid
                ),
                'category_id'
            );

            $currentType = current($category_enterprise_discount);
            $currentType = $currentType ? $currentType['type'] : 1;
        }

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

        $merchList = pdo_fetchall(
            ' SELECT * FROM ' . tablename('superdesk_shop_merch_user') .
            ' WHERE status = 1 '
        );

        include $this->template();
    }

    private function post(){
        global $_GPC,$_W;

        $core_enterprise = $_GPC['core_enterprise'];
        $merchid = empty($_GPC['merchid']) ? 0 : $_GPC['merchid'];
        $type = empty($_GPC['type']) ? 1 : $_GPC['type'];

        if(empty($core_enterprise)){
            show_json(0, '请先选择企业');
        }

        if (!empty($_GPC['discounts'])) {

            $discounts = $_GPC['discounts'];

            if (!is_array($discounts)) {
                show_json(0, '折扣保存失败，请重试!');
            }

            foreach($discounts as $k => $v){
                $this->_category_enterprise_discountModel->saveOrUpdateByColumn(
                    array(
                        'category_id'     => $k,
                        'type'             => $type,
                        'core_enterprise' => $core_enterprise,
                        'merchid'          => $merchid,
                        'discount'         => $v
                    ),
                    array(
                        'category_id'     => $k,
                        'merchid'          => $merchid,
                        'core_enterprise' => $core_enterprise,
                    )
                );
            }

            m('goods')->setCategoryEnterpriseDiscountRedis($core_enterprise,$_GPC['merchid']);

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


