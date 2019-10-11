<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

require_once IA_ROOT . '/addons/superdesk_shopv2/version.php';
require_once IA_ROOT . '/addons/superdesk_shopv2/defines.php';

require_once SUPERDESK_SHOPV2_INC . 'functions.php';
require_once SUPERDESK_SHOPV2_INC . 'processor.php';
require_once SUPERDESK_SHOPV2_INC . 'plugin_model.php';
require_once SUPERDESK_SHOPV2_INC . 'com_model.php';

class Superdesk_ShopV2ModuleProcessor extends Processor
{
    public function respond()
    {
        return parent::respond();
    }
}