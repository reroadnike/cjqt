<?php

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/accessControl.php";

//error_reporting(0);

if (!(defined("IN_IA"))) {
    exit("Access Denied");
}

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/page_mobile.php";
include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/SearchService.class.php');

class Index_SuperdeskShopV2Page extends PcMobilePage
{

    private $_jd_searchService;

    public function __construct()
    {
        $this->_jd_searchService = new SearchService();
        parent::__construct();
    }

    /**
     * 商城首页
     * http://localhost/app/index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=pc.api
     */
    public function main()
    {
        global $_W;
        global $_GPC;

        //新品上架
//        $new_list = $this->goods_list(array('pagesize' => 20, 'isnew' => 1))['list'];

        //热卖商品
        //$hot_list = $this->goods_list(array('pagesize' => 5, 'ishot' => 1))['list'];

        //疯狂抢购
        //$time_list = $this->goods_list(array('pagesize' => 5, 'istime' => 1))['list'];

        //猜您喜欢
        //$discount_list = $this->goods_list(array('pagesize' => 5, 'isdiscount' => 1))['list'];

        //热评商品
        //$recommand_list = $this->goods_list(array('pagesize' => 5, 'isrecommand' => 1))['list'];

        //商城公告
//        $notice_list = pdo_fetchall(
//            ' SELECT * ' .
//            ' FROM ' . tablename('superdesk_shop_notice') .
//            ' WHERE uniacid=:uniacid ' .
//            '       AND status=1  ' .
//            ' ORDER BY displayorder DESC,id DESC limit 4',
//            array(':uniacid' => $_W['uniacid']));

        $friend_link = get_links();

        show_json(1, compact('slides', 'recommends', 'friend_link'));
    }

    /**
     * 获取分类+商品
     * http://localhost/app/index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=pc.api.indexGoods
     */
    public function indexGoods()
    {
        global $_GPC;
        global $_W;
        $psize = $_GPC['psize'] ? $_GPC['psize'] : 5;
        $page  = $_GPC['page'] ? $_GPC['page'] : 1;

        $cache_key = $_W['uniacid'] . '_index_floor_category_goods';

        $parent_category = m('cache')->getArray($cache_key);
        if (SUPERDESK_SHOPV2_REFRESH == true || empty($parent_category)) {
            $parent_category = m('shop')->getRefreshFloorCategory($psize, $page);
        }

        foreach ($parent_category as $k => &$v) {
            $v['hotgoods'] = $this->goods_list(
                array(
                    'cate'     => $v['children'][0]['id'],
                    'pagesize' => 8
                )
            )['list'];
        }

        //这是原来的写法,获取所有分类逐个查找商品然后放入分类中
//        $categories = m('shop')->getCategory();
//
//        foreach($categories['parent'] as $k => &$v){
//            $v['advimg'] = tomedia($v['advimg']);
//            $v['hotgoods'] = $this->goods_list(array('cate' => $v['id'], 'pagesize' => 2, 'ishot' => 1))['list'];
//            $v['recommendgoods'] = $this->goods_list(array('cate' => $v['id'], 'pagesize' => 1, 'isrecommand' => 1))['list'];
//            $v['othergoods'] = $this->goods_list(array('cate' => $v['id'], 'pagesize' => 6))['list'];
//        }

        show_json(1, $parent_category);
    }

    /**
     * 获取所有分类
     */
    public function getCategoryList()
    {
        $data = index_cate();

        show_json(1, $data);
    }

    /**
     * 热门搜索
     */
    public function hotKeyword()
    {
        global $_W;

        $keyword = $_W['shopset']['shop']['keywords'];

        $keyword = explode(',', $keyword);

        show_json(1, $keyword);
    }

    /**
     * 获取广告
     */
    public function getAdv()
    {

        global $_W;
        global $_GPC;

        // init
        $title = $_GPC['title'];
        $adv   = array();

        $cache_key = $_W['uniacid'] . '_index_pc_adv';


//        $adv = m('cache')->getArray($cache_key);

        $adv = $this->loadAdvFromDb($cache_key, $title);

        if (empty($adv)) { // 没缓存
            $adv = $this->loadAdvFromDb($cache_key, $title);
        } elseif (SUPERDESK_SHOPV2_REFRESH == true) { // 强刷
            $adv = $this->loadAdvFromDb($cache_key, $title);
        }


        if (empty($adv)) {
            show_json(0, '没有数据');
        }

        show_json(1, $adv);
    }

    private function loadAdvFromDb($cache_key, $title)
    {
        global $_W;
        global $_GPC;

        $adv = pdo_fetchall(
            ' SELECT * ' .
            'FROM ' . tablename('superdesk_shop_pc_adv') .
            ' WHERE ' .
            '   uniacid = :uniacid ' .
            '   AND title = :title ' .
            '   AND enabled = 1',
            array(
                ':title'   => $title,
                ':uniacid' => $_W['uniacid']
            )
        );

        socket_log('广告' . json_encode($adv));

        $adv = set_medias($adv, 'src');

        m('cache')->set($cache_key, $adv);

        return $adv;
    }

    /**
     * 获取轮播图
     */
    public function getSlide()
    {
        global $_W;
        global $_GPC;

        $cache_key = $_W['uniacid'] . '_index_pc_slide';

        if (SUPERDESK_SHOPV2_REFRESH == true) {
            $slides = array();
        } else {
            $slides = m('cache')->getArray($cache_key);
        }

        if (empty($slides)) {
            $slides = pdo_fetchall(
                ' select id,advname,link,thumb,backcolor ' .
                ' from ' . tablename('superdesk_shop_pc_slide') .
                ' where uniacid=:uniacid ' .
                '       and enabled=1 ' .
                '       AND type=0 ' .
                ' order by displayorder desc ' .
                ' limit 5',
                array(
                    ':uniacid' => $_W['uniacid']
                )
            );

            $slides = set_medias($slides, 'thumb');

            m('cache')->set($cache_key, $slides);

            if (empty($slides)) {
                show_json(0, '没有数据');
            }
        }

        show_json(1, $slides);
    }

    /**
     * 获取顶部菜单栏
     */
    public function getHeadMenu()
    {
        global $_W;
        global $_GPC;

        $cache_key = $_W['uniacid'] . '_index_pc_menu';

        if (SUPERDESK_SHOPV2_REFRESH == true) {
            $menu = array();
        } else {
            $menu = m('cache')->getArray($cache_key);
        }

        if (empty($menu)) {
            $menu = pdo_fetchall(
                'SELECT * ' .
                ' FROM ' . tablename('superdesk_shop_pc_menu') .
                ' WHERE ' .
                '       uniacid=:uniacid ' .
                '       AND enabled=1',
                array(
                    ':uniacid' => $_W['uniacid']
                )
            );
            m('cache')->set($cache_key, $menu);

            if (empty($menu)) {
                show_json(0, '没有数据');
            }
        }

        show_json(1, $menu);
    }

    /**
     * 底部信息
     */
    public function footerInfo()
    {
        $set  = get_set();

        $data = [
            'tel'       => $set['tel'],
            'kefu'      => $set['kefu'],
            'follow'    => tomedia($set['pc_follow']),
            'copyright' => htmlspecialchars_decode($set['pc_copyright'])
        ];

        show_json(1, $data);
    }

    /**
     * 获取问答
     */
    public function getQa()
    {
        $data = get_qa();

        show_json(1, $data);
    }

    public function test()
    {
        $data = $this->_jd_searchService->searchSearch('联想 电脑');

        show_json(1, $data);
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

        $goods = m('goods')->getList($args, 0);

        //$goods = m('goods')->checkGoodsListJdStock($goods['list']);

        return $goods;
    }

    /**
     * 给秀芝的
     * 读取数据 首页 -> 商品推荐
     */
    public function recommendGoods()
    {
        global $_W;

        $uniacid = $_W['uniacid'];
        $goodids = implode(',', $_W['shopset']['shop']['indexrecommands']);

        if (!empty($goodids)) {

            /******************************************************* redis *********************************************************/
            include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
            $_redis = new RedisUtil();
            /******************************************************* redis *********************************************************/

            $_key = 'superdesk_shopv2_plugin_pc_mobile_api_index_recommend_goods:' . $_W['uniacid'];

            if ($_redis->isExists($_key)) {

                $indexrecommands = json_decode($_redis->get($_key), true);

            } else {

                $select_fields =
                    " id,title,thumb,marketprice,productprice,minprice,maxprice,isdiscount,isdiscount_time,isdiscount_discounts,sales,total,description,bargain,jd_vop_sku ";

                $indexrecommands = pdo_fetchall(
                    ' select ' .
                    $select_fields .
                    ' from ' . tablename('superdesk_shop_goods') .
                    ' where id in( ' . $goodids . ' ) ' .
                    '       and uniacid=:uniacid ' .
                    '       and status=1 ' .
                    ' order by instr(\'' . $goodids . '\',id),displayorder desc',
                    array(':uniacid' => $uniacid)
                );

                $_redis->set($_key, json_encode($indexrecommands));
            }


//            include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/PriceService.class.php');
//            $_priceService = new PriceService();
//
//            $indexrecommands = $_priceService->businessProcessingUpdateJdVopPriceForShopList($indexrecommands);// 更新商品价格

            show_json(1, $indexrecommands);
        }

        show_json(0, '没有数据!请联系超级前台管理员');
    }

    public function uploadFile()
    {
        global $_W, $_GPC;

        load()->func('file');

        if (!empty($_FILES['file']['name'])) {

            if ($_FILES['file']['error'] != 0) {
                show_json(0, '上传失败，请重试！');
            }

            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            $ext = strtolower($ext);

            $file = file_upload($_FILES['file']);
            if (is_error($file)) {
                show_json(0, $file['message']);
            }

            $pathname = $file['path'];
            $fullname = ATTACHMENT_ROOT . '/' . $pathname;

            $needZoom = true;  //是否缩放
            $width    = 130;   //缩放后的宽度

            if ($needZoom && $width > 0) {
                $thumbnail = file_image_thumb($fullname, '', $width);
                @unlink($fullname);
                if (is_error($thumbnail)) {
                    show_json(0, $thumbnail['message']);
                } else {
                    $filename = pathinfo($thumbnail, PATHINFO_BASENAME);
                    $pathname = $thumbnail;
                    $fullname = ATTACHMENT_ROOT . '/' . $pathname;
                }
            }

            $info           = array(
                'name'       => $_FILES['file']['name'],
                'ext'        => $ext,
                'filename'   => $pathname,
                'attachment' => $pathname,
                'url'        => tomedia($pathname),
                'is_image'   => 1,
                'filesize'   => filesize($fullname),
            );

            $size           = getimagesize($fullname);
            $info['width']  = $size[0];
            $info['height'] = $size[1];

            pdo_insert('core_attachment', array(
                'uniacid'    => $_W['uniacid'],
                'uid'        => $_W['uid'],
                'filename'   => $_FILES['file']['name'],
                'attachment' => $pathname,
                'type'       => 1,
                'createtime' => TIMESTAMP,
            ));
            show_json(1, $info);
        } else {
            show_json(0, '请选择要上传的图片！');
        }
    }
}
