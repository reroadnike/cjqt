<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/ProductService.class.php');


class Detail_SuperdeskShopV2Page extends MobilePage
{

    private $_productService;

    public function __construct()
    {
        parent::__construct();

        $this->_productService = new ProductService();
    }

    /**
     * 商品详情
     */
    public function main()
    {
        global $_W;
        global $_GPC;

        $uniacid = $_W['uniacid'];

        $id  = intval($_GPC['id']);
        $err = false;

        $merch_plugin    = p('merch');
        $merch_data      = m('common')->getPluginset('merch');
        $commission_data = m('common')->getPluginset('commission');


        // 是否开启多商户
        if ($merch_plugin && $merch_data['is_openmerch']) {
            $is_openmerch = 1;
        } else {
            $is_openmerch = 0;
        }


        $isgift    = 0;
        $gifts     = array();
        $giftgoods = array();


        $gifts = pdo_fetchall(
            ' select ' .
            '   id,goodsid,giftgoodsid,thumb,title ' .
            ' from ' . tablename('superdesk_shop_gift') .
            ' where uniacid = ' . $uniacid .
            '   and activity = 2 and status = 1 ' .
            '   and starttime <= ' . time() .
            '   and endtime >= ' . time() . '  ');

        foreach ($gifts as $key => $value) {
            if (strstr($value['goodsid'], trim($id))) {
                $giftgoods = explode(',', $value['giftgoodsid']);
                foreach ($giftgoods as $k => $val) {
                    $isgift                  = 1;
                    $gifts[$key]['gift'][$k] = pdo_fetch(
                        ' select id,title,thumb,marketprice ' .
                        ' from ' . tablename('superdesk_shop_goods') .
                        ' where uniacid = ' . $uniacid .
                        '       and status = 2 ' .
                        '       and id = ' . $val . ' ');
                    $gifttitle               = $gifts[$key]['gift'][$k]['title'];
                }
            }
        }

        $goods = pdo_fetch(
            ' select * ' .
            ' from ' . tablename('superdesk_shop_goods') .
            ' where id=:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':id'      => $id,
                ':uniacid' => $_W['uniacid']
            )
        );

//        socket_log(json_encode($goods,JSON_UNESCAPED_UNICODE));

        // jd_vop 更新商品详情,图片,价格 start
        if ($goods['jd_vop_sku'] != 0) {
            $this->_productService->businessProcessingGetDetailOne($goods['jd_vop_sku'], $goods['jd_vop_page_num'], 1);
            $goods = pdo_fetch(
                ' select * ' .
                ' from ' . tablename('superdesk_shop_goods') .
                ' where id=:id ' .
                '       and uniacid=:uniacid ' .
                ' limit 1',
                array(
                    ':id'      => $id,
                    ':uniacid' => $_W['uniacid']
                )
            );
        }
        // jd_vop 更新商品详情,图片,价格 end

        if (0 < $goods['bargain']) {
            echo '<script>window.location.href = \'' . mobileUrl('bargain/detail', array('id' => $goods['bargain'])) . '\'</script>';
            return;
        }

        $merchid   = $goods['merchid'];
        $labelname = json_decode($goods['labelname'], true);
        $style     = pdo_fetch(
            ' SELECT id,uniacid,style ' .
            ' FROM ' . tablename('superdesk_shop_goods_labelstyle') .
            ' WHERE uniacid=' . $uniacid);

        if ($is_openmerch == 0) {

            if (0 < $merchid) {
                $err = true;
                include $this->template('goods/detail');
                exit();
            }

        } else if ((0 < $merchid) && ($goods['checked'] == 1)) {
            $err = true;
            include $this->template('goods/detail');
            exit();
        }


        $member    = m('member')->getMember($_W['openid'], $_W['core_user']);
        $showgoods = m('goods')->visit($goods, $member);

        if (empty($goods) || empty($showgoods)) {
            $err = true;
            include $this->template();
            exit();
        }

        //zjh 2018年10月16日 18:11:25 限制后台获取链接然后直接在前端进入的功能
        //g.`status` > 0 and g.`checked`=0 and g.`total`>0 and g.`deleted`=0
        if ($goods['status'] <= 0 || $goods['checked'] != 0 || $goods['deleted'] != 0) {
            $err = true;
            include $this->template();
            exit();
        }

        $task_goods_data = m('goods')->getTaskGoods($_W['openid'], $id);

        if (empty($task_goods_data['is_task_goods'])) {
            $is_task_goods = 0;
        } else {
            $is_task_goods        = $task_goods_data['is_task_goods'];
            $is_task_goods_option = $task_goods_data['is_task_goods_option'];
            $task_goods           = $task_goods_data['task_goods'];
        }

        $goods['sales']   = $goods['sales'] + $goods['salesreal'];
        $goods['content'] = m('ui')->lazy($goods['content']);

        $buyshow = 0;

        if ($goods['buyshow'] == 1) {
            $sql       =
                'select o.id ' .
                'from ' . tablename('superdesk_shop_order') . ' o ' .// TODO 标志 楼宇之窗 openid shop_order 已处理
                'left join ' . tablename('superdesk_shop_order_goods') . ' g on o.id = g.orderid';// TODO 标志 楼宇之窗 openid shop_order_goods 已处理
            $sql       .= ' where o.openid=:openid and o.core_user=:core_user and g.goodsid=:id and o.status>0 and o.uniacid=:uniacid limit 1';
            $buy_goods = pdo_fetch($sql,
                array(
                    ':openid'    => $_W['openid'],
                    ':core_user' => $_W['core_user'],
                    ':id'        => $id,
                    ':uniacid'   => $_W['uniacid']
                )
            );
            if (!empty($buy_goods)) {
                $buyshow             = 1;
                $goods['buycontent'] = m('ui')->lazy($goods['buycontent']);
            }
        }

        $goods['unit'] = ((empty($goods['unit']) ? '件' : $goods['unit']));
        $citys         = m('dispatch')->getNoDispatchAreas($goods);


        if (!empty($citys) && is_array($citys)) {
            $has_city = 1;
        } else {
            $has_city = 0;
        }


        $package_goods = pdo_fetch(
            ' select pg.id,pg.pid,pg.goodsid,p.displayorder ' .
            ' from ' . tablename('superdesk_shop_package_goods') . ' as pg' .
            ' left join ' . tablename('superdesk_shop_package') . ' as p on pg.pid = p.id' .
            ' where pg.uniacid = ' . $uniacid . ' and pg.goodsid = ' . $id .
            ' ORDER BY p.displayorder desc,pg.id desc limit 1 ');
        if ($package_goods['pid']) {
            $packages = pdo_fetchall(
                ' SELECT id,title,thumb,packageprice ' .
                ' FROM ' . tablename('superdesk_shop_package_goods') .
                ' WHERE uniacid = ' . $uniacid . ' and pid = ' . $package_goods['pid'] . '  ORDER BY id DESC');
            $packages = set_medias($packages, array('thumb'));
        }


        $goods['dispatchprice'] = $this->getGoodsDispatchPrice($goods);
        $thumbs                 = iunserializer($goods['thumb_url']);

        if (empty($thumbs)) {
            $thumbs = array($goods['thumb']);
        }

        if (!empty($goods['thumb_first']) && !empty($goods['thumb'])) {
            $thumbs = array_merge(array($goods['thumb']), $thumbs);
        }

        $specs = pdo_fetchall(
            ' select * ' .
            ' from ' . tablename('superdesk_shop_goods_spec') .
            ' where goodsid=:goodsid and  uniacid=:uniacid order by displayorder asc',
            array(':goodsid' => $id, ':uniacid' => $_W['uniacid']));

        $spec_titles = array();

        foreach ($specs as $key => $spec) {
            if (2 <= $key) {
                break;
            }
            $spec_titles[] = $spec['title'];
        }

        $spec_titles = implode('、', $spec_titles);
        $params      = pdo_fetchall(
            ' SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_goods_param') .
            ' WHERE uniacid=:uniacid and goodsid=:goodsid order by displayorder asc',
            array(':uniacid' => $uniacid, ':goodsid' => $goods['id']));

        $goods = set_medias($goods, 'thumb');


        // TODO 是否能买 1 上架 2 没被删除 3　库存大于0
        $goods['canbuy'] = ($goods['status'] == 1) && empty($goods['deleted']);


        if (!empty($goods['hasoption'])) {
            $options       = pdo_fetchall(
                ' select id,stock ' .
                ' from ' . tablename('superdesk_shop_goods_option') .
                ' where goodsid=:goodsid ' .
                '       and uniacid=:uniacid ' .
                ' order by displayorder asc',
                array(':goodsid' => $goods['id'], ':uniacid' => $_W['uniacid']), 'stock');
            $options_stock = array_keys($options);
            if ($options_stock) {
                $goods['total'] = max($options_stock);
            } else {
                $goods['total'] = 0;
            }
        }

        // TODO 是否能买 1 上架 2 没被删除 3　库存大于0 4 看有没限制购买最大量
        if ($goods['total'] <= 0) {
            $goods['canbuy'] = false;
        }

        //判断京东商品是否有库存 zjh 添加于 2018年5月2日 18:17:08
        $goods['jd_stock'] = true;
        if (!m('goods')->checkGoodsJdStock($goods)) {
            $goods['jd_stock'] = false;
            $goods['canbuy']   = false;
        }

        //判断是否有设置默认地址 zjh 添加于 2018年5月3日 14:08:44
        $member_address = $this->getMemberAddress();
        if (empty($member_address)) {
            $goods['jd_stock'] = false;
            $goods['canbuy']   = false;
        }

        $goods['timestate'] = '';
        $goods['userbuy']   = '1';


        if (0 < $goods['usermaxbuy']) {
            $order_goodscount = pdo_fetchcolumn(
                ' select ifnull(sum(og.total),0)  ' .
                ' from ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 已处理
                ' left join ' . tablename('superdesk_shop_order') . ' o on og.orderid=o.id ' .// TODO 标志 楼宇之窗 openid shop_order 已处理
                ' where ' .
                '       og.goodsid=:goodsid ' .
                '       and o.status>=1 ' .
                '       and o.openid=:openid ' .
                '       and o.core_user=:core_user ' .
                '       and og.uniacid=:uniacid ',
                array(
                    ':goodsid'   => $goods['id'],
                    ':uniacid'   => $uniacid,
                    ':openid'    => $_W['openid'],
                    ':core_user' => $_W['core_user']
                )
            );
            if ($goods['usermaxbuy'] <= $order_goodscount) {
                $goods['userbuy'] = 0;
                $goods['canbuy']  = false;// TODO 是否能买 1. 上架 2. 没被删除 3.　库存大于0 4. 看有没限制购买最大量
            }
        }


        $levelid           = $member['level'];
        $groupid           = $member['groupid'];
        $goods['levelbuy'] = '1';


        if ($goods['buylevels'] != '') {
            $buylevels = explode(',', $goods['buylevels']);
            if (!in_array($levelid, $buylevels)) {
                $goods['levelbuy'] = 0;
                $goods['canbuy']   = false;// TODO 是否能买 1. 上架 2. 没被删除 3.　库存大于0 4. 看有没限制购买最大量 5. 购买级别
            }
        }

        $goods['groupbuy'] = '1';
        if ($goods['buygroups'] != '') {
            $buygroups = explode(',', $goods['buygroups']);
            if (!in_array($groupid, $buygroups)) {
                $goods['groupbuy'] = 0;
                $goods['canbuy']   = false;// TODO 是否能买 1. 上架 2. 没被删除 3.　库存大于0 4. 看有没限制购买最大量 5. 购买级别 6. 购买组别
            }
        }

        $goods['timebuy'] = '0';
        if ($goods['istime'] == 1) {
            if (time() < $goods['timestart']) {
                $goods['timebuy'] = '-1';
                $goods['canbuy']  = false;
            } else if ($goods['timeend'] < time()) {
                $goods['timebuy'] = '1';
                $goods['canbuy']  = false;// TODO 是否能买 1. 上架 2. 没被删除 3.　库存大于0 4. 看有没限制购买最大量 5. 购买级别 6. 购买组别 7.　限时购时间内
            }
        }

        $canAddCart = true;

        if (($goods['isverify'] == 2) || ($goods['type'] == 2) || ($goods['type'] == 3) || !empty($goods['cannotrefund']) || !empty($is_task_goods)) {
            $canAddCart = false;
        }

        if (($goods['type'] == 2) && empty($specs)) {
            $gflag = 1;
        } else {
            $gflag = 0;
        }

        $enoughs      = com_run('sale::getEnoughs');
        $enoughfree   = com_run('sale::getEnoughFree');
        $goods_nofree = com_run('sale::getEnoughsGoods');

        // DEBUG

//        echo json_encode($enoughs , JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); // []
//        echo json_encode($enoughfree , JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); // false
//        echo json_encode($goods_nofree , JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);// null


        if (!empty($goods_nofree)) {
            if (in_array($id, $goods_nofree)) {
                $enoughfree = false;
            }
        }
        if ($enoughfree && ($enoughfree < $goods['minprice'])) {
            $goods['dispatchprice'] = 0;
        }

//        echo json_encode($goods , JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);// null


//        | 	ednum	| 	单品满件包邮 0 : 不支持满件包邮	|
//        | 	edmoney	| 	单品满额包邮 0 : 不支持满额包邮	|

        $hasSales = false;
        if ((0 < $goods['ednum']) || (0 < $goods['edmoney'])) {
            $hasSales = true;
        }

        if ($enoughfree || ($enoughs && (0 < count($enoughs)))) {
            $hasSales = true;
        }


        $minprice = $goods['minprice'];
        $maxprice = $goods['maxprice'];

        $level    = m('member')->getLevel($_W['openid'],$_W['core_user']);

        // 促销方式 isdiscount 促销 isdiscount_time 促销结束时间
        if (empty($is_task_goods)) {
            $memberprice = m('goods')->getMemberPrice($goods, $level);
        }


        if ($goods['isdiscount'] && (time() <= $goods['isdiscount_time'])) {

            $goods['oldmaxprice'] = $maxprice;

            $prices = array();

            $isdiscount_discounts = json_decode($goods['isdiscount_discounts'], true);

            if (!isset($isdiscount_discounts['type']) || empty($isdiscount_discounts['type'])) {

                $prices_array = m('order')->getGoodsDiscountPrice($goods, $level, 1);
                $prices[]     = $prices_array['price'];

            } else {

                $goods_discounts = m('order')->getGoodsDiscounts($goods, $isdiscount_discounts, $levelid);
                $prices          = $goods_discounts['prices'];

            }

            $minprice = min($prices);
            $maxprice = max($prices);

        } else {

            if (isset($options) && (0 < count($options)) && $goods['hasoption']) {

                $optionids = array();
                foreach ($options as $val) {
                    $optionids[] = $val['id'];
                }

                $sql =
                    ' update ' . tablename('superdesk_shop_goods') . ' g set' . "\n" .
                    '        g.minprice = (' .
                    ' select min(marketprice) ' .
                    ' from ' . tablename('superdesk_shop_goods_option') .
                    ' where goodsid = ' . $id . '),' . "\n" .
                    '        g.maxprice = (' .
                    ' select max(marketprice) ' .
                    ' from ' . tablename('superdesk_shop_goods_option') .
                    ' where goodsid = ' . $id . ')' . "\n" .
                    '        where g.id = ' . $id . ' and g.hasoption=1';
                pdo_query($sql);

            } else {
                $sql =
                    'update ' . tablename('superdesk_shop_goods') .
                    ' set minprice = marketprice,maxprice = marketprice ' .
                    ' where id = ' . $id . ' and hasoption=0;';
                pdo_query($sql);
            }

            $goods_price = pdo_fetch(
                'select minprice,maxprice from ' . tablename('superdesk_shop_goods') .
                ' where id=:id and uniacid=:uniacid limit 1',
                array(
                    ':id'      => $id,
                    ':uniacid' => $_W['uniacid']
                )
            );

            $maxprice = (double)$goods_price['maxprice'];
            $minprice = (double)$goods_price['minprice'];

        }

        if (!empty($is_task_goods)) {

            if (isset($options) && (0 < count($options)) && $goods['hasoption']) {
                $prices = array();
                foreach ($task_goods['spec'] as $k => $v) {
                    $prices[] = $v['marketprice'];
                }
                $minprice2 = min($prices);
                $maxprice2 = max($prices);
            } else {
                $minprice2 = $task_goods['marketprice'];
                $maxprice2 = $task_goods['marketprice'];
            }

            if ($minprice2 < $minprice) {
                $minprice = $minprice2;
            }

            if ($maxprice < $maxprice2) {
                $maxprice = $maxprice2;
            }
        }

        $goods['minprice'] = $minprice;
        $goods['maxprice'] = $maxprice;

        $getComments = empty($_W['shopset']['trade']['closecommentshow']);
        $hasServices = $goods['cash'] || $goods['seven'] || $goods['repair'] || $goods['invoice'] || $goods['quality'];

        $isFavorite = m('goods')->isFavorite($id);
        $cartCount  = m('goods')->getCartCount();

        m('goods')->addHistory($id);

        $shop        = set_medias(m('common')->getSysset('shop'), 'logo');
        $shop['url'] = mobileUrl('', NULL, true);
        $mid         = intval($_GPC['mid']);

        $opencommission = false;

        if (p('commission')) {

            if (empty($member['agentblack'])) {

                $cset = p('commission')->getSet();

                $opencommission = 0 < intval($cset['level']);

                if ($opencommission) {

                    if (empty($mid)) {
                        if (($member['isagent'] == 1) && ($member['status'] == 1)) {
                            $mid = $member['id'];
                        }
                    }

                    if (!empty($mid)) {
                        if (empty($cset['closemyshop'])) {
                            $shop        = set_medias(p('commission')->getShop($mid), 'logo');
                            $shop['url'] = mobileUrl('commission/myshop', array('mid' => $mid), true);
                        }
                    }
                }
            }
        }

        if (empty($this->merch_user)) {

            $merch_flag = 0;

            if (($is_openmerch == 1) && (0 < $goods['merchid'])) {

                $merch_user = pdo_fetch(
                    ' select * ' .
                    ' from ' . tablename('superdesk_shop_merch_user') .
                    ' where id=:id limit 1',
                    array(
                        ':id' => intval($goods['merchid'])
                    )
                );

                if (!empty($merch_user)) {

                    $shop = $merch_user;

                    $merch_flag = 1;
                }
            }

            if ($merch_flag == 1) {
                $shopdetail = array(
                    'logo'        => (!empty($goods['detail_logo']) ? tomedia($goods['detail_logo']) : tomedia($shop['logo'])),
                    'shopname'    => (!empty($goods['detail_shopname']) ? $goods['detail_shopname'] : $shop['merchname']),
                    'description' => (!empty($goods['detail_totaltitle']) ? $goods['detail_totaltitle'] : $shop['desc']),
                    'btntext1'    => trim($goods['detail_btntext1']),
                    'btnurl1'     => (!empty($goods['detail_btnurl1']) ? $goods['detail_btnurl1'] : mobileUrl('goods', array('merchid' => $goods['merchid']))),
                    'btntext2'    => trim($goods['detail_btntext2']),
                    'btnurl2'     => (!empty($goods['detail_btnurl2']) ? $goods['detail_btnurl2'] : mobileUrl('merch', array('merchid' => $goods['merchid'])))
                );
            } else {
                $shopdetail = array(
                    'logo'        => (!empty($goods['detail_logo']) ? tomedia($goods['detail_logo']) : $shop['logo']),
                    'shopname'    => (!empty($goods['detail_shopname']) ? $goods['detail_shopname'] : $shop['name']),
                    'description' => (!empty($goods['detail_totaltitle']) ? $goods['detail_totaltitle'] : $shop['desc']),
                    'btntext1'    => trim($goods['detail_btntext1']),
                    'btnurl1'     => (!empty($goods['detail_btnurl1']) ? $goods['detail_btnurl1'] : mobileUrl('goods')),
                    'btntext2'    => trim($goods['detail_btntext2']),
                    'btnurl2'     => (!empty($goods['detail_btnurl2']) ? $goods['detail_btnurl2'] : $shop['url'])
                );
            }

            $param = array(':uniacid' => $_W['uniacid']);

            if ($merch_flag == 1) {
                $sqlcon            = ' and merchid=:merchid';
                $param[':merchid'] = $goods['merchid'];
            }

            if (empty($shop['selectgoods'])) {

                $statics = array(
                    'all'      => pdo_fetchcolumn('select count(1) from ' . tablename('superdesk_shop_goods') . ' where uniacid=:uniacid ' . $sqlcon . ' and status=1 and deleted=0', $param),
                    'new'      => pdo_fetchcolumn('select count(1) from ' . tablename('superdesk_shop_goods') . ' where uniacid=:uniacid ' . $sqlcon . ' and isnew=1 and status=1 and deleted=0', $param),
                    'discount' => pdo_fetchcolumn('select count(1) from ' . tablename('superdesk_shop_goods') . ' where uniacid=:uniacid ' . $sqlcon . ' and isdiscount=1 and status=1 and deleted=0', $param)
                );
            } else {

                $goodsids = explode(',', $shop['goodsids']);
                $statics  = array(
                    'all'      => count($goodsids),
                    'new'      => pdo_fetchcolumn('select count(1) from ' . tablename('superdesk_shop_goods') . ' where uniacid=:uniacid ' . $sqlcon . ' and id in( ' . $shop['goodsids'] . ' ) and isnew=1 and status=1 and deleted=0', $param),
                    'discount' => pdo_fetchcolumn('select count(1) from ' . tablename('superdesk_shop_goods') . ' where uniacid=:uniacid ' . $sqlcon . ' and id in( ' . $shop['goodsids'] . ' ) and isdiscount=1 and status=1 and deleted=0', $param)
                );
            }
        } else if ($goods['checked'] == 1) {

            $err = true;
            include $this->template();
            exit();

        } else {
            $shop = $this->merch_user;

            $shopdetail = array(
                'logo'        => (!empty($goods['detail_logo']) ? tomedia($goods['detail_logo']) : tomedia($shop['logo'])),
                'shopname'    => (!empty($goods['detail_shopname']) ? $goods['detail_shopname'] : $shop['merchname']),
                'description' => (!empty($goods['detail_totaltitle']) ? $goods['detail_totaltitle'] : $shop['desc']),
                'btntext1'    => trim($goods['detail_btntext1']),
                'btnurl1'     => (!empty($goods['detail_btnurl1']) ? $goods['detail_btnurl1'] : mobileUrl('goods')),
                'btntext2'    => trim($goods['detail_btntext2']),
                'btnurl2'     => (!empty($goods['detail_btnurl2']) ? $goods['detail_btnurl2'] : mobileUrl('merch', array('merchid' => $goods['merchid'])))
            );

            if (empty($shop['selectgoods'])) {

                $statics = array(
                    'all'      => pdo_fetchcolumn(
                        'select count(1) from ' . tablename('superdesk_shop_goods') .
                        ' where uniacid=:uniacid and merchid=:merchid and status=1 and deleted=0',
                        array(
                            ':uniacid' => $_W['uniacid'],
                            ':merchid' => $goods['merchid']
                        )
                    ),
                    'new'      => pdo_fetchcolumn(
                        'select count(1) from ' . tablename('superdesk_shop_goods') .
                        ' where uniacid=:uniacid and merchid=:merchid and isnew=1 and status=1 and deleted=0',
                        array(
                            ':uniacid' => $_W['uniacid'],
                            ':merchid' => $goods['merchid']
                        )
                    ),
                    'discount' => pdo_fetchcolumn(
                        'select count(1) from ' . tablename('superdesk_shop_goods') .
                        ' where uniacid=:uniacid and merchid=:merchid and isdiscount=1 and status=1 and deleted=0',
                        array(
                            ':uniacid' => $_W['uniacid'],
                            ':merchid' => $goods['merchid']
                        )
                    )
                );

            } else {

                $goodsids = explode(',', $shop['goodsids']);

                $statics = array(
                    'all'      => count($goodsids),
                    'new'      => pdo_fetchcolumn(
                        'select count(1) from ' . tablename('superdesk_shop_goods') .
                        ' where uniacid=:uniacid and merchid=:merchid and id in( ' . $shop['goodsids'] . ' ) and isnew=1 and status=1 and deleted=0',
                        array(
                            ':uniacid' => $_W['uniacid'],
                            ':merchid' => $goods['merchid']
                        )
                    ),
                    'discount' => pdo_fetchcolumn(
                        'select count(1) from ' . tablename('superdesk_shop_goods') .
                        ' where uniacid=:uniacid and merchid=:merchid and id in( ' . $shop['goodsids'] . ' ) and isdiscount=1 and status=1 and deleted=0',
                        array(
                            ':uniacid' => $_W['uniacid'],
                            ':merchid' => $goods['merchid']
                        )
                    )
                );

            }
        }


        $goodsdesc = ((!empty($goods['description']) ? $goods['description'] : $goods['subtitle']));

        $_W['shopshare'] = array(
            'title'  => (!empty($goods['share_title']) ? $goods['share_title'] : $goods['title']),
            'imgUrl' => (!empty($goods['share_icon']) ? tomedia($goods['share_icon']) : tomedia($goods['thumb'])),
            'desc'   => (!empty($goodsdesc) ? $goodsdesc : $_W['shopset']['shop']['name']),
            'link'   => mobileUrl('goods/detail', array('id' => $goods['id']), true)
        );


        $com = p('commission');
        if ($com) {

            $cset = $_W['shopset']['commission'];

            if (!empty($cset)) {
                if (($member['isagent'] == 1) && ($member['status'] == 1)) {
                    $_W['shopshare']['link'] = mobileUrl('goods/detail', array('id' => $goods['id'], 'mid' => $member['id']), true);
                } else if (!empty($_GPC['mid'])) {
                    $_W['shopshare']['link'] = mobileUrl('goods/detail', array('id' => $goods['id'], 'mid' => $_GPC['mid']), true);
                }
            }
        }


        $stores = array();
        if ($goods['isverify'] == 2) {

            $storeids = array();

            if (!empty($goods['storeids'])) {
                $storeids = array_merge(explode(',', $goods['storeids']), $storeids);
            }

            if (empty($storeids)) {
                if (0 < $merchid) {

                    $stores = pdo_fetchall(
                        ' select * ' .
                        ' from ' . tablename('superdesk_shop_merch_store') .
                        ' where uniacid=:uniacid ' .
                        '       and merchid=:merchid ' .
                        '       and status=1 ',
                        array(
                            ':uniacid' => $_W['uniacid'],
                            ':merchid' => $merchid
                        )
                    );

                } else {

                    $stores = pdo_fetchall(
                        ' select * ' .
                        ' from ' . tablename('superdesk_shop_store') .
                        ' where uniacid=:uniacid ' .
                        '       and status=1',
                        array(
                            ':uniacid' => $_W['uniacid']
                        )
                    );

                }

            } else if (0 < $merchid) {

                $stores = pdo_fetchall(
                    ' select * ' .
                    ' from ' . tablename('superdesk_shop_merch_store') .
                    ' where id in (' . implode(',', $storeids) . ') ' .
                    '       and uniacid=:uniacid ' .
                    '       and merchid=:merchid ' .
                    '       and status=1',
                    array(
                        ':uniacid' => $_W['uniacid'],
                        ':merchid' => $merchid
                    )
                );

            } else {
                $stores = pdo_fetchall(
                    ' select * ' .
                    ' from ' . tablename('superdesk_shop_store') .
                    ' where id in (' . implode(',', $storeids) . ') ' .
                    '       and uniacid=:uniacid ' .
                    '       and status=1',
                    array(
                        ':uniacid' => $_W['uniacid']
                    )
                );
            }
        }

        //2019年5月24日 16:48:51 zjh 文礼 价套
        $goods = m('goods')->getGoodsCategoryEnterpriseDiscountOne($goods);


        if (p('diypage')) {
            $diypage = p('diypage')->detailPage($goods['diypage']);
            if ($diypage) {
                include $this->template('diypage/detail');
                exit();
            }
        }


        include $this->template();
    }

    public function querygift()
    {
        global $_W;
        global $_GPC;

        $uniacid = $_W['uniacid'];
        $giftid  = $_GPC['id'];

        $gift = pdo_fetch(
            ' select * ' .
            ' from ' . tablename('superdesk_shop_gift') .
            ' where uniacid = ' . $uniacid . ' and status = 1 and id = ' . $giftid . ' ');

        show_json(1, $gift);
    }

    protected function getGoodsDispatchPrice($goods)
    {
        if (!empty($goods['issendfree'])) {
            return 0;
        }

        if (($goods['type'] == 2) || ($goods['type'] == 3)) {
            return 0;
        }

        if ($goods['dispatchtype'] == 1) {
            return $goods['dispatchprice'];
        }

        if (empty($goods['dispatchid'])) {
            $dispatch = m('dispatch')->getDefaultDispatch($goods['merchid']);
        } else {
            $dispatch = m('dispatch')->getOneDispatch($goods['dispatchid']);
        }

        if (empty($dispatch)) {
            $dispatch = m('dispatch')->getNewDispatch($goods['merchid']);
        }

        $areas = iunserializer($dispatch['areas']);

        if (!empty($areas) && is_array($areas)) {

            $firstprice = array();

            foreach ($areas as $val) {
                $firstprice[] = $val['firstprice'];
            }

            array_push($firstprice, m('dispatch')->getDispatchPrice(1, $dispatch));

            $ret = array(
                'min' => round(min($firstprice), 2),
                'max' => round(max($firstprice), 2)
            );
        } else {

            $ret = m('dispatch')->getDispatchPrice(1, $dispatch);

        }

        return $ret;
    }


    public function get_detail()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        $goods = pdo_fetch(
            ' select * ' .
            ' from ' . tablename('superdesk_shop_goods') .
            ' where id=:id and uniacid=:uniacid limit 1',
            array(
                ':id' => $id, ':uniacid' => $_W['uniacid']
            )
        );


        $goods['content'] = $this->br2nl($goods['content']);

        //判断是否ios微信浏览器,如果是就不使用懒加载
        if ($_GPC['test'] == 1) {
            $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
            print_r($userAgent);
            if (preg_match("/MicroMessenger/i", $userAgent) && preg_match("/iphone/i", $userAgent)) {
                print_r('iPhone 微信浏览器');
            } elseif (preg_match("/MicroMessenger/i", $userAgent) && preg_match("/android/i", $userAgent)) {
                print_r('Android 微信浏览器');
            }
            die;
        }

        //判断是否ios微信浏览器,如果是就不使用懒加载
        $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
        if (preg_match("/MicroMessenger/i", $userAgent) && preg_match("/iphone/i", $userAgent)) {
            exit($goods['content']);
        }

        exit(m('ui')->lazy($goods['content']));
    }

    /**
     *
     * chr(13)
     *
     * @param        $text
     * @param string $replace
     *
     * @return mixed
     */
    private function br2nl($text)
    {
        $text = preg_replace('/<br\\s*?\/??>/i', '', $text);
        return preg_replace('/ /i', ' ', $text);
    }


    public function get_comments()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        $percent = 100;

        $params = array(
            ':goodsid' => $id,
            ':uniacid' => $_W['uniacid']
        );

        $count = array(
            'all'    => pdo_fetchcolumn(
                ' select count(*) ' .
                ' from ' . tablename('superdesk_shop_order_comment') .// TODO 标志 楼宇之窗 openid superdesk_shop_order_comment 不处理
                ' where goodsid=:goodsid ' .
                '       and level>=0 ' .
                '       and deleted=0 ' .
                '       and checked=0 ' .
                '       and uniacid=:uniacid', $params),
            'good'   => pdo_fetchcolumn(
                ' select count(*) ' .
                ' from ' . tablename('superdesk_shop_order_comment') .// TODO 标志 楼宇之窗 openid superdesk_shop_order_comment 不处理
                ' where goodsid=:goodsid ' .
                '       and level>=5 ' .
                '       and deleted=0 ' .
                '       and checked=0 ' .
                '       and uniacid=:uniacid', $params),
            'normal' => pdo_fetchcolumn(
                ' select count(*) ' .
                ' from ' . tablename('superdesk_shop_order_comment') .// TODO 标志 楼宇之窗 openid superdesk_shop_order_comment 不处理
                ' where goodsid=:goodsid ' .
                '       and level>=2 ' .
                '       and level<=4 ' .
                '       and deleted=0 ' .
                '       and checked=0 ' .
                '       and uniacid=:uniacid', $params),
            'bad'    => pdo_fetchcolumn(
                ' select count(*) ' .
                ' from ' . tablename('superdesk_shop_order_comment') .// TODO 标志 楼宇之窗 openid superdesk_shop_order_comment 不处理
                ' where goodsid=:goodsid ' .
                '       and level<=1 ' .
                '       and deleted=0 ' .
                '       and checked=0 ' .
                '       and uniacid=:uniacid', $params),
            'pic'    => pdo_fetchcolumn(
                ' select count(*) ' .
                ' from ' . tablename('superdesk_shop_order_comment') .// TODO 标志 楼宇之窗 openid superdesk_shop_order_comment 不处理
                ' where goodsid=:goodsid ' .
                '       and ifnull(images,\'a:0:{}\')<>\'a:0:{}\' ' .
                '       and deleted=0 ' .
                '       and checked=0 ' .
                '       and uniacid=:uniacid', $params));

        $list = array();

        if (0 < $count['all']) {

            $percent = intval(($count['good'] / ((empty($count['all']) ? 1 : $count['all']))) * 100);

            $list = pdo_fetchall(
                ' select nickname,level,content,images,createtime ' .
                ' from ' . tablename('superdesk_shop_order_comment') .// TODO 标志 楼宇之窗 openid superdesk_shop_order_comment 不处理
                ' where goodsid=:goodsid ' .
                '       and deleted=0 ' .
                '       and checked=0 ' .
                '       and uniacid=:uniacid ' .
                ' order by istop desc, createtime desc, id desc ' .
                ' limit 2',
                array(
                    ':goodsid' => $id,
                    ':uniacid' => $_W['uniacid']
                )
            );

            foreach ($list as &$row) {
                $row['createtime'] = date('Y-m-d H:i', $row['createtime']);
                $row['images']     = set_medias(iunserializer($row['images']));
                $row['nickname']   = cut_str($row['nickname'], 1, 0) . '**' . cut_str($row['nickname'], 1, -1);
            }

            unset($row);
        }

        show_json(1, array(
            'count'   => $count,
            'percent' => $percent,
            'list'    => $list
        ));
    }

    public function get_comment_list()
    {
        global $_W;
        global $_GPC;

        $id    = intval($_GPC['id']);
        $level = trim($_GPC['level']);

        $params = array(
            ':goodsid' => $id,
            ':uniacid' => $_W['uniacid']
        );

        $pindex = max(1, intval($_GPC['page']));
        $psize  = 10;

        $condition = '';


        if ($level == 'good') {
            $condition = ' and level=5';
        } else if ($level == 'normal') {
            $condition = ' and level>=2 and level<=4';
        } else if ($level == 'bad') {
            $condition = ' and level<=1';
        } else if ($level == 'pic') {
            $condition = ' and ifnull(images,\'a:0:{}\')<>\'a:0:{}\'';
        }


        $list = pdo_fetchall(
            ' select * ' .
            ' from ' . tablename('superdesk_shop_order_comment') . ' ' .// TODO 标志 楼宇之窗 openid superdesk_shop_order_comment 不处理
            ' where ' .
            '       goodsid=:goodsid ' .
            '       and uniacid=:uniacid ' .
            '       and deleted=0 ' .
            '       and checked=0 ' .
            $condition .
            ' order by istop desc, createtime desc, id desc ' .
            ' LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize,
            $params
        );

        foreach ($list as &$row) {
            $row['headimgurl']          = tomedia($row['headimgurl']);
            $row['createtime']          = date('Y-m-d H:i', $row['createtime']);
            $row['images']              = set_medias(iunserializer($row['images']));
            $row['reply_images']        = set_medias(iunserializer($row['reply_images']));
            $row['append_images']       = set_medias(iunserializer($row['append_images']));
            $row['append_reply_images'] = set_medias(iunserializer($row['append_reply_images']));
            $row['nickname']            = cut_str($row['nickname'], 1, 0) . '**' . cut_str($row['nickname'], 1, -1);
        }

        unset($row);

        $total = pdo_fetchcolumn(
            'select ' .
            '       count(*) ' .
            ' from ' . tablename('superdesk_shop_order_comment') . // TODO 标志 楼宇之窗 openid superdesk_shop_order_comment 不处理
            ' where ' .
            '       goodsid=:goodsid ' .
            '       and uniacid=:uniacid ' .
            '       and deleted=0 ' .
            '       and checked=0 ' .
            $condition,
            $params
        );

        show_json(1, array(
            'list'     => $list,
            'total'    => $total,
            'pagesize' => $psize
        ));
    }

    public function qrcode()
    {
        global $_W;
        global $_GPC;

        $url = $_W['root'];

        show_json(1, array(
            'url' => m('qrcode')->createQrcode($url)
        ));
    }

    public function getMemberAddress()
    {
        global $_W;

        $params = array(
            ':uniacid'   => $_W['uniacid'],
            ':openid'    => $_W['openid'],
            ':core_user' => $_W['core_user'],
        );

        $address = pdo_fetch(
            'select * ' .
            ' from ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_order_comment 已处理
            ' where ' .
            '       uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and isdefault=1 ',
            $params
        );

        if (empty($address)) {
            return null;
        }

        return $address['province'] . '/' . $address['city'] . '/' . $address['area'];
    }
}