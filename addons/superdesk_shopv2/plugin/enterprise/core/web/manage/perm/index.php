<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}


require SUPERDESK_SHOPV2_PLUGIN . 'enterprise/core/inc/page_enterprise.php';

class Index_SuperdeskShopV2Page extends EnterpriseWebPage
{
    public function main()
    {
        global $_W;
        if (mcv('perm.role') && !empty($_W['accounttotal'])) {
            header('location: ' . enterpriseUrl('perm/role'));
            exit();
            return NULL;
        }


        if (mcv('perm.user') && !empty($_W['accounttotal'])) {
            header('location: ' . enterpriseUrl('perm/user'));
            exit();
            return NULL;
        }


        if (mcv('perm.log')) {
            header('location: ' . enterpriseUrl('perm/log'));
            exit();
        }

    }
}


?>