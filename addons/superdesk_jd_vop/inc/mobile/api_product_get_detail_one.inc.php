<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/7/17
 * Time: 5:58 PM
 *
 * 企业内购
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_product_get_detail_one&page_num=0&overwrite=1&sku=4838625
 * view-source:https://wxm.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_product_get_detail_one&page_num=0&overwrite=1&sku=4838625
 *
 * 福利商城
 * view-source:https://fm.superdesk.cn/app/index.php?i=17&c=entry&m=superdesk_jd_vop&do=api_product_get_detail_one&page_num=0&overwrite=1&sku=100000008091
 *
 * 福利商城测试
 * view-source:https://fmt.superdesk.cn/app/index.php?i=17&c=entry&m=superdesk_jd_vop&do=api_product_get_detail_one&page_num=0&overwrite=1&sku=100000008091
 *
 */

global $_W, $_GPC;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/ProductService.class.php');
$_productService = new ProductService();

$sku       = $_GPC['sku'];
$page_num  = $_GPC['page_num'];
$overwrite = intval($_GPC['overwrite']);

$result = $_productService->businessProcessingGetDetailOne($sku,$page_num,$overwrite);

if($result['success'] == true){
    show_json(1,$result);
} else {
    show_json(0,$result);
}