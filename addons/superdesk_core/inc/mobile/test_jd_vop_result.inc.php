<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 8/13/18
 * Time: 11:54 AM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_core&do=test_jd_vop_result
 */


//$str = '[5729524]商品已下架，不能下单';
//
//$result = array();
//preg_match_all("/(?:\[)(.*)(?:\])/i", $str, $result);
//
////var_dump($result);
//echo $result[1][0];




//$str = '【4838382】商品在该配送区域内受限;';
//$result = array();
//preg_match_all("/(?:\【)(.*)(?:\】)/i", $str, $result);
//$_sku_id = intval($result[1][0]);
//echo $_sku_id;



$str = '编号为2586166的赠品无货，主商品为:929693;';
$result = array();
preg_match_all("/(?:\:)(.*)(?:\;)/i", $str, $result);
$_sku_id = intval($result[1][0]);
echo $_sku_id;