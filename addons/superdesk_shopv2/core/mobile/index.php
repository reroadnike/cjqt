<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/PriceService.class.php');

class Index_SuperdeskShopV2Page extends MobilePage
{

    private $_priceService;

    public function __construct()
    {
        parent::__construct();

        $this->_priceService = new PriceService();
    }

    /**
     * 首页
     */
    public function main()
    {
        global $_W;
        global $_GPC;


        $this->diypage('home');

        $mid = intval($_GPC['mid']);

        $index_cache = $this->getpage();

        if (!empty($mid)) {

            $index_cache = preg_replace_callback('/href=[\\\'"]?([^\\\'" ]+).*?[\\\'"]/', function ($matches) use ($mid) {

                $preg = $matches[1];

                if (strexists($preg, 'mid=')) {
                    return 'href=\'' . $preg . '\'';
                }

                if (!strexists($preg, 'javascript')) {
                    $preg = preg_replace('/(&|\\?)mid=[\\d+]/', '', $preg);

                    if (strexists($preg, '?')) {
                        $newpreg = $preg . '&mid=' . $mid;
                    } else {
                        $newpreg = $preg . '?mid=' . $mid;
                    }

                    return 'href=\'' . $newpreg . '\'';
                }

            }, $index_cache);
        }

        $shop_data = m('common')->getSysset('shop');

        include $this->template();
    }

    public function get_recommand()
    {
        global $_W;
        global $_GPC;

        $args = array(
            'page'        => $_GPC['page'],
            'pagesize'    => 6,
            'isrecommand' => 1,
            'order'       => 'displayorder desc,createtime desc',
            'by'          => ''
        );

        $recommand = m('goods')->getList($args);

        show_json(1, array(
            'list'     => $recommand['list'],
            'pagesize' => $args['pagesize'],
            'total'    => $recommand['total'],
            'page'     => intval($_GPC['page'])
        ));
    }

    private function getcache()
    {
        global $_W;
        global $_GPC;
        return m('common')->createStaticFile(mobileUrl('getpage', NULL, true));
    }

    public function getpage()
    {
        global $_W;
        global $_GPC;

        $defaults = array(
            'switch' => array('text' => '切换项目', 'visible' => 1),
            'adv'    => array('text' => '幻灯片', 'visible' => 1),
            'search' => array('text' => '搜索栏', 'visible' => 1),
            'nav'    => array('text' => '导航栏', 'visible' => 1),
            'notice' => array('text' => '公告栏', 'visible' => 1),
            'cube'   => array('text' => '魔方栏', 'visible' => 1),
            'banner' => array('text' => '广告栏', 'visible' => 1),
            'goods'  => array('text' => '推荐栏', 'visible' => 1)
        );

        $sorts = ((isset($_W['shopset']['shop']['indexsort']) ? $_W['shopset']['shop']['indexsort'] : $defaults));

        $sorts['recommand'] = array('text' => '系统推荐', 'visible' => 1);

        // 幻灯片
        $advs = pdo_fetchall(
            ' select id,advname,link,thumb ' .
            ' from ' . tablename('superdesk_shop_adv') .
            ' where uniacid=:uniacid ' .
            '       and enabled=1 ' .
            ' order by displayorder desc',
            array(
                ':uniacid' => $_W['uniacid']
            )
        );

        // 导航栏
        $navs = pdo_fetchall(
            ' select id,navname,url,icon ' .
            ' from ' . tablename('superdesk_shop_nav') .
            ' where uniacid=:uniacid ' .
            '       and status=1 ' .
            ' order by displayorder desc',
            array(
                ':uniacid' => $_W['uniacid']
            )
        );

        // 魔方栏
        $cubes = ((is_array($_W['shopset']['shop']['cubes']) ? $_W['shopset']['shop']['cubes'] : array()));

        // 广告栏
        $banners = pdo_fetchall(
            ' select id,bannername,link,thumb ' .
            ' from ' . tablename('superdesk_shop_banner') .
            ' where uniacid=:uniacid ' .
            '       and enabled=1 ' .
            ' order by displayorder desc',
            array(
                ':uniacid' => $_W['uniacid']
            )
        );

        // 广告栏
        $bannerswipe = $_W['shopset']['shop']['bannerswipe'];

        // 推荐栏 为你推荐
        if (!empty($_W['shopset']['shop']['indexrecommands'])) {

            $goodids = implode(',', $_W['shopset']['shop']['indexrecommands']);

            if (!empty($goodids)) {

                $select_fields =
                    " id,title,thumb,marketprice,productprice,minprice,maxprice,isdiscount,isdiscount_time, " .
                    "isdiscount_discounts,sales,total,description,bargain,jd_vop_sku,tcate,merchid,costprice ";

                $indexrecommands = pdo_fetchall(
                    ' select ' .
                    $select_fields .
                    ' from ' . tablename('superdesk_shop_goods') .
                    ' where id in( ' . $goodids . ' ) ' .
                    '       and uniacid=:uniacid ' .
                    '       and status=1 ' .
                    ' order by instr(\'' . $goodids . '\',id),displayorder desc',
                    array(
                        ':uniacid' => $_W['uniacid']
                    )
                );

                $indexrecommands = $this->_priceService->businessProcessingUpdateJdVopPriceForShopList($indexrecommands);// 更新商品价格

                //2019年5月24日 16:48:51 zjh 文礼 价套
                $indexrecommands = m('goods')->getGoodsCategoryEnterpriseDiscount($indexrecommands);
            }

        }


        $goodsstyle = $_W['shopset']['shop']['goodsstyle'];

        // 公告栏
        $notices = pdo_fetchall(
            ' select id, title, link, thumb ' .
            ' from ' . tablename('superdesk_shop_notice') .
            ' where uniacid=:uniacid ' .
            '       and status=1 ' .
            ' order by displayorder desc ' .
            ' limit 5',
            array(
                ':uniacid' => $_W['uniacid']
            )
        );

        //切换项目处理地址
        $switch_url = mobileUrl('switchOrganization');

        ob_start();
        ob_implicit_flush(false);

        require $this->template('index_tpl');

        return ob_get_clean();
    }

    public function switchOrganization(){
        global $_W;

        //组合跳转楼宇之窗选择项目地址
        //http://wx.palmnest.com/   super_dev/wechat/newneigou/selectPage?userMobile=xxxx -- 测试
        //https://superdesk.avic-s.com/   super_service/wechat/newneigou/selectPage?userMobile=xxxx --正式

        $headerUrl = SUPERDESK_SHOPV2_BUILD_WINDOW_URL . 'super_service/wechat/newneigou/selectPage?userMobile=' . $_W['userMobile'];

        $key = '__superdesk_shopv2_member_session_' . $_W['uniacid'];
        isetcookie($key, false, -100);
        header('location: ' . $headerUrl);
        exit();
    }
}
