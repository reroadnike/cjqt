<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

//mark kafka 为了kafka转成了model执行
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order.class.php');

class Comment_SuperdeskShopV2Page extends MobileLoginPage
{
    private $_orderModel;

    public function __construct()
    {
        parent::__construct();

        $trade = m('common')->getSysset('trade');

        if (!empty($trade['closecomment'])) {
            $this->message('不允许评论!', '', 'error');
        }

        $this->_orderModel = new orderModel();
    }

    public function main()
    {
        global $_W;
        global $_GPC;

        $orderid = intval($_GPC['id']);

        $order = pdo_fetch(
            ' select id,status,ordersn ' .
            ' from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            ' limit 1',
            array(
                ':id'        => $orderid,
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user']
            )
        );

        if (empty($order)) {
            header('location: ' . mobileUrl('order'));
            exit();
        }

        if (($order['status'] != 3) && ($order['status'] != 4)) {
            $this->message('订单未收货，不能评价!', mobileUrl('order/detail', array('id' => $orderid)));
        }

        $goods = pdo_fetchall(
            'select '.
            '       og.id,og.goodsid,og.price,g.title,g.thumb,og.total,g.credit,og.optionid,o.title as optiontitle ' .
            ' from ' . tablename('superdesk_shop_order_goods') . ' og ' . // TODO 标志 楼宇之窗 openid shop_order_goods 不处理
            ' left join ' . tablename('superdesk_shop_goods') . ' g on g.id=og.goodsid ' .
            ' left join ' . tablename('superdesk_shop_goods_option') . ' o on o.id=og.optionid ' .
            ' where ' .
            '       og.orderid=:orderid ' .
            '       and og.uniacid=:uniacid ',
            array(
                ':uniacid' => $_W['uniacid'],
                ':orderid' => $orderid
            )
        );

        $commentgoods = pdo_fetchall(
            'SELECT * ' .
            ' FROM ' .
            tablename('superdesk_shop_comments_goods') .
            ' WHERE ' .
            ' orderid=:orderid ' ,
            array(
                'orderid' => $orderid
            )
        );
        if(!empty($commentgoods)) {
            foreach($goods as $k=>$v) {
                foreach($commentgoods as $kk=>$vv) {
                    if($v['goodsid']==$vv['goodsid']) {
                        $goods[$k]['level'] = $vv['level'];
                    }
                }
            }
        }
        $comment = pdo_fetch(
            ' select *' .
            ' from ' . tablename('superdesk_shop_order_comment') .// TODO 标志 楼宇之窗 openid superdesk_shop_order_comment 不处理
            ' where ' .
            ' orderid=:orderid ' .
            ' limit 1',
            array(
                ':orderid' => $orderid
            )
        );

        if(!empty($comment)) {
            $comment['images'] = iunserializer($comment['images']);
            if(!empty($comment['append_images'])) {
                $comment['append_images'] = iunserializer($comment['append_images']);
            }
            if(!empty($comment['reply_images'])) {
                $comment['reply_images'] = iunserializer($comment['reply_images']);
            }
            if(!empty($comment['append_reply_images'])) {
                $comment['append_reply_images'] = iunserializer($comment['append_reply_images']);
            }
        }

        $goods = set_medias($goods, 'thumb');

        include $this->template();
    }

    public function submit()
    {
        global $_W;
        global $_GPC;

        $orderid = intval($_GPC['orderid']);

        $order = pdo_fetch(
            ' select id,status,merchid,ordersn ' .
            ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where id=:id and uniacid=:uniacid and openid=:openid and core_user=:core_user limit 1',
            array(
                ':id'        => $orderid,
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user']
            )
        );

        if (empty($order)) {
            show_json(0, '订单未找到');
        }

        $member = m('member')->getMember($_W['openid'], $_W['core_user']);

        $trade = m('common')->getSysset('trade');

        if (!empty($trade['commentchecked'])) {
            $checked = 0;
        } else {
            $checked = 1;
        }
        $old_c = pdo_fetchcolumn(
            ' select count(*) ' .
            ' from ' . tablename('superdesk_shop_order_comment') .// TODO 标志 楼宇之窗 openid superdesk_shop_order_comment 不处理
            ' where ' .
            '       uniacid=:uniacid ' .
            '       and orderid=:orderid ' .
            ' limit 1',
            array(
                ':uniacid' => $_W['uniacid'],
                ':orderid' => $orderid
            )
        );


        if (empty($old_c)) {
            if(!$_GPC['logis'] || !$_GPC['service'] || !$_GPC['describes'] || !$_GPC['content'] || !is_array($_GPC['comments'])) {
                show_json('数据出错，请重试!');
            }
            //$goods = json_encode($_GPC['comments']);
            foreach($_GPC['comments'] as $k=>$v) {
                $comgoods = array(
                    'merchid'    => $order['merchid'],
                    'orderid'    => $orderid,
                    'goodsid'    => $v['goodsid'],
                    'level'      => $v['level'],
                    'content'    => trim($v['content']),
                    'createtime' => time()
                );
                pdo_insert('superdesk_shop_comments_goods',$comgoods);
            }
            $comment = array(
                'uniacid'    => $_W['uniacid'],
                'orderid'    => $orderid,
                'logis'      => $_GPC['logis'],
                'service'    => $_GPC['service'],
                'describes'  => $_GPC['describes'],
                'content'    => trim($_GPC['content']),
                'images'     => (is_array($_GPC['images']) ? iserializer($_GPC['images']) : iserializer(array())),
                'openid'     => $_W['openid'],
                'core_user'  => $_W['core_user'],
                'nickname'   => $member['nickname'],
                'headimgurl' => $member['avatar'],
                'createtime' => time(),
                'checked'    => $checked,
                'merchid'    => $order['merchid']
            );

            $data = pdo_insert('superdesk_shop_order_comment', $comment);// TODO 标志 楼宇之窗 openid superdesk_shop_order_comment 已处理

            //调用传接口

 /*           foreach($_GPC['comments'] as $k=>$v) {
                $good_app[$k]['goods_id'] = $v['goodsid'];
                $good_app[$k]['appraise_level'] = $v['level']*2;
            }

            $good_app = json_encode($good_app);

            //接口图片拼接
            if(is_array($_GPC['images'])) {
                $url = "www.cjqt.com";  //图片链接需要修改
                foreach ($_GPC['images'] as $k => $v) {
                    $urls[] = $url . '/' . $v;
                }
                $urls = implode(',', $urls);
            }

            $sentapi = "http://119.23.39.237:8089/web/unifiedorder/appraise";  //接口地址是否正确
            $apidata = array(
                //'version'   => '1.0',
                //'timestamp' => time(),
                //'charset'   => 'utf-8',
                //'nonce_str' => '',
                'sn'        => $order['ordersn'],  //订单号
                'appraise_content' => trim($_GPC['content']),
                'pics'    => $urls,
                'tran_level'  =>  $_GPC['logis']*2,//物流服务评分
                'service_level'  => $_GPC['service']*2,//客服服务评分
                'describes_level'  => $_GPC['describes']*2,//描述相符评分
                'user_id'  =>  $_W['core_user'],
                'user_name'   => $member['nickname'],
                'append_state' => 2,
                //goodsid 商品id   level 商品评分
                'goods_json' => $good_app,//商品json格式  例：[{"goods_id":"2479885","appraise_level":"4"},{"goods_id":"2479917","appraise_level":"5"}]
                //'sign'   => ''
            );
            load()->func('communication');
            $response = ihttp_post($sentapi, $apidata);   */

        } else {
            $comment = array(
                'append_content' => trim($_GPC['append_content']),
                'append_images'  => (is_array($_GPC['append_images']) ? iserializer($_GPC['append_images']) : iserializer(array())),
                'replychecked'   => $checked,
                'status'         => 2
            );

            $data = pdo_update(
                'superdesk_shop_order_comment',// TODO 标志 楼宇之窗 openid superdesk_shop_order_comment 不处理
                $comment,
                array(
                    'uniacid' => $_W['uniacid'],
                    'orderid' => $orderid
                )
            );

            //接口图片拼接
 /*           if(is_array($_GPC['append_images'])) {
                $url = "www.cjqt.com";
                foreach ($_GPC['append_images'] as $k => $v) {
                    $urls[] = $url . '/' . $v;
                }
                $urls = implode(',', $urls);
            }

            //调用接口
            $sentapi = "http://119.23.39.237:8089/web/unifiedorder/appraise";
            $apidata = array(
                //'version'   => '1.0',
                //'timestamp' => time(),
                //'charset'   => 'utf-8',
                //'nonce_str' => '',
                'sn'        => $order['ordersn'],
                'appraise_content' => trim($_GPC['append_content']),
                'pics'    => $urls,
                'user_id'  =>  $_W['core_user'],
                'user_name'   => $member['nickname'],
                'append_state' => 1,
                //'sign'   => ''
            );
            load()->func('communication');
            $response = ihttp_post($sentapi, $apidata);  */

        }


        if($data==1) {
            show_json(1,array('message'=>'评价成功','success'=>1));
        } else {
            show_json(-1,array('message'=>'评价失败','success'=>-1));
        }

    }

}