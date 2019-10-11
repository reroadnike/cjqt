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

        $page = max(1, intval($_GPC['page']));
        $psize  = 20;

        $kwd      = trim($_GPC['keyword']);
        $params             = array();
        $where = array();

        $condition = ' WHERE a.status = 1 and a.type = 1 and a.contractStatus = 1 and a.parentCode = :parentCode';
        $params = array(
            ':parentCode' => 0
        );

        if (!empty($kwd)) {
            $condition .= ' and (a.name LIKE :keyword or b.name LIKE :keyword )';
            $params[':keyword'] = '%' . $kwd . '%';
        }

        //企业
        $count_sql = "SELECT count(*) as count FROM " . tablename('superdesk_core_virtualarchitecture') ." as a left join "
            .tablename('superdesk_core_organization')." as b on a.organizationId = b.id left join "
            .tablename('superdesk_shop_discount_enterprise_mapping')." as c on c.core_enterprise = a.id"
            .$condition;
        $total = pdo_fetchcolumn($count_sql, $params);

        $sql = "SELECT a.id, a.name, a.organizationId, b.name as org_name, c.discount_id, c.status,c.createtime FROM " . tablename('superdesk_core_virtualarchitecture') ." as a left join "
            .tablename('superdesk_core_organization')." as b on a.organizationId = b.id left join "
            .tablename('superdesk_shop_discount_enterprise_mapping')." as c on c.core_enterprise = a.id"
            .$condition . " ORDER BY a.id desc,a.organizationId DESC LIMIT " . ($page - 1) * $psize . ',' . $psize;
        $list  = pdo_fetchall($sql, $params);

        //套餐名称
        $msql = "SELECT discount_id, discount_name FROM " . tablename('superdesk_shop_discount_merchid');
        $m_list = pdo_fetchall($msql);
        $merchid_dis = array();
        if($m_list){
            foreach ($m_list as $mval) {
                if(isset($mval['discount_id'])) {
                    $merchid_dis[$mval['discount_id']] =  $mval['discount_name'];
                }
            }
        }
        //print_r($merchid_dis);exit();

        $pager = pagination($total, $page, $psize);

        include $this->template();
    }

    public function edit()
    {
        global $_GPC,$_W;
        $id = intval($_GPC['id']);

        if ($_W['ispost']) {
            $id_arr = [];

            //获取企业ID
            $organization = $_GPC['organization'];

            //获取批量
            $org_mul_arr = [];
            array_push($org_mul_arr, $organization);

            $org_mul_post = $_GPC['org_mul'];
            if($org_mul_post) {
                $org_mul_arr = explode('|', $org_mul_post);
            }

            foreach ($org_mul_arr as $value){
                $org_mul[] = (string)trim($value);
            }

            $org_mul_str = implode(',', $org_mul);
            if($org_mul_arr){
                $sqls = ' SELECT organizationId,id FROM ' . tablename('superdesk_core_virtualarchitecture') . ' where organizationId in('.$org_mul_str.')';

                $virtuals = pdo_fetchAll($sqls);

                if($virtuals) {
                    foreach ($virtuals as $vitem) {
                        if(isset($vitem['id']) && $vitem['id']) {
                            $id_arr[] = $vitem['id'];
                        }
                    }
                }

            } else {
                $id_arr = [$id];
            }

            //判断更新企业折扣
            if(count($id_arr)){
                $dicount_id = trim($_GPC['discount']);

                //获取商户折扣
                $sql_dis = ' SELECT * FROM ' . tablename('superdesk_shop_discount_merchid') . ' where discount_id = '.$dicount_id;
                $res_dis = pdo_fetch($sql_dis);
                if(!$res_dis){
                    show_json(0);
                }

                $discount_merchid = json_decode($res_dis['discount_merchid'], true);

                foreach ($id_arr as $vir_id) {
                    $params = [
                        'discount_id' => $dicount_id,
                        'status' => 1,
                        'uniacid' => $_W['uniacid']
                    ];
                    $params['operator_id'] = $_W['uid'];
                    $params['operator_name'] = $_W['username'];

                    // 企业
                    $sqls = ' SELECT count(*) FROM ' . tablename('superdesk_shop_discount_enterprise_mapping') . ' where core_enterprise = '.$vir_id;
                    $virtuals = pdo_fetchcolumn($sqls);

                    //更新
                    if($virtuals) {
                        $params['updatetime'] = time();
                        $ret = pdo_update('superdesk_shop_discount_enterprise_mapping', $params, ['core_enterprise'=>$vir_id]);

                    } else {
                        //插入
                        $params['createtime'] = time();
                        $params['core_enterprise'] = $vir_id;
                        $ret = pdo_insert('superdesk_shop_discount_enterprise_mapping', $params);

                    }

                    //清除该企业的所有价套缓存
                    $core_enterprise = $vir_id;
                    $sql = ' SELECT * FROM ' . tablename('superdesk_shop_category_enterprise_discount') .' where core_enterprise = :core_enterprise';

                    $params = array(
                        ':core_enterprise' => $core_enterprise
                    );
                    $ent_list = pdo_fetchAll($sql,$params);
                    foreach($ent_list as $ent_item) {
                        m('goods')->delCategoryEnterpriseDiscountRedis($ent_item['core_enterprise'], $ent_item['merchid']);
                    }
                    //清除DB
                    $res_del = pdo_delete('superdesk_shop_category_enterprise_discount',['core_enterprise'=>$core_enterprise]);

                    //分发
                    foreach ($discount_merchid as $ditem) {
                        if(isset($ditem['merchid'])) {
                            $merchid = $ditem['merchid'];
                            $discount = $ditem['value']/100;

                            $params_dis = [
                                'core_enterprise' => $core_enterprise,
                                'merchid' => $merchid,
                                'type' => $res_dis['price_type'],
                                'discount' => $discount,
                                'createtime' => time()
                            ];
                            pdo_insert('superdesk_shop_category_enterprise_discount', $params_dis);

                            m('goods')->setCategoryEnterpriseDiscountRedis($core_enterprise, $merchid);
                        }
                    }
                }
            }

            show_json(1);
        }

        //价套
        $condition = ' WHERE status =1 and uniacid = :uniacid';
        $params = array(
            ':uniacid' => $_W['uniacid']
        );
        $sql =
            ' SELECT * FROM ' . tablename('superdesk_shop_discount_merchid') .
            $condition .
            ' ORDER BY discount_id DESC';
        $item = pdo_fetchAll($sql,$params);

        // 项目
        $sqls = ' SELECT id, name FROM ' . tablename('superdesk_core_organization') . ' where status =1 order by id desc';
        $organization = pdo_fetchAll($sqls);

        // 企业
        $sqls = ' SELECT * FROM ' . tablename('superdesk_core_virtualarchitecture') . ' where id = '.$id;
        $virtuals = pdo_fetch($sqls);

        include $this->template();
    }

    /**
     * 删除价格配置
     *
     */
    public function delete()
    {
        global $_GPC, $_W;
        $core_enterprise = intval($_GPC['id']);

        if ($_W['ispost']) {
            $sql =
                ' SELECT * FROM ' . tablename('superdesk_shop_category_enterprise_discount') .' where core_enterprise = :core_enterprise';

            $params = array(
                ':core_enterprise' => $core_enterprise
            );
            $ent_list = pdo_fetchAll($sql,$params);
            foreach($ent_list as $ent_item) {
                m('goods')->delCategoryEnterpriseDiscountRedis($ent_item['core_enterprise'], $ent_item['merchid']);
            }


            //删除关联
            $res1 = pdo_delete('superdesk_shop_discount_enterprise_mapping', ['core_enterprise'=>$core_enterprise]);

            //映射表
            $res2 = pdo_delete('superdesk_shop_category_enterprise_discount', ['core_enterprise'=>$core_enterprise]);

            if($res1 && $res2) {
                show_json(1);
            } else {
                show_json(0);
            }

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


