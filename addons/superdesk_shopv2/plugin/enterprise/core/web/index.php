<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Index_SuperdeskShopV2Page extends PluginWebPage
{
    public function main()
    {
        header('location: ' . webUrl('enterprise/user'));//直接跳到企业列表页
        exit();
        include $this->template();
    }

    public function ajaxuser()
    {
        global $_GPC;
        global $_W;

        $totals = $this->model->getUserTotals();
        $order0 = $this->model->getEnterpriseOrderTotals(0);
        $order3 = $this->model->getEnterpriseOrderTotals(3);

        $totals['totalmoney'] = $order0['totalmoney'];
        $totals['totalcount'] = $order0['totalcount'];
        $totals['tmoney']     = $order3['totalmoney'];
        $totals['tcount']     = $order3['totalcount'];
        
        show_json(1, $totals);
    }
}


?>