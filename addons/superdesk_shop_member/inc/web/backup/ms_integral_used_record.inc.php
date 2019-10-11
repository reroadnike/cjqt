<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=ms_integral_used_record */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/ms_integral_used_record.class.php');
$ms_integral_used_record = new ms_integral_used_recordModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $ms_integral_used_record->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'integral_used_record_id' => $_GPC['integral_used_record_id'],// 使用记录id
    'integral_used_record_data_id' => $_GPC['integral_used_record_data_id'],// 积分数据id
    'integral_used_record_orderid' => $_GPC['integral_used_record_orderid'],// 订单id
    'integral_used_record_number' => $_GPC['integral_used_record_number'],// 使用积分数量
    'integral_used_record_ctime' => $_GPC['integral_used_record_ctime'],// 使用积分时间
    'integral_used_record_goodsid' => $_GPC['integral_used_record_goodsid'],// 商品id
    'integral_used_record_mid' => $_GPC['integral_used_record_mid'],// 会员id
    'integral_used_record_type' => $_GPC['integral_used_record_type'],// 记录类型 1：使用   

        );
        $ms_integral_used_record->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('ms_integral_used_record', array('op' => 'list')), 'success');


    }
    include $this->template('ms_integral_used_record_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $ms_integral_used_record->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('ms_integral_used_record', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $ms_integral_used_record->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('ms_integral_used_record_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $ms_integral_used_record->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $ms_integral_used_record->delete($id);

    message('删除成功！', referer(), 'success');
}

