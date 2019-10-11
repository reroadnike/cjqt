<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 10/30/17
 * Time: 2:36 PM
 * @url http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=main
 *      view-source:http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=main
 */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/sdk/jd_vop_sdk.php');
$jd_sdk = new JDVIPOpenPlatformSDK();
$jd_sdk->init_access_token();


//1.3  获取Access Token
//$jd_sdk->access_token();

//1.4  使用Refresh Token 刷新 Access Token
//$jd_sdk->refresh_token("A23k88ryXJx9QJBay7QEmdPcM0GoJk8uidR2pIjy");

//3.1  获取商品池编号接口
//$jd_sdk->api_product_get_page_num();

//3.2  获取池内商品编号接口
//$jd_sdk->api_product_get_sku(12254565);

//3.3  获取池内商品编号接口-品类商品池（兼容老接口）
//$jd_sdk->api_product_get_sku_by_page(12254565,1);

//3.4  获取商品详细信息接口
//$jd_sdk->api_product_get_detail(127768,true);

//3.5  获取商品上下架状态接口
//$jd_sdk->api_product_sku_state("125288");
//$jd_sdk->api_product_sku_state("125288,127768,158769");
//$jd_sdk->api_product_sku_state("125288,127768,158769,");

//3.6  获取所有图片信息
//$jd_sdk->api_product_sku_image("125288,127768,158769,");

//3.7  商品好评度查询
//$jd_sdk->api_product_get_comment_summarys("125288,127768,158769,");



//4.1  获取一级地址
//$jd_sdk->api_area_get_province();

//4.2  获取二级地址
//$jd_sdk->api_area_get_city(2);//1 北京 2 上海

//$jd_sdk->api_area_get_city(19);//广东

//4.3  获取三级地址
//$jd_sdk->api_area_get_county(72);//朝阳区

//$jd_sdk->api_area_get_county(1607);//深圳市

//4.4  获取四级地址
//$jd_sdk->api_area_get_town(4211);//定福庄
//$jd_sdk->api_area_get_town(2840);//五环到六环之间

//$jd_sdk->api_area_get_town(3639);//福田区
