<?php

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/accessControl.php";

if (!(defined("IN_IA"))) {
    exit("Access Denied");
}

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/page_mobile.php";

class Index_SuperdeskShopV2Page extends PcMobilePage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        $catlevel = intval($_W['shopset']['category']['level']);

        $opencategory = true;

        $plugin_commission = p('commission');

        if ($plugin_commission && (0 < intval($_W['shopset']['commission']['level']))) {

            $mid = intval($_GPC['mid']);

            if (!(empty($mid))) {

                $shop = p('commission')->getShop($mid);

                if (empty($shop['selectcategory'])) {
                    $opencategory = false;
                }
            }
        }

        $args = array(
            'pagesize'    => $_GPC['psize'] ? intval($_GPC['psize']) : 12,
            'page'        => intval($_GPC['page']),
            'isnew'       => trim($_GPC['isnew']),
            'ishot'       => trim($_GPC['ishot']),
            'isrecommand' => trim($_GPC['isrecommand']),
            'isdiscount'  => trim($_GPC['isdiscount']),
            'istime'      => trim($_GPC['istime']),
            'issendfree'  => trim($_GPC['issendfree']),
            'keywords'    => trim($_GPC['keywords']),
            'cate'        => trim($_GPC['cate']),
            'order'       => trim($_GPC['order']),
            'by'          => trim($_GPC['by']),
            'priceMin'    => trim($_GPC['priceMin']),
            'priceMax'    => trim($_GPC['priceMax']),
            'showcount'   => $_GPC['showcount'] ? intval($_GPC['showcount']) : 0,
            'ids'         => $_GPC['goodsids'],
            'merchids'    => $_GPC['merchids'],
            'showAll'     => !empty($_GPC['showAll']) ? 1 : 0,
        );

        $plugin_commission = p('commission');

        if ($plugin_commission && (0 < intval($_W['shopset']['commission']['level']))) {
            $mid = intval($_GPC['mid']);
            if (!(empty($mid))) {
                $shop = p('commission')->getShop($mid);
                if (!(empty($shop['selectgoods']))) {
                    $args['ids'] = $shop['goodsids'];
                }
            }
        }

        $goods = $this->goods_list($args);

        show_json(1, $goods);
    }

    /*
     * 获取历史浏览
     */
    public function get_history()
    {
        global $_W;
        global $_GPC;

        $page  = ((!empty($_GPC['page']) ? intval($_GPC['page']) : 1));
        $psize = ((!empty($_GPC['psize']) ? intval($_GPC['psize']) : 8));

        if (empty($_W['openid'])) {

            $condition =
                ' and f.uniacid=:uniacid ' .
                ' and f.deleted=0';

            $params = array(
                ':uniacid' => $_W['uniacid']
            );

        } else {

            $condition = ' and f.uniacid = :uniacid ' .
                ' and f.openid=:openid ' .
                ' and f.core_user=:core_user ' .
                ' and f.deleted=0';

            $params = array(
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user'],
            );
        }

        $sql = ' SELECT ' .
            '       f.id,f.goodsid,g.title,g.thumb,g.marketprice,g.productprice,f.createtime,g.merchid,g.tcate,g.costprice ' .
            ' FROM ' . tablename('superdesk_shop_member_history') . ' f ' . // TODO 标志 楼宇之窗 openid superdesk_shop_member_history 已处理
            '       left join ' . tablename('superdesk_shop_goods') . ' g on f.goodsid = g.id ' .
            ' where 1 ' .
            $condition .
            ' ORDER BY f.`id` DESC ' .
            ' LIMIT ' . (($page - 1) * $psize) . ',' . $psize;

        $list = pdo_fetchall($sql, $params);

        $total_sql = ' SELECT count(*) ' .
            ' FROM ' . tablename('superdesk_shop_member_history') . ' f ' . // TODO 标志 楼宇之窗 openid superdesk_shop_member_history 已处理
            '       left join ' . tablename('superdesk_shop_goods') . ' g on f.goodsid = g.id ' .
            ' where 1 ' .
            $condition;

        $total = pdo_fetchcolumn($total_sql, $params);

        $merch_plugin = p('merch');

        $merch_data = m('common')->getPluginset('merch');

        if (!(empty($list)) && $merch_plugin && $merch_data['is_openmerch']) {
            $merch_user = $merch_plugin->getListUser($list, 'merch_user');
        }

        foreach ($list as &$row) {

            $row['createtime'] = date('Y-m-d H:i:s', $row['createtime']);
            $row['thumb']      = tomedia($row['thumb']);
            $row['merchname']  = (($merch_user[$row['merchid']]['merchname'] ? $merch_user[$row['merchid']]['merchname'] : $_W['shopset']['shop']['name']));
        }

        //2019年5月24日 16:48:51 zjh 文礼 价套
        $list = m('goods')->getGoodsCategoryEnterpriseDiscount($list);

        unset($row);

        show_json(1, array(
            'list'  => $list,
            'total' => $total
        ));
    }

    /**
     * @param array $args
     *
     * @return mixed
     */
    protected function goods_list($args = array())
    {
        global $_GPC;
        global $_W;

        $_default = array(
            'pagesize'    => 10,
            'page'        => 1,
            'isnew'       => '',
            'ishot'       => '',
            'isrecommand' => '',
            'isdiscount'  => '',
            'istime'      => '',
            'keywords'    => '',
            'cate'        => '',
            'showSql'     => $_GPC['showSql'] ? 1 : 0
//            'order'       => 'id',
//            'by'          => 'desc'
        );

        $args = array_merge($_default, $args);

        $merch_plugin = p('merch');
        $merch_data   = m('common')->getPluginset('merch');

        if ($merch_plugin && $merch_data['is_openmerch']) {
            $args['merchid'] = intval($_GPC['merchid']);
        }

        if (isset($_GPC['nocommission'])) {
            $args['nocommission'] = intval($_GPC['nocommission']);
        }

        $goods = m('goods')->getList($args, $args['showcount'], true);

        //$goods = m('goods')->checkGoodsListJdStock($goods['list']);

        return $goods;
    }

    private function getList($args = array())
    {
        global $_W;

        $page     = ((!empty($args['page']) ? intval($args['page']) : 1));
        $pagesize = ((!empty($args['pagesize']) ? intval($args['pagesize']) : 10));
        $random   = ((!empty($args['random']) ? $args['random'] : false));
        $order    = ((!empty($args['order']) ? $args['order'] : ' displayorder desc,createtime desc'));
        $orderby  = ((empty($args['order']) ? '' : ((!empty($args['by']) ? $args['by'] : ''))));

        $merch_plugin = p('merch');
        $merch_data   = m('common')->getPluginset('merch');

        if ($merch_plugin && $merch_data['is_openmerch']) {
            $is_openmerch = 1;
        } else {
            $is_openmerch = 0;
        }

        $condition =
            ' and `uniacid` = :uniacid ' .
            ' AND `deleted` = 0 ' .
            ' and status=1 ';

        $params = array(
            ':uniacid' => $_W['uniacid']
        );

        $merchid = ((!empty($args['merchid']) ? trim($args['merchid']) : ''));

        if (!empty($merchid)) {
            $condition          .= ' and merchid=:merchid and checked=0';
            $params[':merchid'] = $merchid;
        } else if ($is_openmerch == 0) {
            $condition .= ' and `merchid` = 0';
        } else {
            $condition .= ' and `checked` = 0';
        }

        // 最低价与最高价 start 微信端本来没的
        $priceMin = ((!(empty($args['priceMin'])) ? trim($args['priceMin']) : ''));
        $priceMax = ((!(empty($args['priceMax'])) ? trim($args['priceMax']) : ''));

        if (!(empty($priceMin))) {
            $condition .= ' and minprice > \'' . $priceMin . '\'';
        }

        if (!(empty($priceMax))) {
            $condition .= ' and maxprice < \'' . $priceMax . '\'';
        }

        // 最低价与最高价 start

        if (empty($args['type'])) {
//            $condition .= ' and type !=10 ';
        }

        $ids = ((!empty($args['ids']) ? trim($args['ids']) : ''));
        if (!empty($ids)) {
            $condition .= ' and id in ( ' . $ids . ')';
        }

        $isnew = ((!empty($args['isnew']) ? 1 : 0));
        if (!empty($isnew)) {
            $condition .= ' and isnew=1';
        }

        $ishot = ((!empty($args['ishot']) ? 1 : 0));
        if (!empty($ishot)) {
            $condition .= ' and ishot=1';
        }

        $isrecommand = ((!empty($args['isrecommand']) ? 1 : 0));
        if (!empty($isrecommand)) {
            $condition .= ' and isrecommand=1';
        }

        $isdiscount = ((!empty($args['isdiscount']) ? 1 : 0));
        if (!empty($isdiscount)) {
            $condition .= ' and isdiscount=1';
        }

        $issendfree = ((!empty($args['issendfree']) ? 1 : 0));
        if (!empty($issendfree)) {
            $condition .= ' and issendfree=1';
        }

        $istime = ((!empty($args['istime']) ? 1 : 0));
        if (!empty($istime)) {
            $condition .= ' and istime=1 ';
        }

        if (isset($args['nocommission'])) {
            $condition .= ' AND `nocommission`=' . intval($args['nocommission']);
        }

        $keywords = ((!empty($args['keywords']) ? $args['keywords'] : ''));
        if (!empty($keywords)) {
            $condition           .= ' AND (`title` LIKE :keywords OR `keywords` LIKE :keywords)';
            $params[':keywords'] = '%' . trim($keywords) . '%';
        }

        if (!empty($args['cate'])) {
            $category = m('shop')->getAllCategory();
            $catearr  = array($args['cate']);
            foreach ($category as $index => $row) {
                if ($row['parentid'] == $args['cate']) {
                    $catearr[] = $row['id'];
                    foreach ($category as $ind => $ro) {
                        if ($ro['parentid'] == $row['id']) {
                            $catearr[] = $ro['id'];
                        }
                    }
                }
            }
            $catearr   = array_unique($catearr);
            $condition .= ' AND ( ';
            foreach ($catearr as $key => $value) {
                if ($key == 0) {
                    $condition .= 'FIND_IN_SET(' . $value . ',cates)';
                } else {
                    $condition .= ' || FIND_IN_SET(' . $value . ',cates)';
                }
            }
            $condition .= ' <>0 )';
        }

        $member = m('member')->getMember($_W['openid'], $_W['core_user']);

        if (!empty($member)) {

            $levelid = intval($member['level']);
            $groupid = intval($member['groupid']);

            $condition .= ' and ( ifnull(showlevels,\'\')=\'\' or FIND_IN_SET( ' . $levelid . ',showlevels)<>0 ) ';
            $condition .= ' and ( ifnull(showgroups,\'\')=\'\' or FIND_IN_SET( ' . $groupid . ',showgroups)<>0 ) ';

        } else {

            $condition .= ' and ifnull(showlevels,\'\')=\'\' ';
            $condition .= ' and ifnull(showgroups,\'\')=\'\' ';

        }

        $total = '';


        $select_fields =
            " id,title,thumb,marketprice,productprice,minprice,maxprice,isdiscount,isdiscount_time,isdiscount_discounts,sales,total,description,bargain,jd_vop_sku ";

        if (!$random) {
            $sql =
                ' SELECT ' . $select_fields .
                ' FROM ' . tablename('superdesk_shop_goods') .
                ' WHERE 1 ' . $condition .
                ' ORDER BY ' . $order . ' ' . $orderby .
                ' LIMIT ' . (($page - 1) * $pagesize) . ',' . $pagesize;
            if ($args['showcount']) {
                $total = pdo_fetchcolumn('select count(*) from ' . tablename('superdesk_shop_goods') . ' where 1 ' . $condition . ' ', $params);
            }
        } else {
            $sql   =
                ' SELECT ' . $select_fields .
                ' FROM ' . tablename('superdesk_shop_goods') .
                ' WHERE 1 ' . $condition .
                ' ORDER BY rand() ' .
                ' LIMIT ' . $pagesize;
            $total = $pagesize;
        }

        $list = pdo_fetchall($sql, $params);
        $list = set_medias($list, 'thumb');
        //$list = $this->jd_vop_price_update($list);

        // TODO 更新商品价格

        return array('list' => $list, 'total' => $total);
    }


    private function jd_vop_price_update($list = array())
    {
        if (empty($list)) {
            return array();
        }

        $skuArr = array();

        foreach ($list as $index => $item) {


            if ($item['jd_vop_sku'] != 0) { // 不为0才是京东商品
                $skuArr[$index] = $item['jd_vop_sku'];
            }
        }

        include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/PriceService.class.php');
        $_priceService    = new PriceService();// ok
        $api_price_result = $_priceService->getPrice($skuArr);


        foreach ($skuArr as $list_index => $target_sku) {

            $__item__ = $api_price_result[$target_sku];

//          "124157": {
//            "skuId": 124157,
//            "costprice": 64,// 协议价格 成本
//            "productprice": 67.2, // 商品原价
//            "marketprice": 67.2 // 商品现价
//          }

            $list[$list_index]['costprice']    = $__item__['costprice'];
            $list[$list_index]['productprice'] = $__item__['productprice'];
            $list[$list_index]['marketprice']  = $__item__['marketprice'];
            $list[$list_index]['minprice']     = $__item__['marketprice'];
            $list[$list_index]['maxprice']     = $__item__['marketprice'];
        }

        return $list;
    }

    public function activityGoodsListV2()
    {
        global $_W;
        global $_GPC;

        $id = $_GPC['id'];//线上的话 11为纸品 10为合并 7为新项目 3为IT 线下模拟建了一个2

        $page = pdo_fetch(
            'select * ' .
            ' from ' . tablename('superdesk_shop_diypage') .
            ' where 1 ' .
            '       and id=:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1 ',
            array(
                ':id'      => $id,
                ':uniacid' => $_W['uniacid']
            )
        );

        if (empty($page)) {
            show_json(0, '模板不存在');
        }

        $diypage = p('diypage')->getPage($id);

        $data = $diypage['data']['items'];

        $bannerData      = array();
        $titleData       = array();
        $goodsData       = array();
        $thirdLevelData  = array();
        $secondLevelData = array();
        $title           = '0';
        $picture         = '0';

//        show_json(1,$data);die;
        foreach ($data as $itemid => $item) {

            if ($item['id'] == 'title') {

                $titleData[] = $item['params']['title'];
                $title       = $item['params']['title'];

            } elseif ($item['id'] == 'goods') {


                if ($item['params']['goodsdata'] == '1') {

                    $limit = $item['params']['goodsnum'];
//                    $limit   = 80;
                    $cateid = $item['params']['cateid'];

                    if (!empty($cateid)) {

                        $orderby   = ' displayorder desc, createtime desc';
                        $goodssort = $item['params']['goodssort'];

                        if (!empty($goodssort)) {
                            if ($goodssort == 1) {
                                $orderby = ' sales + salesreal desc, displayorder desc';
                            } else if ($goodssort == 2) {
                                $orderby = ' minprice desc, displayorder desc';
                            } else if ($goodssort == 3) {
                                $orderby = ' minprice asc, displayorder desc';
                            }
                        }
                        $goodslist = m('goods')->getList(
                            array(
                                'cate'     => $cateid,
                                'pagesize' => $limit,
                                'page'     => 1,
                                'order'    => $orderby
                            )
                        );

                        $goods        = $goodslist['list'];
                        $item['data'] = array();

                        if (!empty($goods) && is_array($goods)) {

                            foreach ($goods as $index => $good) {

                                $showgoods = m('goods')->visit($good, $this->member);

                                if (!empty($showgoods)) {

                                    $childid                = rand(1000000000, 9999999999);
                                    $childid                = 'C' . $childid;
                                    $item['data'][$childid] = array(
                                        'thumb'        => $good['thumb'],
                                        'title'        => $good['title'],
                                        'price'        => $good['minprice'],
                                        'productprice' => $good['productprice'],
                                        'gid'          => $good['id'],
                                        'total'        => $good['total'],
                                        'bargain'      => $good['bargain'],
                                        'sales'        => $good['sales'],
                                        'salesreal'    => $good['salesreal'],
                                        'storename'    => $good['storename'],
                                        'groupname'    => $good['groupname'],
                                    );

                                }
                            }
                        }
                    } else {
                        $item['data'] = array();
                    }
                } else if ($item['params']['goodsdata'] == '2') {

                    $limit = $item['params']['goodsnum'];
//                    $limit   = 80;
                    $groupid = intval($item['params']['groupid']);

                    if (!empty($groupid)) {

                        $group = pdo_fetch(
                            ' SELECT * ' .
                            ' FROM ' . tablename('superdesk_shop_goods_group') .
                            ' WHERE 1 ' .
                            '       and id=:id ' .
                            '       and uniacid=:uniacid ' .
                            ' limit 1 ',
                            array(
                                ':id'      => $groupid,
                                ':uniacid' => $_W['uniacid']
                            )
                        );

                    }
                    $item['data'] = array();
                    if (!empty($group) && !empty($group['goodsids'])) {

                        $orderby   = ' order by displayorder desc';
                        $goodssort = $item['params']['goodssort'];

                        if (!empty($goodssort)) {
                            if ($goodssort == 1) {
                                $orderby = ' order by sales+salesreal desc, displayorder desc';
                            } else if ($goodssort == 2) {
                                $orderby = ' order by minprice desc, displayorder desc';
                            } else if ($goodssort == 3) {
                                $orderby = ' order by minprice asc, displayorder desc';
                            }
                        }
                        $goodsids = $group['goodsids'];
                        $goods    = pdo_fetchall(
                            ' select id, title, thumb, minprice, productprice, sales, salesreal, total, showlevels, showgroups, bargain, merchid, tcate, costprice ' .
                            ' from ' . tablename('superdesk_shop_goods') .
                            ' where id in( ' . $goodsids . ' ) ' .
                            '       and status=1 ' .
                            '       and `deleted`=0 ' .
                            '       and `status`=1 ' .
                            '       and uniacid=:uniacid '
                            . $orderby
                            . ' limit ' . $limit,
                            array(
                                ':uniacid' => $_W['uniacid']
                            )
                        );

                        $goods = m('goods')->getGoodsMerch($goods);

                        //2019年5月24日 16:48:51 zjh 文礼 价套
                        $goods = m('goods')->getGoodsCategoryEnterpriseDiscount($goods);

                        if (!empty($goods) && is_array($goods)) {
                            foreach ($goods as $index => $good) {
                                $showgoods = m('goods')->visit($good, $this->member);
                                if (!empty($showgoods)) {
                                    $childid                = rand(1000000000, 9999999999);
                                    $childid                = 'C' . $childid;
                                    $item['data'][$childid] = array(
                                        'thumb'        => $good['thumb'],
                                        'title'        => $good['title'],
                                        'price'        => $good['minprice'],
                                        'productprice' => $good['productprice'],
                                        'gid'          => $good['id'],
                                        'total'        => $good['total'],
                                        'bargain'      => $good['bargain'],
                                        'sales'        => $good['sales'],
                                        'salesreal'    => $good['salesreal'],
                                        'storename'    => $good['storename'],
                                        'groupname'    => $good['groupname'],
                                    );
                                }
                            }
                        }
                    }
                } else if (2 < $item['params']['goodsdata']) {

                    $args = array(
                        'pagesize' => $item['params']['goodsnum'],
                        'page'     => 1
                    );

                    if ($item['params']['goodsdata'] == 3) { /* 选择商品(商品类型): 新品商品 */
                        $args['isnew'] = 1;
                    } else if ($item['params']['goodsdata'] == 4) {/* 选择商品(商品类型): 热卖商品 */
                        $args['ishot'] = 1;
                    } else if ($item['params']['goodsdata'] == 5) {/* 选择商品(商品类型): 推荐商品 */
                        $args['isrecommand'] = 1;
                    } else if ($item['params']['goodsdata'] == 6) {/* 选择商品(商品类型): 促销商品 */
                        $args['isdiscount'] = 1;
                    } else if ($item['params']['goodsdata'] == 7) {/* 选择商品(商品类型): 包邮商品 */
                        $args['issendfree'] = 1;
                    } else if ($item['params']['goodsdata'] == 8) {/* 选择商品(商品类型): 限时卖商品 */
                        $args['istime'] = 1;
                    }

                    $goodssort = $item['params']['goodssort'];
                    if (!empty($goodssort)) {
                        if ($goodssort == 1) {
                            $args['order'] = ' sales+salesreal desc, displayorder desc';
                        } else if ($goodssort == 2) {
                            $args['order'] = ' minprice desc, displayorder desc';
                        } else if ($goodssort == 3) {
                            $args['order'] = ' minprice asc, displayorder desc';
                        }
                    }

                    $goodslist = m('goods')->getList($args);
                    $goods     = $goodslist['list'];

                    $item['data'] = array();

                    if (!empty($goods) && !empty($goods) && is_array($goods)) {

                        foreach ($goods as $index => $good) {

                            $showgoods = m('goods')->visit($good, $this->member);

                            if (!empty($showgoods)) {
                                $childid                = rand(1000000000, 9999999999);
                                $childid                = 'C' . $childid;
                                $item['data'][$childid] = array(
                                    'thumb'     => $good['thumb'],
                                    'title'     => $good['title'],
                                    'price'     => $good['minprice'],
                                    'gid'       => $good['id'],
                                    'total'     => $good['total'],
                                    'bargain'   => $good['bargain'],
                                    'sales'     => $good['sales'],
                                    'salesreal' => $good['salesreal'],
                                    'storename'    => $good['storename'],
                                    'groupname'    => $good['groupname'],
                                );
                            }
                        }
                    }
                }

//                $goodsData                        = array_merge($goodsData, $item['data']);
                $goodsData = $item['data'];
                $this->filter_medias($goodsData);

                $thirdLevelData[$picture][$title] = $goodsData;//$item['data'];
                $secondLevelData[$title]          = $goodsData;//$item['data'];

            } elseif ($item['id'] == 'picture') {

                $img       = reset($item['data']);
                $picture   = strrchr($img['imgurl'], '/');
                $arr       = parse_url($img['linkurl']);
                $arr_query = $this->convertUrlQuery($arr['query']);
                $img['id'] = $arr_query['id'];
                //$img['mini_name'] = $picture;
                $bannerData[$picture] = $img;
                $title                = '0';

            }
        }

        $bannerData = set_medias($bannerData, 'imgurl');

        show_json(1, compact('bannerData', 'titleData', 'goodsData', 'thirdLevelData', 'secondLevelData'));
    }


    /**
     * 处理自定义页面中"商品组"商品 第三方商户 非京东 缩略图 不工整问题
     *
     * @param     $goodsData
     * @param int $height
     * @param int $width
     */
    public function filter_medias(&$goodsData, $height = 600, $width = 600)
    {

        global $_W;

        foreach ($goodsData as $itemid => &$item) {

            $item['thumb'] = tomedia($item['thumb']);
//            $item['siteroot'] = $_W['siteroot'];

            if (strexists($item['thumb'], $_W['siteroot'])) {

                $src = urlencode($item['thumb']);
                $cut = $_W['siteroot'] . 'thumbnail/timthumb.php?src=' . $src . '&h=' . $height . '&w=' . $width . '&zc=1';

                $item['thumb'] = $cut;
            }
        }


    }

    public function activityGoodsList()
    {
        global $_W;
        global $_GPC;

        $openid = $_W['openid'];

        $_switch_source = (!empty($args['switch_source']) ? $args['switch_source'] : 'mysql');// elasticsearch mysql

        $page     = (!empty($_GPC['page']) ? intval($_GPC['page']) : 1);
        $pagesize = (!empty($_GPC['psize']) ? intval($_GPC['psize']) : 10);
        $order    = (!empty($_GPC['order']) ? $_GPC['order'] : ' displayorder desc,createtime desc');
        $orderby  = (empty($_GPC['order']) ? '' : (!empty($_GPC['by']) ? $_GPC['by'] : ''));

        $condition =
            ' uniacid = :uniacid ' .
            ' AND merchid in (43,44) ';

        $params = array(
            ':uniacid' => $_W['uniacid']
        );

        $condition          .= ' AND deleted = :deleted ';
        $params[':deleted'] = 0;

        $condition         .= ' AND status = :status ';
        $params[':status'] = 1;

        $total = '';


        $select_fields =
            " id,title,thumb,marketprice,productprice,minprice,maxprice,isdiscount,isdiscount_time,isdiscount_discounts,sales,total,description,bargain,jd_vop_sku,merchid ";

        $sql =
            ' SELECT ' . $select_fields . // SQL_CALC_FOUND_ROWS
            ' FROM ' . tablename('superdesk_shop_goods') .
            ' WHERE ' . $condition .//' WHERE 1 ' . $condition .
            ' ORDER BY ' . $order . ' ' . $orderby .
            ' LIMIT ' . (($page - 1) * $pagesize) . ',' . $pagesize;


        socket_log($sql);

        if ($_switch_source == 'mysql') {

            $total = pdo_fetchcolumn(
                ' select count(id) ' .
                ' from ' . tablename('superdesk_shop_goods') .
                ' where ' . $condition . ' ',
                $params
            );

            $list = pdo_fetchall($sql, $params);

            $list = set_medias($list, 'thumb');

            //判断京东商品是否有库存 zjh 添加于 2018年5月2日 18:17:08
            $list = m('goods')->checkGoodsListJdStock($list);

            show_json(1, array('list' => $list, 'total' => $total));


        } else if ($_switch_source == 'elasticsearch') {

            include_once(IA_ROOT . '/addons/superdesk_shopv2_elasticsearch/model/goods/ES_shop_goodsModel.class.php');
            $_es_shop_goodsModel = new ES_shop_goodsModel();

            $_es_result = $_es_shop_goodsModel->fetchAll($sql, $params);
            $list       = $_es_result['list'];
            $total      = $_es_result['total'];

            $list = set_medias($list, 'thumb');

            //判断京东商品是否有库存 zjh 添加于 2018年5月2日 18:17:08
            $list = m('goods')->checkGoodsListJdStock($list);

            show_json(1, array('list' => $list, 'total' => $total));

        }
    }

    function convertUrlQuery($query)
    {
        $queryParts = explode('&', $query);
        $params     = array();
        foreach ($queryParts as $param) {
            $item             = explode('=', $param);
            $params[$item[0]] = $item[1];
        }
        return $params;
    }

    /**
     * 将参数变为字符串
     *
     * @param $array_query
     *
     * @return string string 'm=content&c=index&a=lists&catid=6&area=0&author=0&h=0&region=0&s=1&page=1' (length=73)
     */
    function getUrlQuery($array_query)
    {
        $tmp = array();

        foreach ($array_query as $k => $param) {
            $tmp[] = $k . '=' . $param;
        }

        $params = implode('&', $tmp);

        return $params;
    }
}