<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=shop_mj_activity */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/shop_mj_activity.class.php');
$shop_mj_activity = new shop_mj_activityModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $shop_mj_activity->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'shop_mj_id' => $_GPC['shop_mj_id'],// 满减活动表
    'shop_mj_name' => $_GPC['shop_mj_name'],// 名称
    'shop_mj_condition' => $_GPC['shop_mj_condition'],// 1:表示满减，2：表示阶梯满减
    'shop_mj_value' => $_GPC['shop_mj_value'],// 条件对应的值(json结构) type=1元，type=2 积分
    'shop_mj_level' => $_GPC['shop_mj_level'],// 用户级别要求
    'shop_mj_begintime' => $_GPC['shop_mj_begintime'],// 活动开始时间
    'shop_mj_endtime' => $_GPC['shop_mj_endtime'],// 活动结束时间
    'shop_mj_status' => $_GPC['shop_mj_status'],// 0:未启用(未审核)，1：启用(已审核)，2：关闭
    'shop_mj_remark' => $_GPC['shop_mj_remark'],// 
    'shop_mj_code' => $_GPC['shop_mj_code'],// 唯一id
    'shop_mj_activityType' => $_GPC['shop_mj_activityType'],// 1：满减，2：满赠，
    'shop_mj_description' => $_GPC['shop_mj_description'],// 介绍
    'shop_mj_ctime' => $_GPC['shop_mj_ctime'],// 创建时间
    'shop_mj_productType' => $_GPC['shop_mj_productType'],// 1:全部 2:部分

        );
        $shop_mj_activity->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('shop_mj_activity', array('op' => 'list')), 'success');


    }
    include $this->template('shop_mj_activity_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $shop_mj_activity->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('shop_mj_activity', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $shop_mj_activity->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('shop_mj_activity_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $shop_mj_activity->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $shop_mj_activity->delete($id);

    message('删除成功！', referer(), 'success');
}

