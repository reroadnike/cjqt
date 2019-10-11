<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/8/17
 * Time: 5:48 PM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&do=scan&m=superdesk_wechat_scanner
 *
 * http://www.avic-s.com/plugins/app/index.php?i=16&c=entry&do=scan&m=superdesk_wechat_scanner
 */

global $_GPC, $_W;


//require_once MODULE_ROOT."/jssdk/"."jssdk.class.php";

//$settings = iunserializer($this->module['config']);
//
//print_r($settings);
//
//$appid = $settings['appid'];
//$secret = $settings['secret'];

//$appid = $this->module['config']['appid'];
//$secret = $this->module['config']['secret'];
//
//$weixin = new jssdk($appid,$secret);
//$wx = $weixin->get_sign();

$_DEBUG = false;

if (!$_DEBUG) {

    if(empty($_W['fans']['from_user'])){
        include $this->template('error/error');
        exit(0);
    }
    
    if ($_W['account']['jssdkconfig'] == null) {

        include_once(MODULE_ROOT . '/jssdk_error/jssdk.php');

        $jssdk       = new JSSDK($_W['account']['key'], $_W['account']['secret']);
        $signPackage = $jssdk->GetSignPackage();

        $jssdkconfig = array(
            'appId'     => $signPackage['appId'],
            'timestamp' => $signPackage["timestamp"],
            'nonceStr'  => $signPackage["nonceStr"],
            'signature' => $signPackage["signature"]
        );
        $jssdkconfig = json_encode($jssdkconfig);
    } else {
        $jssdkconfig = json_encode($_W['account']['jssdkconfig']);
    }
}




load()->func('tpl');
include $this->template('scan');