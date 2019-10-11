<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=sll_buckets */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_buckets.class.php');
$sll_buckets = new sll_bucketsModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $sll_buckets->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'buckets_id' => $_GPC['buckets_id'],// 水桶管理记录ID
    'fans_id' => $_GPC['fans_id'],// 微信用户ID
    'get_time' => $_GPC['get_time'],// 领桶(开始)时间
    'user_id' => $_GPC['user_id'],// 水店ID
    'number' => $_GPC['number'],// 手机
    'status' => $_GPC['status'],// 0:已领取，1：已归还 , 2：未领取
    'goods_id' => $_GPC['goods_id'],// 品牌名称
    'order_id' => $_GPC['order_id'],// 
    'return_time' => $_GPC['return_time'],// 还桶(结束)时间
    'goods_name' => $_GPC['goods_name'],// 品牌名称
    'user_name' => $_GPC['user_name'],// 水桶品牌
    'total_deposit' => $_GPC['total_deposit'],// 总价
    'buckets_number' => $_GPC['buckets_number'],// 桶数量
    'single_deposit' => $_GPC['single_deposit'],// 单价
    'water_shop' => $_GPC['water_shop'],//  水店
    'company' => $_GPC['company'],// 公司
    'watershop_deposit' => $_GPC['watershop_deposit'],// 水店押金
    'surplus_deposit' => $_GPC['surplus_deposit'],// 剩余押金
    'empty_bucket' => $_GPC['empty_bucket'],// 空桶总数量
    'return_bucket' => $_GPC['return_bucket'],// 退桶数量

        );
        $sll_buckets->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('sll_buckets', array('op' => 'list')), 'success');


    }
    include $this->template('sll_buckets_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $sll_buckets->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('sll_buckets', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $sll_buckets->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('sll_buckets_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $sll_buckets->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $sll_buckets->delete($id);

    message('删除成功！', referer(), 'success');
}

