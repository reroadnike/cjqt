<?php
/**
 * Created by PhpStorm.
 * User=> linjinyu
 * Date=> 4/28/18
 * Time=> 3=>17 AM
 *
 * https://wxm.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=test_superdesk_shopv2_core_model_notice
 */



$order = array(
    "id"=> "12447", 
    "ordersn"=> "ME20180716121845628820", 
    "price"=> "342.30", 
    "openid"=> "oX8KYwkxwNW6qzHF4cF-tGxYTcPg", 
    "dispatchtype"=> "0", 
    "addressid"=> "931", 
    "carrier"=> "a=>0=>{}", 
    "status"=> "0", 
    "isverify"=> "0", 
    "deductcredit2"=> "0.00", 
    "virtual"=> "0", 
    "isvirtual"=> "0", 
    "couponid"=> "0", 
    "isvirtualsend"=> "0", 
    "isparent"=> "0", 
    "paytype"=> "3", 
    "ismerch"=> "1", 
    "merchid"=> "8", 
    "agentid"=> "0", 
    "createtime"=> "1531714725", 
    "buyagainprice"=> "0.00"
);
m('examine')->addExamine($order);