<?php

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/accessControl.php";

if (!defined('IN_IA')) {
    exit('Access Denied');
}

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/page_login_mobile.php";

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/member/member_address.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/member/member_invoice.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/member/member_cart.class.php');

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/goods/goods.class.php');

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/OrderService.class.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/PriceService.class.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/ProductService.class.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/StockService.class.php');

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order_goods.class.php');

class Create_SuperdeskShopV2Page extends PcMobileLoginPage
{

    private $_member_addressModel;
    private $_member_invoiceModel;
    private $_member_cartModel;

    private $_goodsModel;
    private $_orderModel;
    private $_order_goodsModel;
    private $_orderService;
    private $_stockService;
    private $_priceService;
    private $_productService;


    public function __construct()
    {

        global $_W;
        global $_GPC;

        parent::__construct();

        $this->_member_addressModel = new member_addressModel();
        $this->_member_invoiceModel = new member_invoiceModel();
        $this->_member_cartModel    = new member_cartModel();

        $this->_goodsModel = new goodsModel();

        $this->_orderModel       = new orderModel();
        $this->_order_goodsModel = new order_goodsModel();

        $this->_orderService   = new OrderService();
        $this->_stockService   = new StockService();
        $this->_priceService   = new PriceService();
        $this->_productService = new ProductService();
    }

    public function main()
    {
        global $_W;
        global $_GPC;

//        socket_log('order.create.main');

        $view_invoiceType = array(
            1 => '增值税普票',
            2 => '增值税专票'
        );

        $view_selectedInvoiceTitle = array(
            4 => '个人',
            5 => '单位'
        );

        $view_invoiceContent = array(
            1  => '明细',
            3  => '电脑配件',
            19 => '耗材',
            22 => '办公用品'
        );

        $giftid = intval($_GPC['giftid']);//赠品id

        //获取赠品信息
        $gift = pdo_fetch(
            ' select ' .
            '       id,title,thumb,activity,giftgoodsid ' .
            ' from ' . tablename('superdesk_shop_gift') .
            ' where ' .
            '       uniacid = ' . $_W['uniacid'] .
            '       and id = ' . $giftid .
            '       and status = 1 ' .
            '       and starttime <= ' . time() .
            '       and endtime >= ' . time() . ' ');

        $giftGood = array();    //赠品商品数组

        if (!empty($gift['giftgoodsid'])) {

            $giftGoodsid = explode(',', $gift['giftgoodsid']); //获取赠品商品id数组

            if ($giftGoodsid) {
                //赠品商品数组数据填充
                foreach ($giftGoodsid as $key => $value) {
                    $giftGood[$key] = pdo_fetch(
                        ' select id,title,thumb,' .
                        '   marketprice,costprice ' .
                        ' from ' . tablename('superdesk_shop_goods') .
                        ' where ' .
                        '       uniacid = ' . $_W['uniacid'] .
                        '       and status = 2 ' .
                        '       and id = ' . $value .
                        '       and deleted = 0 ');
                }
            }
        }

        $allow_sale = true;
        $packageid  = intval($_GPC['packageid']);

        // 非套餐
        if (!$packageid) {

            $merchdata = $this->merchData();
            extract($merchdata);


            $merch_array = array();
            $merchs      = array();
            $merch_id    = 0;

            $member = m('member')->getMember($_W['openid'], $_W['core_user']);

            $member['carrier_mobile'] = ((empty($member['carrier_mobile']) ? $member['mobile'] : $member['carrier_mobile']));

            $level = m('member')->getLevel($_W['openid'], $_W['core_user']);

            $diyformdata = $this->diyformData($member);
            extract($diyformdata);

            $id         = intval($_GPC['id']);
            $bargain_id = intval($_GPC['bargainid']);   //砍价活动id

            $_SESSION['bargain_id'] = NULL;

            if (p('bargain') && !empty($bargain_id)) {

                $_SESSION['bargain_id'] = $bargain_id;

                $bargain_act = pdo_fetch(
                    ' SELECT goods_id,now_price ' .
                    ' FROM ' . tablename('superdesk_shop_bargain_actor') .
                    ' WHERE ' .
                    '       id = :id ' .
                    '       AND openid = :openid ' .
                    '       AND core_user = :core_user ' .
                    '       AND status = \'0\'',
                    array(
                        ':id'        => $bargain_id,
                        ':openid'    => $_W['openid'],
                        ':core_user' => $_W['core_user'],
                    )
                );

                if (empty($bargain_act)) {
                    show_json(0, '没有这个商品!');
                }

                $bargain_act_id = pdo_fetch(
                    ' SELECT goods_id ' .
                    ' FROM ' . tablename('superdesk_shop_bargain_goods') .
                    ' WHERE id = \'' . $bargain_act['goods_id'] . '\'');

                if (empty($bargain_act_id)) {
                    show_json(0, '没有这个商品!');
                }

                $if_bargain = pdo_fetch(
                    ' SELECT bargain ' .
                    ' FROM ' . tablename('superdesk_shop_goods') .
                    ' WHERE ' .
                    '       id = :id ' .
                    '       AND uniacid = :uniacid ',
                    array(
                        ':id'      => $bargain_act_id['goods_id'],
                        ':uniacid' => $_W['uniacid']
                    )
                );

                if (empty($if_bargain['bargain'])) {
                    show_json(0, '没有这个商品!');
                }


                $id = $bargain_act_id['goods_id'];
            }

            $optionid = intval($_GPC['optionid']); //自定义表单
            $total    = intval($_GPC['total']);

            if ($total < 1) {
                $total = 1;
            }

            $buytotal          = $total; //购买数量
            $errcode           = 0;
            $isverify          = false; //是否支持线下核销
            $isvirtual         = false; //虚拟商品多规格
            $isvirtualsend     = false; //自动发货
            $changenum         = false; //我也不知道啥,就是被赠品id影响了
            $fromcart          = 0; //是否来自购物车
            $hasinvoice        = 0;//false; 是否支持发票
            $buyagain_sale     = true;  //再次购买
            $buyagainprice     = 0; //再次购买价
            $goods             = array();
            $jd_vop_getFreight = array(); // 运费


            if (empty($id)) { /* 购物车购买 这个$id 不为空是直接购买 */
                $sql =
                    ' SELECT ' .
                    ' c.goodsid,c.total,g.maxbuy,' .
                    ' g.type,g.issendfree,g.isnodiscount,' .
                    ' g.weight,o.weight as optionweight,g.title,g.thumb,' .

                    ' ifnull(o.marketprice, g.marketprice) as marketprice,' .
                    ' g.costprice,' .

                    ' o.title as optiontitle,c.optionid,' .
                    ' g.storeids,g.isverify,g.deduct,g.manydeduct,g.virtual,o.virtual as optionvirtual,discounts,' .
                    ' g.deduct2,g.ednum,g.edmoney,g.edareas,g.diyformtype,g.diyformid,diymode,g.dispatchtype,g.dispatchid,g.dispatchprice,g.minbuy, ' .
                    ' g.isdiscount,g.isdiscount_time,g.isdiscount_discounts,g.cates, ' .
                    ' g.virtualsend,invoice,o.specs,g.merchid,g.checked,g.merchsale,' .
                    ' g.buyagain,g.buyagain_islong,g.buyagain_condition, g.buyagain_sale,' .
                    ' g.jd_vop_sku,g.jd_vop_page_num,g.tcate ' .
                    ' FROM ' . tablename('superdesk_shop_member_cart') . ' c ' . // TODO 标志 楼宇之窗 openid superdesk_shop_member_cart 已处理
                    '       left join ' . tablename('superdesk_shop_goods') . ' g on c.goodsid = g.id ' .
                    '       left join ' . tablename('superdesk_shop_goods_option') . ' o on c.optionid = o.id ' .
                    ' where ' .
                    '       c.openid=:openid ' .
                    '       and c.core_user=:core_user ' .
                    '       and c.selected=1 ' .
                    '       and c.deleted=0 ' .
                    '       and c.uniacid=:uniacid ' .
                    ' order by c.id desc';


                //获取购物车商品信息
                $goods = pdo_fetchall($sql, array(
                    ':uniacid'   => $_W['uniacid'],
                    ':openid'    => $_W['openid'],
                    ':core_user' => $_W['core_user'],
                ));

                // 查一下购物车有没有东西
                if (empty($goods)) {

                    show_json(0, '购物车没东西');
                } else {
                    foreach ($goods as $k => $v) {

                        if ($is_openmerch == 0) { // 如果没开多商户 但有大于0的商家

                            if (0 < $v['merchid']) {
                                show_json(0, '未开启多商户');
                            }

                        } else if ((0 < $v['merchid']) && ($v['checked'] == 1)) {
                            show_json(0, '待审核');
                        }

                        //商品规格图片
                        if (!empty($v['specs'])) {
                            $thumb = m('goods')->getSpecThumb($v['specs']);
                            if (!empty($thumb)) {
                                $goods[$k]['thumb'] = $thumb;
                            }
                        }

                        //商品规格中的是否虚拟
                        if (!empty($v['optionvirtual'])) {
                            $goods[$k]['virtual'] = $v['optionvirtual'];
                        }

                        //商品规格中的净重
                        if (!empty($v['optionweight'])) {
                            $goods[$k]['weight'] = $v['optionweight'];
                        }
                    }
                }

                $fromcart = 1;
                // end 购物车购买
            } else { // 直接购买

                $data = $this->_goodsModel->getByIdForShopCart($id, $_W['uniacid']);

                if (!empty($bargain_act)) {
                    $data['marketprice'] = $bargain_act['now_price'];
                }

                if (empty($data)
                    || (!empty($data['showlevels']) && !strexists($data['showlevels'], $member['level']))
                    || ((0 < $data['merchid']) && ($data['checked'] == 1))
                    || (($is_openmerch == 0) && (0 < $data['merchid']))
                ) {
                    //假如没商品,假如等级不够,假如未审核通过,假如未开启多商户
                    show_json(0, '没有这个商品!');
                }

                $follow = m('user')->followed($_W['openid']);

                if (!empty($data['needfollow']) && !$follow && is_weixin()) {

                    $followtip = ((empty($goods['followtip']) ? '如果您想要购买此商品，需要您关注我们的公众号，点击【确定】关注后再来购买吧~' : $goods['followtip']));
                    $followurl = ((empty($goods['followurl']) ? $_W['shopset']['share']['followurl'] : $goods['followurl']));

                    show_json(0, [$followtip, $followurl]);
                }

                //假如存在最小购买数,并且最小购买数比实际购买数大,则把购买数替换成最小购买数
                if ((0 < $data['minbuy']) && ($total < $data['minbuy'])) {
                    $total = $data['minbuy'];
                }

                $data['total']    = $total;
                $data['optionid'] = $optionid;

                //假如存在规格id
                if (!empty($optionid)) {
                    $option = pdo_fetch(
                        ' select ' .
                        '       id,title,marketprice,costprice,' .
                        '       goodssn,productsn,`virtual`,stock,weight,specs ' .
                        ' from ' . tablename('superdesk_shop_goods_option') .
                        ' where ' .
                        '       id=:id ' .
                        '       and goodsid=:goodsid ' .
                        '       and uniacid=:uniacid ' .
                        ' limit 1',
                        array(
                            ':id'      => $optionid,
                            ':goodsid' => $id,
                            ':uniacid' => $_W['uniacid'],
                        )
                    );

                    //假如存在规格
                    if (!empty($option)) {

                        $data['optionid']    = $optionid;
                        $data['optiontitle'] = $option['title'];
                        $data['marketprice'] = $option['marketprice'];
                        $data['costprice']   = $option['costprice'];
                        $data['virtual']     = $option['virtual'];
                        $data['stock']       = $option['stock'];

                        if (!empty($option['weight'])) {
                            $data['weight'] = $option['weight'];
                        }

                        if (!empty($option['specs'])) {
                            $thumb = m('goods')->getSpecThumb($option['specs']);
                            if (!empty($thumb)) {
                                $data['thumb'] = $thumb;
                            }
                        }
                    }
                }

                if ($giftid) {
                    $changenum = false;
                } else {
                    $changenum = true;
                }

                $goods[] = $data;
            }// end 直接购买


            $goods = set_medias($goods, 'thumb');

            foreach ($goods as &$g) {

                $task_goods_data = m('goods')->getTaskGoods($_W['openid'], $id, $optionid);// TODO 分割BUG

                if (empty($task_goods_data['is_task_goods'])) {
                    $g['is_task_goods'] = 0;
                } else {
                    $allow_sale                = false;
                    $g['is_task_goods']        = $task_goods_data['is_task_goods'];
                    $g['is_task_goods_option'] = $task_goods_data['is_task_goods_option'];
                    $g['task_goods']           = $task_goods_data['task_goods'];
                }

                // 多商户
                if ($is_openmerch == 1) {
                    $merchid                          = $g['merchid'];
                    $merch_array[$merchid]['goods'][] = $g['goodsid'];
                }

                if ($g['isverify'] == 2) {
                    $isverify = true;
                }

                // 虚拟物品多规格,自动发货
                if (!empty($g['virtual']) || ($g['type'] == 2)) {
                    $isvirtual = true;
                    if ($g['virtualsend']) {
                        $isvirtualsend = true;
                    }
                }

                // 发票
                if ($g['invoice']) {
                    $hasinvoice = $g['invoice'];
                }

                if (SUPERDESK_SHOPV2_MODE_USER == 2) {
                    $hasinvoice = 0;
                }

                $totalmaxbuy = $g['stock'];

                if (0 < $g['maxbuy']) {
                    if ($totalmaxbuy != -1) {
                        if ($g['maxbuy'] < $totalmaxbuy) {
                            $totalmaxbuy = $g['maxbuy'];    //假如设置了单次最大购买数,并且该商品非无限库存,且库存比单次最大购买数要大,则将商品单次最大够买数赋值给最终可购买数
                        }
                    } else {
                        $totalmaxbuy = $g['maxbuy'];
                    }
                }

                //假如设置了用户最大总购买数
                if (0 < $g['usermaxbuy']) {

                    $order_goodscount = pdo_fetchcolumn(
                        ' select ' .
                        '       ifnull(sum(og.total),0)  ' .
                        ' from ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 已处理
                        '       left join ' . tablename('superdesk_shop_order') . ' o on og.orderid=o.id ' .// TODO 标志 楼宇之窗 openid shop_order 已处理
                        ' where ' .
                        '       og.goodsid=:goodsid ' .
                        '       and o.status>=1 ' .
                        '       and o.openid=:openid ' .
                        '       and o.core_user=:core_user ' .
                        '       and og.uniacid=:uniacid ',
                        array(
                            ':goodsid'   => $g['goodsid'],
                            ':uniacid'   => $_W['uniacid'],
                            ':openid'    => $_W['openid'],
                            ':core_user' => $_W['core_user']
                        )
                    );

                    $last = $g['usermaxbuy'] - $order_goodscount;

                    if ($last <= 0) {
                        $last = 0;
                    }

                    //假如非无限购买且最终可购买数比用户最大总购买数剩余数要大,剩余数赋值给最终可购买数,假如无限购买则也赋值
                    if ($totalmaxbuy != -1) {
                        if ($last < $totalmaxbuy) {
                            $totalmaxbuy = $last;
                        }
                    } else {
                        $totalmaxbuy = $last;
                    }
                }

                if (!empty($g['is_task_goods'])) {
                    if ($g['task_goods']['total'] < $totalmaxbuy) {
                        $totalmaxbuy = $g['task_goods']['total'];
                    }
                }

                $g['totalmaxbuy'] = $totalmaxbuy;
                //假如最终可购买数比实际购买数要小,那就使用最终可购买数作为最终购买数
                if (($g['totalmaxbuy'] < $g['total']) && !empty($g['totalmaxbuy'])) {
                    $g['total'] = $g['totalmaxbuy'];
                }

                //重复购买折扣,是否可叠加
                if ((0 < floatval($g['buyagain'])) && empty($g['buyagain_sale'])) {
                    if (m('goods')->canBuyAgain($g)) {
                        $buyagain_sale = false;
                    }
                }

                // 查 jd_vop 运费 start
                if ($g['jd_vop_sku'] != 0) {
                    $jd_vop_getFreight[] = array(
                        "skuId" => $g['jd_vop_sku'],
                        "num"   => $g['total']
                    );
                }
                // 查 jd_vop 运费 end


            }

            unset($g);


            // 发票
            if ($hasinvoice) {

                $invoice = $this->_member_invoiceModel->getByDefault($_W['uniacid'], $_W['openid'], $_W['core_user']);

            }

            if ($is_openmerch == 1) {
                foreach ($merch_array as $key => $value) {
                    if (0 < $key) {
                        $merch_id                     = $key;
                        $merch_array[$key]['set']     = $merch_plugin->getSet('sale', $key);
                        $merch_array[$key]['enoughs'] = $merch_plugin->getEnoughs($merch_array[$key]['set']);
                    }
                }
            }

            $weight            = 0;
            $total             = 0;
            $goodsprice        = 0;
            $realprice         = 0;
            $grprice           = 0;
            $deductprice       = 0;
            $taskdiscountprice = 0;
            $discountprice     = 0;
            $isdiscountprice   = 0;
            $deductprice2      = 0;
            $stores            = array();
            $address           = false;
            $carrier           = false;
            $carrier_list      = array();

            // 运费
            $dispatch_list         = false;
            $dispatch_price        = 0;
            $dispatch_jd_vop_price = 0;

            $ismerch = 0;

            //2019年5月28日 18:24:26 zjh 价套折扣
            $CEDiscountPrice = 0;

            //有开多商户也有多个商户
            if ($is_openmerch == 1) {
                if (!empty($merch_array)) {
                    if (1 < count($merch_array)) {
                        $ismerch = 1;
                    }
                }
            }

            //不支持线下核销,没有虚拟商品多规格,没有多商户
            if (!$isverify && !$isvirtual && !$ismerch) {
                if (0 < $merch_id) {
                    //carrier_list 应该是发送物流的门店
                    $carrier_list = pdo_fetchall(
                        ' select * ' .
                        ' from ' . tablename('superdesk_shop_merch_store') .
                        ' where ' .
                        '       uniacid=:uniacid ' .
                        '       and merchid=:merchid ' .
                        '       and status=1 ' .
                        '       and type in(1,3) ' .
                        ' order by displayorder desc,id desc',
                        array(
                            ':uniacid' => $_W['uniacid'],
                            ':merchid' => $merch_id
                        )
                    );
                } else {
                    $carrier_list = pdo_fetchall(
                        ' select * ' .
                        ' from ' . tablename('superdesk_shop_store') .
                        ' where ' .
                        '       uniacid=:uniacid ' .
                        '       and status=1 ' .
                        '       and type in(1,3) ' .
                        ' order by displayorder desc,id desc',
                        array(
                            ':uniacid' => $_W['uniacid']
                        )
                    );
                }
            }


            $sale_plugin = com('sale');
            $saleset     = false;

            //再次购买折扣,关注购买
            if ($sale_plugin && $buyagain_sale && $allow_sale) {
                $saleset            = $_W['shopset']['sale'];
                $saleset['enoughs'] = $sale_plugin->getEnoughs();
            }


            foreach ($goods as &$g) {

                if (empty($g['total']) || (intval($g['total']) < 1)) {
                    $g['total'] = 1;
                }

                $gprice = $g['marketprice'] * $g['total'];

                $prices = m('order')->getGoodsDiscountPrice($g, $level);    //获取各种折扣后的价格

                //2019年5月29日 14:33:31 zjh 价套
                $prices = m('order')->getGoodsCategoryEnterpriseDiscount($g,$prices);
                $CEDiscountPrice = round($CEDiscountPrice + $prices['CEDiscountPrice'], 2);

                $g['ggprice']   = $prices['price'];     //计算折扣后的该商品总价格
                $g['unitprice'] = $prices['unitprice']; //计算折扣后的该商品单价

                if ($is_openmerch == 1) {
                    $merchid                          = $g['merchid'];
                    $merch_array[$merchid]['ggprice'] += $g['ggprice'];
                    $merchs[$merchid]                 += $g['ggprice'];
                }

                $g['dflag'] = $CEDiscountPrice == 0 && intval($g['ggprice'] < $gprice);

                if (empty($bargain_id)) {

                    $discountprice     += $prices['discountprice']; //会员折扣差价
                    $taskdiscountprice += $prices['taskdiscountprice'];
                    $buyagainprice     += $prices['buyagainprice']; //再次购买折扣差价

                    $g['taskdiscountprice']   = $prices['taskdiscountprice'];
                    $g['discountprice']       = $prices['discountprice'];   //会员折扣差价
                    $g['isdiscountprice']     = $prices['isdiscountprice']; //促销折扣差价
                    $g['discounttype']        = $prices['discounttype'];    //折扣类型,0:无折扣,1:促销折扣,2:会员折扣
                    $g['isdiscountunitprice'] = $prices['isdiscountunitprice']; //促销折扣差价/商品数量
                    $g['discountunitprice']   = $prices['discountunitprice'];   //会员折扣差价/商品数量

                    if ($prices['discounttype'] == 1) {
                        $isdiscountprice += $prices['isdiscountprice'];
                    } else if ($prices['discounttype'] == 2) {
                        $discountprice += $prices['discountprice'];
                    }
                }

                $realprice += $g['ggprice'];    //累加折扣后该商品总价格
                $grprice   += $g['ggprice'];    //累加折扣后该商品总价格

                if ($g['ggprice'] < $gprice) {
                    $goodsprice += $gprice;
                } else {
                    $goodsprice += $g['ggprice'];
                }

                $total += $g['total'];

                if (empty($bargain_id)) {

                    //有再次购买折扣但是不支持叠加的时候去掉商品的余额积分抵扣
                    if ((0 < floatval($g['buyagain'])) && empty($g['buyagain_sale'])) {
                        if (m('goods')->canBuyAgain($g)) {
                            $g['deduct'] = 0;
                        }
                    }

                    //增加商品可以余额积分抵扣的金额
                    if ($g['manydeduct']) {
                        $deductprice += $g['deduct'] * $g['total'];
                    } else {
                        $deductprice += $g['deduct'];
                    }
                }
            }

            unset($g);

            /* 是否支持线下核销 */
            if ($isverify) {

                $storeids = array();
                $merchid  = 0;

                //集中所有的支持门店
                foreach ($goods as $g) {
                    if (!empty($g['storeids'])) {
                        $merchid  = $g['merchid'];
                        $storeids = array_merge(explode(',', $g['storeids']), $storeids);
                    }
                }

                if (empty($storeids)) {
                    //假如没有支持的门店
                    if (0 < $merchid) {
                        //假如有商户id
                        $stores = pdo_fetchall(
                            ' select * ' .
                            ' from ' . tablename('superdesk_shop_merch_store') .
                            ' where ' .
                            '       uniacid=:uniacid ' .
                            '       and merchid=:merchid ' .
                            '       and status=1 ' .
                            '       and type in(2,3)',
                            array(
                                ':uniacid' => $_W['uniacid'],
                                ':merchid' => $merchid
                            )
                        );
                    } else {
                        $stores = pdo_fetchall(
                            ' select * ' .
                            ' from ' . tablename('superdesk_shop_store') .
                            ' where ' .
                            '       uniacid=:uniacid ' .
                            '       and status=1 ' .
                            '       and type in(2,3)',
                            array(
                                ':uniacid' => $_W['uniacid']
                            )
                        );
                    }
                } else if (0 < $merchid) {
                    //假如有支持的门店也有商户id
                    $stores = pdo_fetchall(
                        ' select * ' .
                        ' from ' . tablename('superdesk_shop_merch_store') .
                        ' where ' .
                        '       id in (' . implode(',', $storeids) . ') ' .
                        '       and uniacid=:uniacid ' .
                        '       and merchid=:merchid ' .
                        '       and status=1 ' .
                        '       and type in(2,3)',
                        array(
                            ':uniacid' => $_W['uniacid'],
                            ':merchid' => $merchid
                        )
                    );
                } else {
                    //假如有支持的门店但没有商户id
                    $stores = pdo_fetchall(
                        ' select * ' .
                        ' from ' . tablename('superdesk_shop_store') .
                        ' where ' .
                        '       id in (' . implode(',', $storeids) . ') ' .
                        '       and uniacid=:uniacid ' .
                        '       and status=1 ' .
                        '       and type in(2,3)',
                        array(
                            ':uniacid' => $_W['uniacid']
                        )
                    );
                }
            } else {
                //不支持线下核销
                //获取默认收货地址
                $address = $this->_member_addressModel->getByDefault($_W['uniacid'], $_W['openid'], $_W['core_user']);

                if (!empty($carrier_list)) {
                    $carrier = $carrier_list[0];
                }

                // 不为虚拟
                if (!$isvirtual) {

                    // TODO 普通运费
                    $dispatch_array = m('order')->getOrderDispatchPrice($goods, $member, $address, $saleset, $merch_array, 0);
                    $dispatch_price = $dispatch_array['dispatch_price'];

                    // TODO 发起 京东运费 请求 start
                    if (sizeof($jd_vop_getFreight) > 0) {

//                        {"freight":6,"baseFreight":6,"remoteRegionFreight":0,"remoteSku":"[]"}
                        $dispath_jd_vop_array = $this->_orderService->getFreight(
                            $jd_vop_getFreight,
                            $address['jd_vop_province_code'],
                            $address['jd_vop_city_code'],
                            $address['jd_vop_county_code'],
                            $address['jd_vop_town_code']
                        );

                        $dispatch_jd_vop_price = $dispath_jd_vop_array['freight'];

                        $dispatch_price += $dispatch_jd_vop_price;
                    }
                    // TODO 发起 京东运费 请求 end
                }
            }


            // 商家满减
            if ($is_openmerch == 1) {

                $merch_enough       = m('order')->getMerchEnough($merch_array);
                $merch_array        = $merch_enough['merch_array'];
                $merch_enough_total = $merch_enough['merch_enough_total'];
                $merch_saleset      = $merch_enough['merch_saleset'];

                if (0 < $merch_enough_total) {
                    $realprice -= $merch_enough_total;
                }
            }


            if ($saleset) {
                foreach ($saleset['enoughs'] as $e) {

                    if ((floatval($e['enough']) <= $realprice) && (0 < floatval($e['money']))) {

                        $saleset['showenough']   = true;
                        $saleset['enoughmoney']  = $e['enough'];
                        $saleset['enoughdeduct'] = $e['money'];

                        $realprice -= floatval($e['money']);

                        break;
                    }
                }
            }

            // TODO 运费
            $realprice += $dispatch_price;

            $deductcredit  = 0;
            $deductmoney   = 0;
            $deductcredit2 = 0;


            if (!empty($saleset)) {

                if (!empty($saleset['creditdeduct'])) {

                    $credit = $member['credit1'];

                    $pcredit = intval($saleset['credit']);

                    $pmoney = round(floatval($saleset['money']), 2);

                    if ((0 < $pcredit) && (0 < $pmoney)) {

                        if (($credit % $pcredit) == 0) {

                            $deductmoney = round(intval($credit / $pcredit) * $pmoney, 2);
                        } else {

                            $deductmoney = round((intval($credit / $pcredit) + 1) * $pmoney, 2);
                        }
                    }

                    if ($deductprice < $deductmoney) {
                        $deductmoney = $deductprice;
                    }

                    if ($realprice < $deductmoney) {
                        $deductmoney = $realprice;
                    }

                    if (($pmoney * $pcredit) != 0) {
                        $deductcredit = ($deductmoney / $pmoney) * $pcredit;
                    }
                }
            }

            //zjh 2018年8月24日 11:51:09 对比微信后屏蔽
            //$haslevel = !(empty($level['id'])) && (0 < $level['discount']) && ($level['discount'] < 10);


            // 已比对PC start

            // 旧方法
            //        $goodsdata   = array();
            //        foreach ($goods as $g) {
            //            $goodsdata[] = array(
            //                'goodsid'  => $g['goodsid'],
            //                'total'    => $g['total'],
            //                'optionid' => $g['optionid']
            //            );
            //        }

            $goodsdata      = array();
            $goodsdata_temp = array();

            foreach ($goods as $g) {

                if (0 < floatval($g['buyagain'])) {

                    if (!m('goods')->canBuyAgain($g) || !empty($g['buyagain_sale'])) {
                        $goodsdata_temp[] = array(
                            'goodsid'             => $g['goodsid'],
                            'total'               => $g['total'],
                            'optionid'            => $g['optionid'],
                            'marketprice'         => $g['marketprice'],
                            'costprice'           => $g['costprice'],// TODO FFFFFFF
                            'merchid'             => $g['merchid'],
                            'cates'               => $g['cates'],
                            'discounttype'        => $g['discounttype'],
                            'isdiscountprice'     => $g['isdiscountprice'],
                            'discountprice'       => $g['discountprice'],
                            'isdiscountunitprice' => $g['isdiscountunitprice'],
                            'discountunitprice'   => $g['discountunitprice']
                        );
                    }

                } else {

                    $goodsdata_temp[] = array(
                        'goodsid'             => $g['goodsid'],
                        'total'               => $g['total'],
                        'optionid'            => $g['optionid'],
                        'marketprice'         => $g['marketprice'],
                        'costprice'           => $g['costprice'],// TODO FFFFFFF
                        'merchid'             => $g['merchid'],
                        'cates'               => $g['cates'],
                        'discounttype'        => $g['discounttype'],
                        'isdiscountprice'     => $g['isdiscountprice'],
                        'discountprice'       => $g['discountprice'],
                        'isdiscountunitprice' => $g['isdiscountunitprice'],
                        'discountunitprice'   => $g['discountunitprice']
                    );
                }

                $goodsdata[] = array(
                    'goodsid'             => $g['goodsid'],
                    'total'               => $g['total'],
                    'unit'                => $g['unit'],
                    'optionid'            => $g['optionid'],
                    'marketprice'         => $g['marketprice'],
                    'costprice'           => $g['costprice'],// TODO FFFFFFF
                    'merchid'             => $g['merchid'],
                    'cates'               => $g['cates'],
                    'discounttype'        => $g['discounttype'],
                    'isdiscountprice'     => $g['isdiscountprice'],
                    'discountprice'       => $g['discountprice'],
                    'isdiscountunitprice' => $g['isdiscountunitprice'],
                    'discountunitprice'   => $g['discountunitprice'],
                    'thumb'               => $g['thumb'],
                    'isnodiscount'        => $g['isnodiscount'],
                    'dflag'               => $g['dflag'],
                    'title'               => $g['title'],
                    'optiontitle'         => $g['optiontitle'],
                    'unitprice'           => $g['unitprice'],
                    'totalmaxbuy'         => $g['totalmaxbuy'],
                    'minbuy'              => $g['minbuy'],
                );
            }
            // 已比对PC end


            if ($giftid) {

                $gift = array();

                $giftdata = pdo_fetch(
                    ' select ' .
                    '       giftgoodsid ' .
                    ' from ' . tablename('superdesk_shop_gift') .
                    ' where ' .
                    '       uniacid = ' . $_W['uniacid'] .
                    '       and id = ' . $giftid .
                    '       and status = 1 ' .
                    '       and starttime <= ' . time() .
                    '       and endtime >= ' . time() . ' '
                );

                if ($giftdata['giftgoodsid']) {

                    $giftgoodsid = explode(',', $giftdata['giftgoodsid']);

                    foreach ($giftgoodsid as $key => $value) {

                        $gift[$key]          = pdo_fetch(
                            ' select ' .
                            '       id as goodsid,title,thumb ' .
                            ' from ' . tablename('superdesk_shop_goods') .
                            ' where ' .
                            '       uniacid = ' . $_W['uniacid'] .
                            '       and status = 2 ' .
                            '       and id = ' . $value .
                            '       and deleted = 0 ');
                        $gift[$key]['total'] = $total;
                    }

                    $goodsdata = array_merge($goodsdata, $gift);
                }

            } else {

                $isgift    = 0;
                $gifts     = array();
                $giftgoods = array();

                $gifts = pdo_fetchall(
                    ' select ' .
                    '       id,goodsid,giftgoodsid,thumb,title ' .
                    ' from ' . tablename('superdesk_shop_gift') .
                    ' where ' .
                    '       uniacid = ' . $_W['uniacid'] .
                    '       and status = 1 ' .
                    '       and starttime <= ' . time() .
                    '       and endtime >= ' . time() .
                    '       and orderprice <= ' . $goodsprice .
                    '       and activity = 1 ');

                foreach ($gifts as $key => $value) {

                    $isgift    = 1;
                    $giftgoods = explode(',', $value['giftgoodsid']);

                    foreach ($giftgoods as $k => $val) {
                        $gifts[$key]['gift'][$k] = pdo_fetch(
                            ' select ' .
                            '       id,title,thumb,marketprice,costprice ' .
                            ' from ' . tablename('superdesk_shop_goods') .
                            ' where ' .
                            '       uniacid = ' . $_W['uniacid'] .
                            '       and status = 2 ' .
                            '       and id = ' . $val . ' ');
                    }
                    $gifttitle = $gifts[$key]['gift'][0]['title'];
                }
            }

            // TODO 标志 楼宇之窗 openid superdesk_shop_coupon_data 已处理
            $couponcount = com_run('coupon::consumeCouponCount', $_W['openid'], $_W['core_user'], $realprice, $merch_array, $goodsdata_temp);

            // 微信端
            if (empty($goodsdata_temp) || !$allow_sale) {
                $couponcount = 0;
            }
            // 微信端 

            // PC端
//            $shareAddress = false;
//
//            if ($_W['shopset']['trade']['shareaddress'] && is_weixin()) {
//                $account = WeAccount::create();
//                if (method_exists($account, 'getShareAddressConfig')) {
//                    $shareAddress = $account->getShareAddressConfig();
//                }
//            }
            // PC端

            $mustbind = 0;

            if (!empty($_W['shopset']['wap']['open'])
                && !empty($_W['shopset']['wap']['mustbind'])
                && empty($member['mobileverify'])
            ) {
                $mustbind = 1;
            }

            if ($is_openmerch == 1) {
                $merchs = $merch_plugin->getMerchs($merch_array);
            }

            $token                   = md5(microtime());
            $_SESSION['order_token'] = $token;

            $createInfo = array(
                'id'             => $id,
                'gdid'           => intval($_GPC['gdid']),
                'fromcart'       => $fromcart,
                'addressid'      => (!empty($address) && !$isverify && !$isvirtual ? $address['id'] : 0),
                'storeid'        => (!empty($carrier_list) && !$isverify && !$isvirtual ? $carrier_list[0]['id'] : 0),
                'invoiceid'      => (!empty($invoice) ? $invoice['id'] : 0),// TODO 发票
                'couponcount'    => $couponcount,
                'isvirtual'      => $isvirtual,
                'isverify'       => $isverify,
                'goods'          => $goodsdata,
                'merchs'         => $merchs,
                'orderdiyformid' => $orderdiyformid,
                'token'          => $token,
                'giftid'         => $giftid,
                'mustbind'       => $mustbind,
                'goodsprice'    => $goodsprice,
                'grprice'       => $grprice
            );
            $buyagain   = $buyagainprice;


        }// end if (!$packageid)

        else { //套餐活动

            $package_total = intval($_GPC['total']);

            $g = $_GPC['goods'];

            $g = json_decode(htmlspecialchars_decode($g, ENT_QUOTES), true);

            $package = pdo_fetch(
                ' SELECT * ' .
                ' FROM ' . tablename('superdesk_shop_package') .
                ' WHERE ' .
                '       uniacid = ' . $_W['uniacid'] .
                '       and id = ' . $packageid . ' ');
            $package = set_medias($package, array('thumb'));


            if (time() < $package['starttime']) {
                exit('套餐活动还未开始，请耐心等待!');
            }

            if ($package['endtime'] < time()) {
                exit('套餐活动已结束，谢谢您的关注，请浏览其他套餐或商品！');
            }

            $goods       = array();
            $goodsprice  = 0;
            $marketprice = 0;
            $total       = 0;

            foreach ($g as $key => $value) {
                $goods[$key] = pdo_fetch(
                    ' select id,title,thumb,' .
                    '   marketprice,costprice ' .
                    ' from ' . tablename('superdesk_shop_goods') . "\n" .
                    ' where ' .
                    '       id = ' . $value['goodsid'] .
                    '       and uniacid = ' . $_W['uniacid'] . ' ');


                $option       = array();
                $packagegoods = array();

                if (0 < $value['optionid']) {

                    $option                      = pdo_fetch(
                        ' select ' .
                        '       title,packageprice ' .
                        ' from ' . tablename('superdesk_shop_package_goods_option') .
                        ' where ' .
                        '       optionid = ' . $value['optionid'] .
                        '       and goodsid=' . $value['goodsid'] .
                        '       and uniacid = ' . $_W['uniacid'] .
                        '       and pid = ' . $packageid . ' ');
                    $goods[$key]['packageprice'] = $option['packageprice'];

                } else {

                    $packagegoods                = pdo_fetch(
                        ' select ' .
                        '       title,packageprice ' .
                        ' from ' . tablename('superdesk_shop_package_goods') .
                        ' where ' .
                        '       goodsid=' . $value['goodsid'] .
                        '       and uniacid = ' . $_W['uniacid'] .
                        '       and pid = ' . $packageid . ' ');
                    $goods[$key]['packageprice'] = $packagegoods['packageprice'];

                }

                $goods[$key]['optiontitle'] = ((!empty($option['title']) ? $option['title'] : ''));
                $goods[$key]['optionid']    = ((!empty($value['optionid']) ? $value['optionid'] : 0));
                $goods[$key]['goodsid']     = $value['goodsid'];
                $goods[$key]['total']       = $package_total; // package 数量


                if ($option) {
                    $goods[$key]['packageprice'] = $option['packageprice'];
                } else {
//                    $goods[$key]['packageprice'] = $goods[$key]['packageprice'];
                }


                $goodsprice  += $goods[$key]['packageprice'] * $package_total;
                $marketprice += $goods[$key]['marketprice'] * $package_total;
                $total       += $goods[$key]['total'];
            }

            $address = $this->_member_addressModel->getByDefault($_W['uniacid'], $_W['openid'], $_W['core_user']);

//            $total                   = count($goods);
            $dispatch_price          = $package['freight'];
            $realprice               = $goodsprice + $package['freight'];
            $grprice                 = $goodsprice;
            $token                   = md5(microtime());
            $_SESSION['order_token'] = $token;

            $createInfo = array(
                'id'             => 0,
                'gdid'           => intval($_GPC['gdid']),
                'fromcart'       => 0,
                'packageid'      => $packageid,
                'addressid'      => $address['id'],
                'storeid'        => 0,
                'couponcount'    => 0,
                'isvirtual'      => 0,
                'isverify'       => 0,
                'goods'          => $goods,
                'merchs'         => 0,
                'orderdiyformid' => 0,
                'token'          => $token,
                'mustbind'       => 0,
                'goodsprice'    => $goodsprice,
                'grprice'       => $grprice
            );
        } // end 套餐活动


        // new add
        $createInfo['hasinvoice'] = $hasinvoice;

        $_W['shopshare']['hideMenus'] = array(
            'menuItem:share:qq',
            'menuItem:share:QZone',
            'menuItem:share:email',
            'menuItem:copyUrl',
            'menuItem:openWithSafari',
            'menuItem:openWithQQBrowser',
            'menuItem:share:timeline',
            'menuItem:share:appMessage'
        );

        $result = [
            'merchname'          => $_W['shopset']['shop']['name'],
            'credittext'         => $_W['shopset']['trade']['credittext'],
            'moneytext'          => $_W['shopset']['trade']['moneytext'],
            'showenough'         => $saleset['showenough'],
            'enoughmoney'        => $saleset['enoughmoney'],
            'enoughdeduct'       => $saleset['enoughdeduct'],
            'merch_showenough'   => $merch_saleset['merch_showenough'],
            'merch_enoughmoney'  => $merch_saleset['merch_enoughmoney'],
            'merch_enoughdeduct' => $merch_saleset['merch_enoughdeduct'],
            'invoiceid'          => (!empty($invoice) ? $invoice['id'] : 0),
            'goods'              => $createInfo['goods'],
            'merchs'             => $createInfo['merchs'],
            'goodsprice'         => $createInfo['goodsprice'],
        ];

        $realprice  = round(floatval($realprice), 2);
        $goodsprice = round(floatval($goodsprice), 2);
        $grprice = round(floatval($grprice), 2);

        $result = array_merge(
            $result,
            compact(
                'isverify', 'isvirtual', 'merch_id', 'changenum', 'hasinvoice', 'total', 'goodsprice', 'grprice',
                'couponcount', 'realprice', 'deductcredit', 'deductmoney', 'deductcredit2', 'weight', 'discountprice', 'dispatch_price',
                'isdiscountprice', 'stores', 'order_formInfo', 'address', 'carrier_list', 'CEDiscountPrice'
            )
        );

        show_json(1, $result);
    }

    /**
     * 计算使用优惠券后的价格
     */
    public function getcouponprice()
    {
        global $_GPC;

        $couponid        = intval($_GPC['couponid']);
        $goodsarr        = $_GPC['goods'];
        $goodsprice      = $_GPC['goodsprice'];
        $discountprice   = $_GPC['discountprice'];
        $isdiscountprice = $_GPC['isdiscountprice'];

        $result = $this->caculatecoupon($couponid, $goodsarr, $goodsprice, $discountprice, $isdiscountprice);

        if (empty($result)) {
            show_json(0);
        }

        show_json(1, $result);
    }


    /**
     * 计算优惠券
     *
     * @param       $couponid
     * @param       $goodsarr
     * @param       $totalprice
     * @param       $discountprice
     * @param       $isdiscountprice
     * @param int   $isSubmit
     * @param array $discountprice_array
     * @param int   $merchisdiscountprice
     *
     * @return array|bool
     */
    public function caculatecoupon($couponid, $goodsarr, $totalprice, $discountprice, $isdiscountprice, $isSubmit = 0, $discountprice_array = array(), $merchisdiscountprice = 0)
    {
        global $_W;

        if (empty($goodsarr)) {
            return false;
        }

        $sql =
            'SELECT ' .
            '   d.id,d.couponid,' .
            '   c.enough,c.backtype,c.deduct,c.discount,c.backmoney,c.backcredit,c.backredpack,c.merchid,' .
            '   c.limitgoodtype,c.limitgoodcatetype,c.limitgoodids,c.limitgoodcateids,c.limitdiscounttype  ' .
            ' FROM ' . tablename('superdesk_shop_coupon_data') . ' d' .// TODO 标志 楼宇之窗 openid shop_coupon_data 已处理
            ' left join ' . tablename('superdesk_shop_coupon') . ' c on d.couponid = c.id' .
            ' where ' .
            '   d.id=:id ' .
            '   and d.uniacid=:uniacid ' .
            '   and d.openid=:openid ' .
            '   and d.core_user=:core_user ' .
            '   and d.used=0 ' .
            ' limit 1';

        $data = pdo_fetch(
            $sql,
            array(
                ':id'        => $couponid,
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user']
            )
        );

        $merchid = intval($data['merchid']);

        if (empty($data)) {
            return;
        }

        if (is_array($goodsarr)) {

            //2019年5月24日 16:48:51 zjh 文礼 价套
            $goodsarr = m('goods')->getGoodsCategoryEnterpriseDiscountOne($goodsarr);

            $goods = array();


            // 过滤前商品数组 起到过滤作用
            foreach ($goodsarr as $good) {

                if (empty($good)) {
                    continue;
                }

                if ((0 < $merchid) && ($good['merchid'] != $merchid)) {
                    continue;
                }

                $cates        = explode(',', $good['cates']);
                $limitcateids = explode(',', $data['limitgoodcateids']);
                $limitgoodids = explode(',', $data['limitgoodids']);
                $pass         = 0;

                if (
                    ($data['limitgoodcatetype'] == 0) // 0 不添加商品分类限制
                    && ($data['limitgoodtype'] == 0) // 0 不添加商品限制
                ) {
                    $pass = 1;
                }

                // 过虑　
                // 商品分类使用限制 优惠券是否只能用于特定商品或商品类型
                // 0 不添加商品分类限制
                // 1 允许以下商品分类使用
                if ($data['limitgoodcatetype'] == 1) {

                    $result = array_intersect($cates, $limitcateids);

                    if (0 < count($result)) {
                        $pass = 1;
                    }
                }

                // 过虑
                // 商品使用限制
                // 0 不添加商品限制
                // 1 允许以下商品使用
                if ($data['limitgoodtype'] == 1) {

                    $isin = in_array($good['goodsid'], $limitgoodids);

                    if ($isin) {
                        $pass = 1;
                    }
                }

                if ($pass == 1) {
                    $goods[] = $good;
                }
            }


            $limitdiscounttype = intval($data['limitdiscounttype']);// 限制折扣类型
            $coupongoodprice   = 0;
            $gprice            = 0;

            // 过滤后商品数组 用于计算coupon
            foreach ($goods as $index => $good) {
                $gprice = (double)$good['marketprice'] * (double)$good['total'];


                // 设定页面 总后台 -> 营销 -> 优惠券 -> 优惠券管理 -> 添加 购物 优惠券
                // 优惠使用限制 优惠券是否可以与特定优惠同时使用
                // 0 不添加优惠使用限制
                // 1 不允许与促销优惠同时使用
                // 2 不允许与会员折扣同时使用
                // 3 不允许与促销优惠和会员折扣同时使用
                switch ($limitdiscounttype) {
                    case 1:// 1 不允许与促销优惠同时使用

                        $coupongoodprice += $gprice - ((double)$good['discountunitprice'] * (double)$good['total']);

                        $discountprice_array[$good['merchid']]['coupongoodprice'] += $gprice - ((double)$good['discountunitprice'] * (double)$good['total']);

                        if ($good['discounttype'] == 1) {

                            $isdiscountprice -= (double)$good['isdiscountunitprice'] * (double)$good['total'];
                            $discountprice   += (double)$good['discountunitprice'] * (double)$good['total'];

                            if ($isSubmit == 1) {

                                $totalprice                                               = ($totalprice - $good['ggprice']) + $good['price2'];
                                $discountprice_array[$good['merchid']]['ggprice']         = ($discountprice_array[$good['merchid']]['ggprice'] - $good['ggprice']) + $good['price2'];
                                $goodsarr[$index]['ggprice']                              = $good['price2'];
                                $discountprice_array[$good['merchid']]['isdiscountprice'] -= (double)$good['isdiscountunitprice'] * (double)$good['total'];
                                $discountprice_array[$good['merchid']]['discountprice']   += (double)$good['discountunitprice'] * (double)$good['total'];

                                if (!empty($data['merchsale'])) {
                                    $merchisdiscountprice                                          -= (double)$good['isdiscountunitprice'] * (double)$good['total'];
                                    $discountprice_array[$good['merchid']]['merchisdiscountprice'] -= (double)$good['isdiscountunitprice'] * (double)$good['total'];
                                }
                            }
                        }

                        break;

                    case 2:// 2 不允许与会员折扣同时使用

                        $coupongoodprice                                          += $gprice - ((double)$good['isdiscountunitprice'] * (double)$good['total']);
                        $discountprice_array[$good['merchid']]['coupongoodprice'] += $gprice - ((double)$good['isdiscountunitprice'] * (double)$good['total']);

                        if ($good['discounttype'] == 2) {

                            $discountprice -= (double)$good['discountunitprice'] * (double)$good['total'];

                            if ($isSubmit == 1) {

                                $totalprice                                             = ($totalprice - $good['ggprice']) + $good['price1'];
                                $discountprice_array[$good['merchid']]['ggprice']       = ($discountprice_array[$good['merchid']]['ggprice'] - $good['ggprice']) + $good['price1'];
                                $goodsarr[$index]['ggprice']                            = $good['price1'];
                                $discountprice_array[$good['merchid']]['discountprice'] -= (double)$good['discountunitprice'] * (double)$good['total'];

                            }

                        }
                        break;

                    case 3:// 3 不允许与促销优惠和会员折扣同时使用

                        $coupongoodprice                                          += $gprice;
                        $discountprice_array[$good['merchid']]['coupongoodprice'] += $gprice;

                        if ($good['discounttype'] == 1) {

                            $isdiscountprice -= (double)$good['isdiscountunitprice'] * (double)$good['total'];

                            if ($isSubmit == 1) {
                                $totalprice                                       = ($totalprice - $good['ggprice']) + $good['price0'];
                                $discountprice_array[$good['merchid']]['ggprice'] = ($discountprice_array[$good['merchid']]['ggprice'] - $good['ggprice']) + $good['price0'];
                                $goodsarr[$index]['ggprice']                      = $good['price0'];

                                if (!empty($data['merchsale'])) {
                                    $merchisdiscountprice                                          -= $good['isdiscountunitprice'] * (double)$good['total'];
                                    $discountprice_array[$good['merchid']]['merchisdiscountprice'] -= $good['isdiscountunitprice'] * (double)$good['total'];
                                }

                                $discountprice_array[$good['merchid']]['isdiscountprice'] -= $good['isdiscountunitprice'] * (double)$good['total'];
                            }

                        } else if ($good['discounttype'] == 2) {

                            $discountprice -= (double)$good['discountunitprice'] * (double)$good['total'];

                            if ($isSubmit == 1) {
                                $totalprice                                             = ($totalprice - $good['ggprice']) + $good['price0'];
                                $goodsarr[$index]['ggprice']                            = $good['price0'];
                                $discountprice_array[$good['merchid']]['ggprice']       = ($discountprice_array[$good['merchid']]['ggprice'] - $good['ggprice']) + $good['price0'];
                                $discountprice_array[$good['merchid']]['discountprice'] -= (double)$good['discountunitprice'] * (double)$good['total'];
                            }
                        }

                        break;

                    default:// 0 不添加优惠使用限制

                        if ($good['discounttype'] == 1) {

                            $coupongoodprice                                          += $gprice - ((double)$good['isdiscountunitprice'] * (double)$good['total']);
                            $discountprice_array[$good['merchid']]['coupongoodprice'] += $gprice - ((double)$good['isdiscountunitprice'] * (double)$good['total']);

                        } else if ($good['discounttype'] == 2) {

                            $coupongoodprice                                          += $gprice - ((double)$good['discountunitprice'] * (double)$good['total']);
                            $discountprice_array[$good['merchid']]['coupongoodprice'] += $gprice - ((double)$good['discountunitprice'] * (double)$good['total']);

                        } else if ($good['discounttype'] == 0) {

                            $coupongoodprice                                          += $gprice;
                            $discountprice_array[$good['merchid']]['coupongoodprice'] += $gprice;

                        }

                        break;
                }
            }


//            设定页面 总后台 -> 营销 -> 优惠券 -> 优惠券管理 -> 添加 购物 优惠券 -> 优惠方式
            $deduct   = (double)$data['deduct'];  // 立减->抵扣
            $discount = (double)$data['discount'];// 打折->折扣
            $backtype = (double)$data['backtype'];// 返利->返还类型 返现 返积分 返红包

            $deductprice       = 0;
            $coupondeduct_text = '';


            if ((0 < $deduct) && ($backtype == 0) && (0 < $coupongoodprice)) {

                if ($coupongoodprice < $deduct) {
                    $deduct = $coupongoodprice;
                }

                if ($deduct <= 0) {
                    $deduct = 0;
                }

                $deductprice       = $deduct;
                $coupondeduct_text = '优惠券优惠';

                foreach ($discountprice_array as $key => $value) {
                    $discountprice_array[$key]['deduct'] = ((double)$value['coupongoodprice'] / (double)$coupongoodprice) * $deduct;
                }

            } else if ((0 < $discount) && ($backtype == 1)) {

                $deductprice = $coupongoodprice * (1 - ($discount / 10));

                if ($coupongoodprice < $deductprice) {
                    $deductprice = $coupongoodprice;
                }

                if ($deductprice <= 0) {
                    $deductprice = 0;
                }

                foreach ($discountprice_array as $key => $value) {
                    $discountprice_array[$key]['deduct'] = (double)$value['coupongoodprice'] * (1 - ($discount / 10));
                }

                if (0 < $merchid) {
                    $coupondeduct_text = '店铺优惠券折扣(' . $discount . '折)';
                } else {
                    $coupondeduct_text = '优惠券折扣(' . $discount . '折)';
                }

            }
        }// end if (is_array($goodsarr))


        $totalprice -= $deductprice;

        $return_array = array();

        // 拆扣
        $return_array['isdiscountprice']     = $isdiscountprice;
        $return_array['discountprice']       = $discountprice;
        $return_array['discountprice_array'] = $discountprice_array;

        // 优惠券 后价格
        $return_array['coupongoodprice']   = $coupongoodprice;
        $return_array['coupondeduct_text'] = $coupondeduct_text;

        // 扣除价
        $return_array['deductprice'] = number_format($deductprice, 2);

        $return_array['totalprice'] = $totalprice;

        // 商户 优惠
        $return_array['merchisdiscountprice'] = $merchisdiscountprice;
        $return_array['couponmerchid']        = $merchid;

        $return_array['goodsarr'] = $goodsarr;
        // TODO mark 陈文礼 coupon

        return $return_array;
    }





//{
//    "totalprice": 382,
//    "addressid": "3",
//    "dflag": 0,
//    "goods": [
//        {
//            "goodsid": "594",
//            "total": "1",
//            "optionid": "0",
//            "marketprice": "382.00",
//            "merchid": "1",
//            "cates": "424",
//            "discounttype": 2,
//            "isdiscountprice": 0,
//            "discountprice": 0,
//            "isdiscountunitprice": 0,
//            "discountunitprice": 0
//        },
//        {
//            "goodsid": "865", 
//            "total": "1", 
//            "optionid": "0", 
//            "marketprice": "11.00", 
//            "merchid": "0", 
//            "cates": "39", 
//            "discounttype": 2, 
//            "isdiscountprice": 0, 
//            "discountprice": 0, 
//            "isdiscountunitprice": 0, 
//            "discountunitprice": 0
//        }
//    ]
//}
    /**
     * 前端:确认订单 页面 before 购物车点 结算
     * 可以认为这个是结算前计算与检查
     */
    public function caculate()
    {
        global $_W;
        global $_GPC;

        $merchdata = $this->merchData();
        extract($merchdata);

        $merch_array       = array();
        $allow_sale        = true;
        $realprice         = 0;
        $nowsendfree       = false;
        $isverify          = false;
        $isvirtual         = false;
        $taskdiscountprice = 0;
        $discountprice     = 0;
        $isdiscountprice   = 0;
        $deductprice       = 0;
        $deductprice2      = 0;
        $deductcredit2     = 0;
        $buyagain_sale     = true;
        $buyagainprice     = 0;
        $goodsprice        = 0;
        $grprice           = 0;

        //2019年5月28日 18:24:26 zjh 价套折扣
        $CEDiscountPrice = 0;

        $totalprice = floatval($_GPC['totalprice']);
        $dflag      = $_GPC['dflag'];

        // 地址
        $addressid = intval($_GPC['addressid']);

        if (empty($addressid)) {
            show_json(0, '请先添加或选择收货地址');
        }

        $address = $this->_member_addressModel->getById($addressid, $_W['uniacid'], $_W['openid']);

        $member = m('member')->getMember($_W['openid'], $_W['core_user']);
        $level  = m('member')->getLevel($_W['openid'], $_W['core_user']);

        $weight = floatval($_GPC['weight']);

        // 运费
        $dispatchid            = intval($_GPC['dispatchid']);
        $dispatch_price        = 0;
        $dispatch_jd_vop_price = 0;
        $jd_vop_getFreight     = array(); // 运费

        $deductenough_money  = 0;
        $deductenough_enough = 0;
        $goodsarr            = $_GPC['goods'];

//[
//    {
//        "goodsid": "594", 
//        "total": "1", 
//        "optionid": "0", 
//        "marketprice": "382.00", 
//        "merchid": "1", 
//        "cates": "424", 
//        "discounttype": 2, 
//        "isdiscountprice": 0, 
//        "discountprice": 0, 
//        "isdiscountunitprice": 0, 
//        "discountunitprice": 0
//    }, 
//    {
//        "goodsid": "865", 
//        "total": "1", 
//        "optionid": "0", 
//        "marketprice": "11.00", 
//        "merchid": "0", 
//        "cates": "39", 
//        "discounttype": 2, 
//        "isdiscountprice": 0, 
//        "discountprice": 0, 
//        "isdiscountunitprice": 0, 
//        "discountunitprice": 0
//    }
//]

//        socket_log(json_encode($goodsarr,JSON_UNESCAPED_UNICODE));
        if (is_array($goodsarr)) {


            //2018年11月12日 10:37:46 zjh 京东校验
            $goodsarr = $this->checkJdVopCanBuy($goodsarr, $address);

            $weight   = 0;
            $allgoods = array();


            foreach ($goodsarr as &$g) {

                if (empty($g)) { // 空是什么情况
                    continue;
                }

                $goodsid    = $g['goodsid'];
                $optionid   = $g['optionid'];
                $goodstotal = $g['total'];

                if ($goodstotal < 1) {
                    $goodstotal = 1;
                }

                if (empty($goodsid)) {
                    $nowsendfree = true;
                }

                // superdesk_shop_goods
                $data = $this->_goodsModel->getById($_W['uniacid'], $goodsid);

                if (empty($data)) {
                    $nowsendfree = true;
                }

                if ($data['status'] == 2) {// 状态 0 下架 1 上架 2 赠品上架 | YES | tinyint(1) | 1
                    $data['marketprice'] = 0;// 赠品不要钱
                }

                // 这个是个死方法 返回 is_task_goods : 0 is_task_goods_option : 0
                $task_goods_data = m('goods')->getTaskGoods($_W['openid'], $goodsid, $optionid);

                if (empty($task_goods_data['is_task_goods'])) {
                    $data['is_task_goods'] = 0;
                } else {
                    $allow_sale                   = false;
                    $data['is_task_goods']        = $task_goods_data['is_task_goods'];
                    $data['is_task_goods_option'] = $task_goods_data['is_task_goods_option'];
                    $data['task_goods']           = $task_goods_data['task_goods'];
                }

                $data['stock'] = $data['total'];
                $data['total'] = $goodstotal;

                if (!empty($optionid)) {

                    $option = pdo_fetch(
                        ' select ' .
                        '       id,title,' .
                        '   marketprice,' .
                        '   costprice,' .
                        '   goodssn,productsn,stock,`virtual`,weight ' .
                        ' from ' . tablename('superdesk_shop_goods_option') .
                        ' where id=:id ' .
                        '       and goodsid=:goodsid ' .
                        '       and uniacid=:uniacid ' .
                        ' limit 1',
                        array(
                            ':uniacid' => $_W['uniacid'],
                            ':goodsid' => $goodsid,
                            ':id'      => $optionid
                        )
                    );

                    if (!empty($option)) {

                        $data['optionid']    = $optionid;
                        $data['optiontitle'] = $option['title'];
                        $data['marketprice'] = $option['marketprice'];
                        $data['costprice']   = $option['costprice'];

                        if (!empty($option['weight'])) {
                            $data['weight'] = $option['weight'];
                        }
                    }
                }

                $prices = m('order')->getGoodsDiscountPrice($data, $level);

                //2019年5月29日 14:33:31 zjh 价套
                $prices = m('order')->getGoodsCategoryEnterpriseDiscount($data,$prices);
                $CEDiscountPrice = round($CEDiscountPrice + $prices['CEDiscountPrice'], 2);

                $data['ggprice'] = $prices['price'];


                if ($is_openmerch == 1) {

                    $merchid                          = $data['merchid'];
                    $merch_array[$merchid]['goods'][] = $data['goodsid'];
                    $merch_array[$merchid]['ggprice'] += $data['ggprice'];

                }

                if ($data['isverify'] == 2) {
                    $isverify = true;
                }

                if (!empty($data['virtual']) || ($data['type'] == 2)) {
                    $isvirtual = true;
                }

                $g['taskdiscountprice'] = $prices['taskdiscountprice'];
                $g['discountprice']     = $prices['discountprice'];
                $g['isdiscountprice']   = $prices['isdiscountprice'];
                $g['discounttype']      = $prices['discounttype'];

                $taskdiscountprice += $prices['taskdiscountprice'];
                $buyagainprice     += $prices['buyagainprice'];

                if ($prices['discounttype'] == 1) {
                    $isdiscountprice += $prices['isdiscountprice'];
                } else if ($prices['discounttype'] == 2) {
                    $discountprice += $prices['discountprice'];
                }

                $realprice  += $data['ggprice'];
                $grprice    += $data['ggprice'];
                $goodsprice += $data['marketprice'] * $data['total'];

                $allgoods[] = $data;

                if ((0 < floatval($g['buyagain'])) && empty($g['buyagain_sale'])) {
                    if (m('goods')->canBuyAgain($g)) {
                        $buyagain_sale = false;
                    }
                }


            }

            unset($g);


            if ($is_openmerch == 1) { // 是否多商户
                foreach ($merch_array as $key => $value) {
                    if (0 < $key) {
                        $merch_array[$key]['set']     = $merch_plugin->getSet('sale', $key);
                        $merch_array[$key]['enoughs'] = $merch_plugin->getEnoughs($merch_array[$key]['set']);
                    }
                }
            }

            $sale_plugin = com('sale');// 组件 营销宝 sale
            $saleset     = false;

            if ($sale_plugin && $buyagain_sale && $allow_sale) {

                $saleset = $_W['shopset']['sale'];

                $saleset['enoughs'] = $sale_plugin->getEnoughs();
            }

            foreach ($allgoods as $g) {

                if ((0 < floatval($g['buyagain'])) && empty($g['buyagain_sale'])) {
                    if (m('goods')->canBuyAgain($g)) {
                        $g['deduct'] = 0;
                    }
                }

                if ($g['manydeduct']) {
                    $deductprice += $g['deduct'] * $g['total'];
                } else {
                    $deductprice += $g['deduct'];
                }

                // TODO 查 jd_vop 运费 start
                if ($g['jd_vop_sku'] != 0) {
                    $jd_vop_getFreight[] = array(
                        "skuId" => $g['jd_vop_sku'],
                        "num"   => $g['total']
                    );
                }
                // TODO 查 jd_vop 运费 end
            }


            if ($isverify || $isvirtual) {
                $nowsendfree = true;
            }


            if (!empty($allgoods) && !$nowsendfree) {

                // TODO 普通运费
                $dispatch_array = m('order')->getOrderDispatchPrice($allgoods, $member, $address, $saleset, $merch_array, 1);

                socket_log(json_encode($dispatch_array, JSON_UNESCAPED_UNICODE));

                $dispatch_price   = $dispatch_array['dispatch_price'];
                $nodispatch_array = $dispatch_array['nodispatch_array'];

                // TODO 发起 京东运费 请求 start
                if (sizeof($jd_vop_getFreight) > 0) {

//                  {"freight":6,"baseFreight":6,"remoteRegionFreight":0,"remoteSku":"[]"}
                    $dispath_jd_vop_array = $this->_orderService->getFreight(
                        $jd_vop_getFreight,
                        $address['jd_vop_province_code'],
                        $address['jd_vop_city_code'],
                        $address['jd_vop_county_code'],
                        $address['jd_vop_town_code']
                    );

                    $dispatch_jd_vop_price = $dispath_jd_vop_array['freight'];

                    $dispatch_price += $dispatch_jd_vop_price;
                }
                // TODO 发起 京东运费 请求 end

            }

            if ($is_openmerch == 1) {

                $merch_enough = m('order')->getMerchEnough($merch_array);

                $merch_array        = $merch_enough['merch_array'];
                $merch_enough_total = $merch_enough['merch_enough_total'];
                $merch_saleset      = $merch_enough['merch_saleset'];

                if (0 < $merch_enough_total) {
                    $realprice -= $merch_enough_total;
                }

            }


            if ($saleset) {
                foreach ($saleset['enoughs'] as $e) {
                    if ((floatval($e['enough']) <= $realprice) && (0 < floatval($e['money']))) {

                        $deductenough_money  = floatval($e['money']);
                        $deductenough_enough = floatval($e['enough']);

                        $realprice -= floatval($e['money']);
                        break;
                    }
                }
            }


            if ($dflag != 'true') {
            }


            $goodsdata_coupon = array();

            foreach ($allgoods as $g) {

                if (0 < floatval($g['buyagain'])) {

                    if (!m('goods')->canBuyAgain($g)
                        || !empty($g['buyagain_sale'])
                    ) {
                        $goodsdata_coupon[] = array(
                            'goodsid'             => $g['goodsid'],
                            'total'               => $g['total'],
                            'optionid'            => $g['optionid'],
                            'marketprice'         => $g['marketprice'],
                            'costprice'           => $g['costprice'],
                            'merchid'             => $g['merchid'],
                            'cates'               => $g['cates'],
                            'discounttype'        => $g['discounttype'],
                            'isdiscountprice'     => $g['isdiscountprice'],
                            'discountprice'       => $g['discountprice'],
                            'isdiscountunitprice' => $g['isdiscountunitprice'],
                            'discountunitprice'   => $g['discountunitprice']
                        );
                    }

                } else {

                    $goodsdata_coupon[] = array(
                        'goodsid'             => $g['goodsid'],
                        'total'               => $g['total'],
                        'optionid'            => $g['optionid'],
                        'marketprice'         => $g['marketprice'],
                        'costprice'           => $g['costprice'],
                        'merchid'             => $g['merchid'],
                        'cates'               => $g['cates'],
                        'discounttype'        => $g['discounttype'],
                        'isdiscountprice'     => $g['isdiscountprice'],
                        'discountprice'       => $g['discountprice'],
                        'isdiscountunitprice' => $g['isdiscountunitprice'],
                        'discountunitprice'   => $g['discountunitprice']
                    );

                }
            }

            // TODO 标志 楼宇之窗 openid superdesk_shop_coupon_data 已处理
            $couponcount = com_run('coupon::consumeCouponCount', $_W['openid'], $_W['core_user'], $realprice, $merch_array, $goodsdata_coupon);

            if (empty($goodsdata_coupon) || !$allow_sale) {
                $couponcount = 0;
            }

            $realprice += $dispatch_price;

            $deductcredit = 0;
            $deductmoney  = 0;


            if (!empty($saleset)) {
                $credit = $member['credit1'];
                if (!empty($saleset['creditdeduct'])) {

                    $pcredit = intval($saleset['credit']);
                    $pmoney  = round(floatval($saleset['money']), 2);

                    if ((0 < $pcredit) && (0 < $pmoney)) {
                        if (($credit % $pcredit) == 0) {
                            $deductmoney = round(intval($credit / $pcredit) * $pmoney, 2);
                        } else {
                            $deductmoney = round((intval($credit / $pcredit) + 1) * $pmoney, 2);
                        }
                    }

                    if ($deductprice < $deductmoney) {
                        $deductmoney = $deductprice;
                    }

                    if ($realprice < $deductmoney) {
                        $deductmoney = $realprice;
                    }

                    $deductcredit = ((($pmoney * $pcredit) == 0 ? 0 : ($deductmoney / $pmoney) * $pcredit));
                }
            }
        }

        if ($is_openmerch == 1) {
            $merchs = $merch_plugin->getMerchs($merch_array);
        }


        // TODO 暂定 检查 京东库存与价格
        if ($is_openmerch == 1) {
            foreach ($merchs as $index => $__merch__) {

//                {
//                    "merchid": 1,
//                    "goods": ["594"],
//                    "ggprice": 382,
//                    "url": "http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=order.create"
//                }

                if ($__merch__['merchid'] == SUPERDESK_SHOPV2_JD_VOP_MERCHID) {

                } else {
                    continue;
                }
            }
        }


        $return_array = array();

        $return_array['price']                     = $dispatch_price;
        $return_array['couponcount']               = $couponcount;
        $return_array['realprice']                 = $realprice;
        $return_array['deductenough_money']        = $deductenough_money;
        $return_array['deductenough_enough']       = $deductenough_enough;
        $return_array['deductcredit2']             = $deductcredit2;
        $return_array['deductcredit']              = $deductcredit;
        $return_array['deductmoney']               = $deductmoney;
        $return_array['taskdiscountprice']         = $taskdiscountprice;
        $return_array['discountprice']             = $discountprice;
        $return_array['isdiscountprice']           = $isdiscountprice;
        $return_array['merch_showenough']          = $merch_saleset['merch_showenough'];
        $return_array['merch_deductenough_money']  = $merch_saleset['merch_enoughdeduct'];
        $return_array['merch_deductenough_enough'] = $merch_saleset['merch_enoughmoney'];
        $return_array['merchs']                    = $merchs;
        $return_array['buyagain']                  = $buyagainprice;
        $return_array['CEDiscountPrice']            = $CEDiscountPrice;
        $return_array['goodsprice']            = $goodsprice;
        $return_array['grprice']                = $grprice;


        if (!empty($nodispatch_array['isnodispatch'])) {
            $return_array['isnodispatch'] = 1;
            $return_array['nodispatch']   = $nodispatch_array['nodispatch'];
        } else {
            $return_array['isnodispatch'] = 0;
            $return_array['nodispatch']   = '';
        }

        show_json(1, $return_array);
    }

    /**
     * 前端:前端:确认订单 页面 after 立即支付 按扭
     */
    public function submit()
    {
        global $_W;
        global $_GPC;


        $_debug = [
            'path'      => 'submit',
            'uniacid'   => $_W['uniacid'],
            'openid'    => $_W['openid'],
            'addressid' => $_GPC['addressid'],
            'invoiceid' => $_GPC['invoiceid'],
            'couponid'  => $_GPC['couponid'],
            'remark'    => $_GPC['remark'],
        ];

        socket_log($_debug);


        if (empty($_SESSION['order_token'])) {
            show_json(-1, '不要短时间重复下订单!');
        }

        unset($_SESSION['order_token']);

        $member = m('member')->getMember($_W['openid'], $_W['core_user']);


        socket_log('段点_黑名_前');
        if ($member['isblack'] == 1) {
            show_json(0, '请联系管理员，帐号异常');
        }


        socket_log('段点_绑定手机_前');
        if (empty($member)) {
            show_json(0, array(
                'message' => '请先绑定手机',
                'url'     => mobileUrl('member/bind', NULL, true)
            ));
        }

        // 绑定手机 逻辑
//        if (!empty($_W['shopset']['wap']['open'])
//            && !empty($_W['shopset']['wap']['mustbind'])
//            && empty($member['mobileverify'])
//        ) {
//
//        }


        $packageid = intval($_GPC['packageid']);

        $package   = array();// 套餐记录
        $packgoods = array();// 套餐商品

        $packageprice = 0;

        socket_log('段点001');


        // 套餐 处理
        // 排除 套餐 有ID 未找到套餐
        // 排除 套餐 有套餐 未找到套餐商品
        // 排除 套餐 套餐活动未开始 套餐活动已结束
        if (!empty($packageid)) {

            $package = pdo_fetch(
                ' SELECT id,title,price,freight,cash,starttime,endtime ' .
                ' FROM ' . tablename('superdesk_shop_package') .
                ' WHERE uniacid = ' . $_W['uniacid'] .
                '       and id = ' . $packageid .
                '       and deleted = 0 ' .
                '       and status = 1 ' .
                ' ORDER BY id DESC');

            if (empty($package)) {
                show_json(0, '未找到套餐！');
            }

            if (time() < $package['starttime']) {
                show_json(0, '套餐活动未开始，请耐心等待！');
            }

            if ($package['endtime'] < time()) {
                show_json(0, '套餐活动已结束，谢谢您的关注，请您浏览其他套餐或商品！');
            }

            $packgoods = pdo_fetchall(
                ' SELECT ' .
                '       id,title,thumb,packageprice,`option`,goodsid ' .
                ' FROM ' . tablename('superdesk_shop_package_goods') .
                ' WHERE ' .
                '       uniacid = ' . $_W['uniacid'] .
                '       and pid = ' . $packageid .
                ' ORDER BY id DESC');

            if (empty($packgoods)) {
                show_json(0, '未找到套餐商品！');
            }

        }


        $goods_from_db_full = $this->diyformData($member);
        extract($goods_from_db_full);

        $merchdata = $this->merchData();
        extract($merchdata);

        $merch_array         = array();
        $ismerch             = 0;
        $discountprice_array = array();

        $level = m('member')->getLevel($_W['openid'], $_W['core_user']);

        // 运费
        $dispatchid   = intval($_GPC['dispatchid']);
        $dispatchtype = intval($_GPC['dispatchtype']);

        socket_log('段点_地址前');

        // 收货地址
        $addressid = intval($_GPC['addressid']);
        $address   = false;


        if (empty($addressid)) {
            show_json(0, '请先添加或选择收货地址');
        }

        socket_log('段点_发票前');

        // 发票
        $invoiceid = SUPERDESK_SHOPV2_MODE_USER == 1 ? intval($_GPC['invoiceid']) : 0;
        $invoice   = false;

        if (!empty($addressid) && $dispatchtype == 0) {

            $address = $this->_member_addressModel->getById($addressid, $_W['uniacid'], $_W['openid']);

            if (empty($address)) {
                show_json(0, '未找到地址');
            }
        }


        // jd_vop 发票
        if (!empty($invoiceid)) {

            $invoice = $this->_member_invoiceModel->getOneByIdVerStrict($invoiceid, $_W['uniacid'], $_W['openid'], $_W['core_user']);

            if (empty($invoice)) {
                show_json(0, '未找到发票');
            }
        }
        // jd_vop 发票


        $carrierid          = intval($_GPC['carrierid']);
        $goods_from_cashier = $_GPC['goods'];

        socket_log('提交');
        socket_log($goods_from_cashier);

        $goods_from_cashier[0]['bargain_id'] = $_SESSION['bargain_id'];// 这是个傻B做法 原微信端中做法

        if (empty($goods_from_cashier) || !is_array($goods_from_cashier)) {
            show_json(0, '未找到任何商品');
        }


        //2018年11月12日 10:37:46 zjh 原先京东检查所在位置
        $goods_from_cashier = $this->checkJdVopCanBuy($goods_from_cashier, $address);


        $allgoods   = array();
        $tgoods     = array();// 这边没有
        $totalprice = 0;
        $goodsprice = 0;
        $grprice    = 0;
        $weight     = 0;

        $taskdiscountprice    = 0;// 这边没有
        $discountprice        = 0;
        $isdiscountprice      = 0;
        $merchisdiscountprice = 0;

        $cash         = 1;
        $deductprice  = 0;
        $deductprice2 = 0;

        $virtualsales = 0;

        // 运费
        $dispatch_price    = 0;
        $jd_vop_getFreight = array();

        $buyagain_sale = true;// 这边没有
        $buyagainprice = 0;// 这边没有

        $sale_plugin = com('sale');
        $saleset     = false;

        if ($sale_plugin) {
            $saleset            = $_W['shopset']['sale'];
            $saleset['enoughs'] = $sale_plugin->getEnoughs();
        }

        $isvirtual     = false;
        $isverify      = false;
        $verifytype    = 0;
        $isvirtualsend = false;
        $couponmerchid = 0;

        //2019年5月29日 09:55:53 zjh 价套
        $CEDiscountPrice = 0;


        $giftid = $_GPC['giftid'];

        if ($giftid) {

            $gift = array();

            $giftdata = pdo_fetch(
                ' select ' .
                '       giftgoodsid ' .
                ' from ' . tablename('superdesk_shop_gift') .
                ' where uniacid = ' . $_W['uniacid'] .
                ' and id = ' . $giftid .
                ' and status = 1 ' .
                ' and starttime <= ' . time() .
                ' and endtime >= ' . time() . ' ');

            if ($giftdata['giftgoodsid']) {

                $giftgoodsid = explode(',', $giftdata['giftgoodsid']);

                foreach ($giftgoodsid as $key => $value) {

                    $gift[$key] = pdo_fetch(
                        ' select ' .
                        '       id as goodsid,title,thumb ' .
                        ' from ' . tablename('superdesk_shop_goods') .
                        ' where ' .
                        '       uniacid = ' . $_W['uniacid'] .
                        '       and status = 2 ' .
                        '       and id = ' . $value .
                        '       and deleted = 0 '
                    );
                }

                $goods_from_cashier = array_merge($goods_from_cashier, $gift);
            }
        }

//        show_json(0,$goods);

        foreach ($goods_from_cashier as $goods_from_cashier_iterator) {

            if (empty($goods_from_cashier_iterator)) {
                continue;
            }

            $goodsid    = intval($goods_from_cashier_iterator['goodsid']);
            $optionid   = intval($goods_from_cashier_iterator['optionid']);
            $goodstotal = intval($goods_from_cashier_iterator['total']);

            if ($goodstotal < 1) {
                $goodstotal = 1;
            }
            if (empty($goodsid)) {
                show_json(0, '参数错误');
            }

            $goods_from_db_full = $this->_goodsModel->getById($_W['uniacid'], $goodsid);

            if ($goods_from_db_full['status'] == 2) { // 这个是赠品
                $goods_from_db_full['marketprice'] = 0;
            }

            if (empty($goods_from_db_full['status']) || !empty($goods_from_db_full['deleted'])) {
                show_json(0, $goods_from_db_full['title'] . '<br/> 已下架!');
            }

            /* task */
            $task_goods_data = m('goods')->getTaskGoods($_W['openid'], $goodsid, $optionid);

            if (empty($task_goods_data['is_task_goods'])) {

                $goods_from_db_full['is_task_goods'] = 0;

            } else {

                $tgoods['title']     = $goods_from_db_full['title'];
                $tgoods['openid']    = $_W['openid'];
                $tgoods['core_user'] = $_W['core_user'];
                $tgoods['goodsid']   = $goodsid;
                $tgoods['optionid']  = $optionid;
                $tgoods['total']     = $goodstotal;

                $goods_from_db_full['is_task_goods']        = $task_goods_data['is_task_goods'];
                $goods_from_db_full['is_task_goods_option'] = $task_goods_data['is_task_goods_option'];
                $goods_from_db_full['task_goods']           = $task_goods_data['task_goods'];
            }
            /* task */

            $merchid                          = $goods_from_db_full['merchid'];
            $merch_array[$merchid]['goods'][] = $goods_from_db_full['goodsid'];

            if (0 < $merchid) {
                $ismerch = 1;
            }

            $virtualid = $goods_from_db_full['virtual'];

            // 有坑
            $goods_from_db_full['stock'] = $goods_from_db_full['total'];
            $goods_from_db_full['total'] = $goodstotal;

            if ($goods_from_db_full['cash'] != 2) {
                $cash = 0;
            }

            if (!empty($packageid)) {
                $cash = $package['cash'];
            }

            $unit = ((empty($goods_from_db_full['unit']) ? '件' : $goods_from_db_full['unit']));

            if (0 < $goods_from_db_full['minbuy']) {
                if ($goodstotal < $goods_from_db_full['minbuy']) {
                    show_json(0, $goods_from_db_full['title'] . '<br/> ' . $goods_from_db_full['minbuy'] . $unit . '起售!');
                }
            }

            if (0 < $goods_from_db_full['maxbuy']) {
                if ($goods_from_db_full['maxbuy'] < $goodstotal) {
                    show_json(0, $goods_from_db_full['title'] . '<br/> 一次限购 ' . $goods_from_db_full['maxbuy'] . $unit . '!');
                }
            }

            if (0 < $goods_from_db_full['usermaxbuy']) {
                $order_goodscount = pdo_fetchcolumn(
                    ' select ifnull(sum(og.total),0) ' .
                    ' from ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 已处理
                    '       left join ' . tablename('superdesk_shop_order') . ' o on og.orderid=o.id ' .// TODO 标志 楼宇之窗 openid shop_order 已处理
                    ' where og.goodsid=:goodsid ' .
                    '       and o.status>=1 ' .
                    '       and o.openid=:openid ' .
                    '       and o.core_user=:core_user ' .
                    '       and og.uniacid=:uniacid ',
                    array(
                        ':goodsid'   => $goods_from_db_full['goodsid'],
                        ':uniacid'   => $_W['uniacid'],
                        ':openid'    => $_W['openid'],
                        ':core_user' => $_W['core_user']
                    )
                );

                if ($goods_from_db_full['usermaxbuy'] <= $order_goodscount) {
                    show_json(0, $goods_from_db_full['title'] . '<br/> 最多限购 ' . $goods_from_db_full['usermaxbuy'] . $unit . '!');
                }
            }

            /* task */
            if (!empty($goods_from_db_full['is_task_goods'])) {
                if ($goods_from_db_full['task_goods']['total'] < $goodstotal) {
                    show_json(0, $goods_from_db_full['title'] . '<br/> 任务活动优惠限购 ' . $goods_from_db_full['task_goods']['total'] . $unit . '!');
                }
            }
            /* task */

            if ($goods_from_db_full['istime'] == 1) {
                if (time() < $goods_from_db_full['timestart']) {
                    show_json(0, $goods_from_db_full['title'] . '<br/> 限购时间未到!');
                }
                if ($goods_from_db_full['timeend'] < time()) {
                    show_json(0, $goods_from_db_full['title'] . '<br/> 限购时间已过!');
                }
            }

            $levelid = intval($member['level']);
            $groupid = intval($member['groupid']);

            if ($goods_from_db_full['buylevels'] != '') {
                $buylevels = explode(',', $goods_from_db_full['buylevels']);
                if (!in_array($levelid, $buylevels)) {
                    show_json(0, '您的会员等级无法购买<br/>' . $goods_from_db_full['title'] . '!');
                }
            }

            if ($goods_from_db_full['buygroups'] != '') {
                $buygroups = explode(',', $goods_from_db_full['buygroups']);
                if (!in_array($groupid, $buygroups)) {
                    show_json(0, '您所在会员组无法购买<br/>' . $goods_from_db_full['title'] . '!');
                }
            }

            if (!empty($optionid)) { // 有规格

                $option = pdo_fetch(
                    ' select ' .
                    '       id,title,marketprice,costprice,goodssn,productsn,stock,`virtual`,weight ' .
                    ' from ' . tablename('superdesk_shop_goods_option') .
                    ' where id=:id ' .
                    '       and goodsid=:goodsid ' .
                    '       and uniacid=:uniacid ' .
                    ' limit 1',
                    array(
                        ':uniacid' => $_W['uniacid'],
                        ':goodsid' => $goodsid,
                        ':id'      => $optionid
                    )
                );

                if (!empty($option)) {


                    // 有规格 库存
                    if ($option['stock'] != -1) {
                        if (empty($option['stock'])) {
                            show_json(-1, $goods_from_db_full['title'] . '<br/>' . $option['title'] . ' 库存不足!');
                        }
                    }

                    // 有规格 修正
                    $goods_from_db_full['optionid']    = $optionid;
                    $goods_from_db_full['optiontitle'] = $option['title'];
                    $goods_from_db_full['marketprice'] = $option['marketprice'];
                    $goods_from_db_full['costprice']   = $option['costprice'];    //2019年3月21日 15:08:11 zjh 宇迪发现.有规格的情况下.存入的是商品本来的成本价而不是规格的成本价

                    // 有规格 有套餐 修正
                    $packageoption = array();
                    if ($packageid) { //

                        $packageoption = pdo_fetch(
                            ' select packageprice ' .
                            ' from ' . tablename('superdesk_shop_package_goods_option') .
                            ' where uniacid = ' . $_W['uniacid'] .
                            '       and goodsid = ' . $goodsid .
                            '       and optionid = ' . $optionid .
                            '       and pid = ' . $packageid . ' '
                        );

                        $goods_from_db_full['marketprice'] = $packageoption['packageprice'];
                        $packageprice                      += $packageoption['packageprice'] * $goodstotal;
                    }

                    $virtualid = $option['virtual'];

                    if (!empty($option['goodssn'])) {
                        $goods_from_db_full['goodssn'] = $option['goodssn'];
                    }

                    if (!empty($option['productsn'])) {
                        $goods_from_db_full['productsn'] = $option['productsn'];
                    }

                    if (!empty($option['weight'])) {
                        $goods_from_db_full['weight'] = $option['weight'];
                    }
                }

            } else if ($packageid) { // 无规格 有套餐 修正

                $pg = pdo_fetch(
                    ' select packageprice ' .
                    ' from ' . tablename('superdesk_shop_package_goods') .
                    ' where uniacid = ' . $_W['uniacid'] .
                    ' and goodsid = ' . $goodsid .
                    ' and pid = ' . $packageid . ' '
                );

                $goods_from_db_full['marketprice'] = $pg['packageprice'];
                $packageprice                      += $pg['packageprice'] * $goodstotal;

            } else if ($goods_from_db_full['stock'] != -1) { // 这边是有个修正 total -> stock 从收银台的数量 放到 total上

                if (empty($goods_from_db_full['stock'])) {
                    show_json(0, $goods_from_db_full['title'] . '<br/>库存不足!');
                }

            }

            $goods_from_db_full['diyformdataid'] = 0;
            $goods_from_db_full['diyformdata']   = iserializer(array());
            $goods_from_db_full['diyformfields'] = iserializer(array());

            if (intval($_GPC['fromcart']) == 1) {
                if ($diyform_plugin) {

                    $cartdata = pdo_fetch(
                        ' select ' .
                        '       id,diyformdataid,diyformfields,diyformdata ' .
                        ' from ' . tablename('superdesk_shop_member_cart') . ' ' . // TODO 标志 楼宇之窗 openid superdesk_shop_member_cart 已处理
                        ' where ' .
                        '       goodsid=:goodsid ' .
                        '       and optionid=:optionid ' .
                        '       and openid=:openid ' .
                        '       and core_user=:core_user ' .
                        '       and deleted=0 ' .
                        ' order by id desc ' .
                        ' limit 1',
                        array(
                            ':goodsid'   => $goods_from_db_full['goodsid'],
                            ':optionid'  => intval($goods_from_db_full['optionid']),
                            ':openid'    => $_W['openid'],
                            ':core_user' => $_W['core_user'],
                        )
                    );

                    if (!empty($cartdata)) {
                        $goods_from_db_full['diyformdataid'] = $cartdata['diyformdataid'];
                        $goods_from_db_full['diyformdata']   = $cartdata['diyformdata'];
                        $goods_from_db_full['diyformfields'] = $cartdata['diyformfields'];
                    }

                }

            } else if (!empty($goods_from_db_full['diyformtype']) && $diyform_plugin) {

                $temp_data = $diyform_plugin->getOneDiyformTemp($_GPC['gdid'], 0);

                $goods_from_db_full['diyformfields'] = $temp_data['diyformfields'];
                $goods_from_db_full['diyformdata']   = $temp_data['diyformdata'];

                if ($goods_from_db_full['diyformtype'] == 2) {

                    $goods_from_db_full['diyformid'] = 0;

                } else {
//                    $data['diyformid'] = $data['diyformid'];// TODO BUG
                }
            }

            $gprice     = $goods_from_db_full['marketprice'] * $goodstotal;
            $goodsprice += $gprice;
            $prices     = m('order')->getGoodsDiscountPrice($goods_from_db_full, $level);

            //2019年5月29日 14:33:31 zjh 价套
            $prices = m('order')->getGoodsCategoryEnterpriseDiscount($goods_from_db_full,$prices);
            $CEDiscountPrice = round($CEDiscountPrice + $prices['CEDiscountPrice'], 2);


            $goods_from_db_full['ggprice']             = $prices['price'];
            $goods_from_db_full['taskdiscountprice']   = $prices['taskdiscountprice'];
            $goods_from_db_full['discountprice']       = $prices['discountprice'];
            $goods_from_db_full['discountprice']       = $prices['discountprice'];
            $goods_from_db_full['discounttype']        = $prices['discounttype'];
            $goods_from_db_full['isdiscountunitprice'] = $prices['isdiscountunitprice'];
            $goods_from_db_full['discountunitprice']   = $prices['discountunitprice'];
            $goods_from_db_full['price0']              = $prices['price0'];
            $goods_from_db_full['price1']              = $prices['price1'];
            $goods_from_db_full['price2']              = $prices['price2'];
            $goods_from_db_full['buyagainprice']       = $prices['buyagainprice'];
            $goods_from_db_full['category_enterprise_discount']       = $prices['CEDiscountPrice'];


            $buyagainprice     += $prices['buyagainprice'];
            $taskdiscountprice += $prices['taskdiscountprice'];


            if ($prices['discounttype'] == 1) {

                $isdiscountprice += $prices['isdiscountprice'];
                $discountprice   += $prices['discountprice'];

                if (!empty($goods_from_db_full['merchsale'])) {
                    $merchisdiscountprice                                  += $prices['isdiscountprice'];
                    $discountprice_array[$merchid]['merchisdiscountprice'] += $prices['isdiscountprice'];
                }

                $discountprice_array[$merchid]['isdiscountprice'] += $prices['isdiscountprice'];
            } else if ($prices['discounttype'] == 2) {
                $discountprice                                  += $prices['discountprice'];
                $discountprice_array[$merchid]['discountprice'] += $prices['discountprice'];
            }

            $discountprice_array[$merchid]['category_enterprise_discount'] += $prices['CEDiscountPrice'];

            $discountprice_array[$merchid]['ggprice'] += $prices['ggprice'];
            $merch_array[$merchid]['ggprice']         += $goods_from_db_full['ggprice'];
            $totalprice                               += $goods_from_db_full['ggprice'];


            if ($goods_from_db_full['isverify'] == 2) {
                $isverify   = true;
                $verifytype = $goods_from_db_full['verifytype'];
            }

            if (!empty($goods_from_db_full['virtual']) || ($goods_from_db_full['type'] == 2)) {
                $isvirtual = true;
                if ($goods_from_db_full['virtualsend']) {
                    $isvirtualsend = true;
                }
            }

            if ((0 < floatval($goods_from_db_full['buyagain'])) && empty($goods_from_db_full['buyagain_sale'])) {
                if (m('goods')->canBuyAgain($goods_from_db_full)) {
                    $goods_from_db_full['deduct'] = 0;
                    $saleset                      = false;
                }
            }

            if ($goods_from_db_full['manydeduct']) {
                $deductprice += $goods_from_db_full['deduct'] * $goods_from_db_full['total'];
            } else {
                $deductprice += $goods_from_db_full['deduct'];
            }

            $virtualsales += $goods_from_db_full['sales'];
            $allgoods[]   = $goods_from_db_full;
        } // end iterator $goods

        $grprice = $totalprice;

        if ((1 < count($goods_from_cashier)) && !empty($tgoods)) {
            show_json(0, '任务活动优惠商品' . $tgoods['title'] . '不能放入购物车下单,请单独购买');
        }

        if (empty($allgoods)) {
            show_json(0, '未找到任何商品');
        }

        $couponid = intval($_GPC['couponid']);


        // 满减
        if ($is_openmerch == 1) {
            foreach ($merch_array as $key => $value) {
                if (0 < $key) {
                    $merch_array[$key]['set']     = $merch_plugin->getSet('sale', $key);
                    $merch_array[$key]['enoughs'] = $merch_plugin->getEnoughs($merch_array[$key]['set']);
                }
            }
            if (empty($goods_from_cashier[0]['bargain_id']) || !p('bargain')) {


                $merch_enough       = m('order')->getMerchEnough($merch_array);
                $merch_array        = $merch_enough['merch_array'];
                $merch_enough_total = $merch_enough['merch_enough_total'];
                $merch_saleset      = $merch_enough['merch_saleset'];


                if (0 < $merch_enough_total) {
                    $totalprice -= $merch_enough_total;
                }
            }
        }

        $deductenough = 0;

        if ($saleset) {
            foreach ($saleset['enoughs'] as $e) {
                if ((floatval($e['enough']) <= $totalprice) && (0 < floatval($e['money']))) {
                    $deductenough = floatval($e['money']);
                    if ($totalprice < $deductenough) {
                        $deductenough = $totalprice;
                    }
                    break;
                }
            }
        }

        $merch_enough_total = 0;

        if ($is_openmerch == 1) {
            foreach ($merch_array as $key => $value) {
                if (0 < $key) {
                    $merch_array[$key]['set']     = $merch_plugin->getSet('sale', $key);
                    $merch_array[$key]['enoughs'] = $merch_plugin->getEnoughs($merch_array[$key]['set']);
                }
            }

            $merch_enough = m('order')->getMerchEnough($merch_array);

            $merch_array        = $merch_enough['merch_array'];
            $merch_enough_total = $merch_enough['merch_enough_total'];
            $merch_saleset      = $merch_enough['merch_saleset'];


        }

        $goodsdata_coupon      = array();
        $goodsdata_coupon_temp = array();

        foreach ($allgoods as $goods_from_cashier_iterator) {

            if (0 < floatval($goods_from_cashier_iterator['buyagain'])) {

                if (!m('goods')->canBuyAgain($goods_from_cashier_iterator) || !empty($goods_from_cashier_iterator['buyagain_sale'])) {
                    $goodsdata_coupon[] = $goods_from_cashier_iterator;
                } else {
                    $goodsdata_coupon_temp[] = $goods_from_cashier_iterator;
                }

            } else {
                $goodsdata_coupon[] = $goods_from_cashier_iterator;
            }

            // TODO 查 jd_vop 运费 start
            if ($goods_from_cashier_iterator['jd_vop_sku'] != 0) {
                $jd_vop_getFreight[] = array(
                    "skuId" => $goods_from_cashier_iterator['jd_vop_sku'],
                    "num"   => $goods_from_cashier_iterator['total']
                );
            }
            // TODO 查 jd_vop 运费 end

        }

        $return_array = $this->caculatecoupon(
            $couponid,
            $goodsdata_coupon,
            $totalprice,
            $discountprice,
            $isdiscountprice,
            1,
            $discountprice_array,
            $merchisdiscountprice
        );

        $couponprice     = 0;
        $coupongoodprice = 0;

        if (!empty($return_array)) {

            $isdiscountprice      = $return_array['isdiscountprice'];
            $discountprice        = $return_array['discountprice'];
            $couponprice          = $return_array['deductprice'];
            $totalprice           = $return_array['totalprice'];
            $discountprice_array  = $return_array['discountprice_array'];
            $merchisdiscountprice = $return_array['merchisdiscountprice'];
            $coupongoodprice      = $return_array['coupongoodprice'];
            $couponmerchid        = $return_array['couponmerchid'];
            $allgoods             = $return_array['goodsarr'];      //zjh 2018年8月22日 14:50:38 $return_array['$goodsarr'] -> $return_array['goodsarr']
            $allgoods             = array_merge($allgoods, $goodsdata_coupon_temp);

//            <b>Warning</b>:  array_merge(): Argument #1 is not an array in <b>/data/wwwroot/wxn.avic-s.com/addons/superdesk_shopv2/core/mobile/order/create.php</b> on line <b>2772</b><br />

        }


        if (!$isvirtual
            && !$isverify
            && ($dispatchtype == 0)
        ) {

            // TODO 普通运费
            $dispatch_array = m('order')->getOrderDispatchPrice($allgoods, $member, $address, $saleset, $merch_array, 2);
//            {"dispatch_price":0,"dispatch_merch":[0,0],"nodispatch_array":[]}
            $dispatch_price   = $dispatch_array['dispatch_price'];
            $nodispatch_array = $dispatch_array['nodispatch_array'];

//            show_json(0,$dispatch_array);

            if (!empty($nodispatch_array['isnodispatch'])) {
                show_json(0, $nodispatch_array['nodispatch']);
            }

            // TODO 发起 京东运费 请求 start
            if (sizeof($jd_vop_getFreight) > 0) {

                // {"freight":6,"baseFreight":6,"remoteRegionFreight":0,"remoteSku":"[]"}
                $dispath_jd_vop_array = $this->_orderService->getFreight(
                    $jd_vop_getFreight,
                    $address['jd_vop_province_code'],
                    $address['jd_vop_city_code'],
                    $address['jd_vop_county_code'],
                    $address['jd_vop_town_code']
                );

                $dispatch_jd_vop_price = $dispath_jd_vop_array['freight'];

                $dispatch_price                                                    += $dispatch_jd_vop_price;
                $dispatch_array['dispatch_price']                                  = $dispatch_array['dispatch_price'] + $dispatch_jd_vop_price;
                $dispatch_array['dispatch_merch'][SUPERDESK_SHOPV2_JD_VOP_MERCHID] = $dispatch_jd_vop_price;
            }
            // TODO 发起 京东运费 请求 end

//            show_json(0,$dispatch_array);
            // {"dispatch_price":6,"dispatch_merch":[0,6],"nodispatch_array":[]}

        }


        // PC端有的优惠 start
//        $couponid = intval($_GPC['couponid']);
//        if (com("coupon") && !(empty($couponid))) {
//            $coupon = com('coupon')->getCouponByDataID($couponid);
//            if (!(empty($coupon))) {
//
//                if (0 < $coupon['merchid']) {
//                    $couponmerchid = $coupon['merchid'];
//                    $ggprice       = $merch_array[$coupon['merchid']]['ggprice'];
//                } else {
//                    $ggprice = $totalprice;
//                }
//
//                if (($coupon['enough'] <= $ggprice) && empty($coupon['used'])) {
//                    if ($coupon['backtype'] == 0) {
//                        if (0 < $coupon['deduct']) {
//                            $couponprice = $coupon['deduct'];
//                        }
//                    } else if ($coupon['backtype'] == 1) {
//                        if (0 < $coupon['discount']) {
//                            $couponprice = $ggprice * (1 - ($coupon['discount'] / 10));
//                        }
//                    }
//                }
//
//                if (0 < $couponprice) {
//                    $totalprice -= $couponprice;
//                }
//            }
//        }
        // PC端有的优惠 end

        if (empty($goods_from_cashier[0]['bargain_id'])
            || !p('bargain')
        ) {
            $totalprice -= $deductenough;
//            $totalprice -= $merch_enough_total;   //2018年9月10日 11:15:12 zjh 根据排查这里有重复满减的嫌疑.屏蔽
        }

        $totalprice += $dispatch_price;

        if ($saleset
            && empty($saleset['dispatchnodeduct'])
        ) {
            $deductprice2 += $dispatch_price;
        }

        if (empty($goods_from_cashier[0]['bargain_id'])
            || !p('bargain')
        ) {

            $deductcredit  = 0;
            $deductmoney   = 0;
            $deductcredit2 = 0;

            if ($sale_plugin) {

                if (!empty($_GPC['deduct'])) {

                    $credit = $member['credit1'];

                    if (!empty($saleset['creditdeduct'])) {

                        $pcredit = intval($saleset['credit']);
                        $pmoney  = round(floatval($saleset['money']), 2);

                        if ((0 < $pcredit) && (0 < $pmoney)) {
                            if (($credit % $pcredit) == 0) {
                                $deductmoney = round(intval($credit / $pcredit) * $pmoney, 2);
                            } else {
                                $deductmoney = round((intval($credit / $pcredit) + 1) * $pmoney, 2);
                            }
                        }

                        if ($deductprice < $deductmoney) {
                            $deductmoney = $deductprice;
                        }

                        if ($totalprice < $deductmoney) {
                            $deductmoney = $totalprice;
                        }
                        $deductcredit = round(($deductmoney / $pmoney) * $pcredit, 2);
                    }
                }

                $totalprice -= $deductmoney;
            }
        }


        $verifyinfo  = array();
        $verifycode  = '';
        $verifycodes = array();


        // isverify 支持线下核销 Null 0 1 不支持 2 支持
        // dispatchtype 配送类型 0 运费模板 1 统一邮费
        if ($isverify
            || $dispatchtype
        ) {

            if ($isverify) {
                // verifytype v2 核销类型 0 按订单核销 1 按次核销 2 按消费码核销
                if (($verifytype == 0) || ($verifytype == 1)) {

                    $verifycode = random(8, true);

                    while (1) {
                        $count = pdo_fetchcolumn(
                            ' select count(*) ' .
                            ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 不处理
                            ' where ' .
                            '       verifycode=:verifycode ' .
                            '       and uniacid=:uniacid ' .
                            ' limit 1',
                            array(
                                ':verifycode' => $verifycode,
                                ':uniacid'    => $_W['uniacid']
                            )
                        );

                        if ($count <= 0) {
                            break;
                        }

                        $verifycode = random(8, true);
                    }

                } // verifytype v2 核销类型 0 按订单核销 1 按次核销 2 按消费码核销
                else if ($verifytype == 2) {

                    $totaltimes = intval($allgoods[0]['total']);

                    if ($totaltimes <= 0) {
                        $totaltimes = 1;
                    }

                    $i = 1;

                    while ($i <= $totaltimes) {

                        $verifycode = random(8, true);

                        while (1) {
                            $count = pdo_fetchcolumn(
                                ' select count(*) ' .
                                ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 不处理
                                ' where ' .
                                '       concat(verifycodes,\'|\' + verifycode +\'|\' ) like :verifycodes ' .
                                '       and uniacid=:uniacid ' .
                                ' limit 1',
                                array(
                                    ':verifycodes' => '%' . $verifycode . '%',
                                    ':uniacid'     => $_W['uniacid']
                                )
                            );

                            if ($count <= 0) {
                                break;
                            }

                            $verifycode = random(8, true);
                        }

                        $verifycodes[] = '|' . $verifycode . '|';
                        $verifyinfo[]  = array(
                            'verifycode'    => $verifycode,
                            'verifyopenid'  => '',
                            'verifytime'    => 0,
                            'verifystoreid' => 0
                        );

                        ++$i;
                    }
                }

            } // 配送类型 0 运费模板 1 统一邮费
            else if ($dispatchtype) {

                $verifycode = random(8, true);

                while (1) { //
                    $count = pdo_fetchcolumn(
                        ' select count(*) ' .
                        ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 不处理
                        ' where verifycode=:verifycode and uniacid=:uniacid limit 1',
                        array(
                            ':verifycode' => $verifycode,
                            ':uniacid'    => $_W['uniacid']
                        )
                    );
                    if ($count <= 0) {
                        break;
                    }
                    $verifycode = random(8, true);
                }
            }
        }


        $carrier  = $_GPC['carriers'];
        $carriers = ((is_array($carrier) ? iserializer($carrier) : iserializer(array())));

        if ($totalprice <= 0) {
            $totalprice = 0;
        }

        if (($ismerch == 0)
            || (($ismerch == 1) && (count($merch_array) == 1))
        ) {
            $multiple_order = 0;
        } else {
            $multiple_order = 1;
        }

        if (0 < $ismerch) {
            $ordersn = m('common')->createNO('order', 'ordersn', 'ME');
        } else {
            $ordersn = m('common')->createNO('order', 'ordersn', 'SH');
        }

        if (!empty($goods_from_cashier[0]['bargain_id']) && p('bargain')) {

            $bargain_act = pdo_fetch(
                ' SELECT * ' .
                ' FROM ' . tablename('superdesk_shop_bargain_actor') .
                ' WHERE id = :id AND openid = :openid ',
                array(
                    ':id'     => $goods_from_cashier[0]['bargain_id'],
                    ':openid' => $_W['openid']
                )
            );

            if (empty($bargain_act)) {
                exit('没有这个商品');
            }

            $totalprice = $bargain_act['now_price'];
            if (!pdo_update('superdesk_shop_bargain_actor',
                array('status' => 1),
                array(
                    'id'     => $goods_from_cashier[0]['bargain_id'],
                    'openid' => $_W['openid']
                )
            )
            ) {
                exit('下单失败');
            }

            $ordersn = substr_replace($ordersn, 'KJ', 0, 2);
        }


        $is_package = 0;

        if (!empty($packageid)) {

            $goodsprice     = $packageprice;
            $dispatch_price = $package['freight'];
            $totalprice     = $packageprice + $package['freight'];
            $is_package     = 1;
        }

        $_serial_number = time();

        $order             = array();
        $order['ismerch']  = $ismerch;
        $order['parentid'] = 0;

        $order['uniacid']   = $_W['uniacid'];
        $order['openid']    = $_W['openid'];
        $order['core_user'] = $_W['core_user'];

        $order['ordersn']              = $ordersn;
        $order['price']                = $totalprice;
        $order['oldprice']             = $totalprice;
        $order['grprice']              = $grprice;
        $order['taskdiscountprice']    = $taskdiscountprice;
        $order['discountprice']        = $discountprice;
        $order['isdiscountprice']      = $isdiscountprice;
        $order['merchisdiscountprice'] = $merchisdiscountprice;
        $order['cash']                 = $cash;
        $order['status']               = 0;
        $order['remark']               = trim($_GPC['remark']);
        $order['addressid']            = ((empty($dispatchtype) ? $addressid : 0)); // 收货地址 id
        $order['invoiceid']            = $invoiceid;                                // 发票信息 id
        $order['goodsprice']           = $goodsprice;


        $order['olddispatchprice'] = $dispatch_price;// TODO 运费
        $order['dispatchprice']    = $dispatch_price;// TODO 运费
        $order['dispatchtype']     = $dispatchtype;
        $order['dispatchid']       = $dispatchid;


        $order['storeid']    = $carrierid;
        $order['carrier']    = $carriers;
        $order['createtime'] = $_serial_number;

        $order['couponid']          = $couponid;
        $order['couponmerchid']     = $couponmerchid;
        $order['paytype']           = 0;
        $order['deductprice']       = $deductmoney;
        $order['deductcredit']      = $deductcredit;
        $order['deductcredit2']     = $deductcredit2;
        $order['deductenough']      = $deductenough;
        $order['merchdeductenough'] = $merch_enough_total;
        $order['couponprice']       = $couponprice;
        $order['merchshow']         = 0;
        $order['buyagainprice']     = $buyagainprice;
        $order['ispackage']         = $is_package;
        $order['packageid']         = $packageid;
        $source_from                = trim($_GPC['source_from']);
        $order['source_from']       = empty($source_from) ? 'pc' : $source_from;

        // 2019年5月29日 09:54:38 zjh 价套
        $order['category_enterprise_discount'] = $CEDiscountPrice;

        // 企业名称 2018年9月11日 15:13:10 zjh 应杨宇迪要求添加
        // mark welfare
        switch (SUPERDESK_SHOPV2_MODE_USER) {
            case 1:// 1 超级前台

                include_once(IA_ROOT . '/addons/superdesk_core/service/VirtualarchitectureService.class.php');
                $_virtualarchitectureService     = new VirtualarchitectureService();
                $curr_virtualarchitecture        = $_virtualarchitectureService->getOneByVirtualArchId($member['core_enterprise']);
                $order['member_enterprise_name'] = $curr_virtualarchitecture['name'];
                $order['member_enterprise_id']   = $member['core_enterprise'];
                $order['member_organization_id'] = $curr_virtualarchitecture['organizationId'];

                break;
            case 2:// 2 福利商城

                include_once(IA_ROOT . '/addons/superdesk_shopv2/model/__plugin__/enterprise/enterprise_user.class.php');
                $_enterprise_userModel           = new enterprise_userModel();
                $enterprise                      = $_enterprise_userModel->getOne($member['core_enterprise']);
                $order['member_enterprise_name'] = $enterprise['enterprise_name'];
                $order['member_enterprise_id']   = $member['core_enterprise'];

                break;
        }


        $author = p('author');
        if ($author) {

            $author_set = $author->getSet();
            if (!empty($member['agentid']) && !empty($member['authorid'])) {
                $order['authorid'] = $member['authorid'];
            }

            if (!empty($author_set['selfbuy'])
                && !empty($member['isauthor'])
                && !empty($member['authorstatus'])
            ) {
                $order['authorid'] = $member['id'];
            }

        }


        // 只向一个商户购买
        if ($multiple_order == 0) {

            $order_merchid          = current(array_keys($merch_array));
            $order['merchid']       = intval($order_merchid);
            $order['isparent']      = 0;
            $order['transid']       = '';
            $order['isverify']      = (($isverify ? 1 : 0));
            $order['verifytype']    = $verifytype;
            $order['verifycode']    = $verifycode;
            $order['verifycodes']   = implode('', $verifycodes);
            $order['verifyinfo']    = iserializer($verifyinfo);
            $order['virtual']       = $virtualid;
            $order['isvirtual']     = (($isvirtual ? 1 : 0));
            $order['isvirtualsend'] = (($isvirtualsend ? 1 : 0));


        } else { // 向多个商户购买

            $order['isparent'] = 1;
            $order['merchid']  = 0;

        }


        if ($diyform_plugin) {
            if (is_array($_GPC['diydata']) && !empty($order_formInfo)) {


                $diyform_data = $diyform_plugin->getInsertData($fields, $_GPC['diydata']);
                $idata        = $diyform_data['data'];


                $order['diyformfields'] = iserializer($fields);
                $order['diyformdata']   = $idata;
                $order['diyformid']     = $order_formInfo['id'];

            }
        }

        // 收货地址
        if (!empty($address)) {
            $order['address'] = iserializer($address);
        }

        // 发票信息
        if (!empty($invoice)) {
            $order['invoice'] = iserializer($invoice);
        }

        // TODO 重要标记 主订单生成
        //mark kafka 为了kafka转成了model执行
        $orderid = $this->_orderModel->insert($order);// 主订单生成

        if (!empty($goods_from_cashier[0]['bargain_id']) && p('bargain')) {
            pdo_update('superdesk_shop_bargain_actor',
                array(
                    'order' => $orderid
                ),
                array(
                    'id'     => $goods_from_cashier[0]['bargain_id'],
                    'openid' => $_W['openid']
                )
            );
        }


        // 结论:这个是如果在多商家那买东西是会分订单的


        /** 产生订单 单个订单 start **/
        if ($multiple_order == 0) {

//            show_json(0,"产生订单 单个订单 start");
//            show_json(0,$allgoods);

            $jd_vop_sku_id_2_shop_goods_id       = array();
            $jd_vop_submit_params_sku            = array();
            $jd_vop_submit_params_orderPriceSnap = array();
            $jd_vop_submit_params_remark         = $order['remark'];

            foreach ($allgoods as $_goods_iterator) {


                $order_goods = array();

                if (!empty($bargain_act) && p('bargain')) {
                    $_goods_iterator['total'] = 1;
                }


                $order_goods['merchid']    = $_goods_iterator['merchid'];
                $order_goods['merchsale']  = $_goods_iterator['merchsale'];
                $order_goods['uniacid']    = $_W['uniacid'];
                $order_goods['orderid']    = $orderid;
                $order_goods['goodsid']    = $_goods_iterator['goodsid'];
                $order_goods['price']      = $_goods_iterator['marketprice'] * $_goods_iterator['total'];
                $order_goods['total']      = $_goods_iterator['total'];
                $order_goods['optionid']   = $_goods_iterator['optionid'];
                $order_goods['createtime'] = $_serial_number;
                $order_goods['optionname'] = $_goods_iterator['optiontitle'];
                $order_goods['goodssn']    = $_goods_iterator['goodssn'];
                $order_goods['productsn']  = $_goods_iterator['productsn'];
                $order_goods['realprice']  = $_goods_iterator['ggprice'];
                $order_goods['oldprice']   = $_goods_iterator['ggprice'];
                $order_goods['costprice']  = $_goods_iterator['costprice'];    //zjh 2018年10月30日 17:13:21 陈康俊
                // TODO costprice 成本价记录

                //2019年5月29日 10:02:42 zjh 价套
                $order_goods['category_enterprise_discount'] = $_goods_iterator['category_enterprise_discount'];

                if ($_goods_iterator['discounttype'] == 1) {
                    $order_goods['isdiscountprice'] = $_goods_iterator['isdiscountprice'];
                } else {
                    $order_goods['isdiscountprice'] = 0;
                }

                $order_goods['openid']    = $_W['openid'];
                $order_goods['core_user'] = $_W['core_user'];

                if ($diyform_plugin) {
                    if ($_goods_iterator['diyformtype'] == 2) {
                        $order_goods['diyformid'] = 0;
                    } else {
                        $order_goods['diyformid'] = $_goods_iterator['diyformid'];
                    }
                    $order_goods['diyformdata']   = $_goods_iterator['diyformdata'];
                    $order_goods['diyformfields'] = $_goods_iterator['diyformfields'];
                }

                if (0 < floatval($_goods_iterator['buyagain'])) {
                    if (!m('goods')->canBuyAgain($_goods_iterator)) {
                        $order_goods['canbuyagain'] = 1;
                    }
                }

                //2018年11月16日 09:50:09 zjh 订单商品静态化的需求..#1745
                $goods_static                = $this->_goodsModel->getOne($_goods_iterator['goodsid']);
                $order_goods['goods_static'] = iserializer($goods_static);

                //mark kafka 为了kafka转成了model执行
                $this->_order_goodsModel->insert($order_goods);

                // TODO jd_vop start
                if ($_goods_iterator['merchid'] == SUPERDESK_SHOPV2_JD_VOP_MERCHID) {

                    $jd_vop_sku_id_2_shop_goods_id[$_goods_iterator['jd_vop_sku']] = $_goods_iterator['goodsid'];

                    require_once(IA_ROOT . '/addons/superdesk_shopv2/service/util/setting/SettingService.class.php');
                    $SettingService = new SettingService();
                    $bNeedGift      = $SettingService->getSettingColumn();

                    $jd_vop_submit_params_sku[] = array(
                        "skuId"      => $_goods_iterator['jd_vop_sku'],
                        "num"        => $_goods_iterator['total'],
                        "bNeedAnnex" => true, // bNeedAnnex 表示是否需要附件，默认每个订单都给附件，默认值为：true，如果客户实在不需要附件bNeedAnnex可以给false，该参数配置为false时请谨慎，真的不会给客户发附件的;
                        "bNeedGift"  => $bNeedGift  // bNeedGift  表示是否需要增品，默认不给增品，默认值为：false，如果需要增品bNeedGift请给true,建议该参数都给true,但如果实在不需要增品可以给false;
                    );

                    $jd_vop_submit_params_orderPriceSnap[] = array(
                        "price" => $_goods_iterator['costprice'],
                        "skuId" => $_goods_iterator['jd_vop_sku']
                    );
                }
                // TODO jd_vop end
            }


            socket_log('产生订单 单个订单情况 -> ' . json_encode($address));

            // TODO submit jd_vop order start
            if (sizeof($jd_vop_submit_params_sku) > 0) {

                $_submit_result = $this->_orderService->submitOrder(
                    $orderid,
                    $ordersn,
                    $jd_vop_submit_params_sku,
                    $jd_vop_submit_params_orderPriceSnap,
                    $address,
                    $jd_vop_submit_params_remark,
                    $jd_vop_sku_id_2_shop_goods_id
                );

                $this->rollBack($_submit_result, $multiple_order, $orderid);

            }
            // TODO submit jd_vop order end


        } /** 产生订单 单个订单情况 end **/

        /** 产生订单 多个订单情况 start **/
        else {

//            show_json(0,"产生订单 多个订单情况 start");

            $og_array      = array();
            $ch_order_data = m('order')->getChildOrderPrice($order, $allgoods, $dispatch_array, $merch_array, $sale_plugin, $discountprice_array);


            $target_merch_orderid = 0;
            $target_merch_ordersn = "";

            foreach ($merch_array as $key => $value) {

                $order['ordersn'] = m('common')->createNO('order', 'ordersn', 'ME');
                $merchid          = $key;


                $order['merchid']    = $merchid;
                $order['parentid']   = $orderid;
                $order['isparent']   = 0;
                $order['merchshow']  = 1;
                $order['createtime'] = $_serial_number;

                // 运费
                $order['dispatchprice']    = $dispatch_array['dispatch_merch'][$merchid];
                $order['olddispatchprice'] = $dispatch_array['dispatch_merch'][$merchid];

                $order['merchisdiscountprice'] = $discountprice_array[$merchid]['merchisdiscountprice'];
                $order['isdiscountprice']      = $discountprice_array[$merchid]['isdiscountprice'];
                $order['discountprice']        = $discountprice_array[$merchid]['discountprice'];

                $order['price']             = $ch_order_data[$merchid]['price'];
                $order['grprice']           = $ch_order_data[$merchid]['grprice'];
                $order['goodsprice']        = $ch_order_data[$merchid]['goodsprice'];
                $order['deductprice']       = $ch_order_data[$merchid]['deductprice'];
                $order['deductcredit']      = $ch_order_data[$merchid]['deductcredit'];
                $order['deductcredit2']     = $ch_order_data[$merchid]['deductcredit2'];
                $order['merchdeductenough'] = $ch_order_data[$merchid]['merchdeductenough'];
                $order['deductenough']      = $ch_order_data[$merchid]['deductenough'];

                $order['coupongoodprice'] = $discountprice_array[$merchid]['coupongoodprice'];
                $order['couponprice']     = $discountprice_array[$merchid]['deduct'];

                // 2019年5月29日 09:54:38 zjh 价套
                $order['category_enterprise_discount'] = $discountprice_array[$merchid]['category_enterprise_discount'];

                if (empty($order['couponprice'])) {
                    $order['couponid']      = 0;
                    $order['couponmerchid'] = 0;
                } else if (0 < $couponmerchid) {
                    if ($merchid == $couponmerchid) {
                        $order['couponid']      = $couponid;
                        $order['couponmerchid'] = $couponmerchid;
                    } else {
                        $order['couponid']      = 0;
                        $order['couponmerchid'] = 0;
                    }
                }
                //$order['couponprice'] = $ch_order_data[$merchid]['couponprice'];

                // TODO 重要标记 子订单生成
                //mark kafka 为了kafka转成了model执行
                $ch_orderid = $this->_orderModel->insert($order);


                $merch_array[$merchid]['orderid'] = $ch_orderid;

                // jd_vop aop start TODO
                if ($merchid == SUPERDESK_SHOPV2_JD_VOP_MERCHID) {
                    $target_merch_orderid = $ch_orderid;
                    $target_merch_ordersn = $order['ordersn'];
                }
                // jd_vop aop end


                if (0 < $couponmerchid) {
                    if ($merchid == $couponmerchid) {
                        $couponorderid = $ch_orderid;
                    }
                }

                foreach ($value['goods'] as $k => $_goods_id) {
                    $og_array[$_goods_id] = $ch_orderid;// goodsid -> ch_order_id
                }
            }

            $jd_vop_sku_id_2_shop_goods_id       = array();
            $jd_vop_submit_params_sku            = array();
            $jd_vop_submit_params_orderPriceSnap = array();
            $jd_vop_submit_params_remark         = "";

            foreach ($allgoods as $goods_from_cashier) {


                $goodsid = $goods_from_cashier['goodsid'];

                $order_goods                    = array();
                $order_goods['parentorderid']   = $orderid;
                $order_goods['merchid']         = $goods_from_cashier['merchid'];
                $order_goods['merchsale']       = $goods_from_cashier['merchsale'];
                $order_goods['orderid']         = $og_array[$goodsid];
                $order_goods['uniacid']         = $_W['uniacid'];
                $order_goods['openid']          = $_W['openid'];
                $order_goods['core_user']       = $_W['core_user'];
                $order_goods['goodsid']         = $goodsid;
                $order_goods['price']           = $goods_from_cashier['marketprice'] * $goods_from_cashier['total'];
                $order_goods['total']           = $goods_from_cashier['total'];
                $order_goods['optionid']        = $goods_from_cashier['optionid'];
                $order_goods['createtime']      = $_serial_number;
                $order_goods['optionname']      = $goods_from_cashier['optiontitle'];
                $order_goods['goodssn']         = $goods_from_cashier['goodssn'];
                $order_goods['productsn']       = $goods_from_cashier['productsn'];
                $order_goods['realprice']       = $goods_from_cashier['ggprice'];
                $order_goods['oldprice']        = $goods_from_cashier['ggprice'];
                $order_goods['costprice']       = $goods_from_cashier['costprice'];    //zjh 2018年10月30日 17:13:21 陈康俊 // TODO costprice 成本价记录
                $order_goods['isdiscountprice'] = $goods_from_cashier['isdiscountprice'];

                //2019年5月29日 10:02:42 zjh 价套
                $order_goods['category_enterprise_discount'] = $goods_from_cashier['category_enterprise_discount'];

                if ($diyform_plugin) {
                    if ($goods_from_cashier['diyformtype'] == 2) {
                        $order_goods['diyformid'] = 0;
                    } else {
                        $order_goods['diyformid'] = $goods_from_cashier['diyformid'];
                    }
                    $order_goods['diyformdata']   = $goods_from_cashier['diyformdata'];
                    $order_goods['diyformfields'] = $goods_from_cashier['diyformfields'];
                }

                if (0 < floatval($goods_from_cashier['buyagain'])) {
                    if (!m('goods')->canBuyAgain($goods_from_cashier)) {
                        $order_goods['canbuyagain'] = 1;
                    }
                }

                //2018年11月16日 09:50:09 zjh 订单商品静态化的需求..#1745
                $goods_static                = $this->_goodsModel->getOne($goods_from_cashier['goodsid']);
                $order_goods['goods_static'] = iserializer($goods_static);

                //mark kafka 为了kafka转成了model执行
                $this->_order_goodsModel->insert($order_goods);


                // jd_vop start
                if ($goods_from_cashier['merchid'] == SUPERDESK_SHOPV2_JD_VOP_MERCHID) {

                    $jd_vop_sku_id_2_shop_goods_id[$goods_from_cashier['jd_vop_sku']] = $goods_from_cashier['goodsid'];

                    require_once(IA_ROOT . '/addons/superdesk_shopv2/service/util/setting/SettingService.class.php');
                    $SettingService = new SettingService();
                    $bNeedGift      = $SettingService->getSettingColumn();

                    $jd_vop_submit_params_sku[] = array(
                        "skuId"      => $goods_from_cashier['jd_vop_sku'],
                        "num"        => $goods_from_cashier['total'],
                        "bNeedAnnex" => true, // bNeedAnnex 表示是否需要附件，默认每个订单都给附件，默认值为：true，如果客户实在不需要附件bNeedAnnex可以给false，该参数配置为false时请谨慎，真的不会给客户发附件的;
                        "bNeedGift"  => $bNeedGift  // bNeedGift  表示是否需要增品，默认不给增品，默认值为：false，如果需要增品bNeedGift请给true,建议该参数都给true,但如果实在不需要增品可以给false;
                    );

                    $jd_vop_submit_params_orderPriceSnap[] = array(
                        "price" => $goods_from_cashier['costprice'],
                        "skuId" => $goods_from_cashier['jd_vop_sku']
                    );

                }
                // jd_vop end
            }


            // submit jd_vop order start
            if (sizeof($jd_vop_submit_params_sku) > 0) {

                $_submit_result = $this->_orderService->submitOrder(
                    $target_merch_orderid,
                    $target_merch_ordersn,
                    $jd_vop_submit_params_sku,
                    $jd_vop_submit_params_orderPriceSnap,
                    $address,
                    $jd_vop_submit_params_remark,
                    $jd_vop_sku_id_2_shop_goods_id
                );

                $this->rollBack($_submit_result, $multiple_order, $orderid);

            }
            // submit jd_vop order end


        }
        /** 产生订单 多个订单情况 end **/

        if (is_array($carrier)) {
            $up = array(
                'realname'       => $carrier['carrier_realname'],
                'carrier_mobile' => $carrier['carrier_mobile']
            );

            pdo_update(
                'superdesk_shop_member',// TODO 标志 楼宇之窗 openid superdesk_shop_member 不处理
                $up,
                array(
                    'id'      => $member['id'],
                    'uniacid' => $_W['uniacid']
                )
            );

            if (!empty($member['uid'])) {
                load()->model('mc');
                mc_update($member['uid'], $up);
            }
        }


        // 成功提单后删除购物车
        if ($_GPC['fromcart'] == 1) {

            $this->_member_cartModel->emptyCart($_W['uniacid'], $_W['openid'], $_W['core_user']);
        }

        if (0 < $deductcredit) {
            m('member')->setCredit($_W['openid'], $_W['core_user'],
                'credit1',
                -$deductcredit,
                array('0', $_W['shopset']['shop']['name'] . '购物积分抵扣 消费积分: ' . $deductcredit . ' 抵扣金额: ' . $deductmoney . ' 订单号: ' . $ordersn));
        }

        if (0 < $buyagainprice) {

            m('goods')->useBuyAgain($orderid);

        }

        if (empty($virtualid)) {

            m('order')->setStocksAndCredits($orderid, 0);

        } else if (isset($allgoods[0])) {

            $vgoods = $allgoods[0];

            pdo_update(
                'superdesk_shop_goods',
                array(
                    'sales' => $vgoods['sales'] + $vgoods['total']
                ),
                array(
                    'id' => $vgoods['goodsid']
                )
            );
        }


        $plugincoupon = com('coupon');

        if ($plugincoupon) {
            if ((0 < $couponmerchid) && ($multiple_order == 1)) {
                $oid = $couponorderid;
            } else {
                $oid = $orderid;
            }
            $plugincoupon->useConsumeCoupon($oid);
        }


        if (!empty($tgoods)) {
            m('goods')->getTaskGoods($tgoods['openid'], $tgoods['goodsid'], $tgoods['optionid'], $tgoods['total']);
        }


        m('notice')->sendOrderMessage($orderid);
        com_run('printer::sendOrderMessage', $orderid);


        $pluginc = p('commission');
        if ($pluginc) {

            if ($multiple_order == 0) {

                $pluginc->checkOrderConfirm($orderid);

            } else if (!empty($merch_array)) {

                foreach ($merch_array as $key => $value) {

                    $pluginc->checkOrderConfirm($value['orderid']);
                }
            }
        }

        unset($_SESSION[$_W['openid'] . '_order_create']);

        show_json(1, array('orderid' => $orderid));
    }

    /**
     * 不是真正意义上的 rollback , 只是把不正常的订单数据清除
     *
     * @param $_submit_result
     * @param $multiple_order
     * @param $orderid
     */
    public function rollBack($_submit_result, $multiple_order, $orderid)
    {
        global $_W;
        global $_GPC;

        socket_log("京东出错#start#" . json_encode($_submit_result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '# end #');

//        $success = false, $resultMessage = '', $resultCode = '0000', $result = false, $code = 200


        if ($_submit_result['success'] == false) {


            // 清理数据
            $this->_orderService->rollBackOrder($multiple_order, $orderid);


            $_status  = 0;
            $_message = '订单提交出错了';

            switch (intval($_submit_result['resultCode'])) {

                case 2006:
//                    {
//                        "success": false,
//                        "resultMessage": "无有效增票资质，下单失败",
//                        "resultCode": "2006",
//                        "result": null,
//                        "code": 200
//                    }

                    $_message = $_submit_result['resultMessage'];
                    break;

                case 3004:

                    //success false;
                    //resultCode 3004;
                    //resultMessage [5729524]商品已下架，不能下单 ;
//                    success false;
//                    resultCode 3004;
//                    resultMessage [586565]商品是品类限购，不能下单 ;

//                    $_status = intval($_submit_result['resultCode']) ;
//                    $_message= $_submit_result['resultCode'] . ':' . $_submit_result['resultMessage'];


                    $result = array();
                    preg_match_all("/(?:\[)(.*)(?:\])/i", $_submit_result['resultMessage'], $result);
                    $_sku_id = intval($result[1][0]);

                    //2018年11月23日 15:49:46 zjh 新发现了 4683579支持预占天数不满足预占要求 的情况
                    //出现这种情况的商品 : 上鲜 鸡米花/盐酥鸡 500g/袋 出口日本级 清真食品 日系原味 炸鸡 速冻食品 快餐鸡肉
                    if (empty($_sku_id)) {
                        preg_match('/\d+/', $_submit_result['resultMessage'], $result);
                        $_sku_id = intval($result[0]);
                    }

                    $_sku_fake_shop_cart = $this->_goodsModel->getBySKuIdForShopCart($_W['uniacid'], $_sku_id);

                    foreach ($_sku_fake_shop_cart as $index => &$item) {
                        $item['state_msg'] = preg_replace("/(?:\[)(.*)(?:\])/i", '', $_submit_result['resultMessage']);// 修正
//                        $item['state_msg'] = '商品下架';
                    }
                    unset($item);

                    // 转换 为 /* 下架 处理 */ 900 state_msg
                    $_status  = 900;
                    $_message = $_sku_fake_shop_cart;

                    break;
                case 3008:
//                    京东出错
//                    #start#
//                    {
//                        "success": false,
//                        "resultMessage": "编号为7965591的附件无货，主商品为:7792550",
//                        "resultCode": "3008",
//                        "result": null,
//                        "code": 200
//                    }
                    # end #
                    //success false;
                    //resultCode 3008;
                    //resultMessage 编号为2586166的赠品无货，主商品为:929693;
//                    success false;
//                    resultCode 3008;
//                    resultMessage 编号为7788763的商品无货;

//                    $_status = intval($_submit_result['resultCode']) ;
//                    $_message= $_submit_result['resultCode'] . ':' . $_submit_result['resultMessage'];

                    $result = array();
                    //preg_match_all("/(?:\:)(.*)(?:\;)/i", $_submit_result['resultMessage'], $result); //原先的处理方式 zjh 2018年10月16日 19:31:27 修正
                    $resultMessage = $_submit_result['resultMessage'];
                    if (!empty(strstr($resultMessage, '，'))) {
                        $resultMessage = strstr($resultMessage, '，');
                    }
                    preg_match('/\d+/', $resultMessage, $result);
                    $_sku_id = intval($result[0]);

                    $_sku_fake_shop_cart = $this->_goodsModel->getBySKuIdForShopCart($_W['uniacid'], $_sku_id);

                    foreach ($_sku_fake_shop_cart as $index => &$item) {
                        $item['stock_msg'] = strpos($_submit_result['resultMessage'], '赠') ? '赠品无货' : (strpos($_submit_result['resultMessage'], '附') ? '附件无货' : '商品无货');// 修正
//                        $item['stock_msg'] = '赠品无货';// 修正
                    }
                    unset($item);

                    // 转换 为 /* 库存 处理 */ 901 stock_msg
                    $_status  = 901;
                    $_message = $_sku_fake_shop_cart;

                    break;
                case 3009:

                    //success false;
                    //resultCode 3009;
                    //resultMessage 【4838382】商品在该配送区域内受限;

//                    $_status = intval($_submit_result['resultCode']) ;
//                    $_message= $_submit_result['resultCode'] . ':' . $_submit_result['resultMessage'];

                    $result = array();
                    preg_match_all("/(?:\【)(.*)(?:\】)/i", $_submit_result['resultMessage'], $result);
                    $_sku_id             = intval($result[1][0]);
                    $_sku_fake_shop_cart = $this->_goodsModel->getBySKuIdForShopCart($_W['uniacid'], $_sku_id);

                    foreach ($_sku_fake_shop_cart as $index => &$item) {
//                        $item['stock_msg'] = $_submit_result['resultMessage'];// 修正
                        $item['stock_msg'] = '区域受限';// 修正
                    }
                    unset($item);

                    // 转换 为 /* 库存 处理 */ 901 stock_msg
                    $_status  = 901;
                    $_message = $_sku_fake_shop_cart;

                    break;
                case 3017:

                    //success false;
                    //resultCode 3017;
                    //resultMessage 您的余额不足;

                    // 转换 为 0
                    $_status = 0;
//                    $_message = $_submit_result['resultCode'] . ':' . $_submit_result['resultMessage'];
                    $_message = $_submit_result['resultCode'] . ':' . '系统繁忙，请稍后再试!';
                    break;
                case 3026:
//                    京东出错
//                    #start#{
//                        "success": false,
//                        "resultMessage": "来晚啦，促销商品数量不足啦。别灰心，请返回购物车删除抢购商品后再提交订单吧！",
//                        "resultCode": "3026",
//                        "result": null,
//                        "code": 200
//                    }\n# end #
                    $_status  = 0;
                    $_message = $_submit_result['resultCode'] . ':' . $_submit_result['resultMessage'];
                    break;
                default:
                    $_status  = 0;
                    $_message = $_submit_result['resultCode'] . ':' . $_submit_result['resultMessage'];
            }

            show_json(
                $_status,
                $_message
            );

        }


    }

    protected function singleDiyformData($id = 0)
    {
        global $_W;
        global $_GPC;

        $goods_data     = false;
        $diyformtype    = false;
        $diyformid      = 0;
        $diymode        = 0;
        $formInfo       = false;
        $goods_data_id  = 0;
        $diyform_plugin = p('diyform');

        if ($diyform_plugin && !empty($id)) {
            $sql =
                ' SELECT ' .
                '       id as goodsid,type,diyformtype,diyformid,diymode,diyfields ' .
                ' FROM ' . tablename('superdesk_shop_goods') .
                ' where ' .
                '       id=:id ' .
                '       and uniacid=:uniacid ' .
                ' limit 1';

            $goods_data = pdo_fetch($sql,
                array(
                    ':uniacid' => $_W['uniacid'],
                    ':id'      => $id
                )
            );

            if (!empty($goods_data)) {


                $diyformtype = $goods_data['diyformtype'];
                $diyformid   = $goods_data['diyformid'];
                $diymode     = $goods_data['diymode'];


                if ($goods_data['diyformtype'] == 1) {

                    $formInfo = $diyform_plugin->getDiyformInfo($diyformid);

                } else if ($goods_data['diyformtype'] == 2) {

                    $fields = iunserializer($goods_data['diyfields']);

                    if (!empty($fields)) {
                        $formInfo = array('fields' => $fields);
                    }
                }
            }
        }

        return array(
            'goods_data'     => $goods_data,
            'diyformtype'    => $diyformtype,
            'diyformid'      => $diyformid,
            'diymode'        => $diymode,
            'formInfo'       => $formInfo,
            'goods_data_id'  => $goods_data_id,
            'diyform_plugin' => $diyform_plugin
        );
    }

    public function diyform()
    {
        global $_W;
        global $_GPC;

        $goodsid = intval($_GPC['id']);
        $cartid  = intval($_GPC['cartid']);

        $data = $this->singleDiyformData($goodsid);
        extract($data);


        if ($diyformtype == 2) {
            $diyformid = 0;
        } else {
            $diyformid = $goods_data['diyformid'];
        }


        $fields      = $formInfo['fields'];
        $insert_data = $diyform_plugin->getInsertData($fields, $_GPC['diyformdata']);


        $idata      = $insert_data['data'];
        $goods_temp = $diyform_plugin->getGoodsTemp($goodsid, $diyformid, $_W['openid'], $_W['core_user']);

        $insert = array(
            'cid'           => $goodsid,
            'openid'        => $_W['openid'],
            'core_user'     => $_W['core_user'],
            'diyformid'     => $diyformid,
            'type'          => 3,
            'diyformfields' => iserializer($fields),
            'diyformdata'   => $idata,
            'uniacid'       => $_W['uniacid']
        );

        if (empty($goods_temp)) {

            pdo_insert('superdesk_shop_diyform_temp', $insert);// TODO 标志 楼宇之窗 openid shop_diyform_temp 已处理
            $gdid = pdo_insertid();
        } else {
            pdo_update('superdesk_shop_diyform_temp',// TODO 标志 楼宇之窗 openid shop_diyform_temp 已处理
                $insert,
                array(
                    'id' => $goods_temp['id']
                )
            );

            $gdid = $goods_temp['id'];
        }


        if (!empty($cartid)) {

            $cart_data = array(
                'diyformid'     => $insert['diyformid'],
                'diyformfields' => $insert['diyformfields'],
                'diyformdata'   => $insert['diyformdata']
            );

            pdo_update(
                'superdesk_shop_member_cart', // TODO 标志 楼宇之窗 openid superdesk_shop_member_cart 不处理
                $cart_data,
                array(
                    'id' => $cartid
                )
            );
        }


        show_json(1, array('goods_data_id' => $gdid));
    }

    /**
     * @return array
     */
    protected function merchData()
    {
        $merch_plugin = p('merch');
        $merch_data   = m('common')->getPluginset('merch');

        if ($merch_plugin && $merch_data['is_openmerch']) {
            $is_openmerch = 1;
        } else {
            $is_openmerch = 0;
        }

        return array(
            'is_openmerch' => $is_openmerch,
            'merch_plugin' => $merch_plugin,
            'merch_data'   => $merch_data
        );
    }

    /**
     * @param $member
     *
     * @return array
     */
    protected function diyformData($member)
    {
        global $_W;
        global $_GPC;

        $diyform_plugin = p('diyform');
        $order_formInfo = false;
        $diyform_set    = false;
        $orderdiyformid = 0;
        $fields         = array();
        $f_data         = array();

        if ($diyform_plugin) {

            $diyform_set = $_W['shopset']['diyform'];

            if (!empty($diyform_set['order_diyform_open'])) {

                $orderdiyformid = intval($diyform_set['order_diyform']);

                if (!empty($orderdiyformid)) {

                    $order_formInfo = $diyform_plugin->getDiyformInfo($orderdiyformid);
                    $fields         = $order_formInfo['fields'];
                    $f_data         = $diyform_plugin->getLastOrderData($orderdiyformid, $member);
                }
            }
        }

        return array(
            'diyform_plugin' => $diyform_plugin,
            'order_formInfo' => $order_formInfo,
            'diyform_set'    => $diyform_set,
            'orderdiyformid' => $orderdiyformid,
            'fields'         => $fields,
            'f_data'         => $f_data
        );
    }

    /**
     * 计算使用优惠券后的价格
     */
    public function getPackagePrice()
    {
        global $_GPC;

        $packageid = intval($_GPC['packageid']);

        $goodsarr = $_GPC['goods'];
        $total    = $_GPC['total'];

        $result = $this->caculatePackage($packageid, $goodsarr, $total);

        if (empty($result)) {
            show_json(0);
        }

        show_json(1, $result);
    }


    /**
     * 计算优惠券
     *
     * @param       $packageid
     * @param       $goodsarr
     * @param       $totalprice
     * @param       $total
     * @param       $isdiscountprice
     * @param int   $isSubmit
     * @param array $discountprice_array
     * @param int   $merchisdiscountprice
     *
     * @return array|bool
     */
    public function caculatePackage($packageid, $goodsarr, $total)
    {
        global $_W;

        if (empty($goodsarr)) {
            return false;
        }

        $params = array(':uniacid' => $_W['uniacid'], ':id' => $packageid);

        $package = pdo_fetch(
            ' SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_package') .
            ' WHERE ' .
            '       uniacid=:uniacid ' .
            '       and id=:id',
            $params
        );

        $goods       = array();
        $goodsprice  = 0;
        $marketprice = 0;


        foreach ($goodsarr as $key => $value) {

            $goods[$key] = pdo_fetch(
                ' select id,title,thumb,' .
                '   marketprice,' .
                '   costprice ' .
                ' from ' . tablename('superdesk_shop_goods') .
                ' where id =:id ' .
                '       and uniacid=:uniacid',
                array(
                    ':uniacid' => $_W['uniacid'],
                    ':id'      => $value['goodsid']
                )
            );

            $option       = array();
            $packagegoods = array();

            if (0 < $value['optionid']) {

                $option = pdo_fetch(
                    ' select title,packageprice ' .
                    ' from ' . tablename('superdesk_shop_package_goods_option') .
                    ' where optionid =:optionid ' .
                    '       and goodsid=:goodsid ' .
                    '       and uniacid=:uniacid ' .
                    '       and pid =:pid ',
                    array(
                        ':optionid' => $value['optionid'],
                        ':goodsid'  => $value['goodsid'],
                        ':uniacid'  => $_W['uniacid'],
                        ':pid'      => $packageid
                    )
                );

                $goods[$key]['packageprice'] = $option['packageprice'];

            } else {

                $packagegoods = pdo_fetch(
                    ' select title,packageprice ' .
                    ' from ' . tablename('superdesk_shop_package_goods') .
                    ' where goodsid=:goodsid' .
                    '       and uniacid=:uniacid ' .
                    '       and pid=:pid',
                    array(
                        ':goodsid' => $value['goodsid'],
                        ':uniacid' => $_W['uniacid'],
                        ':pid'     => $packageid
                    )
                );

                $goods[$key]['packageprice'] = $packagegoods['packageprice'];
            }

            $goods[$key]['optiontitle'] = ((!empty($option['title']) ? $option['title'] : ''));
            $goods[$key]['optionid']    = ((!empty($value['optionid']) ? $value['optionid'] : 0));
            $goods[$key]['goodsid']     = $value['goodsid'];
            $goods[$key]['total']       = $total;


            if ($option) {
                $goods[$key]['packageprice'] = $option['packageprice'];
            } else {
//                $goods[$key]['packageprice'] = $goods[$key]['packageprice'];
            }


            $goodsprice  += $goods[$key]['packageprice'] * $total;
            $marketprice += $goods[$key]['marketprice'] * $total;
        }

        $freight = $package['freight'];

//        $realprice               = $goodsprice + $package['freight'];
        $realprice = $goodsprice;

        $goodsprice = number_format($goodsprice, 2);
        $realprice  = number_format($realprice, 2);

        return compact('goods', 'goodsprice', 'freight', 'realprice', 'marketprice', 'total');
    }

    protected function checkJdVopCanBuy($goods_from_cashier, $address)
    {
        global $_W;

        // 京东check start
        if (empty($address)) {

            $address = $this->_member_addressModel->getByDefault($_W['uniacid'], $_W['openid'], $_W['core_user']);

            if (empty($address)) {
                return $goods_from_cashier;
            }
        }
        // 京东check start

        // 京东check start TODO 2018年6月25日 15:48:26 从微信端复制过来

//        include_once(IA_ROOT . '/addons/superdesk_shopv2/model/goods/goods.class.php');
//        $_goodsModel      = new goodsModel();
        $transform_result = $this->_goodsModel->transformGoodIds2SkuIds($goods_from_cashier/*会附加上skuId*/);


        $__must_check_state = false;
        $__must_check_stock = false;
        $__must_check_price = false;


        // 检查 是否有 京东VOP 的东西
        if (sizeof($transform_result['skuNums']) > 0) {
            $__must_check_state = true;
            $__must_check_stock = true;
            $__must_check_price = true;
        }

        socket_log("开始京东的检验");

        // TODO ========== jd_vop 状态 start ==========
        if ($__must_check_state) {

            // show_json(0, '京东 -> 状态 -> check -> start');
            $__count_state__ = 0;

            $api_state_result = $this->_productService->skuState($transform_result['skuArr']);
//        ==== $api_state_result ====
//            { "5729524": { "state": 0, "sku": 5729524 } }


            foreach ($goods_from_cashier as &$item) {

                if ($item['skuId'] != 0) { // 不为0才是京东商品

                    $__item__ = $api_state_result[$item['skuId']];

                    if ($__item__['state'] == 0) { // 下架
                        $item['state_msg'] = "下架";
                    } else {
                        $__count_state__ += 1;
                    }
                }
            }
            unset($item);

            if ($__count_state__ == sizeof($api_state_result) && $__count_state__ != 0) {
                // OK
            } else {
                // TODO 状态要改，前端要处理
                show_json(900, $goods_from_cashier);
            }

            // show_json(0, '京东 -> 状态 -> check -> end');
        }
        // TODO ========== jd_vop 状态 start ==========


        // TODO ========== jd_vop 库存 start ==========
        if ($__must_check_stock) {

            // show_json(0, '京东 -> 库存 -> check -> start');

            $__count_stock__ = 0;

            // TODO 调用京东库存接口(sku 区域 数量) 6.2  批量获取库存接口（建议订单详情页、下单使用）
            $api_stock_result = $this->_stockService->getNewStockById(json_encode($transform_result['skuNums']), $address['jd_vop_area']);
//        ==== $api_result ====
//        {
//            "124157":{"skuId":124157,"areaId":"19_1607_3639_0","stockStateId":34,"stockStateDesc":"无货","remainNum":-1},
//            "892900":{"skuId":892900,"areaId":"19_1607_3639_0","stockStateId":33,"stockStateDesc":"有货","remainNum":-1},
//            "1037029":{"skuId":1037029,"areaId":"19_1607_3639_0","stockStateId":33,"stockStateDesc":"有货","remainNum":-1}
//        }


            foreach ($goods_from_cashier as &$item) {

                if ($item['skuId'] != 0) { // 不为0才是京东商品

                    $__item__ = $api_stock_result[$item['skuId']];

                    // TODO 返回有货 (33 39 40 36) 继续进入下一个流程
                    if ($__item__['stockStateId'] == 33 //有货 现货-下单立即发货
                        || $__item__['stockStateId'] == 36 //预订
                        || $__item__['stockStateId'] == 39 //有货 在途-正在内部配货，预计2~6天到达本仓库
                        || $__item__['stockStateId'] == 40 //有货 可配货-下单后从有货仓库配货
                    ) {

                        if ($__item__['stockStateId'] == 36) {

                            $__count_stock__ += 1;

                        } else {

                            if ($__item__['remainNum'] != -1) { // 剩余数量 -1未知；当库存小于5时展示真实库存数量

                                if ($__item__['remainNum'] >= $item['total']) {

                                    $__count_stock__ += 1;

                                } else {

                                    $item['stock_msg'] = $__item__['stockStateId'] . ":" . "建议购买数为:" . $__item__['remainNum'];

                                }

                            } else {

                                $__count_stock__ += 1;

                            }

                        }
                    } // TODO 返回无货 (34) 就弹出提示用户无货
                    elseif ($__item__['stockStateId'] == 34) { // 无货
                        $item['stock_msg'] = $__item__['stockStateId'] . ":" . "无货";
                    } elseif ($__item__['stockStateId'] == 10) {
                    	// 毕加索 没在范围内的都认为无货 不能下单 无货 文档没有列的枚举值都认为无货
						// 林进雨 大概就是区域无货 或是货不足
//                        $item['stock_msg'] = $__item__['stockStateDesc']. ":" . "请联系客服";
                        $item['stock_msg'] = "区域无货";
                    }
                }
            }
            unset($item);

            if ($__count_stock__ == sizeof($api_stock_result) && $__count_stock__ != 0) {
                // OK
            } else {
                // TODO 状态要改，前端要处理
                show_json(901, $goods_from_cashier);
            }

            // show_json(0, '京东 -> 库存 -> check -> end');
        }
        // TODO ========== jd_vop 库存 end   ==========


        // TODO ========== jd_vop 价格 start ==========
        if ($__must_check_price) {

            $__count_price__ = 0;

            $api_price_result = $this->_priceService->getPrice($transform_result['skuArr']);

            foreach ($goods_from_cashier as &$item) {

                if ($item['skuId'] != 0) { // 不为0才是京东商品

                    $__item__ = $api_price_result[$item['skuId']];


//                    if ($__item__['marketprice'] == $item['marketprice'])
                    if ($__item__['costprice'] == $item['costprice']) // 想改成这个,逻辑有问题 改成后台修改价格吧 开放权限
                    {
                        $__count_price__ += 1;
                    } else {
                        $item['price_msg'] = "价格变动";
                    }

                }
            }
            unset($item);

            if ($__count_price__ == sizeof($api_price_result) && $__count_price__ != 0) {
                // OK
            } else {
                // TODO 状态要改，前端要处理
                show_json(902, $goods_from_cashier);
            }
        }
        // TODO ========== jd_vop 价格 end   ==========
        // 京东check end

        return $goods_from_cashier;
    }
}