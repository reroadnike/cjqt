<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 4/8/18
 * Time: 11:44 AM
 *
 * view-source:http://192.168.1.124/superdesk/addons/superdesk_shopv2_elasticsearch/call_third_party.php
 */




//include_once(IA_ROOT . '/addons/superdesk_shopv2_elasticsearch/third_party/library/PDO_elasticsearch.class.php');

require_once dirname(__FILE__) . '../third_party/library/PDO_elasticsearch.class.php';





$sql =
    ' SELECT '.
    '       id,title,thumb,marketprice,productprice,minprice,maxprice,isdiscount,isdiscount_time,isdiscount_discounts,sales,total,description,bargain,'.
    '       jd_vop_sku  '.
    ' FROM `ims_superdesk_shop_goods` '.
    ' WHERE 1  and `uniacid` = :uniacid '.
    '       AND `deleted` = 0 and status=1 and `checked` = 0 '.
    '       and merchid in ( 8,11,13,14,15,16,18,19,20) '.
    '       and minprice > 0.00 '.
    ' ORDER BY displayorder desc,createtime desc '.
    ' LIMIT 0,10';

$params = array(':uniacid' => 16);




$pdo_elasticsearch = new PDO_elasticsearch();
//echo 'f';

$sql_22 = $pdo_elasticsearch->execute($sql,$params);

//echo $sql;
echo $sql_22;