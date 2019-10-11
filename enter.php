<?php

//
// 礼品卡二维码跳转
// https://wxn.avic-s.com/enter.php?key=jkfdsjfdslfs
//
if(isset($_GET['key']) && !empty($_GET['key'])){
    $key = $_GET['key'];
    $url = '/app/index.php?i=17&c=entry&m=superdesk_shopv2&do=mobile&r=account.enter&key=';

    header("Location: ".$url.$key);
    exit();
} else {
    echo '<h1 style="width:100%; margin-top:100px; text-align:center;"><span>对不起，非法请求！</span></h1>';
    exit();
}

?>
