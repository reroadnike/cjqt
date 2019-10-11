<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 1/17/18
 * Time: 1:43 PM
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_shop_member&do=js_update_shop_merch
 *
 *
 */


global $_W, $_GPC;

$_store_id = $_GPC['store_id'];

if($_store_id == 145) {
    show_json(0,'不导入');
}

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_water_store.class.php');
$_sll_water_storeModel = new sll_water_storeModel();
include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_goods.class.php');
$_sll_goodsModel = new sll_goodsModel();
include_once(IA_ROOT . '/addons/superdesk_shopv2/service/plugin/MerchService.class.php');
$_plugin_merchService = new MerchService();


//$page      = $_GPC['page'];
//$page_size = 1;

//$result    = $_sll_water_storeModel->queryForImport(array(), $page, $page_size);
//$total     = $result['total'];
//$page      = $result['page'];
//$page_size = $result['page_size'];
//$list      = $result['data'];// 商户List
//$pager = pagination($total, $page, $page_size);

//$count_goods = 0;
//
//foreach ($list as $index => $__store){
//    if($__store['store_id'] == 145) continue;
//    if($__store['store_account'] == 'avic_jdbbuy') continue;
//
//    //  商户入库
//    $_plugin_merchService->importMerchFromOldShop($__store);
//}

// shopv2
//ims_superdesk_shop_merch_account	        V2 多商户商户表 用于存放登陆帐号                  ----           YES
//ims_superdesk_shop_merch_adv	            V2 多商户广告位表                              ----          NO
//ims_superdesk_shop_merch_banner	        V2 多商户广告表                                ----          NO
//ims_superdesk_shop_merch_bill	            V2 多商户结算申请表                            ----          NO
//ims_superdesk_shop_merch_billo	        V2 多商户结算申请订单表                         ----          NO
//ims_superdesk_shop_merch_category	        V2 多商户商品分类表                            ----          YES 要看一下
//ims_superdesk_shop_merch_category_swipe	V2 多商户分类幻灯片表                            ----          NO
//ims_superdesk_shop_merch_clearing	        V2 多商户商品清理表                            ----          NO
//ims_superdesk_shop_merch_group	        V2 多商户商品组表                              ----          YES 要看一下
//ims_superdesk_shop_merch_nav	            V2 多商户商品导航表                             ----          NO
//ims_superdesk_shop_merch_notice	        V2 多商户通知表                                ----          NO
//ims_superdesk_shop_merch_perm_log	        V2 多商户操作日志表                             ----          NO
//ims_superdesk_shop_merch_perm_role	    V2 多商户分权规则表                             ----          NO
//ims_superdesk_shop_merch_reg	            V2 多商户注册表                                ----          YES 要看一下
//ims_superdesk_shop_merch_saler	        V2 多商户工作人员表                              ----          NO
//ims_superdesk_shop_merch_store	        V2 多商户门店表                                ----           NO
//ims_superdesk_shop_merch_user	            V2 多商户用户表                                 ----           YES

//        $data_source
//        {
//            "store_id": "143",
//            "store_account": "jd",
//            "store_name": "京东商城",
//            "store_user": "刘强东",
//            "store_address": "京东",
//            "phone": "13910011001",
//            "store_code": null,
//            "ctime": "2017-03-07 11:34:29",
//            "endTime": "2017-12-09 00:00:00",
//            "status": "400"
//        }
//    DELETE FROM `ims_superdesk_shop_merch_user` WHERE id > 1;
//    DELETE FROM `ims_superdesk_shop_merch_account` WHERE id > 1;


$_store = $_sll_water_storeModel->getOneByIdForImport($_store_id);

//  商户入库
$_store = $_plugin_merchService->importMerchFromOldShop($_store);

// 商品Num 为0则不导入商品
$_store2goods_total = $_sll_goodsModel->countByOriginEq1AndUserId($_store_id);

if ($_store2goods_total != 0) {
    $goods_list = $_sll_goodsModel->queryByMerch($_store_id, 1);
}


include $this->template('js_update_shop_merch');