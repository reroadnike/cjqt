<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

require SUPERDESK_SHOPV2_PLUGIN . 'merch/core/inc/page_merch.php';

class Index_SuperdeskShopV2Page extends MerchWebPage
{
    public function main()
    {
        if (mcv('sysset.shop')) {
            header('location: ' . merchUrl('sysset/shop'));
        }

    }

    public function shop()
    {
        global $_W;
        global $_GPC;

        $item = pdo_fetch(
            'select * ' .
            ' from ' . tablename('superdesk_shop_merch_user') .
            ' where id=:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':id'      => $_W['uniaccount']['merchid'],
                ':uniacid' => $_W['uniacid']
            )
        );

        if (empty($item) || empty($item['accoutntime'])) {
            $accounttime = strtotime('+365 day');
        } else {
            $accounttime = $item['accounttime'];
        }

        if (!empty($item['accountid'])) {
            $account = pdo_fetch(
                'select * ' .
                ' from ' . tablename('superdesk_shop_merch_account') .
                ' where ' .
                '       id=:id ' .
                '       and uniacid=:uniacid ' .
                ' limit 1',
                array(
                    ':id'      => $item['accountid'],
                    ':uniacid' => $_W['uniacid']
                )
            );
        }

        $diyform_flag   = 0;
        $diyform_plugin = p('diyform');
        $f_data         = array();

        if ($diyform_plugin) {
            if (!empty($item['diyformdata'])) {
                $diyform_flag = 1;
                $fields       = iunserializer($item['diyformfields']);
                $f_data       = iunserializer($item['diyformdata']);
            } else {
                $diyform_id = $_W['shopset']['merch']['apply_diyformid'];

                if (!empty($diyform_id)) {
                    $formInfo = $diyform_plugin->getDiyformInfo($diyform_id);

                    if (!empty($formInfo)) {
                        $diyform_flag = 1;
                        $fields       = $formInfo['fields'];
                    }

                }

            }
        }


        if ($_W['ispost']) {

            $fdata = array();

            if ($diyform_flag) {
                $fdata = p('diyform')->getPostDatas($fields);

                if (is_error($fdata)) {
                    show_json(0, $fdata['message']);
                }
            }

            $data = array(
                'uniacid'   => $_W['uniacid'],
                'merchname' => trim($_GPC['merchname']),
                'salecate'  => trim($_GPC['salecate']),
                'realname'  => trim($_GPC['realname']),
                'mobile'    => trim($_GPC['mobile']),
                'desc'      => trim($_GPC['desc1']),
                'address'   => trim($_GPC['address']),
                'tel'       => trim($_GPC['tel']),
                'lng'       => $_GPC['map']['lng'],
                'lat'       => $_GPC['map']['lat'],
                'logo'      => save_media($_GPC['logo'])
            );

            if ($diyform_flag) {
                $data['diyformdata']   = iserializer($fdata);
                $data['diyformfields'] = iserializer($fields);
            }

            pdo_update('superdesk_shop_merch_user', $data, array('id' => $_W['uniaccount']['merchid']));

            show_json(1);
        }


        include $this->template('sysset/index');
    }

    public function notice()
    {
        global $_W;
        global $_GPC;

        $data = $this->getSet('notice');

        $salers = array();

        if (isset($data['openid'])) {
            if (!empty($data['openid'])) {
                $openids     = array();
                $strsopenids = explode(',', $data['openid']);

                foreach ($strsopenids as $openid) {
                    $openids[] = '\'' . $openid . '\'';
                }

                $salers = pdo_fetchall(
                    'select id,nickname,avatar,openid ' .
                    ' from ' . tablename('superdesk_shop_member') .// TODO 标志 楼宇之窗 openid superdesk_shop_member 不处理
                    ' where ' .
                    '       openid in (' . implode(',', $openids) . ') ' .
                    '       and uniacid=' . $_W['uniacid']
                );
            }
        }

        $newtype = explode(',', $data['newtype']);

        if ($_W['ispost']) {

            mca('sysset.notice.edit');

            $data = ((is_array($_GPC['data']) ? $_GPC['data'] : array()));

            if (is_array($_GPC['openids'])) {
                $data['openid'] = implode(',', $_GPC['openids']);
            } else {
                $data['openid'] = '';
            }

            if (is_array($data['newtype'])) {
                $data['newtype'] = implode(',', $data['newtype']);
            } else {
                $data['newtype'] = '';
            }

            $this->updateSet(array('notice' => $data));

            mplog('sysset.notice.edit', '修改系统设置-模板消息通知设置');

            show_json(1);
        }

        include $this->template();
    }
}