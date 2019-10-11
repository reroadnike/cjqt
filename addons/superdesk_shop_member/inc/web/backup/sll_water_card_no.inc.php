<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=sll_water_card_no */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_water_card_no.class.php');
$sll_water_card_no = new sll_water_card_noModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $sll_water_card_no->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'water_card_no_id' => $_GPC['water_card_no_id'],// 水卡编号ID
    'water_card_no_beg' => $_GPC['water_card_no_beg'],// 水卡编号开始值
    'water_card_no_end' => $_GPC['water_card_no_end'],// 水卡编号结束值
    'water_card_no_ctime' => $_GPC['water_card_no_ctime'],// 水卡编号创建时间
    'water_card_create_note_pkCode' => $_GPC['water_card_create_note_pkCode'],// 水卡创建记录主键编码

        );
        $sll_water_card_no->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('sll_water_card_no', array('op' => 'list')), 'success');


    }
    include $this->template('sll_water_card_no_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $sll_water_card_no->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('sll_water_card_no', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $sll_water_card_no->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('sll_water_card_no_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $sll_water_card_no->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $sll_water_card_no->delete($id);

    message('删除成功！', referer(), 'success');
}

