<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Index_SuperdeskShopV2Page extends WebPage
{

    /**
     * 商品管理
     */
    public function main()
    {
        global $_W;
        global $_GPC;

        $merch_plugin = p('merch');
        $merch_data   = m('common')->getPluginset('merch');

        if ($merch_plugin && $merch_data['is_openmerch']) {
            $is_openmerch = 1;
        } else {
            $is_openmerch = 0;
        }

        $pindex       = max(1, intval($_GPC['page']));
        $psize        = 20;
        $sqlcondition = $groupcondition = '';
        $condition    = ' WHERE g.`uniacid` = :uniacid';
        $params       = array(':uniacid' => $_W['uniacid']);

        if (!empty($_GPC['keyword'])) {

            $_GPC['keyword'] = trim($_GPC['keyword']);

            $sqlcondition = ' left join ' . tablename('superdesk_shop_goods_option') . ' op on g.id = op.goodsid';

            if ($merch_plugin) {
                $sqlcondition .= ' left join ' . tablename('superdesk_shop_merch_user') . ' merch on merch.id = g.merchid and merch.uniacid=g.uniacid';
            }

            $groupcondition = ' group by g.`id`';

            $searchfield       = $_GPC['searchfield'];
            $condition_keyword = '';


            if ($searchfield == 'title') { // 商品名称

                $condition_keyword  .= ' g.`title` LIKE :keyword ';
                $condition_keyword  .= ' or op.`title` LIKE :keyword ';
                $params[':keyword'] = '%' . $_GPC['keyword'] . '%';

            } elseif ($searchfield == 'keywords') { // 商品关键字

                $condition_keyword  .= ' g.`keywords` LIKE :keyword ';
                $params[':keyword'] = '%' . $_GPC['keyword'] . '%';

            } elseif ($searchfield == 'id') { // 商品ID

                $condition_keyword .= 'g.`id` = :id ';
                $params[':id']     = $_GPC['keyword'];

            } elseif ($searchfield == 'jd_vop_sku') { // 京东SKU

                $condition_keyword .= ' g.`jd_vop_sku` = :sku ';
                $params[':sku']    = $_GPC['keyword'];

            } elseif ($searchfield == 'goodssn') { // 商品编码

                $condition_keyword  .= ' g.`goodssn` LIKE :keyword ';
                $condition_keyword  .= ' or op.`goodssn` LIKE :keyword ';
                $params[':keyword'] = '%' . $_GPC['keyword'] . '%';

            } elseif ($searchfield == 'productsn') { // 商品条码

                $condition_keyword  .= ' g.`productsn` LIKE :keyword ';
                $condition_keyword  .= ' or op.`productsn` LIKE :keyword';
                $params[':keyword'] = '%' . $_GPC['keyword'] . '%';

            } elseif ($searchfield == 'merch') { // 商户名称

                if ($merch_plugin) {
                    $condition_keyword  .= ' merch.`merchname` LIKE :keyword';
                    $params[':keyword'] = '%' . $_GPC['keyword'] . '%';
                }
            }

            $condition .=
                ' AND ( ';

            $condition .= $condition_keyword;

            $condition .= ' )';


        }

        if (!empty($_GPC['cate'])) {
            $_GPC['cate'] = intval($_GPC['cate']);
            $condition    .= ' AND FIND_IN_SET(' . $_GPC['cate'] . ',cates)<>0 ';
        }

        $goodsfrom = strtolower(trim($_GPC['goodsfrom']));

        empty($goodsfrom) && ($_GPC['goodsfrom'] = $goodsfrom = 'sale');

        if ($goodsfrom == 'sale') {// 出售中
            $condition .= ' AND g.`status` > 0 and g.`checked`=0 and g.`total`>0 and g.`deleted`=0';
            $status    = 1;
        } else if ($goodsfrom == 'out') {// 已售罄
            $condition .= ' AND g.`status` > 0 and g.`total` <= 0 and g.`deleted`=0';
            $status    = 1;
        } else if ($goodsfrom == 'stock') {// 仓库中
            $status    = 0;
            $condition .= ' AND (g.`status` = 0 or g.`checked`=1) and g.`deleted`=0';
        } else if ($goodsfrom == 'cycle') {// 回收站
            $status    = 0;
            $condition .= ' AND g.`status` > 0 and g.`deleted`=1';
        } else if ($goodsfrom == 'check') {// 待审核
            $status    = 0;
            $condition .= ' AND g.`checked`=1 and g.`deleted`=0';
        }else if ($goodsfrom == 'checkfalse') {// 审核驳回
            $status    = 0;
            $condition .= ' AND g.`checked`=2 and g.`deleted`=0';
        }

        // 发现傻X做法
//        $sql =
//            ' SELECT g.id ' .
//            ' FROM ' . tablename('superdesk_shop_goods') . 'g' .
//            $sqlcondition .
//            $condition .
//            $groupcondition;
//        $total_all = pdo_fetchall($sql, $params);
//        $total     = count($total_all);
//        unset($total_all);
        // 修正
        $sql   =
            ' SELECT COUNT(DISTINCT g.id) ' .
            ' FROM ' . tablename('superdesk_shop_goods') . 'g' .
            $sqlcondition .
            $condition;
//            $groupcondition;
        $total = pdo_fetchcolumn(
            $sql,
            $params
        );


        if (!empty($total)) {
            $sql =
                ' SELECT g.* ' .
                ' FROM ' . tablename('superdesk_shop_goods') . 'g' .
                $sqlcondition .
                $condition .
                $groupcondition .
//                ' ORDER BY g.status DESC, g.displayorder DESC,g.id DESC '.
                ' LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize;

            // TODO 商品管理 总后台
            socket_log($sql);
            socket_log(json_encode($params, JSON_UNESCAPED_UNICODE));

            $list  = pdo_fetchall($sql, $params);
            $pager = pagination($total, $pindex, $psize);
            if ($merch_plugin) {
                $merch_user = $merch_plugin->getListUser($list, 'merch_user');
                if (!empty($list) && !empty($merch_user)) {
                    foreach ($list as &$row) {
                        $row['merchname'] = (($merch_user[$row['merchid']]['merchname'] ? $merch_user[$row['merchid']]['merchname'] : $_W['shopset']['shop']['name']));
                    }
                }
            }
        }

        $categorys = m('shop')->getFullCategory(true);
        $category  = array();

        foreach ($categorys as $cate) {
            $category[$cate['id']] = $cate;
        }

        include $this->template();
    }

    public function add()
    {
        $this->post();
    }

    public function edit()
    {
        $this->post();
    }

    protected function post()
    {
        require dirname(__FILE__) . '/post.php';
    }

    public function delete()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        if (empty($id)) {
            $id = ((is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0));
        }

        $items = pdo_fetchall(
            'SELECT id,title FROM ' . tablename('superdesk_shop_goods') .
            ' WHERE id in( ' . $id . ' ) AND uniacid=' . $_W['uniacid']);

        foreach ($items as $item) {
            pdo_update('superdesk_shop_goods', array('deleted' => 1,'updatetime' => time()), array('id' => $item['id']));
            plog('goods.delete', '删除商品 ID: ' . $item['id'] . ' 商品名称: ' . $item['title'] . ' ');
        }
        show_json(1, array('url' => referer()));
    }

    public function status()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        if (empty($id)) {
            $id = ((is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0));
        }

        $items = pdo_fetchall(
            'SELECT id,title FROM ' . tablename('superdesk_shop_goods') .
            ' WHERE id in( ' . $id . ' ) AND uniacid=' . $_W['uniacid']
        );
		
		//加载 20190704 luoxt
        load()->func('communication');

        foreach ($items as $item) {
            pdo_update('superdesk_shop_goods', array('status' => intval($_GPC['status']),'updatetime' => time()), array('id' => $item['id']));

            plog('goods.edit', '修改商品状态<br/>ID: ' . $item['id'] . '<br/>商品名称: ' . $item['title'] . '<br/>状态: ' . ($_GPC['status'] == 1 ? '上架' : '下架'));
        
		
			//加载 20190704 luoxt
            try {
                $loginurl = $_W['config']['api_host'].'/api/base/message/collect';
                $post = [
                    "merchid"  => $item['merchid'],
                    "msg_type"  =>  "goods",
                    "msg_body"=>[
                        "goods_sku" => $item['goodssn'],
                        "goods_type"  =>  $_GPC['status'],
                        "time"  =>  time()
                    ]
                ];

                $response = ihttp_post($loginurl, $post);

            } catch (Exception $e) { }
		}

        show_json(1, array('url' => referer()));
    }

    public function checked()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        if (empty($id)) {
            $id = ((is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0));
        }

        $items = pdo_fetchall(
            'SELECT id,title FROM ' . tablename('superdesk_shop_goods') .
            ' WHERE id in( ' . $id . ' ) AND uniacid=' . $_W['uniacid']
        );
		
		//加载 20190704 luoxt
        load()->func('communication');

        foreach ($items as $item) {

            pdo_update('superdesk_shop_goods', array('checked' => intval($_GPC['checked']),'updatetime' => time()), array('id' => $item['id']));
            plog('goods.edit', '修改商品状态<br/>ID: ' . $item['id'] . '<br/>商品名称: ' . $item['title'] . '<br/>状态: ' . ($_GPC['checked'] == 0 ? '审核通过' : '待审核'));
			
			//加载 20190704 luoxt
            try {
                $loginurl = $_W['config']['api_host'].'/api/base/message/collect';
                $post = [
                    "merchid"  => $item['merchid'],
                    "msg_type"  =>  "goods",
                    "msg_body"=>[
                        "goods_sku" => $item['goodssn'],
                        "goods_type"  =>  $_GPC['status'],
                        "time"  =>  time()
                    ]
                ];

                $response = ihttp_post($loginurl, $post);

            } catch (Exception $e) { }
        }

        show_json(1, array('url' => referer()));
    }

    public function delete1()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        if (empty($id)) {
            $id = ((is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0));
        }

        $items = pdo_fetchall(
            'SELECT id,title FROM ' . tablename('superdesk_shop_goods') .
            ' WHERE id in( ' . $id . ' ) AND uniacid=' . $_W['uniacid']
        );

        foreach ($items as $item) {
            pdo_delete('superdesk_shop_goods', array('id' => $item['id']));
            plog('goods.edit', '从回收站彻底删除商品<br/>ID: ' . $item['id'] . '<br/>商品名称: ' . $item['title']);
        }

        show_json(1, array('url' => referer()));
    }

    public function restore()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        if (empty($id)) {
            $id = ((is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0));
        }

        $items = pdo_fetchall(
            'SELECT id,title FROM ' . tablename('superdesk_shop_goods') .
            ' WHERE id in( ' . $id . ' ) AND uniacid=' . $_W['uniacid']
        );

        foreach ($items as $item) {

            pdo_update('superdesk_shop_goods', array('deleted' => 0,'updatetime' => time()), array('id' => $item['id']));
            plog('goods.edit', '从回收站恢复商品<br/>ID: ' . $item['id'] . '<br/>商品名称: ' . $item['title']);
        }

        show_json(1, array('url' => referer()));
    }

    public function property()
    {
        global $_W;
        global $_GPC;

        $id   = intval($_GPC['id']);
        $type = $_GPC['type'];
        $data = intval($_GPC['data']);

        if (in_array($type, array('new', 'hot', 'recommand', 'discount', 'time', 'sendfree', 'nodiscount'))) {

            pdo_update(
                'superdesk_shop_goods',
                array(
                    'is' . $type => $data,
                    'updatetime' => time()
                ),
                array(
                    'id'      => $id,
                    'uniacid' => $_W['uniacid']
                )
            );

            if ($type == 'new') {

                $typestr = '新品';

            } else if ($type == 'hot') {

                $typestr = '热卖';

            } else if ($type == 'recommand') {

                $typestr = '推荐';

            } else if ($type == 'discount') {

                $typestr = '促销';

            } else if ($type == 'time') {

                $typestr = '限时卖';

            } else if ($type == 'sendfree') {

                $typestr = '包邮';

            } else if ($type == 'nodiscount') {

                $typestr = '不参与折扣状态';

            }

            plog('goods.edit', '修改商品' . $typestr . '状态   ID: ' . $id);
        }

        if (in_array($type, array('status'))) {
            pdo_update(
                'superdesk_shop_goods',
                array(
                    $type => $data,
                    'updatetime' => time()
                ),
                array(
                    'id'      => $id,
                    'uniacid' => $_W['uniacid']
                )
            );
            plog('goods.edit', '修改商品上下架状态   ID: ' . $id);
        }

        if (in_array($type, array('type'))) {
            pdo_update(
                'superdesk_shop_goods',
                array(
                    $type => $data,
                    'updatetime' => time()
                ),
                array(
                    'id'      => $id,
                    'uniacid' => $_W['uniacid']
                )
            );
            plog('goods.edit', '修改商品类型   ID: ' . $id);
        }

        show_json(1);
    }

    public function change()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        if (empty($id)) {
            show_json(
                0,
                array(
                    'message' => '参数错误'
                )
            );
        }

        $type  = trim($_GPC['type']);
        $value = trim($_GPC['value']);

        if (!in_array($type, array('title', 'marketprice', 'total', 'goodssn', 'productsn', 'displayorder', 'costprice'))) {

            show_json(0, array('message' => '参数错误'));

        }

        $goods = pdo_fetch(
            ' select id,hasoption ' .
            ' from ' . tablename('superdesk_shop_goods') .
            ' where ' .
            '   id=:id ' .
            '   and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':uniacid' => $_W['uniacid'],
                ':id'      => $id
            )
        );

        if (empty($goods)) {
            show_json(0, array('message' => '参数错误'));
        }

        pdo_update(
            'superdesk_shop_goods',
            array(
                $type => $value,
                'updatetime' => time()
            ),
            array(
                'id' => $id
            )
        );

        if ($goods['hasoption'] == 0) {
            $sql =
                ' update ' . tablename('superdesk_shop_goods') .
                ' set '.
                '   minprice = marketprice,'.
                '   maxprice = marketprice '.
                ' where '.
                '   id = ' . $goods['id'] .
                '   and hasoption=0;';
            pdo_query($sql);
        }

        show_json(1);
    }

    public function tpl()
    {
        global $_GPC;
        global $_W;

        $tpl = trim($_GPC['tpl']);

        if ($tpl == 'option') {
            $tag = random(32);
            include $this->template('goods/tpl/option');
            return;
        }

        if ($tpl == 'spec') {
            $spec = array('id' => random(32), 'title' => $_GPC['title']);
            include $this->template('goods/tpl/spec');
            return;
        }

        if ($tpl == 'specitem') {
            $spec     = array('id' => $_GPC['specid']);
            $specitem = array('id' => random(32), 'title' => $_GPC['title'], 'show' => 1);
            include $this->template('goods/tpl/spec_item');
            return;
        }

        if ($tpl == 'param') {
            $tag = random(32);
            include $this->template('goods/tpl/param');
        }
    }

    public function query()
    {
        global $_W;
        global $_GPC;

        $kwd                = trim($_GPC['keyword']);
        $type               = intval($_GPC['type']);
        $merchid            = intval($_GPC['merchid']);

        $params             = array();

        $params[':uniacid'] = $_W['uniacid'];

        $condition          =
            ' and status=1 '.
            ' and deleted=0 '.
            ' and uniacid=:uniacid';

        socket_log($kwd.':'.is_numeric($kwd));

        if (!empty($kwd)) {

            if(is_numeric($kwd) || is_long($kwd)){
                $condition           .= ' AND ( id = :keywords OR jd_vop_sku = :keywords)';
                $params[':keywords'] = $kwd;
            } else {
                $condition           .= ' AND (`title` LIKE :keywords OR `keywords` LIKE :keywords)';
                $params[':keywords'] = '%' . $kwd . '%';
            }


        }

        if (empty($type)) {
            $condition .= ' AND `type` != 10 ';
        } else {
            $condition       .= ' AND `type` = :type ';
            $params[':type'] = $type;
        }

        if (!empty($merchid)) {
            $condition .= ' AND `merchid` = :merchid ';
            $params[':merchid'] = $merchid;
        }

        socket_log(' SELECT id,title,thumb,marketprice,productprice,share_title,share_icon,description, minprice '.
            ' FROM ' . tablename('superdesk_shop_goods') .
            ' WHERE 1 ' . $condition .
            ' order by createtime desc');

        $ds = pdo_fetchall(
            ' SELECT id,title,thumb,marketprice,productprice,share_title,share_icon,description, minprice '.
            ' FROM ' . tablename('superdesk_shop_goods') .
            ' WHERE 1 ' . $condition .
            ' order by createtime desc',
            $params
        );

        $ds = set_medias($ds, array('thumb', 'share_icon'));

        if ($_GPC['suggest']) {
            exit(json_encode(array('value' => $ds)));
        }

        include $this->template();
    }

    public function goodsprice()
    {
        global $_W;

        $sql = 'update ' . tablename('superdesk_shop_goods') . ' g set ' . "\n" . 'g.minprice = (select min(marketprice) from ' . tablename('superdesk_shop_goods_option') . ' where g.id = goodsid),' . "\n" . 'g.maxprice = (select max(marketprice) from ' . tablename('superdesk_shop_goods_option') . ' where g.id = goodsid)' . "\n" . 'where g.hasoption=1 and g.uniacid=' . $_W['uniacid'] . ';' . "\n" . 'update ' . tablename('superdesk_shop_goods') . ' set minprice = marketprice,maxprice = marketprice where hasoption=0 and uniacid=' . $_W['uniacid'] . ';';
        pdo_run($sql);
        show_json(1);
    }
}
