<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Index_SuperdeskShopV2Page extends PluginMobilePage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        $uniacid = $_W['uniacid'];

        $mid = intval($_GPC['mid']);

        $enterprise_id = intval($_GPC['enterprise_id']);

        if (!$enterprise_id) {
            $this->message('没有找到此企业', '', 'error');
        }

        $index_cache = $this->getpage($enterprise_id);

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
            }
                , $index_cache);
        }

        $set = $this->model->getListUserOne($enterprise_id);

        if (!empty($set)) {
            $_W['shopshare'] = array(
                'title'  => $set['enterprise_name'],
                'imgUrl' => tomedia($set['logo']),
                'desc'   => $set['desc'],
                'link'   => mobileUrl('enterprise', array('enterprise_id' => $enterprise_id), true)
            );

            if (p('commission')) {

                $set = p('commission')->getSet();

                if (!empty($set['level'])) {

                    $member = m('member')->getMember($_W['openid'], $_W['core_user']);

                    if (!empty($member) && ($member['status'] == 1) && ($member['isagent'] == 1)) {

                        $_W['shopshare']['link'] = mobileUrl('enterprise', array('enterprise_id' => $enterprise_id, 'mid' => $member['id']), true);
                    } else if (!empty($mid)) {

                        $_W['shopshare']['link'] = mobileUrl('enterprise', array('enterprise_id' => $enterprise_id, 'mid' => $mid), true);
                    }
                }
            }
        }

        include $this->template('index');
    }

    public function get_recommand()
    {
        global $_W;
        global $_GPC;

        $args = array(
            'page'          => intval($_GPC['page']),
            'pagesize'      => 6,
            'isrecommand'   => 1,
            'order'         => 'displayorder desc,createtime desc',
            'by'            => '',
            'enterprise_id' => intval($_GPC['enterprise_id'])
        );

        $recommand = m('goods')->getList($args);

        show_json(1, array(
                'list'     => $recommand['list'],
                'pagesize' => $args['pagesize'],
                'total'    => $recommand['total'],
                'page'     => intval($_GPC['page']))
        );
    }

    private function getcache()
    {
        global $_W;
        global $_GPC;

        return m('common')->createStaticFile(mobileUrl('getpage', NULL, true));
    }

    public function getpage($enterprise_id)
    {
        global $_W;
        global $_GPC;

        $uniacid       = $_W['uniacid'];
        $enterprise_id = intval($enterprise_id);

        $defaults = array(
            'adv'    => array('text' => '幻灯片', 'visible' => 1),
            'search' => array('text' => '搜索栏', 'visible' => 1),
            'nav'    => array('text' => '导航栏', 'visible' => 1),
            'notice' => array('text' => '公告栏', 'visible' => 1),
            'cube'   => array('text' => '魔方栏', 'visible' => 1),
            'banner' => array('text' => '广告栏', 'visible' => 1),
            'goods'  => array('text' => '推荐栏', 'visible' => 1)
        );

        $shop  = p('enterprise')->getSet('shop', $enterprise_id);
        $sorts = ((isset($shop['indexsort']) ? $shop['indexsort'] : $defaults));

        $sorts['recommand'] = array('text' => '系统推荐', 'visible' => 1);

        $advs        = pdo_fetchall(
            'select id,advname,link,thumb from ' . tablename('superdesk_shop_enterprise_adv') .
            ' where uniacid=:uniacid and enterprise_id=:enterprise_id and enabled=1 ' .
            ' order by displayorder desc', array(':uniacid' => $uniacid, ':enterprise_id' => $enterprise_id));
        $navs        = pdo_fetchall('select id,navname,url,icon from ' . tablename('superdesk_shop_enterprise_nav') . ' where uniacid=:uniacid and enterprise_id=:enterprise_id and status=1 order by displayorder desc', array(':uniacid' => $uniacid, ':enterprise_id' => $enterprise_id));
        $cubes       = ((is_array($shop['cubes']) ? $shop['cubes'] : array()));
        $banners     = pdo_fetchall('select id,bannername,link,thumb from ' . tablename('superdesk_shop_enterprise_banner') . ' where uniacid=:uniacid and enterprise_id=:enterprise_id and enabled=1 order by displayorder desc', array(':uniacid' => $uniacid, ':enterprise_id' => $enterprise_id));
        $bannerswipe = $shop['bannerswipe'];

        if (!empty($shop['indexrecommands'])) {
            $goodids = implode(',', $shop['indexrecommands']);
            if (!empty($goodids)) {
                $indexrecommands = pdo_fetchall('select id, title, thumb, marketprice, productprice, minprice, total from ' . tablename('superdesk_shop_goods') . ' where id in( ' . $goodids . ' ) and uniacid=:uniacid and enterprise_id=:enterprise_id and status=1 order by instr(\'' . $goodids . '\',id),displayorder desc', array(':uniacid' => $uniacid, ':enterprise_id' => $enterprise_id));
            }
        }

        $goodsstyle = $shop['goodsstyle'];

        $notices = pdo_fetchall('select id, title, link, thumb from ' . tablename('superdesk_shop_enterprise_notice') . ' where uniacid=:uniacid and enterprise_id=:enterprise_id and status=1 order by displayorder desc limit 5', array(':uniacid' => $uniacid, ':enterprise_id' => $enterprise_id));
        ob_start();
        ob_implicit_flush(false);

        require $this->template('index_tpl');

        return ob_get_clean();
    }
}