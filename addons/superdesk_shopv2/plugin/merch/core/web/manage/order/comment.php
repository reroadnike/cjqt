<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}
require SUPERDESK_SHOPV2_PLUGIN . 'merch/core/inc/page_merch.php';

class Comment_SuperdeskShopV2Page extends MerchWebPage
{
    public function main()
    {
        global $_W;
        global $_GPC;
        $uniacid = $_W['uniacid'];
        $merchid = $_W['merchid'];
        $params = array(':uniacid' => $uniacid,':merchid' => $merchid);

        $orders = pdo_fetchall(
            'SELECT id,ordersn,createtime ' . ' FROM ' . tablename('superdesk_shop_order') . ' WHERE ' . ' uniacid=:uniacid AND merchid=:merchid ' ,$params
        );

        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;

        $condition = '';
        $sqlcondition = $groupcondition = '';

        //关键词搜索
        if (!empty($_GPC['keyword'])) {
            $_GPC['keyword'] = trim($_GPC['keyword']);

            $sqlcondition .= ' LEFT JOIN ' . tablename('superdesk_shop_order') . ' op ON c.`orderid` = op.`id`';

            $searchfield       = $_GPC['searchfield'];

            $condition_keyword = '';

            if ($searchfield == 'ordersn') { // 商品名称
                $condition_keyword  .= ' op.`ordersn` LIKE :keyword ';
                $params[':keyword'] = '%' . $_GPC['keyword'] . '%';
            } elseif ($searchfield == 'nickname') { // 商品关键字
                $condition_keyword  .= ' c.`nickname` LIKE :keyword ';
                $params[':keyword'] = '%' . $_GPC['keyword'] . '%';
            }
            $condition .=
                ' AND ( ';

            $condition .= $condition_keyword;

            $condition .= ' )';

        }
        $groupcondition .= ' GROUP BY c.`id`';
        //时间搜索
        if (empty($starttime) || empty($endtime)) {
            $starttime = strtotime('-1 month');
            $endtime   = time();
        }

        if (!empty($_GPC['time']['start']) && !empty($_GPC['time']['end'])) {

            $starttime = strtotime($_GPC['time']['start']);
            $endtime   = strtotime($_GPC['time']['end']);

            $condition .=
                ' AND c.`createtime` >= :starttime ' .
                ' AND c.`createtime` <= :endtime ';

            $params[':starttime'] = $starttime;
            $params[':endtime']   = $endtime;
        }
        $params[':state'] = 1;
        $comment = pdo_fetchall(
            'SELECT c.id,c.uniacid,c.orderid,c.nickname,c.content,c.append_content,c.reply_content,c.append_reply_content,c.createtime,c.logis,c.service,c.describes,c.merchid,c.state ' .
            ' FROM ' . tablename('superdesk_shop_order_comment') . 'c' . $sqlcondition .
            ' WHERE ' .
            ' c.uniacid = :uniacid ' . ' AND ' . ' c.merchid = :merchid ' . ' AND ' . ' c.state = :state ' .
            $condition .
            $groupcondition .
            ' order by id desc' .
            ' LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize,
            $params
        );

        foreach($comment as $k=>$v) {
            foreach($orders as $kk=>$vv) {
                if($v['orderid']==$vv['id']) {
                    $comment[$k]['ordersn'] = $vv['ordersn'];
                    $comment[$k]['ordertime'] = $vv['createtime'];
                }
            }
        }

        $total = pdo_fetchcolumn(
            'SELECT count(1) ' .
            ' FROM ' . tablename('superdesk_shop_order_comment') . 'c' . $sqlcondition .
            ' WHERE ' .
            ' c.uniacid =:uniacid ' . ' AND ' . ' c.merchid = :merchid ' . ' AND ' . ' c.state = :state ' .
            $condition,
            $params
        );

        $pager = pagination($total, $pindex, $psize);

        include $this->template();
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
            'SELECT ' .
            '       id,orderid ' .
            ' FROM ' . tablename('superdesk_shop_order_comment') .
            ' WHERE ' .
            '       id in( ' . $id . ' ) ' .
            '       AND uniacid=' . $_W['uniacid']
        );

        if (empty($item)) {
            $item = array();
        }

        $res = pdo_delete('superdesk_shop_order_comment', array('id' => $item['id']));
        if(!empty($res)) {
            show_json(1, array('url' => referer()));
        }
  /*      foreach ($items as $item) {
            pdo_delete('superdesk_shop_order_comment', array('id' => $item['id']));
            plog('goods.edit', '从回收站彻底删除评价<br/>ID: ' . $item['id'] . '<br/>标签组名称: ' . $item['label']);
        }*/


    }

    public function view() {
        global $_W;
        global $_GPC;
        $id = $_GPC['id'];
        $merchid = $_W['merchid'];
        $comment = pdo_get('superdesk_shop_order_comment',array('orderid'=>$id,'merchid'=>$merchid));
        if(!empty($comment['images'])) {
            $comment['images'] = iunserializer($comment['images']);
        }
        if(!empty($comment['append_images'])) {
            $comment['append_images'] = iunserializer($comment['append_images']);
        }
        if(!empty($comment['reply_images'])) {
            $comment['reply_images'] = iunserializer($comment['reply_images']);
        }
        if(!empty($comment['append_reply_images'])) {
            $comment['append_reply_images'] = iunserializer($comment['append_reply_images']);
        }
        $member = m('member')->getMember($comment['openid'], $comment['core_user']);
        $order = pdo_get('superdesk_shop_order',array('id'=>$comment['orderid'],'merchid'=>$merchid),array('id','ordersn','createtime'));
        $goods = pdo_fetchall(
            ' SELECT * FROM ' .
            tablename('superdesk_shop_comments_goods') . ' cg ' .
            ' LEFT JOIN ' . tablename('superdesk_shop_goods') . ' g ' . ' ON cg.goodsid = g.id ' .
            ' WHERE ' .
            '       cg.orderid=:orderid ' ,
            array(
                ':orderid' => $order['id']
            )
        );

        include $this->template();
    }

    //回复评价
    public function edit() {
        global $_GPC;
        if($_GPC['reply_content']) {
            $image = is_array($_GPC['reply_images']) ? iserializer($_GPC['reply_images']) : iserializer(array());
            $comment = array(
                'reply_images' => $image,
                'reply_content'=> $_GPC['reply_content']
            );
        }
        if($_GPC['append_reply_content']) {
            $image = is_array($_GPC['append_reply_images']) ? iserializer($_GPC['append_reply_images']) : iserializer(array());
            $comment = array(
                'append_reply_images' => $image,
                'append_reply_content'=> $_GPC['append_reply_content']
            );
        }
        //print_r($comment);die;
        $data = pdo_update('superdesk_shop_order_comment',$comment,array('orderid'=>$_GPC['id']));
        if($data) {
            show_json(1);
        } else {
            show_json(0);
        }
    }


    public function status() {
        global $_GPC;
        $id = $_GPC['id'];
        $res = pdo_update('superdesk_shop_order_comment',array('state'=>2),array('id'=>$id));
        if($res) {
            show_json(1, array('url' => referer()));
        }
    }

    public function query()
    {
        global $_W;
        global $_GPC;
        $kwd                = trim($_GPC['keyword']);
        $params             = array();
        $params[':uniacid'] = $_W['uniacid'];
        $condition          = ' and uniacid=:uniacid and status = 1 ';

        if (!empty($kwd)) {
            $condition           .= ' AND label LIKE :keywords ';
            $params[':keywords'] = '%' . $kwd . '%';
        }

        $labels = pdo_fetchall(
            'SELECT id,label,labelname ' .
            ' FROM ' . tablename('superdesk_shop_goods_label') .
            ' WHERE 1 ' .
            $condition .
            ' order by id desc',
            $params
        );

        if (empty($labels)) {
            $labels = array();
        }

        foreach ($labels as $key => $value) {
            $labels[$key]['labelname'] = json_decode($value['labelname'], true);
        }

        include $this->template();
    }


    public function style()
    {
        global $_W;
        global $_GPC;

        $uniacid = intval($_W['uniacid']);

        $style = pdo_fetch(
            'SELECT id,uniacid,style ' .
            ' FROM ' . tablename('superdesk_shop_goods_labelstyle') .
            ' WHERE uniacid=' . $uniacid
        );

        if ($_W['ispost']) {

            $data['style'] = intval($_GPC['style']);

            if (!empty($style)) {

                pdo_update(
                    'superdesk_shop_goods_labelstyle',
                    $data,
                    array(
                        'uniacid' => $uniacid
                    )
                );

                plog('goods.labelstyle.edit', '修改标签组样式');

            } else {

                $data['uniacid'] = $uniacid;

                pdo_insert('superdesk_shop_goods_labelstyle', $data);
                $id = pdo_insertid();

                plog('goods.labelstyle.add', '添加标签组样式');
            }

            show_json(1, array(
                'url' => webUrl('goods/label/style')
            ));
        }


        include $this->template();
    }

    public function withdraw()
    {
        $this->main(1);
    }

}