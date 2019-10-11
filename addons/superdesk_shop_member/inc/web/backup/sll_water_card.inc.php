<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=sll_water_card */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_water_card.class.php');
$sll_water_card = new sll_water_cardModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $sll_water_card->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'water_card_id' => $_GPC['water_card_id'],// 水卡ID
    'water_card_code' => $_GPC['water_card_code'],// 水卡序列码
    'water_card_no' => $_GPC['water_card_no'],// 水卡编号
    'water_card_url' => $_GPC['water_card_url'],// 水卡访问url
    'water_card_status' => $_GPC['water_card_status'],// 状态0失效1有效
    'water_card_ctime' => $_GPC['water_card_ctime'],// 创建时间
    'water_card_create_note_pk_code' => $_GPC['water_card_create_note_pk_code'],// 水卡创建记录主键编码
    'water_card_pkCode' => $_GPC['water_card_pkCode'],// 自定义主键标识
    'water_card_sellPrice' => $_GPC['water_card_sellPrice'],// 打印售价
    'goods_id' => $_GPC['goods_id'],// 商品ID
    'user_id' => $_GPC['user_id'],// 市级用户ID
    'number' => $_GPC['number'],// 生成币数量
    'proxy_price' => $_GPC['proxy_price'],// 代理价
    'base_price' => $_GPC['base_price'],// 成本价
    'sale_price' => $_GPC['sale_price'],// 零售价
    'activate_id' => $_GPC['activate_id'],// 激会者粉丝ID
    'activate_time' => $_GPC['activate_time'],// 激会时间
    'own_id' => $_GPC['own_id'],// 拥有者粉丝ID
    'get_time' => $_GPC['get_time'],// 拥有时间
    'expire_time' => $_GPC['expire_time'],// 到期时间
    'assign_id' => $_GPC['assign_id'],// 分配的水店ID
    'water_card_title' => $_GPC['water_card_title'],// 

        );
        $sll_water_card->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('sll_water_card', array('op' => 'list')), 'success');


    }
    include $this->template('sll_water_card_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $sll_water_card->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('sll_water_card', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $sll_water_card->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('sll_water_card_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $sll_water_card->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $sll_water_card->delete($id);

    message('删除成功！', referer(), 'success');
}

