<?php

defined('IN_IA') or exit('Access Denied');

require_once IA_ROOT . '/addons/superdesk_shopv2/version.php';
require_once IA_ROOT . '/addons/superdesk_shopv2/defines.php';
require_once SUPERDESK_SHOPV2_INC . 'functions.php';

class Superdesk_recoveryModuleSite extends WeModuleSite
{
    public function __construct()
    {
        global $_W;
    }
}
