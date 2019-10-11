<?php

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/accessControl.php";

if (!(defined("IN_IA"))) {
    exit("Access Denied");
}

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/page_mobile.php";
include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/product_detail.class.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/ProductService.class.php');


class Detail_SuperdeskShopV2Page extends PcMobilePage
{

    private $_product_detailModel;
    private $_productService;

    public function __construct()
    {
        global $_W;
        global $_GPC;
        parent::__construct();

        $this->_product_detailModel = new product_detailModel();
        $this->_productService      = new ProductService();
    }

    /**
     * 默认入口
     */
    public function main()
    {
        global $_W;
        global $_GPC;

        $uniacid = $_W['uniacid'];

        $id = intval($_GPC['id']);

        $err = false;

        $merch_plugin    = p('merch');  //获取商户类的实例  addons/superdesk_shopv2/plugin/merch/core/model.php
        $merch_data      = m('common')->getPluginset('merch');//获取商户设置
        $commission_data = m('common')->getPluginset('commission');  //获取分销设置

        //商户是否开启
        if ($merch_plugin && $merch_data['is_openmerch']) {
            $is_openmerch = 1;
        } else {
            $is_openmerch = 0;
        }

        //获取商品
        $goods = pdo_fetch(
            'select * ' .
            ' from ' . tablename('superdesk_shop_goods') .
            ' where 1 ' .
            '       and id=:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':id'      => $id,
                ':uniacid' => $_W['uniacid']
            )
        );

        // 处理多商户情况
        $merchid = $goods['merchid'];  //该商品所属商户id
        if ($is_openmerch == 0) {
            //有商户但是未开启的情况下显示错误模板
            if (0 < $merchid) {
                show_json(0, '未开启多商户');
            }
        } // 2019年1月11日 11:26:24 zjh #2414 - 2019-1-10-杨宇迪-商城后台，商品在编辑完成后，增加一个商品预览功能。$_GPC['isExample'] != 1
        else if ((0 < $merchid) && ($goods['checked'] == 1) && $_GPC['isExample'] != 1) {
            //商户方面已通过,但商品审核不通过时显示错误模板

            show_json(0, '未审核');
        }

        // jd_vop 更新商品详情,图片,价格 start
        if ($goods['jd_vop_sku'] != 0) {

            $this->_productService->businessProcessingGetDetailOne($goods['jd_vop_sku'], $goods['jd_vop_page_num'], 1);

            $goods = pdo_fetch(
                ' select * ' .
                ' from ' . tablename('superdesk_shop_goods') .
                ' where 1 ' .
                '       and id=:id ' .
                '       and uniacid=:uniacid ' .
                ' limit 1',
                array(
                    ':id'      => $id,
                    ':uniacid' => $_W['uniacid']
                )
            );
        }
        // jd_vop 更新商品详情,图片,价格 end

        // 处理京东自营情况
        $jd_vop_sku = $goods['jd_vop_sku'];
        if ($jd_vop_sku != 0 && $merchid == SUPERDESK_SHOPV2_JD_VOP_MERCHID) {

            $_SKU_ = $this->_product_detailModel->getOne($jd_vop_sku);

            if ($_SKU_) {
                $goods['wareQD'] = $_SKU_['wareQD'];
                $goods['param']   = $_SKU_['param'];
                $goods['content'] = $_SKU_['introduction'];

                if(!empty($_SKU_['wareQD'])){
                    $goods['param'] = '包装清单:' . $_SKU_['wareQD'] . '<br/><br/>'. $goods['param'];
                }

                // ADD 同类商品查询

                $goods['similar'] = $this->_productService->getSimilarSku($jd_vop_sku);
            }
        }

        $member = m('member')->getMember($_W['openid'], $_W['core_user']);    //根据页面提交的openid获取用户信息

        $showlevels = (($goods['showlevels'] != '' ? explode(',', $goods['showlevels']) : array()));    //商品展示等级需求
        $showgroups = (($goods['showgroups'] != '' ? explode(',', $goods['showgroups']) : array()));    //商品展示组别需求

        $showgoods = 0;

        if (!(empty($member))) {

            //假如有用户判断需求是否达标
            if ((!(empty($showlevels)) && in_array($member['level'], $showlevels)) || (!(empty($showgroups)) && in_array($member['groupid'], $showgroups)) || (empty($showlevels) && empty($showgroups))) {
                $showgoods = 1;
            }

        } else if (empty($showlevels) && empty($showgroups)) {
            //假如没有需求直接可展示
            $showgoods = 1;
        }

        if (empty($goods) || empty($showgoods)) {
            //假如没有该商品或没有不达标则不展示
            show_json(0, '无商品');
        }

        // 2019年1月11日 11:26:24 zjh #2414 - 2019-1-10-杨宇迪-商城后台，商品在编辑完成后，增加一个商品预览功能。$_GPC['isExample'] != 1
        //g.`status` > 0 and g.`checked`=0 and g.`total`>0 and g.`deleted`=0
        if (($goods['status'] <= 0 || $goods['checked'] != 0 || $goods['deleted'] != 0) && $_GPC['isExample'] != 1) {
            show_json(0, '商品已下架');
        }

        $goods['sales'] = $goods['sales'] + $goods['salesreal'];    //商品实际销售加虚拟销售

        $buyshow = 0;
        if ($goods['buyshow'] == 1) {
            //假如开启了购买后可见并且该用户已购买则展示
            $sql =
                'select o.id ' .
                ' from ' . tablename('superdesk_shop_order') . ' o ' .// TODO 标志 楼宇之窗 openid shop_order 已处理
                ' left join ' . tablename('superdesk_shop_order_goods') . ' g on o.id = g.orderid' .// TODO 标志 楼宇之窗 openid shop_order_goods 已处理
                ' where 1 ' .
                '       and g.goodsid=:id ' .
                '       and o.uniacid=:uniacid ' .
                '       and o.openid=:openid ' .
                '       and o.core_user=:core_user ' .
                '       and o.status>0 ' .
                ' limit 1';

            $buy_goods = pdo_fetch($sql, array(
                ':id'        => $id,
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user'],
            ));

            if (!(empty($buy_goods))) {
                $buyshow             = 1;
                $goods['buycontent'] = m('ui')->lazy($goods['buycontent']);
            }
        }

        $goods['unit'] = ((empty($goods['unit']) ? '件' : $goods['unit']));  //商品单位

        $citys = m('dispatch')->getNoDispatchAreas($goods);    //商品可配送地区

        if (!(empty($citys)) && is_array($citys)) {
            $has_city = 1;
        } else {
            $has_city = 0;
        }

        $goods['dispatchprice'] = $this->getGoodsDispatchPrice($goods);    //商品配送费

        $thumbs = iunserializer($goods['thumb_url']);    //商品展示组图

        if (empty($thumbs)) {
            $thumbs = array($goods['thumb']);    //假如没组图就把列表封面图塞进去
        }

        if (!(empty($goods['thumb_first'])) && !(empty($goods['thumb']))) {
            $thumbs = array_merge(array($goods['thumb']), $thumbs);    //假如开启了显示首图,并且有首图,就把首图与thumbs合并
        }

        foreach ($thumbs as $_index => &$_thumb) {
            $_thumb = tomedia($_thumb);
        }

        //获取该商品的规格
        $specs   = false;// 1-n 维度
        $options = false; // 维度与维度的 X矩阵 #2069 2018-11-29_陈康俊_企业采购_PC端_商品详情页_商城内部_多维度规格的显示_与操作都有问题_当时没有处理多维度规格

        if (!empty($goods) && $goods['hasoption']) {

            $specs = pdo_fetchall(
                ' select * ' .
                ' from ' . tablename('superdesk_shop_goods_spec') .
                ' where 1 ' .
                '       and goodsid=:goodsid ' .
                '       and uniacid=:uniacid ' .
                ' order by displayorder asc',
                array(
                    ':goodsid' => $id,
                    ':uniacid' => $_W['uniacid']
                )
            );

            // 与微信端是有差异的
            foreach ($specs as &$spec) {

                $spec['items'] = pdo_fetchall(
                    'select gsi.*,go.productprice,go.marketprice,go.stock,go.id as optionid ' .
                    ' from ' . tablename('superdesk_shop_goods_spec_item') . ' as gsi ' .
                    '       left join ' . tablename('superdesk_shop_goods_option') . ' as go on gsi.id = go.specs ' .
                    ' where 1 ' .
                    '       and gsi.specid=:specid ' .
                    '       and gsi.show=1 ' .
                    ' order by gsi.displayorder asc',
                    array(
                        ':specid' => $spec['id']
                    )
                );
            }

            unset($spec);

            // 与微信端是有差异的 差异是微信端没去处理缩略图
            if (!empty($specs)) {
                foreach ($specs as $key => $value) {
                    foreach ($specs[$key]['items'] as $k => &$v) {
                        $v['thumb'] = tomedia($v['thumb']);
                    }
                }
            }

            // 新加的 为了处理多维度规格 #2069
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


        //获取商品的额外参数
        $params = pdo_fetchall(
            'SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_goods_param') .
            ' WHERE 1 ' .
            '       and uniacid=:uniacid ' .
            '       and goodsid=:goodsid ' .
            '       AND title<>"" ' .
            ' order by displayorder asc',
            array(
                ':uniacid' => $uniacid,
                ':goodsid' => $goods['id']
            )
        );

        $goods           = set_medias($goods, 'thumb');//给商品图加上完整路径
        $goods['canbuy'] = !(empty($goods['status'])) && empty($goods['deleted']);    //是否可以购买
        if (!(empty($goods['hasoption']))) {
            //假如启用商品规格,获取商品规格具体信息
            $options       = pdo_fetchall(
                ' select ' .
                '       id,stock ' .
                ' from ' . tablename('superdesk_shop_goods_option') .
                ' where ' .
                '       goodsid=:goodsid ' .
                '       and uniacid=:uniacid ' .
                ' order by displayorder asc',
                array(
                    ':goodsid' => $goods['id'],
                    ':uniacid' => $_W['uniacid']),
                'stock'
            );
            $options_stock = array_keys($options);
            if ($options_stock) {
                $goods['total'] = max($options_stock);
            } else {
                $goods['total'] = 0;
            }
        }

        // 是否能买 1 上架 2 没被删除 3　库存大于0 4 看有没限制购买最大量
        if ($goods['total'] <= 0) {
            $goods['canbuy']    = false;  //假如库存不足不能购买
            $goods['error_msg'] = '库存不足';
        }

        $goods['timestate'] = '';
        $goods['userbuy']   = '1';
        if (0 < $goods['usermaxbuy']) {
            $order_goodscount = pdo_fetchcolumn(
                'select ifnull(sum(og.total),0)  ' .
                ' from ' . tablename('superdesk_shop_order_goods') . ' og ' .// 标志 楼宇之窗 openid shop_order_goods 已处理
                ' left join ' . tablename('superdesk_shop_order') . ' o on og.orderid=o.id ' . // 标志 楼宇之窗 openid shop_order 已处理
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
                //假如最大购买数已达上限不能购买
//                $goods['userbuy'] = 0;
                $goods['error_msg'] = '已达该商品购买上限';
                $goods['canbuy']    = false;// 是否能买 1. 上架 2. 没被删除 3.　库存大于0 4. 看有没限制购买最大量
            }
        }
        $levelid           = $member['level'];
        $groupid           = $member['groupid'];
        $goods['levelbuy'] = '1';
        if ($goods['buylevels'] != '') {
            //假如可购买等级不足不能购买
            $buylevels = explode(',', $goods['buylevels']);
            if (!(in_array($levelid, $buylevels))) {
//                $goods['levelbuy'] = 0;
                $goods['error_msg'] = '购买等级不足';
                $goods['canbuy']    = false;// 是否能买 1. 上架 2. 没被删除 3.　库存大于0 4. 看有没限制购买最大量
            }
        }
        $goods['groupbuy'] = '1';
        if ($goods['buygroups'] != '') {
            //假如非可购买组别不能购买
            $buygroups = explode(',', $goods['buygroups']);
            if (!(in_array($groupid, $buygroups))) {
//                $goods['groupbuy'] = 0;
                $goods['error_msg'] = '非可购买组别';
                $goods['canbuy']    = false;// TODO 是否能买 1. 上架 2. 没被删除 3.　库存大于0 4. 看有没限制购买最大量
            }
        }
        $goods['timebuy'] = '0';
        if ($goods['istime'] == 1) {
            //假如非可购买时间不能购买
            if (time() < $goods['timestart']) {
//                $goods['timebuy'] = '-1';
                $goods['error_msg'] = '购买时间未开始';
                $goods['canbuy']    = false;
            } else if ($goods['timeend'] < time()) {
//                $goods['timebuy'] = '1';
                $goods['error_msg'] = '购买时间已结束';
                $goods['canbuy']    = false;// TODO 是否能买 1. 上架 2. 没被删除 3.　库存大于0 4. 看有没限制购买最大量
            }
        }

        //判断是否有设置默认地址 zjh 添加于 2018年5月3日 14:08:44
        $goods['haveAddress'] = true;
        $member_address       = $this->getMemberAddress();

        if (empty($member_address)) {
//            $goods['jd_stock'] = false;
            $goods['error_msg']   = '请添加配送地址方可购买';
            $goods['haveAddress'] = false;
            $goods['canbuy']      = false;
        }

        //判断京东商品是否有库存 zjh 添加于 2018年5月2日 18:17:08
        $goods['jd_stock'] = true;
        if (!empty($member_address) && !m('goods')->checkGoodsJdStock($goods)) {
            $goods['error_msg'] = '该商品在该地区无库存';
            $goods['jd_stock']  = false;
            $goods['canbuy']    = false;
        }

        $canAddCart = true;
        if (($goods['isverify'] == 2) || ($goods['type'] == 2) || ($goods['type'] == 3) || !(empty($goods['cannotrefund']))) {
            //是否支持线下核销,是否虚拟商品,是否不能退款
            $canAddCart = false;
        }
        if (($goods['type'] == 2) && empty($specs)) {
            //是否为虚拟商品并且没有规格
            $gflag = 1;
        } else {
            $gflag = 0;
        }

        //获取优惠信息.判断是否有优惠
        $enoughs      = com_run('sale::getEnoughs');
        $enoughfree   = com_run('sale::getEnoughFree');
        $goods_nofree = com_run('sale::getEnoughsGoods');
        if (!(empty($goods_nofree))) {
            if (in_array($id, $goods_nofree)) {
                $enoughfree = false;
            }
        }
        if ($enoughfree && ($enoughfree < $goods['minprice'])) {
            $goods['dispatchprice'] = 0;
        }
        $hasSales = false;
        if ((0 < $goods['ednum']) || (0 < $goods['edmoney'])) {
            $hasSales = true;
        }
        if ($enoughfree || ($enoughs && (0 < count($enoughs)))) {
            $hasSales = true;
        }
        $minprice = $goods['minprice'];
        $maxprice = $goods['maxprice'];

        $level       = m('member')->getLevel($_W['openid']);
        $memberprice = m('goods')->getMemberPrice($goods, $level);  //获取用户等级相关的优惠价

        if ($goods['isdiscount'] && (time() <= $goods['isdiscount_time'])) {

            //假如开启促销并且在促销时间内
            $goods['oldmaxprice'] = $maxprice;
            $isdiscount_discounts = json_decode($goods['isdiscount_discounts'], true);

            $prices = array();

            if (!(isset($isdiscount_discounts['type'])) || empty($isdiscount_discounts['type'])) {

                $level        = m('member')->getLevel($_W['openid']);
                $prices_array = m('order')->getGoodsDiscountPrice($goods, $level, 1);
                $prices[]     = $prices_array['price'];

            } else {

                $goods_discounts = m('order')->getGoodsDiscounts($goods, $isdiscount_discounts, $levelid, $options);
                $prices          = $goods_discounts['prices'];
                $options         = $goods_discounts['options'];

            }
            $minprice = min($prices);
            $maxprice = max($prices);

        } else {

            if (isset($options) && (0 < count($options)) && $goods['hasoption']) {

                //是否有规格
                $optionids = array();

                foreach ($options as $val) {
                    $optionids[] = $val['id'];
                }

                //删除其他规格,更新商品最大最小价格
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

                //删除所有规格,还原商品最大最小价格
                $sql =
                    ' update ' . tablename('superdesk_shop_goods') .
                    ' set ' .
                    '       minprice = marketprice,' .
                    '       maxprice = marketprice ' .
                    ' where id = ' . $id . ' and hasoption=0;';
                pdo_query($sql);

            }

            $goods_price = pdo_fetch(
                ' select minprice,maxprice ' .
                ' from ' . tablename('superdesk_shop_goods') .
                ' where ' .
                '       id=:id and uniacid=:uniacid ' .
                ' limit 1',
                array(
                    ':id'      => $id,
                    ':uniacid' => $_W['uniacid']
                )
            );

            $goods['minprice'] = $goods_price['minprice'];
            $goods['maxprice'] = $goods_price['maxprice'];
        }

        $goods['minprice'] = round($minprice, 2);
        $goods['maxprice'] = round($maxprice, 2);

        $getComments = empty($_W['shopset']['trade']['closecommentshow']);  //关闭评论
        $hasServices = $goods['cash'] || $goods['seven'] || $goods['repair'] || $goods['invoice'] || $goods['quality'];  //是否有特殊服务(7天退换等)

        $isFavorite = m('goods')->isFavorite($id);  //收藏
        $cartCount  = m('goods')->getCartCount();    //购物车数量
        if (!(empty($_W['openid']))) {
            m('goods')->addHistory($id);    //添加观看记录
        }

        $shop        = set_medias(m('common')->getSysset('shop'), 'logo');
        $shop['url'] = mobileUrl('', NULL, true);
        $mid         = intval($_GPC['mid']);

        $opencommission = false;

        //经销商
        if (p("commission")) {
            //经销商
            if (empty($member['agentblack']))  //是否经销商黑名单
            {
                $cset           = p('commission')->getSet();
                $opencommission = 0 < intval($cset['level']);
                if ($opencommission)    //是否满足开启经销商条件
                {
                    if (empty($mid))    //假如不存在分享
                    {
                        if (($member['isagent'] == 1) && ($member['status'] == 1))    //假如自己是经销商
                        {
                            $mid = $member['id'];
                        }
                    }
                    if (!(empty($mid))) {
                        if (empty($cset['closemyshop']))  //假如开启了我的小店
                        {
                            $shop        = set_medias(p('commission')->getShop($mid), 'logo');
                            $shop['url'] = mobileUrl('commission/myshop', array('mid' => $mid), true);
                        }
                    }
                }
            }
        }

        /* TODO 这里之前只要0.05秒,后面就要0.7秒 */
        if (empty($this->merch_user))    //没传商户id
        {
            $merch_flag = 0;
            if (($is_openmerch == 1) && (0 < $goods['merchid']))    //开启了商户,商品有商户
            {
                $merch_user = pdo_fetch('select * from ' . tablename('superdesk_shop_merch_user') . ' where id=:id limit 1', array(':id' => intval($goods['merchid'])));
                if (!(empty($merch_user))) {
                    $shop       = $merch_user;
                    $merch_flag = 1;
                }
            }
            if ($merch_flag == 1) {

                $shopdetail = array(
                    'logo'        => (!(empty($goods['detail_logo'])) ? tomedia($goods['detail_logo']) : tomedia($shop['logo'])),
                    'shopname'    => (!(empty($goods['detail_shopname'])) ? $goods['detail_shopname'] : $shop['merchname']),
                    'description' => (!(empty($goods['detail_totaltitle'])) ? $goods['detail_totaltitle'] : $shop['desc']),
                    'btntext1'    => trim($goods['detail_btntext1']), 'btnurl1' => (!(empty($goods['detail_btnurl1'])) ? $goods['detail_btnurl1'] : mobileUrl('goods')),
                    'btntext2'    => trim($goods['detail_btntext2']), 'btnurl2' => (!(empty($goods['detail_btnurl2'])) ? $goods['detail_btnurl2'] : mobileUrl('merch', array('merchid' => $goods['merchid'])))
                );

            } else {

                $shopdetail = array(
                    'logo'        => (!(empty($goods['detail_logo'])) ? tomedia($goods['detail_logo']) : $shop['logo']),
                    'shopname'    => (!(empty($goods['detail_shopname'])) ? $goods['detail_shopname'] : $shop['name']),
                    'description' => (!(empty($goods['detail_totaltitle'])) ? $goods['detail_totaltitle'] : $shop['description']),
                    'btntext1'    => trim($goods['detail_btntext1']), 'btnurl1' => (!(empty($goods['detail_btnurl1'])) ? $goods['detail_btnurl1'] : mobileUrl('goods')),
                    'btntext2'    => trim($goods['detail_btntext2']), 'btnurl2' => (!(empty($goods['detail_btnurl2'])) ? $goods['detail_btnurl2'] : $shop['url'])
                );
            }

            $param = array(':uniacid' => $_W['uniacid']);

            if ($merch_flag == 1) {
                $sqlcon            = ' and merchid=:merchid';
                $param[':merchid'] = $goods['merchid'];
            }

            //$statics为所有商品数,新商品数,折扣商品数
//            if (empty($shop['selectgoods'])) {
//                $statics = array('all' => pdo_fetchcolumn('select count(1) from ' . tablename('superdesk_shop_goods') . ' where uniacid=:uniacid ' . $sqlcon . ' and status=1 and deleted=0', $param), 'new' => pdo_fetchcolumn('select count(1) from ' . tablename('superdesk_shop_goods') . ' where uniacid=:uniacid ' . $sqlcon . ' and isnew=1 and status=1 and deleted=0', $param), 'discount' => pdo_fetchcolumn('select count(1) from ' . tablename('superdesk_shop_goods') . ' where uniacid=:uniacid ' . $sqlcon . ' and isdiscount=1 and status=1 and deleted=0', $param));
//            } else {
//                $goodsids = explode(',', $shop['goodsids']);
//                $statics  = array('all' => count($goodsids), 'new' => pdo_fetchcolumn('select count(1) from ' . tablename('superdesk_shop_goods') . ' where uniacid=:uniacid ' . $sqlcon . ' and id in( ' . $shop['goodsids'] . ' ) and isnew=1 and status=1 and deleted=0', $param), 'discount' => pdo_fetchcolumn('select count(1) from ' . tablename('superdesk_shop_goods') . ' where uniacid=:uniacid ' . $sqlcon . ' and id in( ' . $shop['goodsids'] . ' ) and isdiscount=1 and status=1 and deleted=0', $param));
//            }
        } else if ($goods['checked'] == 1 && $_GPC['isExample'] != 1) {
            // 2019年1月11日 11:26:24 zjh #2414 - 2019-1-10-杨宇迪-商城后台，商品在编辑完成后，增加一个商品预览功能。$_GPC['isExample'] != 1

            show_json(0, '未审核');

        } else {

            $shop = $this->merch_user;

            $shopdetail = array(
                'logo'        => (!(empty($goods['detail_logo'])) ? tomedia($goods['detail_logo']) : tomedia($shop['logo'])),
                'shopname'    => (!(empty($goods['detail_shopname'])) ? $goods['detail_shopname'] : $shop['merchname']),
                'description' => (!(empty($goods['detail_totaltitle'])) ? $goods['detail_totaltitle'] : $shop['desc']),
                'btntext1'    => trim($goods['detail_btntext1']),
                'btnurl1'     => (!(empty($goods['detail_btnurl1'])) ? $goods['detail_btnurl1'] : mobileUrl('goods')),
                'btntext2'    => trim($goods['detail_btntext2']),
                'btnurl2'     => (!(empty($goods['detail_btnurl2'])) ? $goods['detail_btnurl2'] : mobileUrl('merch', array('merchid' => $goods['merchid'])))
            );

//            if (empty($shop['selectgoods'])) {
//                $statics = array('all' => pdo_fetchcolumn('select count(1) from ' . tablename('superdesk_shop_goods') . ' where uniacid=:uniacid and merchid=:merchid and status=1 and deleted=0', array(':uniacid' => $_W['uniacid'], ':merchid' => $goods['merchid'])), 'new' => pdo_fetchcolumn('select count(1) from ' . tablename('superdesk_shop_goods') . ' where uniacid=:uniacid and merchid=:merchid and isnew=1 and status=1 and deleted=0', array(':uniacid' => $_W['uniacid'], ':merchid' => $goods['merchid'])), 'discount' => pdo_fetchcolumn('select count(1) from ' . tablename('superdesk_shop_goods') . ' where uniacid=:uniacid and merchid=:merchid and isdiscount=1 and status=1 and deleted=0', array(':uniacid' => $_W['uniacid'], ':merchid' => $goods['merchid'])));
//            } else {
//                $goodsids = explode(',', $shop['goodsids']);
//                $statics  = array('all' => count($goodsids), 'new' => pdo_fetchcolumn('select count(1) from ' . tablename('superdesk_shop_goods') . ' where uniacid=:uniacid and merchid=:merchid and id in( ' . $shop['goodsids'] . ' ) and isnew=1 and status=1 and deleted=0', array(':uniacid' => $_W['uniacid'], ':merchid' => $goods['merchid'])), 'discount' => pdo_fetchcolumn('select count(1) from ' . tablename('superdesk_shop_goods') . ' where uniacid=:uniacid and merchid=:merchid and id in( ' . $shop['goodsids'] . ' ) and isdiscount=1 and status=1 and deleted=0', array(':uniacid' => $_W['uniacid'], ':merchid' => $goods['merchid'])));
//            }
        }
        $goodsdesc = ((!(empty($goods['description'])) ? $goods['description'] : $goods['subtitle']));

        $_W['shopshare'] = array(
            'title'  => (!(empty($goods['share_title'])) ? $goods['share_title'] : $goods['title']),
            'imgUrl' => (!(empty($goods['share_icon'])) ? tomedia($goods['share_icon']) : tomedia($goods['thumb'])),
            'desc'   => (!(empty($goodsdesc)) ? $goodsdesc : $_W['shopset']['shop']['name']),
            'link'   => mobileUrl('goods/detail', array('id' => $goods['id']), true)
        );

        // 经销商
        $com = p('commission');
        if ($com) {
            $cset = $_W['shopset']['commission'];
            if (!(empty($cset))) {
                if (($member['isagent'] == 1) && ($member['status'] == 1)) {
                    $_W['shopshare']['link'] = mobileUrl('goods/detail', array('id' => $goods['id'], 'mid' => $member['id']), true);
                } else if (!(empty($_GPC['mid']))) {
                    $_W['shopshare']['link'] = mobileUrl('goods/detail', array('id' => $goods['id'], 'mid' => $_GPC['mid']), true);
                }
            }
        }

        // 门店
        $stores = array();
        if ($goods['isverify'] == 2) {
            $storeids = array();
            if (!(empty($goods['storeids']))) {
                $storeids = array_merge(explode(',', $goods['storeids']), $storeids);
            }
            if (empty($storeids)) {
                if (0 < $merchid) {

                    //没门店有商户
                    $stores = pdo_fetchall(
                        'select * ' .
                        ' from ' . tablename('superdesk_shop_merch_store') .
                        ' where 1 ' .
                        '       and uniacid=:uniacid ' .
                        '       and merchid=:merchid ' .
                        '       and status=1 ',
                        array(
                            ':uniacid' => $_W['uniacid'],
                            ':merchid' => $merchid
                        )
                    );

                } else {

                    //没门店没商户
                    $stores = pdo_fetchall(
                        'select * ' .
                        ' from ' . tablename('superdesk_shop_store') .
                        ' where 1 ' .
                        '       and uniacid=:uniacid ' .
                        '       and status=1',
                        array(
                            ':uniacid' => $_W['uniacid']
                        )
                    );
                }

            } else if (0 < $merchid) {

                //有门店有商户
                $stores = pdo_fetchall(
                    'select * ' .
                    ' from ' . tablename('superdesk_shop_merch_store') .
                    ' where 1 ' .
                    '       and id in (' . implode(',', $storeids) . ') ' .
                    '       and uniacid=:uniacid ' .
                    '       and merchid=:merchid ' .
                    '       and status=1 ',
                    array(
                        ':uniacid' => $_W['uniacid'],
                        ':merchid' => $merchid
                    )
                );
            } else {

                //有门店没商户
                $stores = pdo_fetchall(
                    'select * ' .
                    ' from ' . tablename('superdesk_shop_store') .
                    ' where 1 ' .
                    '       and id in (' . implode(',', $storeids) . ') ' .
                    '       and uniacid=:uniacid ' .
                    '       and status=1',
                    array(
                        ':uniacid' => $_W['uniacid']
                    )
                );
            }
        }

        // 评价
        //$commons = $this->get_comments();
        $commons_params = array(':goodsid' => $id, ':uniacid' => $_W['uniacid']);
        $commons        = array(
            'all'    => pdo_fetchcolumn(
                'select count(*) ' .
                ' from ' . tablename('superdesk_shop_order_comment') . // TODO 标志 楼宇之窗 openid superdesk_shop_order_comment 不处理
                ' where 1 ' .
                '       and goodsid=:goodsid ' .
                '       and level>=0 ' .
                '       and deleted=0 ' .
                '       and checked=0 ' .
                '       and uniacid=:uniacid',
                $commons_params
            ),
            'good'   => pdo_fetchcolumn(
                'select count(*) ' .
                ' from ' . tablename('superdesk_shop_order_comment') . // TODO 标志 楼宇之窗 openid superdesk_shop_order_comment 不处理
                ' where 1 ' .
                '       and goodsid=:goodsid ' .
                '       and level>=5 ' .
                '       and deleted=0 ' .
                '       and checked=0 ' .
                '       and uniacid=:uniacid',
                $commons_params
            ),
            'normal' => pdo_fetchcolumn(
                'select count(*) ' .
                ' from ' . tablename('superdesk_shop_order_comment') . // TODO 标志 楼宇之窗 openid superdesk_shop_order_comment 不处理
                ' where 1 ' .
                '       and goodsid=:goodsid ' .
                '       and level>=2 ' .
                '       and level<=4 ' .
                '       and deleted=0 ' .
                '       and checked=0 ' .
                '       and uniacid=:uniacid',
                $commons_params
            ),
            'bad'    => pdo_fetchcolumn(
                'select count(*) ' .
                ' from ' . tablename('superdesk_shop_order_comment') . // TODO 标志 楼宇之窗 openid superdesk_shop_order_comment 不处理
                ' where 1 ' .
                '       and goodsid=:goodsid ' .
                '       and level<=1 ' .
                '       and deleted=0 ' .
                '       and checked=0 ' .
                '       and uniacid=:uniacid',
                $commons_params
            ),
            'pic'    => pdo_fetchcolumn(
                'select count(*) ' .
                ' from ' . tablename('superdesk_shop_order_comment') . // TODO 标志 楼宇之窗 openid superdesk_shop_order_comment 不处理
                ' where 1 ' .
                '       and goodsid=:goodsid ' .
                '       and ifnull(images,\'a:0:{}\')<>\'a:0:{}\' ' .
                '       and deleted=0 ' .
                '       and checked=0 ' .
                '       and uniacid=:uniacid',
                $commons_params
            )
        );

        $percent = 100;

        if (0 < $commons['all']) {
            $percent = intval(($commons['good'] / ((empty($commons['all']) ? 1 : $commons['all']))) * 100);
        }

        $commons['percent'] = $percent;

        //2019年5月24日 16:48:51 zjh 文礼 价套
        $goods = m('goods')->getGoodsCategoryEnterpriseDiscountOne($goods);

        // $hotList = m('goods')->getList(array('page' => 1, 'pagesize' => 10, 'order' => 'sales', 'by' => 'DESC'), 0)['list']; // 已拆出去了

        show_json(1, compact('goods', 'thumbs', 'commons', 'isFavorite', 'params', 'specs', 'options', 'shopdetail'));
    }

    protected function getGoodsDispatchPrice($goods)
    {
        if (!(empty($goods['issendfree']))) {
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

        if (!(empty($areas)) && is_array($areas)) {

            $firstprice = array();

            foreach ($areas as $val) {
                $firstprice[] = $val['firstprice'];
            }

            array_push($firstprice, m('dispatch')->getDispatchPrice(1, $dispatch));

            $ret = array('min' => round(min($firstprice), 2), 'max' => round(max($firstprice), 2));

        } else {

            $ret = m('dispatch')->getDispatchPrice(1, $dispatch);
        }

        return $ret;
    }

    public function get_comment_list()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        $level = trim($_GPC['level']);

        $params = array(
            ':goodsid' => $id,
            ':uniacid' => $_W['uniacid']
        );

        $pindex = max(1, intval($_GPC['page']));
        $psize  = $_GPC['psize'] ? intval($_GPC['psize']) : 10;

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
            'select * ' .
            ' from ' . tablename('superdesk_shop_order_comment') .// TODO 标志 楼宇之窗 openid superdesk_shop_order_comment 不处理
            ' where 1 ' .
            '       and goodsid=:goodsid ' .
            '       and uniacid=:uniacid ' .
            '       and deleted=0 ' .
            '       and checked=0 ' .
            $condition .
            ' order by istop desc, createtime desc, id desc ' .
            ' LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize, $params);

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
            'select count(*) ' .
            ' from ' . tablename('superdesk_shop_order_comment') . // TODO 标志 楼宇之窗 openid superdesk_shop_order_comment 不处理
            ' where 1 ' .
            '       and goodsid=:goodsid ' .
            '       and uniacid=:uniacid ' .
            '       and deleted=0 ' .
            '       and checked=0 ' .
            $condition,
            $params
        );

        show_json(1, compact('list', 'total'));
    }

    public function qrcode()
    {
        global $_W;
        global $_GPC;

        $url = $_W['root'];

        show_json(1, array("url" => m("qrcode")->createQrcode($url)));
    }

    public function getMemberAddress()
    {
        global $_W;

        $params = array(
            ':uniacid'   => $_W['uniacid'],
            ':openid'    => $_W['openid'],
            ':core_user' => $_W['core_user'],
        );

        socket_log('plugin/pc/core/mobile/api/goods/detail.php->' . json_encode($params));

        $address = pdo_fetch(
            'select * ' .
            ' from ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 已处理
            ' where ' .
            '       uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and isdefault=1 ',
            $params
        );

        return $address;
    }

    public function checkJdStock()
    {
        global $_GPC, $_W;

        $id = $_GPC['id'];

        $goods = pdo_fetch(
            'select * ' .
            ' from ' . tablename('superdesk_shop_goods') .
            ' where 1 ' .
            '       and id=:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':id'      => $id,
                ':uniacid' => $_W['uniacid']
            )
        );

        if (!m('goods')->checkGoodsJdStock($goods)) {
            show_json(0, '该商品在该地区无库存');
        }

        show_json(1);
    }

    public function similarGoods()
    {
        global $_GPC, $_W;

        $id = $_GPC['id'];

        //2019年1月2日 16:24:43 zjh 关联商品
        $similar_item = pdo_fetch(
            ' select * ' .
            ' from ' . tablename('superdesk_shop_goods_similar') .
            ' where 1 ' .
            '       and id=:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':id'      => $id,
                ':uniacid' => $_W['uniacid']
            )
        );

        $similar_goods = array();

        if (!empty($similar_item)) {

            $select_fields =
                ' id,title,thumb,marketprice,productprice,minprice,maxprice,isdiscount,isdiscount_time, ' .
                ' isdiscount_discounts,sales,salesreal,total,description,bargain,jd_vop_sku,merchid,tcate,costprice ';

            $similar_goods = pdo_fetchall(
                ' select ' .
                $select_fields .
                ' from ' . tablename('superdesk_shop_goods') .
                ' where id in (' . $similar_item['similar_id'] . ') and status=1 and deleted=0 and uniacid=' . $_W['uniacid'] .
                ' order by instr(\'' . $similar_item['similar_id'] . '\',id)');

            $similar_goods = set_medias($similar_goods, 'thumb');

            //2019年5月24日 16:48:51 zjh 文礼 价套
            $similar_goods = m('goods')->getGoodsCategoryEnterpriseDiscount($similar_goods);

            show_json(1, $similar_goods);
        }

        show_json(0);
    }
}