<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

include_once(IA_ROOT . '/addons/superdesk_shopv2/service/member/MemberService.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order_examine.class.php');

/**
 * 企业月结审核类  zjh 20180420 添加
 */
class Examine_SuperdeskShopV2Model
{
    private $_memberService;
    private $_order_examineModel;

    public function __construct()
    {
        $this->_memberService      = new MemberService();
        $this->_order_examineModel = new order_examineModel();
    }

    /**
     * @param $order
     * 采购员下单,添加审核,推送给采购经理
     */
    public function addExamine($order)
    {
        global $_W;

        $member = m('member')->getMember($order['openid'], $order['core_user']);

        //获取需要推送的采购经理
        $manager_arr = $this->_memberService->getMemberListByCashRule($member['core_enterprise']);

        socket_log("TEST POINT: 采购经理信息列表: " . json_encode($manager_arr));

        $status = 0;// 0 未审核状态 1 已通过 2 不通过

        if (empty($manager_arr)) {
            $status = -1;// -1 没采购经理状态
        }


        // 插入订单审核记录
        $insert = array(
            'uniacid'    => $_W['uniacid'],
            'orderid'    => $order['id'],
            'openid'     => $order['openid'],
            'core_user'  => $order['core_user'],// TODO 标志 楼宇之窗 openid superdesk_shop_order_examine 已处理
            'realname'   => $member['realname'],
            'mobile'     => $member['mobile'],
            'price'      => $order['price'],
            'status'     => $status,
            'enterprise' => $member['core_enterprise'],
            'createtime' => time(),
        );

        $this->_order_examineModel->saveOrUpdateByColumn($insert, array('orderid' => $order['id']));

        // 查看 是否有 拆分单
        $child_order_list = m('order')->getChildOrder($order['id']);

        if (!empty($child_order_list)) {

            foreach ($child_order_list as $k => $v) {

                $child_insert = array(
                    'uniacid'         => $_W['uniacid'],
                    'orderid'         => $v['id'],
                    'openid'          => $order['openid'],
                    'core_user'       => $order['core_user'],// TODO 标志 楼宇之窗 openid superdesk_shop_order_examine 已处理
                    'realname'        => $member['realname'],
                    'mobile'          => $member['mobile'],
                    'price'          => $v['price'],
                    'status'          => $status,
                    'enterprise'      => $member['core_enterprise'],
                    'createtime'      => time(),
                    'parent_order_id' => $order['id'],
                );
                $this->_order_examineModel->saveOrUpdateByColumn($child_insert, array('orderid' => $v['id']));
            }
        }

        // 推送
        foreach ($manager_arr as $auditor) {

//            openid,core_user,core_enterprise,core_organization,mobile

            m('notice')->sendExamineCreateNotice(
                $auditor['openid'],
                $auditor['core_user'],
                $auditor['core_enterprise'],
                $auditor['core_organization'],
                $auditor['mobile'],
                $member['realname'], $order['ordersn'], $order['price'], $order['id']);
        }


    }

    /**
     * 更新审核信息
     * status : 1通过,2不通过
     */
    public function updateExamine(
        $orderid, /* 审核 目录订单 ID */
        $openid, /* 审核人信息 openid */
        $core_user, /* 审核人信息 core_user */
        $realname, /* 审核人信息 realname */
        $mobile, /* 审核人信息 mobile */
        $status /* 审核 结果 1通过 approve 2不通过 reject*/
    )
    {

        $params = [
            'manager_openid'    => $openid,
            'manager_core_user' => $core_user,
            'manager_realname'  => $realname,
            'manager_mobile'    => $mobile,
            'status'            => $status,
            'examinetime'       => time(), /* 审核时间 */
        ];

        //更新父订单
        pdo_update(
            'superdesk_shop_order_examine',
            $params,
            [
                'orderid' => $orderid
            ]
        );

        //更新子订单
        pdo_update(
            'superdesk_shop_order_examine',
            $params,
            [
                'parent_order_id' => $orderid
            ]
        );
    }

    /**
     * 根据订单id获取单条审核信息 目前只获取审核状态以及审核人名称
     *
     * @param $orderid
     *
     * @return bool
     */
    public function getExamineOne($orderid)
    {

        $examine = pdo_fetch(
            ' select status,manager_realname ' .
            ' from ' . tablename('superdesk_shop_order_examine') .// TODO 标志 楼宇之窗 openid superdesk_shop_order_examine 不处理
            ' where ' .
            '       orderid=:orderid ',
            array(
                ':orderid' => $orderid
            )
        );

        return $examine;
    }
}