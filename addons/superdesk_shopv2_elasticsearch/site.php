<?php

defined('IN_IA') or exit('Access Denied');

//header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
//if ($_SERVER['HTTP_ORIGIN'] == 'http://localhost') {
//    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
//} else {
//    header('Access-Control-Allow-Origin: http://0.0.0.0:23333');
//}
//header('Access-Control-Allow-Credentials: true');

require_once IA_ROOT . '/addons/superdesk_shopv2/version.php';
require_once IA_ROOT . '/addons/superdesk_shopv2/defines.php';
require_once SUPERDESK_SHOPV2_INC . 'functions.php';

class Superdesk_shopv2_elasticsearchModuleSite extends WeModuleSite
{
    public function __construct()
    {
        global $_W;
    }
}
