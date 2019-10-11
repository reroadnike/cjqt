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

        $uniacid            = $_W['uniacid'];
        $params[':uniacid'] = $uniacid;
        $orders = pdo_fetchall(
            'SELECT id,ordersn,createtime ' . ' FROM ' . tablename('superdesk_shop_order') . ' WHERE ' . ' uniacid=:uniacid ' ,$params
        );

        $condition = '';

        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;

        /*if ($_GPC['enabled'] != '') {
            $condition .= ' and status=' . intval($_GPC['enabled']);
        }*/
//用户可以根据评价时间、评价者、订单编号、订单归属商户等字段信息筛选搜索订单评价
        if (!empty($_GPC['keyword'])) {
            $_GPC['keyword'] = trim($_GPC['keyword']);

            $condition .= ' and nickname like :keyword';

            $params[':keyword'] = '%' . $_GPC['keyword'] . '%';
        }
//订单编号、评价者 评价得分、评价状态、回复状态、下单时间、评价时间
        $comment = pdo_fetchall(
            'SELECT id,uniacid,orderid,nickname,content,append_content,reply_content,append_reply_content,createtime,logis,service,describes ' .
            ' FROM ' . tablename('superdesk_shop_order_comment') .
            ' WHERE ' .
            '       uniacid=:uniacid ' .
            $condition .
            ' order by id ' .
            ' limit ' . (($pindex - 1) * $psize) . ',' . $psize,
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
        //print_r($comment);
        $total = pdo_fetchcolumn(
            'SELECT count(1) ' .
            ' FROM ' . tablename('superdesk_shop_order_comment') .
            ' WHERE ' .
            '       uniacid=:uniacid ' .
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
        global $_GPC;
        $id = $_GPC['id'];
        $comment = pdo_get('superdesk_shop_order_comment',array('id'=>$id));
        $order = pdo_get('superdesk_shop_order',array('id'=>$comment['orderid']),array('id','ordersn','createtime'));
        $comment['goods'] = json_decode($comment['goods']);
        print_r($comment);
        include $this->template();
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
}