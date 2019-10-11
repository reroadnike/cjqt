<?php
/**
 *
 * For test
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 4/4/18
 * Time: 12:41 PM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_shopv2_elasticsearch&do=init_superdesk_shop_goods
 */

global $_GPC, $_W;

//include_once(IA_ROOT . '/addons/superdesk_shopv2/model/goods/goods.class.php');
//$_goodsModel = new goodsModel();

include_once(IA_ROOT . '/addons/superdesk_shopv2_elasticsearch/model/goods/ES_shop_goodsModel.class.php');
$_es_shop_goodsModel = new ES_shop_goodsModel();


//$select_fields =
//    " id,title,thumb,marketprice,productprice,minprice,maxprice,isdiscount,isdiscount_time,isdiscount_discounts,sales,total,description,bargain,jd_vop_sku ";
//
//$select_fields_array = explode(',',trim($select_fields));
//
//$sql =
//    ' SELECT  id,title,thumb,marketprice,productprice,minprice,maxprice,isdiscount,isdiscount_time,isdiscount_discounts,sales,total,description,bargain,jd_vop_sku  '.
//    ' FROM ims_superdesk_shop_goods '.
//    ' WHERE '.
//    '       uniacid = 16 '.
//    '       AND deleted = 0 '.
//    '       and status = true '.
//    '       and checked = 0 '.
//    '       and merchid in ( 8,11,13,14,15,16,18,19,20) '.
//    '       and minprice > 0.00 '.
//    ' ORDER BY  displayorder desc,createtime desc  LIMIT 20,10';
//
//$params = array(
////    ':uniacid' => 16
//);

$sql = ' SELECT  id,title,thumb,marketprice,productprice,minprice,maxprice,isdiscount,isdiscount_time,isdiscount_discounts,sales,total,description,bargain,jd_vop_sku  FROM `ims_superdesk_shop_goods` WHERE  uniacid = :uniacid  AND deleted = :deleted  AND status = :status  and checked = 0  and merchid in ( 8,11,13,14,15,16,18,19,20) and minprice > 0.00 and isrecommand=1 ORDER BY displayorder desc,createtime desc  LIMIT 0,6';
$params = array();


$result = $_es_shop_goodsModel->fetchAll($sql,$params);

show_json(1,$result);
