<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Picker_SuperdeskShopV2Page extends MobilePage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        $goods = pdo_fetch(
            'select ' .
            '       id,thumb,title,marketprice,total,maxbuy,minbuy,unit,hasoption,showtotal,' .
            '       diyformid,diyformtype,diyfields,' .
            '       isdiscount,isdiscount_time,isdiscount_discounts, ' .
            '       needfollow, followtip, followurl, type, isverify, maxprice, minprice, merchsale, tcate, merchid, costprice ' .
            ' from ' . tablename('superdesk_shop_goods')
            . ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':id'      => $id,
                ':uniacid' => $_W['uniacid']
            )
        );

        if (empty($goods)) {
            show_json(0);
        }


        $goods = set_medias($goods, 'thumb');

        if (is_weixin()) {
            $follow = m('user')->followed($_W['openid']);

            if (!empty($goods['needfollow']) && !$follow) {
                $followtip = ((empty($goods['followtip']) ? '如果您想要购买此商品，需要您关注我们的公众号，点击【确定】关注后再来购买吧~' : $goods['followtip']));
                $followurl = ((empty($goods['followurl']) ? $_W['shopset']['share']['followurl'] : $goods['followurl']));
                show_json(2, array('followtip' => $followtip, 'followurl' => $followurl));
            }

        }

        $member = m('member')->getMember($_W['openid'], $_W['core_user']);

        if (empty($_W['openid'])) {
            show_json(4);
        }


        if (!empty($_W['shopset']['wap']['open']) && !empty($_W['shopset']['wap']['mustbind']) && empty($member['mobileverify'])) {
            show_json(3);
        }


        if ($goods['isdiscount'] && (time() <= $goods['isdiscount_time'])) {
            $isdiscount           = true;
            $isdiscount_discounts = json_decode($goods['isdiscount_discounts'], true);
            $levelid              = $member['level'];
            $key                  = ((empty($levelid) ? 'default' : 'level' . $levelid));
        } else {
            $isdiscount = false;
        }

        $specs   = false;
        $options = false;

        if (!empty($goods) && $goods['hasoption']) {

            $specs = pdo_fetchall(
                ' select * ' .
                ' from ' . tablename('superdesk_shop_goods_spec') .
                ' where ' .
                '       goodsid=:goodsid ' .
                '       and uniacid=:uniacid ' .
                ' order by displayorder asc',
                array(
                    ':goodsid' => $id,
                    ':uniacid' => $_W['uniacid']
                )
            );

            // 与分离的PC端是有差异的
            foreach ($specs as &$spec) {
                $spec['items'] = pdo_fetchall(
                    ' select * ' .
                    ' from ' . tablename('superdesk_shop_goods_spec_item') .
                    ' where ' .
                    '       specid=:specid ' .
                    '       and `show`=1 ' .
                    ' order by displayorder asc',
                    array(
                        ':specid' => $spec['id']
                    )
                );
            }

            unset($spec);

            // 与分离的PC端是有差异的 差异是分离的PC端有去处理缩略图
            /** 代码块 处理缩略图 **/

            $options = pdo_fetchall(
                ' select * ' .
                ' from ' . tablename('superdesk_shop_goods_option') .
                ' where ' .
                '       goodsid=:goodsid ' .
                '       and uniacid=:uniacid ' .
                ' order by displayorder asc',
                array(
                    ':goodsid' => $id,
                    ':uniacid' => $_W['uniacid']
                )
            );
        }


        $minprice = $goods['minprice'];
        $maxprice = $goods['maxprice'];

        if ($goods['isdiscount'] && (time() <= $goods['isdiscount_time'])) {

            $goods['oldmaxprice'] = $maxprice;
            $isdiscount_discounts = json_decode($goods['isdiscount_discounts'], true);

            $prices = array();
            if (!isset($isdiscount_discounts['type']) || empty($isdiscount_discounts['type'])) {

                $level        = m('member')->getLevel($_W['openid'],$_W['core_user']);
                $prices_array = m('order')->getGoodsDiscountPrice($goods, $level, 1);
                $prices[]     = $prices_array['price'];

            } else {

                $goods_discounts = m('order')->getGoodsDiscounts($goods, $isdiscount_discounts, $levelid, $options);
                $prices          = $goods_discounts['prices'];
                $options         = $goods_discounts['options'];

            }

            $minprice = min($prices);
            $maxprice = max($prices);
        }

        $goods['minprice'] = $minprice;
        $goods['maxprice'] = $maxprice;

        //2019年5月24日 16:48:51 zjh 文礼 价套
        $goods = m('goods')->getGoodsCategoryEnterpriseDiscountOne($goods);

        $goods['minprice'] = number_format($goods['minprice'], 2);
        $goods['maxprice'] = number_format($goods['maxprice'], 2);

        // 自定义表单
        $diyformhtml    = '';
        $diyform_plugin = p('diyform');
        if ($diyform_plugin) {
            $fields = false;

            if ($goods['diyformtype'] == 1) {
                if (!empty($goods['diyformid'])) {
                    $diyformid = $goods['diyformid'];
                    $formInfo  = $diyform_plugin->getDiyformInfo($diyformid);
                    $fields    = $formInfo['fields'];
                }

            } else if ($goods['diyformtype'] == 2) {
                $diyformid = 0;
                $fields    = iunserializer($goods['diyfields']);

                if (empty($fields)) {
                    $fields = false;
                }

            }

            if (!empty($fields)) {
                ob_start();

                $inPicker = true;

                $member   = m('member')->getMember($_W['openid'], $_W['core_user']);

                $f_data   = $diyform_plugin->getLastData(3, 0, $diyformid, $id, $fields, $member);

                $flag     = 0;

                if (!empty($f_data)) {
                    foreach ($f_data as $k => $v) {
                        if (!empty($v)) {
                            $flag = 1;
                            break;
                        }
                    }
                }

                if (empty($flag)) {
                    $f_data = $diyform_plugin->getLastCartData($id);
                }

                include $this->template('diyform/formfields');
                $diyformhtml = ob_get_contents();
                ob_clean();
            }

        }


        if (!empty($specs)) {
            foreach ($specs as $key => $value) {
                foreach ($specs[$key]['items'] as $k => &$v) {
                    $v['thumb'] = tomedia($v['thumb']);
                }
            }
        }


        $goods['canAddCart'] = true;
        if (($goods['isverify'] == 2) || ($goods['type'] == 2) || ($goods['type'] == 3) || !empty($goods['cannotrefund'])) {
            $goods['canAddCart'] = false;
        }

        //2019年5月24日 16:48:51 zjh 文礼 价套
        $options = m('goods')->getGoodsCategoryEnterpriseDiscount($options,$goods['tcate']);


        show_json(1, array(
            'goods'       => $goods,
            'specs'       => $specs,
            'options'     => $options,
            'diyformhtml' => $diyformhtml
        ));
    }
}