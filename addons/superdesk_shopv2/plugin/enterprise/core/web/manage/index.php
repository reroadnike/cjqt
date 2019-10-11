<?php

require SUPERDESK_SHOPV2_PLUGIN . 'enterprise/core/inc/page_enterprise.php';

class Index_SuperdeskShopV2Page extends EnterpriseWebPage
{
    public function main()
    {
        global $_W;
        global $_GPC;
        $user      = pdo_fetch(
            ' select `id`,`logo`,`enterprise_name`,`desc` ' .
            ' from ' . tablename('superdesk_shop_enterprise_user') .
            ' where id=:id and uniacid=:uniacid limit 1',
            array(
                ':id'      => $_W['uniaccount']['enterprise_id'],
                ':uniacid' => $_W['uniacid']
            )
        );

        $enterprise_id  = $_W['enterprise_id'];
        $url      = mobileUrl('enterprise', array('enterprise_id' => $enterprise_id), true);
        include $this->template();

        //header('location: ' . enterpriseUrl('shop'));
        //exit();
    }

    public function quit()
    {
        global $_W;
        global $_GPC;
        isetcookie('__enterprise_' . $_W['uniacid'] . '_session', -7 * 86400);
        isetcookie('__uniacid', -7 * 86400);
        unset($_SESSION['__enterprise_uniacid']);
        header('location: ' . enterpriseUrl('login') . '&i=' . $_W['uniacid']);
        exit();
    }

    public function updatepassword()
    {
        global $_W;
        global $_GPC;
        $no_left = true;

        if ($_W['ispost']) {
            $password        = trim($_GPC['password']);
            $newpassword     = trim($_GPC['newpassword']);
            $surenewpassword = trim($_GPC['surenewpassword']);

            (strlen($newpassword) < 6) && show_json(0, '密码至少是6位!');
            ($newpassword != $surenewpassword) && show_json(0, '两次输入密码不一致!');

            $item = pdo_fetch(
                ' SELECT * ' .
                ' FROM ' . tablename('superdesk_shop_enterprise_account') .// TODO 标志 楼宇之窗 openid shop_enterprise_account 不处理
                ' WHERE id=:id ' .
                '       AND uniacid=:uniacid ' .
                '       AND enterprise_id=:enterprise_id',
                array(
                    ':id'      => $_W['uniaccount']['id'],
                    ':uniacid' => $_W['uniacid'],
                    ':enterprise_id' => $_W['enterprise_id']
                )
            );

            ($item['pwd'] != md5($password . $item['salt'])) && show_json(0, '原密码输入不正确!');
            $date        = array('salt' => random(8));
            $newpassword = md5($newpassword . $date['salt']);
            $date['pwd'] = $newpassword;

            pdo_update(
                'superdesk_shop_enterprise_account',// TODO 标志 楼宇之窗 openid shop_enterprise_account 不处理
                $date,
                array(
                    'id'      => $_W['uniaccount']['id'],
                    'uniacid' => $_W['uniacid'],
                    'enterprise_id' => $_W['enterprise_id']
                )
            );

            show_json(1);
        }


        include $this->template();
    }
}


?>