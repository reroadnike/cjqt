<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

//include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/PriceService.class.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/StockService.class.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/SearchService.class.php');

include_once(IA_ROOT . '/addons/superdesk_shopv2_elasticsearch/third_party/library/PDO_elasticsearch.class.php');


class Goods_SuperdeskShopV2Model
{

    private $_priceService;
    private $_stockService;
    private $_searchService;

    public function __construct()
    {
        $this->_priceService  = new PriceService();
        $this->_stockService  = new StockService();
        $this->_searchService = new SearchService();
    }

    public function getOption($goodsid = 0, $optionid = 0)
    {
        global $_W;
        return pdo_fetch(
            ' select * ' .
            ' from ' . tablename('superdesk_shop_goods_option') .
            ' where id=:id ' .
            '       and goodsid=:goodsid ' .
            '       and uniacid=:uniacid ' .
            ' Limit 1',
            array(
                ':id'      => $optionid,
                ':uniacid' => $_W['uniacid'],
                ':goodsid' => $goodsid,
            )
        );
    }

    public function getOptionPirce($goodsid = 0, $optionid = 0)
    {
        global $_W;
        return pdo_fetchcolumn(
            ' select marketprice ' .
            ' from ' . tablename('superdesk_shop_goods_option') .
            ' where id=:id ' .
            '       and goodsid=:goodsid ' .
            '       and uniacid=:uniacid',
            array(
                ':id'      => $optionid,
                ':uniacid' => $_W['uniacid'],
                ':goodsid' => $goodsid,
            )
        );
    }

    public function getList($args = array(), $showtotal = 1/** 控制是否返回商品总数 **/, $jd_search_switch = false/** 控制是否调用京东搜索接口 **/)
    {
        global $_W;

        $_switch_source = (!empty($args['switch_source']) ? $args['switch_source'] : SUPERDESK_SHOPV2_MODE_SEARCH);// elasticsearch mysql
        $page           = (!empty($args['page']) ? intval($args['page']) : 1);
        $pagesize       = (!empty($args['pagesize']) ? intval($args['pagesize']) : 10);
        $random         = (!empty($args['random']) ? $args['random'] : false);
        $order          = (!empty($args['order']) ? $args['order'] : ' displayorder desc,createtime desc');
//        $order          = (!empty($args['order']) ? $args['order'] : ' displayorder desc,updatetime desc');
        $orderby = (empty($args['order']) ? '' : (!empty($args['by']) ? $args['by'] : ''));

        $_search_record = array();


        //是否开启多商户
        $merch_plugin = p('merch');
        $merch_data   = m('common')->getPluginset('merch');
        if ($merch_plugin && $merch_data['is_openmerch']) {
            $is_openmerch = 1;
        } else {
            $is_openmerch = 0;
        }


        $condition = ' uniacid = :uniacid AND total>0 ';
        $params    = array(
            ':uniacid' => $_W['uniacid'],
        );

        $condition          .= ' AND deleted = :deleted ';
        $params[':deleted'] = 0;

        $condition         .= ' AND status = :status ';
        $params[':status'] = 1;


        //商户条件判定
        $merchid = ((!empty($args['merchid']) ? trim($args['merchid']) : ''));

        if (!empty($merchid)) {//假如有商户,添加商户id以及商品审核通过条件
            $condition          .= ' and merchid=:merchid and checked=0';
            $params[':merchid'] = $merchid;
        } else if ($is_openmerch == 0) {//否则假如没开启多商户,添加自营id
            $condition .= ' and merchid = 0';
        } else {
            if(empty($args['showAll'])){
                //否则就是开启了多商户但是没商户id的,添加默认全局可见的商户
                $condition .= ' and checked = 0';

                //获取全局可见商户
                $merch_ids = $_W['superdesk_shop_member_2_merch_ids'];

                if (!empty($merch_ids)) {
                    $condition .= ' and merchid in ( ' . $merch_ids . ')';
                }
            }

        }

        $condition .= ' and minprice > 0.00';// 之前存在sku为0.00

        // 最低价与最高价 start 微信端本来没的
        $priceMin = ((!(empty($args['priceMin'])) ? trim($args['priceMin']) : ''));
        $priceMax = ((!(empty($args['priceMax'])) ? trim($args['priceMax']) : ''));

        if (!(empty($priceMin))) {
            $condition                          .= ' and minprice > \'' . $priceMin . '\'';
            $_search_record['has_pricebetween'] = 1;
            $_search_record['minprice']         = $priceMin;
        }
        if (!(empty($priceMax))) {
//            $condition .= ' and maxprice < \'' . $priceMax . '\'';
            $condition                          .= ' and minprice < \'' . $priceMax . '\'';
            $_search_record['has_pricebetween'] = 1;
            $_search_record['maxprice']         = $priceMax;
        }
        // 最低价与最高价 end

        if (empty($args['type'])) {
//            $condition .= ' and type !=10 ';//类型 1 实体物品 2 虚拟物品 3 虚拟物品(卡密) 4 批发 10 话费流量充值 20 充值卡
        }

        $ids = ((!empty($args['ids']) ? trim($args['ids']) : ''));
        if (!empty($ids)) {
            $condition .= ' and id in ( ' . $ids . ')';
        }

        $merchids = ((!empty($args['merchids']) ? trim($args['merchids']) : ''));
        if (!empty($merchids)) {
            $condition .= ' and merchid in ( ' . $merchids . ')';
        }

        $isnew = ((!empty($args['isnew']) ? 1 : 0));
        if (!empty($isnew)) {
            $condition                    .= ' and isnew=1';
            $_search_record['has_filter'] = 1;
            $_search_record['filters'][]  = 'isnew';
        }

        $ishot = ((!empty($args['ishot']) ? 1 : 0));
        if (!empty($ishot)) {
            $condition                    .= ' and ishot=1';
            $_search_record['has_filter'] = 1;
            $_search_record['filters'][]  = 'ishot';
        }

        $isrecommand = ((!empty($args['isrecommand']) ? 1 : 0));
        if (!empty($isrecommand)) {
            $condition                    .= ' and isrecommand=1';
            $_search_record['has_filter'] = 1;
            $_search_record['filters'][]  = 'isrecommand';
        }

        $isdiscount = ((!empty($args['isdiscount']) ? 1 : 0));
        if (!empty($isdiscount)) {
            $condition                    .= ' and isdiscount=1';
            $_search_record['has_filter'] = 1;
            $_search_record['filters'][]  = 'isdiscount';
        }

        $issendfree = ((!empty($args['issendfree']) ? 1 : 0));
        if (!empty($issendfree)) {
            $condition                    .= ' and issendfree=1';
            $_search_record['has_filter'] = 1;
            $_search_record['filters'][]  = 'issendfree';
        }

        $istime = ((!empty($args['istime']) ? 1 : 0));
        if (!empty($istime)) {
            $condition                    .= ' and istime=1 ';
            $_search_record['has_filter'] = 1;
            $_search_record['filters'][]  = 'istime';
        }

        if (isset($args['nocommission'])) {
            $condition                    .= ' AND nocommission=' . intval($args['nocommission']);
            $_search_record['has_filter'] = 1;
            $_search_record['filters'][]  = 'nocommission';
        }

        // 关键字处理
        $jd_search_result = array();
        $keywords = ((!empty($args['keywords']) ? $args['keywords'] : ''));
        if (!empty($keywords)) {

            // 2019年5月6日 10:23:21 zjh 新处理 替换 - 为 空格
            $keywords = str_replace('-',' ',$keywords);

            //luoxt 20190702 ik分词
            $keyword  = strtolower($keywords);
            $keywords = explode(' ', $keyword);
            try{
                load()->func('communication');
                $loginurl = 'http://open.api.test.superdesk.cn/api/base/dict/analy';
                $post = array(
                    'text' => $keyword,
                );
                $response = ihttp_post($loginurl, $post);
                if(isset($response['content']) && !empty($response['content'])) {
                    $res_data = json_decode($response['content'], true);
                } else {
                    $res_data = [];
                }

                $keyword_arr = [];
                if(isset($res_data['result']) && !empty($res_data['result'])){
                    foreach ($res_data['result'] as $res_val){
                        if(isset($res_val['token']) && !empty($res_val['token'])){
                            array_push($keyword_arr, $res_val['token']);
                        }
                    }
                }

                if(count($keyword_arr)) {
                    $keywords = $keyword_arr;
                }
            } catch (Exception $exception) {}


            if ($_switch_source == 'mysql') {

                // 新处理，空格为分隔
                //$keywords = explode(' ', $keywords);
                $condition_keywords = array();
                foreach ($keywords as $_index => $keyword) {
                    $condition_keywords[$_index]   = ' title LIKE :keywords' . $_index . ' ';
                    $params[':keywords' . $_index] = '%' . trim($keyword) . '%';
                }

//              $condition .= ' AND ( ' . implode(' AND ', $condition_keywords) . ' )';
                $condition .= ' AND ' . implode(' AND ', $condition_keywords) . ' ';

            } else if ($_switch_source == 'elasticsearch') {

                // 原处理
//            $condition .= ' AND (title LIKE :keywords OR keywords LIKE :keywords)';
//            $params[':keywords'] = '%' . trim($keywords) . '%';


                //$keywords           = strtolower($keywords);

                //$keywords           = explode(' ', $keywords);
                $condition_keywords = array();
                $condition_subtitle = array();
                foreach ($keywords as $_index => $keyword) {
                    // $condition_keywords[$_index]   = ' title LIKE :keywords' . $_index . ' ';
                    // $params[':keywords' . $_index] = '' . trim($keyword) . '';
                    $condition_keywords[$_index] = ' title.keyword LIKE :keywords' . $_index . ' ';

                    //处理子标题
                    $condition_subtitle[$_index] = ' subtitle LIKE :keywords' . $_index . ' ';
                    $params[':keywords' . $_index] = '%' . trim($keyword) . '%';
                }

                $condition .= ' AND ((' . implode(' AND ', $condition_keywords) . ') ';

                //子标题
                $condition .= ' OR (' . implode(' AND ', $condition_subtitle) . ' )) ';

            }

            $_search_record['has_keyword'] = 1;
            $_search_record['keyword']     = $args['keywords'];

            //京东搜索商品接口
            if ($jd_search_switch) {
                $jd_search_result = $this->_searchService->searchSearch($args['keywords']);

                //TODO 目前就先不给他第一个三级分类 先屏蔽掉
//            if (empty($args['cate']) && $jd_search_result && !empty($jd_search_result['categoryAggregate']['thridCategory'])) {
//                $condition .= ' and cates = :cates';
//                $params[':cates'] = $jd_search_result['categoryAggregate']['thridCategory'][0]['id'];
//            }
            }
        }

        // 商品分类处理
        if (!empty($args['cate'])) {

            // 全部分类
            $category = m('shop')->getAllCategory(SUPERDESK_SHOPV2_REFRESH/*刷新标志*/);

//            socket_log(json_encode($category,JSON_UNESCAPED_UNICODE));

            // 请求过来的分类
            $catearr = array($args['cate']);

//            socket_log(json_encode($catearr,JSON_UNESCAPED_UNICODE));

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

            $catearr = array_unique($catearr);//array_unique() 函数移除数组中的重复的值，并返回结果数组。

//            socket_log(json_encode($catearr,JSON_UNESCAPED_UNICODE));

            if ($_switch_source == 'mysql') {

                $condition .= ' AND ( ';

                foreach ($catearr as $key => $value) {
                    if ($key == 0) {
                        $condition .= 'FIND_IN_SET(' . $value . ',cates)';
                    } else {
                        $condition .= ' || FIND_IN_SET(' . $value . ',cates)';
                    }
                }

                $condition .= ' <>0 )';

            } else if ($_switch_source == 'elasticsearch') {

                // 2019年5月15日 19:33:54 zjh cates 逗号分隔方式
                $catearr   = implode(',', $catearr);
                $condition .= ' and cates.keyword in ( ' . $catearr . ')';

                // 2019年5月15日 19:33:54 zjh 原处理 cates 作为 keyword
//                $catearr   = implode(',', $catearr);
//                $condition .= ' and cates in ( ' . $catearr . ')';

                // 2019年5月15日 19:33:54 zjh like 测试 不行
//                $condition_cate = array();
//                foreach ($catearr as $_index => $cate) {
//
//                    $condition_cate[$_index] = ' cates LIKE :cates' . $_index . ' ';
//
//                    $params[':cates' . $_index] = '%' . trim($cate) . '%';
//
//                }
//
//                $condition .= ' AND ( ' . implode(' OR ', $condition_cate) . ' )';

            }

            $_search_record['has_cate'] = 1;
            $_search_record['cate']     = $args['cate'];

        }


        // 暂时屏蔽查看级别与分组
//        $member = m('member')->getMember($_W['openid'],$_W['core_user']);
//        if (!empty($member)) {
//            $levelid = intval($member['level']);
//            $groupid = intval($member['groupid']);
//            $condition .= ' and ( ifnull(showlevels,\'\')=\'\' or FIND_IN_SET( ' . $levelid . ',showlevels)<>0 ) ';
//            $condition .= ' and ( ifnull(showgroups,\'\')=\'\' or FIND_IN_SET( ' . $groupid . ',showgroups)<>0 ) ';
//        } else {
//            $condition .= ' and ifnull(showlevels,\'\')=\'\' ';
//            $condition .= ' and ifnull(showgroups,\'\')=\'\' ';
//        }

        $total = '';


        $select_fields =
            " id,title,thumb,marketprice,productprice,minprice,maxprice,isdiscount,isdiscount_time,isdiscount_discounts,sales,salesreal,total,description, " .
            " bargain,jd_vop_sku,merchid,costprice,pcate,ccate,tcate,cates,pcates,ccates,tcates,displayorder ";

        //插入搜索记录表
        if (count($_search_record) > 0) {

            if (count($_search_record['filters']) > 0) {
                $_search_record['filters'] = implode(',', $_search_record['filters']);
            }

            if (!empty($args['order'])) {

                $_search_record['has_order'] = 1;
                $_search_record['order_by']  = $args['order'];
                if ($orderby) {
                    $_search_record['order_by'] .= ' ' . $orderby;
                }
            }

            $_search_record['openid']     = $_W['openid'];
            $_search_record['createtime'] = time();

//            socket_log(json_encode($_search_record));

            pdo_insert('superdesk_jd_vop_search', $_search_record);
        }

        if (!$random) {
            $sql =
                ' SELECT ' . $select_fields . // SQL_CALC_FOUND_ROWS
                ' FROM ' . tablename('superdesk_shop_goods') .
                ' WHERE ' . $condition .//' WHERE 1 ' . $condition .
                ' ORDER BY ' . $order . ' ' . $orderby .
                ' LIMIT ' . (($page - 1) * $pagesize) . ',' . $pagesize;

//            $merch_like_ids = pdo_fetchall(
//                ' SELECT merchid ' .
//                ' FROM ' . tablename('superdesk_shop_goods') .
//                ' WHERE ' . $condition .
//                ' GROUP BY merchid ',
//                $params);
//            $merch_like_ids = array_column($merch_like_ids,'merchid');
//
//            $jd_search_result['merch_like_list'] = pdo_fetchall(
//                ' SELECT id,merchname ' .
//                ' FROM ' . tablename('superdesk_shop_merch_user') .
//                ' WHERE id in ('.implode(',',$merch_like_ids).') '
//            );

            socket_log($sql);

            if ($_switch_source == 'mysql') {

                if ($showtotal) {

                    $total = pdo_fetchcolumn(
                        ' select count(id) ' .
                        ' from ' . tablename('superdesk_shop_goods') .
                        ' where ' . $condition . ' ',
                        $params
                    );
                }

                $list = pdo_fetchall($sql, $params);
//                $total = pdo_fetchcolumn('SELECT FOUND_ROWS()');

//                socket_log($total);
//                socket_log(json_encode($list));

                $list = $this->getGoodsMerch($list);

                //2019年5月24日 16:48:51 zjh 文礼 价套
                $list = $this->getGoodsCategoryEnterpriseDiscount($list);

                //print_r($list);exit();

                $list = set_medias($list, 'thumb');
                // $list = $this->_priceService->businessProcessingUpdateJdVopPriceForShopList($list);// 更新商品价格

                //判断京东商品是否有库存 zjh 添加于 2018年5月2日 18:17:08
                $list = $this->checkGoodsListJdStock($list);

                return array(
                    'list'             => $list,
                    'total'            => $total,
                    'can_search_field' => $jd_search_result,
                    'show_sql'         => $args['showSql'] ? $sql : '',
                );


            } else if ($_switch_source == 'elasticsearch') {


                include_once(IA_ROOT . '/addons/superdesk_shopv2_elasticsearch/model/goods/ES_shop_goodsModel.class.php');
                $_es_shop_goodsModel = new ES_shop_goodsModel();
                $_es_result          = $_es_shop_goodsModel->fetchAll($sql, $params);
                $list                = $_es_result['list'];
                $total               = $_es_result['total'];

                $list = $this->getGoodsMerch($list);

                //print_r($list);exit();

                //2019年5月24日 16:48:51 zjh 文礼 价套
                $list = $this->getGoodsCategoryEnterpriseDiscount($list);

                $list = set_medias($list, 'thumb');
                // $list = $this->_priceService->businessProcessingUpdateJdVopPriceForShopList($list);// 更新商品价格

                //判断京东商品是否有库存 zjh 添加于 2018年5月2日 18:17:08
                $list = $this->checkGoodsListJdStock($list);

//                socket_log(json_encode($list));

//                $test_update_ids = array();
//                foreach ($list as $____item____){
//                    $test_update_ids[] = $____item____['id'];
//                }
//                socket_log();

                return array(
                    'list'             => $list,
                    'total'            => $total,
                    'can_search_field' => $jd_search_result,
                    'show_sql'         => $args['showSql'] ? $sql : '',
                );

            }
        } else {
            $sql   =
                ' SELECT ' . $select_fields .
                ' FROM ' . tablename('superdesk_shop_goods') .
                ' WHERE ' . $condition .//' WHERE 1 ' . $condition .
                ' ORDER BY rand() ' .
                ' LIMIT ' . $pagesize;
            $total = $pagesize;

            $list = pdo_fetchall($sql, $params);
//                socket_log(json_encode($list));

            $list = $this->getGoodsMerch($list);

            //2019年5月24日 16:48:51 zjh 文礼 价套
            $list = $this->getGoodsCategoryEnterpriseDiscount($list);

            $list = set_medias($list, 'thumb');
            // $list = $this->_priceService->businessProcessingUpdateJdVopPriceForShopList($list);// 更新商品价格

            //判断京东商品是否有库存 zjh 添加于 2018年5月2日 18:17:08
            $list = $this->checkGoodsListJdStock($list);

            return array('list' => $list, 'total' => $total, 'can_search_field' => $jd_search_result, 'show_sql' => $condition);
        }

    }


    /**
     * 改了之前的直接select的做法
     * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=superdesk_shop_goods_get_total_ajax
     * https://wxm.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=superdesk_shop_goods_get_total_ajax
     * /addons/superdesk_jd_vop/inc/mobile/superdesk_shop_goods_get_total_ajax.inc.php
     *
     * @return array
     */
    public function getTotals()
    {
        global $_W;

        include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
        $_redis = new RedisUtil();

        $redis_key = 'superdesk_shop_goods_cache_getGoodsTotals:' . $_W['uniacid'];

        $sale = $_redis->hget($redis_key, 'sale');
        $sale = json_decode($sale, true);
        $sale = empty($sale['count']) ? 0 : $sale['count'];

        $out = $_redis->hget($redis_key, 'out');
        $out = json_decode($out, true);
        $out = empty($out['count']) ? 0 : $out['count'];

        $stock = $_redis->hget($redis_key, 'stock');
        $stock = json_decode($stock, true);
        $stock = empty($stock['count']) ? 0 : $stock['count'];

        $cycle = $_redis->hget($redis_key, 'cycle');
        $cycle = json_decode($cycle, true);
        $cycle = empty($cycle['count']) ? 0 : $cycle['count'];

        $check = $_redis->hget($redis_key, 'check');
        $check = json_decode($check, true);
        $check = empty($check['count']) ? 0 : $check['count'];

        return compact('sale', 'out', 'stock', 'cycle', 'check');

//        return array(
//            'sale'  => pdo_fetchcolumn(
//                ' select count(1) ' .
//                ' from ' . tablename('superdesk_shop_goods') .
//                ' where status=1 and checked=0 and deleted=0 and total>0 and uniacid=:uniacid',
//                array(':uniacid' => $_W['uniacid'])
//            ),
//            'out'   => pdo_fetchcolumn(
//                ' select count(1) ' .
//                ' from ' . tablename('superdesk_shop_goods') .
//                ' where status=1 and deleted=0 and total=0 and uniacid=:uniacid',
//                array(':uniacid' => $_W['uniacid'])
//            ),
//            'stock' => pdo_fetchcolumn(
//                ' select count(1) ' .
//                ' from ' . tablename('superdesk_shop_goods') .
//                ' where (status=0 or checked=1) and deleted=0 and uniacid=:uniacid',
//                array(':uniacid' => $_W['uniacid'])
//            ),
//            'cycle' => pdo_fetchcolumn(
//                ' select count(1) ' .
//                ' from ' . tablename('superdesk_shop_goods') .
//                ' where deleted=1 and uniacid=:uniacid',
//                array(':uniacid' => $_W['uniacid'])
//            ),
//            'check' => pdo_fetchcolumn(
//                ' select count(1) ' .
//                ' from ' . tablename('superdesk_shop_goods') .
//                ' where deleted=0 and checked = 1 and uniacid=:uniacid',
//                array(':uniacid' => $_W['uniacid'])
//            )
//        );
    }

    public function getComments($goodsid = '0', $args = array())
    {
        global $_W;

        $page     = ((!empty($args['page']) ? intval($args['page']) : 1));
        $pagesize = ((!empty($args['pagesize']) ? intval($args['pagesize']) : 10));

        $condition =
            ' AND uniacid = :uniacid ' .
            ' AND goodsid = :goodsid ' .
            ' AND deleted=0';

        $params = array(
            ':uniacid' => $_W['uniacid'],
            ':goodsid' => $goodsid,
        );

        $sql =
            ' SELECT id,nickname,headimgurl,content,images ' .
            ' FROM ' . tablename('superdesk_shop_goods_comment') .// TODO 标志 楼宇之窗 openid shop_goods_comment 不处理
            ' where 1 ' . $condition .
            ' ORDER BY createtime desc LIMIT ' . (($page - 1) * $pagesize) . ',' . $pagesize;

        $list = pdo_fetchall($sql, $params);

        foreach ($list as &$row) {
            $row['images'] = set_medias(unserialize($row['images']));
        }

        unset($row);

        return $list;
    }

    public function isFavorite($id = '')
    {
        global $_W;

        $count = pdo_fetchcolumn(
            ' select count(*) ' .
            ' from ' . tablename('superdesk_shop_member_favorite') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_favorite 已处理
            ' where goodsid=:goodsid ' .
            '       and deleted=0 ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':goodsid'   => $id,
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user'],
            )
        );
        return 0 < $count;
    }

    public function addHistory($goodsid = 0)
    {
        global $_W;

        pdo_query(
            ' update ' . tablename('superdesk_shop_goods') .
            ' set viewcount = viewcount+1 ' .
            ' where id=:id and uniacid=\'' . $_W['uniacid'] . '\' ',
            array(':id' => $goodsid)
        );

        $history = pdo_fetch(
            ' select id,times ' .
            ' from ' . tablename('superdesk_shop_member_history') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_history 已处理
            ' where goodsid=:goodsid ' .
            '       and uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            ' limit 1',
            array(
                ':goodsid'   => $goodsid,
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user'],
            )
        );

        if (empty($history)) {

            $history = array(
                'uniacid'    => $_W['uniacid'],
                'openid'     => $_W['openid'],
                'core_user'  => $_W['core_user'],
                'goodsid'    => $goodsid,
                'deleted'    => 0,
                'createtime' => time(),
                'times'      => 1,
            );

            pdo_insert('superdesk_shop_member_history', $history); // TODO 标志 楼宇之窗 openid superdesk_shop_member_history 已处理
            return;
        }

        pdo_update(
            'superdesk_shop_member_history', // TODO 标志 楼宇之窗 openid superdesk_shop_member_history 不处理
            array(
                'deleted' => 0,
                'times'   => $history['times'] + 1,
            ),
            array(
                'id' => $history['id'],
            )
        );
    }

    public function getCartCount()
    {
        global $_W;
        global $_GPC;

        $count = pdo_fetchcolumn(
            ' select ' .
            '       sum(total) ' .
            ' from ' . tablename('superdesk_shop_member_cart') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_cart 已处理
            ' where ' .
            '       uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and deleted=0 ' .
            ' limit 1',
            array(
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user'],

            )
        );

        return $count;
    }

    public function getSpecThumb($specs)
    {
        global $_W;

        $thumb     = '';
        $cartspecs = explode('_', $specs);
        $specid    = $cartspecs[0];

        if (!empty($specid)) {

            $spec = pdo_fetch(
                ' select thumb ' .
                ' from ' . tablename('superdesk_shop_goods_spec_item') .
                ' where id=:id ' .
                '       and uniacid=:uniacid ' .
                ' limit 1 ',
                array(
                    ':id'      => $specid,
                    ':uniacid' => $_W['uniacid'],
                )
            );

            if (!empty($spec)) {
                if (!empty($spec['thumb'])) {
                    $thumb = $spec['thumb'];
                }
            }
        }
        return $thumb;
    }

    public function getOptionThumb($goodsid = 0, $optionid = 0)
    {
        global $_W;

        $thumb  = '';
        $option = $this->getOption($goodsid, $optionid);

        if (!empty($option)) {
            $specs = $option['specs'];
            $thumb = $this->getSpecThumb($specs);
        }

        return $thumb;
    }

    public function getAllMinPrice($goods)
    {
        global $_W;

        if (is_array($goods)) {

            $level  = m('member')->getLevel($_W['openid'], $_W['core_user']);
            $member = m('member')->getMember($_W['openid'], $_W['core_user']);

            $levelid = $member['level'];

            foreach ($goods as &$value) {

                $minprice = $value['minprice'];
                $maxprice = $value['maxprice'];

                if ($value['isdiscount'] && (time() <= $value['isdiscount_time'])) {

                    $value['oldmaxprice'] = $maxprice;
                    $isdiscount_discounts = json_decode($value['isdiscount_discounts'], true);
                    $prices               = array();

                    if (!isset($isdiscount_discounts['type']) || empty($isdiscount_discounts['type'])) {
                        $prices_array = m('order')->getGoodsDiscountPrice($value, $level, 1);
                        $prices[]     = $prices_array['price'];
                    } else {
                        $goods_discounts = m('order')->getGoodsDiscounts($value, $isdiscount_discounts, $levelid);
                        $prices          = $goods_discounts['prices'];
                    }

                    $minprice = min($prices);
                    $maxprice = max($prices);
                }

                $value['minprice'] = $minprice;
                $value['maxprice'] = $maxprice;
            }

            unset($value);

        } else {

            $goods = array();

        }

        return $goods;
    }

    public function getOneMinPrice($goods)
    {
        $goods = array($goods);
        $res   = $this->getAllMinPrice($goods);
        return $res[0];
    }

    public function getMemberPrice($goods, $level)
    {
        if (!empty($goods['isnodiscount'])) {
            return;
        }
        $discounts = json_decode($goods['discounts'], true);
        if (is_array($discounts)) {
            $key = ((!empty($level['id']) ? 'level' . $level['id'] : 'default'));
            if (!isset($discounts['type']) || empty($discounts['type'])) {
                $memberprice = $goods['minprice'];
                if (!empty($discounts[$key])) {
                    $dd = floatval($discounts[$key]);
                    if ((0 < $dd) && ($dd < 10)) {
                        $memberprice = round(($dd / 10) * $goods['minprice'], 2);
                    }
                } else {
                    $dd = floatval($discounts[$key . '_pay']);
                    $md = floatval($level['discount']);
                    if (!empty($dd)) {
                        $memberprice = round($dd, 2);
                    } else if ((0 < $md) && ($md < 10)) {
                        $memberprice = round(($md / 10) * $goods['minprice'], 2);
                    }
                }
                return $memberprice;
            }
            $options     = m('goods')->getOptions($goods);
            $marketprice = array();
            foreach ($options as $option) {
                $discount = trim($discounts[$key]['option' . $option['id']]);
                if ($discount == '') {
                    $discount = $level['discount'];
                }
                $optionprice   = m('order')->getFormartDiscountPrice($discount, $option['marketprice']);
                $marketprice[] = $optionprice;
            }
            $minprice    = min($marketprice);
            $maxprice    = max($marketprice);
            $memberprice = array('minprice' => (double)$minprice, 'maxprice' => (double)$maxprice);
            if ($memberprice['minprice'] < $memberprice['maxprice']) {
                $memberprice = $memberprice['minprice'] . '~' . $memberprice['maxprice'];
            } else {
                $memberprice = $memberprice['minprice'];
            }
            return $memberprice;
        }
    }

    public function getOptions($goods)
    {
        global $_W;

        $id      = $goods['id'];
        $specs   = false;
        $options = false;
        if (!empty($goods) && $goods['hasoption']) {

            $specs = pdo_fetchall(
                ' select * ' .
                ' from ' . tablename('superdesk_shop_goods_spec') .
                ' where goodsid=:goodsid ' .
                '   and uniacid=:uniacid ' .
                ' order by displayorder asc',
                array(
                    ':goodsid' => $id,
                    ':uniacid' => $_W['uniacid'],
                )
            );

            foreach ($specs as &$spec) {
                $spec['items'] = pdo_fetchall(
                    ' select * ' .
                    ' from ' . tablename('superdesk_shop_goods_spec_item') .
                    ' where specid=:specid ' .
                    ' order by displayorder asc',
                    array(
                        ':specid' => $spec['id'],
                    )
                );
            }

            unset($spec);

            $options = pdo_fetchall(
                ' select * ' .
                ' from ' . tablename('superdesk_shop_goods_option') .
                ' where goodsid=:goodsid ' .
                '       and uniacid=:uniacid ' .
                ' order by displayorder asc',
                array(
                    ':goodsid' => $id,
                    ':uniacid' => $_W['uniacid'],
                )
            );
        }
        return $options;
    }

    public function visit($goods = array(), $member = array())
    {
        global $_W;
        if (empty($goods)) {
            return 1;
        }
        if (empty($member)) {
            $member = m('member')->getMember($_W['openid'], $_W['core_user']);
        }

        $showlevels = (($goods['showlevels'] != '' ? explode(',', $goods['showlevels']) : array()));
        $showgroups = (($goods['showgroups'] != '' ? explode(',', $goods['showgroups']) : array()));
        $showgoods  = 0;

        if (!empty($member)) {

            if ((!empty($showlevels) && in_array($member['level'], $showlevels))
                || (!empty($showgroups) && in_array($member['groupid'], $showgroups))
                || (empty($showlevels) && empty($showgroups))
            ) {
                $showgoods = 1;
            }
        } else if (empty($showlevels) && empty($showgroups)) {
            $showgoods = 1;
        }

        return $showgoods;
    }

    public function canBuyAgain($goods)
    {
        global $_W;
        $condition = '';
        $id        = $goods['id'];

        if (isset($goods['goodsid'])) {
            $id = $goods['goodsid'];
        }

        if (empty($goods['buyagain_islong'])) {
            $condition = ' AND canbuyagain = 1';
        }

        $order_goods = pdo_fetchall(
            'SELECT id,orderid FROM ' . tablename('superdesk_shop_order_goods') . // TODO 标志 楼宇之窗 openid shop_order_goods 已处理
            ' WHERE uniacid=:uniaicd AND openid=:openid AND core_user=:core_user AND goodsid=:goodsid ' . $condition,
            array(
                ':uniaicd'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user'],
                'goodsid'    => $id,
            ),
            'orderid'
        );

        if (empty($order_goods)) {
            return false;
        }

        $order = pdo_fetchcolumn(
            ' SELECT COUNT(*) FROM ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' WHERE uniacid=:uniaicd AND status>=:status AND id IN (' . implode(',', array_keys($order_goods)) . ')',
            array(
                ':uniaicd' => $_W['uniacid'],
                ':status'  => (empty($goods['buyagain_condition']) ? '1' : '3'),
            )
        );

        return !empty($order);
    }

    public function useBuyAgain($orderid)
    {
        global $_W;
        $order_goods = pdo_fetchall(
            ' SELECT id,goodsid ' .
            ' FROM ' . tablename('superdesk_shop_order_goods') .// TODO 标志 楼宇之窗 openid shop_order_goods 已处理
            ' WHERE uniacid=:uniaicd ' .
            '       AND openid=:openid ' .
            '       AND core_user=:core_user ' .
            '       AND canbuyagain = 1 ' .
            '       AND orderid <> :orderid',
            array(
                ':uniaicd'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user'],
                'orderid'    => $orderid,
            ),
            'goodsid'
        );

        if (empty($order_goods)) {
            return false;
        }

        pdo_query(
            'UPDATE ' . tablename('superdesk_shop_order_goods') . // TODO 标志 楼宇之窗 openid shop_order_goods 不处理
            ' SET canbuyagain=0 ' .
            ' WHERE ' .
            '		uniacid=:uniacid ' .
            '		AND goodsid IN (' . implode(',', array_keys($order_goods)) . ')',
            array(
                ':uniacid' => $_W['uniacid'],
            )
        );
    }

    public function getTaskGoods($openid, $goodsid, $optionid = 0, $total = 1)
    {
        global $_W;

        $is_task_goods        = 0;
        $is_task_goods_option = 0;

        $data                         = array();
        $data['is_task_goods']        = $is_task_goods;
        $data['is_task_goods_option'] = $is_task_goods_option;

        return $data;
    }


    /**
     * @param $list 商品列表
     *
     * @return mixed
     * 检查京东商品是否有无货的,应用于列表页
     * zjh 创建于 2018年5月2日 18:02:42
     */
    public function checkGoodsListJdStock($list)
    {

        global $_W;

        $sku_arr = [];
        if (count($list) != 0) {
            foreach ($list as $k => $v) {

                if ($v['merchid'] == SUPERDESK_SHOPV2_JD_VOP_MERCHID) {
                    $sku_arr[] = $v['jd_vop_sku'];
                }
            }
        }

        if (!empty($sku_arr)) {

            //获取用户默认收货地址
            $params = array(
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user'],
            );

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

            if (empty($address)) {
                foreach ($list as &$v) {
                    if ($v['merchid'] == SUPERDESK_SHOPV2_JD_VOP_MERCHID) {
                        $v['stock'] = -2;
                    }
                }
                unset($v);
                return $list;
            }

            $area = $address['jd_vop_province_code'] . '_' . $address['jd_vop_city_code'] . '_' . $address['jd_vop_county_code'];

            $sku_str   = implode(',', $sku_arr);
            $jd_result = $this->_stockService->getAllStockById($sku_str, $area);

            if ($jd_result) {
                foreach ($jd_result as $sku => $rs) {
                    foreach ($list as $k => &$v) {
                        if ($sku == $v['jd_vop_sku'] && $rs['state'] == 34) {
                            $v['stock'] = -2;
                        }
                    }
                    unset($v);
                }

            }
        }

        return $list;
    }


    /**
     * 检查京东商品是否有无货的,应用于详情页
     * zjh 创建于 2018年5月2日 18:02:42
     *
     * @param     $goods 商品详情
     * @param int $count
     *
     * @return bool
     */
    public function checkGoodsJdStock($goods, $count = 1)
    {

        global $_W;

        if ($goods['merchid'] == SUPERDESK_SHOPV2_JD_VOP_MERCHID) {

            //获取用户默认收货地址
            $params = array(
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user'],
            );

            socket_log('core model -> goods -> checkGoodsJdStock' . json_encode($params));

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

            if (empty($address)) {
                return false;
            }

            $area = $address['jd_vop_province_code'] . '_' . $address['jd_vop_city_code'] . '_' . $address['jd_vop_county_code'];

            $jd_goods = array(
                array(
                    'skuId' => $goods['jd_vop_sku'],
                    'num'   => $count,
                ),
            );

            $jd_result = $this->_stockService->getNewStockById(json_encode($jd_goods), $area);

            $socket_data = array(
                'address'   => $area,
                'jd_result' => $jd_result,
            );

            socket_log(json_encode($socket_data));

            if ($jd_result[$goods['jd_vop_sku']]['stockStateId'] == 34) {
                return false;
            }
        }

        return true;
    }

    /**
     * 获取商品对应商户信息
     */
    public function getGoodsMerch($list){

        $merchids = array_column($list,'merchid');

        if(empty($merchids)){
            return $list;
        }

        $merchids = array_unique($merchids);

        $merchidStr = implode(',',$merchids);

        $data = pdo_fetchall(
            ' SELECT m.id,m.storename,mg.groupname FROM ' . tablename('superdesk_shop_merch_user') . ' as m ' .
            ' LEFT JOIN ' . tablename('superdesk_shop_merch_group') . ' as mg on mg.id = m.groupid' .
            ' WHERE m.id in (' . $merchidStr . ')',
            array(),
            'id'
        );

        foreach($list as $k => &$v){
            $v['storename'] = $data[$v['merchid']]['storename'];
            $v['groupname'] = $data[$v['merchid']]['groupname'];
        }
        unset($v);

        return $list;

    }


    /**
     * 获取商品对应折扣信息,价套,由当前用户的企业和当前商品的第三级分类决定
     * 商品列表
     * 由于规格也可能需要变更金额.所以额外加一个tcate参数. 即有tcate的时候,是在处理规格而不是处理商品
     */
    public function getGoodsCategoryEnterpriseDiscount($list, $tcate = 0){
        global $_W;

        //没登录
        if(empty($_W['core_enterprise'])){
            return $list;
        }

        //商品列表有问题
        if(!$list){
            return $list;
        }

        //该企业没有分类折扣
//        if(empty($data)){
//            return $list;
//        }


        foreach($list as $k => &$v){
            if(empty($v['tcate']) && $tcate > 0){
                $v['tcate'] = $tcate;
            }

            $data = $this->getCategoryEnterpriseDiscountRedis($_W['core_enterprise'], $v['merchid']);

            //该企业既没有针对商户的分类折扣.也没有通用的分类折扣
            if(empty($data)){
                continue;
            }

            $v['haveCategoryEnterpriseDiscount'] = 0;
            //类型(1:折扣,2:成本价) luoxt 20190916
            if($data['type'] == 2){

                $v['minprice_old'] = $v['minprice'];
                $v['minprice'] = $v['costprice'];
                $v['maxprice_old'] = $v['maxprice'];
                $v['maxprice'] = $v['costprice'];
                $v['marketprice_old'] = $v['marketprice'];
                $v['marketprice'] = $v['costprice'];
                $v['haveCategoryEnterpriseDiscount'] = 2;

            }else{

                if(isset($data['discount']) && $data['discount'] > 0){
                    if(isset($v['minprice'])){
                        $v['minprice_old'] = $v['minprice'];
                        $v['minprice'] = sprintf('%.2f',round($v['minprice'] * $data['discount'], 1));
                    }
                    if(isset($v['maxprice'])){
                        $v['maxprice_old'] = $v['maxprice'];
                        $v['maxprice'] = sprintf('%.2f',round($v['maxprice'] * $data['discount'], 1));
                    }
                    if(isset($v['marketprice'])){
                        $v['marketprice_old'] = $v['marketprice'];
                        $v['marketprice'] = sprintf('%.2f',round($v['marketprice'] * $data['discount'], 1));

                        //$v['discount'] = $data['discount'];
                    }
                    $v['haveCategoryEnterpriseDiscount'] = 1;
                }

            }
        }
        unset($v);

        return $list;

    }

    /**
     * 获取商品对应折扣信息,价套,由当前用户的企业和当前商品的第三级分类决定
     * 单个商品
     */
    public function getGoodsCategoryEnterpriseDiscountOne($item, $tcate = 0){
        global $_W,$_GPC;

        //没登录
        if(empty($_W['core_enterprise'])){
            return $item;
        }

        //商品信息有问题
        if(!$item || empty($item)){
            return $item;
        }

        //由于规格也可能需要变更金额.所以额外加一个tcate参数. 即有tcate的时候,是在处理规格而不是处理商品
        if(!isset($item['tcate']) && $tcate > 0){
            $item['tcate'] = $tcate;
        }

        //直接获取企业和商户的价套
        $data = $this->getCategoryEnterpriseDiscountRedis($_W['core_enterprise'],$item['merchid']);


        //该企业没有分类折扣
        if(empty($data)){
            return $item;
        }

        //计算商品价格
        $item['haveCategoryEnterpriseDiscount'] = 0;
        if($data['type'] == 2){
            $item['minprice_old'] = $item['minprice'];
            $item['minprice'] = $item['costprice'];
            $item['maxprice_old'] = $item['maxprice'];
            $item['maxprice'] = $item['costprice'];
            $item['marketprice_old'] = $item['marketprice'];
            $item['marketprice'] = $item['costprice'];
            $item['haveCategoryEnterpriseDiscount'] = $data;

        }else{

            if(isset($data['discount']) && $data['discount'] > 0){
                if(isset($item['minprice'])){
                    $item['minprice_old'] = $item['minprice'];
                    $item['minprice'] = sprintf('%.2f',round($item['minprice'] * $data['discount'], 1));
                }
                if(isset($item['maxprice'])){
                    $item['maxprice_old'] = $item['maxprice'];
                    $item['maxprice'] = sprintf('%.2f',round($item['maxprice'] * $data['discount'], 1));
                }
                if(isset($item['marketprice'])){
                    $item['marketprice_old'] = $item['marketprice'];
                    $item['marketprice'] = sprintf('%.2f',round($item['marketprice'] * $data['discount'], 1));
                }
                $item['haveCategoryEnterpriseDiscount'] = $data;
            }

        }
        return $item;

    }

    /**
     * 设置分类企业折扣redis
     */
    public function setCategoryEnterpriseDiscountRedis($core_enterprise, $merchid = 0){
        //加载Redis
        include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
        $_redis = new RedisUtil();

        //查询DB
        $filter = array(
            ':core_enterprise' => $core_enterprise,
            ':merchid'          => intval($merchid)
        );
        $category_enterprise_discount = pdo_fetch(
            ' SELECT * FROM ' . tablename('superdesk_shop_category_enterprise_discount') .
            ' WHERE core_enterprise = :core_enterprise ' .
            '   AND merchid = :merchid ', $filter);

        //放入缓存
        $redisKey = 'enterprise_discount_cache:' . $core_enterprise . '_' . $merchid;
        $_redis->set($redisKey, json_encode($category_enterprise_discount));

        return $category_enterprise_discount;
    }

    /**
     * 获取分类企业折扣redis
     * 假如没有tcate 返回该企业所有分类折扣
     * 假如有tcate   返回该企业该分类的折扣
     *
     * 先检查是否有 满足(指定企业,指定商户)的分类折扣
     * 没有就重新调用自己 检查是否有 满足(指定企业)的分类折扣
     */
    public function getCategoryEnterpriseDiscountRedis($core_enterprise, $merchid = 0, $tcate = 0){

        include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
        $_redis = new RedisUtil();

        //$_redis->del($redisKey);
        $redisKey = 'enterprise_discount_cache:' . $core_enterprise . '_' . $merchid;
        if(!$_redis->isExists($redisKey)){
            $category_enterprise_discount = $this->setCategoryEnterpriseDiscountRedis($core_enterprise, $merchid);
        }else{
            $category_enterprise_discount = $_redis->get($redisKey);

            $category_enterprise_discount = json_decode($category_enterprise_discount,true);
        }

        $result = $category_enterprise_discount;

        return $result;
    }

    /**
     * 删除分类企业折扣redis
     */
    public function delCategoryEnterpriseDiscountRedis($core_enterprise, $merchid = 0){

        include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
        $_redis = new RedisUtil();

        $redisKey = 'enterprise_discount_cache:' . $core_enterprise . '_' . $merchid;
        $_redis->del($redisKey);

        return true;
    }
}

