<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}


require IA_ROOT . '/addons/superdesk_shopv2/version.php';
require IA_ROOT . '/addons/superdesk_shopv2/defines.php';

require SUPERDESK_SHOPV2_INC . 'functions.php';
require SUPERDESK_SHOPV2_INC . 'receiver.php';

class Superdesk_ShopV2ModuleReceiver extends Receiver
{
    public function receive()
    {
        parent::receive();
    }
}