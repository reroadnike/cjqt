<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=sll_water_card_create_note */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_water_card_create_note.class.php');
$sll_water_card_create_note = new sll_water_card_create_noteModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $sll_water_card_create_note->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'water_card_create_note_id' => $_GPC['water_card_create_note_id'],// 水卡创建记录ID
    'water_card_create_note_time' => $_GPC['water_card_create_note_time'],// 水卡创建记录时间
    'water_card_create_note_status' => $_GPC['water_card_create_note_status'],// 水卡创建记录状态0失败1成功
    'water_card_create_note_fileName' => $_GPC['water_card_create_note_fileName'],// 文件名
    'water_card_create_note_downURL' => $_GPC['water_card_create_note_downURL'],// 下载地址
    'water_card_create_note_number' => $_GPC['water_card_create_note_number'],// 操作结果数量
    'water_card_create_note_pkCode' => $_GPC['water_card_create_note_pkCode'],// 自定义主键标识
    'user_id' => $_GPC['user_id'],// 市级用户ID
    'goods_id' => $_GPC['goods_id'],// 商品ID
    'expire_time' => $_GPC['expire_time'],// 到期时间
    'water_card_create_note_title' => $_GPC['water_card_create_note_title'],// 
    'assign_id' => $_GPC['assign_id'],// 

        );
        $sll_water_card_create_note->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('sll_water_card_create_note', array('op' => 'list')), 'success');


    }
    include $this->template('sll_water_card_create_note_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $sll_water_card_create_note->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('sll_water_card_create_note', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $sll_water_card_create_note->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('sll_water_card_create_note_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $sll_water_card_create_note->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $sll_water_card_create_note->delete($id);

    message('删除成功！', referer(), 'success');
}

