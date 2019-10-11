<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=sll_water_ticket */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_water_ticket.class.php');
$sll_water_ticket = new sll_water_ticketModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $sll_water_ticket->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'water_ticket_id' => $_GPC['water_ticket_id'],// 
    'water_ticket_name' => $_GPC['water_ticket_name'],// 水票名
    'water_ticket_type' => $_GPC['water_ticket_type'],// 1:电子,2:纸张
    'water_ticket_sn' => $_GPC['water_ticket_sn'],// 水序代号
    'water_ticket_ctime' => $_GPC['water_ticket_ctime'],// 生成时间
    'water_ticket_status' => $_GPC['water_ticket_status'],// 0:未分配，1：已分配未使用，2：已使用
    'water_ticket_endtime' => $_GPC['water_ticket_endtime'],// 结束时间
    'fans_id' => $_GPC['fans_id'],// 粉丝ID
    'goods_id' => $_GPC['goods_id'],// 商品id
    'user_id' => $_GPC['user_id'],// 水店ID
    'water_ticket_gettime' => $_GPC['water_ticket_gettime'],// 粉丝获取水票时间
    'water_ticket_usetime' => $_GPC['water_ticket_usetime'],// 使用时间
    'water_ticket_price' => $_GPC['water_ticket_price'],// 水票价格
    'water_ticket_create_note_pkCode' => $_GPC['water_ticket_create_note_pkCode'],// 生成记录的主键
    'orderid' => $_GPC['orderid'],// 

        );
        $sll_water_ticket->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('sll_water_ticket', array('op' => 'list')), 'success');


    }
    include $this->template('sll_water_ticket_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $sll_water_ticket->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('sll_water_ticket', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $sll_water_ticket->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('sll_water_ticket_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $sll_water_ticket->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $sll_water_ticket->delete($id);

    message('删除成功！', referer(), 'success');
}

