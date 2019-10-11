<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Detail_SuperdeskShopV2Page extends MobilePage
{
    /**
     * 推送详情,显示订单信息,选择审核
     * http://localhost/superdesk/app/index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=examine&userMobile=13510109273&orderid=424
     */
    public function main()
    {
        global $_GPC, $_W;

        $orderid = $_GPC['orderid'];

        $order = pdo_fetch(
            ' SELECT o.createtime,o.ordersn,o.price,o.openid,o.core_user,o.status,o.paytype,o.refundstate,o.isparent,oe.status as oestatus,o.remark, ' .
            '        m.realname ' .
            ' FROM ' . tablename('superdesk_shop_order') . ' o ' .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' LEFT JOIN ' . tablename('superdesk_shop_order_examine') . ' oe on oe.orderid = o.id ' .// TODO 标志 楼宇之窗 openid superdesk_shop_order_examine 已处理
            ' LEFT JOIN ' . tablename('superdesk_shop_member') . ' m on m.core_user = o.core_user ' .
            ' WHERE o.id = :id and o.uniacid=:uniacid',
            array(
                ':id'      => $orderid,
                ':uniacid' => $_W['uniacid']
            )
        );

        if (empty($order)) {
            $this->message('无订单');
            exit();
        }

        $where            = ' og.orderid=' . $orderid . ' or og.parent_order_id=' . $orderid;
        $order_child_list = array();
        if ($order['isparent'] == 1) {
            $order_child_list = pdo_fetchall(
                ' SELECT id,ordersn ' .
                ' FROM ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 不处理
                ' WHERE parentid=:id and uniacid=:uniacid',
                array(
                    ':id'      => $orderid,
                    ':uniacid' => $_W['uniacid']
                )
            );
            $id_arr           = array_column($order_child_list, 'id');
            $where            = ' ( og.orderid in (' . implode(",", $id_arr) . ') or og.parent_order_id in (' . implode(",", $id_arr) . ')) ';
        }

        $goods = pdo_fetchall(
            ' select ' .
            '       og.goodsid,og.price,og.total,g.credit,og.optionid,og.optionname as optiontitle,og.orderid,og.parent_order_id, ' .
            '       g.title,g.thumb,g.status, g.cannotrefund,g.isverify,g.storeids' .
            ' from ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
            ' left join ' . tablename('superdesk_shop_goods') . ' g on g.id=og.goodsid ' .
            ' where og.uniacid=:uniacid AND ' . $where,
            array(
                ':uniacid' => $_W['uniacid']
            )
        );

        $member = m('member')->getMember($_W['openid'], $_W['core_user']);

        $manager_cate_id = pdo_fetchcolumn(
            ' select id ' .
            ' from' . tablename('superdesk_shop_member_cash_role') .
            ' where rolename = \'采购经理\'');

        $canCheck = true;

        if ($member['cash_role_id'] != $manager_cate_id) {
            $canCheck = false;
        }

        include $this->template();
        exit();
    }


    /**
     * 企业月结审核(通过|驳回)
     */
    public function examineChange()
    {
        global $_GPC, $_W;


        $orderid = $_GPC['orderid'];
        $status  = $_GPC['status']; // 1通过 approve 2不通过 reject

        try {

            $examine = pdo_fetch(
                ' select * ' .
                ' from ' . tablename('superdesk_shop_order_examine') .
                ' where ' .
                '       orderid=:orderid ',
                array(
                    ':orderid' => $orderid
                )
            );

            $order = pdo_fetch(
                ' select * ' .
                ' from ' . tablename('superdesk_shop_order') .
                ' where ' .
                '       id=:id ',
                array(
                    ':id' => $orderid
                )
            );

            if (empty($examine)) {
                show_json(0, '审核信息不存在');
            }

            if ($examine['status'] != 0) {
                show_json(0, '订单已被他人审核');
            }

            $buyer = m('member')->getMember($examine['openid'],$examine['core_user']);

            // 当前这个是 审核人 采购经理
            $member = m('member')->getMemberByCoreUserEnterprise($_W['core_user'], $buyer['core_enterprise']);

            if (!$member) {
                show_json(0, '您非该企业员工');
            }

            $manager_cate_id = pdo_fetchcolumn(
                ' select id ' .
                ' from' . tablename('superdesk_shop_member_cash_role') .
                ' where ' .
                '       rolename = "采购经理"'
            );

            if ($member['cash_role_id'] != $manager_cate_id) {
                show_json(0, '您非采购经理');
            }

//        $orderid,   /* 审核 目标订单 ID */
//        $openid,    /* 审核人信息 openid */
//        $core_user, /* 审核人信息 core_user */
//        $realname,  /* 审核人信息 realname */
//        $mobile,    /* 审核人信息 mobile */
//        $status     /* 审核 操作结果 1通过 approve 2不通过 reject*/
            m('examine')->updateExamine($orderid, $member['openid'], $member['core_user'], $member['realname'], $member['mobile'], $status);

        } catch (\Exception $e) {
            m('notice')->sendErrorMessage('审核出错',($_W['core_user'] ? $_W['core_user'] : 0));
        }




//        $openid,            /* 采购专员 信息  */
//        $core_user,         /* 采购专员 信息  */
//        $mobile,            /* 采购专员 信息  */
//        $orderid ,          /* 订单 信息  */
//        $price,             /* 订单 信息  */
//        $times,             /* 申请 时间   */
//        $username,          /* 审批人 信息  */
//        $type               /* 审批人 操作  */
        // 推送
        if ($status == 2) {

            // 审核不通过，推送给采购员
            m('notice')->sendExamineResultNotice(
                $examine['openid'],
                $examine['core_user'],
                $examine['mobile'],
                $orderid,
                $examine['price'],
                $examine['createtime'],
                $member['realname'],
                2,/* reject */
                $order['ordersn']
            );

        } else {

            // 审核通过,推送给采购员?以及其他采购经理?
            m('notice')->sendExamineResultNotice(
                $examine['openid'],
                $examine['core_user'],
                $examine['mobile'],
                $orderid,
                $examine['price'],
                $examine['createtime'],
                $member['realname'],
                1,/* approve*/
                $order['ordersn']
            );

            //加载 20190704 luoxt 订单信息加入队列
            try {
                load()->func('communication');
                $loginurl = $_W['config']['api_host'].'/api/base/message/collect';
                $post = [
                    "merchid"  => $order['merchid'],
                    "msg_type"  =>  "orders",
                    "msg_body"=>[
                        "ordersn" => $order['ordersn'],
                        "order_type"  =>  1,//1付款完成 2已发货 3:已完成,-1:已关闭
                        "time"  =>  time()
                    ]
                ];
                $response = ihttp_post($loginurl, $post);
            } catch (Exception $e) { }

        }

        show_json(1, '审核成功');
    }


    /**
     * 创建企业月结审核测试
     */
    public function examineCreate()
    {
        global $_W;

        $tm = $_W['shopset']['notice'];

        if (empty($tm)) {
            $tm = m('common')->getSysset('notice');
        }

        print_r($tm);
        die;

        m('examine')->addExamine(['id' => 895, 'openid' => 'oX8KYwkxwNW6qzHF4cF-tGxYTcPg']);
        return 1;

        $url = mobileUrl('examine', ['userMobile' => '135', 'orderid' => 123]);
        $url = substr_replace($url, $_W['siteroot'] . 'app/', 0, 2);
        print_r($url);
        die;
        m('notice')->sendMemberLogMessage(1);
        return 1;
        m('examine')->addExamine(['id' => 424, 'openid' => 'oX8KYwutiI5NEJ20LIF8zNINwSng']);
    }
}


