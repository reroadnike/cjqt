<?php

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/accessControl.php";

if (!(defined("IN_IA"))) {
    exit("Access Denied");
}

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/page_login_mobile.php";

class Package_SuperdeskShopV2Page extends PcMobileLoginPage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        $openid  = $_W['openid'];
        $uniacid = $_W['uniacid'];

        $goodsid = intval($_GPC['goodsid']);

        $packages_goods = array();
        $packages       = array();
        $goodsid_array  = array();

        if ($goodsid) {
            $packages_goods = pdo_fetchall('SELECT id,pid FROM ' . tablename('superdesk_shop_package_goods') . "\n" . '                    WHERE uniacid = ' . $uniacid . ' and goodsid = ' . $goodsid . ' group by pid  ORDER BY id DESC');
            foreach ($packages_goods as $key => $value) {
                $packages[$key] = pdo_fetch('SELECT id,title,thumb,price,goodsid FROM ' . tablename('superdesk_shop_package') . "\n" . '                    WHERE uniacid = ' . $uniacid . ' and id = ' . $value['pid'] . ' and starttime <= ' . time() . ' and endtime >= ' . time() . ' and deleted = 0 and status = 1  ORDER BY id DESC');
            }
            $packages = array_values(array_filter($packages));
        } else {
            $packages = pdo_fetchall('SELECT id,title,thumb,price FROM ' . tablename('superdesk_shop_package') . "\n" . '                    WHERE uniacid = ' . $uniacid . ' and starttime <= ' . time() . ' and endtime >= ' . time() . ' and deleted = 0 and status = 1  ORDER BY id DESC');
        }

        foreach ($packages as $key => $value) {
            $goods = explode(',', $value['goodsid']);

            foreach ($goods as $k => $val) {
                $g                   = pdo_fetch('SELECT id,marketprice FROM ' . tablename('superdesk_shop_goods') . "\n" . '                    WHERE uniacid = ' . $uniacid . ' and id = ' . $val . '  ORDER BY id DESC');
                $goods['goodsprice'] += $g['marketprice'];
            }

            $packages[$key]['goodsprice'] = $goods['goodsprice'];
        }
        $packages = set_medias($packages, array('thumb'));

        include $this->template();
    }


    public function detail()
    {
        global $_W;
        global $_GPC;

        $uniacid = $_W['uniacid'];
        $pid     = intval($_GPC['pid']);

        $package   = pdo_fetch(
            ' SELECT id,title,price,freight,share_title,share_icon,share_desc ' .
            ' FROM ' . tablename('superdesk_shop_package') .
            ' WHERE '.
            '       uniacid=:uniacid '.
            '       and id=:id'
            , array(
                ':uniacid' => $uniacid,
                ':id' => $pid
            )
        );

        $packgoods = array();
        $packgoods = pdo_fetchall(
            ' SELECT id,title,thumb,marketprice,packageprice,`option`,goodsid ' .
            ' FROM ' . tablename('superdesk_shop_package_goods') .
            ' WHERE '.
            '       uniacid=:uniacid '.
            '       and pid=:pid '.
            ' ORDER BY id DESC',
            array(
                ':uniacid' => $uniacid,
                ':pid' => $pid
            )
        );
        $packgoods = set_medias($packgoods, array('thumb'));

        $option    = array();
        $total     = 1;

        $goodsprice = 0;
        foreach ($packgoods as $key => $value) {
            $option_array = array();
            $option_array = explode(',', $value['option']);

            if (0 < $option_array[0]) {

                $pgo                             = pdo_fetch(
                    ' SELECT id,title,packageprice ' .
                    ' FROM ' . tablename('superdesk_shop_package_goods_option') .
                    ' WHERE '.
                    '       uniacid=:uniacid '.
                    '       and pid=:pid '.
                    '       and goodsid=:goodsid '.
                    '       and optionid=:optionid',
                    array(
                        ':uniacid' => $uniacid,
                        ':pid' => $pid,
                        ':goodsid' => $value['goodsid'],
                        ':optionid' => $option_array[0]
                    )
                );

                $packgoods[$key]['packageprice'] = $pgo['packageprice'];
                $packgoods[$key]['optiontitle']  = $pgo['title'];
            }
            $goodsprice               += $packgoods[$key]['packageprice'];
            $packgoods[$key]['total'] = 1;
        }
        $goodsprice = number_format($goodsprice, 2);

        $result = array(
            'package'      => $package,
            'packagegoods' => $packgoods,
            'total'        => $total,
            'goodsprice'   => $goodsprice
        );

        show_json(1, $result);
    }


    public function option()
    {
        global $_W;
        global $_GPC;

        $openid  = $_W['openid'];
        $uniacid = intval($_W['uniacid']);

        $pid     = intval($_GPC['pid']);
        $goodsid = intval($_GPC['goodsid']);

        $optionid = array();
        $option   = array();

        $option = pdo_fetchall(
            'select optionid,title,goodsid,packageprice ' .
            ' from ' . tablename('superdesk_shop_package_goods_option') .
            ' where ' .
            '       pid = ' . $pid .
            '       and goodsid = ' . $goodsid .
            '       and uniacid = ' . $uniacid . ' '
        );

        show_json(1, $option);
    }
}