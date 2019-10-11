<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

/**
 * 打印机设置
 * Class Printset_SuperdeskShopV2Page
 */
class Printset_SuperdeskShopV2Page extends PluginWebPage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        $sys = pdo_fetch(
            'select * '.
            ' from ' . tablename('superdesk_shop_exhelper_sys') .
            ' where '.
            '       uniacid=:uniacid '.
            '       and merchid=0 '.
            ' limit 1 ',
            array(
                ':uniacid' => $_W['uniacid']
            )
        );

        if ($_W['ispost']) {

            ca('exhelper.printset');

            $port = intval($_GPC['port']);

            $ip   = 'localhost';

            if (!empty($port)) {

                if (empty($sys)) {

                    pdo_insert(
                        'superdesk_shop_exhelper_sys',
                        array(
                            'port' => $port,
                            'ip' => $ip,
                            'uniacid' => $_W['uniacid'],
                            'merchid' => 0
                        )
                    );

                } else {
                    pdo_update(
                        'superdesk_shop_exhelper_sys',
                        array('port' => $port, 'ip' => $ip),
                        array('uniacid' => $_W['uniacid'], 'merchid' => 0)
                    );
                }

                plog('exhelper.printset.edit', '修改打印机端口 原端口: ' . $sys['port'] . ' 新端口: ' . $port);
                show_json(1);
            }
        }
        include $this->template();
    }
}