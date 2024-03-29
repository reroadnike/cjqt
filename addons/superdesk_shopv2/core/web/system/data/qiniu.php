<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Qiniu_SuperdeskShopV2Page extends SystemPage
{
    public function main()
    {
        global $_W;
        global $_GPC;
        $path = IA_ROOT . '/addons/superdesk_shopv2/data/global';

        if (!is_dir($path)) {
            load()->func('file');
            mkdirs($path);
        }


        if ($_W['ispost']) {
            $data = ((is_array($_GPC['data']) ? $_GPC['data'] : array()));

            if ($data['upload']) {
                $check = com('qiniu')->save('addons/superdesk_shopv2/static/images/nopic100.jpg', $data);

                if (empty($check)) {
                    show_json(0, '设置错误，请检查!');
                }

            }


            m('cache')->set('qiniu', $data, 'global');
            $data_authcode = authcode(json_encode($data), 'ENCODE', 'global');
            file_put_contents($path . '/qiniu.cache', $data_authcode);
            show_json(1);
        }


        $data = m('cache')->getArray('qiniu', 'global');

        if (empty($data['upload']) && is_file($path . '/qiniu.cache')) {
            $data_authcode = authcode(file_get_contents($path . '/qiniu.cache'), 'DECODE', 'global');
            $data          = json_decode($data_authcode, true);
        }


        include $this->template();
    }
}


