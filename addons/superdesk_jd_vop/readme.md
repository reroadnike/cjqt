// TODO 普通运费

// TODO 普通运费
$dispatch_array   = m('order')->getOrderDispatchPrice($allgoods, $member, $address, $saleset, $merch_array, 1);
$dispatch_price   = $dispatch_array['dispatch_price'];
$nodispatch_array = $dispatch_array['nodispatch_array'];


#####framework####

include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
$_redis = new RedisUtil();

#####framework####





#####superdesk_core####

include_once(MODULE_ROOT . '/../superdesk_core/model/organization.class.php');
include_once(IA_ROOT . '/addons/superdesk_core/model/organization.class.php');
$_organizationModel = new organizationModel();

include_once(MODULE_ROOT . '/../superdesk_core/model/virtualarchitecture.class.php');
include_once(IA_ROOT . '/addons/superdesk_core/model/virtualarchitecture.class.php');
$_virtualarchitectureModel = new virtualarchitectureModel();



include_once(IA_ROOT . '/addons/superdesk_core/service/VirtualarchitectureService.class.php');
$_virtualarchitectureService = new VirtualarchitectureService();






include_once(IA_ROOT . '/addons/superdesk_core/model/out_db/tb_user.class.php');
$_tb_userModel = new tb_userModel();

include_once(IA_ROOT . '/addons/superdesk_core/model/out_db/tb_wxuser.class.php');
$_tb_wxuserModel = new tb_wxuserModel();

include_once(IA_ROOT . '/addons/superdesk_core/model/out_db/tb_virtualarchitecture.class.php');
$_tb_virtualarchitectureModel = new tb_virtualarchitectureModel();

include_once(IA_ROOT . '/addons/superdesk_core/model/out_db/tb_organization.class.php');
$_tb_organizationModel = new tb_organizationModel();


include_once(IA_ROOT . '/addons/superdesk_core/service/OrganizationService.class.php');
$_organizationService = new OrganizationService();

include_once(IA_ROOT . '/addons/superdesk_core/service/VirtualarchitectureService.class.php');
$_virtualarchitectureService = new VirtualarchitectureService();



include_once(IA_ROOT . '/addons/superdesk_core/service/TbuserService.class.php');
$_tbuserService = new TbuserService();






#####superdesk_core####








#####superdesk_jd_vop####

include_once(MODULE_ROOT . '/model/area.class.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/area.class.php');

include_once(MODULE_ROOT . '/sdk/jd_vop_sdk.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/sdk/jd_vop_sdk.php');


include_once(MODULE_ROOT . '/model/product_detail.class.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/product_detail.class.php');


include_once(MODULE_ROOT . '/model/product_price.class.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/product_price.class.php');


include_once(MODULE_ROOT . '/model/task_cron.class.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/task_cron.class.php');
$task_cron = new task_cronModel();


include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/order_submit_order.class.php');
$this->_order_submit_orderModel     = new order_submit_orderModel();
$_order_submit_orderModel     = new order_submit_orderModel();




include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/order_submit_order_sku.class.php');
$this->_order_submit_order_skuModel = new order_submit_order_skuModel();
$_order_submit_order_skuModel = new order_submit_order_skuModel();






include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/AreaService.class.php');
$_areaService = new AreaService();

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/MessageService.class.php');
$_messageService = new MessageService();

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/OrderService.class.php');
$_orderService = new OrderService();
$_order_submit_order['jd_vop_result_jdOrderId']

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/PriceService.class.php');
$_priceService = new PriceService();

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/ProductService.class.php');
$_productService = new ProductService();

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/StockService.class.php');
$_stockService = new StockService();

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/SearchService.class.php');
$_searchService = new SearchService();

#####superdesk_jd_vop####


#####superdesk_shop_member####

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/zc_import_member.class.php');
$_zc_import_memberModel = new zc_import_memberModel();

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_address.class.php');
$_sll_addressModel = new sll_addressModel();

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/zc_invoice.class.php');
$_zc_invoiceModel = new zc_invoiceModel();

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_goods.class.php');
$_sll_goodsModel = new sll_goodsModel();

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_classify.class.php');
$_sll_classifyModel = new sll_classifyModel();

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_water_store.class.php');
$_sll_water_storeModel = new sll_water_storeModel();

#####superdesk_shop_member####


#####superdesk_shopv2####

include_once(MODULE_ROOT . '/../superdesk_shopv2/model/category.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/category.class.php');
$_categoryModel = new categoryModel();


include_once(MODULE_ROOT . '/../superdesk_shopv2/model/goods/goods.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/goods/goods.class.php');
$_goodsModel = new goodsModel();

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/member/member.class.php');
$_memberModel = new memberModel();


include_once(IA_ROOT . '/addons/superdesk_shopv2/model/member/member_address.class.php');
$_member_addressModel = new member_addressModel();


include_once(IA_ROOT . '/addons/superdesk_shopv2/model/member/member_invoice.class.php');
$_member_invoiceModel = new member_invoiceModel();


include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order.class.php');
$_orderModel = new orderModel();

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order_goods.class.php');
$_order_goodsModel = new order_goodsModel();

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order_refund.class.php');
$_order_refundModel = new order_refundModel();





include_once(IA_ROOT . '/addons/superdesk_shopv2/service/goods/ShopGoodsService.class.php');
$_shopGoodsService = new ShopGoodsService();

include_once(IA_ROOT . '/addons/superdesk_shopv2/service/order/ShopOrderService.class.php');
$_shopOrderService = new ShopOrderService();




include_once(IA_ROOT . '/addons/superdesk_shopv2/model/__plugin__/merch/merch_account.class.php');
$_merch_accountModel = new merch_accountModel();

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/__plugin__/merch/merch_user.class.php');
$_merch_userModel = new merch_userModel();

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/__plugin__/merch/merch_x_enterprise.class.php');
$_merch_x_enterpriseModel = new merch_x_enterpriseModel();



include_once(IA_ROOT . '/addons/superdesk_shopv2/service/member/MemberService.class.php');
$_memberService = new MemberService();


include_once(IA_ROOT . '/addons/superdesk_shopv2/service/plugin/MerchService.class.php');
$_plugin_merchService = new MerchService();



include_once(IA_ROOT . '/addons/superdesk_shopv2/service/CategoryService.class.php');
$_categoryService = new CategoryService();






#####superdesk_shopv2####

SuperdeskShopMemberBaseOutDbModel

extends \superdesk_shop_member\model\out_db\base_setting\BaseModel


include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/base_setting/BaseModel.class.php');
include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/base_setting/SuperdeskShopMemberBaseOutDbModel.class.php');


include_once(IA_ROOT . '/addons/superdesk_core/model/out_db/base_setting/BaseModel.class.php');
include_once(IA_ROOT . '/addons/superdesk_core/model/out_db/base_setting/SuperdeskCoreBaseOutDbModel.class.php');


include_once(IA_ROOT . '/addons/superdesk_shopv2/model/base_setting/SuperdeskCoreBaseOutDbModel.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/base_setting/SuperdeskShopv2BaseModel.class.php');

SuperdeskCoreBaseOutDbModel
SuperdeskShopv2BaseModel


BaseModel
SuperdeskCoreBaseOutDbModel



EWEI_SHOP_PREFIX

SUPERDESK_SHOPV2_PREFIX

avic_jdbbuy

ewei_shop => superdesk_shop