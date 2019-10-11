<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=sll_water_coin */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_water_coin.class.php');
$sll_water_coin = new sll_water_coinModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $sll_water_coin->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'water_coin_id' => $_GPC['water_coin_id'],// 水币ID
    'water_coin_code' => $_GPC['water_coin_code'],// 水币序列码
    'water_coin_no' => $_GPC['water_coin_no'],// 水币编码
    'water_coin_pkCode' => $_GPC['water_coin_pkCode'],// 自定义主键标识
    'water_card_create_note_pk_code' => $_GPC['water_card_create_note_pk_code'],// 水卡创建记录主键编码
    'water_card_pkCode' => $_GPC['water_card_pkCode'],// 水卡主键编码
    'water_coin_status' => $_GPC['water_coin_status'],// 水币状态0无效1有效
    'water_coin_ctime' => $_GPC['water_coin_ctime'],// 水币创建时间
    'goods_id' => $_GPC['goods_id'],// 商品ID
    'user_id' => $_GPC['user_id'],// 市级用户ID
    'openid' => $_GPC['openid'],// 
    'water_coin_usetime' => $_GPC['water_coin_usetime'],// 使用时间

        );
        $sll_water_coin->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('sll_water_coin', array('op' => 'list')), 'success');


    }
    include $this->template('sll_water_coin_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $sll_water_coin->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('sll_water_coin', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $sll_water_coin->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('sll_water_coin_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $sll_water_coin->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $sll_water_coin->delete($id);

    message('删除成功！', referer(), 'success');
}

